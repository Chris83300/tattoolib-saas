<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bookingRequest = App\Models\BookingRequest::find(27);

echo "=== Marquer paiement comme effectué ===\n";
echo "Avant:\n";
echo "- Deposit paid at: " . ($bookingRequest->deposit_paid_at ? $bookingRequest->deposit_paid_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
echo "- Status: " . $bookingRequest->status . "\n";

// Marquer le paiement comme effectué
$bookingRequest->update([
    'deposit_paid_at' => now(),
    'status' => 'deposit_paid'
]);

echo "\nAprès:\n";
echo "- Deposit paid at: " . $bookingRequest->deposit_paid_at->format('Y-m-d H:i:s') . "\n";
echo "- Status: " . $bookingRequest->status . "\n";

echo "\n✅ Paiement marqué avec succès !\n";
