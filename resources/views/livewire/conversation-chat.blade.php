<div class="flex flex-col h-screen bg-gray-50" wire:init="markMessagesAsRead">
    <!-- Header -->
    <div class="bg-white border-b px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-[#0A0A0A]">
                    {{ $isTattooer ? $clientName : $artistName }}
                </h1>
                <p class="text-sm text-gray-500">
                    Projet: {{ $bookingRequest->tattoo_description }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <span
                    class="px-3 py-1 rounded-full text-xs font-medium
                    {{ $bookingRequest->status === \App\Enums\BookingRequestStatus::COMPLETED ? 'bg-green-100 text-green-800' : '' }}
                    {{ $bookingRequest->status === \App\Enums\BookingRequestStatus::IN_PROGRESS ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $bookingRequest->status === \App\Enums\BookingRequestStatus::ACCEPTED ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $bookingRequest->status === \App\Enums\BookingRequestStatus::PENDING ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ $bookingRequestStatus }}
                </span>
            </div>
        </div>
    </div>

    <!-- Financial Info (si tatoueur) -->
    @if ($isTattooer && ($financialInfo['deposit_amount'] || $financialInfo['estimated_price']))
        <div class="bg-[#D4B59E]/10 border-l-4 border-[#D4B59E] p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                @if ($financialInfo['deposit_amount'])
                    <div>
                        <span class="text-gray-600">Acompte:</span>
                        <span
                            class="font-semibold ml-2">{{ number_format($financialInfo['deposit_amount'], 2) }}€</span>
                        @if ($financialInfo['deposit_paid'])
                            <span class="text-green-600 ml-1">✓</span>
                        @endif
                    </div>
                @endif
                @if ($financialInfo['estimated_price'])
                    <div>
                        <span class="text-gray-600">Estimation:</span>
                        <span
                            class="font-semibold ml-2">{{ number_format($financialInfo['estimated_price'], 2) }}€</span>
                    </div>
                @endif
                @if ($financialInfo['final_price'])
                    <div>
                        <span class="text-gray-600">Prix final:</span>
                        <span class="font-semibold ml-2">{{ number_format($financialInfo['final_price'], 2) }}€</span>
                    </div>
                @endif
                @if ($financialInfo['remaining_amount'])
                    <div>
                        <span class="text-gray-600">Reste dû:</span>
                        <span
                            class="font-semibold ml-2">{{ number_format($financialInfo['remaining_amount'], 2) }}€</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Messages Container -->
    <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
        @if (count($messages) === 0)
            <div class="text-center text-gray-500 py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="mt-2">Aucun message pour le moment</p>
                <p class="text-sm">Soyez le premier à démarrer la conversation !</p>
            </div>
        @endif

        @foreach ($messages as $message)
            <div class="flex {{ $message['is_me'] ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-lg">
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="text-xs font-medium text-gray-600">
                            {{ $message['sender_name'] }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $message['created_at'] }}</span>
                        @if ($message['is_me'])
                            <button wire:click="deleteMessage({{ $message['id'] }})"
                                class="text-gray-400 hover:text-red-500 text-xs">
                                Supprimer
                            </button>
                        @endif
                    </div>

                    <div
                        class="{{ $message['is_me'] ? 'bg-[#D4B59E] text-white' : 'bg-white text-gray-800' }}
                                rounded-lg px-4 py-2 shadow-sm">
                        @if ($message['content'])
                            <p class="whitespace-pre-wrap">{{ $message['content'] }}</p>
                        @endif

                        @if (count($message['attachments']) > 0)
                            <div class="mt-2 space-y-2">
                                @foreach ($message['attachments'] as $attachment)
                                    @if ($attachment['is_image'])
                                        <a href="{{ $attachment['url'] }}" target="_blank"
                                            class="block rounded-lg overflow-hidden">
                                            <img src="{{ $attachment['url'] }}" alt="{{ $attachment['name'] }}"
                                                class="max-w-full h-auto rounded">
                                        </a>
                                    @else
                                        <a href="{{ $attachment['url'] }}" target="_blank"
                                            class="flex items-center space-x-2 text-sm bg-gray-100 rounded p-2 hover:bg-gray-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span>{{ $attachment['name'] }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Message Input -->
    <div class="bg-white border-t px-6 py-4">
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit="sendMessage" class="space-y-4">
            <!-- Attachments Preview -->
            @if (count($attachments) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach ($attachments as $index => $file)
                        <div class="relative group">
                            @if (str_starts_with($file->getMimeType(), 'image/'))
                                <img src="{{ $file->temporaryUrl() }}" alt="Attachment {{ $index + 1 }}"
                                    class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            @endif
                            <button type="button" wire:click="removeAttachment({{ $index }})"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex space-x-4">
                <!-- File Input -->
                <input type="file" wire:model="attachments" multiple accept="image/*,.pdf" class="hidden"
                    id="file-input">

                <label for="file-input"
                    class="flex items-center justify-center px-4 py-2 text-gray-600 hover:text-gray-800 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </label>

                <!-- Message Input -->
                <div class="flex-1">
                    <input type="text" wire:model="message" placeholder="Tapez votre message..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-full focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                    @error('message')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Send Button -->
                <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2 bg-[#D4B59E] text-white rounded-full hover:bg-[#C4A68E] transition-colors disabled:opacity-50">
                    <span wire:loading.remove>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </span>
                    <span wire:loading>
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                </button>
            </div>

            <p class="text-xs text-gray-500 text-center">
                Images (PNG, JPG, WebP) et PDF - Max 10MB par fichier
            </p>
        </form>
    </div>
</div>

<script nonce="{{ csp_nonce() }}">
    // Auto-scroll en bas quand de nouveaux messages arrivent
    document.addEventListener('livewire:init', () => {
        Livewire.on('scrollToBottom', () => {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });

        // Scroller en bas au chargement
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
