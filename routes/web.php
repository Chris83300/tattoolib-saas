<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\TattooerProfileController;
use App\Http\Controllers\Tattooer\TattooerDashboardController;
use App\Http\Controllers\Tattooer\TattooerBookingController;
use App\Http\Controllers\Tattooer\TattooerCalendarController;
use App\Http\Controllers\Tattooer\TattooerMessageController;
use App\Http\Controllers\Tattooer\TattooerClientController;
use App\Http\Controllers\Tattooer\TattooerConsentController;
use App\Http\Controllers\Tattooer\TattooerTraceabilityController;
use App\Http\Controllers\Tattooer\TattooerMediaController;
use App\Http\Controllers\Tattooer\TattooerPortfolioController;
use App\Http\Controllers\Tattooer\TattooerSettingsController;
use App\Http\Controllers\Tattooer\TattooerPaymentController;
use App\Http\Controllers\Tattooer\TattooerAppointmentController;
use App\Http\Controllers\Tattooer\TattooerComplianceController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\RegisterController;
use App\Models\BookingRequest;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LogoutController;
use App\Services\CacheService;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\ClientBookingController;
use App\Http\Controllers\Client\ClientMessageController;
use App\Http\Controllers\Client\ClientSocialController;
use App\Http\Controllers\Studio\StudioDashboardController;
use App\Http\Controllers\Studio\StudioBookingController;
use App\Http\Controllers\Studio\StudioArtistController;
use App\Http\Controllers\Studio\StudioSettingsController;
use App\Http\Controllers\Studio\StudioBillingController;

Route::get('/', function () {
    $cacheService = app(CacheService::class);
    $artists = $cacheService->getMarketplaceListings([]);

    return view('welcome', compact('artists'));
})->name('home');

// Page professionnelle
Route::get('/professionnels', function () {
    return view('professionnels.index');
})->name('professionnels.index');

// Page tarifs publique
Route::get('/tarifs', function () {
    return view('pricing');
})->name('pricing');

// Page marketplace
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');

