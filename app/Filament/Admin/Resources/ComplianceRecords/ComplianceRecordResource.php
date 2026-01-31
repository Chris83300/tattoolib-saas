<?php

namespace App\Filament\Admin\Resources\ComplianceRecords;

use App\Filament\Admin\Resources\ComplianceRecords\Pages\CreateComplianceRecord;
use App\Filament\Admin\Resources\ComplianceRecords\Pages\EditComplianceRecord;
use App\Filament\Admin\Resources\ComplianceRecords\Pages\ListComplianceRecords;
use App\Filament\Admin\Resources\ComplianceRecords\Schemas\ComplianceRecordForm;
use App\Filament\Admin\Resources\ComplianceRecords\Tables\ComplianceRecordsTable;
use App\Models\ComplianceRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ComplianceRecordResource extends Resource
{
    protected static ?string $model = ComplianceRecord::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    protected static string|UnitEnum|null $navigationGroup = 'Qualite';

    protected static ?string $modelLabel = 'Document de conformité';

    protected static ?string $pluralModelLabel = 'Documents de conformité';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ComplianceRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComplianceRecordsTable::configure($table);
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
            'index' => ListComplianceRecords::route('/'),
            'create' => CreateComplianceRecord::route('/create'),
            'edit' => EditComplianceRecord::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return ComplianceRecord::whereNull('verified_at')
            ->where('expires_at', '>=', now())
            ->count();
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
