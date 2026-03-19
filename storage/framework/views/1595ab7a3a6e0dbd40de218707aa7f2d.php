<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<div
    class="flex h-[calc(100vh-180px)] rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm"
    wire:poll.15s="$refresh">

    
    <div class="w-80 flex-shrink-0 flex flex-col border-r border-gray-200 dark:border-gray-700">

        
        <div class="inkpik-card px-4 py-4 border-b border-beige-peau dark:border-beige-peau bg-gray-50 dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100 text-sm flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        Conversations support
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        <?php echo e($this->conversations->count()); ?> conversation(s)
                    </p>
                </div>
                <?php $totalUnread = $this->conversations->sum('unread_count'); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalUnread > 0): ?>
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                    <?php echo e($totalUnread); ?>

                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div class="inkpik-card flex-1 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700/50">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $convUser  = $conv->participants->first();
                $lastMsg   = $conv->messages->first();
                $isActive  = $this->activeConversationId === $conv->id;
                $hasUnread = ($conv->unread_count ?? 0) > 0;
                $initial   = strtoupper(substr($convUser?->name ?? $convUser?->pseudo ?? '?', 0, 1));
                $userRole  = match(true) {
                    $convUser?->role === 'tattooer'      => 'Tatoueur',
                    $convUser?->role === 'pierceur'      => 'Pierceur',
                    $convUser?->role === 'client'        => 'Client',
                    $convUser?->role === 'studio'        => 'Studio',
                    $convUser?->role === 'studio_artist' => 'Artiste Studio',
                    default                              => 'Utilisateur',
                };
            ?>

            <button
                wire:click="selectConversation(<?php echo e($conv->id); ?>)"
                class="w-full text-left px-4 py-3 transition-colors
                       <?php echo e($isActive
                           ? 'bg-primary-50 dark:bg-primary-900/20 border-l-4 border-l-primary-500'
                           : 'hover:bg-gray-50 dark:hover:bg-gray-800/60 border-l-4 border-l-transparent'); ?>

                       <?php echo e($hasUnread && !$isActive ? 'bg-blue-50 dark:bg-blue-900/10' : ''); ?>">

                <div class="flex items-start gap-3">

                    
                    <div class="w-10 h-10 inkpik-btn rounded-full flex-shrink-0 flex items-center
                                justify-center text-white font-bold text-sm
                                <?php echo e($hasUnread ? 'bg-primary-500' : 'bg-gray-400 dark:bg-gray-600'); ?>">
                        <?php echo e($initial); ?>

                    </div>

                    
                    <div class="inkpik-card flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <span class="font-medium text-sm truncate
                                         <?php echo e($hasUnread ? 'text-gray-900 dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-200'); ?>">
                                <?php echo e($convUser?->name ?? $convUser?->pseudo ?? 'Utilisateur inconnu'); ?>

                            </span>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasUnread): ?>
                                <span class="w-5 h-5 bg-red-500 text-white text-xs rounded-full
                                             flex items-center justify-center font-bold">
                                    <?php echo e($conv->unread_count); ?>

                                </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    <?php echo e($conv->updated_at->format('H:i')); ?>

                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5
                                  <?php echo e($hasUnread ? 'font-medium text-gray-700 dark:text-gray-300' : ''); ?>">
                            <?php echo e(\Str::limit($lastMsg?->content ?? '…', 38)); ?>

                        </p>

                        <span class="inline-block mt-1 text-xs px-1.5 py-0.5 rounded
                                     bg-gray-100 dark:bg-gray-700
                                     text-gray-500 dark:text-gray-400">
                            <?php echo e($userRole); ?>

                        </span>
                    </div>
                </div>
            </button>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="flex flex-col items-center justify-center py-12 text-gray-400 dark:text-gray-500">
                <span class="text-4xl mb-3">💬</span>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Aucune conversation</p>
                <p class="text-xs mt-1 text-center px-4">Les utilisateurs vous contactent via le chat support</p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="flex-1 flex flex-col min-w-0">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->activeConversationId && $this->activeUser): ?>

        
        <div class="inkpik-card px-6 py-4 border-b border-gray-200 dark:border-gray-700
                    bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="inkpik-avatar w-10 h-10 rounded-full bg-primary-500 flex items-center
                            justify-center text-white font-bold text-sm flex-shrink-0">
                    <?php echo e(strtoupper(substr($this->activeUser->name ?? $this->activeUser->pseudo ?? '?', 0, 1))); ?>

                </div>
                <div>
                    <p class="inkpik-name font-semibold text-gray-900 dark:text-gray-100 text-sm">
                        <?php echo e($this->activeUser->name ?? $this->activeUser->pseudo ?? 'Utilisateur'); ?>

                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <?php echo e($this->activeUser->email); ?>

                        <?php
                            $activeRole = match($this->activeUser->role ?? '') {
                                'tattooer'      => '· Tatoueur',
                                'pierceur'      => '· Pierceur',
                                'client'        => '· Client',
                                'studio'        => '· Studio',
                                'studio_artist' => '· Artiste Studio',
                                default         => '',
                            };
                        ?>
                        <span class="text-gray-400 dark:text-gray-500"><?php echo e($activeRole); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Support actif
            </div>
        </div>

        
        <div
            class="inkpik-card flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gray-50 dark:bg-gray-900/50"
            id="support-chat-messages"
            x-data
            x-init="$el.scrollTop = $el.scrollHeight"
            x-effect="$el.scrollTop = $el.scrollHeight">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->activeMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isAdminMsg = ($message->sender_type ?? '') === 'admin' || $message->sender_id === null;
                $msgContent = $message->content ?? '';
                $msgTime    = $message->created_at->format('d/m H:i');
            ?>

            <div class="flex <?php echo e($isAdminMsg ? 'justify-end' : 'justify-start'); ?> gap-2">

                
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAdminMsg): ?>
                <div class="inkpik-name-admin w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40
                            flex items-center justify-center text-sm flex-shrink-0 mt-1">
                    Support Ink&Pik
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="max-w-[65%]">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isAdminMsg): ?>
                    <p class="inkpik-name text-xs text-gray-500 dark:text-gray-400 mb-1">
                        <?php echo e($message->sender?->name ?? $message->sender?->pseudo ?? 'Utilisateur'); ?>

                    </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="inkpik-btn px-4 py-2.5 rounded-2xl text-sm shadow-sm
                        <?php echo e($isAdminMsg
                            ? 'bg-indigo-600 text-white rounded-tr-none'
                            : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-tl-none border border-gray-200 dark:border-gray-600'); ?>">
                        <?php echo e($msgContent); ?>

                    </div>


                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1
                              <?php echo e($isAdminMsg ? 'text-right' : 'text-left'); ?>">
                        <?php echo e($msgTime); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAdminMsg && $message->read_at): ?>
                            · <span class="text-indigo-400">Lu</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>


            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="flex items-center justify-center h-full py-16 text-gray-400 dark:text-gray-500">
                <div class="text-center">
                    <p class="text-3xl mb-2">💬</p>
                    <p class="text-sm">Aucun message dans cette conversation</p>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="inkpik-card border-t border-gray-200 dark:border-gray-700 px-6 py-4
                    bg-white dark:bg-gray-900">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['newMessage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-500 text-xs mb-2"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex items-end gap-3">
                <div class="flex-1 relative">
                    <textarea
                        wire:model.defer="newMessage"
                        wire:keydown.ctrl.enter="sendReply"
                        placeholder="Répondre en tant qu'équipe Ink&Pik..."
                        rows="2"
                        maxlength="2000"
                        class="inkpik-textarea"
                    ></textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-gray-400 dark:text-gray-500">
                        <?php echo e(\Str::length($newMessage)); ?>/2000
                    </div>
                </div>

                <button
                    wire:click="sendReply"
                    wire:loading.attr="disabled"
                    wire:target="sendReply"
                    class="flex-shrink-0 w-11 h-11 rounded-xl bg-indigo-600
                           text-white flex items-center justify-center
                           hover:bg-indigo-700 transition-colors disabled:opacity-50
                           focus:outline-none focus:ring-2 focus:ring-indigo-400">
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

            <div class="flex items-center justify-between mt-2">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Ctrl+Entrée pour envoyer · L'utilisateur sera notifié
                </p>
                <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Support actif
                </div>
            </div>
        </div>

        <?php else: ?>

        
        <div class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-gray-900/50">
            <div class="text-center text-gray-400 dark:text-gray-500">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">💬</span>
                </div>
                <p class="font-medium text-gray-600 dark:text-gray-300 text-lg mb-2">
                    Sélectionnez une conversation
                </p>
                <p class="text-sm max-w-xs mx-auto">
                    Cliquez sur une conversation dans la liste pour commencer à discuter
                </p>
                <div class="mt-6 flex items-center justify-center gap-2 text-xs">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Support disponible 24/7
                </div>
            </div>
        </div>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/filament/admin/pages/support-chat.blade.php ENDPATH**/ ?>