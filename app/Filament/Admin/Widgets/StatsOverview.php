<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Tattooer;
use App\Models\Pierceur;
use App\Models\Appointment;
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

        $appointmentsThisMonth = Appointment::whereMonth('appointment_date', Carbon::now()->month)->count();
        $appointmentsLastMonth = Appointment::whereMonth('appointment_date', Carbon::now()->subMonth()->month)->count();
        $appointmentGrowth = $appointmentsLastMonth > 0 ? (($appointmentsThisMonth - $appointmentsLastMonth) / $appointmentsLastMonth) * 100 : 0;

        $pendingVerifications = User::where('status', 'pending_verification')->count();

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
