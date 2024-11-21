<?php

namespace App\Http\Controllers;

use App\Models\ClientService;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return redirect(route('transactions.index'));
    }

    public function create()
    {
        // Fetch all client services if needed
        $clientServices = ClientService::with(['client', 'service'])->get();

        return view('dashboard.expenses.new_form', [
            'formTitle' => 'Create Expense',
            'backRoute' => 'expenses.index',
            'formAction' => route('expenses.store'),
            'expense' => null, // No expense data for creating
            'clientServices' => $clientServices,
        ]);
    }

    public function store(Request $request)
    {
        $expense = $this->validateAndSaveExpense($request);

        if ($expense) {
            // Find the ClientService by the income_source_id
            $clientService = ClientService::where('id', $request['client_service_id'])->first();

            // Check if the client service exists
            if ($clientService) {
                // Calculate the remaining amount
                $outsourcedAmount = $clientService->amount - $request['amount'];

                // Update or insert the remaining amount
                if ($outsourcedAmount >= 0) {
                    ClientService::updateOrInsert(
                        ['id' => $clientService->id], // Use the ID to find the existing record
                        ['outsourced_amount' => $request['amount']] // Set the new remaining amount
                    );

                    return redirect()->route('expenses.index')->with('success', 'Expense created successfully!');
                }
            }

            // IF CLINENT SERVICE AMOUNT IS LESSER THAN THAT OF expenses it shouldn't be created that's it
            return redirect()->back()->with('error', 'expense>clientService::Expense= '.$request['amount'].'::clientservice='.$clientService->amount);
        }

        return redirect()->route('expenses.index')->with('error', 'Failed to create expense.');
    }

    public function update(Request $request, $id)
    {
        // Validate and save the expense
        $expense = $this->validateAndSaveExpense($request, $id);

        if ($expense) {
            // Find the ClientService by client_service_id
            $clientService = ClientService::where('id', $request['client_service_id'])->first();

            // Check if the client service exists
            if ($clientService) {
                // Calculate the new remaining amount after expense update
                $outsourcedAmount = $clientService->amount - $request['amount'];

                // Update the ClientService record
                if ($outsourcedAmount >= 0) {
                    ClientService::updateOrInsert(
                        ['id' => $clientService->id], // Use the ID to find the existing record
                        ['outsourced_amount' => $request['amount']] // Set the new outsourced amount
                    );

                    return redirect()->route('transactions.index')->with('success', 'Expense updated successfully!');
                }
            }

            // If client service amount is less than that of expenses
            return redirect()->back()->with('error', 'Expense exceeds available amount: Expense = '.$request['amount'].' | Client Service = '.$clientService->amount);
        }

        return redirect()->route('transactions.index')->with('error', 'Failed to update expense.');
    }

    public function edit($id)
    {
        $expense = Expense::find($id);
        $clientServices = ClientService::with(['client', 'service'])->get();

        if (! $expense) {
            return redirect()->route('expenses.index')->with('error', 'Expense not found.');
        }

        return view('dashboard.expenses.new_form', [
            'formTitle' => 'Edit Expense Record',
            'backRoute' => 'expenses.index',
            'formAction' => route('expenses.update', ['expense' => $expense->id]),
            'expense' => $expense,
            'clientServices' => $clientServices,
        ]);
    }

    public function editInModal($id)
    {
        $expense = Expense::find($id);

        if (! $expense) {
            return response()->json(['error' => 'Expense not found.'], 404);
        }

        return response()->json([
            'expense' => $expense,
            'formTitle' => 'Edit Expense Record',
            'formAction' => route('expenses.update', ['expense' => $expense->id]),
        ]);
    }

    public function updateOnModal(Request $request, $id)
    {
        $expense = $this->validateAndUpdateExpense($request, $id);

        if ($expense) {
            return response()->json(['success' => 'Expense updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update expense.'], 400);
    }

    // Private function for validating and saving expense
    private function validateAndSaveExpense(Request $request, $id = null)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'source_type' => 'required|string|max:255', // The type of expense
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'medium' => 'required|string',
            'client_service_id' => 'nullable|integer', // Only for outsourcing
            // 'expense_source' => 'nullable|string|max:255', // For custom expenses
        ]);

        // Check if the selected expense type is 'outsourcing'
        if ($request->source_type === 'outsourcing') {
            $validatedData['client_service_id'] = $request->client_service_id;
            // $validatedData['expense_source'] = null; // No custom expense source for outsourcing
            // $validatedData['expense_source'] = $request->expense_source; // Set the custom expense source if available
        } else {
            // Handle other expense types
            $validatedData['client_service_id'] = null;
            // $validatedData['expense_source'] = $request->expense_source; // Set the custom expense source if available
        }
        // not in use this expense source but in expense table it is not nullable so this is the solution
        $validatedData['expense_source'] = 'null';
        // Save or update expense
        if ($id) {
            $expense = Expense::find($id);
            if (! $expense) {
                return false;
            }
            $expense->update($validatedData);
        } else {
            Expense::create($validatedData);
        }

        return true;
    }

    private function validateAndUpdateExpense(Request $request, $id)
    {
        $validatedData = $request->validate([
            'expense_source' => 'nullable|string|max:255', // Allow null if not applicable
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'medium' => 'required|string',
        ]);

        // Handle 'medium' field if 'other' is selected
        if ($request->medium === 'other' && ! empty($request->other_source)) {
            $validatedData['medium'] = $request->other_source;
        }

        $expense = Expense::find($id);

        if (! $expense) {
            return false;
        }

        $expense->update($validatedData);

        return $expense;
    }
}
