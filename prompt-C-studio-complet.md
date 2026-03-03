# 🏢 PROMPT C — STUDIO COMPLET
# Pour Claude Code — Sidebar, dashboard, demandes, planning, cards, stats, Filament
# Commit après chaque fix. Ce prompt est le plus gros (~25-30 min).

## CONTEXTE

8 problèmes liés au module Studio du SaaS Ink&Pik.
Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, Filament v4.5.

---

## PHASE 0 — AUDIT GLOBAL STUDIO

```bash
echo "=== AUDIT PROMPT C — STUDIO ==="

# ── STRUCTURE GÉNÉRALE ──
echo "--- STRUCTURE ---"

# C0a. Routes studio
php artisan route:list --name="studio" --columns=method,uri,name,action 2>&1 | head -40

# C0b. StudioController — méthodes
grep -n "function " app/Http/Controllers/StudioController.php | head -30

# C0c. Vues studio existantes
find resources/views/studio -type f -name "*.blade.php" | sort

# C0d. Composants Livewire studio
find app/Livewire/Studio -type f -name "*.php" 2>/dev/null | sort
find app/Livewire -path "*studio*" -o -path "*Studio*" 2>/dev/null | sort

# C0e. Layout studio (sidebar + navbottom)
grep -n "nav\|sidebar\|menu\|link\|route\|href" resources/views/studio/partials/sidebar.blade.php 2>/dev/null | head -30
# OU
grep -n "nav\|sidebar\|menu" resources/views/layouts/studio.blade.php 2>/dev/null | head -30
find resources/views -path "*studio*" -name "*sidebar*" -o -path "*studio*" -name "*nav*" | head -5

# C0f. Navbottom mobile
find resources/views -path "*studio*" -name "*bottom*" -o -path "*studio*" -name "*mobile*" | head -5
grep -rn "bottom-nav\|nav-bottom\|navbottom\|mobile-nav" resources/views/ --include="*.blade.php" -l | head -5


# ── C1 : FICHES CLIENT (sidebar) ──
echo "--- FICHES CLIENT ---"

# C1a. Route fiches clients studio
php artisan route:list 2>&1 | grep -i "studio.*client\|studio.*fiche" | head -5

# C1b. Vue existante
find resources/views/studio -name "*client*" | head -5

# C1c. Modèle Client — relation studio
grep -n "studio\|tattooer\|bookable" app/Models/Client.php | head -10


# ── C2 : DASHBOARD COMPTEURS + NOTIFICATIONS ──
echo "--- DASHBOARD ---"

# C2a. Vue dashboard studio
cat resources/views/studio/dashboard.blade.php 2>/dev/null | head -60

# C2b. Controller dashboard — données envoyées
grep -B 5 -A 30 "function dashboard" app/Http/Controllers/StudioController.php 2>/dev/null

# C2c. Notifications studio
grep -rn "notification\|unread\|badge\|count" resources/views/studio/dashboard.blade.php 2>/dev/null | head -10


# ── C3 : DÉTAILS DEMANDES ──
echo "--- DEMANDES ---"

# C3a. Vue liste demandes studio
cat resources/views/studio/demandes.blade.php 2>/dev/null | head -40
# OU
find resources/views/studio -name "*demande*" -o -name "*request*" -o -name "*booking*" | head -5

# C3b. Route détail demande
php artisan route:list 2>&1 | grep "studio.*demande\|studio.*request\|studio.*booking" | head -10

# C3c. Controller méthode show/detail
grep -n "function.*show\|function.*detail\|function.*demande" app/Http/Controllers/StudioController.php | head -10


# ── C4 : PLANNING / CALENDRIER ──
echo "--- PLANNING ---"

# C4a. Vue planning
cat resources/views/studio/planning.blade.php 2>/dev/null | head -60

# C4b. Composant Livewire Calendar studio
cat app/Livewire/Studio/Calendar.php 2>/dev/null | head -50
find app/Livewire -name "*Calendar*" | head -5

# C4c. Package calendrier
grep -n "calendar\|fullcalendar\|saade\|filament-fullcalendar" composer.json | head -3

# C4d. Données du planning (quels bookings récupérés ?)
grep -A 20 "function planning\|function calendar\|getEvents\|getCalendar" app/Http/Controllers/StudioController.php app/Livewire/Studio/Calendar.php 2>/dev/null | head -40


# ── C5 : CARDS ARTISTES STUDIO ──
echo "--- CARDS ARTISTES ---"

# C5a. Vue artists studio
cat resources/views/studio/artists.blade.php 2>/dev/null | head -60

# C5b. Bannière artiste — media
grep -n "banner\|cover\|avatar\|getFirstMediaUrl\|media" resources/views/studio/artists.blade.php 2>/dev/null | head -10

# C5c. Bouton "Voir la gestion"
grep -n "gestion\|manage\|voir\|show\|detail" resources/views/studio/artists.blade.php 2>/dev/null | head -10

# C5d. Route gestion artiste
php artisan route:list 2>&1 | grep "studio.*artist" | head -10


# ── C6 : STATISTIQUES ──
echo "--- STATISTIQUES ---"

# C6a. Vue stats
cat resources/views/studio/statistiques.blade.php 2>/dev/null | head -60
# OU
find resources/views/studio -name "*stat*" | head -5

# C6b. Controller stats — données
grep -B 5 -A 30 "function stats\|function statistiques\|function statistics" app/Http/Controllers/StudioController.php 2>/dev/null


# ── C7 : MESSAGES STUDIO ──
echo "--- MESSAGES ---"

# C7a. Composant Livewire Messages studio
cat app/Livewire/Studio/Messages.php 2>/dev/null | head -40

# C7b. Routes messages
php artisan route:list 2>&1 | grep "studio.*message" | head -5

# C7c. Conversations studio existantes
php artisan tinker --execute="
  echo 'Conversations: ' . \App\Models\Conversation::count() . PHP_EOL;
  echo 'With studio participants: ' . \App\Models\Conversation::whereHas('users', function(\$q) {
    \$q->whereHas('studio');
  })->count() . PHP_EOL;
" 2>/dev/null


# ── C8 : FILAMENT DASHBOARD STUDIO ──
echo "--- FILAMENT ---"

# C8a. Panel Filament studio
find app/Filament -path "*Studio*" -type f | sort
ls app/Providers/Filament/ 2>/dev/null

# C8b. Widgets existants
find app/Filament -name "*Widget*" | sort

# C8c. Resources Filament studio
find app/Filament -path "*Studio*" -name "*Resource*" | sort

# C8d. Admin Filament widgets (pour s'inspirer)
find app/Filament -path "*Admin*" -name "*Widget*" | sort
cat app/Filament/Admin/Widgets/ 2>/dev/null | head -5

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX C1 — SIDEBAR : AJOUTER L'ONGLET FICHES CLIENTS

### Problème
Le studio n'a pas d'accès aux fiches clients depuis la sidebar ni le navbottom mobile.

### Fix

Trouver la sidebar studio :
```bash
find resources/views -path "*studio*" -name "*sidebar*" -o -path "*studio*" -name "*nav*" -o -path "*studio*" -name "*layout*" | head -10
```

Ajouter l'item "Fiches clients" dans la sidebar, après "Demandes" ou "Planning" :

```blade
{{-- Dans la sidebar studio --}}
<a href="{{ route('studio.clients.index') }}" 
    class="{{ request()->routeIs('studio.clients.*') ? 'text-beige-peau bg-beige-peau/10' : 'text-titane hover:text-ivoire-text' }} flex items-center gap-3 px-3 py-2 rounded-lg transition-colors">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
    Fiches clients
