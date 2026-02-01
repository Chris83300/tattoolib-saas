<?php

namespace App\Filament\Admin\Resources\StudioArtists\Pages;

use App\Filament\Admin\Resources\StudioArtists\StudioArtistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudioArtists extends ListRecords
{
    protected static string $resource = StudioArtistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
