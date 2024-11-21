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
        try {
            $clientsWithServices = Client::with('clientServices')->get();
        } catch (\Exception $e) {
            // Handle the exception, log it, or show an error message
            return redirect()->back()->withErrors('Failed to retrieve clients.');
        }

        return view('dashboard.clients.index', compact('clientsWithServices'));
    }

    public function create()
    {
        $clients = Client::all();
        // Retrieve distinct client types (if needed)
        $existingClientTypes = Client::select('client_type')->distinct()->get();

        // Retrieve all existing service types
        $existingServiceTypes = OurServices::all();
        $this->existingServiceTypes = $existingServiceTypes;

        // Return the view with the necessary data
        return view('dashboard.clients.create', [
            'existingClientTypes' => $existingClientTypes,
            'existingServiceTypes' => $existingServiceTypes,
        ]);
    }

    public function update(Request $request, Client $client)
    {
        // removed  detached so that if income has already been created
        // error won't be thrown in here.
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
            'hosting_service' => 'nullable|string',
            'email_service' => 'nullable|string',
        ]);

        // Update client details
        $client->update($validated);

        // Collect service IDs from the request
        $serviceIds = $request->input('services', []);

        // Handle new service creation if a 'new_service' is provided
        if ($request->filled('new_service')) {
            $newServiceName = $request->input('new_service');
            // Check if the service already exists, or create it
            $existingService = OurServices::firstOrCreate(['name' => $newServiceName]);
            // Add the new service ID to the list of services to attach
            $serviceIds[] = $existingService->id;
        }

        // Add only new services (not already associated with the client)
        $currentServiceIds = $client->services->pluck('id')->toArray();

        // Find the new services that are not already attached
        $newServiceIds = array_diff($serviceIds, $currentServiceIds);

        // Attach only the new services
        foreach ($newServiceIds as $serviceId) {
            $service = OurServices::find($serviceId);

            if ($service) {
                // Attach the new service to the client with its price and other details
                $client->services()->attach($serviceId, [
                    'amount' => $service->price,  // Store the service price as the amount in client_services
                    'remaining_amount' => $service->price,  // Default remaining amount
                    'outsourced_amount' => 0,  // Default outsourced amount
                ]);
            }
        }

        return redirect()->route('clients.index')->with('success', 'Client '.$client->name.' updated successfully.');
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

        return view('dashboard.clients.create', [
            'client' => $client,
            'existingServiceTypes' => $existingServiceTypes,
            'clientServices' => $clientServices,
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
            'new_service' => 'nullable|string|max:255', // Allow new service name
            'hosting_service' => 'nullable|string',
            'email_service' => 'nullable|string',
        ]);

        // Create the client first
        $client = Client::create($validatedClient);

        // Collect service IDs
        $serviceIds = $request->input('services', []);

        // Handle new service creation if `new_service` is provided
        if ($request->filled('new_service')) {
            // Validate new service
            $newServiceName = $request->input('new_service');

            // Check if the service already exists
            $existingService = OurServices::firstOrCreate(['name' => $newServiceName]);

            // Add the new service ID to the list
            $serviceIds[] = $existingService->id;
        }

        // Attach services to the client with their prices
        foreach ($serviceIds as $serviceId) {
            // Find the service and get its price
            $service = OurServices::find($serviceId);

            if ($service) {
                // Attach the service to the client with the amount (price) and other fields
                $client->services()->attach($serviceId, [
                    'amount' => $service->price,  // Store the service price as the amount in client_services
                    // You can add additional fields here if needed
                    // remaining amount for the client service for default
                    'remaining_amount' => $service->price,
                    // outsourced amount zero setting for the initial
                    'outsourced_amount' => 0,
                ]);
            }
        }

        return redirect()->route('clients.index')->with('success', 'Client '.$client->name.' created successfully.');
    }

    public function show(Client $client)
    {
        $clientServices = ClientService::where('client_id', $client->id)->with('service')->get();

        return view('dashboard.clients.showClientInformation', [
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
