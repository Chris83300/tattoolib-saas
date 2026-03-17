<?php

namespace App\Filament\Admin\Resources\BookingRequests;

use App\Filament\Admin\Resources\BookingRequests\Pages\CreateBookingRequest;
use App\Filament\Admin\Resources\BookingRequests\Pages\EditBookingRequest;
use App\Filament\Admin\Resources\BookingRequests\Pages\ListBookingRequests;
use App\Filament\Admin\Resources\BookingRequests\Schemas\BookingRequestForm;
use App\Filament\Admin\Resources\BookingRequests\Tables\BookingRequestsTable;
use App\Models\BookingRequest;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingRequestResource extends Resource
{
    protected static ?string $model = BookingRequest::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Demandes';
    protected static ?string $modelLabel = 'Demande';
    protected static ?string $pluralModelLabel = 'Demandes';
    protected static UnitEnum|string|null $navigationGroup = 'Réservations';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return BookingRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingRequests::route('/'),
            'create' => CreateBookingRequest::route('/create'),
            'edit' => EditBookingRequest::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
