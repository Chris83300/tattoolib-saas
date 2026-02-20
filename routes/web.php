<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\TattooerController;
use App\Http\Controllers\TattooerProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\RegisterController;
use App\Models\BookingRequest;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LogoutController;

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
    Route::get('/profil', [TattooerController::class, 'profile'])->name('profile');
    Route::get('/dashboard', [TattooerController::class, 'dashboard'])->name('dashboard');
    Route::get('/requests', [TattooerController::class, 'requests'])->name('requests');
    Route::get('/requests/{bookingRequest}', [TattooerController::class, 'requestShow'])->name('request.show');
    Route::get('/pricing', [TattooerController::class, 'pricing'])->name('tattooer.pricing');
    Route::get('/requests/{bookingRequest}/accept', function (BookingRequest $bookingRequest) {
        return redirect()->route('tattooer.request.show', $bookingRequest)
            ->with('info', 'Veuillez utiliser la modale d\'acceptation sur cette page.');
    })->name('request.accept.get');
    Route::post('/requests/{bookingRequest}/accept', [TattooerController::class, 'acceptRequest'])->name('request.accept');
    Route::post('/requests/{bookingRequest}/reject', [TattooerController::class, 'requestReject'])->name('request-reject');
    Route::post('/booking-requests/{bookingRequest}/repropose-dates', [TattooerController::class, 'reproposeDates'])->name('booking-requests.repropose-dates');
    Route::get('/calendar', [TattooerController::class, 'calendar'])->name('calendar');
    Route::get('/calendar/events', [TattooerController::class, 'calendarEvents'])->name('calendar.events');
    Route::post('/calendar', [TattooerController::class, 'calendarStore'])->name('calendar.store');
    Route::patch('/calendar/{event}', [TattooerController::class, 'calendarUpdate'])->name('calendar.update');
    Route::delete('/calendar/{event}', [TattooerController::class, 'calendarDestroy'])->name('calendar.destroy');
    Route::get('/messages', [TattooerController::class, 'messages'])->name('messages');
    Route::get('/messages/{bookingRequest}', [TattooerController::class, 'messageShow'])->name('message.show');
    Route::post('/message/{bookingRequest}/send', [TattooerController::class, 'messageSend'])->name('message.send');
    Route::post('/booking-requests/{bookingRequest}/complete', [TattooerController::class, 'completeBooking'])->name('booking-requests.complete');
    Route::post('/booking-requests/{bookingRequest}/no-show', [TattooerController::class, 'markNoShow'])->name('booking-requests.no-show');
    Route::get('/clients', [TattooerController::class, 'clients'])->name('clients');
    Route::get('/clients/create', [TattooerController::class, 'createClient'])->name('clients.create')->middleware('pro');
    Route::post('/clients', [TattooerController::class, 'storeClient'])->name('clients.store')->middleware('pro');
    Route::get('/clients/{client}', [TattooerController::class, 'clientShow'])->name('client.show');
    Route::put('/clients/{client}', [TattooerController::class, 'updateClient'])->name('clients.update')->middleware('pro');
    Route::post('/clients/{client}/consent/upload', [TattooerController::class, 'uploadConsent'])->name('clients.consent.upload')->middleware('pro');
    Route::post('/clients/{client}/consent/store-digital', [TattooerController::class, 'storeDigitalConsent'])->name('clients.consent.store-digital')->middleware('pro');
    Route::delete('/clients/{client}/consent/{media}', [TattooerController::class, 'deleteConsent'])->name('clients.consent.delete')->middleware('pro');
    Route::post('/clients/{client}/traceability', [TattooerController::class, 'storeClientTraceability'])->name('clients.traceability.store')->middleware('pro');
    Route::post('/clients/{client}/photos/upload', [TattooerController::class, 'uploadClientPhotos'])->name('clients.photos.upload')->middleware('pro');
    Route::delete('/clients/{client}/photos/{media}', [TattooerController::class, 'deleteClientPhoto'])->name('clients.photos.delete')->middleware('pro');
    Route::post('/clients/{client}/notes', [TattooerController::class, 'updateClientNotes'])->name('client.update-notes');

    // Consentement tattooer
    Route::post('/consent/{bookingRequest}', [TattooerController::class, 'storeConsent'])
        ->name('tattooer.consent.store');

    // Traçabilité
    Route::post('/traceability/{appointment}', [TattooerController::class, 'storeTraceability'])
        ->name('traceability.store');

    // Clôture RDV
    Route::post('/appointments/{appointment}/complete', [TattooerController::class, 'completeAppointment'])
        ->name('appointments.complete');

    Route::post('/appointments/{appointment}/no-show', [TattooerController::class, 'reportNoShow'])
        ->name('appointments.no-show');

    // Paiement du solde hors plateforme
    Route::post('/bookings/{bookingRequest}/balance/confirm-offline', [App\Http\Controllers\BalancePaymentController::class, 'confirmOffline'])
        ->name('balance-payment.confirm-offline');

    // Médias client
    Route::post('/client/{client}/photos/{bookingRequest}', [TattooerController::class, 'uploadClientTattooPhotos'])
        ->name('client.photos.upload');
    Route::delete('/client/{client}/media/{media}', [TattooerController::class, 'deleteClientMedia'])
        ->name('client.media.delete');

    Route::get('/clients/{clientId}/requests', [TattooerController::class, 'clientRequests'])->name('tattooer.client-requests');
    Route::get('/portfolio', [TattooerController::class, 'portfolio'])->name('portfolio');
    Route::post('/portfolio/upload', [TattooerController::class, 'portfolioUpload'])->name('portfolio.upload');
    Route::post('/portfolio/before-after/store', [TattooerController::class, 'portfolioBeforeAfterStore'])->name('portfolio.before-after.store');
    Route::delete('/portfolio/{media}', [TattooerController::class, 'portfolioDestroy'])->name('portfolio.destroy');
    Route::delete('/portfolio/before-after/{beforeId}/{afterId}', [TattooerController::class, 'portfolioBeforeAfterDestroy'])->name('portfolio.before-after.destroy');
    Route::get('/settings', [TattooerController::class, 'settings'])->name('settings');
    Route::post('/settings', [TattooerController::class, 'settingsUpdate'])->name('settings.update');
    Route::post('/settings/aftercare', [TattooerController::class, 'settingsAftercareUpdate'])->name('settings.aftercare');
    Route::delete('/settings/avatar', [TattooerController::class, 'deleteAvatar'])->name('settings.delete-avatar');
    Route::delete('/settings/banner', [TattooerController::class, 'deleteBanner'])->name('settings.delete-banner');
    Route::post('/settings/schedule', [TattooerController::class, 'settingsUpdateSchedule'])->name('settings.update-schedule');
    Route::post('/settings/password', [TattooerController::class, 'settingsUpdatePassword'])->name('settings.update-password');
    Route::post('/settings/hours', [TattooerController::class, 'updateHours'])->name('tattooer.settings.hours.update');
    Route::get('/payments', [TattooerController::class, 'payments'])->name('payments');

    // ═══ Subscription ═══
    Route::get('/subscription-plans', [SubscriptionController::class, 'plans'])
        ->name('subscription.plans');
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])
        ->name('subscription.subscribe');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])
        ->name('subscription.success');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])
        ->name('subscription.cancel');
    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])
        ->name('subscription.resume');
    Route::get('/subscription/manage', [SubscriptionController::class, 'manage'])
        ->name('subscription.manage');

    Route::get('/compliance', [TattooerController::class, 'compliance'])->name('compliance');

    // Anciennes routes Livewire (gardées pour compatibilité)
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

