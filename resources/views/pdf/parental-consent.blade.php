@extends('pdf.layout')

@section('title', 'Consentement parental')
@section('doc-type', 'Formulaire de consentement parental')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-PC-' . str_pad($parentalConsent->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

    <div class="alert-box">
        <strong>⚕ Obligation légale :</strong> Conformément à l'article R.1311-11 du Code de la Santé Publique,
        la réalisation d'un tatouage sur un mineur est conditionnée à l'autorisation écrite d'un représentant légal.
    </div>

    <h2>Identité du mineur</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Nom complet</div>
            <div class="info-value">{{ $consentForm?->client_full_name ?? '—' }}</div>
            <div class="info-label">Date de naissance</div>
            <div class="info-value">{{ $consentForm?->client_birth_date?->format('d/m/Y') ?? '—' }}</div>
        </div>
        <div class="info-col">
            <div class="info-label">Type d'acte</div>
            <div class="info-value">{{ $consentForm?->act_type ?? '—' }}</div>
            <div class="info-label">Zone corporelle</div>
            <div class="info-value">{{ $consentForm?->body_zone ?? '—' }}</div>
        </div>
    </div>

    @if ($consentForm?->act_description)
        <div class="info-grid">
            <div class="info-col">
                <div class="info-label">Description de l'acte</div>
                <div class="info-value">{{ $consentForm->act_description }}</div>
            </div>
        </div>
    @endif

    <h2>Identité du représentant légal</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Nom complet</div>
            <div class="info-value">{{ $parentalConsent->parent_full_name ?? '—' }}</div>
            <div class="info-label">Lien de parenté</div>
            <div class="info-value">
                @php
                    $relations = \App\Models\ParentalConsentForm::RELATIONSHIPS;
                @endphp
                {{ $relations[$parentalConsent->parent_relationship] ?? ($parentalConsent->parent_relationship ?? '—') }}
            </div>
            <div class="info-label">Pièce d'identité</div>
            <div class="info-value">{{ $parentalConsent->parent_id_document_type ?? '—' }} — N°
                {{ $parentalConsent->parent_id_document_number ?? '—' }}</div>
            @if ($parentalConsent->parent_id_document_expiry)
                <div class="info-label">Validité</div>
                <div class="info-value">{{ $parentalConsent->parent_id_document_expiry->format('d/m/Y') }}</div>
            @endif
        </div>
        <div class="info-col">
            <div class="info-label">Téléphone</div>
            <div class="info-value">{{ $parentalConsent->parent_phone ?? '—' }}</div>
            <div class="info-label">Email</div>
            <div class="info-value">{{ $parentalConsent->parent_email ?? '—' }}</div>
            <div class="info-label">Adresse</div>
            <div class="info-value">{{ $parentalConsent->parent_address ?? '—' }}</div>
        </div>
    </div>

    <h2>Identité du professionnel</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">{{ $isStudio ? 'Studio' : 'Artiste' }}</div>
            <div class="info-value">
                @if ($isStudio)
                    {{ $professional?->name ?? '—' }}
                @else
                    {{ $professional?->user?->name ?? '—' }}
                @endif
            </div>
            <div class="info-label">SIRET</div>
            <div class="info-value">{{ $professional?->siret ?? '—' }}</div>
        </div>
    </div>

    <h2>Déclarations de consentement</h2>
    <ul class="checklist">
        @if ($parentalConsent->parent_consents_to_tattoo)
            <li>J'autorise la réalisation de l'acte sur le mineur susmentionné</li>
        @endif
        @if ($parentalConsent->parent_understands_risks)
            <li>J'ai été informé(e) des risques liés à la prestation et les accepte</li>
        @endif
        @if ($parentalConsent->parent_will_supervise_aftercare)
            <li>Je m'engage à superviser les soins post-prestation du mineur</li>
        @endif
        @if ($parentalConsent->parent_consents_to_emergency_treatment)
            <li>J'autorise tout traitement d'urgence médicale si nécessaire</li>
        @endif
    </ul>

    @if ($parentalConsent->signed_at || $parentalConsent->verified_at)
        <div class="info-grid mt-10">
            @if ($parentalConsent->signed_at)
                <div class="info-col">
                    <div class="info-label">Signé le</div>
                    <div class="info-value">{{ $parentalConsent->signed_at->format('d/m/Y à H:i') }}</div>
                </div>
            @endif
            @if ($parentalConsent->verified_at)
                <div class="info-col">
                    <div class="info-label">Vérifié le</div>
                    <div class="info-value">{{ $parentalConsent->verified_at->format('d/m/Y à H:i') }}</div>
                </div>
            @endif
        </div>
    @endif

    <div class="signature-block mt-20">
        <div class="signature-col">
            <strong>Le professionnel :</strong>
            <div class="signature-line"></div>
            <div class="signature-label">Date et signature</div>
        </div>
        <div class="signature-spacer"></div>
        <div class="signature-col">
            <strong>Le représentant légal :</strong>
            <div class="signature-line"></div>
            <div class="signature-label">Date, signature et mention « lu et approuvé »</div>
        </div>
    </div>

@endsection
