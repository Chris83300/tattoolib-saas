@extends('layouts.studio')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('studio.clients.index') }}" class="text-titane hover:text-ivoire-text transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">
                {{ $client->first_name }} {{ $client->last_name }}
            </h1>
            @if($client->pseudo && $client->pseudo !== trim($client->first_name . ' ' . $client->last_name))
                <p class="text-sm text-titane">{{ $client->pseudo }}</p>
            @endif
        </div>
        @if($client->is_blacklisted)
            <span class="px-3 py-1 bg-rouge-alerte/20 text-rouge-alerte rounded-full text-xs font-semibold">
                Liste noire
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Infos client -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-gris-fonde rounded-xl p-5 space-y-3">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Informations</h2>

                @if($client->email)
                    <div class="flex items-center gap-2 text-sm text-ivoire-text/80">
                        <svg class="w-4 h-4 text-titane shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:{{ $client->email }}" class="hover:text-beige-peau transition-colors truncate">{{ $client->email }}</a>
                    </div>
                @endif

                @if($client->phone)
                    <div class="flex items-center gap-2 text-sm text-ivoire-text/80">
                        <svg class="w-4 h-4 text-titane shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:{{ $client->phone }}" class="hover:text-beige-peau transition-colors">{{ $client->phone }}</a>
                    </div>
                @endif

                @if($client->birth_date)
                    <div class="flex items-center gap-2 text-sm text-ivoire-text/80">
                        <svg class="w-4 h-4 text-titane shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $client->birth_date->format('d/m/Y') }} ({{ $client->getAge() }} ans)
                        @if($client->isMinor())
                            <span class="text-yellow-400 font-semibold text-xs">MINEUR</span>
                        @endif
                    </div>
                @endif

                @if($client->address)
                    <div class="flex items-start gap-2 text-sm text-ivoire-text/80">
                        <svg class="w-4 h-4 text-titane shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $client->address }}
                    </div>
                @endif
            </div>

            <!-- Statistiques -->
            <div class="bg-gris-fonde rounded-xl p-5">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide mb-3">Statistiques</h2>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">Demandes</span>
                        <span class="text-ivoire-text font-semibold">{{ $requests->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">No-shows</span>
                        <span class="font-semibold {{ $client->no_show_count > 0 ? 'text-yellow-400' : 'text-ivoire-text' }}">
                            {{ $client->no_show_count }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">Client depuis</span>
                        <span class="text-ivoire-text font-semibold">{{ $client->created_at?->format('m/Y') }}</span>
                    </div>
                </div>
            </div>

            @if($client->notes)
                <div class="bg-gris-fonde rounded-xl p-5">
                    <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide mb-2">Notes</h2>
                    <p class="text-sm text-ivoire-text/70 whitespace-pre-wrap">{{ $client->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Historique demandes -->
        <div class="lg:col-span-2">
            <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
                <div class="p-4">
                    <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">
                        Demandes ({{ $requests->count() }})
                    </h2>
                </div>

                @forelse ($requests as $request)
                    @php
                        $status = is_object($request->status) ? $request->status->value : $request->status;
                        $statusColors = [
                            'pending'       => 'bg-yellow-500/20 text-yellow-400',
                            'accepted'      => 'bg-vert-validation/20 text-vert-validation',
                            'deposit_paid'  => 'bg-blue-500/20 text-blue-400',
                            'date_confirmed'=> 'bg-blue-500/20 text-blue-400',
                            'completed'     => 'bg-vert-succes/20 text-vert-succes',
                            'cancelled'     => 'bg-rouge-alerte/20 text-rouge-alerte',
                            'rejected'      => 'bg-rouge-alerte/20 text-rouge-alerte',
                        ];
                        $colorClass = $statusColors[$status] ?? 'bg-titane/20 text-titane';
                    @endphp
                    <div class="p-4 flex items-start gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-sm font-semibold text-ivoire-text">
                                    {{ $request->bookable?->user?->name ?? 'Artiste' }}
                                    <span class="font-normal text-titane">
                                        ({{ $request->bookable instanceof \App\Models\Piercer ? '💎 Pierceur' : '🎨 Tatoueur' }})
                                    </span>
                                </p>
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $colorClass }}">
                                    {{ $status }}
                                </span>
                            </div>
                            @if($request->description)
                                <p class="text-xs text-titane mt-1 truncate">{{ $request->description }}</p>
                            @endif
                            <p class="text-xs text-titane/60 mt-1">{{ $request->created_at?->diffForHumans() }}</p>
                        </div>
                        @if($request->deposit_amount)
                            <span class="text-sm font-semibold text-beige-peau shrink-0">
                                {{ number_format($request->deposit_amount / 100, 2) }}€
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-titane text-center py-8">Aucune demande</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
