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

            return response()->json([
                'message' => 'Client services for client '.$client->id.' fetched successfully!',
                'data' => $clientServices,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching services for client '.$client->id,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
