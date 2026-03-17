<?php

namespace App\Filament\Admin\Resources\Complaints;

use App\Filament\Admin\Resources\Complaints\Pages\CreateComplaint;
use App\Filament\Admin\Resources\Complaints\Pages\EditComplaint;
use App\Filament\Admin\Resources\Complaints\Pages\ListComplaints;
use App\Filament\Admin\Resources\Complaints\Schemas\ComplaintForm;
use App\Filament\Admin\Resources\Complaints\Tables\ComplaintsTable;
use App\Models\Complaint;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationLabel = 'Réclamations';
    protected static ?string $modelLabel = 'Réclamation';
    protected static ?string $pluralModelLabel = 'Réclamations';
    protected static UnitEnum|string|null $navigationGroup = 'Qualité';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ComplaintForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComplaintsTable::configure($table);
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
            'index' => ListComplaints::route('/'),
            'create' => CreateComplaint::route('/create'),
            'edit' => EditComplaint::route('/{record}/edit'),
        ];
    }
}
