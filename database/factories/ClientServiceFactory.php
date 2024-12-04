<?php

namespace Database\Factories;

use App\Models\ClientService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientServiceFactory extends Factory
{
    protected $model = ClientService::class;

    public function definition()
    {
        // Generate a billing start date from the previous year to the current year
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');

        // Calculate the billing end date based on the start date and duration (1 to 12 months)
        $duration = $this->faker->numberBetween(1, 12); // Duration between 1 to 12 months
        $endDate = Carbon::parse($startDate)->addMonths($duration);

        return [
            'billing_start_date' => $startDate->format('Y-m-d'),
            'billing_end_date' => $endDate->format('Y-m-d'),
            'billing_period_frequency' => $this->faker->randomElement(['monthly', 'quarterly', 'annually']),
            'advance_paid' => $this->faker->numberBetween(0, 1000),
            'remaining_amount' => $this->faker->numberBetween(0, 5000),
            'outsourced_amount' => $this->faker->numberBetween(0, 2000),
            'amount' => $this->faker->numberBetween(1000, 10000),
            'service_id' => $this->faker->numberBetween(1, 3),
            'client_id' => $this->faker->numberBetween(1, 4),
            'hosting_service' => $this->faker->boolean(),
            'email_service' => $this->faker->boolean(),
            'name' => $this->faker->company(),
            'duration' => $duration, // duration between 1 and 12 months
            'duration_type' => 'months',
            'description' => $this->faker->sentence(),
        ];
    }

    /**
     * Create unique ClientService, ensuring no duplicates
     */
    public function createUnique()
    {
        // Ensure uniqueness based on client_id and service_id
        return ClientService::firstOrCreate([
            'client_id' => $this->faker->numberBetween(1, 4),
            'service_id' => $this->faker->numberBetween(1, 3),
        ], $this->definition());
    }
}
