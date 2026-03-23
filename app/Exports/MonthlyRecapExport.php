<?php

namespace App\Exports;

use App\Services\AccountingExportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyRecapExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function __construct(
        private $artisan,
        private int $year,
    ) {}

    public function collection()
    {
        return app(AccountingExportService::class)
            ->getMonthlyRecap($this->artisan, $this->year);
    }

    public function headings(): array
    {
        return [
            'Mois', 'Nb prestations',
            'CA brut (€)', 'Commissions (€)', 'CA net (€)', 'Acomptes (€)',
        ];
    }

    public function title(): string
    {
        return 'Récap ' . $this->year;
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
