<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\Contract;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all client and service IDs to link contracts
        $clientIds = DB::table('clients')->pluck('id')->toArray();
        $serviceIds = DB::table('our_services')->pluck('id')->toArray();

        // Insert 10 fake contract records
        for ($i = 0; $i < 10; $i++) {
            $startDate = $faker->dateTimeBetween('-1 year', 'now');
            $endDate = (clone $startDate)->modify('+ ' . $faker->numberBetween(3, 12) . ' weeks');

            Contract::updateOrInsert([
                'client_id' => $faker->randomElement($clientIds),
                'service_id' => $faker->randomElement($serviceIds),
                'name' => $faker->sentence(3),
                'duration' => $faker->numberBetween(1, 52),
                'duration_type' => $faker->randomElement(['days', 'weeks', 'months']),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'price' => $faker->randomFloat(2, 50, 5000),
                'advance_amount' => $faker->randomFloat(2, 10, 1000),
                'currency' => 'USD',
                'remarks' => $faker->paragraph,
                'status' => $faker->randomElement(['pending', 'in_progress', 'completed']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
