<?php

namespace App\Services;

class ExpenseServices
{
    public static function checkRemainingAmount($amount, $clientService)
    {
        if ($clientService && $amount > $clientService->remaining_amount) {
            // Call the error message function statically
            return self::errorRemainingAmountExceedingMessage($clientService);
        }

        return null; // Return null if no error
    }

    public static function updateRemainingAmount($clientService, $amount, $oldIncomeAmount = 0)
    {
        if ($clientService) {
            $remainingAmount = $clientService->remaining_amount - ($amount - $oldIncomeAmount);
            if ($remainingAmount < 0) {
                // Call the error message function statically
                return self::errorRemainingAmountExceedingMessage($clientService);
            }
            $clientService->update(['remaining_amount' => $remainingAmount]);
        }

        return null; // Return null if no error
    }

    public static function errorRemainingAmountExceedingMessage($clientService)
    {
        // Return an error message
        return 'The Expense amount cannot exceed the remaining amount ('.$clientService->remaining_amount.') of the selected client service.';
    }
}
