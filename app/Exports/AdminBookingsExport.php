<?php

namespace App\Exports;

use App\Models\BookingRequest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminBookingsExport implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(
        private ?string $status = null,
        private ?string $from = null,
        private ?string $to = null,
    ) {}

    public function query()
    {
        $query = BookingRequest::query()
            ->with(['client.user:id,name,email', 'bookable'])
            ->orderBy('created_at', 'desc');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }

        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID', 'Date', 'Client', 'Email client', 'Artiste', 'Type', 'Statut',
            'Prix total (€)', 'Acompte (€)', 'Solde (€)', 'Mode paiement',
        ];
    }

    public function map($row): array
    {
        $artistType = class_basename($row->bookable_type ?? '');

        return [
            '#' . $row->id,
            $row->created_at?->format('d/m/Y'),
            $row->client?->user?->name ?? 'N/A',
            $row->client?->user?->email ?? 'N/A',
            $row->bookable?->pseudo ?? $row->bookable?->name ?? 'N/A',
            $artistType === 'Tattooer' ? 'Tatoueur' : ($artistType === 'Piercer' ? 'Pierceur' : '—'),
            $row->status,
            number_format((float) ($row->total_price ?? 0), 2, ',', ' '),
            number_format((float) ($row->deposit_amount ?? 0), 2, ',', ' '),
            number_format((float) ($row->balance_amount ?? 0), 2, ',', ' '),
            $row->payment_method ?? '—',
        ];
    }

    public function title(): string
    {
        return 'Réservations';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F5F0EB'],
            ]],
        ];
    }
}
