<?php

namespace App\Filament\Admin\Resources\Subscriptions;

use App\Filament\Admin\Resources\Subscriptions\Pages\ManageSubscriptions;
use App\Models\Subscription;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'plan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subscribable_type')
                    ->required(),
                TextInput::make('subscribable_id')
                    ->required()
                    ->numeric(),
                Select::make('plan')
                    ->options(['free' => 'Free', 'pro' => 'Pro', 'studio' => 'Studio'])
                    ->default('free')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'past_due' => 'Past due', 'canceled' => 'Canceled', 'unpaid' => 'Unpaid'])
                    ->default('active')
                    ->required(),
                TextInput::make('stripe_subscription_id'),
                TextInput::make('stripe_price_id'),
                DateTimePicker::make('current_period_start'),
                DateTimePicker::make('current_period_end'),
                DateTimePicker::make('canceled_at'),
                DateTimePicker::make('ends_at'),
                TextInput::make('price_monthly')
                    ->numeric(),
                TextInput::make('commission_rate')
                    ->required()
                    ->numeric()
                    ->default(7.0),
                TextInput::make('features'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('plan')
            ->columns([
                TextColumn::make('subscribable_type')
                    ->searchable(),
                TextColumn::make('subscribable_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('plan')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('stripe_subscription_id')
                    ->searchable(),
                TextColumn::make('stripe_price_id')
                    ->searchable(),
                TextColumn::make('current_period_start')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('current_period_end')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('canceled_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('price_monthly')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('commission_rate')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSubscriptions::route('/'),
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
