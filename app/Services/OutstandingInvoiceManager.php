<?php

namespace App\Services;

use App\Models\OutStandingInvoice;

class OutstandingInvoiceManager
{
    public static function getAllInvoicesWithClientService()
    {
        // Fetch all invoices with their related client service
        $invoices = OutStandingInvoice::with('clientService')->get();

        // Return the invoices with the related client service
        return $invoices;
    }
}
