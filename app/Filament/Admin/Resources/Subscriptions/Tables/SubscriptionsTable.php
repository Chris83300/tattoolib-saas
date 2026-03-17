<?php

namespace App\Filament\Admin\Resources\Subscriptions\Tables;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\Studio;
use Dom\Text;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Artiste')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        $user = $record->user;
                        if (!$user) return 'N/A';

                        $artistType = 'Inconnu';
                        $artistName = '';

                        // Déterminer le type d'artiste et le nom
                        if ($user->tattooer) {
                            $artistType = 'Tattooer';
                            $artistName = $user->tattooer->first_name . ' ' . $user->tattooer->last_name;
                        } elseif ($user->piercer) {
                            $artistType = 'Piercer';
                            $artistName = $user->piercer->first_name . ' ' . $user->piercer->last_name;
                        } elseif ($user->studio) {
                            $artistType = 'Studio';
                            $artistName = $user->studio->name;
                        } else {
                            $artistName = $user->name ?? $user->email;
                        }

                        return $artistName . ' (' . $artistType . ')';
                    }),
                TextColumn::make('plan')
                    ->badge()
                    ->label('Plan')
                    ->color(fn (string $state): string => match ($state) {
                        'STARTER' => 'warning',
                        'PRO' => 'success',
                        'STUDIO' => 'primary',
                        default => 'gray',
                    })
                    ->getStateUsing(function ($record) {
                        $plan = $record->plan ?? 'unknown';
                        return match ($plan) {
                            'starter' => 'STARTER',
                            'pro' => 'PRO',
                            'studio' => 'STUDIO',
                            default => strtoupper($plan),
                        };
                    }),

                TextColumn::make('stripe_status')
                    ->badge()
                    ->label('Statut')
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'trialing' => 'warning',
                        'canceled' => 'danger',
                        'incomplete' => 'gray',
                        'past_due' => 'danger',
                        'unpaid' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Actif',
                        'trialing' => 'Essai',
                        'canceled' => 'Annulé',
                        'incomplete' => 'Incomplet',
                        'past_due' => 'En retard',
                        'unpaid' => 'Impayé',
                        default => ucfirst($state),
                    }),

                TextColumn::make('stripe_id')
                    ->label('ID Stripe')
                    ->searchable()
                    ->copyable()
                    ->limit(15),

                TextColumn::make('quantity')
                    ->label('Quantité')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->created_at ? $record->created_at->format('d/m/Y H:i') : 'N/A';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->updated_at ? $record->updated_at->format('d/m/Y H:i') : 'N/A';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('stripe_status')
                    ->label('Statut')
                    ->options([
                        'active' => 'Actif',
                        'trialing' => 'Essai',
                        'canceled' => 'Annulé',
                        'incomplete' => 'Incomplet',
                        'past_due' => 'En retard',
                        'unpaid' => 'Impayé',
                    ]),

                SelectFilter::make('stripe_price')
                    ->label('Plan')
                    ->options([
                        'starter' => 'Starter',
                        'pro' => 'Pro',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'starter') {
                            $query->where('stripe_price', 'like', '%starter%');
                        } elseif ($data['value'] === 'pro') {
                            $query->where('stripe_price', 'like', '%pro%');
                        }
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
