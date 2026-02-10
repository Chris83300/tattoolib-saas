<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Exception;

class FileUploadSecurityTest extends TestCase
{
    private User $clientUser;
    private Client $client;
    private Tattooer $tattooer;
    private BookingRequest $bookingRequest;
    private Conversation $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un client
        $this->clientUser = User::factory()->create([
            'email' => 'client@test.com',
            'role' => 'client'
        ]);
        $this->client = Client::factory()->create(['user_id' => $this->clientUser->id]);

        // Créer un tatoueur
        $tattooerUser = User::factory()->create([
            'email' => 'tattooer@test.com',
            'role' => 'tattooer'
        ]);
        $this->tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

        // Créer une demande de réservation
        $this->bookingRequest = BookingRequest::factory()->create([
            'client_id' => $this->client->id,
            'bookable_type' => Tattooer::class,
            'bookable_id' => $this->tattooer->id,
            'status' => 'accepted'
        ]);

        // Créer une conversation
        $this->conversation = Conversation::factory()->create([
            'booking_request_id' => $this->bookingRequest->id
        ]);

        $this->conversation->participants()->attach([
            $this->clientUser->id,
            $tattooerUser->id
        ]);
    }

    /**
     * Test de rejet des fichiers exécutables déguisés
     */
    public function test_reject_executable_files(): void
    {
        // Créer un faux fichier .exe déguisé en .jpg
        $fakeFile = UploadedFile::fake()->createWithContent(
            'malicious.jpg',
            '<?php echo "hack"; ?>'
        );
        $fakeFile->mimeType = 'image/jpeg'; // Forcer le MIME type

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => 'Test message',
                'attachment' => $fakeFile
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['attachment']);
    }

    /**
     * Test de rejet des fichiers avec double extension
     */
    public function test_reject_double_extension_files(): void
    {
        // Créer un fichier avec double extension
        $doubleExtFile = UploadedFile::fake()->create('image.jpg.php', 100);

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => 'Test message',
                'attachment' => $doubleExtFile
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['attachment']);
    }

    /**
     * Test de scan antivirus avec fichier infecté (mock)
     */
    public function test_scan_infected_file_with_antivirus(): void
    {
        // Mock du service antivirus pour simuler une détection
        $this->mock(\App\Services\AntivirusService::class, function ($mock) {
            $mock->shouldReceive('scan')
                ->once()
                ->andThrow(new Exception('Fichier malveillant détecté'));
        });

        $cleanFile = UploadedFile::fake()->image('clean.jpg', 100);

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => 'Test message',
                'attachment' => $cleanFile
            ]);

        $response->assertStatus(500);
    }

    /**
     * Test de respect des limites de taille
     */
    public function test_enforce_file_size_limits(): void
    {
        // Créer une image trop volumineuse (15MB au lieu de 5MB max)
        $oversizedFile = UploadedFile::fake()->image('large.jpg', 15000); // 15MB

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => 'Test message',
                'attachment' => $oversizedFile
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['attachment']);
    }

    /**
     * Test d'acceptation des fichiers valides
     */
    public function test_accept_valid_files(): void
    {
        $validImage = UploadedFile::fake()->image('valid.jpg', 500); // 500KB

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => 'Test message with valid image',
                'attachment' => $validImage
            ]);

        $response->assertStatus(201);

        // Vérifier que le message a été créé
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->clientUser->id,
            'content' => 'Test message with valid image'
        ]);
    }

    /**
     * Test d'acceptation des fichiers PDF valides
     */
    public function test_accept_valid_pdf_files(): void
    {
        $validPdf = UploadedFile::fake()->create('document.pdf', 2000); // 2MB

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => 'Test message with PDF',
                'attachment' => $validPdf
            ]);

        $response->assertStatus(201);
    }

    /**
     * Test de rejet des extensions interdites
     */
    public function test_reject_forbidden_extensions(): void
    {
        $forbiddenFile = UploadedFile::fake()->create('script.js', 100);

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => 'Test message',
                'attachment' => $forbiddenFile
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['attachment']);
    }

    /**
     * Test de validation MIME type réel
     */
    public function test_validate_real_mime_type(): void
    {
        // Créer un fichier avec extension .jpg mais contenu PDF
        $fakeImage = UploadedFile::fake()->createWithContent(
            'fake.jpg',
            '%PDF-1.4 fake pdf content'
        );

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => 'Test message',
                'attachment' => $fakeImage
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['attachment']);
    }

    /**
     * Test de téléchargement sécurisé des pièces jointes
     */
    public function test_secure_file_download(): void
    {
        // Créer un message avec pièce jointe
        $message = Message::factory()->create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->clientUser->id,
            'content' => 'Test message'
        ]);

        $file = UploadedFile::fake()->image('test.jpg', 500);
        $message->addMedia($file)->toMediaCollection('attachments');

        // Test de téléchargement par le propriétaire
        $response = $this->actingAs($this->clientUser)
            ->get("/api/messages/{$message->id}/download");

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/jpeg');
    }

    /**
     * Test de refus de téléchargement par utilisateur non autorisé
     */
    public function test_reject_unauthorized_download(): void
    {
        // Créer un autre utilisateur non autorisé
        $otherUser = User::factory()->create(['role' => 'client']);

        // Créer un message avec pièce jointe
        $message = Message::factory()->create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->clientUser->id,
            'content' => 'Test message'
        ]);

        $file = UploadedFile::fake()->image('test.jpg', 500);
        $message->addMedia($file)->toMediaCollection('attachments');

        // Test de téléchargement par utilisateur non autorisé
        $response = $this->actingAs($otherUser)
            ->get("/api/messages/{$message->id}/download");

        $response->assertStatus(403);
    }
}
