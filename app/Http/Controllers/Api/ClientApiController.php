<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientApiController extends Controller
{
    public function index() {}

    public function show(Client $client)
    {
        try {
            // Eager load the clientServices relationship directly on the client instance
            $clientServices = $client->clientServices;

            // Log the clientServices for debugging
            // \Log::info($clientServices);

            return response()->json([
                'success' => true,
                'client' => $client,
                'clientServices' => $clientServices, // Return the services
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            // \Log::error('Error fetching services for client '.$client->id.': '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching services for client '.$client->id,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
