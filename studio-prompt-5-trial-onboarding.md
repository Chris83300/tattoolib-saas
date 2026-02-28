# 🏢 STUDIO ESSAI 14 JOURS — Trial + Onboarding + Soft Lock
# Pour Claude Code — Commit après chaque phase

## CONTEXTE

Studio est COMPLET : dashboard, artistes CRUD, invitation, Stripe billing, Filament panel.
Ce prompt ajoute le système d'essai gratuit 14 jours SANS carte bancaire.

Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, MySQL, Stripe Billing, Laravel Cashier.

### LOGIQUE MÉTIER

**À l'inscription :**
- Message : "Vous avez 14 jours pour tester l'intégralité des fonctionnalités Studio."
- Pas de CB requise
- Colonne `trial_ends_at` = now() + 14 jours sur la table studios
- Accès complet à toutes les fonctionnalités pendant le trial

**Pendant le trial — Checklist d'activation (dashboard) :**
1. ✅ Ajouter le logo du studio
2. ✅ Ajouter au moins 1 artiste
3. ✅ Configurer les conditions d'acompte (payment_mode choisi)
4. ✅ Personnaliser la fiche studio (description + adresse remplis)
5. ✅ Simuler une demande (au moins 1 booking request reçue)
→ Barre de progression visuelle dans le dashboard
→ Chaque étape cochée = engagement

**J-4 avant fin trial :**
- Email automatique rappel : "Votre essai se termine dans 4 jours"
- Notification dans le dashboard

**Fin des 14 jours (trial expiré) :**
- Compte en LECTURE SEULE (soft lock)
- Le studio peut voir son dashboard, ses artistes, ses demandes
- MAIS ne peut plus : créer d'artistes, accepter de demandes, modifier settings
- Bannière permanente : "Votre essai est terminé. Activez votre abonnement pour continuer."
- Bouton CTA vers Stripe Checkout pour souscrire

**Activation abonnement :**
- Le studio renseigne sa CB → Stripe Checkout / Stripe Elements
- Abonnement créé : 79.99€/mois + 39.99€/artiste supplémentaire
- trial_ends_at mis à NULL → soft lock levé
- Accès complet retrouvé

---

## PHASE 0 — AUDIT

```bash
# 0A. Colonne trial_ends_at sur studios (ajoutée par Cashier au prompt 4)
php artisan tinker --execute="
  echo 'trial_ends_at: ' . (Schema::hasColumn('studios', 'trial_ends_at') ? 'EXISTS' : 'ABSENT');
  echo PHP_EOL . 'stripe_id: ' . (Schema::hasColumn('studios', 'stripe_id') ? 'EXISTS' : 'ABSENT');
"

# 0B. Comment l'inscription studio fonctionne
grep -rn "studio" app/Livewire/Auth/ app/Http/Controllers/Auth/ 2>/dev/null | head -10
find app/Livewire -name "*Register*" -o -name "*register*" | head -5
# Si Livewire :
grep -rn "Studio::create\|studio.*create" app/Livewire/ | head -5
# Si Controller :
grep -rn "Studio::create\|studio.*create" app/Http/Controllers/ | head -5

# 0C. Dashboard studio actuel
cat app/Livewire/Studio/Dashboard.php 2>/dev/null | head -40
# OU
grep -A 20 "function dashboard" app/Http/Controllers/StudioController.php | head -25

# 0D. Layout studio — où mettre la bannière trial
head -30 resources/views/layouts/studio.blade.php

# 0E. StudioBillingService existant
cat app/Services/StudioBillingService.php 2>/dev/null | head -30

# 0F. Notifications/Mails studio existants
find app/Mail -name "*Studio*" -o -name "*studio*" | sort
find app/Notifications -name "*Studio*" -o -name "*studio*" | sort

# 0G. Scheduler existant
cat app/Console/Kernel.php 2>/dev/null | head -30
# OU (Laravel 11+)
cat routes/console.php 2>/dev/null | head -20
```

