<?php

namespace App\Filament\Admin\Resources\BookingRequests\Pages;

use App\Filament\Admin\Resources\BookingRequests\BookingRequestResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookingRequests extends ListRecords
{
    protected static string $resource = BookingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_xlsx')
                ->label('Exporter Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(fn () => route('export.admin.bookings', ['format' => 'xlsx']))
                ->openUrlInNewTab(),

            Action::make('export_csv')
                ->label('Exporter CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(fn () => route('export.admin.bookings', ['format' => 'csv']))
                ->openUrlInNewTab(),

            CreateAction::make(),
        ];
    }
}