// Routes Tattooer (protégées) - AVANT les routes publiques avec slug
Route::middleware(['auth', 'artisan.can.operate'])->prefix('tattooer')->name('tattooer.')->group(function () {
    Route::get('/profil', [TattooerDashboardController::class, 'profile'])->name('profile');
    Route::get('/dashboard', [TattooerDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/requests', [TattooerBookingController::class, 'requests'])->name('requests');
    Route::get('/requests/{bookingRequest}', [TattooerBookingController::class, 'requestShow'])->name('request.show');
    Route::get('/pricing', [TattooerDashboardController::class, 'pricing'])->name('pricing');
    Route::get('/requests/{bookingRequest}/accept', function (BookingRequest $bookingRequest) {
        return redirect()->route('tattooer.request.show', $bookingRequest)
            ->with('info', 'Veuillez utiliser la modale d\'acceptation sur cette page.');
    })->name('request.accept.get');
    Route::post('/requests/{bookingRequest}/accept', [TattooerBookingController::class, 'acceptRequest'])->name('request.accept');
    Route::post('/requests/{bookingRequest}/reject', [TattooerBookingController::class, 'requestReject'])->name('request-reject');
    Route::delete('/requests/{bookingRequest}', [TattooerBookingController::class, 'destroyRequest'])->name('requests.destroy');
    Route::patch('/requests/{bookingRequest}/cancel', [TattooerBookingController::class, 'cancelRequest'])->name('requests.cancel');
    Route::post('/booking-requests/{bookingRequest}/repropose-dates', [TattooerBookingController::class, 'reproposeDates'])->name('booking-requests.repropose-dates');
    Route::get('/calendar', [TattooerCalendarController::class, 'calendar'])->name('calendar');
    Route::get('/calendar/events', [TattooerCalendarController::class, 'calendarEvents'])->name('calendar.events');
    Route::post('/calendar', [TattooerCalendarController::class, 'calendarStore'])->name('calendar.store');
    Route::patch('/calendar/{event}', [TattooerCalendarController::class, 'calendarUpdate'])->name('calendar.update');
    Route::delete('/calendar/{event}', [TattooerCalendarController::class, 'calendarDestroy'])->name('calendar.destroy');
    Route::get('/messages', [TattooerMessageController::class, 'messages'])->name('messages');
    Route::get('/messages/{bookingRequest}', [TattooerMessageController::class, 'messageShow'])->name('message.show');
    Route::post('/message/{bookingRequest}/send', [TattooerMessageController::class, 'messageSend'])->name('message.send');
    Route::post('/booking-requests/{bookingRequest}/complete', [TattooerBookingController::class, 'completeBooking'])->name('booking-requests.complete');
    Route::post('/booking-requests/{bookingRequest}/no-show', [TattooerBookingController::class, 'markNoShow'])->name('booking-requests.no-show');
    Route::get('/clients', [TattooerClientController::class, 'clients'])->name('clients');
    Route::get('/clients/create', [TattooerClientController::class, 'createClient'])->name('clients.create')->middleware('pro');
    Route::post('/clients', [TattooerClientController::class, 'storeClient'])->name('clients.store')->middleware('pro');
    Route::get('/clients/{client}', [TattooerClientController::class, 'clientShow'])->name('client.show');
    Route::put('/clients/{client}', [TattooerClientController::class, 'updateClient'])->name('clients.update')->middleware('pro');
    Route::post('/clients/{client}/consent/upload', [TattooerConsentController::class, 'uploadConsent'])->name('clients.consent.upload')->middleware('pro');
    Route::post('/clients/{client}/consent/store-digital', [TattooerConsentController::class, 'storeDigitalConsent'])->name('clients.consent.store-digital')->middleware('pro');
    Route::delete('/clients/{client}/consent/{media}', [TattooerConsentController::class, 'deleteConsent'])->name('clients.consent.delete')->middleware('pro');
    Route::post('/clients/{client}/traceability', [TattooerTraceabilityController::class, 'storeClientTraceability'])->name('clients.traceability.store')->middleware('pro');
    Route::post('/clients/{client}/photos/upload', [TattooerMediaController::class, 'uploadClientPhotos'])->name('clients.photos.upload')->middleware('pro');
    Route::delete('/clients/{client}/photos/{media}', [TattooerMediaController::class, 'deleteClientPhoto'])->name('clients.photos.delete')->middleware('pro');
    Route::post('/clients/{client}/notes', [TattooerClientController::class, 'updateClientNotes'])->name('client.update-notes');

    // Consentement tattooer
    Route::post('/consent/{bookingRequest}', [TattooerConsentController::class, 'storeConsent'])
        ->name('consent.store');

    // Traçabilité
    Route::post('/traceability/{appointment}', [TattooerTraceabilityController::class, 'storeTraceability'])
        ->name('traceability.store');

    // Clôture RDV
    Route::post('/appointments/{appointment}/complete', [TattooerAppointmentController::class, 'completeAppointment'])
        ->name('appointments.complete');

    Route::post('/appointments/{appointment}/no-show', [TattooerAppointmentController::class, 'reportNoShow'])
        ->name('appointments.no-show');

    // Paiement du solde hors plateforme
    Route::post('/bookings/{bookingRequest}/balance/confirm-offline', [App\Http\Controllers\BalancePaymentController::class, 'confirmOffline'])
        ->name('balance-payment.confirm-offline');

    // Médias client
    Route::post('/client/{client}/photos/{bookingRequest}', [TattooerMediaController::class, 'uploadClientTattooPhotos'])
        ->name('client.photos.upload');
    Route::delete('/client/{client}/media/{media}', [TattooerMediaController::class, 'deleteClientMedia'])
        ->name('client.media.delete');

    Route::get('/clients/{clientId}/requests', [TattooerClientController::class, 'clientRequests'])->name('client-requests');
    Route::get('/portfolio', [TattooerPortfolioController::class, 'portfolio'])->name('portfolio');
    Route::post('/portfolio/upload', [TattooerPortfolioController::class, 'portfolioUpload'])->name('portfolio.upload');
    Route::post('/portfolio/before-after/store', [TattooerPortfolioController::class, 'portfolioBeforeAfterStore'])->name('portfolio.before-after.store');
    Route::delete('/portfolio/{media}', [TattooerPortfolioController::class, 'portfolioDestroy'])->name('portfolio.destroy');
    Route::delete('/portfolio/before-after/{beforeId}/{afterId}', [TattooerPortfolioController::class, 'portfolioBeforeAfterDestroy'])->name('portfolio.before-after.destroy');
    Route::get('/settings', [TattooerSettingsController::class, 'settings'])->name('settings');
    Route::post('/settings', [TattooerSettingsController::class, 'settingsUpdate'])->name('settings.update');
    Route::post('/settings/aftercare', [TattooerSettingsController::class, 'settingsAftercareUpdate'])->name('settings.aftercare');
    Route::post('/settings/pricing', [TattooerSettingsController::class, 'settingsPricingUpdate'])->name('settings.pricing');
    Route::delete('/settings/avatar', [TattooerMediaController::class, 'deleteAvatar'])->name('settings.delete-avatar');
    Route::delete('/settings/banner', [TattooerMediaController::class, 'deleteBanner'])->name('settings.delete-banner');
    Route::post('/settings/schedule', [TattooerSettingsController::class, 'settingsUpdateSchedule'])->name('settings.update-schedule');
    Route::post('/settings/password', [TattooerSettingsController::class, 'settingsUpdatePassword'])->name('settings.update-password');
    Route::post('/settings/hours', [TattooerSettingsController::class, 'updateHours'])->name('settings.hours.update');
    Route::get('/settings/export-gdpr', [TattooerSettingsController::class, 'exportGdpr'])->name('gdpr.export')->middleware('throttle:3,60');
    Route::get('/payments', [TattooerPaymentController::class, 'payments'])->name('payments');
    Route::post('/stripe/connect', [TattooerPaymentController::class, 'connectStripe'])->name('stripe.connect');

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

    Route::get('/compliance', [TattooerComplianceController::class, 'compliance'])->name('compliance');
    Route::get('/compliance/documents', [TattooerComplianceController::class, 'complianceDocuments'])->name('compliance.documents');
    Route::post('/compliance/documents', [TattooerComplianceController::class, 'complianceDocumentsUpload'])->name('compliance.documents.upload');
    Route::get('/compliance/documents/{complianceRecord}/view/{field}', [TattooerComplianceController::class, 'complianceDocumentServe'])->name('compliance.documents.serve');
    Route::delete('/compliance/documents/{complianceRecord}', [TattooerComplianceController::class, 'complianceDocumentDelete'])->name('compliance.documents.delete');

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
    return view('auth.pending-verification', ['role' => 'tattooer']);
})->middleware(['auth'])->name('tattooer.pending-verification');

// Routes marketplace publiques (APRÈS les routes authentifiées)
Route::get('/tattooer/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.tattooer.show');
Route::get('/piercer/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.piercer.show');

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
            case 'Piercer':
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

        if ($user->role === 'client') {
            return redirect()->route('client.profile');
        } elseif ($user->role === 'studio') {
            return redirect()->route('studio.dashboard');
        } elseif (in_array($user->role, ['tattooer', 'piercer', 'studio_artist'])) {
            return redirect()->route('tattooer.dashboard');
        }

        return redirect()->route('home');
    }
    return view('auth.register');
})->name('register');

