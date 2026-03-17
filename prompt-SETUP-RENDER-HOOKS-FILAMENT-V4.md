# 🎨 SETUP RENDER HOOKS — Filament v4 (Ink&Pik Admin)

## Objectif
Mettre en place l'infrastructure complète des Render Hooks Filament v4
pour pouvoir customiser l'UI/UX du panel admin à volonté.

## Documentation de référence
https://filamentphp.com/docs/4.x/advanced/render-hooks

---

## PHASE 1 — AUDIT PRÉALABLE

```bash
# Vérifier le ServiceProvider du panel admin
cat app/Providers/Filament/AdminPanelProvider.php

# Vérifier si un AppServiceProvider existe
cat app/Providers/AppServiceProvider.php

# Vérifier les vues Blade existantes liées au panel
find resources/views/filament/ -type f | sort

# Vérifier les assets existants
ls public/css/ public/js/ 2>/dev/null
ls resources/css/ resources/js/ 2>/dev/null
```

---

## PHASE 2 — CRÉER LE SERVICE PROVIDER DÉDIÉ AUX RENDER HOOKS

Créer un ServiceProvider dédié pour ne pas polluer `AdminPanelProvider` :

```bash
php artisan make:provider FilamentRenderHooksServiceProvider
```

Contenu de `app/Providers/FilamentRenderHooksServiceProvider.php` :

```php
<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;

class FilamentRenderHooksServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ─── TOPBAR ────────────────────────────────────────────────────
        // Bannière au-dessus de la topbar (alertes système, maintenance...)
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_BEFORE,
            fn (): View => view('filament.hooks.topbar-before'),
        );

        // Contenu dans la topbar côté start
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_START,
            fn (): View => view('filament.hooks.topbar-start'),
        );

        // ─── SIDEBAR ───────────────────────────────────────────────────
        // Avant le logo dans la sidebar
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_LOGO_BEFORE,
            fn (): View => view('filament.hooks.sidebar-logo-before'),
        );

        // Après le logo dans la sidebar
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_LOGO_AFTER,
            fn (): View => view('filament.hooks.sidebar-logo-after'),
        );

        // Au début de la nav sidebar (après <nav>)
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            fn (): View => view('filament.hooks.sidebar-nav-start'),
        );

        // À la fin de la nav sidebar (avant </nav>)
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_END,
            fn (): View => view('filament.hooks.sidebar-nav-end'),
        );

        // Footer de la sidebar (épinglé en bas)
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_FOOTER,
            fn (): View => view('filament.hooks.sidebar-footer'),
        );

        // ─── CONTENU PAGES ─────────────────────────────────────────────
        // Avant le contenu de toutes les pages
        FilamentView::registerRenderHook(
            PanelsRenderHook::CONTENT_BEFORE,
            fn (): View => view('filament.hooks.content-before'),
        );

        // Après le contenu de toutes les pages
        FilamentView::registerRenderHook(
            PanelsRenderHook::CONTENT_AFTER,
            fn (): View => view('filament.hooks.content-after'),
        );

        // ─── HEAD & BODY ───────────────────────────────────────────────
        // Dans le <head> (CSS custom, fonts, meta...)
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): View => view('filament.hooks.head-end'),
        );

        // Scripts avant </body>
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): View => view('filament.hooks.body-end'),
        );

        // ─── FOOTER ────────────────────────────────────────────────────
        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn (): View => view('filament.hooks.footer'),
        );
    }
}
```

Enregistrer dans `bootstrap/providers.php` :

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FilamentRenderHooksServiceProvider::class, // ← ajouter
    // ... autres providers
];
```

---

## PHASE 3 — CRÉER TOUTES LES VUES BLADE HOOKS

Créer le dossier et tous les fichiers vides avec contenu par défaut :

```bash
mkdir -p resources/views/filament/hooks
```

### 3.1 — `topbar-before.blade.php`
Bannière système au-dessus de la topbar.
Vide par défaut — décommenter pour activer :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::TOPBAR_BEFORE
    Position : Tout en haut, au-dessus de la barre de navigation
    Utilisation : Bannières système, alertes maintenance, annonces importantes

    Exemple bannière maintenance :
    <div class="w-full bg-yellow-500 text-black text-center py-2 text-sm font-medium">
        ⚠️ Maintenance prévue le 20/03 de 22h à 23h — Sauvegardez votre travail.
    </div>

    Exemple bannière info :
    <div class="w-full bg-primary-600 text-white text-center py-1.5 text-xs">
        🚀 Ink&Pik v1.2 est disponible —
        <a href="#" class="underline font-medium">Voir les nouveautés</a>
    </div>
--}}
```

