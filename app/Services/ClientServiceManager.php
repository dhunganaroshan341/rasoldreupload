<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\OurServices;
use Illuminate\Support\Facades\DB;

class ClientServiceManager
{
    /**
     * Get clients with their client services and the latest outstanding invoice
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getClientsWithInvoices()
    {
        $clients = Client::with(['clientServices', 'clientServices.outStandingInvoices'])->get();

        // Attach the latest invoice to each client service
        $clients->each(function ($client) {
            $client->clientServices->each(function ($clientService) {
                $clientService->latestInvoice = $clientService->outStandingInvoices
                    ->sortByDesc('created_at')
                    ->first();
            });
        });

        return $clients;
    }

    /**
     * Update client services by adding new ones and validating removal of existing ones.
     *
     * @param  Client  $client  The client whose services are being updated.
     * @param  array  $serviceIds  The list of service IDs to associate with the client.
     *
     * @throws \Exception If a service cannot be removed due to associated income.
     */
    public function updateClientServices(Client $client, array $serviceIds)
    {
        DB::transaction(function () use ($client, $serviceIds) {
            // Retrieve current service associations for the client
            $existingServices = $client->services()->pluck('service_id')->toArray();

            // Determine new services to add
            $servicesToAdd = array_diff($serviceIds, $existingServices);

            // Determine services to remove
            $servicesToRemove = array_diff($existingServices, $serviceIds);

            // Validate and detach removable services
            $this->validateAndDetachServices($client, $servicesToRemove);

            // Add new services to the client
            foreach ($servicesToAdd as $serviceId) {
                $this->attachClientService($client, $serviceId);
            }
        });
    }

    /**
     * Attach a new service to a client, creating a ClientService entry if necessary.
     *
     * @param  Client  $client  The client to whom the service is being attached.
     * @param  int  $serviceId  The ID of the service to attach.
     */
    private function attachClientService(Client $client, $serviceId)
    {
        $service = OurServices::find($serviceId);

        if ($service) {
            // Ensure the service is associated with the client, or create it
            ClientService::firstOrCreate(
                [
                    'client_id' => $client->id,
                    'service_id' => $service->id,
                ],
                [
                    'name' => $client->name.' - '.$service->name,  // Dynamic name for ClientService
                    'amount' => $service->price,                      // Default price from the service
                    'remaining_amount' => $service->price,            // Set initial remaining amount
                    'outsourced_amount' => 0,                         // Default outsourced amount
                ]
            );
        }
    }

    /**
     * Validate and detach services from a client, ensuring no related data is affected.
     *
     * @param  Client  $client  The client whose services are being validated for removal.
     * @param  array  $servicesToRemove  The IDs of services to remove.
     *
     * @throws \Exception If a service cannot be removed due to associated income.
     */
    private function validateAndDetachServices(Client $client, array $servicesToRemove)
    {
        foreach ($servicesToRemove as $serviceId) {
            $clientService = ClientService::where('client_id', $client->id)
                ->where('service_id', $serviceId)
                ->first();

            // Check if the ClientService exists and has associated income
            if ($clientService && $clientService->income()->exists()) {
                throw new \Exception(
                    "Cannot remove service '{$clientService->name}' because it has associated income."
                );
            }

            // Detach the service if it passes all validation checks
            $client->services()->detach($serviceId);
        }
    }

    public static function deactivateClientService(ClientService $clientService)
    {
        try {
            $deactivated = $clientService->status = 'inactive';

            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
        }
    }

    public static function getClientServiceById($client_service_id)
    {
        $clientService = ClientService::find($client_service_id);

        return $clientService;
    }
}
