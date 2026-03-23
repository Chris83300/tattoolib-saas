<?php

namespace App\Filament\Studio\Pages;

use App\Models\Conversation;
use App\Services\StudioStatsService;
use Filament\Pages\Page;

class ConversationsArtistes extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Conversations';
    protected static ?string $title           = 'Conversations artistes';
    protected static ?int    $navigationSort  = 4;
    protected string $view = 'filament.studio.pages.conversations';

    public ?int $activeConversationId = null;

    public function getConversations(): \Illuminate\Support\Collection
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return collect();
        }

        return (new StudioStatsService($studio))->getArtistConversations();
    }

    public function getActiveConversation(): ?Conversation
    {
        if (!$this->activeConversationId) {
            return null;
        }

        $conversation = Conversation::with(['messages.sender', 'participants'])
            ->find($this->activeConversationId);

        if (!$conversation) {
            return null;
        }

        $studio        = auth()->user()?->studio;
        $artistUserIds = $studio?->artists()->pluck('user_id')->toArray() ?? [];

        $belongs = $conversation->participants()
            ->whereIn('users.id', $artistUserIds)
            ->exists();

        return $belongs ? $conversation : null;
    }

    public function selectConversation(int $id): void
    {
        $this->activeConversationId = $id;
    }
}
