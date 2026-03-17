<?php

namespace App\Filament\Admin\Resources\StudioArtists;

use App\Filament\Admin\Resources\StudioArtists\Pages\CreateStudioArtist;
use App\Filament\Admin\Resources\StudioArtists\Pages\EditStudioArtist;
use App\Filament\Admin\Resources\StudioArtists\Pages\ListStudioArtists;
use App\Filament\Admin\Resources\StudioArtists\Pages\ViewStudioArtist;
use App\Filament\Admin\Resources\StudioArtists\Schemas\StudioArtistForm;
use App\Filament\Admin\Resources\StudioArtists\Schemas\StudioArtistInfolist;
use App\Filament\Admin\Resources\StudioArtists\Tables\StudioArtistsTable;
use App\Models\StudioArtist;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudioArtistResource extends Resource
{
    protected static ?string $model = StudioArtist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Artistes Studio';

    protected static UnitEnum|string|null $navigationGroup = 'Modération';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return StudioArtistForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudioArtistInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudioArtistsTable::configure($table);
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
            'index' => ListStudioArtists::route('/'),
            'create' => CreateStudioArtist::route('/create'),
            'view' => ViewStudioArtist::route('/{record}'),
            'edit' => EditStudioArtist::route('/{record}/edit'),
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
