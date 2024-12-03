<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Correctly call the seeders without square brackets
        $this->call([
            UserSeeder::class,
            ServiceCategorySeeder::class,
            OurServicesSeeder::class,
            ClientSeeder::class,
            ClientServiceSeeder::class,
            ContractSeeder::class,
            MonthSeeder::class,
            ChartsOfAccountSeeder::class,
            OutstandingInvoiceSeeder::class,
        ]);
    }
}
