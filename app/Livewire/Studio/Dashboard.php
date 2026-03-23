<?php

namespace App\Livewire\Studio;

use App\Models\Studio;
use App\Services\StudioStatsService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.studio')]
class Dashboard extends Component
{
    public ?Studio $studio = null;

    public function mount(): void
    {
        $this->studio = auth()->user()->studio;
        abort_unless($this->studio, 403, 'Profil studio non trouvé');
    }

    public function render()
    {
        $statsService = new StudioStatsService($this->studio);

        $stats               = $statsService->getDashboardStats();
        $artistStats         = $statsService->getArtistStats();
        $revenueChart        = $statsService->getMonthlyRevenueByArtist();
        $bookingsChart       = $statsService->getMonthlyBookings();
        $upcomingAppointments = $statsService->getUpcomingAppointments(8);

        return view('livewire.studio.dashboard', compact(
            'stats', 'artistStats', 'revenueChart', 'bookingsChart', 'upcomingAppointments'
        ));
    }
}