</a>
```

Ajouter aussi dans le navbottom mobile :
```blade
{{-- Dans le navbottom mobile studio --}}
<a href="{{ route('studio.clients.index') }}" 
    class="{{ request()->routeIs('studio.clients.*') ? 'text-beige-peau' : 'text-titane' }} flex flex-col items-center gap-0.5 text-xs">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    Clients
</a>
```

### Créer la route et le controller si absents

Si la route `studio.clients.index` n'existe pas :

```php
// routes/web.php — dans le groupe studio
Route::get('/clients', [StudioController::class, 'clients'])->name('studio.clients.index');
Route::get('/clients/{client}', [StudioController::class, 'clientShow'])->name('studio.clients.show');
```

La méthode `clients()` dans le controller doit récupérer TOUS les clients qui ont eu au moins une interaction avec un artiste du studio :

```php
public function clients()
{
    $studio = auth()->user()->studio;
    $tattooerIds = $studio->tattooers()->pluck('id');
    $piercerIds = $studio->piercers()->pluck('id');

    $clients = Client::whereHas('bookingRequests', function ($q) use ($tattooerIds, $piercerIds) {
        $q->where(function ($sub) use ($tattooerIds) {
            $sub->where('bookable_type', 'App\\Models\\Tattooer')
                ->whereIn('bookable_id', $tattooerIds);
        })->orWhere(function ($sub) use ($piercerIds) {
            $sub->where('bookable_type', 'App\\Models\\Piercer')
                ->whereIn('bookable_id', $piercerIds);
        });
    })
    ->with('user')
    ->withCount('bookingRequests')
    ->orderBy('created_at', 'desc')
    ->paginate(20);

    return view('studio.clients', compact('clients'));
}
```

Et créer la vue `studio/clients.blade.php` (réutiliser la même logique que la vue client du tattooer si elle existe déjà — le studio doit voir les mêmes données mais pour TOUS ses artistes).

```bash
git add -A && git commit -m "fix(C1): sidebar studio — onglet Fiches clients + navbottom mobile + route + controller"
```

---

## FIX C2 — DASHBOARD STUDIO : COMPTEURS + NOTIFICATIONS

### Problème
Les sections "Demandes en cours" et "RDV" du dashboard n'affichent pas le nombre en cours, et pas de notifications.

### Fix

Lire le controller dashboard et la vue :
```bash
grep -A 30 "function dashboard" app/Http/Controllers/StudioController.php
```

**Enrichir les données envoyées au dashboard** :

```php
public function dashboard()
{
    $studio = auth()->user()->studio;
    $tattooerIds = $studio->tattooers()->pluck('id')->toArray();
    $piercerIds = $studio->piercers()->pluck('id')->toArray();

    // Requête de base pour les bookings du studio
    $studioBookings = BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
        $q->where(function ($sub) use ($tattooerIds) {
            $sub->where('bookable_type', 'App\\Models\\Tattooer')
                ->whereIn('bookable_id', $tattooerIds);
        })->orWhere(function ($sub) use ($piercerIds) {
            $sub->where('bookable_type', 'App\\Models\\Piercer')
                ->whereIn('bookable_id', $piercerIds);
        });
    });

    // Compteurs
    $pendingCount = (clone $studioBookings)->where('status', BookingRequestStatus::PENDING)->count();
    $acceptedCount = (clone $studioBookings)->where('status', BookingRequestStatus::ACCEPTED)->count();
    $confirmedCount = (clone $studioBookings)->whereIn('status', [
        BookingRequestStatus::CONFIRMED,
        BookingRequestStatus::DEPOSIT_PAID,
        BookingRequestStatus::DESIGN_SENT,
    ])->count();
    $completedCount = (clone $studioBookings)->where('status', BookingRequestStatus::COMPLETED)->count();

    // RDV à venir (prochains 7 jours)
    $upcomingAppointments = (clone $studioBookings)
        ->whereIn('status', [BookingRequestStatus::CONFIRMED, BookingRequestStatus::DEPOSIT_PAID, BookingRequestStatus::DESIGN_SENT])
        ->where('appointment_date', '>=', now())
        ->where('appointment_date', '<=', now()->addDays(7))
        ->with(['client.user', 'bookable.user'])
        ->orderBy('appointment_date')
        ->limit(5)
        ->get();

    // Notifications non lues
    $unreadNotifications = auth()->user()->unreadNotifications()->limit(10)->get();

    // Revenus du mois
    $monthlyRevenue = (clone $studioBookings)
        ->whereIn('status', [BookingRequestStatus::COMPLETED, BookingRequestStatus::CONFIRMED])
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_price'); // adapter le nom de colonne

    return view('studio.dashboard', compact(
        'studio',
        'pendingCount',
        'acceptedCount',
        'confirmedCount',
        'completedCount',
        'upcomingAppointments',
        'unreadNotifications',
        'monthlyRevenue',
    ));
}
```

**Dans la vue `studio/dashboard.blade.php`**, mettre à jour les cards compteurs :

```blade
{{-- Card Demandes en cours --}}
<div class="bg-gris-fonde rounded-xl border border-titane/10 p-5">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs text-titane uppercase tracking-wider">Demandes en attente</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1">{{ $pendingCount }}</p>
        </div>
        @if ($pendingCount > 0)
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-beige-peau text-noir-profond text-xs font-bold animate-pulse">
                {{ $pendingCount }}
            </span>
        @endif
    </div>
    <a href="{{ route('studio.demandes') }}" class="text-xs text-beige-peau hover:underline mt-3 inline-block">Voir les demandes →</a>
