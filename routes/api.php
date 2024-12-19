<?php

use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\GeneralApiController;
use App\Http\Controllers\Api\OutStandingInvoiceController as ApiOutStandingInvoiceController;
use App\Models\Client;
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
Route::get('/services/{service}/latest-invoice', [ApiOutStandingInvoiceController::class, 'showLatestInvoice']);
// Resource routes for Outstanding Invoices
Route::resource('outstanding-invoices', ApiOutStandingInvoiceController::class);

// Resource routes for Clients
Route::get('clients/{client}', [ClientApiController::class, 'show']);
Route::get('client/$id/chart-data', [ClientApiController::class, 'getChartData']);
// }); // Uncomment for Sanctum authentication middleware

// transaction trend for client income vs expenses
Route::get('transactions/{client_servie_id', [GeneralApiController::class, 'showTransactionsByClientService']);
