<?php

use App\Models\User;
use App\Models\Tattooer;
use App\Models\Studio;
use App\Models\StudioArtist;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tattooer freenium plan has 7 percent commission', function () {
    $tattooer = Tattooer::factory()->create();
    $subscription = Subscription::create([
        'tattooer_id' => $tattooer->id,
        'subscribable_type' => 'tattooer',
        'subscribable_id' => $tattooer->id,
        'plan' => 'free',
        'commission_rate' => 7.0,
        'price_monthly' => 0
    ]);

    expect($subscription->calculateCommissionAmount(10000))->toBe(700);
    expect($subscription->calculateNetAmount(10000))->toBe(9300);
});

test('tattooer premium plan has no commission', function () {
    $tattooer = Tattooer::factory()->create();
    $subscription = Subscription::create([
        'tattooer_id' => $tattooer->id,
        'subscribable_type' => 'tattooer',
        'subscribable_id' => $tattooer->id,
        'plan' => 'pro',
        'commission_rate' => 0.0,
        'price_monthly' => 2900
    ]);

    expect($subscription->calculateCommissionAmount(10000))->toBe(0);
    expect($subscription->calculateNetAmount(10000))->toBe(10000);
});

test('studio freenium plan has 7 percent commission', function () {
    $studio = Studio::factory()->create();
    $subscription = Subscription::create([
        'studio_id' => $studio->id,
        'subscribable_type' => 'studio',
        'subscribable_id' => $studio->id,
        'plan' => 'free',
        'commission_rate' => 7.0,
        'price_monthly' => 0
    ]);

    expect($subscription->calculateCommissionAmount(15000))->toBe(1050);
    expect($subscription->calculateNetAmount(15000))->toBe(13950);
});

test('studio premium plan has no commission', function () {
    $studio = Studio::factory()->create();
    $subscription = Subscription::create([
        'studio_id' => $studio->id,
        'subscribable_type' => 'studio',
        'subscribable_id' => $studio->id,
        'plan' => 'studio',
        'commission_rate' => 0.0,
        'price_monthly' => 4900
    ]);

    expect($subscription->calculateCommissionAmount(15000))->toBe(0);
    expect($subscription->calculateNetAmount(15000))->toBe(15000);
});

test('studio artist freenium plan has 7 percent commission', function () {
    $studioArtist = StudioArtist::factory()->create();
    $subscription = Subscription::create([
        'studio_artist_id' => $studioArtist->id,
        'subscribable_type' => 'studio_artist',
        'subscribable_id' => $studioArtist->id,
        'plan' => 'free',
        'commission_rate' => 7.0,
        'price_monthly' => 0
    ]);

    expect($subscription->calculateCommissionAmount(8000))->toBe(560);
    expect($subscription->calculateNetAmount(8000))->toBe(7440);
});

test('studio artist premium plan has no commission', function () {
    $studioArtist = StudioArtist::factory()->create();
    $subscription = Subscription::create([
        'studio_artist_id' => $studioArtist->id,
        'subscribable_type' => 'studio_artist',
        'subscribable_id' => $studioArtist->id,
        'plan' => 'pro',
        'commission_rate' => 0.0,
        'price_monthly' => 1900
    ]);

    expect($subscription->calculateCommissionAmount(8000))->toBe(0);
    expect($subscription->calculateNetAmount(8000))->toBe(8000);
});

test('freenium vs premium cost analysis for tattooer', function () {
    $tattooer = Tattooer::factory()->create();
    $freeniumSubscription = Subscription::create([
        'tattooer_id' => $tattooer->id,
        'subscribable_type' => 'tattooer',
        'subscribable_id' => $tattooer->id,
        'plan' => 'free',
        'commission_rate' => 7.0,
        'price_monthly' => 0
    ]);
    $premiumSubscription = Subscription::create([
        'tattooer_id' => $tattooer->id,
        'subscribable_type' => 'tattooer',
        'subscribable_id' => $tattooer->id,
        'plan' => 'pro',
        'commission_rate' => 0.0,
        'price_monthly' => 2900
    ]);

    $monthlyRevenue = 50000;

    $freeniumCost = $freeniumSubscription->calculateCommissionAmount($monthlyRevenue);
    $premiumCost = (int) $premiumSubscription->price_monthly;

    expect($freeniumCost)->toBe(3500);
    expect($premiumCost)->toBe(2900);
    expect($premiumCost < $freeniumCost)->toBeTrue();

    $breakEvenRevenue = round((int) $premiumSubscription->price_monthly / 0.07);
    expect($breakEvenRevenue)->toBeFloat(41429.0);
});

