<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG BOOKING REQUEST 31 ===\n";

// Récupérer le booking request 31
$bookingRequest = \App\Models\BookingRequest::find(31);

if (!$bookingRequest) {
    echo "Booking Request 31 non trouvé\n";
    exit;
}

echo "Booking Request trouvé:\n";
echo "- ID: " . $bookingRequest->id . "\n";
echo "- Client ID: " . $bookingRequest->client_id . "\n";
echo "- Status: " . $bookingRequest->status . "\n";
echo "- Deposit Paid At: " . ($bookingRequest->deposit_paid_at ? date('Y-m-d H:i:s', strtotime($bookingRequest->deposit_paid_at)) : 'NULL') . "\n";
echo "- Stripe Payment Intent ID: " . ($bookingRequest->stripe_payment_intent_id ?: 'NULL') . "\n";
echo "- Total Deposit Amount: " . $bookingRequest->total_deposit_amount . "\n";

echo "\n=== CONVERSATION ASSOCIÉE ===\n";

// Vérifier la conversation associée
$conversation = $bookingRequest->conversation;
if ($conversation) {
    echo "Conversation trouvée:\n";
    echo "- ID: " . $conversation->id . "\n";
    echo "- Status: " . $conversation->status . "\n";
    echo "- Deposit Deadline At: " . ($conversation->deposit_deadline_at ? $conversation->deposit_deadline_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "- Expires At: " . ($conversation->expires_at ? $conversation->expires_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
} else {
    echo "Aucune conversation trouvée\n";
}

echo "\n=== BOOKING TRANSACTIONS ===\n";

// Vérifier les transactions pour ce booking request
$transactions = \App\Models\BookingTransaction::where('booking_request_id', 31)->get();
echo "Nombre de transactions: " . $transactions->count() . "\n";

foreach ($transactions as $transaction) {
    echo "- Transaction ID: " . $transaction->id . "\n";
    echo "  Type: " . $transaction->type . "\n";
    echo "  Amount: " . $transaction->amount . "\n";
    echo "  Status: " . $transaction->status . "\n";
    echo "  Stripe Session ID: " . ($transaction->stripe_session_id ?: 'NULL') . "\n";
    echo "  Stripe Payment Intent ID: " . ($transaction->stripe_payment_intent_id ?: 'NULL') . "\n";
    echo "  Created At: " . $transaction->created_at->format('Y-m-d H:i:s') . "\n";
    echo "\n";
}

echo "\n=== ACCOUNTING TRANSACTIONS ===\n";

// Vérifier les accounting transactions pour ce booking request
$accountingTransactions = \Illuminate\Support\Facades\DB::table('accounting_transactions')->get();
echo "Nombre total de accounting transactions: " . $accountingTransactions->count() . "\n";

foreach ($accountingTransactions as $transaction) {
    echo "- Accounting Transaction ID: " . $transaction->id . "\n";
    echo "  Type: " . $transaction->type . "\n";
    echo "  Amount: " . $transaction->amount . "\n";
    echo "  Status: " . $transaction->status . "\n";
    echo "  Client ID: " . ($transaction->client_id ?: 'NULL') . "\n";
    echo "  Appointment ID: " . ($transaction->appointment_id ?: 'NULL') . "\n";
    echo "\n";
}
