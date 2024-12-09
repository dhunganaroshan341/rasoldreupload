<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\OurServices;
use App\Services\ClientHandler;
use App\Services\ClientServiceManager;
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
        $allServices = OurServices::all();

        return view('dashboard.clients.create', [
            'existingClientTypes' => $existingClientTypes,
            'existingServiceTypes' => $existingServiceTypes,
            'allServices' => $allServices,
        ]);
    }

    public function update(Request $request, Client $client, ClientServiceManager $clientServiceManager)
    {
        // Validate incoming client data
        $validated = $this->ClientHandler->validateClientData($request, $client);

        // Update the client details
        $client->update($validated);

        // Extract service IDs from the request
        $serviceIds = $this->ClientHandler->getServiceIds($request);

        // Update services for the client
        try {
            $clientServiceManager->updateClientServices($client, $serviceIds);
        } catch (\Exception $e) {
            // Handle validation exceptions for services (e.g., income exists)
            return redirect()->back()->with('error', $e->getMessage());
        }

        // Redirect back to the client list with a success message
        return redirect()->route('clients.index')->with('success', 'Client '.$client->name.' updated successfully.');
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
