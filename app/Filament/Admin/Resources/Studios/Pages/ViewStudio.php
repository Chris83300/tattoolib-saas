<?php

namespace App\Filament\Admin\Resources\Studios\Pages;

use App\Filament\Admin\Resources\Studios\StudioResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudio extends ViewRecord
{
    protected static string $resource = StudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
