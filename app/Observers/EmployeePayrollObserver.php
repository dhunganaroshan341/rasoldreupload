<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Expense;
use App\Models\Ledger;

class EmployeePayrollObserver
{
    /**
     * Handle the Expense "created" event.
     */
    public function created(Expense $expense): void
    {
        //
        // Logic to create a ledger entry when income is created
        $this->updateLedger($expense);
    }

    /**
     * Handle the Expense "updated" event.
     */
    public function updated(Expense $expense): void
    {
        //
        // Logic to update the ledger entry when income is updated
        $this->updateLedger($expense);
    }

    /**
     * Handle the Expense "deleted" event.
     */
    public function deleted(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "restored" event.
     */
    public function restored(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "force deleted" event.
     */
    public function forceDeleted(Expense $expense): void
    {
        //
    }

    protected function updateLedger(Expense $expense)
    {
        // Check if a ledger entry already exists for the given client_service_id
        $ledgerEntry = Ledger::where('client_service_id', $expense->income_source_id)
            ->where('transaction_date', $expense->transaction_date)
            ->first();
        if ($ledgerEntry) {
            // Get the ClientService associated with the ledger entry
            $clientService = ClientService::find($ledgerEntry->client_service_id);

            // Get the client_id from the ClientService
            $clientId = $clientService ? $clientService->client_id : null; // Assuming client_id is the foreign key in ClientService
        }
        // $clientId = 999;

        // Prepare ledger data
        $ledgerData = [
            'client_id' => 1, // Assuming this maps to client_id
            'transaction_type' => 'income',
            'source' => 'income',
            'transaction_date' => $expense->transaction_date,
            'amount' => $expense->amount,
            'medium' => $expense->medium,
            'client_service_id' => $expense->income_source_id,
            'income_id' => $expense->id,
        ];

        if ($ledgerEntry) {
            // If ledger entry exists, update it
            $ledgerEntry->update($ledgerData);
        } else {
            // If ledger entry does not exist, create a new one
            Ledger::create($ledgerData);
        }
    }
}
