<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$base = __DIR__ . '/app/Filament/Admin/Resources/';

// ── 1. AppointmentResource.php ─────────────────────────────────────────────
$path = $base . 'Appointments/AppointmentResource.php';
$c = file_get_contents($path);
$c = str_replace(
    "                Tables\\Columns\\BadgeColumn::make('status')\n" .
    "                    ->label('Statut')\n" .
    "                    ->colors([\n" .
    "                        'warning' => 'pending',\n" .
    "                        'success' => 'confirmed',\n" .
    "                        'primary' => 'completed',\n" .
    "                        'danger' => 'cancelled',\n" .
    "                        'secondary' => 'client_no_show',\n" .
    "                    ])\n" .
    "                    ->formatStateUsing(fn (\$state) => match(\$state) {\n" .
    "                        'pending' => 'En attente',\n" .
    "                        'confirmed' => 'Confirmé',\n" .
    "                        'completed' => 'Terminé',\n" .
    "                        'cancelled' => 'Annulé',\n" .
    "                        'client_no_show' => 'Absent',\n" .
    "                        default => \$state,\n" .
    "                    }),",
    "                Tables\\Columns\\TextColumn::make('status')\n" .
    "                    ->label('Statut')\n" .
    "                    ->badge()\n" .
    "                    ->color(fn (\$state) => match(\$state) {\n" .
    "                        'pending' => 'warning',\n" .
    "                        'confirmed' => 'success',\n" .
    "                        'completed' => 'primary',\n" .
    "                        'cancelled' => 'danger',\n" .
    "                        'client_no_show' => 'gray',\n" .
    "                        default => 'gray',\n" .
    "                    })\n" .
    "                    ->formatStateUsing(fn (\$state) => match(\$state) {\n" .
    "                        'pending' => 'En attente',\n" .
    "                        'confirmed' => 'Confirmé',\n" .
    "                        'completed' => 'Terminé',\n" .
    "                        'cancelled' => 'Annulé',\n" .
    "                        'client_no_show' => 'Absent',\n" .
    "                        default => \$state,\n" .
    "                    }),",
    $c
);
file_put_contents($path, $c);
echo "Fixed: AppointmentResource.php\n";

// ── Helper function for BadgeColumn → TextColumn->badge() ────────────────
function fixBadgeColumn(string $path, string $fieldName, array $colorMap, array $formatMap, array $iconMap = []): void
{
    $c = file_get_contents($path);

    // Build the old BadgeColumn string (flexible match)
    // We do a preg_replace to handle the whole BadgeColumn block
    $pattern = '/Tables\\\\Columns\\\\BadgeColumn::make\(\'' . preg_quote($fieldName, '/') . '\'\).*?(?=\n\s+(?:Tables\\\\Columns|Tables\\\\Filters|\/\/))/s';

    $colorPhp = "fn (\$state) => match(\$state) {\n";
    foreach ($colorMap as $state => $color) {
        $colorPhp .= "                        '$state' => '$color',\n";
    }
    $colorPhp .= "                        default => 'gray',\n                    }";

    $formatPhp = "fn (\$state) => match(\$state) {\n";
    foreach ($formatMap as $state => $label) {
        $formatPhp .= "                        '$state' => '$label',\n";
    }
    $formatPhp .= "                        default => \$state,\n                    }";

    // Simple str_replace for known patterns
    // Build old string components by looking for the label and colors array
    echo "  Processing: $fieldName in $path\n";
    file_put_contents($path, $c);
}

