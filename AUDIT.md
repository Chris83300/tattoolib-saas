# AUDIT INK&PIK — 2026-03-13
Généré par Claude Code — Ne pas modifier manuellement

## Score global : 7/10
> Application SaaS Laravel 12 bien structurée avec une architecture polymorphique propre (Tattooer/Piercer), une gestion Stripe Connect en place, et un back-office Filament complet. Points forts : couverture fonctionnelle large, sécurité applicative sérieuse, 25 commandes CLI artisan. Points faibles : TattooerController monolithique (2991 lignes), dette technique visible (22 TODO/FIXME actifs), debug_mode activé en local (acceptable), CSP commentée en production (à risque), duplication des contrôleurs de dépôt, webhooks subscription non fonctionnels (logique déléguée à Cashier sans synchronisation réelle des modèles Tattooer/Piercer).

---

## 1. STACK TECHNIQUE

### Versions
- Laravel : 12.46.0
- PHP : 8.3.16
- Livewire : 3.7.6
- Filament : 4.5.1
- Cashier : ^16.2 (Laravel Cashier Stripe)
- TailwindCSS : ^4.0.7 (via @tailwindcss/vite ^4.1.11)
- Spatie Permission : 6.24.0
- Spatie MediaLibrary : ^11.17
- Stripe PHP SDK : ^17.6
- Firebase (kreait) : ^6.2
- DomPDF : ^3.1

### Routes
- Total : 470 routes
- Groupes : tattooer, pierceur, studio, client, admin (Filament), api, public, stripe/webhooks

### Migrations
- Exécutées : 29 (batchs 1 à 4)
- En attente : 0

### Cache
- Config : NOT CACHED (à optimiser avant production)
- Events : NOT CACHED
- Routes : NOT CACHED
- Views : CACHED (seul)

### Queue & Session
- Queue driver : database
- Session driver : database
- Broadcast driver : null

---

## 2. BASE DE DONNÉES

### Tables (56 tables au total)
`accounting_transactions`, `appointments`, `availabilities`, `booking_requests`, `booking_transactions`, `cache`, `cache_locks`, `calendar_events`, `client_care_sheets`, `client_consent_forms`, `clients`, `complaints`, `compliance_records`, `conversation_user`, `conversations`, `expense_items`, `expense_reports`, `failed_jobs`, `inventory_items`, `inventory_movements`, `invoices`, `job_batches`, `jobs`, `media`, `messages`, `migrations`, `model_has_permissions`, `model_has_roles`, `notifications`, `parental_consent_forms`, `password_reset_tokens`, `payments`, `permissions`, `personal_access_tokens`, `piercers`, `purchase_order_items`, `purchase_orders`, `refunds`, `reviews`, `role_has_permissions`, `roles`, `sessions`, `studio_accounting_entries`, `studio_artists`, `studio_subscriptions`, `studios`, `subscription_items`, `subscriptions`, `tattoo_histories`, `tattooer_subscriptions`, `tattooers`, `traceability_inks`, `traceability_needles`, `traceability_records`, `transactions`, `users`, `working_hours`

### Colonnes clés par table

**users** : id, name, pseudo, phone, birth_date, role_id, email, first_name, last_name, timezone, email_verified_at, is_beta_tester, beta_registered_at, cgu_accepted_at, privacy_accepted_at, password, role, status, banned_at, banned_reason, unbanned_at, unbanned_reason, suspended_at, suspended_reason, studio_id, is_studio_owner, is_studio_artist, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, remember_token, fcm_token, is_active, is_admin, last_login_at, created_at, updated_at, stripe_id, pm_name, pm_last_four, trial_ends_at

**tattooers** : id, user_id, studio_id, first_name, last_name, pseudo, siret, siret_verified, is_decision_maker, compliance_status, last_compliance_check_at, name, studio_name, slug, bio, working_hours, styles, custom_styles, years_of_experience, minimum_price, wait_time_weeks_min, wait_time_weeks_max, phone, address, city, postal_code, email, stripe_connect_account_id, stripe_connect_status, stripe_connect_status, stripe_connect_activated_at, stripe_connect_last_transaction_at, stripe_connect_deactivated_at, has_accepted_payment_terms, payment_terms_accepted_at, current_plan, is_subscribed, is_blocked, trial_ends_at, has_compliance_badge, upgraded_to_pro_at, stripe_onboarding_complete, instagram, facebook, tiktok, website, minimum_deposit, default_deposit_rate, default_client_payment_deadline_days, default_tattooer_design_deadline_days, default_design_versions_included, weekday_wait_days, weekend_wait_days, admin_verified_at, aftercare_sheet, aftercare_reminder_2h, aftercare_reminder_7d, aftercare_reminder_14d, created_at, updated_at, deleted_at

