<?php

//
/**hello
hi

### Explanation of the **OutstandingInvoiceManager** Class:
The class is designed to manage invoice creation and updates for services provided to clients. Here's a breakdown of its key features:

1. **Billing Cycle Calculation (`getBillingCycleMonths`)**:
   - Determines the months in a billing cycle based on the billing frequency (monthly, quarterly, etc.).

2. **Monthly Amount Calculation (`calculateMonthlyAmount`)**:
   - Calculates a per-month service cost by dividing the total amount by the service duration.

3. **Due Date Calculation (`calculateDueDate`)**:
   - Determines the next invoice due date based on the billing frequency and the last invoice.

4. **Invoice Amount Calculation (`calculateInvoiceAmount`)**:
   - Computes the outstanding invoice amount, factoring in the current and previous balances.

5. **Outstanding Balances and Payments**:
   - Handles previous unpaid balances (`getPrevRemainingAmount`).
   - Summarizes total payments (`calculateTotalPaid`) and calculates the unpaid balance (`calculateRemainingAmount`).

6. **Invoice Discount Calculation**:
   - Provides functionality to calculate invoice discounts by percentage.

7. **Invoice Management**:
   - Creates new invoices (`generateInvoice`).
   - Generates unique bill numbers.
   - Retrieves the previous invoice for a client service.
   - Updates the payment status dynamically based on the amounts received.

8. **Status Management**:
   - Allows updates to invoice statuses (e.g., pending, overdue, paid) via `changeInvoiceStatus`.

9. **Error Handling**:
   - Includes try-catch blocks to ensure database operations (e.g., `updatePaidAmount`) handle errors gracefully and provide detailed feedback.

### Use Cases:
This class is ideal for:
- Automating invoice creation and payment tracking for subscription-based or recurring services.
- Managing payment histories and maintaining accurate financial records.
- Providing detailed insights into outstanding balances and payment status for clients.

### Extensibility:
The class can be expanded with features like:
- Notification triggers for overdue invoices.
- Integration with external payment gateways.
- Reporting and analytics tools for financial summaries.


**/

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
        // Directly return the 'is_fully_paid' boolean from the result of calculateTotalPaid
        return self::calculateTotalPaid($invoice)['is_fully_paid'];
    }

    /**
     * Calculate the remaining amount for an invoice.
     *
     * @param  OutstandingInvoice  $invoice  The invoice details.
     * @return float Remaining amount to be paid.
     */
    public static function calculateRemainingAmount(OutstandingInvoice $invoice)
    {
        // Ensure that remaining amount is being calculated correctly
        return $invoice->all_total - $invoice->paid_amount;  // Example calculation
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

    public static function getSecondLastOutstandingInvoice(ClientService $clientService)
    {
        // Assuming you are using Eloquent or a similar ORM
        return Invoice::where('client_service_id', $clientService->id)
            ->orderBy('invoice_date', 'desc') // Order by date in descending order
            ->skip(1) // Skip the most recent invoice
            ->take(1) // Take the next one (second last)
            ->first(); // Return the first (and only) result, which is the second last invoice
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

    public static function updateRemainingAmount(OutstandingInvoice $invoice)
    {
        $remainingAmount = self::calculateRemainingAmount($invoice);
        logger('Remaining Amount: ', [$remainingAmount]); // Log the remaining amount to debug

        // Check if remainingAmount is a valid value
        if ($remainingAmount !== null) {
            $invoice->update(['remaining_amount' => $remainingAmount]);
        } else {
            // Handle the case where the remaining amount isn't calculated properly
            logger('Failed to calculate remaining amount');
        }
    }

    public static function autoUpdateOtherColumns(OutstandingInvoice $invoice)
    {
        try {
            // Log steps for debugging
            logger('Updating columns for invoice ID: ', [$invoice->id]);

            // Example logs for debugging
            $clientService = $invoice->clientService;
            $previousInvoice = self::getSecondLastOutstandingInvoice($clientService);
            // logger('Previous Invoice:', $previousInvoice->);

            $previousInvoiceIsFullyPaid = self::isFullyPaid($invoice);
            logger('Previous Invoice Fully Paid:', [$previousInvoiceIsFullyPaid]);

            // Continue with your logic...
            self::updatePaidAmount($invoice);
            self::updateRemainingAmount($invoice);
            if ($previousInvoice != null) {
                self::changeInvoiceStatus($previousInvoice->amount, 'overdue');
            }
            // More steps...

            return 'successfully updated rest of the columns';
        } catch (\Throwable $th) {
            logger('Error in autoUpdateOtherColumns: ', [$th->getMessage()]);

            return 'error updating columns: '.$th->getMessage();
        }
    }

    public static function updateNewInvoice() {}

    public static function showInvoice(OutstandingInvoice $invoice)
    {
        if ($invoice) {
            // Return the data directly (no JSON wrapping)
            return $invoice;  // Just return the invoice object (not in JSON format)
        } else {
            return null;  // Return null or handle error as needed
        }
    }
}
