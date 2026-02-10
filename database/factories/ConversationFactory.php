<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Enums\ConversationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition()
    {
        return [
            'subject' => $this->faker->optional()->sentence,
            'status' => $this->faker->randomElement(ConversationStatus::cases()),
            'last_message_at' => $this->faker->optional()->dateTime,
        ];
    }

    public function pending()
    {
        return $this->state(fn (array $attributes) => [
            'status' => ConversationStatus::PENDING,
        ]);
    }

    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'status' => ConversationStatus::ACTIVE,
        ]);
    }

    public function fullAccess()
    {
        return $this->state(fn (array $attributes) => [
            'status' => ConversationStatus::FULL_ACCESS,
        ]);
    }

    public function closing()
    {
        return $this->state(fn (array $attributes) => [
            'status' => ConversationStatus::CLOSING,
        ]);
    }

    public function closed()
    {
        return $this->state(fn (array $attributes) => [
            'status' => ConversationStatus::CLOSED,
        ]);
    }

    public function archived()
    {
        return $this->state(fn (array $attributes) => [
            'status' => ConversationStatus::CLOSED, // archived -> closed dans notre enum
        ]);
    }

    public function muted()
    {
        return $this->state(fn (array $attributes) => [
            'status' => ConversationStatus::CLOSED, // blocked -> closed dans notre enum
        ]);
    }
}
