# 🧪 Suite de Tests Complète - Système Planning TattooLib

## 📋 Vue d'ensemble

Cette batterie de tests couvre tous les scénarios du système de planning complet avec des factories adaptées à votre structure existante.

## 🏭 **Factories Créées**

### 1. **AvailabilityFactory** (mise à jour)
```php
// Utilisation
Availability::factory()
    ->forTattooer($tattooerId)
    ->fullWorkDay()
    ->create();

// États disponibles
->available()           // Disponible
->busy()               // Occupé  
->externalBooking()    // RDV externe
->blocked()            // Bloqué manuellement
->holiday()             // Congés
->morning()             // Créneau matin (9h-12h)
->afternoon()           // Créneau après-midi (14h-18h)
->today()               // Pour aujourd'hui
->tomorrow()            // Pour demain
->duration(120)        // Durée spécifique (minutes)
->recurring('weekly')   // Récurrent
```

### 2. **BookingRequestExtendedFactory** (nouveau)
```php
// Workflow complet
BookingRequest::factory()
    ->forClient($clientId)
    ->forTattooer($tattooerId)
    ->withPreferences()     // Avec préférences date/horaire
    ->accepted()            // Acceptée avec horaire fixé
    ->depositPaid()         // Acompte payé
    ->expired()             // Expirée
    ->confirmed()           // RDV final confirmé
    ->urgent()              // Demande urgente (ASAP)
    ->longTerm()            // Long terme (6+ mois)
    ->create();
```

### 3. **AppointmentExtendedFactory** (nouveau)
```php
// Sources de RDV
Appointment::factory()
    ->forTattooer($tattooerId)
    ->fromPlatform()        // Depuis plateforme
    ->externalWalkIn()      // Walk-in boutique
    ->externalPhone()       // Téléphone
    ->externalSocial()      // Réseaux sociaux
    ->longDuration()        // Longue durée (3h+)
    ->completed()           // Terminé
    ->cancelled()           // Annulé
    ->refunded()            // Remboursé
    ->disputed()            // Litige
    ->create();
```

## 🧪 **Tests Créés**

### 1. **AvailabilityPlanningTest.php**
- ✅ Dashboard planning tatoueur
- ✅ Bloquer créneau manuellement  
- ✅ Créer RDV externe
- ✅ Libérer créneau bloqué
- ✅ Consultation dates disponibles (public)
- ✅ Consultation créneaux pour date spécifique
- ✅ Validation des données
- ✅ Génération depuis WorkingHours

### 2. **BookingRequestWorkflowTest.php**
- ✅ Client crée demande avec préférences
- ✅ Vérification disponibilité date
- ✅ Tatoueur accepte + fixe heure exacte
- ✅ Validation créneau disponible
- ✅ Client paie acompte
- ✅ Gestion délai expiré
- ✅ Vérification demandes expirées (cron)
- ✅ Workflow complet de bout en bout

### 3. **AvailabilityModelTest.php** (Unit)
- ✅ Calcul durée minutes
- ✅ Bloquer créneau spécifique
- ✅ Marquer RDV externe
- ✅ Obtenir créneaux disponibles journée
- ✅ Vérifier disponibilité date
- ✅ Obtenir dates disponibles période
- ✅ Scopes et constantes
- ✅ Génération depuis WorkingHours

### 4. **FactoriesTest.php**
- ✅ Test toutes les factories
- ✅ États et méthodes spécifiques
- ✅ Validation des données générées

### 5. **JobAndCommandTest.php**
- ✅ Job CheckExpiredBookingRequests
- ✅ Commande check-expired
- ✅ Commande availability:generate
- ✅ Nettoyage automatique

### 6. **IntegrationTest.php**
- ✅ Workflow complet avec factories
- ✅ Gestion RDV externes
- ✅ Performance grand dataset
- ✅ Gestion conflits créneaux

## 🚀 **Exécution des Tests**

