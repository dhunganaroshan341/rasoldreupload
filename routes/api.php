<?php

use App\Models\Client;
use App\Models\ClientService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Register API routes for your application. These routes are assigned
| to the "api" middleware group and are protected with Sanctum auth.
|
*/

// Protected routes - Only accessible by authenticated users
// Route::middleware('auth:sanctum')->group(function () {

// Fetch services for a specific client with their latest invoices
Route::get('/clients/{client}/services', function (Client $client) {

    // Fetch all services for the client
    $services = $client->clientServices;

    if ($services->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No services found for this client',
        ]);
    }

    $response = $services->map(function ($service) {
        return [
            'id' => $service->id,
            'name' => $service->name,
        ];
    });

    return response()->json([
        'success' => true,
        'client' => $client,
        'services' => $response,
    ]);
});

// Fetch the latest invoice for a specific service
Route::get('/services/{service}/latest-invoice', function (ClientService $service) {
    // Fetch the latest invoice for the given service
    $latestInvoice = $service->outStandingInvoices()
        ->orderBy('created_at', 'desc')
        ->first(['id', 'bill_number', 'total_amount', 'remaining_amount', 'due_date', 'created_at']);

    return response()->json([
        'success' => true,
        'invoice' => $latestInvoice,
    ]);
});

// });
