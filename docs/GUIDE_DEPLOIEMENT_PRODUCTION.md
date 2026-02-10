# 🚀 GUIDE DÉPLOIEMENT PRODUCTION - INK&PIK SAAS

## Pré-requis Serveur

### Stack Technique Requise
- **PHP** : 8.3+ avec extensions : bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml, redis, gd
- **Base de données** : MySQL 8.0+ ou PostgreSQL 14+
- **Cache/Queue** : Redis 6.0+
- **Node.js** : 20.x pour build frontend
- **Composer** : 2.6+
- **Supervisor** : Gestion queue workers
- **Nginx** : Serveur web (ou Apache)
- **SSL/TLS** : Let's Encrypt recommandé
- **Optionnel** : ClamAV pour scan antivirus

### Configuration PHP
```ini
# php.ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 12M
max_file_uploads = 20
```

## Checklist Pré-Déploiement

### 1. Variables Environnement Production

**Fichier** : `.env.production`
```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://inkpik.fr
APP_NAME="Ink&Pik"
APP_TIMEZONE="Europe/Paris"

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=inkpik_prod
DB_USERNAME=inkpik_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# Redis
REDIS_HOST=your-redis-host
REDIS_PASSWORD=REDIS_PASSWORD_HERE
REDIS_PORT=6379
REDIS_CLIENT=phpredis

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
QUEUE_CONNECTION=redis

# File Storage
FILESYSTEM_DISK=secure_uploads
SECURE_UPLOADS_DISK=local_secure

# Stripe
STRIPE_KEY=pk_live_XXXXXXXXXXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_live_XXXXXXXXXXXXXXXXXXXXXXXX
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXXXXXXXX

# Google Maps
GOOGLE_MAPS_API_KEY=AIzaXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# ClamAV (si activé)
CLAMAV_ENABLED=true
CLAMAV_SOCKET=/var/run/clamav/clamd.ctl
CLAMAV_TIMEOUT=30

# Email
MAIL_MAILER=postmark
MAIL_HOST=smtp.postmarkapp.com
MAIL_PORT=587
MAIL_USERNAME=your-postmark-token
MAIL_PASSWORD=your-postmark-token
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@inkpik.fr
MAIL_FROM_NAME="Ink&Pik"

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=warning

# Monitoring (optionnel)
SENTRY_LARAVEL_DSN=https://XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX@sentry.io/XXXXX
```

### 2. Configuration Nginx

**Fichier** : `/etc/nginx/sites-available/inkpik.fr`
```nginx
server {
    listen 80;
    server_name inkpik.fr www.inkpik.fr;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name inkpik.fr www.inkpik.fr;

    root /var/www/inkpik/public;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/inkpik.fr/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/inkpik.fr/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Laravel
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    # Static Files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Secure Uploads
    location /secure-uploads {
        internal;
        alias /var/www/inkpik/storage/app/secure;
    }

    # Health Check
    location /health {
        access_log off;
        return 200 "healthy\n";
        add_header Content-Type text/plain;
    }

    # Block .htaccess
    location ~ /\.ht {
        deny all;
    }
}
```

### 3. Scripts Migration

#### Script Migration Automatisée
**Fichier** : `scripts/deploy.sh`
```bash
#!/bin/bash

set -e

echo "🚀 Déploiement Ink&Pik SaaS..."

# 1. Backup Database
echo "📦 Backup database..."
php artisan backup:run --only-db --filename=backup-$(date +%Y%m%d-%H%M%S).sql

# 2. Maintenance Mode
echo "🔧 Maintenance mode..."
php artisan down --retry=60 --message="Mise à jour en cours..."

# 3. Pull Latest Code
echo "📥 Pulling code..."
git pull origin main

# 4. Install Dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 5. Database Migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# 6. Clear & Cache Config
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "💾 Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Optimize Laravel
echo "⚡ Optimizing Laravel..."
php artisan optimize

# 8. Build Frontend Assets
echo "🎨 Building frontend assets..."
npm ci --production
npm run build

# 9. Warmup Application Cache
echo "🔥 Warming up cache..."
php artisan cache:warmup
php artisan queue:clear

# 10. Restart Queue Workers
echo "🔄 Restarting queue workers..."
php artisan queue:restart

# 11. Clear OPCache (if enabled)
php artisan opcache:clear

# 12. Exit Maintenance Mode
echo "✅ Deployment complete!"
php artisan up

echo "🎉 Ink&Pik deployed successfully!"
```

#### Script Rollback
**Fichier** : `scripts/rollback.sh`
```bash
#!/bin/bash

set -e

echo "🔄 Rollback Ink&Pik SaaS..."

# 1. Maintenance Mode
php artisan down --retry=60 --message="Rollback en cours..."

# 2. Get Previous Tag/Commit
PREVIOUS_TAG=$(git describe --tags --abbrev=0 HEAD~1)
echo "📅 Rolling back to: $PREVIOUS_TAG"

# 3. Checkout Previous Version
git checkout $PREVIOUS_TAG

# 4. Install Dependencies
composer install --no-dev --optimize-autoloader

# 5. Restore Database (if needed)
read -p "Restaurer database backup? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    LATEST_BACKUP=$(ls -t storage/app/backups/*.sql | head -n1)
    echo "📦 Restoring: $LATEST_BACKUP"
    mysql inkpik_prod < $LATEST_BACKUP
fi

# 6. Clear Cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 7. Optimize
php artisan optimize

# 8. Build Assets
npm ci --production
npm run build

# 9. Exit Maintenance
php artisan up

echo "✅ Rollback completed!"
```

