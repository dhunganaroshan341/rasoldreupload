<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\OurServices;
use App\Services\ClientHandler;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $ClientHandler;

    public function __construct(ClientHandler $ClientHandler)
    {
        $this->ClientHandler = $ClientHandler;
    }

    public function index()
    {
        try {
            $clientsWithServices = Client::with('clientServices')->get();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to retrieve clients.');
        }

        return view('dashboard.clients.index', compact('clientsWithServices'));
    }

    public function create()
    {
        $clients = Client::all();
        $existingClientTypes = Client::select('client_type')->distinct()->get();
        $existingServiceTypes = OurServices::all();

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
        $validated = $this->ClientHandler->validateClientData($request, $client);

        // Update client details
        $client->update($validated);

        // Handle services (existing or new)
        $serviceIds = $this->ClientHandler->getServiceIds($request);

        // Clear existing services before reattaching
        $client->services()->detach();

        // Attach services to the client and create ClientServices
        foreach ($serviceIds as $serviceId) {
            $this->ClientHandler->attachOrCreateClientService($client, $serviceId);
        }

        return redirect()->route('clients.index')->with('success', 'Client '.$client->name.' successfully.');
    }

    public function store(Request $request)
    {
        // Validate client data
        $validatedClient = $this->ClientHandler->validateClientData($request);

        // Create the client first
        $client = Client::create($validatedClient);

        // Handle services (existing or new)
        $serviceIds = $this->ClientHandler->getServiceIds($request);

        // Attach services to the client and create ClientServices
        foreach ($serviceIds as $serviceId) {
            $this->ClientHandler->attachOrCreateClientService($client, $serviceId);
        }

        return redirect()->route('clients.index')->with('success', 'Client '.$client->name.' created successfully.');
    }

    public function show(Client $client)
    {
        $clientServices = $client->clientServices()->with('service')->get();

        return view('dashboard.clients.showClientInformation', [
            'client' => $client,
            'clientServices' => $clientServices,
        ]);
    }

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

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client '.$client->name.' deleted successfully.');
    }
}
