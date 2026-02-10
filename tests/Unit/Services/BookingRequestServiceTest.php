<?php

namespace Tests\Unit\Services;

use App\Services\BookingRequestService;
use App\Models\BookingRequest;
use App\Models\Tattooer;
use App\Models\Client;
use App\Models\User;
use App\Exceptions\BookingException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BookingRequestServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookingRequestService $service;
    private BookingRequest $bookingRequest;
    private Tattooer $tattooer;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = app(BookingRequestService::class);
        
        $this->tattooer = Tattooer::factory()->create();
        $this->client = Client::factory()->create();
        
        $this->bookingRequest = BookingRequest::factory()->create([
            'bookable_id' => $this->tattooer->id,
            'bookable_type' => Tattooer::class,
            'client_id' => $this->client->id,
            'status' => BookingRequest::STATUS_PENDING,
        ]);
    }

    /**
     * Test accept booking request creates conversation
     */
    public function test_accept_booking_request_creates_conversation(): void
    {
        $result = $this->service->accept($this->bookingRequest, [
            'estimated_total_price' => 200,
            'deposit_rate' => 30,
            'design_versions' => 3,
        ]);
        
        expect($result->status)->toBe(BookingRequest::STATUS_ACCEPTED);
        expect($result->conversation)->not->toBeNull();
        expect($result->total_deposit_amount)->toBe(60.0);
        expect($result->client_payment_deadline)->not->toBeNull();
    }

    /**
     * Test cannot accept already accepted booking
     */
    public function test_cannot_accept_already_accepted_booking(): void
    {
        $this->bookingRequest->update(['status' => BookingRequest::STATUS_ACCEPTED]);
        
        $this->expectException(BookingException::class);
        $this->expectExceptionMessage('Cette demande ne peut plus être acceptée.');
        
        $this->service->accept($this->bookingRequest, [
            'estimated_total_price' => 200,
        ]);
    }

    /**
     * Test reject booking request
     */
    public function test_reject_booking_request(): void
    {
        $result = $this->service->reject($this->bookingRequest, 'Pas disponible');
        
        expect($result->status)->toBe(BookingRequest::STATUS_REJECTED);
        expect($result->rejection_reason)->toBe('Pas disponible');
        expect($result->rejected_at)->not->toBeNull();
    }

    /**
     * Test confirm deposit
     */
    public function test_confirm_deposit(): void
    {
        $this->bookingRequest->update([
            'status' => BookingRequest::STATUS_ACCEPTED,
            'total_deposit_amount' => 60,
        ]);
        
        $result = $this->service->confirmDeposit(
            $this->bookingRequest,
            'pi_test_123456'
        );
        
        expect($result->status)->toBe(BookingRequest::STATUS_DEPOSIT_PAID);
        expect($result->deposit_paid_at)->not->toBeNull();
        expect($result->stripe_payment_intent_id)->toBe('pi_test_123456');
    }

    /**
     * Test send design increments version counter
     */
    public function test_send_design_increments_version_counter(): void
    {
        $this->bookingRequest->update([
            'status' => BookingRequest::STATUS_DEPOSIT_PAID,
            'design_versions_used' => 0,
            'included_design_versions' => 3,
        ]);
        
        $this->service->sendDesign(
            $this->bookingRequest,
            [UploadedFile::fake()->image('design.jpg')],
            'Voici le design'
        );
        
        expect($this->bookingRequest->fresh()->design_versions_used)->toBe(1);
        expect($this->bookingRequest->fresh()->status)->toBe(BookingRequest::STATUS_DESIGN_SENT);
    }

    /**
     * Test cannot send more designs than allowed for free plan
     */
    public function test_cannot_send_more_designs_than_allowed_for_free_plan(): void
    {
        $tattooer = Tattooer::factory()->create(['is_subscribed' => false]);
        $bookingRequest = BookingRequest::factory()->create([
            'bookable_id' => $tattooer->id,
            'status' => BookingRequest::STATUS_DESIGN_SENT,
            'design_versions_used' => 3,
            'included_design_versions' => 3,
        ]);
        
        $this->expectException(BookingException::class);
        $this->expectExceptionMessage('Nombre maximum de versions atteint.');
        
        $this->service->sendDesign(
            $bookingRequest,
            [UploadedFile::fake()->image('design.jpg')]
        );
    }

    /**
     * Test confirm appointment creates appointment record
     */
    public function test_confirm_appointment_creates_appointment_record(): void
    {
        $this->bookingRequest->update([
            'status' => BookingRequest::STATUS_DESIGN_SENT,
            'estimated_total_price' => 200,
            'total_deposit_amount' => 60,
        ]);
        
        $appointment = $this->service->confirmAppointment(
            $this->bookingRequest,
            now()->addWeek(),
            120 // 2 heures
        );
        
        expect($appointment)->not->toBeNull();
        expect($appointment->remaining_amount)->toBe(140.0);
        expect($this->bookingRequest->fresh()->status)->toBe(BookingRequest::STATUS_CONFIRMED);
    }

    /**
     * Test cancel booking request
     */
    public function test_cancel_booking_request(): void
    {
        $result = $this->service->cancel(
            $this->bookingRequest,
            'Client a changé d\'avis',
            false
        );
        
        expect($result->status)->toBe(BookingRequest::STATUS_CANCELLED);
        expect($result->cancellation_reason)->toBe('Client a changé d\'avis');
        expect($result->cancelled_at)->not->toBeNull();
    }

    /**
     * Test get tattooer stats
     */
    public function test_get_tattooer_stats(): void
    {
        // Créer quelques booking requests
        BookingRequest::factory()->count(3)->create([
            'bookable_id' => $this->tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_PENDING,
        ]);
        
        BookingRequest::factory()->create([
            'bookable_id' => $this->tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_CONFIRMED,
            'estimated_total_price' => 300,
        ]);
        
        $stats = $this->service->getTattooerStats($this->tattooer);
        
        expect($stats['total'])->toBe(4);
        expect($stats['pending'])->toBe(3);
        expect($stats['confirmed'])->toBe(1);
        expect($stats['total_revenue'])->toBe(300.0);
    }
}
