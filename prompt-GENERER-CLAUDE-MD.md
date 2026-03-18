# 📋 GÉNÉRER `CLAUDE.md` — Fichier de contexte Claude Code pour Ink&Pik

## Objectif
Créer un fichier `CLAUDE.md` à la racine du projet que Claude Code lira
automatiquement au début de chaque session. Ce fichier doit contenir tout
le contexte nécessaire pour travailler efficacement sans avoir à tout réexpliquer.

---

## PHASE 1 — COLLECTER LES INFORMATIONS

```bash
# Stack technique exacte
php artisan about --json 2>/dev/null | head -50 || php artisan about

# Versions packages clés
composer show laravel/framework filament/filament livewire/livewire \
  laravel/cashier stripe/stripe-php --format=json 2>/dev/null | \
  grep -E '"name"|"version"' | head -30

# Versions npm
node -e "const p=require('./package.json'); \
  ['tailwindcss','vite','@tailwindcss/vite','laravel-vite-plugin'].forEach(k=> \
  console.log(k+':', p.devDependencies?.[k] || p.dependencies?.[k] || 'absent'))"

# Structure du projet
php artisan route:list | wc -l
find app/Models -name "*.php" | wc -l
find app/Http/Controllers -name "*.php" | wc -l
find app/Livewire -name "*.php" | wc -l
find app/Filament -name "*.php" | wc -l
find resources/views -name "*.blade.php" | wc -l

# Migrations exécutées
php artisan migrate:status | grep -c "Ran"

# Prix Stripe (depuis .env)
grep "STRIPE_PRICE" .env | sed 's/=.*/=***/'

# Stripe connect status
grep "STRIPE_CONNECT\|STRIPE_KEY\|CASHIER" .env | \
  sed 's/=.*/=***MASQUÉ***/'
```

---

## PHASE 2 — GÉNÉRER `CLAUDE.md`

Créer le fichier `CLAUDE.md` à la racine avec ce contenu (adapter selon
les vraies valeurs collectées en Phase 1) :

````markdown
# 🎨 CLAUDE.md — Contexte Ink&Pik pour Claude Code
## Dernière mise à jour : [DATE]
## ⚠️ Mettre à jour ce fichier après chaque modification architecturale majeure

---

## 🎯 LE PROJET

**Ink&Pik** = SaaS marketplace + gestion pour tatoueurs, pierceurs et studios en France.
Plateforme de mise en relation et de gestion complète : réservations, paiements,
fiches clients, traçabilité, compliance SIRET, aftercare.

**URL locale** : http://tattoolib-saas.test
**Dépôt** : [nom du repo]
**Environnement dev** : Laragon (Windows), PHP 8.3, MySQL

---

## 🛠️ STACK TECHNIQUE EXACTE

| Composant | Version | Notes |
|-----------|---------|-------|
| Laravel | 12.x | Framework principal |
| PHP | 8.3.x | |
| Filament | 4.x | Panel admin — structure v4 stricte |
| Livewire | 3.7.x | Composants réactifs |
| TailwindCSS | 4.x | **v4** (pas v3) via @tailwindcss/vite |
| Alpine.js | 3.x | JS léger inline |
| Vite | 6.x | Bundler |
| Laravel Cashier | 16.x | Abonnements Stripe |
| Stripe PHP SDK | 17.x | Paiements |
| Spatie Permission | 6.x | Rôles & permissions |
| Spatie MediaLibrary | 11.x | Gestion médias |
| DomPDF | 3.x | Export PDF |
| Firebase (kreait) | 6.x | Notifications push FCM |

---

## 💰 PRICING & PLANS

| Plan | Prix | Commission | Stripe Price ID |
|------|------|-----------|-----------------|
| STARTER | 9,99€/mois | 7% via application_fee | `STRIPE_PRICE_ID_STARTER` |
| PRO | 29,99€/mois | 0% | `STRIPE_PRICE_ID_PRO` |
| STUDIO | 59,99€/mois + 24,99€/artiste supp. | configurable | `STRIPE_PRICE_ID_STUDIO` |
| Trial | 14 jours | 0% | sans CB |

**Bêta-testeurs** : coupon `BETA-LAUNCH-30` → -30% à vie + 1 mois offert

---

## 🏗️ ARCHITECTURE CLÉS

### Modèles principaux
- **User** → Billable Cashier (abonnements sur User, pas sur Tattooer/Piercer)
- **Tattooer** → artiste indépendant ou studio, `HasSubscription`, `HasStripeConnect`
- **Piercer** → même architecture que Tattooer (polymorphisme)
- **Studio** → `payment_mode` (studio|direct_artist), `artist_commission_rate`
- **StudioArtist** → pivot User↔Studio
- **BookingRequest** → polymorphique `bookable_type/bookable_id` (Tattooer|Piercer)
- **Conversation** → `type` (booking|support|admin_private)
- **Message** → colonnes : `content` (pas `body`), `sender_type`, `user_id`

