<?php

namespace App\Filament\Admin\Resources\DataProcessingRecords\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DataProcessingRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identification du traitement')->schema([
                TextInput::make('name')
                    ->label('Nom du traitement')
                    ->required()
                    ->maxLength(255),

                TextInput::make('purpose')
                    ->label('Finalité')
                    ->required()
                    ->maxLength(255),

                TextInput::make('legal_basis')
                    ->label('Base légale')
                    ->required()
                    ->helperText('Ex: Exécution du contrat, Consentement, Obligation légale, Intérêt légitime'),

                TextInput::make('retention_period')
                    ->label('Durée de conservation')
                    ->required(),
            ])->columns(2),

            Section::make('Personnes et données concernées')->schema([
                TagsInput::make('data_categories')
                    ->label('Catégories de données')
                    ->required(),

                TagsInput::make('data_subjects')
                    ->label('Personnes concernées')
                    ->required(),

                TagsInput::make('recipients')
                    ->label('Destinataires / Sous-traitants'),
            ]),

            Section::make('Transferts et sécurité')->schema([
                Toggle::make('transfers_outside_eu')
                    ->label('Transferts hors UE')
                    ->live(),

                Toggle::make('requires_dpia')
                    ->label("AIPD (Analyse d'Impact) requise")
                    ->live(),

                Toggle::make('is_active')
                    ->label('Traitement actif')
                    ->default(true),

                Textarea::make('security_measures')
                    ->label('Mesures de sécurité')
                    ->rows(3)
                    ->columnSpanFull(),

                Textarea::make('dpia_notes')
                    ->label('Notes AIPD')
                    ->rows(3)
                    ->columnSpanFull()
                    ->visible(fn($get) => $get('requires_dpia')),
            ])->columns(3),
        ]);
    }
}
