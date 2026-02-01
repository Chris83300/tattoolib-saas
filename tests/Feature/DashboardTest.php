<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/client/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->client()->create());

    $this->get('/client/dashboard')->assertStatus(403);
});
