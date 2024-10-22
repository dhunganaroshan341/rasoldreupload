<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function dailyReports()
    {
        $transactions = $this->getIncomeExpenses();
        $latestRows = $this->getLatestRows();
        $recenClients = $this->getRecentClients();

        return [
            'recentClients' => $recenClients,
            'latestRows' => $latestRows,
            'transactions' => $transactions,
        ];
    }

    /**
     * Retrieve income and expense data for the last 7 days.
     *
     * @return array
     */
    private function getIncomeExpenses()
    {
        $dates = $this->getLastSevenDates();

        $incomeData = $this->getFinancialData(Income::class);
        $expenseData = $this->getFinancialData(Expense::class);

        $income = $this->fillMissingData($dates, $incomeData);
        $expenses = $this->fillMissingData($dates, $expenseData);

        return [
            'dates' => $dates,
            'income' => $income,
            'expenses' => $expenses,
        ];
    }

    /**
     * Retrieve financial data (income or expenses) for the last 7 days.
     *
     * @return array
     */
    private function getFinancialData(string $model)
    {
        return $model::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();
    }

    /**
     * Fill missing dates with zero values based on provided financial data.
     *
     * @return array
     */
    private function fillMissingData(array $dates, array $financialData)
    {
        $result = [];
        foreach ($dates as $date) {
            $result[] = $financialData[$date] ?? 0;
        }

        return $result;
    }

    /**
     * Get the last seven dates.
     *
     * @return array
     */
    private function getLastSevenDates()
    {
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }

        return $dates;
    }

    /**
     * Get the latest records from specified models.
     *
     * @return array
     */
    public function getLatestRows()
    {
        return [
            'latest_income' => $this->getLatestRecord(Income::class),
            'latest_expense' => $this->getLatestRecord(Expense::class),
            'latest_client' => $this->getLatestRecord(Client::class),
            'latest_client_service' => $this->getLatestRecord(ClientService::class),
        ];
    }

    /**
     * Retrieve the latest record from a specified model.
     *
     * @return mixed
     */
    private function getLatestRecord(string $model)
    {
        return $model::orderBy('updated_at', 'desc')->first() ?? $model::orderBy('created_at', 'desc')->first();
    }

    private function getRecentClients()
    {
        return Client::orderBy('created_at', 'desc')->take(5)->get();
    }

    // Example function where you get the incomes and expenses
    public static function getClientIncomeExpenseReportByClient($id)
    {
        // Fetch the client by ID
        $client = Client::find($id);

        if (! $client) {
            return 'Client not found'; // Handle case where client is not found
        }

        // Fetch client services and related incomes and expenses
        // Make sure to convert to a Collection
        $incomes = $client->clientServices->flatMap->incomes; // This should be a Collection
        $expenses = $client->clientServices->flatMap->expenses; // This should be a Collection

        // If you are still getting an array, you can convert it to a collection explicitly
        if (is_array($incomes)) {
            $incomes = collect($incomes);
        }
        if (is_array($expenses)) {
            $expenses = collect($expenses);
        }

        // Now you can safely call pluck on these variables
        $report = [
            'incomes' => $incomes,
            'expenses' => $expenses,
            // ... other report data
        ];

        return $report; // Return the generated report
    }
    // public static function getClientIncomeExpenseReportByClient($id)
    // {
    //     // Fetch the client by ID
    //     $client = Client::find($id);

    //     if (! $client) {
    //         return 'Client not found'; // Handle case where client is not found
    //     }

    //     // Fetch client services and related incomes and expenses
    //     $incomes = $client->clientServices->flatMap->incomes->all();  // Collect all incomes related to client services
    //     $expenses = $client->clientServices->flatMap->expenses->all(); // Collect all expenses related to client services

    //     // Calculate total income and total expenses
    //     $totalIncome = collect($incomes)->sum('amount');  // Assuming 'amount' field exists in Income model
    //     $totalExpense = collect($expenses)->sum('amount'); // Assuming 'amount' field exists in Expense model

    //     // Fetch client services with no income recorded (Remaining Client Service Payments)
    //     $remainingClientServicePayments = $client->clientServices->filter(function ($service) {
    //         return $service->incomes->isEmpty(); // Find services with no income recorded
    //     });

    //     // Generate remaining client service payments message
    //     if ($remainingClientServicePayments->isEmpty()) {
    //         $remainingClientServicePaymentMessage = 'All client services have recorded incomes.';
    //     } else {
    //         $remainingClientServicePaymentMessage = 'There are '.$remainingClientServicePayments->count().' client services with no income recorded.';
    //     }

    //     // Prepare the report array
    //     $report = [
    //         'client_name' => $client->name, // Assuming 'name' field exists in Client model
    //         'total_income' => $totalIncome,
    //         'incomes' => $incomes, // All income records as an array
    //         'expenses' => $expenses, // All expense records as an array
    //         'total_expense' => $totalExpense,
    //         'remaining_service_payments' => $remainingClientServicePaymentMessage,
    //         'services_with_no_income' => $remainingClientServicePayments->pluck('name'), // Assuming 'name' field exists in ClientService model
    //     ];

    //     return $report; // Return the generated report
    // }
}
