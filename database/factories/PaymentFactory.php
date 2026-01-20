<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'booking_request_id' => function () {
                return \App\Models\BookingRequest::factory()->create()->id;
            },
            'stripe_payment_intent_id' => 'pi_test_' . fake()->unique()->randomNumber(),
            'amount' => $this->faker->randomFloat(50, 500, 2),
            'currency' => 'EUR',
            'status' => 'pending',
            'payment_type' => 'deposit',
            'paid_at' => null,
            'failure_reason' => null,
        ];
    }
}
