# 🏢 STUDIO PROMPT 3/4 — Artiste Studio (workflow complet)
# Pour Claude Code — Commit après chaque phase

## CONTEXTE

Suite des Prompts 1 et 2. En place :
- Models Studio + StudioArtist complets
- Tables avec toutes les colonnes (dont invitation_token, artisan_type)
- StudioController avec storeArtist(), inviteArtist(), acceptInvitation(), processInvitation()
- Layout studio + Dashboard + Settings + Liste artistes + Formulaire création/invitation
- Profil public salon + Page acceptation invitation
- Routes studio complètes
- Rôles Spatie : studio, studio_owner, studio_artist

Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, MySQL, Stripe Connect.

### CE QUE CE PROMPT IMPLÉMENTE
1. Email d'invitation artiste (Mailable)
2. Email identifiants temporaires (création directe)
3. Stripe Connect pour artiste studio (centralisé vs distribué)
4. L'artiste studio utilise les MÊMES vues que tattooer/piercer indépendant
5. Navigation : l'artiste studio voit le nom du studio dans son layout
6. Le studio peut voir les demandes/stats de ses artistes

---

## PHASE 0 — AUDIT

```bash
# 0A. Mailables existants
find app/Mail -type f 2>/dev/null | sort
grep -rn "StudioInvitation\|StudioArtist\|TemporaryPassword\|invitation" app/Mail/ 2>/dev/null

# 0B. Comment le storeArtist et inviteArtist sont codés
grep -A 30 "function storeArtist" app/Http/Controllers/StudioController.php
grep -A 30 "function inviteArtist" app/Http/Controllers/StudioController.php

# 0C. Comment l'artiste studio est redirigé après login
grep -rn "studio_artist\|isStudioArtist\|redirect.*after.*login" app/Http/Controllers/Auth/ app/Http/Middleware/ app/Providers/ 2>/dev/null | head -10

# 0D. Layout tattooer — comment l'artiste sait s'il est rattaché à un studio
grep -n "studio\|isStudioArtist\|artistStudio" resources/views/layouts/tattooer.blade.php 2>/dev/null

# 0E. Stripe Connect onboarding existant
grep -rn "stripe.*onboarding\|stripe.*connect\|stripe_account_id" app/Http/Controllers/TattooerController.php app/Http/Controllers/StudioController.php | head -10

# 0F. Comment le Stripe Connect est configuré pour les artistes indépendants
grep -rn "Stripe\\\Account\|stripe.*account.*create\|connect.*onboarding" app/ --include="*.php" | head -15
```

**MONTRE-MOI les résultats avant de continuer.**

---

## PHASE 1 — MAILABLE : INVITATION STUDIO

```bash
php artisan make:mail StudioInvitationMail --markdown=emails.studio.invitation
```

```php
// app/Mail/StudioInvitationMail.php
namespace App\Mail;

use App\Models\Studio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudioInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Studio $studio,
        public string $token,
        public string $artisanType,
        public string $email
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->studio->name} vous invite à rejoindre Ink&Pik",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.studio.invitation',
            with: [
                'studio' => $this->studio,
                'invitationUrl' => route('studio.invitation.accept', $this->token),
                'artisanType' => $this->artisanType === 'piercer' ? 'Pierceur' : 'Tatoueur',
            ],
        );
    }
}
```

Vue email :
```blade
{{-- resources/views/emails/studio/invitation.blade.php --}}
<x-mail::message>
# Vous êtes invité !

**{{ $studio->name }}** vous invite à rejoindre son studio sur Ink&Pik en tant que **{{ $artisanType }}**.

En acceptant cette invitation, vous bénéficierez de :
- Un profil professionnel complet
- La gestion de vos réservations et clients
- La visibilité sur la marketplace Ink&Pik

<x-mail::button :url="$invitationUrl">
Accepter l'invitation
</x-mail::button>

Ce lien est valable 7 jours.

Cordialement,<br>
L'équipe Ink&Pik
</x-mail::message>
```

