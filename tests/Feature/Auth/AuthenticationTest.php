<?php

use App\Models\User;
use Laravel\Fortify\Features;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->tattooer()->create();

    $response = $this->post(route('login.authenticate'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('tattooer.dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.authenticate'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrorsIn('email');

    $this->assertGuest();
});

test('users with two factor enabled are redirected to two factor challenge', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    $user = User::factory()->withTwoFactor()->create();

    $response = $this->withSession([])
        ->post(route('login.authenticate'), [
            'email' => $user->email,
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

    // L'utilisateur est authentifié mais devrait être redirigé vers le profil
    $response->assertRedirect(route('client.profile'));
    $this->assertAuthenticated();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post('/logout');

    $response->assertRedirect(route('home'));
    $this->assertGuest();
});
