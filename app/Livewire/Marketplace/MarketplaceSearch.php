<?php

namespace App\Livewire\Marketplace;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\Studio;
use Illuminate\Support\Facades\Cache;

class MarketplaceSearch extends Component
{
    use WithPagination;

    // Recherche
    public string $search    = '';
    public string $city      = '';

    // Filtre type
    public string $type      = 'all'; // all | tattooer | piercer | studio

    // Filtres artiste
    public array  $styles    = [];
    public array  $piercings = [];
    public string $minPrice  = '';
    public string $maxPrice  = '';
    public bool   $proOnly   = false;
    public bool   $certifiedOnly = false;

    // Tri
    public string $sortBy    = 'pro_first'; // pro_first | price_asc | newest

    // Pagination
    public int $perPage = 12;

    public function updatedSearch(): void        { $this->resetPage(); }
    public function updatedCity(): void          { $this->resetPage(); }
    public function updatedType(): void          { $this->resetPage(); $this->styles = []; $this->piercings = []; }
    public function updatedStyles(): void        { $this->resetPage(); }
    public function updatedPiercings(): void     { $this->resetPage(); }
    public function updatedMinPrice(): void      { $this->resetPage(); }
    public function updatedMaxPrice(): void      { $this->resetPage(); }
    public function updatedProOnly(): void       { $this->resetPage(); }
    public function updatedCertifiedOnly(): void { $this->resetPage(); }
    public function updatedSortBy(): void        { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->search        = '';
        $this->city          = '';
        $this->styles        = [];
        $this->piercings     = [];
        $this->minPrice      = '';
        $this->maxPrice      = '';
        $this->proOnly       = false;
        $this->certifiedOnly = false;
        $this->sortBy        = 'pro_first';
        $this->type          = 'all';
        $this->resetPage();
    }

    public function getAvailableStylesProperty(): array
    {
        return Cache::remember('marketplace.styles', 3600, function () {
            return Tattooer::marketplaceVisible()
                ->whereNotNull('styles')
                ->get(['styles', 'custom_styles'])
                ->flatMap(function ($t) {
                    $styles = is_array($t->styles) ? $t->styles : (json_decode($t->styles ?? '[]', true) ?? []);
                    $custom = is_array($t->custom_styles) ? $t->custom_styles : (json_decode($t->custom_styles ?? '[]', true) ?? []);
                    return array_merge($styles, $custom);
                })
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        });
    }

    public function getAvailablePiercingsProperty(): array
    {
        return Cache::remember('marketplace.piercings', 3600, function () {
            return Piercer::marketplaceVisible()
                ->whereNotNull('piercing_types')
                ->get(['piercing_types'])
                ->flatMap(function ($p) {
                    return is_array($p->piercing_types)
                        ? $p->piercing_types
                        : (json_decode($p->piercing_types ?? '[]', true) ?? []);
                })
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        });
    }

