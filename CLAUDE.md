# CLAUDE.md — Contexte Ink&Pik pour Claude Code
## Dernière mise à jour : 2026-03-20
## Mettre à jour ce fichier après chaque modification architecturale majeure

---

## 🤖 RÈGLES DE TRAVAIL — À APPLIQUER SYSTÉMATIQUEMENT

Ces 5 règles s'appliquent à **chaque tâche**, sans exception.

---

### RÈGLE 1 — PLANIFIER avant de coder
Avant d'écrire la moindre ligne de code :
1. Lire tous les fichiers concernés
2. Identifier les dépendances et impacts
3. Lister les étapes dans l'ordre
4. Identifier les risques et cas limites
5. Valider le plan **avant** de commencer

> ❌ Ne jamais commencer à coder sans avoir compris l'ensemble du contexte.

---

### RÈGLE 2 — DÉLÉGUER via sous-agents
Pour les tâches complexes (> 3 fichiers ou > 2 domaines) :
- Décomposer en sous-tâches indépendantes
- Traiter chaque sous-tâche de façon isolée et testable
- Valider chaque sous-tâche avant de passer à la suivante
- Ne jamais modifier plus de 5 fichiers en une seule passe

> ✅ Préférer 3 petites tâches validées à 1 grosse tâche risquée.

---

### RÈGLE 3 — S'AUTO-AMÉLIORER via logs
À chaque correction :
1. **Avant** : logger le comportement actuel (attendu vs observé)
2. **Pendant** : commenter pourquoi la solution choisie résout le problème
3. **Après** : vérifier dans les logs que le problème est résolu
4. **Documenter** : noter la cause racine + solution dans le rapport final

Format de log standardisé :
```php
Log::info('[NomFix] Avant: {problème}');
// correction
Log::info('[NomFix] Après: {comportement attendu}');
```

> 🎯 Objectif : ne jamais reproduire deux fois la même erreur.

---

### RÈGLE 4 — TOUT TESTER avant de valider
Pour chaque modification :

**Tests obligatoires :**
```bash
# 1. Syntaxe PHP
php artisan route:cache 2>&1 | head -5

# 2. Routes impactées
php artisan route:list | grep <pattern_modifié>

# 3. Test fonctionnel Artisan
php artisan tinker --execute="/* vérifier le comportement */"

# 4. Pas de régression évidente
php artisan about 2>&1 | grep -i "error\|warning"
```

**Si des tests Pest/PHPUnit existent :**
```bash
php artisan test --filter=<NomTest>
```

> ❌ Ne jamais déclarer une tâche terminée sans avoir testé.

---

### RÈGLE 5 — CORRIGER les bugs jusqu'à résolution complète
En cas d'échec d'un test :
1. **Lire** les logs d'erreur en entier (pas seulement la première ligne)
2. **Identifier** la cause racine (pas seulement le symptôme)
3. **Corriger** en ciblant la cause (pas un patch superficiel)
4. **Re-tester** avec exactement le même test qu'avant
5. **S'améliorer** : documenter la cause + solution pour éviter la récurrence

**Cycle obligatoire :**
```
Test → Échec → Log → Cause racine → Fix → Re-test → Succès → Documenter
```

> 🔄 Ne jamais laisser un test en échec. Si bloqué après 3 tentatives :
> expliquer le problème en détail plutôt que de contourner.

---

### RÉCAPITULATIF RAPIDE
```
1. PLANIFIER  → Lire, comprendre, lister les étapes
2. DÉLÉGUER   → Sous-tâches isolées, max 5 fichiers par passe
3. AMÉLIORER  → Logs avant/après, documenter cause + solution
4. TESTER     → route:cache + tinker + tests unitaires
5. CORRIGER   → Cause racine, re-test, documenter
```

---

## LE PROJET

**Ink&Pik** = SaaS marketplace + gestion pour tatoueurs, pierceurs et studios en France.
Plateforme de mise en relation et de gestion complète : réservations, paiements,
fiches clients, traçabilité, compliance SIRET, aftercare.

**URL locale** : http://tattoolib-saas.test
**Environnement dev** : Laragon (Windows 11), PHP 8.3, MySQL
**Shell** : bash (git bash via Laragon) — syntaxe Unix, pas Windows
**Branche principale** : `main` / branche dev : `frontend`

