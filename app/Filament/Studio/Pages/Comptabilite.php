<?php

namespace App\Filament\Studio\Pages;

use App\Services\AccountingExportService;
use App\Services\StudioStatsService;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class Comptabilite extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationLabel = 'Comptabilité';
    protected static ?string $title           = 'Comptabilité';
    protected static ?int    $navigationSort  = 3;
    protected string $view = 'filament.studio.pages.comptabilite';

    public ?int $year = null;

    public function mount(): void
    {
        $this->year = (int) request('year', now()->year);
    }

    public function getStats(): array
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return [];
        }

        return Cache::remember("studio.{$studio->id}.compta.stats.{$this->year}", 300, function () use ($studio) {
            return (new StudioStatsService($studio))->getDashboardStats();
        });
    }

    public function getTransactions(): \Illuminate\Support\Collection
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return collect();
        }

        return app(AccountingExportService::class)
            ->getStudioTransactions($studio)
            ->take(50);
    }

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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label('Exporter Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->url(route('studio.transactions', ['format' => 'xlsx']))
                ->openUrlInNewTab(),

            Action::make('export_csv')
                ->label('Exporter CSV')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(route('studio.transactions', ['format' => 'csv']))
                ->openUrlInNewTab(),
        ];
    }
}
