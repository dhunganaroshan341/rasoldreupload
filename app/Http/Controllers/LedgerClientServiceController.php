<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Ledger;
use App\Models\OurServices;
use App\Services\LedgerTableTransactionProvider;
use App\Services\LedgerTransactionsProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LedgerClientServiceController extends Controller
{
    protected $user;

    public function index($id)
    {
        $client = Client::find($id);
        $clientServices = ClientService::where('client_id', $client->id)->get(); // Corrected: using get() instead of all()
        $employees = Employee::all();
        $expenses = Expense::all();

        // Pass data to the view
        return view('dashboard.ledgerClientService.index', compact('client', 'employees', 'expenses', 'clientServices'));
    }

    // ledger coming from the ledger table that is joined  from income and exense
    public function show($id)
    {
        // Get the client service with the specified ID
        $clientService = ClientService::with(['client', 'service', 'ledgers'])->find($id);
        if (! $clientService) {
            // Handle the case where the client service is not found
            abort(404, 'Client service not found.');
        }

        // Retrieve the associated client
        $client = $clientService->client;

        // Get all client services for the client
        $clientServices = $client ? $client->clientServices : collect();

        // Prepare other data for the view
        $ledgers = LedgerTableTransactionProvider::getLedgerEntriesForClientService($id);
        $ledgerCalculationForClientService = LedgerTableTransactionProvider::getLedgerCalculationForClientService($clientService->id);
        $clientServiceName = $clientService->name ?? ($clientService->client->name.'-'.$clientService->service->name);

        // Pass the data to the view
        return view('dashboard.ledgerClientService.showFromLedger', compact(
            'clientService', 'client', 'ledgers', 'ledgerCalculationForClientService', 'clientServiceName', 'clientServices'
        ));
    }

    // public function showClientService($client_service_id) {}

    // public function showSingleEntity($ledger_id)
    // {
    //     // get clientid and then client details
    //     $client_id = Ledger::where('id', $ledger_id)->value('client_id');
    //     $client = Client::where('id', $client_id)->first(); // Use first() to retrieve the first record
    //     $ledger = Ledger::where('id', $ledger_id)->first(); // Use get() to retrieve the collection
    //     // Assuming the source format is 'client_id|service_id'
    //     [$clientId, $serviceId] = explode('|', $ledger->source);

    //     // Fetch the Client and OurService models using the IDs
    //     $client = Client::find($clientId);
    //     $service = OurServices::find($serviceId);
    //     $employees = Employee::all();

    //     // Pass both client and ledgers to the view
    //     return view('dashboard.ledgerClientService.showSingleEntity', compact('client', 'ledger', 'service', 'employees'));
    // }

    public function clientServiceIndex($client_service_id)
    {
        // Fetch the ledger data
        $ledgerData = LedgerTransactionsProvider::getLedgerOfClientService($client_service_id);

        // Check if the ledger data was retrieved successfully
        if (! $ledgerData) {
            // Handle the case where no ledger data is returned (e.g., return an error view)
            return redirect()->back()->with('error', 'Client service not found.');
        }

        // Extract the ledger array from the response
        $ledger = $ledgerData['ledger']; // This will contain the combined entries of incomes and expenses
        $totalIncome = $ledgerData['total_income'];
        $totalExpense = $ledgerData['total_expense'];
        $finalBalance = $ledgerData['final_balance'];
        $totalClientServiceAmount = $ledgerData['totalClientServiceAmount'];

        // Separate income and expense entries
        $incomes = array_filter($ledger, fn ($entry) => $entry['type'] === 'Income');
        $expenses = array_filter($ledger, fn ($entry) => $entry['type'] === 'Expense');

        // Pass the data to the view
        return view('dashboard.ledgerClientService.ledger_client_service', compact('ledger', 'incomes', 'expenses', 'totalIncome', 'totalExpense', 'finalBalance', 'totalClientServiceAmount'));
    }

    public function create()
    {
        // Fetch contracts for the dropdown (if applicable)
        $contracts = Contract::all();

        // Prepare view data
        return view('dashboard.incomes.form', [
            'formTitle' => 'Create Income Record',
            'contracts' => $contracts,
            'formAction' => route('incomes.store'), // Route for form submission
            'backRoute' => 'incomes.index', // Route for back button
        ]);
    }

    public function store(Request $request)
    {
        $this->user = Auth::user();
        // Validate incoming request data
        $validatedData = $request->validate([
            'income_source' => 'required|string',
            // 'income_source_id' => 'nullable|numeric',
            'medium' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        // Create new income record
        Income::create($validatedData);

        // Redirect back or to another page after successful creation
        return redirect()->route('incomes.index')->with('success', 'Income created successfully!');
    }

    public function edit($id)
    {
        // Fetch the income record by ID
        $income = Income::find($id);

        // Redirect if the income record is not found
        if (! $income) {
            return redirect()->route('incomes.index')->with('error', 'Income not found.');
        }

        // Fetch contracts for the dropdown
        $contracts = Contract::all();

        // Prepare view data
        return view('dashboard.incomes.form', [
            'income' => $income,
            'formTitle' => 'Edit Income Record',
            'formAction' => route('incomes.update', ['income' => $income->id]), // Route for form update
            'backRoute' => 'incomes.index', // Route for back button
            'contracts' => $contracts,
        ]);
    }

    // public function edit($id)
    // {
    //     $income = Income::find($id);

    //     if (! $income) {
    //         return response()->json(['error' => 'Income not found.'], 404);
    //     }

    //     return response()->json($income);
    // }

    // public function edit($id)
    // {
    //     $income = Income::find($id);

    //     if (! $income) {
    //         return response()->json(['error' => 'Income not found.'], 404);
    //     }

    //     if (request()->ajax()) {
    //         return response()->json($income);
    //     }

    //     // For standard requests
    //     $formTitle = 'Edit Income Record';
    //     $backRoute = 'incomes.index';
    //     $formAction = route('incomes.update', ['income' => $income->id]);

    //     return view('dashboard.incomes.form', compact('income', 'formTitle', 'backRoute', 'formAction'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'income_source' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'medium' => 'required|string',
            // Add more validation rules as needed
        ]);

        $income = Income::find($id);

        if (! $income) {

            return redirect()->route('incomes.index')->with('error', 'Income not found.');
        }

        $income->income_source = $request->income_source;
        $income->transaction_date = $request->transaction_date;
        $income->amount = $request->amount;

        $income->save();

        return redirect()->route('transactions.index')->with('success', 'Income record of:'.$income->income_source.' updated successfully.');
    }

    // getting multiple details for invoice
    // public function getMultipleDetails(Request $request)
    // {
    //     $ids = $request->ids;
    //     if (empty($ids)) {
    //         return response()->json(['success' => false]);

    //     }
    //     $incomesFromLedger = Ledger::with('clientService')->whereIn('id', $ids)->get();
    //     if ($incomesFromLedger->count() > 0) {
    //         return response()->json(['success' => true, 'data' => $incomesFromLedger]);
    //     }

    // }
    public function getMultipleDetails(Request $request)
    {
        $ids = $request->input('ids');

        // Retrieve the ledgers with the clientService relation
        $ledgers = Ledger::whereIn('id', $ids)->with('clientService')->get();

        // Calculate the sum of all clientService amounts
        $totalAmount = $ledgers->sum(function ($ledger) {
            return $ledger->clientService->amount;
        });

        // Map the data to send in the response
        $data = $ledgers->map(function ($ledger) use ($totalAmount) {
            return [
                'id' => $ledger->id,
                'totalClientServiceAmount' => $totalAmount, // Attach the total sum to each record
                'amount' => $ledger->amount,
                'client_id' => $ledger->clientService->client->id,
                'client_service' => [
                    'name' => $ledger->clientService->name,
                    'amount' => $ledger->clientService->amount,
                    'remaining_amount' => $ledger->clientService->remaining_amount,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'total_client_service_amount' => $totalAmount, // The total sum of client service amounts
        ]);
    }
}