// Routes spécifiques (AVANT les routes génériques)
Route::get('/tattooer/pending-verification', function () {
    return view('livewire.tattooer.pending-verification-full');
})->middleware(['auth'])->name('tattooer.pending-verification');

// Routes marketplace publiques (APRÈS les routes authentifiées)
Route::get('/tattooer/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.tattooer.show');
Route::get('/pierceur/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.pierceur.show');

// Authentification
Route::get('/login', function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (auth()->check()) {
        $user = auth()->user();
        switch ($user->role) {
            case 'client':
                return redirect()->route('client.profile');
            case 'tattooer':
                return redirect()->route('tattooer.profile');
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
    return view('livewire.auth.login-simple');
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

Route::post('/login', [LoginController::class, 'authenticate'])
    ->middleware('throttle:login')
    ->name('login.authenticate');
Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');

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
    Route::get('/', [App\Http\Controllers\ClientController::class, 'index'])->name('index');
    Route::get('/dashboard', [App\Http\Controllers\ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/booking-requests', [App\Http\Controllers\ClientController::class, 'bookingRequests'])->name('booking-requests');
    Route::get('/booking-requests/{bookingRequest}', [App\Http\Controllers\ClientController::class, 'bookingRequestShow'])->name('booking-request.show');
    Route::post('/booking-requests/{bookingRequest}/select-date', [App\Http\Controllers\ClientController::class, 'selectProposedDate'])->name('booking-request.select-date');
    Route::post('/booking-requests/{bookingRequest}/request-alternatives', [App\Http\Controllers\ClientController::class, 'requestAlternativeDates'])->name('booking-request.request-alternatives');
    Route::get('/chat/{conversation}', [App\Http\Controllers\ClientController::class, 'chat'])->name('chat');
    Route::post('/message/{conversation}/send', [App\Http\Controllers\ClientController::class, 'sendMessage'])->name('message.send');
    Route::get('/reviews', [App\Http\Controllers\ClientController::class, 'reviews'])->name('reviews');
    Route::get('/complaints', [App\Http\Controllers\ClientController::class, 'complaints'])->name('complaints');
    Route::post('/reviews/{bookingRequest}', [App\Http\Controllers\ClientController::class, 'createReview'])->name('reviews.create');
    Route::post('/complaints/{bookingRequest}', [App\Http\Controllers\ClientController::class, 'createComplaint'])->name('complaints.create');
    Route::post('/booking-requests/{bookingRequest}/cancel', [App\Http\Controllers\ClientController::class, 'bookingRequestCancel'])->name('booking-request.cancel');
    Route::delete('/booking-request/{bookingRequest}/delete', [App\Http\Controllers\ClientController::class, 'bookingRequestDelete'])->name('booking-request.delete');
    Route::get('/bookings/{bookingRequest}/balance', [App\Http\Controllers\BalancePaymentController::class, 'show'])
        ->name('balance.show');
});

// Routes Pierceur (protégées — Livewire)
Route::middleware(['auth', 'role:pierceur'])->prefix('pierceur')->name('pierceur.')->group(function () {
    Route::get('/', App\Livewire\Pierceur\Dashboard::class)->name('index');
    Route::get('/dashboard', App\Livewire\Pierceur\Dashboard::class)->name('dashboard');
    Route::get('/settings', App\Livewire\Pierceur\Settings::class)->name('settings');
    Route::get('/profil', App\Livewire\Pierceur\Profile::class)->name('profile');
    Route::get('/messages', App\Livewire\Pierceur\Messages::class)->name('messages');
    Route::get('/calendar', App\Livewire\Pierceur\Calendar::class)->name('calendar');
    Route::get('/demandes', App\Livewire\Pierceur\BookingRequests::class)->name('booking-requests');
});

// Routes Studio (protégées)
// Profil public Studio (accessible sans auth)
Route::get('/studios/{slug}', [App\Http\Controllers\StudioController::class, 'publicProfile'])->name('studio.public');

// Routes Studio (protégées — fusionnées Controller + Livewire)
Route::middleware(['auth', 'role:studio'])->prefix('studio')->name('studio.')->group(function () {
    Route::get('/dashboard', App\Livewire\Studio\Dashboard::class)->name('dashboard');
    Route::get('/profil', App\Livewire\Studio\Profile::class)->name('profile');
    Route::get('/profil/edit', App\Livewire\Studio\Profile::class)->name('profile.edit');
    Route::get('/messages', App\Livewire\Studio\Messages::class)->name('messages');
    Route::get('/parametres', App\Livewire\Studio\Settings::class)->name('settings');
    Route::get('/calendar', App\Livewire\Studio\Calendar::class)->name('calendar');
    Route::get('/artists', [App\Http\Controllers\StudioController::class, 'artists'])->name('artists');
    Route::post('/artists/invite', [App\Http\Controllers\StudioController::class, 'inviteArtist'])->name('artists.invite');
    Route::get('/upgrade', function () {
        return view('professionnels.index');
    })->name('upgrade');
    Route::get('/compliance', function () {
        return view('studio.compliance');
    })->name('studio.compliance');
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
    })->name('studio-artist.compliance');
});

// Page publique artiste (Tattooer OU Pierceur)
Route::get('/artistes/{slug}', [MarketplaceController::class, 'show'])
    ->name('marketplace.show.artist');


Route::get('/pierceur/pending-verification', function () {
    $pierceur = auth()->user()->pierceur;
    return view('livewire.pierceur.pending-verification', compact('pierceur'));
})->middleware(['auth'])->name('pierceur.pending-verification');

Route::get('/studio/pending-verification', function () {
    return view('auth.pending-verification', ['role' => 'studio']);
})->middleware(['auth'])->name('studio.pending-verification');

// Routes profil client
Route::get('/client/profile', [App\Http\Controllers\Client\ProfileController::class, 'index'])
    ->middleware(['auth'])
    ->name('client.profile');

// Route suppression compte tattooer
Route::delete('/tattooer/delete-account', [App\Http\Controllers\Tattooer\AccountController::class, 'delete'])
    ->middleware(['auth'])
    ->name('tattooer.delete-account');


// Routes webhook Stripe (sans CSRF)
Route::post('/webhooks/stripe', [App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])
    ->name('webhooks.stripe');

// Routes paiement acompte
Route::middleware(['auth'])->prefix('deposit')->name('deposit.')->group(function () {
    Route::get('/{bookingRequest}/payment', [App\Http\Controllers\DepositController::class, 'payment'])
        ->name('payment');

    Route::post('/{bookingRequest}/process', [App\Http\Controllers\DepositController::class, 'process'])
        ->name('process');

    Route::get('/{bookingRequest}/success', [App\Http\Controllers\DepositController::class, 'success'])
        ->name('success');

    Route::get('/{bookingRequest}/cancel', [App\Http\Controllers\DepositController::class, 'cancel'])
        ->name('cancel');
});

// Routes formulaires de demande (publiques)
Route::prefix('booking-request')->name('booking-request.')->group(function () {
    Route::get('/success', function () {
        return view('booking-request.success');
    })->name('success');

    Route::get('/{bookableId}/{bookableType}', function (int $bookableId, string $bookableType) {
        return view('booking-request-form', compact('bookableId', 'bookableType'));
    })->name('form')->where([
        'bookableId' => '[0-9]+',
        'bookableType' => 'tattooer|pierceur|piercer|studio-artist',
    ]);
});

// Routes chat conversation
Route::middleware(['auth'])->prefix('conversation/{conversation}/chat')->name('conversation.chat.')->group(function () {
    Route::get('/', [App\Http\Controllers\ClientController::class, 'chat'])->name('show');
});

// Routes auth
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Routes demande acompte
Route::middleware(['auth'])->prefix('booking-request/{bookingRequest}/deposit')->name('booking-request.deposit.')->group(function () {
    Route::get('/request', App\Livewire\RequestDeposit::class)->name('request');
});

require __DIR__.'/settings.php';
