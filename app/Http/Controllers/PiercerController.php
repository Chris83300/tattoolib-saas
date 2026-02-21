<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Client;
use App\Models\BookingRequest;
use App\Models\ClientConsentForm;
use App\Models\TraceabilityRecord;
use App\Models\Appointment;
use App\Models\CalendarEvent;
use App\Models\Piercer;
use App\Enums\BookingRequestStatus;
use App\Actions\CompleteAppointmentAction;
use App\Actions\ReportNoShowAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiercerController extends Controller
{
    /**
     * Profil public du Piercer (vue interne)
     */
    public function profile()
    {
        $piercer = auth()->user()->piercer;

        // Charger portfolio
        $portfolio = $piercer->getMedia('portfolio');

        // Statistiques simples
        $stats = [
            'completed_requests' => $piercer->bookingRequests()->where('status', 'completed')->count(),
            'total_revenue' => $piercer->bookingRequests()->where('status', 'completed')->sum('total_price'),
        ];

        // Avis
        $reviews = $piercer->reviews()->with('client')->get();

        return view('piercer.profile', compact('piercer', 'portfolio', 'stats', 'reviews'));
    }

    /**
     * Dashboard du Piercer
     */
    public function dashboard()
    {
        $piercer = auth()->user()->piercer;

        if (!$piercer) {
            return redirect()->route('register.Piercer')
                ->with('error', 'Veuillez compléter votre profil Piercer pour accéder au dashboard.');
        }

        // Statistiques
        $stats = [
            'booking_requests_count' => $piercer->bookingRequests()->count(),
            'pending_requests' => $piercer->bookingRequests()->where('status', 'pending')->count(),
            'accepted_requests' => $piercer->bookingRequests()->where('status', 'accepted')->count(),
            'completed_requests' => $piercer->bookingRequests()->where('status', 'completed')->count(),
            'total_revenue' => $piercer->bookingRequests()->where('status', 'completed')->sum('total_price'),
        ];

        // Demandes récentes
        $recentRequests = $piercer->bookingRequests()
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Rendez-vous du jour
        $todayAppointments = $piercer->appointments()
            ->whereDate('start_time', today())
            ->orderBy('start_time')
            ->get();

        return view('piercer.dashboard', compact('piercer', 'stats', 'recentRequests', 'todayAppointments'));
    }

    /**
     * Paramètres du Piercer
     */
    public function settings()
    {
        $piercer = auth()->user()->piercer;
        return view('piercer.settings', compact('piercer'));
    }

    /**
     * Portfolio du Piercer
     */
    public function portfolio()
    {
        $piercer = auth()->user()->piercer;
        $portfolio = $piercer->getMedia('portfolio');

        return view('piercer.portfolio', compact('piercer', 'portfolio'));
    }

    /**
     * Clients du Piercer
     */
    public function clients()
    {
        $piercer = auth()->user()->piercer;
        $clients = $piercer->clients()
            ->withCount('bookingRequests')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('piercer.clients', compact('piercer', 'clients'));
    }

    /**
     * Messages du Piercer
     */
    public function messages()
    {
        $piercer = auth()->user()->piercer;
        $conversations = $piercer->conversations()
            ->with(['client', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('piercer.messages', compact('piercer', 'conversations'));
    }

    /**
     * Demandes de réservation du Piercer
     */
    public function bookingRequests()
    {
        $piercer = auth()->user()->piercer;
        $bookingRequests = $piercer->bookingRequests()
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('piercer.booking-requests', compact('piercer', 'bookingRequests'));
    }

    /**
     * Afficher une demande de réservation spécifique
     */
    public function bookingRequestShow(BookingRequest $bookingRequest)
    {
        $piercer = auth()->user()->piercer;

        // Vérifier que la demande appartient bien au Piercer
        if ($bookingRequest->bookable_id !== $piercer->id || $bookingRequest->bookable_type !== 'App\Models\Piercer') {
            abort(403);
        }

        return view('piercer.booking-request-show', compact('piercer', 'bookingRequest'));
    }

    /**
     * Sélectionner une date proposée
     */
    public function selectProposedDate(Request $request, BookingRequest $bookingRequest)
    {
        $piercer = auth()->user()->piercer;

        // Vérifier que la demande appartient bien au Piercer
        if ($bookingRequest->bookable_id !== $piercer->id || $bookingRequest->bookable_type !== 'App\Models\Piercer') {
            abort(403);
        }

        $validated = $request->validate([
            'selected_date' => 'required|date',
            'selected_time' => 'required|string',
        ]);

        $bookingRequest->update([
            'confirmed_date' => $validated['selected_date'],
            'confirmed_period' => $validated['selected_time'],
        ]);

        return back()->with('success', 'Date proposée avec succès');
    }

    /**
     * Page d'accueil du Piercer
     */
    public function index()
    {
        return redirect()->route('piercer.dashboard');
    }
}
