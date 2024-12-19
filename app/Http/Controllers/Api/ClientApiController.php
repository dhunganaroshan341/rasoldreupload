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

    public function getChartData(Client $client)
    {
        // Check if client has services
        if ($client->clientServices->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No client services found.',
            ], 404);
        }

        // Fetch only necessary fields from the database for efficiency
        $clientServices = $client->clientServices()->select('name', 'amount')->get();

        // Prepare data for the chart
        $chartData = [
            'labels' => $clientServices->pluck('name')->toArray(),
            'data' => $clientServices->pluck('amount')->toArray(),
        ];

        return response()->json([
            'success' => true,
            'data' => $chartData,
        ]);
    }
}
