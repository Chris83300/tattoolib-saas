<?php

namespace App\Http\Controllers;

use App\Exports\AdminBookingsExport;
use App\Exports\ArtistFullExport;
use App\Exports\ArtistTransactionsExport;
use App\Exports\MonthlyRecapExport;
use App\Exports\StudioTransactionsExport;
use App\Services\AccountingExportService;
use App\Services\CsvExportHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export des transactions de l'artiste connecté (tattooer ou pierceur).
     */
    public function artistTransactions(Request $request)
    {
        $artisan = $this->getArtisan();
        abort_unless($artisan, 403, 'Accès réservé aux artistes.');

        [$from, $to] = $this->parseDateRange($request);
        $format   = $request->get('format', 'xlsx');
        $filename = 'transactions-' . now()->format('Y-m-d');

        if ($format === 'csv') {
            $data = app(AccountingExportService::class)->getArtistTransactions($artisan, $from, $to);
            return CsvExportHelper::download($data, $this->transactionHeadings(), $filename . '.csv');
        }

        return Excel::download(
            new ArtistTransactionsExport($artisan, $from, $to),
            $filename . '.xlsx'
        );
    }

    /**
     * Export complet artiste : transactions + récap mensuel (multi-feuilles).
     */
    public function artistFull(Request $request)
    {
        $artisan = $this->getArtisan();
        abort_unless($artisan, 403, 'Accès réservé aux artistes.');

        [$from, $to] = $this->parseDateRange($request);
        $year = (int) $request->get('year', now()->year);

        return Excel::download(
            new ArtistFullExport($artisan, $year, $from, $to),
            'comptabilite-' . $year . '-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export récap mensuel artiste uniquement.
     */
    public function artistMonthlyRecap(Request $request)
    {
        $artisan = $this->getArtisan();
        abort_unless($artisan, 403, 'Accès réservé aux artistes.');

        $year     = (int) $request->get('year', now()->year);
        $format   = $request->get('format', 'xlsx');
        $filename = 'recap-mensuel-' . $year;

        if ($format === 'csv') {
            $data = app(AccountingExportService::class)->getMonthlyRecap($artisan, $year);
            return CsvExportHelper::download($data, $this->recapHeadings(), $filename . '.csv');
        }

        return Excel::download(new MonthlyRecapExport($artisan, $year), $filename . '.xlsx');
    }

    /**
     * Export transactions studio.
     */
    public function studioTransactions(Request $request)
    {
        $user   = auth()->user();
        $studio = $user->studio ?? null;
        abort_unless($studio, 403, 'Accès réservé aux studios.');

        [$from, $to] = $this->parseDateRange($request);
        $format   = $request->get('format', 'xlsx');
        $filename = 'transactions-studio-' . now()->format('Y-m-d');

        if ($format === 'csv') {
            $data = app(AccountingExportService::class)->getStudioTransactions($studio, $from, $to);
            return CsvExportHelper::download(
                $data,
                array_merge($this->transactionHeadings(), ['Artiste']),
                $filename . '.csv'
            );
        }

        return Excel::download(new StudioTransactionsExport($studio, $from, $to), $filename . '.xlsx');
    }

    /**
     * Export admin : toutes les réservations.
     */
    public function adminBookings(Request $request)
    {
        $format   = $request->get('format', 'xlsx');
        $status   = $request->get('status');
        $from     = $request->get('from');
        $to       = $request->get('to');
        $filename = 'reservations-admin-' . now()->format('Y-m-d');

        if ($format === 'csv') {
            $export = new AdminBookingsExport($status, $from, $to);
            $data   = $export->query()->get();
            return CsvExportHelper::download($data->map(fn ($r) => $export->map($r)), $export->headings(), $filename . '.csv');
        }

        return Excel::download(new AdminBookingsExport($status, $from, $to), $filename . '.xlsx');
    }

    // ─── Helpers privés ────────────────────────────────────────────────────────

    private function getArtisan()
    {
        return auth()->user()->artisan();
    }

    private function parseDateRange(Request $request): array
    {
        $from = $request->get('from') ? Carbon::parse($request->get('from'))->startOfDay() : null;
        $to   = $request->get('to')   ? Carbon::parse($request->get('to'))->endOfDay()   : null;
        return [$from, $to];
    }

    private function transactionHeadings(): array
    {
        return [
            'Date', 'Référence', 'Client', 'Description', 'Statut',
            'Montant total (€)', 'Acompte (€)', 'Solde (€)',
            'Commission (€)', 'Net artiste (€)', 'Remboursement (€)',
            'Date acompte', 'Date solde',
        ];
    }

    private function recapHeadings(): array
    {
        return ['Mois', 'Nb prestations', 'CA brut (€)', 'Commissions (€)', 'CA net (€)', 'Acomptes (€)'];
    }
}
