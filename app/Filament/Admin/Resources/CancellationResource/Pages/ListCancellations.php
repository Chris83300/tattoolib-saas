<?php
namespace App\Filament\Admin\Resources\CancellationResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\CancellationResource;

class ListCancellations extends ListRecords
{
    protected static string $resource = CancellationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
