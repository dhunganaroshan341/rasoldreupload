<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\OurServices;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $existingServiceTypes;

    public function index()
    {
        $clients = Client::all();

        return view('dashboard.clients.index', compact('clients'));
    }

    public function create()
    {
        // Retrieve distinct client types (if needed)
        $existingClientTypes = Client::select('client_type')->distinct()->get();

        // Retrieve all existing service types
        $existingServiceTypes = OurServices::all();
        $this->existingServiceTypes = $existingServiceTypes;

        // Return the view with the necessary data
        return view('dashboard.clients.old.old_create', [
            'existingClientTypes' => $existingClientTypes,
            'existingServiceTypes' => $existingServiceTypes,
        ]);
    }

    public function store(Request $request)
    {
        // Validate client data
        $validatedClient = $request->validate([
            'name' => 'required',
            'client_type' => 'nullable',
            'address' => 'required',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|unique:clients,phone',
            'pan_no' => 'nullable|string|unique:clients,pan_no',
            'services' => 'nullable|array',
            'services.*' => 'exists:our_services,id',
        ]);

        // Create the client first
        $client = Client::create($validatedClient);

        $serviceIds = [];

        // Handle new service creation if `new_service` is provided
        if ($request->filled('new_service')) {
            // Validate new service
            $request->validate([
                'new_service' => 'nullable|string|max:255',
            ]);

            $newServiceName = $request->input('new_service');
            $existingService = OurServices::where('name', $newServiceName)->first();

            if (! $existingService) {
                // Create new service if it does not exist
                $existingService = OurServices::create(['name' => $newServiceName]);
            }

            // Add the new service ID to the list
            $serviceIds[] = $existingService->id;
        }

        // Add existing services to the list
        if ($request->filled('services')) {
            $serviceIds = array_merge($serviceIds, $request->input('services'));
        }

        // Attach services to the client
        $client->services()->sync($serviceIds);

        return redirect()->route('clients.index')->with('success', 'Client '.$client->name.' created successfully.');
    }

    // public function store(Request $request)
    // {
    //     \Log::info('Request Data:', $request->all());
    //     dd($request->all());
    // }

    public function edit($clientId)
    {
        // Find the client by ID
        $client = Client::findOrFail($clientId);

        // Get the services associated with the client
        $clientServices = $client->clientServices()->pluck('service_id')->toArray();

        // Get all existing services
        $existingServiceTypes = OurServices::all();

        return view('dashboard.clients.edit', [
            'client' => $client,
            'existingServiceTypes' => $existingServiceTypes,
            'clientServices' => $clientServices,
        ]);
    }

    public function update(Request $request, Client $client)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required',
            'client_type' => 'nullable',
            'address' => 'required',
            'pan_no' => 'nullable|string|unique:clients,pan_no,'.$client->id,
            'email' => 'required|email|unique:clients,email,'.$client->id,
            'phone' => 'required',
            'services' => 'nullable|array',
            'services.*' => 'exists:our_services,id',
            'new_service' => 'nullable|string|max:255',
        ]);

        // Update client details
        $client->update($validated);

        $serviceIds = [];

        // Handle new service creation
        if ($request->filled('new_service')) {
            $newServiceName = $request->input('new_service');
            $existingService = OurServices::where('name', $newServiceName)->first();

            if (! $existingService) {
                // Create new service if it does not exist
                $existingService = OurServices::create(['name' => $newServiceName]);
            }

            // Add the new service to the service IDs list
            $serviceIds[] = $existingService->id;
        }

        // Add existing services to the service IDs list
        if ($request->filled('services')) {
            $serviceIds = array_merge($serviceIds, $request->input('services'));
        }

        // Ensure unique service IDs
        $serviceIds = array_unique($serviceIds);

        // Update services for the client
        $client->services()->sync($serviceIds);

        return redirect()->route('clients.index')->with('success', 'Client '.$client->name.' updated successfully.');
    }

    public function show(Client $client)
    {
        $clientServices = ClientService::where('client_id', $client->id)->with('service')->get();

        return view('dashboard.clients.card', [
            'client' => $client,
            'clientServices' => $clientServices,
        ]);
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client'.$client->name.' deleted successfully.');
    }
}
