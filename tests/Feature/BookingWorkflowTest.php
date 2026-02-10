<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Appointment;
use App\Services\BookingRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BookingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $client;
    private User $tattooer;
    private Tattooer $tattooerProfile;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer utilisateurs de test
        $this->client = User::factory()->client()->create();
        $this->tattooer = User::factory()->tattooer()->create();
        $this->tattooerProfile = $this->tattooer->tattooer;
        
        Storage::fake('public');
    }

    /** @test */
    public function client_can_create_booking_request()
    {
        $response = $this->actingAs($this->client, 'sanctum')
            ->postJson('/api/booking-requests', [
                'bookable_type' => Tattooer::class,
                'bookable_id' => $this->tattooerProfile->id,
                'tattoo_size' => 'medium',
                'body_zone' => 'bras',
                'description' => 'Je voudrais un dragon japonais',
                'preferred_timeframe' => 'dans_1_mois',
                'budget_range' => '200-400',
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'status',
                'client_id',
                'bookable_id',
            ],
        ]);

        $this->assertDatabaseHas('booking_requests', [
            'client_id' => $this->client->client->id,
            'bookable_id' => $this->tattooerProfile->id,
            'status' => BookingRequest::STATUS_PENDING,
        ]);
    }

    /** @test */
    public function client_can_upload_reference_images()
    {
        $booking = BookingRequest::factory()->create([
            'client_id' => $this->client->client->id,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $file1 = UploadedFile::fake()->image('reference1.jpg');
        $file2 = UploadedFile::fake()->image('reference2.jpg');

        $response = $this->actingAs($this->client, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/reference-images", [
                'images' => [$file1, $file2],
            ]);

        $response->assertOk();
        expect($booking->fresh()->getMedia('reference_images'))->toHaveCount(2);
    }

    /** @test */
    public function tattooer_can_accept_booking_request()
    {
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $this->tattooerProfile->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->tattooer, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/accept", [
                'estimated_total_price' => 300,
                'deposit_rate' => 30,
                'price_range_min' => 250,
                'price_range_max' => 350,
                'design_versions' => 3,
            ]);

        $response->assertOk();
        
        $booking->refresh();
        expect($booking->status)->toBe(BookingRequest::STATUS_ACCEPTED);
        expect($booking->total_deposit_amount)->toBe(90.0); // 30% de 300
        expect($booking->conversation)->not->toBeNull();
        expect($booking->conversation->expiry_type)->toBe('deposit_pending');
    }

    /** @test */
    public function tattooer_cannot_accept_other_tattooer_booking()
    {
        $otherTattooer = Tattooer::factory()->create();
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $otherTattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->tattooer, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/accept", [
                'estimated_total_price' => 300,
            ]);

        $response->assertForbidden();
    }

    /** @test */
    public function tattooer_can_reject_booking_with_reason()
    {
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $this->tattooerProfile->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->tattooer, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/reject", [
                'reason' => 'Je ne suis pas disponible sur cette période',
            ]);

        $response->assertOk();
        
        $booking->refresh();
        expect($booking->status)->toBe(BookingRequest::STATUS_REJECTED);
        expect($booking->rejection_reason)->toBe('Je ne suis pas disponible sur cette période');
    }

    /** @test */
    public function client_can_pay_deposit_after_acceptance()
    {
        $booking = BookingRequest::factory()->create([
            'client_id' => $this->client->client->id,
            'bookable_id' => $this->tattooerProfile->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
            'total_deposit_amount' => 90,
        ]);

        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
            'expiry_type' => 'deposit_pending',
        ]);

        // Simuler paiement Stripe réussi
        $paymentIntentId = 'pi_test_' . uniqid();

        $response = $this->actingAs($this->client, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/confirm-deposit", [
                'payment_intent_id' => $paymentIntentId,
            ]);

        $response->assertOk();
        
        $booking->refresh();
        $conversation->refresh();
        
        expect($booking->status)->toBe(BookingRequest::STATUS_DEPOSIT_PAID);
        expect($booking->deposit_paid_at)->not->toBeNull();
        expect($booking->stripe_payment_intent_id)->toBe($paymentIntentId);
        expect($conversation->expiry_type)->toBe('permanent');
    }

    /** @test */
    public function client_cannot_pay_deposit_for_pending_booking()
    {
        $booking = BookingRequest::factory()->create([
            'client_id' => $this->client->client->id,
            'status' => BookingRequest::STATUS_PENDING, // Pas encore accepté
        ]);

        $response = $this->actingAs($this->client, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/confirm-deposit", [
                'payment_intent_id' => 'pi_test_123',
            ]);

        $response->assertForbidden();
    }

    /** @test */
    public function tattooer_can_send_design_after_deposit_paid()
    {
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $this->tattooerProfile->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_DEPOSIT_PAID,
            'design_versions_used' => 0,
            'included_design_versions' => 3,
        ]);

        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
        ]);

        $designFile = UploadedFile::fake()->image('design.jpg');

        $response = $this->actingAs($this->tattooer, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/send-design", [
                'images' => [$designFile],
                'message' => 'Voici ma première proposition',
            ]);

        $response->assertOk();
        
        $booking->refresh();
        expect($booking->status)->toBe(BookingRequest::STATUS_DESIGN_SENT);
        expect($booking->design_versions_used)->toBe(1);
        
        $designMessage = $conversation->messages()
            ->where('is_design_version', true)
            ->first();
        
        expect($designMessage)->not->toBeNull();
        expect($designMessage->design_version_number)->toBe(1);
    }

    /** @test */
    public function free_plan_cannot_send_more_than_3_designs()
    {
        $this->tattooerProfile->update(['is_subscribed' => false]);
        
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $this->tattooerProfile->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_DESIGN_SENT,
            'design_versions_used' => 3, // Max atteint
            'included_design_versions' => 3,
        ]);

        $designFile = UploadedFile::fake()->image('design4.jpg');

        $response = $this->actingAs($this->tattooer, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/send-design", [
                'images' => [$designFile],
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'error' => 'Nombre maximum de versions atteint.',
        ]);
    }

    /** @test */
    public function pro_plan_can_send_unlimited_designs()
    {
        $this->tattooerProfile->update(['is_subscribed' => true]);
        
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $this->tattooerProfile->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_DESIGN_SENT,
            'design_versions_used' => 5, // Déjà 5 versions
            'included_design_versions' => 3,
        ]);

        $designFile = UploadedFile::fake()->image('design6.jpg');

        $response = $this->actingAs($this->tattooer, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/send-design", [
                'images' => [$designFile],
            ]);

        $response->assertOk();
        expect($booking->fresh()->design_versions_used)->toBe(6);
    }

    /** @test */
    public function tattooer_can_confirm_appointment()
    {
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $this->tattooerProfile->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_DESIGN_SENT,
            'estimated_total_price' => 300,
            'total_deposit_amount' => 90,
        ]);

        $appointmentDate = now()->addWeek()->startOfDay()->setHour(14);

        $response = $this->actingAs($this->tattooer, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/confirm-appointment", [
                'start_time' => $appointmentDate->toISOString(),
                'duration_minutes' => 120,
            ]);

        $response->assertOk();
        
        $booking->refresh();
        expect($booking->status)->toBe(BookingRequest::STATUS_CONFIRMED);
        expect($booking->appointment)->not->toBeNull();
        expect($booking->appointment->remaining_amount)->toBe(210.0); // 300 - 90
    }

    /** @test */
    public function client_can_cancel_booking_before_confirmation()
    {
        $booking = BookingRequest::factory()->create([
            'client_id' => $this->client->client->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
        ]);

        $response = $this->actingAs($this->client, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/cancel", [
                'reason' => 'J\'ai changé d\'avis',
            ]);

        $response->assertOk();
        
        $booking->refresh();
        expect($booking->status)->toBe(BookingRequest::STATUS_CANCELLED);
        expect($booking->cancellation_reason)->toBe('J\'ai changé d\'avis');
    }

    /** @test */
    public function complete_booking_workflow_integration()
    {
        // 1. Client crée demande
        $booking = BookingRequest::factory()->create([
            'client_id' => $this->client->client->id,
            'bookable_id' => $this->tattooerProfile->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        // 2. Tatoueur accepte
        $service = app(BookingRequestService::class);
        $booking = $service->accept($booking, [
            'estimated_total_price' => 300,
            'deposit_rate' => 30,
        ]);

        expect($booking->status)->toBe(BookingRequest::STATUS_ACCEPTED);

        // 3. Client paie acompte
        $booking->update(['status' => BookingRequest::STATUS_AWAITING_DEPOSIT]);
        $booking = $service->confirmDeposit($booking, 'pi_test_123');

        expect($booking->status)->toBe(BookingRequest::STATUS_DEPOSIT_PAID);

        // 4. Tatoueur envoie design
        $service->sendDesign(
            $booking,
            [UploadedFile::fake()->image('design.jpg')],
            'Voici le design'
        );

        expect($booking->fresh()->status)->toBe(BookingRequest::STATUS_DESIGN_SENT);

        // 5. Tatoueur confirme RDV
        $appointment = $service->confirmAppointment(
            $booking->fresh(),
            now()->addWeek(),
            120
        );

        expect($booking->fresh()->status)->toBe(BookingRequest::STATUS_CONFIRMED);
        expect($appointment)->not->toBeNull();
    }
}
