<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Ledger;
use Illuminate\Support\Facades\Log;

class ClientServiceTransactionProvider
{
    public static function getRemainingAmount(ClientService $clientService)
    {
        // Total amount for the service
        $totalAmount = $clientService->amount;

        // Fetch all incomes associated with this specific client service
        $incomes = Income::where('income_source_id', $clientService->id)->get();

        // Calculate total paid amount
        $paidAmount = $incomes->sum('amount'); // Assuming 'amount' is the paid column in Income model

        // Calculate remaining amount
        $remainingAmount = $totalAmount - $paidAmount;
        Log::info("Total Amount: $totalAmount, Paid Amount: $paidAmount");

        return $remainingAmount;
    }

    // remaining amount upto the date
    public static function getRemainingAmountUpto(ClientService $clientService, $upToDate = null)
    {
        // Total amount for the service
        $totalAmount = $clientService->amount;

        // Fetch incomes associated with this specific client service up to a particular date
        $query = Income::where('income_source_id', $clientService->id);

        // Apply date filter if a date is provided
        if ($upToDate) {
            $query->whereDate('created_at', '<=', $upToDate);
        }

        // Calculate total paid amount up to the given date
        $paidAmount = $query->sum('amount'); // Assuming 'amount' is the paid column in Income model

        // Calculate remaining amount
        $remainingAmountUpto = $totalAmount - $paidAmount;
        Log::info("Total Amount: $totalAmount, Paid Amount (up to $upToDate): $paidAmount");

        return $remainingAmountUpto;
    }

    public static function getRemainingAmountsForAllClientServices()
    {
        // Fetch all client services
        $clientServices = ClientService::all();

        $remainingAmounts = [];

        foreach ($clientServices as $clientService) {
            // Calculate remaining amount for each client service
            $remainingAmount = self::getRemainingAmount($clientService);

            // Store the remaining amount in the array using the client service ID
            $remainingAmounts[$clientService->id] = $remainingAmount;
        }

        return $remainingAmounts;
    }
}
class LedgerTransactionsProvider
{
    public static function getLedgerOfClientService($client_service_id)
    {
        // Initialize the ledger
        $ledger = [];

        // Fetch the client service
        $client_service = ClientService::find($client_service_id);
        if (! $client_service) {
            return []; // or throw an exception if the client service is not found
        }
        $totalClientServiceAmount = $client_service->sum('amount');

        // Fetch incomes and expenses
        $incomes = Income::where('income_source_id', $client_service_id)->get();
        $expenses = Expense::where('client_service_id', $client_service_id)->get();

        // Calculate total income and total expenses
        $totalIncome = $incomes->sum('amount');  // Assuming there's an 'amount' column
        $totalExpense = $expenses->sum('amount'); // Assuming there's an 'amount' column

        // Initialize balance
        $remainingBalance = $client_service->amount - $totalExpense; // client_service->amount is the initial amount

        // Prepare ledger entries for incomes
        foreach ($incomes as $income) {
            $ledger[] = [
                'type' => 'Income',
                'amount' => $income->amount,
                'remaining_balance' => $remainingBalance,
                'description' => $income->description, // Assuming there's a description field
                'date' => $income->created_at, // Assuming there's a created_at field
            ];
            $remainingBalance -= $income->amount; // Update remaining balance after each income
        }

        // Reset balance for expenses
        $remainingBalance = $client_service->amount - $totalExpense;

        // Prepare ledger entries for expenses
        foreach ($expenses as $expense) {
            $ledger[] = [
                'type' => 'Expense',
                'amount' => $expense->amount,
                'remaining_balance' => $remainingBalance,
                'description' => $expense->description, // Assuming there's a description field
                'date' => $expense->created_at, // Assuming there's a created_at field
            ];
            $remainingBalance += $expense->amount; // Update remaining balance after each expense
        }

        // Optionally return total income, total expenses, and final balance
        return [
            'totalClientServiceAmount' => $totalClientServiceAmount,
            'ledger' => $ledger,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'final_balance' => $remainingBalance,
        ];
    }
}
// this is for the ledger calculation  especially for ledger table, the code below