**piercers** : id, user_id, studio_id, first_name, last_name, pseudo, siret, name, slug, specialization, bio, styles, custom_styles, years_of_experience, minimum_price, wait_time_weeks_min, wait_time_weeks_max, working_hours, aftercare_sheet, aftercare_reminder_2h, aftercare_reminder_7d, aftercare_reminder_14d, pricing_grid, custom_pricing_note, piercing_types, default_appointment_duration, city, postal_code, address, phone, email, subscription_plan, is_subscribed, is_blocked, trial_ends_at, stripe_connect_id, has_compliance_badge, admin_verified_at, siret_verified, is_decision_maker, compliance_status, last_compliance_check_at, studio_name, stripe_connect_account_id, stripe_onboarding_complete, stripe_connect_status, stripe_connect_activated_at, stripe_connect_last_transaction_at, stripe_connect_deactivated_at, has_accepted_payment_terms, payment_terms_accepted_at, minimum_deposit, default_deposit_rate, default_client_payment_deadline_days, default_design_versions_included, weekday_wait_days, weekend_wait_days, current_plan, upgraded_to_pro_at, instagram, facebook, tiktok, website, created_at, updated_at, deleted_at

**studios** : id, name, slug, description, address, city, postal_code, country, phone, email, website, social_media_links, logo_url, cover_images, latitude, longitude, opening_hours, facilities, settings, siret, stripe_account_id, stripe_id, pm_type, pm_last_four, trial_ends_at, is_subscribed, stripe_onboarding_complete, max_artists, vat_number, stripe_customer_id, total_artists, is_active, is_blocked, is_verified, verified_at, payment_mode, artist_commission_rate, uses_accounting_module, payment_mode_changed_at, user_id, first_name, last_name, created_at, updated_at, deleted_at

**booking_requests** : 70+ colonnes — id, client_id, bookable_type, bookable_id, tattoo_size, body_zone, tattoo_style, description, tattooer_notes, estimated_price, estimated_budget, preferred_timeframe, preferred_days, date_notes, preferred_date, preferred_time_slot, preferred_time_notes, proposed_dates, client_selected_dates, date_selection_deadline, client_dates_selected_at, confirmed_date, confirmed_period, tattooer_acceptance_message, total_deposit_amount, deposit_amount, estimated_total_price, price_estimate_min, price_estimate_max, client_payment_deadline_days, deposit_deadline_hours, tattooer_design_deadline_days, client_payment_deadline, tattooer_design_deadline, design_sent_at, deposit_deadline, is_long_term_booking, design_preparation_starts_at, design_preparation_notified, included_design_versions, included_designs, modifications_per_design, design_versions_used, designs_sent_count, design_modifications_tracker, current_design_modifications_count, stripe_payment_intent_id, status, deposit_paid_at, expired_at, accepted_at, scheduled_start_time, scheduled_end_time, scheduled_duration_minutes, total_price, balance_amount, balance_paid_at, balance_payment_method, balance_stripe_session_id, refund_amount, refund_percent, refund_processed_at, tattooer_missed_deadline, client_missed_deadline, appointment_datetime, appointment_duration_minutes, overage_decision, surcharge_amount, surcharge_paid_at, overage_reason, cancelled_by, cancellation_reason, cancelled_at, created_at, updated_at, deleted_at

**studio_artists** : id, studio_id, user_id, artisan_type, role, invitation_token, invitation_email, invited_at, commission_rate, artist_name, slug, bio, specialties, stripe_connect_account_id, stripe_connect_status, stripe_connect_activated_at, stripe_connect_last_transaction_at, stripe_connect_deactivated_at, has_accepted_payment_terms, payment_terms_accepted_at, is_decision_maker, compliance_status, last_compliance_check_at, status, is_active, joined_at, left_at, working_schedule, total_appointments, total_revenue, credentials_managed_by_studio, siret_verified, stripe_onboarding_complete, notes, created_at, updated_at, deleted_at

**subscriptions** (Cashier) : id, user_id, type, stripe_id, stripe_status, stripe_price, plan, quantity, trial_ends_at, ends_at, created_at, updated_at

**transactions** : id, payment_id, stripe_payment_intent_id, stripe_charge_id, client_id, artist_id, artist_type, amount, commission_amount, net_amount, currency, status, payment_type, refund_status, refund_amount, processed_at, created_at, updated_at

**booking_transactions** : id, booking_request_id, user_id, type, amount, currency, status, payment_method, stripe_payment_intent_id, stripe_session_id, metadata, created_at, updated_at

### Données en base (au 2026-03-13)
- Users : 12
- Tattooers : 5 (5 visibles marketplace, 0 bloqués)
- Piercers : 1 (1 visible marketplace, 0 bloqués)
- Studios : 2
- Booking requests : 8
- Booking transactions : 6
- Subscriptions (Cashier) : 4 (3 active, 1 trialing)
- Tattooers abonnés (is_subscribed=true) : 2
- Tattooers en trial actif : 3
- Tattooers bloqués : 0
- Tattooers plan starter : 1
- Tattooers plan pro : 4
- Piercers abonnés : 1
- Studios abonnés : 2

