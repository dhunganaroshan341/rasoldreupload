<?php

namespace App\View\Components;

use App\Services\ReportService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ClientServicesList extends Component
{
    /**
     * Create a new component instance.
     */
    public $recentClients;

    public function __construct()
    {
        //
        $reportService = new ReportService;
        $dailyReports = $reportService->dailyReports();
        $recentClients = $dailyReports['recentClients'];
        $this->recentClients = $recentClients;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.client-services-list');
    }
}