### 3.2 — `topbar-start.blade.php`
Contenu au début de la topbar (côté logo) :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::TOPBAR_START
    Position : Début de la barre du haut, avant le logo
    Utilisation : Indicateur d'environnement, mode debug...

    Exemple badge environnement dev :
--}}
@if (app()->environment('local'))
<span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium
             bg-orange-100 text-orange-700 rounded-lg mr-3">
    🔧 DEV
</span>
@endif
```

### 3.3 — `sidebar-logo-before.blade.php`
Avant le logo dans la sidebar :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::SIDEBAR_LOGO_BEFORE
    Position : Juste avant le logo Ink&Pik dans la sidebar
    Utilisation : Badges de version, indicateurs...
--}}
```

### 3.4 — `sidebar-logo-after.blade.php`
Après le logo — idéal pour un sous-titre ou badge version :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::SIDEBAR_LOGO_AFTER
    Position : Juste après le logo dans la sidebar
    Utilisation : Version, sous-titre, badge plan...
--}}
<div class="px-4 pb-2">
    <span class="text-xs text-gray-400 dark:text-gray-500">
        Panel Administration
    </span>
</div>
```

### 3.5 — `sidebar-nav-start.blade.php`
Début de la navigation sidebar :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::SIDEBAR_NAV_START
    Position : Tout en haut de la nav (après <nav>)
    Utilisation : Raccourcis rapides, stats mini, actions globales...

    Exemple bloc stats rapides :
--}}
{{-- Décommenter pour activer
<div class="mx-3 mb-3 p-3 rounded-xl bg-gray-800/50 dark:bg-gray-800/80 border border-gray-700/50">
    <div class="grid grid-cols-2 gap-2">
        <div class="text-center">
            <p class="text-lg font-bold text-white">{{ \App\Models\BookingRequest::where('status', 'pending')->count() }}</p>
            <p class="text-xs text-gray-400">En attente</p>
        </div>
        <div class="text-center">
            <p class="text-lg font-bold text-orange-400">
                {{ \App\Models\BookingRequest::where('status', 'cancelled')->whereNull('refund_processed_at')->whereNotNull('deposit_paid_at')->count() }}
            </p>
            <p class="text-xs text-gray-400">Remboursements</p>
        </div>
    </div>
</div>
--}}
```

### 3.6 — `sidebar-nav-end.blade.php`
Fin de la navigation sidebar :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::SIDEBAR_NAV_END
    Position : Tout en bas de la nav (avant </nav>)
    Utilisation : Liens utiles, séparateurs, infos supplémentaires...

    Exemple liens rapides :
--}}
{{-- Décommenter pour activer
<div class="mx-3 mt-2 pt-3 border-t border-gray-700/50">
    <a href="{{ route('welcome') }}" target="_blank"
       class="flex items-center gap-2 px-3 py-2 text-xs text-gray-400
              hover:text-white rounded-lg hover:bg-gray-700/50 transition">
        <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4"/>
        Voir le site
    </a>
    <a href="https://docs.inkpik.fr" target="_blank"
       class="flex items-center gap-2 px-3 py-2 text-xs text-gray-400
              hover:text-white rounded-lg hover:bg-gray-700/50 transition">
        <x-heroicon-o-book-open class="w-4 h-4"/>
        Documentation
    </a>
</div>
--}}
```

### 3.7 — `sidebar-footer.blade.php`
Footer épinglé en bas de la sidebar :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::SIDEBAR_FOOTER
    Position : Épinglé tout en bas de la sidebar
    Utilisation : Version app, infos admin connecté, copyright...
--}}
<div class="px-4 py-3 border-t border-gray-700/30">
    <div class="flex items-center justify-between">
        <span class="text-xs text-gray-500">
            Ink&Pik Admin
        </span>
        <span class="text-xs text-gray-600">
            v1.0.0
        </span>
    </div>
    @php $user = auth()->user(); @endphp
    @if ($user)
    <p class="text-xs text-gray-600 truncate mt-0.5">
        {{ $user->name ?? $user->email }}
    </p>
    @endif
</div>
```

