<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;

class RevenueByArtistType extends ChartWidget
{
    protected ?string $heading = '💰 Revenus par Type d\'Artiste';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
