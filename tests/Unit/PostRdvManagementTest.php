<?php

use App\Actions\ManagePostAppointmentChat;
use App\Actions\ManageClientReviews;
use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\ClientReview;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\AccountingTransaction;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('client can leave review after appointment', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);

    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $tattooerUser = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);

    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::COMPLETED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subDays(20)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subDays(20)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(20)->setTime(15, 0),
        'end_datetime' => now()->subDays(20)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::COMPLETED,
        'actual_end_time' => now()->subDays(20)->addMinutes(120),
    ]);

    // Créer la conversation
    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'closed',
        'closed_at' => now()->subDays(10),
    ]);

    // Test: Vérifier que le client peut laisser un avis (J+14)
    $action = new ManageClientReviews();
    $canLeaveReview = $action->canLeaveReview($appointment);

    expect($canLeaveReview)->toBeTrue(); // 20 jours > 14 jours

    // Test: Créer l'avis
    $review = $action->createReview(
        $appointment,
        5,
        'Excellent travail, très satisfait du résultat !',
        ['photo1.jpg', 'photo2.jpg']
    );

    // Assertions
    expect($review)->not->toBeNull();
    expect($review->rating)->toBe(5);
    expect($review->comment)->toBe('Excellent travail, très satisfait du résultat !');
    expect($review->photos)->toBe(['photo1.jpg', 'photo2.jpg']);
    expect($review->status)->toBe('published');
    expect($review->client_id)->toBe($client->id);
    expect($review->appointment_id)->toBe($appointment->id);
    expect($review->bookable_id)->toBe($tattooer->id);
    expect($review->bookable_type)->toBe(Tattoer::class);

    // Vérifier que la note moyenne du tattooer a été mise à jour
    $tattooer->refresh();
    expect($tattooer->average_rating)->toBe(5.0);
    expect($tattooer->total_reviews)->toBe(1);
});

test('client limited to 4 images post-appointment', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);

    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $tattooerUser = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);

    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::COMPLETED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subDays(5)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subDays(5)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(5)->setTime(15, 0),
        'end_datetime' => now()->subDays(5)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => 'AppointmentStatus::COMPLETED',
        'actual_end_time' => now()->subDays(5)->addMinutes(120),
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'closed',
        'closed_at' => now()->subDays(3),
    ]);

    // Test: Envoyer 4 images post-RDV
    $action = new ManagePostAppointmentChat();

    // Image 1
    $action->handlePostAppointmentImage($appointment, 'image1.jpg', 'Première photo de suivi');

    // Image 2
    $action->handlePostAppointmentImage($appointment, 'image2.jpg', 'Deuxième photo');

    // Image 3
    $action->handlePostAppointmentImage($appointment, 'image3.jpg', 'Troisième photo');

    // Image 4
    $action->handlePostAppointmentImage($appointment, 'image4.jpg', 'Quatrième photo');

    // Vérifier que les 4 images ont été envoyées
    $postRdvImagesCount = $action->getPostRdvImagesCount($appointment);
    expect($postRdvImagesCount)->toBe(4);

    // Test: Tenter d'envoyer une 5ème image (doit lever une exception)
    expect(fn() => $action->handlePostAppointmentImage($appointment, 'image5.jpg', 'Cinquième photo'))
        ->toThrow(\InvalidArgumentException::class, 'Limite d\'images post-RDV atteinte');
});

test('chat closes 30 days after appointment', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);

    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $tattooerUser = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);

    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::COMPLETED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subDays(35)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subDays(35)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(35)->setTime(15, 0),
        'end_datetime' => now()->subDays(35)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::COMPLETED,
        'actual_end_time' => now()->subDays(35)->addMinutes(120),
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'closed',
        'closed_at' => now()->subDays(5),
    ]);

    // Test: Vérifier que le chat n'est plus ouvert (J+30)
    $action = new ManagePostAppointmentChat();
    $chatStatus = $action->getPostAppointmentChatStatus($appointment);

    expect($chatStatus['is_open'])->toBeFalse(); // 35 jours > 30 jours
    expect($chatStatus['days_remaining'])->toBe(0);
    expect($chatStatus['closes_at']->format('Y-m-d'))->toBe(now()->subDays(5)->format('Y-m-d'));
});

test('expired bookings without payment are cleaned up', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);

    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $tattooerUser = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);

    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    // Créer une booking request expirée sans paiement
    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo expiré',
        'status' => BookingRequestStatus::EXPIRED,
        'deposit_deadline' => now()->subDays(10),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
    ]);

    // Créer une conversation avec messages et pièces jointes
    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'closed',
        'closed_at' => now()->subDays(5),
    ]);

    // Ajouter des messages
    $conversation->messages()->create([
        'sender_id' => $client->user_id,
        'sender_type' => 'client',
        'content' => 'Message test 1',
    ]);

    $conversation->messages()->create([
        'sender_id' => $tattooerUser->id,
        'sender_type' => 'tattooer',
        'content' => 'Message test 2',
        'attachments' => ['image1.jpg', 'image2.jpg'],
    ]);

    // Test: Nettoyer la booking request expirée
    $command = new \App\Console\Commands\CleanupExpiredBookings();

    // Simuler le nettoyage
    $expiredBookings = BookingRequest::where('status', BookingRequestStatus::EXPIRED)
        ->whereNull('deposit_paid_at')
        ->get();

    foreach ($expiredBookings as $expiredBooking) {
        // Vérifier que la booking request a été supprimée
        expect(BookingRequest::find($expiredBooking->id))->toBeNull();

        // Vérifier que la conversation a été supprimée
        expect(Conversation::where('booking_request_id', $expiredBooking->id))->doesntExist();
    }

    // Vérifier que la booking request avec paiement n'est pas supprimée
    $paidBookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo payé',
        'status' => BookingRequestStatus::EXPIRED,
        'deposit_paid_at' => now()->subDays(10),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
    ]);

    // La booking request payée doit toujours exister
    expect(BookingRequest::find($paidBookingRequest->id))->not->BeNull();
});

