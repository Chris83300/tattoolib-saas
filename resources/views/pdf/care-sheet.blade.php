@extends('pdf.layout')

@section('title', 'Fiche de soins')
@section('doc-type', 'Fiche de soins post-prestation')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-CS-' . str_pad($careSheet->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

<div class="alert-box">
    <strong>⚕ Obligation légale :</strong> Ce document est remis conformément à l'article R.1311-12 du Code de la Santé Publique.
    Il détaille les précautions à respecter après la réalisation de la prestation.
</div>

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
    </div>
</div>

<h2>Informations de l'artiste</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Artiste</div>
        <div class="info-value">{{ $artisan?->user?->name ?? '—' }}</div>
        <div class="info-label">SIRET</div>
        <div class="info-value">{{ $artisan?->siret ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Studio</div>
        <div class="info-value">{{ $careSheet->studio?->name ?? 'Indépendant' }}</div>
        <div class="info-label">Date de la prestation</div>
        <div class="info-value">{{ $careSheet->created_at?->format('d/m/Y') ?? '—' }}</div>
    </div>
</div>

<h2>Détail du tatouage</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Description</div>
        <div class="info-value">{{ $careSheet->tattoo_description ?? '—' }}</div>
        <div class="info-label">Zone corporelle</div>
        <div class="info-value">{{ $careSheet->tattoo_location ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Taille</div>
        <div class="info-value">{{ $careSheet->tattoo_size ?? '—' }}</div>
        <div class="info-label">Technique utilisée</div>
        <div class="info-value">{{ $careSheet->technique_used ?? '—' }}</div>
    </div>
</div>

@if ($careSheet->allergies_details || $careSheet->skin_conditions_details || $careSheet->medications_details)
<h2>Informations médicales déclarées</h2>
<div class="alert-box">
    @if ($careSheet->allergies_details)
        <strong>Allergies :</strong> {{ $careSheet->allergies_details }}<br>
    @endif
    @if ($careSheet->skin_conditions_details)
        <strong>Conditions de peau :</strong> {{ $careSheet->skin_conditions_details }}<br>
    @endif
    @if ($careSheet->medications_details)
        <strong>Médicaments :</strong> {{ $careSheet->medications_details }}
    @endif
    @if ($careSheet->has_diabetes)
        <br><strong>Diabète :</strong> Oui
    @endif
    @if ($careSheet->has_blood_disorders)
        <br><strong>Trouble sanguin :</strong> Oui
    @endif
    @if ($careSheet->is_pregnant)
        <br><strong>Grossesse :</strong> Oui
    @endif
</div>
@endif

@if ($careSheet->products_used)
<h3>Produits utilisés</h3>
<div class="info-value">{{ $careSheet->products_used }}</div>
@endif

@if ($careSheet->ink_colors_used)
<h3>Couleurs d'encre utilisées</h3>
<div class="info-value">{{ $careSheet->ink_colors_used }}</div>
@endif

<h2>Soins du pansement</h2>
@if ($careSheet->bandage_type || $careSheet->bandage_removal_time)
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Type de pansement</div>
        <div class="info-value">{{ $careSheet->bandage_type ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Retrait du pansement</div>
        <div class="info-value">
            @if ($careSheet->bandage_removal_time)
                {{ $careSheet->bandage_removal_time->format('d/m/Y à H:i') }}
            @else
                —
            @endif
        </div>
    </div>
</div>
@endif

<h2>Consignes de soins post-prestation</h2>

@if ($careSheet->immediate_care_instructions)
<h3>Soins immédiats (instructions personnalisées)</h3>
<div class="info-value">{{ $careSheet->immediate_care_instructions }}</div>
@endif

@if ($careSheet->washing_instructions)
<h3>Instructions de nettoyage</h3>
<div class="info-value">{{ $careSheet->washing_instructions }}</div>
@endif

@if ($careSheet->moisturizing_instructions)
<h3>Hydratation</h3>
<div class="info-value">{{ $careSheet->moisturizing_instructions }}</div>
@endif

@if ($careSheet->activity_restrictions)
<h3>Restrictions d'activités</h3>
<div class="info-value">{{ $careSheet->activity_restrictions }}</div>
@endif

@if ($careSheet->sun_exposure_warnings)
<h3>Exposition solaire</h3>
<div class="info-value">{{ $careSheet->sun_exposure_warnings }}</div>
@endif

{{-- Consignes générales si pas de consignes personnalisées --}}
@if (!$careSheet->immediate_care_instructions && !$careSheet->washing_instructions)
<ul class="checklist">
    <li>Garder le pansement initial pendant 2 à 4 heures minimum</li>
    <li>Laver délicatement la zone à l'eau tiède et au savon doux (pH neutre, sans parfum)</li>
    <li>Sécher en tamponnant avec un papier absorbant propre (ne pas frotter)</li>
    <li>Appliquer une fine couche de crème cicatrisante 2 à 3 fois par jour</li>
    <li>Ne pas gratter, ne pas arracher les croûtes ou peaux mortes</li>
    <li>Éviter les bains (piscine, mer, baignoire) pendant 3 à 4 semaines</li>
    <li>Éviter l'exposition directe au soleil pendant au moins 4 semaines</li>
    <li>Surveiller tout signe d'infection et consulter un médecin si nécessaire</li>
</ul>
@endif

@if ($careSheet->healing_estimated_date || $careSheet->first_touchup_date)
<h3>Dates de suivi</h3>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Cicatrisation estimée</div>
        <div class="info-value">{{ $careSheet->healing_estimated_date?->format('d/m/Y') ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Retouche prévue</div>
        <div class="info-value">{{ $careSheet->first_touchup_date?->format('d/m/Y') ?? '—' }}</div>
    </div>
</div>
@endif

<div class="alert-box mt-20">
    <strong>⚠ Important :</strong> En cas de réaction anormale (douleur persistante, gonflement excessif, écoulement purulent, fièvre),
    consultez immédiatement un médecin ou rendez-vous aux urgences. Vous pouvez également signaler tout effet indésirable
    sur le portail national des signalements : <strong>signalement.social-sante.gouv.fr</strong>
</div>

<div class="signature-block mt-20">
    <div class="signature-col">
        <strong>L'artiste :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Signature</div>
    </div>
    <div class="signature-spacer"></div>
    <div class="signature-col">
        <strong>Le client :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Signature (lu et approuvé)</div>
    </div>
</div>

@endsection
