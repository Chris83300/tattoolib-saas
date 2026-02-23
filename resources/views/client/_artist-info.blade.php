{{-- client/_artist-info.blade.php --}}
<div class="space-y-2 text-sm">
    <div class="flex justify-between">
        <span class="text-ivoire-text/70">Nom:</span>
        <span class="text-ivoire-text">{{ $bookingRequest->bookable->user->pseudo }}</span>
    </div>
    <div class="flex justify-between">
        <span class="text-ivoire-text/70">Type:</span>
        <span class="text-ivoire-text">
            @if ($bookingRequest->bookable_type === 'App\Models\Tattooer')
                Tatoueur indépendant
            @elseif ($bookingRequest->bookable_type === 'App\Models\Piercer')
                Pierceur indépendant
            @elseif($bookingRequest->bookable_type === 'App\Models\StudioArtist')
                Artiste de studio
            @endif
        </span>
    </div>
    <div class="flex justify-between">
        <span class="text-ivoire-text/70">Email:</span>
        <span class="text-ivoire-text">{{ $bookingRequest->bookable->user->email }}</span>
    </div>
</div>
