<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Str;

class Messages extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Messages - Ink&Pik')]

    public function render()
    {
        $client = auth()->user()->client;

        if (!$client) {
            return view('client.messages', [
                'conversations' => collect([])
            ]);
        }

        // Récupérer les conversations du client
        $conversations = Conversation::whereHas('bookingRequest', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->with(['bookingRequest.bookable.user', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        return view('client.messages', [
            'conversations' => $conversations
        ]);
    }
}
