<?php

namespace App\Filament\Exports;

use App\Models\Transaction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TransactionExporter extends Exporter
{
    protected static ?string $model = Transaction::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID Transaction'),
            ExportColumn::make('processed_at')
                ->label('Date')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : ''),
            ExportColumn::make('client.name')
                ->label('Client'),
            ExportColumn::make('artist.name')
                ->label('Artiste')
                ->formatStateUsing(function ($record) {
                    return $record->artist ? $record->artist->name : 'N/A';
                }),
            ExportColumn::make('artist_type')
                ->label('Type Artiste')
                ->formatStateUsing(fn ($state) => match($state) {
                    'tattooer' => 'Tatoueur',
                    'piercer' => 'Pierceur',
                    default => $state,
                }),
            ExportColumn::make('payment_type')
                ->label('Type Paiement')
                ->formatStateUsing(fn ($state) => match($state) {
                    'deposit' => 'Acompte',
                    'full_payment' => 'Paiement complet',
                    default => $state,
                }),
            ExportColumn::make('amount')
                ->label('Montant Total (€)')
                ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ')),
            ExportColumn::make('commission_amount')
                ->label('Commission Plateforme (€)')
                ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ')),
            ExportColumn::make('net_amount')
                ->label('Net Artiste (€)')
                ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ')),
            ExportColumn::make('status')
                ->label('Statut')
                ->formatStateUsing(fn ($state) => match($state) {
                    'succeeded' => 'Succès',
                    'pending' => 'En attente',
                    'failed' => 'Échec',
                    default => $state,
                }),
            ExportColumn::make('refund_status')
                ->label('Statut Remboursement')
                ->formatStateUsing(fn ($state) => match($state) {
                    'none' => 'Aucun',
                    'partial' => 'Partiel',
                    'full' => 'Complet',
                    default => $state,
                }),
            ExportColumn::make('refund_amount')
                ->label('Montant Remboursé (€)')
                ->formatStateUsing(fn ($state) => $state ? number_format($state, 2, ',', ' ') : '0,00'),
            ExportColumn::make('stripe_payment_intent_id')
                ->label('Payment Intent ID'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'L\'export de vos transactions est terminé. ' . number_format($export->total_rows) . ' enregistrements exportés.';
        
        if ($export->file_path) {
            $body .= ' Le fichier est disponible pour téléchargement.';
        }
        
        return $body;
    }
}
