<?php

namespace App\Filament\Admin\Resources\Pierceurs;

use App\Filament\Admin\Resources\Pierceurs\Pages\CreatePierceur;
use App\Filament\Admin\Resources\Pierceurs\Pages\EditPierceur;
use App\Filament\Admin\Resources\Pierceurs\Pages\ListPierceurs;
use App\Filament\Admin\Resources\Pierceurs\Schemas\PierceurForm;
use App\Filament\Admin\Resources\Pierceurs\Tables\PierceursTable;
use App\Models\Pierceur;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PierceurResource extends Resource
{
    protected static ?string $model = Pierceur::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static string|UnitEnum|null $navigationGroup = 'Moderation';

    protected static ?string $modelLabel = 'Pierceur';

    protected static ?string $pluralModelLabel = 'Pierceurs';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PierceurForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PierceursTable::configure($table);
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
            'index' => ListPierceurs::route('/'),
            'create' => CreatePierceur::route('/create'),
            'edit' => EditPierceur::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Pierceur::whereHas('user', function ($query) {
            $query->where('status', 'pending_verification');
        })->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery();
    }
}
