<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\OutstandingInvoice;
use App\Services\ClientServiceManager;
use Illuminate\Http\Request;

class OutStandingInvoiceController extends Controller
{
    // Fetch all outstanding invoices (API endpoint)
    public function getIndexJsonResponse()
    {
        $clients = ClientServiceManager::getClientsWithInvoices();

        return response()->json([
            'success' => true,
            'data' => $clients,
            'message' => 'All outstanding invoices retrieved successfully',
        ], 200);
    }

    // Fetch a single invoice by ID (API endpoint)
    public function show($id)
    {
        $invoice = OutstandingInvoice::find($id);

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

    // Store a new invoice (API endpoint)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_service_id' => 'required|integer',
            'total_amount' => 'required|numeric',
            'prev_remaining_amount' => 'nullable|numeric',
            'all_total' => 'required|numeric',
            'paid_amount' => 'nullable|numeric',
            'remaining_amount' => 'nullable|numeric',
            'discount_amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'due_date' => 'required|date',
            'last_paid' => 'nullable|date',
            'remarks' => 'nullable|string',
            'bill_number' => 'required|string',
            'status' => 'required|string',
            'all_total_paid' => 'nullable|numeric',
        ]);

        $invoice = OutstandingInvoice::create($validatedData);

        return response()->json([
            'success' => true,
            'data' => $invoice,
            'message' => 'Invoice created successfully',
        ], 201);
    }

    // Update an invoice (API endpoint)
    public function update(Request $request, $id)
    {
        $invoice = OutstandingInvoice::find($id);

        if (! $invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found',
            ], 404);
        }

        $validatedData = $request->validate([
            'client_service_id' => 'nullable|integer',
            'total_amount' => 'nullable|numeric',
            'prev_remaining_amount' => 'nullable|numeric',
            'all_total' => 'nullable|numeric',
            'paid_amount' => 'nullable|numeric',
            'remaining_amount' => 'nullable|numeric',
            'discount_amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'due_date' => 'nullable|date',
            'last_paid' => 'nullable|date',
            'remarks' => 'nullable|string',
            'bill_number' => 'nullable|string',
            'status' => 'nullable|string',
            'all_total_paid' => 'nullable|numeric',
        ]);

        $invoice->update($validatedData);

        return response()->json([
            'success' => true,
            'data' => $invoice,
            'message' => 'Invoice updated successfully',
        ], 200);
    }

    // Delete an invoice (API endpoint)
    public function destroy($id)
    {
        $invoice = OutstandingInvoice::find($id);

        if (! $invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found',
            ], 404);
        }

        $invoice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invoice deleted successfully',
        ], 200);
    }

    // Show the listing page for invoices (Web view)
    public function index()
    {
        $clients = ClientServiceManager::getClientsWithInvoices();
        $invoices = OutstandingInvoice::with('clientService')->get();

        return view('dashboard.outstandingInvoices.index', compact('clients', 'invoices'));
    }

    // Show the form for creating a new invoice (Web view)
    public function create()
    {
        return view('dashboard.outstandingInvoices.create');  // Create view page
    }

    // Show the edit form for a specific invoice (Web view)
    public function edit($id)
    {
        $invoice = OutstandingInvoice::findOrFail($id);

        return view('dashboard.outstandingInvoices.edit', compact('invoice'));  // Edit view page
    }

    public function ShowInvoiceByClient($id)
    {
        // Fetch all clients
        $clients = Client::where('id', $id)->get();

        // Fetch the specific client
        $selectedClient = Client::find($id);

        // Fetch the client's services
        $ClientServices = ClientServiceManager::getClientServicesByClientId($id);

        // Get invoices for the selected client's services
        $invoices = OutstandingInvoice::with('clientService')
            ->whereIn('client_service_id', $ClientServices->pluck('id'))
            ->get();

        return view('dashboard.outstandingInvoices.index', compact('clients', 'selectedClient', 'invoices'));
    }

    public function ShowInvoiceByClientService($id)
    {
        // Fetch the specific client service
        $clientService = ClientService::find($id);

        if (! $clientService) {
            abort(404, 'Client service not found');
        }

        // Fetch the client associated with this service
        $selectedClient = $clientService->client;

        // Get invoices for this specific client service
        $invoices = OutstandingInvoice::with('clientService')
            ->where('client_service_id', $id)
            ->get();

        // Fetch all clients (optional, if needed for UI dropdowns or lists)
        $clients = Client::where('id', $id)->get();

        return view('dashboard.outstandingInvoices.index', compact('clients', 'selectedClient', 'clientService', 'invoices'));
    }
}
