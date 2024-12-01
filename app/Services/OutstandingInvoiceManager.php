<?php 
namespace App\Services;

use App\Models\ClientService;
use App\Models\OutstandingInvoice;
use Carbon\Carbon;

class OutstandingInvoiceManager
{
    /**
     * Calculate the due date for a new invoice.
     *
     * @param ClientService $clientService The client service for which the invoice is being generated.
     * @return string The calculated due date in 'YYYY-MM-DD' format.
     */
    public static function calculateDueDate(ClientService $clientService)
    {
        // Get the billing frequency (e.g., monthly, quarterly, etc.)
        $billingFrequency = $clientService->billing_period_frequency;

        // Fetch the previous invoice to get the due date if it exists.
        $previousInvoice = OutstandingInvoice::where('client_service_id', $clientService->id)
            ->orderBy('due_date', 'desc') // Get the most recent invoice.
            ->first();

        // Use the previous due date or fallback to billing start date
        $dueDate = $previousInvoice
            ? Carbon::parse($previousInvoice->due_date)
            : Carbon::parse($clientService->billing_start_date);

        // Adjust the due date based on the billing frequency
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

        return $dueDate->toDateString(); // Return due date in YYYY-MM-DD format
    }

    /**
     * Calculate the total outstanding amount (all_total) for the invoice.
     *
     * @param ClientService $clientService The client service for which the invoice is being generated.
     * @return float The total outstanding amount.
     */
    public static function calculateTotalOutstandingAmount(ClientService $clientService)
    {
        // Get the billing frequency and previous invoice
        $billingFrequency = $clientService->billing_period_frequency;
        $previousInvoice = OutstandingInvoice::where('client_service_id', $clientService->id)
            ->orderBy('due_date', 'desc')
            ->first();

        // Get the previous remaining amount if a previous invoice exists
        $prevRemainingAmount = $previousInvoice ? $previousInvoice->remaining_amount : 0;

        // Determine the number of months in the current billing cycle
        $billingCycleMonths = match ($billingFrequency) {
            'monthly' => 1,
            'quarterly' => 3,
            'semi-annually' => 6,
            'annually' => 12,
            default => 1,
        };

        // Convert the service duration to months
        $totalServiceDurationMonths = match ($clientService->duration_type) {
            'months' => $clientService->duration,
            'years' => $clientService->duration * 12,
            default => $clientService->duration,
        };

        // Calculate the monthly service cost
        $monthlyAmount = $clientService->amount / $totalServiceDurationMonths;

        // Calculate the amount for the current billing cycle
        $currentInvoiceAmount = $monthlyAmount * $billingCycleMonths;

        // Calculate the total outstanding amount (previous remaining + current billing cycle amount)
        $totalOutstandingAmount = $prevRemainingAmount + $currentInvoiceAmount;

        return $totalOutstandingAmount; // Return the calculated total outstanding amount
    }

    /**
     * Generate a new invoice for the client service.
     *
     * @param ClientService $clientService The client service for which the invoice is being generated.
     * @return OutstandingInvoice The newly created invoice instance.
     */
    public static function generateInvoice(ClientService $clientService)
    {
        // Calculate the due date for the new invoice
        $dueDate = self::calculateDueDate($clientService);

        // Calculate the total outstanding amount for the invoice
        $totalOutstandingAmount = self::calculateTotalOutstandingAmount($clientService);

        // Create and save the new invoice record
        $newInvoice = OutstandingInvoice::create([
            'client_service_id' => $clientService->id,
            'total_amount' => $totalOutstandingAmount, // Total outstanding amount
            'prev_remaining_amount' => $totalOutstandingAmount - $clientService->amount, // Assuming amount is paid previously
            'all_total' => $totalOutstandingAmount, // Total outstanding amount
            'paid_amount' => 0, // Assuming no payment yet
            'remaining_amount' => $totalOutstandingAmount, // Same as total outstanding initially
            'due_date' => $dueDate, // Calculated due date
            'description' => 'Generated Invoice for Service: ' . $clientService->name,
            'bill_number' => self::generateBillNumber($clientService), // Unique bill number
            'status' => 'pending', // Default status
        ]);

        return $newInvoice; // Return the newly created invoice
    }

    /**
     * Generate a unique bill number for the invoice.
     *
     * @param ClientService $clientService The client service for which the invoice is being generated.
     * @return string The generated bill number.
     */
    protected static function generateBillNumber(ClientService $clientService)
    {
        return 'INV-' . strtoupper($clientService->name) . '-' . now()->timestamp;
    }
}
