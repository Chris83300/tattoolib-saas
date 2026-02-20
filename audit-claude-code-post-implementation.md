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
- Problème de route après les dernières implémentations (prompt 13 à 16)
- Sur le profil tattooer les avis ne sont pas visibles (les avis doivent automatiquement être validés)
- Sur le dashboard Admin il doit y avoir la possibilité de supprimer un avis (si insulte ou contenu non approprié)
- NON TESTÉ : dashboard Filament studio, création compte pierceur/bodymodeur, création compte studio, création studio artiste depuis un studio

## CONSIGNES

- **NE MODIFIE RIEN** pendant les phases d'audit (Phases 1 à 7)
- Génère un rapport structuré avec priorités P0/P1/P2
- Pour chaque problème : fichier, ligne, description, fix recommandé
- **Phase 8** : applique les corrections UNIQUEMENT après validation du rapport
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
    "piercer.dashboard"
    "piercer.settings"
    "studio.dashboard"
    "studio.artists"
    "studio.artists.create"
    "studio.settings"
)
for route in "${CRITICAL_ROUTES[@]}"; do
    php artisan route:list --name="$route" 2>&1 | grep -q "$route" \
        && echo "✅ $route" \
        || echo "❌ MANQUANTE: $route"
done

# 1F. Chercher les imports use manquants dans routes/web.php
head -50 routes/web.php
grep -n "use " routes/web.php
grep -oP '\b\w+Controller\b' routes/web.php | sort -u | while read -r ctrl; do
    grep -q "use.*$ctrl" routes/web.php || echo "❌ IMPORT MANQUANT: $ctrl"
done

# 1G. Fichier routes/web.php — taille
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
# Bouton réclamation
grep -rn "réclamation\|complaint\|Complaint\|récla" resources/views/client/ resources/views/layouts/client.blade.php | head -20
# Bouton avis
grep -rn "avis\|review\|Review\|openReviewModal" resources/views/client/ resources/views/layouts/client.blade.php | head -20
# Condition de visibilité
grep -rn "completed" resources/views/client/ | head -20
# Comparaison Enum cassée ?
grep -rn "status.*===.*completed\|status.*==.*completed" resources/views/client/ | head -10

# ═══ P1-12 BIS : Avis sur profil public tattooer ═══
echo "=== P1-12 BIS AVIS PROFIL PUBLIC TATTOOER ==="
# Les avis doivent être AUTOMATIQUEMENT validés (is_published = true par défaut, PAS de modération a priori)
grep -rn "is_published\|auto.*publish\|published" app/Models/Review.php app/Http/Controllers/ | head -10
# Vue du profil public tattooer
find resources/views -name "*profile*" -o -name "*public*" | head -10
grep -rn "review\|avis\|rating\|étoile\|star" resources/views/tattooer/profile* resources/views/public* resources/views/marketplace* 2>/dev/null | head -10
# Vérifier la valeur par défaut de is_published dans la migration
grep -rn "is_published" database/migrations/ | head -5

# ═══ P2-13 : Pierceur ═══
echo "=== P2-13 PIERCEUR ==="
ls app/Models/Piercer.php 2>/dev/null && echo "Model Piercer: EXISTS" || echo "Model Piercer: ABSENT"
php artisan tinker --execute="echo 'Table piercers: ' . (Schema::hasTable('piercers') ? 'OUI' : 'NON');" 2>/dev/null
php artisan route:list 2>&1 | grep -c "piercer"
ls resources/views/piercer/ 2>/dev/null | head -5 || echo "Pas de vues piercer"
ls resources/views/layouts/piercer.blade.php 2>/dev/null || echo "Pas de layout piercer"
grep -rn "piercer\|bodymod" app/Models/User.php config/auth.php app/Providers/ routes/web.php | head -10
# Vérifier que l'inscription pierceur fonctionne
grep -rn "piercer\|bodymod" resources/views/auth/ 2>/dev/null | head -5