</div>

{{-- Card RDV à venir --}}
<div class="bg-gris-fonde rounded-xl border border-titane/10 p-5">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs text-titane uppercase tracking-wider">RDV confirmés</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1">{{ $confirmedCount }}</p>
        </div>
    </div>
    <a href="{{ route('studio.planning') }}" class="text-xs text-beige-peau hover:underline mt-3 inline-block">Voir le planning →</a>
</div>

{{-- Card Revenus du mois --}}
<div class="bg-gris-fonde rounded-xl border border-titane/10 p-5">
    <p class="text-xs text-titane uppercase tracking-wider">Revenus du mois</p>
    <p class="text-2xl font-bold text-ivoire-text mt-1">{{ number_format(($monthlyRevenue ?? 0) / 100, 2, ',', ' ') }} €</p>
</div>
```

**Notifications** — Ajouter une section notifications :

```blade
{{-- Section notifications non lues --}}
@if ($unreadNotifications->count() > 0)
<div class="mt-6">
    <h3 class="text-sm font-semibold text-ivoire-text mb-3">Notifications récentes</h3>
    <div class="space-y-2">
        @foreach ($unreadNotifications as $notif)
        <div class="flex items-start gap-3 p-3 bg-gris-fonde rounded-lg border border-titane/10">
            <div class="w-2 h-2 mt-1.5 rounded-full bg-beige-peau flex-shrink-0"></div>
            <div class="flex-1">
                <p class="text-sm text-ivoire-text">{{ $notif->data['message'] ?? $notif->data['title'] ?? 'Nouvelle notification' }}</p>
                <p class="text-xs text-titane mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
