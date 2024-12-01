<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClientService;

class OutstandingInvoiceFactory extends Factory
{
    protected $model = \App\Models\OutstandingInvoice::class;

    public function definition()
    {
        return [
            'client_service_id' => ClientService::factory(),
            'total_amount' => $this->faker->numberBetween(1000, 5000),
            'prev_remaining_amount' => $this->faker->numberBetween(0, 2000),
            'all_total' => $this->faker->numberBetween(1000, 7000),
            'paid_amount' => 0,
            'remaining_amount' => $this->faker->numberBetween(1000, 7000),
            'discount_amount' => 0,
            'discount_percentage' => 0,
            'due_date' => $this->faker->date(),
            'last_paid' => null,
            'remarks' => $this->faker->sentence(),
            'bill_number' => $this->faker->unique()->numerify('INV-#####'),
            'status' => 'pending',
            'all_total_paid' => 0,
        ];
    }
}
