<?php
/**
 * OutstandingInvoiceManager Class
 *
 * This class is responsible for managing invoice-related logic for client services.
 * It includes methods to calculate invoice amounts, due dates, discounts, outstanding balances, and other related operations.
 * The flow of the class enables seamless generation, updating, and status management of invoices.
 *
 * Flow of Methods:
 *
 * 1. `getBillingCycleMonths`:
 *    Determines the number of months in a billing cycle based on the billing frequency (e.g., monthly, quarterly).
 *
 * 2. `calculateMonthlyAmount`:
 *    Computes the monthly cost of a service by dividing the total amount by the total service duration in months.
 *
 * 3. `calculateDueDate`:
 *    Calculates the next due date for an invoice based on the billing frequency and the most recent invoice or start date.
 *
 * 4. `calculateInvoiceAmount`:
 *    Determines the total amount for an invoice, factoring in previous balances or calculating based on the service's billing cycle.
 *
 * 5. `getPrevRemainingAmount`:
 *    Retrieves the remaining amount from the previous invoice, if any.
 *
 * 6. `calculateTotalPaid`:
 *    Sums up the payments made toward an invoice and checks if it is fully paid.
 *
 * 7. `calculateRemainingAmount`:
 *    Calculates the remaining unpaid balance for an invoice.
 *
 * 8. `calculateDiscountAmountFromPercent`:
 *    Computes the discount amount for an invoice based on a given percentage.
 *
 * 9. `generateInvoice`:
 *    Generates a new outstanding invoice for a client service, incorporating total, previous remaining balances, and due date.
 *
 * 10. `generateBillNumber`:
 *     Creates a unique bill number for an invoice based on the client service name and timestamp.
 *
 * 11. `getPreviousOutStandingInvoice`:
 *     Retrieves the most recent outstanding invoice for a client service.
 *
 * 12. `changeInvoiceStatus`:
 *     Updates the status of an invoice and saves the changes to the database.
 *
 * The methods are designed to work cohesively to streamline invoice generation and management for client services.
 */

namespace App\Services;

use App\Models\ClientService;
use App\Models\Invoice;
use App\Models\OutstandingInvoice;
use Carbon\Carbon;

