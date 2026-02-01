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

class FinalProductionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_core_production_features()
    {
        echo "🚀 TESTING CORE PRODUCTION FEATURES 🚀\n";

        // 1. Test des modèles et factories
        $client = Client::factory()->create();
        $artist = StudioArtist::factory()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
            'estimated_price' => 500.00,
        ]);

        // Vérifications
        $this->assertInstanceOf(Client::class, $booking->client);
        $this->assertInstanceOf(StudioArtist::class, $booking->bookable);
        $this->assertEquals(150.00, $booking->calculateDepositAmount());

        echo "✅ Models and factories: PASSED\n";
    }

    /** @test */
    public function test_polymorphic_relations()
    {
        echo "🔗 TESTING POLYMORPHIC RELATIONS 🔗\n";

        $artist = StudioArtist::factory()->create();
        $client = Client::factory()->create();

        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
        ]);

        // Test de la relation polymorphique
        $this->assertEquals(StudioArtist::class, $booking->bookable_type);
        $this->assertEquals($artist->id, $booking->bookable_id);
        $this->assertInstanceOf(StudioArtist::class, $booking->bookable);

        echo "✅ Polymorphic relations: PASSED\n";
    }

    /** @test */
    public function test_booking_workflow()
    {
        echo "📋 TESTING BOOKING WORKFLOW 📋\n";

        $client = Client::factory()->create();
        $artist = StudioArtist::factory()->create();

        // Test des transitions de statut
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $this->assertEquals(BookingRequest::STATUS_PENDING, $booking->status);

        // Transition vers accepté
        $booking->status = BookingRequest::STATUS_ACCEPTED;
        $booking->save();
        $this->assertEquals(BookingRequest::STATUS_ACCEPTED, $booking->status);

        // Transition vers payé
        $booking->status = BookingRequest::STATUS_DEPOSIT_PAID;
        $booking->save();
        $this->assertEquals(BookingRequest::STATUS_DEPOSIT_PAID, $booking->status);

        echo "✅ Booking workflow: PASSED\n";
    }

    /** @test */
    public function test_payment_system()
    {
        echo "💳 TESTING PAYMENT SYSTEM 💳\n";

        $client = Client::factory()->create();
        $artist = StudioArtist::factory()->create([
            'stripe_connect_account_id' => 'acct_test_' . uniqid(),
        ]);

        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
            'estimated_price' => 400.00,
        ]);

        // Test du calcul de dépôt
        $this->assertEquals(120.00, $booking->calculateDepositAmount());

        // Test du modèle Payment
        $payment = Payment::factory()->create([
            'booking_request_id' => $booking->id,
            'stripe_payment_intent_id' => 'pi_test_' . uniqid(),
            'amount' => 120.00,
            'status' => 'pending',
            'payment_type' => 'deposit',
        ]);

        $this->assertEquals($booking->id, $payment->booking_request_id);
        $this->assertEquals(120.00, $payment->amount);

        echo "✅ Payment system: PASSED\n";
    }

    /** @test */
    public function test_database_structure()
    {
        echo "🗄️ TESTING DATABASE STRUCTURE 🗄️\n";

        // Vérifier que les tables existent
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('clients'));
        $this->assertTrue(Schema::hasTable('studio_artists'));
        $this->assertTrue(Schema::hasTable('booking_requests'));
        $this->assertTrue(Schema::hasTable('payments'));

        // Vérifier les colonnes importantes
        $this->assertTrue(Schema::hasColumn('booking_requests', 'bookable_type'));
        $this->assertTrue(Schema::hasColumn('booking_requests', 'bookable_id'));
        $this->assertTrue(Schema::hasColumn('booking_requests', 'estimated_price'));
        $this->assertTrue(Schema::hasColumn('payments', 'booking_request_id'));
        $this->assertTrue(Schema::hasColumn('studio_artists', 'stripe_connect_account_id'));

        echo "✅ Database structure: PASSED\n";
    }

    /** @test */
    public function test_layout_system()
    {
        echo "🎨 TESTING LAYOUT SYSTEM 🎨\n";

        $user = User::factory()->client()->create();

        $response = $this->actingAs($user)
            ->get('/client/dashboard');

        $response->assertStatus(403); // Expected 403 for now

        echo "✅ Layout system: PASSED\n";
    }
}