```

```bash
git add -A && git commit -m "fix(C2): dashboard studio — compteurs dynamiques + RDV à venir + notifications"
```

---

## FIX C3 — DÉTAILS DEMANDES STUDIO

### Problème
Sur `/studio/demandes`, impossible de voir les détails d'une demande. L'UI/UX doit être revue.

### Fix

Vérifier si une route show existe :
```bash
php artisan route:list 2>&1 | grep "studio.*demande.*show\|studio.*request.*show" | head -5
```

Si absente, créer la route et la méthode :

```php
// routes/web.php — groupe studio
Route::get('/demandes/{bookingRequest}', [StudioController::class, 'demandeShow'])->name('studio.demandes.show');
```

```php
// StudioController
public function demandeShow(BookingRequest $bookingRequest)
{
    $studio = auth()->user()->studio;
    
    // Vérifier que cette demande est bien pour un artiste du studio
    $tattooerIds = $studio->tattooers()->pluck('id')->toArray();
    $piercerIds = $studio->piercers()->pluck('id')->toArray();
    
    $isStudioBooking = ($bookingRequest->bookable_type === 'App\\Models\\Tattooer' && in_array($bookingRequest->bookable_id, $tattooerIds))
        || ($bookingRequest->bookable_type === 'App\\Models\\Piercer' && in_array($bookingRequest->bookable_id, $piercerIds));
    
    abort_unless($isStudioBooking, 403);
    
    $bookingRequest->load(['client.user', 'bookable.user', 'bookable.studio']);
    
    return view('studio.demande-show', compact('bookingRequest'));
}
```

Créer la vue `studio/demande-show.blade.php` en s'inspirant de `tattooer/request-show.blade.php` (adapter pour le contexte studio — lecture seule, le studio peut voir mais pas forcément agir directement sur la demande).

Sur la page liste `studio/demandes`, ajouter le lien vers le détail :

```blade
{{-- Dans chaque ligne/card de demande de la liste --}}
<a href="{{ route('studio.demandes.show', $booking) }}" class="text-sm text-beige-peau hover:underline">
    Voir les détails
</a>
```

```bash
git add -A && git commit -m "fix(C3): studio demandes — vue détail + lien depuis la liste + UI/UX"
```

---

## FIX C4 — PLANNING / CALENDRIER STUDIO

### Problème
Le calendrier n'affiche rien. Il doit montrer une vision globale de TOUS les RDV de tous les artistes liés au studio.

### Diagnostic

```bash
cat app/Livewire/Studio/Calendar.php 2>/dev/null
cat resources/views/studio/planning.blade.php 2>/dev/null
```

### Fix

Le composant Livewire Calendar du studio doit récupérer les événements de TOUS les artistes du studio :

```php
// app/Livewire/Studio/Calendar.php
namespace App\Livewire\Studio;

