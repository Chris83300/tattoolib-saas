# 🚨 FIX URGENT — Layout Support Chat admin complètement cassé

## Problème observé (screenshot)
La page `/admin/support-chat` affiche tout en vrac :
- Les 4 conversations s'affichent en grille horizontale illisible
- Les infos utilisateur (nom, email, statut) se mélangent avec les messages
- La zone de réponse est perdue en bas sans contexte
- Impossible de savoir quelle conversation est active

## Cause
La vue Blade ne respecte pas un layout sidebar/main clair.
Le composant Livewire mélange liste + détail dans un seul flux vertical.

---

## PHASE 1 — LIRE L'EXISTANT

```bash
# Lire la vue actuelle
cat resources/views/filament/admin/pages/support-chat.blade.php

# Lire le composant PHP
cat app/Filament/Admin/Pages/SupportChat.php

# Vérifier les colonnes messages
php artisan tinker --execute="
  dd([
    'cols'   => \Schema::getColumnListing('messages'),
    'sample' => \App\Models\Message::first()?->toArray(),
  ]);
"
```

---

## PHASE 2 — RÉÉCRIRE LA VUE COMPLÈTEMENT

Remplacer intégralement `resources/views/filament/admin/pages/support-chat.blade.php`
par cette structure propre :

```blade
<x-filament-panels::page>
<div
    class="flex h-[calc(100vh-180px)] rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm"
    wire:poll.4s="$refresh">

    {{-- ═══════════════════════════════════════════════════════
         COLONNE GAUCHE — Liste des conversations (fixe 320px)
    ═══════════════════════════════════════════════════════ --}}
    <div class="w-80 flex-shrink-0 flex flex-col border-r border-gray-200 dark:border-gray-700">

        {{-- Header liste --}}
        <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 text-sm">
                Conversations support
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                {{ $this->conversations->count() }} conversation(s)
                @php $totalUnread = $this->conversations->sum('unread_count'); @endphp
                @if ($totalUnread > 0)
                · <span class="text-red-500 font-medium">{{ $totalUnread }} non lu(s)</span>
                @endif
            </p>
        </div>

        {{-- Liste scrollable --}}
        <div class="flex-1 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700/50">

            @forelse ($this->conversations as $conv)
            @php
                $convUser    = $conv->users->first();
                $lastMsg     = $conv->messages->first();
                $isActive    = $this->activeConversationId === $conv->id;
                $hasUnread   = ($conv->unread_count ?? 0) > 0;
                $initial     = strtoupper(substr($convUser?->name ?? $convUser?->pseudo ?? '?', 0, 1));
                $userRole    = match(true) {
                    (bool) $convUser?->tattooer  => 'Tatoueur',
                    (bool) $convUser?->piercer   => 'Pierceur',
                    (bool) $convUser?->client    => 'Client',
                    (bool) $convUser?->studio    => 'Studio',
                    default                      => 'Utilisateur',
                };
            @endphp

            <button
                wire:click="selectConversation({{ $conv->id }})"
                class="w-full text-left px-4 py-3 transition-colors
                       {{ $isActive
                           ? 'bg-primary-50 dark:bg-primary-900/20 border-l-4 border-l-primary-500'
                           : 'hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-l-transparent' }}">

                <div class="flex items-start gap-3">

                    {{-- Avatar initiale CSS (PAS d'image) --}}
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center
                                justify-center text-white font-bold text-sm
                                {{ $hasUnread ? 'bg-primary-500' : 'bg-gray-400 dark:bg-gray-600' }}">
                        {{ $initial }}
                    </div>

                    {{-- Contenu --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <span class="font-medium text-sm text-gray-900 dark:text-gray-100 truncate">
                                {{ $convUser?->name ?? $convUser?->pseudo ?? 'Utilisateur inconnu' }}
                            </span>
                            <span class="text-xs text-gray-400 flex-shrink-0">
                                {{ $conv->updated_at->format('H:i') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-1 mt-0.5">
                            <span class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ \Str::limit($lastMsg?->content ?? $lastMsg?->body ?? 'Aucun message', 38) }}
                            </span>
                            @if ($hasUnread)
                            <span class="w-5 h-5 bg-red-500 text-white text-xs rounded-full
                                         flex items-center justify-center font-bold flex-shrink-0">
                                {{ $conv->unread_count }}
                            </span>
                            @endif
                        </div>

                        <span class="inline-block mt-1 text-xs px-1.5 py-0.5 rounded
                                     bg-gray-100 dark:bg-gray-700
                                     text-gray-500 dark:text-gray-400">
                            {{ $userRole }}
                        </span>
                    </div>
                </div>
            </button>

            @empty
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <span class="text-4xl mb-3">💬</span>
                <p class="text-sm">Aucune conversation</p>
                <p class="text-xs mt-1">Les utilisateurs peuvent vous contacter via le chat support</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         COLONNE DROITE — Conversation active
    ═══════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0">

        @if ($this->activeConversationId && $this->activeUser)

        {{-- Header conversation active --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700
                    bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                {{-- Avatar --}}
                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center
                            justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($this->activeUser->name ?? $this->activeUser->pseudo ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">
                        {{ $this->activeUser->name ?? $this->activeUser->pseudo ?? 'Utilisateur' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $this->activeUser->email }}
                        @php
                            $role = match(true) {
                                (bool) $this->activeUser->tattooer => '· Tatoueur',
                                (bool) $this->activeUser->piercer  => '· Pierceur',
                                (bool) $this->activeUser->client   => '· Client',
                                (bool) $this->activeUser->studio   => '· Studio',
                                default => '',
                            };
                        @endphp
                        {{ $role }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Fil de messages --}}
        <div
            class="flex-1 overflow-y-auto px-6 py-4 space-y-4"
            id="support-chat-messages"
            x-init="$el.scrollTop = $el.scrollHeight"
            x-effect="$el.scrollTop = $el.scrollHeight">

            @forelse ($this->activeMessages as $message)
            @php
                $isAdminMsg = ($message->sender_type ?? '') === 'admin'
                              || $message->user_id === null
                              || $message->sender_id === null;
                $msgContent = $message->content ?? $message->body ?? '';
                $msgTime    = $message->created_at->format('d/m H:i');
            @endphp

            <div class="flex {{ $isAdminMsg ? 'justify-end' : 'justify-start' }} gap-2">

                {{-- Avatar message utilisateur --}}
                @if (!$isAdminMsg)
                <div class="w-7 h-7 rounded-full bg-gray-300 dark:bg-gray-600
                            flex items-center justify-center text-xs font-semibold
                            text-gray-700 dark:text-gray-200 flex-shrink-0 mt-1">
                    {{ strtoupper(substr($message->user?->name ?? $message->user?->pseudo ?? '?', 0, 1)) }}
                </div>
                @endif

                <div class="max-w-[70%]">
                    <div class="px-4 py-2.5 rounded-2xl text-sm
                        {{ $isAdminMsg
                            ? 'bg-primary-600 text-white rounded-tr-sm'
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-tl-sm' }}">
                        {{ $msgContent }}
                    </div>
                    <p class="text-xs text-gray-400 mt-1
                              {{ $isAdminMsg ? 'text-right' : 'text-left' }}">
                        {{ $msgTime }}
                        @if ($isAdminMsg)· <span class="text-primary-400">Admin</span>@endif
                    </p>
                </div>

                {{-- Avatar admin --}}
                @if ($isAdminMsg)
                <div class="w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900
                            flex items-center justify-center text-sm flex-shrink-0 mt-1">
                    🛡️
                </div>
                @endif
            </div>

            @empty
            <div class="flex items-center justify-center h-full py-16 text-gray-400">
                <div class="text-center">
                    <p class="text-3xl mb-2">💬</p>
                    <p class="text-sm">Aucun message dans cette conversation</p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Zone de réponse --}}
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4
                    bg-white dark:bg-gray-900">
            @error('newMessage')
            <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
            @enderror

            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <textarea
                        wire:model.defer="newMessage"
                        wire:keydown.enter.prevent="sendReply"
                        placeholder="Répondre en tant qu'équipe Ink&Pik..."
                        rows="2"
                        maxlength="2000"
                        class="w-full resize-none rounded-xl border border-gray-200 dark:border-gray-600
                               bg-gray-50 dark:bg-gray-800 px-4 py-3 text-sm
                               text-gray-800 dark:text-gray-100
                               placeholder-gray-400 dark:placeholder-gray-500
                               focus:outline-none focus:ring-2 focus:ring-primary-400
                               max-h-32"
                    ></textarea>
                    <p class="text-xs text-gray-400 mt-1">
                        Entrée pour envoyer · L'utilisateur sera notifié
                    </p>
                </div>

                <button
                    wire:click="sendReply"
                    wire:loading.attr="disabled"
                    wire:target="sendReply"
                    class="flex-shrink-0 w-11 h-11 rounded-xl bg-primary-600
                           text-white flex items-center justify-center
                           hover:bg-primary-700 transition disabled:opacity-50
                           focus:outline-none focus:ring-2 focus:ring-primary-400">
                    <svg wire:loading.remove wire:target="sendReply"
                         class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <svg wire:loading wire:target="sendReply"
                         class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 12 16 4.373 20.373z"/>
                    </svg>
                </button>
            </div>
        </div>

        @else

        {{-- Aucune conversation sélectionnée --}}
        <div class="flex-1 flex items-center justify-center">
            <div class="text-center text-gray-400 dark:text-gray-500">
                <p class="text-6xl mb-4">👈</p>
                <p class="font-medium text-gray-600 dark:text-gray-300">
                    Sélectionnez une conversation
                </p>
                <p class="text-sm mt-1">
                    Cliquez sur une conversation dans la liste à gauche
                </p>
            </div>
        </div>

        @endif
    </div>
</div>
</x-filament-panels::page>
```