### Brancher l'envoi dans StudioController::inviteArtist()

Trouver le TODO dans inviteArtist() et le remplacer par l'envoi réel :

```php
// Dans inviteArtist(), remplacer le TODO par :
\Mail::to($validated['email'])->send(new \App\Mail\StudioInvitationMail(
    $studio,
    $token,
    $validated['artisan_type'],
    $validated['email']
));
```

```bash
git add -A && git commit -m "feat(studio): email invitation artiste avec lien d'acceptation"
```

---

## PHASE 2 — MAILABLE : IDENTIFIANTS TEMPORAIRES (création directe)

```bash
php artisan make:mail StudioArtistCreatedMail --markdown=emails.studio.artist-created
```

```php
// app/Mail/StudioArtistCreatedMail.php
namespace App\Mail;

use App\Models\Studio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudioArtistCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Studio $studio,
        public string $name,
        public string $email,
        public string $tempPassword,
        public string $artisanType
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre compte Ink&Pik a été créé par {$this->studio->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.studio.artist-created',
            with: [
                'studio' => $this->studio,
                'name' => $this->name,
                'email' => $this->email,
                'tempPassword' => $this->tempPassword,
                'artisanType' => $this->artisanType === 'piercer' ? 'Pierceur' : 'Tatoueur',
                'loginUrl' => route('login'),
            ],
        );
    }
}
```

Vue email :
```blade
{{-- resources/views/emails/studio/artist-created.blade.php --}}
<x-mail::message>
# Bienvenue sur Ink&Pik, {{ $name }} !

**{{ $studio->name }}** a créé votre compte professionnel en tant que **{{ $artisanType }}**.

Voici vos identifiants de connexion :

- **Email** : {{ $email }}
- **Mot de passe temporaire** : {{ $tempPassword }}

**⚠️ Pensez à changer votre mot de passe dès votre première connexion.**

<x-mail::button :url="$loginUrl">
Se connecter
</x-mail::button>

Cordialement,<br>
L'équipe Ink&Pik
</x-mail::message>
```

### Brancher dans StudioController::storeArtist()

Trouver le TODO/flash message dans storeArtist() et ajouter l'envoi email :

```php
// Après la création du StudioArtist, AVANT le return :
\Mail::to($validated['email'])->send(new \App\Mail\StudioArtistCreatedMail(
    $studio,
    $validated['name'],
    $validated['email'],
    $tempPassword,
    $validated['artisan_type']
));

// Modifier le return pour ne plus afficher le mot de passe dans le flash :
return redirect()->route('studio.artists')
    ->with('success', "Artiste {$validated['name']} créé. Un email avec les identifiants a été envoyé.");
```

```bash
git add -A && git commit -m "feat(studio): email identifiants temporaires pour artiste créé par le studio"
```

---

## PHASE 3 — STRIPE CONNECT : CENTRALISÉ vs DISTRIBUÉ

Le studio choisit son modèle de paiement (dans settings, colonne `payment_mode`).

### 3A. Comprendre le flow existant

```bash
# Comment un artiste indépendant fait son onboarding Stripe Connect
grep -rn "stripe.*onboarding\|stripe.*account\|Account::create\|accountLinks\|AccountLink" app/ --include="*.php" | head -20

# Le service ou controller qui gère ça
grep -rn "onboarding\|connectStripe\|stripeOnboarding" app/Http/Controllers/ app/Services/ --include="*.php" | head -10
```

### 3B. Logique pour artiste studio

La logique dépend du `payment_mode` du studio :

**Si CENTRALISÉ** (`studio.payment_mode === 'centralized'`) :
- L'artiste studio N'A PAS besoin de son propre Stripe Connect
- Les paiements passent par le compte Stripe Connect du STUDIO
- Dans le workflow booking, quand un client paie un acompte :
  - `stripe_account_id` = celui du STUDIO, pas de l'artiste
  - L'application_fee est calculée selon le plan du studio