**MONTRE-MOI les résultats avant de continuer.**

---

## PHASE 1 — TRIAL À L'INSCRIPTION

### 1A. Adapter la création du studio à l'inscription

Trouver où le Studio est créé lors de l'inscription :

```bash
grep -rn "Studio::create" app/ --include="*.php" | head -5
```

Ajouter `trial_ends_at` lors de la création :

```php
// Dans le code qui crée le Studio à l'inscription
Studio::create([
    // ... champs existants ...
    'trial_ends_at' => now()->addDays(14),
]);
```

### 1B. Helpers trial dans le model Studio

Ajouter dans `app/Models/Studio.php` :

```php
// ═══ TRIAL ═══

/**
 * Le studio est-il en période d'essai ?
 */
public function onTrial(): bool
{
    return $this->trial_ends_at !== null && $this->trial_ends_at->isFuture();
}

/**
 * Le trial est-il expiré (sans abonnement actif) ?
 */
public function trialExpired(): bool
{
    if ($this->trial_ends_at === null) return false;
    return $this->trial_ends_at->isPast() && !$this->hasActiveSubscription();
}

/**
 * Jours restants dans le trial
 */
public function trialDaysLeft(): int
{
    if (!$this->trial_ends_at || $this->trial_ends_at->isPast()) return 0;
    return (int) now()->diffInDays($this->trial_ends_at, false);
}

/**
 * Le studio a-t-il un abonnement actif (hors trial) ?
 */
public function hasActiveSubscription(): bool
{
    return $this->subscribed('studio');
}

/**
 * Le studio peut-il effectuer des actions (trial actif OU abonnement actif) ?
 */
public function canOperate(): bool
{
    return $this->onTrial() || $this->hasActiveSubscription();
}

/**
 * Le studio est-il en lecture seule ? (trial expiré, pas d'abonnement)
 */
public function isReadOnly(): bool
{
    return !$this->canOperate();
}
```

Vérifier que `trial_ends_at` est dans les $casts :

```php
protected $casts = [
    // ... casts existants ...
    'trial_ends_at' => 'datetime',
];
```

Et dans $fillable :

```php
protected $fillable = [
    // ... fillable existants ...
    'trial_ends_at',
];
```

```bash
git add -A && git commit -m "feat(studio-trial): trial 14j à l'inscription + helpers model"
```

---

## PHASE 2 — MIDDLEWARE SOFT LOCK

Créer un middleware qui bloque les actions d'écriture quand le trial est expiré.

```bash
php artisan make:middleware EnsureStudioCanOperate
```

```php
// app/Http/Middleware/EnsureStudioCanOperate.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureStudioCanOperate
{
    /**
     * Routes en lecture seule autorisées même si trial expiré.
     * Le studio peut voir mais pas agir.
     */
    private array $readOnlyRoutes = [
        'studio.dashboard',
        'studio.artists',      // Voir la liste (pas créer)
        'studio.billing',      // Voir la facturation (et souscrire !)
        'studio.settings',     // Voir les settings (pas modifier)
        'studio.planning',
        'studio.requests',
        'studio.stats',
        'studio.profile',
        'studio.subscribe',    // Page de souscription
        'studio.subscribe.process', // Traitement souscription
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if (!$user || !$user->isStudio()) {
            return $next($request);
        }

        $studio = $user->studio;
        if (!$studio) {
            return $next($request);
        }

        // Si le studio peut opérer (trial actif ou abonnement), laisser passer
        if ($studio->canOperate()) {
            return $next($request);
        }

        // Trial expiré — vérifier si la route est autorisée en lecture seule
        $currentRoute = $request->route()?->getName();
        
        if ($currentRoute && in_array($currentRoute, $this->readOnlyRoutes)) {
            return $next($request);
        }

        // Route non autorisée → rediriger vers billing avec message
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Votre essai est terminé. Activez votre abonnement pour continuer.',
            ], 403);
        }

        return redirect()->route('studio.billing')
            ->with('error', 'Votre essai est terminé. Activez votre abonnement pour continuer.');
    }
}
```