### 3.8 — `content-before.blade.php`
Avant le contenu de chaque page :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::CONTENT_BEFORE
    Position : Avant le contenu principal de chaque page
    Utilisation : Bandeau d'info global, breadcrumb custom, fil d'Ariane...

    Note : S'affiche sur TOUTES les pages admin.
    Pour cibler une page spécifique, utiliser le scope dans le ServiceProvider :
    FilamentView::registerRenderHook(
        PanelsRenderHook::PAGE_START,
        fn() => view('filament.hooks.dashboard-header'),
        scopes: \App\Filament\Admin\Pages\Dashboard::class,
    );
--}}
```

### 3.9 — `content-after.blade.php`
Après le contenu de chaque page :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::CONTENT_AFTER
    Position : Après le contenu principal de chaque page
    Utilisation : Boutons d'action globaux, aide contextuelle...
--}}
```

### 3.10 — `head-end.blade.php`
Dans le `<head>` HTML — pour CSS custom :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::HEAD_END
    Position : Avant </head>
    Utilisation : CSS custom, polices, meta tags supplémentaires...
--}}
<style>
    /*
     * CSS custom pour le panel Admin Ink&Pik
     * ----------------------------------------
     * Ajouter ici les customisations CSS globales.
     * Décommenter les blocs pour les activer.
     */

    /* Exemple : arrondi plus prononcé sur les cards */
    /*
    .fi-wi-stats-overview-stat {
        border-radius: 1rem !important;
    }
    */

    /* Exemple : sidebar plus étroite */
    /*
    .fi-sidebar {
        width: 240px !important;
    }
    */

    /* Exemple : font custom */
    /*
    .fi-body {
        font-family: 'Inter', sans-serif;
    }
    */
</style>
```

### 3.11 — `body-end.blade.php`
Avant `</body>` — pour JS custom :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::BODY_END
    Position : Avant </body>
    Utilisation : Scripts JS custom, tracking, widgets tiers...
--}}
<script>
    /*
     * JS custom pour le panel Admin Ink&Pik
     * ----------------------------------------
     * Ajouter ici les scripts globaux.
     */

    // Exemple : raccourcis clavier globaux
    // document.addEventListener('keydown', function(e) {
    //     if (e.ctrlKey && e.key === 'k') {
    //         // Ouvrir la recherche globale
    //     }
    // });
</script>
```

### 3.12 — `footer.blade.php`
Footer de chaque page :

```blade
{{--
    RENDER HOOK : PanelsRenderHook::FOOTER
    Position : Footer de chaque page admin
    Utilisation : Copyright, version, liens légaux...
--}}
<div class="text-center py-3 text-xs text-gray-400 dark:text-gray-600">
    © {{ date('Y') }} Ink&Pik — Administration
</div>
```

---

## PHASE 4 — HOOKS SCOPÉS PAR PAGE (optionnels, à activer selon besoin)

Ajouter dans `FilamentRenderHooksServiceProvider::boot()` des hooks
ciblant des pages spécifiques :

```php
// ─── DASHBOARD UNIQUEMENT ──────────────────────────────────────
// Avant les widgets du header du Dashboard
FilamentView::registerRenderHook(
    PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
    fn (): View => view('filament.hooks.dashboard-header'),
    scopes: \App\Filament\Admin\Pages\Dashboard::class,
);

// Après les widgets du header du Dashboard
FilamentView::registerRenderHook(
    PanelsRenderHook::PAGE_HEADER_WIDGETS_AFTER,
    fn (): View => view('filament.hooks.dashboard-after-header-widgets'),
    scopes: \App\Filament\Admin\Pages\Dashboard::class,
);

// ─── AVANT/APRÈS LES TABLES DES RESOURCES ─────────────────────
// Avant la table des Tatoueurs
FilamentView::registerRenderHook(
    PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE,
    fn (): View => view('filament.hooks.tattooers-table-before'),
    scopes: \App\Filament\Admin\Resources\Tattooers\Pages\ListTattooers::class,
);
```

Créer les vues scopées correspondantes (vides par défaut, à remplir selon besoin) :

```bash
touch resources/views/filament/hooks/dashboard-header.blade.php
touch resources/views/filament/hooks/dashboard-after-header-widgets.blade.php
touch resources/views/filament/hooks/tattooers-table-before.blade.php
```

```blade
{{-- dashboard-header.blade.php --}}
{{--
    Hook scopé au Dashboard uniquement.
    Décommenter pour afficher un message de bienvenue custom :

    <div class="mb-4 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-xl
                border border-primary-200 dark:border-primary-800">
        <p class="text-sm text-primary-700 dark:text-primary-300">
            👋 Bonjour {{ auth()->user()->name ?? 'Administrateur' }} —
            {{ now()->format('l d F Y') }}
        </p>
    </div>
--}}
```

