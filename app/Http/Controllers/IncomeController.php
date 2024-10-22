<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Contract;
use App\Models\Income;
use App\Models\OurServices;
use App\Services\ClientServiceTransactionProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    protected $user;

    public function index()
    {
        $this->user = Auth::user();
        // Example: Fetch all incomes
        $incomes = Income::all();

        // Pass data to the view
        return redirect(route('transactions.index'));
    }

    public function create()
    {
        // Fetch all client services and services
        $clientServices = ClientService::all();
        $services = OurServices::all();
        $clients = Client::all();

        // Prepare view data
        return view('dashboard.incomes.create', [
            'services' => $services,
            'formTitle' => 'Create Income Record',
            'clientServices' => $clientServices,
            'formAction' => route('incomes.store'), // Route for form submission
            'backRoute' => 'transactions.index', // Route for back button
            'clients' => $clients,
        ]);
    }

    public function store(Request $request)
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

        return redirect()->back('incomes.index')->with('success', 'Income created successfully!');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'income_source' => 'required|exists:client_services,id',
            'medium' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'new_service_name' => 'nullable|string',
            'source_type' => 'required|string',
        ]);

        $income = Income::find($id);
        $oldIncomeAmount = $income->amount;

        if (! $income) {
            return redirect()->route('incomes.index')->with('error', 'Income not found.');
        }

        // Determine if the source is existing or new
        if ($request->input('source_type') === 'existing') {
            $clientService = ClientService::find($validatedData['income_source']);
            $this->checkRemainingAmount($validatedData['amount'], $clientService);
            $income->income_source_id = $validatedData['income_source'];
        } elseif ($request->input('source_type') === 'new') {
            $newData = $request->validate([
                'new_service_name' => 'required|string|max:255',
                'new_service_id' => 'required|exists:our_services,id',
                'new_client_id' => 'required|exists:clients,id',
            ]);

            $newClientService = ClientService::create([
                'service_name' => $request->input('new_service_name'),
                'service_id' => $newData['new_service_id'],
                'client_id' => $newData['new_client_id'],
                'amount' => OurServices::find($newData['new_service_id'])->price,
                'remaining_amount' => OurServices::find($newData['new_service_id'])->price,
            ]);

            $income->income_source_id = $newClientService->id;
        }

        // Update the rest of the income fields
        $income->medium = $validatedData['medium'];
        $income->transaction_date = $validatedData['transaction_date'];
        $income->amount = $validatedData['amount'];
        $income->save();

        // Update the remaining amount
        $clientService = ClientService::find($income->income_source_id);
        $errorMessage = $this->updateRemainingAmount($clientService, $validatedData['amount'], $oldIncomeAmount);

        return redirect()->route('incomes.index')->with('success', 'Income updated successfully!');
    }

    protected function checkRemainingAmount($amount, $clientService)
    {
        if ($clientService && $amount < $clientService->remaining_amount) {
            $this->erorRemainingAmountExceedingMessage($clientService);
        }
    }

    protected function updateRemainingAmount($clientService, $amount, $oldIncomeAmount = 0)
    {
        if ($clientService) {
            $remainingAmount = $clientService->remaining_amount - ($amount - $oldIncomeAmount);
            if ($remainingAmount < 0) {
                $errorMessage = $this->erorRemainingAmountExceedingMessage($clientService);

                return $errorMessage;
            }
            $clientService->update(['remaining_amount' => $remainingAmount]);
        }
    }

    public function edit($id)
    {
        // Fetch the income record by ID, redirect if not found
        $income = Income::find($id);

        if (! $income) {
            return redirect()->route('incomes.index')->with('error', 'Income not found.');
        }

        // Eager load the associated client service
        $clientService = $income->clientService; // This will fetch the ClientService related to this Income

        // Check if the client service exists
        if (! $clientService) {
            return redirect()->route('incomes.index')->with('error', 'Associated client service not found.');
        }
        // to fetch current client service;
        $currentClientService = $clientService;
        // Fetch remaining amount for the client service
        $remainingAmount = ClientServiceTransactionProvider::getRemainingAmount($clientService); // This takes the ClientService object

        // Fetch the total amount for the client service
        $incomeSourceAmount = $clientService->amount;
        $income = Income::with('clientService')->find($id);
        $selectedClientService = $income ? $income->clientService : null;
        $selectedClientServiceId = $selectedClientService ? $selectedClientService->id : null;

        // Fetch additional data for the form (contracts, clients, services)
        $services = OurServices::all();
        $contracts = Contract::all();
        $clients = Client::all();
        $clientServices = ClientService::all(); // Fetch all client services if needed for dropdown

        // Mark as edit mode
        $edit = true;

        // Prepare data for the view
        return view('dashboard.incomes.create', [
            'client_service_remaining_amount' => $remainingAmount,   // Remaining amount for the service
            'client_service_total_amount' => $incomeSourceAmount,    // Total amount for the service
            'income' => $income,                                     // Income being edited
            'services' => $services,                                 // All services
            'formTitle' => 'Edit Income Record',                     // Title for the form
            'formAction' => route('incomes.update', ['income' => $income->id]), // Route for form update
            'backRoute' => 'incomes.index',                          // Route for back button
            'contracts' => $contracts,                               // All contracts
            'edit' => $edit,                                         // Flag to indicate edit mode
            'clientServices' => $clientServices,                     // All client services
            'clients' => $clients,                                   // All clients
            'selectedClientService' => $selectedClientService,
            'currentClientService' => $currentClientService,
        ]);
    }

    public function erorRemainingAmountExceedingMessage($clientService)
    {
        return redirect()->back()->withErrors(['amount' => 'The income amount cannot exceed the remaining amount('.$clientService->remaining_amount.') of the selected client service.']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function destroy($id)
    {
        // Fetch the income record
        $income = Income::find($id);
        if (! $income) {
            return redirect()->route('incomes.index')->with('error', 'Income not found.');
        }

        // Delete the income record
        $income->delete();

        // Redirect back with success message
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