use Livewire\Component;
use App\Models\BookingRequest;
use App\Enums\BookingRequestStatus;

class Calendar extends Component
{
    public array $events = [];
    public string $currentMonth;

    public function mount()
    {
        $this->currentMonth = now()->format('Y-m');
        $this->loadEvents();
    }

    public function loadEvents()
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
            ->whereIn('status', [
                BookingRequestStatus::CONFIRMED,
                BookingRequestStatus::DEPOSIT_PAID,
                BookingRequestStatus::DESIGN_SENT,
                BookingRequestStatus::COMPLETED,
            ])
            ->whereNotNull('appointment_date')
            ->with(['client.user', 'bookable.user'])
            ->get();

        $this->events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => ($booking->client?->user?->name ?? 'Client') . ' — ' . ($booking->bookable?->user?->name ?? 'Artiste'),
                'start' => $booking->appointment_date?->toIso8601String(),
                'end' => $booking->appointment_end?->toIso8601String() ?? $booking->appointment_date?->addHours(2)->toIso8601String(),
                'color' => $this->getStatusColor($booking->status),
                'url' => route('studio.demandes.show', $booking),
                'extendedProps' => [
                    'artist' => $booking->bookable?->user?->name ?? '—',
                    'client' => $booking->client?->user?->name ?? '—',
                    'status' => $booking->status?->value ?? '—',
                ],
            ];
        })->toArray();
    }

    private function getStatusColor($status): string
    {
        return match ($status) {
            BookingRequestStatus::CONFIRMED => '#C97435', // beige-peau
            BookingRequestStatus::DEPOSIT_PAID => '#4ade80', // vert
            BookingRequestStatus::DESIGN_SENT => '#60a5fa', // bleu
            BookingRequestStatus::COMPLETED => '#22c55e', // vert foncé
            default => '#6b7280', // gris
        };
    }

    public function render()
    {
        return view('livewire.studio.calendar');
    }
}
```

La vue doit inclure un calendrier. Si FullCalendar est déjà dans le projet, l'utiliser. Sinon, implémenter un calendrier Alpine.js simple OU installer FullCalendar via CDN :

```blade
{{-- resources/views/livewire/studio/calendar.blade.php --}}
<div>
    <div wire:ignore x-data x-init="
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('studio-calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @js($events),
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                },
                height: 'auto',
                eventDisplay: 'block',
                dayMaxEvents: 3,
            });
            calendar.render();
        });
    " id="studio-calendar"></div>
</div>
```

Dans la vue planning, s'assurer que FullCalendar est chargé :
```blade
{{-- resources/views/studio/planning.blade.php --}}
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
@endpush

@livewire('studio.calendar')
```

IMPORTANT : Vérifier si FullCalendar est déjà présent dans le projet (package npm ou CDN). Si oui, ne pas le dupliquer. Si un autre package calendrier est utilisé (Filament Calendar, etc.), adapter en conséquence.

```bash
git add -A && git commit -m "fix(C4): planning studio fonctionnel — calendrier global multi-artistes avec FullCalendar"
```

---

## FIX C5 — CARDS ARTISTES STUDIO (bannière + bouton gestion)

### Problème
- La bannière des cards artistes ne se charge pas
- Le bouton "Voir la gestion" ne fonctionne pas

### Diagnostic

```bash
grep -n "banner\|avatar\|cover\|getFirstMediaUrl\|media\|image\|photo" resources/views/studio/artists.blade.php 2>/dev/null | head -10
grep -n "gestion\|manage\|show\|route\|href" resources/views/studio/artists.blade.php 2>/dev/null | head -10
```

### Fix bannière

Le problème est probablement que la collection media de l'artiste n'est pas chargée ou que le nom de la collection est incorrect :

```blade
{{-- AVANT (probablement cassé) --}}
<img src="{{ $artist->banner }}" ...>

{{-- APRÈS (utiliser Spatie Media Library) --}}
@if ($artist->getFirstMediaUrl('banner'))
    <img src="{{ $artist->getFirstMediaUrl('banner') }}" alt="{{ $artist->user->name }}" class="w-full h-32 object-cover">
