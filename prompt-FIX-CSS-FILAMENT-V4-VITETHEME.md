# 🎨 FIX — CSS Custom Filament v4 (viteTheme + Tailwind + CSS hooks)

## Problème
1. Les styles dans `head-end.blade.php` ne s'appliquent pas car Filament
   charge ses styles APRÈS le hook HEAD_END — les `!important` sont écrasés
2. Les classes Tailwind custom ne sont pas compilées car les vues Filament
   ne sont pas dans le `content` de tailwind.config.js

## Solution correcte pour Filament v4
Utiliser `->viteTheme()` dans AdminPanelProvider + un fichier CSS dédié
compilé par Vite — c'est la méthode officielle Filament v4.

---

## PHASE 1 — AUDIT

```bash
# Vérifier la config Vite
cat vite.config.js

# Vérifier la config Tailwind
cat tailwind.config.js 2>/dev/null || cat tailwind.config.ts 2>/dev/null

# Vérifier les CSS/JS existants
ls resources/css/
ls resources/js/

# Vérifier AdminPanelProvider
grep -n "viteTheme\|theme\|colors\|font" \
  app/Providers/Filament/AdminPanelProvider.php

# Vérifier package.json
cat package.json | grep -A5 '"scripts"'
```

---

## PHASE 2 — CRÉER LE FICHIER CSS ADMIN DÉDIÉ

### 2.1 — Créer `resources/css/filament/admin/theme.css`

```bash
mkdir -p resources/css/filament/admin
```

```css
/* resources/css/filament/admin/theme.css
 * ─────────────────────────────────────────
 * CSS custom compilé par Vite pour le panel Admin Ink&Pik
 * Ce fichier est chargé APRÈS les styles Filament → spécificité correcte
 *
 * ⚠️ Après modification, relancer : npm run dev (ou npm run build en prod)
 */

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

@tailwind base;
@tailwind components;
@tailwind utilities;

/* ══════════════════════════════════════════
   TYPOGRAPHIE
══════════════════════════════════════════ */
.fi-body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* ══════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════ */
.fi-sidebar {
    width: 260px;
}

/* Groupes de navigation */
.fi-sidebar-group-btn {
    border: 1px solid rgba(233, 198, 160, 0.25);
    border-radius: 0.625rem;
    margin-bottom: 0.125rem;
}

/* Items de navigation */
.fi-sidebar-item-button {
    border-radius: 0.625rem;
    transition: all 0.15s ease;
}

/* ══════════════════════════════════════════
   WIDGETS STATS
══════════════════════════════════════════ */
.fi-wi-stats-overview-stat {
    border-radius: 1rem;
    border: 1px solid rgba(233, 198, 160, 0.25);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.fi-wi-stats-overview-stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* ══════════════════════════════════════════
   TABLES
══════════════════════════════════════════ */
.fi-ta-table {
    border-radius: 0.75rem;
    overflow: hidden;
}

/* Header des colonnes */
.fi-ta-header-cell {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ══════════════════════════════════════════
   CARDS / SECTIONS
══════════════════════════════════════════ */
.fi-section {
    border-radius: 1rem;
}

/* ══════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════ */
.fi-topbar {
    border-bottom: 1px solid rgba(233, 198, 160, 0.15);
    backdrop-filter: blur(8px);
}

/* ══════════════════════════════════════════
   SUPPORT CHAT (page custom)
══════════════════════════════════════════ */
.fi-header-chat {
    border: 1px solid rgba(233, 198, 160, 0.25);
    border-radius: 1rem;
}

/* ══════════════════════════════════════════
   BADGES
══════════════════════════════════════════ */
.fi-badge {
    border-radius: 0.375rem;
}

/* ══════════════════════════════════════════
   CLASSES TAILWIND CUSTOM INK&PIK
   (utilisables dans les vues Filament)
══════════════════════════════════════════ */
@layer components {
    /* Couleurs brand */
    .inkpik-gold {
        color: #e9c6a0;
    }

    .inkpik-gold-bg {
        background-color: rgba(233, 198, 160, 0.1);
    }

    .inkpik-border {
        border: 1px solid rgba(233, 198, 160, 0.25);
    }

    /* Card custom */
    .inkpik-card {
        @apply rounded-2xl border border-gray-200 dark:border-gray-700
               bg-white dark:bg-gray-900 shadow-sm;
        border-color: rgba(233, 198, 160, 0.2);
    }

    /* Bouton brand */
    .inkpik-btn {
        @apply px-4 py-2 rounded-xl font-medium text-sm transition;
        background-color: rgba(233, 198, 160, 0.15);
        color: #e9c6a0;
        border: 1px solid rgba(233, 198, 160, 0.3);
    }

    .inkpik-btn:hover {
        background-color: rgba(233, 198, 160, 0.25);
    }

    /* Badge status */
    .inkpik-badge-pending {
        @apply inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium
               bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400;
    }

    .inkpik-badge-success {
        @apply inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium
               bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400;
    }

    .inkpik-badge-danger {
        @apply inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium
               bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400;
    }
}
```

