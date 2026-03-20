<?php

namespace App\Filament\Exports;

use App\Models\Payment;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PaymentExporter extends Exporter
{
    protected static ?string $model = Payment::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('booking_request_id'),
            ExportColumn::make('stripe_payment_intent_id'),
            ExportColumn::make('stripe_charge_id'),
            ExportColumn::make('amount'),
            ExportColumn::make('currency'),
            ExportColumn::make('status'),
            ExportColumn::make('payment_type'),
            ExportColumn::make('recipient_type'),
            ExportColumn::make('recipient_name'),
            ExportColumn::make('paid_at'),
            ExportColumn::make('failure_reason'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'L\'export de vos paiements est terminé. ' . Number::format($export->successful_rows) . ' ligne(s) exportée(s).';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ligne(s) en échec.';
        }

        return $body;
    }
}
