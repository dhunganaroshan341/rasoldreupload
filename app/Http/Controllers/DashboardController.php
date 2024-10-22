<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $dailyReports = $this->reportService->dailyReports();

        return view('component.dashboard', [
            'recentClients' => $dailyReports['recentClients'],
            'dailyReports' => $dailyReports['transactions'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Placeholder for showing create form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Placeholder for storing newly created resource
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Placeholder for showing specific resource details
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Placeholder for showing edit form
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Placeholder for updating specific resource
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Placeholder for deleting specific resource
    }
}
