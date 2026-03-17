# Guide Render Hooks — Panel Admin Ink&Pik

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
| HEAD_END | `head-end.blade.php` | CSS custom dans `<head>` |
| BODY_END | `body-end.blade.php` | JS custom avant `</body>` |
| FOOTER | `footer.blade.php` | Footer de chaque page |

## Hooks scopés (par page)

| Hook | Fichier | Scope |
|------|---------|-------|
| PAGE_HEADER_WIDGETS_BEFORE | `dashboard-header.blade.php` | Dashboard |
| PAGE_HEADER_WIDGETS_AFTER | `dashboard-after-header-widgets.blade.php` | Dashboard |
| RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE | `tattooers-table-before.blade.php` | ListTattooers |

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
- `PAGE_START` / `PAGE_END`
- `PAGE_HEADER_WIDGETS_BEFORE` / `AFTER` / `START` / `END`
- `PAGE_FOOTER_WIDGETS_BEFORE` / `AFTER`
- `PAGE_HEADER_ACTIONS_BEFORE` / `AFTER`
- `RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE` / `AFTER`
- `LAYOUT_START` / `LAYOUT_END`

## Contraintes

- Les vues hooks globaux (TOPBAR, SIDEBAR, CONTENT) s'exécutent sur **chaque page** — pas de requêtes DB lourdes
- Pour les requêtes DB, utiliser les hooks scopés ou envelopper dans `@once`
- Ne pas utiliser `@livewire()` dans les hooks sauf si nécessaire (risque de conflits Livewire)
