<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

test('reset password link screen can be rendered', function () {
    $response = $this->get(route('password.request'));

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->withoutMiddleware()
        ->post(route('password.request'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    $user = User::factory()->create();

    // Créer un token valide
    $token = Password::createToken($user);

    $response = $this->withSession([])
        ->get(route('password.reset', $token));
    $response->assertStatus(200);
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->withoutMiddleware()
        ->post(route('password.request'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = $this->withSession([])
            ->post(route('password.update'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login', absolute: false));

        return true;
    });
});
