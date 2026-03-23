<x-filament-panels::page>

    {{-- Stats financières --}}
    @php $stats = $this->getStats(); @endphp
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-filament::section>
            <p class="text-xs text-gray-500">CA total</p>
            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                {{ number_format($stats['total_revenue'] ?? 0, 2, ',', ' ') }} €
            </p>
        </x-filament::section>

        <x-filament::section>
            <p class="text-xs text-gray-500">CA ce mois</p>
            <p class="text-2xl font-bold text-success-600 dark:text-success-400">
                {{ number_format($stats['revenue_this_month'] ?? 0, 2, ',', ' ') }} €
            </p>
        </x-filament::section>

        <x-filament::section>
            <p class="text-xs text-gray-500">Acomptes encaissés</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['total_deposits'] ?? 0, 2, ',', ' ') }} €
            </p>
        </x-filament::section>

        <x-filament::section>
            <p class="text-xs text-gray-500">RDV complétés</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $stats['completed_bookings'] ?? 0 }}
            </p>
        </x-filament::section>
    </div>

    {{-- CA par artiste --}}
    <x-filament::section heading="CA par artiste" class="mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Artiste</th>
                        <th class="py-2 px-3 text-right text-xs font-medium text-gray-500 uppercase">CA total</th>
                        <th class="py-2 px-3 text-right text-xs font-medium text-gray-500 uppercase">CA ce mois</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">RDV</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">% du total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @php
                        $artistStats = $this->getArtistStats();
                        $totalCA = array_sum(array_column($artistStats, 'total_revenue')) ?: 1;
                    @endphp
                    @forelse ($artistStats as $artist)
                        <tr>
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $artist['color'] }}"></span>
                                    <span class="font-medium">{{ $artist['pseudo'] ?: $artist['name'] }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-right font-medium">{{ number_format($artist['total_revenue'], 2, ',', ' ') }} €</td>
                            <td class="py-2 px-3 text-right">{{ number_format($artist['revenue_this_month'], 2, ',', ' ') }} €</td>
                            <td class="py-2 px-3 text-center">{{ $artist['total_bookings'] }}</td>
                            <td class="py-2 px-3 text-center">
                                {{ round($artist['total_revenue'] / $totalCA * 100, 1) }}%
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-400">Aucun artiste</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    {{-- Dernières transactions --}}
    <x-filament::section heading="Dernières transactions">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Réf.</th>
                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Artiste</th>
                        <th class="py-2 px-3 text-right text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="py-2 px-3 text-right text-xs font-medium text-gray-500 uppercase">Acompte</th>
                        <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($this->getTransactions() as $tx)
                        <tr>
                            <td class="py-2 px-3 font-mono text-xs text-gray-400">{{ $tx['reference'] ?? '—' }}</td>
                            <td class="py-2 px-3 text-gray-500">{{ $tx['date'] ?? '—' }}</td>
                            <td class="py-2 px-3">{{ $tx['client'] ?? '—' }}</td>
                            <td class="py-2 px-3 text-gray-500">{{ $tx['artiste'] ?? '—' }}</td>
                            <td class="py-2 px-3 text-right font-medium">{{ $tx['montant_total_€'] ?? '—' }} €</td>
                            <td class="py-2 px-3 text-right text-gray-500">{{ $tx['acompte_€'] ?? '—' }} €</td>
                            <td class="py-2 px-3 text-center">
                                <x-filament::badge :color="match($tx['statut'] ?? '') {
                                    'completed', 'fully_completed', 'balance_paid' => 'success',
                                    'pending', 'accepted', 'deposit_paid' => 'warning',
                                    'cancelled', 'rejected' => 'danger',
                                    default => 'gray'
                                }">
                                    {{ ucfirst($tx['statut'] ?? '—') }}
                                </x-filament::badge>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-4 text-center text-gray-400">Aucune transaction</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

</x-filament-panels::page>
