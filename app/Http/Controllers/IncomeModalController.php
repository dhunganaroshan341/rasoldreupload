<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Income;
use App\Models\OurServices;
use App\Services\IncomeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomeModalController extends Controller
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

            $incomeSourceId = null;
            $clientService = null;

            // Handle existing or new client service
            if ($request->input('source_type') === 'existing') {
                $existingData = $request->validate([
                    // 'income_source' => 'required|exists:client_services,id',
                ]);

                // Retrieve the client service
                $clientService = ClientService::find($existingData['income_source_id']);

                // Check if the amount exceeds the remaining amount
                $checkError = IncomeServices::checkRemainingAmount($validatedData['amount'], $clientService);
                if ($checkError) {
                    return response()->json(['error' => $checkError], 400);
                }

                $incomeSourceId = $existingData['income_source_id'];
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
                return response()->json(['error' => 'Unable to determine income source.'], 400);
            }

            // Create the Income record
            Income::create([
                'income_source_id' => $incomeSourceId,
                'income_source' => $incomeSourceId,
                'medium' => $validatedData['medium'],
                'transaction_date' => $validatedData['transaction_date'],
                'amount' => $validatedData['amount'],
                'remarks' => $validatedData['remarks'] ?? '',
            ]);

            // Update remaining amount
            $updateError = IncomeServices::updateRemainingAmount($clientService, $validatedData['amount']);
            if ($updateError) {
                return response()->json(['error' => $updateError], 400);
            }

            // Return success response
            return response()->json(['message' => 'Income of '.$validatedData['amount'].' from '.$clientService->name.' created successfully!'], 201);
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
            'income_source' => 'required|exists:client_services,id',
            'medium' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'new_service_name' => 'nullable|string',
            'source_type' => 'required|string',
        ]);

        $income = Income::find($id);
        if (! $income) {
            return response()->json(['error' => 'Income not found.'], 404);
        }

        $oldIncomeAmount = $income->amount;

        // Determine if the source is existing or new
        if ($request->input('source_type') === 'existing') {
            $clientService = ClientService::find($validatedData['income_source']);
            IncomeServices::checkRemainingAmount($validatedData['amount'], $clientService);
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
            $income->income_source_id = $newClientService->id;
        }

        // Update the rest of the income fields
        $income->medium = $validatedData['medium'];
        $income->transaction_date = $validatedData['transaction_date'];
        $income->amount = $validatedData['amount'];
        $income->save();

        // Update the remaining amount
        $clientService = ClientService::find($income->income_source_id);
        IncomeServices::updateRemainingAmount($clientService, $validatedData['amount'], $oldIncomeAmount);

        return response()->json(['success' => 'Income updated successfully!'], 200);
    }

    // Other methods...

    public function destroy($id)
    {
        // Fetch the income record
        $income = Income::find($id);
        if (! $income) {
            return response()->json(['error' => 'Income not found.'], 404);
        }

        // Delete the income record
        $income->delete();

        // Return JSON response
        return response()->json(['success' => 'Income deleted successfully!'], 200);
    }

    public function storeIncomeModal(Request $request)
    {
        // The logic here is similar to the store method above.
        // Implement it accordingly, and return JSON responses as needed.
    }

    // Additional methods...
    public function storeIncomeFromClient(Request $request)
    {
        DB::beginTransaction();
        try {
            Income::create($request->all());

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
