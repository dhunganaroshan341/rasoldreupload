<?php

namespace App\View\Components;

use App\Services\ReportService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class IncomeExpenseChartCustom extends Component
{
    public $incomes;

    public $expenses;

    public $id;

    /**
     * Create a new component instance.
     */
    public function __construct($id)
    {
        // Assign the id properly
        $this->id = $id;

        // Fetch the report for the client
        $report = ReportService::getClientIncomeExpenseReportByClient($id);

        // Ensure that 'incomes' and 'expenses' exist in the $report array
        $this->incomes = $report['incomes'] ?? []; // All incomes
        $this->expenses = $report['expenses'] ?? []; // All expenses
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.income-expense-chart-custom', [
            'incomes' => $this->incomes,
            'expenses' => $this->expenses,
        ]);
    }
}