### 2B. Enregistrer le middleware

```bash
grep -n "middleware\|EnsureUser" bootstrap/app.php | head -10
```

Ajouter dans le groupe de routes studio. Trouver le groupe dans routes/web.php :

```bash
grep -n "studio.*group\|middleware.*studio" routes/web.php | head -10
```

Ajouter le middleware au groupe studio :

```php
Route::middleware(['auth', 'verified', 'role:studio', \App\Http\Middleware\EnsureStudioCanOperate::class])
    ->prefix('studio')
    ->name('studio.')
    ->group(function () {
        // ... routes existantes ...
    });
```

OU si les middleware sont définis dans bootstrap/app.php, ajouter un alias :

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        // ... aliases existants ...
        'studio.active' => \App\Http\Middleware\EnsureStudioCanOperate::class,
    ]);
})
```

Puis dans les routes : `middleware(['auth', 'verified', 'role:studio', 'studio.active'])`

IMPORTANT : Les routes POST de souscription (subscribe, subscribe.process) doivent être DANS le groupe mais AUTORISÉES dans le middleware (elles sont dans readOnlyRoutes). Sinon le studio ne pourra jamais souscrire.

```bash
git add -A && git commit -m "feat(studio-trial): middleware soft lock lecture seule après trial expiré"
```

---

## PHASE 3 — CHECKLIST ONBOARDING DASHBOARD

La checklist apparaît dans le dashboard studio pendant le trial.

### 3A. Méthode onboarding dans Studio model

```php
// Dans app/Models/Studio.php, ajouter :

/**
 * Retourne l'état de la checklist d'onboarding.
 * Chaque étape = ['label' => '...', 'done' => bool, 'icon' => '...']
 */
public function getOnboardingChecklist(): array
{
    return [
        [
            'key' => 'logo',
            'label' => 'Ajouter le logo du studio',
            'done' => $this->getFirstMediaUrl('logo') !== '',
            'icon' => '🖼️',
        ],
        [
            'key' => 'artist',
            'label' => 'Ajouter au moins 1 artiste',
            'done' => $this->studioArtists()->where('is_active', true)->exists(),
            'icon' => '👤',
        ],
        [
            'key' => 'payment',
            'label' => 'Configurer le mode de paiement',
            'done' => $this->payment_mode !== null,
            'icon' => '💳',
        ],
        [
            'key' => 'profile',
            'label' => 'Personnaliser la fiche studio',
            'done' => !empty($this->description) && !empty($this->address),
            'icon' => '📝',
        ],
        [
            'key' => 'booking',
            'label' => 'Recevoir une première demande',
            'done' => $this->hasReceivedBookingRequest(),
            'icon' => '📋',
        ],
    ];
}

/**
 * Pourcentage de progression onboarding (0-100)
 */
public function onboardingProgress(): int
{
    $checklist = $this->getOnboardingChecklist();
    $done = collect($checklist)->where('done', true)->count();
    return (int) round(($done / count($checklist)) * 100);
}

/**
 * Onboarding complet ?
 */
public function onboardingComplete(): bool
{
    return $this->onboardingProgress() === 100;
}

/**
 * Le studio a-t-il reçu au moins une booking request ?
 */
public function hasReceivedBookingRequest(): bool
{
    $artistUserIds = $this->studioArtists()->where('is_active', true)->pluck('user_id')->filter();
    if ($artistUserIds->isEmpty()) return false;
    
    $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
    $piercerIds = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');
    
    return \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
        $q->where(function ($q2) use ($tattooerIds) {
            $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
        })->orWhere(function ($q2) use ($piercerIds) {
            $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
        });
    })->exists();
}
```

### 3B. Afficher la checklist dans le dashboard

Trouver le dashboard studio :
```bash
cat resources/views/studio/dashboard.blade.php 2>/dev/null | head -10
cat resources/views/livewire/studio/dashboard.blade.php 2>/dev/null | head -10
```

Ajouter la checklist AVANT les stats, visible uniquement pendant le trial et si l'onboarding n'est pas complet :

```blade
@php
    $checklist = $studio->getOnboardingChecklist();
    $progress = $studio->onboardingProgress();
    $showChecklist = $studio->onTrial() && !$studio->onboardingComplete();
