<?php

namespace App\Services;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccountingExportService
{
    // Statuts avec activité financière (inclus dans les exports)
    private const FINANCIAL_STATUSES = [
        BookingRequestStatus::DEPOSIT_PAID,
        BookingRequestStatus::DATE_CONFIRMED,
        BookingRequestStatus::COMPLETED,
        BookingRequestStatus::BALANCE_PAID,
        BookingRequestStatus::BALANCE_PAID_OFFLINE,
        BookingRequestStatus::FULLY_COMPLETED,
        BookingRequestStatus::CANCELLED,
        BookingRequestStatus::NO_SHOW,
    ];

    // Statuts "revenus confirmés" pour le récap mensuel CA
    private const REVENUE_STATUSES = [
        BookingRequestStatus::COMPLETED,
        BookingRequestStatus::BALANCE_PAID,
        BookingRequestStatus::BALANCE_PAID_OFFLINE,
        BookingRequestStatus::FULLY_COMPLETED,
    ];

    /**
     * Transactions d'un artiste (tattooer ou pierceur).
     */
    public function getArtistTransactions($artisan, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $query = $this->baseQuery(get_class($artisan), [$artisan->id], $from, $to);

        return $query->get()->map(fn ($booking) => $this->formatTransactionRow($booking));
    }

    /**
     * Transactions de tous les artistes d'un studio.
     */
    public function getStudioTransactions(Studio $studio, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $tattooerIds = $studio->tattooers()->pluck('id')->toArray();
        $piercerIds  = $studio->piercers()->pluck('id')->toArray();

        $rows = collect();

        if (!empty($tattooerIds)) {
            $rows = $rows->concat(
                $this->baseQuery('App\Models\Tattooer', $tattooerIds, $from, $to)
                    ->with('bookable.user')
                    ->get()
                    ->map(function ($booking) {
                        $row = $this->formatTransactionRow($booking);
                        $row['artiste'] = $booking->bookable?->user?->name ?? '—';
                        return $row;
                    })
            );
        }

        if (!empty($piercerIds)) {
            $rows = $rows->concat(
                $this->baseQuery('App\Models\Piercer', $piercerIds, $from, $to)
                    ->with('bookable.user')
                    ->get()
                    ->map(function ($booking) {
                        $row = $this->formatTransactionRow($booking);
                        $row['artiste'] = $booking->bookable?->user?->name ?? '—';
                        return $row;
                    })
            );
        }

        return $rows->sortByDesc('date')->values();
    }

    /**
     * Récapitulatif mensuel pour un artiste.
     */
    public function getMonthlyRecap($artisan, int $year): Collection
    {
        return collect(range(1, 12))->map(function ($m) use ($artisan, $year) {
            $from = Carbon::create($year, $m, 1)->startOfMonth();
            $to   = $from->copy()->endOfMonth();

            $bookings = BookingRequest::where('bookable_type', get_class($artisan))
                ->where('bookable_id', $artisan->id)
                ->whereIn('status', array_map(fn ($s) => $s->value, self::REVENUE_STATUSES))
                ->whereBetween('created_at', [$from, $to])
                ->get();

            $totalBrut      = (float) $bookings->sum('total_price');
            $totalDeposit   = (float) $bookings->sum('total_deposit_amount');
            $totalCommission = (float) $this->getCommissionSumForBookings($bookings->pluck('id')->toArray());
            $nbPrestations  = $bookings->count();

            return [
                'mois'           => $from->locale('fr')->translatedFormat('F Y'),
                'nb_prestations' => $nbPrestations,
                'ca_brut_€'      => $this->formatEuros($totalBrut),
                'commissions_€'  => $this->formatEuros($totalCommission),
                'ca_net_€'       => $this->formatEuros($totalBrut - $totalCommission),
                'acomptes_€'     => $this->formatEuros($totalDeposit),
            ];
        });
    }

    // ─── Helpers privés ────────────────────────────────────────────────────────

