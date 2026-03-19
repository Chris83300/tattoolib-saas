<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\BookingRequest;

class TattooerDashboardController extends ArtisanBaseController
{
    /**
     * Tableau de bord du tattooer
     */
    public function dashboard()
    {
        $tattooer = $this->artisan();

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Service pour stats (1-2 requêtes au lieu de 5+)
        $statsService = app(\App\Services\TattooerStatsService::class);
        $stats = $statsService->getDashboardStats($tattooer);

        // Revenus du mois avec commission (pour les plans starter)
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $monthlyEarnings = $statsService->getMonthlyEarningsWithCommission($tattooer, $currentYear, $currentMonth);

        // Demandes récentes
        $recentRequests = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->with(['client.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Rendez-vous à venir
        $upcomingAppointments = \App\Models\Appointment::query()
            ->forBookable($tattooer)
            ->upcoming()
            ->with(['client.user', 'bookingRequest.client.user'])
            ->take(5)
            ->get();

        // Activité récente
        $recentActivity = [
            'new_requests' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', get_class($tattooer))
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),

            'completed_appointments' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', get_class($tattooer))
                ->where('status', 'completed')
                ->where('updated_at', '>=', now()->subDays(7))
                ->count(),
        ];

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.dashboard', compact('tattooer', 'stats', 'monthlyEarnings', 'recentRequests', 'upcomingAppointments', 'recentActivity', 'pendingCount', 'unreadCount'));
    }

    /**
     * Profil public du tattooer (vue interne)
     */
    public function profile()
    {
        $tattooer = $this->artisan();

        if (!$tattooer) {
            return redirect()->route('register.' . $this->artisanType())
                ->with('error', 'Veuillez compléter votre profil tatoueur pour accéder à cette page.');
        }

        $cacheService = app(\App\Services\CacheService::class);

        // Charger données depuis cache
        $portfolio = $cacheService->getPortfolio($tattooer);
        $workingHours = $cacheService->getWorkingHours($tattooer);
        $stats = $cacheService->getDashboardStats($tattooer);

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.profile', compact(
            'tattooer',
            'portfolio',
            'workingHours',
            'stats',
            'pendingCount',
            'unreadCount'
        ));
    }

    /**
     * Afficher le formulaire de création de client manuel
     */
    public function upgrade()
    {
        $tattooer = $this->artisan();

        if ($tattooer->isPro()) {
            return redirect()->route($this->routePrefix() . '.profile')
                ->with('info', 'Vous êtes déjà abonné au plan PRO.');
        }

        return view('tattooer.upgrade');
    }

    /**
     * Page des plans d'abonnement (pricing)
     */
    public function pricing()
    {
        return redirect()->route($this->routePrefix() . '.subscription.plans');
    }
}
