<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== TEST DU DÉCOMPTEUR DE DESSINS ===\n";

// Récupérer le booking request 31
$bookingRequest = \App\Models\BookingRequest::find(31);

if (!$bookingRequest) {
    echo "Booking Request 31 non trouvé\n";
    exit;
}

echo "État actuel du BR 31:\n";
echo "- Design versions used: " . ($bookingRequest->design_versions_used ?? 0) . "\n";
echo "- Modifications used: " . ($bookingRequest->modifications_used ?? 0) . "\n";
echo "- Included design versions: " . ($bookingRequest->included_design_versions ?? 2) . "\n";
echo "- Modifications per version: " . ($bookingRequest->modifications_per_version ?? 2) . "\n";

// Simuler l'envoi d'un message avec pièce jointe
echo "\n=== SIMULATION ENVOI DESSIN ===\n";

// Créer un message test
$message = new \App\Models\Message();
$message->conversation_id = 7; // Conversation du BR 31
$message->booking_request_id = 31;
$message->sender_id = 9; // Client user ID
$message->sender_type = 'client';
$message->content = 'Voici ma proposition de design :';
$message->save();

echo "Message test créé avec ID: " . $message->id . "\n";

// Simuler l'ajout d'un média
$testFile = tempnam(sys_get_temp_dir(), 'test_');

// Créer une vraie image PNG (1x1 pixel transparent)
$imageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgOyA/8AAAABJRU5ErkJggg==');

file_put_contents($testFile, $imageData);

// Renommer en .png pour le test
$testFilePng = $testFile . '.png';
rename($testFile, $testFilePng);

// Créer un faux fichier uploadé
$uploadedFile = new \Illuminate\Http\UploadedFile(
    $testFilePng,
    'design_proposition_v1.png',
    'image/png',
    null,
    true
);

$message->addMedia($uploadedFile)->toMediaCollection('attachments');

echo "Média ajouté au message\n";

// Tester la fonction updateDesignCounters
$clientController = new \App\Http\Controllers\ClientController();

// Utiliser la réflexion pour appeler la méthode privée
$reflection = new ReflectionClass($clientController);
$method = $reflection->getMethod('updateDesignCounters');
$method->setAccessible(true);

echo "Appel de updateDesignCounters...\n";
$method->invoke($clientController, $bookingRequest, $message);

// Vérifier les compteurs après mise à jour
$updatedBookingRequest = \App\Models\BookingRequest::find(31); // Recharger depuis la base
echo "\n=== ÉTAT APRÈS MISE À JOUR ===\n";
echo "- Design versions used: " . $updatedBookingRequest->design_versions_used . "\n";
echo "- Modifications used: " . $updatedBookingRequest->modifications_used . "\n";

// Nettoyer
unlink($testFile);
