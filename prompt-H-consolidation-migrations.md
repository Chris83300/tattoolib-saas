# 🗄️ PROMPT H — CONSOLIDATION DES MIGRATIONS
# Pour Claude Code — Réduire 142 fichiers migrations en migrations propres et organisées
# ⚠️ BACKUP OBLIGATOIRE AVANT EXÉCUTION

## CONTEXTE

Le dossier `database/migrations` contient 142 fichiers accumulés au fil du développement (créations de tables + dizaines de modifications/ajouts de colonnes). On veut les consolider en migrations propres AVANT de faire un `php artisan migrate:fresh --seed`.

**Objectif** : Passer de ~142 fichiers à ~20-30 migrations propres, organisées par domaine fonctionnel, contenant la structure FINALE de chaque table.

Stack : Laravel 12, MySQL.

---

## PHASE 0 — AUDIT & BACKUP

```bash
echo "=== AUDIT MIGRATIONS ==="

# 0A. Nombre de fichiers
ls database/migrations/*.php | wc -l

# 0B. Lister TOUS les fichiers pour comprendre la structure
ls -1 database/migrations/*.php | sort

# 0C. Tables existantes en base
php artisan tinker --execute="
  \$tables = DB::select('SHOW TABLES');
  \$key = 'Tables_in_' . env('DB_DATABASE'); 
  foreach(\$tables as \$t) {
    echo \$t->\$key . PHP_EOL;
  }
" 2>/dev/null

# 0D. Structure ACTUELLE de CHAQUE table (colonnes finales)
php artisan tinker --execute="
  \$tables = DB::select('SHOW TABLES');
  \$key = 'Tables_in_' . env('DB_DATABASE');
  foreach(\$tables as \$t) {
    \$name = \$t->\$key;
    if (in_array(\$name, ['migrations'])) continue;
    \$cols = DB::select('SHOW CREATE TABLE ' . \$name);
    echo '=== ' . \$name . ' ===' . PHP_EOL;
    echo \$cols[0]->{'Create Table'} . PHP_EOL . PHP_EOL;
  }
" 2>/dev/null > /tmp/current_schema.sql
echo "Schema complet exporté dans /tmp/current_schema.sql"

# 0E. Seeders existants
ls database/seeders/*.php | sort

# 0F. Factories existantes
ls database/factories/*.php 2>/dev/null | sort

# 0G. Backup du dossier migrations actuel
cp -r database/migrations database/migrations_backup_$(date +%Y%m%d)
echo "Backup créé : database/migrations_backup_$(date +%Y%m%d)"

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats, en particulier :**
1. La liste des 142 fichiers
2. Le `SHOW CREATE TABLE` de chaque table (dans /tmp/current_schema.sql)

**C'est CRITIQUE : on a besoin de la structure finale exacte de chaque table pour recréer les migrations propres.**

---

## PHASE 1 — EXPORTER LE SCHEMA ACTUEL

Avant de toucher aux migrations, capturer l'état EXACT de la base :

```bash
# Option A : schema:dump (Laravel natif — crée un fichier SQL)
php artisan schema:dump

