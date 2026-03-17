<?php

namespace App\Filament\Admin\Resources\Payments;

use App\Filament\Admin\Resources\Payments\Pages\CreatePayment;
use App\Filament\Admin\Resources\Payments\Pages\EditPayment;
use App\Filament\Admin\Resources\Payments\Pages\ListPayments;
use App\Filament\Admin\Resources\Payments\Pages\ViewPayment;
use App\Filament\Admin\Resources\Payments\Schemas\PaymentForm;
use App\Filament\Admin\Resources\Payments\Schemas\PaymentInfolist;
use App\Filament\Admin\Resources\Payments\Tables\PaymentsTable;
use App\Models\BookingTransaction;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Paiements';
    protected static ?string $modelLabel = 'Paiement';
    protected static ?string $pluralModelLabel = 'Paiements';
    protected static UnitEnum|string|null $navigationGroup = 'Finances';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
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
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'view' => ViewPayment::route('/{record}'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
