# 🔧 FIX — Page Profil Studio (remplacer le placeholder)
# Pour Claude Code — 1 commit

## CONTEXTE

Après inscription studio, l'utilisateur arrive sur une page profil qui affiche "en cours de développement". C'est un placeholder du Prompt 2 Phase 8 qui n'a jamais été remplacé par du contenu fonctionnel.

La page profil studio doit permettre au propriétaire de modifier les infos publiques de son studio (celles visibles sur /salon/{slug}).

## PHASE 0 — AUDIT

```bash
# 0A. Trouver la vue profil actuelle
find resources/views -path "*studio*profile*" -o -path "*studio*profil*" | sort
find resources/views/livewire -path "*studio*profile*" -o -path "*studio*profil*" | sort

# 0B. Contenu actuel
cat resources/views/studio/profile-edit.blade.php 2>/dev/null
cat resources/views/livewire/studio/profile-edit.blade.php 2>/dev/null
cat resources/views/livewire/studio/profile.blade.php 2>/dev/null

# 0C. Route profil
php artisan route:list --name="studio.profile" 2>&1

# 0D. Controller/Livewire qui gère le profil
grep -rn "function profile\|studio.profile" app/Http/Controllers/StudioController.php | head -5
find app/Livewire/Studio -name "*rofi*" -type f 2>/dev/null

# 0E. La vue settings existe et fonctionne ? (pour ne pas dupliquer)
cat resources/views/livewire/studio/settings.blade.php 2>/dev/null | head -30
cat resources/views/studio/settings.blade.php 2>/dev/null | head -30

# 0F. Le profil public fonctionne ?
cat resources/views/studio/public-profile.blade.php 2>/dev/null | head -20
```

**MONTRE-MOI les résultats.**

## PHASE 1 — DÉCISION

Deux approches possibles :

**Option A** : Si la page Settings contient DÉJÀ les infos du studio (nom, description, adresse, photos, horaires), alors la page Profil est un DOUBLON. Dans ce cas :
- Rediriger `studio.profile` → `studio.settings`
- Supprimer la vue placeholder

**Option B** : Si Settings est technique (paiement, Stripe, config) et Profil est la fiche publique (description, photos, horaires), alors créer la page Profil en reprenant le contenu de la vue Settings qui concerne l'apparence publique.

Vérifier en regardant le contenu de settings :
```bash
grep -n "description\|photo\|logo\|cover\|horaire\|opening_hours\|address\|adresse" resources/views/livewire/studio/settings.blade.php resources/views/studio/settings.blade.php 2>/dev/null
```

- Si settings contient DÉJÀ description + photos + horaires → **Option A** (redirection)
- Sinon → **Option B** (créer la page)

## PHASE 2A — SI OPTION A (Settings contient tout) : REDIRECTION

Dans le controller ou le composant Livewire qui gère `studio.profile` :

```php
public function profile()
{
    return redirect()->route('studio.settings');
}
```

OU dans le composant Livewire, rediriger dans mount() :

```php
public function mount()
{
    return $this->redirect(route('studio.settings'), navigate: true);
}
```

ET mettre à jour la navigation sidebar pour pointer vers settings au lieu de profil, ou garder les deux liens qui pointent vers la même page.

## PHASE 2B — SI OPTION B (Profil séparé) : CRÉER LA PAGE

Remplacer le placeholder par une vraie page de modification du profil public.

La page doit contenir :
- **Aperçu en direct** du profil public (comment les clients voient le studio)
- Formulaire : nom, description, adresse, ville, CP, téléphone
- Upload : logo, cover, photos du salon
- Horaires d'ouverture
- Liens réseaux sociaux
- Bouton "Voir mon profil public" → ouvre /salon/{slug} dans un nouvel onglet

Si c'est un composant Livewire, adapter le composant existant. Le formulaire utilise les mêmes champs que ceux dans Studio model ($fillable).

Utiliser le même design system : noir-profond, gris-fonde, beige-peau, ivoire-text, titane.

