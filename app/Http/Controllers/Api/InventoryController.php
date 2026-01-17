<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InventoryController extends Controller
{
    /**
     * Liste des articles en stock
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $query = InventoryItem::query()->forTattooer($user->tattooer->id)->active();

        // Filtres
        if ($request->has('category')) {
            $query->category($request->category);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%")
                  ->orWhere('brand', 'like', "%{$request->search}%");
            });
        }

        // Alertes stock
        if ($request->has('low_stock')) {
            $query->lowStock();
        }

        if ($request->has('out_of_stock')) {
            $query->outOfStock();
        }

        $items = $query->orderBy('name')->paginate(20);

        return response()->json($items);
    }

    /**
     * Créer un article en stock
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:' . implode(',', InventoryItem::CATEGORIES),
            'brand' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'current_stock' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'max_stock_level' => 'required|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'unit_type' => 'required|in:' . implode(',', InventoryItem::UNIT_TYPES),

            // Spécifique aux encres
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:50',
            'is_vegan' => 'boolean',
            'expiration_date' => 'nullable|date|after:today',

            // Spécifique aux aiguilles
            'needle_type' => 'nullable|string|max:100',
            'needle_size' => 'nullable|string|max:50',

            'notes' => 'nullable|string',
        ]);

        // Générer un SKU si non fourni
        if (!isset($validated['sku'])) {
            $validated['sku'] = InventoryItem::generateSku($validated['name'], $validated['category']);
        }

        $item = InventoryItem::create([
            'tattooer_id' => $user->tattooer->id,
            ...$validated,
        ]);

        // Créer le mouvement initial
        if ($validated['current_stock'] > 0) {
            $item->stockIn($validated['current_stock'], 'initial_stock', 'Stock initial');
        }

        return response()->json($item, 201);
    }

    /**
     * Afficher un article
     */
    public function show(InventoryItem $item)
    {
        Gate::authorize('view', $item);

        $item->load(['movements' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(50);
        }]);

        return response()->json([
            'item' => $item,
            'stats' => $item->getStockStats(),
        ]);
    }

    /**
     * Mettre à jour un article
     */
    public function update(Request $request, InventoryItem $item)
    {
        Gate::authorize('update', $item);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'category' => 'in:' . implode(',', InventoryItem::CATEGORIES),
            'brand' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'min_stock_level' => 'integer|min:0',
            'max_stock_level' => 'integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'unit_type' => 'in:' . implode(',', InventoryItem::UNIT_TYPES),
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:50',
            'is_vegan' => 'boolean',
            'expiration_date' => 'nullable|date|after:today',
            'needle_type' => 'nullable|string|max:100',
            'needle_size' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $item->update($validated);

        return response()->json($item);
    }

    /**
     * Mouvement de stock
     */
    public function movement(Request $request, InventoryItem $item)
    {
        Gate::authorize('update', $item);

        $validated = $request->validate([
            'movement_type' => 'required|in:' . implode(',', InventoryMovement::TYPES),
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|in:' . implode(',', InventoryMovement::REASONS),
            'notes' => 'nullable|string',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        try {
            if ($validated['movement_type'] === InventoryMovement::TYPE_IN) {
                $movement = $item->stockIn(
                    $validated['quantity'],
                    $validated['reason'],
                    $validated['notes'] ?? null
                );
            } else {
                $movement = $item->stockOut(
                    $validated['quantity'],
                    $validated['reason'],
                    $validated['notes'] ?? null,
                    $validated['appointment_id'] ?? null
                );
            }

            return response()->json($movement, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Ajuster le stock
     */
    public function adjustStock(Request $request, InventoryItem $item)
    {
        Gate::authorize('update', $item);

        $validated = $request->validate([
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            $movement = $item->adjustStock(
                $validated['new_quantity'],
                $validated['reason'],
                $validated['notes'] ?? null
            );

            return response()->json($movement, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Historique des mouvements
     */
    public function movements(Request $request, InventoryItem $item)
    {
        Gate::authorize('view', $item);

        $query = $item->movements()->with(['appointment']);

        // Filtres
        if ($request->has('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(50);

        return response()->json($movements);
    }

    /**
     * Statistiques du stock
     */
    public function statistics(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $items = InventoryItem::query()->forTattooer($user->tattooer->id)->active();

        return response()->json([
            'total_items' => $items->count(),
            'low_stock_items' => $items->lowStock()->count(),
            'out_of_stock_items' => $items->outOfStock()->count(),
            'expiring_soon_items' => $items->expiringSoon()->count(),
            'total_stock_value' => $items->get()->sum(function ($item) {
                return $item->getTotalValue();
            }),
            'by_category' => $items->get()->groupBy('category')->map(function ($categoryItems) {
                return [
                    'count' => $categoryItems->count(),
                    'total_value' => $categoryItems->sum(function ($item) {
                        return $item->getTotalValue();
                    }),
                ];
            }),
        ]);
    }

    /**
     * Alertes de stock
     */
    public function alerts(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $items = InventoryItem::query()->forTattooer($user->tattooer->id)->active();

        $alerts = [
            'low_stock' => $items->lowStock()->get(),
            'out_of_stock' => $items->outOfStock()->get(),
            'expiring_soon' => $items->expiringSoon()->get(),
        ];

        return response()->json($alerts);
    }
}
