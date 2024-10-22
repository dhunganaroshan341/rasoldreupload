<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Income;
use App\Models\Ledger;

class IncomeObserver
{
    /**
     * Handle the Income "created" event.
     */
    public function created(Income $income): void
    {
        //
        // Logic to create a ledger entry when income is created
        $this->updateLedger($income);
    }

    /**
     * Handle the Income "updated" event.
     */
    public function updated(Income $income): void
    {
        //
        // Logic to update the ledger entry when income is updated
        $this->updateLedger($income);
    }

    /**
     * Handle the Income "deleted" event.
     */
    public function deleted(Income $income): void
    {
        //
    }

    /**
     * Handle the Income "restored" event.
     */
    public function restored(Income $income): void
    {
        //
    }

    /**
     * Handle the Income "force deleted" event.
     */
    public function forceDeleted(Income $income): void
    {
        //
    }

    protected function updateLedger(Income $income)
    {
        // Check if a ledger entry already exists for the given client_service_id
        $ledgerEntry = Ledger::where('client_service_id', $income->income_source_id)
            ->where('transaction_date', $income->transaction_date)
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
            'transaction_date' => $income->transaction_date,
            'amount' => $income->amount,
            'medium' => $income->medium,
            'client_service_id' => $income->income_source_id,
            'income_id' => $income->id,
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
