<?php

namespace App\Filament\Admin\Resources\Tattooers\Tables;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;

class TattooersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
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
                    ->getStateUsing(fn ($record) => $record->user->getFirstMediaUrl('avatar') ?? url('/images/default-tattooer-avatar.png'))
                    ->defaultImageUrl(url('/images/default-tattooer-avatar.png')),

                // COLONNE 3 : Nom (principal)
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nom')
                    ->searchable(['first_name', 'last_name', 'name', 'pseudo'])
                    ->sortable()
                    ->weight('medium')
                    ->copyable()
                    ->tooltip('Cliquer pour copier le nom')
                    ->description(fn ($record): ?string => $record->location)
                    ->getStateUsing(fn ($record): string =>
                        $record->pseudo ??
                        trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? '')) ?:
                        $record->name ?? 'N/A'
                    )
                    ->url(fn ($record) => '/admin/tattooers/'.$record->id.'/edit')
                    ->openUrlInNewTab(false),

                // COLONNE 4 : Email
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->iconColor('primary')
                    ->toggleable(),

                // COLONNE 5 : Profil Public
                Tables\Columns\TextColumn::make('profile_link')
                    ->label('Profil')
                    ->url(fn ($record) => route('marketplace.show.artist', $record->slug))
                    ->icon('heroicon-o-photo')
                    ->iconColor('primary')
                    ->openUrlInNewTab(true)
                    ->tooltip('Voir le profil public du tatoueur'),

                // COLONNE 5 : SIRET
                Tables\Columns\TextColumn::make('siret')
                    ->label('SIRET')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->color(fn ($record) => $record->siret_verified ? 'success' : 'warning')
                    ->icon(fn ($record) => $record->siret_verified ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-triangle')
                    ->toggleable(),

                // COLONNE 6 : Ville + Code Postal
                Tables\Columns\TextColumn::make('location')
                    ->label('Localisation')
                    ->searchable(['city', 'postal_code'])
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                // COLONNE 7 : Statut (badge coloré)
                Tables\Columns\TextColumn::make('user.status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending_verification' => 'warning',
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pending_verification' => 'heroicon-o-clock',
                        'active' => 'heroicon-o-check-circle',
                        'suspended' => 'heroicon-o-x-circle',
                        default => '',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending_verification' => 'En attente',
                        'active' => 'Actif',
                        'suspended' => 'Suspendu',
                        default => $state,
                    }),

                // COLONNE 8 : Badge Conformité
                Tables\Columns\IconColumn::make('has_compliance_badge')
                    ->label('Badge')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->tooltip(fn ($record) => $record->has_compliance_badge ? 'Badge de conformité actif' : 'Badge de conformité inactif'),

                // COLONNE 9 : Portfolio (compteur)
                Tables\Columns\TextColumn::make('portfolio_count')
                    ->label('Portfolio')
                    ->getStateUsing(fn ($record) => $record->getMedia('portfolio')->count())
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'warning')
                    ->icon('heroicon-o-photo')
                    ->tooltip(fn ($record) => $record->getMedia('portfolio')->count() . ' photos dans le portfolio'),

                // COLONNE 10 : Stripe Connect
                Tables\Columns\IconColumn::make('stripe_connect_account_id')
                    ->label('Stripe')
                    ->boolean()
                    ->trueIcon('heroicon-o-credit-card')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->tooltip(fn ($record) => $record->stripe_connect_account_id ? 'Stripe Connect configuré' : 'Stripe Connect non configuré')
                    ->toggleable(isToggledHiddenByDefault: true),

                // COLONNE 11 : Date de création
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

                // COLONNE 12 : Date de validation
                Tables\Columns\TextColumn::make('admin_verified_at')
                    ->label('Validé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Non validé')
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                // FILTRE 1 : Statut
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->relationship('user', 'status')
                    ->options([
                        'pending_verification' => 'En attente de validation',
                        'pending_verification' => 'En attente',
                        'active' => 'Actif',
                        'suspended' => 'Suspendu',
                    ]),

                Tables\Filters\SelectFilter::make('subscription_plan')
                    ->label('Plan')
                    ->options([
                        'free' => 'FREE',
                        'pro' => 'PRO',
                        'studio' => 'STUDIO',
                    ]),

                Tables\Filters\Filter::make('has_compliance_badge')
                    ->label('Badge de conformité')
                    ->query(fn ($query) => $query->where('has_compliance_badge', true))
                    ->toggle(),

                Tables\Filters\Filter::make('no_compliance_badge')
                    ->label('Sans badge')
                    ->query(fn ($query) => $query->where('has_compliance_badge', false))
                    ->toggle(),
            ])
             ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
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
