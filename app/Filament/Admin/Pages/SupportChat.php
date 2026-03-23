<?php
namespace App\Filament\Admin\Pages;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

class SupportChat extends Page
{
    protected static ?string $navigationLabel = 'Chat Support';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static UnitEnum|string|null $navigationGroup = 'Communication';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.admin.pages.support-chat';

    // Badge : messages non lus (support + admin_private), avec cache 30s
    public static function getNavigationBadge(): ?string
    {
        $count = Cache::remember('admin.support.unread', 30, function () {
            return Message::whereHas('conversation', fn ($q) =>
                    $q->whereIn('type', ['support', 'admin_private'])
                )
                ->where('sender_type', '!=', 'admin')
                ->whereNull('read_at')
                ->count();
        });

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Messages non lus (support + chat admin)';
    }

    public function getSubheading(): ?string
    {
        $awaiting = $this->conversations->filter(function ($c) {
            $last = $c->messages->first(); // eager-loaded (limit 1)
            return ($c->unread_count ?? 0) === 0 && $last && $last->sender_type !== 'admin';
        })->count();

        if ($awaiting > 0) {
            return "{$awaiting} conversation(s) en attente de réponse";
        }

        return null;
    }

    public ?int $activeConversationId = null;
    public string $newMessage = '';

    public function mount(): void
    {
        // Ouvrir la première conversation avec un message non lu par défaut
        $first = Conversation::whereIn('type', ['support', 'admin_private'])
            ->whereHas('messages', fn ($q) =>
                $q->where('sender_type', '!=', 'admin')->whereNull('read_at')
            )
            ->latest('updated_at')
            ->first();

        $this->activeConversationId = $first?->id;

        if ($this->activeConversationId) {
            $this->markConversationAsRead($this->activeConversationId);
        }
    }

    public function selectConversation(int $conversationId): void
    {
        $this->activeConversationId = $conversationId;
        $this->markConversationAsRead($conversationId);
    }

    private function markConversationAsRead(int $conversationId): void
    {
        Message::where('conversation_id', $conversationId)
            ->where('sender_type', '!=', 'admin')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        Cache::forget('admin.support.unread');
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

        Cache::forget('admin.support.unread');
    }

    public function getConversationsProperty()
    {
        return Conversation::whereIn('type', [Conversation::TYPE_SUPPORT, Conversation::TYPE_ADMIN_PRIVATE])
            ->withCount(['messages as unread_count' => fn ($q) =>
                $q->whereNull('read_at')->where('sender_type', '!=', 'admin')
            ])
            ->with([
                'participants',
                'messages' => fn ($q) => $q->latest()->limit(1),
            ])
            ->orderByDesc('unread_count')
            ->orderByDesc('updated_at')
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

    public function getActiveConversationProperty(): ?Conversation
    {
        if (!$this->activeConversationId) {
            return null;
        }

        return Conversation::find($this->activeConversationId);
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
