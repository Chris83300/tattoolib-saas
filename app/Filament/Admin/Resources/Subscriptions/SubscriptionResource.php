<?php

namespace App\Filament\Admin\Resources\Subscriptions;

use App\Filament\Admin\Resources\Subscriptions\Pages\ManageSubscriptions;
use App\Filament\Admin\Resources\Subscriptions\Schemas\SubscriptionForm;
use App\Filament\Admin\Resources\Subscriptions\Tables\SubscriptionsTable;
use App\Models\Subscription;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $recordTitleAttribute = 'plan';

    protected static ?string $navigationLabel = 'Abonnements Artistes';

    protected static ?string $modelLabel = 'Abonnement Artiste';

    protected static ?string $pluralModelLabel = 'Abonnements Artistes';

    protected static UnitEnum|string|null $navigationGroup = 'Finances';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('user', function ($query) {
                $query->whereIn('role', ['tattooer', 'piercer', 'studio']);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return SubscriptionForm::schema($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscriptionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSubscriptions::route('/'),
        ];
    }
}