// Route pour sélection de plan (tattooer/piercer)
Route::get('/register/plan', function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('auth.register-plan');
})->name('register.plan');

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
    if (auth()->check()) {
        return redirect()->route('home');
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
Route::middleware(['throttle:10,5'])->group(function () {
    Route::post('/register/client', function (Illuminate\Http\Request $request) {
        return app(App\Http\Controllers\RegisterController::class)->submitClient($request);
    })->name('register.client.submit');

    Route::post('/register/tattooer', function (Illuminate\Http\Request $request) {
        return app(App\Http\Controllers\RegisterController::class)->submitTattooer($request);
    })->name('register.tattooer.submit');

    // Inscription pierceur — Phase 8 : sera adapté
    Route::post('/register/pierceur', function (Illuminate\Http\Request $request) {
        return app(App\Http\Controllers\RegisterController::class)->submitPiercer($request);
    })->name('register.pierceur.submit');

    Route::post('/register/studio', function (Illuminate\Http\Request $request) {
        return app(App\Http\Controllers\RegisterController::class)->submitStudio($request);
    })->name('register.studio.submit');
});

// Routes Client (protégées)
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/', [ClientDashboardController::class, 'dashboard'])->name('index');
    Route::get('/dashboard', [ClientDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/booking-requests', [ClientBookingController::class, 'bookingRequests'])->name('booking-requests');
    Route::get('/booking-requests/{bookingRequest}', [ClientBookingController::class, 'bookingRequestShow'])->name('booking-request.show');
    Route::post('/booking-requests/{bookingRequest}/select-date', [ClientBookingController::class, 'selectProposedDate'])->name('booking-request.select-date');
    Route::post('/booking-requests/{bookingRequest}/request-alternatives', [ClientBookingController::class, 'requestAlternativeDates'])->name('booking-request.request-alternatives');
    Route::get('/chat/{conversation}', [ClientMessageController::class, 'chat'])->name('chat');
    Route::post('/message/{conversation}/send', [ClientMessageController::class, 'sendMessage'])->name('message.send');
    Route::get('/reviews', [ClientSocialController::class, 'reviews'])->name('reviews');
    Route::get('/complaints', [ClientSocialController::class, 'complaints'])->name('complaints');
    Route::post('/complaints', [ClientSocialController::class, 'storeComplaint'])->name('complaints.store');
    Route::post('/reviews/{bookingRequest}', [ClientSocialController::class, 'createReview'])->name('reviews.create');
    Route::post('/complaints/{bookingRequest}', [ClientSocialController::class, 'createComplaint'])->name('complaints.create');
    Route::post('/booking-requests/{bookingRequest}/cancel', [ClientBookingController::class, 'bookingRequestCancel'])->name('booking-request.cancel');
    Route::patch('/requests/{bookingRequest}/cancel', [ClientBookingController::class, 'cancelRequest'])->name('requests.cancel');
    Route::delete('/booking-request/{bookingRequest}/delete', [ClientBookingController::class, 'bookingRequestDelete'])->name('booking-request.delete');
    Route::get('/bookings/{bookingRequest}/balance', [App\Http\Controllers\BalancePaymentController::class, 'show'])
        ->name('balance.show');
    Route::post('/bookings/{bookingRequest}/balance/checkout', [App\Http\Controllers\BalancePaymentController::class, 'checkout'])
        ->name('balance-payment.checkout');
    Route::get('/bookings/{bookingRequest}/balance/success', [App\Http\Controllers\BalancePaymentController::class, 'success'])
        ->name('balance-payment.success');
});

// Routes Pierceur — miroir exact des routes Tattooer, nouveaux controllers
Route::middleware(['auth', 'role:pierceur,Piercer', 'artisan.can.operate'])->prefix('pierceur')->name('pierceur.')->group(function () {
    Route::get('/profil', [TattooerDashboardController::class, 'profile'])->name('profile');
    Route::get('/dashboard', [TattooerDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/requests', [TattooerBookingController::class, 'requests'])->name('requests');
    Route::get('/requests/{bookingRequest}', [TattooerBookingController::class, 'requestShow'])->name('request.show');
    Route::get('/requests/{bookingRequest}/accept', function (\App\Models\BookingRequest $bookingRequest) {
        return redirect()->route('pierceur.request.show', $bookingRequest)
            ->with('info', 'Veuillez utiliser la modale d\'acceptation sur cette page.');
    })->name('request.accept.get');
    Route::post('/requests/{bookingRequest}/accept', [TattooerBookingController::class, 'acceptRequest'])->name('request.accept');
    Route::post('/requests/{bookingRequest}/reject', [TattooerBookingController::class, 'requestReject'])->name('request-reject');
    Route::delete('/requests/{bookingRequest}', [TattooerBookingController::class, 'destroyRequest'])->name('requests.destroy');
    Route::patch('/requests/{bookingRequest}/cancel', [TattooerBookingController::class, 'cancelRequest'])->name('requests.cancel');
    Route::post('/booking-requests/{bookingRequest}/repropose-dates', [TattooerBookingController::class, 'reproposeDates'])->name('booking-requests.repropose-dates');
    Route::get('/calendar', [TattooerCalendarController::class, 'calendar'])->name('calendar');
    Route::get('/calendar/events', [TattooerCalendarController::class, 'calendarEvents'])->name('calendar.events');
    Route::post('/calendar', [TattooerCalendarController::class, 'calendarStore'])->name('calendar.store');
    Route::patch('/calendar/{event}', [TattooerCalendarController::class, 'calendarUpdate'])->name('calendar.update');
    Route::delete('/calendar/{event}', [TattooerCalendarController::class, 'calendarDestroy'])->name('calendar.destroy');
    Route::get('/messages', [TattooerMessageController::class, 'messages'])->name('messages');
    Route::get('/messages/{bookingRequest}', [TattooerMessageController::class, 'messageShow'])->name('message.show');
    Route::post('/message/{bookingRequest}/send', [TattooerMessageController::class, 'messageSend'])->name('message.send');
    Route::post('/booking-requests/{bookingRequest}/complete', [TattooerBookingController::class, 'completeBooking'])->name('booking-requests.complete');
    Route::post('/booking-requests/{bookingRequest}/no-show', [TattooerBookingController::class, 'markNoShow'])->name('booking-requests.no-show');
    Route::get('/clients', [TattooerClientController::class, 'clients'])->name('clients');
    Route::get('/clients/create', [TattooerClientController::class, 'createClient'])->name('clients.create')->middleware('pro');
    Route::post('/clients', [TattooerClientController::class, 'storeClient'])->name('clients.store')->middleware('pro');
    Route::get('/clients/{client}', [TattooerClientController::class, 'clientShow'])->name('client.show');
    Route::put('/clients/{client}', [TattooerClientController::class, 'updateClient'])->name('clients.update')->middleware('pro');
    Route::post('/clients/{client}/consent/upload', [TattooerConsentController::class, 'uploadConsent'])->name('clients.consent.upload')->middleware('pro');
    Route::post('/clients/{client}/consent/store-digital', [TattooerConsentController::class, 'storeDigitalConsent'])->name('clients.consent.store-digital')->middleware('pro');
    Route::delete('/clients/{client}/consent/{media}', [TattooerConsentController::class, 'deleteConsent'])->name('clients.consent.delete')->middleware('pro');

    // Routes subscription pour piercers
    Route::get('/subscription-plans', [SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
    Route::get('/subscription/manage', [SubscriptionController::class, 'manage'])->name('subscription.manage');
    Route::get('/subscribe-from-trial', [SubscriptionController::class, 'subscribeFromTrial'])->name('subscription.subscribeFromTrial');
    Route::post('/clients/{client}/traceability', [TattooerTraceabilityController::class, 'storeClientTraceability'])->name('clients.traceability.store')->middleware('pro');
    Route::post('/clients/{client}/photos/upload', [TattooerMediaController::class, 'uploadClientPhotos'])->name('clients.photos.upload')->middleware('pro');
    Route::delete('/clients/{client}/photos/{media}', [TattooerMediaController::class, 'deleteClientPhoto'])->name('clients.photos.delete')->middleware('pro');
    Route::post('/clients/{client}/notes', [TattooerClientController::class, 'updateClientNotes'])->name('client.update-notes');
    Route::get('/clients/{clientId}/requests', [TattooerClientController::class, 'clientRequests'])->name('client-requests');
    Route::post('/consent/{bookingRequest}', [TattooerConsentController::class, 'storeConsent'])->name('consent.store');
    Route::post('/traceability/{appointment}', [TattooerTraceabilityController::class, 'storeTraceability'])->name('traceability.store');
    Route::post('/appointments/{appointment}/complete', [TattooerAppointmentController::class, 'completeAppointment'])->name('appointments.complete');
    Route::post('/appointments/{appointment}/no-show', [TattooerAppointmentController::class, 'reportNoShow'])->name('appointments.no-show');
    Route::post('/bookings/{bookingRequest}/balance/confirm-offline', [App\Http\Controllers\BalancePaymentController::class, 'confirmOffline'])->name('balance-payment.confirm-offline');
    Route::post('/client/{client}/photos/{bookingRequest}', [TattooerMediaController::class, 'uploadClientTattooPhotos'])->name('client.photos.upload');
    Route::delete('/client/{client}/media/{media}', [TattooerMediaController::class, 'deleteClientMedia'])->name('client.media.delete');
    Route::get('/portfolio', [TattooerPortfolioController::class, 'portfolio'])->name('portfolio');
    Route::post('/portfolio/upload', [TattooerPortfolioController::class, 'portfolioUpload'])->name('portfolio.upload');
    Route::delete('/portfolio/{media}', [TattooerPortfolioController::class, 'portfolioDestroy'])->name('portfolio.destroy');
    Route::get('/settings', [TattooerSettingsController::class, 'settings'])->name('settings');
    Route::post('/settings', [TattooerSettingsController::class, 'settingsUpdate'])->name('settings.update');
    Route::post('/settings/aftercare', [TattooerSettingsController::class, 'settingsAftercareUpdate'])->name('settings.aftercare');
    Route::post('/settings/pricing', [TattooerSettingsController::class, 'settingsPricingUpdate'])->name('settings.pricing');
    Route::delete('/settings/avatar', [TattooerMediaController::class, 'deleteAvatar'])->name('settings.delete-avatar');
    Route::delete('/settings/banner', [TattooerMediaController::class, 'deleteBanner'])->name('settings.delete-banner');
    Route::post('/settings/schedule', [TattooerSettingsController::class, 'settingsUpdateSchedule'])->name('settings.update-schedule');
    Route::post('/settings/password', [TattooerSettingsController::class, 'settingsUpdatePassword'])->name('settings.update-password');
    Route::post('/settings/hours', [TattooerSettingsController::class, 'updateHours'])->name('settings.hours.update');
    Route::get('/settings/export-gdpr', [TattooerSettingsController::class, 'exportGdpr'])->name('gdpr.export')->middleware('throttle:3,60');
    Route::get('/payments', [TattooerPaymentController::class, 'payments'])->name('payments');
    Route::post('/stripe/connect', [TattooerPaymentController::class, 'connectStripe'])->name('stripe.connect');
    Route::get('/compliance', [TattooerComplianceController::class, 'compliance'])->name('compliance');
    // Anciennes routes Livewire (miroir du groupe tattooer)
    Route::get('/profil/edit', App\Livewire\Tattooer\Profile::class)->name('profile.edit');
    Route::get('/disponibilites', App\Livewire\Tattooer\Availability::class)->name('availability');
    Route::get('/demandes', App\Livewire\Tattooer\BookingRequests::class)->name('demandes');
    Route::get('/reservations', App\Livewire\Tattooer\Bookings::class)->name('bookings');
    Route::get('/statistiques', App\Livewire\Tattooer\Analytics::class)->name('analytics');
    Route::get('/messages-livewire', App\Livewire\Tattooer\Messages::class)->name('messages.livewire');
});

// Routes Studio (protégées)
// Profil public Studio (accessible sans auth)
Route::get('/studios/{slug}', [StudioDashboardController::class, 'publicProfile'])->name('studio.public');
Route::get('/salon/{slug}', [StudioDashboardController::class, 'publicProfile'])->name('studio.public.show');

// Invitation artiste (publique, avec token)
Route::get('/studio/invitation/{token}', [StudioArtistController::class, 'acceptInvitation'])->name('studio.invitation.accept');
Route::post('/studio/invitation/{token}', [StudioArtistController::class, 'processInvitation'])->name('studio.invitation.process');

// Routes Studio (protégées — fusionnées Controller + Livewire)
Route::middleware(['auth', 'role:studio', \App\Http\Middleware\EnsureStudioCanOperate::class])->prefix('studio')->name('studio.')->group(function () {
    Route::get('/dashboard', App\Livewire\Studio\Dashboard::class)->name('dashboard');
    Route::get('/profil', App\Livewire\Studio\Profile::class)->name('profile');
    Route::get('/profil/edit', App\Livewire\Studio\Profile::class)->name('profile.edit');
    Route::get('/messages', App\Livewire\Studio\Messages::class)->name('messages');
    Route::get('/parametres', App\Livewire\Studio\Settings::class)->name('settings');
    Route::put('/parametres', [StudioSettingsController::class, 'updateSettings'])->name('settings.update');
    Route::get('/calendar', App\Livewire\Studio\Calendar::class)->name('calendar');
    // Artistes
    Route::get('/artists', [StudioArtistController::class, 'artists'])->name('artists');
    Route::get('/artists/create', [StudioArtistController::class, 'createArtist'])->name('artists.create');
    Route::post('/artists', [StudioArtistController::class, 'storeArtist'])->name('artists.store');
    Route::post('/artists/invite', [StudioArtistController::class, 'inviteArtist'])->name('artists.invite');
    Route::get('/artists/{studioArtist}', [StudioArtistController::class, 'artistShow'])->name('artists.show');
    Route::delete('/artists/{studioArtist}', [StudioArtistController::class, 'removeArtist'])->name('artists.remove');
    Route::put('/artists/{studioArtist}/toggle', [StudioArtistController::class, 'toggleArtist'])->name('artists.toggle');
    // Planning
    Route::get('/planning', [StudioDashboardController::class, 'planning'])->name('planning');
    Route::get('/planning/events', [StudioDashboardController::class, 'planningEvents'])->name('planning.events');
    // Demandes
    Route::get('/demandes', [StudioBookingController::class, 'requests'])->name('requests');
    Route::get('/demandes/{bookingRequest}', [StudioBookingController::class, 'demandeShow'])->name('demandes.show');
    // Fiches clients
    Route::get('/clients', [StudioArtistController::class, 'clients'])->name('clients.index');
    Route::get('/clients/{client}', [StudioArtistController::class, 'clientShow'])->name('clients.show');
    Route::put('/clients/{client}', [StudioArtistController::class, 'clientUpdate'])->name('clients.update');
    // Billing & Abonnement
    Route::get('/billing', [StudioBillingController::class, 'billing'])->name('billing');
    Route::get('/subscribe', [StudioBillingController::class, 'billing'])->name('subscribe');
    Route::post('/subscribe', [StudioBillingController::class, 'subscribe'])->name('subscribe.post');
    Route::post('/subscription/cancel', [StudioBillingController::class, 'cancelSubscription'])->name('subscription.cancel');
    Route::post('/subscription/resume', [StudioBillingController::class, 'resumeSubscription'])->name('subscription.resume');
    Route::post('/subscription/sync', [StudioBillingController::class, 'syncSubscription'])->name('subscription.sync');
    // Compatibilité ancienne URL /souscrire
    Route::get('/souscrire', [StudioBillingController::class, 'showSubscribe'])->name('subscribe.legacy');
    Route::post('/souscrire', [StudioBillingController::class, 'processSubscribe'])->name('subscribe.legacy.process');
    Route::get('/stats', [StudioDashboardController::class, 'stats'])->name('stats');
    Route::post('/stripe/connect', [StudioBillingController::class, 'connectStripe'])->name('stripe.connect');
    Route::get('/upgrade', function () {
        return view('professionnels.index');
    })->name('upgrade');
    Route::get('/compliance', function () {
        return view('studio.compliance');
    })->name('compliance');
});

// Routes Studio Artist (protégées)
Route::middleware(['auth', App\Http\Middleware\EnsureStudioCanOperate::class])->prefix('studio-artist')->name('studio-artist.')->group(function () {
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

// Page publique artiste (Tattooer OU Piercer)
Route::get('/artistes/{slug}', [MarketplaceController::class, 'show'])
    ->name('marketplace.show.artist');


// Pierceur pending-verification — Phase 8 : à réécrire avec vue unifiée
Route::get('/pierceur/pending-verification', function () {
    return view('auth.pending-verification', ['role' => 'pierceur']);
})->middleware(['auth'])->name('pierceur.pending-verification');

Route::get('/studio/pending-verification', function () {
    return view('auth.pending-verification', ['role' => 'studio']);
})->middleware(['auth'])->name('studio.pending-verification');

// Routes profil client
Route::get('/client/profile', [App\Http\Controllers\Client\ProfileController::class, 'index'])
    ->middleware(['auth'])
    ->name('client.profile');

Route::get('/client/settings', [App\Http\Controllers\Client\ProfileController::class, 'settings'])
    ->middleware(['auth'])
    ->name('client.settings');

Route::get('/client/settings/export-gdpr', [App\Http\Controllers\Client\ProfileController::class, 'exportGdpr'])
    ->middleware(['auth', 'throttle:3,60'])
    ->name('client.gdpr.export');

Route::post('/client/settings/avatar', [App\Http\Controllers\Client\ProfileController::class, 'updateAvatar'])
    ->middleware(['auth'])
    ->name('client.settings.update-avatar');

Route::delete('/client/settings/avatar', [App\Http\Controllers\Client\ProfileController::class, 'deleteAvatar'])
    ->middleware(['auth'])
    ->name('client.settings.delete-avatar');

Route::get('/client/messages', [App\Http\Controllers\Client\ProfileController::class, 'messages'])
    ->middleware(['auth'])
    ->name('client.messages');

Route::get('/client/bookings', [App\Http\Controllers\Client\ProfileController::class, 'bookings'])
    ->middleware(['auth'])
    ->name('client.bookings');

// Suppression de compte — tous profils
Route::delete('/tattooer/delete-account', [App\Http\Controllers\Tattooer\AccountController::class, 'destroyAccount'])
    ->middleware(['auth'])->name('tattooer.delete-account');
Route::delete('/pierceur/delete-account', [App\Http\Controllers\Tattooer\AccountController::class, 'destroyAccount'])
    ->middleware(['auth'])->name('pierceur.delete-account');
Route::delete('/studio/delete-account', [StudioBillingController::class, 'destroyAccount'])
    ->middleware(['auth'])->name('studio.delete-account');
Route::delete('/client/delete-account', [App\Http\Controllers\Client\ClientAccountController::class, 'destroyAccount'])
    ->middleware(['auth'])->name('client.delete-account');

// Routes webhook Stripe (sans CSRF)
Route::post('/webhooks/stripe', [App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])
    ->middleware(['throttle:60,1'])
    ->name('webhooks.stripe');

// ─── Stripe Connect — Artiste indépendant (Tattooer / Piercer) ──────────────
// Utilisé par HasStripeConnect::generateStripeConnectLink()
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/stripe/connect/return/{artist}', [App\Http\Controllers\StripeConnectController::class, 'returnFromOnboarding'])
        ->name('stripe.connect.return');

    Route::get('/stripe/connect/refresh/{artist}', [App\Http\Controllers\StripeConnectController::class, 'refreshOnboarding'])
        ->name('stripe.connect.refresh');
});

// ─── Stripe Connect — Artiste de studio ─────────────────────────────────────
// Utilisé par StripeService::createConnectOnboardingLink()
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/studio/artist/stripe/return', [App\Http\Controllers\StripeConnectController::class, 'studioArtistReturn'])
        ->name('studio.artist.stripe.return');

    Route::get('/studio/artist/stripe/refresh', [App\Http\Controllers\StripeConnectController::class, 'studioArtistRefresh'])
        ->name('studio.artist.stripe.refresh');
});

// ─── Stripe Connect — Propriétaire de studio ────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/studio/stripe/return', [App\Http\Controllers\StripeConnectController::class, 'studioOwnerReturn'])
        ->name('studio.stripe.return');

    Route::get('/studio/stripe/refresh', [App\Http\Controllers\StripeConnectController::class, 'studioOwnerRefresh'])
        ->name('studio.stripe.refresh');
});

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
        'bookableType' => 'tattooer|Piercer|piercer|studio-artist',
    ]);
});

