@extends('layouts.app')

@section('title', $artist->name . ' - Ink&Pik')

@section('content')
    <div class="bg-noir-profond min-h-screen">

        <!-- Hero Section -->
        <section class="bg-gris-fonde py-12 px-4">
            <div class="container mx-auto max-w-6xl">
                <div class="flex flex-col md:flex-row gap-8">

                    <!-- Avatar -->
                    <div
                        class="w-48 h-48 rounded-xl overflow-hidden flex-shrink-0 bg-noir-profond border-4 border-beige-peau/20">
                        <img src="{{ $artist->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}"
                            alt="{{ $artist->name }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Infos -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h1 class="text-4xl font-display font-bold text-ivoire-text mb-2">
                                    {{ $artist->user->pseudo ?? $artist->name }}
                                </h1>
                                <p class="text-xl text-beige-peau mb-2">
                                    {{ $type === 'tattooer' ? 'Tatoueur professionnel' : 'Perceur professionnel' }}
                                </p>
                                <div class="flex items-center gap-2 text-ivoire-text/70">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $artist->city }}, {{ $artist->postal_code }}</span>
                                </div>
                            </div>

                            <!-- Badges -->
                            <div class="flex flex-col gap-2">
                                @if ($artist->admin_verified_at)
                                    <span
                                        class="bg-vert-succes/20 text-vert-succes px-3 py-1 rounded-full text-sm font-semibold">
                                        ✓ Vérifié
                                    </span>
                                @endif

                                @if ($artist->has_compliance_badge)
                                    <span
                                        class="bg-vert-succes/20 text-vert-succes px-3 py-1 rounded-full text-sm font-semibold">
                                        ✓ Badge conformité
                                    </span>
                                @endif

                                @if ($stats['rating'] >= 4.5 && $stats['reviews_count'] >= 5)
                                    <span
                                        class="bg-beige-peau/20 text-beige-peau px-3 py-1 rounded-full text-sm font-semibold">
                                        ⭐ Top noté
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center p-3 bg-noir-profond rounded-lg">
                                <div class="text-2xl font-bold text-beige-peau">{{ round($stats['rating'], 1) }}</div>
                                <div class="text-xs text-ivoire-text/60">Note moyenne</div>
                                <div class="text-xs text-ivoire-text/60">({{ $stats['reviews_count'] }} avis)</div>
                            </div>
                            <div class="text-center p-3 bg-noir-profond rounded-lg">
                                <div class="text-2xl font-bold text-beige-peau">{{ $stats['appointments_count'] }}</div>
                                <div class="text-xs text-ivoire-text/60">Rendez-vous</div>
                            </div>
                            <div class="text-center p-3 bg-noir-profond rounded-lg">
                                <div class="text-2xl font-bold text-beige-peau">{{ $stats['years_experience'] }}+</div>
                                <div class="text-xs text-ivoire-text/60">Ans d'expérience</div>
                            </div>
                        </div>

                        <!-- CTA -->
                        <a href="{{ route('booking-request.form', [$artist->id, $type]) }}"
                            class="inline-block px-8 py-4 bg-beige-peau text-noir-profond font-bold rounded-lg text-lg hover:bg-beige-peau/90 transition-colors">
                            📅 prendre RDV
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bio -->
        @if ($artist->bio)
            <section class="py-12 px-4">
                <div class="container mx-auto max-w-6xl">
                    <div class="bg-gris-fonde rounded-xl p-8">
                        <h2 class="text-2xl font-display font-bold text-ivoire-text mb-4">À propos</h2>
                        <p class="text-ivoire-text/80 leading-relaxed whitespace-pre-line">{{ $artist->bio }}</p>
                    </div>
                </div>
            </section>
        @endif

        <!-- Portfolio -->
        @if ($portfolio->isNotEmpty())
            <section class="py-12 px-4">
                <div class="container mx-auto max-w-6xl">
                    <h2 class="text-2xl font-display font-bold text-ivoire-text mb-8">Portfolio</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($portfolio as $media)
                            <div class="aspect-square rounded-lg overflow-hidden bg-gris-fonde">
                                <img src="{{ $media->getUrl() }}" alt="Portfolio"
                                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300 cursor-pointer"
                                    onclick="openLightbox('{{ $media->getUrl() }}')">
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

    </div>

    <!-- Lightbox simple -->
    <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center" onclick="closeLightbox()">
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full">
    </div>

    @push('scripts')
        <script>
            function openLightbox(url) {
                document.getElementById('lightbox-image').src = url;
                document.getElementById('lightbox').classList.remove('hidden');
                document.getElementById('lightbox').classList.add('flex');
            }

            function closeLightbox() {
                document.getElementById('lightbox').classList.add('hidden');
                document.getElementById('lightbox').classList.remove('flex');
            }
        </script>
    @endpush
@endsection