### Relations clés
- `BookingRequest` polymorphique via `bookable_type`/`bookable_id` → Tattooer | Piercer
- `User` → `Tattooer` (hasOne), `User` → `Piercer` (hasOne), `User` → `Client` (hasOne)
- `Tattooer` et `Piercer` implémentent tous deux `ArtisanInterface` via le trait `IsArtisan`
- `Subscription` polymorphique via `subscribable_type`/`subscribable_id` (table custom) ET table Cashier `subscriptions` (sur User)
- `Studio` → `StudioArtist[]` → `User`
- `Conversation` liée à `BookingRequest` (1:1), `Message[]` avec soft-delete

---

## 3. ABONNEMENTS & PLANS

### État actuel en base
- 4 abonnements Cashier (3 active, 1 trialing)
- 2 tattooers avec is_subscribed=true
- 3 tattooers avec trial_ends_at futur
- 0 tattooers bloqués
- 1 piercer abonné
- 2 studios abonnés

### Plans disponibles
Définis dans `app/Models/Subscription.php` :
- **STARTER** (alias FREE) : 9.99€/mois — `PLAN_STARTER = 'starter'` / `PLAN_FREE = 'starter'` (alias — **attention : constante PLAN_FREE pointe sur 'starter', pas 'free'**)
- **PRO** : 29.99€/mois — `PLAN_PRO = 'pro'`
- **STUDIO** : 59.99€/mois + 24.99€/artiste — `PLAN_STUDIO = 'studio'`

### Commission par plan
- Subscription::COMMISSION_FREE = 7.00% (sur STARTER aussi)
- Subscription::COMMISSION_STARTER = 7.00%
- Subscription::COMMISSION_PRO = 0.00%
- Studio : taux configuré via `studios.artist_commission_rate` (colonne, per-studio)

### Matrice features
| Feature | TRIAL | STARTER | PRO | STUDIO | Middleware / Fichier |
|---------|-------|---------|-----|--------|------------|
| Profil marketplace | ✅ | ✅ | ✅ | ✅ | scopeMarketplaceVisible() |
| Recevoir demandes | ✅ | ✅ | ✅ | ✅ | — |
| Calendrier basique | ✅ | ✅ | ✅ | ✅ | — |
| Chat limité | ✅ | ✅ | ✅ | ✅ | — |
| Badge compliance | ✅ | ✅ | ✅ | ✅ | — |
| Paiement acompte | ✅ | ✅ | ✅ | ✅ | — |
| Portfolio (20 images) | ✅ | ✅ | — | — | HasSubscription::getPortfolioLimit() |
| Portfolio illimité (100) | — | — | ✅ | ✅ | HasSubscription::getPortfolioLimit() |
| Historique client | — | — | ✅ | ✅ | EnsureProPlan |
| Archive conversations | — | — | ✅ | ✅ | EnsureProPlan |
| Traçabilité | — | — | ✅ | ✅ | EnsureProPlan |
| Inventaire | — | — | ✅ | ✅ | EnsureProPlan |
| Stats avancées | — | — | ✅ | ✅ | EnsureProPlan |
| Commission 0% | — | — | ✅ | dépend studio | HasSubscription::calculateCommission() |
| Designs illimités | — | — | ✅ | ✅ | HasSubscription::canSendMoreDesigns() |
| Conservation conv. 365j | — | — | ✅ | ✅ | HasSubscription::getConversationRetentionDays() |

### Middlewares de plan
- `artisan.can.operate` → `EnsureArtisanCanOperate` (`app/Http/Middleware/EnsureArtisanCanOperate.php`) : bloque toute navigation si trial expiré ou is_blocked=true, sauf routes whitelistées (settings, subscription, compliance, messages avec acompte payé, read-only requests)
- `pro` → `EnsureProPlan` (`app/Http/Middleware/EnsureProPlan.php`) : bloque si `artisan->isFree()`, redirige vers plans
- `EnsureStudioCanOperate` (`app/Http/Middleware/EnsureStudioCanOperate.php`) : équivalent pour studios

### Service Trial
`app/Services/TrialService.php` : trial de 14 jours (SubscriptionPlan::STARTER->trialDays()), `isOnTrial()`, `isTrialExpired()`, `hasActiveAccess()`, `blockExpiredTrial()`

---

## 4. STRIPE & PAIEMENTS

### Configuration
- `config('cashier.key')` : SET
- `config('cashier.secret')` : SET
- `config('cashier.webhook.secret')` : SET
- `config('cashier.model')` : null (non défini → défaut Cashier = App\Models\User)
- `config('cashier.currency')` : eur
- `config('services.stripe.key')` : SET
- `config('services.stripe.secret')` : SET
- `config('services.stripe.webhook_secret')` : SET

### Points de création PaymentIntent/Session

