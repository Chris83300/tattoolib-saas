@extends('pdf.layout')

@section('title', 'Fiche de traçabilité')
@section('doc-type', 'Fiche de traçabilité')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-TR-' . str_pad($record->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

    <div class="alert-box">
        <strong>⚕ Obligation légale :</strong> Ce document est établi conformément aux obligations de traçabilité
        applicables aux tatoueurs (identification des lots d'encres, aiguilles, et équipements stériles).
    </div>

    <h2>Informations de la session</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Date de procédure</div>
            <div class="info-value">{{ $record->procedure_date?->format('d/m/Y') ?? '—' }}</div>
            <div class="info-label">Heure de début</div>
            <div class="info-value">{{ $record->procedure_start_time?->format('H:i') ?? '—' }}</div>
            <div class="info-label">Heure de fin</div>
            <div class="info-value">{{ $record->procedure_end_time?->format('H:i') ?? '—' }}</div>
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
            @if ($record->room_number)
                <div class="info-label">Numéro de salle</div>
                <div class="info-value">{{ $record->room_number }}</div>
            @endif
        </div>
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

    <h2>Aiguilles utilisées</h2>
    @if (!empty($needles))
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Marque</th>
                    <th>N° de lot</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($needles as $needle)
                    <tr>
                        <td>{{ $needle['type'] ?? 'Aiguille' }}</td>
                        <td>{{ $needle['brand'] ?? '—' }}</td>
                        <td>{{ $needle['lot_number'] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="info-value text-muted">Aucune aiguille enregistrée.</div>
    @endif

    <h2>Encres utilisées</h2>
    @if (!empty($inks))
        <table>
            <thead>
                <tr>
                    <th>Marque</th>
                    <th>Couleur</th>
                    <th>N° de lot</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inks as $ink)
                    <tr>
                        <td>{{ $ink['brand'] ?? '—' }}</td>
                        <td>{{ $ink['color'] ?? '—' }}</td>
                        <td>{{ $ink['lot_number'] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="info-value text-muted">Aucune encre enregistrée.</div>
    @endif

    {{-- Informations de stérilisation depuis le JSON --}}
    @php
        $sterileEquipment = is_array($record->sterile_equipment) ? $record->sterile_equipment : [];
        $sterilizationDate = $sterileEquipment['sterilization_date'] ?? null;
        $sterilizationLotNumber = $sterileEquipment['sterilization_lot_number'] ?? null;
        $autoclaveCycleNumber = $sterileEquipment['autoclave_cycle_number'] ?? null;
    @endphp
    @if (
        $sterilizationDate ||
            $sterilizationLotNumber ||
            $autoclaveCycleNumber ||
            $record->autoclave_batch_number ||
            $record->autoclave_test_date)
        <h2>Stérilisation</h2>
        <div class="info-grid">
            @if ($sterilizationDate)
                <div class="info-col">
                    <div class="info-label">Date de stérilisation</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($sterilizationDate)->format('d/m/Y') }}</div>
                </div>
            @endif
            @if ($sterilizationLotNumber)
                <div class="info-col">
                    <div class="info-label">N° de lot stérilisation</div>
                    <div class="info-value">{{ $sterilizationLotNumber }}</div>
                </div>
            @endif
            @if ($autoclaveCycleNumber)
                <div class="info-col">
                    <div class="info-label">N° de cycle autoclave</div>
                    <div class="info-value">{{ $autoclaveCycleNumber }}</div>
                </div>
            @endif
            @if ($record->autoclave_batch_number)
                <div class="info-col">
                    <div class="info-label">N° de lot autoclave</div>
                    <div class="info-value">{{ $record->autoclave_batch_number }}</div>
                </div>
            @endif
            @if ($record->autoclave_test_date)
                <div class="info-col">
                    <div class="info-label">Date du test autoclave</div>
                    <div class="info-value">{{ $record->autoclave_test_date->format('d/m/Y') }}</div>
                </div>
            @endif
        </div>
    @endif

    {{-- Photos de lots uploadées --}}
    @if ($record->getMedia('lot_photos')->count() > 0)
        <h2>Photos des lots</h2>
        <div class="info-value text-muted">Photos des lots et équipements uploadées dans le système.</div>
        <div class="info-value">
            Nombre de photos : {{ $record->getMedia('lot_photos')->count() }}
        </div>
    @endif

    @if ($record->procedure_notes || $record->client_condition_notes || $record->equipment_notes)
        <h2>Observations</h2>
        @if ($record->procedure_notes)
            <h3>Notes de procédure</h3>
            <div class="info-value">{{ $record->procedure_notes }}</div>
        @endif
        @if ($record->client_condition_notes)
            <h3>État du client</h3>
            <div class="info-value">{{ $record->client_condition_notes }}</div>
        @endif
        @if ($record->equipment_notes)
            <h3>Notes équipement</h3>
            <div class="info-value">{{ $record->equipment_notes }}</div>
        @endif
    @endif

    <div class="info-grid mt-10">
        <div class="info-col">
            <div class="info-label">Traçabilité vérifiée par l'artiste</div>
            <div class="info-value">{{ $record->tattooer_verified_traceability ? 'Oui' : 'Non' }}</div>
        </div>
        @if ($record->verified_at)
            <div class="info-col">
                <div class="info-label">Vérifié le</div>
                <div class="info-value">{{ $record->verified_at->format('d/m/Y à H:i') }}</div>
            </div>
        @endif
    </div>

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
            <div class="signature-label">Date et signature</div>
        </div>
    </div>

@endsection
