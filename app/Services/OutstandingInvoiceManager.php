<?php

namespace App\Services;

use App\Models\ClientService;
use App\Models\OutStandingInvoice;
use Carbon\Carbon;

class OutstandingInvoiceManager
{
    public static function getAllInvoicesWithClientService()
    {
        // Fetch all invoices with their related client service
        $invoices = OutStandingInvoice::with('clientService')->get();

        // Return the invoices with the related client service
        return $invoices;
    }

    /**
     * Calculate the due date for a new invoice.
     *
     * @param  ClientService  $clientService  The client service for which the invoice is being generated.
     * @return string The calculated due date in 'YYYY-MM-DD' format.
     */
    public static function calculateDueDate(ClientService $clientService)
    {
        // Get the billing frequency (e.g., monthly, quarterly).
        $billingFrequency = $clientService->billing_period_frequency;

        // Check if there is a previous invoice for this service.
        $previousInvoice = OutstandingInvoice::where('client_service_id', $clientService->id)
            ->orderBy('due_date', 'desc') // Get the most recent invoice.
            ->first();

        // Start from the previous due date if it exists, otherwise use the billing start date.
        $dueDate = $previousInvoice
            ? Carbon::parse($previousInvoice->due_date)
            : Carbon::parse($clientService->billing_start_date);

        // Adjust the due date based on the billing frequency.
        switch ($billingFrequency) {
            case 'monthly':
                $dueDate->addMonth(); // Add one month.
                break;
            case 'quarterly':
                $dueDate->addMonths(3); // Add three months.
                break;
            case 'semi-annually':
                $dueDate->addMonths(6); // Add six months.
                break;
            case 'annually':
                $dueDate->addYear(); // Add one year.
                break;
            default:
                $dueDate->addMonth(); // Default to adding one month.
                break;
        }

        // Return the calculated due date as a string.
        return $dueDate->toDateString();
    }

    /**
     * Calculate the total amount, previous remaining amount, and total outstanding amount for an invoice.
     *
     * @param  ClientService  $clientService  The client service for which the invoice is being generated.
     * @return array An array containing 'total_amount', 'prev_remaining_amount', and 'all_total'.
     */
    public static function calculateInvoiceAmount(ClientService $clientService)
    {
        // Get the billing frequency (e.g., monthly, quarterly).
        $billingFrequency = $clientService->billing_period_frequency;

        // Fetch the previous invoice to get the remaining balance.
        $previousInvoice = OutstandingInvoice::where('client_service_id', $clientService->id)
            ->orderBy('due_date', 'desc') // Get the most recent invoice.
            ->first();

        // Remaining amount from the previous invoice.
        $prevRemainingAmount = $previousInvoice ? $previousInvoice->remaining_amount : 0;

        // Determine the number of months in the current billing cycle.
        $billingCycleMonths = match ($billingFrequency) {
            'monthly' => 1,
            'quarterly' => 3,
            'semi-annually' => 6,
            'annually' => 12,
            default => 1, // Default to 1 month.
        };

        // Convert the total service duration into months.
        $totalServiceDurationMonths = match ($clientService->duration_type) {
            'months' => $clientService->duration,
            'years' => $clientService->duration * 12,
            default => $clientService->duration, // Default to months.
        };

        // Calculate the monthly amount for the service.
        $monthlyAmount = $clientService->amount / $totalServiceDurationMonths;

        // Calculate the amount for the current billing cycle.
        $currentInvoiceAmount = $monthlyAmount * $billingCycleMonths;

        // Total outstanding amount (previous remaining + current cycle amount).
        $totalAmount = $currentInvoiceAmount + $prevRemainingAmount;

        // Return the calculated amounts.
        return [
            'total_amount' => $currentInvoiceAmount,
            'prev_remaining_amount' => $prevRemainingAmount,
            'all_total' => $totalAmount,
        ];
    }

    /**
     * Generate a new invoice for the specified client service.
     *
     * @param  ClientService  $clientService  The client service for which the invoice is being generated.
     * @return OutstandingInvoice The newly created invoice instance.
     */
    public static function generateInvoice(ClientService $clientService)
    {
        // Calculate the due date for the new invoice.
        $dueDate = self::calculateDueDate($clientService);
        // Calculate the total amount, previous remaining amount, and outstanding total.
        $amounts = self::calculateInvoiceAmount($clientService);
        // Create and save the new invoice record in the database.
        $newInvoice = OutstandingInvoice::create([
            'client_service_id' => $clientService->id,
            'total_amount' => $amounts['total_amount'], // Amount for the current billing cycle.
            'prev_remaining_amount' => $amounts['prev_remaining_amount'], // Previous balance.
            'all_total' => $amounts['all_total'], // Total outstanding amount.
            'paid_amount' => 0, // Initial payment is zero.
            'remaining_amount' => $amounts['all_total'], // Same as total outstanding initially.
            'due_date' => $dueDate, // Calculated due date.
            'description' => 'Generated Invoice for Service: '.$clientService->name,
            'bill_number' => self::generateBillNumber($clientService), // Unique bill number.
            'status' => 'pending', // Initial status is 'pending'.
        ]);

        return $newInvoice;
    }

    /**
     * Update payment details for an invoice.
     *
     * @param  OutstandingInvoice  $invoice  The invoice to be updated.
     * @param  float  $paymentAmount  The amount paid for the invoice.
     * @return void
     */
    public static function updateInvoicePayment(OutstandingInvoice $invoice, $paymentAmount)
    {
        // Increment the paid amount by the payment received.
        $invoice->paid_amount += $paymentAmount;

        // Decrease the remaining amount by the payment received.
        $invoice->remaining_amount -= $paymentAmount;

        // If the remaining amount is zero or less, mark the invoice as 'paid'.
        if ($invoice->remaining_amount <= 0) {
            $invoice->status = 'paid';
            $invoice->remaining_amount = 0; // Ensure no negative balance.
        }

        // Save the updated invoice.
        $invoice->save();
    }

    /**
     * Generate a unique bill number for an invoice.
     *
     * @param  ClientService  $clientService  The client service for which the invoice is being generated.
     * @return string The generated bill number.
     */
    protected static function generateBillNumber(ClientService $clientService)
    {
        return 'INV-'.strtoupper($clientService->name).'-'.now()->timestamp;
    }
}
