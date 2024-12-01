<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class TransactionServiceProvider
{
    protected $incomes;

    protected $expenses;

    public function __construct()
    {
        $this->incomes = Income::all();
        $this->expenses = Expense::all();
    }

    public function getAllIncomes()
    {
        return $this->incomes->toArray();
    }

    public function getAllExpenses()
    {
        return $this->expenses->toArray();
    }

    public function totalIncome($startDate = null, $endDate = null)
    {
        $incomeQuery = Income::query();

        if ($startDate && $endDate) {
            $incomeQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $incomeQuery->where('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $incomeQuery->where('transaction_date', '<=', $endDate);
        }

        $totalIncome = $incomeQuery->sum('amount');

        return $totalIncome;
    }

    public function totalExpense($startDate = null, $endDate = null)
    {
        $expenseQuery = Expense::query();

        if ($startDate && $endDate) {
            $expenseQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $expenseQuery->where('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $expenseQuery->where('transaction_date', '<=', $endDate);
        }

        $totalExpense = $expenseQuery->sum('amount');

        return $totalExpense;
    }

    public function totalBalance($startDate = null, $endDate = null)
    {
        if (! $startDate || ! $endDate) {
            // If no date range is provided, return total balance for all records
            return $this->totalIncome() - $this->totalExpense();
        }

        // Calculate starting balance up to and including the day before $startDate
        $previousDay = Carbon::parse($startDate)->subDay()->toDateString();
        $startingAmount = $this->getAmount($previousDay)['totalBalanceUpTo'];

        // Calculate total income and total expense within the specified date range
        $totalIncome = $this->totalIncome($startDate, $endDate);
        $totalExpense = $this->totalExpense($startDate, $endDate);

        // Calculate total balance considering starting amount
        $totalBalance = $startingAmount + $totalIncome - $totalExpense;

        return $totalBalance;
    }

    public function getAllData($startDate = null, $endDate = null)
    {
        if (! $startDate || ! $endDate) {
            // If no date range is provided, return total data for all records
            return [
                'totalIncome' => $this->totalIncome(),
                'totalExpense' => $this->totalExpense(),
                'totalBalance' => $this->totalBalance(),
            ];
        }

        // Calculate previous day
        $previousDay = Carbon::parse($startDate)->subDay()->toDateString();

        // Calculate starting amount (total balance up to the previous day)
        $startingAmount = $this->getAmount($previousDay)['totalBalanceUpTo'];

        // Calculate total income and total expense within the date range
        $totalIncome = $this->totalIncome($startDate, $endDate);
        $totalExpense = $this->totalExpense($startDate, $endDate);

        // Calculate total balance considering starting amount
        $totalBalance = $startingAmount + $totalIncome - $totalExpense;

        return [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'totalBalance' => $totalBalance,
        ];
    }

    public function totalIncomeUpTo($date)
    {
        return Income::where('transaction_date', '<=', $date)->sum('amount');
    }

    public function totalExpenseUpTo($date)
    {
        return Expense::where('transaction_date', '<=', $date)->sum('amount');
    }

    public function getAmount($date)
    {
        // Calculate total income up to and including the given date
        $totalIncomeUpTo = $this->totalIncome(null, $date);

        // Calculate total expense up to and including the given date
        $totalExpenseUpTo = $this->totalExpense(null, $date);

        // Calculate total balance up to the given date
        $totalBalanceUpTo = $totalIncomeUpTo - $totalExpenseUpTo;

        return [
            'totalIncomeUpTo' => $totalIncomeUpTo,
            'totalExpenseUpTo' => $totalExpenseUpTo,
            'totalBalanceUpTo' => $totalBalanceUpTo,
        ];
    }

    public function getTransactions($startDate = null, $endDate = null)
    {
        $incomeQuery = Income::query();
        $expenseQuery = Expense::query();

        if ($startDate && $endDate) {
            $incomeQuery->whereBetween('transaction_date', [$startDate, $endDate]);
            $expenseQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        $incomes = $incomeQuery->orderBy('transaction_date')->get();
        $expenses = $expenseQuery->orderBy('transaction_date')->get();

        return $this->mergeTransactions($incomes, $expenses);
    }

    private function mergeTransactions($incomes, $expenses)
    {
        $merged = collect();
        $id = 1;

        foreach ($incomes as $income) {
            $merged->push([
                'transaction_date' => $income->transaction_date,
                'amount' => $income->amount,
                'source' => $income->income_source,
                'type' => 'income',
                'medium' => $income->medium,
                'income_source' => $income->source,
                'client_service' => $income->income_source_id,
                'income_id' => $income->id,
                'expense_id' => null,
                'remarks' => $income->remarks,
                'id' => $id++,
            ]);
        }

        foreach ($expenses as $expense) {
            $merged->push([
                'transaction_date' => $expense->transaction_date,
                'amount' => $expense->amount,
                'source' => $expense->expense_source,
                'type' => 'expense',
                'income_id' => null,
                'medium' => $expense->medium,

                'expense_source' => $expense->source,
                // in use for source i transactions right now
                'client_service' => $expense->client_service_id,
                'expense_id' => $expense->id,
                'remarks' => $expense->remarks,
                'id' => $id++,

            ]);
        }

        return $merged->sortBy('transaction_date')->values()->all();
    }

    public function getExportTransactions($startDate = null, $endDate = null)
    {
        $incomeQuery = Income::query();
        $expenseQuery = Expense::query();

        if ($startDate && $endDate) {
            $incomeQuery->whereBetween('transaction_date', [$startDate, $endDate]);
            $expenseQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        $incomes = $incomeQuery->orderBy('transaction_date')->get();
        $expenses = $expenseQuery->orderBy('transaction_date')->get();

        return [
            'incomes' => $incomes,
            'expenses' => $expenses,
        ];
    }

    public function prepareExportData($startDate, $endDate)
    {
        $transactions = $this->getExportTransactions($startDate, $endDate);
        $totalIncome = $this->totalIncome($startDate, $endDate);
        $totalExpense = $this->totalExpense($startDate, $endDate);
        $totalBalance = $this->totalBalance($startDate, $endDate);
        $startingBalance = $this->getAmount($startDate)['totalBalanceUpTo'];

        $exportData = [
            'Income' => [],
            'Expense' => [],
            'Summary' => [],
        ];

        // Add headers (this should be the first row)
        $exportData['Income'][] = ['Source', 'Date', 'Amount', 'medium'];
        $exportData['Expense'][] = ['Source', 'Date', 'Amount', 'medium'];

        // Add Income details
        // $exportData['Income'][] = ['Income', '', ''];
        foreach ($transactions['incomes'] as $income) {
            $exportData['Income'][] = [$income->clientService->name, $income->transaction_date, $income->amount, $income->medium];
        }
        // $exportData['Income'][] = ['', 'Total', $totalIncome];

        // Add Expense details
        // $exportData['Expense'][] = ['Expense', '', ''];
        foreach ($transactions['expenses'] as $expense) {
            $exportData['Expense'][] = [$expense->clientService->name, $expense->transaction_date, $expense->amount, $expense->medium];
        }
        // $exportData['Expense'][] = ['', 'Total', $totalExpense];

        // Add summary data
        $summaryData = [
            ['', ''],
            ['Opening Balance', $startingBalance],
            ['Total Income', $totalIncome],
            ['Total Expense', $totalExpense],
            ['Total Balance', $totalBalance],
        ];
        $exportData['Summary'] = $summaryData;
        // $exportData['startDate']=$startDate;
        // $exportData['endDate']=$endDate;

        return $exportData;
    }

    public function exportTransactions($transactions)
    {
        $csvData = "Date,Amount,Source,Type\n";

        foreach ($transactions as $transaction) {
            $csvData .= "{$transaction['transaction_date']},{$transaction['amount']},{$transaction['source']},{$transaction['medium']},{$transaction['type']}\n";
        }

        $fileName = 'transactions_'.date('Ymd_His').'.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }

    // newer function for starting amount
    public function getStartingAmount($endDate = null)
    {
        // Calculate total balance up to and including the day before $endDate
        $previousDay = Carbon::parse($endDate)->subDay()->toDateString();
        $startingAmount = $this->getTotalBalance($previousDay);

        return $startingAmount;
    }

    public function getTotalBalance($endDate = null)
    {
        // Calculate total income and total expense up to the provided end date
        $totalIncome = $this->totalIncome(null, $endDate);
        $totalExpense = $this->totalExpense(null, $endDate);

        // Calculate starting amount (total balance up to and including the day before $endDate)
        $startingAmount = $this->getStartingAmount($endDate);

        // Calculate total balance
        $totalBalance = $startingAmount + $totalIncome - $totalExpense;

        return $totalBalance;
    }
}
