<?php

namespace App\Filament\Studio\Widgets;

use App\Services\StudioStatsService;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UpcomingAppointments extends Widget
{
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 1;
    protected string $view = 'filament.studio.widgets.upcoming-appointments';

    public function getAppointments(): Collection
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return collect();
        }

        return Cache::remember("studio.{$studio->id}.upcoming", 120, function () use ($studio) {
            return (new StudioStatsService($studio))->getUpcomingAppointments(8);
        });
    }
}
