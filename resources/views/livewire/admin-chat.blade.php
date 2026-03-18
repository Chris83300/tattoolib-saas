<div class="fixed bottom-20 right-6 z-50">

    {{-- Bouton flottant --}}
    <div class="relative">
        @if ($unreadCount > 0)
            <span
                class="absolute -top-1 -right-1 z-10 w-5 h-5 bg-red-500 text-white
                     text-xs rounded-full flex items-center justify-center font-bold animate-bounce">
                {{ $unreadCount }}
            </span>
        @endif

        <button wire:click="{{ $isOpen ? 'close' : 'open' }}"
            class="w-14 h-14 bg-gradient-to-br from-beige-peau to-noir-profond border border-beige-fonce
                   text-white rounded-full
                   flex items-center justify-center transition-all
                   hover:scale-105 active:scale-95">
            @if ($isOpen)
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            @endif
        </button>
    </div>

    {{-- Fenêtre de chat --}}
    @if ($isOpen)
        <div class="absolute bottom-20 right-0 w-80 sm:w-96 bg-gris-fonde rounded-2xl
                shadow-2xl border border-beige-peau/80 flex flex-col overflow-hidden shadow-lg shadow-beige-peau/20"
            style="height: 480px;" wire:poll.3s="countUnread">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-beige-fonce to-noir-profond p-4 flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-white/20 rounded-full border border-electric-blue/40 shadow-md shadow-electric-blue/30 flex items-center justify-center text-lg">
                    🛡️
                </div>
                <div>
                    <p class="font-semibold text-white text-sm">Support Ink&amp;Pik</p>
                    <p class="text-white/70 text-xs">Nous répondons en général sous 24h</p>
                </div>
            </div>

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="admin-chat-messages" wire:poll.3s="$refresh" x-data
                x-init="$el.scrollTop = $el.scrollHeight" x-effect="$el.scrollTop = $el.scrollHeight">

                @if ($this->messages->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-4xl mb-2">👋</p>
                        <p class="text-sm text-ivoire-text/70">Bonjour ! Comment pouvons-nous vous aider ?</p>
                        <p class="text-xs text-ivoire-text/40 mt-1">Posez votre question, notre équipe vous répondra.
                        </p>
                    </div>
                @endif

                @foreach ($this->messages as $message)
                    @php
                        $isAdmin = ($message->sender_type ?? '') === 'admin' || $message->sender_id === null;
                        $isUser = !$isAdmin;
                    @endphp

                    <div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }} gap-2">
                        @if ($isAdmin)
                            <div
                                class="w-7 h-7 bg-indigo-100 rounded-full flex items-center
                            justify-center text-xs flex-shrink-0 mt-1">
                                🛡️
                            </div>
                        @endif

                        <div class="max-w-[75%]">
                            <div
                                class="px-3 py-2 rounded-2xl text-sm
                        {{ $isAdmin
                            ? 'bg-electric-blue/80 text-ivoire-text rounded-tl-none'
                            : 'bg-beige-peau text-noir-profond rounded-tr-none' }}">
                                {{ $message->content }}
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5 {{ $isUser ? 'text-right' : 'text-left' }}">
                                {{ $message->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Input --}}
            <div class="border-t border-gray-100 p-3">
                @error('newMessage')
                    <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                @enderror

                <div class="flex items-end gap-2">
                    <textarea wire:model.defer="newMessage" wire:keydown.enter.prevent="sendMessage" placeholder="Écrivez votre message..."
                        rows="1"
                        class="flex-1 resize-none border border-beige-peau/40 rounded-xl px-3 py-2
                           text-sm focus:outline-none focus:ring-2 focus:ring-electric-blue/60
                           max-h-24 overflow-y-auto text-ivoire-text"
                        x-data x-on:input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"></textarea>

                    <button wire:click="sendMessage" wire:loading.attr="disabled"
                        class="w-9 h-9 bg-beige-fonce text-white rounded-xl flex items-center
                           justify-center hover:bg-beige-peau transition flex-shrink-0
                           disabled:opacity-50">
                        <svg wire:loading.remove wire:target="sendMessage" class="w-4 h-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <svg wire:loading wire:target="sendMessage" class="w-4 h-4 animate-spin" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 12 16 4.373 20.373z" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1 text-center">Entrée pour envoyer</p>
            </div>
        </div>
    @endif
    
</div>
