<?php $__env->startSection('title', 'Chat - ' . $bookingRequest->client->full_name); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête avec alertes d'expiration -->
            <div class="mb-6">
                <a href="<?php echo e(route('tattooer.requests')); ?>"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux demandes
                </a>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->conversation): ?>
                    <?php
                        $conversation = $bookingRequest->conversation;
                        $expiryInfo = null;
                        if ($conversation) {
                            $expiryInfo = [
                                'expires_at' => $conversation->expires_at,
                                'days_remaining' => $conversation->getDaysUntilExpiry(),
                                'time_remaining' => $conversation->getTimeUntilExpiry(),
                                'warning_message' => $conversation->getExpiryWarningMessage(),
                                'is_expired' => $conversation->isExpired(),
                                'expiry_type' => $conversation->expiry_type,
                                'deposit_deadline_at' => $conversation->deposit_deadline_at,
                            ];
                        }
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiryInfo && $expiryInfo['warning_message']): ?>
                        <div
                            class="mb-4 p-4 rounded-lg border <?php echo e($expiryInfo['is_expired'] ? 'bg-rouge-alerte/10 border-rouge-alerte/30' : 'bg-jaune-alerte/10 border-jaune-alerte/30'); ?>">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mt-0.5 mr-3 <?php echo e($expiryInfo['is_expired'] ? 'text-rouge-alerte' : 'text-jaune-alerte'); ?>"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiryInfo['is_expired']): ?>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    <?php else: ?>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </svg>
                                <div class="flex-1">
                                    <h3
                                        class="font-semibold <?php echo e($expiryInfo['is_expired'] ? 'text-rouge-alerte' : 'text-jaune-alerte'); ?> mb-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiryInfo['is_expired']): ?>
                                            ❌ Conversation expirée
                                        <?php elseif($expiryInfo['expiry_type'] === 'deposit_pending'): ?>
                                            ⏰ Délai d'acompte
                                        <?php else: ?>
                                            ℹ️ Information
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </h3>
                                    <p
                                        class="<?php echo e($expiryInfo['is_expired'] ? 'text-rouge-alerte' : 'text-jaune-alerte'); ?> text-sm">
                                        <?php echo e($expiryInfo['warning_message']); ?>

                                    </p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$expiryInfo['is_expired'] && $expiryInfo['time_remaining'] !== ''): ?>
                                        <div class="mt-2">
                                            <div class="flex items-center justify-between text-sm">
                                                <span
                                                    class="<?php echo e($expiryInfo['days_remaining'] <= 2 ? 'text-rouge-alerte font-semibold' : 'text-jaune-alerte'); ?>">
                                                    <?php echo e($expiryInfo['time_remaining']); ?> restant(es)
                                                </span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status === 'awaiting_deposit'): ?>
                                                    <a href="<?php echo e(route('deposit.payment', $bookingRequest->id)); ?>"
                                                        class="inline-flex items-center px-3 py-1 bg-beige-peau text-noir-profond rounded text-sm font-medium hover:bg-beige-peau/90 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                        Payer l'acompte
                                                    </a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-ivoire-text">Chat avec <?php echo e($bookingRequest->client->user->name ?? $bookingRequest->client->full_name); ?>

                    </h1>
                    <p class="text-ivoire-text/70 mt-1">Projet: <?php echo e($bookingRequest->tattoo_description); ?></p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status === 'accepted'): ?>
                    <a href="<?php echo e(route('booking-request.deposit.request', $bookingRequest)); ?>"
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        💰 Demander acompte
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Zone de chat -->
        <div class="bg-titane/20 rounded-xl border border-titane/30">
            <!-- Messages flash avec fermeture auto + manuelle -->
            <div x-data="{
                showSuccess: <?php echo e(session('success') ? 'true' : 'false'); ?>,
                showError: <?php echo e(session('error') ? 'true' : 'false'); ?>,
                showWarning: <?php echo e(session('warning') ? 'true' : 'false'); ?>

            }" x-init="if (showSuccess || showError || showWarning) {
                setTimeout(() => {
                    showSuccess = false;
                    showError = false;
                    showWarning = false;
                }, 5000);
            }">

                <!-- Message succès -->
                <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-vert-succes/20 border border-vert-succes/30 text-vert-succes p-4 rounded-xl m-4 flex items-center justify-between"
                    style="display: none;">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="showSuccess = false" class="text-vert-succes/80 hover:text-vert-succes">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Message erreur -->
                <div x-show="showError" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte p-4 rounded-xl m-4 flex items-center justify-between"
                    style="display: none;">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><?php echo e(session('error')); ?></span>
                    </div>
                    <button @click="showError = false" class="text-rouge-alerte/80 hover:text-rouge-alerte">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Message warning -->
                <div x-show="showWarning" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-jaune-alerte/20 border border-jaune-alerte/30 text-jaune-alerte p-4 rounded-xl m-4 flex items-center justify-between"
                    style="display: none;">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <span><?php echo e(session('warning')); ?></span>
                    </div>
                    <button @click="showWarning = false" class="text-jaune-alerte/80 hover:text-jaune-alerte">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div id="messages-container" class="h-96 overflow-y-auto p-6 space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($messages->isEmpty()): ?>
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-noir-profond rounded-full mb-4">
                            <svg class="w-8 h-8 text-ivoire-text/50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.009 9.009 0 00-2.617-.656L4 19l1.383-5.344A9.002 9.002 0 016 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                $bookingRequest->status === 'accepted' &&
                                    $bookingRequest->accepted_at &&
                                    $bookingRequest->accepted_at->diffInDays(now()) <= 7 &&
                                    !$bookingRequest->deposit_paid_at): ?>
                                Chat ouvert
                            <?php else: ?>
                                Chat fermé
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </h3>
                        <p class="text-ivoire-text/70">
                            <?php if($bookingRequest->status !== 'accepted'): ?>
                                Le chat sera disponible lorsque le projet sera accepté
                            <?php elseif($bookingRequest->deposit_paid_at): ?>
                                Le chat est fermé car l'acompte a été payé
                            <?php elseif($bookingRequest->accepted_at && $bookingRequest->accepted_at->diffInDays(now()) > 7): ?>
                                Le chat est fermé (délai de 7 jours dépassé)
                            <?php else: ?>
                                Le chat est ouvert pour discuter avec le client
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex <?php echo e($message->sender_type === 'client' ? 'justify-start' : 'justify-end'); ?>">
                            <div class="max-w-xs lg:max-w-md">
                                <div
                                    class="<?php echo e($message->sender_type === 'client' ? 'bg-noir-profond text-ivoire-text' : 'bg-beige-peau text-noir-profond'); ?> rounded-lg px-4 py-2">
                                    <p class="text-sm whitespace-pre-wrap">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty(trim($message->content))): ?>
                                            <?php echo e($message->content); ?>

                                        <?php elseif($message->getMedia('attachments')->isNotEmpty()): ?>
                                            <span class="text-ivoire-text/60 italic">Dessin envoyé</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->getMedia('attachments')->isNotEmpty()): ?>
                                        <div class="mt-2 space-y-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $message->getMedia('attachments'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_starts_with($media->mime_type, 'image/')): ?>
                                                    <img src="<?php echo e($media->getUrl()); ?>" alt="Pièce jointe"
                                                        class="rounded max-w-full cursor-pointer hover:opacity-90"
                                                        onclick="window.open('<?php echo e($media->getUrl()); ?>', '_blank')">
                                                <?php else: ?>
                                                    <a href="<?php echo e($media->getUrl()); ?>" target="_blank"
                                                        class="block text-xs underline">
                                                        📎 <?php echo e($media->file_name); ?>

                                                    </a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <p class="text-xs text-ivoire-text/50 mt-1">
                                    <?php echo e($message->created_at->format('H:i')); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Zone de saisie -->
            <div class="border-t border-titane/30 p-4">
                <div class="bg-titane/20 rounded-xl p-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->isChatOpen()): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$bookingRequest->deposit_paid_at): ?>
                            <div class="bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg p-3 mb-4">
                                <p class="text-jaune-alerte text-sm">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Les pièces jointes sont désactivées jusqu'au paiement de l'acompte
                                </p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <form action="<?php echo e(route('tattooer.message.send', $bookingRequest)); ?>" method="POST"
                            enctype="multipart/form-data" class="space-y-3" x-data="{
                                message: '',
                                attachments: [],
                                resizeTextarea() {
                                    const textarea = this.$refs.messageInput;
                                    textarea.style.height = 'auto';
                                    textarea.style.height = textarea.scrollHeight + 'px';
                                },
                                handleFileSelect(event) {
                                    const files = event.target.files;
                                    this.attachments = Array.from(files);
                                },
                                removeFile(index) {
                                    this.attachments.splice(index, 1);
                                }
                            }"
                            @submit="message = ''">
                            <?php echo csrf_field(); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->deposit_paid_at): ?>
                                <div class="flex items-end gap-2">
                                    <input type="file" name="attachments[]" id="attachments" multiple
                                        accept="image/*,application/pdf" class="hidden"
                                        @change="handleFileSelect($event)">

                                    <button type="button" onclick="document.getElementById('attachments').click()"
                                        class="px-4 py-3 bg-noir-profond text-ivoire-text rounded-lg hover:bg-noir-profond/80 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                    </button>

                                    <textarea x-ref="messageInput" x-model="message" @input="resizeTextarea()" name="content" rows="1"
                                        placeholder="Votre message..."
                                        class="flex-1 px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none overflow-hidden"
                                        style="min-height: 3rem; max-height: 10rem;"
                                        onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>

                                    <button type="submit"
                                        class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                        Envoyer
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="flex items-end gap-2">
                                    <input type="file" name="attachments[]" id="attachments" multiple
                                        accept="image/*,application/pdf" class="hidden"
                                        @change="handleFileSelect($event)">

                                    <button type="button" onclick="document.getElementById('attachments').click()"
                                        class="px-4 py-3 bg-noir-profond text-ivoire-text rounded-lg hover:bg-noir-profond/80 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                    </button>

                                    <textarea x-ref="messageInput" x-model="message" @input="resizeTextarea()" name="content" rows="1"
                                        placeholder="Votre message..."
                                        class="flex-1 px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none overflow-hidden"
                                        style="min-height: 3rem; max-height: 10rem;"
                                        onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>

                                    <button type="submit"
                                        class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                        Envoyer
                                    </button>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <!-- Prévisualisation des fichiers -->
                            <div x-show="attachments.length > 0" class="mt-2 flex gap-2 flex-wrap">
                                <template x-for="(file, index) in attachments" :key="index">
                                    <div class="relative inline-block">
                                        <img :src="URL.createObjectURL(file)"
                                             :alt="file.name"
                                             class="w-20 h-20 rounded-lg object-cover">
                                        <button type="button"
                                                @click="removeFile(index)"
                                                class="absolute -top-2 -right-2 bg-rouge-alerte text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-rouge-alerte/80">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </form>
                    <?php else: ?>
                        <form class="flex space-x-4 opacity-50 pointer-events-none">
                            <input type="text" placeholder="Chat fermé"
                                class="flex-1 px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:outline-none focus:ring-2 focus:ring-beige-peau focus:border-transparent"
                                disabled>
                            <button type="submit" disabled
                                class="px-6 py-2 bg-gris-fonde text-ivoire-text/50 rounded-lg font-semibold cursor-not-allowed">
                                Fermé
                            </button>
                        </form>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Informations projet et client (accordéons mobile uniquement) -->
            <div class="md:hidden mt-6 space-y-4">
                <!-- Détails du projet -->
                <div class="bg-titane/20 rounded-xl border border-titane/30 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-titane/30 transition-colors">
                        <h3 class="text-lg font-bold text-ivoire-text">Détails du projet</h3>
                        <svg class="w-5 h-5 text-ivoire-text transition-transform"
                            :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="px-6 pb-4">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Emplacement:</span>
                                <span class="text-ivoire-text"><?php echo e($bookingRequest->tattoo_location); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Style:</span>
                                <span class="text-ivoire-text"><?php echo e($bookingRequest->tattoo_style); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Estimation tattoo:</span>
                                <span class="text-ivoire-text">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->price_range_min && $bookingRequest->price_range_max): ?>
                                        <?php echo e(number_format($bookingRequest->price_range_min, 0)); ?>€ - <?php echo e(number_format($bookingRequest->price_range_max, 0)); ?>€
                                    <?php elseif($bookingRequest->estimated_total_price): ?>
                                        {{ number_format($bookingRequest->estimated_total_price, 2, ',', ' ') . ' €'
                                    <?php else: ?>
                                        Non défini
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Statut:</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                <?php echo e(match ($bookingRequest->status) {
                                    'pending' => 'bg-jaune-alerte/20 text-jaune-alerte',
                                    'accepted' => 'bg-vert-succes/20 text-vert-succes',
                                    'awaiting_deposit' => 'bg-jaune-alerte/20 text-jaune-alerte',
                                    'deposit_paid' => 'bg-vert-succes/20 text-vert-succes',
                                    'in_progress' => 'bg-beige-peau/20 text-beige-peau',
                                    'completed' => 'bg-vert-succes/20 text-vert-succes',
                                    'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                    default => 'bg-gris-fonde/20 text-ivoire-text',
                                }); ?>">
                                    <?php echo e(match ($bookingRequest->status) {
                                        'pending' => '⏳ En attente',
                                        'accepted' => '✅ Acceptée',
                                        'awaiting_deposit' => '⏳ Acompte attendu',
                                        'deposit_paid' => '💰 Acompte payé',
                                        'in_progress' => '🎨 En cours',
                                        'completed' => '✅ Terminé',
                                        'cancelled' => '❌ Annulée',
                                        default => ucfirst($bookingRequest->status),
                                    }); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations client -->
                <div class="bg-titane/20 rounded-xl border border-titane/30 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-titane/30 transition-colors">
                        <h3 class="text-lg font-bold text-ivoire-text">Informations client</h3>
                        <svg class="w-5 h-5 text-ivoire-text transition-transform"
                            :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="px-6 pb-4">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Email:</span>
                                <span class="text-ivoire-text"><?php echo e($bookingRequest->client->user->email); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Téléphone:</span>
                                <span class="text-ivoire-text"><?php echo e($bookingRequest->client->phone ?: 'Non renseigné'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Date de naissance:</span>
                                <span class="text-ivoire-text"><?php echo e($bookingRequest->client->birth_date ? $bookingRequest->client->birth_date->format('d/m/Y') : 'Non renseignée'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Version desktop (toujours visible) -->
            <div class="hidden md:grid md:grid-cols-2 md:gap-6 md:mt-6">
                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Détails du projet</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Emplacement:</span>
                            <span class="text-ivoire-text"><?php echo e($bookingRequest->tattoo_location); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Style:</span>
                            <span class="text-ivoire-text"><?php echo e($bookingRequest->tattoo_style); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Estimation tattoo:</span>
                            <span class="text-ivoire-text">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->price_range_min && $bookingRequest->price_range_max): ?>
                                    <?php echo e(number_format($bookingRequest->price_range_min, 0)); ?>€ - <?php echo e(number_format($bookingRequest->price_range_max, 0)); ?>€
                                <?php elseif($bookingRequest->estimated_total_price): ?>
                                    {{ number_format($bookingRequest->estimated_total_price, 2, ',', ' ') . ' €'
                                <?php else: ?>
                                    Non défini
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Statut:</span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            <?php echo e(match ($bookingRequest->status) {
                                'pending' => 'bg-jaune-alerte/20 text-jaune-alerte',
                                'accepted' => 'bg-vert-succes/20 text-vert-succes',
                                'awaiting_deposit' => 'bg-jaune-alerte/20 text-jaune-alerte',
                                'deposit_paid' => 'bg-vert-succes/20 text-vert-succes',
                                'in_progress' => 'bg-beige-peau/20 text-beige-peau',
                                'completed' => 'bg-vert-succes/20 text-vert-succes',
                                'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                default => 'bg-gris-fonde/20 text-ivoire-text',
                            }); ?>">
                                <?php echo e(match ($bookingRequest->status) {
                                    'pending' => '⏳ En attente',
                                    'accepted' => '✅ Acceptée',
                                    'awaiting_deposit' => '⏳ Acompte attendu',
                                    'deposit_paid' => '💰 Acompte payé',
                                    'in_progress' => '🎨 En cours',
                                    'completed' => '✅ Terminé',
                                    'cancelled' => '❌ Annulée',
                                    default => ucfirst($bookingRequest->status),
                                }); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Informations client</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Email:</span>
                            <span class="text-ivoire-text"><?php echo e($bookingRequest->client->user->email); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Téléphone:</span>
                            <span class="text-ivoire-text"><?php echo e($bookingRequest->client->phone ?: 'Non renseigné'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Date de naissance:</span>
                            <span class="text-ivoire-text"><?php echo e($bookingRequest->client->birth_date ? $bookingRequest->client->birth_date->format('d/m/Y') : 'Non renseignée'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Scroll automatique vers dernier message -->
    <script>
        // Scroll automatique vers dernier message au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messages-container');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });

        // Scroll après envoi message (optionnel, si formulaire en AJAX)
        document.addEventListener('message-sent', function() {
            const messagesContainer = document.getElementById('messages-container');
            if (messagesContainer) {
                setTimeout(() => {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }, 100);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/tattooer/message-show.blade.php ENDPATH**/ ?>