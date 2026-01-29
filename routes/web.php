<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Page professionnelle
Route::get('/professionnels', function () {
    return view('professionnels.index');
})->name('professionnels.index');

// Page marketplace
Route::get('/marketplace', function () {
    return view('marketplace.index');
})->name('marketplace.index');

// Authentification
Route::get('/login', function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (auth()->check()) {
        $user = auth()->user();
        switch ($user->role) {
            case 'client':
                return redirect()->route('client.profile');
            case 'tattooer':
                return redirect()->route('tattooer.dashboard');
            case 'pierceur':
                return redirect()->route('pierceur.dashboard');
            case 'studio':
                return redirect()->route('studio.dashboard');
            case 'studio_artist':
                return redirect()->route('studio-artist.dashboard');
            default:
                return redirect()->route('home');
        }
    }
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (auth()->check()) {
        $user = auth()->user();
        switch ($user->role) {
            case 'client':
                return redirect()->route('client.profile');
            case 'tattooer':
                return redirect()->route('tattooer.dashboard');
            case 'pierceur':
                return redirect()->route('pierceur.dashboard');
            case 'studio':
                return redirect()->route('studio.dashboard');
            case 'studio_artist':
                return redirect()->route('studio-artist.dashboard');
            default:
                return redirect()->route('home');
        }
    }
    return view('auth.register');
})->name('register');

// Routes d'inscription par rôle (accessibles après choix sur page register)
Route::get('/register/client', function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (auth()->check()) {
        return redirect()->route('client.profile');
    }
    return view('auth.register-client');
})->name('register.client');

Route::get('/register/tattooer', function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (auth()->check()) {
        return redirect()->route('tattooer.dashboard');
    }
    return view('auth.register-tattooer');
})->name('register.tattooer');

Route::get('/register/pierceur', function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (auth()->check()) {
        return redirect()->route('pierceur.dashboard');
    }
    return view('auth.register-pierceur');
})->name('register.pierceur');

Route::get('/register/studio', function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (auth()->check()) {
        return redirect()->route('tattooer.dashboard');
    }
    return view('auth.register-studio');
})->name('register.studio');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Routes POST d'inscription (accessibles par tout le monde)
Route::post('/register/client', function (Illuminate\Http\Request $request) {
    return app(App\Http\Controllers\RegisterController::class)->submitClient($request);
})->name('register.client.submit');

Route::post('/register/tattooer', function (Illuminate\Http\Request $request) {
    return app(App\Http\Controllers\RegisterController::class)->submitTattooer($request);
})->name('register.tattooer.submit');

Route::post('/register/pierceur', function (Illuminate\Http\Request $request) {
    return app(App\Http\Controllers\RegisterController::class)->submitPierceur($request);
})->name('register.pierceur.submit');

Route::post('/register/studio', function (Illuminate\Http\Request $request) {
    return app(App\Http\Controllers\RegisterController::class)->submitStudio($request);
})->name('register.studio.submit');

// Routes Client (protégées)
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/profil', App\Livewire\Client\Profile::class)->name('profile');
    Route::get('/profil/edit', App\Livewire\Client\Profile::class)->name('profile.edit');
    Route::get('/reservations', App\Livewire\Client\Bookings::class)->name('bookings');
    Route::get('/messages', App\Livewire\Client\Messages::class)->name('messages');
    Route::get('/parametres', App\Livewire\Client\Settings::class)->name('settings');
});

// Routes Tattooer (protégées)
Route::middleware(['auth'])->prefix('tattooer')->name('tattooer.')->group(function () {
    Route::get('/dashboard', App\Livewire\Tattooer\Dashboard::class)->name('dashboard');
    Route::get('/profil', App\Livewire\Tattooer\Profile::class)->name('profile');
    Route::get('/profil/edit', App\Livewire\Tattooer\Profile::class)->name('profile.edit');
    Route::get('/portfolio', App\Livewire\Tattooer\Portfolio::class)->name('portfolio');
    Route::get('/disponibilites', App\Livewire\Tattooer\Availability::class)->name('availability');
    Route::get('/demandes', App\Livewire\Tattooer\BookingRequests::class)->name('booking-requests');
    Route::get('/messages', App\Livewire\Tattooer\Messages::class)->name('messages');
    Route::get('/reservations', App\Livewire\Tattooer\Bookings::class)->name('bookings');
    Route::get('/clients', App\Livewire\Tattooer\Clients::class)->name('clients');
    Route::get('/statistiques', App\Livewire\Tattooer\Analytics::class)->name('analytics');
    Route::get('/parametres', App\Livewire\Tattooer\Settings::class)->name('settings');
    Route::get('/calendar', App\Livewire\Tattooer\Calendar::class)->name('calendar');
    Route::get('/upgrade', function () {
        return view('professionnels.index');
    })->name('upgrade');
    Route::get('/compliance', function () {
        return view('tattooer.compliance');
    })->name('compliance');
});

