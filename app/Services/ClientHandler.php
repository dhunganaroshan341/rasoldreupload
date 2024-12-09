<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\OurServices;
use Illuminate\Http\Request;

class ClientHandler
{
    // Handle attaching or creating ClientService
    public function attachOrCreateClientService(Client $client, $serviceId)
    {
        // Find the service
        $service = OurServices::find($serviceId);
        if ($service) {
            // Create the ClientService name based on client name and service name
            $clientServiceName = $client->name.' - '.$service->name;

            // Check if a ClientService with this name already exists
            return ClientService::firstOrCreate(
                [
                    'client_id' => $client->id,
                    'service_id' => $service->id,
                ],
                [
                    'name' => $clientServiceName,  // Set the dynamic name
                    'amount' => $service->price,   // You can set any additional attributes
                    'remaining_amount' => $service->price, // You can set default values for remaining amount
                    'outsourced_amount' => 0,       // Default value for outsourced amount
                ]
            );
        }

        return null;  // Return null if service not found
    }

    // Get service IDs (including new services)
    public function getServiceIds(Request $request)
    {
        $serviceIds = $request->input('services', []);

        if ($request->filled('new_service')) {
            $newServiceName = $request->input('new_service');

            // Check if the service already exists or create a new one
            $existingService = OurServices::firstOrCreate(['name' => $newServiceName]);

            // Add the new service ID to the list
            $serviceIds[] = $existingService->id;
        }

        return $serviceIds;
    }

    // Attach services to the client
    public function attachServicesToClient(Client $client, array $serviceIds)
    {
        foreach ($serviceIds as $serviceId) {
            $service = OurServices::find($serviceId);

            if ($service) {
                $client->services()->attach($serviceId, [
                    'amount' => $service->price,
                    'remaining_amount' => $service->price,
                    'outsourced_amount' => 0,
                ]);
            }
        }
    }

    // Validate client data
    public function validateClientData(Request $request, ?Client $client = null)
    {
        // Merge the default status value if not provided in the request
        $request->merge([
            'status' => $request->input('status', 'active'),
        ]);

        return $request->validate([
            'name' => 'required',
            'client_type' => 'nullable',
            'address' => 'required',
            'email' => 'required|email|unique:clients,email,'.($client ? $client->id : ''),
            'phone' => 'required|unique:clients,phone,'.($client ? $client->id : ''),
            'pan_no' => 'nullable|string|unique:clients,pan_no,'.($client ? $client->id : ''),
            'services' => 'nullable|array',
            'services.*' => 'exists:our_services,id',
            'new_service' => 'nullable|string|max:255',
            'hosting_service' => 'nullable|string',
            'email_service' => 'nullable|string',
            'status' => 'nullable|string', // `nullable` is enough here
            'billing_period_frequency' => 'nullable|in:one-time annually,semi-annually,quarterly,monthly', // Use `in` for validation
        ]);
    }
}
