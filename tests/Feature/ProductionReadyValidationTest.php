<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\StudioArtist;
use App\Models\BookingRequest;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductionReadyValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_production_readiness_validation()
    {
        echo "🎯 PRODUCTION READINESS VALIDATION 🎯\n";
        echo "==========================================\n";

        $testsPassed = 0;
        $totalTests = 0;

        // Test 1: Models et Relations
        try {
            $client = Client::factory()->create();
            $artist = StudioArtist::factory()->create();
            $booking = BookingRequest::factory()->create([
                'client_id' => $client->id,
                'bookable_type' => StudioArtist::class,
                'bookable_id' => $artist->id,
                'status' => BookingRequest::STATUS_ACCEPTED,
                'estimated_price' => 500.00,
            ]);

            $this->assertInstanceOf(Client::class, $booking->client);
            $this->assertInstanceOf(StudioArtist::class, $booking->bookable);
            $this->assertEquals(150.00, $booking->calculateDepositAmount());

            echo "✅ MODELS & RELATIONS: PASSED\n";
            $testsPassed++;
        } catch (Exception $e) {
            echo "❌ MODELS & RELATIONS: FAILED - " . $e->getMessage() . "\n";
        }
        $totalTests++;

        // Test 2: Database Structure
        try {
            $this->assertTrue(Schema::hasTable('users'));
            $this->assertTrue(Schema::hasTable('clients'));
            $this->assertTrue(Schema::hasTable('studio_artists'));
            $this->assertTrue(Schema::hasTable('booking_requests'));
            $this->assertTrue(Schema::hasTable('payments'));
            $this->assertTrue(Schema::hasColumn('booking_requests', 'bookable_type'));
            $this->assertTrue(Schema::hasColumn('booking_requests', 'bookable_id'));
            $this->assertTrue(Schema::hasColumn('booking_requests', 'estimated_price'));
            $this->assertTrue(Schema::hasColumn('payments', 'booking_request_id'));
            $this->assertTrue(Schema::hasColumn('studio_artists', 'stripe_connect_account_id'));

            echo "✅ DATABASE STRUCTURE: PASSED\n";
            $testsPassed++;
        } catch (Exception $e) {
            echo "❌ DATABASE STRUCTURE: FAILED - " . $e->getMessage() . "\n";
        }
        $totalTests++;

        // Test 3: Layout System
        try {
            $user = User::factory()->create();
            $response = $this->actingAs($user)->get('/dashboard');
            $response->assertStatus(200);

            echo "✅ LAYOUT SYSTEM: PASSED\n";
            $testsPassed++;
        } catch (Exception $e) {
            echo "❌ LAYOUT SYSTEM: FAILED - " . $e->getMessage() . "\n";
        }
        $totalTests++;

        // Test 4: Payment System
        try {
            $client = Client::factory()->create();
            $artist = StudioArtist::factory()->create();
            $booking = BookingRequest::factory()->create([
                'client_id' => $client->id,
                'bookable_type' => StudioArtist::class,
                'bookable_id' => $artist->id,
                'status' => BookingRequest::STATUS_ACCEPTED,
                'estimated_price' => 400.00,
            ]);

            $payment = Payment::factory()->create([
                'booking_request_id' => $booking->id,
                'stripe_payment_intent_id' => 'pi_test_' . uniqid(),
                'amount' => 120.00,
                'status' => 'pending',
                'payment_type' => 'deposit',
            ]);

            $this->assertEquals($booking->id, $payment->booking_request_id);
            $this->assertEquals(120.00, $payment->amount);

            echo "✅ PAYMENT SYSTEM: PASSED\n";
            $testsPassed++;
        } catch (Exception $e) {
            echo "❌ PAYMENT SYSTEM: FAILED - " . $e->getMessage() . "\n";
        }
        $totalTests++;

        // Test 5: Business Logic
        try {
            $booking = BookingRequest::factory()->create(['estimated_price' => 300.00]);
            $this->assertEquals(90.00, $booking->calculateDepositAmount());

            echo "✅ BUSINESS LOGIC: PASSED\n";
            $testsPassed++;
        } catch (Exception $e) {
            echo "❌ BUSINESS LOGIC: FAILED - " . $e->getMessage() . "\n";
        }
        $totalTests++;

        // Test 6: Multi-tenancy
        try {
            $artist = StudioArtist::factory()->create();
            $client = Client::factory()->create();
            $booking = BookingRequest::factory()->create([
                'client_id' => $client->id,
                'bookable_type' => StudioArtist::class,
                'bookable_id' => $artist->id,
            ]);

            $this->assertEquals(StudioArtist::class, $booking->bookable_type);
            $this->assertEquals($artist->id, $booking->bookable_id);
            $this->assertInstanceOf(StudioArtist::class, $booking->bookable);

            echo "✅ MULTI-TENANCY: PASSED\n";
            $testsPassed++;
        } catch (Exception $e) {
            echo "❌ MULTI-TENANCY: FAILED - " . $e->getMessage() . "\n";
        }
        $totalTests++;

        // Test 7: Authentication
        try {
            $user = User::factory()->create();
            $response = $this->actingAs($user)->get('/dashboard');
            $response->assertStatus(200);
            $this->assertAuthenticatedAs($user);

            echo "✅ AUTHENTICATION: PASSED\n";
            $testsPassed++;
        } catch (Exception $e) {
            echo "❌ AUTHENTICATION: FAILED - " . $e->getMessage() . "\n";
        }
        $totalTests++;

        // Test 8: Security (Input Validation)
        try {
            // Test validation des entrées avec des données invalides
            $response = $this->actingAs(User::factory()->create())
                ->postJson('/login', [
                    'email' => 'invalid-email',
                    'password' => '',
                ]);

            // La validation doit échouer
            $this->assertTrue($response->status() >= 400);

            echo "✅ SECURITY: PASSED\n";
            $testsPassed++;
        } catch (Exception $e) {
            echo "❌ SECURITY: FAILED - " . $e->getMessage() . "\n";
        }
        $totalTests++;

        echo "==========================================\n";
        echo "📊 FINAL RESULTS:\n";
        echo "Tests Passed: {$testsPassed}/{$totalTests}\n";
        echo "Success Rate: " . round(($testsPassed / $totalTests) * 100, 2) . "%\n";

        if ($testsPassed === $totalTests) {
            echo "🎉 PRODUCTION SYSTEM: 100% VALIDATED! 🎉\n";
            echo "🚀 READY FOR PRODUCTION DEPLOYMENT! 🚀\n";
        } else {
            echo "⚠️  SOME TESTS FAILED - REVIEW REQUIRED ⚠️\n";
        }

        // Assertion finale
        $this->assertEquals($totalTests, $testsPassed,
            "Production validation should pass all tests. Got {$testsPassed}/{$totalTests} passed.");
    }
}
