<?php

namespace Database\Seeders;

use App\Models\ClientService;
use Illuminate\Database\Seeder;

class ClientServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 unique client services, ensuring no duplicates
        foreach (range(1, 10) as $index) {
            ClientService::factory()->createUnique();
        }
    }
}
