<?php

namespace App\Providers;

use App\Models\Ledger;
use Illuminate\Support\ServiceProvider;

class LedgerServiceProvider extends ServiceProvider
{
    /**
     * Calculate the balance for a given client service.
     */
    public function calculateBalance(int $clientServiceId): array
    {
        // Calculate total income from the ledger for the client service
        $totalIncome = Ledger::where('client_service_id', $clientServiceId)
            ->where('transaction_type', 'income') // Assuming 'income' indicates income transactions in your ledger
            ->sum('amount');

        // Calculate total expenses from the ledger for the client service
        $totalExpenses = Ledger::where('client_service_id', $clientServiceId)
            ->where('transaction_type', 'expense') // Assuming 'expense' indicates expense transactions in your ledger
            ->sum('amount');

        // Calculate the remaining amount (balance)
        $remainingAmount = $totalIncome - $totalExpenses;

        return [
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'remaining_amount' => $remainingAmount, // Changed from balance to remaining_amount for clarity
        ];
    }
}