@endphp

@if ($showChecklist)
<div class="bg-gris-fonde rounded-xl p-4 md:p-6 border border-beige-peau/20">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-sm font-bold text-beige-peau uppercase tracking-wider">🚀 Démarrage rapide</h2>
            <p class="text-xs text-titane mt-0.5">Configurez votre studio en quelques étapes</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm font-bold text-beige-peau">{{ $progress }}%</span>
        </div>
    </div>
    
    {{-- Barre de progression --}}
    <div class="w-full bg-noir-profond rounded-full h-2 mb-4">
        <div class="bg-beige-peau h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
    </div>
    
    {{-- Étapes --}}
    <div class="space-y-2">
        @foreach ($checklist as $step)
            <div class="flex items-center gap-3 py-2 {{ $step['done'] ? 'opacity-60' : '' }}">
                <span class="text-lg">{{ $step['done'] ? '✅' : $step['icon'] }}</span>
                <span class="text-sm {{ $step['done'] ? 'text-titane line-through' : 'text-ivoire-text font-medium' }}">
                    {{ $step['label'] }}
                </span>
                @if (!$step['done'])
                    @switch($step['key'])
                        @case('logo')
                            <a href="{{ route('studio.settings') }}" class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                            @break
                        @case('artist')
                            <a href="{{ route('studio.artists.create') }}" class="ml-auto text-xs text-beige-peau hover:underline">Ajouter →</a>
                            @break
                        @case('payment')
                            <a href="{{ route('studio.settings') }}" class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                            @break
                        @case('profile')
                            <a href="{{ route('studio.settings') }}" class="ml-auto text-xs text-beige-peau hover:underline">Personnaliser →</a>
                            @break
                        @case('booking')
                            <span class="ml-auto text-xs text-titane">En attente...</span>
                            @break
                    @endswitch
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif
```

S'assurer que la variable `$studio` est passée au dashboard. Vérifier le composant Livewire ou le controller :
```bash
grep -n "\$studio\|studio" app/Livewire/Studio/Dashboard.php 2>/dev/null | head -10
```

Si c'est un composant Livewire, ajouter les propriétés computed ou les passer dans `mount()`.

```bash
git add -A && git commit -m "feat(studio-trial): checklist onboarding dashboard avec progression"
```

---

## PHASE 4 — BANNIÈRES TRIAL DANS LE LAYOUT

### 4A. Bannière trial actif (compte à rebours)

Dans le layout studio (`resources/views/layouts/studio.blade.php`), ajouter en haut du contenu :

```blade
@auth
@php
    $studio = auth()->user()->studio;
@endphp

{{-- Bannière trial actif --}}
@if ($studio && $studio->onTrial())
    <div class="bg-beige-peau/10 border-b border-beige-peau/20 px-4 py-2">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <p class="text-xs text-beige-peau">
                ⏳ <strong>Essai gratuit</strong> — {{ $studio->trialDaysLeft() }} jour{{ $studio->trialDaysLeft() > 1 ? 's' : '' }} restant{{ $studio->trialDaysLeft() > 1 ? 's' : '' }}
            </p>
            <a href="{{ route('studio.billing') }}" class="text-xs font-semibold text-beige-peau hover:text-beige-peau/80">
                Activer l'abonnement →
            </a>
        </div>
    </div>
@endif