| Fichier | Méthode | on_behalf_of | transfer_data | application_fee | Fallback |
|---------|---------|-------------|---------------|----------------|---------|
| `app/Http/Controllers/DepositController.php:131` | `process()` | ✅ Si compte Connect | ✅ Si compte Connect | ✅ 7% STARTER / 0% PRO | Passe sans Connect (sans frais) |
| `app/Http/Controllers/BalancePaymentController.php:50` | `checkout()` | ✅ | ✅ | ✅ | abort(500) si pas de compte |
| `app/Http/Controllers/DepositPaymentController.php` | (doublon non utilisé) | Non vérifié | Non vérifié | Non vérifié | — |

**Bug critique — DepositController.php lignes 127-131** : utilise `env('STRIPE_SECRET')` directement au lieu de `config('services.stripe.secret')`. En production avec cache config activé, `env()` retourne null, ce qui casse tous les paiements d'acompte.

### Flux de paiement
1. Client accepte la demande de booking → statut `awaiting_deposit`
2. Client va sur `/deposit/{bookingRequest}/payment` (DepositController@payment)
3. POST `/deposit/{bookingRequest}/process` → crée une Session Stripe Checkout
4. Stripe redirige sur success URL → DepositController@success vérifie la session ET/OU webhook traite l'événement
5. Paiement solde : après RDV terminé (`COMPLETED`), client va sur balance-payment, BalancePaymentController@checkout crée une Session Checkout
6. Webhook `checkout.session.completed` → StripeWebhookController@handleCheckoutCompleted dispatche vers handleDepositPayment ou handleBalancePayment
7. Paiement hors-plateforme : tattooer confirme via BalancePaymentController@confirmOffline

---

## 5. WEBHOOKS

| Événement Stripe | Handler | Action |
|-----------------|---------|--------|
| `checkout.session.completed` | `handleCheckoutCompleted()` | Dispatch selon `payment_type` (deposit/balance) |
| `payment_intent.succeeded` | `handlePaymentSucceeded()` | Log seulement (logique dans checkout.session) |
| `payment_intent.payment_failed` | `handlePaymentFailed()` | Marque AccountingTransaction failed |
| `charge.refunded` | `handleChargeRefunded()` | Crée AccountingTransaction refund + met à jour BookingRequest |
| `invoice.payment_succeeded` | `handleInvoicePaymentSucceeded()` | Log seulement |
| `invoice.payment_failed` | `handleInvoicePaymentFailed()` | Log seulement |
| `customer.subscription.created` | `handleSubscriptionCreated()` | Log seulement — délégué Cashier (non fonctionnel) |
| `customer.subscription.updated` | `handleSubscriptionUpdated()` | Log seulement — délégué Cashier (non fonctionnel) |
| `customer.subscription.deleted` | `handleSubscriptionDeleted()` | Log seulement — délégué Cashier (non fonctionnel) |
| `account.updated` | `handleAccountUpdated()` | Synchronise stripe_connect_status sur Tattooer/Piercer/Studio |

**Routes webhook** :
- `POST /stripe/webhook` → Cashier WebhookController (Laravel Cashier natif)
- `POST /webhooks/stripe` → StripeWebhookController custom (web.php:492)
- `POST /api/stripe/webhook` → StripeWebhookController custom (api.php:102)

**CSRF** : correctement exclu via `bootstrap/app.php` lignes 25-29 pour `stripe/webhook`, `stripe/*`, `webhooks/stripe`.

**Problème** : 3 endpoints webhook distincts, risque de double traitement. Les abonnements subscription (created/updated/deleted) sont "délégués à Cashier" mais les handlers custom ne synchronisent pas `tattooers.is_subscribed` / `tattooers.current_plan` → désynchronisation possible entre Cashier et les modèles métier.

---

## 6. SÉCURITÉ

### Authentification
- Laravel Fortify (`laravel/fortify ^1.30`) avec registration, resetPasswords, emailVerification, 2FA
- Laravel Sanctum pour les tokens API
- Sessions en base de données
- `TwoFactorAuthenticatable` sur le modèle User

### 2FA
- Activé dans `config/fortify.php` via `Features::twoFactorAuthentication()`
- Interface Livewire : `app/Livewire/Settings/TwoFactor.php` et `app/Livewire/Settings/TwoFactor/RecoveryCodes.php`
- Colonnes en base : `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`
- **État** : implémenté mais facultatif (non forcé pour les artisans)

### Protections CSRF
- Webhook Stripe correctement exclu dans `bootstrap/app.php:25-29`
- `ExcludeWebhookFromCsrf.php` middleware existe également (doublon)

### Headers de sécurité
`app/Http/Middleware/SecurityHeaders.php` appliqué globalement :
- X-Content-Type-Options: nosniff
- X-Frame-Options: SAMEORIGIN
- X-XSS-Protection: 1; mode=block
- Referrer-Policy: strict-origin-when-cross-origin
- Permissions-Policy configurée
- HSTS activé en production uniquement
- **CSP commentée** (ligne 24-26) : `$response->headers->set('Content-Security-Policy', $csp)` est commenté → aucun Content-Security-Policy envoyé

### IP Blocking
`app/Http/Middleware/BlockSuspiciousIps.php` appliqué globalement (prepend web)

