<?php

namespace Tests\Feature\Policies;

use App\Models\User;
use App\Models\Conversation;
use App\Models\BookingRequest;
use App\Models\Tattooer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Participant can view conversation
     */
    public function test_participant_can_view_conversation(): void
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
        ]);
        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
        ]);
        
        expect($client->can('view', $conversation))->toBeTrue();
    }

    /**
     * Non-participant cannot view conversation
     */
    public function test_non_participant_cannot_view_conversation(): void
    {
        $client1 = User::factory()->client()->create();
        $client2 = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client2->client->id,
        ]);
        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
        ]);
        
        expect($client1->can('view', $conversation))->toBeFalse();
    }

    /**
     * Participant can send message in active conversation
     */
    public function test_participant_can_send_message_in_active_conversation(): void
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
        ]);
        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
            'status' => 'active',
            'is_expired' => false,
        ]);
        
        expect($client->can('sendMessage', $conversation))->toBeTrue();
    }

    /**
     * Cannot send message in expired conversation
     */
    public function test_cannot_send_message_in_expired_conversation(): void
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
        ]);
        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
            'is_expired' => true,
        ]);
        
        expect($client->can('sendMessage', $conversation))->toBeFalse();
    }

    /**
     * Cannot send message in archived conversation
     */
    public function test_cannot_send_message_in_archived_conversation(): void
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
        ]);
        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
            'status' => 'archived',
        ]);
        
        expect($client->can('sendMessage', $conversation))->toBeFalse();
    }

    /**
     * Only PRO can archive conversations
     */
    public function test_only_pro_can_archive_conversations(): void
    {
        $tattooerFree = User::factory()->tattooer()->create();
        $tattooerFree->tattooer->update(['is_subscribed' => false]);
        
        $tattooerPro = User::factory()->tattooer()->create();
        $tattooerPro->tattooer->update(['is_subscribed' => true]);
        
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $tattooerFree->tattooer->id,
            'bookable_type' => Tattooer::class,
        ]);
        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
        ]);
        
        expect($tattooerFree->can('archive', $conversation))->toBeFalse();
        
        $booking->update(['bookable_id' => $tattooerPro->tattooer->id]);
        expect($tattooerPro->can('archive', $conversation))->toBeTrue();
    }

    /**
     * Participant can download attachments
     */
    public function test_participant_can_download_attachments(): void
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
        ]);
        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
        ]);
        
        expect($client->can('downloadAttachment', $conversation))->toBeTrue();
    }

    /**
     * Non-participant cannot download attachments
     */
    public function test_non_participant_cannot_download_attachments(): void
    {
        $client1 = User::factory()->client()->create();
        $client2 = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client2->client->id,
        ]);
        $conversation = Conversation::factory()->create([
            'booking_request_id' => $booking->id,
        ]);
        
        expect($client1->can('downloadAttachment', $conversation))->toBeFalse();
    }

    /**
     * Admin can delete any conversation
     */
    public function test_admin_can_delete_any_conversation(): void
    {
        $admin = User::factory()->admin()->create();
        $conversation = Conversation::factory()->create();
        
        expect($admin->can('delete', $conversation))->toBeTrue();
    }

    /**
     * Regular user cannot delete conversation
     */
    public function test_regular_user_cannot_delete_conversation(): void
    {
        $client = User::factory()->client()->create();
        $conversation = Conversation::factory()->create();
        
        expect($client->can('delete', $conversation))->toBeFalse();
    }
}