    public function getResultsProperty(): array
    {
        $results = collect();

        // ─── TATTOOERS ─────────────────────────────────────────────
        if (in_array($this->type, ['all', 'tattooer'])) {
            $styles   = $this->styles;
            $tattooers = Tattooer::marketplaceVisible()
                ->with(['user', 'reviews'])
                ->when($this->search, function ($q) {
                    $q->where(function ($sq) {
                        $sq->where('pseudo', 'like', "%{$this->search}%")
                           ->orWhere('name', 'like', "%{$this->search}%")
                           ->orWhere('bio', 'like', "%{$this->search}%")
                           ->orWhere('city', 'like', "%{$this->search}%");
                    });
                })
                ->when($this->city, function ($q) {
                    $q->where('city', 'like', "%{$this->city}%");
                })
                ->when($this->minPrice !== '', function ($q) {
                    $q->where('minimum_price', '>=', (float) $this->minPrice);
                })
                ->when($this->maxPrice !== '', function ($q) {
                    $q->where('minimum_price', '<=', (float) $this->maxPrice);
                })
                ->when($this->proOnly, function ($q) {
                    $q->where('is_subscribed', true)->where('current_plan', 'pro');
                })
                ->when($this->certifiedOnly, function ($q) {
                    $q->where('has_compliance_badge', true);
                })
                ->when(!empty($styles), function ($q) use ($styles) {
                    $q->where(function ($sq) use ($styles) {
                        foreach ($styles as $style) {
                            $sq->orWhereJsonContains('styles', $style)
                               ->orWhereJsonContains('custom_styles', $style);
                        }
                    });
                })
                ->get()
                ->map(function ($t) {
                    $t->_type = 'tattooer';
                    return $t;
                });

            $results = $results->concat($tattooers);
        }

        // ─── PIERCERS ──────────────────────────────────────────────
        if (in_array($this->type, ['all', 'piercer'])) {
            $piercings = $this->piercings;
            $piercers  = Piercer::marketplaceVisible()
                ->with(['user', 'reviews'])
                ->when($this->search, function ($q) {
                    $q->where(function ($sq) {
                        $sq->where('pseudo', 'like', "%{$this->search}%")
                           ->orWhere('name', 'like', "%{$this->search}%")
                           ->orWhere('bio', 'like', "%{$this->search}%")
                           ->orWhere('city', 'like', "%{$this->search}%");
                    });
                })
                ->when($this->city, function ($q) {
                    $q->where('city', 'like', "%{$this->city}%");
                })
                ->when($this->certifiedOnly, function ($q) {
                    $q->where('has_compliance_badge', true);
                })
                ->when(!empty($piercings), function ($q) use ($piercings) {
                    $q->where(function ($sq) use ($piercings) {
                        foreach ($piercings as $pType) {
                            $sq->orWhereJsonContains('piercing_types', $pType);
                        }
                    });
                })
                ->get()
                ->map(function ($p) {
                    $p->_type = 'piercer';
                    return $p;
                });

            $results = $results->concat($piercers);
        }

        // ─── STUDIOS ───────────────────────────────────────────────
        if (in_array($this->type, ['all', 'studio'])) {
            $studios = Studio::where('is_active', true)
                ->where('is_blocked', false)
                ->with(['user'])
                ->when($this->search, function ($q) {
                    $q->where(function ($sq) {
                        $sq->where('name', 'like', "%{$this->search}%")
                           ->orWhere('description', 'like', "%{$this->search}%")
                           ->orWhere('city', 'like', "%{$this->search}%");
                    });
                })
                ->when($this->city, function ($q) {
                    $q->where('city', 'like', "%{$this->city}%");
                })
                ->get()
                ->map(function ($s) {
                    $s->_type = 'studio';
                    return $s;
                });

            $results = $results->concat($studios);
        }

        // ─── TRI ───────────────────────────────────────────────────
        $results = match ($this->sortBy) {
            'price_asc' => $results->sortBy(fn ($a) => $a->minimum_price ?? 9999),
            'newest'    => $results->sortByDesc('created_at'),
            default     => $results->sortByDesc(function ($a) {
                if ($a->_type === 'studio') return 0;
                return method_exists($a, 'isPro') && $a->isPro() ? 1 : 0;
            }),
        };

        // ─── PAGINATION MANUELLE ───────────────────────────────────
        $page  = $this->getPage();
        $total = $results->count();
        $items = $results->slice(($page - 1) * $this->perPage, $this->perPage)->values();

        return [
            'items'       => $items,
            'total'       => $total,
            'currentPage' => $page,
            'lastPage'    => max(1, (int) ceil($total / $this->perPage)),
            'perPage'     => $this->perPage,
        ];
    }

    public function render()
    {
        return view('livewire.marketplace.marketplace-search', [
            'results'            => $this->results,
            'availableStyles'    => $this->availableStyles,
            'availablePiercings' => $this->availablePiercings,
        ]);
    }
}