### Autorisation
- 13 Policies Spatie : AccountingPolicy, AppointmentPolicy, AvailabilityPolicy, BookingRequestPolicy, ClientCareSheetPolicy, ClientPolicy, ConversationPolicy, InventoryPolicy, MessagePolicy, PaymentPolicy, PierceurPolicy, TattooerPolicy, TraceabilityPolicy
- `Gate::authorize()` utilisé systématiquement dans les controllers API (app/Http/Controllers/Api/)
- **Manque** : les controllers web (TattooerController, ClientController) utilisent des vérifications manuelles `abort_if`/`abort_unless` sans policies systématiques

### Données personnelles & RGPD
- SoftDeletes sur : Tattooer, Piercer, Studio, StudioArtist, BookingRequest, Appointment, Client, Message, ComplianceRecord, StudioAccountingEntry, TattooerSubscription, Conversation
- `DeleteUserForm` Livewire pour suppression compte
- Colonnes `cgu_accepted_at`, `privacy_accepted_at` sur User
- Colonne `clients.is_blacklisted` avec raison
- Pas de mécanisme d'export RGPD (droit à la portabilité) visible

---

## 7. PERFORMANCE & QUALITÉ

### Taille des controllers (lignes)
| Fichier | Lignes |
|---------|--------|
| `TattooerController.php` | 2991 |
| `StudioController.php` | 1198 |
| `ClientController.php` | 821 |
| `StripeWebhookController.php` | 561 |
| `RegisterController.php` | 398 |
| `SubscriptionController.php` | 348 |
| `DepositController.php` | 341 |
| `StripeConnectController.php` | 295 |
| `MarketplaceController.php` | 270 |
| `DepositPaymentController.php` | 265 |
| `Api/TraceabilityController.php` | 474 |
| `Api/AppointmentController.php` | 420 |
| `Api/BookingRequestController.php` | 353 |
| `Api/TattooerController.php` | 308 |
| `Api/InventoryController.php` | 301 |

### Problèmes N+1 potentiels
- `TattooerController::clients()` (ligne ~1065) : chargement via `->with(['client.user'])` — correct
- `TattooerController::requestShow()` (ligne ~164) : `->with(['client.user', 'conversation.messages'])` — correct
- Certaines boucles dans les Livewire (BookingRequests, Dashboard) peuvent déclencher des requêtes non eager-loaded sur les relations polymorphiques `bookable` (Tattooer/Piercer)

### Code mort / Debug
- **Aucun `dd()` ou `var_dump()` trouvé** dans le code de production
- Logs de debug (Log::info avec "DEBUG") présents dans :
  - `app/Http/Controllers/ClientController.php:125-126` : log DEBUG avec texte littéral
  - `app/Http/Controllers/ClientController.php:666-667` : log DEBUG pour createReview
  - `app/Http/Controllers/DepositController.php` : nombreux `Log::info()` de diagnostic (lignes 20-25, 59-62, etc.) — acceptable mais verbeux

### TODO/FIXME actifs (22 trouvés)
| Fichier | Ligne | Description |
|---------|-------|-------------|
| `app/Filament/Admin/Pages/RefundsPage.php` | 250 | Export non implémenté |
| `app/Filament/Admin/Resources/Pierceurs/Schemas/PierceurForm.php` | 41 | API SIRET non appelée |
| `app/Filament/Admin/Resources/Tattooers/Schemas/TattooerForm.php` | 57 | API SIRET non appelée |
| `app/Http/Controllers/Api/AccountingController.php` | 286 | Export CSV/Excel non implémenté |
| `app/Http/Controllers/BalancePaymentController.php` | 94 | Klarna non implémenté |
| `app/Http/Controllers/TattooerController.php` | 2257 | Notification système non implémentée |
| `app/Http/Controllers/TattooerController.php` | 2325 | Notification client non implémentée |
| `app/Livewire/Tattooer/AppointmentDetailModal.php` | 128 | Calcul remboursement non implémenté |
| `app/Models/Appointment.php` | 349, 368, 385, 407, 467, 517, 540, 544 | 8 Events non dispatchés |
| `app/Models/BookingRequest.php` | 805, 815, 831, 849, 864, 882 | 6 Events non dispatchés |
| `app/Services/BookingRequestService.php` | 581 | Remboursement Stripe non implémenté |
| `app/Services/MarketplaceSearchService.php` | 263 | Filtrage par styles non implémenté |
| `app/Services/StudioArtistService.php` | 170 | Stripe subscription update non implémenté |

---

## 8. MARKETPLACE

### Visibilité (au 2026-03-13)
- Tattooers visibles : 5 / 5 (0 bloqués)
- Piercers visibles : 1 / 1 (0 bloqués)

### Scope marketplaceVisible
Défini dans `app/Models/Tattooer.php:426` et `app/Models/Piercer.php:522` :
- `is_blocked = false` ET
- (abonné `is_subscribed = true` sans studio_id) OU
- `trial_ends_at > now()` OU
- (trial_ends_at null ET created_at > now()-14j) OU
- Studio avec abonnement actif ou trial valide