**Si DISTRIBUÉ** (`studio.payment_mode === 'distributed'`) :
- L'artiste studio A besoin de son propre Stripe Connect (comme un indépendant)
- Le flow d'onboarding Stripe est identique à un artiste indépendant
- Dans le workflow booking :
  - `stripe_account_id` = celui de l'ARTISTE
  - L'application_fee est calculée selon le plan de l'artiste

### 3C. Adapter le helper pour récupérer le stripe_account_id

Dans le trait IsArtisan (ou dans chaque model), ajouter :

```php
/**
 * Retourne le stripe_account_id à utiliser pour les paiements.
 * Si artiste studio en mode centralisé → stripe du studio.
 * Sinon → stripe de l'artiste.
 */
public function getStripeAccountId(): ?string
{
    // Artiste rattaché à un studio en mode centralisé
    if ($this->studio_id) {
        $studio = $this->studio;
        if ($studio && $studio->payment_mode === 'centralized') {
            return $studio->stripe_account_id;
        }
    }
    
    // Artiste indépendant ou studio distribué
    return $this->stripe_account_id;
}

/**
 * Vérifie si l'artiste a un Stripe Connect opérationnel
 */
public function hasStripeConnect(): bool
{
    return !empty($this->getStripeAccountId());
}

/**
 * L'artiste a-t-il besoin de configurer son propre Stripe Connect ?
 * Non si le studio est en mode centralisé.
 */
public function needsOwnStripeConnect(): bool
{
    if ($this->studio_id) {
        $studio = $this->studio;
        if ($studio && $studio->payment_mode === 'centralized') {
            return false; // Le studio gère tout
        }
    }
    return true; // Indépendant ou studio distribué
}
```

### 3D. Adapter les endroits qui utilisent stripe_account_id

```bash
# Trouver tous les endroits qui récupèrent le stripe_account_id
grep -rn "stripe_account_id\|->stripe_account" app/ --include="*.php" | grep -v "migration\|Model\|fillable\|Schema" | head -20
```

Pour CHAQUE endroit trouvé, remplacer :
```php
// AVANT
$artisan->stripe_account_id

// APRÈS  
$artisan->getStripeAccountId()
```

SAUF dans les Models (fillable, casts) et les migrations.

### 3E. Adapter l'onboarding Stripe pour artiste studio

Dans le dashboard artiste (layout tattooer), l'artiste studio en mode centralisé ne doit PAS voir le bouton "Configurer Stripe Connect" car le studio gère les paiements.

```bash
# Trouver où le bouton Stripe onboarding est affiché
grep -rn "stripe.*onboarding\|connecter.*stripe\|configurer.*stripe\|stripe.*connect" resources/views/ --include="*.blade.php" | head -10
```

Ajouter un conditionnel :
```blade
@if ($artisan->needsOwnStripeConnect())
    {{-- Bouton Stripe Connect existant --}}
    @if (!$artisan->stripe_account_id)
        <a href="{{ route($routePrefix . '.stripe.onboarding') }}" ...>Configurer Stripe Connect</a>
    @endif
@else
    <div class="bg-gris-fonde rounded-xl p-4">
        <p class="text-sm text-titane">💳 Les paiements sont gérés par votre studio <strong class="text-ivoire-text">{{ $artisan->studio?->name }}</strong>.</p>
    </div>
@endif
```

```bash
git add -A && git commit -m "feat(studio): Stripe Connect centralisé/distribué + getStripeAccountId()"
```

---

## PHASE 4 — L'ARTISTE STUDIO UTILISE LES MÊMES VUES

L'artiste rattaché au studio est un User normal avec un profil Tattooer ou Piercer.
Il utilise EXACTEMENT les mêmes routes et vues que les indépendants.

### 4A. Vérifier que la redirection post-login fonctionne

```bash
# Comment la redirection après login est gérée
grep -rn "redirectTo\|authenticated\|HOME\|redirect.*dashboard" app/Http/Controllers/Auth/ app/Providers/ app/Http/Middleware/ | head -15
```

