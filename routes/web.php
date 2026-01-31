<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\TattooerController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Page professionnelle
Route::get('/professionnels', function () {
    return view('professionnels.index');
})->name('professionnels.index');

// Page marketplace
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');

// Routes Tattooer (protégées) - AVANT les routes publiques avec slug
Route::middleware(['auth'])->prefix('tattooer')->name('tattooer.')->group(function () {
    Route::get('/dashboard', [TattooerController::class, 'dashboard'])->name('dashboard');
    Route::get('/requests', [TattooerController::class, 'requests'])->name('requests');
    Route::get('/requests/{project}', [TattooerController::class, 'requestShow'])->name('request.show');
    Route::get('/calendar', [TattooerController::class, 'calendar'])->name('calendar');
    Route::post('/calendar', [TattooerController::class, 'calendarStore'])->name('calendar.store');
    Route::patch('/calendar/{event}', [TattooerController::class, 'calendarUpdate'])->name('calendar.update');
    Route::delete('/calendar/{event}', [TattooerController::class, 'calendarDestroy'])->name('calendar.destroy');
    Route::get('/messages', [TattooerController::class, 'messages'])->name('messages');
    Route::get('/messages/{project}', [TattooerController::class, 'messageShow'])->name('message.show');
    Route::get('/clients', [TattooerController::class, 'clients'])->name('clients');
    Route::get('/clients/{client}', [TattooerController::class, 'clientShow'])->name('client.show');
    Route::get('/portfolio', [TattooerController::class, 'portfolio'])->name('portfolio');
    Route::post('/portfolio/upload', [TattooerController::class, 'portfolioUpload'])->name('portfolio.upload');
    Route::post('/portfolio/before-after/store', [TattooerController::class, 'portfolioBeforeAfterStore'])->name('portfolio.before-after.store');
    Route::delete('/portfolio/{media}', [TattooerController::class, 'portfolioDestroy'])->name('portfolio.destroy');
    Route::delete('/portfolio/before-after/{beforeId}/{afterId}', [TattooerController::class, 'portfolioBeforeAfterDestroy'])->name('portfolio.before-after.destroy');
    Route::get('/settings', [TattooerController::class, 'settings'])->name('settings');
    Route::post('/settings', [TattooerController::class, 'settingsUpdate'])->name('settings.update');
    Route::post('/settings/schedule', [TattooerController::class, 'settingsUpdateSchedule'])->name('settings.update-schedule');
    Route::post('/settings/password', [TattooerController::class, 'settingsUpdatePassword'])->name('settings.update-password');
    Route::get('/payments', [TattooerController::class, 'payments'])->name('payments');
    Route::get('/upgrade', [TattooerController::class, 'upgrade'])->name('upgrade');

    // Anciennes routes Livewire (gardées pour compatibilité)
    Route::get('/profil', App\Livewire\Tattooer\Profile::class)->name('profile');
    Route::get('/profil/edit', App\Livewire\Tattooer\Profile::class)->name('profile.edit');
    Route::get('/portfolio-livewire', App\Livewire\Tattooer\Portfolio::class)->name('portfolio.livewire');
    Route::get('/disponibilites', App\Livewire\Tattooer\Availability::class)->name('availability');
    Route::get('/demandes', App\Livewire\Tattooer\BookingRequests::class)->name('demandes');
    Route::get('/messages-livewire', App\Livewire\Tattooer\Messages::class)->name('messages.livewire');
    Route::get('/reservations', App\Livewire\Tattooer\Bookings::class)->name('bookings');
    Route::get('/clients-livewire', App\Livewire\Tattooer\Clients::class)->name('clients.livewire');
    Route::get('/statistiques', App\Livewire\Tattooer\Analytics::class)->name('analytics');
    Route::get('/parametres', App\Livewire\Tattooer\Settings::class)->name('settings.livewire');
});

// Routes marketplace publiques (APRÈS les routes authentifiées)
Route::get('/tattooer/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.show');
Route::get('/pierceur/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.show'); // Même route

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
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

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

// Routes Tattooer Calendar (publique pour réservations)
Route::get('/tattooer/upgrade', function () {
    return view('professionnels.index');
})->name('upgrade');
Route::get('/tattooer/compliance', function () {
    return view('tattooer.compliance');
})->name('compliance');

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
Route::get('/artistes/{slug}', [MarketplaceController::class, 'show'])
    ->name('marketplace.show.artist');

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

// Routes projets client
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/projets', function () {
        return view('client.projects');
    })->name('projects');

    Route::get('/projets/{project}', function ($project) {
        return view('client.project-show', ['project' => $project]);
    })->name('projects.show');
});

// Routes paiement acompte
Route::middleware(['auth'])->prefix('deposit')->name('deposit.')->group(function () {
    Route::get('/{project}/payment', [App\Http\Controllers\DepositPaymentController::class, 'show'])
        ->name('payment');

    Route::post('/{project}/checkout-session', [App\Http\Controllers\DepositPaymentController::class, 'createCheckoutSession'])
        ->name('checkout.session');

    Route::get('/{project}/success', [App\Http\Controllers\DepositPaymentController::class, 'success'])
        ->name('success');

    Route::get('/{project}/cancel', [App\Http\Controllers\DepositPaymentController::class, 'cancel'])
        ->name('cancel');
});

// Routes formulaires de demande
Route::middleware(['auth'])->prefix('booking-request')->name('booking-request.')->group(function () {
    Route::get('/{bookableId}/{bookableType}', App\Livewire\BookingRequestForm::class)
        ->name('form');
});

// Routes chat projet
Route::middleware(['auth'])->prefix('project/{project}/chat')->name('project.chat.')->group(function () {
    Route::get('/', App\Livewire\ProjectChat::class)->name('show');
});

// Routes demande acompte
Route::middleware(['auth'])->prefix('project/{project}/deposit')->name('project.deposit.')->group(function () {
    Route::get('/request', App\Livewire\RequestDeposit::class)->name('request');
});

require __DIR__.'/settings.php';
