<x-filament-panels::page>
<div
    class="flex h-[calc(100vh-180px)] rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm"
    wire:poll.15s="$refresh">

    {{-- ═══════════════════════════════════════════════════════
         COLONNE GAUCHE — Liste des conversations (fixe 320px)
    ═══════════════════════════════════════════════════════ --}}
    <div class="w-80 flex-shrink-0 flex flex-col border-r border-gray-200 dark:border-gray-700">

        {{-- Header liste --}}
        <div class="inkpik-card px-4 py-4 border-b border-beige-peau dark:border-beige-peau bg-gray-50 dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100 text-sm flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full chat-pulse" style="background-color:#22c55e"></span>
                        Conversations support
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $this->conversations->count() }} conversation(s)
                    </p>
                </div>
                @php $totalUnread = $this->conversations->sum('unread_count'); @endphp
                @if ($totalUnread > 0)
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                    {{ $totalUnread }}
                </span>
                @endif
            </div>
        </div>

        {{-- Liste scrollable --}}
        <div class="inkpik-card flex-1 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700/50">

            @forelse ($this->conversations as $conv)
            @php
                $convUser  = $conv->participants->first();
                $lastMsg   = $conv->messages->first();
                $isActive  = $this->activeConversationId === $conv->id;
                $hasUnread = ($conv->unread_count ?? 0) > 0;
                $isAwaiting = !$hasUnread && $lastMsg && $lastMsg->sender_type !== 'admin';
                $convType  = $conv->type === 'support' ? 'Support' : 'Privé';
                $initial   = strtoupper(substr($convUser?->name ?? $convUser?->pseudo ?? '?', 0, 1));
                $userRole  = match(true) {
                    $convUser?->role === 'tattooer'      => 'Tatoueur',
                    $convUser?->role === 'pierceur'      => 'Pierceur',
                    $convUser?->role === 'client'        => 'Client',
                    $convUser?->role === 'studio'        => 'Studio',
                    $convUser?->role === 'studio_artist' => 'Artiste Studio',
                    default                              => 'Utilisateur',
                };
            @endphp

            <button
                wire:click="selectConversation({{ $conv->id }})"
                class="w-full text-left px-4 py-3 transition-colors
                       {{ $isActive ? 'chat-row-active' : ($hasUnread ? 'chat-row-unread' : 'chat-row-default') }}">

                <div class="flex items-start gap-3">

                    {{-- Avatar initiale avec point non lu --}}
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm
                                    {{ $hasUnread ? 'chat-avatar-unread' : ($isAwaiting ? 'chat-avatar-awaiting' : 'chat-avatar-default') }}">
                            {{ $initial }}
                        </div>
                        @if ($hasUnread)
                        <span class="absolute -top-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white dark:border-gray-900 chat-pulse" style="background-color:#ef4444"></span>
                        @elseif ($isAwaiting)
                        <span class="absolute -top-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white dark:border-gray-900" style="background-color:#eab308"></span>
                        @endif
                    </div>

                    {{-- Contenu --}}
                    <div class="inkpik-card flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <span class="font-medium text-sm truncate
                                         {{ $hasUnread ? 'text-gray-900 dark:text-white font-bold' : 'text-gray-700 dark:text-gray-200' }}">
                                {{ $convUser?->name ?? $convUser?->pseudo ?? 'Utilisateur inconnu' }}
                            </span>
                            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                @if ($hasUnread)
                                <span class="chat-badge-unread">{{ $conv->unread_count }}</span>
                                @elseif ($isAwaiting)
                                <span class="chat-badge-awaiting">En attente</span>
                                @else
                                <span class="chat-badge-replied">Répondu</span>
                                @endif
                                <span class="text-[10px] text-gray-400 dark:text-gray-500">
                                    {{ $conv->updated_at->format('H:i') }}
                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5
                                  {{ $hasUnread ? 'font-medium text-gray-700 dark:text-gray-300' : '' }}">
                            {{ \Str::limit($lastMsg?->content ?? '…', 38) }}
                        </p>

                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                {{ $userRole }}
                            </span>
                            <span class="{{ $conv->type === 'support' ? 'chat-type-support' : 'chat-type-private' }}">
                                {{ $convType }}
                            </span>
                        </div>
                    </div>
                </div>
            </button>

            @empty
            <div class="flex flex-col items-center justify-center py-12 text-gray-400 dark:text-gray-500">
                <span class="text-4xl mb-3">💬</span>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Aucune conversation</p>
                <p class="text-xs mt-1 text-center px-4">Les utilisateurs vous contactent via le chat support</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         COLONNE DROITE — Conversation active
    ═══════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0">

        @if ($this->activeConversationId && $this->activeUser)

        {{-- Header conversation active --}}
        <div class="inkpik-card px-6 py-4 border-b border-gray-200 dark:border-gray-700
                    bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="inkpik-avatar w-10 h-10 rounded-full bg-primary-500 flex items-center
                            justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($this->activeUser->name ?? $this->activeUser->pseudo ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p class="inkpik-name font-semibold text-gray-900 dark:text-gray-100 text-sm">
                        {{ $this->activeUser->name ?? $this->activeUser->pseudo ?? 'Utilisateur' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $this->activeUser->email }}
                        @php
                            $activeRole = match($this->activeUser->role ?? '') {
                                'tattooer'      => '· Tatoueur',
                                'pierceur'      => '· Pierceur',
                                'client'        => '· Client',
                                'studio'        => '· Studio',
                                'studio_artist' => '· Artiste Studio',
                                default         => '',
                            };
                        @endphp
                        <span class="text-gray-400 dark:text-gray-500">{{ $activeRole }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                <span class="w-2 h-2 rounded-full chat-pulse" style="background-color:#22c55e"></span>
                Support actif
            </div>
        </div>

        {{-- Fil de messages --}}
        <div
            class="inkpik-card flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gray-50 dark:bg-gray-900/50"
            id="support-chat-messages"
            x-data
            x-init="$el.scrollTop = $el.scrollHeight"
            x-effect="$el.scrollTop = $el.scrollHeight">

            @forelse ($this->activeMessages as $message)
            @php
                $isAdminMsg = ($message->sender_type ?? '') === 'admin' || $message->sender_id === null;
                $msgContent = $message->content ?? '';
                $msgTime    = $message->created_at->format('d/m H:i');
            @endphp

            <div class="flex {{ $isAdminMsg ? 'justify-end' : 'justify-start' }} gap-2">

                
                {{-- Avatar admin --}}
                @if ($isAdminMsg)
                <div class="inkpik-name-admin w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40
                            flex items-center justify-center text-sm flex-shrink-0 mt-1">
                    Support Ink&Pik
                </div>
                @endif

                <div class="max-w-[65%]">
                    @if (!$isAdminMsg)
                    <p class="inkpik-name text-xs text-gray-500 dark:text-gray-400 mb-1">
                        {{ $message->sender?->name ?? $message->sender?->pseudo ?? 'Utilisateur' }}
                    </p>
                    @endif

                    <div class="px-4 py-2.5 text-sm shadow-sm {{ $isAdminMsg ? 'chat-bubble-admin' : 'chat-bubble-user' }}">
                        {{ $msgContent }}
                    </div>


                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1
                              {{ $isAdminMsg ? 'text-right' : 'text-left' }}">
                        {{ $msgTime }}
                        @if ($isAdminMsg && $message->read_at)
                            · <span class="text-indigo-400">Lu</span>
                        @endif
                    </p>
                </div>


            </div>

            @empty
            <div class="flex items-center justify-center h-full py-16 text-gray-400 dark:text-gray-500">
                <div class="text-center">
                    <p class="text-3xl mb-2">💬</p>
                    <p class="text-sm">Aucun message dans cette conversation</p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Zone de réponse --}}
        <div class="inkpik-card border-t border-gray-200 dark:border-gray-700 px-6 py-4
                    bg-white dark:bg-gray-900">
            @error('newMessage')
            <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
            @enderror

            <div class="flex items-end gap-3">
                <div class="flex-1 relative">
                    <textarea
                        wire:model.defer="newMessage"
                        wire:keydown.ctrl.enter="sendReply"
                        placeholder="Répondre en tant qu'équipe Ink&Pik..."
                        rows="2"
                        maxlength="2000"
                        class="inkpik-textarea"
                    ></textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-gray-400 dark:text-gray-500">
                        {{ \Str::length($newMessage) }}/2000
                    </div>
                </div>

                <button
                    wire:click="sendReply"
                    wire:loading.attr="disabled"
                    wire:target="sendReply"
                    class="flex-shrink-0 w-11 h-11 rounded-xl bg-indigo-600
                           text-white flex items-center justify-center
                           hover:bg-indigo-700 transition-colors disabled:opacity-50
                           focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <svg wire:loading.remove wire:target="sendReply"
                         class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <svg wire:loading wire:target="sendReply"
                         class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 12 16 4.373 20.373z"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center justify-between mt-2">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Ctrl+Entrée pour envoyer · L'utilisateur sera notifié
                </p>
                <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                    <span class="w-2 h-2 rounded-full chat-pulse" style="background-color:#22c55e"></span>
                    Support actif
                </div>
            </div>
        </div>

        @else

        {{-- Aucune conversation sélectionnée --}}
        <div class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-gray-900/50">
            <div class="text-center text-gray-400 dark:text-gray-500">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">💬</span>
                </div>
                <p class="font-medium text-gray-600 dark:text-gray-300 text-lg mb-2">
                    Sélectionnez une conversation
                </p>
                <p class="text-sm max-w-xs mx-auto">
                    Cliquez sur une conversation dans la liste pour commencer à discuter
                </p>
                <div class="mt-6 flex items-center justify-center gap-2 text-xs">
                    <span class="w-2 h-2 rounded-full chat-pulse" style="background-color:#22c55e"></span>
                    Support disponible 24/7
                </div>
            </div>
        </div>

        @endif
    </div>
</div>
</x-filament-panels::page>