{{-- Bannière trial expiré (soft lock) --}}
@if ($studio && $studio->trialExpired())
    <div class="bg-rouge-alerte/10 border-b border-rouge-alerte/30 px-4 py-3">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-rouge-alerte">⚠️ Votre essai est terminé</p>
                <p class="text-xs text-rouge-alerte/80 mt-0.5">Votre studio est en lecture seule. Activez votre abonnement pour continuer à utiliser toutes les fonctionnalités.</p>
            </div>
            <a href="{{ route('studio.billing') }}" 
                class="shrink-0 px-4 py-2 bg-beige-peau text-noir-profond rounded-xl text-sm font-semibold hover:bg-beige-peau/90 transition-colors">
                Activer — 79,99€/mois
            </a>
        </div>
    </div>
@endif
@endauth
```

### 4B. Indicateur visuel dans la sidebar

Dans la navigation sidebar du layout studio, sous le nom du studio :

```blade
@if ($studio->onTrial())
    <span class="text-xs bg-beige-peau/20 text-beige-peau rounded-full px-2 py-0.5 font-semibold">
        Essai • {{ $studio->trialDaysLeft() }}j
    </span>
@elseif ($studio->hasActiveSubscription())
    <span class="text-xs bg-vert-validation/20 text-vert-validation rounded-full px-2 py-0.5 font-semibold">
        Pro
    </span>
@elseif ($studio->trialExpired())
    <span class="text-xs bg-rouge-alerte/20 text-rouge-alerte rounded-full px-2 py-0.5 font-semibold">
        Expiré
    </span>
@endif
```

```bash
git add -A && git commit -m "feat(studio-trial): bannières trial dans layout + badge sidebar"
```

---

## PHASE 5 — EMAIL RAPPEL J-4

### 5A. Créer le Mailable

```bash
php artisan make:mail StudioTrialEndingSoonMail --markdown=emails.studio.trial-ending
```

```php
// app/Mail/StudioTrialEndingSoonMail.php
namespace App\Mail;

use App\Models\Studio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudioTrialEndingSoonMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Studio $studio) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre essai Ink&Pik se termine dans {$this->studio->trialDaysLeft()} jours",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.studio.trial-ending',
            with: [
                'studio' => $this->studio,
                'daysLeft' => $this->studio->trialDaysLeft(),
                'progress' => $this->studio->onboardingProgress(),
                'billingUrl' => route('studio.billing'),
            ],
        );
    }
}
```

```blade
{{-- resources/views/emails/studio/trial-ending.blade.php --}}
<x-mail::message>
# Votre essai se termine bientôt

Bonjour,

L'essai gratuit de **{{ $studio->name }}** se termine dans **{{ $daysLeft }} jour{{ $daysLeft > 1 ? 's' : '' }}**.

@if ($progress < 100)
Vous avez complété **{{ $progress }}%** de la configuration. Terminez la mise en place pour tirer le maximum de votre essai !
@else
Vous avez complété toute la configuration — votre studio est prêt ! 🎉
@endif

Pour continuer à utiliser toutes les fonctionnalités Studio après la fin de l'essai, activez votre abonnement :

<x-mail::button :url="$billingUrl">
Activer l'abonnement — 79,99€/mois
</x-mail::button>

**Ce qui est inclus :**
- Gestion complète de votre studio
- 1 artiste inclus
- Dashboard avancé et traçabilité
- Visibilité sur la marketplace
- Artistes supplémentaires à 39,99€/mois

Cordialement,<br>
L'équipe Ink&Pik
</x-mail::message>
```

### 5B. Commande artisan pour envoyer les rappels

```bash
php artisan make:command SendStudioTrialReminders
```

```php
// app/Console/Commands/SendStudioTrialReminders.php
namespace App\Console\Commands;

