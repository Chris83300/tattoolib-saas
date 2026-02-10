<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\StudioArtist;
use App\Models\Tattooer;
use App\Enums\BookingRequestStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingRequestFactory extends Factory
{
    protected $model = BookingRequest::class;

    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'bookable_type' => Tattooer::class,
            'bookable_id' => Tattooer::factory(),
            'tattoo_size' => $this->faker->randomElement(['small', 'medium', 'large']),
            'body_zone' => $this->faker->randomElement(['arm', 'leg', 'back', 'chest', 'neck']),
            'description' => $this->faker->paragraph,
            'estimated_budget' => $this->faker->numberBetween(50, 1000),
            'preferred_timeframe' => $this->faker->randomElement(['asap', '3-4months', '5-6months', '6plus']),
            'preferred_days' => json_encode([$this->faker->numberBetween(1, 7)]),
            'date_notes' => $this->faker->optional()->paragraph,
            'total_deposit_amount' => $this->faker->numberBetween(50, 300),
            'estimated_total_price' => $this->faker->numberBetween(300, 1000),
            'client_payment_deadline_days' => 7,
            'tattooer_design_deadline_days' => 7,
            'client_payment_deadline' => now()->addDays(7),
            'tattooer_design_deadline' => now()->addDays(7),
            'status' => $this->faker->randomElement(BookingRequestStatus::cases()),
        ];
    }

    public function pending()
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequestStatus::PENDING,
        ]);
    }

    public function accepted()
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequestStatus::ACCEPTED,
        ]);
    }

    public function depositRequested()
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequestStatus::DEPOSIT_REQUESTED,
        ]);
    }

    public function depositPaid()
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequestStatus::DEPOSIT_PAID,
        ]);
    }

    public function dateConfirmed()
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequestStatus::DATE_CONFIRMED,
        ]);
    }

    public function completed()
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequestStatus::COMPLETED,
        ]);
    }

    public function cancelled()
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequestStatus::CANCELLED,
        ]);
    }

    public function expired()
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequestStatus::EXPIRED,
        ]);
    }

    public function noShow()
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequestStatus::NO_SHOW,
        ]);
    }
}
