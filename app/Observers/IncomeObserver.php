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
        // Find the ledger entry based on the specific income_id
        $ledgerEntry = Ledger::where('income_id', $income->id)->first();

        // Fetch the associated client_id from ClientService if needed
        $clientService = ClientService::find($income->income_source_id);
        $clientId = $clientService ? $clientService->client_id : null;

        // Prepare ledger data
        $ledgerData = [
            'client_id' => $clientId, // Using dynamic client_id
            'transaction_type' => 'income',
            'source' => 'income',
            'transaction_date' => $income->transaction_date,
            'amount' => $income->amount,
            'medium' => $income->medium,
            'client_service_id' => $income->income_source_id,
            'income_id' => $income->id,
        ];

        if ($ledgerEntry) {
            // Update existing ledger if it exists for this specific income
            $ledgerEntry->update($ledgerData);
        } else {
            // Create a new ledger entry if one doesn't exist for this income
            Ledger::create($ledgerData);
        }
    }
}
