<?php

namespace App\Livewire\Studio;

use Livewire\Component;
use App\Models\Studio;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\BookingRequest;

class Messages extends Component
{
    public function render()
    {
        $studio = auth()->user()?->studio;

        $recentConversations = collect();

        if ($studio) {
            $artistUserIds = $studio->studioArtists()
                ->where('is_active', true)
                ->pluck('user_id')
                ->filter();

            $tattooerIds = Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
            $piercerIds  = Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

            $recentConversations = BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
                $q->where(function ($q2) use ($tattooerIds) {
                    $q2->where('bookable_type', 'App\\Models\\Tattooer')
                       ->whereIn('bookable_id', $tattooerIds);
                })->orWhere(function ($q2) use ($piercerIds) {
                    $q2->where('bookable_type', 'App\\Models\\Piercer')
                       ->whereIn('bookable_id', $piercerIds);
                });
            })
            ->whereHas('messages')
            ->with(['bookable.user', 'client', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->latest('updated_at')
            ->limit(20)
            ->get();
        }

        return view('livewire.studio.messages', [
            'recentConversations' => $recentConversations,
            'studio' => $studio,
        ]);
    }
}
