<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Ledger;
use App\Models\OurServices;
use Illuminate\Support\Collection as SupportCollection;

class LedgerTransactionForServices
{
    public static function getLedgerEntriesForService(int $serviceId): SupportCollection
    {
        $ourService = OurServices::with('clientServices.incomes', 'clientServices.expenses')->findOrFail($serviceId);

        // Ensure there are client services
        if ($ourService->clientServices->isEmpty()) {
            return collect(); // Or handle as needed
        }

        $ledgerEntries = collect();

        $totalIncome = $ourService->clientServices->flatMap->incomes->sum('amount');
        $totalExpense = $ourService->clientServices->flatMap->expenses->sum('amount');

        foreach ($ourService->clientServices as $clientServiceEntry) {
            foreach ($clientServiceEntry->incomes as $income) {
                $ledgerEntries->push([
                    'type' => 'income',
                    'amount' => $income->amount,
                    'description' => $income->description,
                    'date' => $income->date->format('Y-m-d'),
                    'client_service_id' => $clientServiceEntry->id,
                    'summary' => self::incomeSummaryClientService($income),
                ]);
            }

            foreach ($clientServiceEntry->expenses as $expense) {
                $ledgerEntries->push([
                    'type' => 'expense',
                    'amount' => $expense->amount,
                    'description' => $expense->description,
                    'date' => $expense->transaction_date->format('Y-m-d'),
                    'client_service_id' => $clientServiceEntry->id,
                    'summary' => self::expenseSummaryClientService($expense),
                ]);
            }
        }

        $ledgerEntries->push([
            'type' => 'summary',
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
        ]);

        return $ledgerEntries->sortBy('date')->values();
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
