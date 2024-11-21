<?php

namespace App\Providers;

use App\Services\EmployeePayrollService;
use Illuminate\Support\ServiceProvider;

class EmployeePayrollServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        // Bind the service class to the container
        $this->app->singleton(EmployeePayrollService::class, function ($app) {
            return new EmployeePayrollService;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
