<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOutStandingInvoiceRequest;
use App\Http\Requests\UpdateOutStandingInvoiceRequest;
use App\Models\ClientService;
use App\Models\OutstandingInvoice;
use App\Services\OutstandingInvoiceManager;

class OutStandingInvoiceController extends Controller
{
    /**
     * Store a newly created outstanding invoice.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOutStandingInvoiceRequest $request)
    {
        // Create a new outstanding invoice
        try {
            $invoice = OutstandingInvoice::create($request->validated());

            return response()->json([
                'message' => 'Invoice created successfully!',
                'data' => $invoice,
            ], 201); // 201 is HTTP status code for "created"
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating invoice',
                'error' => $e->getMessage(),
            ], 500); // 500 for server error
        }
    }

    /**
     * Update the specified outstanding invoice.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateOutStandingInvoiceRequest $request, OutstandingInvoice $outstandingInvoice)
    {
        // Update the existing outstanding invoice
        try {
            // You could use the update method directly
            $outstandingInvoice->update($request->validated());

            return response()->json([
                'message' => 'Invoice updated successfully!',
                'data' => $outstandingInvoice,
            ], 200); // 200 is HTTP status code for "OK"
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating invoice',
                'error' => $e->getMessage(),
            ], 500); // 500 for server error
        }
    }

    /**
     * Get all outstanding invoices.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $invoices = OutstandingInvoice::all();

            return response()->json([
                'message' => 'Invoices fetched successfully!',
                'data' => $invoices,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching invoices',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the details of a single outstanding invoice.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(OutstandingInvoice $outstandingInvoice)
    {
        try {
            return response()->json([
                'message' => 'Invoice fetched successfully!',
                'data' => $outstandingInvoice,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showLatestInvoice(ClientService $clientService)
    {
        // Fetch the latest invoice for the given service
        $dueDate = OutstandingInvoiceManager::calculateDueDate($clientService);
        $invoice = OutstandingInvoiceManager::calculateInvoiceAmount($clientService);
        $payableAmount = $invoice['all_total'];
        $latestInvoice = $clientService->outStandingInvoices()
            ->orderBy('created_at', 'desc')
            ->first(['id', 'bill_number', 'total_amount', 'remaining_amount', 'due_date', 'created_at']);

        return response()->json([
            'success' => true,
            'invoice' => $latestInvoice,
            'dueDate' => $dueDate,
            'payableAmount' => $payableAmount,
        ]);

    }
}