use App\Mail\StudioTrialEndingSoonMail;
use App\Models\Studio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendStudioTrialReminders extends Command
{
    protected $signature = 'studios:send-trial-reminders';
    protected $description = 'Envoie des rappels aux studios dont le trial expire dans 4 jours';

    public function handle(): int
    {
        // Studios dont le trial expire dans exactement 4 jours
        $studios = Studio::whereNotNull('trial_ends_at')
            ->whereDate('trial_ends_at', now()->addDays(4)->toDateString())
            ->whereDoesntHave('subscriptions', function ($q) {
                $q->where('name', 'studio')->where('stripe_status', 'active');
            })
            ->with('user')
            ->get();

        $count = 0;
        foreach ($studios as $studio) {
            $email = $studio->email ?? $studio->user?->email;
            if (!$email) continue;

            Mail::to($email)->send(new StudioTrialEndingSoonMail($studio));
            $count++;
            $this->info("Rappel envoyé à {$studio->name} ({$email})");
        }

        $this->info("Total : {$count} rappel(s) envoyé(s)");
        return Command::SUCCESS;
    }
}
```

### 5C. Scheduler

```bash
# Trouver le fichier scheduler
cat routes/console.php 2>/dev/null | head -20
cat app/Console/Kernel.php 2>/dev/null | head -30
```

Ajouter dans le scheduler (routes/console.php ou Kernel.php) :

```php
// Laravel 11+ (routes/console.php) :
use Illuminate\Support\Facades\Schedule;
Schedule::command('studios:send-trial-reminders')->dailyAt('09:00');

// OU Laravel < 11 (Kernel.php) :
$schedule->command('studios:send-trial-reminders')->dailyAt('09:00');
```

```bash
git add -A && git commit -m "feat(studio-trial): email rappel J-4 + commande scheduler"
```

---

## PHASE 6 — PAGE SOUSCRIPTION (Stripe Checkout)

La page billing doit permettre au studio de souscrire quand le trial expire.

### 6A. Route souscription

Ajouter dans le groupe studio de routes/web.php :

```php
Route::get('/souscrire', [StudioController::class, 'showSubscribe'])->name('subscribe');
Route::post('/souscrire', [StudioController::class, 'processSubscribe'])->name('subscribe.process');
```

### 6B. Méthodes dans StudioController

```php
public function showSubscribe()
{
    $studio = $this->studio();
    
    // Si déjà abonné, rediriger
    if ($studio->hasActiveSubscription()) {
        return redirect()->route('studio.billing')
            ->with('info', 'Vous avez déjà un abonnement actif.');
    }

    return view('studio.subscribe', [
        'studio' => $studio,
        'monthlyPrice' => $studio->monthlyPrice(),
        'paidArtistCount' => $studio->paidArtistCount(),
    ]);
}

/**
 * Crée une session Stripe Checkout pour l'abonnement studio.
 */
public function processSubscribe()
{
    $studio = $this->studio();
    
    if ($studio->hasActiveSubscription()) {
        return redirect()->route('studio.billing');
    }

    $studioPriceId = config('services.stripe.studio_price_id');
    $artistPriceId = config('services.stripe.studio_artist_price_id');
    
    if (!$studioPriceId) {
        return back()->with('error', 'Configuration Stripe incomplète. Contactez le support.');
    }

    // Créer le customer Stripe si nécessaire
    if (!$studio->hasStripeId()) {
        $studio->createAsStripeCustomer([
            'name' => $studio->name,
            'email' => $studio->stripeEmail(),
            'metadata' => ['studio_id' => $studio->id],
        ]);
    }

    // Construire les line items
    $lineItems = [
        ['price' => $studioPriceId, 'quantity' => 1],
    ];

    $paidArtists = $studio->paidArtistCount();
    if ($paidArtists > 0 && $artistPriceId) {
        $lineItems[] = ['price' => $artistPriceId, 'quantity' => $paidArtists];
    }

    // Stripe Checkout Session
    $checkout = $studio->newSubscription('studio', collect($lineItems)->map(fn($i) => $i['price'])->toArray())
        ->checkout([
            'success_url' => route('studio.billing') . '?activated=1',
            'cancel_url' => route('studio.subscribe'),
        ]);

    return $checkout;
}
```

IMPORTANT : La méthode Cashier pour créer un checkout subscription peut varier selon la version. Vérifier la doc Cashier.

Alternative si `checkout()` ne supporte pas multi-price :

```php
// Alternative : Stripe Checkout natif
$session = \Stripe\Checkout\Session::create([
    'customer' => $studio->stripe_id,
    'mode' => 'subscription',
    'line_items' => $lineItems,
    'success_url' => route('studio.billing') . '?activated=1',
    'cancel_url' => route('studio.subscribe'),
], ['api_key' => config('cashier.secret')]);

