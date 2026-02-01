<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Tattooer;
use App\Models\Pierceur;
use App\Models\Appointment;
use App\Models\Studio;
use App\Models\StudioArtist;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $usersThisMonth = User::whereMonth('created_at', Carbon::now()->month)->count();
        $usersLastMonth = User::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $userGrowth = $usersLastMonth > 0 ? (($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100 : 0;

        $activeTattooers = Tattooer::whereHas('user', fn($q) => $q->where('status', 'active'))->count();
        $totalTattooers = Tattooer::count();
        $activePierceurs = Pierceur::whereHas('user', fn($q) => $q->where('status', 'active'))->count();
        $totalPierceurs = Pierceur::count();
        $totalActiveArtists = $activeTattooers + $activePierceurs;
        $totalArtists = $totalTattooers + $totalPierceurs;

        $appointmentsThisMonth = Appointment::whereMonth('start_datetime', Carbon::now()->month)->count();
        $appointmentsLastMonth = Appointment::whereMonth('start_datetime', Carbon::now()->subMonth()->month)->count();
        $appointmentGrowth = $appointmentsLastMonth > 0 ? (($appointmentsThisMonth - $appointmentsLastMonth) / $appointmentsLastMonth) * 100 : 0;

        $pendingVerifications = User::where('status', 'pending_verification')->count();

        // Stats Studios
        $activeStudios = Studio::where('is_active', true)->count();
        $totalStudios = Studio::count();
        $studiosThisMonth = Studio::whereMonth('created_at', Carbon::now()->month)->count();
        $studiosLastMonth = Studio::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $studioGrowth = $studiosLastMonth > 0 ? (($studiosThisMonth - $studiosLastMonth) / $studiosLastMonth) * 100 : 0;

        // Stats Studio Artists
        $activeStudioArtists = StudioArtist::where('is_active', true)->count();
        $totalStudioArtists = StudioArtist::count();
        $studioArtistsThisMonth = StudioArtist::whereMonth('created_at', Carbon::now()->month)->count();
        $studioArtistsLastMonth = StudioArtist::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $studioArtistGrowth = $studioArtistsLastMonth > 0 ? (($studioArtistsThisMonth - $studioArtistsLastMonth) / $studioArtistsLastMonth) * 100 : 0;

        return [
            Stat::make('Utilisateurs Totaux', $totalUsers)
                ->description($userGrowth >= 0 ? "+{$userGrowth}%" : "{$userGrowth}%")
                ->descriptionIcon($userGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($userGrowth >= 0 ? 'success' : 'danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Artistes Actifs', "{$totalActiveArtists}/{$totalArtists}")
                ->description(round(($totalActiveArtists / max($totalArtists, 1)) * 100) . '% actifs')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Studios Actifs', $activeStudios)
                ->description($studioGrowth >= 0 ? "+{$studioGrowth}%" : "{$studioGrowth}%")
                ->descriptionIcon($studioGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($studioGrowth >= 0 ? 'success' : 'danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Artistes Studio', $activeStudioArtists)
                ->description($studioArtistGrowth >= 0 ? "+{$studioArtistGrowth}%" : "{$studioArtistGrowth}%")
                ->descriptionIcon('heroicon-m-user-group')
                ->color($studioArtistGrowth >= 0 ? 'info' : 'warning')
                ->chart([3, 2, 5, 4, 6, 3, 7, 4]),

            Stat::make('RDV ce mois', $appointmentsThisMonth)
                ->description($appointmentGrowth >= 0 ? "+{$appointmentGrowth}%" : "{$appointmentGrowth}%")
                ->descriptionIcon($appointmentGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($appointmentGrowth >= 0 ? 'success' : 'danger')
                ->chart([3, 5, 8, 12, 7, 14, 9]),

            Stat::make('Validations en attente', $pendingVerifications)
                ->description('À vérifier')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingVerifications > 0 ? 'warning' : 'success')
                ->url('/admin/users?tableFilters=status%5Bvalue%5D%5B0%5D=pending_verification'),
        ];
    }
}
