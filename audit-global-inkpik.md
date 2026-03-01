# 🔍 AUDIT GLOBAL INK&PIK
# Date : 2026-03-01

---

## RÉSUMÉ EXÉCUTIF

| Métrique | Valeur |
|---|---|
| Total routes | ~430 (200+ web + 113 API + filament/livewire) |
| Total models | 38 |
| Total vues Blade | 183 |
| Total tests | 193 tests / 91 fichiers |
| Total migrations | 136 |
| Tables DB | 57 |
| Notifications | 23 classes |
| Commands artisan custom | 20 |
| Livewire components | 53 |
| Services | 13 |
| Actions | 15 |
| **Avancement global estimé** | **~72%** |

---

## 1. INFRASTRUCTURE

### Stack technique
| Composant | Version |
|---|---|
| Laravel | 12.46.0 |
| PHP | 8.3.16 |
| Node.js | 24.13.0 |
| npm | 11.6.2 |
| Livewire | 3.x |
| Filament | 4.x |
| Laravel Cashier | installé |
| Stripe PHP | installé |
| Spatie Permission | installé |
| Spatie Medialibrary | installé |

### Build assets
- `public/build/` : compilé (manifest.json, assets/, sw.js, workbox)
- TailwindCSS v4 + Alpine.js

### Configuration custom
- `config/inkpik.php` : plans Free (7% commission) / PRO (49,99€, 0% commission)
- `config/tattoolib.php` : planning, créneaux, disponibilités
- `config/firebase.php` : Firebase configuré (FCM push)

---

## 2. PAR MODULE — ÉTAT DÉTAILLÉ

### 2.1 AUTHENTIFICATION & INSCRIPTION

| Feature | Status | Détail |
|---|---|---|
| Login email/password | ✅ FAIT | LoginController + Fortify |
| Inscription Tattooer | ✅ FAIT | RegisterTattooer + Livewire |
| Inscription Pierceur | ✅ FAIT | RegisterPierceur (polymorphique) |
| Inscription Studio | ✅ FAIT | RegisterStudio + Livewire |
| Inscription Client | ✅ FAIT | RegisterClient + Livewire |
| 2FA (Two Factor) | ✅ FAIT | TwoFactor Livewire + RecoveryCodes |
| Reset mot de passe | ✅ FAIT | ForgotPasswordController |
| Vérification email | ✅ FAIT | EmailVerification |
| Rôles Spatie | ✅ FAIT | tattooer, pierceur, client, admin, studio_owner |
| Logout | ✅ FAIT | LogoutController + Livewire Action |
| SIRET à l'inscription | ✅ FAIT | Champ requis pour tattooer/pierceur |
| Validation SIRET via API | ❌ MANQUANT | TODO dans Filament (entreprise.data.gouv.fr) |
| Pending verification flow | ✅ FAIT | PendingVerification Livewire |

### 2.2 TATTOOER (indépendant)

| Feature | Status | Détail |
|---|---|---|
| Dashboard | ✅ FAIT | TattooerController@dashboard + Livewire |
| Profil + édition | ✅ FAIT | Livewire\Tattooer\Profile |
| Portfolio upload/delete | ✅ FAIT | portfolioUpload, portfolioDestroy |
| **portfolio_count** | ⚠️ PARTIEL | ArtistResource retourne 0 — TODO implémentation |
| Disponibilités | ✅ FAIT | Livewire\Tattooer\Availability |
| Calendrier | ✅ FAIT | TattooerController@calendar + CalendarEvents |
| Paramètres | ✅ FAIT | Livewire\Tattooer\Settings |
| Demandes (booking requests) | ✅ FAIT | Livewire\Tattooer\BookingRequests |
| Accepter demande | ✅ FAIT | AcceptBookingRequest action |
| Rejeter demande | ✅ FAIT | RejectBookingRequest action |
| Reproposer dates | ✅ FAIT | TattooerController@reproposeBookingDates |
| Liste clients | ✅ FAIT | Livewire\Tattooer\Clients |
| Fiche client (show/edit) | ✅ FAIT | TattooerController@clientShow, updateClient |
| Upload photos client | ✅ FAIT | uploadClientPhotos, deleteClientPhotos |
| Notes client | ✅ FAIT | updateClientNotes |
| Consentement client | ✅ FAIT | uploadConsent, storeConsent, deleteConsent |
| Traçabilité client | ✅ FAIT | storeClientTraceability |
| Messages (chat) | ✅ FAIT | TattooerController@messages + Livewire |
| Paiements | ✅ FAIT | TattooerController@payments |
| Compliance / badges | ✅ FAIT | TattooerController@compliance |
| Analytics | ✅ FAIT | Livewire\Tattooer\Analytics |
| Suppression compte | ✅ FAIT | Tattooer\AccountController@delete |
| Notification nouveau client créé | ⚠️ PARTIEL | TODO dans ClientController@storeClient (l.385) |
| Notification sélection date client | ⚠️ PARTIEL | TODO dans DateSelection Livewire (l.70, l.89) |

