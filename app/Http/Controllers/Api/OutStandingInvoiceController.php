<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOutStandingInvoiceRequest;
use App\Http\Requests\UpdateOutStandingInvoiceRequest;
use App\Models\ClientService;
use App\Models\OutstandingInvoice;
use App\Services\OutstandingInvoiceManager;
use Illuminate\Support\Facades\Log;

class OutStandingInvoiceController extends Controller
{
    /**
     * Store a newly created outstanding invoice.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOutStandingInvoiceRequest $request)
    {
        // Log incoming request data
        logger('Request Data:', $request->all());

        try {
            // Create a new outstanding invoice
            $invoice = OutstandingInvoice::create($request->validated());

            // Log success
            logger('Invoice created successfully:', ['invoice_id' => $invoice->id]);

            return response()->json([
                'success' => true, // Indicating success for AJAX
                'message' => 'Invoice created successfully!',
                'data' => $invoice,
            ], 201); // HTTP 201 Created
        } catch (\Exception $e) {
            // Log error details
            logger()->error('Invoice Creation Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false, // Indicating error for AJAX
                'message' => $e->getTrace(),
                'error' => $e->getMessage(),
            ], 500); // HTTP 500 Internal Server Error
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

    public function showLatestInvoice($service_id)
    {
        try {
            //code...

            $clientService = ClientService::find($service_id);
            // dd($clientService->duration_type);
            // Fetch the due date and invoice amounts
            $dueDate = OutstandingInvoiceManager::calculateDueDate($clientService);
            $payableAmount = OutstandingInvoiceManager::calculateInvoiceAmount($clientService); // Total amount for this invoice
            // Retrieve the previous invoice
            $previousInvoice = OutstandingInvoiceManager::getPreviousOutStandingInvoice($clientService);
            // Extract the previous remaining amount if a previous invoice exists
            $prevRemainingAmount = $previousInvoice
                ? OutstandingInvoiceManager::calculateRemainingAmount($previousInvoice) // Calculate remaining amount
                : 0; // Default to 0 if no previous invoice

            // Optionally, combine all details into one "latest invoice amount" field
            $latestInvoiceAmount = $prevRemainingAmount + $payableAmount;

            return response()->json([
                'success' => true,
                'latestInvoiceAmount' => $latestInvoiceAmount, // Return the full details if needed
                'dueDate' => $dueDate,
                'payableAmount' => $payableAmount,
                'prevRemainingAmount' => $prevRemainingAmount, // Include for clarity
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'Error' => $th,
            ]);
        }
    }
}
