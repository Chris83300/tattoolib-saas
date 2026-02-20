# 🔍 AUDIT COMPLET POST-IMPLÉMENTATION — INK&PIK SaaS
# Pour Claude Code (terminal) — Exécution séquentielle
# Date : 20/02/2026

## CONTEXTE CRITIQUE

Tu es dans un projet Laravel 12 "Ink&Pik" — SaaS marketplace pour tatoueurs/pierceurs/studios en France.
Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, MySQL, Stripe Connect + Billing, Spatie Permission, Laravel Cashier, Spatie Media Library, Filament v4.5.

**16 prompts Cascade ont été exécutés** sur ce projet pour ajouter des fonctionnalités et corriger des bugs.
Cascade a tendance à : casser des routes existantes, ajouter du code sans vérifier l'existant,
dupliquer des méthodes/vues, mal gérer les Enums Laravel (comparer string vs BackedEnum),
et ne pas nettoyer ses interventions.

**Résultat** : plusieurs routes cassées, incohérences possibles, code zombie.

### Ce qui a été implémenté par les 16 prompts :
1. Fix badge messages non-lus (sidebar tattooer)
2. Auto-expiration demandes sans acompte (délai dépassé)
3. Boutons Terminé / No-show sur les demandes (FONCTIONNE — vérifié en BDD)
4. Limite portfolio 15 images plan Free
5. Vérification commission 7% Stripe Application Fee (CONFIRMÉ OK)
6. Système notifications complet (22 notifications existaient déjà — CONFIRMÉ OK)
7. Fiche de soins aftercare dans settings tattooer
8. Sidebar mobile client (remplacement menu burger)
9. Indicateur demandes en cours + accordéon mobile client
10. Fix images before/after portfolio (object-contain)
11. Vérification no_show_count incrémenté (FONCTIONNE)
12. Système avis (Review) + réclamations (Complaint) — BOUTON Réclamation INVISIBLE et Avis INVISIBLE sur profil Tattooer.
13. Implémentation Pierceur (Piercer) comme type de profil Tattooer
14. Implémentation Studio comme type de profil Tattooer mais avec studio artiste relié et dashboard Filament (crée des Users artistes, comptant 39.99€/mois par artiste créer (1 Artiste inclue dans l'offre studio))
15. Implémentation Studio Artiste comme type de profil Tattooer mais relié au studio affilié.
16. Dashboard Filament pour Admin qui à une visibilité global sur tous le SaaS (sauf les fiche client, messages et autre info privé)

### Problèmes connus :
- Le bouton "Réclamation" n'apparaît nulle part côté client
- Routes potentiellement cassées après les 16 interventions Cascade
- Code possiblement dupliqué dans les vues/controllers

## CONSIGNES

- **NE MODIFIE RIEN** pendant les phases d'audit (Phases 1 à 6)
- Génère un rapport structuré avec priorités P0/P1/P2
- Pour chaque problème : fichier, ligne, description, fix recommandé
- **Phase 7** : applique les corrections UNIQUEMENT après validation du rapport
- **Ignore** : `node_modules/`, `vendor/`, `public/build/`, `storage/`, `.env*`, IDE configs

---

## PHASE 1 — ROUTES CASSÉES (P0)

C'est le problème le plus urgent. Cascade a potentiellement cassé des routes en les dupliquant ou en modifiant routes/web.php.

```bash
# 1A. Vérifier que TOUTES les routes compilent sans erreur
php artisan route:list 2>&1 | head -5
# Si "Unable to prepare route" ou "Target class does not exist" → P0

# 1B. Lister toutes les routes et chercher les doublons
php artisan route:list --columns=method,uri,name,action 2>&1 | tee /tmp/routes.txt
# Chercher les doublons (même URI, méthodes différentes ou même nom)
awk '{print $1, $2}' /tmp/routes.txt | sort | uniq -d

# 1C. Vérifier les routes nommées dupliquées
php artisan route:list --columns=name 2>&1 | sort | uniq -d | grep -v "^$"

# 1D. Routes qui pointent vers des controllers/méthodes inexistants
php artisan route:list --columns=action 2>&1 | grep -v "Closure" | while read -r action; do
    controller=$(echo "$action" | cut -d'@' -f1)
    method=$(echo "$action" | cut -d'@' -f2)
    if [ -n "$controller" ] && [ -n "$method" ]; then
        file=$(echo "$controller" | sed 's|\\|/|g' | sed 's|^App/|app/|').php
        if [ -f "$file" ]; then
            grep -q "function $method" "$file" || echo "MÉTHODE MANQUANTE: $action"
        else
            echo "CONTROLLER MANQUANT: $file ($action)"
        fi
    fi
done 2>/dev/null | head -30

# 1E. Vérifier les routes critiques qui doivent exister
declare -a CRITICAL_ROUTES=(
    "tattooer.dashboard"
    "tattooer.clients"
    "tattooer.client.show"
    "tattooer.messages"
    "tattooer.request.show"
    "tattooer.request.complete"
    "tattooer.request.no-show"
    "tattooer.portfolio"
    "tattooer.settings"
    "tattooer.traceability.store"
    "tattooer.clients.consent.upload"
    "tattooer.clients.consent.store-digital"
    "tattooer.clients.traceability.store"
    "client.dashboard"
    "client.messages"
    "client.reviews"
    "client.reviews.create"
    "client.complaints"
    "client.complaints.create"
)
for route in "${CRITICAL_ROUTES[@]}"; do
    php artisan route:list --name="$route" 2>&1 | grep -q "$route" \
        && echo "✅ $route" \
        || echo "❌ MANQUANTE: $route"
done

# 1F. Chercher les imports use manquants dans routes/web.php
head -40 routes/web.php
grep -n "use " routes/web.php
# Vérifier que chaque Controller référencé est bien importé
grep -oP '\b\w+Controller\b' routes/web.php | sort -u | while read -r ctrl; do
    grep -q "use.*$ctrl" routes/web.php || echo "❌ IMPORT MANQUANT: $ctrl"
done

# 1G. Fichier routes/web.php — structure complète
wc -l routes/web.php
# Si > 500 lignes : risque de doublons après 16 prompts
```

---

## PHASE 2 — DIAGNOSTIC POST-PROMPTS (P0)

Vérifier que chaque implémentation des 16 prompts est cohérente.

```bash
# ═══ P0-1 : Badge messages non-lus ═══
echo "=== P0-1 BADGE MESSAGES ==="
grep -rn "unread\|read_at\|unreadMessages\|unread_count" resources/views/layouts/tattooer.blade.php
# Doit exister : un count filtrant sur read_at IS NULL

# ═══ P0-2 : Auto-expiration acompte ═══
echo "=== P0-2 EXPIRATION ACOMPTE ==="
grep -rn "expire\|isDepositExpired\|ExpireUnpaid\|bookings:expire" app/Console/ app/Models/BookingRequest.php
php artisan schedule:list 2>/dev/null | grep -i "expire"

# ═══ P0-3 : Boutons Terminé / No-show ═══
echo "=== P0-3 BOUTONS TERMINÉ/NO-SHOW ==="
php artisan route:list --name="complete" 2>&1; php artisan route:list --name="no-show" 2>&1
grep -rn "markComplete\|markNoShow\|isCompleted\|isNoShow" app/Http/Controllers/ app/Models/BookingRequest.php
grep -rn "Terminé\|No-show\|no.show\|complete" resources/views/tattooer/ | grep -i "button\|btn\|submit" | head -10

# ═══ P0-4 : Limite portfolio 15 images ═══
echo "=== P0-4 LIMITE PORTFOLIO ==="
grep -rn "15\|max_images\|limit\|portfolio.*count\|isPro" app/Http/Controllers/Tattooer/PortfolioController.php app/Http/Controllers/PortfolioController.php 2>/dev/null | head -10

# ═══ P0-5 : Commission 7% (confirmé OK) ═══
echo "=== P0-5 COMMISSION ==="
grep -rn "application_fee\|calculateCommission\|commission" app/Http/Controllers/ app/Services/ app/Models/ --include="*.php" | head -10

# ═══ P1-6 : Notifications ═══
echo "=== P1-6 NOTIFICATIONS ==="
ls app/Notifications/ 2>/dev/null | wc -l
php artisan schedule:list 2>/dev/null | grep -i "notif\|remind\|care\|aftercare"

# ═══ P1-7 : Fiche de soins aftercare ═══
echo "=== P1-7 AFTERCARE ==="
grep -rn "aftercare\|care_sheet\|soins" app/Models/Tattooer.php database/migrations/ | head -10
grep -rn "aftercare\|soins\|Soins" resources/views/tattooer/settings* | head -5

# ═══ P1-8 : Sidebar mobile client ═══
echo "=== P1-8 SIDEBAR CLIENT ==="
wc -l resources/views/layouts/client.blade.php
grep -c "fixed.*bottom\|bottom.*nav\|mobile.*nav" resources/views/layouts/client.blade.php
# Si 0 : la sidebar mobile n'a pas été implémentée

# ═══ P1-9 : Indicateur demandes en cours ═══
echo "=== P1-9 INDICATEUR DEMANDES ==="
grep -rn "activeRequests\|demande.*en.*cours\|animate-ping\|accordion\|x-collapse" resources/views/client/dashboard* resources/views/client/index* 2>/dev/null | head -5

# ═══ P1-10 : Before/After images ═══
echo "=== P1-10 BEFORE/AFTER ==="
grep -rn "object-contain\|before.*after\|beforeAfter" resources/views/tattooer/portfolio* | head -5

# ═══ P1-11 : No-show count (confirmé OK) ═══
echo "=== P1-11 NO-SHOW COUNT ==="
grep -rn "no_show_count\|increment.*no_show" app/Http/Controllers/ app/Models/ | head -5

# ═══ P1-12 : Avis + Réclamations (CASSÉ) ═══
echo "=== P1-12 AVIS + RÉCLAMATIONS ==="
# Le bouton avis est invisible → diagnostic complet
php artisan tinker --execute="
  echo 'Table reviews: ' . (Schema::hasTable('reviews') ? 'OUI' : 'NON');
  echo ' | Model Review: ' . (class_exists('App\Models\Review') ? 'OUI' : 'NON');
  echo ' | Table complaints: ' . (Schema::hasTable('complaints') ? 'OUI' : 'NON');
  \$br = App\Models\BookingRequest::where('status', 'completed')->first();
  if (\$br) {
    echo ' | BR completed #' . \$br->id;
    echo ' | status type: ' . (is_object(\$br->status) ? get_class(\$br->status) : gettype(\$br->status));
    echo ' | isCompleted method: ' . (method_exists(\$br, 'isCompleted') ? 'EXISTS' : 'ABSENT');
  } else {
    echo ' | Aucune BR completed';
  }
"
# Chercher où est le bouton avis et pourquoi il est caché
grep -rn "avis\|review\|Review\|openReviewModal" resources/views/client/ resources/views/layouts/client.blade.php | head -20
# Chercher la condition de visibilité
grep -rn "completed" resources/views/client/ | head -20
# Vérifier si le problème est une comparaison Enum
grep -rn "status.*===.*completed\|status.*==.*completed" resources/views/client/ | head -10
```

---

## PHASE 3 — SÉCURITÉ (P0)

### 3.1 Authentification & Autorisation
```bash
# Routes sans middleware auth qui devraient être protégées
php artisan route:list --columns=method,uri,middleware 2>&1 | grep -v "auth\|guest\|public\|sanctum\|api/webhook\|verification\|password\|login\|register\|GET.*/$" | grep -E "POST|PUT|PATCH|DELETE"

# Policies existantes vs controllers
ls app/Policies/ 2>/dev/null
echo "---"
ls app/Http/Controllers/Tattooer/ app/Http/Controllers/Client/ 2>/dev/null

# Accès horizontal : un tattooer accède aux données d'un autre
grep -rn "auth()->user()->tattooer->id\|abort_unless\|abort_if\|authorize\|policy\|->can(" app/Http/Controllers/Tattooer/ | head -20
# CHAQUE action sur BookingRequest, Client, etc. doit vérifier la propriété
```

### 3.2 Injection & Validation
```bash
# Requêtes raw SQL
grep -rn "DB::raw\|->whereRaw\|->selectRaw\|DB::select\|DB::statement" app/ --include="*.php" | grep -v "migration\|seeder"

# Controllers store/update SANS validation
for f in app/Http/Controllers/Tattooer/*.php app/Http/Controllers/Client/*.php; do
    methods=$(grep -n "function store\|function update\|function upload\|function create" "$f" 2>/dev/null)
    if [ -n "$methods" ]; then
        while IFS= read -r line; do
            linenum=$(echo "$line" | cut -d: -f1)
            # Chercher validate dans les 15 lignes suivantes
            has_validate=$(sed -n "$((linenum)),$(($linenum+15))p" "$f" | grep -c "validate\|FormRequest\|Validator")
            if [ "$has_validate" -eq 0 ]; then
                echo "⚠️ PAS DE VALIDATION: $f:$linenum → $line"
            fi
        done <<< "$methods"
    fi
done

# Models sans $fillable (mass assignment danger)
for f in app/Models/*.php; do
    grep -qL "fillable\|guarded" "$f" 2>/dev/null && echo "❌ PAS DE \$fillable: $f"
done
```

### 3.3 Stripe & Paiements
```bash
# Clés Stripe hardcodées
grep -rn "sk_live\|sk_test\|pk_live\|pk_test\|whsec_" app/ resources/ config/ routes/ --include="*.php" --include="*.blade.php" --include="*.js" 2>/dev/null

# Validation webhook Stripe
grep -rn "Webhook::constructEvent\|stripe_signature\|webhook" app/ routes/ --include="*.php" | head -10

# Montants non validés côté serveur
grep -rn "amount.*request\|price.*input\|deposit.*request" app/Http/Controllers/ --include="*.php" | head -10
```

### 3.4 Upload fichiers
```bash
# Validation des uploads
grep -rn "mimes:\|max:\|image\|file" app/Http/Requests/ app/Http/Controllers/ --include="*.php" | grep -i "valid\|rule" | head -15
```

### 3.5 CSRF
```bash
# Exclusions CSRF
grep -rn "except\|withoutMiddleware.*csrf\|validateCsrfTokens" bootstrap/app.php app/Http/Middleware/ routes/ --include="*.php" 2>/dev/null
```

---

## PHASE 4 — FICHIERS MORTS & DOUBLONS (P1)

```bash
# 4A. Controllers orphelins (sans route)
echo "=== CONTROLLERS ORPHELINS ==="
for f in app/Http/Controllers/*.php app/Http/Controllers/Tattooer/*.php app/Http/Controllers/Client/*.php 2>/dev/null; do
    class=$(basename "$f" .php)
    refs=$(grep -rn "$class" routes/ --include="*.php" 2>/dev/null | wc -l)
    if [ "$refs" -eq 0 ]; then
        echo "ORPHELIN: $f"
    fi
done

# 4B. Vues orphelines
echo "=== VUES ORPHELINES ==="
for dir in tattooer client piercer studio; do
    for f in resources/views/$dir/*.blade.php 2>/dev/null; do
        name=$(basename "$f" .blade.php)
        refs=$(grep -rn "'$dir.$name'\|\"$dir.$name\"\|$dir/$name" app/ routes/ resources/views/ --include="*.php" --include="*.blade.php" 2>/dev/null | wc -l)
        if [ "$refs" -eq 0 ]; then
            echo "ORPHELINE: $f"
        fi
    done
done

# 4C. Méthodes dupliquées dans les controllers (Cascade ajoute sans vérifier)
echo "=== MÉTHODES DUPLIQUÉES ==="
for f in app/Http/Controllers/Tattooer/*.php app/Http/Controllers/Client/*.php 2>/dev/null; do
    grep -o "public function [a-zA-Z]*" "$f" | sort | uniq -d | while read -r dup; do
        echo "DOUBLON dans $f: $dup"
    done
done

# 4D. Doublons dans routes/web.php (même route définie 2 fois)
echo "=== ROUTES DUPLIQUÉES ==="
grep -n "Route::" routes/web.php | grep -oP "name\('\K[^']*" | sort | uniq -d

# 4E. Tables legacy en BDD
echo "=== TABLES BDD ==="
php artisan tinker --execute="
  \$tables = collect(DB::select('SHOW TABLES'))->pluck('Tables_in_' . config('database.connections.mysql.database'));
  \$tables->each(fn(\$t) => print(\$t . ' (' . DB::table(\$t)->count() . ' rows)' . PHP_EOL));
"

# 4F. Migrations en attente
php artisan migrate:status | grep -v "Ran"

# 4G. Models peu ou pas utilisés
echo "=== MODELS PEU UTILISÉS ==="
for f in app/Models/*.php; do
    class=$(basename "$f" .php)
    count=$(grep -rn "\\\\$class\b\|use.*Models\\\\$class\|$class::" app/ routes/ resources/ --include="*.php" --include="*.blade.php" 2>/dev/null | grep -v "$f" | wc -l)
    if [ "$count" -lt 3 ]; then
        echo "⚠️ $class ($count refs) → $f"
    fi
done

# 4H. Code JS dupliqué dans les blades
echo "=== SCRIPTS DUPLIQUÉS ==="
grep -rn "<script>" resources/views/ --include="*.blade.php" | grep -v "layouts/" | grep -v "@push"
grep -rn "openLightbox\|closeLightbox" resources/views/ --include="*.blade.php" | wc -l
grep -rn "openReviewModal" resources/views/ --include="*.blade.php"
```

---

## PHASE 5 — PERFORMANCE (P2)

```bash
# 5A. N+1 dans les layouts (exécutées sur CHAQUE page)
echo "=== QUERIES DANS LAYOUTS ==="
grep -rn "::where\|::count\|::find\|::first\|DB::" resources/views/layouts/ --include="*.blade.php"

# 5B. Controllers sans eager loading
echo "=== EAGER LOADING MANQUANT ==="
grep -rn "->get()\|->paginate\|->find(" app/Http/Controllers/ --include="*.php" | grep -v "->with(" | head -20

# 5C. Index BDD manquants sur colonnes fréquemment filtrées
php artisan tinker --execute="
  \$checks = [
    'booking_requests' => ['status', 'bookable_id', 'client_id', 'tattooer_id', 'client_user_id'],
    'appointments' => ['status', 'start_datetime', 'booking_request_id'],
    'conversations' => ['booking_request_id'],
    'messages' => ['conversation_id', 'sender_id', 'read_at'],
    'reviews' => ['booking_request_id', 'client_user_id', 'tattooer_id'],
    'complaints' => ['user_id', 'status', 'booking_request_id'],
    'traceability_records' => ['appointment_id', 'tattooer_client_id'],
  ];
  foreach(\$checks as \$table => \$cols) {
    if (!Schema::hasTable(\$table)) { echo \"TABLE ABSENTE: \$table\" . PHP_EOL; continue; }
    \$indexes = collect(DB::select(\"SHOW INDEX FROM \$table\"))->pluck('Column_name')->unique();
    foreach(\$cols as \$col) {
      if (!Schema::hasColumn(\$table, \$col)) { echo \"COLONNE ABSENTE: \$table.\$col\" . PHP_EOL; continue; }
      \$has = \$indexes->contains(\$col);
      echo \"\$table.\$col: \" . (\$has ? '✅' : '❌ PAS INDEX') . PHP_EOL;
    }
  }
"

# 5D. Taille des controllers (> 300 lignes = à refactorer)
echo "=== CONTROLLERS TROP GROS ==="
wc -l app/Http/Controllers/*.php app/Http/Controllers/Tattooer/*.php app/Http/Controllers/Client/*.php 2>/dev/null | sort -rn | head -15
```

---

## PHASE 6 — ENUM & COMPARAISONS (P0 — cause racine du bug avis)

C'est le problème #1 post-Cascade. Laravel 12 cast les status en BackedEnum, mais Cascade compare avec des strings.

```bash
# 6A. Identifier l'Enum des BookingRequest status
echo "=== ENUM BOOKING STATUS ==="
cat app/Enums/BookingRequestStatus.php 2>/dev/null || cat app/Enums/BookingStatus.php 2>/dev/null || echo "PAS D'ENUM TROUVÉ"
grep -n "protected \$casts" app/Models/BookingRequest.php

# 6B. TOUTES les comparaisons string vs Enum (BUG PATTERN)
echo "=== COMPARAISONS DANGEREUSES === "
# Pattern: $xxx->status === 'string' ou == 'string' → FALSE si Enum
grep -rn "->status\s*===\s*'" app/ resources/ --include="*.php" --include="*.blade.php" | grep -v "->status->value"
grep -rn "->status\s*==\s*'" app/ resources/ --include="*.php" --include="*.blade.php" | grep -v "->status->value"
# Pattern dans les Blades avec @if
grep -rn "status.*===\|status.*==" resources/views/ --include="*.blade.php" | grep -v "Enum\|->value" | head -30

# 6C. Vérifier si isCompleted() / isNoShow() existent dans le modèle
grep -n "function is" app/Models/BookingRequest.php

# 6D. Vérifier les match() qui utilisent des strings au lieu de l'Enum
grep -rn "match.*status" app/ resources/ --include="*.php" --include="*.blade.php" | head -20

# 6E. Lister TOUTES les valeurs possibles de l'Enum
php artisan tinker --execute="
  if (enum_exists('App\Enums\BookingRequestStatus')) {
    foreach(App\Enums\BookingRequestStatus::cases() as \$c) {
      echo \$c->value . PHP_EOL;
    }
  } else {
    echo 'Enum BookingRequestStatus non trouvé. Chercher...';
    \$br = App\Models\BookingRequest::first();
    echo 'Status type: ' . gettype(\$br->status) . PHP_EOL;
    echo 'Status class: ' . (is_object(\$br->status) ? get_class(\$br->status) : 'scalar') . PHP_EOL;
  }
"
```

---

## PHASE 7 — TESTS (P1)

```bash
# 7A. Lancer les tests existants
php artisan test --parallel 2>&1 | tail -20

# 7B. Tests qui échouent
php artisan test 2>&1 | grep -E "FAIL|Error|Exception" | head -20

# 7C. Nombre de tests
php artisan test --parallel 2>&1 | grep -E "Tests:|Assertions:"
```

---

## FORMAT DU RAPPORT

Génère le rapport dans CE format exact :

```
# 📊 RAPPORT D'AUDIT POST-IMPLÉMENTATION INK&PIK
Date : 20/02/2026

## RÉSUMÉ
- Routes cassées : X
- Comparaisons Enum dangereuses : X
- Fichiers orphelins : X
- Failles sécurité : X
- Tests échoués : X / Y

## P0 — CRITIQUE (routes cassées, sécurité, Enum)
| # | Phase | Fichier:ligne | Problème | Fix |
|---|-------|---------------|----------|-----|
| 1 | ... | ... | ... | ... |

## P1 — IMPORTANT (doublons, fichiers morts, validations)
| # | Phase | Fichier:ligne | Problème | Fix |
|---|-------|---------------|----------|-----|

## P2 — DETTE TECHNIQUE (perf, refactoring)
| # | Phase | Fichier:ligne | Problème | Fix |
|---|-------|---------------|----------|-----|

## FICHIERS À SUPPRIMER
| Fichier | Raison |
|---------|--------|

## STATUS DES 16 PROMPTS
| # | Prompt | Status | Problème résiduel |
|---|--------|--------|-------------------|
| 1 | Badge messages | ✅/⚠️/❌ | ... |
| 2 | Expiration acompte | ✅/⚠️/❌ | ... |
| ... | ... | ... | ... |

## PLAN DE CORRECTION (par ordre)
1. ...
2. ...
```

## ⚠️ RÈGLES ABSOLUES

1. **LECTURE SEULE** phases 1-7 : ne modifie AUCUN fichier
2. **Exhaustif** : scanne TOUT le projet
3. **Actionnable** : chaque problème = un fix concret avec fichier et ligne
4. **Pas de faux positifs** : vérifie avant de reporter
5. **Le rapport doit être COMPLET** avant toute correction
6. Si tu trouves plus de 20 problèmes P0 → STOP et affiche le rapport partiel
7. **Priorité #1** : les routes cassées et les comparaisons Enum
