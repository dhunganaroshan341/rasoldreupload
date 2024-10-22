<?php

namespace App\Providers;

use App\Models\Contract;
use App\Models\Expense;
use App\Models\Income;
use App\Observers\IncomeObserver;
use App\Observers\ModelObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Contract::observe(ModelObserver::class);

        Income::observe(IncomeObserver::class);
        Expense::observe(ModelObserver::class);
    }
}
