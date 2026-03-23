<?php

namespace App\Services;

use App\Models\Studio;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Conversation;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StudioStatsService
{
    protected Studio $studio;
    protected array $tattooerIds;
    protected array $piercerIds;

    public function __construct(Studio $studio)
    {
        $this->studio     = $studio;
        $this->tattooerIds = $studio->tattooers()->pluck('id')->toArray();
        $this->piercerIds  = $studio->piercers()->pluck('id')->toArray();
    }

    /**
     * Query de base pour tous les bookings du studio.
     */
    protected function baseBookingQuery()
    {
        return BookingRequest::where(function ($q) {
            $q->where(function ($sub) {
                $sub->where('bookable_type', 'App\\Models\\Tattooer')
                    ->whereIn('bookable_id', $this->tattooerIds);
            })->orWhere(function ($sub) {
                $sub->where('bookable_type', 'App\\Models\\Piercer')
                    ->whereIn('bookable_id', $this->piercerIds);
            });
        });
    }

    /**
     * Bookings d'un client spécifique du studio (accès public).
     */
    public function getClientBookings(int $clientId): Collection
    {
        return $this->baseBookingQuery()
            ->where('client_id', $clientId)
            ->with(['bookable.user'])
            ->latest()
            ->get();
    }

    /**
     * Stats globales pour le dashboard.
     */
    public function getDashboardStats(): array
    {
        $bookings = $this->baseBookingQuery();

        $now               = now();
        $startOfMonth      = $now->copy()->startOfMonth();
        $startOfLastMonth  = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth    = $now->copy()->subMonth()->endOfMonth();
        $startOfWeek       = $now->copy()->startOfWeek();
        $startOfLastWeek   = $now->copy()->subWeek()->startOfWeek();
        $endOfLastWeek     = $now->copy()->subWeek()->endOfWeek();

        $revenueStatuses = ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline', 'deposit_paid'];

        $revenueThisMonth = (clone $bookings)
            ->whereIn('status', $revenueStatuses)
            ->where('updated_at', '>=', $startOfMonth)
            ->sum('total_price');

        $revenueLastMonth = (clone $bookings)
            ->whereIn('status', $revenueStatuses)
            ->whereBetween('updated_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_price');

        $bookingsThisWeek = (clone $bookings)->where('created_at', '>=', $startOfWeek)->count();
        $bookingsLastWeek = (clone $bookings)->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();
        $bookingsThisMonth = (clone $bookings)->where('created_at', '>=', $startOfMonth)->count();
        $bookingsLastMonth = (clone $bookings)->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        return [
            'total_revenue' => round((float) (clone $bookings)
                ->whereIn('status', $revenueStatuses)
                ->sum('total_price'), 2),
            'revenue_this_month' => round((float) $revenueThisMonth, 2),
            'revenue_last_month' => round((float) $revenueLastMonth, 2),
            'revenue_change_pct' => $revenueLastMonth > 0
                ? round(($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth * 100, 1)
                : ($revenueThisMonth > 0 ? 100 : 0),

            'bookings_this_month' => $bookingsThisMonth,
            'bookings_last_month' => $bookingsLastMonth,
            'bookings_month_change_pct' => $bookingsLastMonth > 0
                ? round(($bookingsThisMonth - $bookingsLastMonth) / $bookingsLastMonth * 100, 1)
                : ($bookingsThisMonth > 0 ? 100 : 0),

            'bookings_this_week' => $bookingsThisWeek,
            'bookings_last_week' => $bookingsLastWeek,
            'bookings_week_change_pct' => $bookingsLastWeek > 0
                ? round(($bookingsThisWeek - $bookingsLastWeek) / $bookingsLastWeek * 100, 1)
                : ($bookingsThisWeek > 0 ? 100 : 0),

            'pending_bookings'   => (clone $bookings)->where('status', 'pending')->count(),
            'completed_bookings' => (clone $bookings)->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'])->count(),
            'total_bookings'     => (clone $bookings)->count(),
            'total_deposits'     => round((float) (clone $bookings)->whereNotNull('deposit_paid_at')->sum('total_deposit_amount'), 2),
            'total_clients'      => $this->getUniqueClientCount(),
            'active_artists'     => $this->studio->artists()->where('is_active', true)->count(),
            'total_artists'      => $this->studio->artists()->count(),
        ];
    }

    /**
     * Stats par artiste.
     */
    public function getArtistStats(): Collection
    {
        $colors = ['#D4B59E', '#E8A87C', '#85CDCA', '#C38D9E', '#41B3A3', '#F4D35E', '#EE964B', '#F95738'];

        return $this->studio->artists()->with('user')->get()
            ->map(function ($studioArtist, $index) use ($colors) {
                $user = $studioArtist->user;
                if (!$user) return null;

                $artisan = $user->artisan();
                if (!$artisan) return null;

                $bookings = BookingRequest::where('bookable_type', get_class($artisan))
                    ->where('bookable_id', $artisan->id);

                $now           = now();
                $thisMonthBase = (clone $bookings)->where('created_at', '>=', $now->copy()->startOfMonth());
                $lastMonthBase = (clone $bookings)->whereBetween('created_at', [
                    $now->copy()->subMonth()->startOfMonth(),
                    $now->copy()->subMonth()->endOfMonth(),
                ]);

                $revenueStatuses = ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline', 'deposit_paid'];

                $bookingsThisMonth = (clone $thisMonthBase)->count();
                $bookingsLastMonth = (clone $lastMonthBase)->count();
                $revenueThisMonth  = (clone $thisMonthBase)->whereIn('status', $revenueStatuses)->sum('total_price');
                $revenueLastMonth  = (clone $lastMonthBase)->whereIn('status', $revenueStatuses)->sum('total_price');

                return [
                    'id'          => $artisan->id,
                    'user_id'     => $user->id,
                    'name'        => $user->name,
                    'pseudo'      => $artisan->pseudo ?? '',
                    'type'        => $artisan instanceof \App\Models\Tattooer ? 'Tatoueur' : 'Pierceur',
                    'is_active'   => $studioArtist->is_active,
                    'color'       => $colors[$index % count($colors)],

                    'total_bookings'       => (clone $bookings)->count(),
                    'completed'            => (clone $bookings)->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'])->count(),
                    'pending'              => (clone $bookings)->where('status', 'pending')->count(),
                    'cancelled'            => (clone $bookings)->whereIn('status', ['cancelled', 'rejected'])->count(),

                    'bookings_this_month'  => $bookingsThisMonth,
                    'bookings_change_pct'  => $bookingsLastMonth > 0
                        ? round(($bookingsThisMonth - $bookingsLastMonth) / $bookingsLastMonth * 100, 1)
                        : ($bookingsThisMonth > 0 ? 100 : 0),

                    'total_revenue'        => round((float) (clone $bookings)->whereIn('status', $revenueStatuses)->sum('total_price'), 2),
                    'revenue_this_month'   => round((float) $revenueThisMonth, 2),
                    'revenue_change_pct'   => $revenueLastMonth > 0
                        ? round(($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth * 100, 1)
                        : ($revenueThisMonth > 0 ? 100 : 0),

                    'avg_rating'        => round((float) ($artisan->reviews()->avg('rating') ?? 0), 1),
                    'reviews_count'     => $artisan->reviews()->count(),
                    'stripe_connected'  => $artisan->isStripeConnected(),
                    'clients_count'     => (clone $bookings)->distinct('client_id')->count('client_id'),
                ];
            })
            ->filter()
            ->values();
    }

    /**
     * Revenus mensuels par artiste (6 derniers mois) — pour le graphique multi-séries.
     */
    public function getMonthlyRevenueByArtist(int $months = 6): array
    {
        $artists  = $this->getArtistStats();
        $labels   = [];
        $datasets = [];

        $revenueStatuses = ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline', 'deposit_paid'];

        for ($i = $months - 1; $i >= 0; $i--) {
            $labels[] = now()->subMonths($i)->translatedFormat('M Y');
        }

        foreach ($artists as $artist) {
            $data = [];
            for ($i = $months - 1; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $revenue = BookingRequest::where('bookable_type',
                        $artist['type'] === 'Tatoueur' ? 'App\\Models\\Tattooer' : 'App\\Models\\Piercer')
                    ->where('bookable_id', $artist['id'])
                    ->whereIn('status', $revenueStatuses)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_price');

                $data[] = round((float) $revenue, 2);
            }

            $datasets[] = [
                'label' => $artist['pseudo'] ?: $artist['name'],
                'data'  => $data,
                'color' => $artist['color'],
            ];
        }

        return ['labels' => $labels, 'datasets' => $datasets];
    }

    /**
     * RDV par mois (6 derniers mois) — global studio.
     */
    public function getMonthlyBookings(int $months = 6): array
    {
        $result = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = $this->baseBookingQuery()
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $result[] = [
                'month' => $month->translatedFormat('M Y'),
                'count' => $count,
            ];
        }
        return $result;
    }

    /**
     * Nombre de clients uniques du studio.
     */
    public function getUniqueClientCount(): int
    {
        return $this->baseBookingQuery()
            ->whereNotNull('client_id')
            ->distinct('client_id')
            ->count('client_id');
    }

    /**
     * Liste des clients du studio (via les bookings des artistes).
     */
    public function getStudioClients(): Collection
    {
        $clientIds = $this->baseBookingQuery()
            ->whereNotNull('client_id')
            ->distinct()
            ->pluck('client_id');

        return Client::whereIn('id', $clientIds)
            ->with('user')
            ->get()
            ->map(function ($client) {
                $bookings = $this->baseBookingQuery()->where('client_id', $client->id);

                return [
                    'id'              => $client->id,
                    'name'            => $client->user?->name ?? 'Client',
                    'email'           => $client->user?->email ?? '',
                    'phone'           => $client->phone ?? '',
                    'total_bookings'  => (clone $bookings)->count(),
                    'completed'       => (clone $bookings)->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'])->count(),
                    'total_spent'     => round((float) (clone $bookings)
                        ->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline', 'deposit_paid'])
                        ->sum('total_price'), 2),
                    'last_visit'      => (clone $bookings)->latest()->first()?->created_at,
                ];
            });
    }

    /**
     * Conversations des artistes du studio (lecture seule).
     */
    public function getArtistConversations(): Collection
    {
        $artistUserIds = $this->studio->artists()->pluck('user_id')->filter()->toArray();

        return Conversation::whereHas('participants', function ($q) use ($artistUserIds) {
                $q->whereIn('users.id', $artistUserIds);
            })
            ->with([
                'participants',
                'messages' => fn ($q) => $q->latest()->limit(1),
            ])
            ->withCount('messages')
            ->latest('updated_at')
            ->get();
    }

    /**
     * RDV à venir (planning).
     */
    public function getUpcomingAppointments(int $limit = 8): Collection
    {
        return $this->baseBookingQuery()
            ->whereIn('status', ['accepted', 'deposit_paid', 'date_confirmed', 'design_sent'])
            ->where(function ($q) {
                $q->whereNotNull('confirmed_date')
                  ->orWhereNotNull('appointment_datetime');
            })
            ->with(['client', 'bookable.user'])
            ->orderByRaw('COALESCE(confirmed_date, DATE(appointment_datetime)) ASC')
            ->take($limit)
            ->get();
    }
}
