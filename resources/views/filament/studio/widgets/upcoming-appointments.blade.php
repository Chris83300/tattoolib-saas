<x-filament-widgets::widget>
    <x-filament::section heading="Prochains RDV" icon="heroicon-o-calendar-days">
        <div class="space-y-2">
            @forelse ($this->getAppointments() as $booking)
                @php
                    $date =
                        $booking->confirmed_date ??
                        ($booking->appointment_datetime ? \Carbon\Carbon::parse($booking->appointment_datetime) : null);
                @endphp
                <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500/10 flex flex-col items-center justify-center">
                            <span class="text-[10px] font-bold text-primary-600 dark:text-primary-400 leading-none">
                                {{ $date?->format('d') ?? '?' }}
                            </span>
                            <span class="text-[8px] text-primary-600/70 dark:text-primary-400/70 uppercase leading-none">
                                {{ $date?->translatedFormat('M') ?? '' }}
                            </span>
                        </div>
                        <div class="truncate">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $booking->client?->user?->name ?? 'Client' }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $booking->bookable?->user?->name ?? 'Artiste' }}
                                · {{ ucfirst($booking->status->value) }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-2">
                        <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                            {{ number_format($booking->total_price ?? 0, 0, ',', ' ') }} €
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-4 text-sm">Aucun RDV à venir</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
