<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\TraceabilityRecord;
use App\Models\Appointment;
use Illuminate\Http\Request;

class TattooerTraceabilityController extends ArtisanBaseController
{
    /**
     * Enregistrer la traçabilité pour un rendez-vous
     */
    public function storeTraceability(Request $request, Appointment $appointment)
    {
        $tattooer = $this->artisan();

        // Vérifier propriété via booking request
        $bookingRequest = $appointment->bookingRequest;
        if (!$bookingRequest || $bookingRequest->bookable_id !== $tattooer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'needles' => 'nullable|array',
            'needles.*' => 'nullable|array',
            'needles.*.brand' => 'nullable|string|max:255',
            'needles.*.lot_number' => 'nullable|string|max:255',
            'needles.*.type' => 'nullable|string|max:255',
            'inks' => 'nullable|array',
            'inks.*' => 'nullable|array',
            'inks.*.brand' => 'nullable|string|max:255',
            'inks.*.color' => 'nullable|string|max:255',
            'inks.*.lot_number' => 'nullable|string|max:255',
            'sterilization_date' => 'nullable|date',
            'sterilization_lot_number' => 'nullable|string|max:255',
            'autoclave_cycle_number' => 'nullable|string|max:255',
            'other_supplies' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
            'lot_photos' => 'nullable|array|max:5',
            'lot_photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Filtrer les encres vides
        if (isset($validated['inks'])) {
            $validated['inks'] = array_values(array_filter($validated['inks'], function ($ink) {
                return !empty(trim($ink['brand'] ?? '')) || !empty(trim($ink['color'] ?? '')) || !empty(trim($ink['lot_number'] ?? ''));
            }));
            if (empty($validated['inks'])) {
                unset($validated['inks']);
            }
        }

        // Préparer les données pour le modèle (adapter aux colonnes existantes)
        $traceData = [
            'tattooer_id' => $tattooer->id,
            'appointment_id' => $appointment->id,
            'client_consent_form_id' => $appointment->bookingRequest?->clientConsentForm?->id ?? null,
            'procedure_date' => now()->format('Y-m-d'),
            'procedure_start_time' => $appointment->start_datetime?->format('H:i:s') ?? null,
            'procedure_end_time' => $appointment->end_datetime?->format('H:i:s') ?? null,
            'sterile_equipment' => [
                'needles' => $validated['needles'] ?? [],
                'inks' => $validated['inks'] ?? [],
                'sterilization_date' => $validated['sterilization_date'] ?? null,
                'sterilization_lot_number' => $validated['sterilization_lot_number'] ?? '',
                'autoclave_cycle_number' => $validated['autoclave_cycle_number'] ?? ''
            ],
            'aftercare_products' => $validated['aftercare_products'] ?? [],
            'procedure_notes' => $validated['other_supplies'] ?? '',
            'equipment_notes' => $validated['notes'] ?? '',
            'tattooer_verified_traceability' => true,
            'verified_at' => now(),
        ];

        $traceability = TraceabilityRecord::updateOrCreate(
            ['appointment_id' => $appointment->id],
            $traceData
        );

        // Upload photos de lots via Media Library
        $hasPhotos = false;
        if ($request->hasFile('lot_photos')) {
            $hasPhotos = true;
            foreach ($request->file('lot_photos') as $photo) {
                $traceability->addMedia($photo)->toMediaCollection('lot_photos');
            }
        }

        // Si des photos ont été uploadées, marquer comme complète
        if ($hasPhotos) {
            $traceability->update([
                'tattooer_verified_traceability' => true,
                'verified_at' => now(),
            ]);
        }

        return redirect()->to(url()->previous() . '#trace')->with('success', '✅ Traçabilité enregistrée.');
    }

    /**
     * Créer une traçabilité standalone pour un client
     */
    public function storeClientTraceability(Request $request, Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $validated = $request->validate([
            'session_date' => 'required|date',
            'tattoo_description' => 'required|string|max:500',
            'body_zone' => 'required|string|max:100',
            'procedure_start_time' => 'required|date_format:H:i',
            'procedure_end_time' => 'required|date_format:H:i|after:procedure_start_time',
            'needles_used' => 'nullable|array',
            'inks_used' => 'nullable|array',
            'sterile_equipment' => 'nullable|string',
            'aftercare_products' => 'nullable|string',
            'room_number' => 'nullable|string|max:50',
            'autoclave_batch_number' => 'nullable|string|max:100',
            'autoclave_test_date' => 'nullable|date',
            'procedure_notes' => 'nullable|string|max:1000',
            'client_condition_notes' => 'nullable|string|max:500',
            'equipment_notes' => 'nullable|string|max:500',
        ]);

        // Créer la traçabilité standalone
        $traceability = TraceabilityRecord::create([
            'user_id' => $tattooer->user_id,
            'tattooer_id' => $tattooer->id,
            'client_id' => $client->id, // Lien direct avec le client
            'appointment_id' => null, // Pas d'appointment lié
            'studio_id' => $tattooer->studio_id,
            'session_date' => $validated['session_date'],
            'tattoo_description' => $validated['tattoo_description'],
            'body_zone' => $validated['body_zone'],
            'procedure_date' => $validated['session_date'],
            'procedure_start_time' => $validated['procedure_start_time'],
            'procedure_end_time' => $validated['procedure_end_time'],
            'needles_used' => $validated['needles_used'] ?? [],
            'inks_used' => $validated['inks_used'] ?? [],
            'sterile_equipment' => $validated['sterile_equipment'],
            'aftercare_products' => $validated['aftercare_products'],
            'room_number' => $validated['room_number'],
            'autoclave_batch_number' => $validated['autoclave_batch_number'],
            'autoclave_test_date' => $validated['autoclave_test_date'],
            'procedure_notes' => $validated['procedure_notes'],
            'client_condition_notes' => $validated['client_condition_notes'],
            'equipment_notes' => $validated['equipment_notes'],
            'tattooer_verified_traceability' => true,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', '✅ Traçabilité enregistrée avec succès !');
    }
}