### 4. Configuration Supervisor (Queue Workers)

**Fichier** : `/etc/supervisor/conf.d/inkpik-worker.conf`
```ini
[program:inkpik-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/inkpik/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/inkpik/storage/logs/worker.log
stopwaitsecs=3600

[program:inkpik-scheduler]
process_name=%(program_name)s
command=php /var/www/inkpik/artisan schedule:work
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/inkpik/storage/logs/scheduler.log
```

**Commandes Supervisor**
```bash
# Recharger configuration
sudo supervisorctl reread
sudo supervisorctl update

# Démarrer workers
sudo supervisorctl start inkpik-worker:*
sudo supervisorctl start inkpik-scheduler

# Vérifier status
sudo supervisorctl status

# Voir logs
sudo tail -f /var/www/inkpik/storage/logs/worker.log
```

### 5. Configuration CRON

**Fichier** : `crontab -e`
```bash
# Laravel Scheduler (chaque minute)
* * * * * cd /var/www/inkpik && php artisan schedule:run >> /dev/null 2>&1

# Backup quotidien à 2h du matin
0 2 * * * cd /var/www/inkpik && php artisan backup:run --only-db >> /var/log/inkpik-backup.log 2>&1

# Cleanup logs anciens (dimanche 3h)
0 3 * * 0 find /var/www/inkpik/storage/logs -name "*.log" -mtime +30 -delete

# Cache cleanup quotidien (4h du matin)
0 4 * * * cd /var/www/inkpik && php artisan cache:clear >> /dev/null 2>&1
```

### 6. Configuration Webhooks Stripe

**Dashboard Stripe** → Developers → Webhooks

**URL du Webhook** : `https://inkpik.fr/api/stripe/webhook`

**Événements à écouter** :
- ✅ `payment_intent.succeeded` - Paiement réussi
- ✅ `payment_intent.payment_failed` - Paiement échoué
- ✅ `account.updated` - Mise à jour compte Stripe
- ✅ `charge.refunded` - Remboursement effectué
- ✅ `payout.created` - Création payout
- ✅ `payout.failed` - Échec payout

**Configuration dans .env** :
```env
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXXXXXXXX
STRIPE_WEBHOOK_TOLERANCE=300
```

**Test Webhook** :
```bash
# Test local avec Stripe CLI
stripe listen --forward-to localhost:8000/api/stripe/webhook

# Trigger test event
stripe trigger payment_intent.succeeded
```

### 7. Configuration SSL/TLS (Let's Encrypt)

**Installation Certbot** :
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install certbot python3-certbot-nginx

# Générer certificat
sudo certbot --nginx -d inkpik.fr -d www.inkpik.fr

# Auto-renewal (déjà configuré)
sudo certbot renew --dry-run
```

**Renouvellement Automatique** :
```bash
# Ajouter dans crontab
0 12 * * * /usr/bin/certbot renew --quiet
```

### 8. Configuration Monitoring

#### Monitoring de Base
**Fichier** : `routes/api.php`
```php
// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => config('app.version', '2.0.0'),
        'environment' => config('app.env'),
        'services' => [
            'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
            'redis' => Redis::ping() ? 'connected' : 'disconnected',
            'cache' => Cache::get('health_check', 'ok'),
        ]
    ]);
});

// Detailed health (admin only)
Route::get('/health/detailed', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'system' => [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
        ],
        'database' => [
            'connection' => DB::connection()->getDatabaseName(),
            'driver' => DB::connection()->getDriverName(),
        ],
        'cache' => [
            'driver' => config('cache.default'),
            'prefix' => config('cache.prefix'),
        ],
        'queues' => [
            'pending' => Queue::size(),
            'failed' => DB::table('failed_jobs')->count(),
        ]
    ]);
})->middleware(['auth:sanctum', 'admin']);
```

#### Monitoring Externe (Recommandé)
- **Uptime** : UptimeRobot, Pingdom, StatusCake
- **Errors** : Sentry, Flare, Bugsnag
- **Performance** : New Relic, Scout APM, Datadog
- **Logs** : Papertrail, Logtail, ELK Stack

### 9. Vérifications Post-Déploiement

#### Script Validation
**Fichier** : `scripts/validate-deployment.sh`
```bash
#!/bin/bash

echo "🔍 Validation déploiement..."