return redirect($session->url);
```

### 6C. Vue souscription

```blade
{{-- resources/views/studio/subscribe.blade.php --}}
@extends('layouts.studio')

@section('content')
<div class="max-w-lg mx-auto space-y-6 py-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-ivoire-text">Activez votre studio</h1>
        <p class="text-sm text-titane mt-2">Continuez à utiliser toutes les fonctionnalités sans interruption.</p>
    </div>

    <div class="bg-gris-fonde rounded-2xl p-6 space-y-4">
        <h2 class="text-lg font-bold text-ivoire-text text-center">Studio Ink&Pik</h2>
        
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-ivoire-text">Abonnement Studio</span>
                <span class="text-sm font-semibold text-ivoire-text">79,99€/mois</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-titane">1 artiste inclus</span>
                <span class="text-sm text-vert-validation">✓ Inclus</span>
            </div>
            @if ($paidArtistCount > 0)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-ivoire-text">{{ $paidArtistCount }} artiste{{ $paidArtistCount > 1 ? 's' : '' }} supplémentaire{{ $paidArtistCount > 1 ? 's' : '' }}</span>
                    <span class="text-sm font-semibold text-beige-peau">{{ number_format($paidArtistCount * 39.99, 2) }}€/mois</span>
                </div>
            @endif
            <div class="border-t border-titane/20 pt-3 flex justify-between items-center">
                <span class="font-bold text-ivoire-text">Total</span>
                <span class="text-xl font-bold text-beige-peau">{{ number_format($monthlyPrice, 2) }}€<span class="text-sm text-titane font-normal">/mois</span></span>
            </div>
        </div>

        <div class="space-y-2 pt-2">
            <p class="text-xs text-titane">✓ Dashboard complet et gestion avancée</p>
            <p class="text-xs text-titane">✓ Traçabilité et fiches clients</p>
            <p class="text-xs text-titane">✓ Visibilité marketplace</p>
            <p class="text-xs text-titane">✓ Stripe Connect intégré</p>
            <p class="text-xs text-titane">✓ Sans engagement, résiliable à tout moment</p>
        </div>

        <form action="{{ route('studio.subscribe.process') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3.5 bg-beige-peau text-noir-profond rounded-xl font-bold text-base hover:bg-beige-peau/90 transition-colors active:scale-95">
                Activer l'abonnement
            </button>
        </form>
        
        <p class="text-xs text-titane text-center">Paiement sécurisé par Stripe. Facture mensuelle automatique.</p>
    </div>
</div>
@endsection
```

### 6D. Enrichir la page billing existante

Dans la vue billing, ajouter le CTA si pas abonné :

```blade
@if (!$isSubscribed && $studio->trialExpired())
    <a href="{{ route('studio.subscribe') }}" 
        class="w-full py-3.5 bg-beige-peau text-noir-profond rounded-xl font-bold text-center block hover:bg-beige-peau/90 transition-colors active:scale-95">
        Activer l'abonnement — {{ number_format($monthlyPrice, 2) }}€/mois
    </a>
@elseif (!$isSubscribed && $studio->onTrial())
    <a href="{{ route('studio.subscribe') }}" 
        class="w-full py-3 bg-gris-fonde text-ivoire-text rounded-xl font-semibold text-center block border border-beige-peau/30 hover:bg-beige-peau/10 transition-colors">
        Activer maintenant ({{ $studio->trialDaysLeft() }} jours restants)
    </a>
