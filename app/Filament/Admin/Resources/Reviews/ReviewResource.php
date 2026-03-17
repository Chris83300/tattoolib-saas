<?php

namespace App\Filament\Admin\Resources\Reviews;

use App\Filament\Admin\Resources\Reviews\Pages\CreateReview;
use App\Filament\Admin\Resources\Reviews\Pages\EditReview;
use App\Filament\Admin\Resources\Reviews\Pages\ListReviews;
use App\Filament\Admin\Resources\Reviews\Schemas\ReviewForm;
use App\Filament\Admin\Resources\Reviews\Tables\ReviewsTable;
use App\Models\Review;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Avis';
    protected static ?string $modelLabel = 'Avis';
    protected static ?string $pluralModelLabel = 'Avis';
    protected static UnitEnum|string|null $navigationGroup = 'Qualité';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReviewsTable::configure($table);
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
            'index' => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit' => EditReview::route('/{record}/edit'),
        ];
    }
}
