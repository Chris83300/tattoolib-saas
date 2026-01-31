<?php

namespace App\Filament\Admin\Resources\ComplianceRecords\Schemas;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ComplianceRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                
                Section::make('Informations Document')
                    ->schema([
                        
                        Select::make('tattooer_id')
                            ->label('Artiste')
                            ->relationship('tattooer', 'name')
                            ->searchable()
                            ->required(),
                        
                        Select::make('type')
                            ->label('Type de document')
                            ->options([
                                'hygiene' => 'Formation Hygiène & Salubrité',
                                'ars' => 'Déclaration ARS',
                                'certibiocide' => 'Certibiocide TP2',
                            ])
                            ->required()
                            ->native(false),
                        
                        TextInput::make('document_number')
                            ->label('Numéro du document')
                            ->helperText('Ex: Numéro ARS, numéro d\'attestation')
                            ->maxLength(255),
                        
                        DatePicker::make('issued_date')
                            ->label('Date d\'émission')
                            ->required(),
                        
                        DatePicker::make('expiry_date')
                            ->label('Date d\'expiration')
                            ->helperText('Laisser vide si pas de date d\'expiration'),
                        
                    ])
                    ->columns(2),

                Section::make('Validation Admin')
                    ->schema([
                        
                        Toggle::make('verified_by_admin')
                            ->label('Document vérifié')
                            ->helperText('Cocher si le document a été validé par l\'admin')
                            ->reactive(),
                        
                        DateTimePicker::make('verified_at')
                            ->label('Date de vérification')
                            ->disabled()
                            ->visible(fn ($get) => $get('verified_by_admin')),
                        
                        Textarea::make('admin_notes')
                            ->label('Notes admin')
                            ->rows(3)
                            ->helperText('Notes privées visibles uniquement par l\'administration')
                            ->columnSpanFull(),
                        
                    ]),

                Section::make('Document Scanné')
                    ->schema([
                        
                        FileUpload::make('document')
                            ->label('PDF du document')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->helperText('Format PDF uniquement, max 10MB')
                            ->columnSpanFull(),
                        
                    ]),
                
            ]);
    }
}
