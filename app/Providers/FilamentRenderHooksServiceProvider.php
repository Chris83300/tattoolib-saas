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
        // Dans le <head> (meta tags, polices...)
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): View => view('filament.hooks.head-end'),
        );

        // CSS chargé APRÈS les styles Filament → spécificité correcte
        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): View => view('filament.hooks.styles-after'),
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
