<?php
namespace App\Filament\Admin\Pages;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class SupportChat extends Page
{
    protected static ?string $navigationLabel = 'Chat Support';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static UnitEnum|string|null $navigationGroup = 'Communication';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.admin.pages.support-chat';

    // Badge : messages non lus des utilisateurs
    public static function getNavigationBadge(): ?string
    {
        $count = Message::whereHas('conversation', fn ($q) =>
            $q->where('type', 'admin_private')
        )
        ->whereNotIn('sender_type', ['admin'])
        ->whereNull('read_at')
        ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    // Indicateur de statut pour le menu
    public static function getNavigationBadgeTooltip(): ?string
    {
        $count = Message::whereHas('conversation', fn ($q) =>
            $q->where('type', 'admin_private')
        )
        ->whereNotIn('sender_type', ['admin'])
        ->whereNull('read_at')
        ->count();

        return $count > 0 ? $count . ' message(s) non lu(s)' : null;
    }

    public ?int $activeConversationId = null;
    public string $newMessage = '';

    public function mount(): void
    {
        // Ouvrir la première conversation avec un message non lu par défaut
        $first = Conversation::where('type', 'admin_private')
            ->whereHas('messages', fn ($q) =>
                $q->whereNotIn('sender_type', ['admin'])->whereNull('read_at')
            )
            ->latest('updated_at')
            ->first();

        $this->activeConversationId = $first?->id;
    }

    public function selectConversation(int $conversationId): void
    {
        $this->activeConversationId = $conversationId;
        $this->markConversationAsRead($conversationId);
    }

    private function markConversationAsRead(int $conversationId): void
    {
        Message::where('conversation_id', $conversationId)
            ->whereNotIn('sender_type', ['admin'])
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function sendReply(): void
    {
        $this->validate(['newMessage' => 'required|string|min:1|max:2000']);

        if (!$this->activeConversationId) {
            return;
        }

        $conversation = Conversation::find($this->activeConversationId);
        if (!$conversation) {
            return;
        }

        Message::create([
            'conversation_id' => $this->activeConversationId,
            'sender_id'       => auth()->id(),
            'sender_type'     => 'admin',
            'content'         => $this->newMessage,
        ]);

        // Notifier les participants (non-admin)
        $conversation->participants->each(fn ($u) =>
            $u->notify(new \App\Notifications\AdminMessageReceived(
                $this->newMessage,
                null
            ))
        );

        $conversation->touch();
        $this->newMessage = '';
    }

    public function getConversationsProperty()
    {
        return Conversation::where('type', 'admin_private')
            ->with([
                'participants',
                'messages' => fn ($q) => $q->latest()->limit(1),
            ])
            ->withCount([
                'messages as unread_count' => fn ($q) =>
                    $q->whereNotIn('sender_type', ['admin'])->whereNull('read_at'),
            ])
            ->latest('updated_at')
            ->get();
    }

    public function getActiveMessagesProperty()
    {
        if (!$this->activeConversationId) {
            return collect();
        }

        return Message::where('conversation_id', $this->activeConversationId)
            ->with('sender')
            ->oldest()
            ->get();
    }

    public function getActiveUserProperty(): ?User
    {
        if (!$this->activeConversationId) {
            return null;
        }

        return Conversation::with('participants')
            ->find($this->activeConversationId)
            ?->participants
            ->first(fn ($u) => !$u->is_admin);
    }
}
