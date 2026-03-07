<?php $__env->startSection('title', 'Mes conversations'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond">
        <div class="h-screen flex flex-col">
            
            <!-- Header -->
            <div class="bg-gris-fonde border-b border-titane/20 px-4 py-3">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold text-ivoire-text">
                        💬 Mes conversations
                    </h1>
                    
                    <div class="flex items-center space-x-3">
                        <a href="<?php echo e(route('client.booking-requests')); ?>" 
                           class="text-ivoire-text/60 hover:text-ivoire-text transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                        <a href="<?php echo e(route('client.conversations')); ?>" 
                           class="text-ivoire-text/60 hover:text-ivoire-text transition-colors" title="Voir toutes les conversations">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="flex-1 flex overflow-hidden">
                
                <!-- Liste des conversations -->
                <div class="w-full md:w-1/3 lg:w-1/4 bg-gris-fonde border-r border-titane/20 overflow-y-auto">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversations->count() > 0): ?>
                        <div class="divide-y divide-titane/20">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $bookingRequest = $conversation->bookingRequest;
                                    $artist = $bookingRequest?->bookable;
                                    $artistUser = $artist?->user;
                                    $artistName = $artistUser?->name ?? 'Artiste inconnu';
                                    $lastMessage = $conversation->lastMessage;
                                    $isActive = isset($activeConversation) && $activeConversation->id === $conversation->id;
                                ?>
                                
                                <a href="<?php echo e(route('client.chat', $conversation)); ?>" 
                                   class="block p-4 hover:bg-titane/10 transition-colors <?php echo e($isActive ? 'bg-titane/20 border-l-4 border-beige-peau' : ''); ?>">
                                    <div class="flex items-start space-x-3">
                                        <!-- Avatar artiste -->
                                        <div class="w-12 h-12 rounded-full overflow-hidden bg-beige-peau/10 flex-shrink-0">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artistUser && $artistUser->getFirstMediaUrl('avatar')): ?>
                                                <img src="<?php echo e($artistUser->getFirstMediaUrl('avatar')); ?>" 
                                                     alt="<?php echo e($artistName); ?>" 
                                                     class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full bg-beige-peau/20 flex items-center justify-center">
                                                    <span class="text-beige-peau/60 text-sm font-bold">
                                                        <?php echo e(substr($artistName, 0, 2)); ?>

                                                    </span>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        
                                        <!-- Infos conversation -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-1">
                                                <h3 class="font-semibold text-ivoire-text truncate">
                                                    <?php echo e($artistName); ?>

                                                </h3>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversation->unread_count > 0): ?>
                                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-beige-peau text-noir-profond text-xs font-bold rounded-full">
                                                        <?php echo e($conversation->unread_count); ?>

                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <p class="text-ivoire-text/60 text-sm truncate">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMessage): ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMessage->sender_type === 'system'): ?>
                                                            🤖 <?php echo e(Str::limit($lastMessage->content, 30)); ?>

                                                        <?php else: ?>
                                                            <?php echo e(Str::limit($lastMessage->content, 30)); ?>

                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php else: ?>
                                                        📝 Nouvelle conversation
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </p>
                                                <span class="text-ivoire-text/40 text-xs">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversation->last_message_at): ?>
                                                        <?php echo e($conversation->last_message_at->diffForHumans()); ?>

                                                    <?php else: ?>
                                                        <?php echo e($conversation->created_at->diffForHumans()); ?>

                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </span>
                                            </div>
                                            
                                            <!-- Statut booking -->
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest): ?>
                                                <div class="mt-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                        <?php switch($bookingRequest->status):
                                                            case ('pending'): ?> bg-orange-terre-cuite/20 text-orange-terre-cuite <?php break; ?>
                                                            <?php case ('accepted'): ?> bg-vert-succes/20 text-vert-succes <?php break; ?>
                                                            <?php case ('in_progress'): ?> bg-beige-peau/20 text-beige-peau <?php break; ?>
                                                            <?php case ('completed'): ?> bg-titane/30 text-ivoire-text/60 <?php break; ?>
                                                            <?php case ('cancelled'): ?> bg-rouge-alerte/20 text-rouge-alerte <?php break; ?>
                                                            <?php default: ?> bg-titane/20 text-ivoire-text/60
                                                        <?php endswitch; ?>
                                                    ">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($bookingRequest->status):
                                                            case ('pending'): ?> ⏳ En attente <?php break; ?>
                                                            <?php case ('accepted'): ?> ✓ Acceptée <?php break; ?>
                                                            <?php case ('in_progress'): ?> 🎨 En cours <?php break; ?>
                                                            <?php case ('completed'): ?> ✅ Terminée <?php break; ?>
                                                            <?php case ('cancelled'): ?> ❌ Annulée <?php break; ?>
                                                            <?php default: ?> <?php echo e($bookingRequest->status); ?>

                                                        <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-beige-peau/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <p class="text-ivoire-text/60 mb-4">
                                Aucune conversation
                            </p>
                            <a href="<?php echo e(route('client.booking-requests')); ?>" 
                               class="text-beige-peau hover:text-beige-peau/80 font-medium">
                                Commencer une demande
                            </a>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Chat actif (desktop) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($activeConversation)): ?>
                    <div class="hidden md:flex flex-1 flex-col">
                        <!-- En-tête conversation -->
                        <div class="bg-titane/20 border-b border-titane/30 px-6 py-4">
                            <?php
                                $artist = $activeConversation->bookingRequest?->bookable;
                                $artistUser = $artist?->user;
                            ?>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-beige-peau/10">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artistUser && $artistUser->getFirstMediaUrl('avatar')): ?>
                                            <img src="<?php echo e($artistUser->getFirstMediaUrl('avatar')); ?>" 
                                                 alt="<?php echo e($artistUser->name); ?>" 
                                                 class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-beige-peau/20 flex items-center justify-center">
                                                <span class="text-beige-peau/60 text-xs font-bold">
                                                    <?php echo e(substr($artistUser->name ?? '??', 0, 2)); ?>

                                                </span>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-ivoire-text">
                                            <?php echo e($artistUser->name ?? 'Artiste inconnu'); ?>

                                        </h3>
                                        <p class="text-ivoire-text/60 text-sm">
                                            <?php echo e($artist ? class_basename($artist) : 'Artiste'); ?>

                                        </p>
                                    </div>
                                </div>
                                
                                <a href="<?php echo e(route('client.chat', $activeConversation)); ?>" 
                                   class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond rounded-lg font-medium transition-colors">
                                    Ouvrir le chat
                                </a>
                            </div>
                        </div>

                        <!-- Messages récents -->
                        <div class="flex-1 overflow-y-auto p-6">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeConversation->messages && $activeConversation->messages->count() > 0): ?>
                                <div class="space-y-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $activeConversation->messages->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-start space-x-3 <?php echo e($message->sender_type === 'client' ? 'justify-end' : ''); ?>">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->sender_type !== 'client'): ?>
                                                <div class="w-8 h-8 rounded-full overflow-hidden bg-beige-peau/10 flex-shrink-0">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->sender && $message->sender->getFirstMediaUrl('avatar')): ?>
                                                        <img src="<?php echo e($message->sender->getFirstMediaUrl('avatar')); ?>" 
                                                             alt="<?php echo e($message->sender->name); ?>" 
                                                             class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div class="w-full h-full bg-beige-peau/20 flex items-center justify-center">
                                                            <span class="text-beige-peau/60 text-xs">
                                                                🤖
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            
                                            <div class="max-w-xs lg:max-w-md">
                                                <div class="px-4 py-2 rounded-lg 
                                                    <?php switch($message->sender_type):
                                                        case ('client'): ?> bg-beige-peau text-noir-profond <?php break; ?>
                                                        <?php case ('system'): ?> bg-titane/30 text-ivoire-text/80 <?php break; ?>
                                                        <?php default: ?> bg-gris-fonde text-ivoire-text
                                                    <?php endswitch; ?>
                                                ">
                                                    <p class="text-sm"><?php echo e($message->content); ?></p>
                                                </div>
                                                <p class="text-ivoire-text/40 text-xs mt-1 <?php echo e($message->sender_type === 'client' ? 'text-right' : ''); ?>">
                                                    <?php echo e($message->created_at->format('H:i')); ?>

                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-ivoire-text/60 py-8">
                                    Aucun message dans cette conversation
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Input message (désactivé - juste pour aperçu) -->
                        <div class="bg-gris-fonde border-t border-titane/20 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <input type="text" 
                                       placeholder="Tapez votre message..." 
                                       disabled
                                       class="flex-1 px-4 py-2 bg-titane/20 border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/40 disabled:opacity-50">
                                <button disabled 
                                        class="px-4 py-2 bg-beige-peau/50 text-noir-profond rounded-lg font-medium disabled:opacity-50">
                                    Envoyer
                                </button>
                            </div>
                            <p class="text-ivoire-text/40 text-xs mt-2 text-center">
                                Cliquez sur "Ouvrir le chat" pour continuer la conversation
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- État vide -->
                    <div class="hidden md:flex flex-1 items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto mb-6 bg-beige-peau/10 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-beige-peau/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-ivoire-text mb-2">
                                Sélectionnez une conversation
                            </h3>
                            <p class="text-ivoire-text/60">
                                Choisissez une conversation dans la liste pour voir les messages
                            </p>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client-messages-complex.blade.php ENDPATH**/ ?>