Durée trial configurable via `config('inkpik.trial_days', 14)`.

### Tri et filtres
- `MarketplaceSearchService` (`app/Services/MarketplaceSearchService.php`) gère la recherche et le filtrage
- Filtrage par styles : **TODO non implémenté** (ligne 263)
- `CacheService` (`app/Services/CacheService.php`) gère le cache des listings

---

## 9. FONCTIONNALITÉS MÉTIER

| Fonctionnalité | État | Notes |
|---------------|------|-------|
| Booking flow complet | ✅ | Demande → Acceptation → Dates → Acompte → Design → RDV → Solde → Complet |
| Chat client↔artiste | ✅ | Conversations avec accès limité avant acompte, archivage 30j/365j selon plan |
| Paiement acompte | ⚠️ | Fonctionnel mais bug env() dans DepositController — cassera avec config:cache |
| Paiement solde | ✅ | BalancePaymentController + webhook |
| Paiement hors plateforme | ✅ | confirmOffline() avec méthode (cash/card/transfer/other) |
| Calendrier artiste | ✅ | TattooerController + Livewire/Tattooer/Calendar.php + Availability |
| Fiches clients | ✅ | ClientController, consentements, traçabilité, photos |
| Analytics | ⚠️ | Structure présente (Livewire/Tattooer/Analytics.php), fonctionnalité PRO uniquement |
| Portfolio | ✅ | Upload, avant/après, limite 20/100 selon plan |
| Notifications | ✅ | 25 notifications (email + DB) couvrant tout le cycle booking |
| Stripe Connect artiste | ✅ | Onboarding Express, activation/désactivation, webhook account.updated |
| Stripe Connect studio | ✅ | StudioController::connectStripe(), StripeConnectController::studioOwnerReturn() |
| Dashboard studio | ✅ | Filament Studio panel + Livewire/Studio/Dashboard.php |
| Compliance/SIRET | ⚠️ | Structure présente, vérification SIRET via API non implémentée (TODO) |
| Aftercare | ✅ | aftercare_sheet, reminders 2h/7j/14j, Notification PostTattooCare |
| Pierceurs (polymorphisme) | ✅ | Architecture complète via IsArtisan trait, routes pierceur.* miroir tattooer.* |
| Traceability (encres/aiguilles) | ✅ | Modèles TraceabilityRecord, traceability_inks, traceability_needles, API controller |
| Inventaire | ✅ | inventory_items, inventory_movements, API controller |
| Avis clients | ✅ | Reviews polymorphiques, ClientController::createReview() |
| Réclamations | ✅ | Complaints, ClientController::createComplaint() + Filament admin |
| PDF Export | ✅ | PdfExportController, DomPDF |
| Remboursements | ❌ | BookingRequestService.php:581 — Stripe refund non implémenté (TODO) |
| Events Laravel | ❌ | 14 TODO de dispatch d'events dans Appointment et BookingRequest — système événementiel absent |
| Export SIRET/API entreprise | ❌ | TODO dans Filament forms |

---

## 10. FILAMENT ADMIN

### Panels
1. **Admin** (`/admin`) : panel complet pour les administrateurs
2. **Studio** (`/admin/studio`) : panel réduit pour les propriétaires de studio

### Resources Admin
- Users (CRUD)
- Tattooers (CRUD + vérification admin)
- Pierceurs (CRUD)
- Studios (CRUD + view)
- StudioArtists (CRUD + view)
- BookingRequests (CRUD)
- Appointments (Manage)
- Payments (CRUD + view)
- Reviews (CRUD)
- Complaints (CRUD)
- ComplianceRecords (CRUD)
- Subscriptions (Manage)
- Transactions (CRUD)
- Pages : RefundsPage

### Resources Studio
- BookingRequestResource (lecture)
- StudioArtistResource (CRUD)

### Widgets Admin (15+)
ArtistRevenueChartWidget, CommissionWidget, ComplaintsWidget, MonthlyRevenueChart, MonthlyRevenueChartWidget, PendingPierceurs, PendingStudios, PendingTattooers, QualityAlerts, RecentActivity, RecentActivityChartWidget, RevenueByArtistType, RevenueChart, RevenueOverviewWidget, RevenueStatsWidget, StatsOverview, TotalTransactionsWidgetFixed

### Widgets Studio
MonthlyRevenueChart, RevenueByArtistChart, StudioStatsOverview

### Accès sécurisé
- Panel admin protégé par Filament Auth (login dédié `/admin/login`)
- Middleware `admin` → `EnsureUserIsAdmin`
- Panel studio à `/admin/studio` — sécurisé par `filament.studio.auth.login` mais URL prévisible
- **Attention** : le panel Studio partage le préfixe `/admin/studio` avec le panel Admin

---

## 11. PROBLÈMES IDENTIFIÉS

