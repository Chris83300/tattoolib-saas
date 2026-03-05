<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- TATTOOERS ---\n";
$cols = DB::select('DESCRIBE tattooers');
foreach ($cols as $col) {
    if (str_contains($col->Field, 'plan') || str_contains($col->Field, 'subscri') || str_contains($col->Field, 'blocked') || str_contains($col->Field, 'trial')) {
        echo $col->Field . ': ' . $col->Type . ' default=' . ($col->Default ?? 'NULL') . "\n";
    }
}

echo "--- PIERCERS ---\n";
$cols = DB::select('DESCRIBE piercers');
foreach ($cols as $col) {
    if (str_contains($col->Field, 'plan') || str_contains($col->Field, 'subscri') || str_contains($col->Field, 'blocked') || str_contains($col->Field, 'trial')) {
        echo $col->Field . ': ' . $col->Type . ' default=' . ($col->Default ?? 'NULL') . "\n";
    }
}

echo "--- USERS ---\n";
$cols = DB::select('DESCRIBE users');
foreach ($cols as $col) {
    if (str_contains($col->Field, 'beta') || str_contains($col->Field, 'trial')) {
        echo $col->Field . ': ' . $col->Type . ' default=' . ($col->Default ?? 'NULL') . "\n";
    }
}
