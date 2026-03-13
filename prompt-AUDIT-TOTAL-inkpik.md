# 🔍 AUDIT TOTAL — Ink&Pik SaaS
## À exécuter par Claude Code — Résultat exporté dans `AUDIT.md` à la racine

---

## INSTRUCTIONS GÉNÉRALES

Effectuer un audit complet et exhaustif du projet. Lire TOUS les fichiers
mentionnés avant d'écrire quoi que ce soit. Ne faire AUCUNE modification de code.
Produire un fichier `AUDIT.md` à la racine du projet avec les résultats complets.

---

## PÉRIMÈTRE DE L'AUDIT

### A — ARCHITECTURE & STRUCTURE

```bash
# Vue d'ensemble du projet
php artisan about

# Structure des dossiers principaux
find app/ -type f -name "*.php" | sort
find resources/views/ -type f -name "*.blade.php" | sort
php artisan route:list --json > /tmp/routes.json && wc -l /tmp/routes.json

# Migrations
php artisan migrate:status

# Providers & config
cat config/cashier.php
cat config/stripe.php 2>/dev/null || echo "pas de config stripe dédiée"
cat bootstrap/app.php
```

Documenter :
- Stack technique complète (versions Laravel, Livewire, Filament, Cashier, etc.)
- Nombre de routes total
- Nombre de migrations (exécutées / en attente)
- Middlewares enregistrés

---

### B — MODÈLES & BASE DE DONNÉES

```bash
php artisan tinker
```

```php
// Lister toutes les tables
$tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
dd(array_map(fn($t) => array_values((array)$t)[0], $tables));
```

Pour chaque modèle principal, documenter les colonnes, relations et méthodes clés :

```bash
# Modèles à auditer
ls app/Models/
```

Pour chaque modèle : `User`, `Tattooer`, `Piercer`, `Studio`, `StudioArtist`,
`BookingRequest`, `BookingTransaction`, `Deposit`, `Payment`, `Notification` (et autres) :

```php
// Dans tinker — colonnes + counts
dd([
    'users'              => ['cols' => \Schema::getColumnListing('users'),              'count' => \App\Models\User::count()],
    'tattooers'          => ['cols' => \Schema::getColumnListing('tattooers'),          'count' => \App\Models\Tattooer::count()],
    'piercers'           => ['cols' => \Schema::getColumnListing('piercers'),           'count' => \App\Models\Piercer::count()],
    'studios'            => ['cols' => \Schema::getColumnListing('studios'),            'count' => \App\Models\Studio::count()],
    'booking_requests'   => ['cols' => \Schema::getColumnListing('booking_requests'),   'count' => \App\Models\BookingRequest::count()],
    'subscriptions'      => ['cols' => \Schema::getColumnListing('subscriptions'),      'count' => \DB::table('subscriptions')->count()],
]);
```

Documenter pour chaque modèle :
- Colonnes présentes
- Relations définies (`hasMany`, `belongsTo`, `morphTo`...)
- Traits utilisés (`HasSubscription`, `HasStripeConnect`, `SoftDeletes`...)
- Scopes définis (`marketplaceVisible`, `active`...)
- Méthodes métier clés

---

### C — SYSTÈME D'ABONNEMENT & PLANS

```bash
php artisan tinker
```

```php
// État des abonnements en base
dd([
    'subscriptions_total'    => \DB::table('subscriptions')->count(),
    'subscriptions_active'   => \DB::table('subscriptions')->where('stripe_status', 'active')->count(),
    'subscriptions_trialing' => \DB::table('subscriptions')->where('stripe_status', 'trialing')->count(),
    'tattooers_subscribed'   => \App\Models\Tattooer::where('is_subscribed', true)->count(),
    'tattooers_on_trial'     => \App\Models\Tattooer::whereNotNull('trial_ends_at')
                                    ->where('trial_ends_at', '>', now())
                                    ->where('is_subscribed', false)->count(),
    'tattooers_starter'      => \App\Models\Tattooer::where('current_plan', 'starter')->count(),
    'tattooers_pro'          => \App\Models\Tattooer::where('current_plan', 'pro')->count(),
    'piercers_subscribed'    => \App\Models\Piercer::where('is_subscribed', true)->count(),
    'studios_subscribed'     => \App\Models\Studio::where('is_subscribed', true)->count(),
]);
```

Lire et documenter intégralement :
- `app/Traits/HasSubscription.php`
- `app/Traits/HasStripeConnect.php`
- `app/Services/TrialService.php`
- `app/Console/Commands/` — toutes les commandes (cron trial, etc.)
- `app/Http/Middleware/` — tous les middlewares de plan/trial

Matrice à remplir pour chaque feature :