| # | Composant | Description | Criticité | Impact |
|---|-----------|-------------|-----------|--------|
| 1 | `DepositController.php:131,231` | `env('STRIPE_SECRET')` au lieu de `config('services.stripe.secret')` — casse avec `php artisan config:cache` | **CRITIQUE** | Tous les paiements d'acompte échouent en production |
| 2 | `StripeWebhookController.php:523-560` | Les handlers `handleSubscriptionCreated/Updated/Deleted` ne font que logger — ils ne synchronisent pas `tattooers.is_subscribed`, `tattooers.current_plan` | **ÉLEVÉ** | Désynchronisation plan/abonnement si Cashier gère l'event mais pas les modèles métier |
| 3 | `app/Http/Controllers/StripeWebhookController.php:337` | Instanciation incorrecte `new Stripe(config('services.stripe.secret_key'))` — utilise `secret_key` au lieu de `secret`, et Stripe n'est pas un client instanciable directement | **ÉLEVÉ** | Récupération des reçus webhook toujours en exception silencieuse |
| 4 | `app/Models/Subscription.php:19` | `const PLAN_FREE = 'starter'` — l'alias FREE pointe vers 'starter', mais la constante `PLAN_STARTER = 'starter'` existe aussi. La confusion entre FREE et STARTER peut entraîner des comportements inattendus dans `getCurrentPlan()` | **MOYEN** | Logique de commission potentiellement incorrecte |
| 5 | `TattooerController.php` | 2991 lignes — monolithique, 50+ méthodes publiques dans un seul controller | **MOYEN** | Maintenabilité dégradée, tests difficiles |
| 6 | `SecurityHeaders.php:24-26` | CSP commentée — aucun header Content-Security-Policy envoyé | **MOYEN** | Exposition XSS en cas d'injection de script |
| 7 | `app/Http/Controllers/DepositPaymentController.php` | Doublon de DepositController (265 lignes) non référencé dans les routes web | **MOYEN** | Code mort confusant, risque de maintenance parallèle |
| 8 | `app/Services/BookingRequestService.php:581` | Remboursement Stripe non implémenté (TODO) | **MOYEN** | Remboursements impossibles via la plateforme |
| 9 | `app/Models/Appointment.php` | 8 events non dispatchés (AppointmentCompleted, ClientNoShow, etc.) | **MOYEN** | Notifications/side-effects dépendants des events non déclenchés |
| 10 | `app/Models/BookingRequest.php` | 6 events non dispatchés (BookingRequestAccepted, DepositPaid, etc.) | **MOYEN** | Même impact que ci-dessus |
| 11 | Webhooks | 3 endpoints webhook distincts (`/stripe/webhook` Cashier, `/webhooks/stripe` web, `/api/stripe/webhook` api) | **MOYEN** | Risque de double traitement, maintenance complexe |
| 12 | `config/cashier.php` | Fichier absent — la config Cashier est uniquement via env(). `cashier.model` est null | **MOYEN** | Si CASHIER_MODEL non défini en .env, Cashier utilise App\Models\User par défaut — fonctionnel mais non documenté |
| 13 | `BalancePaymentController.php:21` | `$bookingRequest->client_id === auth()->id()` compare l'ID de Client avec l'ID de User — si ce sont des tables séparées, la vérification est incorrecte | **ÉLEVÉ** | Potentielle faille d'autorisation sur le paiement du solde |
| 14 | `APP_DEBUG=true` | Mode debug activé (local uniquement, acceptable) | **BAS** | Normal en développement, ne pas oublier en production |
| 15 | Cache non warmé | Config/Routes/Events non cachés | **BAS** | Performance dégradée, à corriger avant déploiement |
| 16 | `app/Services/MarketplaceSearchService.php:263` | Filtrage par styles non implémenté | **BAS** | Expérience marketplace dégradée |
| 17 | `StripeService::createConnectOnboardingLink()` (ligne 102) | Utilise `route('studio.artist.stripe.refresh')` hardcodé — ne fonctionne pas pour les artistes indépendants | **MOYEN** | Lien de refresh cassé pour les tattooers/piercers indépendants |
| 18 | Deux tables de subscription | `subscriptions` (Cashier sur User) + table custom (morphMany sur Tattooer/Piercer) — deux sources de vérité | **MOYEN** | Risque désynchronisation, logique de plan répartie entre `is_subscribed`, `current_plan`, et les deux tables |
| 19 | `ClientController.php:125,667` | Logs de debug avec label "DEBUG" en production | **BAS** | Bruit dans les logs, pas de risque sécurité |
| 20 | Aucun test automatisé visible | PestPHP installé mais pas de tests métier spécifiques détectés | **MOYEN** | Régression difficile à détecter |

---

## 12. RECOMMANDATIONS PRIORITAIRES

