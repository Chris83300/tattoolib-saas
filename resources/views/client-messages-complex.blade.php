@extends('layouts.app')

@section('title', 'Mes conversations')

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="h-screen flex flex-col">
            
            <!-- Header -->
            <div class="bg-gris-fonde border-b border-titane/20 px-4 py-3">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold text-ivoire-text">
                        💬 Mes conversations
                    </h1>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('client.booking-requests') }}" 
                           class="text-ivoire-text/60 hover:text-ivoire-text transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                        <a href="{{ route('client.conversations') }}" 
                           class="text-ivoire-text/60 hover:text-ivoire-text transition-colors" title="Voir toutes les conversations">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="flex-1 flex overflow-hidden">
                
                <!-- Liste des conversations -->
                <div class="w-full md:w-1/3 lg:w-1/4 bg-gris-fonde border-r border-titane/20 overflow-y-auto">
                    @if ($conversations->count() > 0)
                        <div class="divide-y divide-titane/20">
                            @foreach ($conversations as $conversation)
                                @php
                                    $bookingRequest = $conversation->bookingRequest;
                                    $artist = $bookingRequest?->bookable;
                                    $artistUser = $artist?->user;
                                    $artistName = $artistUser?->name ?? 'Artiste inconnu';
                                    $lastMessage = $conversation->lastMessage;
                                    $isActive = isset($activeConversation) && $activeConversation->id === $conversation->id;
                                @endphp
                                
                                <a href="{{ route('client.chat', $conversation) }}" 
                                   class="block p-4 hover:bg-titane/10 transition-colors {{ $isActive ? 'bg-titane/20 border-l-4 border-beige-peau' : '' }}">
                                    <div class="flex items-start space-x-3">
                                        <!-- Avatar artiste -->
                                        <div class="w-12 h-12 rounded-full overflow-hidden bg-beige-peau/10 flex-shrink-0">
                                            @if ($artistUser && $artistUser->getFirstMediaUrl('avatar'))
                                                <img src="{{ $artistUser->getFirstMediaUrl('avatar') }}" 
                                                     alt="{{ $artistName }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-beige-peau/20 flex items-center justify-center">
                                                    <span class="text-beige-peau/60 text-sm font-bold">
                                                        {{ substr($artistName, 0, 2) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Infos conversation -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-1">
                                                <h3 class="font-semibold text-ivoire-text truncate">
                                                    {{ $artistName }}
                                                </h3>
                                                @if ($conversation->unread_count > 0)
                                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-beige-peau text-noir-profond text-xs font-bold rounded-full">
                                                        {{ $conversation->unread_count }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <p class="text-ivoire-text/60 text-sm truncate">
                                                    @if ($lastMessage)
                                                        @if ($lastMessage->sender_type === 'system')
                                                            🤖 {{ Str::limit($lastMessage->content, 30) }}
                                                        @else
                                                            {{ Str::limit($lastMessage->content, 30) }}
                                                        @endif
                                                    @else
                                                        📝 Nouvelle conversation
                                                    @endif
                                                </p>
                                                <span class="text-ivoire-text/40 text-xs">
                                                    @if ($conversation->last_message_at)
                                                        {{ $conversation->last_message_at->diffForHumans() }}
                                                    @else
                                                        {{ $conversation->created_at->diffForHumans() }}
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            <!-- Statut booking -->
                                            @if ($bookingRequest)
                                                <div class="mt-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                        @switch($bookingRequest->status)
                                                            @case('pending') bg-orange-terre-cuite/20 text-orange-terre-cuite @break
                                                            @case('accepted') bg-vert-succes/20 text-vert-succes @break
                                                            @case('in_progress') bg-beige-peau/20 text-beige-peau @break
                                                            @case('completed') bg-titane/30 text-ivoire-text/60 @break
                                                            @case('cancelled') bg-rouge-alerte/20 text-rouge-alerte @break
                                                            @default bg-titane/20 text-ivoire-text/60
                                                        @endswitch
                                                    ">
                                                        @switch($bookingRequest->status)
                                                            @case('pending') ⏳ En attente @break
                                                            @case('accepted') ✓ Acceptée @break
                                                            @case('in_progress') 🎨 En cours @break
                                                            @case('completed') ✅ Terminée @break
                                                            @case('cancelled') ❌ Annulée @break
                                                            @default {{ $bookingRequest->status }}
                                                        @endswitch
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-beige-peau/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <p class="text-ivoire-text/60 mb-4">
                                Aucune conversation
                            </p>
                            <a href="{{ route('client.booking-requests') }}" 
                               class="text-beige-peau hover:text-beige-peau/80 font-medium">
                                Commencer une demande
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Chat actif (desktop) -->
                @if (isset($activeConversation))
                    <div class="hidden md:flex flex-1 flex-col">
                        <!-- En-tête conversation -->
                        <div class="bg-titane/20 border-b border-titane/30 px-6 py-4">
                            @php
                                $artist = $activeConversation->bookingRequest?->bookable;
                                $artistUser = $artist?->user;
                            @endphp
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-beige-peau/10">
                                        @if ($artistUser && $artistUser->getFirstMediaUrl('avatar'))
                                            <img src="{{ $artistUser->getFirstMediaUrl('avatar') }}" 
                                                 alt="{{ $artistUser->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-beige-peau/20 flex items-center justify-center">
                                                <span class="text-beige-peau/60 text-xs font-bold">
                                                    {{ substr($artistUser->name ?? '??', 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-ivoire-text">
                                            {{ $artistUser->name ?? 'Artiste inconnu' }}
                                        </h3>
                                        <p class="text-ivoire-text/60 text-sm">
                                            {{ $artist ? class_basename($artist) : 'Artiste' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <a href="{{ route('client.chat', $activeConversation) }}" 
                                   class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond rounded-lg font-medium transition-colors">
                                    Ouvrir le chat
                                </a>
                            </div>
                        </div>

                        <!-- Messages récents -->
                        <div class="flex-1 overflow-y-auto p-6">
                            @if ($activeConversation->messages && $activeConversation->messages->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($activeConversation->messages->take(5) as $message)
                                        <div class="flex items-start space-x-3 {{ $message->sender_type === 'client' ? 'justify-end' : '' }}">
                                            @if ($message->sender_type !== 'client')
                                                <div class="w-8 h-8 rounded-full overflow-hidden bg-beige-peau/10 flex-shrink-0">
                                                    @if ($message->sender && $message->sender->getFirstMediaUrl('avatar'))
                                                        <img src="{{ $message->sender->getFirstMediaUrl('avatar') }}" 
                                                             alt="{{ $message->sender->name }}" 
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-beige-peau/20 flex items-center justify-center">
                                                            <span class="text-beige-peau/60 text-xs">
                                                                🤖
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                            
                                            <div class="max-w-xs lg:max-w-md">
                                                <div class="px-4 py-2 rounded-lg 
                                                    @switch($message->sender_type)
                                                        @case('client') bg-beige-peau text-noir-profond @break
                                                        @case('system') bg-titane/30 text-ivoire-text/80 @break
                                                        @default bg-gris-fonde text-ivoire-text
                                                    @endswitch
                                                ">
                                                    <p class="text-sm">{{ $message->content }}</p>
                                                </div>
                                                <p class="text-ivoire-text/40 text-xs mt-1 {{ $message->sender_type === 'client' ? 'text-right' : '' }}">
                                                    {{ $message->created_at->format('H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-ivoire-text/60 py-8">
                                    Aucun message dans cette conversation
                                </div>
                            @endif
                        </div>

                        <!-- Input message (désactivé - juste pour aperçu) -->
                        <div class="bg-gris-fonde border-t border-titane/20 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <input type="text" 
                                       placeholder="Tapez votre message..." 
                                       disabled
                                       class="flex-1 px-4 py-2 bg-titane/20 border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/40 disabled:opacity-50">
                                <button disabled 
                                        class="px-4 py-2 bg-beige-peau/50 text-noir-profond rounded-lg font-medium disabled:opacity-50">
                                    Envoyer
                                </button>
                            </div>
                            <p class="text-ivoire-text/40 text-xs mt-2 text-center">
                                Cliquez sur "Ouvrir le chat" pour continuer la conversation
                            </p>
                        </div>
                    </div>
                @else
                    <!-- État vide -->
                    <div class="hidden md:flex flex-1 items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto mb-6 bg-beige-peau/10 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-beige-peau/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-ivoire-text mb-2">
                                Sélectionnez une conversation
                            </h3>
                            <p class="text-ivoire-text/60">
                                Choisissez une conversation dans la liste pour voir les messages
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
