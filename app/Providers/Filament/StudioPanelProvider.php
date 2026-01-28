<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class StudioPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('studio')
            ->path('admin/studio')
            ->login()
            ->colors([
                'primary' => Color::hex('#D4B59E'), // Beige peau
                'danger' => Color::hex('#991B1B'), // Rouge alerte
                'success' => Color::hex('#10B981'), // Vert succès
            ])
            ->darkMode(true)
            ->brandName('Ink&Pik Studio')
            ->brandLogo(asset('images/logo.svg'))
            ->favicon(asset('favicon.ico'))
            ->discoverResources(in: app_path('Filament/Studio/Resources'), for: 'App\\Filament\\Studio\\Resources')
            ->discoverPages(in: app_path('Filament/Studio/Pages'), for: 'App\\Filament\\Studio\\Pages')
            ->pages([
                \App\Filament\Studio\Pages\Dashboard::class,
            ])
            ->authMiddleware([
                \App\Http\Middleware\EnsureUserIsStudio::class,
            ]);
    }
}