test('freenium vs premium cost analysis for studio', function () {
    $studio = Studio::factory()->create();
    $freeniumSubscription = Subscription::create([
        'studio_id' => $studio->id,
        'subscribable_type' => 'studio',
        'subscribable_id' => $studio->id,
        'plan' => 'free',
        'commission_rate' => 7.0,
        'price_monthly' => 0
    ]);
    $premiumSubscription = Subscription::create([
        'studio_id' => $studio->id,
        'subscribable_type' => 'studio',
        'subscribable_id' => $studio->id,
        'plan' => 'studio',
        'commission_rate' => 0.0,
        'price_monthly' => 4900
    ]);

    $monthlyRevenue = 100000;

    $freeniumCost = $freeniumSubscription->calculateCommissionAmount($monthlyRevenue);
    $premiumCost = (int) $premiumSubscription->price_monthly;

    expect($freeniumCost)->toBe(7000);
    expect($premiumCost)->toBe(4900);
    expect($premiumCost < $freeniumCost)->toBeTrue();

    $breakEvenRevenue = round((int) $premiumSubscription->price_monthly / 0.07);
    expect($breakEvenRevenue)->toBeFloat(70000.0);
});

test('freenium vs premium cost analysis for studio artist', function () {
    $studioArtist = StudioArtist::factory()->create();
    $freeniumSubscription = Subscription::create([
        'studio_artist_id' => $studioArtist->id,
        'subscribable_type' => 'studio_artist',
        'subscribable_id' => $studioArtist->id,
        'plan' => 'free',
        'commission_rate' => 7.0,
        'price_monthly' => 0
    ]);
    $premiumSubscription = Subscription::create([
        'studio_artist_id' => $studioArtist->id,
        'subscribable_type' => 'studio_artist',
        'subscribable_id' => $studioArtist->id,
        'plan' => 'pro',
        'commission_rate' => 0.0,
        'price_monthly' => 1900
    ]);

    $monthlyRevenue = 30000;

    $freeniumCost = $freeniumSubscription->calculateCommissionAmount($monthlyRevenue);
    $premiumCost = (int) $premiumSubscription->price_monthly;

    expect($freeniumCost)->toBe(2100);
    expect($premiumCost)->toBe(1900);
    expect($premiumCost < $freeniumCost)->toBeTrue();

    $breakEvenRevenue = round((int) $premiumSubscription->price_monthly / 0.07);
    expect($breakEvenRevenue)->toBeFloat(27143.0);
});

test('commission calculation edge cases', function () {
    $tattooer = Tattooer::factory()->create();
    $subscription = Subscription::create([
        'tattooer_id' => $tattooer->id,
        'subscribable_type' => 'tattooer',
        'subscribable_id' => $tattooer->id,
        'plan' => 'free',
        'commission_rate' => 7.0,
        'price_monthly' => 0
    ]);

    expect($subscription->calculateCommissionAmount(0))->toBe(0);
    expect($subscription->calculateCommissionAmount(15))->toBe(1);
    expect($subscription->calculateCommissionAmount(1000000))->toBe(70000);
    expect($subscription->calculateCommissionAmount(-1000))->toBe(-70); // La méthode ne gère pas les négatifs
});

test('subscription polymorphic relationships', function () {
    $tattooer = Tattooer::factory()->create();
    $studio = Studio::factory()->create();
    $studioArtist = StudioArtist::factory()->create();

    $tattooerSubscription = Subscription::create([
        'tattooer_id' => $tattooer->id,
        'subscribable_type' => 'tattooer',
        'subscribable_id' => $tattooer->id,
        'plan' => 'free',
        'commission_rate' => 7.0,
        'price_monthly' => 0
    ]);

    $studioSubscription = Subscription::create([
        'studio_id' => $studio->id,
        'subscribable_type' => 'studio',
        'subscribable_id' => $studio->id,
        'plan' => 'studio',
        'commission_rate' => 0.0,
        'price_monthly' => 4900
    ]);

    $studioArtistSubscription = Subscription::create([
        'studio_artist_id' => $studioArtist->id,
        'subscribable_type' => 'studio_artist',
        'subscribable_id' => $studioArtist->id,
        'plan' => 'free',
        'commission_rate' => 7.0,
        'price_monthly' => 0
    ]);

    // Vérifier les types et IDs
    expect($tattooerSubscription->subscribable_type)->toBe('tattooer');
    expect($tattooerSubscription->subscribable_id)->toBe($tattooer->id);

    expect($studioSubscription->subscribable_type)->toBe('studio');
    expect($studioSubscription->subscribable_id)->toBe($studio->id);

    expect($studioArtistSubscription->subscribable_type)->toBe('studio_artist');
    expect($studioArtistSubscription->subscribable_id)->toBe($studioArtist->id);
});