Le user avec rôle `studio_artist` + `tattooer` doit être redirigé vers `tattooer.dashboard`, pas vers `studio.dashboard`.
Le user avec rôle `studio_artist` + `pierceur` doit être redirigé vers `pierceur.dashboard`.

Vérifier que la logique de redirection respecte le rôle artisan AVANT le rôle studio_artist :

```php
// Dans la logique de redirection (RouteServiceProvider, LoginController, etc.)
if ($user->hasRole('tattooer') || $user->role === 'tattooer') {
    return redirect()->route('tattooer.dashboard');
}
if ($user->hasRole('pierceur') || $user->role === 'pierceur') {
    return redirect()->route('pierceur.dashboard');
}
if ($user->hasRole('studio') || $user->role === 'studio') {
    return redirect()->route('studio.dashboard');
}
// Le rôle studio_artist est secondaire — l'artiste se comporte comme un tattooer/pierceur
```

### 4B. Adapter le layout artisan pour montrer le studio

Dans le layout tattooer/artisan, si l'artiste est rattaché à un studio, afficher une petite bannière ou un badge :

```bash
grep -n "isStudioArtist\|artistStudio\|studio" resources/views/layouts/tattooer.blade.php | head -5
```

Ajouter dans le layout (en haut de la sidebar ou dans le header) :

```blade
@php
    $userStudio = auth()->user()->isStudioArtist() ? auth()->user()->artistStudio() : null;
@endphp

@if ($userStudio)
    <div class="px-4 py-2 bg-beige-peau/10 border-b border-beige-peau/20">
        <div class="flex items-center gap-2">
            @if ($userStudio->getFirstMediaUrl('logo'))
                <img src="{{ $userStudio->getFirstMediaUrl('logo') }}" alt="" class="w-6 h-6 rounded object-cover">
            @else
                <span class="text-sm">🏢</span>
            @endif
            <span class="text-xs text-beige-peau font-semibold truncate">{{ $userStudio->name }}</span>
        </div>
    </div>
@endif
```

### 4C. Le middleware doit accepter l'artiste studio

Vérifier que le middleware `role:tattooer` (ou `role:pierceur`) accepte AUSSI les artistes studio :

```bash
cat app/Http/Middleware/EnsureUserHasRole.php
```

Le middleware vérifie probablement `$user->role`. Un artiste studio a `role = 'tattooer'` ou `role = 'pierceur'` en rôle principal. Le rôle `studio_artist` est un rôle secondaire (Spatie), pas le champ `role` de la table users.

Si le middleware fait `$user->role === 'tattooer'` → ça marche déjà, car storeArtist() ne met pas `role = 'studio_artist'` mais `role = 'tattooer'` ou `role = 'pierceur'`.

**VÉRIFIER** que la méthode `storeArtist()` dans StudioController met bien le bon `role` sur le User :

```bash
grep -A 20 "function storeArtist" app/Http/Controllers/StudioController.php | grep "role"
```

Si le User est créé sans `role` → ajouter :
```php
$user = User::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'password' => Hash::make($tempPassword),
    'email_verified_at' => now(),
    'role' => $validated['artisan_type'] === 'piercer' ? 'pierceur' : 'tattooer',
    // ^^ IMPORTANT pour le middleware role:
]);
```

Même chose dans `processInvitation()` :
```php
$user = User::create([
    // ...
    'role' => $invitation->artisan_type === 'piercer' ? 'pierceur' : 'tattooer',
]);
```

```bash
git add -A && git commit -m "feat(studio): artiste studio utilise les mêmes vues + badge studio dans layout"
```

---

## PHASE 5 — LE STUDIO VOIT LES DEMANDES DE SES ARTISTES

Le dashboard studio affiche déjà un compteur de demandes. Mais le studio doit pouvoir VOIR les demandes en détail.

### 5A. Ajouter une vue demandes dans le StudioController

