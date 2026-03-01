# 🔍 AUDIT GLOBAL COMPLET — Ink&Pik SaaS
# Pour Claude Code — Génère un fichier audit-global-inkpik.md avec tous les résultats

## OBJECTIF

Analyser l'intégralité du projet Ink&Pik pour produire un rapport complet au format .md :
- Ce qui est FAIT et fonctionnel
- Ce qui est PARTIEL (existe mais incomplet)
- Ce qui est MANQUANT
- Les BUGS détectés (erreurs de compilation, routes mortes, vues cassées)
- Les TODO/FIXME dans le code
- Les incohérences (routes sans controller, controllers sans vue, models sans migration)

Le rapport final doit être sauvegardé dans `audit-global-inkpik.md` à la racine du projet.

---

## SECTION 1 — INFRASTRUCTURE TECHNIQUE

```bash
echo "══════════════════════════════════════════"
echo "SECTION 1 — INFRASTRUCTURE TECHNIQUE"
echo "══════════════════════════════════════════"

# 1A. Version stack
echo "--- VERSIONS ---"
php artisan --version
php -v | head -1
node -v 2>/dev/null || echo "Node: NON INSTALLÉ"
npm -v 2>/dev/null || echo "NPM: NON INSTALLÉ"
composer show laravel/framework | grep versions
composer show livewire/livewire | grep versions 2>/dev/null
composer show filament/filament | grep versions 2>/dev/null
composer show laravel/cashier | grep versions 2>/dev/null
composer show stripe/stripe-php | grep versions 2>/dev/null
composer show spatie/laravel-permission | grep versions 2>/dev/null
composer show spatie/laravel-medialibrary | grep versions 2>/dev/null

# 1B. Structure du projet
echo "--- STRUCTURE ---"
echo "Models:" && ls app/Models/ | wc -l
echo "Controllers:" && find app/Http/Controllers -name "*.php" | wc -l
echo "Livewire:" && find app/Livewire -name "*.php" 2>/dev/null | wc -l
echo "Vues Blade:" && find resources/views -name "*.blade.php" | wc -l
echo "Migrations:" && find database/migrations -name "*.php" | wc -l
echo "Tests:" && find tests -name "*.php" 2>/dev/null | wc -l
echo "Mail:" && find app/Mail -name "*.php" 2>/dev/null | wc -l
echo "Notifications:" && find app/Notifications -name "*.php" 2>/dev/null | wc -l
echo "Services:" && find app/Services -name "*.php" 2>/dev/null | wc -l
echo "Jobs:" && find app/Jobs -name "*.php" 2>/dev/null | wc -l
echo "Commands:" && find app/Console/Commands -name "*.php" 2>/dev/null | wc -l
echo "Middleware:" && find app/Http/Middleware -name "*.php" 2>/dev/null | wc -l
echo "Filament Resources:" && find app/Filament -name "*Resource.php" 2>/dev/null | wc -l
echo "Filament Widgets:" && find app/Filament -name "*Widget*.php" -o -name "*Overview*.php" 2>/dev/null | wc -l

# 1C. Routes totales
echo "--- ROUTES ---"
php artisan route:list 2>&1 | wc -l
php artisan route:list --name="tattooer" 2>&1 | wc -l
php artisan route:list --name="pierceur" 2>&1 | wc -l
php artisan route:list --name="studio" 2>&1 | wc -l
php artisan route:list --name="client" 2>&1 | wc -l
php artisan route:list --name="admin" 2>&1 | wc -l

# 1D. Tables de la DB
echo "--- BASE DE DONNÉES ---"
php artisan tinker --execute="
  \$tables = DB::select('SHOW TABLES');
  \$key = array_key_first((array)\$tables[0]);
  foreach(\$tables as \$t) {
    \$name = ((array)\$t)[\$key];
    \$count = DB::table(\$name)->count();
    echo \$name . ' (' . \$count . ' rows)' . PHP_EOL;
  }
"

# 1E. Compilation
echo "--- COMPILATION ---"
php artisan route:clear 2>&1
php artisan view:clear 2>&1
php artisan config:clear 2>&1
php artisan route:list 2>&1 | head -3
echo "Routes OK si pas d'erreur ci-dessus"
```

