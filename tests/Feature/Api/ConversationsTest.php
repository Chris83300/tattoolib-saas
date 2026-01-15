<?php

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;

beforeEach(function () {
    test()->user1 = User::factory()->create();
    test()->user2 = User::factory()->create();
    actingAs(test()->user1, 'sanctum');
});

// Tests de création de conversation
test('users can create conversation', function () {
    $response = postJson('/api/conversations', [
        'participant_id' => test()->user2->id,
        'message' => 'Bonjour, je souhaite discuter d\'un tattoo'
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'participants',
            'created_at'
        ]);

    test()->assertDatabaseHas('conversations', []);
    test()->assertDatabaseHas('messages', [
        'content' => 'Bonjour, je souhaite discuter d\'un tattoo'
    ]);
});

// Tests de liste des conversations
test('user can see their conversations', function () {
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user1->id, test()->user2->id]);

    $response = getJson('/api/conversations');

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'id',
                'participants',
                'last_message',
                'created_at'
            ]
        ]);
});

test('user can see conversation details', function () {
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user1->id, test()->user2->id]);

    $response = getJson("/api/conversations/{$conversation->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $conversation->id
        ]);
});

// Tests des messages
test('user can send message in conversation', function () {
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user1->id, test()->user2->id]);

    $response = postJson("/api/conversations/{$conversation->id}/messages", [
        'content' => 'Nouveau message de test'
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'content' => 'Nouveau message de test'
        ]);

    test()->assertDatabaseHas('messages', [
        'conversation_id' => $conversation->id,
        'user_id' => test()->user1->id,
        'content' => 'Nouveau message de test'
    ]);
});

test('user can see messages in conversation', function () {
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user1->id, test()->user2->id]);

    Message::factory()->create([
        'conversation_id' => $conversation->id,
        'user_id' => test()->user1->id,
        'content' => 'Message de test'
    ]);

    $response = getJson("/api/conversations/{$conversation->id}/messages");

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'id',
                'content',
                'user_id',
                'created_at'
            ]
        ]);
});

test('user can delete their own message', function () {
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user1->id, test()->user2->id]);

    $message = Message::factory()->create([
        'conversation_id' => $conversation->id,
        'user_id' => test()->user1->id,
        'content' => 'Message à supprimer'
    ]);

    $response = deleteJson("/api/conversations/{$conversation->id}/messages/{$message->id}");

    $response->assertStatus(204);
    test()->assertDatabaseMissing('messages', [
        'id' => $message->id
    ]);
});

// Tests d'autorisation
test('user cannot access conversation they are not part of', function () {
    $otherUser = User::factory()->create();
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user2->id, $otherUser->id]);

    $response = getJson("/api/conversations/{$conversation->id}");
    $response->assertStatus(403);
});

test('user cannot send message in conversation they are not part of', function () {
    $otherUser = User::factory()->create();
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user2->id, $otherUser->id]);

    $response = postJson("/api/conversations/{$conversation->id}/messages", [
        'content' => 'Message non autorisé'
    ]);

    $response->assertStatus(403);
});

test('user cannot delete other users message', function () {
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user1->id, test()->user2->id]);

    $message = Message::factory()->create([
        'conversation_id' => $conversation->id,
        'user_id' => test()->user2->id,
        'content' => 'Message d\'un autre utilisateur'
    ]);

    $response = deleteJson("/api/conversations/{$conversation->id}/messages/{$message->id}");
    $response->assertStatus(403);
});

// Tests de fonctionnalités avancées
test('user can mark conversation as read', function () {
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user1->id, test()->user2->id]);

    $response = postJson("/api/conversations/{$conversation->id}/mark-as-read");
    $response->assertStatus(200);
});

test('user can archive conversation', function () {
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user1->id, test()->user2->id]);

    $response = postJson("/api/conversations/{$conversation->id}/archive");
    $response->assertStatus(200);
});

test('user can see archived conversations', function () {
    $conversation = Conversation::factory()->create();
    $conversation->participants()->attach([test()->user1->id, test()->user2->id]);

    $response = getJson('/api/conversations/archived');
    $response->assertStatus(200);
});
