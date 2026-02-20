<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Conversation;

class ClientLayoutComposer
{
    public function compose(View $view): void
    {
        if (!auth()->check() || !auth()->user()->client) {
            $view->with('clientUnreadCount', 0);
            return;
        }

        $client = auth()->user()->client;

        $unreadCount = Conversation::whereHas('bookingRequest', function ($q) use ($client) {
            $q->where('client_id', $client->id);
        })
            ->withCount(['messages as unread_messages_count' => function ($q) {
                $q->where('sender_id', '!=', auth()->id())
                    ->where('created_at', '>', function ($sub) {
                        $sub->select('last_read_at')
                            ->from('conversation_user')
                            ->whereColumn('conversation_user.conversation_id', 'conversations.id')
                            ->where('conversation_user.user_id', auth()->id())
                            ->limit(1);
                    });
            }])
            ->get()
            ->sum('unread_messages_count');

        $view->with('clientUnreadCount', $unreadCount);
    }
}
