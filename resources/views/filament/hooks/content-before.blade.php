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
