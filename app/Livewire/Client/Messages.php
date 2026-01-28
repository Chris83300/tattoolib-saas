<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Messages extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Messages - Ink&Pik')]
    public function render()
    {
        $conversations = auth()->user()->conversations()
            ->with(['messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        return view('livewire.client.messages', [
            'conversations' => $conversations
        ]);
    }
}
