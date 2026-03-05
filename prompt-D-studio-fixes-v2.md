# 🔧 PROMPT D — FIXES STUDIO V2
# Pour Claude Code — 6 correctifs studio post-Prompt C
# Commit après chaque fix

## CONTEXTE

Le Prompt C a mis en place la structure studio (sidebar, dashboard, planning, stats, fiches clients, Filament). Mais 6 problèmes subsistent après les tests manuels. Ce prompt les corrige.

Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, Filament v4.5.

**RAPPEL CRITIQUE** : Un studio est par définition PRO (plan Studio = 0% commission). Les artistes rattachés au studio sont automatiquement `is_subscribed = true`. Aucune fonctionnalité ne doit être bloquée par un check PRO pour un utilisateur studio ou un artiste rattaché à un studio.

---

## PHASE 0 — AUDIT CIBLÉ

```bash
echo "=== AUDIT PROMPT D ==="

# ── D1 : MODAL "FONCTIONNALITÉ PRO" ──
echo "--- D1: MODAL PRO ---"

# D1a. Trouver TOUTES les occurrences de check PRO / is_subscribed / plan dans les vues studio
grep -rn "is_subscribed\|is_pro\|plan.*pro\|PRO\|fonctionnalité.*pro\|feature.*pro\|upgrade\|passer.*pro" resources/views/studio/ resources/views/livewire/studio/ --include="*.blade.php" 2>/dev/null | head -20

# D1b. Trouver les modales PRO
grep -rn "Fonctionnalité PRO\|Passez au plan\|passer.*PRO\|49.99\|fonctionnalite-pro\|pro-modal\|proModal" resources/views/ --include="*.blade.php" -l 2>/dev/null | head -10

# D1c. Vérifier la vue client-show studio (là où la modale apparaît)
cat resources/views/studio/client-show.blade.php 2>/dev/null | head -100
# OU
find resources/views/studio -name "*client*show*" -o -name "*client*detail*" | head -5

# D1d. Vérifier le composant Livewire client-show studio si c'est un composant
find app/Livewire -path "*Studio*" -name "*Client*" | head -5

# D1e. Policy ou middleware qui bloque les features
grep -rn "is_subscribed\|is_pro\|canAccess\|authorize\|PRO" app/Http/Controllers/StudioController.php | head -15

# D1f. Check dans la vue tattooer client-show (pour comparer — c'est peut-être un partiel partagé)
grep -rn "is_subscribed\|is_pro\|PRO\|fonctionnalité" resources/views/tattooer/client-show.blade.php 2>/dev/null | head -15

# D1g. Le check exact dans les vues consentement et traçabilité
grep -B 5 -A 10 "is_subscribed\|is_pro\|PRO" resources/views/tattooer/client-show.blade.php resources/views/studio/client-show.blade.php resources/views/partials/client-* 2>/dev/null | head -40


# ── D2 : PLANNING — RDV PAS AFFICHÉS ──
echo "--- D2: PLANNING ---"

# D2a. Route events JSON
php artisan route:list 2>&1 | grep "planning\|events\|calendar" | head -10

# D2b. Controller méthode events
grep -B 5 -A 30 "function.*event\|function.*planning" app/Http/Controllers/StudioController.php 2>/dev/null | head -50
grep -B 5 -A 30 "function.*event\|function.*getEvent" app/Livewire/Studio/Calendar.php 2>/dev/null | head -40

# D2c. Vérifier le format JSON retourné par l'endpoint
# Chercher le format attendu par FullCalendar
grep -n "events\|FullCalendar\|eventSources\|url.*events\|fetch.*events" resources/views/studio/planning.blade.php resources/views/livewire/studio/calendar.blade.php 2>/dev/null | head -15

# D2d. Vérifier que l'endpoint retourne des données
php artisan tinker --execute="
  use App\Models\BookingRequest;
  use App\Enums\BookingRequestStatus;
  \$confirmed = BookingRequest::whereIn('status', [
    BookingRequestStatus::CONFIRMED,
    BookingRequestStatus::DEPOSIT_PAID,
    BookingRequestStatus::DESIGN_SENT,
  ])->whereNotNull('appointment_date')->count();
  echo 'Bookings avec appointment_date et statut confirmé: ' . \$confirmed . PHP_EOL;
  
  // Vérifier la colonne appointment_date
  \$cols = Schema::getColumnListing('booking_requests');
  \$dateCol = array_filter(\$cols, fn(\$c) => str_contains(\$c, 'date') || str_contains(\$c, 'appointment'));
  echo 'Colonnes date: ' . implode(', ', \$dateCol) . PHP_EOL;
"

# D2e. Vérifier s'il y a des bookings pour les artistes du studio de test
php artisan tinker --execute="
  use App\Models\Studio;
  \$studio = Studio::first();
  if (\$studio) {
    \$tIds = \$studio->tattooers()->pluck('id')->toArray();
    \$pIds = \$studio->piercers()->pluck('id')->toArray();
    echo 'Studio: ' . \$studio->name . PHP_EOL;
    echo 'Tattooers: ' . implode(',', \$tIds) . PHP_EOL;
    echo 'Piercers: ' . implode(',', \$pIds) . PHP_EOL;
    
    use App\Models\BookingRequest;
    \$bookings = BookingRequest::where(function(\$q) use (\$tIds, \$pIds) {
      \$q->where(function(\$s) use (\$tIds) {
        \$s->where('bookable_type', 'App\\\Models\\\Tattooer')->whereIn('bookable_id', \$tIds);
      })->orWhere(function(\$s) use (\$pIds) {
        \$s->where('bookable_type', 'App\\\Models\\\Piercer')->whereIn('bookable_id', \$pIds);
      });
    })->get();
    echo 'Bookings studio: ' . \$bookings->count() . PHP_EOL;
    foreach(\$bookings->take(3) as \$b) {
      echo '  #' . \$b->id . ' status=' . \$b->status->value . ' appointment_date=' . (\$b->appointment_date ?? 'NULL') . PHP_EOL;
    }
  } else {
    echo 'Aucun studio trouvé' . PHP_EOL;
  }
"


# ── D3 : STATS — ACOMPTES = 0€ ──
echo "--- D3: STATS PAIEMENTS ---"

# D3a. Colonnes financières exactes
php artisan tinker --execute="
  \$cols = Schema::getColumnListing('booking_requests');
  \$finance = array_filter(\$cols, fn(\$c) => str_contains(\$c, 'price') || str_contains(\$c, 'amount') || str_contains(\$c, 'deposit') || str_contains(\$c, 'commission') || str_contains(\$c, 'payment') || str_contains(\$c, 'revenue') || str_contains(\$c, 'total'));
  echo 'Colonnes financières: ' . implode(', ', \$finance) . PHP_EOL;
  
  // Vérifier les valeurs réelles
  use App\Models\BookingRequest;
  \$b = BookingRequest::whereNotNull('total_price')->orWhereNotNull('price')->first();
  if (\$b) {
    echo 'Exemple booking #' . \$b->id . ':' . PHP_EOL;
    foreach(\$finance as \$c) {
      echo '  ' . \$c . ' = ' . (\$b->\$c ?? 'NULL') . PHP_EOL;
    }
  } else {
    echo 'Aucun booking avec montant trouvé' . PHP_EOL;
  }
"

# D3b. Vérifier la query stats
grep -n "sum\|total_price\|price\|amount\|deposit\|revenue\|commission" app/Http/Controllers/StudioController.php | head -15

# D3c. Type des colonnes (int = centimes ? decimal = euros ?)
php artisan tinker --execute="
  \$connection = config('database.default');
  \$driver = config(\"database.connections.\$connection.driver\");
  if (\$driver === 'mysql') {
    \$cols = DB::select('DESCRIBE booking_requests');
    foreach(\$cols as \$col) {
      if (str_contains(\$col->Field, 'price') || str_contains(\$col->Field, 'amount') || str_contains(\$col->Field, 'deposit') || str_contains(\$col->Field, 'commission') || str_contains(\$col->Field, 'total')) {
        echo \$col->Field . ': ' . \$col->Type . ' (Null: ' . \$col->Null . ')' . PHP_EOL;
      }
    }
  }
"


# ── D4 : FICHES CLIENTS SANS ACOMPTE ──
echo "--- D4: FILTRE CLIENTS ---"

# D4a. La query qui charge les clients dans le controller
grep -B 5 -A 30 "function clients\b" app/Http/Controllers/StudioController.php 2>/dev/null

# D4b. Vérifier si deposit_paid_at existe
php artisan tinker --execute="
  echo 'deposit_paid_at exists: ' . (Schema::hasColumn('booking_requests', 'deposit_paid_at') ? 'OUI' : 'NON') . PHP_EOL;
  // Chercher la bonne colonne
  \$cols = Schema::getColumnListing('booking_requests');
  \$depositCols = array_filter(\$cols, fn(\$c) => str_contains(\$c, 'deposit') || str_contains(\$c, 'paid'));
  echo 'Colonnes deposit/paid: ' . implode(', ', \$depositCols) . PHP_EOL;
"


# ── D5 : ARTIST DETAIL /studio/artists/{id} ──
echo "--- D5: ARTIST DETAIL ---"

# D5a. Vue actuelle
cat resources/views/studio/artist-show.blade.php 2>/dev/null | head -60
# OU
find resources/views/studio -name "*artist*show*" -o -name "*artist*detail*" | head -5

# D5b. Controller méthode
grep -B 5 -A 30 "function artistShow\|function showArtist\|function artist_show" app/Http/Controllers/StudioController.php 2>/dev/null | head -40

# D5c. Données envoyées à la vue
grep "compact\|return view\|with(" app/Http/Controllers/StudioController.php | grep -i "artist" | head -5


# ── D6 : LIEN FILAMENT SIDEBAR ──
echo "--- D6: LIEN FILAMENT ---"

# D6a. Panel Filament studio — URL de base
grep -rn "path\|getUrl\|slug\|getId" app/Providers/Filament/ 2>/dev/null | grep -i "studio" | head -5
# OU
find app/Filament -name "*StudioPanelProvider*" -o -name "*Panel*" | head -5
grep -n "path\|slug\|id" app/Filament/Studio/StudioPanelProvider.php 2>/dev/null | head -5

# D6b. URL réelle du panel Filament studio
php artisan route:list 2>&1 | grep "filament\|admin.*studio\|studio.*filament" | head -10

# D6c. Sidebar actuelle
cat resources/views/studio/partials/sidebar.blade.php 2>/dev/null | head -50
# OU chercher le fichier
find resources/views -path "*studio*" -name "*sidebar*" -o -path "*studio*" -name "*nav*" | head -5

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX D1 — MODAL "FONCTIONNALITÉ PRO" BLOQUE LE STUDIO

### Problème
Quand le studio accède aux fiches clients (consentements, traçabilité), une modale "Fonctionnalité PRO" apparaît et bloque l'accès. C'est incorrect car le studio EST PRO par définition.

### Cause probable
La vue partage probablement un template ou composant avec le tattooer qui vérifie `$artisan->is_subscribed` ou similaire. Mais dans le contexte studio, l'artiste consulté peut être un artiste du studio qui a `is_subscribed = true` grâce au studio, pas par abonnement personnel.

OU : la vue studio réutilise le template tattooer/client-show qui contient un check `@if (!auth()->user()->tattooer->is_subscribed)` alors que l'user connecté est un studio owner (pas un tattooer).

### Fix

Trouver TOUTES les vérifications PRO dans les vues et s'assurer qu'elles prennent en compte le contexte studio :

```bash
# Lister TOUS les endroits avec le check PRO
grep -rn "is_subscribed\|is_pro\|Fonctionnalité PRO\|plan.*PRO\|passer.*PRO\|proModal\|pro-modal\|upgrade.*pro" resources/views/ --include="*.blade.php" | head -30
```

Pour CHAQUE occurrence trouvée dans les vues studio ou partagées :

**Option A** — Si le check est `@if (!$artisan->is_subscribed)` ou similaire :
```blade
{{-- AVANT (bloque le studio) --}}
@if (!$artisan->is_subscribed)
    {{-- Modal PRO --}}