---

## PHASE 3 — CONFIGURER VITE

### 3.1 — Modifier `vite.config.js`

Lire le fichier actuel puis ajouter le CSS admin :

```js
import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css', // ← AJOUTER
            ],
            refresh: [
                ...refreshPaths,
                'app/Filament/**',
                'app/Providers/Filament/**',
                'resources/views/filament/**',    // ← AJOUTER
                'resources/css/filament/**',       // ← AJOUTER
            ],
        }),
    ],
})
```

### 3.2 — Configurer Tailwind pour scanner les vues Filament

Lire `tailwind.config.js` (ou `.ts`) et ajouter les chemins manquants :

```js
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/Filament/**/*.php',          // ← AJOUTER
        './resources/views/filament/**/*.blade.php', // ← AJOUTER
    ],
    // ... reste de la config
}
```

---

## PHASE 4 — CONNECTER LE THÈME AU PANEL

### 4.1 — Dans `app/Providers/Filament/AdminPanelProvider.php`

```bash
grep -n "viteTheme\|theme\|->configure" \
  app/Providers/Filament/AdminPanelProvider.php
```

Ajouter `->viteTheme()` dans la configuration du panel :

```php
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Theme;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... config existante ...

        // ✅ Thème CSS compilé par Vite
        ->viteTheme('resources/css/filament/admin/theme.css')

        // Si tu veux aussi définir les couleurs primary Filament
        // pour qu'elles correspondent à ta charte :
        // ->colors([
        //     'primary' => Color::hex('#e9c6a0'),
        // ])
        ;
}
```

### 4.2 — Vider le hook HEAD_END (il n'est plus nécessaire)

Dans `resources/views/filament/hooks/head-end.blade.php`, commenter tout le `<style>` :

```blade
{{--
    CSS custom maintenant géré via viteTheme() dans AdminPanelProvider
    → resources/css/filament/admin/theme.css
    Ne plus mettre de <style> ici — les styles seraient chargés avant
    ceux de Filament et seraient écrasés.

    Ce hook HEAD_END reste disponible pour :
    - Meta tags supplémentaires
    - Polices Google (via <link>, pas via @import CSS)
    - Variables CSS globales si besoin
--}}

{{-- Police Inter via link (plus performant que @import CSS) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet">
```

---

## PHASE 5 — COMPILER ET TESTER

```bash
# Compiler les assets
npm run build
# OU en développement :
npm run dev

# Vider le cache des vues
php artisan view:clear
php artisan filament:clear-cached-components 2>/dev/null || true
```

### Tests visuels à vérifier :
1. `/admin` → la font Inter est appliquée sur tout le panel
2. Les widgets stats ont `border-radius: 1rem` et une bordure gold subtile
3. La sidebar fait 260px
4. Inspecter l'élément dans DevTools → le CSS vient d'un fichier compilé
   (pas d'un `<style>` inline)

### Pour utiliser les classes custom dans les vues :
```blade
{{-- Dans une vue filament/hooks/*.blade.php --}}
<div class="inkpik-card p-4">
    <span class="inkpik-gold">Contenu avec style Ink&Pik</span>
</div>

<span class="inkpik-badge-success">✅ Traité</span>
<span class="inkpik-badge-pending">⏳ En attente</span>
```

---

## ⚠️ Contraintes
- Ne jamais mettre de CSS dans HEAD_END pour styler les classes `fi-*`
  → toujours utiliser le fichier `theme.css` compilé par Vite
- Si `->viteTheme()` génère une erreur, vérifier que le manifest Vite
  existe : `public/build/manifest.json`
- En dev : `npm run dev` doit tourner pour que les changements CSS soient visibles
- En prod : `npm run build` puis `php artisan optimize`
- Rapport final :
  1. `theme.css` créé et compilé sans erreur
  2. `->viteTheme()` ajouté dans AdminPanelProvider
  3. Test visuel : font Inter + border-radius widgets ✅
  4. Une classe `inkpik-card` utilisable dans les vues Filament ✅
