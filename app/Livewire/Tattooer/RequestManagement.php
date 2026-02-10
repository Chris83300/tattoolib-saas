<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Actions\AcceptBookingRequest;
use App\Actions\RejectBookingRequest;
use App\Enums\BookingRequestStatus;

class RequestManagement extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Gestion des demandes - Ink&Pik')]
    public $bookingRequest;
    public $showAcceptModal = false;
    public $showRejectModal = false;
    public $rejectReason = '';
    
    // Propriétés pour le formulaire d'acceptation
    public $priceEstimateMin = '';
    public $priceEstimateMax = '';
    public $depositAmount = '';
    public $depositDeadlineHours = 72;
    public $includedDesigns = 1;
    public $modificationsPerDesign = 2;
    public $proposedDates = [];
    public $acceptanceMessage = '';
    
    protected $rules = [
        'priceEstimateMin' => 'required|numeric|min:0',
        'priceEstimateMax' => 'required|numeric|gte:priceEstimateMin',
        'depositAmount' => 'required|numeric|min:0',
        'depositDeadlineHours' => 'required|integer|in:24,48,72,120,168',
        'includedDesigns' => 'required|integer|min:1|max:5',
        'modificationsPerDesign' => 'required|integer|min:1|max:10',
        'proposedDates.0.date' => 'required|date',
        'proposedDates.0.period' => 'nullable|in:morning,afternoon,evening',
        'proposedDates.1.date' => 'required|date',
        'proposedDates.1.period' => 'nullable|in:morning,afternoon,evening',
        'proposedDates.2.date' => 'required|date',
        'proposedDates.2.period' => 'nullable|in:morning,afternoon,evening',
        'acceptanceMessage' => 'nullable|string|max:1000',
    ];
    
    protected $listeners = [
        'showAcceptModal' => 'openAcceptModal',
        'showRejectModal' => 'openRejectModal',
    ];
    
    public function mount($bookingRequestId)
    {
        $this->bookingRequest = auth()->user()->tattooer->bookingRequests()
            ->with(['client'])
            ->findOrFail($bookingRequestId);
    }
    
    public function openAcceptModal($bookingRequestId)
    {
        $this->bookingRequest = auth()->user()->tattooer->bookingRequests()
            ->with(['client'])
            ->findOrFail($bookingRequestId);
        $this->showAcceptModal = true;
    }
    
    public function openRejectModal($bookingRequestId)
    {
        $this->bookingRequest = auth()->user()->tattooer->bookingRequests()
            ->with(['client'])
            ->findOrFail($bookingRequestId);
        $this->showRejectModal = true;
    }
    
    public function acceptBookingRequest()
    {
        $this->validate();
        
        $action = app(AcceptBookingRequest::class);
        $action->execute($this->bookingRequest, [
            'price_estimate_min' => $this->priceEstimateMin,
            'price_estimate_max' => $this->priceEstimateMax,
            'deposit_amount' => $this->depositAmount,
            'deposit_deadline_hours' => $this->depositDeadlineHours,
            'included_designs' => $this->includedDesigns,
            'modifications_per_design' => $this->modificationsPerDesign,
            'proposed_dates' => collect($this->proposedDates)->filter(fn($d) => !empty($d['date']))->values()->toArray(),
            'message' => $this->acceptanceMessage,
        ]);
        
        $this->showAcceptModal = false;
        $this->bookingRequest->refresh();
        
        session()->flash('success', 'Demande acceptée avec succès !');
    }
    
    public function rejectBookingRequest()
    {
        $this->validate(['rejectReason' => 'required|string|max:500']);
        
        $action = app(RejectBookingRequest::class);
        $action->execute($this->bookingRequest, [
            'reason' => $this->rejectReason,
        ]);
        
        $this->showRejectModal = false;
        $this->bookingRequest->refresh();
        
        session()->flash('success', 'Demande refusée avec succès !');
    }
    
    public function render()
    {
        return view('livewire.tattooer.request-management', [
            'bookingRequest' => $this->bookingRequest,
            'showAcceptModal' => $this->showAcceptModal,
            'showRejectModal' => $this->showRejectModal,
        ]);
    }
}
