<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AmazonOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'amazon_order_id' => fake()->isbn10(),
            'store_id' => fake()->randomNumber(5),
            'is_request_sent' => $this->faker->randomElement(['0','1','2']),
            'order_status' => $this->faker->randomElement(['PendingAvailability', 'Pending', 'Unshipped', 'PartiallyShipped', 'Shipped', 'InvoiceUnconfirmed', 'Canceled', 'Unfulfillable']),
            'request_sent_date' => now(),
        ];
    }
}