### Relations importantes
```php
User → hasOne(Tattooer) | hasOne(Piercer) | hasOne(Client) | hasOne(Studio)
Tattooer/Piercer → polymorphique via BookingRequest (bookable_type/bookable_id)
Studio → Billable via User (pas directement)
$studio->user->subscription('default') // ← accès abonnement studio
```

### Traits partagés
- `HasSubscription` → `isPro()`, `isStarter()`, `isOnTrial()`, `canAccessProFeature()`
- `HasStripeConnect` → `hasStripeConnect()`, `isStripeConnectActive()`, `canSetupStripeConnect()`
- `HasAccountDeletion` → `destroyAccount()` partagé sur 4 controllers

---

## 💳 STRIPE CONNECT — FLUX DE PAIEMENT

### Architecture Direct Charges (frais sur l'artiste)
```
Client paie → Plateforme reçoit
  → on_behalf_of: $artist->stripe_connect_id
  → transfer_data.destination: $artist->stripe_connect_id
  → application_fee_amount: 7% si STARTER, 0% si PRO/trial
Artiste reçoit le net, frais Stripe à sa charge
Plateforme gagne : commission + abonnements
```

### Cas Studio
- `payment_mode = 'studio'` → destination = `$studio->stripe_connect_id`
- `payment_mode = 'direct_artist'` → destination = `$artist->stripe_connect_id`
- Commission studio = `$studio->artist_commission_rate` (nullable, 0-99%)
- **Note** : commission studio non implémentée via Stripe (décision produit)

### Montants
- **En base** : EUROS (decimal) — ex: `deposit_amount = 50.00`
- **Stripe** : CENTIMES (integer) — ex: `5000`
- Conversion : `(int) round($euros * 100)`

---

## 🗄️ BASE DE DONNÉES

**56 tables** — principales :
`users`, `tattooers`, `piercers`, `studios`, `studio_artists`,
`booking_requests` (70+ colonnes), `booking_transactions`,
`conversations`, `messages`, `subscriptions` (Cashier),
`notifications`, `client_care_sheets`, `traceability_records`

### Colonnes sensibles chiffrées (cast `encrypted`)
`client_care_sheets` : `blood_type`, `medical_conditions`, `allergies_details`,
`medications_details`, `parent_id_number`, `parent_name`

### Soft Deletes sur
Tattooer, Piercer, Studio, StudioArtist, BookingRequest, Appointment,
Client, Message, ComplianceRecord, Conversation

---

## 📁 STRUCTURE FILAMENT V4

```
app/Filament/Admin/
├── Pages/          → Pages custom (Dashboard, SupportChat)
├── Resources/
│   └── {Nom}/
│       ├── {Nom}Resource.php
│       ├── Pages/   → List{Nom}.php, Create{Nom}.php, Edit{Nom}.php, View{Nom}.php
│       ├── Schemas/ → {Nom}Form.php
│       └── Tables/  → {Nom}Table.php
└── Widgets/        → Widgets du dashboard
```

### Règles Filament v4 STRICTES
- `->live()` pas `->reactive()` (v3 déprécié)
- `getHeaderActions()` pas `getActions()` dans les Pages
- Structure obligatoire : Resource + Pages/ + Schemas/ + Tables/
- Panel admin URL : `/admin`
- Provider : `app/Providers/Filament/AdminPanelProvider.php`

### Render Hooks
- Provider : `app/Providers/FilamentRenderHooksServiceProvider.php`
- Vues : `resources/views/filament/hooks/`
- Hook CSS : `STYLES_AFTER` (pas `HEAD_END`) pour que les styles s'appliquent après Filament
- **NE PAS** utiliser `->viteTheme()` (conflit Tailwind v4/v3)

---

## 🔐 SÉCURITÉ — ÉTAT ACTUEL

**Score** : ~8/10 (post-fixes critiques et orange)

### ✅ En place
- Webhook Stripe avec vérification signature `constructEvent()`
- Montants toujours depuis la DB (jamais du POST client)
- 13 Policies Eloquent enregistrées
- Routes API sous `auth:sanctum`
- CSP enforced (pas Report-Only)
- SESSION_ENCRYPT=true
- Rate limiting : inscriptions (10/5min), webhook (60/1min)
- Données médicales chiffrées (cast encrypted)
- 2FA obligatoire pour les admins
- Documents compliance sur disk privé