# Vérifier le dump
ls database/schema/*.sql 2>/dev/null
cat database/schema/*.sql 2>/dev/null | head -20

# Option B : mysqldump (plus fiable)
mysqldump -u root --no-data $(grep DB_DATABASE .env | cut -d= -f2) > database/schema_backup.sql
echo "Schema SQL exporté dans database/schema_backup.sql"
```

```bash
git add -A && git commit -m "chore(migrations): backup schema actuel avant consolidation"
```

---

## PHASE 2 — ANALYSER ET REGROUPER

À partir de la liste des 142 fichiers et du schema actuel, regrouper les migrations par domaine fonctionnel.

### Plan de consolidation cible

Créer les migrations CONSOLIDÉES suivantes (ordre chronologique respecté via le timestamp) :

```
01 — 0001_01_01_000000_create_users_table.php          (users + password_resets + sessions)
02 — 0001_01_01_000001_create_cache_table.php           (cache + cache_locks)
03 — 0001_01_01_000002_create_jobs_table.php            (jobs + job_batches + failed_jobs)
04 — 2025_01_01_000001_create_tattooers_table.php       (tattooers — structure FINALE)
05 — 2025_01_01_000002_create_piercers_table.php        (piercers — structure FINALE)
06 — 2025_01_01_000003_create_studios_table.php         (studios — structure FINALE)
07 — 2025_01_01_000004_create_clients_table.php         (clients — structure FINALE)
08 — 2025_01_01_000005_create_booking_requests_table.php (booking_requests — structure FINALE)
09 — 2025_01_01_000006_create_conversations_tables.php  (conversations + conversation_user + messages)
10 — 2025_01_01_000007_create_reviews_table.php         (reviews)
11 — 2025_01_01_000008_create_notifications_table.php   (notifications)
12 — 2025_01_01_000009_create_subscriptions_tables.php  (subscriptions + subscription_items + studio_subscriptions)
13 — 2025_01_01_000010_create_traceability_tables.php   (traceability_records + traceability_needles + traceability_inks)
14 — 2025_01_01_000011_create_consent_forms_tables.php  (client_consent_forms + parental_consent_forms + client_care_sheets)
15 — 2025_01_01_000012_create_inventory_tables.php      (inventory_items + inventory_movements + purchase_orders + purchase_order_items)
16 — 2025_01_01_000013_create_studio_accounting_table.php (studio_accounting_entries)
17 — 2025_01_01_000014_create_complaints_table.php      (complaints)
18 — 2025_01_01_000015_create_media_table.php           (media — Spatie)
19 — 2025_01_01_000016_create_permissions_tables.php    (permissions + roles + model_has_* — Spatie)
20 — 2025_01_01_000017_create_personal_access_tokens.php (personal_access_tokens — Sanctum)
21 — 2025_01_01_000018_create_misc_tables.php           (toute table restante non catégorisée)
```

IMPORTANT :
- Le nombre final et les regroupements doivent être adaptés aux VRAIES tables trouvées en Phase 0
- Chaque migration contient la structure FINALE (toutes les colonnes, index, foreign keys)
- Les migrations Laravel par défaut (users, cache, jobs) peuvent être gardées telles quelles SI elles correspondent au schema final
- Les tables ajoutées par des packages (Spatie Media, Spatie Permissions, Cashier) doivent utiliser les migrations du package OU être consolidées

---

## PHASE 3 — CRÉER LES MIGRATIONS CONSOLIDÉES

### Méthodologie pour CHAQUE table

1. Lire le `SHOW CREATE TABLE` de la table depuis /tmp/current_schema.sql
2. Convertir en migration Laravel `Schema::create()`
3. Inclure TOUTES les colonnes finales (pas de `Schema::table()` pour modifier)
4. Inclure les index et foreign keys

### Exemple de conversion

Si le `SHOW CREATE TABLE tattooers` donne :
```sql
CREATE TABLE `tattooers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `studio_id` bigint unsigned DEFAULT NULL,
  `siret` varchar(14) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_subscribed` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `plan` varchar(255) NOT NULL DEFAULT 'starter',
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ars_declaration_number` varchar(255) DEFAULT NULL,
  `hygiene_certificate_number` varchar(255) DEFAULT NULL,
  `hygiene_certificate_expires_at` date DEFAULT NULL,
  `styles` json DEFAULT NULL,
  `min_price` decimal(8,2) DEFAULT NULL,
  `deposit_percentage` int DEFAULT '30',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tattooers_user_id_foreign` (`user_id`),
  KEY `tattooers_studio_id_foreign` (`studio_id`),
  CONSTRAINT `tattooers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tattooers_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

La migration consolidée sera :
```php
Schema::create('tattooers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('studio_id')->nullable()->constrained()->nullOnDelete();
    $table->string('siret', 14)->nullable();
    $table->text('bio')->nullable();
    $table->string('city')->nullable();
    $table->text('address')->nullable();
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->boolean('is_subscribed')->default(false);
    $table->boolean('is_active')->default(true);
    $table->boolean('is_blocked')->default(false);
    $table->string('plan')->default('starter');
    $table->timestamp('trial_ends_at')->nullable();
    $table->string('ars_declaration_number')->nullable();
    $table->string('hygiene_certificate_number')->nullable();
    $table->date('hygiene_certificate_expires_at')->nullable();
    $table->json('styles')->nullable();
    $table->decimal('min_price', 8, 2)->nullable();
    $table->integer('deposit_percentage')->default(30);
    $table->timestamps();
});
```

### Procédure pour CHAQUE migration consolidée

```bash
# Pour chaque table dans /tmp/current_schema.sql :
# 1. Lire le SHOW CREATE TABLE
# 2. Créer le fichier migration avec Schema::create() 
# 3. Convertir CHAQUE colonne SQL en méthode Blueprint Laravel
# 4. Convertir les foreign keys en ->constrained()
# 5. Conserver les index
```

### Tables à traiter avec attention spéciale

**Tables Cashier** (subscriptions, subscription_items) :
```bash
# Vérifier si Cashier a une commande pour publier ses migrations
php artisan vendor:publish --tag="cashier-migrations" --force 2>/dev/null
# Si oui, utiliser les migrations Cashier plutôt que recréer manuellement
```

**Tables Spatie Media Library** :
```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations" --force 2>/dev/null
```

**Tables Spatie Permissions** :
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="permission-migrations" --force 2>/dev/null
```

**Tables Fortify** (si 2FA) :
```bash
php artisan vendor:publish --tag="fortify-migrations" --force 2>/dev/null
```

Pour ces packages, on peut soit :
- Garder leurs migrations publiées (recommandé)
- Les fusionner dans nos migrations consolidées (risque de divergence si package mis à jour)

**Recommandation** : Garder les migrations des packages séparément et ne consolider que les tables métier d'Ink&Pik.

---

## PHASE 4 — REMPLACER LES MIGRATIONS

```bash
# 1. Sauvegarder l'ancien dossier (déjà fait en Phase 0)
ls database/migrations_backup_* 2>/dev/null && echo "Backup OK"

# 2. Supprimer TOUTES les anciennes migrations
rm database/migrations/*.php

# 3. Copier les nouvelles migrations consolidées
# (elles ont été créées en Phase 3)
ls database/migrations/*.php | wc -l
echo "Nouvelles migrations"

# 4. Vérifier la cohérence — les timestamps doivent être ordonnés
ls -1 database/migrations/*.php | sort
```

### Ordre des foreign keys

L'ordre des migrations est CRITIQUE pour les foreign keys. Les tables référencées doivent être créées AVANT les tables qui les référencent :

```
1. users (aucune dépendance)
2. cache, jobs (aucune dépendance)
3. studios (dépend de users)
4. tattooers (dépend de users + studios)
5. piercers (dépend de users + studios)
6. clients (dépend de users)
7. booking_requests (dépend de clients + tattooers/piercers polymorphique)
8. conversations (dépend de users)
9. reviews (dépend de users + polymorphique)
10. subscriptions (dépend de users — Cashier)
11. traceability (dépend de clients + polymorphique)
12. consent_forms (dépend de clients + polymorphique)
13. ... etc
```

Si une relation est polymorphique (`bookable_type`/`bookable_id`), PAS de foreign key SQL — juste les colonnes + un index.

```bash
git add -A && git commit -m "chore(migrations): consolidation 142 → ~25 migrations propres"
```

---

## PHASE 5 — TEST : FRESH + SEED

```bash
# 1. Vider la base et relancer les migrations
php artisan migrate:fresh --seed

# 2. Vérifier que toutes les tables sont créées
php artisan tinker --execute="
  \$tables = DB::select('SHOW TABLES');
  \$key = 'Tables_in_' . env('DB_DATABASE');
  foreach(\$tables as \$t) {
    echo \$t->\$key . PHP_EOL;
  }
"

# 3. Compter les tables
php artisan tinker --execute="
  \$count = count(DB::select('SHOW TABLES'));
  echo \$count . ' tables créées' . PHP_EOL;
"

# 4. Vérifier que les seeders ont fonctionné
php artisan tinker --execute="
  echo 'Users: ' . \App\Models\User::count() . PHP_EOL;
  echo 'Tattooers: ' . \App\Models\Tattooer::count() . PHP_EOL;
  echo 'Piercers: ' . \App\Models\Piercer::count() . PHP_EOL;
  echo 'Studios: ' . \App\Models\Studio::count() . PHP_EOL;
  echo 'Clients: ' . \App\Models\Client::count() . PHP_EOL;
  echo 'BookingRequests: ' . \App\Models\BookingRequest::count() . PHP_EOL;
"

# 5. Vérifier qu'il n'y a pas d'erreur de foreign key
php artisan migrate:status | head -30
```

### Résoudre les erreurs courantes

**Erreur foreign key** : `Cannot add foreign key constraint`
→ Vérifier l'ordre des migrations (la table référencée doit exister avant)
→ Vérifier que les types de colonnes correspondent (bigint unsigned ↔ bigint unsigned)

**Erreur colonne manquante dans un seeder** :
→ Comparer le SHOW CREATE TABLE de /tmp/current_schema.sql avec la nouvelle migration
→ S'assurer que TOUTES les colonnes sont présentes

**Erreur Spatie/Package** :
→ Re-publier les migrations du package : `php artisan vendor:publish --tag=xxx-migrations`

```bash
git add -A && git commit -m "chore(migrations): fresh --seed validé avec migrations consolidées"
```

---

## PHASE 6 — VÉRIFICATION COMPLÈTE

```bash
echo "=== VÉRIFICATION CONSOLIDATION ==="

# V1. Nombre de fichiers
OLD_COUNT=142
NEW_COUNT=$(ls database/migrations/*.php | wc -l)
echo "Avant: $OLD_COUNT migrations"
echo "Après: $NEW_COUNT migrations"
echo "Réduction: $(( OLD_COUNT - NEW_COUNT )) fichiers supprimés"

# V2. Fresh + seed OK
php artisan migrate:fresh --seed 2>&1 | tail -5

# V3. Toutes les tables présentes
php artisan tinker --execute="
  \$expected = ['users', 'tattooers', 'piercers', 'studios', 'clients', 'booking_requests', 
    'conversations', 'messages', 'reviews', 'subscriptions', 'traceability_records',
    'client_consent_forms', 'parental_consent_forms', 'client_care_sheets',
    'media', 'roles', 'permissions', 'notifications', 'personal_access_tokens'];
  foreach(\$expected as \$t) {
    echo \$t . ': ' . (Schema::hasTable(\$t) ? '✓' : '✗ MANQUANTE') . PHP_EOL;
  }
"

# V4. Colonnes critiques présentes
php artisan tinker --execute="
  // Vérifier les colonnes ajoutées par les prompts récents
  \$checks = [
    ['users', 'is_beta_tester'],
    ['users', 'two_factor_secret'],
    ['users', 'cgu_accepted_at'],
    ['tattooers', 'is_blocked'],
    ['tattooers', 'trial_ends_at'],
    ['tattooers', 'plan'],
    ['piercers', 'is_blocked'],
    ['piercers', 'trial_ends_at'],
    ['studios', 'is_subscribed'],
    ['studios', 'trial_ends_at'],
    ['booking_requests', 'deposit_paid_at'],
    ['booking_requests', 'total_deposit_amount'],
  ];
  foreach(\$checks as [\$table, \$col]) {
    echo \$table . '.' . \$col . ': ' . (Schema::hasColumn(\$table, \$col) ? '✓' : '✗') . PHP_EOL;
  }
"

# V5. Routes fonctionnelles
php artisan route:list 2>&1 | head -5
echo "Routes OK si pas d'erreur"

# V6. Backup conservé
ls database/migrations_backup_* 2>/dev/null && echo "Backup migrations conservé ✓"

echo "=== CONSOLIDATION TERMINÉE ==="
```

---

## ⚠️ RÈGLES CRITIQUES

1. **BACKUP AVANT TOUT** — `cp -r database/migrations database/migrations_backup_YYYYMMDD`
2. **SHOW CREATE TABLE = source de vérité** — pas les fichiers migrations (qui peuvent être incohérents entre eux)
3. **Ordre des foreign keys** — tables référencées AVANT tables qui référencent
4. **Polymorphique = pas de foreign key SQL** — juste colonnes + index
5. **Packages (Spatie, Cashier, Fortify)** — garder leurs migrations séparément OU les intégrer avec prudence
6. **NE PAS modifier les tables Laravel par défaut** (users base, cache, jobs, sessions) sauf pour les colonnes ajoutées
7. **Tester avec `migrate:fresh --seed`** — c'est le test ultime
8. **Garder le backup** des anciennes migrations au cas où
9. **Chaque migration consolidée doit avoir un `down()`** avec `Schema::dropIfExists()`
10. **Les seeders doivent fonctionner** avec la nouvelle structure — vérifier après le fresh