---

## STACK TECHNIQUE EXACTE

| Composant | Version | Notes |
|-----------|---------|-------|
| Laravel | 12.46.0 | Framework principal |
| PHP | 8.3.16 | |
| Filament | 4.5.1 | Panel admin — structure v4 stricte |
| Livewire | 3.7.6 | Composants réactifs |
| TailwindCSS | ^4.0.7 | **v4** (pas v3) via @tailwindcss/vite ^4.1.11 |
| Alpine.js | 3.x | JS léger inline |
| Vite | ^7.0.4 | Bundler |
| Laravel Cashier | ^16.2 | Abonnements Stripe |
| Stripe PHP SDK | ^17.6 | Paiements |
| Spatie Permission | ^6.24 | Rôles & permissions |
| Spatie MediaLibrary | ^11.17 | Gestion médias |
| DomPDF | ^3.1 | Export PDF |
| Firebase (kreait) | ^6.2 | Notifications push FCM |
| Livewire Flux | v2.10.2 | UI components auth/settings (19 vues) |
| Pest | v4.3.1 | Tests — browser testing via Playwright |

---

## PRICING & PLANS

| Plan | Prix | Commission | Stripe Price ID |
|------|------|-----------|-----------------|
| STARTER | 9,99€/mois | 7% via application_fee | `STRIPE_PRICE_ID_STARTER` |
| PRO | 29,99€/mois | 0% | `STRIPE_PRICE_ID_PRO` |
| STUDIO | 59,99€/mois | configurable | `STRIPE_PRICE_ID_STUDIO` |
| STUDIO_EXTRA | 24,99€/artiste supp. | configurable | `STRIPE_PRICE_ID_STUDIO_EXTRA` |
| Trial | 14 jours | 0% | sans CB |

**Bêta-testeurs** : coupon `BETA-LAUNCH-30` → -30% à vie + 1 mois offert

---

## ARCHITECTURE CLÉS

### Modèles principaux (39 modèles)
- **User** → Billable Cashier (abonnements sur User, pas sur Tattooer/Piercer)
- **Tattooer** → artiste indépendant ou studio, `HasSubscription`, `HasStripeConnect`, `IsArtisan`
- **Piercer** → même architecture que Tattooer (polymorphisme via trait `IsArtisan`)
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

### Controllers Tattooer (app/Http/Controllers/Tattooer/)
L'ancien `TattooerController` (~3000 lignes) a été découpé en 14 fichiers :
- `ArtisanBaseController` — base avec `artisan()`, `getDashboardCounts()`, `artisanType()`, `routePrefix()`
- `TattooerDashboardController` — `dashboard()`, `profile()`, `upgrade()`, `pricing()`
- `TattooerBookingController` — requests, accept/reject/cancel/complete/no-show
- `TattooerCalendarController` — calendar CRUD + events
- `TattooerMessageController` — messages + send
- `TattooerClientController` — clients CRUD + notes + clientRequests
- `TattooerConsentController` — consentements (upload, digital, delete)
- `TattooerTraceabilityController` — traçabilité bookings + clients
- `TattooerMediaController` — avatar, bannière, photos clients
- `TattooerPortfolioController` — portfolio + before/after
- `TattooerSettingsController` — settings + GDPR export + schedule + password
- `TattooerPaymentController` — payments + Stripe Connect
- `TattooerAppointmentController` — complete + no-show RDV
- `TattooerComplianceController` — compliance documents

Tous étendent `ArtisanBaseController` et fonctionnent pour tattooers ET pierceurs (polymorphisme).

### Marketplace (implémenté)
- `MarketplaceSearchService` — recherche tattooer + piercer + studio
- `ArtistSortHelper` — tri unifié : PRO > Studio > STARTER > Trial, rotation hebdomadaire
- Composant Livewire `MarketplaceSearch` — filtres (styles, types piercing, prix, ville, PRO only, certifié)
- Badges PRO/Studio/Conforme visibles sur les cards artistes
- API : `/api/marketplace/search`, `/api/marketplace/featured`