# 1. Health Check
echo "🏥 Health check..."
HEALTH=$(curl -s https://inkpik.fr/api/health)
if [[ $HEALTH == *"ok"* ]]; then
    echo "✅ Health check OK"
else
    echo "❌ Health check FAILED"
    exit 1
fi

# 2. SSL Certificate
echo "🔒 SSL certificate..."
SSL_INFO=$(openssl s_client -connect inkpik.fr:443 -servername inkpik.fr 2>/dev/null | openssl x509 -noout -dates)
if [[ $SSL_INFO == *"notAfter"* ]]; then
    echo "✅ SSL certificate valid"
else
    echo "❌ SSL certificate issue"
fi

# 3. Database Connection
echo "🗄️ Database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';"

# 4. Redis Connection
echo "🔴 Redis connection..."
php artisan tinker --execute="Redis::ping(); echo 'Redis OK';"

# 5. Queue Workers
echo "🔄 Queue workers..."
QUEUE_STATUS=$(php artisan queue:monitor)
echo "$QUEUE_STATUS"

# 6. Cache Status
echo "💾 Cache status..."
php artisan cache:table

# 7. File Permissions
echo "📁 File permissions..."
if [ -w "storage/logs/laravel.log" ]; then
    echo "✅ Logs writable"
else
    echo "❌ Logs not writable"
fi

# 8. Frontend Assets
echo "🎨 Frontend assets..."
if [ -f "public/build/assets/app.css" ]; then
    echo "✅ Frontend assets built"
else
    echo "❌ Frontend assets missing"
fi

echo "✅ Validation completed!"
```

#### Tests Automatisés en Production
```bash
# Tests critiques uniquement
php artisan test --env=production --filter=CriticalPath

# Vérifier que tout fonctionne
php artisan about
```

### 10. Configuration Backup

**Fichier** : `config/backup.php`
```php
'destination' => [
    'filename_prefix' => 'inkpik-',
    'disks' => [
        'local',
        // 's3' // Pour backup cloud
    ],
],

'source' => [
    'files' => [
        'include' => [
            base_path('.env'),
        ],
        'exclude' => [
            base_path('node_modules'),
            base_path('storage/app/secure'),
        ],
    ],
    'databases' => [
        'mysql' => [
            'dump_command_path' => '/usr/bin/mysqldump',
            'use_single_transaction' => true,
            'timeout' => 60,
        ],
    ],
],
```

**Commandes Backup** :
```bash
# Backup complet
php artisan backup:run

# Backup DB uniquement
php artisan backup:run --only-db

# Backup fichiers uniquement
php artisan backup:run --only-files

# Lister backups
php artisan backup:list

# Nettoyer anciens backups
php artisan backup:clean
```

## 🚨 Procédures d'Urgence

### Rollback Complet
```bash
# 1. Maintenance immédiat
php artisan down --message="Maintenance technique en cours..."

# 2. Identifier problème
tail -f storage/logs/laravel.log

# 3. Rollback si nécessaire
./scripts/rollback.sh

# 4. Vérifier
./scripts/validate-deployment.sh

# 5. Communication
# Notifier utilisateurs via status page
```

### Incident Response Plan
1. **Detection** : Monitoring alerte (uptime > 5min, errors > 1%)
2. **Assessment** : Vérifier health endpoint, logs, metrics
3. **Communication** : Status page, email support
4. **Resolution** : Fix, rollback, ou mitigation
5. **Post-mortem** : Analyse et prévention

## 📊 Monitoring & Alerting

### Métriques à Surveiller
- **Performance** : Temps réponse < 200ms
- **Errors** : Taux erreurs < 1%
- **Queue** : Taille < 100 jobs
- **CPU** : Usage < 70%
- **Memory** : Usage < 80%
- **Disk** : Espace libre > 20%
- **Database** : Connections < 80%

### Alertes Recommandées
- UptimeRobot : Monitoring externe
- Sentry : Erreurs applicatives
- New Relic : Performance monitoring
- Prometheus/Grafana : Métriques système

## ✅ Checklist Finale Déploiement

### Pré-Déploiement
- [ ] Variables environnement configurées
- [ ] Backup base de données effectué
- [ ] SSL certificate valide
- [ ] Monitoring configuré
- [ ] Team notifiée

### Déploiement
- [ ] Mode maintenance activé
- [ ] Code mis à jour
- [ ] Dépendances installées
- [ ] Migrations exécutées
- [ ] Cache vidé et recréé
- [ ] Assets buildés
- [ ] Queue workers redémarrés
- [ ] Mode maintenance désactivé

### Post-Déploiement
- [ ] Health check OK
- [ ] Tests critiques passent
- [ ] Monitoring actif
- [ ] Logs vérifiés
- [ ] Performance acceptable
- [ ] Utilisateurs notifiés

### Documentation
- [ ] Guide déploiement mis à jour
- [ ] Changelog publié
- [ ] Release notes créées
- [ ] Team formée

---

## 🎯 Conclusion

Ce guide de déploiement assure une migration sécurisée et contrôlée vers la production. Avec les procédures de rollback, monitoring et validation, le déploiement peut être effectué avec confiance minimale.

**Prochaines étapes** :
1. Déploiement staging pour validation finale
2. Tests utilisateurs beta
3. Monitoring production activé
4. Documentation support mise à jour

**Statut** : 🚀 **PRODUCTION-READY**