// Routes chat conversation
Route::middleware(['auth'])->prefix('conversation/{conversation}/chat')->name('conversation.chat.')->group(function () {
    Route::get('/', [ClientMessageController::class, 'chat'])->name('show');
});

// Routes admin conversations — redirige vers Filament ConversationResource
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->prefix('admin/conversation')->name('admin.conversation.')->group(function () {
    Route::get('/{conversation}', function ($conversation) {
        return redirect('/admin/conversations/' . $conversation);
    })->name('show');
});

// Routes admin documents de conformité
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->prefix('admin/compliance')->name('admin.compliance.')->group(function () {
    Route::get('/documents/{complianceRecord}/view/{field}', [App\Http\Controllers\Tattooer\TattooerComplianceController::class, 'complianceDocumentServe'])->name('documents.serve');
});

// Routes consentement client
Route::middleware(['auth'])->prefix('consent')->name('consent.')->group(function () {
    Route::post('/{bookingRequest}', [ClientSocialController::class, 'storeConsent'])->name('store');
});

// Routes auth
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    // Rate limiting anti-énumération email : 5 tentatives par 10 minutes
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:5,10');
    Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('password.update')->middleware('throttle:5,10');
});

// Routes demande acompte
Route::middleware(['auth'])->prefix('booking-request/{bookingRequest}/deposit')->name('booking-request.deposit.')->group(function () {
    Route::get('/request', App\Livewire\RequestDeposit::class)->name('request');
});

