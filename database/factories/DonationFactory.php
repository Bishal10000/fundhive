<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DonationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'campaign_id' => Campaign::factory(),

            'donor_name' => $this->faker->name(),
            'donor_email' => $this->faker->safeEmail(),

            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'amount' => $this->faker->numberBetween(100, 5000),

            'payment_method' => $this->faker->randomElement([
                'esewa',
                'khalti',
                'card',
                'bank'
            ]),

            'status' => $this->faker->randomElement(['pending', 'completed']),
            'is_fraud' => false,

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
