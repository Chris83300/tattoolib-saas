<?php

namespace App\Livewire\Tattooer;

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
        $tattooer = auth()->user()->tattooer;

        if (!$tattooer) {
            return view('livewire.tattooer.messages', [
                'projects' => collect([])
            ]);
        }

        // Récupérer les projets du tattooer avec des messages
        $projects = Project::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->whereHas('messages')
            ->with(['client', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        // Ajouter les informations de messages non lus
        foreach ($projects as $project) {
            $unreadCount = Message::where('project_id', $project->id)
                ->where('sender_type', 'client')
                ->whereNull('read_by_tattooer_at')
                ->count();

            $project->unread_count = $unreadCount;
            $project->last_message = $project->messages->first();
        }

        return view('livewire.tattooer.messages', [
            'projects' => $projects
        ]);
    }
}