---

## SECTION 2 — MODELS ET RELATIONS

```bash
echo "══════════════════════════════════════════"
echo "SECTION 2 — MODELS ET RELATIONS"
echo "══════════════════════════════════════════"

# 2A. Lister tous les models avec leurs traits et interfaces
for model in app/Models/*.php; do
  echo "--- $(basename $model .php) ---"
  grep -n "class \|use \|implements \|extends " "$model" | head -10
  echo "fillable:" && grep -c "'[a-z]" "$model" | head -1
  grep "function .*(" "$model" | grep -v "__\|boot\|cast" | head -15
  echo ""
done

# 2B. Traits
echo "--- TRAITS ---"
for trait in app/Models/Traits/*.php 2>/dev/null; do
  echo "$(basename $trait):"
  grep "function " "$trait" | head -10
  echo ""
done

# 2C. Contracts/Interfaces
echo "--- INTERFACES ---"
find app/Contracts -name "*.php" 2>/dev/null -exec basename {} \;

# 2D. Relations vérification rapide
php artisan tinker --execute="
  \$models = ['User', 'Tattooer', 'Piercer', 'Studio', 'StudioArtist', 'BookingRequest', 'Client'];
  foreach(\$models as \$m) {
    \$class = 'App\Models\\\\' . \$m;
    if (class_exists(\$class)) {
      \$obj = new \$class;
      \$methods = collect(get_class_methods(\$obj))->filter(fn(\$method) => !str_starts_with(\$method, '__') && !str_starts_with(\$method, 'get') && !str_starts_with(\$method, 'set') && !str_starts_with(\$method, 'scope'));
      echo \$m . ': OK (' . \$class . ')' . PHP_EOL;
    } else {
      echo \$m . ': CLASSE ABSENTE' . PHP_EOL;
    }
  }
"
```

---

## SECTION 3 — ROUTES ET CONTROLLERS

```bash
echo "══════════════════════════════════════════"
echo "SECTION 3 — ROUTES ET CONTROLLERS"
echo "══════════════════════════════════════════"

# 3A. Routes par groupe avec action
echo "--- ROUTES TATTOOER ---"
php artisan route:list --name="tattooer" --columns=method,uri,name,action 2>&1

echo "--- ROUTES PIERCEUR ---"
php artisan route:list --name="pierceur" --columns=method,uri,name,action 2>&1

echo "--- ROUTES STUDIO ---"
php artisan route:list --name="studio" --columns=method,uri,name,action 2>&1

echo "--- ROUTES CLIENT ---"
php artisan route:list --name="client" --columns=method,uri,name,action 2>&1

echo "--- ROUTES ADMIN ---"
php artisan route:list --name="admin" --columns=method,uri,name,action 2>&1

echo "--- ROUTES AUTH ---"
php artisan route:list --name="login\|register\|password\|verify\|logout" --columns=method,uri,name 2>&1

echo "--- ROUTES PUBLIQUES (marketplace, profil public, etc.) ---"
php artisan route:list 2>&1 | grep -v "tattooer\.\|pierceur\.\|studio\.\|client\.\|admin\.\|filament\.\|livewire\.\|ignition\.\|sanctum\.\|login\|register\|password\|verify\|logout" | head -30

# 3B. Controllers — liste avec nombre de méthodes
echo "--- CONTROLLERS ---"
for ctrl in app/Http/Controllers/*.php app/Http/Controllers/**/*.php 2>/dev/null; do
  methods=$(grep "public function " "$ctrl" 2>/dev/null | wc -l)
  echo "$(basename $ctrl): $methods méthodes"
done

# 3C. Livewire components
echo "--- COMPOSANTS LIVEWIRE ---"
find app/Livewire -name "*.php" 2>/dev/null | sort | while read f; do
  echo "$(echo $f | sed 's|app/Livewire/||')"
done

# 3D. Routes mortes (controller/action qui n'existe pas)
echo "--- VÉRIFICATION ROUTES ---"
php artisan route:list 2>&1 | tail -5
```

