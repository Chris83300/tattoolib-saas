# 📊 P1.3 — EXPORT CSV/EXCEL COMPTABILITÉ
# Pour Claude Code — Exports comptables artistes, studios, admin
# Commit après chaque phase

## CONTEXTE

L'audit a identifié un TODO dans AccountingController (l.286) pour l'export CSV/Excel.
Les artistes indépendants (auto-entrepreneurs, micro-entreprises) ont l'obligation légale de tenir un livre des recettes.
Les studios doivent pouvoir exporter leur comptabilité.

Stack : Laravel 12, Livewire 3.7. Installer maatwebsite/excel OU utiliser les exports CSV natifs Laravel (League CSV est déjà inclus dans Laravel).

### EXPORTS NÉCESSAIRES

| # | Export | Qui l'utilise | Contenu |
|---|--------|---------------|---------|
| 1 | **Transactions artiste** | Tattooer/Pierceur | Historique acomptes + soldes reçus, commissions prélevées, remboursements |
| 2 | **Transactions studio** | Studio | Toutes les transactions de tous les artistes du studio |
| 3 | **Abonnements artiste** | Tattooer/Pierceur PRO | Historique des paiements d'abonnement (pour déclaration charges) |
| 4 | **Abonnements studio** | Studio | Historique des paiements studio (pour comptabilité) |
| 5 | **Récap annuel / mensuel** | Tous | Synthèse par mois : CA brut, commissions, net, nb prestations |
| 6 | **Transactions admin** | Admin Filament | Vue globale de toutes les transactions de la plateforme |

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT EXPORT COMPTA ==="

# 0A. AccountingController existant ?
find app -name "*Accounting*" -o -name "*accounting*" | head -10
grep -n "export\|csv\|excel\|download" app/Http/Controllers/AccountingController.php 2>/dev/null | head -10

# 0B. StudioAccountingEntry model
grep -n "fillable\|function \|class " app/Models/StudioAccountingEntry.php 2>/dev/null | head -20

# 0C. Tables comptabilité
php artisan tinker --execute="
  \$tables = ['studio_accounting_entries', 'payments', 'transactions', 'booking_requests', 'subscriptions'];
  foreach(\$tables as \$t) {
    if (Schema::hasTable(\$t)) {
      echo \$t . ': ' . implode(', ', Schema::getColumnListing(\$t)) . PHP_EOL;
    } else {
      echo \$t . ': TABLE ABSENTE' . PHP_EOL;
    }
  }
"

# 0D. BookingRequest — colonnes financières
grep -n "deposit\|price\|amount\|commission\|payment\|paid\|refund" app/Models/BookingRequest.php | head -15

# 0E. Stripe/Cashier — modèle Subscription
grep -n "use Billable\|HasStripeId\|Subscription" app/Models/User.php app/Models/Tattooer.php app/Models/Piercer.php app/Models/Studio.php 2>/dev/null | head -10
php artisan tinker --execute="
  if (Schema::hasTable('subscriptions')) {
    echo 'subscriptions: ' . implode(', ', Schema::getColumnListing('subscriptions')) . PHP_EOL;
  }
"

# 0F. Paiements — table payments si existante
php artisan tinker --execute="
  if (Schema::hasTable('payments')) {
    echo 'payments: ' . implode(', ', Schema::getColumnListing('payments')) . PHP_EOL;
  } else {
    echo 'TABLE payments: ABSENTE — les données financières sont dans booking_requests' . PHP_EOL;
  }
"

# 0G. Enums / statuts pour filtrer
cat app/Enums/BookingRequestStatus.php 2>/dev/null | head -30

# 0H. Routes existantes comptabilité
php artisan route:list 2>&1 | grep -i "account\|compta\|export\|csv\|excel\|billing\|finance\|payment" | head -15

# 0I. Vues existantes
find resources/views -name "*account*" -o -name "*compta*" -o -name "*payment*" -o -name "*billing*" -o -name "*finance*" 2>/dev/null | head -10

# 0J. Package excel déjà installé ?
grep "maatwebsite\|league.*csv\|openspout\|phpspreadsheet" composer.json | head -5

# 0K. PaymentController / route paiements artiste
grep -rn "class.*PaymentController\|payments\|transactions" app/Http/Controllers/ --include="*.php" -l | head -5
php artisan route:list 2>&1 | grep "payment" | head -10
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## PHASE 1 — INSTALLER LE PACKAGE EXPORT

