<?php

namespace App\Filament\Studio\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.studio.pages.dashboard';
    
    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            \App\Filament\Studio\Widgets\StudioStatsWidget::class,
            \App\Filament\Studio\Widgets\ArtistsOverviewWidget::class,
        ];
    }
}
