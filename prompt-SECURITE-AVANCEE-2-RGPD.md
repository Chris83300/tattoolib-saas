# 🛡️ SÉCURITÉ AVANCÉE 2/2 — CONFORMITÉ RGPD
## Obligations légales France — données de santé (Article 9 RGPD) + DSP2

---

## CONTEXTE LÉGAL
Ink&Pik collecte des **données de santé** (allergies, groupe sanguin,
conditions médicales, consentements médicaux) = catégorie spéciale RGPD Art. 9.
Obligations renforcées : base légale explicite, registre des traitements,
durées de conservation, export sur demande, DPO désigné.

---

## PHASE 1 — AUDIT PRÉALABLE

```bash
# Données de santé en base
php artisan tinker --execute="
  \$tables = ['client_care_sheets', 'client_consent_forms', 'parental_consent_forms',
               'traceability_records', 'compliance_records'];
  foreach(\$tables as \$t) {
    try {
      \$count = \DB::table(\$t)->count();
      echo \$t . ': ' . \$count . ' enregistrements' . PHP_EOL;
    } catch(\Exception \$e) { echo \$t . ': table absente' . PHP_EOL; }
  }
"

# Pages légales existantes
ls resources/views/legal/ 2>/dev/null || \
  find resources/views -name "*legal*" -o -name "*cgu*" -o -name "*rgpd*" \
    -o -name "*privacy*" -o -name "*mentions*" | head -10

# Colonnes consentement
php artisan tinker --execute="
  dd([
    'users_consent' => array_filter(\Schema::getColumnListing('users'),
      fn(\$c) => str_contains(\$c, 'accept') || str_contains(\$c, 'consent')),
  ]);
"

# Export RGPD existant
grep -rn "export\|portabilit\|download.*data\|data.*export" \
  app/Http/Controllers/ --include="*.php" | grep -v "pdf\|csv\|excel" | head -10
```

---

## A — REGISTRE DES TRAITEMENTS

### A1 — Migration : table `data_processing_records`

```bash
php artisan make:migration create_data_processing_records_table
```

