<?php

namespace App\Filament\Studio\Resources;

use App\Filament\Studio\Resources\BookingRequestResource\Pages;
use App\Filament\Studio\Resources\BookingRequestResource\Schemas\BookingRequestForm;
use App\Filament\Studio\Resources\BookingRequestResource\Tables\BookingRequestTable;
use App\Models\BookingRequest;
use App\Models\Piercer;
use App\Models\Tattooer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookingRequestResource extends Resource
{
    protected static ?string $model = BookingRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Demandes';

    protected static ?string $modelLabel = 'Demande';

    protected static ?string $pluralModelLabel = 'Demandes';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return BookingRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingRequestTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingRequests::route('/'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        $artistUserIds = $studio->studioArtists()->where('is_active', true)->pluck('user_id')->filter();
        $tattooerIds   = Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds    = Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        return parent::getEloquentQuery()->where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        });
    }
}
