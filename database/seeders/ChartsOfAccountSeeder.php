<?php

namespace Database\Seeders;

use App\Models\ChartsOfAccount;
use Illuminate\Database\Seeder;

class ChartsOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $chartsOfAccounts = [
            ['name' => 'assets', 'type' => 'asset', 'description' => 'this is the asset description'],
            ['name' => 'liabilites', 'type' => 'liabilites', 'description' => 'this is the liabilities description'],
            ['name' => 'incomes', 'type' => 'asset', 'description' => 'this is the income description'],
            ['name' => 'expenses', 'type' => 'asset', 'description' => 'this is the expense description'],
            ['name' => 'employee_expenses', 'type' => 'liability', 'description' => 'this is the expense of employee like salary, bonuses description'],

        ];
        foreach ($chartsOfAccounts as $chartsOfAccount) {
            ChartsOfAccount::updateOrCreate(['name' => $chartsOfAccount['name'], 'type' => $chartsOfAccount['type'], 'description' => $chartsOfAccount['description']]);
        }
    }
}
