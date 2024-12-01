<?php 


namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientServiceFactory extends Factory
{
    protected $model = \App\Models\ClientService::class;

    public function definition()
    {
        return [
            'billing_start_date' => $this->faker->date(),
            'billing_end_date' => $this->faker->date(),
            'billing_period_frequency' => $this->faker->randomElement(['monthly', 'quarterly', 'annually']),
            'advance_paid' => $this->faker->numberBetween(0, 1000),
            'remaining_amount' => $this->faker->numberBetween(0, 5000),
            'outsourced_amount' => $this->faker->numberBetween(0, 2000),
            'amount' => $this->faker->numberBetween(1000, 10000),
            'service_id' => $this->faker->numberBetween(1, 10),
            'client_id' => $this->faker->numberBetween(1, 10),
            'hosting_service' => $this->faker->boolean(),
            'email_service' => $this->faker->boolean(),
            'name' => $this->faker->company(),
            'duration' => $this->faker->numberBetween(1, 24), // months
            'duration_type' => 'months',
            'description' => $this->faker->sentence(),
        ];
    }
}