Option A — **maatwebsite/excel** (recommandé pour Excel + CSV) :
```bash
composer require maatwebsite/excel
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

Option B — Si l'installation échoue ou trop lourd, utiliser **League CSV** (déjà inclus dans Laravel) pour du CSV pur. Dans ce cas, pas besoin d'installer de package.

**Choisir l'option qui fonctionne et continuer.**

```bash
git add -A && git commit -m "feat(export): installer maatwebsite/excel ou confirmer League CSV"
```

---

## PHASE 2 — SERVICE D'EXPORT COMPTABLE

Créer un service qui centralise la logique de requêtes comptables. Ce service est utilisé à la fois par les controllers web ET par les exports Excel/CSV.

```php
// app/Services/AccountingExportService.php
namespace App\Services;

use App\Models\BookingRequest;
use App\Models\Studio;
use App\Enums\BookingRequestStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AccountingExportService
{
    /**
     * Transactions d'un artiste (tattooer ou pierceur).
     * Retourne une collection de données formatées pour l'export.
     */
    public function getArtistTransactions($artisan, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $query = BookingRequest::where('bookable_type', get_class($artisan))
            ->where('bookable_id', $artisan->id)
            ->whereIn('status', [
                BookingRequestStatus::COMPLETED,
                BookingRequestStatus::DEPOSIT_PAID,
                BookingRequestStatus::DESIGN_SENT,
                BookingRequestStatus::CONFIRMED,
                BookingRequestStatus::CANCELLED,
            ]);

        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to);

        return $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return $this->formatTransactionRow($booking);
            });
    }

    /**
     * Transactions de tous les artistes d'un studio.
     */
    public function getStudioTransactions(Studio $studio, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        // Récupérer les IDs des artistes du studio
        $tattooerIds = $studio->tattooers()->pluck('id')->toArray();
        $piercerIds = $studio->piercers()->pluck('id')->toArray();

        $query = BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
                $q->where(function ($sub) use ($tattooerIds) {
                    $sub->where('bookable_type', 'App\\Models\\Tattooer')
                        ->whereIn('bookable_id', $tattooerIds);
                })->orWhere(function ($sub) use ($piercerIds) {
                    $sub->where('bookable_type', 'App\\Models\\Piercer')
                        ->whereIn('bookable_id', $piercerIds);
                });
            })
            ->whereIn('status', [
                BookingRequestStatus::COMPLETED,
                BookingRequestStatus::DEPOSIT_PAID,
                BookingRequestStatus::DESIGN_SENT,
                BookingRequestStatus::CONFIRMED,
                BookingRequestStatus::CANCELLED,
            ]);

        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to);

        return $query->with(['bookable.user', 'client.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                $row = $this->formatTransactionRow($booking);
                $row['artiste'] = $booking->bookable?->user?->name ?? '—';
                return $row;
            });
    }

    /**
     * Récapitulatif mensuel pour un artiste.
     */
    public function getMonthlyRecap($artisan, int $year): Collection
    {
        $months = collect();

        for ($m = 1; $m <= 12; $m++) {
            $from = Carbon::create($year, $m, 1)->startOfMonth();
            $to = $from->copy()->endOfMonth();

            $bookings = BookingRequest::where('bookable_type', get_class($artisan))
                ->where('bookable_id', $artisan->id)
                ->whereIn('status', [
                    BookingRequestStatus::COMPLETED,
                    BookingRequestStatus::CONFIRMED,
                ])
                ->whereBetween('created_at', [$from, $to])
                ->get();

            // Adapter les noms de colonnes aux vrais noms trouvés en Phase 0
            $totalBrut = $bookings->sum('total_price') ?? $bookings->sum('price') ?? 0;
            $totalDeposit = $bookings->sum('total_deposit_amount') ?? $bookings->sum('deposit_amount') ?? 0;
            $totalCommission = $bookings->sum('commission_amount') ?? 0;
            $nbPrestations = $bookings->count();

            $months->push([
                'mois' => $from->translatedFormat('F Y'),
                'nb_prestations' => $nbPrestations,
                'ca_brut_€' => $this->centsToEuros($totalBrut),
                'commissions_€' => $this->centsToEuros($totalCommission),
                'ca_net_€' => $this->centsToEuros($totalBrut - $totalCommission),
                'acomptes_€' => $this->centsToEuros($totalDeposit),
            ]);
        }

        return $months;
    }

    /**
     * Formater une ligne de transaction.
     */
    private function formatTransactionRow(BookingRequest $booking): array
    {
        // ADAPTER les noms de colonnes aux vrais noms trouvés en Phase 0
        $totalPrice = $booking->total_price ?? $booking->price ?? 0;
        $depositAmount = $booking->total_deposit_amount ?? $booking->deposit_amount ?? 0;
        $commissionAmount = $booking->commission_amount ?? 0;
        $refundAmount = $booking->refund_amount ?? 0;

        return [
            'date' => $booking->created_at?->format('d/m/Y'),
            'reference' => 'INK-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
            'client' => $booking->client?->user?->name ?? '—',
            'description' => $booking->description ?? $booking->project_description ?? 'Prestation',
            'statut' => $booking->status?->value ?? $booking->status ?? '—',
            'montant_total_€' => $this->centsToEuros($totalPrice),
            'acompte_€' => $this->centsToEuros($depositAmount),
            'solde_€' => $this->centsToEuros($totalPrice - $depositAmount),
            'commission_€' => $this->centsToEuros($commissionAmount),
            'net_artiste_€' => $this->centsToEuros($totalPrice - $commissionAmount),
            'remboursement_€' => $this->centsToEuros($refundAmount),
            'date_acompte' => $booking->deposit_paid_at?->format('d/m/Y') ?? '',
            'date_solde' => $booking->balance_paid_at?->format('d/m/Y') ?? '',
        ];
    }

    /**
     * Centimes → Euros formaté.
     * IMPORTANT : Vérifier en Phase 0 si les montants sont en centimes ou en euros.
     * Si déjà en euros, retirer la division par 100.
     */
    private function centsToEuros(int|float $cents): string
    {
        return number_format($cents / 100, 2, ',', ' ');
    }
}
```

IMPORTANT :
- Les noms de colonnes (`total_price`, `deposit_amount`, `commission_amount`, `refund_amount`, `balance_paid_at`) sont indicatifs
- Phase 0 révélera les vrais noms → adapter
- La méthode `centsToEuros()` suppose des centimes — si les montants sont en euros en DB, supprimer la division par 100
- Les relations `studio->tattooers()` et `studio->piercers()` doivent exister (vérifier)

```bash
git add -A && git commit -m "feat(export): AccountingExportService — transactions et récap mensuel"
```

---

## PHASE 3 — CLASSES D'EXPORT (maatwebsite/excel)

Si maatwebsite/excel est installé, créer les classes d'export :

### 3A. Export transactions artiste

```php
// app/Exports/ArtistTransactionsExport.php
namespace App\Exports;

