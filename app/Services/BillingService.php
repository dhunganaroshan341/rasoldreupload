<?php

namespace App\Services;

use App\Models\OutStandingInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillingService
{
    /**
     * Calculate the billing end date based on the billing start date and duration.
     *
     * @param  string  $billingStartDate
     * @param  int  $duration
     * @param  string  $durationType  (e.g., 'days', 'weeks', 'months', 'years')
     * @return string
     *
     * @throws \Exception
     */
    public static function calculateBillingEndDate($billingStartDate, $duration, $durationType)
    {
        // Parse the start date
        $startDate = Carbon::parse($billingStartDate);

        // Normalize duration type to lowercase for consistency
        $durationType = strtolower($durationType);

        // Add the duration to the start date based on the duration type
        switch ($durationType) {
            case 'day':
            case 'days':
                return $startDate->addDays($duration)->toDateString();

            case 'week':
            case 'weeks':
                return $startDate->addWeeks($duration)->toDateString();

            case 'month':
            case 'months':
                return $startDate->addMonths($duration)->toDateString();

            case 'year':
            case 'years':
                return $startDate->addYears($duration)->toDateString();

            default:
                throw new \Exception("Invalid duration type: $durationType");
        }
    }

    /**
     * Calculate both billing start and end dates.
     *
     * @param  string  $billingStartDate
     * @param  int  $duration
     * @param  string  $durationType
     * @return array
     */
    public static function calculateBillingDates($billingStartDate, $duration, $durationType)
    {
        // Billing start date (it's already passed as input)
        $startDate = Carbon::parse($billingStartDate);

        // Calculate the end date
        $endDate = self::calculateBillingEndDate($billingStartDate, $duration, $durationType);

        return [
            'billing_start_date' => $startDate->toDateString(),
            'billing_end_date' => $endDate,
        ];
    }

    /**
     * Validate input data and calculate the billing dates.
     *
     * @return array
     */
    public static function calculateBilling(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'billing_start_date' => 'required|date',
            'duration' => 'required|integer', // Numeric duration
            'duration_type' => 'required|string', // days, weeks, months, years
        ]);

        // Call the reusable service to calculate the billing start and end dates
        try {
            return self::calculateBillingDates(
                $validated['billing_start_date'],
                $validated['duration'],
                $validated['duration_type']
            );
        } catch (\Exception $e) {
            // Handle errors gracefully (you could return an error array or throw a new exception)
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function getInvoiceWithClientService($invoiceId)
    {
        // Fetch the invoice with its related client service and client
        $invoice = OutStandingInvoice::with(['clientService', 'clientService.client'])->find($invoiceId);

        if ($invoice) {
            // Access the client service and client
            $clientService = $invoice->clientService;
            $client = $clientService ? $clientService->client : null;

            // You can return or process the invoice, client service, and client as needed
            return [
                'invoice' => $invoice,
                'clientService' => $clientService,
                'client' => $client,
            ];
        }

        return null; // In case the invoice isn't found
    }
}
