<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // SECTION 1 : Informations utilisateur
                Section::make('Informations utilisateur')
                    ->description('Informations de base de l\'utilisateur')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('Nom complet')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('pseudo')
                            ->label('Pseudo')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Téléphone')
                            ->tel()
                            ->maxLength(20),

                    ])
                    ->columns(2),

                // SECTION 2 : Rôle et permissions
                Section::make('Rôle et permissions')
                    ->description('Configuration du rôle et du statut de l\'utilisateur')
                    ->schema([

                        Forms\Components\Select::make('role')
                            ->label('Rôle')
                            ->options([
                                'admin' => 'Admin',
                                'tattooer' => 'Tatoueur',
                                'pierceur' => 'Pierceur',
                                'studio' => 'Studio',
                                'studio_artist' => 'Artiste Studio',
                                'client' => 'Client',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state === 'admin') {
                                    $set('is_active', true);
                                }
                            }),

                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'pending_verification' => 'En attente de vérification',
                                'active' => 'Actif',
                                'suspended' => 'Suspendu',
                            ])
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Compte actif')
                            ->helperText('Un compte inactif ne peut pas se connecter')
                            ->default(true),

                        Forms\Components\Toggle::make('is_admin')
                            ->label('Accès admin')
                            ->helperText('Donne l\'accès au panel d\'administration')
                            ->default(false),

                    ])
                    ->columns(2),

                // SECTION 3 : Informations système
                Section::make('Informations système')
                    ->description('Informations techniques et de suivi')
                    ->schema([

                        Forms\Components\TextInput::make('timezone')
                            ->label('Fuseau horaire')
                            ->default('Europe/Paris')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email vérifié le')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('last_login_at')
                            ->label('Dernière connexion')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Date d\'inscription')
                            ->disabled(),

                    ])
                    ->columns(2)
                    ->collapsed(),

            ]);
    }
}