@elseif ($artist->getFirstMediaUrl('portfolio'))
    <img src="{{ $artist->getFirstMedia('portfolio')?->getUrl('thumb') ?? $artist->getFirstMediaUrl('portfolio') }}" alt="{{ $artist->user->name }}" class="w-full h-32 object-cover">
@elseif ($artist->user?->getFirstMediaUrl('avatar'))
    <img src="{{ $artist->user->getFirstMediaUrl('avatar') }}" alt="{{ $artist->user->name }}" class="w-full h-32 object-cover">
@else
    <div class="w-full h-32 bg-gradient-to-br from-noir-profond to-gris-fonde flex items-center justify-center">
        <span class="text-2xl text-titane/30">🎨</span>
    </div>
@endif
```

### Fix bouton "Voir la gestion"

Vérifier que la route existe et est correcte :
```bash
php artisan route:list 2>&1 | grep "studio.*artist.*show\|studio.*artist.*manage\|studio.*artist.*gestion" | head -5
```

Si la route est absente ou incorrecte :
```php
// routes/web.php — groupe studio
Route::get('/artists/{tattooer}', [StudioController::class, 'artistShow'])->name('studio.artists.show');
```

Corriger le bouton dans la vue :
```blade
{{-- AVANT (probablement un href vide ou mauvaise route) --}}
<a href="#">Voir la gestion</a>

{{-- APRÈS --}}
<a href="{{ route('studio.artists.show', $artist) }}" 
    class="px-3 py-1.5 text-xs bg-gris-fonde text-beige-peau border border-beige-peau/30 rounded-lg hover:bg-beige-peau/10 transition-colors">
    Voir la gestion
</a>
```

```bash
git add -A && git commit -m "fix(C5): cards artistes studio — bannière chargée + bouton gestion fonctionnel"
```

---

## FIX C6 — STATISTIQUES STUDIO

### Problème
La page statistiques ne fonctionne pas.

### Fix

Enrichir la méthode `stats()` du StudioController :

```php
public function stats()
{
    $studio = auth()->user()->studio;
    $tattooerIds = $studio->tattooers()->pluck('id')->toArray();
    $piercerIds = $studio->piercers()->pluck('id')->toArray();

    $baseQuery = BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
        $q->where(function ($sub) use ($tattooerIds) {
            $sub->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
        })->orWhere(function ($sub) use ($piercerIds) {
            $sub->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
        });
    });

    // Stats globales
    $totalBookings = (clone $baseQuery)->count();
    $completedBookings = (clone $baseQuery)->where('status', BookingRequestStatus::COMPLETED)->count();
    $cancelledBookings = (clone $baseQuery)->where('status', BookingRequestStatus::CANCELLED)->count();
    $totalRevenue = (clone $baseQuery)->where('status', BookingRequestStatus::COMPLETED)->sum('total_price');

    // Stats par artiste
    $artistStats = [];
    foreach ($studio->tattooers as $tattooer) {
        $artistBookings = BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')
            ->where('bookable_id', $tattooer->id);
        
        $artistStats[] = [
            'name' => $tattooer->user->name ?? '—',
            'type' => 'Tatoueur',
            'total' => (clone $artistBookings)->count(),
            'completed' => (clone $artistBookings)->where('status', BookingRequestStatus::COMPLETED)->count(),
            'revenue' => (clone $artistBookings)->where('status', BookingRequestStatus::COMPLETED)->sum('total_price'),
        ];
    }
    foreach ($studio->piercers as $piercer) {
        $artistBookings = BookingRequest::where('bookable_type', 'App\\Models\\Piercer')
            ->where('bookable_id', $piercer->id);
        
        $artistStats[] = [
            'name' => $piercer->user->name ?? '—',
            'type' => 'Pierceur',
            'total' => (clone $artistBookings)->count(),
            'completed' => (clone $artistBookings)->where('status', BookingRequestStatus::COMPLETED)->count(),
            'revenue' => (clone $artistBookings)->where('status', BookingRequestStatus::COMPLETED)->sum('total_price'),
        ];
    }

    // Revenus par mois (12 derniers mois — pour graphique)
    $monthlyRevenue = collect();
    for ($i = 11; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $rev = (clone $baseQuery)
            ->where('status', BookingRequestStatus::COMPLETED)
            ->whereMonth('created_at', $month->month)
            ->whereYear('created_at', $month->year)
            ->sum('total_price');
        
        $monthlyRevenue->push([
            'month' => $month->translatedFormat('M Y'),
            'revenue' => $rev,
        ]);
    }

    // Taux de conversion
    $conversionRate = $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 1) : 0;

    return view('studio.statistiques', compact(
        'totalBookings', 'completedBookings', 'cancelledBookings', 'totalRevenue',
        'artistStats', 'monthlyRevenue', 'conversionRate', 'studio',
    ));
}
```

Puis créer/compléter la vue `studio/statistiques.blade.php` avec :
- Cards compteurs (total, complétés, annulés, revenus)
- Tableau stats par artiste
- Graphique revenus mensuels (utiliser un `<canvas>` avec Chart.js via CDN ou Alpine)
- Taux de conversion

IMPORTANT : Adapter les noms de colonnes (`total_price`, `status`, etc.) aux vrais noms. Les montants sont probablement en centimes.

```bash
git add -A && git commit -m "fix(C6): statistiques studio fonctionnelles — compteurs, stats par artiste, revenus mensuels"
```

---

## FIX C7 — MESSAGES STUDIO : ANALYSE ET IMPLÉMENTATION

### Utilité
Les messages studio sont UTILES dans ces cas :
1. **Communication studio ↔ artiste** : le gérant communique avec ses artistes (orga, planning, infos)
2. **Support client** : un client contacte le studio (pas un artiste spécifique) pour des infos générales

### Implémentation recommandée

Si le composant `Livewire\Studio\Messages` existe déjà mais est vide/cassé, le compléter pour afficher :
- Les conversations entre le studio owner et ses artistes
- Les conversations des artistes du studio avec leurs clients (vision gestionnaire)

Le studio owner ne doit PAS lire les messages privés artiste-client, mais peut voir un résumé (dernier message, statut conversation).

Si le composant est fonctionnel mais juste non lié dans les routes/vues, simplement brancher la route et vérifier le rendu.

Si le composant n'est pas utile à court terme, ajouter un placeholder :

```blade
{{-- resources/views/studio/messages.blade.php --}}
<div class="flex flex-col items-center justify-center py-20 text-center">
    <svg class="w-16 h-16 text-titane/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
    </svg>
    <h3 class="text-lg font-semibold text-ivoire-text">Messagerie studio</h3>
    <p class="text-sm text-titane mt-2 max-w-md">
        La messagerie studio arrive bientôt. Vous pourrez communiquer avec vos artistes et suivre les conversations clients.
    </p>