use App\Services\AccountingExportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ArtistTransactionsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function __construct(
        private $artisan,
        private ?Carbon $from = null,
        private ?Carbon $to = null,
    ) {}

    public function collection()
    {
        return app(AccountingExportService::class)
            ->getArtistTransactions($this->artisan, $this->from, $this->to);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Référence',
            'Client',
            'Description',
            'Statut',
            'Montant total (€)',
            'Acompte (€)',
            'Solde (€)',
            'Commission (€)',
            'Net artiste (€)',
            'Remboursement (€)',
            'Date acompte',
            'Date solde',
        ];
    }

    public function title(): string
    {
        return 'Transactions';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F5F0EB'],
                ],
            ],
        ];
    }
}
```

### 3B. Export transactions studio (avec colonne artiste)

```php
// app/Exports/StudioTransactionsExport.php
namespace App\Exports;

use App\Services\AccountingExportService;
use App\Models\Studio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

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
            'Date',
            'Référence',
            'Client',
            'Description',
            'Statut',
            'Montant total (€)',
            'Acompte (€)',
            'Solde (€)',
            'Commission (€)',
            'Net artiste (€)',
            'Remboursement (€)',
            'Date acompte',
            'Date solde',
            'Artiste',
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
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F5F0EB'],
                ],
            ],
        ];
    }
}
```

### 3C. Export récap mensuel

```php
// app/Exports/MonthlyRecapExport.php
namespace App\Exports;

