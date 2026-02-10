<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('login');
        RateLimiter::clear('api');
    }

    /** @test */
    public function blocks_after_5_failed_login_attempts()
    {
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/login', [
                'email' => 'wrong@example.com',
                'password' => 'wrongpassword',
            ]);
            
            $response->assertStatus(422);
        }

        // 6ème tentative bloquée
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(429);
        $response->assertJson([
            'error' => 'Trop de requêtes. Réessayez dans',
        ]);
    }

    /** @test */
    public function api_rate_limit_for_unauthenticated_users()
    {
        for ($i = 0; $i < 10; $i++) {
            $response = $this->getJson('/api/tattooers');
            $response->assertOk();
        }

        // 11ème requête bloquée
        $response = $this->getJson('/api/tattooers');
        $response->assertStatus(429);
    }

    /** @test */
    public function authenticated_users_have_higher_rate_limit()
    {
        $user = User::factory()->client()->create();

        for ($i = 0; $i < 60; $i++) {
            $response = $this->actingAs($user, 'sanctum')
                ->getJson('/api/tattooers');
            $response->assertOk();
        }

        // 61ème requête bloquée
        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/tattooers');
        $response->assertStatus(429);
    }

    /** @test */
    public function upload_rate_limit_enforced()
    {
        $user = User::factory()->client()->create();
        $conversation = \App\Models\Conversation::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            $response = $this->actingAs($user, 'sanctum')
                ->postJson('/api/messages', [
                    'conversation_id' => $conversation->id,
                    'attachment' => \Illuminate\Http\UploadedFile::fake()->image('test.jpg'),
                ]);
            $response->assertStatus(201);
        }

        // 11ème upload bloqué
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/messages', [
                'conversation_id' => $conversation->id,
                'attachment' => \Illuminate\Http\UploadedFile::fake()->image('test.jpg'),
            ]);
        $response->assertStatus(429);
    }

    /** @test */
    public function payment_rate_limit_prevents_spam()
    {
        $user = User::factory()->client()->create();
        $booking = \App\Models\BookingRequest::factory()->create([
            'client_id' => $user->client->id,
            'status' => 'awaiting_deposit',
        ]);

        for ($i = 0; $i < 3; $i++) {
            $response = $this->actingAs($user, 'sanctum')
                ->postJson("/api/booking-requests/{$booking->id}/confirm-deposit", [
                    'payment_intent_id' => 'pi_test_' . $i,
                ]);
            // Peut échouer pour d'autres raisons mais pas rate limit
            expect($response->status())->not->toBe(429);
        }

        // 4ème tentative bloquée
        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/confirm-deposit", [
                'payment_intent_id' => 'pi_test_4',
            ]);
        $response->assertStatus(429);
    }

    /** @test */
    public function rate_limit_returns_retry_after_header()
    {
        for ($i = 0; $i < 11; $i++) {
            $this->getJson('/api/tattooers');
        }

        $response = $this->getJson('/api/tattooers');
        $response->assertStatus(429);
        
        expect($response->headers->has('Retry-After'))->toBeTrue();
        expect($response->json('retry_after'))->toBeGreaterThan(0);
    }
}