---

## PHASE 3 — VÉRIFIER LE COMPOSANT PHP

Dans `app/Filament/Admin/Pages/SupportChat.php`, vérifier que :

```php
// 1. getConversationsProperty() charge les bonnes relations
public function getConversationsProperty()
{
    return \App\Models\Conversation::where('type', 'admin_private')
        ->with([
            'users',
            'messages' => fn($q) => $q->latest()->limit(1),
        ])
        ->withCount([
            'messages as unread_count' => fn($q) =>
                $q->where('sender_type', 'user')
                  ->orWhere(fn($sq) => $sq->whereNull('sender_type')->whereNotNull('user_id'))
                  ->whereNull('read_at'),
        ])
        ->latest('updated_at')
        ->get();
}

// 2. getActiveUserProperty() retourne le bon user
public function getActiveUserProperty(): ?\App\Models\User
{
    if (!$this->activeConversationId) return null;

    $conv = \App\Models\Conversation::with(['users'])->find($this->activeConversationId);
    // Retourner le user non-admin
    return $conv?->users->first(fn($u) => !$u->is_admin);
}

// 3. getActiveMessagesProperty() charge les messages avec user
public function getActiveMessagesProperty()
{
    if (!$this->activeConversationId) return collect();

    return \App\Models\Message::where('conversation_id', $this->activeConversationId)
        ->with('user')
        ->oldest()
        ->get();
}

// 4. selectConversation() marque les messages comme lus
public function selectConversation(int $conversationId): void
{
    $this->activeConversationId = $conversationId;

    // Marquer les messages utilisateur comme lus
    \App\Models\Message::where('conversation_id', $conversationId)
        ->where(fn($q) => $q->where('sender_type', 'user')
                            ->orWhere(fn($sq) => $sq->whereNull('sender_type')
                                                    ->whereNotNull('user_id')))
        ->whereNull('read_at')
        ->update(['read_at' => now()]);
}

// 5. sendReply() crée le message avec les bonnes colonnes
public function sendReply(): void
{
    $this->validate(['newMessage' => 'required|string|min:1|max:2000']);

    if (!$this->activeConversationId) return;

    // Adapter les noms de colonnes selon Schema::getColumnListing('messages')
    $messageData = [
        'conversation_id' => $this->activeConversationId,
    ];

    // Adapter selon les colonnes existantes :
    if (\Schema::hasColumn('messages', 'content')) {
        $messageData['content'] = $this->newMessage;
    } elseif (\Schema::hasColumn('messages', 'body')) {
        $messageData['body'] = $this->newMessage;
    }

    if (\Schema::hasColumn('messages', 'sender_type')) {
        $messageData['sender_type'] = 'admin';
    }
    if (\Schema::hasColumn('messages', 'user_id')) {
        $messageData['user_id'] = null; // message admin, pas d'user
    }
    if (\Schema::hasColumn('messages', 'sender_id')) {
        $messageData['sender_id'] = null;
    }

    \App\Models\Message::create($messageData);

    // Notifier l'utilisateur
    $conv = \App\Models\Conversation::find($this->activeConversationId);
    $conv?->users
        ->filter(fn($u) => !$u->is_admin)
        ->each(fn($u) => $u->notify(
            new \App\Notifications\AdminMessageReceived(
                $this->newMessage,
                null
            )
        ));

    $conv?->touch();
    $this->newMessage = '';
}
```

---

## TESTS

```bash
# Vérifier la syntaxe
php artisan view:cache
php artisan view:clear

# Tester l'affichage
# → /admin/support-chat doit avoir 2 colonnes claires
# → Cliquer une conversation → messages s'affichent à droite
# → Envoyer une réponse → apparaît dans le fil
```

---

## ⚠️ Contraintes
- NE MODIFIER QUE la vue Blade et les méthodes PHP de SupportChat
- Ne toucher à rien d'autre
- La détection admin/user dans les messages doit couvrir les deux cas :
  `sender_type='admin'` ET `user_id=null` (selon le schéma)
- Rapport : screenshot ou description du résultat après correction
