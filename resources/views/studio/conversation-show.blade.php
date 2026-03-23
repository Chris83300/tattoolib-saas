@extends('layouts.studio')

@section('title', 'Conversation #' . $conversation->id)

@section('content')
<div class="space-y-6 max-w-3xl">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('studio.conversations') }}"
           class="text-titane hover:text-beige-peau transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-ivoire-text">Conversation #{{ $conversation->id }}</h1>
            <p class="text-xs text-titane mt-0.5">
                @php
                    $participants = $conversation->participants;
                    $names = $participants->map(fn($u) => $u->name ?? $u->pseudo ?? 'Utilisateur')->implode(', ');
                @endphp
                {{ $names }} · {{ $conversation->messages->count() }} messages
            </p>
        </div>
    </div>

    {{-- Bandeau lecture seule --}}
    <div class="bg-beige-peau/10 border border-beige-peau/20 rounded-xl px-4 py-3 flex items-center gap-3">
        <svg class="w-4 h-4 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        <p class="text-sm text-beige-peau">Vous consultez cette conversation en <strong>lecture seule</strong>.</p>
    </div>

    {{-- Infos participants --}}
    <div class="bg-gris-fonde rounded-xl p-4 border border-titane/10">
        <h3 class="text-xs font-semibold text-titane uppercase tracking-wider mb-3">Participants</h3>
        <div class="flex flex-wrap gap-3">
            @foreach($conversation->participants as $participant)
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white"
                     style="background:rgba(233,198,160,0.3)">
                    {{ mb_strtoupper(mb_substr($participant->name ?? $participant->pseudo ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs font-semibold text-ivoire-text">{{ $participant->name ?? $participant->pseudo ?? 'Utilisateur' }}</p>
                    <p class="text-[10px] text-titane">{{ $participant->email }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Fil de messages --}}
    <div class="bg-gris-fonde rounded-xl border border-titane/10 overflow-hidden">
        <div class="p-4 border-b border-titane/10">
            <h3 class="text-xs font-semibold text-titane uppercase tracking-wider">Messages</h3>
        </div>

        <div class="p-4 space-y-4 max-h-[60vh] overflow-y-auto">
            @forelse($conversation->messages as $message)
            @php
                $isAdmin     = $message->sender_type === 'admin';
                $isTattooer  = in_array($message->sender_type, ['tattooer', 'studio_artist']);
                $isRight     = $isAdmin || $isTattooer;
                $senderName  = $message->sender?->name ?? $message->sender?->pseudo ?? 'Utilisateur';
                $initial     = mb_strtoupper(mb_substr($senderName, 0, 1));
                $bubbleBg    = $isAdmin ? 'rgba(99,102,241,0.9)' : ($isTattooer ? 'rgba(233,198,160,0.2)' : 'rgba(55,65,81,1)');
                $textColor   = $isAdmin ? '#ffffff' : '#f3f4f6';
            @endphp
            <div class="flex {{ $isRight ? 'justify-end' : 'justify-start' }} gap-2">
                @if(!$isRight)
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0 mt-1"
                     style="background:rgba(233,198,160,0.2)">
                    {{ $initial }}
                </div>
                @endif

                <div class="max-w-[70%]">
                    @if(!$isRight)
                    <p class="text-[10px] text-titane mb-1">{{ $senderName }}</p>
                    @endif
                    <div class="px-3 py-2 rounded-2xl text-sm"
                         style="background: {{ $bubbleBg }}; color: {{ $textColor }};
                                {{ $isRight ? 'border-radius:1rem 0.25rem 1rem 1rem' : 'border-radius:0.25rem 1rem 1rem 1rem' }}">
                        {{ $message->content }}
                    </div>
                    <p class="text-[10px] text-titane/50 mt-1 {{ $isRight ? 'text-right' : 'text-left' }}">
                        {{ $message->created_at->format('d/m H:i') }}
                        @if($isAdmin) · <span style="color:#818cf8">Admin</span> @endif
                    </p>
                </div>

                @if($isRight && !$isAdmin)
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0 mt-1"
                     style="background:rgba(233,198,160,0.3)">
                    {{ $initial }}
                </div>
                @endif
            </div>
            @empty
            <div class="py-8 text-center">
                <p class="text-titane text-sm">Aucun message dans cette conversation</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Note bottom --}}
    <p class="text-xs text-titane/50 text-center">
        Conversation créée le {{ $conversation->created_at->format('d/m/Y') }} · Dernière activité {{ $conversation->updated_at->diffForHumans() }}
    </p>

</div>
@endsection
