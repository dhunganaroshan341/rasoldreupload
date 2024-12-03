<?php

namespace Database\Factories;

use App\Models\OutstandingInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class OutstandingInvoiceFactory extends Factory
{
    protected $model = OutstandingInvoice::class;

    public function definition()
    {
        return [
            'client_service_id' => $this->faker->randomElement([1, 2]),
            'total_amount' => $this->faker->randomFloat(2, 100, 5000),
            'prev_remaining_amount' => $this->faker->randomFloat(2, 0, 1000),
            'all_total' => $this->faker->randomFloat(2, 500, 10000),
            'paid_amount' => $this->faker->randomFloat(2, 100, 5000),
            'remaining_amount' => $this->faker->randomFloat(2, 0, 3000),
            'discount_amount' => $this->faker->randomFloat(2, 0, 500),
            'discount_percentage' => $this->faker->randomFloat(2, 0, 100),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'last_paid' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'remarks' => $this->faker->sentence,
            'bill_number' => $this->faker->unique()->numerify('BILL-#####'),
            // 'status' => $this->faker->randomElement(['pending', 'paid', 'overdue']),
            'all_total_paid' => $this->faker->boolean,
        ];
    }
}
