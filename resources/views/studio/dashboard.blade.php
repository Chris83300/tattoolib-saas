@extends('layouts.studio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-ivoire-text mb-6">Dashboard Studio</h1>
    
    <!-- Stats globales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
            <div class="text-beige-peau text-2xl font-bold">{{ $totalArtists }}</div>
            <div class="text-ivoire-text/70">Artistes total</div>
        </div>
        
        <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
            <div class="text-beige-peau text-2xl font-bold">{{ $activeArtists }}</div>
            <div class="text-ivoire-text/70">Artistes actifs</div>
        </div>
        
        <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
            <div class="text-beige-peau text-2xl font-bold">€{{ number_format($totalRevenue, 2) }}</div>
            <div class="text-ivoire-text/70">Revenu total</div>
        </div>
    </div>
    
    <!-- Artistes récents -->
    <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
        <h2 class="text-xl font-bold text-beige-peau mb-4">Artistes</h2>
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
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
