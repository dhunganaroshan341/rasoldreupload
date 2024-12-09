<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOutStandingInvoiceRequest;
use App\Http\Requests\UpdateOutStandingInvoiceRequest;
use App\Models\ClientService;
use App\Models\OutstandingInvoice;
use App\Services\ClientServiceManager;
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
        // Log incoming request data for debugging
        logger('Request Data:', $request->all());

        try {
            // Validate the incoming request data
            $validatedData = $request->validated();

            // Assign the total_amount as remaining_amount initially
            $validatedData['remaining_amount'] = $validatedData['total_amount'];
            $clientService = ClientServiceManager::getClientServiceById($validatedData['client_service_id']);
            // Get the previous remaining amount for this client service
            $previousRemaining = OutstandingInvoiceManager::getPrevRemainingAmount($clientService);

            // Calculate the remaining_amount amount
            $validatedData['remaining_amount'] = $validatedData['total_amount'] + $previousRemaining;

            // Add the due_date to the validated data
            // $validatedData['due_date'] = $validatedData['due_date'];

            // Check if the invoice already exists based on client_service_id
            $invoice = OutstandingInvoice::updateOrCreate(
                [
                    'client_service_id' => $validatedData['client_service_id'], // Unique identifier
                ],
                $validatedData // This is the data to update if the record exists
            );

            // Log the creation or update of the invoice
            logger('Invoice created/updated successfully:', ['invoice_id' => $invoice->id]);

            // Auto-update other columns (like paid_amount, remaining_amount, etc.)
            $autoUpdateRest = OutstandingInvoiceManager::autoUpdateOtherColumns($invoice);

            // Log success of auto-update
            logger('Invoice updated with other columns:', ['invoice_id' => $invoice->id]);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Invoice created and updated successfully.',
                'invoice' => $invoice,  // Optionally return the invoice object
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            logger('Error creating/updating invoice:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create or update invoice.',
                'error' => $e->getMessage(),  // Include error details for debugging
            ], 500);
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
            //
            $autoUpdateRest = OutstandingInvoiceManager::autoUpdateOtherColumns($outstandingInvoice);

            return response()->json([
                'message' => 'Invoice updated successfully! '.$autoUpdateRest,
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
    public function show($id)
    {
        $invoice = OutstandingInvoice::with('clientService')->find($id);

        if ($invoice) {
            return response()->json([
                'success' => true,
                'data' => $invoice,
                'message' => 'Invoice retrieved successfully',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found',
            ], 404);
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