---

## PHASE 5 — VÉRIFICATION

```bash
# Vérifier que le ServiceProvider est enregistré
php artisan provider:list | grep RenderHooks

# Vider les caches
php artisan view:clear
php artisan config:clear

# Vérifier qu'il n'y a pas d'erreurs
php artisan route:list | head -5
```

Tester dans le navigateur :
1. `/admin` → le footer "© 2026 Ink&Pik — Administration" doit apparaître
2. Le badge "DEV" orange doit apparaître dans la topbar en environnement local
3. "Panel Administration" doit apparaître sous le logo dans la sidebar
4. La version "v1.0.0" doit apparaître dans le footer sidebar

---

## PHASE 6 — GUIDE D'UTILISATION (générer dans RENDER_HOOKS_GUIDE.md)

Créer `RENDER_HOOKS_GUIDE.md` à la racine :

```markdown
# 🎨 Guide Render Hooks — Panel Admin Ink&Pik

## Infrastructure
- **ServiceProvider** : `app/Providers/FilamentRenderHooksServiceProvider.php`
- **Vues hooks** : `resources/views/filament/hooks/`
- **Enregistrement** : `bootstrap/providers.php`

## Hooks disponibles et leurs fichiers

| Hook | Fichier | Description |
|------|---------|-------------|
| TOPBAR_BEFORE | `topbar-before.blade.php` | Bannière au-dessus de la nav |
| TOPBAR_START | `topbar-start.blade.php` | Début de la topbar |
| SIDEBAR_LOGO_BEFORE | `sidebar-logo-before.blade.php` | Avant le logo |
| SIDEBAR_LOGO_AFTER | `sidebar-logo-after.blade.php` | Après le logo |
| SIDEBAR_NAV_START | `sidebar-nav-start.blade.php` | Début de la nav sidebar |
| SIDEBAR_NAV_END | `sidebar-nav-end.blade.php` | Fin de la nav sidebar |
| SIDEBAR_FOOTER | `sidebar-footer.blade.php` | Footer sidebar épinglé |
| CONTENT_BEFORE | `content-before.blade.php` | Avant le contenu (toutes pages) |
| CONTENT_AFTER | `content-after.blade.php` | Après le contenu (toutes pages) |
| HEAD_END | `head-end.blade.php` | CSS custom dans <head> |
| BODY_END | `body-end.blade.php` | JS custom avant </body> |
| FOOTER | `footer.blade.php` | Footer de chaque page |

## Hooks scopés (par page)
| Hook | Fichier | Scope |
|------|---------|-------|
| PAGE_HEADER_WIDGETS_BEFORE | `dashboard-header.blade.php` | Dashboard |
| PAGE_HEADER_WIDGETS_AFTER | `dashboard-after-header-widgets.blade.php` | Dashboard |

## Ajouter un nouveau hook

1. Dans `FilamentRenderHooksServiceProvider::boot()` :
```php
FilamentView::registerRenderHook(
    PanelsRenderHook::HOOK_NAME,
    fn (): View => view('filament.hooks.mon-hook'),
    // scopes: MonPageClass::class, // optionnel — cibler une page
);
```

2. Créer la vue `resources/views/filament/hooks/mon-hook.blade.php`

3. `php artisan view:clear`

## Hooks scopés disponibles
Hooks qui acceptent un `scopes:` parameter :
- PAGE_START / PAGE_END
- PAGE_HEADER_WIDGETS_BEFORE / AFTER / START / END
- PAGE_FOOTER_WIDGETS_BEFORE / AFTER
- PAGE_HEADER_ACTIONS_BEFORE / AFTER
- RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE / AFTER
- LAYOUT_START / LAYOUT_END
```

---

## ⚠️ Contraintes
- Les vues hooks doivent être légères — pas de requêtes DB dans les hooks globaux
  (TOPBAR, SIDEBAR, CONTENT) car ils s'exécutent sur chaque page
- Pour les requêtes DB, utiliser les hooks scopés (page spécifique) ou
  envelopper dans `@once` pour éviter les doublons
- Ne jamais utiliser `@livewire()` dans les hooks sauf si nécessaire
  (risque de conflits Livewire)
- Rapport final :
  1. Liste des fichiers créés
  2. ServiceProvider enregistré ✅
  3. Test visuel : footer visible sur /admin ✅
  4. Fichier `RENDER_HOOKS_GUIDE.md` créé à la racine ✅