---

## SECTION 4 — VUES ET FRONTEND

```bash
echo "══════════════════════════════════════════"
echo "SECTION 4 — VUES ET FRONTEND"
echo "══════════════════════════════════════════"

# 4A. Arborescence des vues
echo "--- ARBORESCENCE VUES ---"
find resources/views -type d | sort | while read dir; do
  count=$(find "$dir" -maxdepth 1 -name "*.blade.php" | wc -l)
  if [ "$count" -gt 0 ]; then
    echo "$dir/ ($count fichiers)"
  fi
done

# 4B. Layouts
echo "--- LAYOUTS ---"
ls resources/views/layouts/ 2>/dev/null

# 4C. Vues avec placeholder/TODO/en cours
echo "--- VUES PLACEHOLDER / TODO ---"
grep -rn "en cours de développement\|TODO\|FIXME\|placeholder\|prochainement\|coming soon\|sera disponible" resources/views/ --include="*.blade.php" | head -30

# 4D. Vues qui référencent des routes inexistantes
echo "--- ROUTES RÉFÉRENCÉES DANS LES VUES ---"
grep -roh "route('[^']*')" resources/views/ --include="*.blade.php" | sort -u | while read route_call; do
  route_name=$(echo "$route_call" | sed "s/route('//;s/')//;s/,.*//" )
  exists=$(php artisan route:list --name="$route_name" 2>&1 | grep -c "$route_name")
  if [ "$exists" -eq 0 ]; then
    echo "ROUTE MANQUANTE: $route_name"
  fi
done 2>/dev/null | head -20

# 4E. Assets (CSS, JS)
echo "--- ASSETS ---"
ls resources/css/ 2>/dev/null
ls resources/js/ 2>/dev/null
cat vite.config.js 2>/dev/null | head -20
cat tailwind.config.js 2>/dev/null | head -20

# 4F. Fichiers CSS/JS compilés
echo "--- BUILD ---"
ls public/build/ 2>/dev/null | head -10
ls public/build/assets/ 2>/dev/null | wc -l
```

---

## SECTION 5 — STRIPE ET PAIEMENTS

```bash
echo "══════════════════════════════════════════"
echo "SECTION 5 — STRIPE ET PAIEMENTS"
echo "══════════════════════════════════════════"

# 5A. Config Stripe
echo "--- CONFIG ---"
grep -n "STRIPE" .env | sed 's/=.*/=***/' # Masquer les valeurs

# 5B. Services Stripe
echo "--- SERVICES STRIPE ---"
find app/Services -name "*Stripe*" -o -name "*Payment*" -o -name "*Billing*" 2>/dev/null | sort
for f in $(find app/Services -name "*Stripe*" -o -name "*Payment*" -o -name "*Billing*" 2>/dev/null); do
  echo "$(basename $f):"
  grep "public function " "$f" | head -10
  echo ""
done

# 5C. Webhooks
echo "--- WEBHOOKS ---"
grep -rn "webhook\|WebhookReceived\|WebhookController\|StripeWebhook" app/ routes/ --include="*.php" | head -15

# 5D. Stripe Connect
echo "--- STRIPE CONNECT ---"
grep -rn "stripe_account_id\|getStripeAccountId\|needsOwnStripeConnect\|Account::create\|AccountLink" app/ --include="*.php" | grep -v "migration\|fillable\|casts" | head -15

# 5E. Cashier (subscriptions)
echo "--- CASHIER ---"
grep -rn "Billable\|subscription\|subscribe\|newSubscription" app/Models/ --include="*.php" | head -10
php artisan tinker --execute="
  echo 'subscriptions table: ' . (Schema::hasTable('subscriptions') ? 'OUI (' . DB::table('subscriptions')->count() . ' rows)' : 'NON');
  echo PHP_EOL . 'subscription_items: ' . (Schema::hasTable('subscription_items') ? 'OUI' : 'NON');
"

# 5F. Application Fee (commission)
echo "--- COMMISSION ---"
grep -rn "application_fee\|getCommission\|commission_rate\|getFeePercent" app/ --include="*.php" | head -10

# 5G. Coupons / Promotions
echo "--- COUPONS ---"
grep -rn "coupon\|COUPON\|promotion\|BETA" app/ --include="*.php" | head -10
grep -rn "coupon\|COUPON\|BETA" .env | head -5

# 5H. Remboursements
echo "--- REMBOURSEMENTS ---"
grep -rn "refund\|Refund\|remboursement" app/ --include="*.php" | head -10
```

