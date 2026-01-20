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

class FinalValidationTest extends TestCase 
{
    use RefreshDatabase;

    /** @test */
    public function test_models_and_factories_work()
    {
        // Test que les modèles et factories fonctionnent
        $client = Client::factory()->create();
        $artist = StudioArtist::factory()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
        ]);

        // Vérifier que les relations fonctionnent
        $this->assertInstanceOf(Client::class, $booking->client);
        $this->assertInstanceOf(StudioArtist::class, $booking->bookable);
        $this->assertEquals($client->id, $booking->client->id);
        $this->assertEquals($artist->id, $booking->bookable->id);
    }

    /** @test */
    public function test_polymorphic_relations_work()
    {
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
    }

    /** @test */
    public function test_booking_status_transitions()
    {
        $client = Client::factory()->create();
        $artist = StudioArtist::factory()->create();

        // Créer un booking en status pending
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $this->assertEquals(BookingRequest::STATUS_PENDING, $booking->status);

        // Changer le statut vers accepté
        $booking->status = BookingRequest::STATUS_ACCEPTED;
        $booking->save();

        $this->assertEquals(BookingRequest::STATUS_ACCEPTED, $booking->status);
    }

    /** @test */
    public function test_deposit_calculation()
    {
        $booking = new BookingRequest([
            'estimated_price' => 500.00,
        ]);

        $this->assertEquals(150.00, $booking->calculateDepositAmount());

        $booking->estimated_price = 1000.00;
        $this->assertEquals(300.00, $booking->calculateDepositAmount());
    }

    /** @test */
    public function test_payment_deadline_check()
    {
        $booking = new BookingRequest([
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
            'client_payment_deadline' => now()->subDay(), // Expiré
        ]);

        $this->assertTrue($booking->isPaymentOverdue());

        $booking->client_payment_deadline = now()->addDay(); // Non expiré
        $this->assertFalse($booking->isPaymentOverdue());
    }

    /** @test */
    public function test_studio_artist_factory()
    {
        $artist = StudioArtist::factory()->create([
            'artist_name' => 'Test Artist',
            'status' => 'active',
        ]);

        $this->assertNotNull($artist->id);
        $this->assertEquals('Test Artist', $artist->artist_name);
        $this->assertEquals('active', $artist->status);
        $this->assertNotNull($artist->user_id);
    }

    /** @test */
    public function test_payment_model()
    {
        $client = Client::factory()->create();
        $artist = StudioArtist::factory()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
        ]);

        $payment = Payment::factory()->create([
            'booking_request_id' => $booking->id,
            'stripe_payment_intent_id' => 'pi_test_123',
            'amount' => 150.00,
            'status' => 'pending',
            'payment_type' => 'deposit',
        ]);

        $this->assertNotNull($payment->id);
        $this->assertEquals($booking->id, $payment->booking_request_id);
        $this->assertEquals('pi_test_123', $payment->stripe_payment_intent_id);
        $this->assertEquals(150.00, $payment->amount);
        $this->assertEquals('pending', $payment->status);
    }

    /** @test */
    public function test_database_structure()
    {
        // Vérifier que les tables existent
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('clients'));
        $this->assertTrue(Schema::hasTable('studios'));
        $this->assertTrue(Schema::hasTable('studio_artists'));
        $this->assertTrue(Schema::hasTable('booking_requests'));
        $this->assertTrue(Schema::hasTable('payments'));

        // Vérifier les colonnes importantes
        $this->assertTrue(Schema::hasColumn('booking_requests', 'bookable_type'));
        $this->assertTrue(Schema::hasColumn('booking_requests', 'bookable_id'));
        $this->assertTrue(Schema::hasColumn('booking_requests', 'estimated_price'));
        $this->assertTrue(Schema::hasColumn('payments', 'booking_request_id'));
        $this->assertTrue(Schema::hasColumn('studio_artists', 'stripe_connect_account_id'));
    }

    /** @test */
    public function test_user_authentication()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        // Test que l'authentification fonctionne
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        // Test que le client est bien lié à l'utilisateur
        $this->assertEquals($user->id, $client->user_id);
    }
}
