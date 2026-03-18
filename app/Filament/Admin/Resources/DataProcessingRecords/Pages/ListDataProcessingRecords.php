<?php

namespace App\Filament\Admin\Resources\DataProcessingRecords\Pages;

use App\Filament\Admin\Resources\DataProcessingRecords\DataProcessingRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataProcessingRecords extends ListRecords
{
    protected static string $resource = DataProcessingRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