@endif

{{-- APRÈS (exclure le contexte studio) --}}
@php
    $isStudioContext = auth()->user()->studio !== null || auth()->user()->hasRole('studio');
    $isPro = $artisan->is_subscribed ?? false;
@endphp
@if (!$isPro && !$isStudioContext)
    {{-- Modal PRO --}}
@endif
```

**Option B** — Si le check est dans un middleware ou policy :
```php
// Vérifier si l'utilisateur est studio owner
$user = auth()->user();
$isPro = false;

if ($user->studio) {
    $isPro = true; // Studio est toujours PRO
} elseif ($user->tattooer) {
    $isPro = $user->tattooer->is_subscribed;
} elseif ($user->piercer) {
    $isPro = $user->piercer->is_subscribed;
}
```

**Option C** — Créer un helper réutilisable :
```php
// app/Helpers/SubscriptionHelper.php
namespace App\Helpers;

class SubscriptionHelper
{
    /**
     * Vérifier si l'utilisateur actuel a accès aux fonctionnalités PRO.
     * Un studio owner a TOUJOURS accès PRO.
     * Un artiste rattaché à un studio a TOUJOURS accès PRO.
     */
    public static function isPro(?object $user = null): bool
    {
        $user = $user ?? auth()->user();
        if (!$user) return false;

        // Studio owner = toujours PRO
        if ($user->studio || $user->hasRole('studio')) return true;

        // Artiste avec abonnement actif
        if ($user->tattooer?->is_subscribed) return true;
        if ($user->piercer?->is_subscribed) return true;

        // Artiste rattaché à un studio actif
        if ($user->tattooer?->studio_id) return true;
        if ($user->piercer?->studio_id) return true;

        return false;
    }
}
```

Et remplacer TOUS les checks `$artisan->is_subscribed` par `\App\Helpers\SubscriptionHelper::isPro()` dans les vues, OU enregistrer un alias Blade :

```php
// Dans AppServiceProvider::boot()
Blade::if('pro', function () {
    return \App\Helpers\SubscriptionHelper::isPro();
});
```

Puis dans les vues :
```blade
{{-- AVANT --}}
@if ($artisan->is_subscribed)
    {{-- contenu PRO --}}
