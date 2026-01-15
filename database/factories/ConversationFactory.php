<?php

namespace Database\Factories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition()
    {
        return [
            'title' => $this->faker->optional()->sentence,
            'is_archived' => false,
            'is_muted' => false,
            'last_message_at' => $this->faker->optional()->dateTime,
        ];
    }

    public function archived()
    {
        return $this->state(fn (array $attributes) => [
            'is_archived' => true,
        ]);
    }

    public function muted()
    {
        return $this->state(fn (array $attributes) => [
            'is_muted' => true,
        ]);
    }
}
