<?php

namespace App\Services;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ContractServiceProvider
{
    public static function calculateEndDate($startDate, $duration, $durationType)
    {
        $endDate = Carbon::parse($startDate);

        // Add duration based on duration type
        switch ($durationType) {
            case 'hours':
                $endDate->addHours($duration);
                break;
            case 'days':
                $endDate->addDays($duration);
                break;
            case 'weeks':
                $endDate->addWeeks($duration);
                break;
            case 'months':
                $endDate->addMonths($duration);
                break;
            default:
                // Handle unsupported duration type (optional)
                break;
        }

        return $endDate;
    }

    public function CreateNewClient($clientName)
    {
        Client::create([
            'name' => $clientName,
        ]);
    }

    public function createContract($data)
    {
        // Logic to create contract

        // Clear cache after creating or updating a contract
        Cache::forget('daily_reports_'.now()->format('Ymd'));
    }

    public function updateContract($id, $data)
    {
        // Logic to update contract

        // Clear cache after creating or updating a contract
        Cache::forget('daily_reports_'.now()->format('Ymd'));
    }

    public function deleteContract($id)
    {
        // Logic to delete contract

        // Clear cache after deleting a contract
        Cache::forget('daily_reports_'.now()->format('Ymd'));
    }

    //for controller
    public function statusCalculator($date)
    {
        // Parse the input date
        $date = Carbon::parse($date);

        // Get today's date
        $now = Carbon::now(); // This returns the current date and time

        // Determine if the date is in the future, past, or today
        $isFuture = $date->isFuture();
        $isPast = $date->isPast();
        $isToday = $date->isToday();

        // Calculate the difference in days
        $difference = $now->diffInDays($date);

        // Return the results
        return [
            'is_today' => $isToday,
            'is_future' => $isFuture,
            'is_past' => $isPast,
            'difference_in_days' => $difference,
        ];
    }
}