@else
    {{-- modal PRO --}}
@endif

{{-- APRÈS --}}
@pro
    {{-- contenu PRO --}}
@else
    {{-- modal PRO --}}
@endpro
```

Appliquer cette correction dans TOUTES les vues qui affichent la modale PRO, en particulier :
- La vue fiche client (studio et tattooer)
- Les sections consentement et traçabilité
- Toute autre vue qui restreint des fonctionnalités

```bash
git add -A && git commit -m "fix(D1): supprimer blocage PRO pour studio — helper isPro() + directive @pro"
```

---

## FIX D2 — PLANNING : RDV NON AFFICHÉS

### Problème
Le calendrier FullCalendar est visible mais vide — les RDV ne s'affichent pas.

### Causes possibles
1. L'endpoint `/studio/planning/events` ne retourne pas de données (query incorrecte)
2. Le format JSON ne correspond pas à ce qu'attend FullCalendar
3. La colonne `appointment_date` est NULL pour les bookings existants
4. L'endpoint n'est pas appelé correctement par FullCalendar (URL incorrecte)

### Diagnostic

Vérifier d'abord en tinker (Phase 0 le fait déjà). Puis :

```bash
# Vérifier l'endpoint directement
curl -s "http://tattoolib-saas.test/studio/planning/events" -H "Cookie: $(cat /tmp/session_cookie 2>/dev/null)" | head -100
# OU accéder via le navigateur en étant connecté
```

### Fix

**Si la query ne retourne rien** — vérifier les colonnes :

```php
// L'endpoint events doit retourner ce format pour FullCalendar v6 :
[
    {
        "id": 1,
        "title": "Client Dupont — Artiste Martin",
        "start": "2026-03-15T10:00:00",
        "end": "2026-03-15T12:00:00",
        "color": "#C97435",
        "url": "/studio/demandes/1"
    }
]
```

Vérifier que :
1. La colonne `appointment_date` (ou `selected_date`, `confirmed_date`, `appointment_at`) existe et contient des données
2. La query filtre correctement par artistes du studio
3. Les statuts filtrés correspondent aux bookings existants

```php
// Fix de la méthode events dans StudioController
public function planningEvents()
{
    $studio = auth()->user()->studio;
    $tattooerIds = $studio->tattooers()->pluck('id')->toArray();
    $piercerIds = $studio->piercers()->pluck('id')->toArray();

    $bookings = BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($sub) use ($tattooerIds) {
                $sub->where('bookable_type', 'App\\Models\\Tattooer')
                    ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($sub) use ($piercerIds) {
                $sub->where('bookable_type', 'App\\Models\\Piercer')
                    ->whereIn('bookable_id', $piercerIds);
            });
        })
        // IMPORTANT : inclure TOUS les statuts qui ont un RDV
        // Pas seulement COMPLETED — aussi ACCEPTED, DEPOSIT_PAID, DESIGN_SENT, CONFIRMED
        ->whereIn('status', [
            BookingRequestStatus::ACCEPTED,
            BookingRequestStatus::DEPOSIT_PAID,
            BookingRequestStatus::DESIGN_SENT,
            BookingRequestStatus::CONFIRMED,
            BookingRequestStatus::COMPLETED,
        ])
        // Vérifier le VRAI nom de la colonne date
        // Possible : appointment_date, selected_date, confirmed_date, appointment_at
        ->whereNotNull('appointment_date') // ← ADAPTER le nom de colonne
        ->with(['client.user', 'bookable.user'])
        ->get();

    $events = $bookings->map(function ($booking) {
        $start = $booking->appointment_date; // ← ADAPTER
        
        return [
            'id' => $booking->id,
            'title' => ($booking->client?->user?->name ?? 'Client') 
                     . ' — ' 
                     . ($booking->bookable?->user?->name ?? 'Artiste'),
            'start' => $start instanceof \Carbon\Carbon ? $start->toIso8601String() : $start,
            'end' => $start instanceof \Carbon\Carbon ? $start->addHours(2)->toIso8601String() : null,
            'color' => match($booking->status) {
                BookingRequestStatus::ACCEPTED => '#f59e0b',
                BookingRequestStatus::DEPOSIT_PAID => '#3b82f6',
                BookingRequestStatus::DESIGN_SENT => '#8b5cf6',
                BookingRequestStatus::CONFIRMED => '#C97435',
                BookingRequestStatus::COMPLETED => '#22c55e',
                default => '#6b7280',
            },
            'url' => route('studio.demandes.show', $booking),
        ];
    });

    return response()->json($events);
}
```

**Si FullCalendar ne charge pas l'endpoint** — vérifier la config dans la vue :

```javascript
// FullCalendar doit utiliser eventSources ou events en tant qu'URL
const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'fr',
    events: '/studio/planning/events', // ← URL de l'endpoint JSON
    // OU
    eventSources: [{
        url: '/studio/planning/events',
        method: 'GET',
        extraParams: function() {
            return { _token: document.querySelector('meta[name="csrf-token"]')?.content };
        },
        failure: function() {
            console.error('Erreur chargement événements');
        },
    }],
    // ... reste de la config
});
```

**Si les bookings n'ont pas de `appointment_date` renseignée** — c'est un problème de données. Vérifier :
```bash
php artisan tinker --execute="
  use App\Models\BookingRequest;
  // Chercher toutes les colonnes qui pourraient contenir la date du RDV
  \$b = BookingRequest::first();
  if (\$b) {
    \$attrs = \$b->getAttributes();
    foreach(\$attrs as \$k => \$v) {
      if (str_contains(\$k, 'date') || str_contains(\$k, 'time') || str_contains(\$k, 'appointment') || str_contains(\$k, 'selected') || str_contains(\$k, 'confirmed') || str_contains(\$k, 'scheduled')) {
        echo \$k . ' = ' . (\$v ?? 'NULL') . PHP_EOL;
      }
    }
  }
