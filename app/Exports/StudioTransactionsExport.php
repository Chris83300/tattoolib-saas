<?php

namespace App\Exports;

use App\Models\Studio;
use App\Services\AccountingExportService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudioTransactionsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function __construct(
        private Studio $studio,
        private ?Carbon $from = null,
        private ?Carbon $to = null,
    ) {}

    public function collection()
    {
        return app(AccountingExportService::class)
            ->getStudioTransactions($this->studio, $this->from, $this->to);
    }

    public function headings(): array
    {
        return [
            'Date', 'Référence', 'Client', 'Description', 'Statut',
            'Montant total (€)', 'Acompte (€)', 'Solde (€)',
            'Commission (€)', 'Net artiste (€)', 'Remboursement (€)',
            'Date acompte', 'Date solde', 'Artiste',
        ];
    }

    public function title(): string
    {
        return 'Transactions Studio';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F5F0EB'],
                ],
            ],
        ];
    }
}
