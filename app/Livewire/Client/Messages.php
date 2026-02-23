<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Str;

class Messages extends Component
{
    public $conversations;

    public function mount()
    {
        $client = auth()->user()->client;

        if (!$client) {
            $this->conversations = collect([]);
            return;
        }

        // Récupérer les conversations du client
        $this->conversations = Conversation::whereHas('bookingRequest', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->with(['bookingRequest.bookable.user', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.client.messages');
    }
}