### Traits partagés
- `HasSubscription` → `isPro()`, `isStarter()`, `isOnTrial()`, `canAccessProFeature()`
- `HasStripeConnect` → `hasStripeConnect()`, `isStripeConnectActive()`, `canSetupStripeConnect()`
- `HasAccountDeletion` → `destroyAccount()` partagé sur 4 controllers
- `IsArtisan` → `isPiercer()`, `isTattooer()`, `routePrefix()`, `artisanType()`

### Pattern polymorphique artisan
```php
// User helpers
auth()->user()->artisan()      // retourne Tattooer ou Piercer selon le rôle
auth()->user()->artisanType()  // 'tattooer' ou 'pierceur'
auth()->user()->isPiercer()

// Dans les nouveaux controllers Tattooer/* (ex: TattooerSettingsController)
$this->artisan()               // jamais auth()->user()->tattooer

// Routes Blade
route($tattooer->routePrefix() . '.settings')  // jamais route('tattooer.settings')

// BookingRequest polymorphique
get_class($artisan)            // jamais hardcoder App\Models\Tattooer
$booking->bookable             // retourne Tattooer OU Piercer
```

### Rôles Spatie
`tattooer`, `pierceur` (minuscule), `client`, `admin`, `studio_owner`

---

## STRIPE CONNECT — FLUX DE PAIEMENT

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

## BASE DE DONNÉES

**34 migrations exécutées** — tables principales :
`users`, `tattooers`, `piercers`, `studios`, `studio_artists`,
`booking_requests` (70+ colonnes), `booking_transactions`,
`conversations`, `messages`, `subscriptions` (Cashier),
`notifications`, `client_care_sheets`, `client_consent_forms`, `traceability_records`

### Colonnes sensibles chiffrées (cast `encrypted`)
`client_consent_forms` : `parent_name`, `parent_id_number`, `medical_allergies_detail`,
`medical_skin_disease_detail`, `signature_data`, `parent_signature_data`

`consents` : `medical_conditions`, `allergies`, `medications` → cast `encrypted:array`

### Soft Deletes sur
Tattooer, Piercer, Studio, StudioArtist, BookingRequest, Appointment,
Client, Message, ComplianceRecord, Conversation

---

## STRUCTURE FILAMENT V4

