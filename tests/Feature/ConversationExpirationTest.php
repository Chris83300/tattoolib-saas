<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ConversationExpirationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function conversation_expires_after_7_days_if_deposit_not_paid()
    {
        $booking = BookingRequest::factory()->create([
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
        ]);

        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
            'expiry_type' => 'deposit_pending',
            'deposit_deadline_at' => now()->addDays(7),
        ]);

        // Avancer le temps de 8 jours
        Carbon::setTestNow(now()->addDays(8));

        // Exécuter commande d'expiration
        $this->artisan('conversations:check-expiration');

        $conversation->refresh();
        expect($conversation->is_expired)->toBeTrue();
        expect($conversation->status)->toBe('expired');
    }

    /** @test */
    public function conversation_becomes_permanent_after_deposit_paid()
    {
        $booking = BookingRequest::factory()->create([
            'status' => BookingRequest::STATUS_DEPOSIT_PAID,
        ]);

        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
            'expiry_type' => 'permanent',
            'deposit_deadline_at' => null,
        ]);

        // Avancer le temps de 100 jours
        Carbon::setTestNow(now()->addDays(100));

        $this->artisan('conversations:check-expiration');

        $conversation->refresh();
        expect($conversation->is_expired)->toBeFalse();
    }

    /** @test */
    public function free_plan_conversation_deleted_after_appointment()
    {
        $tattooer = User::factory()->tattooer()->create();
        $tattooer->tattooer->update(['is_subscribed' => false]);

        $booking = BookingRequest::factory()->create([
            'bookable_id' => $tattooer->tattooer->id,
            'bookable_type' => get_class($tattooer->tattooer),
            'status' => BookingRequest::STATUS_CONFIRMED,
        ]);

        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
            'expiry_type' => 'post_appointment',
            'appointment_completed_at' => now()->subDay(),
        ]);

        $this->artisan('conversations:cleanup');

        $this->assertDatabaseMissing('conversations', [
            'id' => $conversation->id,
        ]);
    }

    /** @test */
    public function pro_plan_conversation_archived_after_appointment()
    {
        $tattooer = User::factory()->tattooer()->create();
        $tattooer->tattooer->update(['is_subscribed' => true]);

        $booking = BookingRequest::factory()->create([
            'bookable_id' => $tattooer->tattooer->id,
            'bookable_type' => get_class($tattooer->tattooer),
            'status' => BookingRequest::STATUS_CONFIRMED,
        ]);

        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
            'expiry_type' => 'post_appointment',
            'appointment_completed_at' => now()->subDay(),
        ]);

        $this->artisan('conversations:cleanup');

        $conversation->refresh();
        expect($conversation->status)->toBe('archived');
        expect($conversation->archived_at)->not->toBeNull();
        expect($conversation->images_preserved)->toBeTrue();
    }

    /** @test */
    public function cannot_send_message_in_expired_conversation()
    {
        $client = User::factory()->client()->create();
        
        $conversation = Conversation::factory()->create([
            'is_expired' => true,
            'status' => 'expired',
        ]);

        $response = $this->actingAs($client, 'sanctum')
            ->postJson('/api/messages', [
                'conversation_id' => $conversation->id,
                'content' => 'Test message',
            ]);

        $response->assertForbidden();
    }

    /** @test */
    public function warning_sent_2_days_before_expiration()
    {
        $booking = BookingRequest::factory()->create([
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
        ]);

        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
            'expiry_type' => 'deposit_pending',
            'deposit_deadline_at' => now()->addDays(2),
            'expiry_warning_sent_at' => null,
        ]);

        $this->artisan('conversations:send-expiration-warnings');

        $conversation->refresh();
        expect($conversation->expiry_warning_sent_at)->not->toBeNull();
    }
}