---

## SECTION 6 — BOOKING WORKFLOW

```bash
echo "══════════════════════════════════════════"
echo "SECTION 6 — BOOKING WORKFLOW"
echo "══════════════════════════════════════════"

# 6A. Statuts de booking
echo "--- STATUTS BOOKING ---"
grep -rn "enum\|STATUS\|status.*=\|BookingStatus" app/Models/BookingRequest.php app/Enums/ 2>/dev/null | head -15

# 6B. Workflow complet
echo "--- MÉTHODES BOOKING ---"
grep "public function " app/Http/Controllers/*Booking* app/Services/*Booking* app/Livewire/**/Booking* 2>/dev/null | head -20

# 6C. Acompte (deposit)
echo "--- ACOMPTE ---"
grep -rn "deposit\|acompte\|DEPOSIT" app/ --include="*.php" | grep -v "migration\|test\|fillable" | head -15

# 6D. Paiement solde
echo "--- PAIEMENT SOLDE ---"
grep -rn "balance\|solde\|final_payment\|remaining" app/ --include="*.php" | grep -v "migration\|fillable" | head -10

# 6E. Dessins / Modifications
echo "--- DESSINS ---"
grep -rn "drawings_sent\|modifications_count\|max_drawings\|max_modifications" app/ --include="*.php" | head -10

# 6F. Annulation
echo "--- ANNULATION ---"
grep -rn "cancel\|annul" app/ --include="*.php" | grep -v "migration\|fillable\|test" | head -10

# 6G. Calendrier / Disponibilités
echo "--- CALENDRIER ---"
grep -rn "availability\|disponib\|calendar\|working_hours\|slots" app/ --include="*.php" | grep -v "migration\|fillable" | head -15

# 6H. Expiration auto
echo "--- EXPIRATION ---"
grep -rn "expire\|Expire\|expir" app/Console/ app/Jobs/ app/Services/ --include="*.php" | head -10
```

---

## SECTION 7 — CHAT ET MESSAGING

```bash
echo "══════════════════════════════════════════"
echo "SECTION 7 — CHAT ET MESSAGING"
echo "══════════════════════════════════════════"

# 7A. Models chat
echo "--- MODELS CHAT ---"
grep -rn "class.*Conversation\|class.*Message\|class.*Chat" app/Models/ --include="*.php" | head -5

# 7B. Tables chat
php artisan tinker --execute="
  \$tables = ['conversations', 'messages', 'chat_messages'];
  foreach(\$tables as \$t) {
    echo \$t . ': ' . (Schema::hasTable(\$t) ? 'OUI (' . DB::table(\$t)->count() . ' rows)' : 'NON') . PHP_EOL;
  }
"

# 7C. Upload restriction avant acompte
echo "--- RESTRICTION UPLOAD ---"
grep -rn "deposit_paid\|upload.*restrict\|restrict.*upload\|can.*upload\|canUpload" app/ --include="*.php" | head -10

# 7D. Expiration 3 phases
echo "--- EXPIRATION CHAT ---"
grep -rn "expir.*chat\|chat.*expir\|phase.*expir\|30.*jour\|30.*day" app/ --include="*.php" | head -10
```

