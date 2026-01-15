<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingRequestController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\FCMController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\TattooerController;
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
    Route::post('/fcm-token', [FCMController::class, 'store']);

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
        Route::post('/{bookingRequest}/request-deposit', [BookingRequestController::class, 'requestDeposit']);
        Route::post('/{bookingRequest}/send-design', [BookingRequestController::class, 'sendDesign']);

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
});