@endif
```

```bash
git add -A && git commit -m "feat(studio-trial): page souscription Stripe Checkout + CTA billing"
```

---

## PHASE 7 — WEBHOOK : ACTIVATION POST-SOUSCRIPTION

Quand le studio souscrit via Stripe Checkout, il faut s'assurer que le trial est terminé proprement.

### 7A. Écouter l'événement Cashier

```bash
# Vérifier les événements Cashier/Stripe existants
grep -rn "WebhookReceived\|SubscriptionCreated\|customer.subscription" app/ --include="*.php" | head -10
```

Créer un listener :

```php
// app/Listeners/HandleStudioSubscriptionCreated.php
namespace App\Listeners;

use App\Models\Studio;
use Laravel\Cashier\Events\WebhookReceived;

class HandleStudioSubscriptionCreated
{
    public function handle(WebhookReceived $event): void
    {
        $payload = $event->payload;
        
        if ($payload['type'] !== 'customer.subscription.created') return;

        $stripeId = $payload['data']['object']['customer'] ?? null;
        if (!$stripeId) return;

        $studio = Studio::where('stripe_id', $stripeId)->first();
        if (!$studio) return;

        // Le studio a souscrit → plus besoin du trial
        // On garde trial_ends_at pour historique, mais canOperate() retournera true via hasActiveSubscription()
        
        \Log::info("Studio {$studio->name} a activé son abonnement", [
            'studio_id' => $studio->id,
            'stripe_id' => $stripeId,
        ]);
    }
}
```

Enregistrer dans EventServiceProvider :

```bash
grep -n "EventServiceProvider\|WebhookReceived" app/Providers/EventServiceProvider.php 2>/dev/null
```

```php
// Dans EventServiceProvider
protected $listen = [
    // ... existant ...
    \Laravel\Cashier\Events\WebhookReceived::class => [
        \App\Listeners\HandleStudioSubscriptionCreated::class,
    ],
];
```

OU si Laravel 11+ avec event discovery automatique, le listener sera découvert automatiquement.

```bash
git add -A && git commit -m "feat(studio-trial): listener webhook activation abonnement studio"
```

---

## PHASE 8 — VÉRIFICATION FINALE

```bash
# 8A. Helpers trial
php artisan tinker --execute="
  \$s = App\Models\Studio::first();
  if (\$s) {
    echo 'trial_ends_at: ' . \$s->trial_ends_at;
    echo PHP_EOL . 'onTrial: ' . (\$s->onTrial() ? 'true' : 'false');
    echo PHP_EOL . 'trialExpired: ' . (\$s->trialExpired() ? 'true' : 'false');
    echo PHP_EOL . 'trialDaysLeft: ' . \$s->trialDaysLeft();
    echo PHP_EOL . 'canOperate: ' . (\$s->canOperate() ? 'true' : 'false');
    echo PHP_EOL . 'isReadOnly: ' . (\$s->isReadOnly() ? 'true' : 'false');
    echo PHP_EOL . 'onboardingProgress: ' . \$s->onboardingProgress() . '%';
  } else {
    echo 'Aucun studio en base';
  }
"

# 8B. Middleware enregistré
php artisan route:list --name="studio" 2>&1 | head -3

# 8C. Commande scheduler
php artisan studios:send-trial-reminders --help 2>&1 | head -3

# 8D. Mailable
php artisan tinker --execute="
  echo 'StudioTrialEndingSoonMail: ' . (class_exists('App\Mail\StudioTrialEndingSoonMail') ? 'OK' : 'ABSENT');
"

# 8E. Routes subscribe
php artisan route:list --name="studio.subscribe" 2>&1

# 8F. Vues compilent
php artisan view:clear
php artisan route:list 2>&1 | head -3

echo "=== STUDIO TRIAL 14 JOURS — TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire
2. **trial_ends_at existe déjà** (ajouté par Cashier prompt 4) — NE PAS recréer
3. **Le soft lock est PERMISSIF** — lecture seule, pas de blocage total
4. **Les routes de souscription doivent rester accessibles** même en trial expiré
5. **payment_mode** (pas payment_model) — utiliser le nom de colonne existant
6. **Commit après chaque phase**
