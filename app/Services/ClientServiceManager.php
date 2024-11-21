<?php

namespace App\Services;

use App\Models\Client;

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
}
