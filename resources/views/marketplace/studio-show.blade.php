@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
    <div class="max-w-4xl w-full">
        <!-- Header Studio -->
        <div class="relative">
            @if($studio->banner_url)
                <img src="{{ $studio->banner_url }}" alt="{{ $studio->name }}" class="w-full h-48 object-cover">
            @else
                <div class="h-48 bg-gris-fonde flex items-center justify-center">
                    <h1 class="text-3xl font-bold text-beige-peau">{{ $studio->name }}</h1>
                </div>
            @endif
        </div>

        <!-- Contenu Studio -->
        <div class="bg-gris-fonde rounded-xl p-8 mt-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Infos Studio -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-noir-profond rounded-xl p-6 border border-ivoire-text/20">
                        <h2 class="text-xl font-bold text-beige-peau mb-4">{{ $studio->name }}</h2>
                        <p class="text-ivoire-text/70">{{ $studio->bio }}</p>
                        
                        @if($studio->address)
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold text-beige-peau mb-2">Adresse</h3>
                                <p class="text-ivoire-text">
                                    {{ $studio->address }}<br>
                                    {{ $studio->postal_code }} {{ $studio->city }}<br>
                                    {{ $studio->country }}
                                </p>
                            </div>
                        @endif
                        
                        @if($studio->phone)
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold text-beige-peau mb-2">Contact</h3>
                                <p class="text-ivoire-text">
                                    {{ $studio->phone }}<br>
                                    {{ $studio->email }}
                                </p>
                            </div>
                        @endif
                        
                        @if($studio->website)
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold text-beige-peau mb-2">Site web</h3>
                                <a href="{{ $studio->website }}" target="_blank" class="text-beige-peau hover:underline">{{ $studio->website }}</a>
                            </div>
                        @endif
                        
                        @if($studio->opening_hours)
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold text-beige-peau mb-2">Horaires</h3>
                                <div class="text-ivoire-text">
                                    @foreach($studio->opening_hours as $day => $hours)
                                        <div class="flex justify-between">
                                            <span class="font-semibold">{{ $day }}</span>
                                            <span>{{ $hours }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                
                <!-- Artistes du Studio -->
                <div class="lg:col-span-1">
                    <h2 class="text-xl font-bold text-beige-peau mb-4">Nos Artistes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($studio->artists as $artist)
                            @if($artist->user)
                                <div class="bg-noir-profond rounded-lg p-4 border border-ivoire-text/20">
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ $artist->user->getMedia('avatar')->getUrl() }}" alt="{{ $artist->user->name }}" class="w-16 h-16 rounded-full object-cover">
                                        <div>
                                            <h3 class="text-lg font-semibold text-beige-peau">{{ $artist->user->name }}</h3>
                                            <p class="text-ivoire-text/70">{{ $artist->role }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
