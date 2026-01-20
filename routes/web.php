<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Page publique artiste (Tattooer OU StudioArtist)
Route::get('/artists/{slug}', [ArtistController::class, 'show'])
    ->name('artists.show');

require __DIR__.'/settings.php';
