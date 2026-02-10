<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== AUDIT COMPLET DU SYSTÈME DE PAIEMENT ===\n\n";

// 1. Vérifier le BookingRequest
$bookingRequest = App\Models\BookingRequest::find(27);
echo "📋 BOOKING REQUEST (ID: 27)\n";
echo "Status: " . $bookingRequest->status . "\n";
echo "Deposit paid at: " . ($bookingRequest->deposit_paid_at ? $bookingRequest->deposit_paid_at : 'NULL') . "\n";
echo "Deposit amount: " . $bookingRequest->total_deposit_amount . "€\n";
echo "Deposit deadline: " . ($bookingRequest->deposit_deadline ? $bookingRequest->deposit_deadline : 'NULL') . "\n";
echo "Appointment datetime: " . ($bookingRequest->appointment_datetime ? $bookingRequest->appointment_datetime : 'NULL') . "\n";
echo "Appointment duration: " . ($bookingRequest->appointment_duration_minutes ?? 'NULL') . " minutes\n";
echo "Created at: " . $bookingRequest->created_at . "\n";
echo "Updated at: " . $bookingRequest->updated_at . "\n\n";

// 2. Vérifier la Conversation
$conversation = $bookingRequest->conversation;
echo "💬 CONVERSATION\n";
if ($conversation) {
    echo "ID: " . $conversation->id . "\n";
    echo "Status: " . $conversation->status . "\n";
    echo "Created at: " . $conversation->created_at->format('Y-m-d H:i:s') . "\n";
    echo "Deposit deadline at: " . ($conversation->deposit_deadline_at ? $conversation->deposit_deadline_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "Expires at: " . ($conversation->expires_at ? $conversation->expires_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "Chat closes at: " . ($conversation->chat_closes_at ? $conversation->chat_closes_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
} else {
    echo "❌ Aucune conversation trouvée\n";
}
echo "\n";

// 3. Vérifier les Messages
echo "📨 MESSAGES\n";
$messages = $conversation ? $conversation->messages : collect([]);
echo "Nombre de messages: " . $messages->count() . "\n";
if ($messages->isNotEmpty()) {
    echo "Dernier message: " . $messages->last()->created_at->format('Y-m-d H:i:s') . "\n";
    echo "Messages du client: " . $messages->where('sender_type', 'client')->count() . "\n";
    echo "Messages du tattooer: " . $messages->where('sender_type', 'tattooer')->count() . "\n";
}
echo "\n";

// 4. Vérifier l'Appointment
echo "📅 APPOINTMENT\n";
$appointment = $bookingRequest->appointment;
if ($appointment) {
    echo "ID: " . $appointment->id . "\n";
    echo "Status: " . $appointment->status . "\n";
    echo "Start at: " . $appointment->start_at->format('Y-m-d H:i:s') . "\n";
    echo "End at: " . $appointment->end_at->format('Y-m-d H:i:s') . "\n";
    echo "Duration: " . $appointment->start_at->diffInMinutes($appointment->end_at) . " minutes\n";
    echo "Created at: " . $appointment->created_at->format('Y-m-d H:i:s') . "\n";
} else {
    echo "❌ Aucun rendez-vous trouvé\n";
}
echo "\n";

// 5. Vérifier les Accounting Transactions (si elles existent)
echo "💰 ACCOUNTING TRANSACTIONS\n";
try {
    if (class_exists('App\Models\AccountingTransaction')) {
        $transactions = App\Models\AccountingTransaction::where('booking_request_id', 27)->get();
        echo "Nombre de transactions: " . $transactions->count() . "\n";
        foreach ($transactions as $transaction) {
            echo "- ID: " . $transaction->id . ", Type: " . $transaction->type . ", Amount: " . $transaction->amount . "€, Date: " . $transaction->created_at->format('Y-m-d H:i:s') . "\n";
        }
    } else {
        echo "❌ Modèle AccountingTransaction non trouvé\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lors de la vérification des transactions: " . $e->getMessage() . "\n";
}
echo "\n";

// 6. Vérifier les médias liés au paiement
echo "🖼️ MÉDIAS LIÉS AU PAIEMENT\n";
$paymentMedia = $bookingRequest->getMedia('payment_receipt');
echo "Reçus de paiement: " . $paymentMedia->count() . "\n";
foreach ($paymentMedia as $media) {
    echo "- Fichier: " . $media->file_name . ", Taille: " . $media->size . " bytes\n";
}
echo "\n";

// 7. Vérifier le statut du client et tattooer
echo "👤 UTILISATEURS\n";
echo "Client ID: " . $bookingRequest->client_id . "\n";
echo "Tattooer ID: " . $bookingRequest->bookable_id . "\n";
echo "Tattooer Type: " . $bookingRequest->bookable_type . "\n";
echo "Client User ID: " . ($bookingRequest->client->user_id ?? 'NULL') . "\n";
echo "Tattooer User ID: " . ($bookingRequest->bookable->user_id ?? 'NULL') . "\n";
echo "\n";

// 8. Vérifier les logs récents de paiement
echo "📋 LOGS RÉCENTS (dernières 10 lignes)\n";
$logs = file_get_contents(storage_path('logs/laravel.log'));
$lines = explode("\n", $logs);
$lastLines = array_slice($lines, -10);
foreach ($lastLines as $line) {
    if (strpos($line, 'payment') !== false || strpos($line, 'stripe') !== false || strpos($line, 'deposit') !== false) {
        echo $line . "\n";
    }
}

echo "\n=== FIN DE L'AUDIT ===\n";
