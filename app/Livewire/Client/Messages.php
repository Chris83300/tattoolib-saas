<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Project;
use App\Models\Message;

class Messages extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Messages - Ink&Pik')]

    public function render()
    {
        $client = auth()->user()->client;

        if (!$client) {
            return view('livewire.client.messages', [
                'projects' => collect([])
            ]);
        }

        // Récupérer les projets du client avec des messages
        $projects = Project::where('client_id', $client->id)
            ->whereHas('messages')
            ->with(['bookable', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        // Ajouter les informations de messages non lus
        foreach ($projects as $project) {
            $unreadCount = Message::where('project_id', $project->id)
                ->where('sender_type', 'tattooer')
                ->whereNull('read_by_client_at')
                ->count();

            $project->unread_count = $unreadCount;
            $project->last_message = $project->messages->first();
        }

        return view('livewire.client.messages', [
            'projects' => $projects
        ]);
    }
}