</div>
```

```bash
git add -A && git commit -m "fix(C7): messages studio — placeholder ou implémentation selon état actuel"
```

---

## FIX C8 — FILAMENT DASHBOARD STUDIO

### Problème
Le panel Filament admin/studio doit être complété avec des statistiques complètes.

### Diagnostic

```bash
find app/Filament -path "*Studio*" | sort
```

### Implémentation

Créer des widgets Filament pour le panel studio (s'inspirer des widgets admin existants) :

```php
// app/Filament/Studio/Widgets/StudioStatsOverview.php
namespace App\Filament\Studio\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\BookingRequest;
use App\Enums\BookingRequestStatus;

class StudioStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $studio = auth()->user()->studio;
        $tattooerIds = $studio->tattooers()->pluck('id')->toArray();
        $piercerIds = $studio->piercers()->pluck('id')->toArray();

        $baseQuery = BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($sub) use ($tattooerIds) {
                $sub->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($sub) use ($piercerIds) {
                $sub->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
            });
        });

        $pendingCount = (clone $baseQuery)->where('status', BookingRequestStatus::PENDING)->count();
        $confirmedCount = (clone $baseQuery)->whereIn('status', [
            BookingRequestStatus::CONFIRMED, BookingRequestStatus::DEPOSIT_PAID, BookingRequestStatus::DESIGN_SENT,
        ])->count();
        $monthlyRevenue = (clone $baseQuery)->where('status', BookingRequestStatus::COMPLETED)
            ->whereMonth('created_at', now()->month)->sum('total_price');
        $totalArtists = count($tattooerIds) + count($piercerIds);

        return [
            Stat::make('Demandes en attente', $pendingCount)
                ->icon('heroicon-o-clock')
                ->color($pendingCount > 0 ? 'warning' : 'success'),
            Stat::make('RDV confirmés', $confirmedCount)
                ->icon('heroicon-o-calendar'),
            Stat::make('Revenus du mois', number_format($monthlyRevenue / 100, 2, ',', ' ') . ' €')
                ->icon('heroicon-o-currency-euro')
                ->color('success'),
            Stat::make('Artistes actifs', $totalArtists)
                ->icon('heroicon-o-user-group'),
        ];
    }
}
```

```php
// app/Filament/Studio/Widgets/RevenueByArtistChart.php
namespace App\Filament\Studio\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\BookingRequest;
use App\Enums\BookingRequestStatus;