### 2.3 PIERCER (polymorphique)

| Feature | Status | Détail |
|---|---|---|
| Architecture miroir tattooer | ✅ FAIT | Même TattooerController, routes /pierceur/* |
| Trait IsArtisan | ✅ FAIT | routePrefix(), isPiercer(), isTattooer() |
| Toutes routes tattooer dupliquées | ✅ FAIT | 40+ routes pierceur identiques |
| Dashboard | ✅ FAIT | pierceur.dashboard → TattooerController@dashboard |
| Portfolio | ✅ FAIT | pierceur.portfolio |
| Stripe Connect (centralisé/distribué) | ✅ FAIT | getStripeAccountId() polymorphique |
| SIRET optionnel si studio centralisé | ✅ FAIT | Validé dans inscription |
| Filament Admin PierceurResource | ✅ FAIT | avec vérification SIRET manuelle |
| **messages-livewire route** | ❌ MANQUANT | pierceur.messages.livewire absent (tattooer l'a) |

### 2.4 STUDIO (multi-tenant)

| Feature | Status | Détail |
|---|---|---|
| Dashboard | ✅ FAIT | Livewire\Studio\Dashboard |
| Profil studio | ✅ FAIT | Livewire\Studio\Profile |
| Paramètres | ✅ FAIT | Livewire\Studio\Settings |
| Gestion artistes | ✅ FAIT | artists, createArtist, storeArtist, removeArtist, toggleArtist |
| Invitation artiste | ✅ FAIT | inviteArtist + token d'invitation |
| Créer artiste lié | ✅ FAIT | StudioController@storeArtist (crée User + rôle) |
| Planning studio | ✅ FAIT | StudioController@planning |
| Calendrier studio | ✅ FAIT | Livewire\Studio\Calendar |
| Messages | ✅ FAIT | Livewire\Studio\Messages |
| Abonnement (billing) | ✅ FAIT | StudioBillingService, showSubscribe, processSubscribe |
| Essai gratuit 14j | ✅ FAIT | formule_essais dans flux inscription |
| Portail billing | ✅ FAIT | billingPortalUrl() |
| Stats | ✅ FAIT | StudioController@stats |
| Compliance | ⚠️ PARTIEL | Route studio.studio.compliance existe, vue non vérifiée |
| Upgrade plan | ⚠️ PARTIEL | Route studio.upgrade existe, logique à confirmer |
| Profil public (/salon/{slug}) | ✅ FAIT | StudioController@publicProfile |
| Comptabilité | ⚠️ PARTIEL | StudioAccountingEntry model + tables (0 rows) |
| Inventaire | ⚠️ PARTIEL | InventoryItem + InventoryMovement models (0 rows) |
| Commandes fournisseur | ⚠️ PARTIEL | PurchaseOrder + PurchaseOrderItem (0 rows) |
| Filament panel Studio | ✅ FAIT | BookingRequestResource + StudioArtistResource |
| StudioArtist = PRO auto | ✅ FAIT | is_subscribed=true, 0% commission |

### 2.5 CLIENT

| Feature | Status | Détail |
|---|---|---|
| Dashboard | ✅ FAIT | ClientController@dashboard |
| Profil | ✅ FAIT | Client\ProfileController |
| Liste bookings | ✅ FAIT | Livewire\Client\Bookings |
| Détail booking | ✅ FAIT | ClientController@bookingRequestShow |
| Sélection date | ✅ FAIT | Livewire\Client\DateSelection |
| Demander alternatives | ✅ FAIT | client.booking-request.request-alternatives |
| Annuler booking | ✅ FAIT | ClientController@bookingRequestCancel |
| Chat client | ✅ FAIT | ClientController@chat |
| Envoyer message | ✅ FAIT | ClientController@sendMessage |
| Messages | ✅ FAIT | Client\ProfileController@messages |
| Avis | ✅ FAIT | ClientController@reviews + createReview |
| Réclamations | ✅ FAIT | ClientController@complaints + createComplaint |
| Settings | ✅ FAIT | Client\ProfileController@settings |
| Avatar (upload/delete) | ✅ FAIT | updateAvatar, deleteAvatar |
| **Paiement acompte (client)** | ✅ FAIT | client.balance.show → BalancePaymentController |
| **Notification tattooer à sélection date** | ❌ MANQUANT | TODO dans DateSelection Livewire |
| Notifications email client | ⚠️ PARTIEL | Classes existent mais plusieurs TODO non reliés |

### 2.6 BOOKING WORKFLOW

| Feature | Status | Détail |
|---|---|---|
| **Statuts** | ✅ FAIT | pending → accepted → awaiting_deposit → deposit_paid → design_sent → confirmed → rejected/expired/cancelled |
| Enum BookingRequestStatus | ✅ FAIT | `app/Enums/BookingRequestStatus.php` |
| Chat statuts | ✅ FAIT | CHAT_STATUS_OPEN/CLOSED/EXPIRED + DISPUTE |
| Création demande (API) | ✅ FAIT | Api\BookingRequestController@store |
| Accepter demande | ✅ FAIT | AcceptBookingRequest action |
| Rejeter demande | ✅ FAIT | RejectBookingRequest action |
| Reproposer dates | ✅ FAIT | TattooerController@reproposeBookingDates |
| Acompte (dépôt) | ✅ FAIT | DepositPaymentController, montants configurables (.env) |
| Webhook acompte | ✅ FAIT | DepositPaymentController@webhook |
| Paiement solde | ✅ FAIT | BalancePaymentController |
| Webhook solde | ⚠️ PARTIEL | StripeWebhookController (commentaire: "webhook gère la confirmation") |
| Design envoyé | ✅ FAIT | STATUS_DESIGN_SENT, DesignSentNotification |
| Suivi dessins | ✅ FAIT | TrackDesignDelivery service |
| Annulation + remboursement | ✅ FAIT | CancelBookingWithRefund action complète |
| Calcul % remboursement | ✅ FAIT | calculateRefundPercentage (selon annuleur) |
| No-show client | ✅ FAIT | reportNoShow routes + action |
| Complétion RDV | ✅ FAIT | tattooer.appointments.complete |
| Expiration acompte impayé | ✅ FAIT | ExpireUnpaidDeposits command |
| Expiration générale | ✅ FAIT | CleanupExpiredBookingRequests command |
| **Copie médias → fiche client à expiration** | ❌ MANQUANT | TODO dans CheckExpiredBookingRequests (l.170) |
| Modification de projet (amendment) | ❌ MANQUANT | Non implémenté |
| Remboursement basé dessins | ⚠️ PARTIEL | calculateRefundPercentage existe, lié aux dessins ? À vérifier |

### 2.7 PAIEMENTS & STRIPE

| Feature | Status | Détail |
|---|---|---|
| Stripe Connect (tattooer/pierceur) | ✅ FAIT | StripeService (createConnectAccount, onboarding, status) |
| Stripe Connect centralisé (studio) | ✅ FAIT | Tattooer/Piercer.getStripeAccountId() polymorphique |
| Application fee (commission) | ✅ FAIT | 7% free, 0% pro, via application_fee_amount |
| Abonnement PRO (tattooer/pierceur) | ✅ FAIT | Subscription model + is_subscribed flag |
| Abonnement Studio | ✅ FAIT | StripeStudioSubscriptionService |
| Webhooks Stripe | ✅ FAIT | StripeWebhookController + listeners Cashier |
| Acompte client (Stripe Checkout) | ✅ FAIT | DepositPaymentController@createCheckoutSession |
| Paiement solde | ✅ FAIT | BalancePaymentController |
| Remboursements Stripe | ✅ FAIT | ProcessStripeRefund job + CancelBookingWithRefund |
| Portail billing | ✅ FAIT | StudioBillingService@billingPortalUrl |
| Désactivation Stripe inactif | ✅ FAIT | DeactivateInactiveStripeAccounts command |
| **Coupons / codes promo** | ❌ MANQUANT | Aucun système (pas de table, pas de logique) |
| **Coupon BETA-testeurs** | ❌ MANQUANT | Champ is_beta_tester absent de la table users |
| **Klarna** | ❌ MANQUANT | TODO dans BalancePaymentController (l.93) |
| Export comptabilité CSV/Excel | ❌ MANQUANT | TODO dans AccountingController (l.286) |
| Factures PDF | ❌ MANQUANT | Pas de dompdf/snappy dans composer.json |
| pending_deposits dans dashboard | ⚠️ PARTIEL | TODO dans TattooerController (l.2076), retourne 0 |

### 2.8 CHAT & MESSAGING

| Feature | Status | Détail |
|---|---|---|
| Model Conversation | ✅ FAIT | Conversation + ConversationUser pivot |
| Model Message | ✅ FAIT | implements HasMedia (Spatie) |
| Chat artiste/client | ✅ FAIT | ProjectChat Livewire |
| Chat studio | ✅ FAIT | Livewire\Studio\Messages |
| Chat studio-artist | ✅ FAIT | Livewire\StudioArtist\Messages |
| Tables conversation/messages | ✅ FAIT | 2 rows conversations, 7 messages (données test) |
| Expiration chat (3 phases) | ✅ FAIT | ManageChatStatus command, CloseExpiredConversations |
| Restriction upload avant acompte | ✅ FAIT | CloseExpiredConversations@whereNull('deposit_paid_at') |
| Envoi fichiers/images | ✅ FAIT | Messages via Spatie Medialibrary |
| Scan antivirus uploads | ✅ FAIT | AntivirusService (ClamAV optional, log warning si absent) |
| Non-lu messages | ✅ FAIT | UnreadMessagesController |

### 2.9 NOTIFICATIONS

| Feature | Status | Détail |
|---|---|---|
| 23 classes notification | ✅ FAIT | Toutes les étapes workflow couvertes |
| AppointmentConfirmed/Reminder | ✅ FAIT | |
| DepositPaid/Requested/Expired | ✅ FAIT | |
| DesignSent/NoDesignAlert | ✅ FAIT | |
| NewBookingRequest/Rejected/Accepted | ✅ FAIT | |
| BookingCancelled/Modified | ✅ FAIT | |
| CareSheetReminder/ConsentReminder | ✅ FAIT | |
| PostTattooCare/HealingCheck | ✅ FAIT | |
| RequestReview | ✅ FAIT | |
| NoShowReported | ✅ FAIT | |
| Emails transactionnels | ✅ FAIT | 3 Mailables (StudioArtist, Invitation, TrialEndingSoon) |
| Templates email blade | ✅ FAIT | resources/views/emails/studio/ (3 templates) |
| Scheduler | ✅ FAIT | 6 commandes planifiées dans console.php |
| **Notification tattooer: accept/reject** | ❌ MANQUANT | TODO dans AcceptBookingRequest (l.53, l.182) et RejectBookingRequest (l.35, l.85) |
| **Notification tattooer: confirm date** | ❌ MANQUANT | TODO dans ConfirmAppointmentDate (l.48) |
| **Notification admin: no-show** | ❌ MANQUANT | TODO dans ReportNoShowAction (l.72) |
| **Notification: création rapide RDV** | ❌ MANQUANT | TODO dans BookingQuickCreate (l.129) |
| **Push FCM mobile** | ⚠️ PARTIEL | Firebase configuré, FCMController existe, intégration complète ? |
| Notifications web (VAPID) | ⚠️ PARTIEL | VAPID keys dans .env, mais push web non vérifiée |

### 2.10 FICHES CLIENTS & TRAÇABILITÉ

| Feature | Status | Détail |
|---|---|---|
| Model ClientCareSheet | ✅ FAIT | + table client_care_sheets (0 rows) |
| Model ClientConsentForm | ✅ FAIT | + table client_consent_forms (0 rows) |
| Model TraceabilityRecord | ✅ FAIT | + tables traceability_records/needles/inks (0 rows) |
| Model ParentalConsentForm | ✅ FAIT | + table parental_consent_forms (0 rows) |
| Upload consentement | ✅ FAIT | uploadConsent, storeConsent routes |
| Traçabilité par client | ✅ FAIT | storeClientTraceability route |
| ConsentReminderNotification | ✅ FAIT | J-4 rappel via SendConsentReminders command |
| CareSheetReminder | ✅ FAIT | Notification classe existante |
| Post-tattoo care notifications | ✅ FAIT | SendPostTattooNotifications command |
| Politique RGPD | ⚠️ PARTIEL | Non vérifiée dans les vues (mention légale ?) |
| **Export PDF fiches** | ❌ MANQUANT | dompdf/snappy absent de composer.json |
| **Copie médias vers fiche client** | ❌ MANQUANT | TODO dans CheckExpiredBookingRequests |

### 2.11 CONFORMITÉ LÉGALE

| Feature | Status | Détail |
|---|---|---|
| SIRET obligatoire inscription | ✅ FAIT | Tattooer + Pierceur indépendant |
| SIRET vérification manuelle admin | ✅ FAIT | Toggle siret_verified dans Filament |
| Validation SIRET via API externe | ❌ MANQUANT | TODO dans Filament forms (entreprise.data.gouv.fr) |
| Model ComplianceRecord | ✅ FAIT | + table compliance_records (0 rows) |
| Expiration certifications | ✅ FAIT | CheckComplianceExpirations command |
| Alertes expiration documents | ✅ FAIT | Command avec thresholds et alertes admin |
| TattooHistory model | ✅ FAIT | table tattoo_histories (0 rows) |
| Consentement numérique | ✅ FAIT | Consent model + ConsentReminderNotification |
| Consentement parental (mineurs) | ✅ FAIT | ParentalConsentForm model |
| Badges vérification artiste | ⚠️ PARTIEL | compliance_status sur Tattooer, logique UI ? |
| **Export PDF légal** | ❌ MANQUANT | Pas de librairie PDF |
| **Politique CGU / mentions légales** | ❌ MANQUANT | Non trouvées dans les vues |

### 2.12 MARKETPLACE & PAGES PUBLIQUES

| Feature | Status | Détail |
|---|---|---|
| Page d'accueil (/) | ✅ FAIT | resources/views/welcome.blade.php |
| Marketplace (listing) | ✅ FAIT | resources/views/marketplace/index.blade.php |
| Profil artiste public | ✅ FAIT | resources/views/marketplace/show.blade.php |
| Profil studio public | ✅ FAIT | resources/views/marketplace/studio-show.blade.php |
| MarketplaceSearchService | ✅ FAIT | Tattooer + Piercer inclus |
| API marketplace (featured, search, stats, filters) | ✅ FAIT | Api\MarketplaceController |
| PublicProfileController | ✅ FAIT | view('public.tattooer-profile') |
| Profil public /artistes/{slug} | ⚠️ PARTIEL | Vue 'public.tattooer-profile' — chemin à vérifier |
| CacheService (listings) | ✅ FAIT | Inclut tattooers + pierceurs |
| WarmupCache command | ✅ FAIT | |
| **Page /about, /contact** | ❌ MANQUANT | Pas de routes publiques "marketing" |
| **SEO / meta tags** | ❌ MANQUANT | Non trouvé dans les layouts |

### 2.13 FILAMENT ADMIN

| Feature | Status | Détail |
|---|---|---|
| Panel Admin (/admin) | ✅ FAIT | AdminPanelProvider |
| Panel Studio (/studio/admin) | ✅ FAIT | StudioPanelProvider |
| Panel Shop | ⚠️ PARTIEL | ShopPanelProvider existe — usage ? |
| UserResource | ✅ FAIT | |
| TattooerResource | ✅ FAIT | avec vérification SIRET |
| PierceurResource | ✅ FAIT | avec vérification SIRET |
| StudioResource | ✅ FAIT | |
| StudioArtistResource (admin) | ✅ FAIT | |
| BookingRequestResource (admin) | ✅ FAIT | |
| AppointmentResource | ✅ FAIT | |
| PaymentResource | ✅ FAIT | |
| SubscriptionResource | ✅ FAIT | avec commission_rate |
| ReviewResource | ✅ FAIT | |
| ComplaintResource | ✅ FAIT | |
| ComplianceRecordResource | ✅ FAIT | |
| TransactionResource | ✅ FAIT | |
| StudioArtistResource (studio panel) | ✅ FAIT | |
| BookingRequestResource (studio panel) | ✅ FAIT | |
| StatsOverview widget | ✅ FAIT | |
| RevenueOverviewWidget | ✅ FAIT | |
| ComplaintsWidget | ✅ FAIT | |
| StudioStatsOverview widget | ✅ FAIT | |

### 2.14 SÉCURITÉ

| Feature | Status | Détail |
|---|---|---|
| SecurityHeaders middleware | ✅ FAIT | CSP adapté local/prod |
| BlockSuspiciousIps middleware | ✅ FAIT | |
| CustomThrottle middleware | ✅ FAIT | Rate limiting personnalisé |
| SecureFileUpload middleware | ✅ FAIT | |
| EnsureOwnership middleware | ✅ FAIT | |
| EnsureProPlan middleware | ✅ FAIT | |
| EnsureStudioCanOperate middleware | ✅ FAIT | |
| EnsureUserHasRole middleware | ✅ FAIT | |
| EnsureUserHasStatus middleware | ✅ FAIT | |
| EnsureUserIsAdmin middleware | ✅ FAIT | |
| ExcludeWebhookFromCsrf middleware | ✅ FAIT | Pour /webhooks/stripe |
| EnsureSanctumAuthentication | ✅ FAIT | API mobile |
| InputSanitizerService | ✅ FAIT | |
| SecurityMonitoringService | ✅ FAIT | |
| Antivirus scan uploads | ✅ FAIT | ClamAV (optionnel, graceful fallback) |
| 14 Policies (Authorization) | ✅ FAIT | Booking, Conversation, Client, etc. |
| Signature webhook Stripe | ✅ FAIT | constructEvent avec endpoint_secret |
| **2FA** | ✅ FAIT | TOTP Livewire |

### 2.15 TESTS

| Feature | Status | Détail |
|---|---|---|
| Structure tests | ✅ FAIT | Feature + Unit |
| Total fichiers tests | ✅ FAIT | 91 fichiers |
| Total assertions/tests | ✅ FAIT | 193 tests |
| Auth tests | ✅ FAIT | Authentication, Registration, Password, 2FA |
| API tests | ✅ FAIT | Appointments, BookingRequests, Conversations, Availability |
| BookingWorkflow tests | ✅ FAIT | WorkflowTest, TransitionTest, AdditionalTest |
| BalancePayment tests | ✅ FAIT | |
| Cache tests | ✅ FAIT | |
| Pierceur API tests | ✅ FAIT | |
| **Tests Livewire** | ❌ MANQUANT | Pas de tests pour les composants Livewire |
| **Tests Studio** | ❌ MANQUANT | Pas de Feature tests pour le module Studio |
| **Tests Stripe webhooks** | ❌ MANQUANT | Non trouvés |
| Couverture code | ⚠️ PARTIEL | Estimée ~40% (backend API couvert, UI non) |

---

## 3. BUGS DÉTECTÉS

### 3.1 Routes problématiques

| Bug | Fichier | Détail |
|---|---|---|
| `studio.studio.compliance` | routes/web.php | Nom de route avec double "studio" (studio.studio.compliance) — probablement un groupe mal nommé |
| `pierceur.messages.livewire` | routes/web.php | Route absente pour pierceur (tattooer l'a, pierceur non) |
| `tattooer.tattooer.client-requests` | routes/web.php | Nom de route avec double "tattooer" (idem) |
| `tattooer.tattooer.consent.store` | routes/web.php | Nom de route avec double "tattooer" |

### 3.2 TODO critiques (code non terminé)

| Fichier | Ligne | Problème |
|---|---|---|
| `app/Actions/AcceptBookingRequest.php` | 53, 182 | **Notification client absente** après acceptation |
| `app/Actions/RejectBookingRequest.php` | 35, 85 | **Notification client absente** après rejet |
| `app/Actions/ConfirmAppointmentDate.php` | 48 | **Notification tattooer absente** après confirmation date |
| `app/Actions/ReportNoShowAction.php` | 72 | **Notification admin absente** pour no-show |
| `app/Http/Controllers/ClientController.php` | 385, 454 | **Notification tattooer absente** pour actions client |
| `app/Http/Controllers/TattooerController.php` | 2076 | `pending_deposits` retourne 0 (non calculé) |
| `app/Http/Controllers/TattooerController.php` | 2180, 2240 | Systèmes de notifications manquants |
| `app/Http/Controllers/Api/AccountingController.php` | 286 | Export CSV/Excel non implémenté |
| `app/Http/Controllers/BalancePaymentController.php` | 93 | Klarna non implémenté |
| `app/Jobs/CheckExpiredBookingRequests.php` | 170 | Copie médias → fiche client manquante |
| `app/Jobs/CheckExpiredBookingRequests.php` | 185, 188 | Notifications non implémentées |
| `app/Livewire/Client/DateSelection.php` | 70, 89 | Notifications tattooer manquantes |
| `app/Livewire/Tattooer/AppointmentDetailModal.php` | 93, 133 | Notifications client manquantes |
| `app/Livewire/Tattooer/BookingQuickCreate.php` | 129 | Notification client manquante |
| `app/Livewire/Tattooer/QuickBookingModal.php` | 189 | Notification manquante |
| `app/Models/Appointment.php` | 349, 368, 385 | Events non déclenchés (AppointmentCompleted, ClientNoShow, AppointmentDisputed) |
| `app/Resources/ArtistResource.php` | 76 | `portfolio_count` retourne 0 |
| `app/Filament/.../PierceurForm.php` | 41 | Vérification SIRET via API externe non implémentée |
| `app/Filament/.../TattooerForm.php` | 57 | Vérification SIRET via API externe non implémentée |
| `app/Console/Commands/DeactivateInactiveStripeAccounts.php` | 34 | Email de notification non envoyé à désactivation |

### 3.3 Données vides (0 rows en DB)

Ces tables existent mais sont vides (normal en dev, mais à surveiller) :
`accounting_transactions`, `availabilities`, `client_care_sheets`, `client_consent_forms`, `complaints`, `compliance_records`, `consents`, `inventory_items`, `inventory_movements`, `invoices`, `parental_consent_forms`, `payments`, `permissions`, `refunds`, `reviews`, `studio_accounting_entries`, `studio_subscriptions`, `tattoo_histories`, `tattooer_subscriptions`, `traceability_records/needles/inks`, `transactions`, `working_hours`

---

## 4. TODO/FIXME DANS LE CODE

### TODO par priorité fonctionnelle

```
CRITIQUE — Workflow cassé :
- AcceptBookingRequest: notification client manquante (l.53, l.182)
- RejectBookingRequest: notification client manquante (l.35, l.85)
- ConfirmAppointmentDate: notification tattooer manquante (l.48)
- DateSelection: notification tattooer après sélection date (l.70, l.89)
- TattooerController: pending_deposits = 0 (l.2076)

IMPORTANT — Fonctionnalités incomplètes :
- AccountingController: export CSV/Excel (l.286)
- ArtistResource: portfolio_count = 0 (l.76)
- CheckExpiredBookingRequests: copie médias → fiche client (l.170)
- Appointment model: Events non déclenchés (l.349, 368, 385)

NICE TO HAVE — Améliorations :
- BalancePaymentController: Klarna (l.93)
- DeactivateInactiveStripeAccounts: email notification (l.34)
- ReportNoShowAction: admin notification (l.72)
- BookingQuickCreate/QuickBookingModal: notifications client (l.129, l.189)
- SIRET API externe: entreprise.data.gouv.fr (Filament forms)
```

---

## 5. FEATURES MANQUANTES — PRIORISÉES

### BLOC P0 — Bloquant pour le lancement

| # | Feature | Impact | Effort estimé |
|---|---|---|---|
| P0.1 | **Notifications manquantes dans les Actions** (AcceptBooking, RejectBooking, ConfirmDate, DateSelection) | Workflow silencieux — clients et artistes pas informés | 1-2j |
| P0.2 | **pending_deposits calculé dans dashboard tattooer** | Dashboard financier faux | 0.5j |
| P0.3 | **Copie médias → fiche client à expiration booking** | Perte de données | 1j |
| P0.4 | **Doublons dans noms de routes** (studio.studio.*, tattooer.tattooer.*) | Bugs potentiels dans les vues | 0.5j |
| P0.5 | **portfolio_count = 0** dans ArtistResource API | Marketplace toujours "0 photos" | 0.5j |
| P0.6 | **Route pierceur.messages.livewire absente** | Feature manquante côté pierceur | 0.5j |

### BLOC P1 — Important pour le lancement

| # | Feature | Impact | Effort estimé |
|---|---|---|---|
| P1.1 | **Export comptabilité CSV/Excel** | Obligation légale pour artisans | 1-2j |
| P1.2 | **Validation SIRET via API** (entreprise.data.gouv.fr) | Vérification identité fraude | 1j |
| P1.3 | **Events Appointment** (AppointmentCompleted, ClientNoShow, AppointmentDisputed) | Découplage logique métier | 1j |
| P1.4 | **Export PDF** (fiches clients, consentements, traçabilité) | Obligation légale santé/hygiène | 2-3j (dompdf) |
| P1.5 | **CGU / Mentions légales** pages | Obligation légale France | 0.5j |
| P1.6 | **Coupons / codes promo BETA** | Onboarding des premiers clients | 1-2j |

### BLOC P2 — Post-lancement

| # | Feature | Impact | Effort estimé |
|---|---|---|---|
| P2.1 | **Klarna paiement** | Conversion clients (paiement fractionné) | 1-2j |
| P2.2 | **Push notifications mobiles** (FCM) | Engagement utilisateurs | 2-3j (Firebase intégré) |
| P2.3 | **Vérification SIRET admin** automatisée | Réduction charge opérationnelle | 1j |
| P2.4 | **Tests Livewire** | Qualité code | 3-5j |
| P2.5 | **Tests webhooks Stripe** | Sécurité paiements | 1-2j |
| P2.6 | **Tests module Studio** | Qualité code | 2-3j |
| P2.7 | **Notifications email studio** (plus que 3 templates) | UX studio owner | 1-2j |
| P2.8 | **SEO meta tags** marketplace | Acquisition organique | 1j |
| P2.9 | **Pages marketing** (/about, /contact, /pricing) | Conversion visiteurs | 1-2j |
| P2.10 | **Modification de projet** (amendment) | Feature contractuelle | 2-3j |
| P2.11 | **Suspension inactivité** automatique (account_status) | Qualité données | 1j |
| P2.12 | **Panel Shop** (ShopPanelProvider) | Usage non documenté | À évaluer |
| P2.13 | **Inventaire + Comptabilité** (tables vides) | Reporting studio | 3-5j |

---

## 6. RECOMMANDATION TIMELINE

### Avant lancement (P0 + P1) : ~3 semaines
```
Semaine 1 : P0 (bugs critiques)
  - Notifications manquantes dans les Actions → 2j
  - pending_deposits + portfolio_count + route fixes → 1j
  - Copie médias à expiration → 1j
  - Route pierceur.messages.livewire → 0.5j

Semaine 2 : P1 partie 1
  - Export CSV/Excel comptabilité → 2j
  - Validation SIRET via API → 1j
  - Events Appointment → 1j
  - CGU/mentions légales pages → 0.5j

Semaine 3 : P1 partie 2
  - PDF export (dompdf) → 3j
  - Coupons BETA → 2j
```

### Post-lancement (P2) : 6-8 semaines
```
Mois 2 : Push FCM mobile, Klarna, Tests Livewire
Mois 3 : SEO/Marketing pages, Modification projet, Inventaire
```

---

## 7. ÉVALUATION PAR MODULE

| Module | % complet | Bloquant lancement |
|---|---|---|
| Auth & inscription | 90% | Non (SIRET API P1) |
| Tattooer | 80% | Oui (notifications P0) |
| Pierceur | 85% | Oui (route manquante P0) |
| Studio | 78% | Non |
| Client | 82% | Oui (notifications P0) |
| Booking workflow | 80% | Oui (médias, pending_deposits P0) |
| Paiements Stripe | 78% | Non (Klarna P2) |
| Chat & messaging | 90% | Non |
| Notifications | 60% | Oui (P0 critiques) |
| Fiches clients / Traçabilité | 70% | Non (PDF P1) |
| Conformité légale | 60% | Oui (CGU, PDF P1) |
| Marketplace | 75% | Non (portfolio_count P0) |
| Filament Admin | 85% | Non |
| Sécurité | 90% | Non |
| Tests | 55% | Non |
| **GLOBAL** | **~72%** | |

---

*Rapport généré le 2026-03-01 par audit automatique du codebase Ink&Pik.*
*Basé sur : 38 models, 183 vues, 136 migrations, 57 tables DB, 430+ routes, 193 tests, 20 commands artisan.*