class OutstandingInvoiceManager
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_OVERDUE = 'overdue';

    public const STATUS_PAID = 'paid';

    /**
     * Determine the number of months in a billing cycle based on frequency.
     *
     * @param  string  $frequency  The billing frequency (e.g., 'monthly', 'quarterly').
     * @return int Number of months in the billing cycle.
     */
    private static function getBillingCycleMonths($frequency)
    {
        return match ($frequency) {
            'monthly' => 1,
            'quarterly' => 3,
            'semi-annually' => 6,
            'annually' => 12,
            default => 1,
        };
    }

    /**
     * Calculate the monthly cost of a service.
     *
     * @param  ClientService  $clientService  The client service details.
     * @return float Monthly amount for the service.
     */
    private static function calculateMonthlyAmount(ClientService $clientService)
    {
        $totalMonths = match ($clientService->duration_type) {
            'months' => $clientService->duration,
            'years' => $clientService->duration * 12,
            default => $clientService->duration,
        };

        return $clientService->amount / $totalMonths;
    }

    /**
     * Calculate the next due date for a client service invoice.
     *
     * @param  ClientService  $clientService  The client service details.
     * @return string Calculated due date in 'YYYY-MM-DD' format.
     */
    public static function calculateDueDate(ClientService $clientService)
    {
        $billingFrequency = $clientService->billing_period_frequency;

        // Retrieve the most recent invoice for the client service
        $previousInvoice = OutstandingInvoice::where('client_service_id', $clientService->id)
            ->orderBy('due_date', 'desc')
            ->first();

        $baseDate = $previousInvoice
            ? Carbon::parse($previousInvoice->due_date)
            : Carbon::parse($clientService->billing_start_date);

        // Add the corresponding months based on the billing frequency
        switch ($billingFrequency) {
            case 'monthly':
                $baseDate->addMonth();
                break;
            case 'quarterly':
                $baseDate->addMonths(3);
                break;
            case 'semi-annually':
                $baseDate->addMonths(6);
                break;
            case 'annually':
                $baseDate->addYear();
                break;
            default:
                $baseDate->addMonth();
                break;
        }

        return $baseDate->toDateString();
    }

    /**
     * Calculate the total amount for an invoice.
     *
     * @param  ClientService  $clientService  The client service details.
     * @return float Total outstanding amount for the invoice.
     */
    public static function calculateInvoiceAmount(ClientService $clientService)
    {
        $previousInvoice = OutstandingInvoice::where('client_service_id', $clientService->id)
            ->orderBy('due_date', 'desc')
            ->first();

        $totalOutstandingAmount = 0;

        if ($previousInvoice) {
            // If a previous invoice exists, calculate the outstanding amount
            $totalOutstandingAmount = $previousInvoice->remaining_amount + $previousInvoice->total_amount;
        } else {
            // Get the number of months for the billing cycle
            $billingCycleMonths = self::getBillingCycleMonths($clientService->billing_period_frequency);

            // Calculate the total service duration in months
            $totalServiceDurationMonths = match ($clientService->duration_type) {
                'days' => $clientService->duration / 30,
                'months' => $clientService->duration,
                'years' => $clientService->duration * 12,
                default => $clientService->duration,
            };

            // Check if the total service duration is valid (greater than zero)
            if ($totalServiceDurationMonths <= 0) {
                // Handle the case where the service duration is invalid
                throw new \Exception('Invalid service duration for client service ID '.$clientService->id);
            }

            // Calculate the monthly amount based on the duration
            $monthlyAmount = $clientService->amount / $totalServiceDurationMonths;

            // Calculate the total outstanding amount for the invoice
            $totalOutstandingAmount = $monthlyAmount * $billingCycleMonths;
        }

        return $totalOutstandingAmount;
    }

    public static function getPrevRemainingAmount(ClientService $clientService)
    {
        $prevInvoice = self::getPreviousOutStandingInvoice($clientService);

        // Ensure $prevInvoice is not null before accessing its properties
        $prevRemainingAmount = $prevInvoice ? $prevInvoice->remaining_amount : 0;

        return $prevRemainingAmount;
    }

    public static function updateNewInvoice() {}

    /**
     * Calculate the total paid amount for an invoice.
     *
     * @param  OutstandingInvoice  $invoice  The invoice details.
     * @return array Contains total paid amount and payment status.
     */
    public static function calculateTotalPaid(OutstandingInvoice $invoice)
    {
        $totalPaid = $invoice->incomes()->sum('amount');
        $isFullyPaid = $totalPaid >= $invoice->all_total;

        return [
            'total_paid' => $totalPaid,
            'is_fully_paid' => $isFullyPaid,
        ];
    }

    public static function isFullyPaid(OutstandingInvoice $invoice)
    {
        $calculateTotalPaid = self::calculateTotalPaid($invoice);

        // Directly return the value as a boolean
        return (bool) $calculateTotalPaid['is_fully_paid'];
    }

    /**
     * Calculate the remaining amount for an invoice.
     *
     * @param  OutstandingInvoice  $invoice  The invoice details.
     * @return float Remaining amount to be paid.
     */
    public static function calculateRemainingAmount(OutstandingInvoice $invoice)
    {
        $totalPaidSummary = self::calculateTotalPaid($invoice);

        if ($totalPaidSummary['is_fully_paid']) {
            return 0;
        }

        return $invoice->all_total - $totalPaidSummary['total_paid'];
    }

    /**
     * Calculate discount amount based on a percentage.
     *
     * @param  OutstandingInvoice  $invoice  The invoice details.
     * @param  float  $discountPercent  Discount percentage.
     * @return float Calculated discount amount.
     */
    public static function calculateDiscountAmountFromPercent(OutstandingInvoice $invoice, $discountPercent)
    {
        $invoiceAmount = self::calculateInvoiceAmount($invoice->clientService);

        return $discountPercent * $invoiceAmount / 100;
    }

    /**
     * Generate a new invoice for a client service.
     *
     * @param  ClientService  $clientService  The client service details.
     * @return OutstandingInvoice Newly created invoice.
     */

    /**
     * Generate a unique bill number for the invoice.
     *
     * @param  ClientService  $clientService  The client service details.
     * @return string Unique bill number.
     */
    protected static function generateBillNumber(ClientService $clientService)
    {
        return 'INV-'.strtoupper($clientService->name).'-'.now()->timestamp;
    }

    /**
     * Retrieve the previous invoice for a client service.
     *
     * @param  ClientService  $clientService  The client service details.
     * @return OutstandingInvoice|null Previous invoice, if it exists.
     */
    public static function getPreviousOutStandingInvoice(ClientService $clientService)
    {
        return $clientService->outstandingInvoices()
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public static function changeInvoiceStatus(OutStandingInvoice $invoice, $status)
    {
        try {
            // Update the status of the invoice
            $invoice->status = $status;

            // Save the changes to the database
            $invoice->save();

            // Return success message
            return [
                'success' => true,
                'message' => 'Invoice status updated successfully.',
            ];
        } catch (\Throwable $e) {
            // Handle any exceptions or errors
            return [
                'success' => false,
                'message' => 'Failed to update invoice status.',
                'error' => $e->getMessage(), // Include error details for debugging
            ];
        }
    }

    public static function generateInvoice(ClientService $clientService)
    {
        $dueDate = self::calculateDueDate($clientService);
        $totalOutstandingAmount = self::calculateInvoiceAmount($clientService);
        $previousInvoice = self::getPreviousOutStandingInvoice($clientService);
        $previousRemainingAmount = $previousInvoice
            ? self::calculateRemainingAmount($previousInvoice)
            : 0;

        return OutstandingInvoice::create([
            'client_service_id' => $clientService->id,
            'total_amount' => $totalOutstandingAmount,
            'prev_remaining_amount' => $previousRemainingAmount,
            'all_total' => $totalOutstandingAmount + $previousRemainingAmount,
            'paid_amount' => 0,
            'remaining_amount' => $totalOutstandingAmount + $previousRemainingAmount,
            'due_date' => $dueDate,
            'description' => 'Generated Invoice for Service: '.$clientService->name,
            'bill_number' => self::generateBillNumber($clientService),
            'status' => self::STATUS_PENDING,
        ]);
    }

    public static function updatePaidAmount(OutstandingInvoice $invoice)
    {
        try {
            // Calculate the total paid amount
            $paidAmount = self::calculateTotalPaid($invoice);

            // Ensure $paidAmount['total_paid'] is set
            if (! isset($paidAmount['total_paid'])) {
                return 'Invalid total paid value';  // Return a simple string message
            }

            // Update the invoice with the calculated paid amount
            $invoice->update(['paid_amount' => $paidAmount['total_paid']]);

            // Return a success message
            return 'Paid amount updated successfully';  // Success message

        } catch (\Throwable $th) {
            // Log the exception for better debugging

            return 'An error occurred while updating paid amount.'.'-'.$th->getMessage();  // Error message
        }
    }
}