---

## SECTION 8 — NOTIFICATIONS

```bash
echo "══════════════════════════════════════════"
echo "SECTION 8 — NOTIFICATIONS"
echo "══════════════════════════════════════════"

# 8A. Toutes les classes de notification
echo "--- NOTIFICATIONS ---"
find app/Notifications -name "*.php" 2>/dev/null | sort

# 8B. Tous les Mailables
echo "--- MAILABLES ---"
find app/Mail -name "*.php" 2>/dev/null | sort

# 8C. Templates email
echo "--- TEMPLATES EMAIL ---"
find resources/views/emails -name "*.blade.php" 2>/dev/null | sort
find resources/views/mail -name "*.blade.php" 2>/dev/null | sort

# 8D. Push notifications (Firebase/FCM)
echo "--- PUSH ---"
grep -rn "firebase\|FCM\|push.*notif\|OneSignal" app/ config/ --include="*.php" | head -10
grep "FIREBASE\|FCM\|ONESIGNAL" .env 2>/dev/null | head -5

# 8E. Scheduler (notifications planifiées)
echo "--- SCHEDULER ---"
cat routes/console.php 2>/dev/null
cat app/Console/Kernel.php 2>/dev/null | grep -A 2 "schedule"
```

---

## SECTION 9 — SÉCURITÉ ET MIDDLEWARE

```bash
echo "══════════════════════════════════════════"
echo "SECTION 9 — SÉCURITÉ ET MIDDLEWARE"
echo "══════════════════════════════════════════"

# 9A. Middleware custom
echo "--- MIDDLEWARE ---"
ls app/Http/Middleware/ 2>/dev/null
for mw in app/Http/Middleware/*.php; do
  echo "$(basename $mw):" && head -5 "$mw" | grep "class "
done

# 9B. CSP, rate limiting, etc
echo "--- SÉCURITÉ ---"
grep -rn "SecurityHeaders\|CSP\|RateLimit\|BlockSuspicious\|Throttle" app/Http/Middleware/ bootstrap/app.php --include="*.php" | head -10

# 9C. Validation uploads
echo "--- VALIDATION UPLOADS ---"
grep -rn "mimes:\|max:\|image\|file.*validate" app/Http/Controllers/ app/Livewire/ --include="*.php" | head -15

# 9D. SIRET validation
echo "--- SIRET ---"
grep -rn "siret\|SIRET" app/ --include="*.php" | grep -v "migration\|fillable\|casts" | head -10
```

---

## SECTION 10 — CONFORMITÉ LÉGALE (France)

