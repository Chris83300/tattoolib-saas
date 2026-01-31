<?php

namespace App\Filament\Admin\Resources\ComplianceRecords\Pages;

use App\Filament\Admin\Resources\ComplianceRecords\ComplianceRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComplianceRecords extends ListRecords
{
    protected static string $resource = ComplianceRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