# ═══ P2-14 : Studio ═══
echo "=== P2-14 STUDIO ==="
ls app/Models/Studio.php 2>/dev/null && echo "Model Studio: EXISTS" || echo "Model Studio: ABSENT"
ls app/Models/StudioArtist.php 2>/dev/null && echo "Model StudioArtist: EXISTS" || echo "Model StudioArtist: ABSENT"
php artisan tinker --execute="
  echo 'Table studios: ' . (Schema::hasTable('studios') ? 'OUI' : 'NON');
  echo ' | Table studio_artists: ' . (Schema::hasTable('studio_artists') ? 'OUI' : 'NON');
" 2>/dev/null
php artisan route:list 2>&1 | grep -c "studio"
ls resources/views/studio/ 2>/dev/null | head -10 || echo "Pas de vues studio"
# Dashboard Filament Studio
ls app/Filament/StudioPanel/ 2>/dev/null || ls app/Filament/Resources/Studio* 2>/dev/null || echo "Pas de panel Filament Studio"
# Pricing 39.99€/artiste (1 artiste inclus dans l'offre)
grep -rn "39.99\|3999\|artist.*price\|per_artist\|inclus\|included" app/ config/ --include="*.php" | head -5
# Inscription studio
grep -rn "studio" resources/views/auth/ 2>/dev/null | head -5

# ═══ P2-15 : Studio Artiste ═══
echo "=== P2-15 STUDIO ARTISTE ==="
grep -rn "studio_id\|studioArtist\|studio_artist\|belongsToStudio" app/Models/Tattooer.php app/Models/Piercer.php app/Models/User.php 2>/dev/null | head -10
# Logique Stripe : si studio centralized → pas de Stripe Connect pour l'artiste
grep -rn "centralized\|distributed\|payment_model" app/Models/Studio.php app/Http/Controllers/ app/Services/ 2>/dev/null | head -10
# Création d'un studio artiste depuis le dashboard studio
grep -rn "createArtist\|inviteArtist\|addArtist\|artist.*create\|artist.*store" app/Http/Controllers/Studio/ app/Filament/ 2>/dev/null | head -10

# ═══ P2-16 : Dashboard Filament Admin ═══
echo "=== P2-16 DASHBOARD ADMIN ==="
ls app/Filament/ 2>/dev/null
ls app/Filament/Resources/ 2>/dev/null | head -20
# L'admin doit pouvoir SUPPRIMER des avis
grep -rn "Review\|review\|DeleteAction\|delete.*review\|ReviewResource" app/Filament/Resources/ 2>/dev/null | head -10
# Widgets dashboard
ls app/Filament/Widgets/ 2>/dev/null
# Vérifier les resources pour réclamations
grep -rn "Complaint\|complaint\|ComplaintResource" app/Filament/Resources/ 2>/dev/null | head -5
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
ls app/Http/Controllers/Tattooer/ app/Http/Controllers/Client/ app/Http/Controllers/Piercer/ app/Http/Controllers/Studio/ 2>/dev/null

# Accès horizontal : un tattooer accède aux données d'un autre
grep -rn "auth()->user()->tattooer->id\|abort_unless\|abort_if\|authorize\|policy\|->can(" app/Http/Controllers/Tattooer/ app/Http/Controllers/Piercer/ app/Http/Controllers/Studio/ 2>/dev/null | head -20
```

### 3.2 Injection & Validation
```bash
# Requêtes raw SQL
grep -rn "DB::raw\|->whereRaw\|->selectRaw\|DB::select\|DB::statement" app/ --include="*.php" | grep -v "migration\|seeder"

