#!/usr/bin/env php
<?php

// Script pour ajouter le préfixe test_ aux méthodes de test

$testFiles = [
    'tests/Feature/BookingRequestWorkflowTest.php',
    'tests/Feature/FactoriesTest.php',
    'tests/Feature/JobAndCommandTest.php',
    'tests/Feature/IntegrationTest.php'
];

foreach ($testFiles as $file) {
    if (!file_exists($file)) {
        echo "File not found: $file\n";
        continue;
    }

    $content = file_get_contents($file);

    // Remplacer les méthodes de test sans préfixe test_
    $content = preg_replace_callback(
        '/public function (\w+)\s*\(/',
        function ($matches) {
            $methodName = $matches[1];

            // Si la méthode commence déjà par test_, ne pas modifier
            if (str_starts_with($methodName, 'test_')) {
                return $matches[0];
            }

            // Si c'est setUp ou tearDown, ne pas modifier
            if (in_array($methodName, ['setUp', 'tearDown'])) {
                return $matches[0];
            }

            // Ajouter le préfixe test_
            return "public function test_" . $methodName . "(";
        },
        $content
    );

    file_put_contents($file, $content);
    echo "Fixed: $file\n";
}

echo "Done!\n";