"
```

Il est possible que la date soit dans `selected_date` ou `proposed_dates` (JSON) plutôt que `appointment_date`. Adapter la query en conséquence.

```bash
git add -A && git commit -m "fix(D2): planning studio — events endpoint corrigé + FullCalendar charge les RDV"
```

---

## FIX D3 — STATS : ACOMPTES ET PAIEMENTS = 0€

### Problème
La page statistiques affiche 0€ pour les acomptes et paiements.

### Cause
La query utilise probablement le mauvais nom de colonne (ex: `total_price` alors que c'est `price`, ou `deposit_amount` alors que c'est `total_deposit_amount`) OU les montants sont stockés différemment.

### Fix

Phase 0 révèle les vrais noms de colonnes financières. Corriger le controller stats :

```bash
# Vérifier les colonnes exactes
grep -n "sum\|total_price\|price\|deposit\|amount\|revenue\|payment" app/Http/Controllers/StudioController.php | head -20
```

Remplacer TOUTES les occurrences par les vrais noms de colonnes. Exemple :

```php
// Si les colonnes sont : price, deposit_amount, commission_amount
// AVANT (probablement mauvais) :
$monthlyRevenue = (clone $baseQuery)->sum('total_price');

// APRÈS (adapté aux vraies colonnes) :
$monthlyRevenue = (clone $baseQuery)->sum('price'); // ← nom réel
```

**Si les montants sont en centimes** (type `int`/`bigint`), diviser par 100 dans la vue :
```blade
{{ number_format($monthlyRevenue / 100, 2, ',', ' ') }} €
```

**Si les montants sont en euros** (type `decimal`), pas de division :
```blade
{{ number_format($monthlyRevenue, 2, ',', ' ') }} €
```

Vérifier aussi que les bookings de test ont des montants renseignés :
```bash
php artisan tinker --execute="
  use App\Models\BookingRequest;
  \$bookings = BookingRequest::whereNotNull('price')->orWhereNotNull('total_price')->limit(5)->get();
  foreach(\$bookings as \$b) {
    echo '#' . \$b->id . ' price=' . \$b->price . ' deposit=' . \$b->deposit_amount . ' total=' . (\$b->total_price ?? 'NULL') . PHP_EOL;
  }
