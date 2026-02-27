<?php

namespace App\Filament\Studio\Resources;

use App\Filament\Studio\Resources\StudioArtistResource\Pages;
use App\Filament\Studio\Resources\StudioArtistResource\Schemas\StudioArtistForm;
use App\Filament\Studio\Resources\StudioArtistResource\Tables\StudioArtistTable;
use App\Models\StudioArtist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudioArtistResource extends Resource
{
    protected static ?string $model = StudioArtist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Artistes';

    protected static ?string $modelLabel = 'Artiste';

    protected static ?string $pluralModelLabel = 'Artistes';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return StudioArtistForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudioArtistTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudioArtists::route('/'),
            'edit'  => Pages\EditStudioArtist::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
        return parent::getEloquentQuery()->where('studio_id', $studio->id);
    }
}
