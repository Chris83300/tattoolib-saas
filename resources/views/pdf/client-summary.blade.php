@extends('pdf.layout')

@section('title', 'Fiche client complète')
@section('doc-type', 'Récapitulatif fiche client')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-CS-' . str_pad($client->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

<h2>Informations du client</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Nom complet</div>
        <div class="info-value">{{ trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')) ?: '—' }}</div>
        <div class="info-label">Date de naissance</div>
        <div class="info-value">{{ $client?->birth_date?->format('d/m/Y') ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Email</div>
        <div class="info-value">{{ $client?->email ?? '—' }}</div>
        <div class="info-label">Téléphone</div>
        <div class="info-value">{{ $client?->phone ?? '—' }}</div>
        @if ($client?->address)
        <div class="info-label">Adresse</div>
        <div class="info-value">{{ $client->address }}</div>
        @endif
    </div>
</div>

<h2>Artiste référent</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Artiste</div>
        <div class="info-value">{{ $artisan?->user?->name ?? '—' }}</div>
        <div class="info-label">SIRET</div>
        <div class="info-value">{{ $artisan?->siret ?? '—' }}</div>
    </div>
</div>

{{-- ===== FICHES DE SOINS ===== --}}
@if ($careSheets->isNotEmpty())
<h2>Fiches de soins ({{ $careSheets->count() }})</h2>
@foreach ($careSheets as $cs)
<div style="border: 1px solid #e0d5c8; border-radius: 4px; padding: 10px; margin-bottom: 10px;">
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Date</div>
            <div class="info-value">{{ $cs->created_at?->format('d/m/Y') ?? '—' }}</div>
            <div class="info-label">Description</div>
            <div class="info-value">{{ $cs->tattoo_description ?? '—' }}</div>
        </div>
        <div class="info-col">
            <div class="info-label">Zone</div>
            <div class="info-value">{{ $cs->tattoo_location ?? '—' }}</div>
            <div class="info-label">Technique</div>
            <div class="info-value">{{ $cs->technique_used ?? '—' }}</div>
        </div>
    </div>
    @if ($cs->healing_estimated_date || $cs->first_touchup_date)
    <div class="info-grid">
        @if ($cs->healing_estimated_date)
        <div class="info-col">
            <div class="info-label">Cicatrisation estimée</div>
            <div class="info-value">{{ $cs->healing_estimated_date->format('d/m/Y') }}</div>
        </div>
        @endif
        @if ($cs->first_touchup_date)
        <div class="info-col">
            <div class="info-label">Retouche prévue</div>
            <div class="info-value">{{ $cs->first_touchup_date->format('d/m/Y') }}</div>
        </div>
        @endif
    </div>
    @endif
</div>
@endforeach
@endif

{{-- ===== FORMULAIRES DE CONSENTEMENT ===== --}}
@if ($consentForms->isNotEmpty())
<h2>Consentements signés ({{ $consentForms->count() }})</h2>
<table>
    <thead>
        <tr>
            <th>Date signature</th>
            <th>Type d'acte</th>
            <th>Zone</th>
            <th>Mineur</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($consentForms as $cf)
        <tr>
            <td>{{ $cf->signed_at?->format('d/m/Y') ?? $cf->created_at?->format('d/m/Y') ?? '—' }}</td>
            <td>{{ $cf->act_type ?? '—' }}</td>
            <td>{{ $cf->body_zone ?? '—' }}</td>
            <td>{{ $cf->is_minor ? 'Oui' : 'Non' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- ===== FICHES DE TRAÇABILITÉ ===== --}}
@if ($traceRecords->isNotEmpty())
<h2>Fiches de traçabilité ({{ $traceRecords->count() }})</h2>
<table>
    <thead>
        <tr>
            <th>Date procédure</th>
            <th>Aiguilles</th>
            <th>Encres</th>
            <th>N° lot autoclave</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($traceRecords as $tr)
        <tr>
            <td>{{ $tr->procedure_date?->format('d/m/Y') ?? '—' }}</td>
            <td>{{ count($tr->needles_used ?? []) }}</td>
            <td>{{ count($tr->inks_used ?? []) }}</td>
            <td>{{ $tr->autoclave_batch_number ?? '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if ($careSheets->isEmpty() && $consentForms->isEmpty() && $traceRecords->isEmpty())
<div class="alert-box mt-10">
    Aucun document enregistré pour ce client.
</div>
@endif

<div class="info-grid mt-20">
    <div class="info-col">
        <div class="info-label">Document généré le</div>
        <div class="info-value">{{ $generatedAt->format('d/m/Y à H:i') }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Total documents</div>
        <div class="info-value">
            {{ $careSheets->count() }} fiche(s) de soins,
            {{ $consentForms->count() }} consentement(s),
            {{ $traceRecords->count() }} traçabilité(s)
        </div>
    </div>
</div>

@endsection
