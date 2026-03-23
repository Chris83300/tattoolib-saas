<x-filament-widgets::widget>
    <x-filament::section heading="Performance par artiste" icon="heroicon-o-user-group">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Artiste</th>
                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">RDV (mois)</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">Tendance</th>
                        <th class="py-2 px-3 text-right text-xs font-medium text-gray-500 uppercase">CA (mois)</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">Tendance CA</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">Complétés</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">En attente</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">Note</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">Clients</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">Stripe</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($this->getArtistStats() as $artist)
                        <tr>
                            <td class="py-2.5 px-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background: {{ $artist['color'] }}"></span>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $artist['name'] }}</p>
                                        @if ($artist['pseudo'])
                                            <p class="text-xs text-gray-500">{{ $artist['pseudo'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-xs text-gray-500">{{ $artist['type'] }}</td>
                            <td class="py-2.5 px-3 text-center font-medium">{{ $artist['bookings_this_month'] }}</td>
                            <td class="py-2.5 px-3 text-center">
                                @if ($artist['bookings_change_pct'] > 0)
                                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-success-600 dark:text-success-400">
                                        <x-heroicon-m-arrow-trending-up class="w-3.5 h-3.5" />
                                        +{{ $artist['bookings_change_pct'] }}%
                                    </span>
                                @elseif ($artist['bookings_change_pct'] < 0)
                                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-danger-600 dark:text-danger-400">
                                        <x-heroicon-m-arrow-trending-down class="w-3.5 h-3.5" />
                                        {{ $artist['bookings_change_pct'] }}%
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">= 0%</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right font-medium text-primary-600 dark:text-primary-400">
                                {{ number_format($artist['revenue_this_month'], 2, ',', ' ') }} €
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if ($artist['revenue_change_pct'] > 0)
                                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-success-600 dark:text-success-400">
                                        <x-heroicon-m-arrow-trending-up class="w-3.5 h-3.5" />
                                        +{{ $artist['revenue_change_pct'] }}%
                                    </span>
                                @elseif ($artist['revenue_change_pct'] < 0)
                                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-danger-600 dark:text-danger-400">
                                        <x-heroicon-m-arrow-trending-down class="w-3.5 h-3.5" />
                                        {{ $artist['revenue_change_pct'] }}%
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">= 0%</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center text-success-600 dark:text-success-400">{{ $artist['completed'] }}</td>
                            <td class="py-2.5 px-3 text-center {{ $artist['pending'] > 0 ? 'text-warning-600 dark:text-warning-400 font-bold' : 'text-gray-400' }}">
                                {{ $artist['pending'] }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if ($artist['avg_rating'] > 0)
                                    <span class="text-primary-600 dark:text-primary-400 font-medium">{{ number_format($artist['avg_rating'], 1) }}</span>
                                    <span class="text-xs text-gray-400">({{ $artist['reviews_count'] }})</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center">{{ $artist['clients_count'] }}</td>
                            <td class="py-2.5 px-3 text-center">
                                @if ($artist['stripe_connected'])
                                    <x-heroicon-m-check-circle class="w-5 h-5 text-success-500 mx-auto" />
                                @else
                                    <x-heroicon-m-x-circle class="w-5 h-5 text-danger-400 mx-auto" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-6 text-center text-gray-400">Aucun artiste dans le studio</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