```bash
echo "══════════════════════════════════════════"
echo "SECTION 10 — CONFORMITÉ LÉGALE"
echo "══════════════════════════════════════════"

# 10A. Traçabilité
echo "--- TRAÇABILITÉ ---"
find app/Models -name "*Trace*" -o -name "*trace*" 2>/dev/null
php artisan tinker --execute="
  \$tables = ['traceability_records', 'traceability_needles', 'traceability_inks'];
  foreach(\$tables as \$t) {
    echo \$t . ': ' . (Schema::hasTable(\$t) ? 'OUI (' . DB::table(\$t)->count() . ' rows, cols: ' . implode(', ', Schema::getColumnListing(\$t)) . ')' : 'NON') . PHP_EOL;
  }
"

# 10B. Consentement
echo "--- CONSENTEMENT ---"
grep -rn "consent\|consentement\|mineur\|minor\|parental" app/ --include="*.php" | head -10

# 10C. Fiches clients
echo "--- FICHES CLIENTS ---"
grep -rn "client_care_sheet\|client_consent_form\|ClientCare\|ClientConsent" app/Models/ --include="*.php" | head -10
php artisan tinker --execute="
  \$tables = ['client_care_sheets', 'client_consent_forms'];
  foreach(\$tables as \$t) {
    echo \$t . ': ' . (Schema::hasTable(\$t) ? 'OUI (' . DB::table(\$t)->count() . ' rows)' : 'NON') . PHP_EOL;
  }
"

# 10D. Badges vérification
echo "--- BADGES ---"
grep -rn "badge\|verified\|verification_status\|attestation\|hygiene\|ARS" app/ --include="*.php" | grep -v "email_verified\|migration\|fillable" | head -15

# 10E. Export PDF
echo "--- EXPORT PDF ---"
grep -rn "pdf\|PDF\|export.*pdf\|dompdf\|snappy" app/ --include="*.php" | head -10
grep -rn "pdf\|dompdf\|snappy" composer.json | head -5
```

---

## SECTION 11 — FILAMENT ADMIN

```bash
echo "══════════════════════════════════════════"
echo "SECTION 11 — FILAMENT ADMIN"
echo "══════════════════════════════════════════"

# 11A. Panels
echo "--- PANELS ---"
find app/Providers/Filament -name "*.php" 2>/dev/null | sort
for panel in app/Providers/Filament/*.php; do
  echo "$(basename $panel):"
  grep "->id\|->path\|->tenant" "$panel" | head -5
  echo ""
done

# 11B. Resources par panel
echo "--- RESOURCES ADMIN ---"
find app/Filament/Resources -name "*Resource.php" 2>/dev/null | sort
echo "--- RESOURCES STUDIO ---"
find app/Filament/Studio -name "*Resource.php" 2>/dev/null | sort

# 11C. Widgets
echo "--- WIDGETS ---"
find app/Filament -name "*Widget*" -o -name "*Overview*" -o -name "*Stats*" 2>/dev/null | sort

# 11D. Pages custom
echo "--- PAGES ---"
find app/Filament -name "*.php" -path "*/Pages/*" 2>/dev/null | sort
```

---

## SECTION 12 — TESTS

```bash
echo "══════════════════════════════════════════"
echo "SECTION 12 — TESTS"
echo "══════════════════════════════════════════"

# 12A. Structure tests
echo "--- FICHIERS TESTS ---"
find tests -name "*.php" 2>/dev/null | sort

# 12B. Compter les tests
echo "--- NOMBRE DE TESTS ---"
grep -rn "function test_\|->test(\|it(" tests/ 2>/dev/null | wc -l

# 12C. Lancer les tests (dry run — juste vérifier la compilation)
echo "--- COMPILATION TESTS ---"
php artisan test --list 2>&1 | tail -5

# 12D. Coverage par module
for dir in tests/Feature tests/Unit; do
  if [ -d "$dir" ]; then
    echo "--- $(basename $dir) ---"
    find "$dir" -name "*.php" | while read f; do
      count=$(grep -c "function test_\|->test(\|it(" "$f" 2>/dev/null)
      echo "$(basename $f): $count tests"
    done
  fi
done
```

---

## SECTION 13 — TODO / FIXME / HACKS DANS LE CODE

```bash
echo "══════════════════════════════════════════"
echo "SECTION 13 — TODO / FIXME / HACKS"
echo "══════════════════════════════════════════"

# 13A. TODO
echo "--- TODO ---"
grep -rn "TODO\|todo\|@todo" app/ resources/ routes/ --include="*.php" --include="*.blade.php" | head -40

# 13B. FIXME
echo "--- FIXME ---"
grep -rn "FIXME\|fixme\|HACK\|hack\|WORKAROUND" app/ resources/ routes/ --include="*.php" --include="*.blade.php" | head -20

# 13C. Fonctionnalités commentées
echo "--- CODE COMMENTÉ ---"
grep -rn "^    //" app/Http/Controllers/ app/Services/ --include="*.php" | grep -i "stripe\|payment\|webhook\|beta\|suspend" | head -20
```

