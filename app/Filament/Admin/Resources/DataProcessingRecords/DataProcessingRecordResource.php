<?php

namespace App\Filament\Admin\Resources\DataProcessingRecords;

use App\Filament\Admin\Resources\DataProcessingRecords\Pages\CreateDataProcessingRecord;
use App\Filament\Admin\Resources\DataProcessingRecords\Pages\EditDataProcessingRecord;
use App\Filament\Admin\Resources\DataProcessingRecords\Pages\ListDataProcessingRecords;
use App\Filament\Admin\Resources\DataProcessingRecords\Schemas\DataProcessingRecordForm;
use App\Filament\Admin\Resources\DataProcessingRecords\Tables\DataProcessingRecordsTable;
use App\Models\DataProcessingRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DataProcessingRecordResource extends Resource
{
    protected static ?string $model = DataProcessingRecord::class;

    protected static ?string $navigationLabel      = 'Registre des traitements';
    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-shield-check';
    protected static UnitEnum|string|null $navigationGroup   = 'RGPD & Conformité';
    protected static ?int    $navigationSort        = 1;

    protected static ?string $modelLabel       = 'Traitement RGPD';
    protected static ?string $pluralModelLabel = 'Registre des traitements';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DataProcessingRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataProcessingRecordsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDataProcessingRecords::route('/'),
            'create' => CreateDataProcessingRecord::route('/create'),
            'edit'   => EditDataProcessingRecord::route('/{record}/edit'),
        ];
    }
}