// Routes Pierceur (protégées)
Route::middleware(['auth'])->prefix('pierceur')->name('pierceur.')->group(function () {
    Route::get('/dashboard', App\Livewire\Pierceur\Dashboard::class)->name('dashboard');
    Route::get('/profil', App\Livewire\Pierceur\Profile::class)->name('profile');
    Route::get('/profil/edit', App\Livewire\Pierceur\Profile::class)->name('profile.edit');
    Route::get('/demandes', App\Livewire\Pierceur\BookingRequests::class)->name('booking-requests');
    Route::get('/messages', App\Livewire\Pierceur\Messages::class)->name('messages');
    Route::get('/parametres', App\Livewire\Pierceur\Settings::class)->name('settings');
    Route::get('/calendar', App\Livewire\Pierceur\Calendar::class)->name('calendar');
    Route::get('/upgrade', function () {
        return view('professionnels.index');
    })->name('upgrade');
    Route::get('/compliance', function () {
        return view('pierceur.compliance');
    })->name('compliance');
});

// Routes Studio (protégées)
Route::middleware(['auth'])->prefix('studio')->name('studio.')->group(function () {
    Route::get('/dashboard', App\Livewire\Studio\Dashboard::class)->name('dashboard');
    Route::get('/profil', App\Livewire\Studio\Profile::class)->name('profile');
    Route::get('/profil/edit', App\Livewire\Studio\Profile::class)->name('profile.edit');
    Route::get('/messages', App\Livewire\Studio\Messages::class)->name('messages');
    Route::get('/parametres', App\Livewire\Studio\Settings::class)->name('settings');
    Route::get('/calendar', App\Livewire\Studio\Calendar::class)->name('calendar');
    Route::get('/upgrade', function () {
        return view('professionnels.index');
    })->name('upgrade');
    Route::get('/compliance', function () {
        return view('studio.compliance');
    })->name('compliance');
});

// Routes Studio Artist (protégées)
Route::middleware(['auth'])->prefix('studio-artist')->name('studio-artist.')->group(function () {
    Route::get('/dashboard', App\Livewire\StudioArtist\Dashboard::class)->name('dashboard');
    Route::get('/profil', App\Livewire\StudioArtist\Profile::class)->name('profile');
    Route::get('/profil/edit', App\Livewire\StudioArtist\Profile::class)->name('profile.edit');
    Route::get('/demandes', App\Livewire\StudioArtist\BookingRequests::class)->name('booking-requests');
    Route::get('/messages', App\Livewire\StudioArtist\Messages::class)->name('messages');
    Route::get('/parametres', App\Livewire\StudioArtist\Settings::class)->name('settings');
    Route::get('/calendar', App\Livewire\StudioArtist\Calendar::class)->name('calendar');
    Route::get('/upgrade', function () {
        return view('professionnels.index');
    })->name('upgrade');
    Route::get('/compliance', function () {
        return view('studio-artist.compliance');
    })->name('compliance');
});

// Page publique artiste (Tattooer OU Pierceur)
Route::get('/artistes/{slug}', [ArtistController::class, 'show'])
    ->name('marketplace.show');

// Routes en attente de validation
Route::get('/tattooer/pending-verification', App\Livewire\Tattooer\PendingVerification::class)
    ->middleware(['auth'])->name('tattooer.pending-verification');

Route::get('/pierceur/pending-verification', function () {
    return view('auth.pending-verification', ['role' => 'pierceur']);
})->middleware(['auth'])->name('pierceur.pending-verification');

Route::get('/studio/pending-verification', function () {
    return view('auth.pending-verification', ['role' => 'studio']);
})->middleware(['auth'])->name('studio.pending-verification');

// Routes profil client
Route::get('/client/profile', function () {
    return view('client.profile');
})->middleware(['auth'])->name('client.profile');

require __DIR__.'/settings.php';