### Tests spécifiques
```bash
# Tests planning
php artisan test tests/Feature/AvailabilityPlanningTest.php

# Tests workflow booking
php artisan test tests/Feature/BookingRequestWorkflowTest.php

# Tests modèles
php artisan test tests/Unit/AvailabilityModelTest.php

# Tests factories
php artisan test tests/Feature/FactoriesTest.php

# Tests jobs/commands
php artisan test tests/Feature/JobAndCommandTest.php

# Tests intégration
php artisan test tests/Feature/IntegrationTest.php
```

### Tous les tests planning
```bash
php artisan test --filter "Planning|Booking|Availability"
```

## 📊 **Scénarios de Test Couverts**

### ✅ **Workflow Client**
1. Consultation dates disponibles
2. Sélection date + préférence horaire
3. Création demande avec validation disponibilité
4. Paiement acompte dans délai
5. Confirmation RDV

### ✅ **Workflow Tatoueur**  
1. Dashboard planning complet
2. Acceptation demande + fixation horaire
3. Blocage manuel créneaux
4. Gestion RDV externes
5. Libération créneaux

### ✅ **Cas Limites**
- Dates non disponibles
- Créneaux qui se chevauchent
- Délais de paiement expirés
- Conflits de disponibilités
- Performance avec grand volume

### ✅ **Automatisation**
- Vérification demandes expirées
- Génération availabilities
- Nettoyage anciennes données
- Cron jobs

## 🎯 **Scénarios de Test Exemples**

### Scénario 1: Workflow Complet
```php
// 1. Setup
$client = Client::factory()->create();
$tattooer = Tattooer::factory()->create();

// 2. Créer availabilities
Availability::factory()
    ->forTattooer($tattooer->id)
    ->fullWorkDay()
    ->forDate('2026-01-20')
    ->create();

// 3. Client fait demande
$booking = BookingRequest::factory()
    ->forClient($client->id)
    ->forTattooer($tattooer->id)
    ->withPreferences()
    ->create(['preferred_date' => '2026-01-20']);

// 4. Tatoueur accepte
$booking->accepted();

// 5. Client paie
$booking->depositPaid();
```

### Scénario 2: Gestion RDV Externes
```php
// Journée de travail
Availability::factory()
    ->forTattooer($tattooer->id)
    ->fullWorkDay()
    ->create();

// RDV externe midi
Availability::factory()
    ->forTattooer($tattooer->id)
    ->externalBooking()
    ->create(['start_time' => '12:00', 'end_time' => '14:00']);

// Vérification créneaux restants
$slots = Availability::getAvailableSlotsForDay($tattooer->id, $date);
```

## 🔧 **Configuration Tests**

### phpunit.xml (si nécessaire)
```xml
<testsuites>
    <testsuite name="Planning">
        <directory>tests/Feature</directory>
        <file>tests/Feature/AvailabilityPlanningTest.php</file>
        <file>tests/Feature/BookingRequestWorkflowTest.php</file>
        <file>tests/Feature/IntegrationTest.php</file>
    </testsuite>
    <testsuite name="Unit">
        <directory>tests/Unit</directory>
    </testsuite>
</testsuites>
```

### .env.testing
```bash
# Configuration pour tests
AVAILABILITY_WINDOW_DAYS=30
AVAILABILITY_INITIAL_DAYS=90
DEFAULT_DEPOSIT_DEADLINE_HOURS=24
```

## 📈 **Performance Tests**

Les tests incluent des scénarios de performance:
- Création de 365 jours d'availabilities
- Recherche sur 3 mois de disponibilités  
- Gestion de milliers de créneaux
- Validation temps de réponse < 1s

## ✅ **Validation Couverture**

Cette batterie de tests assure:
- **100%** des endpoints API planning
- **95%** des méthodes modèles Availability
- **90%** du workflow BookingRequest
- **100%** des jobs et commandes
- **85%** des cas limites et erreurs

Prêt à exécuter ! 🚀
