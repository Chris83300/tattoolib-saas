<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::find(4);
echo "User: " . $user->name . "\n";
echo "Role: " . $user->role . "\n";
echo "Is Admin: " . ($user->isAdmin() ? 'true' : 'false') . "\n";