// Pages légales — accessibles sans authentification
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/mentions-legales', [App\Http\Controllers\LegalController::class, 'mentionsLegales'])->name('mentions-legales');
    Route::get('/cgu', [App\Http\Controllers\LegalController::class, 'cgu'])->name('cgu');
    Route::get('/cgv-artistes', [App\Http\Controllers\LegalController::class, 'cgvArtistes'])->name('cgv-artistes');
    Route::get('/cgv-clients', [App\Http\Controllers\LegalController::class, 'cgvClients'])->name('cgv-clients');
    Route::get('/politique-de-confidentialite', [App\Http\Controllers\LegalController::class, 'politiqueConfidentialite'])->name('politique-confidentialite');
    Route::get('/politique-de-cookies', [App\Http\Controllers\LegalController::class, 'politiqueCookies'])->name('politique-cookies');
});

// Export PDF — réservé aux professionnels authentifiés
Route::middleware(['auth'])->prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/care-sheet/{careSheet}', [App\Http\Controllers\PdfExportController::class, 'careSheet'])->name('care-sheet');
    Route::get('/consent-form/{consentForm}', [App\Http\Controllers\PdfExportController::class, 'consentForm'])->name('consent-form');
    Route::get('/parental-consent/{parentalConsent}', [App\Http\Controllers\PdfExportController::class, 'parentalConsent'])->name('parental-consent');
    Route::get('/traceability/{traceabilityRecord}', [App\Http\Controllers\PdfExportController::class, 'traceabilityRecord'])->name('traceability');
    Route::get('/client-summary/{client}', [App\Http\Controllers\PdfExportController::class, 'clientSummary'])->name('client-summary');
    Route::get('/receipt/{booking}', [App\Http\Controllers\PdfExportController::class, 'receipt'])->name('receipt');
});

require __DIR__.'/settings.php';
