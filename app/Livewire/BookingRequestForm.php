<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Project;
use App\Models\Tattooer;
use App\Models\StudioArtist;
use App\Models\Pierceur;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Notifications\NewBookingRequestNotification;

class BookingRequestForm extends Component
{
    use WithFileUploads;

    public $bookableId;
    public $bookableType;
    public $bookable;
    public $bookableName;

    // Infos client
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $birthDate;
    public $address;

    // Détails projet
    public $description;
    public $tattoo_size;
    public $location;
    public $style;
    public $estimatedBudget;
    public $proposedDate;
    public $referenceImages = [];

    // Styles disponibles
    public $styles = [
        'réalisme' => 'Réalisme',
        'tribal' => 'Tribal',
        'japonais' => 'Japonais',
        'old_school' => 'Old School',
        'new_school' => 'New School',
        'géométrique' => 'Géométrique',
        'aquarelle' => 'Aquarelle',
        'pointillisme' => 'Pointillisme',
        'lettrage' => 'Lettrage',
        'autre' => 'Autre',
    ];

    // Zones du corps
    public $bodyLocations = [
        'bras' => 'Bras',
        'avant_bras' => 'Avant-bras',
        'épaule' => 'Épaule',
        'dos' => 'Dos',
        'poitrine' => 'Poitrine',
        'ventre' => 'Ventre',
        'jambe' => 'Jambe',
        'mollet' => 'Mollet',
        'pied' => 'Pied',
        'cou' => 'Cou',
        'nuque' => 'Nuque',
        'main' => 'Main',
        'doigt' => 'Doigt',
        'cuisse' => 'Cuisse',
        'cheville' => 'Cheville',
        'visage' => 'Visage',
        'autre' => 'Autre',
    ];

    protected $rules = [
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'birthDate' => 'required|date|before:today',
        'description' => 'required|string|min:10|max:1000',
        'tattoo_size' => 'nullable|numeric|min:0|max:500',
        'location' => 'required|string',
        'style' => 'required|string',
        'estimatedBudget' => 'required|numeric|min:50',
        'proposedDate' => 'nullable|date|after:today',
        'referenceImages' => 'nullable|array|max:5',
        'referenceImages.*' => 'file|mimes:jpeg,jpg,png,webp,heic,heif|max:10240', // 10MB max
    ];

    protected $messages = [
        'birthDate.before' => 'Vous devez être majeur pour faire une demande.',
        'description.min' => 'La description doit faire au moins 10 caractères.',
        'estimatedBudget.min' => 'Le budget minimum est de 50€.',
    ];

    public function mount($bookableId, $bookableType)
    {
        $this->bookableId = $bookableId;
        $this->bookableType = $bookableType;

        // Charger le bookable (tatoueur, studio artist, perceur)
        $this->bookable = match($bookableType) {
            'tattooer' => Tattooer::findOrFail($bookableId),
            'studio-artist' => StudioArtist::findOrFail($bookableId),
            'piercer', 'pierceur' => Pierceur::findOrFail($bookableId),
            default => abort(404),
        };

        $this->bookableName = match($this->bookableType) {
            'tattooer' => $this->bookable->user->name,
            'studio-artist' => $this->bookable->artist_name,
            'piercer', 'pierceur' => $this->bookable->user->name,
            default => 'Artiste',
        };

        // Pré-remplir si utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            $this->email = $user->email;

            // Si le client existe déjà
            $existingClient = Client::where('user_id', $user->id)->first();
            if ($existingClient) {
                $this->firstName = $existingClient->first_name;
                $this->lastName = $existingClient->last_name;
                $this->phone = $existingClient->phone;
                $this->birthDate = $existingClient->birth_date?->format('Y-m-d');
                $this->address = $existingClient->address;
            }
        }
    }

    public function submitRequest()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // 1. Client (User + Client)
            if (Auth::check()) {
                $user = Auth::user();

                if (!$user->isClient()) {
                    throw new \Exception('Compte non client: veuillez vous déconnecter ou utiliser un compte client.');
                }
            } else {
                $user = User::firstOrCreate(
                    ['email' => $this->email],
                    [
                        'name' => trim($this->firstName . ' ' . $this->lastName),
                        'pseudo' => Str::slug($this->firstName . ' ' . $this->lastName),
                        'password' => bcrypt(Str::random(32)),
                        'role' => 'client',
                        'status' => 'active',
                    ]
                );

                if (!$user->isClient()) {
                    throw new \Exception('Email déjà utilisé par un autre type de compte.');
                }
            }

            $client = Client::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $this->firstName,
                    'last_name' => $this->lastName,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'birth_date' => $this->birthDate,
                    'address' => $this->address,
                ]
            );

            // 2. Project
            $project = Project::create([
                'client_id' => $client->id,
                'bookable_id' => $this->bookableId,
                'bookable_type' => $this->getBookableModelClass(),
                'status' => Project::STATUS_PENDING,
                'tattoo_description' => $this->description,
                'tattoo_location' => $this->location,
                'tattoo_style' => $this->style,
                'estimated_price' => $this->estimatedBudget,
                'proposed_date' => $this->proposedDate ? new \DateTime($this->proposedDate) : null,
            ]);

            // 3. Images de référence
            foreach ($this->referenceImages as $image) {
                $project->addMedia($image)
                    ->toMediaCollection('reference_images');
            }

            // 4. Notification artiste
            if ($this->bookable && isset($this->bookable->user)) {
                $this->bookable->user->notify(new NewBookingRequestNotification($project));
            }

            DB::commit();

            return redirect()->route('booking-request.success')
                ->with('success', 'Votre demande a été envoyée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash(
                'error',
                config('app.debug')
                    ? ('Une erreur est survenue: ' . $e->getMessage())
                    : 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer.'
            );
            Log::error('Booking request error: ' . $e->getMessage(), [
                'bookableId' => $this->bookableId,
                'bookableType' => $this->bookableType,
                'email' => $this->email,
            ]);
        }
    }

    /**
     * Obtenir la classe du modèle bookable
     */
    private function getBookableModelClass(): string
    {
        return match($this->bookableType) {
            'tattooer' => 'App\\Models\\Tattooer',
            'studio-artist' => 'App\\Models\\StudioArtist',
            'piercer', 'pierceur' => 'App\\Models\\Pierceur',
            default => abort(404),
        };
    }

    /**
     * Supprimer une image de référence
     */
    public function removeReferenceImage($index)
    {
        unset($this->referenceImages[$index]);
        $this->referenceImages = array_values($this->referenceImages);
    }

    /**
     * Obtenir le nom du bookable pour l'affichage
     */
    public function getBookableNameProperty(): string
    {
        return match($this->bookableType) {
            'tattooer' => $this->bookable->user->name,
            'studio-artist' => $this->bookable->artist_name,
            'piercer', 'pierceur' => $this->bookable->user->name,
            default => 'Artiste',
        };
    }

    public function render()
    {
        return view('livewire.booking-request-form');
    }
}
