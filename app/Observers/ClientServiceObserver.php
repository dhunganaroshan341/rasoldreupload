<?php

namespace App\Observers;

use App\Models\ClientService;
use App\Models\Income;

class ClientServiceObserver
{
    /**
     * Handle the ClientService "created" event.
     */
    public function created(ClientService $clientService): void
    {
        $this->handleAdvancePayment($clientService);
    }

    /**
     * Handle the ClientService "updated" event.
     */
    public function updated(ClientService $clientService): void
    {
        $this->handleAdvancePayment($clientService);
    }

    /**
     * Handle the advance payment logic.
     */
    protected function handleAdvancePayment(ClientService $clientService)
    {
        // Check if advance payment is made and the amount is greater than 0
        if ($clientService->advance_paid > 0) {
            // Check if an income entry for advance payment already exists
            $income = Income::where('income_source_id', $clientService->id)
                ->where('remarks', 'Advance Paid for service')
                ->first();

            if ($income) {
                // If an existing income entry is found, update it with the new advance payment amount
                $income->update([
                    'amount' => $clientService->advance_paid, // Update to the new advance_paid amount
                    'transaction_date' => now(),
                    'remarks' => 'Advance Paid for service', // Optional: Add or update remarks
                ]);
            } else {
                // If no existing income entry is found, create a new one for the advance payment
                Income::create([
                    'amount' => $clientService->advance_paid,
                    'income_source_id' => $clientService->id,
                    'transaction_date' => now(),
                    'medium' => 'cash', // Adjust payment medium if needed
                    'remarks' => 'Advance Paid for service', // Add custom remarks
                ]);
            }
        }
    }
}
