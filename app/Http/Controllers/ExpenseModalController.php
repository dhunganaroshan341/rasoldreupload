<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Expense;
use App\Models\OurServices;
use App\Services\expenseServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseModalController extends Controller
{
    protected $user;

    public function index()
    {
        $this->user = Auth::user();
        // Example: Fetch all expenses
        $expenses = expense::all();

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
        return view('dashboard.expenses.create', [
            'services' => $services,
            'formTitle' => 'Create expense Record',
            'clientServices' => $clientServices,
            'formAction' => route('expenses.store'), // Route for form submission
            'backRoute' => 'transactions.index', // Route for back button
            'clients' => $clients,
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Authenticate the user
            $this->user = Auth::user();

            // Validate common fields first
            $validatedData = $request->validate([
                'source_type' => 'required|string|in:existing,new',
                'medium' => 'required|string',
                'transaction_date' => 'required|date',
                'amount' => 'required|numeric',
                'remarks' => 'nullable|string',
            ]);

            $expenseSourceId = null;
            $clientService = null;

            // Handle existing or new client service
            if ($request->input('source_type') === 'existing') {
                $existingData = $request->validate([
                    // 'expense_source' => 'required|exists:client_services,id',
                ]);

                // Retrieve the client service
                $clientService = ClientService::find($existingData['client_service_id']);

                // Check if the amount exceeds the remaining amount
                $checkError = ExpenseServices::checkRemainingAmount($validatedData['amount'], $clientService);
                if ($checkError) {
                    return response()->json(['error' => $checkError], 400);
                }

                $expenseSourceId = $existingData['client_service_id'];
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

                $expenseSourceId = $clientService->id;
            }

            // Ensure expenseSourceId is set before creating the expense record
            if ($expenseSourceId === null) {
                return response()->json(['error' => 'Unable to determine expense source.'], 400);
            }

            // Create the expense record
            expense::create([
                'client_service_id' => $expenseSourceId,
                'expense_source' => $expenseSourceId,
                'medium' => $validatedData['medium'],
                'transaction_date' => $validatedData['transaction_date'],
                'amount' => $validatedData['amount'],
                'remarks' => $validatedData['remarks'] ?? '',
            ]);

            // Update remaining amount
            $updateError = expenseServices::updateRemainingAmount($clientService, $validatedData['amount']);
            if ($updateError) {
                return response()->json(['error' => $updateError], 400);
            }

            // Return success response
            return response()->json(['message' => 'expense of '.$validatedData['amount'].' from '.$clientService->name.' created successfully!'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'An error occurred: '.$e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'expense_source' => 'required|exists:client_services,id',
            'medium' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'new_service_name' => 'nullable|string',
            'source_type' => 'required|string',
        ]);

        $expense = expense::find($id);
        if (! $expense) {
            return response()->json(['error' => 'expense not found.'], 404);
        }

        $oldexpenseAmount = $expense->amount;

        // Determine if the source is existing or new
        if ($request->input('source_type') === 'existing') {
            $clientService = ClientService::find($validatedData['expense_source']);
            expenseServices::checkRemainingAmount($validatedData['amount'], $clientService);
            $expense->client_service_id = $validatedData['expense_source'];
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

            $expense->client_service_id = $newClientService->id;
            $expense->client_service_id = $newClientService->id;
        }

        // Update the rest of the expense fields
        $expense->medium = $validatedData['medium'];
        $expense->transaction_date = $validatedData['transaction_date'];
        $expense->amount = $validatedData['amount'];
        $expense->save();

        // Update the remaining amount
        $clientService = ClientService::find($expense->client_service_id);
        expenseServices::updateRemainingAmount($clientService, $validatedData['amount'], $oldexpenseAmount);

        return response()->json(['success' => 'expense updated successfully!'], 200);
    }

    // Other methods...

    public function destroy($id)
    {
        // Fetch the expense record
        $expense = expense::find($id);
        if (! $expense) {
            return response()->json(['error' => 'expense not found.'], 404);
        }

        // Delete the expense record
        $expense->delete();

        // Return JSON response
        return response()->json(['success' => 'expense deleted successfully!'], 200);
    }

    public function storeexpenseModal(Request $request)
    {
        // The logic here is similar to the store method above.
        // Implement it accordingly, and return JSON responses as needed.
    }

    // Additional methods...
    public function storeexpenseFromClient(Request $request)
    {
        DB::beginTransaction();
        try {
            expense::create($request->all());

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