# Controllers store/update SANS validation
for f in app/Http/Controllers/Tattooer/*.php app/Http/Controllers/Client/*.php app/Http/Controllers/Piercer/*.php app/Http/Controllers/Studio/*.php 2>/dev/null; do
    [ -f "$f" ] || continue
    methods=$(grep -n "function store\|function update\|function upload\|function create" "$f" 2>/dev/null)
    if [ -n "$methods" ]; then
        while IFS= read -r line; do
            linenum=$(echo "$line" | cut -d: -f1)
            has_validate=$(sed -n "$((linenum)),$(($linenum+15))p" "$f" | grep -c "validate\|FormRequest\|Validator")
            if [ "$has_validate" -eq 0 ]; then
                echo "⚠️ PAS DE VALIDATION: $f:$linenum → $line"
            fi
        done <<< "$methods"
    fi
done

# Models sans $fillable
for f in app/Models/*.php; do
    grep -L "fillable\|guarded" "$f" 2>/dev/null | while read -r noguard; do
        echo "❌ PAS DE \$fillable: $noguard"
    done
done
```

### 3.3 Stripe & Paiements
```bash
# Clés Stripe hardcodées
grep -rn "sk_live\|sk_test\|pk_live\|pk_test\|whsec_" app/ resources/ config/ routes/ --include="*.php" --include="*.blade.php" --include="*.js" 2>/dev/null

# Validation webhook Stripe
grep -rn "Webhook::constructEvent\|stripe_signature\|webhook" app/ routes/ --include="*.php" | head -10

# Laravel Cashier
grep -rn "Billable\|cashier" app/Models/User.php app/Models/Tattooer.php app/Models/Studio.php config/cashier.php 2>/dev/null | head -10
```

### 3.4 Upload fichiers & CSRF
```bash
grep -rn "mimes:\|max:\|image\|file" app/Http/Requests/ app/Http/Controllers/ --include="*.php" | grep -i "valid\|rule" | head -15
grep -rn "except\|withoutMiddleware.*csrf\|validateCsrfTokens" bootstrap/app.php app/Http/Middleware/ routes/ --include="*.php" 2>/dev/null
```

---

## PHASE 4 — FICHIERS MORTS & DOUBLONS (P1)

```bash
# 4A. Controllers orphelins
echo "=== CONTROLLERS ORPHELINS ==="
for f in $(find app/Http/Controllers -name "*.php" -not -path "*/Middleware/*" 2>/dev/null); do
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
        refs=$(grep -rn "'$dir.$name'\|\"$dir.$name\"\|$dir/$name\|$dir\.$name" app/ routes/ resources/views/ --include="*.php" --include="*.blade.php" 2>/dev/null | wc -l)
        if [ "$refs" -eq 0 ]; then
            echo "ORPHELINE: $f"
        fi
    done
done

# 4C. Méthodes dupliquées dans les controllers
echo "=== MÉTHODES DUPLIQUÉES ==="
for f in $(find app/Http/Controllers -name "*.php" 2>/dev/null); do
    dups=$(grep -o "public function [a-zA-Z]*" "$f" | sort | uniq -d)
    if [ -n "$dups" ]; then
        echo "DOUBLON dans $f:"
        echo "$dups"
    fi
done

# 4D. Routes nommées dupliquées dans routes/web.php
echo "=== ROUTES NOMMÉES DUPLIQUÉES ==="
grep -oP "name\('\K[^']*" routes/web.php | sort | uniq -d

# 4E. Tables BDD complètes
echo "=== TABLES BDD ==="
php artisan tinker --execute="
  \$tables = collect(DB::select('SHOW TABLES'))->pluck('Tables_in_' . config('database.connections.mysql.database'));
  \$tables->each(fn(\$t) => print(\$t . ' (' . DB::table(\$t)->count() . ' rows)' . PHP_EOL));
"

# 4F. Migrations en attente
php artisan migrate:status | grep -v "Ran"

# 4G. Models peu utilisés
echo "=== MODELS PEU UTILISÉS ==="
for f in app/Models/*.php; do
    class=$(basename "$f" .php)
    count=$(grep -rn "\\\\$class\b\|use.*Models\\\\$class\|$class::" app/ routes/ resources/ --include="*.php" --include="*.blade.php" 2>/dev/null | grep -v "$f" | wc -l)
    if [ "$count" -lt 3 ]; then
        echo "⚠️ $class ($count refs) → $f"
    fi
done

# 4H. Code JS/lightbox dupliqué dans les blades
echo "=== SCRIPTS DUPLIQUÉS ==="
grep -rn "<script>" resources/views/ --include="*.blade.php" | grep -v "layouts/" | grep -v "@push" | wc -l
echo "Lightbox functions:"
grep -rn "openLightbox\|closeLightbox" resources/views/ --include="*.blade.php" | wc -l
echo "Review modal:"
grep -rn "openReviewModal" resources/views/ --include="*.blade.php"
```

---

## PHASE 5 — PERFORMANCE (P2)

```bash
# 5A. Queries dans les layouts (exécutées sur CHAQUE page)
echo "=== QUERIES DANS LAYOUTS ==="
grep -rn "::where\|::count\|::find\|::first\|DB::" resources/views/layouts/ --include="*.blade.php"

# 5B. Controllers sans eager loading
echo "=== EAGER LOADING MANQUANT ==="
grep -rn "->get()\|->paginate\|->find(" app/Http/Controllers/ --include="*.php" | grep -v "->with(" | head -20

# 5C. Index BDD manquants
php artisan tinker --execute="
  \$checks = [
    'booking_requests' => ['status', 'bookable_id', 'client_id', 'tattooer_id', 'client_user_id'],
    'appointments' => ['status', 'start_datetime', 'booking_request_id'],
    'conversations' => ['booking_request_id'],
    'messages' => ['conversation_id', 'sender_id', 'read_at'],
    'reviews' => ['booking_request_id', 'client_user_id', 'tattooer_id'],
    'complaints' => ['user_id', 'status', 'booking_request_id'],
    'traceability_records' => ['appointment_id', 'tattooer_client_id'],
    'studios' => ['user_id', 'slug'],
    'studio_artists' => ['studio_id', 'user_id'],
    'piercers' => ['user_id'],
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

# 5D. Taille des controllers
echo "=== CONTROLLERS TROP GROS (>300 lignes) ==="
find app/Http/Controllers -name "*.php" -exec wc -l {} + 2>/dev/null | sort -rn | head -15
```

---

## PHASE 6 — ENUM & COMPARAISONS (P0 — cause racine bugs avis/réclamation)

```bash
# 6A. Identifier l'Enum des BookingRequest status
echo "=== ENUM BOOKING STATUS ==="
find app/Enums -name "*ooking*" -o -name "*tatus*" 2>/dev/null | head -5
cat app/Enums/BookingRequestStatus.php 2>/dev/null || cat app/Enums/BookingStatus.php 2>/dev/null || echo "PAS D'ENUM TROUVÉ"
grep -n "protected \$casts" app/Models/BookingRequest.php

# 6B. TOUTES les comparaisons string vs Enum (BUG PATTERN — cause racine)
echo "=== COMPARAISONS DANGEREUSES ==="
# Pattern: ->status === 'string' → FALSE si Enum BackedEnum
grep -rn "->status\s*===\s*'" app/ resources/ --include="*.php" --include="*.blade.php" | grep -v "->status->value" | grep -v vendor
grep -rn "->status\s*==\s*'" app/ resources/ --include="*.php" --include="*.blade.php" | grep -v "->status->value" | grep -v vendor
# Dans les blades spécifiquement
grep -rn "status.*===\|status.*==" resources/views/ --include="*.blade.php" | grep -v "Enum\|->value" | head -30

# 6C. Helper methods dans le modèle BookingRequest
echo "=== HELPERS isXxx() ==="
grep -n "function is" app/Models/BookingRequest.php

# 6D. match() avec strings au lieu d'Enum
grep -rn "match.*status" app/ resources/ --include="*.php" --include="*.blade.php" | grep -v vendor | head -20

# 6E. Valeurs de l'Enum
php artisan tinker --execute="
  \$enumClasses = ['App\Enums\BookingRequestStatus', 'App\Enums\BookingStatus'];
  foreach (\$enumClasses as \$class) {
    if (enum_exists(\$class)) {
      echo \$class . ':' . PHP_EOL;
      foreach (\$class::cases() as \$c) {
        echo '  ' . \$c->name . ' = ' . \$c->value . PHP_EOL;
      }
    }
  }
  \$br = App\Models\BookingRequest::first();
  if (\$br) {
    echo 'Status runtime type: ' . (is_object(\$br->status) ? get_class(\$br->status) : gettype(\$br->status)) . PHP_EOL;
  }
"

# 6F. Review auto-publish — is_published doit être TRUE par défaut
echo "=== REVIEW AUTO-PUBLISH ==="
grep -rn "is_published" app/Models/Review.php database/migrations/ | head -10
# Vérifier le default en BDD
php artisan tinker --execute="
  if (Schema::hasTable('reviews') && Schema::hasColumn('reviews', 'is_published')) {
    \$col = collect(DB::select(\"SHOW COLUMNS FROM reviews WHERE Field = 'is_published'\"))->first();
    echo 'is_published default: ' . (\$col->Default ?? 'NULL') . PHP_EOL;
    echo 'is_published type: ' . \$col->Type . PHP_EOL;
  } else {
    echo 'reviews.is_published: COLONNE ABSENTE';
  }
"
# Vérifier que le controller NE MET PAS is_published = false à la création
grep -rn "is_published" app/Http/Controllers/Client/ app/Http/Controllers/Tattooer/ | head -10

# 6G. Admin suppression avis — Filament ReviewResource avec DeleteAction
echo "=== ADMIN DELETE REVIEWS ==="
find app/Filament -name "*Review*" 2>/dev/null
grep -rn "DeleteAction\|DeleteBulkAction\|forceDelete\|delete" app/Filament/Resources/ReviewResource* 2>/dev/null | head -10
```

---

## PHASE 7 — TESTS (P1)

```bash
# 7A. Lancer les tests
php artisan test --parallel 2>&1 | tail -20

# 7B. Tests qui échouent
php artisan test 2>&1 | grep -E "FAIL|Error|Exception" | head -20

# 7C. Résumé
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

## P0 — CRITIQUE (routes cassées, sécurité, Enum, avis/réclamation invisible)
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
| 3 | Boutons Terminé/No-show | ✅ | Fonctionne |
| 4 | Limite portfolio 15 | ✅/⚠️/❌ | ... |
| 5 | Commission 7% | ✅ | Confirmé OK |
| 6 | Notifications | ✅ | Confirmé OK |
| 7 | Aftercare settings | ✅/⚠️/❌ | ... |
| 8 | Sidebar mobile client | ✅/⚠️/❌ | ... |
| 9 | Indicateur demandes | ✅/⚠️/❌ | ... |
| 10 | Before/After images | ✅/⚠️/❌ | ... |
| 11 | No-show count | ✅ | Fonctionne |
| 12 | Avis + Réclamations | ❌ | Bouton réclamation invisible, avis invisible sur profil tattooer, is_published doit être true par défaut, admin doit pouvoir supprimer un avis |
| 13 | Pierceur | ⚠️ | Non testé — vérifier inscription + dashboard |
| 14 | Studio | ⚠️ | Non testé — vérifier inscription + dashboard + création artistes + pricing 39.99€ |
| 15 | Studio Artiste | ⚠️ | Non testé — vérifier création depuis studio + logique Stripe centralized/distributed |
| 16 | Dashboard Admin | ⚠️ | Non testé — vérifier resources + suppression avis + gestion réclamations |

## PLAN DE CORRECTION (par ordre de priorité)
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
8. **Priorité #2** : bouton réclamation invisible + avis visible sur profil tattooer + auto-publish reviews + admin delete reviews
9. **Priorité #3** : vérifier que pierceur/studio/studio-artiste compilent sans erreur
