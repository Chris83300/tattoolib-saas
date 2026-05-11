# 🖋️ Ink&Pik — Marketplace & Back-Office for Body Art Professionals

![Status](https://img.shields.io/badge/status-beta-orange)
![Laravel](https://img.shields.io/badge/Laravel-12.0-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![Livewire](https://img.shields.io/badge/Livewire-3.7-purple)
![License](https://img.shields.io/badge/license-proprietary-lightgrey)

> 🇫🇷 Version française disponible dans [README.fr.md](./README.fr.md)

---

## 🎯 Overview

**Ink&Pik** is a French SaaS platform built for **body art professionals** — tattoo artists, piercers and studios — and their clients.

The project is built around a **dual value proposition**:

- **Marketplace**: Clients find and contact a tattoo artist or piercer near them, browse portfolios, and submit booking requests directly through the platform.
- **Professional back-office**: Every artist or studio gets a complete management workspace — calendar, booking requests, client files, consent forms, traceability, payments, regulatory compliance and accounting.

> Solo personal project, currently in **beta**. Not open-source.

---

## 🛠️ Tech Stack

### Back-end

| Technology | Version |
|---|---|
| PHP | ^8.2 |
| Laravel Framework | ^12.0 |
| Livewire | ^3.7 |
| Livewire Flux | ^2.10 |
| Filament (admin panel) | ^4.9.1 |
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

| Technology | Version |
|---|---|
| Vite | ^7.0.4 |
| TailwindCSS | ^4.0.7 |
| Alpine.js Collapse Plugin | ^3.15.11 |
| Axios | ^1.7.4 |
| Firebase JS SDK | ^12.7.0 |
| Web Push | ^3.6.7 |
| vite-plugin-pwa | ^1.2.0 |
| laravel-vite-plugin | ^2.0 |

### Infrastructure & Tools

| Tool | Role |
|---|---|
| MySQL | Primary database |
| Queue (database driver) | Asynchronous jobs |
| Cache (database driver) | Application cache |
| Stripe | SaaS subscriptions + artist payouts (Stripe Connect) |
| Firebase / FCM | Mobile push notifications |
| VAPID | Web push notifications (PWA) |

---

## ✨ Features by Profile

### 👤 Client
- Search for artists and studios on the **marketplace** (filters: style, city, type)
- Browse public profiles and portfolios
- Submit **booking requests** (project details, budget, availability)
- Select appointment slots from artist proposals
- Pay the **deposit online** (Stripe)
- Pay the **final balance** through the platform
- Integrated messaging with the artist
- Appointment management (cancellation, status tracking)
- Submit reviews and disputes
- Personal data export (GDPR)
- Account deletion

### 🎨 Tattoo Artist / Piercer (independent)
- **Dashboard** with statistics and KPIs
- **Booking request management** (accept, decline, re-propose dates)
- **Calendar** (FullCalendar-like, event management, availability slots)
- **Client management** with full profile (notes, history, photos)
- **Portfolio** with before/after photos
- Integrated **messaging** with clients
- **Consent forms** (PDF upload or digital entry)
- **Product traceability** (inks, jewelry)
- **Regulatory compliance** (administrative documents)
- **Stripe Connect payments** (connected seller account)
- **Subscription** management STARTER / PRO (from back-office)
- **Accounting export** (transactions, monthly summary, Excel format)
- **PDF export** (care sheets, consent forms, traceability records)
- GDPR personal data export
- **2FA authentication** (via Laravel Fortify)
- Advanced settings (working hours, aftercare, pricing)

### 🏢 Studio
- **Dashboard** with consolidated statistics
- **Studio artist management** (token-based invitation, add/remove)
- **Studio planning** (consolidated view of all artist calendars)
- Artist **booking request** tracking
- Centralised **client files**
- **Billing & subscription** STUDIO
- **Stripe Connect** connection (studio level)
- Studio transaction exports
- Internal messaging
- Studio regulatory compliance

### 🎭 Studio Artist
- Dedicated dashboard
- Personal booking request management
- Personal calendar
- Profile and portfolio
- Messaging

### 🔧 Admin (Filament)
- Full administration panel via **Filament 4.9.1**
- User, artist and studio supervision
- Conversation management
- Compliance document oversight
- Booking exports

---

## 🗂️ Project Architecture

```
tattoolib-saas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/              # REST API (artists, bookings, availability, payments…)
│   │   │   ├── Auth/             # Login, logout, password reset
│   │   │   ├── Client/           # Dashboard, bookings, messages, client profile
│   │   │   ├── Studio/           # Dashboard, artists, billing, studio settings
│   │   │   └── Tattooer/         # Dashboard, bookings, clients, compliance, media…
│   │   └── Middleware/
│   ├── Livewire/
│   │   ├── Auth/                 # Role-based registration
│   │   ├── Client/               # Client components
│   │   ├── Studio/               # Studio components
│   │   ├── StudioArtist/         # Studio artist components
│   │   ├── Tattooer/             # Tattooer / piercer components
│   │   ├── Marketplace/          # Marketplace search
│   │   └── Components/           # Shared components (availability calendar…)
│   ├── Models/
│   │   ├── Tattooer.php          # Independent artist (polymorphic via IsArtisan)
│   │   ├── Piercer.php           # Piercer (mirrors Tattooer)
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
│       ├── IsArtisan.php         # Polymorphic trait (Tattooer + Piercer)
│       ├── HasSubscription.php
│       ├── HasStripeConnect.php
│       ├── BookableArtist.php
│       ├── HasCompliance.php
│       └── HasWorkingHours.php
├── database/
│   ├── migrations/               # 40+ migrations organised chronologically
│   └── seeders/
├── resources/
│   └── views/
│       ├── livewire/             # Livewire templates
│       ├── tattooer/             # Artist back-office views
│       ├── studio/               # Studio back-office views
│       ├── client/               # Client area views
│       └── marketplace/          # Public marketplace views
├── routes/
│   └── web.php                   # Routes grouped by profile
└── public/
    └── build/                    # Compiled assets (Vite) + PWA (sw.js, manifest)
```

### Polymorphic artist pattern

Tattooers and piercers share the same controller (`TattooerController`) and Blade views. The `IsArtisan` trait on both models exposes `isPiercer()`, `isTattooer()`, `routePrefix()` and `artisanType()`. Views use `@if ($tattooer->isPiercer())` to adapt the display accordingly.

**Spatie roles**: `tattooer` · `pierceur` · `client` · `studio` · `studio_owner` · `admin`

---

## ⚙️ Requirements

- **PHP** 8.2 or higher (extensions: `pdo_mysql`, `mbstring`, `xml`, `gd`, `zip`, `intl`)
- **Composer** 2.x
- **Node.js** 20.x (LTS) + **npm** or **pnpm**
- **MySQL** 8.0+
- **Stripe CLI** (to listen to webhooks locally)
- **Stripe** account with Stripe Connect enabled
- **Firebase** project (push notifications)
- Mail server (or `log` driver for development)

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone <repo-url> tattoolib-saas
cd tattoolib-saas
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and fill in at minimum: database connection, Stripe, Firebase, mail and VAPID keys.

### 4. Create the database

```sql
CREATE DATABASE tattoolib_saas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

### 6. Install front-end dependencies and compile assets

```bash
npm install
npm run build
```

### 7. Link media storage

```bash
php artisan storage:link
```

### 8. Start the queue worker

```bash
php artisan queue:work --tries=3
```

### 9. Listen to Stripe webhooks locally

```bash
stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe
```

Copy the displayed `whsec_...` value into `STRIPE_WEBHOOK_SECRET` in your `.env`.

### 10. Start the development server

```bash
# All-in-one via Composer script
composer dev
# Or manually:
php artisan serve
npm run dev
```

The application is available at `http://localhost:8000` (or your configured Laragon URL).

---

## 🔑 Key Environment Variables

| Variable | Description | Example |
|---|---|---|
| `APP_NAME` | Application name | `"Ink&Pik"` |
| `APP_URL` | Base URL | `http://tattoolib-saas.test` |
| `APP_KEY` | Laravel encryption key | generated by `artisan key:generate` |
| `DB_DATABASE` | MySQL database name | `tattoolib_saas` |
| `DB_USERNAME` | MySQL user | `root` |
| `DB_PASSWORD` | MySQL password | _(empty locally)_ |
| `STRIPE_KEY` | Stripe public key | `pk_test_...` |
| `STRIPE_SECRET` | Stripe secret key | `sk_test_...` |
| `STRIPE_WEBHOOK_SECRET` | Stripe webhook secret | `whsec_...` |
| `STRIPE_PRICE_ID_STARTER` | Stripe STARTER price ID | `price_...` |
| `STRIPE_PRICE_ID_PRO` | Stripe PRO price ID | `price_...` |
| `STRIPE_PRICE_ID_STUDIO` | Stripe STUDIO price ID | `price_...` |
| `STRIPE_PRICE_ID_STUDIO_EXTRA` | Price ID per extra studio artist | `price_...` |
| `STRIPE_BETA_COUPON_ID` | Beta coupon (discount) | `BETA2026` |
| `FIREBASE_CREDENTIALS` | Path to Firebase Admin JSON | `storage/app/firebase.json` |
| `FIREBASE_API_KEY` | Firebase JS API key | `AIzaSy...` |
| `FIREBASE_PROJECT_ID` | Firebase project ID | `inkandpik-...` |
| `VAPID_PUBLIC_KEY` | VAPID public key (web push) | `BA...` |
| `VAPID_PRIVATE_KEY` | VAPID private key (web push) | `...` |
| `VAPID_SUBJECT` | VAPID contact URI | `mailto:contact@inkandpik.fr` |
| `MAIL_MAILER` | Mail driver | `smtp` / `log` |
| `MAIL_FROM_ADDRESS` | Sender address | `contact@inkandpik.fr` |
| `SESSION_ENCRYPT` | Session encryption | `true` |
| `AVAILABILITY_WINDOW_DAYS` | Availability search window (days) | `90` |
| `DEFAULT_DEPOSIT_RATE` | Default deposit rate (%) | `30` |
| `TATTOOER_RESPONSE_DEADLINE_HOURS` | Artist response deadline (h) | `48` |

---

## 💰 Business Model

Ink&Pik offers monthly subscriptions to body art professionals, billed via **Stripe Billing**.

| Plan | Target | Price |
|---|---|---|
| **STARTER** | Independent tattoo artist / piercer (core features) | **€9.99/month** |
| **PRO** | Tattoo artist / piercer (all features: clients, compliance, exports) | **€29.99/month** |
| **STUDIO** | Studio (multi-artist management) | **€59.99/month** |
| **STUDIO EXTRA** | Additional artist in a studio | **+€24.99/month** |

> A beta discount coupon (`STRIPE_BETA_COUPON_ID`) provides preferential conditions for early adopters.

**Artist payments** (deposits and balances) flow through **Stripe Connect**: the platform collects a configured commission, and funds are transferred directly to the artist's connected Stripe account.

---

## 🗺️ Roadmap

### ✅ Done

- Multi-profile registration (client, tattoo artist, piercer, studio, studio artist)
- Public marketplace with search and filters
- Public artist and studio profiles
- Full tattoo artist / piercer back-office (polymorphic architecture)
- Booking request workflow (submission → acceptance → appointment → closure)
- Deposit and balance payments via Stripe
- Stripe Connect (independent artists and studios)
- Calendar and availability management
- Internal messaging (artist ↔ client ↔ admin)
- Portfolio with before/after photos
- Client files with history
- Consent forms (digital entry and PDF upload)
- Product traceability (inks, jewelry)
- Care sheets and parental consent forms
- Regulatory compliance (administrative documents)
- STARTER / PRO / STUDIO subscriptions via Stripe Billing
- Filament admin panel (user, conversation and compliance supervision)
- Accounting export (Excel) and PDF export
- GDPR data export and account deletion
- 2FA authentication (Fortify)
- REST API (artists, bookings, availability, payments)
- Progressive Web App (PWA) with service worker
- Web push notifications (VAPID) and mobile push (Firebase FCM)
- Full legal pages (ToS, T&Cs, legal notice, privacy policy)
- Beta live on dedicated subdomain

### 🔄 In progress

- Stripe production integration finalisation
- Main domain setup (`inkandpik.fr`)
- Beta user testing and bug fixes

### 📋 Coming up

- Client review module with moderation
- Full transactional email notifications
- Marketplace search improvements (geolocation, advanced filters)
- Optimised mobile interface
- Advanced analytics dashboard (artist + studio)
- Online quote module
- Waitlist management
- Google Calendar / Apple Calendar integration

---

## 📄 License & Status

**Ink&Pik** is a **solo personal project**, currently in beta.

The source code is **proprietary and not open-source**. Any reuse, redistribution or reproduction — even partial — is strictly prohibited without the explicit authorisation of the author.

© 2025–2026 — All rights reserved.
