@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 py-6 px-4">
    {{-- Cover --}}
    @if ($studio->getFirstMediaUrl('cover'))
        <div class="relative rounded-2xl overflow-hidden h-48 sm:h-64">
            <img src="{{ $studio->getFirstMediaUrl('cover') }}" alt="{{ $studio->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 to-transparent"></div>
            <div class="absolute bottom-4 left-4 flex items-center gap-3">
                @if ($studio->getFirstMediaUrl('logo'))
                    <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="Logo"
                        class="w-16 h-16 rounded-xl object-cover border-2 border-beige-peau">
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text">{{ $studio->name }}</h1>
                    <p class="text-sm text-ivoire-text/80">
                        {{ $studio->city }}{{ $studio->postal_code ? ' (' . $studio->postal_code . ')' : '' }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="flex items-center gap-3">
            @if ($studio->getFirstMediaUrl('logo'))
                <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="Logo" class="w-16 h-16 rounded-xl object-cover">
            @endif
            <div>
                <h1 class="text-2xl font-bold text-ivoire-text">{{ $studio->name }}</h1>
                <p class="text-sm text-titane">
                    {{ $studio->city }}{{ $studio->postal_code ? ' (' . $studio->postal_code . ')' : '' }}
                </p>
            </div>
        </div>
    @endif

    {{-- Description --}}
    @if ($studio->description)
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-sm text-ivoire-text leading-relaxed">{{ $studio->description }}</p>
        </div>
    @endif

    {{-- Infos pratiques --}}
    <div class="flex flex-wrap gap-3">
        @if ($studio->address)
            <div class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2">
                <span class="text-sm">📍</span>
                <span class="text-sm text-ivoire-text">{{ $studio->address }}, {{ $studio->city }} {{ $studio->postal_code }}</span>
            </div>
        @endif
        @if ($studio->phone)
            <a href="tel:{{ $studio->phone }}" class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2 hover:bg-gris-fonde/80 transition-colors">
                <span class="text-sm">📞</span>
                <span class="text-sm text-ivoire-text">{{ $studio->phone }}</span>
            </a>
        @endif
        @if ($studio->website)
            <a href="{{ $studio->website }}" target="_blank" rel="noopener noreferrer"
                class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2 hover:bg-gris-fonde/80 transition-colors">
                <span class="text-sm">🌐</span>
                <span class="text-sm text-beige-peau">Site web</span>
            </a>
        @endif
    </div>

    {{-- Artistes du studio --}}
    <section>
        <h2 class="text-lg font-bold text-ivoire-text mb-4">👥 Nos artistes</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($artists as $sa)
                @if ($sa->user && $sa->is_active)
                    @php $artisan = $sa->user->artisan(); @endphp
                    <a href="{{ $artisan?->getProfileUrl() ?? '#' }}"
                        class="bg-gris-fonde rounded-xl overflow-hidden hover:ring-2 hover:ring-beige-peau/50 transition-all group">
                        {{-- Photo portfolio ou avatar --}}
                        <div class="h-40 bg-noir-profond overflow-hidden">
                            @php
                                $imageUrl = $artisan?->getFirstMediaUrl('portfolio')
                                    ?: $sa->user->getFirstMediaUrl('avatar')
                                    ?: asset('images/default-avatar.png');
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $sa->user->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $sa->user->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}"
                                    alt="" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-semibold text-ivoire-text">{{ $sa->user->name }}</p>
                                    <p class="text-xs text-titane">
                                        {{ $sa->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @empty
                <p class="text-sm text-titane col-span-full text-center py-8">Aucun artiste pour le moment</p>
            @endforelse
        </div>
    </section>

    {{-- Horaires --}}
    @if ($studio->opening_hours && count($studio->opening_hours) > 0)
        <section class="bg-gris-fonde rounded-xl p-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">🕐 Horaires</h2>
            <div class="space-y-1">
                @foreach ($studio->opening_hours as $day => $hours)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-ivoire-text capitalize">{{ $day }}</span>
                        <span class="text-titane">
                            @if (!empty($hours['closed']) && $hours['closed'])
                                Fermé
                            @elseif (!empty($hours['open']) && !empty($hours['close']))
                                {{ $hours['open'] }} — {{ $hours['close'] }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Photos du salon --}}
    @if ($studio->getMedia('photos')->count() > 0)
        <section>
            <h2 class="text-lg font-bold text-ivoire-text mb-4">📸 Le salon</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach ($studio->getMedia('photos') as $photo)
                    <img src="{{ $photo->getUrl() }}" alt="Photo salon"
                        class="w-full h-32 sm:h-40 rounded-lg object-cover hover:opacity-90 transition-opacity cursor-pointer">
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