---

## SECTION 14 — MARKETPLACE ET PAGES PUBLIQUES

```bash
echo "══════════════════════════════════════════"
echo "SECTION 14 — MARKETPLACE ET PAGES PUBLIQUES"
echo "══════════════════════════════════════════"

# 14A. Marketplace
echo "--- MARKETPLACE ---"
find app -name "*Marketplace*" -o -name "*marketplace*" 2>/dev/null | sort
grep "public function " app/Http/Controllers/MarketplaceController.php app/Services/MarketplaceSearchService.php 2>/dev/null | head -15
find resources/views/marketplace -name "*.blade.php" 2>/dev/null | sort

# 14B. Pages publiques
echo "--- PAGES PUBLIQUES ---"
grep -rn "Route::get.*'/'\\|Route::get.*'/about'\\|Route::get.*'/contact'\\|Route::get.*'/salon'" routes/web.php | head -10
find resources/views -name "welcome*" -o -name "home*" -o -name "landing*" 2>/dev/null

# 14C. Profil public artiste
echo "--- PROFIL PUBLIC ---"
grep -rn "public.*profile\|publicProfile\|getProfileUrl" app/ --include="*.php" | head -10
```

---

## SECTION 15 — BETA-TESTEURS ET FEATURES MANQUANTES

```bash
echo "══════════════════════════════════════════"
echo "SECTION 15 — FEATURES MANQUANTES"
echo "══════════════════════════════════════════"

# 15A. Système bêta-testeurs
echo "--- BETA-TESTEURS ---"
grep -rn "beta\|BETA\|is_beta\|beta_coupon\|beta_tester" app/ --include="*.php" | head -10
php artisan tinker --execute="
  echo 'users.is_beta_tester: ' . (Schema::hasColumn('users', 'is_beta_tester') ? 'EXISTS' : 'ABSENT');
  echo PHP_EOL . 'users.beta_coupon: ' . (Schema::hasColumn('users', 'beta_coupon') ? 'EXISTS' : 'ABSENT');
  echo PHP_EOL . 'users.last_transaction_at: ' . (Schema::hasColumn('users', 'last_transaction_at') ? 'EXISTS' : 'ABSENT');
  echo PHP_EOL . 'users.account_status: ' . (Schema::hasColumn('users', 'account_status') ? 'EXISTS' : 'ABSENT');
"

# 15B. Job suspension inactivité
echo "--- SUSPENSION INACTIVITÉ ---"
grep -rn "Suspend\|suspend\|inactive\|inactiv" app/Console/ app/Jobs/ --include="*.php" | head -5

# 15C. Remboursement basé dessins
echo "--- REMBOURSEMENT DESSINS ---"
grep -rn "refund.*drawing\|drawing.*refund\|calcul.*remboursement\|refund_percent" app/ --include="*.php" | head -10

# 15D. Modification de projet
echo "--- MODIFICATION PROJET ---"
grep -rn "amendment\|modification.*projet\|project.*modif" app/ --include="*.php" | head -10

# 15E. Push mobile
echo "--- PUSH MOBILE ---"
grep -rn "firebase\|FCM\|push\|OneSignal" app/ config/ --include="*.php" | head -10

# 15F. Antivirus scan
echo "--- ANTIVIRUS ---"
grep -rn "antivirus\|virus.*scan\|clamav\|malware" app/ --include="*.php" | head -5

# 15G. Alerte expiration documents
echo "--- EXPIRATION DOCS ---"
grep -rn "expir.*document\|document.*expir\|attestation.*expir" app/ --include="*.php" | head -5
```

---

## SECTION 16 — .ENV ET CONFIG

