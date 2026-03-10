<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#D4B59E'),
                'danger' => Color::hex('#E63946'),
                'success' => Color::hex('#06D6A0'),
                'warning' => Color::hex('#F77F00'),
            ])
            ->darkMode(true)
            ->brandName('Ink&Pik Admin')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                \App\Filament\Admin\Widgets\StatsOverview::class,
                \App\Filament\Admin\Widgets\RevenueChart::class,
                \App\Filament\Admin\Widgets\RevenueOverviewWidget::class,
                \App\Filament\Admin\Widgets\ComplaintsWidget::class,
                \App\Filament\Admin\Widgets\RecentActivity::class,
                // Nouveaux widgets graphiques
                \App\Filament\Admin\Widgets\RevenueStatsWidget::class,
                \App\Filament\Admin\Widgets\CommissionWidget::class,
                \App\Filament\Admin\Widgets\MonthlyRevenueChartWidget::class,
                \App\Filament\Admin\Widgets\ArtistRevenueChartWidget::class,
                \App\Filament\Admin\Widgets\RecentActivityChartWidget::class,
            ])
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
                \App\Http\Middleware\EnsureUserIsAdmin::class,
            ])
            ->databaseNotifications()
            ->navigationGroups([
                'Vue d\'ensemble',
                'Moderation',
                'Utilisateurs',
                'Activite',
                'Qualite',
                'Parametres',
            ]);
    }
}