```blade
{{-- Remplacer le contenu du placeholder par : --}}
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Profil Studio</h1>
            <p class="text-sm text-titane mt-1">Ce que les clients voient sur votre page publique</p>
        </div>
        @if ($studio->slug)
            <a href="{{ route('studio.public.show', $studio->slug) }}" target="_blank"
                class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold flex items-center gap-1">
                Voir mon profil public ↗
            </a>
        @endif
    </div>

    <form action="{{ route('studio.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Photos --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">📸 Visuels</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-titane block mb-2">Logo</label>
                    @if ($studio->getFirstMediaUrl('logo'))
                        <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="Logo" class="w-20 h-20 rounded-xl object-cover mb-2">
                    @endif
                    <input type="file" name="logo" accept="image/*"
                        class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer">
                </div>
                <div>
                    <label class="text-xs text-titane block mb-2">Photo de couverture</label>
                    @if ($studio->getFirstMediaUrl('cover'))
                        <img src="{{ $studio->getFirstMediaUrl('cover') }}" alt="Cover" class="w-full h-24 rounded-xl object-cover mb-2">
                    @endif
                    <input type="file" name="cover" accept="image/*"
                        class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer">
                </div>
            </div>
            <div>
                <label class="text-xs text-titane block mb-2">Photos du salon</label>
                @if ($studio->getMedia('photos')->count() > 0)
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach ($studio->getMedia('photos') as $photo)
                            <img src="{{ $photo->getUrl() }}" alt="" class="w-20 h-20 rounded-lg object-cover">
                        @endforeach
                    </div>
                @endif
                <input type="file" name="photos[]" accept="image/*" multiple
                    class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer">
            </div>
        </div>

        {{-- Informations --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">🏢 Informations</h2>
            <div>
                <label class="text-xs text-titane block mb-1">Nom du studio *</label>
                <input type="text" name="name" value="{{ old('name', $studio->name) }}" required
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Description</label>
                <textarea name="description" rows="4" placeholder="Présentez votre studio aux clients..."
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau resize-none">{{ old('description', $studio->description) }}</textarea>
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Adresse</label>
                <input type="text" name="address" value="{{ old('address', $studio->address) }}"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Ville</label>
                    <input type="text" name="city" value="{{ old('city', $studio->city) }}"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                </div>
                <div class="w-full sm:w-32">
                    <label class="text-xs text-titane block mb-1">Code postal</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $studio->postal_code) }}" maxlength="5"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $studio->phone) }}"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                </div>
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Email professionnel</label>
                    <input type="email" name="email" value="{{ old('email', $studio->email) }}"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                </div>
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Site web</label>
                <input type="url" name="website" value="{{ old('website', $studio->website) }}" placeholder="https://..."
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
            </div>
        </div>

        {{-- Horaires --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6" x-data="{
            hours: {{ Js::from($studio->opening_hours ?? [
                'lundi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'mardi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'mercredi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'jeudi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'vendredi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'samedi' => ['open' => '10:00', 'close' => '18:00', 'closed' => false],
                'dimanche' => ['open' => '', 'close' => '', 'closed' => true],
            ]) }}
        }">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-4">🕐 Horaires d'ouverture</h2>
            <div class="space-y-2">
                <template x-for="(day, name) in hours" :key="name">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="w-24 text-sm text-ivoire-text capitalize" x-text="name"></span>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="checkbox" :name="'opening_hours[' + name + '][closed]'" x-model="day.closed"
                                class="rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                            <span class="text-xs text-titane">Fermé</span>
                        </label>
                        <template x-if="!day.closed">
                            <div class="flex items-center gap-1">
                                <input type="time" :name="'opening_hours[' + name + '][open]'" x-model="day.open"
                                    class="px-2 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                <span class="text-titane text-xs">→</span>
                                <input type="time" :name="'opening_hours[' + name + '][close]'" x-model="day.close"
                                    class="px-2 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
            Sauvegarder le profil
        </button>
    </form>
</div>
```

IMPORTANT :
- Si la vue est un composant Livewire (pas une vue Blade classique), adapter : utiliser wire:submit au lieu de form action, utiliser des propriétés Livewire au lieu de old(), etc.
- Si la route studio.profile.update n'existe pas, la créer ou réutiliser studio.settings.update
- Vérifier que le StudioController::updateSettings() gère les champs profil (nom, description, photos, horaires)

```bash
# Vérifier
php artisan route:list --name="studio.profile" 2>&1
php artisan route:list --name="studio.settings" 2>&1

git add -A && git commit -m "fix(studio): remplacer placeholder profil par page fonctionnelle"
```

## ⚠️ RÈGLES
1. **Audit d'abord** — comprendre si c'est Livewire ou Blade, et si settings couvre déjà le profil
2. **Ne pas dupliquer** — si settings fait la même chose, rediriger
3. **Design system** : noir-profond, gris-fonde, beige-peau, ivoire-text, titane
4. **1 commit**
