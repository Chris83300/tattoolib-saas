@extends('layouts.studio')

@section('title', 'Comptabilité')

@section('content')
@php
    function trendBadgeCpt(float $pct): string {
        if ($pct > 0) return '<span class="text-xs font-semibold" style="color:#4ade80">↑ ' . abs($pct) . '%</span>';
        if ($pct < 0) return '<span class="text-xs font-semibold" style="color:#f87171">↓ ' . abs($pct) . '%</span>';
        return '<span class="text-xs" style="color:rgba(255,248,240,0.3)">= 0%</span>';
    }
@endphp
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Comptabilité</h1>
            <p class="text-sm text-titane mt-1">{{ $studio->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Sélecteur année --}}
            <form method="GET" action="{{ route('studio.comptabilite') }}" class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()"
                    class="bg-gris-fonde border border-titane/30 text-ivoire-text text-sm rounded-lg px-3 py-2 focus:ring-1 focus:ring-beige-peau focus:outline-none">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            @include('partials.export-buttons', ['type' => 'studio', 'year' => $year])
        </div>
    </div>

    {{-- Stats financières --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">CA total</p>
            <p class="text-xl font-bold text-beige-peau">{{ number_format($stats['total_revenue'], 2, ',', ' ') }}€</p>
            <p class="text-[10px] text-titane mt-1">Depuis le début</p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">CA ce mois</p>
            <p class="text-xl font-bold text-beige-peau">{{ number_format($stats['revenue_this_month'], 2, ',', ' ') }}€</p>
            <div class="flex items-center gap-1 mt-1">
                {!! trendBadgeCpt($stats['revenue_change_pct']) !!}
                <span class="text-[10px] text-ivoire-text/40">vs mois précédent</span>
            </div>
        </div>
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">Acomptes encaissés</p>
            <p class="text-xl font-bold text-ivoire-text">{{ number_format($stats['total_deposits'], 2, ',', ' ') }}€</p>
            <p class="text-[10px] text-titane mt-1">Total cumulé</p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">RDV complétés</p>
            <p class="text-xl font-bold text-ivoire-text">{{ $stats['completed_bookings'] }}</p>
            <p class="text-[10px] text-titane mt-1">Toutes périodes</p>
        </div>
    </div>

    {{-- Tableau transactions --}}
    <div class="bg-gris-fonde rounded-xl border border-titane/10 overflow-hidden">
        <div class="p-4 border-b border-titane/10 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">
                Dernières transactions
                <span class="text-titane font-normal normal-case ml-2">({{ $transactions->count() }} affichées)</span>
            </h2>
        </div>

        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-[10px] text-titane uppercase tracking-wider border-b border-titane/10 bg-noir-profond/30">
                        <th class="px-4 py-3 text-left">Réf.</th>
                        <th class="px-3 py-3 text-left">Date</th>
                        <th class="px-3 py-3 text-left">Client</th>
                        <th class="px-3 py-3 text-left hidden md:table-cell">Artiste</th>
                        <th class="px-3 py-3 text-right">Total</th>
                        <th class="px-3 py-3 text-right hidden lg:table-cell">Acompte</th>
                        <th class="px-3 py-3 text-right hidden lg:table-cell">Solde</th>
                        <th class="px-3 py-3 text-center">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-titane/5">
                    @foreach($transactions as $tx)
                    <tr class="hover:bg-noir-profond/20 transition-colors">
                        <td class="px-4 py-3 font-mono text-titane">{{ $tx['reference'] }}</td>
                        <td class="px-3 py-3 text-titane whitespace-nowrap">{{ $tx['date'] }}</td>
                        <td class="px-3 py-3 text-ivoire-text font-medium max-w-[120px] truncate">{{ $tx['client'] }}</td>
                        <td class="px-3 py-3 text-titane hidden md:table-cell max-w-[120px] truncate">{{ $tx['artiste'] ?? '—' }}</td>
                        <td class="px-3 py-3 text-right font-semibold text-beige-peau whitespace-nowrap">{{ $tx['montant_total_€'] }}€</td>
                        <td class="px-3 py-3 text-right text-titane hidden lg:table-cell">{{ $tx['acompte_€'] }}€</td>
                        <td class="px-3 py-3 text-right text-titane hidden lg:table-cell">{{ $tx['solde_€'] }}€</td>
                        <td class="px-3 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                  style="background:rgba(233,198,160,0.1);color:#e9c6a0">
                                {{ is_string($tx['statut']) ? $tx['statut'] : $tx['statut'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-12 text-center">
            <p class="text-titane text-sm">Aucune transaction pour cette période</p>
        </div>
        @endif
    </div>

    {{-- Récap par artiste --}}
    @if($artistStats->count() > 0)
    <div class="bg-gris-fonde rounded-xl border border-titane/10 overflow-hidden">
        <div class="p-4 border-b border-titane/10">
            <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Récapitulatif par artiste</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-[10px] text-titane uppercase tracking-wider border-b border-titane/10 bg-noir-profond/30">
                        <th class="px-4 py-3 text-left">Artiste</th>
                        <th class="px-3 py-3 text-center">RDV total</th>
                        <th class="px-3 py-3 text-center">Complétés</th>
                        <th class="px-3 py-3 text-right">CA total</th>
                        <th class="px-3 py-3 text-right">CA ce mois</th>
                        <th class="px-3 py-3 text-right hidden lg:table-cell">% du CA total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-titane/5">
                    @php $totalRevAll = $artistStats->sum('total_revenue') ?: 1; @endphp
                    @foreach($artistStats as $artist)
                    <tr class="hover:bg-noir-profond/20 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: {{ $artist['color'] }}"></span>
                                <div>
                                    <p class="font-semibold text-ivoire-text text-xs">{{ $artist['pseudo'] ?: $artist['name'] }}</p>
                                    <p class="text-[10px] text-titane">{{ $artist['type'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-center text-ivoire-text">{{ $artist['total_bookings'] }}</td>
                        <td class="px-3 py-3 text-center text-ivoire-text">{{ $artist['completed'] }}</td>
                        <td class="px-3 py-3 text-right font-semibold text-beige-peau">{{ number_format($artist['total_revenue'], 2, ',', ' ') }}€</td>
                        <td class="px-3 py-3 text-right text-beige-peau">{{ number_format($artist['revenue_this_month'], 2, ',', ' ') }}€</td>
                        <td class="px-3 py-3 text-right hidden lg:table-cell text-titane">
                            {{ number_format($artist['total_revenue'] / $totalRevAll * 100, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
