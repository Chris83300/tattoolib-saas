<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ArtistFullExport implements WithMultipleSheets
{
    public function __construct(
        private $artisan,
        private int $year,
        private ?Carbon $from = null,
        private ?Carbon $to = null,
    ) {}

    public function sheets(): array
    {
        return [
            new ArtistTransactionsExport($this->artisan, $this->from, $this->to),
            new MonthlyRecapExport($this->artisan, $this->year),
        ];
    }
}