```
app/Filament/Admin/             (112 fichiers PHP)
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

## SECURITE — ETAT ACTUEL

**Score** : ~8.5/10 (post-audit + fixes 2026-03-20)

### En place
- Webhook Stripe avec vérification signature `constructEvent()`
- Montants toujours depuis la DB (jamais du POST client)
- 13 Policies Eloquent enregistrées (toutes les 13 policies mappées dans AuthServiceProvider)
- Routes API sous `auth:sanctum`
- CSP enforced (pas Report-Only)
- CSP nonces sur script-src en production (sans unsafe-inline/eval) — `csp_nonce()` helper disponible
- `SESSION_ENCRYPT=true`, `SESSION_SECURE_COOKIE` configuré via env (défaut false, prod doit forcer true)
- Rate limiting : inscriptions (10/5min), webhook (60/1min), reset password (5/10min)
- Données médicales chiffrées (cast encrypted)
- 2FA obligatoire pour les admins (vérifié dans SecurityHeaders middleware)
- 2FA obligatoire pour artistes avec Stripe Connect actif (`artist.2fa` middleware)
- Documents compliance sur disk `local` (privé), servis via route authentifiée
- SRI (sha384) sur tous les CDN : FullCalendar 6.1.11 + img-comparison-slider
- filament/tables v4.9.1 (CVE-2026-33080 corrigé)

### RGPD — Conformité Art. 9
- `DataProcessingRecord` : registre des traitements (5 entrées seedées)
- `GdprExportService` : export JSON portabilité (Art. 20) — routes protégées `throttle:3,60`
- `gdpr:purge-inactive` : purge mensuelle (comptes inactifs +3 ans, tokens FCM, sessions)
- Consentement RGPD tracé à l'inscription : `cgu_version_accepted`, `privacy_version_accepted`, `consent_ip`
- Filament admin : Registre des traitements dans groupe "RGPD & Conformité"
- Bouton export dans les settings tatoueur, pierceur et client

### En cours / TODO
- Pentest externe avant lancement public
- Audit RGPD formel avec DPO désigné (données de santé = catégorie spéciale Art. 9)
- Ajouter `APP_CGU_VERSION` et `APP_PRIVACY_VERSION` dans `.env` pour versionner les CGU
- Logo optimisé 512x512 (139 KB) — original 1024x1024 conservé dans `logo-original-1024.png`

### Variables sensibles
- Ne JAMAIS logguer `$request->all()` → utiliser `$request->except(['password', ...])`
- Ne JAMAIS utiliser `env()` dans le code métier → toujours `config()`
- Ne JAMAIS hardcoder des clés → toujours depuis `.env` via `config()`

---

## WEBHOOKS STRIPE

**Deux endpoints** (intentionnel) :
- `POST /stripe/webhook` → Cashier natif (table `subscriptions`)
- `POST /webhooks/stripe` → `StripeWebhookController` custom (logique métier)

**Événements gérés** : `checkout.session.completed`, `payment_intent.succeeded`,
`payment_intent.payment_failed`, `charge.refunded`,
`customer.subscription.created/updated/deleted`, `account.updated`

**En local** : `stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe`

---

## NOTIFICATIONS

**25+ notifications** (email + database) couvrant tout le cycle booking.
Notifications admin : `AdminMessageReceived`, `UserMessageToAdmin`,
`BookingCancelledWithRefund`

---

## TAILLE DU PROJET

| Composant | Nombre |
|-----------|--------|
| Routes | ~502 lignes (route:list) |
| Modèles (`app/Models`) | 40 fichiers |
| Controllers | 60 fichiers (dont 14 dans `Tattooer/`) |
| Livewire components | 55 fichiers |
| Filament PHP | 118 fichiers |
| Vues Blade | 238 fichiers |
| Migrations exécutées | 36 |
| Commits (branche frontend) | 229 |
| Prompts Claude appliqués | 22 |

---

## PIEGES CONNUS

1. **Cashier Billable** → sur `User`, pas sur Tattooer/Piercer/Studio
   ```php
   $studio->user->subscription('default') // OK
   $studio->subscription('default')        // ERREUR
   ```

2. **Tailwind v4** → ne pas utiliser `tailwind.config.js` (v4 n'en utilise pas)
   → ne pas ajouter `@tailwind base/components/utilities` dans les CSS Filament

3. **Filament v4** → `->live()` pas `->reactive()`, `getHeaderActions()` pas `getActions()`

4. **Montants** → EUROS en base, CENTIMES pour Stripe (toujours convertir)

5. **Polymorphisme BookingRequest** → `$booking->bookable` retourne Tattooer OU Piercer
   → ne jamais hardcoder `App\Models\Tattooer` → utiliser `get_class($artisan)`

6. **Conversation types** → `booking` (privée client↔artiste), `support` (annulations/réclamations admin), `admin_private` (canal admin↔user)

7. **eval() interdit** → vecteur XSS — utiliser whitelist de fonctions ou events Livewire

8. **Artisan in TattooerController** → toujours `$this->artisan()` jamais `auth()->user()->tattooer`

9. **Routes artisan** → toujours `route($tattooer->routePrefix() . '.xxx')` jamais `route('tattooer.xxx')`

10. **Flux UI** → installé (v2.10.2), utilisé UNIQUEMENT dans les vues auth/settings (19 fichiers).
    Les vues métier (tattooer, pierceur, client, marketplace) utilisent Tailwind + Alpine natif.
    → ne PAS mélanger Flux dans les vues métier

11. **Pest v4 Browser Testing** → basé sur Playwright (pas Dusk). Utiliser `visit()` + `->screenshot()`.
    → `composer require pestphp/pest-plugin-browser --dev` + `npx playwright install`

12. **VitePWA** → gère le Service Worker et le manifest. NE PAS enregistrer manuellement de SW dans app.js.
    → `resources/js/sw.js` supprimé (vestige) — le SW est généré automatiquement par vite-plugin-pwa

13. **CDN FullCalendar** → utilisé dans 3 vues (calendar.blade.php, planning.blade.php, tattooer-calendar.blade.php).
    Version unifiée : 6.1.11. SRI sha384 en place.
    → À migrer vers npm post-bêta pour éliminer la dépendance CDN

---

## PROMPTS CLAUDE APPLIQUÉS

| # | Prompt | Contenu |
|---|--------|---------|
| 1 | prompt-CONTROLLERS-1-TATTOOER-REFACTOR | Découpage TattooerController → 14 fichiers |
| 2 | prompt-CONTROLLERS-2-STUDIO-CLIENT-FORMREQUESTS | Controllers Studio/Client + FormRequests |
| 3 | prompt-SECURITE-AVANCEE-1-CSP-2FA-HSTS | CSP nonces, HSTS, 2FA admin |
| 4 | prompt-SECURITE-AVANCEE-2-RGPD | RGPD Art.9, chiffrement médical, registre |
| 5 | prompt-FIX-CRITIQUE-securite | Fixes sécurité critiques |
| 6 | prompt-FIX-ORANGE-avant-beta | Fixes orange pré-bêta |
| 7 | prompt-FIX-JAUNE-dette-technique | Dette technique |
| 8 | prompt-FIX-2FA-settings-flux-error | Fix 2FA settings |
| 9 | prompt-FIX-URGENT-admin-2fa-flux | Fix admin 2FA + Flux UI |
| 10 | prompt-K-marketplace-tri-pro | Tri PRO marketplace + ArtistSortHelper |
| 11 | prompt-MARKETPLACE-recherche-filtres-studio | Marketplace search + filtres + studios |
| 12 | prompt-UPDATE-CLAUDE-MD-REGLES | Règles de travail CLAUDE.md |
| 13 | prompt-GENERER-CLAUDE-MD | Génération CLAUDE.md initial |
| 14 | prompt-AUDIT-1-SECURITE | Audit sécurité → AUDIT_SECURITE.md (score 7.5/10) |
| 15 | prompt-AUDIT-2-CONTROLLERS | Audit controllers → AUDIT_CONTROLLERS.md (score 7.5/10) |
| 16 | prompt-AUDIT-3-FRONTEND | Audit frontend → AUDIT_FRONTEND.md (score 6.5/10) |
| 17 | prompt-AUDIT-4-ADMIN-GLOBAL | Audit admin + synthèse → AUDIT_ADMIN.md + AUDIT_GLOBAL.md (score 7.1/10) |
| 18 | prompt-FIXES-AUDIT-securite-perf | 12 fixes audit : filament v4.9.1, SRI, cache widgets, policies, N+1, logo |
| 19 | prompt-UPDATE-CLAUDE-MD | Mise à jour CLAUDE.md (ce prompt) |

---

## COMMANDES UTILES

```bash
# Dev
npm run dev                                    # Vite watch
stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe  # Webhooks

