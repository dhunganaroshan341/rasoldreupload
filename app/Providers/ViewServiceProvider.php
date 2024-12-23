<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.main', function ($view) {
            //
            $menuItems = [
                [
                    'name' => 'Configurations',
                    'icon' => 'dw dw-settings',
                    'url' => url('/OurServices'), // Main menu item URL
                    'subItems' => [
                        ['name' => 'Our Services', 'url' => url('/OurServices')],
                        // [
                        //     // 'name' => 'Categories',
                        //     'url' => url('/servicecategory'),
                        //     // 'subItems' => [
                        //     //     ['name' => 'All Categories', 'url' => url('/servicecategory')],
                        //     //     ['name' => 'New Categories', 'url' => url('/servicecategory/create')],
                        //     // ],
                        // ],
                        // ['name' => 'New Service', 'url' => url('/OurServices/create')],
                        ['name' => 'coa', 'url' => url('/coa')],
                        ['name' => 'config Taxes', 'url' => url('/taxconfig')],

                    ],
                ],
                [
                    'name' => 'Clients',
                    'icon' => 'dw dw-user',
                    'url' => url('/clients'), // Main menu item URL
                    'subItems' => [
                        ['name' => 'All Clients', 'url' => url('/clients')],
                        ['name' => 'New Client', 'url' => url('/clients/create')],
                        ['name' => 'New ClientService', 'url' => url('/client/service/create')],

                    ],
                ],
                [
                    'name' => 'Employees',
                    'icon' => 'fas fa-address-card',
                    'url' => url('/employees'), // Main menu item URL
                    'subItems' => [
                        ['name' => 'Employees', 'url' => url('/employees')],
                        ['name' => 'Add New employee', 'url' => url('/employees/create')],
                    ],
                ],

                // [
                //     'name' => 'coa',
                //     'icon' => 'dw dw-list',
                //     'url' => url('/coa'), // Main menu item URL
                //     'subItems' => [

                //         ['name' => 'Add New coa', 'url' => url('/coa/create')],
                //     ],
                // ],

                [
                    'name' => 'Transactions',
                    'icon' => 'dw dw-exchange',
                    'url' => '/transactions', // No main URL
                    'subItems' => [
                        ['name' => 'View Transactions', 'url' => url('/transactions')],
                        [
                            'name' => 'Create Incomes',
                            'url' => url('/incomes/create'),
                            // 'subItems' => [['name' => 'Create New', 'url' => url('/incomes/create')]],
                        ],
                        [
                            'name' => ' Create Expenses',
                            'url' => url('/expenses/create'),
                            // 'subItems' => [['name' => 'Create New', 'url' => url('/expenses/create')]],
                        ],
                    ],
                ],
                [
                    'name' => 'Bill Management',
                    'icon' => 'dw dw-invoice',
                    'url' => '/outstanding-invoices', // No main URL
                    'subItems' => [
                        ['name' => 'Manage invoices', 'url' => url('/outstanding-invoices')],
                    ],
                ],
                [
                    'name' => 'Financial Summary',
                    'icon' => 'dw dw-book',
                    'url' => url('/ledger'), // Main menu item URL
                    'subItems' => [
                        ['name' => 'View Summary', 'url' => url('/ledger')],
                        // [
                        //     'name' => 'Create',
                        //     'subItems' => [['name' => 'Create New', 'url' => url('/ledger/create')]],
                        // ],
                    ],
                ],
            ];

            $view->with('menuItems', $menuItems);
        });
    }
}
