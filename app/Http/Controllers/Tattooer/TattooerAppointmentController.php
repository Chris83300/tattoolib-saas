<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\Appointment;
use App\Actions\CompleteAppointmentAction;
use App\Actions\ReportNoShowAction;
use Illuminate\Http\Request;

class TattooerAppointmentController extends ArtisanBaseController
{
    /**
     * Marquer un rendez-vous comme terminé
     */
    public function completeAppointment(Request $request, Appointment $appointment)
    {
        // Vérifier que le tattooer est bien le propriétaire
        $this->authorizeAppointmentOwner($appointment);

        // Vérifier que le RDV est bien passé (end_datetime < now)
        if ($appointment->end_datetime->isFuture()) {
            return back()->with('error', 'Ce rendez-vous n\'est pas encore terminé.');
        }

        $validated = $request->validate([
            'completion_notes' => 'nullable|string|max:1000',
        ]);

        $action = new CompleteAppointmentAction();
        $action->execute($appointment, 'tattooer', $validated['completion_notes'] ?? null);

        return back()->with('success', 'Rendez-vous marqué comme terminé !');
    }

    /**
     * Signaler un no-show (client absent)
     */
    public function reportNoShow(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointmentOwner($appointment);

        $validated = $request->validate([
            'no_show_reason' => 'nullable|string|max:1000',
        ]);

        $action = new ReportNoShowAction();
        $action->execute($appointment, 'tattooer', $validated['no_show_reason'] ?? null);

        return back()->with('success', 'No-show signalé. Notre équipe va examiner la situation.');
    }

    /**
     * Vérifier que le tattooer connecté est bien le propriétaire du RDV
     */
    private function authorizeAppointmentOwner(Appointment $appointment): void
    {
        $bookingRequest = $appointment->bookingRequest;
        $user = auth()->user();

        // Adapter selon la logique polymorphique (bookable_type/bookable_id)
        abort_unless(
            $bookingRequest &&
            $bookingRequest->bookable_type === get_class($user->tattooer) &&
            $bookingRequest->bookable_id === $user->tattooer?->id,
            403,
            'Vous n\'êtes pas autorisé à modifier ce rendez-vous.'
        );
    }
}
