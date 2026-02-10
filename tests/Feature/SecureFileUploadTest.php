<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SecureFileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('secure_uploads');
    }

    /** @test */
    public function rejects_executable_files()
    {
        $user = User::factory()->client()->create();
        $conversation = Conversation::factory()->create();

        // Créer fichier .exe déguisé en .jpg
        $maliciousFile = UploadedFile::fake()->create('malicious.exe', 100);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/messages', [
                'conversation_id' => $conversation->id,
                'content' => 'Test',
                'attachment' => $maliciousFile,
            ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function rejects_files_with_double_extension()
    {
        $user = User::factory()->client()->create();
        $conversation = Conversation::factory()->create();

        $file = UploadedFile::fake()->create('image.jpg.php', 100);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/messages', [
                'conversation_id' => $conversation->id,
                'content' => 'Test',
                'attachment' => $file,
            ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function enforces_file_size_limits()
    {
        $user = User::factory()->client()->create();
        $conversation = Conversation::factory()->create();

        // Créer fichier de 15MB (limite: 10MB)
        $largeFile = UploadedFile::fake()->create('large.jpg', 15360);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/messages', [
                'conversation_id' => $conversation->id,
                'content' => 'Test',
                'attachment' => $largeFile,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['attachment']);
    }

    /** @test */
    public function validates_mime_type_server_side()
    {
        $user = User::factory()->client()->create();
        $conversation = Conversation::factory()->create();

        // Fichier avec extension valide mais MIME incorrect
        $file = UploadedFile::fake()->create('document.jpg', 100, 'application/x-msdownload');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/messages', [
                'conversation_id' => $conversation->id,
                'content' => 'Test',
                'attachment' => $file,
            ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function accepts_valid_image_files()
    {
        $user = User::factory()->client()->create();
        $conversation = Conversation::factory()->create();

        $validImage = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/messages', [
                'conversation_id' => $conversation->id,
                'content' => 'Voici une photo',
                'attachment' => $validImage,
            ]);

        $response->assertStatus(201);
        
        $message = Message::latest()->first();
        expect($message->getMedia('attachments'))->toHaveCount(1);
    }

    /** @test */
    public function accepts_valid_pdf_files()
    {
        $user = User::factory()->client()->create();
        $conversation = Conversation::factory()->create();

        $validPdf = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/messages', [
                'conversation_id' => $conversation->id,
                'content' => 'Voici le document',
                'attachment' => $validPdf,
            ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function sanitizes_file_names()
    {
        $user = User::factory()->client()->create();
        $conversation = Conversation::factory()->create();

        $file = UploadedFile::fake()->image('../../malicious path.jpg');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/messages', [
                'conversation_id' => $conversation->id,
                'attachment' => $file,
            ]);

        $response->assertStatus(201);
        
        $message = Message::latest()->first();
        $media = $message->getFirstMedia('attachments');
        
        // Vérifier que le nom ne contient pas de caractères dangereux
        expect($media->file_name)->not->toContain('..');
        expect($media->file_name)->not->toContain('/');
    }

    /** @test */
    public function download_requires_authorization()
    {
        $user1 = User::factory()->client()->create();
        $user2 = User::factory()->client()->create();
        
        $conversation = Conversation::factory()->create();
        
        $message = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
        ]);
        
        $message->addMedia(UploadedFile::fake()->image('test.jpg'))
            ->toMediaCollection('attachments');

        $media = $message->getFirstMedia('attachments');

        // User2 n'est pas participant
        $response = $this->actingAs($user2, 'sanctum')
            ->get("/api/messages/{$message->id}/download");

        $response->assertForbidden();
    }
}
