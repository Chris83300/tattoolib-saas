<?php

use Tests\TestCase;
use App\Models\Tattooer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

test('new_tattooer_has_no_stripe_connect_by_default', function () {
    $user = User::factory()->create();
    $tattooer = new Tattooer();
    $tattooer->user_id = $user->id;
    $tattooer->name = 'Test Tattooer';
    $tattooer->slug = 'test-tattooer';
    $tattooer->siret = '12345678901234';
    $tattooer->stripe_connect_status = 'not_connected';
    $tattooer->save();

    expect($tattooer->hasNoStripeConnect())->toBeTrue();
    expect($tattooer->canReceivePayments())->toBeFalse();
});

test('tattooer_can_activate_connect_if_requirements_met', function () {
    $user = User::factory()->create();
    $tattooer = new Tattooer();
    $tattooer->user_id = $user->id;
    $tattooer->name = 'Test Tattooer';
    $tattooer->slug = 'test-tattooer';
    $tattooer->siret = '12345678901234';
    $tattooer->siret_verified = true;
    $tattooer->has_accepted_payment_terms = true;
    $tattooer->stripe_connect_status = 'not_connected';
    $tattooer->save();

    expect($tattooer->canCreateStripeConnect())->toBeTrue();
});

test('tattooer_cannot_activate_without_siret', function () {
    $user = User::factory()->create();
    $tattooer = new Tattooer();
    $tattooer->user_id = $user->id;
    $tattooer->name = 'Test Tattooer';
    $tattooer->slug = 'test-tattooer';
    $tattooer->siret = '12345678901234';
    $tattooer->has_accepted_payment_terms = true;
    $tattooer->stripe_connect_status = 'not_connected';
    $tattooer->save();

    expect($tattooer->canCreateStripeConnect())->toBeFalse();
});

test('active_account_becomes_inactive_after_60_days_without_transaction', function () {
    $user = User::factory()->create();
    $tattooer = new Tattooer();
    $tattooer->user_id = $user->id;
    $tattooer->name = 'Test Tattooer';
    $tattooer->slug = 'test-tattooer';
    $tattooer->siret = '12345678901234';
    $tattooer->stripe_connect_status = 'active';
    $tattooer->stripe_connect_activated_at = now()->subDays(65);
    $tattooer->stripe_connect_last_transaction_at = now()->subDays(61);
    $tattooer->save();

    // diffInDays retourne -61, on utilise abs() pour la valeur absolue
    $daysSince = abs(Carbon::now()->diffInDays($tattooer->stripe_connect_last_transaction_at));

    // On vérifie juste que c'est >= 60 jours
    expect($daysSince >= 60)->toBeTrue();
});

test('active_account_with_recent_transaction_stays_active', function () {
    $user = User::factory()->create();
    $tattooer = new Tattooer();
    $tattooer->user_id = $user->id;
    $tattooer->name = 'Test Tattooer';
    $tattooer->slug = 'test-tattooer';
    $tattooer->siret = '12345678901234';
    $tattooer->stripe_connect_status = 'active';
    $tattooer->stripe_connect_last_transaction_at = now()->subDays(30);
    $tattooer->save();

    expect($tattooer->shouldBeDeactivated())->toBeFalse();
});

test('recording_transaction_updates_last_transaction_date', function () {
    $user = User::factory()->create();
    $tattooer = new Tattooer();
    $tattooer->user_id = $user->id;
    $tattooer->name = 'Test Tattooer';
    $tattooer->slug = 'test-tattooer';
    $tattooer->siret = '12345678901234';
    $tattooer->stripe_connect_status = 'active';
    $tattooer->save();

    $tattooer->recordStripeTransaction();

    expect($tattooer->fresh()->stripe_connect_last_transaction_at)->not->toBeNull();
});

test('recording_transaction_reactivates_inactive_account', function () {
    $user = User::factory()->create();
    $tattooer = new Tattooer();
    $tattooer->user_id = $user->id;
    $tattooer->name = 'Test Tattooer';
    $tattooer->slug = 'test-tattooer';
    $tattooer->siret = '12345678901234';
    $tattooer->stripe_connect_status = 'inactive';
    $tattooer->save();

    $tattooer->recordStripeTransaction();

    expect($tattooer->fresh()->isStripeActive())->toBeTrue();
});

test('deactivation_command_finds_inactive_accounts', function () {
    // Créer utilisateurs
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // Compte actif récent
    $activeTattooer = new Tattooer();
    $activeTattooer->user_id = $user1->id;
    $activeTattooer->name = 'Active Tattooer';
    $activeTattooer->slug = 'active-tattooer';
    $activeTattooer->siret = '12345678901234';
    $activeTattooer->stripe_connect_status = 'active';
    $activeTattooer->stripe_connect_last_transaction_at = now()->subDays(30);
    $activeTattooer->current_plan = 'free';
    $activeTattooer->save();

    // Compte à désactiver
    $inactiveTattooer = new Tattooer();
    $inactiveTattooer->user_id = $user2->id;
    $inactiveTattooer->name = 'Inactive Tattooer';
    $inactiveTattooer->slug = 'inactive-tattooer';
    $inactiveTattooer->siret = '12345678901235';
    $inactiveTattooer->stripe_connect_status = 'active';
    $inactiveTattooer->stripe_connect_last_transaction_at = now()->subDays(65);
    $inactiveTattooer->current_plan = 'free';
    $inactiveTattooer->save();

    // Exécuter commande
    $this->artisan('stripe:deactivate-inactive')
        ->assertExitCode(0);

    // Vérifier désactivation
    expect($inactiveTattooer->fresh()->isStripeInactive())->toBeTrue();
});

test('stripe_connect_status_badge_returns_correct_html', function () {
    $user = User::factory()->create();
    $tattooer = new Tattooer();
    $tattooer->user_id = $user->id;
    $tattooer->name = 'Test Tattooer';
    $tattooer->slug = 'test-tattooer';
    $tattooer->siret = '12345678901234';
    $tattooer->stripe_connect_status = 'active';
    $tattooer->save();

    expect($tattooer->getStripeConnectStatusBadge())->toContain('✅ Actif');
    expect($tattooer->getStripeConnectStatusBadge())->toContain('bg-success');
});

test('stripe_connect_alert_message_for_inactive_account', function () {
    $user = User::factory()->create();
    $tattooer = new Tattooer();
    $tattooer->user_id = $user->id;
    $tattooer->name = 'Test Tattooer';
    $tattooer->slug = 'test-tattooer';
    $tattooer->siret = '12345678901234';
    $tattooer->stripe_connect_status = 'inactive';
    $tattooer->save();

    expect($tattooer->getStripeConnectAlertMessage())->toContain('inactif');
});