```php
Schema::create('data_processing_records', function (Blueprint $table) {
    $table->id();
    $table->string('name');                          // Nom du traitement
    $table->string('purpose');                       // Finalité
    $table->string('legal_basis');                   // Base légale (consentement, contrat, intérêt légitime...)
    $table->json('data_categories');                 // Catégories de données traitées
    $table->json('data_subjects');                   // Personnes concernées
    $table->json('recipients')->nullable();          // Destinataires
    $table->boolean('transfers_outside_eu')->default(false);
    $table->string('retention_period');              // Durée de conservation
    $table->text('security_measures')->nullable();   // Mesures de sécurité
    $table->boolean('requires_dpia')->default(false); // AIPD requise ?
    $table->text('dpia_notes')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### A2 — Seeder avec les traitements d'Ink&Pik

```bash
php artisan make:seeder DataProcessingRecordsSeeder
```

```php
// database/seeders/DataProcessingRecordsSeeder.php
public function run(): void
{
    $records = [
        [
            'name'            => 'Gestion des comptes utilisateurs',
            'purpose'         => 'Création et gestion des comptes tatoueurs, pierceurs, clients et studios',
            'legal_basis'     => 'Exécution du contrat (CGU)',
            'data_categories' => ['identité', 'contact', 'authentification'],
            'data_subjects'   => ['tatoueurs', 'pierceurs', 'clients', 'studios'],
            'retention_period'=> '3 ans après la dernière activité',
            'requires_dpia'   => false,
        ],
        [
            'name'            => 'Traitement des réservations et paiements',
            'purpose'         => 'Gestion du flux de réservation, paiements via Stripe',
            'legal_basis'     => 'Exécution du contrat',
            'data_categories' => ['identité', 'contact', 'données financières', 'historique achats'],
            'data_subjects'   => ['clients', 'tatoueurs', 'pierceurs'],
            'recipients'      => ['Stripe Inc. (sous-traitant paiement, US - Privacy Shield)'],
            'transfers_outside_eu' => true,
            'retention_period'=> '10 ans (obligation comptable)',
            'requires_dpia'   => false,
        ],
        [
            'name'            => 'Données de santé - Fiches clients et consentements',
            'purpose'         => 'Traçabilité médicale obligatoire pour tatouage/piercing (arrêté du 11 mars 2009)',
            'legal_basis'     => 'Obligation légale + Consentement explicite (Art. 9 RGPD)',
            'data_categories' => ['données de santé', 'groupe sanguin', 'allergies',
                                   'traitements médicaux', 'historique tatouage'],
            'data_subjects'   => ['clients'],
            'retention_period'=> '10 ans (traçabilité médicale réglementaire)',
            'requires_dpia'   => true,
            'dpia_notes'      => 'AIPD requise - données de santé catégorie spéciale Art.9 RGPD. Mesures : chiffrement AES-256, accès restreint artiste concerné, logs d\'accès.',
        ],
        [
            'name'            => 'Notifications push et emails',
            'purpose'         => 'Envoi de notifications relatives aux réservations et à la plateforme',
            'legal_basis'     => 'Intérêt légitime (notifications contractuelles) + Consentement (marketing)',
            'data_categories' => ['contact', 'tokens push (FCM)'],
            'data_subjects'   => ['tous utilisateurs'],
            'recipients'      => ['Google Firebase (sous-traitant push, US)'],
            'transfers_outside_eu' => true,
            'retention_period'=> 'Durée du compte + 30 jours',
            'requires_dpia'   => false,
        ],
        [
            'name'            => 'Stripe Connect - Comptes artistes',
            'purpose'         => 'Onboarding KYC et gestion des paiements vers les artistes',
            'legal_basis'     => 'Exécution du contrat + Obligation légale (LCB-FT)',
            'data_categories' => ['identité', 'documents officiels', 'coordonnées bancaires', 'SIRET'],
            'data_subjects'   => ['tatoueurs', 'pierceurs', 'studios'],
            'recipients'      => ['Stripe Inc. (sous-traitant, US - Privacy Shield)'],
            'transfers_outside_eu' => true,
            'retention_period'=> '5 ans après fin de relation commerciale (LCB-FT)',
            'requires_dpia'   => true,
            'dpia_notes'      => 'Données bancaires et documents d\'identité. KYC délégué à Stripe.',
        ],
    ];

    foreach ($records as $record) {
        \App\Models\DataProcessingRecord::create($record);
    }
}
```

---

## B — EXPORT DONNÉES UTILISATEUR (Droit à la portabilité)

### B1 — Service d'export RGPD

Créer `app/Services/GdprExportService.php` :

```php
<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GdprExportService
{
    /**
     * Générer un export ZIP complet des données d'un utilisateur
     */
    public function exportUserData(User $user): string
    {
        $data = [];

        // Données de base
        $data['profil'] = [
            'nom'        => $user->name,
            'email'      => $user->email,
            'telephone'  => $user->phone,
            'inscription'=> $user->created_at->format('d/m/Y'),
            'role'       => $user->role,
        ];

        // Données selon le rôle
        if ($tattooer = $user->tattooer) {
            $data['profil_artiste'] = $tattooer->only([
                'pseudo', 'bio', 'city', 'postal_code', 'email',
                'years_of_experience', 'minimum_price', 'siret',
                'current_plan', 'created_at',
            ]);

            $data['reservations'] = $tattooer->bookingRequests()
                ->with('client.user')
                ->get()
                ->map(fn($b) => [
                    'id'          => $b->id,
                    'client'      => $b->client?->user?->name,
                    'description' => $b->description,
                    'statut'      => $b->status,
                    'montant'     => $b->estimated_total_price,
                    'date'        => $b->created_at->format('d/m/Y'),
                ])
                ->toArray();
        }

        if ($client = $user->client) {
            $data['reservations_client'] = \App\Models\BookingRequest::where('client_id', $client->id)
                ->with('bookable')
                ->get()
                ->map(fn($b) => [
                    'id'      => $b->id,
                    'artiste' => $b->bookable?->pseudo ?? $b->bookable?->name,
                    'statut'  => $b->status,
                    'date'    => $b->created_at->format('d/m/Y'),
                ])
                ->toArray();
        }

        // Notifications
        $data['notifications'] = $user->notifications()
            ->latest()->limit(100)
            ->get(['type', 'data', 'read_at', 'created_at'])
            ->toArray();

        // Créer le fichier JSON
        $exportPath = 'gdpr-exports/' . $user->id . '_' . now()->format('Y-m-d') . '.json';
        Storage::disk('local')->put($exportPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $exportPath;
    }
}
```

### B2 — Controller et routes export

Dans chaque controller settings (Tattooer, Piercer, Client, Studio) :

```php
public function exportGdpr(Request $request)
{
    $user = $request->user();

    $exportService = app(\App\Services\GdprExportService::class);
    $path = $exportService->exportUserData($user);

    return Storage::disk('local')->download(
        $path,
        'mes-donnees-inkpik-' . now()->format('Y-m-d') . '.json'
    );
}
```

Routes dans `routes/web.php` :
```php
Route::get('/tattooer/settings/export-gdpr',
    [TattooerController::class, 'exportGdpr'])
    ->name('tattooer.gdpr.export')
    ->middleware(['auth', 'verified', 'throttle:3,60']); // Max 3 exports/heure

Route::get('/pierceur/settings/export-gdpr',
    [PiercerController::class, 'exportGdpr'])
    ->name('piercer.gdpr.export')
    ->middleware(['auth', 'verified', 'throttle:3,60']);

Route::get('/client/settings/export-gdpr',
    [ClientController::class, 'exportGdpr'])
    ->name('client.gdpr.export')
    ->middleware(['auth', 'verified', 'throttle:3,60']);
```

---

## C — PURGE AUTOMATIQUE DES DONNÉES

### C1 — Command de purge RGPD

```bash
php artisan make:command Gdpr/PurgeInactiveData
```

```php
<?php
namespace App\Console\Commands\Gdpr;

use Illuminate\Console\Command;

class PurgeInactiveData extends Command
{
    protected $signature   = 'gdpr:purge-inactive';
    protected $description = 'Purger les données des comptes inactifs selon la politique de rétention RGPD';

    public function handle(): void
    {
        // 1. Comptes sans activité depuis 3 ans (non supprimés mais inactifs)
        $inactiveUsers = \App\Models\User::where('last_login_at', '<', now()->subYears(3))
            ->whereNull('deleted_at')
            ->get();

        $this->info("Comptes inactifs +3 ans : {$inactiveUsers->count()}");

        foreach ($inactiveUsers as $user) {
            // Anonymiser (pas supprimer — garder les données comptables)
            $user->update([
                'phone'     => null,
                'fcm_token' => null,
                // Ne pas anonymiser email ni stripe_id (obligations légales)
            ]);
            $this->line("Anonymisé : user #{$user->id}");
        }

        // 2. Tokens FCM expirés (tokens push inutilisés depuis 6 mois)
        \App\Models\User::whereNotNull('fcm_token')
            ->where('updated_at', '<', now()->subMonths(6))
            ->whereDoesntHave('notifications', fn($q) =>
                $q->where('created_at', '>', now()->subMonths(6))
            )
            ->update(['fcm_token' => null]);

        $this->info('Tokens FCM expirés nettoyés');

        // 3. Logs d'export RGPD > 30 jours
        \Illuminate\Support\Facades\Storage::disk('local')
            ->deleteDirectory('gdpr-exports'); // Recréé à la prochaine demande

        // 4. Sessions expirées
        \DB::table('sessions')
            ->where('last_activity', '<', now()->subDays(30)->timestamp)
            ->delete();

        $this->info('✅ Purge RGPD terminée');
    }
}
```

Ajouter dans `routes/console.php` ou `bootstrap/app.php` :
```php
Schedule::command('gdpr:purge-inactive')->monthly();
```

---

## D — CONSENTEMENT EXPLICITE TRACÉ

### D1 — Vérifier le traçage des consentements existants

```bash
php artisan tinker --execute="
  dd([
    'cgu_accepted_at_present'     => \Schema::hasColumn('users', 'cgu_accepted_at'),
    'privacy_accepted_at_present' => \Schema::hasColumn('users', 'privacy_accepted_at'),
    'sample_user_consents'        => \App\Models\User::first()?->only([
      'cgu_accepted_at', 'privacy_accepted_at'
    ]),
  ]);
"
```

### D2 — Ajouter le traçage de version CGU

```bash
php artisan make:migration add_cgu_version_to_users_table
```

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('cgu_version_accepted')->nullable()->after('cgu_accepted_at')
          ->comment('Version des CGU acceptée, ex: 1.2');
    $table->string('privacy_version_accepted')->nullable()->after('privacy_accepted_at');
    $table->string('consent_ip')->nullable()->after('privacy_version_accepted')
          ->comment('IP lors de l\'acceptation des CGU');
});
```

### D3 — Logger la version et l'IP au moment de l'inscription

Dans `RegisterController` :

```php
// Lors de la création du compte, logger le consentement
$user->update([
    'cgu_accepted_at'          => now(),
    'cgu_version_accepted'     => config('app.cgu_version', '1.0'),
    'privacy_accepted_at'      => now(),
    'privacy_version_accepted' => config('app.privacy_version', '1.0'),
    'consent_ip'               => $request->ip(),
]);
```

---

## E — MENTIONS RGPD DANS LES VUES

### E1 — Vérifier les pages légales

```bash
find resources/views -name "*legal*" -o -name "*cgu*" -o -name "*rgpd*" \
  -o -name "*privacy*" -o -name "*mentions*" 2>/dev/null
```

Les pages suivantes doivent exister et être complètes :
- CGU (Conditions Générales d'Utilisation)
- CGV (Conditions Générales de Vente)
- Politique de confidentialité (RGPD)
- Mentions légales
- Politique cookies

### E2 — Ajouter bouton export dans les settings

Dans chaque vue settings utilisateur :

```blade
{{-- Section RGPD dans les settings --}}
<div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-1">
        Vos données personnelles
    </h3>
    <p class="text-sm text-gray-500 mb-4">
        Conformément au RGPD, vous pouvez télécharger une copie de toutes
        vos données personnelles stockées sur Ink&Pik.
    </p>
    <a href="{{ route('tattooer.gdpr.export') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100
              dark:bg-gray-700 text-gray-700 dark:text-gray-200
              text-sm rounded-lg hover:bg-gray-200 transition">
        📥 Télécharger mes données (JSON)
    </a>
    <p class="text-xs text-gray-400 mt-2">
        Maximum 3 exports par heure. Le fichier contient toutes vos
        informations personnelles, réservations et historique.
    </p>
</div>
```

---

## F — RESOURCE FILAMENT ADMIN RGPD

### F1 — Page registre des traitements

```bash
php artisan make:filament-resource DataProcessingRecord --generate --panel=admin
```

Configuration minimale dans `DataProcessingRecordResource.php` :

```php
protected static ?string $navigationLabel = 'Registre des traitements';
protected static ?string $navigationIcon  = 'heroicon-o-shield-check';
protected static ?string $navigationGroup = 'RGPD & Conformité';

// Table : nom, finalité, base légale, AIPD requise, actif
// Form : tous les champs
```

---

## VALIDATION FINALE

```bash
# Vérifier le service export
php artisan tinker --execute="
  \$user = \App\Models\User::first();
  \$path = app(\App\Services\GdprExportService::class)->exportUserData(\$user);
  echo 'Export créé : ' . \$path . PHP_EOL;
  echo 'Taille : ' . filesize(storage_path('app/' . \$path)) . ' bytes';
"

# Vérifier la commande de purge (dry-run)
php artisan gdpr:purge-inactive --dry-run 2>/dev/null || \
  php artisan gdpr:purge-inactive

# Vérifier les routes RGPD
php artisan route:list | grep gdpr

# Vérifier le seeder
php artisan db:seed --class=DataProcessingRecordsSeeder
php artisan tinker --execute="echo \App\Models\DataProcessingRecord::count() . ' traitements';"
```

## ⚠️ CONTRAINTES LÉGALES
- La durée de conservation des données comptables est **10 ans** en France
  (Code de commerce L.123-22) → ne jamais supprimer les BookingRequest complétés
- Les données de santé doivent avoir une base légale Art. 9 RGPD explicite
  → consentement explicite OU obligation légale (arrêté tatouage/piercing)
- L'export RGPD ne doit pas inclure les données des autres utilisateurs
  (ex: ne pas inclure le nom de l'artiste dans l'export client)
- Rapport final : export fonctionnel + registre des traitements peuplé + routes RGPD OK
