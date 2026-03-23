@extends('layouts.studio')

@section('title', 'Conversations artistes')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Conversations artistes</h1>
            <p class="text-sm text-titane mt-1">{{ $conversations->count() }} conversation(s) — lecture seule</p>
        </div>
    </div>

    {{-- Bandeau info --}}
    <div class="bg-gris-fonde rounded-xl p-4 border border-titane/20 flex items-start gap-3">
        <svg class="w-5 h-5 text-beige-peau flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-ivoire-text/80">
            En tant que gérant de studio, vous consultez les conversations de vos artistes en <strong class="text-beige-peau">lecture seule</strong>.
            Vous ne pouvez pas répondre directement dans ces conversations.
        </p>
    </div>

    {{-- Filtre artiste --}}
    @php
        $artistUserIds = $studio->artists()->with('user')->get()->pluck('user', 'user_id')->filter();
    @endphp
    @if($artistUserIds->count() > 1)
    <div x-data="{ filter: 'all' }" class="space-y-4">
        <div class="flex flex-wrap gap-2">
            <button @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane hover:text-ivoire-text'"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors border border-titane/20">
                Tous
            </button>
            @foreach($artistUserIds as $userId => $user)
            <button @click="filter = '{{ $userId }}'"
                :class="filter === '{{ $userId }}' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane hover:text-ivoire-text'"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors border border-titane/20">
                {{ $user?->name ?? 'Artiste' }}
            </button>
            @endforeach
        </div>

        {{-- Liste --}}
        @if($conversations->count() > 0)
        <div class="bg-gris-fonde rounded-xl border border-titane/10 divide-y divide-titane/5">
            @foreach($conversations as $conv)
            @php
                $artistParticipant = $conv->participants->first(fn($u) => $artistUserIds->has($u->id));
                $clientParticipant = $conv->participants->first(fn($u) => !$artistUserIds->has($u->id));
                $lastMsg = $conv->messages->first();
                $convType = $conv->type === 'support' ? 'Support' : ($conv->type === 'booking' ? 'Réservation' : 'Privé');
                $typeColor = $conv->type === 'support' ? 'rgba(168,85,247,0.15);color:#c084fc' : ($conv->type === 'booking' ? 'rgba(59,130,246,0.15);color:#60a5fa' : 'rgba(233,198,160,0.1);color:#e9c6a0');
            @endphp
            <div x-show="filter === 'all' || filter === '{{ $artistParticipant?->id ?? '' }}'">
                <a href="{{ route('studio.conversations.show', $conv) }}"
                   class="flex items-start gap-4 px-4 py-4 hover:bg-noir-profond/30 transition-colors">

                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-sm text-white"
                         style="background:rgba(233,198,160,0.3)">
                        {{ mb_strtoupper(mb_substr($clientParticipant?->name ?? $clientParticipant?->pseudo ?? '?', 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="text-sm font-semibold text-ivoire-text truncate">
                                {{ $clientParticipant?->name ?? $clientParticipant?->pseudo ?? 'Utilisateur' }}
                            </p>
                            <span class="text-[10px] px-1.5 py-0.5 rounded flex-shrink-0"
                                  style="background: {{ $typeColor }}">
                                {{ $convType }}
                            </span>
                        </div>
                        <p class="text-xs text-titane">
                            → {{ $artistParticipant?->name ?? 'Artiste' }}
                        </p>
                        <p class="text-xs text-titane/60 mt-1 truncate">
                            {{ \Str::limit($lastMsg?->content ?? '—', 60) }}
                        </p>
                    </div>

                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <span class="text-[10px] text-titane whitespace-nowrap">
                            {{ $conv->updated_at->diffForHumans() }}
                        </span>
                        <span class="text-[10px] text-titane/50">{{ $conv->messages_count }} msg</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gris-fonde rounded-xl py-12 text-center border border-titane/10">
            <p class="text-titane text-sm">Aucune conversation trouvée pour vos artistes</p>
        </div>
        @endif
    </div>
    @else
    {{-- Pas de filtre si 1 seul artiste --}}
    @if($conversations->count() > 0)
    <div class="bg-gris-fonde rounded-xl border border-titane/10 divide-y divide-titane/5">
        @foreach($conversations as $conv)
        @php
            $clientParticipant = $conv->participants->first();
            $lastMsg = $conv->messages->first();
            $convType = $conv->type === 'support' ? 'Support' : ($conv->type === 'booking' ? 'Réservation' : 'Privé');
            $typeColor = $conv->type === 'support' ? 'rgba(168,85,247,0.15);color:#c084fc' : ($conv->type === 'booking' ? 'rgba(59,130,246,0.15);color:#60a5fa' : 'rgba(233,198,160,0.1);color:#e9c6a0');
        @endphp
        <a href="{{ route('studio.conversations.show', $conv) }}"
           class="flex items-start gap-4 px-4 py-4 hover:bg-noir-profond/30 transition-colors">
            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-sm text-white"
                 style="background:rgba(233,198,160,0.3)">
                {{ mb_strtoupper(mb_substr($clientParticipant?->name ?? '?', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <p class="text-sm font-semibold text-ivoire-text truncate">
                        {{ $clientParticipant?->name ?? 'Utilisateur' }}
                    </p>
                    <span class="text-[10px] px-1.5 py-0.5 rounded flex-shrink-0"
                          style="background: {{ $typeColor }}">
                        {{ $convType }}
                    </span>
                </div>
                <p class="text-xs text-titane/60 mt-1 truncate">
                    {{ \Str::limit($lastMsg?->content ?? '—', 60) }}
                </p>
            </div>
            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                <span class="text-[10px] text-titane">{{ $conv->updated_at->diffForHumans() }}</span>
                <span class="text-[10px] text-titane/50">{{ $conv->messages_count }} msg</span>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="bg-gris-fonde rounded-xl py-12 text-center border border-titane/10">
        <p class="text-titane text-sm">Aucune conversation trouvée pour vos artistes</p>
        <p class="text-xs text-titane/60 mt-2">Les conversations apparaîtront ici une fois que vos artistes auront des échanges avec des clients</p>
    </div>
    @endif
    @endif

</div>
@endsection