### À corriger immédiatement (avant tout déploiement prod)
1. **DepositController.php:131,231** — Remplacer `env('STRIPE_SECRET')` par `config('services.stripe.secret')` (2 occurrences)
2. **BalancePaymentController.php:21,44,110** — Vérifier que la comparaison `client_id === auth()->id()` est correcte selon le modèle Client (si `clients.user_id` ≠ `clients.id`, la condition doit être `auth()->user()->client->id`)
3. **StripeWebhookController.php:337** — Corriger l'instanciation Stripe (`new \Stripe\StripeClient(config('services.stripe.secret'))` et appel via `$stripe->paymentIntents->retrieve()`)
4. **Avant `config:cache`** — S'assurer qu'aucun `env()` n'est appelé en dehors des fichiers config/

### À corriger avant le lancement
5. **Webhooks subscription** — Implémenter la synchronisation réelle dans `handleSubscriptionUpdated/Deleted` : mettre à jour `tattooers.is_subscribed`, `tattooers.current_plan`, `piercers.is_subscribed`, `piercers.current_plan`
6. **CSP** — Décommenter et activer le header Content-Security-Policy dans `SecurityHeaders.php`
7. **DepositPaymentController.php** — Supprimer ou intégrer (code mort)
8. **StripeService::createConnectOnboardingLink** — Corriger les URLs refresh/return hardcodées pour les artistes indépendants
9. **Remboursement Stripe** — Implémenter `BookingRequestService.php:581`
10. **Events** — Dispatcher les 14 events manquants (AppointmentCompleted, DepositPaid, etc.) pour déclencher les notifications associées
11. **Unicité webhook** — Choisir un seul endpoint webhook et supprimer les redondants
12. **2FA obligatoire** — Forcer l'activation 2FA pour les comptes admin et studio_owner

### À planifier post-lancement
13. **Refactoring TattooerController** — Découper en sous-controllers (BookingController, PortfolioController, ComplianceController, ClientsController) pour passer de 2991 à <500 lignes chacun
14. **Tests automatisés** — Couvrir les flux critiques (paiement acompte, subscription flow, webhook handlers)
15. **API SIRET** — Implémenter la vérification via `entreprise.data.gouv.fr` (TODO dans Filament forms)
16. **Export RGPD** — Implémenter le droit à la portabilité des données (export CSV/JSON pour les clients)
17. **Filtrage marketplace par styles** — Implémenter le scope manquant (`MarketplaceSearchService.php:263`)
18. **Klarna** — Évaluer et implémenter si pertinent (`BalancePaymentController.php:94`)
19. **Cache config/routes** — `php artisan config:cache && php artisan route:cache` avant chaque déploiement prod
20. **Clarification PLAN_FREE** — Décider si PLAN_FREE = 'starter' est intentionnel ou si un vrai plan 'free' (sans carte) doit exister

### Améliorations futures
21. **Export comptable** — AccountingController.php:286, RefundsPage.php:250 — exports CSV non implémentés
22. **Analytics avancées** — Le Livewire Analytics.php est en place mais la couche de données méritera un enrichissement
23. **Notifications push Firebase** — FCM token en base (users.fcm_token), kreait/laravel-firebase installé, à activer
24. **PWA** — vite-plugin-pwa installé dans package.json, non configuré
25. **Suppression compte** — Vérifier le cascade de suppression (soft-delete) pour s'assurer que toutes les données liées sont correctement anonymisées/supprimées

---

## 13. RÉSUMÉ EXÉCUTIF

**Ink&Pik est une application SaaS Laravel 12 bien architecturée**, couvrant l'intégralité du cycle de vie d'une réservation de tatouage/piercing : de la demande initiale au paiement du solde, avec gestion du design, traçabilité, compliance SIRET, aftercare, analytics et un back-office Filament complet. L'architecture polymorphique Tattooer/Piercer (trait `IsArtisan`) est propre et extensible. La sécurité de base est sérieuse (headers HTTP, CSRF, Policies Spatie, 2FA disponible, SoftDeletes sur toutes les entités sensibles).

**L'application n'est PAS prête pour la production en l'état** en raison de 2 bugs critiques bloquants :
1. `env('STRIPE_SECRET')` dans DepositController cassera tous les paiements d'acompte dès que `config:cache` sera activé
2. La comparaison `client_id === auth()->id()` dans BalancePaymentController présente un risque d'autorisation incorrecte

**La dette technique principale** est concentrée dans TattooerController (2991 lignes, 50 méthodes), 14 events non dispatchés, 22 TODO actifs, 3 endpoints webhook redondants, et une désynchronisation latente entre les deux tables d'abonnement (Cashier + custom).

**Après correction des 4 points critiques** (env() Stripe, autorisation balance, fix webhook Stripe instanciation, synchronisation webhooks subscription), l'application peut raisonnablement passer en bêta privée. Un sprint de 2 semaines pour adresser les points "avant lancement" permettrait un lancement public serein.

**Score justifié 7/10** : stack moderne, architecture solide, couverture fonctionnelle large et système de paiement Stripe Connect bien pensé. Points perdus sur le manque de tests, les bugs critiques Stripe, la dette de refactoring du TattooerController monolithique et les 14 events non dispatchés.
