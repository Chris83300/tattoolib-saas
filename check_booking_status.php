<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bookingRequest = App\Models\BookingRequest::find(27);

echo "=== Booking Request ID 27 ===\n";
echo "Status: " . $bookingRequest->status . "\n";
echo "Deposit paid at: " . ($bookingRequest->deposit_paid_at ? $bookingRequest->deposit_paid_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
echo "Deposit amount: " . $bookingRequest->total_deposit_amount . "\n";
echo "Deposit deadline: " . ($bookingRequest->deposit_deadline ? $bookingRequest->deposit_deadline->format('Y-m-d H:i:s') : 'NULL') . "\n";
