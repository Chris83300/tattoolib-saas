<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\BookingRequestController;
use App\Http\Controllers\Api\ClientCareSheetController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\FCMController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\AccountingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TattooerController;
use App\Http\Controllers\Api\TattooerPlanningController;
use App\Http\Controllers\Api\TraceabilityController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Public (Sans authentification)
|--------------------------------------------------------------------------
*/

// Routes d'authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes publiques Tattooers (recherche, profils publics)
Route::prefix('tattooers')->group(function () {
    Route::get('/', [TattooerController::class, 'index']);
    Route::get('/{id}', [TattooerController::class, 'show']);
    Route::get('/{id}/portfolio', [TattooerController::class, 'portfolio']);
    Route::get('/{id}/availability', [TattooerController::class, 'availability']);
});

/*
|--------------------------------------------------------------------------
| API Routes - Protected (Avec authentification Sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // ===== USER INFO & LOGOUT =====
    Route::get('/user', function (Request $request) {
        return $request->user()->load(['client', 'tattooer']);
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // ===== FCM TOKEN =====
    Route::post('/fcm-token', [FcmController::class, 'store']);

    // ===== CONVERSATIONS =====
    Route::prefix('conversations')->group(function () {
        Route::get('/', [ConversationController::class, 'index']);
        Route::get('/archived', [ConversationController::class, 'archived']);
        Route::get('/{conversation}', [ConversationController::class, 'show']);
        Route::post('/{conversation}/mark-as-read', [ConversationController::class, 'markAsRead']);
        Route::post('/{conversation}/toggle-mute', [ConversationController::class, 'toggleMute']);
        Route::post('/{conversation}/archive', [ConversationController::class, 'archive']);
        Route::post('/{conversation}/block', [ConversationController::class, 'block']);

        // Messages dans une conversation
        Route::get('/{conversation}/messages', [MessageController::class, 'index']);
        Route::post('/{conversation}/messages', [MessageController::class, 'store']);
        Route::delete('/{conversation}/messages/{message}', [MessageController::class, 'destroy']);
        Route::get('/{conversation}/design-versions', [MessageController::class, 'designVersions']);
        Route::get('/{conversation}/messages/search', [MessageController::class, 'search']);
    });

    // ===== MESSAGES =====
    Route::get('/messages/{message}/download', [MessageController::class, 'downloadAttachment']);

});

// ===== PAYMENTS (Protected routes) =====
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings/{bookingRequest}/payment/deposit',
        [PaymentController::class, 'createDepositPayment']);
});

// ===== WEBHOOKS (PAS d'auth, vérifié par signature) =====
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

// ===== TATTOOERS (Protected routes) =====
    Route::prefix('tattooers/{tattooer}')->group(function () {
        // Gestion du portfolio
        Route::post('/portfolio', [TattooerController::class, 'uploadPortfolioImage']);
        Route::delete('/portfolio/{mediaId}', [TattooerController::class, 'deletePortfolioImage']);

        // Gestion des horaires
        Route::get('/working-hours', [TattooerController::class, 'getWorkingHours']);
        Route::put('/working-hours', [TattooerController::class, 'updateWorkingHours']);
        Route::put('/working-hours/{day}', [TattooerController::class, 'updateDayWorkingHours'])
            ->where('day', '[0-6]');
    });

    // ===== BOOKING REQUESTS =====
    Route::prefix('booking-requests')->group(function () {
        Route::get('/', [BookingRequestController::class, 'index']);
        Route::get('/statistics', [BookingRequestController::class, 'statistics']);
        Route::post('/', [BookingRequestController::class, 'store']);
        Route::get('/{bookingRequest}', [BookingRequestController::class, 'show']);

        // Actions du tatoueur
        Route::post('/{bookingRequest}/accept', [BookingRequestController::class, 'accept']);
        Route::post('/{bookingRequest}/reject', [BookingRequestController::class, 'reject']);
        Route::post('/{bookingRequest}/confirm-deposit', [BookingRequestController::class, 'confirmDeposit']);
        Route::post('/{bookingRequest}/send-design', [BookingRequestController::class, 'sendDesign']);

        // Vérification des demandes expirées (cron)
        Route::get('/check-expired', [BookingRequestController::class, 'checkExpiredRequests']);

        // Actions communes
        Route::post('/{bookingRequest}/confirm-appointment', [BookingRequestController::class, 'confirmAppointment']);
        Route::post('/{bookingRequest}/cancel', [BookingRequestController::class, 'cancel']);

        // Webhook Stripe (à protéger différemment en production)
        Route::post('/{bookingRequest}/mark-deposit-paid', [BookingRequestController::class, 'markDepositPaid']);


    });

    // ===== APPOINTMENTS =====
    Route::prefix('appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::get('/upcoming', [AppointmentController::class, 'upcoming']);
        Route::get('/past', [AppointmentController::class, 'past']);
        Route::get('/statistics', [AppointmentController::class, 'statistics']);
        Route::get('/require-confirmation', [AppointmentController::class, 'requireConfirmation']);
        Route::get('/calendar', [AppointmentController::class, 'calendar']);
        Route::get('/{appointment}', [AppointmentController::class, 'show']);
        Route::post('/{appointment}/confirm-completion', [AppointmentController::class, 'confirmCompletion']);
        Route::post('/{appointment}/report-no-show', [AppointmentController::class, 'reportNoShow']);
        Route::post('/{appointment}/report-issue', [AppointmentController::class, 'reportIssue']);
        Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel']);
        Route::post('/{appointment}/dispute-refund', [AppointmentController::class, 'disputeRefund']);
    });

    // ===== AVAILABILITIES & PLANNING =====
    Route::prefix('availabilities')->group(function () {
        Route::get('/', [AvailabilityController::class, 'index']);
        Route::post('/', [AvailabilityController::class, 'store']);
        Route::put('/{availability}', [AvailabilityController::class, 'update']);
        Route::delete('/{availability}', [AvailabilityController::class, 'destroy']);
        Route::post('/generate-from-working-hours', [AvailabilityController::class, 'generateFromWorkingHours']);
        Route::get('/check', [AvailabilityController::class, 'checkAvailability']);
    });

    // ===== TATTOOER PLANNING (NOUVEAU) =====
    Route::prefix('planning')->group(function () {
        // Dashboard planning tatoueur
        Route::get('/dashboard', [TattooerPlanningController::class, 'dashboard']);

        // Gestion manuelle des créneaux
        Route::post('/block-slot', [TattooerPlanningController::class, 'blockSlot']);
        Route::post('/create-external-appointment', [TattooerPlanningController::class, 'createExternalAppointment']);
        Route::delete('/release-slot/{availability}', [TattooerPlanningController::class, 'releaseSlot']);

        // Consultation des disponibilités (pour clients)
        Route::get('/tattooers/{tattooerId}/available-dates', [TattooerPlanningController::class, 'availableDates']);
        Route::get('/tattooers/{tattooerId}/slots-for-date', [TattooerPlanningController::class, 'slotsForDate']);
    });

    // ===== CLIENT CARE SHEETS =====
    Route::prefix('care-sheets')->group(function () {
        Route::get('/', [ClientCareSheetController::class, 'index']);
        Route::post('/', [ClientCareSheetController::class, 'store']);
        Route::get('/my-sheets', [ClientCareSheetController::class, 'clientIndex']);
        Route::get('/statistics', [ClientCareSheetController::class, 'statistics']);
        Route::get('/{careSheet}', [ClientCareSheetController::class, 'show']);
        Route::put('/{careSheet}', [ClientCareSheetController::class, 'update']);
        Route::post('/{careSheet}/photos', [ClientCareSheetController::class, 'addPhoto']);
        Route::post('/appointments/{appointment}/create', [ClientCareSheetController::class, 'createFromAppointment']);
    });

    // ===== INVENTORY =====
    Route::prefix('inventory')->group(function () {
        Route::get('/', [InventoryController::class, 'index']);
        Route::post('/', [InventoryController::class, 'store']);
        Route::get('/statistics', [InventoryController::class, 'statistics']);
        Route::get('/alerts', [InventoryController::class, 'alerts']);
        Route::get('/{item}', [InventoryController::class, 'show']);
        Route::put('/{item}', [InventoryController::class, 'update']);
        Route::post('/{item}/movement', [InventoryController::class, 'movement']);
        Route::post('/{item}/adjust', [InventoryController::class, 'adjustStock']);
        Route::get('/{item}/movements', [InventoryController::class, 'movements']);
    });

    // ===== ACCOUNTING =====
    Route::prefix('accounting')->group(function () {
        Route::get('/dashboard', [AccountingController::class, 'dashboard']);
        Route::get('/report', [AccountingController::class, 'report']);
        Route::get('/appointment-stats', [AccountingController::class, 'appointmentStats']);
        Route::get('/export', [AccountingController::class, 'export']);

        Route::prefix('transactions')->group(function () {
            Route::get('/', [AccountingController::class, 'transactions']);
            Route::post('/', [AccountingController::class, 'storeTransaction']);
            Route::post('/{transaction}/mark-paid', [AccountingController::class, 'markAsPaid']);
        });
    });

    // ===== TRACEABILITY (TATTOOER UNIQUMENT) =====
    Route::prefix('traceability')->group(function () {
        // Formulaires de consentement
        Route::prefix('consent-forms')->group(function () {
            Route::get('/', [TraceabilityController::class, 'consentForms']);
            Route::post('/', [TraceabilityController::class, 'storeConsentForm']);
            Route::post('/{consentForm}/verify', [TraceabilityController::class, 'verifyConsentForm']);
            Route::post('/{consentForm}/parental-consent', [TraceabilityController::class, 'storeParentalConsent']);
        });

        // Enregistrements de tracabilité
        Route::prefix('records')->group(function () {
            Route::get('/', [TraceabilityController::class, 'traceabilityRecords']);
            Route::post('/', [TraceabilityController::class, 'storeTraceabilityRecord']);
            Route::post('/{record}/materials', [TraceabilityController::class, 'addMaterials']);
            Route::post('/{record}/photos', [TraceabilityController::class, 'addPhotos']);
            Route::post('/{record}/verify', [TraceabilityController::class, 'verifyTraceability']);
            Route::get('/{record}/report', [TraceabilityController::class, 'generateReport']);
        });

        // Statistiques
        Route::get('/statistics', [TraceabilityController::class, 'statistics']);
    });