### 🔴 En cours / TODO
- CSP : `unsafe-inline` et `unsafe-eval` à supprimer (nonces requis)
- 2FA obligatoire pour artistes avec Stripe Connect actif
- Pentest externe avant lancement public
- Audit RGPD formel (données de santé = catégorie spéciale)

### Variables sensibles
- Ne JAMAIS logguer `$request->all()` → utiliser `$request->except(['password', ...])`
- Ne JAMAIS utiliser `env()` dans le code métier → toujours `config()`
- Ne JAMAIS hardcoder des clés → toujours depuis `.env` via `config()`

---

## 🌐 WEBHOOKS STRIPE

**Deux endpoints** (intentionnel) :
- `POST /stripe/webhook` → Cashier natif (table `subscriptions`)
- `POST /webhooks/stripe` → `StripeWebhookController` custom (logique métier)

**Événements gérés** : `checkout.session.completed`, `payment_intent.succeeded`,
`payment_intent.payment_failed`, `charge.refunded`,
`customer.subscription.created/updated/deleted`, `account.updated`

**En local** : `stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe`

---

## 📧 NOTIFICATIONS

**25 notifications** (email + database) couvrant tout le cycle booking.
Notifications admin : `AdminMessageReceived`, `UserMessageToAdmin`,
`BookingCancelledWithRefund`

---

## 🚨 PIÈGES CONNUS

1. **Cashier Billable** → sur `User`, pas sur Tattooer/Piercer/Studio
   ```php
   $studio->user->subscription('default') // ✅
   $studio->subscription('default')        // ❌
   ```

2. **Tailwind v4** → ne pas utiliser `tailwind.config.js` (v4 n'en utilise pas)
   → ne pas ajouter `@tailwind base/components/utilities` dans les CSS Filament

3. **Filament v4** → `->live()` pas `->reactive()`, `getHeaderActions()` pas `getActions()`

4. **Montants** → EUROS en base, CENTIMES pour Stripe (toujours convertir)

5. **Polymorphisme BookingRequest** → `$booking->bookable` retourne Tattooer OU Piercer
   → ne jamais hardcoder `App\Models\Tattooer` → utiliser `get_class($artist)`

6. **Conversation types** → `booking` (privée client↔artiste), `support` (annulations/réclamations admin), `admin_private` (canal admin↔user)

7. **eval() interdit** → vecteur XSS — utiliser whitelist de fonctions ou events Livewire

---

## 📋 COMMANDES UTILES

```bash
# Dev
npm run dev                                    # Vite watch
stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe  # Webhooks

# Artisan
php artisan inkpik:block-expired-trials        # Bloquer trials expirés (cron daily)
php artisan route:list | grep <pattern>        # Chercher une route
php artisan filament:check-page-access        # Vérifier accès Filament

# Cache (dev)
php artisan view:clear && php artisan config:clear && php artisan cache:clear

# Cache (prod uniquement)
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## 📊 DETTE TECHNIQUE CONNUE

| Item | Priorité | Effort |
|------|---------|--------|
| TattooerController : 3062 lignes | 🟡 Post-bêta | XL |
| StudioController : 1242 lignes | 🟡 Post-bêta | L |
| 97 validations inline → Form Requests | 🟡 Post-bêta | L |
| 3565 lignes JS inline dans les vues | 🟡 Post-bêta | L |
| CSP nonces (suppr. unsafe-inline) | 🔴 Avant lancement public | M |
| Pentest externe | 🔴 Avant lancement public | - |

---

## 🔄 MISE À JOUR DE CE FICHIER

**Mettre à jour CLAUDE.md quand :**
- Nouvelle table ou modèle ajouté
- Nouveau plan tarifaire
- Changement architectural (nouveau service, trait, middleware)
- Vulnérabilité corrigée ou découverte
- Nouvelle fonctionnalité majeure deployée

**Format de mise à jour** : ajouter la date en haut + modifier la section concernée.
````

---

## PHASE 3 — VÉRIFICATION

Après génération, vérifier que Claude Code lit bien le fichier :

```bash
# Le fichier doit être à la racine (pas dans un sous-dossier)
ls -la CLAUDE.md
wc -l CLAUDE.md

# Tester que Claude Code le détecte
# → Ouvrir une nouvelle session Claude Code et vérifier qu'il référence
#   automatiquement le contexte Ink&Pik sans qu'on le lui rappelle
```

---

## ⚠️ CONTRAINTES
- Adapter TOUTES les valeurs avec les vraies données collectées en Phase 1
- Ne jamais mettre de vraies clés API dans CLAUDE.md (utiliser `***` ou `[config]`)
- Le fichier doit être committé dans git (c'est son but — donner le contexte à Claude Code)
- Sections à adapter si elles ont changé depuis ce template :
  prix, colonnes chiffrées, score sécurité, dette technique
