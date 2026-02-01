<?php

namespace App\Filament\Admin\Resources\Studios\Pages;

use App\Filament\Admin\Resources\Studios\StudioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudios extends ListRecords
{
    protected static string $resource = StudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