| Feature | TRIAL | STARTER | PRO | STUDIO | Middleware protège ? | Controller protège ? |
|---------|-------|---------|-----|--------|---------------------|---------------------|
| Marketplace profil | ? | ? | ? | ? | ? | ? |
| Réception demandes | ? | ? | ? | ? | ? | ? |
| Chat client | ? | ? | ? | ? | ? | ? |
| Calendrier | ? | ? | ? | ? | ? | ? |
| Paiements | ? | ? | ? | ? | ? | ? |
| Fiche client avancée | ? | ? | ? | ? | ? | ? |
| Analytics | ? | ? | ? | ? | ? | ? |
| Export PDF | ? | ? | ? | ? | ? | ? |
| Export CSV | ? | ? | ? | ? | ? | ? |
| Portfolio illimité | ? | ? | ? | ? | ? | ? |
| Filament admin | ? | ? | ? | ? | ? | ? |

---

### D — STRIPE & PAIEMENTS

```bash
grep -rn "PaymentIntent\|Checkout\|on_behalf_of\|transfer_data\|application_fee" \
  app/ --include="*.php"
```

Lire et documenter :
- `app/Services/StripeService.php` — méthodes complètes
- `app/Http/Controllers/DepositController.php`
- `app/Http/Controllers/BalancePaymentController.php`
- `app/Http/Controllers/StripeConnectController.php`
- Tout WebhookController custom

Pour chaque point de création de PaymentIntent, documenter :

| Fichier | Méthode | on_behalf_of | transfer_data | application_fee | Fallback si pas Connect |
|---------|---------|-------------|---------------|----------------|------------------------|
| ? | ? | ✅/❌ | ✅/❌ | ✅/❌ | ? |

Vérifier la configuration Stripe :
```bash
php artisan tinker --execute="
dd([
    'stripe_key_set'     => !empty(config('cashier.key')),
    'stripe_secret_set'  => !empty(config('cashier.secret')),
    'webhook_secret_set' => !empty(config('cashier.webhook.secret')),
    'cashier_model'      => config('cashier.model'),
    'cashier_currency'   => config('cashier.currency'),
]);
"
```

---

### E — WEBHOOKS

```bash
# Routes webhook enregistrées
php artisan route:list | grep -i webhook

# CSRF exceptions
grep -A 20 "withMiddleware\|VerifyCsrf\|except" bootstrap/app.php

# Handlers implémentés
grep -rn "handle.*Webhook\|webhook.*handle\|customer\.subscription\|payment_intent\|account\.updated" \
  app/ --include="*.php"
```

Documenter pour chaque événement Stripe :

| Événement Stripe | Handler présent | Action effectuée |
|-----------------|----------------|-----------------|
| `customer.subscription.created` | ✅/❌ | ? |
| `customer.subscription.updated` | ✅/❌ | ? |
| `customer.subscription.deleted` | ✅/❌ | ? |
| `payment_intent.succeeded` | ✅/❌ | ? |
| `checkout.session.completed` | ✅/❌ | ? |
| `account.updated` | ✅/❌ | ? |
| `invoice.payment_succeeded` | ✅/❌ | ? |
| `invoice.payment_failed` | ✅/❌ | ? |

---

### F — SÉCURITÉ & AUTHENTIFICATION

```bash
# Auth & 2FA
grep -rn "2fa\|TwoFactor\|Fortify\|twoFactor" app/ config/ --include="*.php" -l

# Policies et Gates
ls app/Policies/ 2>/dev/null || echo "Pas de Policies"
grep -rn "Gate::\|can(\|authorize(" app/Http/Controllers/ --include="*.php" | head -20

# Middlewares d'auth sur les routes
php artisan route:list | grep "auth\|verified\|admin"

# RGPD
grep -rn "cookie\|rgpd\|gdpr\|consent" app/ resources/ --include="*.php" -l
```

Documenter :
- Système d'auth en place (Fortify, Sanctum, Breeze...)
- 2FA activé ? Sur quels comptes ?
- Protection CSRF correcte partout sauf webhooks ?
- Données personnelles : chiffrement, soft deletes, suppression compte
- Pages légales présentes (CGU, CGV, RGPD, mentions légales, cookies)

---

### G — PERFORMANCE & QUALITÉ DU CODE

```bash
# Détection N+1 potentiels
grep -rn "->get()\|->all()\|->paginate()" app/Http/Controllers/ --include="*.php" | \
  grep -v "with(\|load(" | head -20

# Routes sans middleware
php artisan route:list | grep -v "auth\|verified\|web" | grep "GET\|POST"

# Variables non définies dans les vues (controllers qui n'envoient pas toutes les vars)
grep -rn "\$tattooer\|\$piercer\|\$studio\|\$artist" \
  resources/views/ --include="*.blade.php" | grep -v "@if\|@isset\|@empty" | head -20

# Taille des controllers (complexité)
wc -l app/Http/Controllers/*.php app/Http/Controllers/**/*.php 2>/dev/null | sort -rn | head -20
```

---

### H — MARKETPLACE

