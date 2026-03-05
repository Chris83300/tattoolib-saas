@extends('pdf.layout')

@section('title', 'Reçu de prestation')
@section('doc-type', 'Reçu de prestation')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-REC-' . str_pad($booking->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

    <div class="alert-box">
        <strong>Information :</strong> Ce document est un reçu de prestation et ne constitue pas une facture
        au sens fiscal du terme. Pour toute demande de facture, veuillez contacter le professionnel directement.
    </div>

    <h2>Parties</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Client</div>
            <div class="info-value">{{ trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')) ?: '—' }}</div>
            <div class="info-label">Email</div>
            <div class="info-value">{{ $client?->email ?? '—' }}</div>
            <div class="info-label">Téléphone</div>
            <div class="info-value">{{ $client?->phone ?? '—' }}</div>
        </div>
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
            <div class="info-label">Studio</div>
            <div class="info-value">
                @if ($isStudio)
                    {{ $professional?->name ?? '—' }}
                @else
                    {{ $professional?->studio?->name ?? 'Indépendant' }}
                @endif
            </div>
        </div>
    </div>

    <h2>Détail de la prestation</h2>
    <div class="info-grid">
        <div class="info-col">
            @if ($booking->appointment_datetime)
                <div class="info-label">Date du rendez-vous</div>
                <div class="info-value">{{ $booking->appointment_datetime->format('d/m/Y à H:i') }}</div>
            @endif
            @if ($booking->body_zone)
                <div class="info-label">Zone corporelle</div>
                <div class="info-value">{{ $booking->body_zone }}</div>
            @endif
        </div>
        <div class="info-col">
            @if ($booking->tattoo_size)
                <div class="info-label">Taille</div>
                <div class="info-value">{{ $booking->tattoo_size }}</div>
            @endif
            @if ($booking->description)
                <div class="info-label">Description</div>
                <div class="info-value">{{ $booking->description }}</div>
            @endif
        </div>
    </div>

    <h2>Récapitulatif financier</h2>
    <table>
        <thead>
            <tr>
                <th>Poste</th>
                <th style="text-align: right;">Montant</th>
                <th style="text-align: right;">Date</th>
            </tr>
        </thead>
        <tbody>
            @if ($booking->total_deposit_amount || $booking->deposit_amount)
                @php $depositAmount = $booking->total_deposit_amount ?? $booking->deposit_amount; @endphp
                <tr>
                    <td>Acompte</td>
                    <td style="text-align: right;">{{ number_format($depositAmount, 2, ',', ' ') }} €</td>
                    <td style="text-align: right;">{{ $booking->deposit_paid_at?->format('d/m/Y') ?? '—' }}</td>
                </tr>
            @endif
            @if ($booking->balance_amount)
                <tr>
                    <td>Solde</td>
                    <td style="text-align: right;">{{ number_format($booking->balance_amount, 2, ',', ' ') }} €</td>
                    <td style="text-align: right;">{{ $booking->balance_paid_at?->format('d/m/Y') ?? '—' }}</td>
                </tr>
            @endif
            @if ($booking->total_price)
                <tr>
                    <td><strong>Total prestation</strong></td>
                    <td style="text-align: right;"><strong>{{ number_format($booking->total_price, 2, ',', ' ') }}
                            €</strong></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    @if ($booking->deposit_paid_at)
        <div class="alert-box mt-10">
            <strong>Acompte reçu :</strong> Le paiement de l'acompte a été confirmé le
            {{ $booking->deposit_paid_at->format('d/m/Y à H:i') }}.
            @if ($booking->balance_paid_at)
                Le solde a été réglé le {{ $booking->balance_paid_at->format('d/m/Y à H:i') }}.
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
            <strong>Le client :</strong>
            <div class="signature-line"></div>
            <div class="signature-label">Date et signature (accusé de réception)</div>
        </div>
    </div>

@endsection
