<?php

namespace App\Filament\Admin\Resources\BookingRequests\Pages;

use App\Filament\Admin\Resources\BookingRequests\BookingRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookingRequests extends ListRecords
{
    protected static string $resource = BookingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
