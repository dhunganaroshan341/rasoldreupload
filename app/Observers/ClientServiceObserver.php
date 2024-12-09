<?php

namespace App\Observers;

use App\Models\ClientService;
use App\Models\Income;
use Illuminate\Support\Facades\Log;

class ClientServiceObserver
{
    /**
     * Handle the ClientService "created" event.
     */
    public function created(ClientService $clientService): void
    {
        $this->handleAdvancePayment($clientService);
        $this->handleOurService($clientService);
    }

    /**
     * Handle the ClientService "updated" event.
     */
    public function updated(ClientService $clientService): void
    {
        $this->handleAdvancePayment($clientService);
    }

    public function deleted(ClientService $clientService)
    {
        $this->handleOurService($clientService, 'inactive');
    }

    /**
     * Handle the advance payment logic.
     */
    protected function handleAdvancePayment(ClientService $clientService)
    {
        try {
            if ($clientService->advance_paid > 0) {
                $income = Income::where('income_source_id', $clientService->id)
                    ->where('remarks', 'Advance Paid for service')
                    ->first();

                if ($income) {
                    $income->update([
                        'amount' => $clientService->advance_paid,
                        'transaction_date' => now(),
                    ]);
                } else {
                    Income::create([
                        'amount' => $clientService->advance_paid,
                        'income_source_id' => $clientService->id,
                        'transaction_date' => now(),
                        'medium' => 'cash',
                        'remarks' => 'Advance Paid for service',
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error handling advance payment: '.$e->getMessage());
        }
    }

    protected function handleOurService(ClientService $clientService, $status = 'active')
    {
        // Retrieve the associated OurService model
        $ourService = $clientService->ourService;

        if ($ourService) {
            // Update the status of OurService
            $ourService->status = $status;
            $ourService->save(); // Save the changes in the database

            return 'Success';
        }

        // If no OurService is found, return a failure message
        return 'OurService not found.';
    }
}
