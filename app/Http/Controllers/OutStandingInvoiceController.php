<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOutStandingInvoiceRequest;
use App\Http\Requests\UpdateOutStandingInvoiceRequest;
use App\Models\OutStandingInvoice;
use App\Services\ClientServiceManager;
use Illuminate\Http\JsonResponse;

class OutStandingInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ClientServiceManager $clientServiceManager)
    {
        $clients = $clientServiceManager->getClientsWithInvoices();

        // Collect all invoices from the client services
        $invoices = $clients->flatMap(function ($client) {
            return $client->clientServices->flatMap(function ($service) {
                return $service->outStandingInvoices;
            });
        });

        // Pass the data to the view
        return view('dashboard.outstandingInvoices.index', compact('clients', 'invoices'));
    }

    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOutStandingInvoiceRequest $request): JsonResponse
    {
        // Validate the request and store the invoice
        $validatedData = $request->validated();

        $invoice = OutStandingInvoice::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice created successfully',
            'data' => $invoice,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(OutStandingInvoice $outStandingInvoice): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $outStandingInvoice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutStandingInvoiceRequest $request, OutStandingInvoice $outStandingInvoice): JsonResponse
    {
        // Validate the request and update the invoice
        $validatedData = $request->validated();

        $outStandingInvoice->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice updated successfully',
            'data' => $outStandingInvoice,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutStandingInvoice $outStandingInvoice): JsonResponse
    {
        // Delete the invoice and return success response
        $outStandingInvoice->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice deleted successfully',
        ]);
    }
}
