<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" style="min-height: 600px;">

        {{-- Liste conversations --}}
        <div class="lg:col-span-1">
            <x-filament::section heading="Conversations" class="h-full">
                <div class="space-y-1 max-h-[550px] overflow-y-auto">
                    @forelse ($this->getConversations() as $conv)
                        @php $lastMsg = $conv->messages->first(); @endphp
                        <button wire:click="selectConversation({{ $conv->id }})"
                            class="w-full text-left p-3 rounded-lg transition
                                       {{ $this->activeConversationId === $conv->id
                                           ? 'bg-primary-50 dark:bg-primary-500/10 ring-1 ring-primary-500/30'
                                           : 'hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $conv->participants->pluck('name')->join(', ') }}
                            </p>
                            <p class="text-xs text-gray-500 truncate mt-0.5">
                                {{ \Str::limit($lastMsg?->content ?? '', 50) }}
                            </p>
                            <div class="flex items-center justify-between mt-1">
                                <span
                                    class="text-[10px] text-gray-400">{{ $lastMsg?->created_at?->diffForHumans() }}</span>
                                <x-filament::badge size="sm" :color="match ($conv->type) {
                                    'booking' => 'primary',
                                    'support' => 'warning',
                                    default => 'gray',
                                }">
                                    {{ $conv->type }}
                                </x-filament::badge>
                            </div>
                        </button>
                    @empty
                        <p class="text-center text-gray-400 py-8 text-sm">Aucune conversation</p>
                    @endforelse
                </div>
            </x-filament::section>
        </div>

        {{-- Messages --}}
        <div class="lg:col-span-2">
            <x-filament::section class="h-full">
                @if ($conversation = $this->getActiveConversation())
                    @php
                        $studio = auth()->user()?->studio;
                        $artistUserIds = $studio?->artists()->pluck('user_id')->toArray() ?? [];
                        $belongs = $conversation->participants()->whereIn('users.id', $artistUserIds)->exists();
                    @endphp

                    {{-- Bandeau lecture seule --}}
                    <div
                        class="mb-4 p-2.5 rounded-lg bg-warning-50 dark:bg-warning-500/10 border border-warning-200 dark:border-warning-500/20">
                        <p class="text-xs text-warning-700 dark:text-warning-400 flex items-center gap-1.5">
                            <x-heroicon-m-eye class="w-4 h-4" />
                            Consultation en lecture seule — vous ne pouvez pas répondre.
                        </p>
                    </div>

                    {{-- Messages --}}
                    <div class="space-y-3 max-h-[480px] overflow-y-auto" id="messages-container">
                        @foreach ($conversation->messages->sortBy('created_at') as $message)
                            @php
                                $isArtist = in_array($message->sender_id, $artistUserIds);
                            @endphp
                            <div class="flex {{ $isArtist ? 'justify-end' : 'justify-start' }}">
                                <div
                                    class="max-w-[75%] p-3 rounded-xl text-sm
                                            {{ $isArtist
                                                ? 'bg-primary-500/10 text-gray-900 dark:text-white'
                                                : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-200' }}">
                                    <p
                                        class="text-[10px] font-semibold mb-1 {{ $isArtist ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500' }}">
                                        {{ $message->sender?->name ?? 'Utilisateur' }}
                                    </p>
                                    <p>{{ $message->content }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">
                                        {{ $message->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center justify-center h-full py-20">
                        <div class="text-center text-gray-400">
                            <x-heroicon-o-chat-bubble-left-right class="w-12 h-12 mx-auto mb-3 opacity-30" />
                            <p class="text-sm">Sélectionnez une conversation</p>
                        </div>
                    </div>
                @endif
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