// ── 2. TattooersTable.php — BadgeColumn user.status ──────────────────────
$path = $base . 'Tattooers/Tables/TattooersTable.php';
$c = file_get_contents($path);
// Remove BadgeColumn import
$c = str_replace("use Filament\\Tables\\Columns\\BadgeColumn;\n", '', $c);
// Replace the BadgeColumn block
$old = "                Tables\\Columns\\BadgeColumn::make('user.status')\n" .
       "                    ->label('Statut')\n" .
       "                    ->colors([\n" .
       "                        'warning' => 'pending_verification',\n" .
       "                        'success' => 'active',\n" .
       "                        'danger' => 'suspended',\n" .
       "                    ])\n" .
       "                    ->icons([\n" .
       "                        'heroicon-o-clock' => 'pending_verification',\n" .
       "                        'heroicon-o-check-circle' => 'active',\n" .
       "                        'heroicon-o-x-circle' => 'suspended',\n" .
       "                    ])\n" .
       "                    ->formatStateUsing(fn (string \$state): string => match (\$state) {\n" .
       "                        'pending_verification' => 'En attente',\n" .
       "                        'active' => 'Actif',\n" .
       "                        'suspended' => 'Suspendu',\n" .
       "                        default => \$state,\n" .
       "                    }),";
$new = "                Tables\\Columns\\TextColumn::make('user.status')\n" .
       "                    ->label('Statut')\n" .
       "                    ->badge()\n" .
       "                    ->color(fn (string \$state): string => match (\$state) {\n" .
       "                        'pending_verification' => 'warning',\n" .
       "                        'active' => 'success',\n" .
       "                        'suspended' => 'danger',\n" .
       "                        default => 'gray',\n" .
       "                    })\n" .
       "                    ->icon(fn (string \$state): string => match (\$state) {\n" .
       "                        'pending_verification' => 'heroicon-o-clock',\n" .
       "                        'active' => 'heroicon-o-check-circle',\n" .
       "                        'suspended' => 'heroicon-o-x-circle',\n" .
       "                        default => '',\n" .
       "                    })\n" .
       "                    ->formatStateUsing(fn (string \$state): string => match (\$state) {\n" .
       "                        'pending_verification' => 'En attente',\n" .
       "                        'active' => 'Actif',\n" .
       "                        'suspended' => 'Suspendu',\n" .
       "                        default => \$state,\n" .
       "                    }),";
$c = str_replace($old, $new, $c);
file_put_contents($path, $c);
echo "Fixed: TattooersTable.php\n";

// ── 3. PierceursTable.php — same pattern ─────────────────────────────────
$path = $base . 'Pierceurs/Tables/PierceursTable.php';
$c = file_get_contents($path);
$c = str_replace($old, $new, $c); // same old/new from above
file_put_contents($path, $c);
echo "Fixed: PierceursTable.php\n";

// ── 4. UsersTable.php — role BadgeColumn + status BadgeColumn ────────────
$path = $base . 'Users/Tables/UsersTable.php';
$c = file_get_contents($path);
// Fix role BadgeColumn
$c = preg_replace(
    '/Tables\\\\Columns\\\\BadgeColumn::make\(\'role\'\)(.*?)(?=\n\s+\/\/)/s',
    "Tables\\Columns\\TextColumn::make('role')$1->badge()",
    $c
);
// Fix status BadgeColumn
$c = preg_replace(
    '/Tables\\\\Columns\\\\BadgeColumn::make\(\'status\'\)(.*?)(?=\n\s+\/\/)/s',
    "Tables\\Columns\\TextColumn::make('status')$1->badge()",
    $c
);
// Remove use BadgeColumn (not imported as standalone)
file_put_contents($path, $c);
echo "Fixed: UsersTable.php (partial - regex)\n";

