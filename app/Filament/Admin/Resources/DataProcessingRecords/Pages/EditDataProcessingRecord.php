<?php

namespace App\Filament\Admin\Resources\DataProcessingRecords\Pages;

use App\Filament\Admin\Resources\DataProcessingRecords\DataProcessingRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataProcessingRecord extends EditRecord
{
    protected static string $resource = DataProcessingRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
