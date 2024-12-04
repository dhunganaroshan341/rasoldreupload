<?php

namespace App\Observers;

// use App\Managers\OutstandingInvoiceManager;
use App\Models\OutStandingInvoice;
use App\Services\OutstandingInvoiceManager;

class OutstandingInvoiceObserver
{
    /**
     * Handle the OutStandingInvoice "created" event.
     */
    public function created(OutStandingInvoice $invoice): void
    {
        // Find the most recent invoice for the same client, excluding the current one
        $clientService = $invoice->clientService;
        $previousInvoice = OutstandingInvoiceManager::getPreviousOutStandingInvoice($clientService);

        // If a previous invoice exists, update its status to 'overdue'
        if ($previousInvoice) {
            $previousInvoice->update([
                'status' => 'overdue',
            ]);
        }

        // Check if the current invoice is fully paid
        if (OutstandingInvoiceManager::isFullyPaid($invoice)) {
            // If fully paid, change the current invoice's status to 'paid'
            OutstandingInvoiceManager::changeInvoiceStatus($invoice, 'paid');
        }
    }

    /**
     * Handle the OutStandingInvoice "updated" event.
     */
    public function updated(OutStandingInvoice $invoice): void
    {
        // Find the most recent invoice for the same client, excluding the current one
        $previousInvoice = OutstandingInvoiceManager::getPreviousOutStandingInvoice($invoice->clientService);

        // If a previous invoice exists, update its status to 'overdue'
        if ($previousInvoice) {
            $previousInvoice->update([
                'status' => 'overdue',
            ]);
        }

        // Check if the current invoice is fully paid
        if (OutstandingInvoiceManager::isFullyPaid($invoice)) {
            // If fully paid, change the current invoice's status to 'paid'
            OutstandingInvoiceManager::changeInvoiceStatus($invoice, 'paid');
        }
    }

    /**
     * Handle the OutStandingInvoice "deleted" event.
     */
    public function deleted(OutStandingInvoice $invoice): void
    {
        // Add any logic for deleted event here if needed
    }

    /**
     * Handle the OutStandingInvoice "restored" event.
     */
    public function restored(OutStandingInvoice $invoice): void
    {
        // Add any logic for restored event here if needed
    }

    /**
     * Handle the OutStandingInvoice "force deleted" event.
     */
    public function forceDeleted(OutStandingInvoice $invoice): void
    {
        // Add any logic for force deleted event here if needed
    }
}
