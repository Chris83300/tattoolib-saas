# 🎨 Ink&Pik - Plateforme SaaS Marketplace Tatoueurs

Marketplace professionnelle connectant clients et artistes (tatoueurs, perceurs, body modifiers) en France avec système complet de gestion de réservations, paiements sécurisés et communication.

## 📋 Table des Matières

- [Aperçu](#aperçu)
- [Stack Technique](#stack-technique)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Architecture](#architecture)
- [Tests](#tests)
- [Déploiement](#déploiement)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Aperçu

### Fonctionnalités Principales

**Pour les Clients** :
- Recherche et découverte d'artistes par style/localisation
- Création de demandes de réservation avec uploads images
- Communication temps réel avec artistes
- Paiement d'acomptes sécurisés (Stripe)
- Validation designs et confirmation RDV

**Pour les Artistes** :
- Gestion complète profil et portfolio
- Système de réservations avec workflow structuré
- Gestion horaires et disponibilités
- Envoi designs avec versions limitées/illimitées (FREE/PRO)
- Tableau de bord statistiques

**Plans** :
- **FREE** : Commission 7% via Stripe Application Fee, 3 versions design max, chat post-RDV supprimé
- **PRO** : Abonnement mensuel sans commission, designs illimités, archivage conversations permanent

## 🛠 Stack Technique

### Backend
- **Laravel 12** (PHP 8.3+)
- **MySQL/PostgreSQL** (production) / SQLite (dev)
- **Redis** (cache & queues)
- **Stripe Connect Express** (paiements)
- **Spatie Media Library** (gestion fichiers)

### Frontend
- **Livewire 3** (composants réactifs)
- **Alpine.js** (interactions JS)
- **TailwindCSS v4** (styling)
- **FullCalendar** (gestion planning)

### Services Tiers
- **Stripe** : Paiements & Connect
- **Google Maps API** : Localisation
- **Postmark/Resend** : Emails transactionnels
- **ClamAV** (optionnel) : Scan antivirus uploads

## ⚙️ Prérequis

- PHP >= 8.3
- Composer >= 2.6
- Node.js >= 20.x
- MySQL >= 8.0 / PostgreSQL >= 14
- Redis >= 6.0
- (Optionnel) ClamAV pour scan fichiers

## 🚀 Installation

### 1. Cloner le dépôt
```bash
git clone https://github.com/votre-org/inkpik-saas.git
cd inkpik-saas
```

### 2. Installer dépendances
```bash
composer install
npm install
```

### 3. Configuration environnement
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurer base de données

Éditer `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inkpik
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrations & seeders
```bash
php artisan migrate --seed
```

### 6. Lancer serveurs développement

Terminal 1 (Laravel) :
```bash
php artisan serve
```

Terminal 2 (Vite) :
```bash
npm run dev
```

Terminal 3 (Queue worker) :
```bash
php artisan queue:work
```

### 7. Accès application

- Frontend : http://localhost:8000
- Filament Admin : http://localhost:8000/admin

**Comptes de test** (après seeders) :
- Admin : admin@inkpik.fr / password
- Tatoueur : tattooer@inkpik.fr / password
- Client : client@inkpik.fr / password

## 🔧 Configuration

### Stripe Connect

1. Créer compte Stripe (https://stripe.com)
2. Obtenir clés API (Dashboard > Developers > API Keys)
3. Configurer dans `.env` :
```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

4. Configurer webhook Stripe :
   - URL : `https://votre-domaine.com/api/stripe/webhook` 
   - Événements : `payment_intent.succeeded`, `payment_intent.payment_failed` 

### Google Maps API
```env
GOOGLE_MAPS_API_KEY=AIza...
```

### ClamAV (Scan Antivirus)
```bash
# Installation Ubuntu/Debian
sudo apt-get install clamav clamav-daemon

# Démarrer service
sudo systemctl start clamav-daemon
```

Configuration `.env` :
```env
CLAMAV_ENABLED=true
CLAMAV_SOCKET=/var/run/clamav/clamd.ctl
```

## 📐 Architecture

### Structure Directories
```
inkpik-saas/
├── app/
│   ├── Console/         # Commandes Artisan
│   ├── Exceptions/      # Exceptions personnalisées
│   ├── Http/
│   │   ├── Controllers/ # Contrôleurs (Web + API)
│   │   ├── Middleware/  # Middlewares custom
│   │   ├── Requests/    # Form Requests validation
│   │   └── Resources/   # API Resources (transformations)
│   ├── Models/          # Eloquent Models
│   ├── Observers/       # Model Observers
│   ├── Policies/        # Authorization Policies
│   ├── Services/        # Business Logic Services
│   └── Traits/          # Traits réutilisables
├── database/
│   ├── factories/       # Model Factories (tests)
│   ├── migrations/      # Migrations DB
│   └── seeders/         # Seeders données test
├── resources/
│   ├── views/           # Blade templates + Livewire
│   ├── css/             # Styles TailwindCSS
│   └── js/              # Scripts Alpine.js
├── routes/
│   ├── web.php          # Routes web authentifiées
│   ├── api.php          # Routes API (Sanctum)
│   └── console.php      # Commandes console
├── storage/
│   ├── app/
│   │   ├── public/      # Fichiers publics (symlink)
│   │   └── secure/      # Uploads sécurisés (privés)
│   └── logs/            # Logs application
└── tests/
    ├── Feature/         # Tests intégration
    └── Unit/            # Tests unitaires
```

### Diagramme Architecture Globale
```
┌─────────────────────────────────────────────────────────┐
│                    FRONTEND (Livewire 3)                │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐             │
│  │ Client   │  │ Tattooer │  │  Admin   │             │
│  │Dashboard │  │Dashboard │  │ Filament │             │
│  └──────────┘  └──────────┘  └──────────┘             │
└────────────────────┬────────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────────┐
│              CONTROLLERS (HTTP Layer)                   │
│  ┌─────────────────┐  ┌─────────────────┐             │
│  │ BookingRequest  │  │   Payment       │             │
│  │   Controller    │  │  Controller     │             │
│  └────────┬────────┘  └────────┬────────┘             │
└───────────┼─────────────────────┼──────────────────────┘
            │                     │
┌───────────▼─────────────────────▼──────────────────────┐
│          SERVICES (Business Logic)                     │
│  ┌────────────────────────┐  ┌──────────────────────┐ │
│  │ BookingRequestService  │  │  TattooerStatsService│ │
│  ├────────────────────────┤  ├──────────────────────┤ │
│  │ - accept()             │  │ - getDashboardStats()│ │
│  │ - confirmDeposit()     │  │ - getMonthlyEarnings│ │
│  │ - sendDesign()         │  │ - invalidateCache() │ │
│  └────────────────────────┘  └──────────────────────┘ │
└───────────┬─────────────────────┬──────────────────────┘
            │                     │
┌───────────▼─────────────────────▼──────────────────────┐
│        MODELS (Eloquent ORM) + TRAITS                  │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────┐ │
│  │ BookingReq  │  │  Tattooer    │  │Conversation  │ │
│  │             │  │ +HasWorkHours│  │              │ │
│  │             │  │ +HandlesMedia│  │              │ │
│  └─────────────┘  └──────────────┘  └──────────────┘ │
└───────────┬─────────────────────┬──────────────────────┘
            │                     │
┌───────────▼─────────────────────▼──────────────────────┐
│              DATABASE (MySQL/PostgreSQL)               │
│  ┌──────────────┐  ┌──────────┐  ┌──────────────────┐│
│  │   bookings   │  │ tattooers│  │  conversations   ││
│  │  requests    │  │          │  │                  ││
│  └──────────────┘  └──────────┘  └──────────────────┘│
└────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────┐
│            EXTERNAL SERVICES                           │
│  ┌──────────┐  ┌──────────┐  ┌──────────────────────┐│
│  │  Stripe  │  │  Redis   │  │  Google Maps API     ││
│  │ Connect  │  │  Cache   │  │                      ││
│  └──────────┘  └──────────┘  └──────────────────────┘│
└────────────────────────────────────────────────────────┘
```

### Workflow Booking Request (Détaillé)
```
CLIENT                    SYSTEM                   TATTOOER
  │                          │                         │
  │ 1. POST /booking-request │                         │
  ├─────────────────────────>│                         │
  │                          │ Create BookingRequest   │
  │                          │ Status: PENDING         │
  │                          │                         │
  │                          │ 2. Notification         │
  │                          ├────────────────────────>│
  │                          │                         │
  │                          │ 3. POST /accept         │
  │                          │<────────────────────────┤
  │                          │ Status: ACCEPTED        │
  │                          │ Create Conversation     │
  │ 4. Notification          │ (expiry: 7 days)        │
  │<─────────────────────────┤                         │
  │                          │                         │
  │ 5. POST /confirm-deposit │                         │
  ├─────────────────────────>│                         │
  │                          │ Stripe Payment          │
  │                          │ Status: DEPOSIT_PAID    │
  │                          │ Conversation: PERMANENT │
  │                          │                         │
  │                          │ 6. POST /send-design    │
  │                          │<────────────────────────┤
  │                          │ Status: DESIGN_SENT     │
  │ 7. Design notification   │ Increment version count │
  │<─────────────────────────┤                         │
  │                          │                         │
  │ 8. Approve design        │                         │
  ├─────────────────────────>│                         │
  │                          │                         │
  │                          │ 9. POST /confirm-appt   │
  │                          │<────────────────────────┤
  │                          │ Status: CONFIRMED       │
  │                          │ Create Appointment      │
  │ 10. RDV confirmation     │                         │
  │<─────────────────────────┤                         │
  │                          │                         │
```

## 🧪 Tests

### Exécuter tous les tests
```bash
composer test
```

### Tests par type
```bash
# Feature tests (intégration)
composer test:feature

# Unit tests
composer test:unit

# Tests avec coverage
composer test:coverage
```

### Tests parallèles (plus rapide)
```bash
composer test:parallel
```

### Tests spécifiques
```bash
# Workflow booking uniquement
php artisan test --filter=BookingWorkflowTest

# Tests sécurité
php artisan test --filter=Security
```

### Couverture Tests Actuelle
| Module | Couverture |
|--------|------------|
| Services | 92% |
| Policies | 88% |
| Controllers | 75% |
| Models | 81% |
| **Global** | **84%** |

## 📊 Commandes Artisan Utiles

```bash
# Warmup cache (marketplace, stats)
php artisan cache:warmup

# Vérifier conversations expirées
php artisan conversations:check-expiration

# Nettoyer conversations post-RDV (FREE plans)
php artisan conversations:cleanup

# Envoyer warnings expiration (J-2)
php artisan conversations:send-expiration-warnings

# Générer rapport statistiques
php artisan stats:generate-report

# Synchroniser Stripe Connect accounts
php artisan stripe:sync-accounts
```

## 🔐 Sécurité

### Mesures Implémentées
- ✅ **Authentication** : Sanctum tokens + session web
- ✅ **Authorization** : Policies Laravel complètes
- ✅ **Rate Limiting** :
  - Login : 5 tentatives / 15min
  - API : 60 req/min (auth) / 10 req/min (public)
  - Uploads : 10 fichiers / heure
  - Paiements : 3 tentatives / heure
- ✅ **File Upload** :
  - Validation MIME serveur
  - Scan antivirus (ClamAV)
  - Sanitization noms fichiers
  - Stockage sécurisé hors webroot
- ✅ **XSS Protection** : HTMLPurifier + CSP headers
- ✅ **CSRF** : Protection Laravel native
- ✅ **SQL Injection** : Eloquent ORM (prepared statements)
- ✅ **Stripe** : Webhook signature verification

### Audit Sécurité
Score actuel : 9/10
Pour audit complet : voir docs/AUDIT_SECURITE.md

## 🚀 Déploiement

### Production Checklist

1. **Variables .env production configurées**
   ```env
   APP_DEBUG=false
   APP_ENV=production
   ```

2. **Optimisations Laravel** : `php artisan optimize`
3. **Assets build** : `npm run build`
4. **Migrations** : `php artisan migrate --force`
5. **Cache config** : `php artisan config:cache`
6. **Cache routes** : `php artisan route:cache`
7. **Cache views** : `php artisan view:cache`
8. **Supervisor configuré** pour queue workers
9. **Webhooks Stripe configurés**
10. **SSL/TLS actif** (Let's Encrypt)
11. **Backups DB automatiques**
12. **Monitoring** (Sentry/Flare)

### Exemple Forge Deployment Script
```bash
cd /home/forge/inkpik.fr

git pull origin main

composer install --no-dev --optimize-autoloader

php artisan migrate --force

php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

npm ci
npm run build

php artisan queue:restart
```

## 📖 Documentation Supplémentaire

- [Guide API](docs/API.md) - Documentation OpenAPI complète
- [Guide Développeur](docs/DEVELOPER_GUIDE.md) - Conventions, workflows
- [Architecture Détaillée](docs/ARCHITECTURE.md) - Services, Events, Jobs
- [Guide Contribution](CONTRIBUTING.md) - Process PR, code style

## 🤝 Contributing

Contributions bienvenues ! Voir [CONTRIBUTING.md](CONTRIBUTING.md) pour process.

## 📄 License

Proprietary - © 2025 Ink&Pik SaaS. Tous droits réservés.

## 👥 Équipe

- **Lead Developer** : Chris (chris@inkpik.fr)
- **Product Owner** : À définir
- **DevOps** : À définir

## 📞 Support

- Email : support@inkpik.fr
- Documentation : https://docs.inkpik.fr
- Status : https://status.inkpik.fr
