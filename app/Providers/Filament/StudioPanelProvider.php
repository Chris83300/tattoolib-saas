<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StudioPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('studio')
            ->path('studio/admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#c4956a'), // beige-peau
                'danger'  => Color::hex('#E63946'),
                'success' => Color::hex('#06D6A0'),
                'warning' => Color::hex('#F77F00'),
            ])
            ->darkMode(true)
            ->brandName('Ink&Pik Studio')
            ->favicon(asset('favicon.ico'))
            ->discoverResources(in: app_path('Filament/Studio/Resources'), for: 'App\\Filament\\Studio\\Resources')
            ->discoverPages(in: app_path('Filament/Studio/Pages'), for: 'App\\Filament\\Studio\\Pages')
            ->pages([
                \App\Filament\Studio\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Studio/Widgets'), for: 'App\\Filament\\Studio\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\EnsureUserIsStudio::class,
            ]);
    }
}
