<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Ledger;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request; // Ensure you import the Pdf facade

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $invoices = Invoice::all();
        // $clientServices = $invoices->clients->clientServices;

        // Now you can pass the transactions and client services to the view
        return view('dashboard.invoices.index', compact('invoices'));
    }

    /**
     * Generate and download an invoice PDF.
     */
    // public function downloadInvoice($id)
    // {
    //     // Fetch invoice data
    //     $Transaction = Transaction::find($id);
    //     if (! $Transaction) {
    //         return redirect()->back()->withErrors(['message' => 'Contract not found.']);
    //     }

    //     // Load the view and pass the Transaction data
    //     $pdf = Pdf::loadView('invoices.invoice', ['Transaction' => $Transaction]);

    //     // Download the PDF file
    //     return $pdf->download('invoice_'.$id.'.pdf');
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return view to create a new invoice
        return view('dashboard.invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Validate and store a new invoice
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'required|numeric|min:0',
    //         'currency' => 'required|string',
    //         'start_date' => 'nullable|date',
    //         // Add other validation rules as needed
    //     ]);

    //     // Store the invoice data
    //     $Transaction = Contract::create($validatedData);

    //     return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the invoice data by ID
        $invoice = Invoice::find($id); // Assuming 'Invoice' is the correct model for invoices

        // Check if the invoice exists
        if (! $invoice) {
            // Redirect back with an error message if the invoice is not found
            return redirect()->route('invoices.index')->withErrors(['message' => 'Invoice not found.']);
        }

        // Pass the invoice data to the view
        return view('dashboard.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Return view to edit a specific invoice
        $Transaction = Contract::find($id);
        if (! $Transaction) {
            return redirect()->back()->withErrors(['message' => 'Contract not found.']);
        }

        return view('dashboard.invoices.edit', compact('Transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate and update the invoice data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'start_date' => 'nullable|date',
            // Add other validation rules as needed
        ]);

        $Transaction = Contract::find($id);
        if (! $Transaction) {
            return redirect()->back()->withErrors(['message' => 'Contract not found.']);
        }

        $Transaction->update($validatedData);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete the specified invoice
        $Transaction = Contract::find($id);
        if (! $Transaction) {
            return redirect()->back()->withErrors(['message' => 'Contract not found.']);
        }

        $Transaction->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    // different types of invices
    public function store(Request $request)
    {
        // Validate the request (optional but recommended)
        $request->validate([
            'ledger_id' => 'required|integer',
            'total_amount' => 'required|string',
            'remaining_amount' => 'required|string',
        ]);

        // Create an invoice
        $invoice = Invoice::create([
            'invoice_date' => now(), // Example field, modify as needed
            'total_amount' => 0, // You'll calculate this later
        ]);

        $totalAmount = 0;

        // Iterate over selected ledger entries and save them
        foreach ($request->selected_ledgers as $index => $ledgerId) {
            $clientServiceId = $request->client_service_ids[$index];
            $incomeId = $request->income_ids[$index];

            // Find the ledger entry by ID
            $ledger = Ledger::find($ledgerId);
            // $ledger = 'ledger';

            // Associate ledger entry with the invoice
            $invoice->ledgerEntries()->create([
                'ledger_id' => $ledgerId,
                'client_service_id' => $clientServiceId,
                'income_id' => $incomeId,
                'amount' => $ledger->amount,
            ]);

            // Add to the total invoice amount
            $totalAmount += $ledger->amount;
        }

        // Update total amount for the invoice
        $invoice->total_amount = $totalAmount;
        $invoice->save();

        // Redirect or return success response
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');
    }
}
