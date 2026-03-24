<?php

namespace App\Filament\Admin\Widgets;

use App\Services\PlatformRevenueService;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;

class RevenueStatsWidget extends Widget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.revenue-detail';

    public function getData(): array
    {
        return Cache::remember('admin.platform.revenue.detail', 300, function () {
            return app(PlatformRevenueService::class)->getPlatformRevenue();
        });
    }
}