Lire et documenter :
- Scope `marketplaceVisible()` sur Tattooer et Piercer
- Comment les studios apparaissent (ou n'apparaissent pas) dans la marketplace
- Système de tri actuel (PRO en premier ?)
- Filtres disponibles et leur état de fonctionnement
- Gestion du `is_blocked`

```bash
php artisan tinker --execute="
dd([
    'tattooers_visible'   => \App\Models\Tattooer::marketplaceVisible()->count(),
    'piercers_visible'    => \App\Models\Piercer::marketplaceVisible()->count(),
    'tattooers_blocked'   => \App\Models\Tattooer::where('is_blocked', true)->count(),
    'piercers_blocked'    => \App\Models\Piercer::where('is_blocked', true)->count(),
]);
"
```

---

### I — FONCTIONNALITÉS MÉTIER

Pour chaque fonctionnalité, documenter l'état (✅ Complet / ⚠️ Partiel / ❌ Manquant / 🔴 Bugué) :

#### Booking / Réservations
- Flux complet demande → confirmation → paiement acompte → RDV → paiement solde
- Gestion des statuts (pending, confirmed, completed, cancelled, expired...)
- Notifications à chaque étape
- Gestion des annulations et remboursements

#### Chat
- Messagerie client ↔ artiste
- Notifications nouveaux messages
- Doublon de RDV depuis le chat (bug connu ?)

#### Calendrier
- Affichage des RDV
- Blocage de créneaux
- Synchronisation avec les bookings

#### Fiches clients
- Création automatique à la confirmation
- Fiche manuelle (PRO only)
- Export PDF

#### Analytics
- Données disponibles
- Période couverte
- Exactitude des calculs

#### Studio
- Dashboard studio
- Gestion des artistes membres
- Planning studio
- Facturation artistes supplémentaires

#### Notifications
- Types de notifications implémentées
- Canal (database, email, push...)
- Notifications non lues

---

### J — FILAMENT ADMIN

```bash
# Resources Filament
ls app/Filament/Resources/ 2>/dev/null
ls app/Filament/Pages/ 2>/dev/null
ls app/Filament/Widgets/ 2>/dev/null
```

Documenter :
- Resources disponibles dans l'admin
- Accès admin sécurisé (qui peut accéder ?)
- Fonctionnalités d'administration implémentées

---

### K — PROBLÈMES CONNUS & DETTE TECHNIQUE

Recenser TOUS les problèmes identifiés pendant l'audit :

Pour chaque problème :
```
| # | Composant | Description | Criticité | Impact |
```

Niveaux de criticité :
- 🔴 **CRITIQUE** — Bloque l'utilisation ou perd de l'argent
- 🟠 **MAJEUR** — Fonctionnalité cassée mais workaround possible
- 🟡 **MINEUR** — UX dégradée, pas bloquant
- 🔵 **AMÉLIORATION** — Pas un bug, juste mieux à faire

---

## FORMAT DU FICHIER AUDIT.md À GÉNÉRER

```markdown
# 📊 AUDIT INK&PIK — [DATE]
Généré par Claude Code — Ne pas modifier manuellement

## Score global : X/10

---

## 1. STACK TECHNIQUE
[résultats section A]

## 2. BASE DE DONNÉES
[résultats section B — toutes les tables avec colonnes]

## 3. ABONNEMENTS & PLANS
[résultats section C — matrice features complète]

## 4. STRIPE & PAIEMENTS
[résultats section D — tableau PaymentIntents + config]

## 5. WEBHOOKS
[résultats section E — tableau événements]

## 6. SÉCURITÉ
[résultats section F]

## 7. PERFORMANCE & QUALITÉ
[résultats section G]

## 8. MARKETPLACE
[résultats section H]

## 9. FONCTIONNALITÉS MÉTIER
[résultats section I — état de chaque feature]

## 10. FILAMENT ADMIN
[résultats section J]

## 11. PROBLÈMES IDENTIFIÉS
[tableau complet criticité + description]

## 12. RECOMMANDATIONS PRIORITAIRES
### 🔴 À corriger immédiatement
### 🟠 À corriger avant le lancement
### 🟡 À planifier post-lancement
### 🔵 Améliorations futures

## 13. RÉSUMÉ EXÉCUTIF
[Paragraphe synthétique — état global du projet,
 prêt pour bêta ? points bloquants ?]
```

---

## ⚠️ CONSIGNES FINALES

- **Aucune modification de code** pendant cet audit
- **Tout documenter** même les parties qui fonctionnent bien
- **Être précis** sur les numéros de lignes et noms de fichiers exacts
- **Score global /10** basé sur : sécurité (30%), fonctionnalités (30%),
  qualité code (20%), Stripe/paiements (20%)
- Le fichier `AUDIT.md` doit être **autonome** — quelqu'un qui ne connaît
  pas le projet doit comprendre l'état exact du SaaS en le lisant
- Exporter dans **`AUDIT.md` à la racine du projet** (pas dans un sous-dossier)
