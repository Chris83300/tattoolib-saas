<?php

namespace App\Filament\Studio\Resources\BookingRequestResource\Pages;

use App\Filament\Studio\Resources\BookingRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListBookingRequests extends ListRecords
{
    protected static string $resource = BookingRequestResource::class;
}
