<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\Contract;
use App\Models\Income;
use App\Models\OurServices;
use App\Services\IncomeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    // Display all incomes
    public function index()
    {
        $incomes = Income::all();

        return view('dashboard.incomes.index', compact('incomes'));
    }

    // Show the form to create a new income record
    public function create()
    {
        $clientServices = ClientService::all();
        $services = OurServices::all();
        $clients = Client::all();

        return view('dashboard.incomes.create', [
            'services' => $services,
            'formTitle' => 'Create Income Record',
            'clientServices' => $clientServices,
            'formAction' => route('incomes.store'),
            'backRoute' => 'transactions.index',
            'clients' => $clients,
        ]);
    }

    // Store a new income record
    public function store(IncomeRequest $request)
    {
        $validatedData = $request->validated();

        // Handle either existing or new client service
        $clientService = $this->handleClientService($request, $validatedData);

        // Check if the client service is valid
        if (! $clientService) {
            return redirect()->back()->withErrors(['source_type' => 'Unable to determine income source.']);
        }

        // Check if income amount exceeds the remaining amount
        $errorMessage = IncomeServices::checkRemainingAmount($validatedData['amount'], $clientService);
        if ($errorMessage) {
            return redirect()->back()->withErrors(['amount' => $errorMessage]);
        }

        // Create income record
        Income::create([
            'income_source_id' => $clientService->id,
            'medium' => $validatedData['medium'],
            'transaction_date' => $validatedData['transaction_date'],
            'amount' => $validatedData['amount'],
            'remarks' => $validatedData['remarks'] ?? '',
        ]);

        // Update remaining amount for the client service
        IncomeServices::updateRemainingAmount($clientService, $validatedData['amount']);

        return redirect()->route('transactions.index')->with('success', 'Income created successfully!');
    }

    // Edit an existing income record
    public function edit($id)
    {
        $income = Income::find($id);

        if (! $income) {
            return redirect()->route('incomes.index')->with('error', 'Income not found.');
        }

        $clientService = $income->clientService;
        $remainingAmount = $clientService ? $clientService->remaining_amount : 0;
        $incomeSourceAmount = $clientService ? $clientService->amount : 0;

        // Fetch necessary data for the form
        $services = OurServices::all();
        $contracts = Contract::all();
        $clients = Client::all();
        $clientServices = ClientService::all();
        $formTitle = ' ('.$clientService->name.') ';

        return view('dashboard.incomes.create', [
            'income' => $income,
            'clientService' => $clientService,
            'remainingAmount' => $remainingAmount,
            'incomeSourceAmount' => $incomeSourceAmount,
            'services' => $services,
            'contracts' => $contracts,
            'clients' => $clients,
            'clientServices' => $clientServices,
            'formTitle' => $formTitle,
            'formAction' => route('incomes.update', $income->id),
            'backRoute' => 'transactions.index',
        ]);
    }

    // Update an existing income record
    public function update(IncomeRequest $request, $id)
    {
        $validatedData = $request->validated(); // Use validated() from the IncomeRequest

        $income = Income::find($id);

        if (! $income) {
            return redirect()->route('incomes.index')->with('error', 'Income not found.');
        }

        // Handle the client service (whether it's an existing one or a new one)
        $clientService = $this->handleClientService($request, $validatedData);
        if (! $clientService) {
            return redirect()->back()->withErrors(['source_type' => 'Unable to determine income source.']);
        }

        // Check if the income amount exceeds the remaining amount
        $errorMessage = IncomeServices::checkRemainingAmount($validatedData['amount'], $clientService);
        if ($errorMessage) {
            return redirect()->back()->withErrors(['amount' => $errorMessage]);
        }

        // Update the income record
        $income->update([
            'income_source_id' => $clientService->id,
            'medium' => $validatedData['medium'],
            'transaction_date' => $validatedData['transaction_date'],
            'amount' => $validatedData['amount'],
            'remarks' => $validatedData['remarks'] ?? '',
        ]);

        // Update remaining amount
        IncomeServices::updateRemainingAmount($clientService, $validatedData['amount'], $income->amount);

        return redirect()->route('incomes.index')->with('success', 'Income updated successfully!');
    }

    // Handle client service creation or selection
    protected function handleClientService(Request $request, array $validatedData)
    {
        $clientService = null;

        if ($request->input('source_type') === 'existing') {
            $existingData = $request->validate([
                'income_source' => 'required|exists:client_services,id',
            ]);
            $clientService = ClientService::find($existingData['income_source']);
        } elseif ($request->input('source_type') === 'new') {
            $newData = $request->validate([
                'new_service_name' => 'required|string|max:255',
                'new_service_id' => 'required|exists:our_services,id',
                'new_client_id' => 'required|exists:clients,id',
            ]);

            // Create new client service
            $clientService = ClientService::create([
                'name' => $newData['new_service_name'],
                'client_id' => $newData['new_client_id'],
                'service_id' => $newData['new_service_id'],
                'amount' => OurServices::find($newData['new_service_id'])->price,
                'remaining_amount' => OurServices::find($newData['new_service_id'])->price,
            ]);
        }

        return $clientService;
    }

    // Delete an income record
    public function destroy($id)
    {
        $income = Income::find($id);
        if (! $income) {
            return redirect()->route('incomes.index')->with('error', 'Income not found.');
        }

        $income->delete();

        return redirect()->route('incomes.index')->with('success', 'Income deleted successfully!');
    }

    public function storeIncomeModal(Request $request)
    {
        $this->user = Auth::user();
        // Validate common fields first
        $validatedData = $request->validate([
            'source_type' => 'required|string|in:existing,new',
            'medium' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric',
            'remarks' => 'nullable|string',
        ]);

        $incomeSourceId = null;
        $clientService = null;

        // Handle existing or new client service
        if ($request->input('source_type') === 'existing') {
            $existingData = $request->validate([
                'income_source' => 'required|exists:client_services,id',
            ]);

            // Retrieve the client service
            $clientService = ClientService::find($existingData['income_source']);
            // Check if amount exceeds remaining amount
            $this->checkRemainingAmount($validatedData['amount'], $clientService);

            $incomeSourceId = $existingData['income_source'];
        } elseif ($request->input('source_type') === 'new') {
            $newData = $request->validate([
                'new_service_name' => 'required|string|max:255',
                'new_service_id' => 'required|exists:our_services,id',
                'new_client_id' => 'required|exists:clients,id',
            ]);

            // Create new client service
            $clientService = ClientService::create([
                'name' => $newData['new_service_name'],
                'client_id' => $newData['new_client_id'],
                'service_id' => $newData['new_service_id'],
                'amount' => OurServices::find($newData['new_service_id'])->price,
                'remaining_amount' => OurServices::find($newData['new_service_id'])->price,
            ]);

            $incomeSourceId = $clientService->id;
        }

        // Ensure incomeSourceId is set before creating the Income record
        if ($incomeSourceId === null) {
            return redirect()->back()->withErrors(['source_type' => 'Unable to determine income source.']);
        }

        // Create the Income record
        Income::create([
            'income_source_id' => $incomeSourceId,
            'medium' => $validatedData['medium'],
            'transaction_date' => $validatedData['transaction_date'],
            'amount' => $validatedData['amount'],
            'remarks' => $validatedData['remarks'] ?? '',
        ]);

        // Update remaining amount
        $this->updateRemainingAmount($clientService, $validatedData['amount']);

        return response('incomes.index')->with('success', 'Income created successfully!');
    }

    public function CreateModal()
    {
        // Fetch all client services and services
        $clientServices = ClientService::all();
        $services = OurServices::all();

        // Prepare view data
        return response()->json([
            'formTitle' => 'Create Income Record',
            'clientServices' => $clientServices,
            'formAction' => route('incomes.store'), // Route for form submission
            'backRoute' => 'transactions.index', // Route for back button
        ]);
    }
}