    private function baseQuery(string $bookableType, array $ids, ?Carbon $from, ?Carbon $to)
    {
        $query = BookingRequest::with(['client.user'])
            ->where('bookable_type', $bookableType)
            ->whereIn('bookable_id', $ids)
            ->whereIn('status', array_map(fn ($s) => $s->value, self::FINANCIAL_STATUSES))
            ->addSelect('booking_requests.*')
            ->addSelect(DB::raw("(
                SELECT COALESCE(SUM(t.commission_amount), 0)
                FROM transactions t
                JOIN payments p ON t.payment_id = p.id
                WHERE p.booking_request_id = booking_requests.id
            ) as commission_total"))
            ->orderBy('booking_requests.created_at', 'desc');

        if ($from) {
            $query->where('booking_requests.created_at', '>=', $from);
        }
        if ($to) {
            $query->where('booking_requests.created_at', '<=', $to);
        }

        return $query;
    }

    private function formatTransactionRow(BookingRequest $booking): array
    {
        $totalPrice      = (float) ($booking->total_price ?? 0);
        $depositAmount   = (float) ($booking->total_deposit_amount ?? $booking->deposit_amount ?? 0);
        $balanceAmount   = (float) ($booking->balance_amount ?? ($totalPrice - $depositAmount));
        $commissionTotal = (float) ($booking->commission_total ?? 0);
        $refundAmount    = (float) ($booking->refund_amount ?? 0);

        return [
            'date'             => $booking->created_at?->format('d/m/Y'),
            'reference'        => 'INK-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
            'client'           => $booking->client?->user?->name ?? '—',
            'description'      => $booking->description ?? 'Prestation',
            'statut'           => $booking->status instanceof BookingRequestStatus
                                    ? $booking->status->label()
                                    : ($booking->status ?? '—'),
            'montant_total_€'  => $this->formatEuros($totalPrice),
            'acompte_€'        => $this->formatEuros($depositAmount),
            'solde_€'          => $this->formatEuros($balanceAmount),
            'commission_€'     => $this->formatEuros($commissionTotal),
            'net_artiste_€'    => $this->formatEuros($totalPrice - $commissionTotal),
            'remboursement_€'  => $this->formatEuros($refundAmount),
            'date_acompte'     => $booking->deposit_paid_at?->format('d/m/Y') ?? '',
            'date_solde'       => $booking->balance_paid_at?->format('d/m/Y') ?? '',
        ];
    }

    private function getCommissionSumForBookings(array $bookingRequestIds): float
    {
        if (empty($bookingRequestIds)) {
            return 0.0;
        }

        return (float) DB::table('transactions as t')
            ->join('payments as p', 't.payment_id', '=', 'p.id')
            ->whereIn('p.booking_request_id', $bookingRequestIds)
            ->sum('t.commission_amount');
    }

    private function formatEuros(float $amount): string
    {
        // Montants déjà en euros dans la DB (decimal:2) — pas de conversion
        return number_format($amount, 2, ',', ' ');
    }

    /**
     * Exporter les transactions en Excel.
     */
    public function exportToExcel(Collection $transactions, string $filename)
    {
        $headers = [
            'Date', 'Référence', 'Client', 'Artiste', 'Description', 'Statut',
            'Montant Total €', 'Acompte €', 'Solde €', 'Commission €',
            'Net Artiste €', 'Remboursement €', 'Date Acompte', 'Date Solde'
        ];

        $rows = $transactions->map(function ($transaction) {
            return [
                $transaction['date'],
                $transaction['reference'],
                $transaction['client'],
                $transaction['artiste'] ?? '',
                $transaction['description'],
                $transaction['statut'],
                $transaction['montant_total_€'],
                $transaction['acompte_€'],
                $transaction['solde_€'],
                $transaction['commission_€'],
                $transaction['net_artiste_€'],
                $transaction['remboursement_€'],
                $transaction['date_acompte'],
                $transaction['date_solde'],
            ];
        })->toArray();

        // Création du fichier CSV simple (Excel compatible)
        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');

            // Ajout BOM pour UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // En-têtes
            fputcsv($file, $headers, ';');

            // Données
            foreach ($rows as $row) {
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }

    /**
     * Exporter les transactions en CSV.
     */
    public function exportToCsv(Collection $transactions, string $filename)
    {
        return $this->exportToExcel($transactions, $filename);
    }
}
