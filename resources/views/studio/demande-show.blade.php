@extends('layouts.studio')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('studio.requests') }}" class="text-titane hover:text-ivoire-text transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-ivoire-text">Demande #{{ $bookingRequest->id }}</h1>
                <p class="text-sm text-titane">{{ $bookingRequest->created_at?->format('d/m/Y à H:i') }}</p>
            </div>

            @php
                $status = is_object($bookingRequest->status) ? $bookingRequest->status->value : $bookingRequest->status;
                $statusColors = [
                    'pending' => 'bg-yellow-500/20 text-yellow-400',
                    'accepted' => 'bg-blue-500/20 text-blue-400',
                    'deposit_requested' => 'bg-purple-500/20 text-purple-400',
                    'deposit_paid' => 'bg-vert-succes/20 text-vert-succes',
                    'date_confirmed' => 'bg-vert-succes/20 text-vert-succes',
                    'completed' => 'bg-vert-succes/20 text-vert-succes',
                    'balance_paid' => 'bg-vert-succes/20 text-vert-succes',
                    'balance_paid_offline' => 'bg-vert-succes/20 text-vert-succes',
                    'fully_completed' => 'bg-vert-succes/20 text-vert-succes',
                    'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                    'rejected' => 'bg-rouge-alerte/20 text-rouge-alerte',
                    'expired' => 'bg-titane/20 text-titane',
                    'no_show' => 'bg-rouge-alerte/20 text-rouge-alerte',
                ];
                $colorClass = $statusColors[$status] ?? 'bg-titane/20 text-titane';
            @endphp

            <span class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $colorClass }}">
                {{ str_replace('_', ' ', ucfirst($status)) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-4">

                <!-- Détails du projet -->
                <div class="bg-gris-fonde rounded-xl p-5 space-y-4">
                    <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Détails du projet</h2>

                    @if ($bookingRequest->description)
                        <div>
                            <p class="text-xs text-titane uppercase tracking-wide mb-1">Description</p>
                            <p class="text-sm text-ivoire-text/90 whitespace-pre-wrap">{{ $bookingRequest->description }}
                            </p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        @if ($bookingRequest->tattoo_style)
                            <div>
                                <p class="text-xs text-titane uppercase tracking-wide mb-1">Style</p>
                                <p class="text-sm text-ivoire-text">{{ $bookingRequest->tattoo_style }}</p>
                            </div>
                        @endif

                        @if ($bookingRequest->body_zone)
                            <div>
                                <p class="text-xs text-titane uppercase tracking-wide mb-1">Zone corporelle</p>
                                <p class="text-sm text-ivoire-text">{{ $bookingRequest->body_zone }}</p>
                            </div>
                        @endif

                        @if ($bookingRequest->tattoo_size)
                            <div>
                                <p class="text-xs text-titane uppercase tracking-wide mb-1">Taille</p>
                                <p class="text-sm text-ivoire-text">{{ $bookingRequest->tattoo_size }}</p>
                            </div>
                        @endif

                        @if ($bookingRequest->preferred_timeframe)
                            <div>
                                <p class="text-xs text-titane uppercase tracking-wide mb-1">Délai souhaité</p>
                                <p class="text-sm text-ivoire-text">{{ $bookingRequest->preferred_timeframe }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($bookingRequest->preferred_date || $bookingRequest->confirmed_date)
                        <div>
                            <p class="text-xs text-titane uppercase tracking-wide mb-1">Date</p>
                            <p class="text-sm text-ivoire-text">
                                @if ($bookingRequest->confirmed_date)
                                    <span class="text-vert-succes font-semibold">Confirmée :
                                        {{ \Carbon\Carbon::parse($bookingRequest->confirmed_date)->format('d/m/Y') }}</span>
                                @elseif($bookingRequest->preferred_date)
                                    Souhaitée :
                                    {{ \Carbon\Carbon::parse($bookingRequest->preferred_date)->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Financier -->
                @if (true)
                    <div class="bg-gris-fonde rounded-xl p-5 space-y-3">
                        <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Financier</h2>
                        <div class="grid grid-cols-1 gap-4">
                            @if ($bookingRequest->price_estimate_max || $bookingRequest->total_price)
                                <div>
                                    <p class="text-xs text-titane uppercase tracking-wide mb-1">Prix total</p>
                                    <p class="text-lg font-bold text-beige-peau">
                                        {{ number_format($bookingRequest->price_estimate_max ?? $bookingRequest->total_price, 2) }}€
                                        @if ($bookingRequest->total_deposit_amount)
                                            <span class="text-vert-succes/50">(-{{ number_format($bookingRequest->total_deposit_amount, 2) }}€)</span>
                                        @endif
                                    </p>
                                    @if ($bookingRequest->total_deposit_amount)
                                        <p class="text-md text-ivoire-text mt-1">
                                            Reste à payer :
                                            <span class="text-lg text-ambre-warning">{{ number_format(($bookingRequest->price_estimate_max ?? $bookingRequest->total_price) - $bookingRequest->total_deposit_amount, 2) }}€</span>
                                        </p>
                                    @endif
                                </div>
                            @endif
                            @if ($bookingRequest->deposit_amount)
                                <div>
                                    <p class="text-xs text-titane uppercase tracking-wide mb-1">Acompte</p>
                                    <p class="text-lg font-bold text-ivoire-text">
                                        {{ number_format($bookingRequest->deposit_amount, 2) }}€
                                        @if ($bookingRequest->deposit_paid_at)
                                            <span class="text-xs text-vert-succes font-normal ml-1">Payé</span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Messages -->
                @if ($bookingRequest->messages && $bookingRequest->messages->count() > 0)
                    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
                        <div class="p-4">
                            <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">
                                Messages ({{ $bookingRequest->messages->count() }})
                            </h2>
                        </div>
                        @foreach ($bookingRequest->messages->take(5) as $message)
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="text-xs font-semibold text-beige-peau">{{ $message->sender?->name ?? 'Système' }}</span>
                                    <span class="text-xs text-titane">{{ $message->created_at?->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-ivoire-text/80">{{ $message->body }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-4">

                <!-- Artiste -->
                <div class="bg-gris-fonde rounded-xl p-5 space-y-3">
                    <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Artiste</h2>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-beige-peau/20 flex items-center justify-center shrink-0">
                            <span class="text-beige-peau font-bold text-sm">
                                {{ mb_strtoupper(mb_substr($bookingRequest->bookable?->user?->name ?? 'A', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-ivoire-text">
                                {{ $bookingRequest->bookable?->user?->name ?? 'Artiste' }}</p>
                            <p class="text-xs text-beige-peau">
                                {{ $bookingRequest->bookable instanceof \App\Models\Piercer ? 'Pierceur' : 'Tatoueur' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Client -->
                <div class="bg-gris-fonde rounded-xl p-5 space-y-3">
                    <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Client</h2>
                    @if ($bookingRequest->client)
                        <div class="space-y-2">
                            <p class="text-sm font-semibold text-ivoire-text">
                                {{ $bookingRequest->client->first_name }} {{ $bookingRequest->client->last_name }}
                            </p>
                            @if ($bookingRequest->client->email)
                                <a href="mailto:{{ $bookingRequest->client->email }}"
                                    class="flex items-center gap-1.5 text-xs text-titane hover:text-beige-peau transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $bookingRequest->client->email }}
                                </a>
                            @endif
                            @if ($bookingRequest->client->phone)
                                <a href="tel:{{ $bookingRequest->client->phone }}"
                                    class="flex items-center gap-1.5 text-xs text-titane hover:text-beige-peau transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $bookingRequest->client->phone }}
                                </a>
                            @endif
                            <a href="{{ route('studio.clients.show', $bookingRequest->client) }}"
                                class="inline-block text-xs text-beige-peau hover:underline mt-1">
                                Voir la fiche client →
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-titane">Client non associé</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
