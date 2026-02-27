<?php

namespace App\Filament\Studio\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public function getWidgets(): array
    {
        return [
            \App\Filament\Studio\Widgets\StudioStatsOverview::class,
        ];
    }
}
