<?php

namespace App\Filament\Studio\Widgets;

use App\Services\StudioStatsService;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;

class ArtistPerformanceTable extends Widget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';
    protected string $view = 'filament.studio.widgets.artist-performance-table';

    public function getArtistStats(): array
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return [];
        }

        return Cache::remember("studio.{$studio->id}.artist.stats", 300, function () use ($studio) {
            return (new StudioStatsService($studio))->getArtistStats()->toArray();
        });
    }
}