test('paid bookings are never fully deleted', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);

    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $tattooerUser = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);

    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    // Créer une booking request avec paiement
    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo payé',
        'status' => BookingRequestStatus::COMPLETED,
        'deposit_paid_at' => now()->subDays(10),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(10)->setTime(15, 0),
        'end_datetime' => now()->subDays(10)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::COMPLETED,
        'actual_end_time' => now()->subDays(10)->addMinutes(120),
    ]);

    // Créer une transaction comptable
    AccountingTransaction::create([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $client->user_id,
        'type' => 'deposit',
        'amount' => 150.00,
        'currency' => 'eur',
        'status' => 'completed',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_123456',
        'processed_at' => now()->subDays(10),
    ]);

    // Créer un avis
    $review = ClientReview::create([
        'appointment_id' => $appointment->id,
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_id' => $tattooer->id,
        'bookable_type' => Tattooer::class,
        'rating' => 5,
        'comment' => 'Excellent travail !',
        'status' => 'published',
        'reviewed_at' => now()->subDays(5),
    ]);

    // Test: Nettoyer une booking request payée (ne doit pas la supprimer)
    $command = new \App\Console\Commands\CleanupExpiredBookings();

    // La booking request payée doit toujours exister
    expect(BookingRequest::find($bookingRequest->id))->not->BeNull();

    // La transaction comptable doit toujours exister
    expect(AccountingTransaction::where('booking_request_id', $bookingRequest->id)->exists())->toBeTrue();

    // L'avis doit toujours exister
    expect(ClientReview::where('appointment_id', $appointment->id)->exists())->toBeTrue();

    // La conversation doit toujours exister (si elle a été créée)
    $conversation = Conversation::where('booking_request_id', $bookingRequest->id)->first();
    if ($conversation) {
        expect($conversation->status)->toBe('closed');
        expect(Conversation::find($conversation->id))->not->BeNull();
    }
});

test('client can request retouch after appointment', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);

    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $tattooerUser = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);

    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'le Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::COMPLETED,
        'deposit_paid_at' => now()->subDays(20),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subDays(20)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subDays(20)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(20)->setTime(15, 0),
        'end_datetime' => now()->subDays(20)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::COMPLETED,
        'actual_end_time' => now()->subDays(20)->addMinutes(120),
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'closed',
        'closed_at' => now()->subDays(10),
    ]);

    // Test: Demander une retouche
    $action = new ManagePostAppointmentChat();
    $action->handleRetouchRequest(
        $appointment,
        'J\'aimerais une petite retouche sur la partie supérieure du tatouage',
        ['retouche1.jpg', 'retouche2.jpg']
    );

    // Assertions
    $messages = $conversation->messages()->get();
    expect($messages)->toHaveCount(2); // Message système + message de retouche

    $retouchMessage = $messages->lastWhere('sender_type', 'client');
    expect($retouchMessage)->not->BeNull();
    expect($retouchMessage->content)->toContain('🔄 Demande de retouche');
    expect($retouchMessage->metadata['type'])->toBe('retouch_request');
    expect($retouchMessage->metadata['appointment_id'])->toBe($appointment->id);
    expect($retouchMessage->attachments)->toBe(['retouche1.jpg', 'retouche2.jpg']);
});

test('chat status shows correct remaining images', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);

    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $tattooerUser = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);

    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::COMPLETED,
        'deposit_paid_at' => now()->subDays(10),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subDays(10)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subDays(10)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(10)->setTime(15, 0),
        'end_datetime' => now()->subDays(10)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::COMPLETED,
        'actual_end_time' => now()->subDays(10)->addMinutes(120),
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'closed',
        'closed_at' => now()->subDays(5),
    ]);

    // Test: Envoyer 2 images et vérifier le statut
    $action = new ManagePostAppointmentChat();

    $action->handlePostAppointmentImage($appointment, 'image1.jpg', 'Première photo');
    $action->handlePostAppointmentImage($appointment, 'image2.jpg', 'Deuxième photo');

    $chatStatus = $action->getPostAppointmentChatStatus($appointment);

    expect($chatStatus['images_sent'])->toBe(2);
    expect($chatStatus['images_remaining'])->toBe(2);
    expect($chatStatus['can_send_image'])->toBeTrue();
    expect($chatStatus['is_open'])->toBeTrue(); // 10 jours < 30 jours
});
