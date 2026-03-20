<?php

use App\Models\User;
use Tests\Browser\Data\ScreenshotRoutes;

// ─── GUEST (pas de login) ───────────────────────────────

it('captures all guest pages — desktop', function () {
    foreach (ScreenshotRoutes::guest() as $uri) {
        $slug = trim(str_replace('/', '-', $uri), '-') ?: 'home';
        visit($uri)
            ->screenshot(filename: "guest/desktop_{$slug}", fullPage: true);
    }
})->group('screenshots', 'guest');

it('captures all guest pages — mobile', function () {
    foreach (ScreenshotRoutes::guest() as $uri) {
        $slug = trim(str_replace('/', '-', $uri), '-') ?: 'home';
        visit($uri)->on()->mobile()
            ->screenshot(filename: "guest/mobile_{$slug}", fullPage: true);
    }
})->group('screenshots', 'guest');


// ─── TATTOOER ───────────────────────────────────────────

it('captures all tattooer pages — desktop + mobile', function () {
    $user = User::where('email', 'screenshot-tattooer@test.local')->firstOrFail();
    $this->actingAs($user);

    foreach (ScreenshotRoutes::tattooer() as $uri) {
        $slug = trim(str_replace('/', '-', $uri), '-');
        visit($uri)
            ->screenshot(filename: "tattooer/desktop_{$slug}", fullPage: true);
        visit($uri)->on()->mobile()
            ->screenshot(filename: "tattooer/mobile_{$slug}", fullPage: true);
    }
})->group('screenshots', 'tattooer');


// ─── PIERCEUR ───────────────────────────────────────────

it('captures all pierceur pages — desktop + mobile', function () {
    $user = User::where('email', 'screenshot-pierceur@test.local')->firstOrFail();
    $this->actingAs($user);

    foreach (ScreenshotRoutes::pierceur() as $uri) {
        $slug = trim(str_replace('/', '-', $uri), '-');
        visit($uri)
            ->screenshot(filename: "pierceur/desktop_{$slug}", fullPage: true);
        visit($uri)->on()->mobile()
            ->screenshot(filename: "pierceur/mobile_{$slug}", fullPage: true);
    }
})->group('screenshots', 'pierceur');


// ─── CLIENT ─────────────────────────────────────────────

it('captures all client pages — desktop + mobile', function () {
    $user = User::where('email', 'screenshot-client@test.local')->firstOrFail();
    $this->actingAs($user);

    foreach (ScreenshotRoutes::client() as $uri) {
        $slug = trim(str_replace('/', '-', $uri), '-');
        visit($uri)
            ->screenshot(filename: "client/desktop_{$slug}", fullPage: true);
        visit($uri)->on()->mobile()
            ->screenshot(filename: "client/mobile_{$slug}", fullPage: true);
    }
})->group('screenshots', 'client');


// ─── STUDIO ─────────────────────────────────────────────

it('captures all studio pages — desktop + mobile', function () {
    $user = User::where('email', 'screenshot-studio@test.local')->firstOrFail();
    $this->actingAs($user);

    foreach (ScreenshotRoutes::studio() as $uri) {
        $slug = trim(str_replace('/', '-', $uri), '-');
        visit($uri)
            ->screenshot(filename: "studio/desktop_{$slug}", fullPage: true);
        visit($uri)->on()->mobile()
            ->screenshot(filename: "studio/mobile_{$slug}", fullPage: true);
    }
})->group('screenshots', 'studio');


// ─── ADMIN FILAMENT ─────────────────────────────────────

it('captures all admin pages — desktop', function () {
    $admin = User::where('email', 'screenshot-admin@test.local')->firstOrFail();
    $this->actingAs($admin);

    foreach (ScreenshotRoutes::admin() as $uri) {
        $slug = trim(str_replace('/', '-', $uri), '-') ?: 'admin-dashboard';
        visit($uri)
            ->screenshot(filename: "admin/desktop_{$slug}", fullPage: true);
    }
})->group('screenshots', 'admin');


// ─── DARK MODE (bonus) ──────────────────────────────────

it('captures key pages in dark mode', function () {
    foreach (['/' => 'home', '/marketplace' => 'marketplace', '/auth/login' => 'login'] as $uri => $slug) {
        visit($uri)->inDarkMode()
            ->screenshot(filename: "darkmode/desktop_{$slug}", fullPage: true);
    }
})->group('screenshots', 'darkmode');
