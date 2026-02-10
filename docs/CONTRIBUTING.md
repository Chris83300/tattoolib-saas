# 🤝 Contributing to Ink&Pik

Guide pour contribuer au projet Ink&Pik SaaS.

## 📋 Table des Matières

- [Process de Contribution](#process-de-contribution)
- [Setup Environnement](#setup-environnement)
- [Standards de Code](#standards-de-code)
- [Process Pull Request](#process-pull-request)
- [Guidelines de Commit](#guidelines-de-commit)
- [Testing](#testing)
- [Documentation](#documentation)

## 🔄 Process de Contribution

### 1. Fork & Clone
```bash
# Forker le dépôt sur GitHub
git clone https://github.com/VOTRE_USERNAME/inkpik-saas.git
cd inkpik-saas
git remote add upstream https://github.com/ORIGINAL_OWNER/inkpik-saas.git
```

### 2. Branche de Développement
```bash
git checkout develop
git pull upstream develop
git checkout -b feature/votre-feature
```

### 3. Développement
- Suivre les standards de code
- Ajouter les tests nécessaires
- Mettre à jour la documentation

### 4. Pull Request
- Push vers votre fork
- Créer PR vers `develop`
- Attendre review et merge

## 🛠 Setup Environnement

### Prérequis
- PHP >= 8.3
- Composer >= 2.6
- Node.js >= 20.x
- MySQL >= 8.0 / PostgreSQL >= 14
- Redis >= 6.0

### Installation
```bash
# Cloner votre fork
git clone https://github.com/VOTRE_USERNAME/inkpik-saas.git
cd inkpik-saas

# Installer dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Démarrer serveurs
npm run dev
php artisan serve
php artisan queue:work
```

### Outils de Développement
```bash
# Code formatting
composer pint

# Tests
composer test

# Coverage
composer test:coverage

# Debug tools
composer require laravel/telescope --dev
php artisan telescope:install
```

## 📝 Standards de Code

### PHP Standards

#### Style Guide
- Utiliser **Laravel Pint** pour le formatage
- Indentation : 4 espaces
- Maximum 120 caractères par ligne
- Pas de trailing whitespace

#### Naming Conventions
```php
// Classes : PascalCase
class BookingRequestService {}

// Methods : camelCase
public function acceptBooking() {}

// Variables : camelCase
$bookingRequest = new BookingRequest();

// Constants : UPPER_SNAKE_CASE
const MAX_DESIGN_VERSIONS = 3;

// Private properties : snake_case avec underscore
private $booking_request_id;
```

#### Structure de Classe
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\BookingRequest;

/**
 * Service pour gérer les demandes de réservation.
 */
class BookingRequestService
{
    // 1. Constants
    const DEFAULT_DEPOSIT_RATE = 30;
    
    // 2. Properties
    public function __construct(
        private NotificationService $notifications,
        private StripeService $stripe
    ) {}
    
    // 3. Public methods
    public function accept(BookingRequest $booking, array $data): BookingRequest
    {
        // Implementation
    }
    
    // 4. Private methods
    private function calculateDeposit(float $total): float
    {
        return round($total * (self::DEFAULT_DEPOSIT_RATE / 100), 2);
    }
}
```

#### Documentation
```php
/**
 * Accepter une demande de réservation.
 *
 * @param BookingRequest $booking La demande à accepter
 * @param array $data Données d'acceptation
 * @return BookingRequest La demande mise à jour
 *
 * @throws BookingException Si le statut est invalide
 * @throws AuthorizationException Si non autorisé
 */
public function accept(BookingRequest $booking, array $data): BookingRequest
```

### JavaScript/Alpine.js Standards

#### Style Guide
```javascript
// Functions : camelCase
function handleBookingSubmit() {}

// Variables : camelCase
const bookingData = {};

// Constants : UPPER_SNAKE_CASE
const API_BASE_URL = '/api';

// Alpine.js components
Alpine.data('bookingForm', () => ({
    // 1. Data
    formData: {},
    
    // 2. Methods
    submit() {
        // Implementation
    },
    
    // 3. Computed
    get isValid() {
        return this.formData.description?.length > 0;
    }
}));
```

### Blade/Livewire Standards

#### Component Structure
```php
// Livewire Component
class BookingForm extends Component
{
    // 1. Properties
    public $description = '';
    public $tattooSize = '';
    
    // 2. Validation rules
    protected $rules = [
        'description' => 'required|string|max:1000',
        'tattooSize' => 'required|in:small,medium,large',
    ];
    
    // 3. Methods
    public function submit()
    {
        $this->validate();
        // Logic
    }
    
    // 4. Render
    public function render()
    {
        return view('livewire.booking-form');
    }
}
```

#### Blade Templates
```blade
{{-- Use proper indentation --}}
<div class="space-y-4">
    {{-- Form with proper attributes --}}
    <form wire:submit="submit">
        {{-- Labels and inputs --}}
        <label for="description">Description</label>
        <textarea 
            id="description"
            wire:model="description"
            class="w-full rounded-lg border-gray-300"
            rows="4"
        ></textarea>
        
        {{-- Error handling --}}
        @error('description')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </form>
</div>
```

## 🔄 Process Pull Request

### 1. Pré-commit Checklist
- [ ] Code formaté avec `composer pint`
- [ ] Tests passants : `composer test`
- [ ] Pas de `dd()`, `dump()`, ou `var_dump()`
- [ ] Documentation mise à jour si nécessaire
- [ ] Features testées manuellement

### 2. Branch Strategy
```
main (production)
├── staging (pré-production)
└── develop (développement)
    ├── feature/booking-workflow
    ├── fix/security-vulnerability
    ├── refactor/payment-service
    └── chore/update-dependencies
```

### 3. PR Template

```markdown
## Type de Changement
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Description
Description détaillée des changements...

## Motivation
Pourquoi ce changement est nécessaire...

## Tests Commentés
- [ ] Unit tests ajoutés/mis à jour
- [ ] Feature tests ajoutés/mis à jour
- [ ] Tests manuels effectués

## Checklist
- [ ] Code suit les standards du projet
- [ ] Tests passent localement
- [ ] Documentation mise à jour
- [ ] Pas de breaking changes non documentés

## Screenshots (si applicable)
Ajouter screenshots pour les changements UI...

## Issues Connexes
Closes #123
```

### 4. Review Process
1. **Automated Checks** : CI/CD pipeline
2. **Code Review** : Au moins 1 reviewer
3. **Testing** : QA sur staging
4. **Merge** : Squash and merge vers `develop`

## 📝 Guidelines de Commit

### Convention de Commits
Format : `<type>(<scope>): <description>`

#### Types
- `feat` : Nouvelle fonctionnalité
- `fix` : Correction de bug
- `docs` : Documentation
- `style` : Formatting, style (pas de changement de code)
- `refactor` : Refactoring
- `test` : Tests
- `chore` : Maintenance, dépendances

#### Exemples
```bash
feat(booking): add design version tracking
fix(payment): resolve stripe webhook timeout
docs(api): update payment endpoints documentation
refactor(services): extract payment logic to service
test(booking): add coverage for acceptance workflow
chore(deps): update laravel to v12.1
```

#### Messages de Commit
```bash
# Bon
feat(booking): add design version tracking

- Add version counter to booking_requests table
- Implement design upload with versioning
- Add validation for max versions per plan
- Update UI to display version history

Closes #156

# Mauvais
add design versions
fixed bugs
update stuff
```

## 🧪 Testing

### Structure des Tests
```
tests/
├── Feature/           # Tests d'intégration
│   ├── BookingWorkflowTest.php
│   ├── PaymentTest.php
│   └── Api/
│       └── BookingRequestApiTest.php
└── Unit/              # Tests unitaires
    ├── Services/
    │   └── BookingRequestServiceTest.php
    ├── Models/
    │   └── BookingRequestTest.php
    └── Traits/
        └── HasWorkingHoursTest.php
```

### Écrire des Tests

#### Feature Test Example
```php
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
            'description' => 'Test tattoo description',
        ]);

    // Assert
    $response->assertStatus(201);
    $this->assertDatabaseHas('booking_requests', [
        'client_id' => $client->client->id,
        'description' => 'Test tattoo description',
    ]);
}
```

#### Unit Test Example
```php
/** @test */
public function it_calculates_deposit_correctly()
{
    // Arrange
    $service = new BookingRequestService();
    $total = 300;
    $rate = 30;

    // Act
    $deposit = $service->calculateDeposit($total, $rate);

    // Assert
    expect($deposit)->toBe(90.0);
}
```

### Commandes de Test
```bash
# Tous les tests
composer test

# Tests spécifiques
php artisan test --filter BookingWorkflowTest

# Coverage
composer test:coverage

# Tests parallèles
composer test:parallel

# Watch mode
php artisan test --watch
```

## 📚 Documentation

### Types de Documentation
1. **Code Documentation** : PHPDoc, comments
2. **API Documentation** : OpenAPI/Swagger
3. **User Documentation** : Guides, tutoriels
4. **Developer Documentation** : Architecture, patterns

### Mise à Jour Documentation
- Mettre à jour `docs/API.md` pour les changements d'API
- Mettre à jour `docs/DEVELOPER_GUIDE.md` pour les changements d'architecture
- Mettre à jour `README.md` pour les changements majeurs
- Ajouter des commentaires dans le code pour la logique complexe

### Exemple de Documentation API
```php
/**
 * @OA\Post(
 *     path="/booking-requests",
 *     summary="Create a new booking request",
 *     tags={"Booking Requests"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="tattoo_size", type="string", enum={"small","medium","large"})
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Booking request created successfully"
 *     )
 * )
 */
```

## 🚀 Déploiement

### Process de Release
1. **Development** : Branch `develop`
2. **Staging** : Merge vers `staging`
3. **Production** : Tag et merge vers `main`

### Versioning
- Semantic Versioning : `MAJOR.MINOR.PATCH`
- Exemples : `v1.2.3`, `v2.0.0`

### Release Checklist
- [ ] Tous les tests passent
- [ ] Documentation à jour
- [ ] Changelog mis à jour
- [ ] Migration testée
- [ ] Performance testée
- [ ] Sécurité validée

## 🐞 Signalement de Bugs

### Bug Report Template
```markdown
## Description
Description claire et concise du bug...

## Étapes pour Reproduire
1. Aller à...
2. Cliquer sur...
3. Voir l'erreur...

## Comportement Attendu
Ce qui devrait se produire...

## Comportement Actuel
Ce qui se produit réellement...

## Screenshots
Ajouter screenshots si applicable...

## Environnement
- OS: [Windows 10/11, macOS, Linux]
- Navigateur: [Chrome, Firefox, Safari]
- Version: [v1.2.3]

## Logs
Ajouter les logs d'erreur pertinents...
```

## 💡 Suggestions d'Amélioration

### Feature Request Template
```markdown
## Description
Description de la fonctionnalité proposée...

## Problème Résolu
Quel problème cette fonctionnalité résout-elle...

## Solution Proposée
Description détaillée de la solution...

## Alternatives Considérées
Autres approches envisagées...

## Impact
Impact sur l'application et les utilisateurs...
```

## 📞 Support

### Canaux de Communication
- **Discord** : Serveur communautaire Ink&Pik
- **GitHub Issues** : Bugs et feature requests
- **Email** : dev@inkpik.fr pour questions techniques

### Code de Conduite
- Respect mutuel
- Communication constructive
- Acceptation des feedbacks
- Aide aux nouveaux contributeurs

---

Merci de contribuer à Ink&Pik ! Votre aide est précieuse pour améliorer la plateforme. 🎨
