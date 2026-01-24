<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccountingTransaction;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccountingController extends Controller
{
    /**
     * Liste des transactions comptables
     */
    public function transactions(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $query = AccountingTransaction::query()->forTattooer($user->tattooer->id)
            ->with(['client.user:id,name,email', 'appointment']);

        // Filtres
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->between($request->date_from, $request->date_to);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(50);

        return response()->json($transactions);
    }

    /**
     * Créer une transaction manuelle
     */
    public function storeTransaction(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', AccountingTransaction::TYPES),
            'category' => 'required|in:' . implode(',', AccountingTransaction::CATEGORIES),
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:transaction_date',
            'payment_method' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'tax_rate' => 'numeric|min:0|max:100',
        ]);

        $transaction = AccountingTransaction::create([
            'user_id' => $user->id,
            'reference' => AccountingTransaction::generateReference(
                $validated['type'],
                $validated['category']
            ),
            'tax_amount' => $validated['amount'] * ($validated['tax_rate'] / 100),
            ...$validated,
        ]);

        return response()->json($transaction, 201);
    }

    /**
     * Tableau de bord comptable
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Période par défaut : ce mois-ci
        $startDate = $request->get('date_from', now()->startOfMonth()->toDateString());
        $endDate = $request->get('date_to', now()->endOfMonth()->toDateString());

        $balance = AccountingTransaction::getBalance(
            $user->tattooer->id,
            null,
            \Carbon\Carbon::parse($startDate),
            \Carbon\Carbon::parse($endDate)
        );

        // Transactions récentes
        $recentTransactions = AccountingTransaction::query()
            ->forTattooer($user->tattooer->id)
            ->with(['client.user:id,name'])
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();

        // Factures impayées
        $unpaidInvoices = Invoice::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['draft', 'sent'])
            ->where('due_date', '<', now())
            ->get();

        // Revenus par catégorie
        $incomeByCategory = AccountingTransaction::query()
            ->forTattooer($user->tattooer->id)
            ->income()
            ->between($startDate, $endDate)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        // Dépenses par catégorie
        $expenseByCategory = AccountingTransaction::query()
            ->forTattooer($user->tattooer->id)
            ->expense()
            ->between($startDate, $endDate)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return response()->json([
            'balance' => $balance,
            'recent_transactions' => $recentTransactions,
            'unpaid_invoices' => $unpaidInvoices,
            'income_by_category' => $incomeByCategory,
            'expense_by_category' => $expenseByCategory,
        ]);
    }

    /**
     * Rapport financier
     */
    public function report(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'report_type' => 'required|in:monthly,quarterly,yearly',
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);

        // Balance globale
        $balance = AccountingTransaction::getBalance(
            $user->tattooer->id,
            null,
            $startDate,
            $endDate
        );

        // Évolution mensuelle
        $monthlyEvolution = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $monthBalance = AccountingTransaction::getBalance(
                $user->tattooer->id,
                null,
                $current->copy()->startOfMonth(),
                $current->copy()->endOfMonth()
            );

            $monthlyEvolution[] = [
                'month' => $current->format('Y-m'),
                'month_name' => $current->format('F Y'),
                'income' => $monthBalance['total_income'],
                'expense' => $monthBalance['total_expense'],
                'net' => $monthBalance['net_balance'],
            ];

            $current->addMonth();
        }

        // Top clients
        $topClients = AccountingTransaction::query()
            ->forTattooer($user->tattooer->id)
            ->income()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereNotNull('client_id')
            ->with('client.user:id,name')
            ->selectRaw('client_id, SUM(amount) as total_spent, COUNT(*) as transaction_count')
            ->groupBy('client_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'period' => [
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'report_type' => $validated['report_type'],
            ],
            'summary' => $balance,
            'monthly_evolution' => $monthlyEvolution,
            'top_clients' => $topClients,
        ]);
    }

    /**
     * Statistiques des rendez-vous
     */
    public function appointmentStats(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $startDate = $request->get('date_from', now()->startOfMonth()->toDateString());
        $endDate = $request->get('date_to', now()->endOfMonth()->toDateString());

        $appointments = \App\Models\Appointment::query()
            ->forTattooer($user->tattooer->id)
            ->whereBetween('start_time', [$startDate, $endDate]);

        return response()->json([
            'total_appointments' => $appointments->count(),
            'completed_appointments' => $appointments->where('status', 'completed')->count(),
            'cancelled_appointments' => $appointments->where('status', 'cancelled')->count(),
            'total_revenue' => $appointments->where('status', 'completed')->sum('total_price'),
            'average_appointment_value' => $appointments->where('status', 'completed')->avg('total_price'),
            'no_shows' => $appointments->where('status', 'client_no_show')->count(),
        ]);
    }

    /**
     * Marquer une transaction comme payée
     */
    public function markAsPaid(Request $request, AccountingTransaction $transaction)
    {
        Gate::authorize('update', $transaction);

        $validated = $request->validate([
            'payment_method' => 'required|string|max:100',
        ]);

        $transaction->markAsPaid($validated['payment_method']);

        return response()->json($transaction);
    }

    /**
     * Exporter les données comptables
     */
    public function export(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'format' => 'required|in:csv,xlsx',
        ]);

        // TODO: Implémenter l'export CSV/Excel
        return response()->json([
            'message' => 'Export à implémenter',
            'params' => $validated,
        ]);
    }
}
