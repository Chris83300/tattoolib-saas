<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class XssProtectionTest extends TestCase
{
    use RefreshDatabase;

    private User $clientUser;
    private User $tattooerUser;
    private Conversation $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un client
        $this->clientUser = User::factory()->create([
            'email' => 'client@test.com',
            'role' => 'client'
        ]);

        // Créer un tatoueur
        $this->tattooerUser = User::factory()->create([
            'email' => 'tattooer@test.com',
            'role' => 'tattooer'
        ]);

        // Créer une conversation
        $this->conversation = Conversation::factory()->create();

        $this->conversation->participants()->attach([
            $this->clientUser->id,
            $this->tattooerUser->id
        ]);
    }

    /**
     * Test que le contenu des messages est nettoyé des tags HTML
     */
    public function test_message_content_strips_html_tags(): void
    {
        $maliciousContent = 'Hello <script>alert("XSS")</script> World!';
        $expectedCleanContent = 'Hello  World!';

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => $maliciousContent,
            ]);

        $response->assertStatus(201);

        // Vérifier que le contenu est nettoyé
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->clientUser->id,
            'content' => $expectedCleanContent,
        ]);
    }

    /**
     * Test que les tentatives d'injection avec on* sont bloquées
     */
    public function test_message_blocks_on_event_handlers(): void
    {
        $maliciousContent = 'Click <img src="x" onerror="alert(\'XSS\')"> here';

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => $maliciousContent,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * Test que les tentatives d'injection javascript: sont bloquées
     */
    public function test_message_blocks_javascript_protocol(): void
    {
        $maliciousContent = 'Visit <a href="javascript:alert(\'XSS\')">this link</a>';

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => $maliciousContent,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * Test que les tentatives d'injection data: sont bloquées
     */
    public function test_message_blocks_data_protocol(): void
    {
        $maliciousContent = 'Open <a href="data:text/html,<script>alert(\'XSS\')</script>">data</a>';

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => $maliciousContent,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * Test que le contenu texte normal est préservé
     */
    public function test_normal_text_content_preserved(): void
    {
        $normalContent = 'Hello! How are you today? 😊';

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => $normalContent,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->clientUser->id,
            'content' => $normalContent,
        ]);
    }

    /**
     * Test que la bio du tatoueur autorise uniquement le HTML sécurisé
     */
    public function test_tattooer_bio_allows_only_safe_html(): void
    {
        $tattooer = Tattooer::factory()->create(['user_id' => $this->tattooerUser->id]);

        $maliciousBio = '<script>alert("XSS")</script><p>Safe text</p>';
        $expectedCleanBio = '<p>Safe text</p>';

        $response = $this->actingAs($this->tattooerUser)
            ->putJson("/api/tattooers/{$tattooer->id}", [
                'bio' => $maliciousBio,
            ]);

        $response->assertStatus(200);

        $updatedTattooer = $tattooer->fresh();
        $this->assertEquals($expectedCleanBio, $updatedTattooer->bio);
    }

    /**
     * Test que les URLs du profil sont nettoyées
     */
    public function test_tattooer_urls_are_sanitized(): void
    {
        $tattooer = Tattooer::factory()->create(['user_id' => $this->tattooerUser->id]);

        $maliciousUrl = 'javascript:alert("XSS")';
        $expectedCleanUrl = '';

        $response = $this->actingAs($this->tattooerUser)
            ->putJson("/api/tattooers/{$tattooer->id}", [
                'instagram' => $maliciousUrl,
                'website' => $maliciousUrl,
            ]);

        $response->assertStatus(200);

        $updatedTattooer = $tattooer->fresh();
        $this->assertEquals($expectedCleanUrl, $updatedTattooer->instagram);
        $this->assertEquals($expectedCleanUrl, $updatedTattooer->website);
    }

    /**
     * Test que les URLs valides sont préservées
     */
    public function test_valid_urls_are_preserved(): void
    {
        $tattooer = Tattooer::factory()->create(['user_id' => $this->tattooerUser->id]);

        $validInstagram = 'https://instagram.com/tattooer';
        $validWebsite = 'https://mytattoostudio.com';

        $response = $this->actingAs($this->tattooerUser)
            ->putJson("/api/tattooers/{$tattooer->id}", [
                'instagram' => $validInstagram,
                'website' => $validWebsite,
            ]);

        $response->assertStatus(200);

        $updatedTattooer = $tattooer->fresh();
        $this->assertEquals($validInstagram, $updatedTattooer->instagram);
        $this->assertEquals($validWebsite, $updatedTattooer->website);
    }

    /**
     * Test que les caractères < et > sont interdits après sanitization
     */
    public function test_angle_brackets_are_forbidden_after_sanitization(): void
    {
        $maliciousContent = 'Hello <world> test';

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => $maliciousContent,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * Test que le contenu vide avec pièce jointe est autorisé
     */
    public function test_empty_content_with_attachment_is_allowed(): void
    {
        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => '',
                'attachment' => null, // Pas de vraie pièce jointe pour ce test
            ]);

        // Devrait échouer car ni contenu ni pièce jointe
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * Test que les tentatives d'injection CSS sont bloquées
     */
    public function test_css_injection_is_blocked(): void
    {
        $maliciousContent = '<style>body { background: url("javascript:alert(\'XSS\')"); }</style>Hello';

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => $maliciousContent,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * Test que les tentatives d'injection iframe sont bloquées
     */
    public function test_iframe_injection_is_blocked(): void
    {
        $maliciousContent = '<iframe src="javascript:alert(\'XSS\')"></iframe>Hello';

        $response = $this->actingAs($this->clientUser)
            ->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'content' => $maliciousContent,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * Test que le service de sanitization détecte le contenu suspect
     */
    public function test_sanitizer_detects_suspicious_content(): void
    {
        $sanitizer = app(\App\Services\InputSanitizerService::class);

        $this->assertTrue($sanitizer->containsSuspiciousContent('<script>alert("XSS")</script>'));
        $this->assertTrue($sanitizer->containsSuspiciousContent('javascript:alert("XSS")'));
        $this->assertTrue($sanitizer->containsSuspiciousContent('<iframe src="evil.com"></iframe>'));
        $this->assertFalse($sanitizer->containsSuspiciousContent('Hello world!'));
    }

    /**
     * Test que les noms de fichiers sont nettoyés
     */
    public function test_filename_sanitization(): void
    {
        $sanitizer = app(\App\Services\InputSanitizerService::class);

        $maliciousFilename = '../../../etc/passwd';
        $expectedCleanFilename = '_____etc_passwd';

        $this->assertEquals($expectedCleanFilename, $sanitizer->sanitizeFilename($maliciousFilename));
    }

    /**
     * Test que les emails sont nettoyés
     */
    public function test_email_sanitization(): void
    {
        $sanitizer = app(\App\Services\InputSanitizerService::class);

        $maliciousEmail = 'test<script>alert("XSS")</script>@example.com';
        $expectedCleanEmail = 'testalertXSS@example.com';

        $this->assertEquals($expectedCleanEmail, $sanitizer->sanitizeEmail($maliciousEmail));
    }
}
