<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio;

class StudioDashboardController extends Controller
{
    /**
     * Récupère le studio que l'utilisateur connecté POSSÈDE.
     */
    private function studio(): Studio
    {
        $studio = auth()->user()->studio;
        abort_unless($studio, 403, 'Profil studio non trouvé');
        return $studio;
    }

    public function dashboard()
    {
        $studio = $this->studio();
        $artists = $studio->studioArtists()->with('user')->where('is_active', true)->get();

        // Récupérer les IDs des artisans du studio
        $artistUserIds = $artists->pluck('user_id')->filter();
        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        // Requête de base pour les demandes du studio
        $bookingBase = \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        });

        $pendingCount   = (clone $bookingBase)->where('status', 'pending')->count();
        $confirmedCount = (clone $bookingBase)->whereIn('status', ['accepted', 'deposit_paid', 'date_confirmed'])->count();
        $completedCount = (clone $bookingBase)->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'])
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        // Chiffre d'affaires du mois (acomptes encaissés — total_deposit_amount en euros)
        $monthlyRevenue = (clone $bookingBase)
            ->whereNotNull('deposit_paid_at')
            ->whereMonth('deposit_paid_at', now()->month)
            ->whereYear('deposit_paid_at', now()->year)
            ->sum('total_deposit_amount');

        // 5 dernières demandes en attente
        $latestRequests = (clone $bookingBase)
            ->with(['bookable.user', 'client'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('studio.dashboard', [
            'studio'         => $studio,
            'artists'        => $artists,
            'artistCount'    => $artists->count(),
            'monthlyPrice'   => $studio->monthlyPrice(),
            'totalArtists'   => $artists->count(),
            'activeArtists'  => $artists->count(),
            'totalRevenue'   => 0,
            // Nouveaux compteurs
            'pendingCount'   => $pendingCount,
            'confirmedCount' => $confirmedCount,
            'completedCount' => $completedCount,
            'monthlyRevenue' => $monthlyRevenue,
            'latestRequests' => $latestRequests,
        ]);
    }

    public function profile()
    {
        return view('studio.profile-edit', [
            'studio' => $this->studio(),
        ]);
    }

    public function publicProfile(string $slug)
    {
        $studio = \App\Models\Studio::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $artists = $studio->studioArtists()->with('user')->where('is_active', true)->get();

        return view('studio.public-profile', [
            'studio'  => $studio,
            'artists' => $artists,
        ]);
    }

    public function stats()
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        // Requête de base
        $base = \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
            });
        });

        $totalRequests    = (clone $base)->count();
        $pendingRequests  = (clone $base)->where('status', 'pending')->count();
        $completedAll     = (clone $base)->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'])->count();
        $cancelledAll     = (clone $base)->whereIn('status', ['cancelled', 'rejected', 'no_show'])->count();

        // Revenus mensuels (6 derniers mois) — acomptes encaissés (total_deposit_amount en euros)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = (clone $base)
                ->whereNotNull('deposit_paid_at')
                ->whereMonth('deposit_paid_at', $month->month)
                ->whereYear('deposit_paid_at', $month->year)
                ->sum('total_deposit_amount');
            $monthlyRevenue[] = [
                'label'   => $month->format('M Y'),
                'revenue' => round((float) $revenue, 2),
            ];
        }

        // Stats par artiste
        $artistsStats = $studio->studioArtists()
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->map(function ($sa) use ($tattooerIds, $piercerIds) {
                if (!$sa->user_id) return null;

                $artistTattooerIds = \App\Models\Tattooer::where('user_id', $sa->user_id)->pluck('id');
                $artistPiercerIds  = \App\Models\Piercer::where('user_id', $sa->user_id)->pluck('id');

                $artistBase = \App\Models\BookingRequest::where(function ($q) use ($artistTattooerIds, $artistPiercerIds) {
                    $q->where(function ($q2) use ($artistTattooerIds) {
                        $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $artistTattooerIds);
                    })->orWhere(function ($q2) use ($artistPiercerIds) {
                        $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $artistPiercerIds);
                    });
                });

                return [
                    'name'       => $sa->artist_name ?: $sa->user?->name ?? 'Artiste',
                    'type'       => $sa->artisan_type,
                    'total'      => (clone $artistBase)->count(),
                    'pending'    => (clone $artistBase)->where('status', 'pending')->count(),
                    'completed'  => (clone $artistBase)->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'])->count(),
                    'revenue'    => round((float) (clone $artistBase)->whereNotNull('deposit_paid_at')->sum('total_deposit_amount'), 2),
                ];
            })
            ->filter()
            ->values();

        return view('studio.stats', compact(
            'studio',
            'totalRequests',
            'pendingRequests',
            'completedAll',
            'cancelledAll',
            'monthlyRevenue',
            'artistsStats'
        ));
    }

    public function planning()
    {
        $studio = $this->studio();
        $artists = $studio->studioArtists()->with('user')->where('is_active', true)->get();

        return view('studio.planning', [
            'studio'  => $studio,
            'artists' => $artists,
        ]);
    }

    /**
     * JSON : événements du planning pour FullCalendar
     */
    public function planningEvents(\Illuminate\Http\Request $request)
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        $bookings = \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        })
        ->where(function ($q) {
            $q->whereNotNull('confirmed_date')
              ->orWhereNotNull('appointment_datetime');
        })
        ->whereNotIn('status', ['cancelled', 'rejected', 'expired', 'no_show'])
        ->with(['bookable.user', 'client'])
        ->get();

        // Couleurs par artiste (hash simple)
        $colors = ['#8B7355', '#6B9E78', '#7B8FA1', '#A07850', '#9E6B6B', '#6B7F9E'];

        $events = $bookings->map(function ($booking) use ($colors) {
            $artistName = $booking->bookable?->user?->name ?? 'Artiste';
            $colorIndex = crc32($artistName) % count($colors);
            $color = $colors[abs($colorIndex)];

            // Utiliser confirmed_date en priorité, sinon appointment_datetime
            if ($booking->confirmed_date) {
                $date = $booking->confirmed_date instanceof \Carbon\Carbon
                    ? $booking->confirmed_date->toDateString()
                    : substr($booking->confirmed_date, 0, 10);

                $start = $booking->scheduled_start_time
                    ? $date . 'T' . $booking->scheduled_start_time
                    : $date;

                $end = $booking->scheduled_end_time
                    ? $date . 'T' . $booking->scheduled_end_time
                    : null;
            } else {
                // Fallback sur appointment_datetime
                $appt = $booking->appointment_datetime instanceof \Carbon\Carbon
                    ? $booking->appointment_datetime
                    : \Carbon\Carbon::parse($booking->appointment_datetime);

                $start = $appt->toIso8601String();
                $end = $booking->appointment_duration_minutes
                    ? $appt->copy()->addMinutes($booking->appointment_duration_minutes)->toIso8601String()
                    : $appt->copy()->addHours(2)->toIso8601String();
            }

            $clientName = trim(($booking->client?->first_name ?? '') . ' ' . ($booking->client?->last_name ?? ''));

            return [
                'id'              => $booking->id,
                'title'           => $clientName . ' → ' . $artistName,
                'start'           => $start,
                'end'             => $end,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'textColor'       => '#FFF8F0',
                'url'             => route('studio.demandes.show', $booking),
                'extendedProps'   => [
                    'artist'  => $artistName,
                    'client'  => $clientName,
                    'status'  => is_object($booking->status) ? $booking->status->value : $booking->status,
                    'type'    => $booking->bookable instanceof \App\Models\Piercer ? 'piercing' : 'tatouage',
                ],
            ];
        });

        return response()->json($events->values());
    }
}
