<?php

namespace App\Http\Controllers\Tattooer;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;

class ArtisanBaseController extends Controller
{
    /**
     * Retourne le profil artisan (Tattooer ou Piercer) de l'utilisateur connecté.
     * Rend le controller polymorphique pour tattooers ET pierceurs.
     */
    protected function artisan(): ?\Illuminate\Database\Eloquent\Model
    {
        return auth()->user()->artisan();
    }

    /**
     * Retourne les compteurs pendingCount et unreadCount pour le layout artiste.
     * Évite la duplication du même bloc dans chaque méthode du controller.
     */
    protected function getDashboardCounts(\Illuminate\Database\Eloquent\Model $artisan): array
    {
        $pendingCount = BookingRequest::where('bookable_id', $artisan->id)
            ->where('bookable_type', get_class($artisan))
            ->where('status', 'pending')
            ->count();

        $unreadCount = \App\Models\Conversation::whereHas('messages', function ($query) {
                $query->where(function ($q) {
                    if (auth()->user()->isTattooer() || auth()->user()->isPiercer()) {
                        $q->whereNull('read_by_tattooer_at');
                    } else {
                        $q->whereNull('read_by_client_at');
                    }
                })
                ->where('sender_id', '!=', auth()->id());
            })
            ->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->count();

        return compact('pendingCount', 'unreadCount');
    }

    protected function artisanType(): string
    {
        return auth()->user()->artisanType() ?? 'tattooer';
    }

    protected function routePrefix(): string
    {
        return $this->artisanType() === 'piercer' ? 'pierceur' : 'tattooer';
    }
}