"
```

**Appliquer le même fix dans** :
1. `StudioController@stats` — toutes les sum()
2. `StudioController@dashboard` — le compteur revenus du mois
3. Les widgets Filament (`StudioStatsOverview`, `RevenueByArtistChart`, `MonthlyRevenueChart`)
4. `StudioController@artistShow` — si des montants sont affichés

```bash
git add -A && git commit -m "fix(D3): stats studio — colonnes financières corrigées + montants affichés correctement"
```

---

## FIX D4 — FICHES CLIENTS : EXCLURE CLIENTS SANS ACOMPTE

### Problème
La liste des fiches clients du studio affiche des clients qui n'ont pas encore payé l'acompte. La règle métier est : **pas d'acompte payé = pas de fiche client créée**.

### Fix

Modifier la query dans `StudioController@clients` :

```php
public function clients()
{
    $studio = auth()->user()->studio;
    $tattooerIds = $studio->tattooers()->pluck('id')->toArray();
    $piercerIds = $studio->piercers()->pluck('id')->toArray();

    $clients = Client::whereHas('bookingRequests', function ($q) use ($tattooerIds, $piercerIds) {
        $q->where(function ($sub) use ($tattooerIds, $piercerIds) {
            $sub->where(function ($s) use ($tattooerIds) {
                $s->where('bookable_type', 'App\\Models\\Tattooer')
                    ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($s) use ($piercerIds) {
                $s->where('bookable_type', 'App\\Models\\Piercer')
                    ->whereIn('bookable_id', $piercerIds);
            });
        })
        // ═══ AJOUTER : filtre acompte payé ═══
        ->whereNotNull('deposit_paid_at') // ← ADAPTER le nom de colonne
        // OU si le filtre est par statut :
        ->whereIn('status', [
            BookingRequestStatus::DEPOSIT_PAID,
            BookingRequestStatus::DESIGN_SENT,
            BookingRequestStatus::CONFIRMED,
            BookingRequestStatus::COMPLETED,
        ]);
    })
    ->with('user')
    ->withCount(['bookingRequests' => function ($q) use ($tattooerIds, $piercerIds) {
        $q->where(function ($sub) use ($tattooerIds, $piercerIds) {
            $sub->where(function ($s) use ($tattooerIds) {
                $s->where('bookable_type', 'App\\Models\\Tattooer')
                    ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($s) use ($piercerIds) {
                $s->where('bookable_type', 'App\\Models\\Piercer')
                    ->whereIn('bookable_id', $piercerIds);
            });
        })
        ->whereNotNull('deposit_paid_at'); // ← même filtre
    }])
    ->orderBy('created_at', 'desc')
    ->paginate(20);

    return view('studio.clients', compact('clients'));
}
```

**Appliquer le même filtre** dans la vue tattooer/client si elle a le même problème (vérifier).

Le même filtre doit aussi être appliqué dans le controller tattooer pour la liste de fiches clients si applicable :
```bash
grep -B 5 -A 20 "function clients\b" app/Http/Controllers/TattooerController.php 2>/dev/null | head -30
```

```bash
git add -A && git commit -m "fix(D4): fiches clients — exclure clients sans acompte payé"
```

---

## FIX D5 — `/studio/artists/{id}` : ENRICHIR AVEC PAIEMENTS ET SUIVI

### Problème
La vue détail artiste depuis le studio est trop simple. Il manque les paiements reçus et le suivi des demandes.

### Fix

Enrichir `StudioController@artistShow` :

```php
public function artistShow($id)
{
    $studio = auth()->user()->studio;
    
    // Trouver l'artiste (tattooer ou pierceur)
    $tattooer = $studio->tattooers()->where('id', $id)->first();
    $piercer = null;
    if (!$tattooer) {
        $piercer = $studio->piercers()->where('id', $id)->first();
    }
    $artist = $tattooer ?? $piercer;
    abort_unless($artist, 404);

    $artistType = $tattooer ? 'App\\Models\\Tattooer' : 'App\\Models\\Piercer';

    // Demandes de l'artiste
    $bookings = BookingRequest::where('bookable_type', $artistType)
        ->where('bookable_id', $artist->id)
        ->with('client.user')
        ->orderBy('created_at', 'desc')
        ->get();

    // Stats de l'artiste
    $totalBookings = $bookings->count();
    $completedBookings = $bookings->where('status', BookingRequestStatus::COMPLETED)->count();
    $pendingBookings = $bookings->where('status', BookingRequestStatus::PENDING)->count();
    
    // Paiements reçus (adapter les noms de colonnes)
    $totalRevenue = $bookings->whereIn('status', [BookingRequestStatus::COMPLETED, BookingRequestStatus::CONFIRMED])
        ->sum('price'); // ← ADAPTER le nom de colonne
    $totalDeposits = $bookings->whereNotNull('deposit_paid_at')
        ->sum('deposit_amount'); // ← ADAPTER
    $totalCommissions = $bookings->whereIn('status', [BookingRequestStatus::COMPLETED, BookingRequestStatus::CONFIRMED])
        ->sum('commission_amount'); // ← ADAPTER

    // Demandes récentes (les 10 dernières)
    $recentBookings = $bookings->take(10);

    // Prochains RDV
    $upcomingAppointments = $bookings
        ->whereIn('status.value', ['confirmed', 'deposit_paid', 'design_sent'])
        ->where('appointment_date', '>=', now()) // ← ADAPTER le nom
        ->sortBy('appointment_date')
        ->take(5);

    // Clients uniques
    $uniqueClientsCount = $bookings->pluck('client_id')->unique()->count();

    // Note moyenne
    $averageRating = $artist->reviews()->avg('rating');
    $reviewsCount = $artist->reviews()->count();

    return view('studio.artist-show', compact(
        'artist', 'studio', 'bookings', 'recentBookings',
        'totalBookings', 'completedBookings', 'pendingBookings',
        'totalRevenue', 'totalDeposits', 'totalCommissions',
        'upcomingAppointments', 'uniqueClientsCount',
        'averageRating', 'reviewsCount',
    ));
}
```

Enrichir la vue `studio/artist-show.blade.php` avec :

```blade
{{-- Section 1 : Profil artiste (déjà existant — garder) --}}

{{-- Section 2 : Compteurs stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
    <div class="bg-gris-fonde rounded-xl border border-titane/10 p-4 text-center">
        <p class="text-2xl font-bold text-ivoire-text">{{ $totalBookings }}</p>
        <p class="text-xs text-titane mt-1">Demandes totales</p>
    </div>
    <div class="bg-gris-fonde rounded-xl border border-titane/10 p-4 text-center">
        <p class="text-2xl font-bold text-green-400">{{ $completedBookings }}</p>
        <p class="text-xs text-titane mt-1">Terminées</p>
    </div>
    <div class="bg-gris-fonde rounded-xl border border-titane/10 p-4 text-center">
        <p class="text-2xl font-bold text-ivoire-text">{{ $uniqueClientsCount }}</p>
        <p class="text-xs text-titane mt-1">Clients uniques</p>
    </div>
    <div class="bg-gris-fonde rounded-xl border border-titane/10 p-4 text-center">
        <p class="text-2xl font-bold text-beige-peau">
            {{ $averageRating ? number_format($averageRating, 1) . '/5' : '—' }}
        </p>
        <p class="text-xs text-titane mt-1">Note moyenne ({{ $reviewsCount }} avis)</p>
    </div>
</div>

{{-- Section 3 : Paiements reçus --}}
<div class="mt-6 bg-gris-fonde rounded-xl border border-titane/10 p-5">
    <h3 class="text-sm font-semibold text-ivoire-text mb-4">Paiements reçus</h3>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <p class="text-xs text-titane">Revenus bruts</p>
            <p class="text-lg font-bold text-ivoire-text">{{ number_format($totalRevenue / 100, 2, ',', ' ') }} €</p>
        </div>
        <div>
            <p class="text-xs text-titane">Acomptes reçus</p>
            <p class="text-lg font-bold text-ivoire-text">{{ number_format($totalDeposits / 100, 2, ',', ' ') }} €</p>
        </div>
        <div>
            <p class="text-xs text-titane">Commissions Ink&Pik</p>
            <p class="text-lg font-bold text-rouge-alerte">- {{ number_format($totalCommissions / 100, 2, ',', ' ') }} €</p>
        </div>
    </div>
</div>

{{-- Section 4 : Prochains RDV --}}
@if ($upcomingAppointments->count() > 0)
<div class="mt-6 bg-gris-fonde rounded-xl border border-titane/10 p-5">
    <h3 class="text-sm font-semibold text-ivoire-text mb-4">Prochains rendez-vous</h3>
    <div class="space-y-2">
        @foreach ($upcomingAppointments as $appt)
        <div class="flex items-center justify-between p-3 bg-noir-profond/30 rounded-lg">
            <div>
                <p class="text-sm text-ivoire-text">{{ $appt->client?->user?->name ?? 'Client' }}</p>
                <p class="text-xs text-titane">{{ $appt->appointment_date?->translatedFormat('l d M Y à H:i') ?? '—' }}</p>
            </div>
            <a href="{{ route('studio.demandes.show', $appt) }}" class="text-xs text-beige-peau hover:underline">Détails</a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Section 5 : Dernières demandes --}}
<div class="mt-6 bg-gris-fonde rounded-xl border border-titane/10 p-5">
    <h3 class="text-sm font-semibold text-ivoire-text mb-4">Dernières demandes</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-titane uppercase border-b border-titane/10">
                    <th class="py-2 text-left">Date</th>
                    <th class="py-2 text-left">Client</th>
                    <th class="py-2 text-left">Statut</th>
                    <th class="py-2 text-right">Montant</th>
                    <th class="py-2 text-right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentBookings as $booking)
                <tr class="border-b border-titane/5">
                    <td class="py-2 text-titane">{{ $booking->created_at?->format('d/m/Y') }}</td>
                    <td class="py-2 text-ivoire-text">{{ $booking->client?->user?->name ?? '—' }}</td>
                    <td class="py-2">
                        <span class="px-2 py-0.5 text-xs rounded-full 
                            {{ $booking->status === BookingRequestStatus::COMPLETED ? 'bg-green-500/10 text-green-400' : 
                               ($booking->status === BookingRequestStatus::PENDING ? 'bg-yellow-500/10 text-yellow-400' : 
                               'bg-titane/10 text-titane') }}">
                            {{ $booking->status?->label() ?? $booking->status?->value ?? '—' }}
                        </span>
                    </td>
                    <td class="py-2 text-right text-ivoire-text">{{ number_format(($booking->price ?? 0) / 100, 2, ',', ' ') }} €</td>
                    <td class="py-2 text-right">
                        <a href="{{ route('studio.demandes.show', $booking) }}" class="text-xs text-beige-peau hover:underline">Voir</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
```

IMPORTANT : Adapter les noms de colonnes (`price`, `deposit_amount`, `commission_amount`, `appointment_date`) et la division par 100 selon les résultats de Phase 0.

```bash
git add -A && git commit -m "fix(D5): artist-show studio enrichi — paiements, RDV, demandes, stats"
```

---

## FIX D6 — LIEN FILAMENT DANS SIDEBAR + NAVBOTTOM

### Problème
Pas de lien vers le dashboard Filament studio `/admin/studio/` dans la navigation du studio.

### Fix

Trouver l'URL correcte du panel Filament studio :
```bash
php artisan route:list 2>&1 | grep "filament.*studio\|admin.*studio" | head -5
```

L'URL est probablement `/admin/studio` ou `/studio/admin` — adapter.

Ajouter dans la sidebar :

```blade
{{-- Dans la sidebar studio — en bas, avant la déconnexion --}}
<div class="border-t border-titane/10 pt-4 mt-4">
    <a href="/admin/studio" target="_blank"
        class="flex items-center gap-3 px-3 py-2 text-titane hover:text-beige-peau hover:bg-beige-peau/5 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Gestion avancée
        <svg class="w-3 h-3 ml-auto text-titane/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
    </a>
</div>
```

Ajouter dans le navbottom mobile (si l'espace le permet — sinon dans un menu "Plus") :

```blade
{{-- Dans le navbottom studio --}}
<a href="/admin/studio" target="_blank"
    class="{{ request()->is('admin/studio*') ? 'text-beige-peau' : 'text-titane' }} flex flex-col items-center gap-0.5 text-xs">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    Gestion
</a>
```

```bash
git add -A && git commit -m "fix(D6): lien Filament 'Gestion avancée' dans sidebar + navbottom studio"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT D ==="

# V1. Modal PRO
echo "--- D1: MODAL PRO ---"
grep -c "isPro\|SubscriptionHelper\|@pro" resources/views/ -r --include="*.blade.php" 2>/dev/null
echo "Helper isPro utilisé (doit être > 0)"
grep -c "Fonctionnalité PRO\|passer.*PRO" resources/views/studio/ -r --include="*.blade.php" 2>/dev/null
echo "Modales PRO dans vues studio (devrait être 0 — toutes protégées par @pro)"

# V2. Planning
echo "--- D2: PLANNING ---"
php artisan route:list 2>&1 | grep "planning.*event" | head -3
echo "Route events OK si > 0"

# V3. Stats
echo "--- D3: STATS ---"
grep -c "sum(" app/Http/Controllers/StudioController.php 2>/dev/null
echo "Queries sum dans controller (vérifier les noms de colonnes)"

# V4. Clients filtrés
echo "--- D4: CLIENTS ---"
grep -c "deposit_paid_at\|DEPOSIT_PAID\|deposit" app/Http/Controllers/StudioController.php 2>/dev/null
echo "Filtre acompte dans la query clients (doit être > 0)"

# V5. Artist show enrichi
echo "--- D5: ARTIST SHOW ---"
grep -c "totalRevenue\|totalDeposits\|recentBookings\|upcomingAppointments" app/Http/Controllers/StudioController.php 2>/dev/null
echo "Données enrichies dans artistShow (doit être > 0)"

# V6. Lien Filament
echo "--- D6: LIEN FILAMENT ---"
grep -c "admin/studio\|Gestion avancée\|filament" resources/views/studio/partials/sidebar.blade.php resources/views/studio/partials/nav* 2>/dev/null
echo "Lien Filament dans sidebar (doit être > 0)"

# V7. Compilation
php artisan route:clear && php artisan view:clear
php artisan route:list 2>&1 | head -3
echo "Pas d'erreur = OK"

echo "=== PROMPT D TERMINÉ — 6 fixes ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire
2. **Studio = TOUJOURS PRO** — Aucune modale PRO ne doit bloquer un studio owner
3. **Noms de colonnes** — Les colonnes financières et de dates DOIVENT être vérifiées en Phase 0
4. **Centimes vs euros** — Vérifier le type de colonne avant de diviser par 100
5. **Filtre acompte** — `whereNotNull('deposit_paid_at')` OU filtre par statut >= DEPOSIT_PAID
6. **Planning** — Le format JSON DOIT matcher ce qu'attend FullCalendar v6 (start/end en ISO 8601)
7. **Ne pas casser les artistes indépendants** — Les fixes D1/D3/D4 s'appliquent au studio, ne pas altérer le comportement tattooer
8. **Commit après chaque fix** (6 commits)
