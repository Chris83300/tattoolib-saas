<?php

namespace App\Filament\Admin\Resources\Tattooers\Pages;

use App\Filament\Admin\Resources\Tattooers\TattooerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTattooers extends ListRecords
{
    protected static string $resource = TattooerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