use App\Services\AccountingExportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
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
            'Mois',
            'Nb prestations',
            'CA brut (€)',
            'Commissions (€)',
            'CA net (€)',
            'Acomptes (€)',
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
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F5F0EB'],
                ],
            ],
        ];
    }
}
```

### 3D. Export multi-feuilles (transactions + récap dans le même fichier)

```php
// app/Exports/ArtistFullExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

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
```

### 3E. FALLBACK CSV (si maatwebsite/excel n'est pas installé)

Si l'installation de maatwebsite/excel échoue, créer un helper CSV avec les streams Laravel natifs :

```php
// app/Services/CsvExportHelper.php
namespace App\Services;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportHelper
{
    /**
     * Générer un fichier CSV téléchargeable à partir d'une collection.
     */
    public static function download(Collection $data, array $headings, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($data, $headings) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // En-têtes
            fputcsv($handle, $headings, ';');

            // Données
            foreach ($data as $row) {
                fputcsv($handle, array_values(is_array($row) ? $row : $row->toArray()), ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
```

```bash
git add -A && git commit -m "feat(export): classes Export Excel/CSV (transactions, récap mensuel, multi-feuilles)"
```

---

## PHASE 4 — CONTROLLER EXPORT

Brancher les exports dans un controller dédié (ou enrichir l'AccountingController existant) :

```php
// app/Http/Controllers/ExportController.php
namespace App\Http\Controllers;

use App\Exports\ArtistTransactionsExport;
use App\Exports\ArtistFullExport;
use App\Exports\StudioTransactionsExport;
use App\Exports\MonthlyRecapExport;
use App\Services\AccountingExportService;
use App\Services\CsvExportHelper;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExportController extends Controller
{
    /**
     * Export des transactions de l'artiste connecté.
     */
    public function artistTransactions(Request $request)
    {
        $artisan = $this->getArtisan();
        abort_unless($artisan, 403);

        [$from, $to] = $this->parseDateRange($request);
        $format = $request->get('format', 'xlsx');

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
     * Export complet de l'artiste (transactions + récap mensuel).
     */
    public function artistFull(Request $request)
    {
        $artisan = $this->getArtisan();
        abort_unless($artisan, 403);

        [$from, $to] = $this->parseDateRange($request);
        $year = $request->get('year', now()->year);

        return Excel::download(
            new ArtistFullExport($artisan, (int) $year, $from, $to),
            'comptabilite-' . $year . '-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export du récap mensuel uniquement.
     */
    public function artistMonthlyRecap(Request $request)
    {
        $artisan = $this->getArtisan();
        abort_unless($artisan, 403);

        $year = $request->get('year', now()->year);
        $format = $request->get('format', 'xlsx');

        $filename = 'recap-mensuel-' . $year;

        if ($format === 'csv') {
            $data = app(AccountingExportService::class)->getMonthlyRecap($artisan, (int) $year);
            return CsvExportHelper::download($data, $this->recapHeadings(), $filename . '.csv');
        }

        return Excel::download(
            new MonthlyRecapExport($artisan, (int) $year),
            $filename . '.xlsx'
        );
    }

    /**
     * Export transactions studio.
     */
    public function studioTransactions(Request $request)
    {
        $user = auth()->user();
        $studio = $user->studio ?? $user->managedStudio ?? null;
        abort_unless($studio, 403);

        [$from, $to] = $this->parseDateRange($request);
        $format = $request->get('format', 'xlsx');

        $filename = 'transactions-studio-' . now()->format('Y-m-d');

        if ($format === 'csv') {
            $data = app(AccountingExportService::class)->getStudioTransactions($studio, $from, $to);
            return CsvExportHelper::download($data, array_merge($this->transactionHeadings(), ['Artiste']), $filename . '.csv');
        }

        return Excel::download(
            new StudioTransactionsExport($studio, $from, $to),
            $filename . '.xlsx'
        );
    }

    // --- Helpers ---

    private function getArtisan()
    {
        $user = auth()->user();
        return $user->tattooer ?? $user->piercer ?? null;
    }

    private function parseDateRange(Request $request): array
    {
        $from = $request->get('from') ? Carbon::parse($request->get('from'))->startOfDay() : null;
        $to = $request->get('to') ? Carbon::parse($request->get('to'))->endOfDay() : null;
        return [$from, $to];
    }

    private function transactionHeadings(): array
    {
        return ['Date', 'Référence', 'Client', 'Description', 'Statut', 'Montant total (€)', 'Acompte (€)', 'Solde (€)', 'Commission (€)', 'Net artiste (€)', 'Remboursement (€)', 'Date acompte', 'Date solde'];
    }

    private function recapHeadings(): array
    {
        return ['Mois', 'Nb prestations', 'CA brut (€)', 'Commissions (€)', 'CA net (€)', 'Acomptes (€)'];
    }
}
```

Ajouter les routes :

```php
// Dans routes/web.php — middleware auth

// Export artiste
Route::middleware(['auth'])->prefix('export')->name('export.')->group(function () {
    // Artiste (tattooer/pierceur)
    Route::get('/transactions', [ExportController::class, 'artistTransactions'])->name('artist.transactions');
    Route::get('/comptabilite', [ExportController::class, 'artistFull'])->name('artist.full');
    Route::get('/recap-mensuel', [ExportController::class, 'artistMonthlyRecap'])->name('artist.monthly');

    // Studio
    Route::get('/studio/transactions', [ExportController::class, 'studioTransactions'])->name('studio.transactions');
});
```

IMPORTANT : Vérifier que le TODO dans l'AccountingController existant est aussi résolu ou que les routes ne sont pas en conflit.

```bash
# Résoudre le TODO dans AccountingController si existant
grep -n "TODO\|todo\|export\|csv" app/Http/Controllers/AccountingController.php 2>/dev/null | head -5

# Si un TODO existe, soit le remplacer par un redirect vers les nouvelles routes,
# soit intégrer la logique directement dans AccountingController
```

```bash
git add -A && git commit -m "feat(export): ExportController + routes /export/* (artiste + studio)"
```

---

## PHASE 5 — BOUTONS DANS LES VUES

### 5A. Créer un composant Blade partial pour les boutons d'export

```blade
{{-- resources/views/partials/export-buttons.blade.php --}}
@props(['type' => 'artist', 'year' => now()->year])

<div class="flex flex-wrap items-center gap-3">
    @if ($type === 'artist')
        {{-- Export transactions --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gris-fonde text-titane hover:text-beige-peau border border-titane/20 hover:border-beige-peau/30 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter
            </button>
            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 mt-2 w-56 bg-gris-fonde border border-titane/20 rounded-lg shadow-lg z-50 py-1">
                <a href="{{ route('export.artist.transactions', ['format' => 'xlsx']) }}" class="block px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    📊 Transactions (Excel)
                </a>
                <a href="{{ route('export.artist.transactions', ['format' => 'csv']) }}" class="block px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    📄 Transactions (CSV)
                </a>
                <div class="border-t border-titane/10 my-1"></div>
                <a href="{{ route('export.artist.full', ['year' => $year]) }}" class="block px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    📋 Comptabilité {{ $year }} (Excel)
                </a>
                <a href="{{ route('export.artist.monthly', ['year' => $year, 'format' => 'csv']) }}" class="block px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    📅 Récap mensuel {{ $year }} (CSV)
                </a>
            </div>
        </div>
    @elseif ($type === 'studio')
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gris-fonde text-titane hover:text-beige-peau border border-titane/20 hover:border-beige-peau/30 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter Studio
            </button>
            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 mt-2 w-56 bg-gris-fonde border border-titane/20 rounded-lg shadow-lg z-50 py-1">
                <a href="{{ route('export.studio.transactions', ['format' => 'xlsx']) }}" class="block px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    📊 Transactions (Excel)
                </a>
                <a href="{{ route('export.studio.transactions', ['format' => 'csv']) }}" class="block px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    📄 Transactions (CSV)
                </a>
            </div>
        </div>
    @endif
</div>
```

### 5B. Intégrer les boutons dans les vues existantes

```bash
# Trouver les vues de paiement / dashboard / comptabilité
grep -rn "payment\|paiement\|comptabilite\|accounting\|finance\|transactions" resources/views/ --include="*.blade.php" -l | head -10

# Dashboard tattooer (là où pending_deposits est affiché)
grep -rn "pending_deposits\|dashboard\|revenus\|earnings" resources/views/tattooer/ --include="*.blade.php" | head -10

# Dashboard studio
find resources/views/studio -name "*dashboard*" -o -name "*stats*" -o -name "*payment*" | head -5
```

Pour CHAQUE vue identifiée (dashboard tattooer, page paiements, dashboard studio) :

```blade
{{-- Dans le header ou la section financière de la vue --}}
@include('partials.export-buttons', ['type' => 'artist', 'year' => now()->year])

{{-- Pour le studio --}}
@include('partials.export-buttons', ['type' => 'studio', 'year' => now()->year])
```

### 5C. Résoudre le TODO dans AccountingController

```bash
# Lire le TODO exact
grep -B 5 -A 10 "TODO\|todo\|export\|csv" app/Http/Controllers/AccountingController.php 2>/dev/null
```

Si le TODO existe, remplacer par un redirect ou intégrer la logique :

```php
// Option A : redirect vers les nouvelles routes
return redirect()->route('export.artist.transactions', ['format' => 'csv']);

// Option B : intégrer directement dans la méthode existante
// (utiliser AccountingExportService + CsvExportHelper)
```

```bash
git add -A && git commit -m "feat(export): boutons export dans les vues artiste et studio + résolution TODO AccountingController"
```

---

## PHASE 6 — EXPORT ADMIN (Filament)

Ajouter un export global dans le panel admin Filament :

```bash
# Vérifier les resources Filament existantes
find app/Filament -name "*BookingRequest*" -o -name "*Payment*" -o -name "*Transaction*" | head -5
```

Si un BookingRequestResource existe dans Filament, ajouter une action d'export :

```php
// Dans la resource Filament existante, ajouter une action de table
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArtistTransactionsExport;

// Dans la méthode table() ou getHeaderActions() :
Tables\Actions\Action::make('export_csv')
    ->label('Exporter CSV')
    ->icon('heroicon-o-arrow-down-tray')
    ->action(function () {
        // Export global de toutes les transactions
        $data = app(\App\Services\AccountingExportService::class)
            ->getArtistTransactions(null); // null = tous les artistes
        
        return \App\Services\CsvExportHelper::download(
            $data,
            ['Date', 'Référence', 'Client', 'Description', 'Statut', 'Montant total (€)', 'Acompte (€)', 'Solde (€)', 'Commission (€)', 'Net artiste (€)', 'Remboursement (€)', 'Date acompte', 'Date solde'],
            'export-global-' . now()->format('Y-m-d') . '.csv'
        );
    }),
```

OU ajouter une page Filament dédiée si l'export admin est plus complexe.

ALTERNATIVE plus simple : Ajouter un bouton d'export dans l'action header de la liste des BookingRequests dans Filament :

```php
// Dans BookingRequestResource::table()
->headerActions([
    Tables\Actions\Action::make('export')
        ->label('Export Excel')
        ->icon('heroicon-o-arrow-down-tray')
        ->url(route('export.artist.transactions', ['format' => 'xlsx']))
        ->openUrlInNewTab(),
])
```

```bash
git add -A && git commit -m "feat(export): bouton export admin Filament"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION EXPORT CSV/EXCEL ==="

# V1. Package installé
composer show maatwebsite/excel 2>&1 | head -3 || echo "maatwebsite/excel non installé — fallback CSV"

# V2. Service
ls app/Services/AccountingExportService.php && echo "Service OK"
ls app/Services/CsvExportHelper.php && echo "CSV Helper OK"

# V3. Exports
ls app/Exports/ 2>/dev/null

# V4. Controller
ls app/Http/Controllers/ExportController.php && echo "Controller OK"

# V5. Routes
php artisan route:list --name="export" --columns=method,uri,name 2>&1

# V6. Compilation
php artisan route:clear
php artisan view:clear
php artisan route:list 2>&1 | head -3

# V7. Boutons dans les vues
grep -rn "export-buttons\|export\." resources/views/ --include="*.blade.php" | grep -v "partials/export" | head -10

# V8. TODO AccountingController résolu
grep -c "TODO" app/Http/Controllers/AccountingController.php 2>/dev/null || echo "Pas d'AccountingController"

echo "=== EXPORT TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 révèle les vrais noms de colonnes financières
2. **Centimes vs euros** — VÉRIFIER en Phase 0 si les montants sont en centimes (diviser par 100) ou en euros
3. **Séparateur CSV = point-virgule (;)** — standard français, compatible Excel FR
4. **BOM UTF-8** — indispensable pour que Excel affiche les accents correctement
5. **Relations polymorphiques** — `bookable_type/bookable_id` pour les requêtes multi-artistes
6. **Autorisation stricte** — un artiste ne voit QUE ses transactions, un studio voit celles de ses artistes
7. **Si maatwebsite/excel échoue** → utiliser le fallback CsvExportHelper (pas de dépendance externe)
8. **Commit après chaque phase** (5-6 commits)
9. **Format des montants** — nombre_format avec virgule décimale (standard FR) : `1 234,56`
