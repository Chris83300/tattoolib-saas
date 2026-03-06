@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-ivoire-text">Statistiques</h1>
        <p class="text-sm text-titane mt-1">Vue d'ensemble de l'activité du studio</p>
    </div>

    <!-- Compteurs globaux -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <div class="text-3xl font-bold text-beige-peau">{{ $totalRequests }}</div>
            <div class="text-sm text-titane mt-1">Demandes totales</div>
        </div>
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <div class="text-3xl font-bold text-ambre-warning">{{ $pendingRequests }}</div>
            <div class="text-sm text-titane mt-1">En attente</div>
        </div>
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <div class="text-3xl font-bold text-vert-succes">{{ $completedAll }}</div>
            <div class="text-sm text-titane mt-1">Complétées</div>
        </div>
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <div class="text-3xl font-bold text-rouge-alerte">{{ $cancelledAll }}</div>
            <div class="text-sm text-titane mt-1">Annulées</div>
        </div>
    </div>

    <!-- Revenus mensuels -->
    <div class="bg-gris-fonde rounded-xl p-5">
        <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide mb-4">Revenus mensuels (6 derniers mois)</h2>
        @php $maxRevenue = max(collect($monthlyRevenue)->pluck('revenue')->toArray() ?: [1]); @endphp
        <div class="space-y-3">
            @foreach($monthlyRevenue as $month)
                <div class="flex items-center gap-3">
                    <span class="text-xs text-titane w-16 shrink-0">{{ $month['label'] }}</span>
                    <div class="flex-1 bg-noir-profond rounded-full h-5 overflow-hidden">
                        <div class="h-full bg-beige-peau rounded-full transition-all duration-700"
                            style="width: {{ $maxRevenue > 0 ? round(($month['revenue'] / $maxRevenue) * 100) : 0 }}%">
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-ivoire-text w-20 text-right shrink-0">
                        {{ number_format($month['revenue'], 0, ',', ' ') }}€
                    </span>
                </div>
            @endforeach
        </div>
        <p class="text-xs text-titane/60 mt-3">Basé sur les acomptes des demandes complétées.</p>
    </div>

    <!-- Tableau par artiste -->
    @if($artistsStats->count() > 0)
        <div class="bg-gris-fonde rounded-xl overflow-hidden">
            <div class="p-4 border-b border-titane/10">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Statistiques par artiste</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-noir-profond/50">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs text-titane font-semibold uppercase tracking-wide">Artiste</th>
                            <th class="text-center px-4 py-3 text-xs text-titane font-semibold uppercase tracking-wide">Type</th>
                            <th class="text-center px-4 py-3 text-xs text-titane font-semibold uppercase tracking-wide">Demandes</th>
                            <th class="text-center px-4 py-3 text-xs text-titane font-semibold uppercase tracking-wide">En attente</th>
                            <th class="text-center px-4 py-3 text-xs text-titane font-semibold uppercase tracking-wide">Complétées</th>
                            <th class="text-right px-4 py-3 text-xs text-titane font-semibold uppercase tracking-wide">Revenu (acomptes)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-titane/10">
                        @foreach($artistsStats as $stat)
                            <tr class="hover:bg-noir-profond/30 transition-colors">
                                <td class="px-4 py-3 text-ivoire-text font-semibold">{{ $stat['name'] }}</td>
                                <td class="px-4 py-3 text-center text-titane">
                                    {{ $stat['type'] === 'piercer' ? 'Pierceur' : 'Tatoueur' }}
                                </td>
                                <td class="px-4 py-3 text-center text-beige-peau">{{ $stat['total'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="{{ $stat['pending'] > 0 ? 'text-ambre-warning font-semibold' : 'text-titane' }}">
                                        {{ $stat['pending'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-vert-succes font-semibold">{{ $stat['completed'] }}</td>
                                <td class="px-4 py-3 text-right text-beige-peau font-semibold">
                                    {{ number_format($stat['revenue'], 0, ',', ' ') }}€
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-noir-profond/50 border-t border-titane/20">
                        <tr>
                            <td class="px-4 py-3 text-ivoire-text font-bold" colspan="2">Total</td>
                            <td class="px-4 py-3 text-center text-beige-peau font-bold">{{ $artistsStats->sum('total') }}</td>
                            <td class="px-4 py-3 text-center text-ambre-warning font-bold">{{ $artistsStats->sum('pending') }}</td>
                            <td class="px-4 py-3 text-center text-vert-succes font-bold">{{ $artistsStats->sum('completed') }}</td>
                            <td class="px-4 py-3 text-right text-beige-peau font-bold">
                                {{ number_format($artistsStats->sum('revenue'), 0, ',', ' ') }}€
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <div class="bg-gris-fonde rounded-xl p-6 text-center">
            <p class="text-sm text-titane">Aucun artiste actif dans le studio.</p>
        </div>
    @endif

    <!-- Taux de conversion -->
    @if($totalRequests > 0)
        <div class="bg-gris-fonde rounded-xl p-5">
            <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide mb-4">Taux de conversion</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 rounded-lg bg-noir-profond">
                    <div class="text-2xl font-bold text-vert-succes">
                        {{ round(($completedAll / $totalRequests) * 100) }}%
                    </div>
                    <div class="text-xs text-titane mt-1">Taux de complétion</div>
                </div>
                <div class="text-center p-4 rounded-lg bg-noir-profond">
                    <div class="text-2xl font-bold text-rouge-alerte">
                        {{ round(($cancelledAll / $totalRequests) * 100) }}%
                    </div>
                    <div class="text-xs text-titane mt-1">Taux d'annulation</div>
                </div>
                <div class="text-center p-4 rounded-lg bg-noir-profond">
                    <div class="text-2xl font-bold text-ambre-warning">
                        {{ round(($pendingRequests / $totalRequests) * 100) }}%
                    </div>
                    <div class="text-xs text-titane mt-1">En cours de traitement</div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
