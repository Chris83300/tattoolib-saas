<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'conversation_id' => Conversation::factory(),
            'sender_id' => User::factory(),
            'sender_type' => 'client',
            'content' => $this->faker->paragraph,
            'booking_request_id' => null,
        ];
    }

    public function text()
    {
        return $this->state(fn (array $attributes) => [
            'sender_type' => 'client',
        ]);
    }

    public function image()
    {
        return $this->state(fn (array $attributes) => [
            'attachment_type' => 'image',
            'content' => $this->faker->imageUrl(),
        ]);
    }

    public function file()
    {
        return $this->state(fn (array $attributes) => [
            'attachment_type' => 'document',
            'content' => $this->faker->url,
        ]);
    }
}
