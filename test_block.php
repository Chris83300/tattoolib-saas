<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test: bloquer un artiste
$user = \App\Models\User::where('role', 'tattooer')->first();

if ($user && $user->tattooer) {
    $user->tattooer->update(['is_blocked' => true]);
    echo "Artiste bloqué pour test: " . $user->name . PHP_EOL;
    echo "ID: " . $user->tattooer->id . PHP_EOL;
    echo "is_blocked: " . ($user->tattooer->is_blocked ? 'true' : 'false') . PHP_EOL;
} else {
    echo "Aucun artiste tattooer trouvé" . PHP_EOL;
}
