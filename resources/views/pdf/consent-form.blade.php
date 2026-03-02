@extends('pdf.layout')

@section('title', 'Consentement éclairé')
@section('doc-type', 'Formulaire de consentement éclairé')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-CF-' . str_pad($consentForm->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

<div class="alert-box">
    <strong>⚕ Obligation légale :</strong> Conformément à l'article R.1311-12 du Code de la Santé Publique,
    le client doit être informé des risques auxquels il s'expose avant la réalisation de l'acte.
</div>

<h2>Identité du client</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Nom complet</div>
        <div class="info-value">{{ $consentForm->client_full_name ?? ($consentForm->full_name ?? '—') }}</div>
        <div class="info-label">Date de naissance</div>
        <div class="info-value">{{ $consentForm->client_birth_date?->format('d/m/Y') ?? ($consentForm->birth_date?->format('d/m/Y') ?? '—') }}</div>
        <div class="info-label">Pièce d'identité</div>
        <div class="info-value">{{ $consentForm->client_id_type ?? ($consentForm->id_document_type ?? '—') }} — {{ $consentForm->client_id_number ?? ($consentForm->id_document_number ?? '—') }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Adresse</div>
        <div class="info-value">{{ $consentForm->client_address ?? ($consentForm->address ?? '—') }}</div>
        <div class="info-label">Téléphone</div>
        <div class="info-value">{{ $consentForm->client_phone ?? ($consentForm->phone ?? '—') }}</div>
        <div class="info-label">Email</div>
        <div class="info-value">{{ $consentForm->client_email ?? ($consentForm->email ?? '—') }}</div>
    </div>
</div>

@if ($consentForm->is_minor)
<div class="alert-box">
    <strong>Client mineur :</strong> {{ $consentForm->parent_name ?? '—' }} ({{ $consentForm->parent_relation ?? '—' }}) — N° pièce d'identité : {{ $consentForm->parent_id_number ?? '—' }}
</div>
@endif

<h2>Identité du professionnel</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Artiste</div>
        <div class="info-value">{{ $artisan?->user?->name ?? '—' }}</div>
        <div class="info-label">SIRET</div>
        <div class="info-value">{{ $artisan?->siret ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Studio</div>
        <div class="info-value">{{ $consentForm->studio?->studio_name ?? 'Indépendant' }}</div>
    </div>
</div>

<h2>Acte prévu</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Type d'acte</div>
        <div class="info-value">{{ $consentForm->act_type ?? '—' }}</div>
        <div class="info-label">Description</div>
        <div class="info-value">{{ $consentForm->act_description ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Zone corporelle</div>
        <div class="info-value">{{ $consentForm->body_zone ?? ($consentForm->body_area ?? '—') }}</div>
        @if ($consentForm->total_price)
        <div class="info-label">Prix total</div>
        <div class="info-value">{{ number_format($consentForm->total_price / 100, 2, ',', ' ') }} €</div>
        @endif
    </div>
</div>

<h2>Déclarations médicales</h2>
<table>
    <thead>
        <tr>
            <th>Question</th>
            <th>Réponse</th>
            @if ($consentForm->medical_allergies_detail || $consentForm->medical_skin_disease_detail || $consentForm->medical_other)
            <th>Détail</th>
            @endif
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Allergies</td>
            <td>{{ $consentForm->medical_allergies ? 'Oui' : 'Non' }}</td>
            @if ($consentForm->medical_allergies_detail)<td class="text-small">{{ $consentForm->medical_allergies_detail }}</td>@endif
        </tr>
        <tr>
            <td>Traitement anticoagulant</td>
            <td>{{ $consentForm->medical_anticoagulant ? 'Oui' : 'Non' }}</td>
        </tr>
        <tr>
            <td>Diabète</td>
            <td>{{ $consentForm->medical_diabetes ? 'Oui' : 'Non' }}</td>
        </tr>
        <tr>
            <td>Problème de cicatrisation</td>
            <td>{{ $consentForm->medical_cicatrisation ? 'Oui' : 'Non' }}</td>
        </tr>
        <tr>
            <td>Maladie de peau</td>
            <td>{{ $consentForm->medical_skin_disease ? 'Oui' : 'Non' }}</td>
            @if ($consentForm->medical_skin_disease_detail)<td class="text-small">{{ $consentForm->medical_skin_disease_detail }}</td>@endif
        </tr>
        <tr>
            <td>VIH / Hépatite</td>
            <td>{{ $consentForm->medical_vih_hepatite ? 'Oui' : 'Non' }}</td>
        </tr>
        <tr>
            <td>Grossesse</td>
            <td>{{ $consentForm->medical_pregnant ? 'Oui' : 'Non' }}</td>
        </tr>
        <tr>
            <td>Roaccutane</td>
            <td>{{ $consentForm->medical_roaccutane ? 'Oui' : 'Non' }}</td>
        </tr>
        <tr>
            <td>Tendance aux chéloïdes</td>
            <td>{{ $consentForm->medical_cheloide ? 'Oui' : 'Non' }}</td>
        </tr>
        @if ($consentForm->medical_other)
        <tr>
            <td>Autre condition</td>
            <td colspan="2">{{ $consentForm->medical_other }}</td>
        </tr>
        @endif
    </tbody>
</table>

<h2>Confirmations et consentement</h2>
<ul class="checklist">
    @if ($consentForm->confirm_medical_sincere) <li>Informations médicales fournies sincèrement et complètement</li> @endif
    @if ($consentForm->confirm_risks_informed) <li>Informé(e) des risques liés à la prestation</li> @endif
    @if ($consentForm->confirm_info_sheet_read) <li>Fiche d'information lue et comprise</li> @endif
    @if ($consentForm->confirm_aftercare_received) <li>Consignes de soins post-prestation reçues</li> @endif
    @if ($consentForm->confirm_not_intoxicated) <li>Déclare ne pas être sous l'emprise de substances</li> @endif
    @if ($consentForm->confirm_over_18_or_authorized) <li>Majeur(e) ou autorisé(e) par un représentant légal</li> @endif
    @if ($consentForm->image_authorization) <li>Autorise la prise de photos à des fins professionnelles</li> @endif
</ul>

@if ($consentForm->handwritten_mention)
<div class="info-grid mt-10">
    <div class="info-col">
        <div class="info-label">Mention manuscrite</div>
        <div class="info-value">{{ $consentForm->handwritten_mention }}</div>
    </div>
</div>
@endif

<div class="signature-block mt-20">
    <div class="signature-col">
        <strong>Le professionnel :</strong>
        <div class="signature-line">
            @if ($consentForm->signed_at)
                <div class="text-small text-muted" style="padding-top: 5px;">Signé le {{ $consentForm->signed_at->format('d/m/Y à H:i') }}</div>
            @endif
        </div>
        <div class="signature-label">Date et signature</div>
    </div>
    <div class="signature-spacer"></div>
    <div class="signature-col">
        <strong>Le client :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Date, signature et mention « lu et approuvé »</div>
    </div>
</div>

@endsection