// ── 5. SubscriptionsTable.php — plan + stripe_status BadgeColumns ────────
$path = $base . 'Subscriptions/Tables/SubscriptionsTable.php';
$c = file_get_contents($path);
// Remove BadgeColumn import
$c = str_replace("use Filament\\Tables\\Columns\\BadgeColumn;\n", '', $c);
// Replace plan BadgeColumn
$c = str_replace(
    "                BadgeColumn::make('plan')",
    "                TextColumn::make('plan')\n                    ->badge()",
    $c
);
// Replace stripe_status BadgeColumn
$c = str_replace(
    "                BadgeColumn::make('stripe_status')",
    "                TextColumn::make('stripe_status')\n                    ->badge()",
    $c
);
// Fix colors() → color() for plan
$c = str_replace(
    "                    ->colors([\n" .
    "                        'warning' => 'starter',\n" .
    "                        'success' => 'pro',\n" .
    "                        'primary' => 'studio',\n" .
    "                    ])",
    "                    ->color(fn (string \$state): string => match (\$state) {\n" .
    "                        'STARTER' => 'warning',\n" .
    "                        'PRO' => 'success',\n" .
    "                        'STUDIO' => 'primary',\n" .
    "                        default => 'gray',\n" .
    "                    })",
    $c
);
// Fix colors() → color() for stripe_status
$c = str_replace(
    "                    ->colors([\n" .
    "                        'success' => 'active',\n" .
    "                        'warning' => 'trialing',\n" .
    "                        'danger' => 'canceled',\n" .
    "                        'incomplete' => 'incomplete',\n" .
    "                        'past_due' => 'past_due',\n" .
    "                        'unpaid' => 'unpaid',\n" .
    "                    ])",
    "                    ->color(fn (string \$state): string => match (\$state) {\n" .
    "                        'active' => 'success',\n" .
    "                        'trialing' => 'warning',\n" .
    "                        'canceled' => 'danger',\n" .
    "                        'incomplete' => 'gray',\n" .
    "                        'past_due' => 'danger',\n" .
    "                        'unpaid' => 'danger',\n" .
    "                        default => 'gray',\n" .
    "                    })",
    $c
);
file_put_contents($path, $c);
echo "Fixed: SubscriptionsTable.php\n";

// ── 6. ComplianceRecordsTable.php — type BadgeColumn ─────────────────────
$path = $base . 'ComplianceRecords/Tables/ComplianceRecordsTable.php';
$c = file_get_contents($path);
$c = str_replace(
    "                Tables\\Columns\\BadgeColumn::make('type')\n" .
    "                    ->label('Type')\n" .
    "                    ->colors([\n" .
    "                        'primary' => 'hygiene',\n" .
    "                        'success' => 'ars',\n" .
    "                        'warning' => 'certibiocide',\n" .
    "                    ])\n" .
    "                    ->icons([\n" .
    "                        'heroicon-o-academic-cap' => 'hygiene',\n" .
    "                        'heroicon-o-building-office-2' => 'ars',\n" .
    "                        'heroicon-o-beaker' => 'certibiocide',\n" .
    "                    ])\n" .
    "                    ->formatStateUsing(fn (string \$state): string => match (\$state) {\n" .
    "                        'hygiene' => 'Formation Hygiène',\n" .
    "                        'ars' => 'Déclaration ARS',\n" .
    "                        'certibiocide' => 'Certibiocide TP2',\n" .
    "                        default => \$state,\n" .
    "                    }),",
    "                Tables\\Columns\\TextColumn::make('type')\n" .
    "                    ->label('Type')\n" .
    "                    ->badge()\n" .
    "                    ->color(fn (string \$state): string => match (\$state) {\n" .
    "                        'hygiene' => 'primary',\n" .
    "                        'ars' => 'success',\n" .
    "                        'certibiocide' => 'warning',\n" .
    "                        default => 'gray',\n" .
    "                    })\n" .
    "                    ->icon(fn (string \$state): string => match (\$state) {\n" .
    "                        'hygiene' => 'heroicon-o-academic-cap',\n" .
    "                        'ars' => 'heroicon-o-building-office-2',\n" .
    "                        'certibiocide' => 'heroicon-o-beaker',\n" .
    "                        default => '',\n" .
    "                    })\n" .
    "                    ->formatStateUsing(fn (string \$state): string => match (\$state) {\n" .
    "                        'hygiene' => 'Formation Hygiène',\n" .
    "                        'ars' => 'Déclaration ARS',\n" .
    "                        'certibiocide' => 'Certibiocide TP2',\n" .
    "                        default => \$state,\n" .
    "                    }),",
    $c
);
file_put_contents($path, $c);
echo "Fixed: ComplianceRecordsTable.php\n";

echo "\nAll badge fixes done!\n";
