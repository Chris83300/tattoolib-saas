<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== VÉRIFICATION DIRECTE EN BASE ===\n";

// Vérifier directement en base
$result = \Illuminate\Support\Facades\DB::select('SELECT design_versions_used, modifications_used FROM booking_requests WHERE id = 31');

if ($result) {
    $row = $result[0];
    echo "Design versions used en base: " . $row->design_versions_used . "\n";
    echo "Modifications used en base: " . $row->modifications_used . "\n";
} else {
    echo "Aucune donnée trouvée\n";
}
