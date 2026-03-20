<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Complaint;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class ComplaintsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return Cache::remember('admin.widget.complaints', 300, function () {
        $pendingComplaints = Complaint::where('status', 'pending')->count();
        $resolvedComplaints = Complaint::where('status', 'resolved')->count();
        $totalComplaints = Complaint::count();

        $complaintsThisMonth = Complaint::whereMonth('created_at', Carbon::now()->month)->count();
        $complaintsLastMonth = Complaint::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $complaintGrowth = $complaintsLastMonth > 0 ? (($complaintsThisMonth - $complaintsLastMonth) / $complaintsLastMonth) * 100 : 0;

        return [
            Stat::make('Réclamations en attente', $pendingComplaints)
                ->description('Nécessitent une action')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($pendingComplaints > 0 ? 'danger' : 'success')
                ->url('/admin/complaints?tableFilters=status%5Bvalue%5D%5B0%5D=pending'),

            Stat::make('Réclamations résolues', $resolvedComplaints)
                ->description('Ce mois-ci')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total réclamations', $totalComplaints)
                ->description($complaintGrowth >= 0 ? "+{$complaintGrowth}%" : "{$complaintGrowth}%")
                ->descriptionIcon($complaintGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($complaintGrowth >= 0 ? 'warning' : 'success'),
        ];
        }); // end Cache::remember
    }
}
