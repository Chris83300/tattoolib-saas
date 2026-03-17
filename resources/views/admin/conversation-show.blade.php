@extends('layouts.app')

@section('title', 'Conversation #' . $conversation->id)

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- En-tête --}}
            <div class="mb-6">
                <a href="{{ route('filament.admin.resources.cancellations.index') }}"
                    class="inline-flex items-center text-ivoire-text/70 hover:text-ivoire-text mb-4 text-sm">
                    ← Retour aux annulations
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-ivoire-text">Conversation #{{ $conversation->id }}</h1>
                    <span class="px-2 py-1 text-xs rounded bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30">
                        Lecture seule — Vue admin
                    </span>
                </div>
                @if ($conversation->bookingRequest)
                    <p class="text-ivoire-text/60 text-sm mt-1">
                        Demande #{{ $conversation->bookingRequest->id }}
                        · {{ $conversation->bookingRequest->bookable?->user?->pseudo ?? '—' }}
                        · {{ $conversation->bookingRequest->client?->user?->name ?? '—' }}
                    </p>
                @endif
            </div>

            {{-- Détails de la demande --}}
            @if ($conversation->bookingRequest)
                @php $br = $conversation->bookingRequest; @endphp
                <div class="bg-titane/10 rounded-xl p-5 border border-titane/20 mb-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-ivoire-text/50 block mb-0.5">Client</span>
                        <span class="text-ivoire-text font-medium">{{ $br->client?->user?->name ?? '—' }}</span>
                        <span class="text-ivoire-text/50 block text-xs">{{ $br->client?->user?->email ?? '' }}</span>
                    </div>
                    <div>
                        <span class="text-ivoire-text/50 block mb-0.5">Artiste</span>
                        <span class="text-ivoire-text font-medium">{{ $br->bookable?->user?->pseudo ?? $br->bookable?->pseudo ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-ivoire-text/50 block mb-0.5">Statut</span>
                        <span class="text-ivoire-text font-medium">{{ $br->status->label() }}</span>
                    </div>
                    <div>
                        <span class="text-ivoire-text/50 block mb-0.5">Annulé par</span>
                        <span class="text-ivoire-text font-medium">{{ $br->cancelled_by ?? '—' }}</span>
                    </div>
                </div>
            @endif

            {{-- Messages — lecture seule --}}
            <div class="bg-titane/10 rounded-xl border border-titane/20">
                <div class="px-6 py-4 border-b border-titane/20 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-ivoire-text">💬 Messages ({{ $conversation->messages->count() }})</h2>
                    <span class="text-xs text-ivoire-text/40">Vue en lecture seule</span>
                </div>

                <div class="p-6">
                    @if ($conversation->messages->isEmpty())
                        <p class="text-ivoire-text/40 text-center py-8">Aucun message dans cette conversation</p>
                    @else
                        <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                            @foreach ($conversation->messages as $message)
                                @php
                                    $isClient = $message->sender_type === 'client'
                                        || optional($message->sender)->hasRole('client');
                                    $bubbleClass = $isClient
                                        ? 'bg-noir-profond border border-titane/30 text-ivoire-text'
                                        : 'bg-beige-peau/20 border border-beige-peau/30 text-ivoire-text';
                                    $alignClass = $isClient ? 'justify-start' : 'justify-end';
                                @endphp
                                <div class="flex gap-3 {{ $alignClass }}">
                                    <div class="max-w-sm lg:max-w-lg">
                                        <div class="flex items-center gap-2 mb-1 {{ $isClient ? '' : 'justify-end' }}">
                                            <span class="text-xs text-ivoire-text/60 font-medium">
                                                {{ $message->sender?->name ?? $message->sender?->pseudo ?? '—' }}
                                            </span>
                                            <span class="text-xs text-ivoire-text/30">
                                                {{ $message->created_at->format('d/m H:i') }}
                                            </span>
                                        </div>
                                        <div class="{{ $bubbleClass }} rounded-xl px-4 py-2.5">
                                            <p class="text-sm whitespace-pre-wrap">{{ $message->content }}</p>

                                            @if ($message->attachments?->isNotEmpty())
                                                <div class="mt-2 space-y-1">
                                                    @foreach ($message->attachments as $attachment)
                                                        <a href="{{ $attachment->getUrl() }}"
                                                            target="_blank"
                                                            class="flex items-center gap-1 text-xs text-beige-peau/70 hover:text-beige-peau underline">
                                                            📎 {{ $attachment->file_name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Pas de formulaire d'envoi — lecture seule --}}
                <div class="px-6 py-4 border-t border-titane/20 bg-titane/5 rounded-b-xl">
                    <p class="text-xs text-ivoire-text/30 text-center">
                        🔒 Interface de consultation uniquement — les admins ne peuvent pas envoyer de messages
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection
