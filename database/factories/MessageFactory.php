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
            'user_id' => User::factory(),
            'content' => $this->faker->paragraph,
            'type' => 'text',
            'is_read' => false,
            'read_at' => null,
        ];
    }

    public function text()
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'text',
        ]);
    }

    public function image()
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'image',
            'content' => $this->faker->imageUrl(),
        ]);
    }

    public function file()
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'file',
            'content' => $this->faker->url,
        ]);
    }

    public function read()
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
            'read_at' => $this->faker->dateTime,
        ]);
    }
}
