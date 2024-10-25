<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Ledger;

class LedgerTableTransactionProvider
{
    public static function getLedgerEntriesForClient($clientId)
    {
        // Step 1: Find the client by ID
        $client = Client::findOrFail($clientId);

        // Step 2: Get all ClientServices for the client
        $clientServices = $client->clientServices()->pluck('id');

        // Step 3: Find all LedgerEntries related to those ClientServices
        $ledgerEntries = Ledger::whereIn('client_service_id', $clientServices)->get();

        return $ledgerEntries;
    }

    public static function getLedgerEntriesForClientService($client_service_id)
    {
        // Find the client service by the provided ID
        $clientService = ClientService::find($client_service_id);

        // Ensure the client service exists
        if (! $clientService) {
            return null; // Return null or handle the error as needed
        }

        // Fetch all ledger entries associated with the client service
        $ledgerEntries = Ledger::where('client_service_id', $clientService->id)->get(); // Corrected: using get() instead of all()

        return $ledgerEntries;
    }

    public static function getLedgerCalculationForClientService($client_service_id)
    {
        // Step 1: Find the ClientService by ID
        $clientService = ClientService::findOrFail($client_service_id);

        // Step 2: Get all ledger entries for the specified client service
        $ledgerEntries = Ledger::where('client_service_id', $client_service_id)->get(); // Use get() instead of all()

        // Step 3: Calculate total income by filtering for income transaction type
        $totalIncome = $ledgerEntries->where('transaction_type', 'income')->sum('amount');
        $totalExpense = $ledgerEntries->where('transaction_type', operator: 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;
        $totalClientServiceAmount = $clientService->sum('amount');

        return [
            'totalClientServiceAmount' => $totalClientServiceAmount,
            'clientService' => $clientService,
            'clientServiceLedgerEntries' => $ledgerEntries,
            'clientServiceTotalIncome' => $totalIncome,
            'clientServiceTotalExpense' => $totalExpense,
            'clientServiceBalance' => $balance,
        ];
    }

    public static function getLedgerCalculationForClient($client_id)
    {
        // Step 1: Find the Client by ID
        $client = Client::findOrFail($client_id);

        // Step 2: Get all client services for the specified client
        $clientServices = $client->clientServices;

        // Initialize total income, total expense, and balance
        $totalIncome = 0;
        $totalExpense = 0;

        // Step 3: Loop through each client service to get ledger entries and calculate totals
        foreach ($clientServices as $clientService) {
            // Get all ledger entries for the current client service
            $ledgerEntries = Ledger::where('client_service_id', $clientService->id)->get();

            // Calculate total income for the current client service
            $totalIncome += $ledgerEntries->where('transaction_type', 'income')->sum('amount');

            // Calculate total expense for the current client service
            $totalExpense += $ledgerEntries->where('transaction_type', 'expense')->sum('amount');
        }

        // Step 4: Calculate the balance
        $balance = $totalIncome - $totalExpense;
        $totalRemaining = $totalIncome - $balance;

        return [
            'client_id' => $client_id,
            'clientServices' => $clientServices,
            'clientTotalIncome' => $totalIncome,
            'clientTotalExpense' => $totalExpense,
            'clientBalance' => $balance,
            'clientTotalRemaining' => $totalRemaining,
        ];
    }

    public function incomeCalculationForClient($client_id)
    {
        // Step 1: Find the client by ID
        $client = Client::findOrFail($client_id);

        // Step 2: Get all ClientService IDs for the client
        $clientServiceIds = $client->clientServices()->pluck('id');

        // Step 3: Find all Income entries related to those ClientService IDs
        $incomes = Income::whereIn('income_source_id', $clientServiceIds)->get();
        $remainingAmount =
        // Optionally, you can sum the income if you want the total income
        $totalIncome = $incomes->sum('amount'); // Assuming 'amount' is the field storing the income value

        return [
            'incomes' => $incomes,
            'totalIncome' => $totalIncome,
        ];
    }

    public function incomeCalculationForClientService($client_service_id)
    {
        $clientService = ClientService::find($client_service_id);
        $incomes = Income::where('income_source_id', $clientService->id)->get();
        $totalIncome = $incomes->sum('amount');
        $remainingAmount = $clientService->remaining_amount;

        return [
            'incomes' => $incomes,
            'totalIncome' => $totalIncome,
            'remainingAmount' => $remainingAmount,
        ];

    }

    public function expenseCalculationForClient($client_id)
    {
        // Step 1: Find the client by ID
        $client = Client::findOrFail($client_id);

        // Step 2: Get all ClientService IDs for the client
        $clientServiceIds = $client->clientServices()->pluck('id');

        // Step 3: Find all Income entries related to those ClientService IDs
        $expenses = Expense::whereIn('client_service_id', $clientServiceIds)->get();

        // Optionally, you can sum the income if you want the total income
        $totalOutSourcedExpense = $expenses->sum('amount'); // Assuming 'amount' is the field storing the income value

        return [
            'clientExpenses' => $expenses,
            'totalClientExpense' => $totalOutSourcedExpense,
        ];
    }

    public function expenseCalculationForClientService($client_service_id)
    {
        $clientService = ClientService::find($client_service_id);
        $expenses = Expense::where('client_service_id', $clientService->id)->get();
        $totalExpense = $expenses->sum('amount');
        // $remainingAmount = $clientService->remaining_amount;

        return [
            'clientServiceExpenses' => $expenses,
            'totalClientServiceExpense' => $totalExpense,
            // 'remainingAmount' => $remainingAmount,
        ];

    }

    // total amount by client id
    public static function getTotalClientServiceAmountByClient(Client $client)
    {
        // Initialize the total amount
        $totalAmount = 0;

        // Retrieve all client services for the client
        $clientServices = $client->clientServices;

        // Loop through each client service and sum the amounts
        foreach ($clientServices as $clientService) {
            // Assuming 'amount' is a direct attribute of the ClientService model
            $totalAmount += $clientService->amount;
        }

        // Now $totalAmount contains the total amount for the client's services
        return $totalAmount;
    }
}
