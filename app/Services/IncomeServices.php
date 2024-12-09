<?php

namespace App\Services;

use App\Models\ClientService;

class IncomeServices
{
    // Check if the income amount exceeds the remaining amount for the client service
    public static function checkRemainingAmount($amount, $clientService)
    {
        if ($clientService && $amount > $clientService->remaining_amount) {
            return self::errorRemainingAmountExceedingMessage($clientService);
        }

        return null; // No error
    }

    // Update the remaining amount for the client service after a transaction
    public static function updateRemainingAmount(ClientService $clientService, $amount, $oldIncomeAmount = 0)
    {
        if ($clientService) {
            $remainingAmount = $clientService->remaining_amount - ($amount - $oldIncomeAmount);
            if ($remainingAmount < 0) {
                return self::errorRemainingAmountExceedingMessage($clientService);
            }
            $clientService->update(['remaining_amount' => $remainingAmount]);
        }

        return null; // No error
    }

    // Error message when the income exceeds the remaining amount
    public static function errorRemainingAmountExceedingMessage(ClientService $clientService)
    {
        return 'The income amount cannot exceed the remaining amount ('.$clientService->remaining_amount.') of the selected client service.';
    }
}
