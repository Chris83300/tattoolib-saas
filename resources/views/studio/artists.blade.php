@extends('layouts.studio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-ivoire-text">Artistes du Studio</h1>
        <button class="bg-beige-peau text-noir-profond px-4 py-2 rounded-lg hover:bg-beige-peau/90">
            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16a1 1 0 012 12v4M8 12a4 4 0 018 0z"></path>
            </svg>
            Inviter un artiste
        </button>
    </div>
    
    <div class="bg-gris-fonde rounded-xl p-6">
        <div class="space-y-4">
            @foreach($artists as $artist)
                <div class="bg-noir-profond rounded-lg p-4 border border-ivoire-text/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-beige-peau">{{ $artist->user->name }}</h3>
                            <p class="text-ivoire-text/70 text-sm">{{ $artist->role }}</p>
                        </div>
                        <div class="text-ivoire-text/70 text-sm">
                            Rejoint le {{ $artist->joined_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @if($artist->is_active)
                            <span class="text-vert-succes">● Actif</span>
                        @else
                            <span class="text-orange-500">● Inactif</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
