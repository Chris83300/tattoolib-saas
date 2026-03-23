<?php
namespace App\Livewire;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class AdminChat extends Component
{
    public ?Conversation $conversation = null;
    public string $newMessage = '';
    public bool $isOpen = false;
    public int $unreadCount = 0;

    protected $rules = [
        'newMessage' => 'required|string|min:1|max:2000',
    ];

    public function mount(): void
    {
        $user = auth()->user();

        $this->conversation = Conversation::where('type', Conversation::TYPE_ADMIN_PRIVATE)
            ->whereHas('participants', fn ($q) => $q->where('users.id', $user->id))
            ->first();

        $this->countUnread();
    }

    public function open(): void
    {
        $this->isOpen = true;
        $this->markAsRead();
        $this->countUnread();
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function sendMessage(): void
    {
        $this->validate();

        $user = auth()->user();

        // Créer la conversation si elle n'existe pas encore
        if (!$this->conversation) {
            $admin = User::where('is_admin', true)->orWhereHas('roles', fn ($q) =>
                $q->where('name', 'admin')
            )->first();

            if (!$admin) {
                $this->addError('newMessage', 'Support temporairement indisponible.');
                return;
            }

            $this->conversation = Conversation::create([
                'type'          => Conversation::TYPE_ADMIN_PRIVATE,
                'admin_user_id' => $admin->id,
                'status'        => 'active',
            ]);
            $this->conversation->participants()->attach($user->id);
        }

        // sender_type = rôle réel de l'utilisateur (ENUM: tattooer, client, system, admin)
        $senderType = match (true) {
            $user->isTattooer() || $user->isPiercer() || $user->isStudioArtist() => 'tattooer',
            $user->isClient()  => 'client',
            default            => 'client',
        };

        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id'       => $user->id,
            'sender_type'     => $senderType,
            'content'         => $this->newMessage,
        ]);

        // Notifier l'admin
        $admin = $this->conversation->admin_user_id
            ? User::find($this->conversation->admin_user_id)
            : User::where('is_admin', true)->first();

        $admin?->notify(new \App\Notifications\UserMessageToAdmin(
            $this->newMessage,
            $user,
            $this->conversation
        ));

        $this->newMessage = '';
        $this->conversation->touch();

        // Invalider le cache du badge sidebar admin
        Cache::forget('admin.support.unread');
    }

    public function markAsRead(): void
    {
        if (!$this->conversation) {
            return;
        }

        Message::where('conversation_id', $this->conversation->id)
            ->where('sender_type', 'admin')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function countUnread(): void
    {
        if (!$this->conversation) {
            $this->unreadCount = 0;
            return;
        }

        $this->unreadCount = Message::where('conversation_id', $this->conversation->id)
            ->where('sender_type', 'admin')
            ->whereNull('read_at')
            ->count();
    }

    public function getMessagesProperty()
    {
        if (!$this->conversation) {
            return collect();
        }

        return Message::where('conversation_id', $this->conversation->id)
            ->with('sender')
            ->oldest()
            ->get();
    }

    public function render()
    {
        return view('livewire.admin-chat');
    }
}
