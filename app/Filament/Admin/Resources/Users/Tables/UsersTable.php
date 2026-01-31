<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // COLONNE 1 : ID (cachée par défaut)
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // COLONNE 2 : Avatar
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular()
                    ->size(50)
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name ?? 'User') . '&color=7F9CF5&background=EBF4FF'),

                // COLONNE 3 : Nom (principal)
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->copyable()
                    ->tooltip('Cliquer pour copier le nom')
                    ->description(fn ($record): ?string => $record->pseudo ?? null),

                // COLONNE 4 : Pseudo
                Tables\Columns\TextColumn::make('pseudo')
                    ->label('Pseudo')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary')
                    ->toggleable(),

                // COLONNE 5 : Email
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->iconColor('primary'),

                // COLONNE 6 : Rôle (badge coloré)
                Tables\Columns\BadgeColumn::make('role')
                    ->label('Rôle')
                    ->colors([
                        'danger' => 'admin',
                        'success' => 'tattooer',
                        'warning' => 'pierceur',
                        'info' => 'studio',
                        'secondary' => 'studio_artist',
                        'gray' => 'client',
                    ])
                    ->icons([
                        'heroicon-o-shield-check' => 'admin',
                        'heroicon-o-paint-brush' => 'tattooer',
                        'heroicon-o-scissors' => 'pierceur',
                        'heroicon-o-building-office-2' => 'studio',
                        'heroicon-o-user-group' => 'studio_artist',
                        'heroicon-o-user' => 'client',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin',
                        'tattooer' => 'Tatoueur',
                        'pierceur' => 'Pierceur',
                        'studio' => 'Studio',
                        'studio_artist' => 'Artiste Studio',
                        'client' => 'Client',
                        default => $state,
                    }),

                // COLONNE 7 : Statut (badge coloré)
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'pending_verification',
                        'success' => 'active',
                        'danger' => 'suspended',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending_verification',
                        'heroicon-o-check-circle' => 'active',
                        'heroicon-o-x-circle' => 'suspended',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending_verification' => 'En attente',
                        'active' => 'Actif',
                        'suspended' => 'Suspendu',
                        default => $state,
                    }),

                // COLONNE 8 : Compte actif
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn ($record) => $record->is_active ? 'Compte actif' : 'Compte inactif'),

                // COLONNE 9 : Accès admin
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('danger')
                    ->falseColor('gray')
                    ->tooltip(fn ($record) => $record->is_admin ? 'Accès administrateur' : 'Accès utilisateur standard')
                    ->toggleable(isToggledHiddenByDefault: true),

                // COLONNE 10 : Date de création
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

                // COLONNE 11 : Dernière connexion
                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Dernière connexion')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Jamais')
                    ->color(fn ($record) => $record->last_login_at ? 'success' : 'warning')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                // FILTRE 1 : Rôle
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rôle')
                    ->options([
                        'admin' => 'Admin',
                        'tattooer' => 'Tatoueur',
                        'pierceur' => 'Pierceur',
                        'studio' => 'Studio',
                        'studio_artist' => 'Artiste Studio',
                        'client' => 'Client',
                    ]),

                // FILTRE 2 : Statut
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'pending_verification' => 'En attente',
                        'active' => 'Actif',
                        'suspended' => 'Suspendu',
                    ]),

                // FILTRE 3 : Compte actif
                Tables\Filters\Filter::make('is_active')
                    ->label('Comptes actifs')
                    ->query(fn ($query) => $query->where('is_active', true))
                    ->toggle(),

                // FILTRE 4 : Compte inactif
                Tables\Filters\Filter::make('is_inactive')
                    ->label('Comptes inactifs')
                    ->query(fn ($query) => $query->where('is_active', false))
                    ->toggle(),

                // FILTRE 5 : Admin
                Tables\Filters\Filter::make('is_admin')
                    ->label('Admins uniquement')
                    ->query(fn ($query) => $query->where('is_admin', true))
                    ->toggle(),

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->striped()
            ->poll('60s');
    }
}
