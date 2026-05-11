# 🖋️ Ink&Pik — Marketplace & Back-Office pour le Body Art

![Statut](https://img.shields.io/badge/statut-bêta-orange)
![Laravel](https://img.shields.io/badge/Laravel-12.0-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![Livewire](https://img.shields.io/badge/Livewire-3.7-purple)
![Licence](https://img.shields.io/badge/licence-propriétaire-lightgrey)

> 🇬🇧 English version available in [README.md](./README.md)

---

## 🎯 Présentation

**Ink&Pik** est une plateforme SaaS française dédiée aux professionnels du **body art** — tatoueurs, pierceurs et studios — ainsi qu'à leurs clients.

Le projet repose sur une **double proposition de valeur** :

- **Marketplace** : Les clients trouvent et contactent un tatoueur ou pierceur près de chez eux, consultent leurs portfolios, et soumettent une demande de réservation directement depuis la plateforme.
- **Back-office professionnel** : Chaque artiste ou studio dispose d'un espace de gestion complet — agenda, demandes de réservation, fiches clients, consentements, traçabilité, paiements, conformité réglementaire et comptabilité.

> Projet personnel solo, actuellement en **version bêta**. Non open-source.

---

## 🛠️ Stack Technique

### Back-end

| Technologie | Version |
|---|---|
| PHP | ^8.2 |
| Laravel Framework | ^12.0 |
| Livewire | ^3.7 |
| Livewire Flux | ^2.10 |
| Filament (admin) | ^4.9.1 |
| Filament Spatie Media Library Plugin | ^4.9.1 |
| Laravel Cashier (Stripe) | ^16.2 |
| Laravel Fortify | ^1.30 |
| Laravel Sanctum | ^4.2 |
| Spatie Laravel Permission | ^6.24 |
| Spatie Laravel Media Library | ^11.17 |
| Stripe PHP SDK | ^17.6 |
| Kreait Laravel Firebase | ^6.2 |
| Barryvdh Laravel DomPDF | ^3.1 |
| Maatwebsite Excel | ^3.1 |

### Front-end

| Technologie | Version |
|---|---|
| Vite | ^7.0.4 |
| TailwindCSS | ^4.0.7 |
| Alpine.js Collapse Plugin | ^3.15.11 |
| Axios | ^1.7.4 |
| Firebase JS SDK | ^12.7.0 |
| Web Push | ^3.6.7 |
| vite-plugin-pwa | ^1.2.0 |
| laravel-vite-plugin | ^2.0 |

### Infrastructure & Outils

| Outil | Rôle |
|---|---|
| MySQL | Base de données principale |
| Queue (database driver) | Jobs asynchrones |
| Cache (database driver) | Cache applicatif |
| Stripe | Abonnements SaaS + paiements artistes (Stripe Connect) |
| Firebase / FCM | Notifications push mobiles |
| VAPID | Notifications push web (PWA) |

---

## ✨ Fonctionnalités par Profil

### 👤 Client
- Recherche d'artistes et studios sur la **marketplace** (filtres : style, ville, type)
- Consultation des profils publics et portfolios
- Soumission de **demandes de réservation** (projet, budget, disponibilités)
- Sélection de dates parmi les créneaux proposés par l'artiste
- Paiement de l'**acompte en ligne** (Stripe)
- Paiement du **solde final** via la plateforme
- Messagerie intégrée avec l'artiste
- Gestion des rendez-vous (annulation, suivi statut)
- Dépôt d'avis et de réclamations
- Export des données personnelles (RGPD)
- Suppression de compte

### 🎨 Tatoueur / Pierceur (artiste indépendant)
- **Dashboard** avec statistiques et KPIs
- Gestion des **demandes de réservation** (accepter, refuser, reproposer des dates)
- **Calendrier** (FullCalendar-like, gestion d'événements, disponibilités)
- **Gestion clients** avec fiche complète (notes, historique, photos)
- **Portfolio** avec photos avant/après
- **Messagerie** interne avec les clients
- Formulaires de **consentement** (upload PDF ou saisie numérique)
- **Traçabilité** des produits utilisés (encres, bijoux)
- **Conformité réglementaire** (documents administratifs)
- **Paiements Stripe Connect** (compte de vente connecté)
- **Abonnement** STARTER / PRO (gestion depuis le back-office)
- Export **comptabilité** (transactions, récapitulatif mensuel, format Excel)
- Export **PDF** (fiches soins, consentements, traçabilité)
- Export données personnelles RGPD
- Authentification **2FA** (via Laravel Fortify)
- Paramètres avancés (horaires de travail, aftercare, tarification)

### 🏢 Studio
- **Dashboard** avec statistiques consolidées
- Gestion des **artistes du studio** (invitation par token, ajout/retrait)
- **Planning studio** (vue consolidée des calendriers artistes)
- Suivi des **demandes de réservation** des artistes
- **Fiches clients** centralisées
- **Facturation & abonnement** STUDIO
- Connexion **Stripe Connect** (studio)
- Export des transactions studio
- Messagerie interne
- Conformité réglementaire studio

### 🎭 Artiste de Studio
- Dashboard dédié
- Gestion de ses demandes de réservation
- Calendrier personnel
- Profil et portfolio
- Messagerie

### 🔧 Admin (Filament)
- Panel d'administration complet via **Filament 4.9.1**
- Supervision des utilisateurs, artistes, studios
- Gestion des conversations
- Supervision des documents de conformité
- Export des réservations

---

## 🗂️ Architecture du Projet

```
tattoolib-saas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/              # API REST (artistes, réservations, disponibilités, paiements…)
│   │   │   ├── Auth/             # Login, logout, reset password
│   │   │   ├── Client/           # Dashboard, réservations, messages, profil client
│   │   │   ├── Studio/           # Dashboard, artistes, billing, settings studio
│   │   │   └── Tattooer/         # Dashboard, réservations, clients, conformité, médias…
│   │   └── Middleware/
│   ├── Livewire/
│   │   ├── Auth/                 # Inscriptions par rôle
│   │   ├── Client/               # Composants client
│   │   ├── Studio/               # Composants studio
│   │   ├── StudioArtist/         # Composants artiste de studio
│   │   ├── Tattooer/             # Composants tatoueur/pierceur
│   │   ├── Marketplace/          # Recherche marketplace
│   │   └── Components/           # Composants partagés (calendrier dispo…)
│   ├── Models/
│   │   ├── Tattooer.php          # Artiste indépendant (polymorphique via IsArtisan)
│   │   ├── Piercer.php           # Pierceur (miroir de Tattooer)
│   │   ├── Studio.php
│   │   ├── StudioArtist.php
│   │   ├── Client.php
│   │   ├── User.php
│   │   ├── BookingRequest.php
│   │   ├── Appointment.php
│   │   ├── Conversation.php / Message.php
│   │   ├── Payment.php / Transaction.php / Refund.php
│   │   ├── Subscription.php / TattooerSubscription.php / StudioSubscription.php
│   │   ├── TraceabilityRecord.php
│   │   ├── ClientConsentForm.php / ParentalConsentForm.php
│   │   ├── ClientCareSheet.php
│   │   ├── InventoryItem.php / InventoryMovement.php
│   │   ├── Invoice.php / AccountingTransaction.php
│   │   └── ComplianceRecord.php / DataProcessingRecord.php
│   ├── Services/
│   │   ├── MarketplaceSearchService.php
│   │   └── CacheService.php
│   └── Traits/
│       ├── IsArtisan.php         # Trait polymorphique (Tattooer + Piercer)
│       ├── HasSubscription.php
│       ├── HasStripeConnect.php
│       ├── BookableArtist.php
│       ├── HasCompliance.php
│       └── HasWorkingHours.php
├── database/
│   ├── migrations/               # 40+ migrations organisées chronologiquement
│   └── seeders/
├── resources/
│   └── views/
│       ├── livewire/             # Templates Livewire
│       ├── tattooer/             # Vues back-office artiste
│       ├── studio/               # Vues back-office studio
│       ├── client/               # Vues espace client
│       └── marketplace/          # Vues publiques marketplace
├── routes/
│   └── web.php                   # Routes groupées par profil
└── public/
    └── build/                    # Assets compilés (Vite) + PWA (sw.js, manifest)
```

### Pattern polymorphique artiste

Tattooers et pierceurs partagent le même controller (`TattooerController`) et les mêmes vues Blade. Le trait `IsArtisan` sur les deux modèles expose `isPiercer()`, `isTattooer()`, `routePrefix()` et `artisanType()`. Les vues utilisent `@if ($tattooer->isPiercer())` pour adapter l'affichage.

**Rôles Spatie** : `tattooer` · `pierceur` · `client` · `studio` · `studio_owner` · `admin`

---

## ⚙️ Prérequis

- **PHP** 8.2 ou supérieur (extensions : `pdo_mysql`, `mbstring`, `xml`, `gd`, `zip`, `intl`)
- **Composer** 2.x
- **Node.js** 20.x (LTS) + **npm** ou **pnpm**
- **MySQL** 8.0+
- **Stripe CLI** (pour écouter les webhooks en local)
- Compte **Stripe** avec Stripe Connect activé
- Projet **Firebase** (notifications push)
- Serveur mail (ou log pour le développement)

---

## 🚀 Installation

### 1. Cloner le dépôt

```bash
git clone <url-du-repo> tattoolib-saas
cd tattoolib-saas
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Éditer `.env` en renseignant a minima : connexion base de données, Stripe, Firebase, mail et VAPID.

### 4. Créer la base de données

```sql
CREATE DATABASE tattoolib_saas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Lancer les migrations et les seeders

```bash
php artisan migrate
php artisan db:seed
```

### 6. Installer les dépendances front-end et compiler les assets

```bash
npm install
npm run build
```

### 7. Lier le stockage des médias

```bash
php artisan storage:link
```

### 8. Démarrer la queue (jobs asynchrones)

```bash
php artisan queue:work --tries=3
```

### 9. Écouter les webhooks Stripe en local

```bash
stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe
```

Copier le `whsec_...` affiché dans `STRIPE_WEBHOOK_SECRET` du `.env`.

### 10. Lancer le serveur de développement

```bash
# Tout en un via le script Composer
composer dev
# Ou manuellement :
php artisan serve
npm run dev
```

L'application est accessible sur `http://localhost:8000` (ou l'URL Laragon configurée).

---

## 🔑 Variables d'Environnement Essentielles

| Variable | Description | Exemple |
|---|---|---|
| `APP_NAME` | Nom de l'application | `"Ink&Pik"` |
| `APP_URL` | URL de base | `http://tattoolib-saas.test` |
| `APP_KEY` | Clé de chiffrement Laravel | généré par `artisan key:generate` |
| `DB_DATABASE` | Nom de la base MySQL | `tattoolib_saas` |
| `DB_USERNAME` | Utilisateur MySQL | `root` |
| `DB_PASSWORD` | Mot de passe MySQL | _(vide en local)_ |
| `STRIPE_KEY` | Clé publique Stripe | `pk_test_...` |
| `STRIPE_SECRET` | Clé secrète Stripe | `sk_test_...` |
| `STRIPE_WEBHOOK_SECRET` | Secret webhook Stripe | `whsec_...` |
| `STRIPE_PRICE_ID_STARTER` | ID du prix Stripe STARTER | `price_...` |
| `STRIPE_PRICE_ID_PRO` | ID du prix Stripe PRO | `price_...` |
| `STRIPE_PRICE_ID_STUDIO` | ID du prix Stripe STUDIO | `price_...` |
| `STRIPE_PRICE_ID_STUDIO_EXTRA` | ID du prix par artiste supplémentaire | `price_...` |
| `STRIPE_BETA_COUPON_ID` | Coupon bêta (remise) | `BETA2026` |
| `FIREBASE_CREDENTIALS` | Chemin vers le JSON Firebase Admin | `storage/app/firebase.json` |
| `FIREBASE_API_KEY` | Clé API Firebase JS | `AIzaSy...` |
| `FIREBASE_PROJECT_ID` | ID du projet Firebase | `inkandpik-...` |
| `VAPID_PUBLIC_KEY` | Clé publique VAPID (push web) | `BA...` |
| `VAPID_PRIVATE_KEY` | Clé privée VAPID (push web) | `...` |
| `VAPID_SUBJECT` | URI de contact VAPID | `mailto:contact@inkandpik.fr` |
| `MAIL_MAILER` | Driver mail | `smtp` / `log` |
| `MAIL_FROM_ADDRESS` | Adresse expéditeur | `contact@inkandpik.fr` |
| `SESSION_ENCRYPT` | Chiffrement des sessions | `true` |
| `AVAILABILITY_WINDOW_DAYS` | Fenêtre de recherche dispo (jours) | `90` |
| `DEFAULT_DEPOSIT_RATE` | Taux d'acompte par défaut (%) | `30` |
| `TATTOOER_RESPONSE_DEADLINE_HOURS` | Délai de réponse artiste (h) | `48` |

---

## 💰 Modèle Économique

Ink&Pik propose des abonnements mensuels aux professionnels du body art, facturés via **Stripe Billing**.

| Plan | Cible | Prix |
|---|---|---|
| **STARTER** | Tatoueur / Pierceur indépendant (fonctionnalités de base) | **9,99 €/mois** |
| **PRO** | Tatoueur / Pierceur (toutes fonctionnalités : clients, conformité, exports) | **29,99 €/mois** |
| **STUDIO** | Studio (gestion multi-artistes) | **59,99 €/mois** |
| **STUDIO EXTRA** | Artiste supplémentaire dans un studio | **+24,99 €/mois** |

> Un coupon de réduction bêta (`STRIPE_BETA_COUPON_ID`) permet d'offrir des conditions préférentielles aux premiers utilisateurs.

Les **paiements artistes** (acomptes et soldes) transitent via **Stripe Connect** : la plateforme perçoit une commission configurée par le studio, les fonds sont reversés directement sur le compte Stripe Connect de l'artiste.

---

## 🗺️ Roadmap

### ✅ Réalisé

- Inscription multi-profils (client, tatoueur, pierceur, studio, artiste de studio)
- Marketplace publique avec recherche et filtres
- Profils publics artistes et studios
- Back-office tatoueur/pierceur complet (architecture polymorphique)
- Gestion des demandes de réservation (workflow complet : soumission → acceptation → rendez-vous → clôture)
- Paiement acompte et solde via Stripe
- Stripe Connect (artistes indépendants et studios)
- Calendrier et gestion des disponibilités
- Messagerie interne (artiste ↔ client ↔ admin)
- Portfolio avec photos avant/après
- Fiches clients avec historique
- Formulaires de consentement (numériques et upload PDF)
- Traçabilité des produits (encres, bijoux)
- Fiches soins et consentements parentaux
- Conformité réglementaire (documents administratifs)
- Abonnements STARTER / PRO / STUDIO via Stripe Billing
- Panel admin Filament (supervision utilisateurs, conversations, conformité)
- Export comptabilité (Excel) et export PDF
- Export données RGPD et suppression de compte
- Authentification 2FA (Fortify)
- API REST (artistes, réservations, disponibilités, paiements)
- Progressive Web App (PWA) avec service worker
- Notifications push web (VAPID) et mobile (Firebase FCM)
- Pages légales complètes (CGU, CGV, mentions légales, politique de confidentialité)
- Bêta en ligne sur sous-domaine dédié

### 🔄 En cours

- Finalisation de l'intégration Stripe en production
- Nom de domaine principal (`inkandpik.fr`)
- Tests utilisateurs bêta et correction de bugs

### 📋 À venir

- Module d'avis clients avec modération
- Notifications email transactionnelles complètes
- Amélioration de la recherche marketplace (géolocalisation, filtres avancés)
- Interface mobile optimisée
- Tableau de bord analytics avancé (artiste + studio)
- Module de devis en ligne
- Gestion des listes d'attente
- Intégration agenda Google Calendar / Apple Calendar

---

## 📄 Licence & Statut

**Ink&Pik** est un **projet personnel solo**, actuellement en version bêta.

Le code source est **propriétaire et non open-source**. Toute réutilisation, redistribution ou reproduction — même partielle — est interdite sans autorisation explicite de l'auteur.

© 2025–2026 — Tous droits réservés.
