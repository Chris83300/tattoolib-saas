# 👨‍💻 Guide Développeur Ink&Pik

Guide complet pour développeurs travaillant sur Ink&Pik SaaS.

## 📋 Table des Matières

1. [Setup Environnement](#setup-environnement)
2. [Conventions Code](#conventions-code)
3. [Architecture Services](#architecture-services)
4. [Workflow Git](#workflow-git)
5. [Testing](#testing)
6. [Debugging](#debugging)
7. [Performance](#performance)

## 🛠 Setup Environnement

### IDE Recommandé

**VS Code** avec extensions :
- Laravel Extension Pack
- PHP Intelephense
- Tailwind CSS IntelliSense
- Livewire Language Support
- GitLens

**PHPStorm** (alternative premium)

### Configuration VS Code

**`.vscode/settings.json`** :
```json
{
  "php.suggest.basic": false,
  "php.validate.executablePath": "/usr/bin/php8.3",
  "editor.formatOnSave": true,
  "files.associations": {
    "*.blade.php": "blade"
  },
  "[php]": {
    "editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
  },
  "tailwindCSS.experimental.classRegex": [
    ["class: '([^']*)'"],
    ["class=\"([^\"]*)\""]
  ]
}
```

### Laravel Pint (Code Style)
```bash
# Formater tout le code
./vendor/bin/pint

# Vérifier sans modifier
./vendor/bin/pint --test
```

### Xdebug Configuration

**`.vscode/launch.json`** :
```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9003,
      "pathMappings": {
        "/var/www/html": "${workspaceFolder}"
      }
    }
  ]
}
```

## 📐 Conventions Code

### Naming Conventions

**Controllers** :
```php
// Singulier, suffixe Controller
BookingRequestController.php
TattooerController.php
```

**Models** :
```php
// Singulier, PascalCase
BookingRequest.php
Tattooer.php
```

**Services** :
```php
// Descriptif, suffixe Service
BookingRequestService.php
TattooerStatsService.php
```

**Traits** :
```php
// Verbe d'action ou état
HasWorkingHours.php
CalculatesStats.php
```

**Variables** :
```php
// camelCase
$bookingRequest
$tattooerProfile
```

**Méthodes** :
```php
// camelCase, verbe d'action
public function acceptBooking()
public function sendDesign()
```

### Structure Méthodes
```php
public function acceptBooking(BookingRequest $bookingRequest, array $data): BookingRequest
{
    // 1. Validation autorisation
    $this->authorize('update', $bookingRequest);
    
    // 2. Validation logique métier
    if ($bookingRequest->status !== BookingRequest::STATUS_PENDING) {
        throw new BookingException('...');
    }
    
    // 3. Transaction DB si modifications multiples
    DB::beginTransaction();
    
    try {
        // 4. Logique métier
        $bookingRequest->update([...]);
        
        // 5. Actions secondaires
        $this->createConversation($bookingRequest);
        $this->sendNotification($bookingRequest);
        
        DB::commit();
        
        // 6. Return
        return $bookingRequest->fresh(['client', 'bookable']);
        
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

### Documentation PHPDoc
```php
/**
 * Accepter une demande de réservation et créer la conversation.
 *
 * @param BookingRequest $bookingRequest Demande à accepter
 * @param array $data Données d'acceptation (prix, deadline, etc.)
 * @return BookingRequest Demande mise à jour avec relations chargées
 * 
 * @throws BookingException Si statut invalide
 * @throws AuthorizationException Si non autorisé
 */
public function accept(BookingRequest $bookingRequest, array $data): BookingRequest
```

## 🏗 Architecture Services

### Quand Créer un Service ?

✅ **OUI** si :
- Logique métier complexe (>50 lignes)
- Réutilisé dans plusieurs contrôleurs
- Nécessite transactions DB multiples
- Intègre services externes (Stripe, etc.)

❌ **NON** si :
- Simple CRUD sans logique
- Utilisé une seule fois
- Transformation simple de données

### Pattern Service Typique
```php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BookingRequestService
{
    // Dépendances injectées
    public function __construct(
        private NotificationService $notificationService,
        private StripeService $stripeService
    ) {}
    
    // Méthodes publiques (API du service)
    public function accept(BookingRequest $booking, array $data): BookingRequest
    {
        return $this->processAcceptance($booking, $data);
    }
    
    // Méthodes privées (implémentation)
    private function processAcceptance(BookingRequest $booking, array $data): BookingRequest
    {
        // ...
    }
    
    private function calculateDeposit(float $total, int $rate): float
    {
        return round($total * ($rate / 100), 2);
    }
}
```

### Injection Dépendances

**Dans contrôleur** :
```php
public function accept(
    Request $request,
    BookingRequest $booking,
    BookingRequestService $service // Laravel résout automatiquement
) {
    $validated = $request->validate([...]);
    $result = $service->accept($booking, $validated);
    return response()->json($result);
}
```

**Dans Job/Command** :
```php
public function handle(BookingRequestService $service)
{
    $service->processExpired();
}
```

## 🔀 Workflow Git

### Branches
```
main (production)
├── staging (pré-production)
└── develop (développement actif)
    ├── feature/booking-workflow-refactor
    ├── fix/upload-security-issue
    └── chore/update-dependencies
```

### Convention Commits
```bash
# Features
git commit -m "feat: add design version tracking"

# Bugfixes
git commit -m "fix: resolve N+1 query in dashboard"

# Refactoring
git commit -m "refactor: extract booking logic to service"

# Tests
git commit -m "test: add coverage for payment workflow"

# Documentation
git commit -m "docs: update API documentation"

# Chores
git commit -m "chore: upgrade Laravel to 12.1"
```

### Pull Request Process

1. **Créer branche depuis develop**
```bash
git checkout develop
git pull origin develop
git checkout -b feature/ma-feature
```

2. **Développer et tester**
```bash
# Commits atomiques
git add .
git commit -m "feat: implement feature part 1"

# Tests locaux
composer test
```

3. **Créer PR**

**Title** : `[FEATURE] Add booking workflow automation`

**Description** :
```markdown
## Changements
- Ajout BookingRequestService
- Tests workflow complet
- Documentation API mise à jour

## Tests
- [x] Unit tests passent
- [x] Feature tests passent
- [x] Tests manuels effectués

## Checklist
- [x] Code formaté (Pint)
- [x] Pas de `dd()` ou `dump()` 
- [x] Variables `.env.example` à jour
```

4. **Review & Merge**
- 1 approbation minimum
- Tests CI verts
- Merge via "Squash and merge"

## 🧪 Testing

### Structure Tests
```
tests/
├── Feature/
│   ├── BookingWorkflowTest.php        # Tests intégration
│   ├── ConversationExpirationTest.php
│   └── Api/
│       └── BookingRequestApiTest.php  # Tests API
└── Unit/
    ├── Services/
    │   └── BookingRequestServiceTest.php
    └── Traits/
        └── HasWorkingHoursTest.php
```

### Écrire Un Test Feature
```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_can_create_booking_request()
    {
        // Arrange
        $client = User::factory()->client()->create();
        $tattooer = Tattooer::factory()->create();

        // Act
        $response = $this->actingAs($client, 'sanctum')
            ->postJson('/api/booking-requests', [
                'bookable_id' => $tattooer->id,
                'bookable_type' => Tattooer::class,
                'description' => 'Test tattoo',
            ]);

        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('booking_requests', [
            'client_id' => $client->client->id,
            'status' => 'pending',
        ]);
    }
}
```

### Factories & Seeders

**Factory BookingRequest** :
```php
public function definition(): array
{
    return [
        'client_id' => Client::factory(),
        'bookable_type' => Tattooer::class,
        'bookable_id' => Tattooer::factory(),
        'status' => BookingRequest::STATUS_PENDING,
        'tattoo_size' => fake()->randomElement(['small', 'medium', 'large']),
        'body_zone' => fake()->randomElement(['bras', 'dos', 'jambe']),
        'description' => fake()->paragraph(),
    ];
}

// States
public function accepted(): static
{
    return $this->state(fn (array $attributes) => [
        'status' => BookingRequest::STATUS_ACCEPTED,
        'accepted_at' => now(),
    ]);
}
```

**Utilisation** :
```php
$booking = BookingRequest::factory()->accepted()->create();
```

## 🐛 Debugging

### Telescope (Local)
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Accès : http://localhost:8000/telescope

### Ray (Alternative Premium)
```bash
composer require spatie/laravel-ray
```

```php
ray($bookingRequest)->green();
ray()->showEvents();
```

### Clockwork (Browser Extension)
Profiling requêtes SQL, cache, etc.
```bash
composer require itsgoingd/clockwork
```

### Debug Bar
```bash
composer require barryvdh/laravel-debugbar --dev
```

### Logs Structurés
```php
Log::channel('booking')->info('Booking accepted', [
    'booking_id' => $booking->id,
    'tattooer_id' => $tattooer->id,
    'deposit_amount' => $booking->total_deposit_amount,
]);
```

## ⚡ Performance

### N+1 Queries

❌ **Mauvais** :
```php
$tattooers = Tattooer::all();
foreach ($tattooers as $tattooer) {
    echo $tattooer->user->name; // N+1 !
}
```

✅ **Bon** :
```php
$tattooers = Tattooer::with('user')->get();
foreach ($tattooers as $tattooer) {
    echo $tattooer->user->name;
}
```

### Eager Loading Conditionnel
```php
$bookings = BookingRequest::query()
    ->when($includeMessages, function($query) {
        $query->with('conversation.messages');
    })
    ->get();
```

### Caching

```php
// Cache simple
$tattooers = Cache::remember('marketplace.featured', 1800, function() {
    return Tattooer::featured()->get();
});

// Cache tags (invalider groupes)
Cache::tags(['marketplace', 'tattooers'])->flush();

// Cache partiel (Eloquent)
$tattooer = Tattooer::find($id);
$stats = $tattooer->remember(3600)->getBookingStats();
```

### Queues

Déporter tâches longues :

```php
// Dans contrôleur
ProcessBookingRequest::dispatch($booking);

// Job
class ProcessBookingRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function handle(BookingRequestService $service)
    {
        $service->processAsync($this->booking);
    }
}
```

### Optimisations DB

```php
// Indexation appropriée
Schema::table('booking_requests', function (Blueprint $table) {
    $table->index(['status', 'created_at']); // Composite index
    $table->index('bookable_id'); // Foreign key
});

// Requêtes optimisées
$stats = DB::table('booking_requests')
    ->selectRaw('
        COUNT(*) as total,
        SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed
    ')
    ->where('tattooer_id', $tattooerId)
    ->first();
```

## 📚 Ressesses Utiles

### Documentation Laravel
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel News](https://laravel-news.com/)
- [Laracasts](https://laracasts.com/)

### Packages Recommandés
- **Spatie** : MediaLibrary, Activitylog, Backup
- **Laravel Shift** : Code quality tools
- **Beyond Code** : Debugging tools

### Outils
- **Laravel Telescope** : Debugging local
- **Laravel Horizon** : Queue monitoring
- **Laravel Pulse** : Performance monitoring
- **Sentry** : Error tracking production

## 🔍 Bonnes Pratiques

### Validation
```php
// Form Request pour validation complexe
class StoreBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => 'required|string|max:1000',
            'budget_range' => 'required|regex:/^\d+-\d+$/',
            'tattoo_size' => ['required', Rule::in(['small', 'medium', 'large'])],
        ];
    }
}
```

### Error Handling
```php
// Exceptions personnalisées
class BookingException extends Exception
{
    public static function invalidStatus(string $status): self
    {
        return new self("Invalid booking status: {$status}");
    }
}

// Dans service
if (!$booking->canAccept()) {
    throw BookingException::invalidStatus($booking->status);
}
```

### Events & Listeners
```php
// Event
class BookingAccepted
{
    public function __construct(public BookingRequest $booking) {}
}

// Listener
class SendBookingNotification
{
    public function handle(BookingAccepted $event)
    {
        // Envoyer notification
    }
}
```

Ce guide évoluera avec l'application. N'hésitez pas à proposer des améliorations !
