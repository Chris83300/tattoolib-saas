<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Dashboard extends Component
{
    public $user;
    public $tattooer;
    public $stats;
    public $pendingRequests = 0;
    public $upcomingAppointments = 0;
    public $totalClients = 0;
    public $monthlyRevenue = 0;

    public function mount()
    {
        try {
            $this->user = auth()->user();

            // Récupérer le tattooer selon le rôle de l'utilisateur
            switch ($this->user->role) {
                case 'tattooer':
                    $this->tattooer = $this->user->tattooer;
                    break;
                case 'pierceur':
                    $this->tattooer = $this->user->pierceur;
                    break;
                case 'studio_artist':
                    $this->tattooer = $this->user->studioArtist;
                    break;
                default:
                    abort(403, 'Accès non autorisé');
            }

            if (!$this->tattooer) {
                abort(404, 'Profil artiste non trouvé');
            }

            // Stats rapides avec valeurs par défaut
            $this->pendingRequests = $this->tattooer->bookingRequests()->where('status', 'pending')->count() ?? 0;
            $this->upcomingAppointments = $this->tattooer->appointments()->where('start_time', '>', now())->count() ?? 0;
            $this->totalClients = $this->tattooer->appointments()->distinct('client_id')->count('client_id') ?? 0;
            $this->monthlyRevenue = 0;

            $this->stats = [
                'pending_requests' => $this->pendingRequests,
                'upcoming_appointments' => $this->upcomingAppointments,
                'unread_messages' => 0,
            ];
        } catch (\Exception $e) {
            // En cas d'erreur, afficher des valeurs par défaut
            $this->pendingRequests = 0;
            $this->upcomingAppointments = 0;
            $this->totalClients = 0;
            $this->monthlyRevenue = 0;
            $this->stats = ['pending_requests' => 0, 'upcoming_appointments' => 0, 'unread_messages' => 0];
        }
    }

    public function render()
    {
        return view('livewire.tattooer.dashboard-simple');
    }
}