```bash
echo "══════════════════════════════════════════"
echo "SECTION 16 — CONFIGURATION"
echo "══════════════════════════════════════════"

# 16A. Variables .env (masquées)
echo "--- .ENV (clés uniquement) ---"
cat .env | grep -v "^#\|^$" | sed 's/=.*//' | sort

# 16B. Config custom
echo "--- CONFIG ---"
ls config/ | sort

# 16C. Services configurés
grep -n "=>" config/services.php | head -20
```

---

## GÉNÉRATION DU RAPPORT

Maintenant, avec TOUTES les informations collectées ci-dessus, génère un fichier `audit-global-inkpik.md` à la racine du projet.

Le rapport doit suivre cette structure :

```markdown
# 🔍 AUDIT GLOBAL INK&PIK
# Date : [date du jour]

## RÉSUMÉ EXÉCUTIF
- Total routes : X
- Total models : X
- Total vues : X
- Total tests : X
- Estimation avancement global : X%

## 1. INFRASTRUCTURE
[Résumé stack, versions, stats]

## 2. PAR MODULE — ÉTAT DÉTAILLÉ

### 2.1 AUTHENTIFICATION & INSCRIPTION
| Feature | Status | Détail |

### 2.2 TATTOOER (indépendant)
| Feature | Status | Détail |

### 2.3 PIERCER (polymorphique)
| Feature | Status | Détail |

### 2.4 STUDIO (multi-tenant)
| Feature | Status | Détail |

### 2.5 CLIENT
| Feature | Status | Détail |

### 2.6 BOOKING WORKFLOW
| Feature | Status | Détail |

### 2.7 PAIEMENTS & STRIPE
| Feature | Status | Détail |

### 2.8 CHAT & MESSAGING
| Feature | Status | Détail |

### 2.9 NOTIFICATIONS
| Feature | Status | Détail |

### 2.10 FICHES CLIENTS & TRAÇABILITÉ
| Feature | Status | Détail |

### 2.11 CONFORMITÉ LÉGALE
| Feature | Status | Détail |

### 2.12 MARKETPLACE & PAGES PUBLIQUES
| Feature | Status | Détail |

### 2.13 FILAMENT ADMIN
| Feature | Status | Détail |

### 2.14 SÉCURITÉ
| Feature | Status | Détail |

### 2.15 TESTS
| Feature | Status | Détail |

## 3. BUGS DÉTECTÉS
[Routes mortes, vues placeholder, erreurs compilation]

## 4. TODO/FIXME DANS LE CODE
[Liste exhaustive]

## 5. FEATURES MANQUANTES — PRIORISÉES

### BLOC P0 — Bloquant pour le lancement
| # | Feature | Effort estimé |

### BLOC P1 — Important pour le lancement
| # | Feature | Effort estimé |

### BLOC P2 — Post-lancement
| # | Feature | Effort estimé |

## 6. RECOMMANDATION TIMELINE
[Estimation temps pour chaque bloc]
```

Légende des statuts :
- ✅ FAIT — Implémenté et fonctionnel
- ⚠️ PARTIEL — Existe mais incomplet
- ❌ MANQUANT — Pas implémenté
- 🐛 BUG — Existe mais cassé

IMPORTANT :
- Être HONNÊTE dans l'évaluation — ne pas gonfler les pourcentages
- Chaque feature doit avoir un statut CONCRET basé sur le code, pas sur des suppositions
- Les bugs doivent être listés avec le fichier et la ligne
- Les TODO dans le code doivent être reportés fidèlement
- Le rapport doit être ACTIONNABLE — on doit pouvoir en faire des prompts

```bash
# Sauvegarder le rapport
# Le fichier audit-global-inkpik.md doit être créé à la racine du projet

git add audit-global-inkpik.md
git commit -m "docs: audit global complet Ink&Pik"

echo "=== AUDIT GLOBAL TERMINÉ ==="
```