# Artisan
php artisan inkpik:block-expired-trials        # Bloquer trials expirés (cron daily)
php artisan route:list | grep <pattern>        # Chercher une route
php artisan filament:check-page-access         # Vérifier accès Filament

# Cache (dev)
php artisan view:clear && php artisan config:clear && php artisan cache:clear

# Cache (prod uniquement)
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## DETTE TECHNIQUE CONNUE

| Item | Priorité | Effort |
|------|---------|--------|
| SESSION_SECURE_COOKIE=true en prod | 🔴 Immédiat | 5 min |
| StripeWebhookController : 700L / 1 méthode | 🟡 Post-bêta | 1 jour |
| StudioArtistController : 591L | 🟡 Post-bêta | 4h |
| StudioController : ~1200 lignes | 🔵 Long terme | L |
| JS inline : ~2300L dans 19 vues | 🟡 Post-bêta | 2 jours |
| 0 tests Pest écrits | 🟡 Post-bêta | 2 jours |
| Pentest externe | 🔵 Avant lancement public | Externe |
| FullCalendar via CDN → migrer vers npm | 🔵 Post-bêta | M |

---

## MISE A JOUR DE CE FICHIER

**Mettre à jour CLAUDE.md quand :**
- Nouvelle table ou modèle ajouté
- Nouveau plan tarifaire
- Changement architectural (nouveau service, trait, middleware)
- Vulnérabilité corrigée ou découverte
- Nouvelle fonctionnalité majeure déployée

**Format de mise à jour** : modifier la date en haut + modifier la section concernée.

**Déclencher aussi une mise à jour CLAUDE.md après :**
- Résolution d'un bug complexe → documenter la cause + solution
- Découverte d'un nouveau piège → l'ajouter à la section "PIÈGES CONNUS"
- Changement de règle de sécurité → mettre à jour la section sécurité