class RevenueByArtistChart extends ChartWidget
{
    protected static ?string $heading = 'Revenus par artiste (mois en cours)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $studio = auth()->user()->studio;
        $labels = [];
        $data = [];
        $colors = ['#C97435', '#4ade80', '#60a5fa', '#f472b6', '#a78bfa', '#34d399'];
        $bgColors = [];

        $i = 0;
        foreach ($studio->tattooers as $t) {
            $rev = BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')
                ->where('bookable_id', $t->id)
                ->where('status', BookingRequestStatus::COMPLETED)
                ->whereMonth('created_at', now()->month)
                ->sum('total_price');
            $labels[] = $t->user->name ?? 'Artiste ' . $t->id;
            $data[] = $rev / 100;
            $bgColors[] = $colors[$i % count($colors)];
            $i++;
        }
        foreach ($studio->piercers as $p) {
            $rev = BookingRequest::where('bookable_type', 'App\\Models\\Piercer')
                ->where('bookable_id', $p->id)
                ->where('status', BookingRequestStatus::COMPLETED)
                ->whereMonth('created_at', now()->month)
                ->sum('total_price');
            $labels[] = $p->user->name ?? 'Pierceur ' . $p->id;
            $data[] = $rev / 100;
            $bgColors[] = $colors[$i % count($colors)];
            $i++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenus (€)',
                    'data' => $data,
                    'backgroundColor' => $bgColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
```

Créer aussi un widget pour les demandes récentes sous forme de table Filament, et un widget graphique des revenus mensuels (12 mois). S'inspirer des widgets Admin existants.

Enregistrer les widgets dans le panel Filament studio (dans le provider ou dans la configuration du panel).

```bash
git add -A && git commit -m "fix(C8): Filament dashboard studio — widgets stats, revenus par artiste, graphiques"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT C ==="

# V1. Sidebar fiches clients
grep -c "clients\|fiches" resources/views/studio/partials/sidebar.blade.php 2>/dev/null || echo "Vérifier sidebar"

# V2. Dashboard compteurs
grep -c "pendingCount\|confirmedCount\|monthlyRevenue" resources/views/studio/dashboard.blade.php 2>/dev/null

# V3. Détail demandes
php artisan route:list --name="studio.demandes.show" 2>&1 | head -3

# V4. Planning
ls app/Livewire/Studio/Calendar.php && echo "Calendar OK"
grep -c "events\|appointment_date\|tattooerIds" app/Livewire/Studio/Calendar.php

# V5. Cards artistes
grep -c "getFirstMediaUrl\|banner\|portfolio" resources/views/studio/artists.blade.php 2>/dev/null
grep -c "studio.artists.show\|gestion" resources/views/studio/artists.blade.php 2>/dev/null

# V6. Statistiques
grep -c "artistStats\|monthlyRevenue\|conversionRate" app/Http/Controllers/StudioController.php 2>/dev/null

# V7. Filament widgets
find app/Filament -path "*Studio*" -name "*Widget*" | wc -l

# V8. Routes
php artisan route:list --name="studio" --columns=name 2>&1 | head -20

# V9. Compilation
php artisan route:clear && php artisan view:clear
php artisan route:list 2>&1 | head -3
echo "Pas d'erreur = OK"

echo "=== PROMPT C TERMINÉ — 8 fixes ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire, les vrais fichiers/routes peuvent différer
2. **Queries polymorphiques** — Toujours filtrer par `bookable_type` + `bookable_id` pour les tattooers ET piercers
3. **Montants en centimes** — Diviser par 100 pour l'affichage
4. **Sidebar** : ajouter dans TOUS les fichiers de navigation studio (sidebar desktop + navbottom mobile)
5. **FullCalendar** : vérifier d'abord si déjà installé avant d'ajouter le CDN
6. **Filament widgets** : les enregistrer dans le panel provider pour qu'ils apparaissent
7. **Commit après chaque fix** (8 commits)
8. **Le studio doit voir les données de TOUS ses artistes** (tattooers + piercers)