```php
// Dans StudioController, ajouter :
public function requests()
{
    $studio = $this->studio();
    $artistIds = $studio->studioArtists()
        ->where('is_active', true)
        ->pluck('user_id')
        ->filter();
    
    // Récupérer les IDs des profils artisan (tattooers + piercers)
    $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistIds)->pluck('id');
    $piercerIds = \App\Models\Piercer::whereIn('user_id', $artistIds)->pluck('id');
    
    $requests = \App\Models\BookingRequest::where(function($q) use ($tattooerIds, $piercerIds) {
        $q->where(function($q2) use ($tattooerIds) {
            $q2->where('bookable_type', 'App\\Models\\Tattooer')
               ->whereIn('bookable_id', $tattooerIds);
        })->orWhere(function($q2) use ($piercerIds) {
            $q2->where('bookable_type', 'App\\Models\\Piercer')
               ->whereIn('bookable_id', $piercerIds);
        });
    })
    ->with(['bookable', 'client.user'])
    ->latest()
    ->paginate(20);

    return view('studio.requests', [
        'studio' => $studio,
        'requests' => $requests,
    ]);
}
```

### 5B. Route

Ajouter dans le groupe studio de routes/web.php :
```php
Route::get('/demandes', [StudioController::class, 'requests'])->name('requests');
```

### 5C. Vue

```blade
{{-- resources/views/studio/requests.blade.php --}}
@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Demandes</h1>
    <p class="text-sm text-titane">Toutes les demandes adressées aux artistes de votre studio</p>

    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
        @forelse ($requests as $request)
            <div class="p-4 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-ivoire-text truncate">
                            {{ $request->client?->user?->name ?? 'Client' }}
                        </p>
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                            @if ($request->status->value ?? $request->status === 'pending') bg-yellow-500/20 text-yellow-400
                            @elseif (in_array($request->status->value ?? $request->status, ['accepted', 'deposit_paid'])) bg-vert-validation/20 text-vert-validation
                            @elseif ($request->status->value ?? $request->status === 'completed') bg-blue-500/20 text-blue-400
                            @else bg-titane/20 text-titane @endif">
                            {{ $request->status->value ?? $request->status }}
                        </span>
                    </div>
                    <p class="text-xs text-titane mt-0.5">
                        → {{ $request->bookable?->user?->name ?? 'Artiste' }}
                        ({{ $request->bookable instanceof \App\Models\Piercer ? '💎' : '🎨' }})
                        • {{ $request->created_at?->diffForHumans() }}
                    </p>
                </div>
                @if ($request->deposit_amount)
                    <span class="text-sm font-semibold text-beige-peau">{{ number_format($request->deposit_amount / 100, 2) }}€</span>
                @endif
            </div>
        @empty
            <p class="text-sm text-titane text-center py-8">Aucune demande</p>
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
@endsection
```

### 5D. Ajouter dans la navigation studio

Dans le layout studio (sidebar), ajouter le lien Demandes :

```blade
{{-- Après le lien Planning --}}
<a href="{{ route('studio.requests') }}" class="...">
    📋 Demandes
</a>
```

```bash
git add -A && git commit -m "feat(studio): vue demandes globale avec toutes les demandes des artistes"
```

---

## PHASE 6 — MARKETPLACE : STUDIOS VISIBLES

Les studios doivent être trouvables dans la marketplace (en plus des artistes individuels).

### 6A. Audit

```bash
# Comment la marketplace affiche les résultats
grep -rn "studio\|Studio" app/Services/MarketplaceSearchService.php | head -10
grep -rn "studio\|Studio" resources/views/marketplace/ --include="*.blade.php" | head -10
```

### 6B. Ajouter les studios dans la marketplace

Si le MarketplaceSearchService ne retourne pas les studios, l'adapter :

```php
// Dans le service, ajouter une méthode ou merger les studios :
public function getStudios($filters = [])
{
    return Studio::with(['studioArtists.user', 'media'])
        ->where('is_active', true)
        ->when($filters['city'] ?? null, fn($q, $city) => $q->where('city', 'like', "%{$city}%"))
        ->get();
}
```

