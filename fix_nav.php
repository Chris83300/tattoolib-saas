<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$base = __DIR__ . '/app/Filament/Admin/Resources/';

$files = [
    'BookingRequests/BookingRequestResource.php' => [
        'icon' => 'heroicon-o-calendar',
        'label' => 'Demandes',
        'model_label' => 'Demande',
        'group' => 'Réservations',
        'sort' => 1,
    ],
    'Complaints/ComplaintResource.php' => [
        'icon' => 'heroicon-o-exclamation-triangle',
        'label' => 'Réclamations',
        'model_label' => 'Réclamation',
        'group' => 'Qualité',
        'sort' => 2,
    ],
    'Payments/PaymentResource.php' => [
        'icon' => 'heroicon-o-credit-card',
        'label' => 'Paiements',
        'model_label' => 'Paiement',
        'group' => 'Finances',
        'sort' => 1,
    ],
    'Reviews/ReviewResource.php' => [
        'icon' => 'heroicon-o-star',
        'label' => 'Avis',
        'model_label' => 'Avis',
        'group' => 'Qualité',
        'sort' => 1,
    ],
    'Transactions/TransactionResource.php' => [
        'icon' => 'heroicon-o-banknotes',
        'label' => 'Transactions',
        'model_label' => 'Transaction',
        'group' => 'Finances',
        'sort' => 2,
    ],
];

$old = "    protected static string|BackedEnum|null \$navigationIcon = Heroicon::OutlinedRectangleStack;";

foreach ($files as $rel => $props) {
    $path = $base . $rel;
    $content = file_get_contents($path);

    $new  = "    protected static string|BackedEnum|null \$navigationIcon = '{$props['icon']}';\n";
    $new .= "    protected static ?string \$navigationLabel = '{$props['label']}';\n";
    $new .= "    protected static ?string \$modelLabel = '{$props['model_label']}';\n";
    $new .= "    protected static ?string \$pluralModelLabel = '{$props['label']}';\n";
    $new .= "    protected static UnitEnum|string|null \$navigationGroup = '{$props['group']}';\n";
    $new .= "    protected static ?int \$navigationSort = {$props['sort']};";

    $content = str_replace($old, $new, $content);

    // Add UnitEnum use if not already there
    if (strpos($content, 'use UnitEnum') === false) {
        $content = str_replace("use BackedEnum;\n", "use BackedEnum;\nuse UnitEnum;\n", $content);
    }

    file_put_contents($path, $content);
    echo "Updated: $rel\n";
}

// Update SubscriptionResource icon only
$subPath = $base . 'Subscriptions/SubscriptionResource.php';
$subContent = file_get_contents($subPath);
$subContent = str_replace(
    'Heroicon::OutlinedRectangleStack',
    "'heroicon-o-credit-card'",
    $subContent
);
// Also add sort
if (strpos($subContent, 'navigationSort') === false) {
    $subContent = str_replace(
        "protected static UnitEnum|string|null \$navigationGroup = 'Finances';",
        "protected static UnitEnum|string|null \$navigationGroup = 'Finances';\n    protected static ?int \$navigationSort = 3;",
        $subContent
    );
}
file_put_contents($subPath, $subContent);
echo "Updated: Subscriptions/SubscriptionResource.php\n";

echo "All done!\n";
