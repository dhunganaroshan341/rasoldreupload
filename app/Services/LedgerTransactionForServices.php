<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Ledger;
use App\Models\OurServices;

class LedgerTransactionForServices
{
    public static function getServiceLedgerEntriesWithSummary(int $serviceId)
    {
        // Retrieve the service with its related client services
        $service = OurServices::with('clientServices')->findOrFail($serviceId);
        // Calculate the total possible income by summing the 'amount' field in client services
        $totalPossibleIncome = $service->clientServices->sum('amount');

        // Get all client_service_ids for this service
        $clientServiceIds = $service->clientServices->pluck('id');

        // Retrieve all ledger entries for the found client_service_ids
        $ledgers = Ledger::whereIn('client_service_id', $clientServiceIds)->get();

        // Calculate total income
        $totalIncome = Ledger::whereIn('client_service_id', $clientServiceIds)
            ->where('transaction_type', 'income')
            ->sum('amount');

        // Calculate total expense
        $totalExpense = Ledger::whereIn('client_service_id', $clientServiceIds)
            ->where('transaction_type', 'expense')
            ->sum('amount');

        // Calculate balance
        $balance = $totalIncome - $totalExpense;

        // Return ledger entries along with the summary
        return [
            'ledgers' => $ledgers,
            'summary' => [
                'totalIncome' => $totalIncome,
                'totalExpense' => $totalExpense,
                'balance' => $balance,
                'totalClientServiceAmount' => $totalPossibleIncome,
            ],
        ];
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

        return [
            'clientService' => $clientService,
            'clientServiceLedgerEntries' => $ledgerEntries,
            'clientServiceTotalIncome' => $totalIncome,
            'clientServiceTotalExpense' => $totalExpense,
            'clientServiceBalance' => $balance,
        ];
    }

    public function incomeCalculationForService($service_id)
    {
        // Find the service and load related client services and their incomes
        $service = OurServices::with('clientServices.incomes')->find($service_id);

        if (! $service) {
            return [
                'error' => 'Service not found.',
                'incomes' => collect(),
                'totalIncome' => 0,
                'remainingAmount' => 0,
            ];
        }

        // Collect all incomes and calculate total income and remaining amounts
        $incomes = $service->clientServices->flatMap->incomes;
        $totalIncome = $incomes->sum('amount');
        $remainingAmount = $service->clientServices->sum('remaining_amount');

        return [
            'incomes' => $incomes,
            'totalIncome' => $totalIncome,
            'remainingAmount' => $remainingAmount,
        ];
    }

    public function expenseCalculationForService($service_id)
    {
        // Find the service and load related client services and their expenses
        $service = OurServices::with('clientServices.expenses')->find($service_id);

        if (! $service) {
            return [
                'error' => 'Service not found.',
                'expenses' => collect(),
                'totalExpense' => 0,
                'remainingAmount' => 0,
            ];
        }

        // Collect all expenses and calculate total expense and remaining amounts
        $expenses = $service->clientServices->flatMap->expenses;
        $totalExpense = $expenses->sum('amount');
        $remainingAmount = $service->clientServices->sum('remaining_amount');

        return [
            'expenses' => $expenses,
            'totalExpense' => $totalExpense,
            'remainingAmount' => $remainingAmount,
        ];
    }

    //    summary client service
    // Method to generate a summary for each income entry related to a client service
    public static function incomeSummaryClientService($income)
    {
        $clientService = $income->clientService;
        $serviceName = $clientService->service->name ?? 'Unknown Service';
        $clientName = $clientService->client->name ?? 'Unknown Client';

        return "Income of amount {$income->amount} was received from {$clientName} for the service '{$serviceName}' on {$income->date}.";
    }

    // Method to generate a summary for each expense entry related to a client service
    public static function expenseSummaryClientService($expense)
    {
        $clientService = $expense->clientService;
        $serviceName = $clientService->service->name ?? 'Unknown Service';
        $clientName = $clientService->client->name ?? 'Unknown Client';

        return "Expense of amount {$expense->amount} was recorded for {$clientName} under the service '{$serviceName}' on {$expense->date}.";
    }
}