Et dans la vue marketplace, ajouter une section ou un filtre "Studios" :

```blade
{{-- Ajouter un bouton filtre --}}
<button @click="filter = 'studio'" ...>🏢 Studios</button>
```

Avec les cartes studio :
```blade
@foreach ($studios as $studio)
    <a href="{{ route('studio.public.show', $studio->slug) }}" class="bg-gris-fonde rounded-xl overflow-hidden hover:ring-2 hover:ring-beige-peau/50 transition-all group">
        <div class="h-40 bg-noir-profond overflow-hidden">
            <img src="{{ $studio->getFirstMediaUrl('cover') ?: $studio->getFirstMediaUrl('logo') ?: asset('images/default-studio.png') }}" 
                alt="{{ $studio->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        </div>
        <div class="p-3">
            <div class="flex items-center gap-2">
                @if ($studio->getFirstMediaUrl('logo'))
                    <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="" class="w-8 h-8 rounded object-cover">
                @endif
                <div>
                    <p class="text-sm font-semibold text-ivoire-text">{{ $studio->name }}</p>
                    <p class="text-xs text-titane">
                        🏢 Studio • {{ $studio->city }}
                        • {{ $studio->studioArtists()->where('is_active', true)->count() }} artiste(s)
                    </p>
                </div>
            </div>
        </div>
        <span class="absolute top-2 right-2 px-2 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-400">
            🏢 Studio
        </span>
    </a>
@endforeach
```

IMPORTANT : adapter au format EXACT de la vue marketplace existante. Ne pas casser le layout.

```bash
git add -A && git commit -m "feat(marketplace): studios visibles dans la recherche + filtre type"
```

---

## PHASE 7 — VÉRIFICATION FINALE

```bash
# 7A. Mailables
php artisan tinker --execute="
  echo 'StudioInvitationMail: ' . (class_exists('App\Mail\StudioInvitationMail') ? 'OK' : 'ABSENT');
  echo PHP_EOL . 'StudioArtistCreatedMail: ' . (class_exists('App\Mail\StudioArtistCreatedMail') ? 'OK' : 'ABSENT');
"

# 7B. getStripeAccountId dans les models
grep -n "getStripeAccountId\|needsOwnStripeConnect\|hasStripeConnect" app/Models/Tattooer.php app/Models/Piercer.php app/Models/Traits/IsArtisan.php 2>/dev/null

# 7C. stripe_account_id remplacé par getStripeAccountId()
grep -rn "->stripe_account_id" app/ --include="*.php" | grep -v "Model\|Migration\|fillable\|casts\|Schema\|getStripe" | head -10
# Les résidus sont à corriger

# 7D. Routes studio complètes
php artisan route:list --name="studio" 2>&1 | wc -l

# 7E. L'artiste studio a le bon role pour le middleware
php artisan tinker --execute="
  \$sa = App\Models\StudioArtist::first();
  if (\$sa && \$sa->user) {
    echo 'User: ' . \$sa->user->name;
    echo ' | role: ' . \$sa->user->role;
    echo ' | artisanType: ' . \$sa->user->artisanType();
    echo ' | isStudioArtist: ' . (\$sa->user->isStudioArtist() ? 'true' : 'false');
  } else {
    echo 'Aucun studio artist en base';
  }
"

# 7F. Vues compilent
php artisan view:clear
php artisan route:list 2>&1 | head -3

echo "=== PROMPT 3/4 STUDIO — ARTISTE WORKFLOW TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire
2. **NE PAS dupliquer les vues artiste** — L'artiste studio utilise EXACTEMENT les mêmes vues que les indépendants
3. **Le champ user.role PRIME sur Spatie** — Le middleware vérifie user.role, pas Spatie
4. **payment_mode** (pas payment_model) — Utiliser le nom de colonne existant
5. **Ne pas casser Stripe Connect existant** — Les artistes indépendants doivent continuer à fonctionner
6. **Commit après chaque phase**
