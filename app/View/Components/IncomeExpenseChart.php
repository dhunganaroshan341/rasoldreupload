<?php

namespace App\View\Components;

use App\Services\ReportService;
use Illuminate\View\Component;

class IncomeExpenseChart extends Component
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function render()
    {
        $dailyReports = $this->reportService->dailyReports();

        return view('components.income-expense-chart', [
            'transaction_dates' => $dailyReports['transactions']['dates'],
            'incomes' => $dailyReports['transactions']['incomes'],
            'expenses' => $dailyReports['transactions']['expenses'],
        ]);
    }
}
