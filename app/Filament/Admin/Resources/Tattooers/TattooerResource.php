<?php

namespace App\Filament\Admin\Resources\Tattooers;

use App\Filament\Admin\Resources\Tattooers\Pages\CreateTattooer;
use App\Filament\Admin\Resources\Tattooers\Pages\EditTattooer;
use App\Filament\Admin\Resources\Tattooers\Pages\ListTattooers;
use App\Filament\Admin\Resources\Tattooers\Schemas\TattooerForm;
use App\Filament\Admin\Resources\Tattooers\Tables\TattooersTable;
use App\Models\Tattooer;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TattooerResource extends Resource
{
    protected static ?string $model = Tattooer::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static string|UnitEnum|null $navigationGroup = 'Moderation';

    protected static ?string $modelLabel = 'Tatoueur';

    protected static ?string $pluralModelLabel = 'Tatoueurs';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return TattooerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TattooersTable::configure($table);
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
            'index' => ListTattooers::route('/'),
            'create' => CreateTattooer::route('/create'),
            'edit' => EditTattooer::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Tattooer::whereHas('user', function ($query) {
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
