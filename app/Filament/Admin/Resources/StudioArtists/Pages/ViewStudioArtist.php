<?php

namespace App\Filament\Admin\Resources\StudioArtists\Pages;

use App\Filament\Admin\Resources\StudioArtists\StudioArtistResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudioArtist extends ViewRecord
{
    protected static string $resource = StudioArtistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
