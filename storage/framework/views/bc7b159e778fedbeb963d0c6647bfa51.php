<?php $__env->startSection('title', 'Chat avec ' . $bookingRequest->bookable->user->name); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            
            <div class="mb-6">
                <a href="<?php echo e(route('client.booking-requests')); ?>"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à mes demandes
                </a>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiryInfo && $expiryInfo['warning_message']): ?>
                    <div
                        class="mb-4 p-4 rounded-lg border <?php echo e($expiryInfo['is_expired'] ? 'bg-rouge-alerte/10 border-rouge-alerte/30' : 'bg-jaune-alerte/10 border-jaune-alerte/30'); ?>">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-3 <?php echo e($expiryInfo['is_expired'] ? 'text-rouge-alerte' : 'text-jaune-alerte'); ?>"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status === 'awaiting_deposit' && !$bookingRequest->deposit_paid_at): ?>
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
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiryInfo['days_remaining'] <= 2): ?>
                                            <div class="mt-2 bg-rouge-alerte/20 rounded p-2">
                                                <p class="text-rouge-alerte text-xs font-medium">
                                                    ⚠️ Urgent : Payer l'acompte avant la suppression du chat
                                                </p>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$expiryInfo && $bookingRequest->status === 'awaiting_deposit' && !$bookingRequest->deposit_paid_at): ?>
                    <div class="mb-4 p-4 bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-3 text-jaune-alerte" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1">
                                <h3 class="font-semibold text-jaune-alerte mb-1">⏰ Acompte en attente</h3>
                                <p class="text-jaune-alerte text-sm">Payer l'acompte pour finaliser votre réservation</p>
                                <div class="mt-2">
                                    <a href="<?php echo e(route('deposit.payment', $bookingRequest->id)); ?>"
                                        class="inline-flex items-center px-3 py-1 bg-beige-peau text-noir-profond rounded text-sm font-medium hover:bg-beige-peau/90 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Payer l'acompte
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-ivoire-text">Chat avec
                            <?php echo e($bookingRequest->bookable->user->name); ?></h1>
                        <p class="text-ivoire-text/70 mt-1">Projet:
                            <?php echo e(Str::limit($bookingRequest->tattoo_description, 80)); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="bg-titane/20 rounded-xl border border-titane/30">

                
                <div id="messages-container" class="h-96 overflow-y-auto p-6 space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($messages->isEmpty()): ?>
                        <div class="text-center py-12">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 bg-noir-profond rounded-full mb-4">
                                <svg class="w-8 h-8 text-ivoire-text/50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.009 9.009 0 00-2.617-.656L4 19l1.383-5.344A9.002 9.002 0 016 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sendMessage', $conversation)): ?>
                                    Aucun message
                                <?php else: ?>
                                    Chat fermé
                                <?php endif; ?>
                            </h3>
                            <p class="text-ivoire-text/70">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sendMessage', $conversation)): ?>
                                    Commencez la conversation avec l'artiste
                                <?php else: ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status === \App\Enums\BookingRequestStatus::PENDING): ?>
                                        Le chat sera disponible après acceptation du projet
                                    <?php elseif($bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID): ?>
                                        Le chat est actif (acompte payé)
                                    <?php else: ?>
                                        Le chat est fermé
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->sender_type === 'system'): ?>
                                
                                <?php
                                    $consentMatch = [];
                                    $isConsentMsg = preg_match(
                                        '/\[CONSENT_FORM:(\d+)\]/',
                                        $message->content,
                                        $consentMatch,
                                    );
                                    $consentBrId = $isConsentMsg ? (int) $consentMatch[1] : null;
                                    $existingConsent = $consentBrId
                                        ? \App\Models\ClientConsentForm::where(
                                            'booking_request_id',
                                            $consentBrId,
                                        )->first()
                                        : null;
                                ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isConsentMsg): ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingConsent && $existingConsent->isValid()): ?>
                                        <div class="flex justify-center mb-4">
                                            <div
                                                class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-4 text-center max-w-sm">
                                                <span class="text-2xl">✅</span>
                                                <p class="text-sm text-vert-succes font-semibold mt-1">Consentement éclairé
                                                    signé</p>
                                                <p class="text-xs text-titane mt-1">Le
                                                    <?php echo e($existingConsent->signed_at->format('d/m/Y à H:i')); ?></p>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex justify-center mb-4">
                                            <div
                                                class="bg-beige-peau/10 border border-beige-peau/30 rounded-xl p-4 text-center max-w-sm">
                                                <span class="text-2xl">📝</span>
                                                <p class="text-sm text-ivoire-text font-semibold mt-1">Consentement éclairé
                                                    à remplir</p>
                                                <p class="text-xs text-titane mt-1 mb-3">Obligatoire avant votre rendez-vous
                                                </p>
                                                <button
                                                    onclick="document.getElementById('consent-modal-<?php echo e($consentBrId); ?>').classList.remove('hidden'); document.body.style.overflow='hidden';"
                                                    class="px-6 py-2.5 bg-beige-peau text-noir-profond rounded-lg font-bold text-sm hover:bg-beige-peau/90 transition-colors">
                                                    Remplir le consentement
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                    
                                    <div class="flex justify-center mb-4">
                                        <div class="max-w-sm">
                                            <div
                                                class="bg-titane/20 border border-titane/30 text-ivoire-text/80 rounded-lg px-4 py-2 text-center">
                                                <p class="text-sm"><?php echo e($message->content); ?></p>
                                                <p class="text-xs text-ivoire-text/50 mt-1">
                                                    <?php echo e($message->created_at->format('H:i')); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                
                                <div
                                    class="flex <?php echo e($message->sender_type === 'tattooer' ? 'justify-start' : 'justify-end'); ?> mb-4">
                                    <div class="max-w-xs lg:max-w-md">
                                        <div
                                            class="<?php echo e($message->sender_type === 'tattooer'
                                                ? 'bg-noir-profond text-ivoire-text'
                                                : 'bg-beige-peau text-noir-profond'); ?> rounded-lg px-4 py-2">
                                            <p class="text-sm whitespace-pre-wrap"><?php echo e($message->content); ?></p>

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
                                        <p
                                            class="text-xs text-ivoire-text/50 mt-1 <?php echo e($message->sender_type === 'tattooer' ? '' : 'text-right'); ?>">
                                            <?php echo e($message->created_at->format('H:i')); ?>

                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                    $bookingRequest->proposed_dates &&
                        count($bookingRequest->proposed_dates) > 0 &&
                        !$bookingRequest->confirmed_date &&
                        in_array($bookingRequest->status->value, ['accepted', 'deposit_paid']) &&
                        $bookingRequest->deposit_paid_at): ?>
                    <div class="bg-vert-succes/5 border border-vert-succes/20 rounded-xl p-4 mx-4 mt-4">
                        <h3 class="text-sm font-bold text-ivoire-text mb-1">📅 Choisissez votre date de rendez-vous</h3>
                        <p class="text-xs text-ivoire-text/60 mb-3">Sélectionnez la date qui vous convient.</p>

                        <div class="space-y-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequest->proposed_dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $proposalDate = \Carbon\Carbon::parse($proposal['date']);
                                    $periodLabel = match ($proposal['period'] ?? '') {
                                        'morning' => '☀️ Matin',
                                        'afternoon' => '🌤️ Après-midi',
                                        'evening' => '🌙 Soirée',
                                        default => '🔄 Flexible',
                                    };
                                    $medal = match ($index) {
                                        0 => '🥇',
                                        1 => '🥈',
                                        2 => '🥉',
                                        default => '📅',
                                    };
                                ?>

                                <form action="<?php echo e(route('client.booking-request.select-date', $bookingRequest)); ?>"
                                    method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="index" value="<?php echo e($index); ?>">
                                    <button type="submit"
                                        onclick="return confirm('Confirmer la date du <?php echo e($proposalDate->translatedFormat('l d F Y')); ?> (<?php echo e(strip_tags($periodLabel)); ?>) ?')"
                                        class="w-full flex items-center justify-between p-3 rounded-lg border border-titane/30
                                            hover:border-beige-peau hover:bg-beige-peau/10 cursor-pointer transition-all text-left">
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg"><?php echo e($medal); ?></span>
                                            <div>
                                                <p class="text-ivoire-text font-medium text-sm">
                                                    <?php echo e($proposalDate->translatedFormat('l d F Y')); ?>

                                                </p>
                                                <p class="text-xs text-titane"><?php echo e($periodLabel); ?></p>
                                            </div>
                                        </div>
                                        <span class="text-beige-peau font-bold text-xs">Choisir →</span>
                                    </button>
                                </form>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <form action="<?php echo e(route('client.booking-request.request-alternatives', $bookingRequest)); ?>"
                            method="POST" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-xs text-titane underline hover:text-ivoire-text">
                                Aucune date ne me convient
                            </button>
                        </form>
                    </div>
                <?php elseif($bookingRequest->confirmed_date && !$bookingRequest->appointment_datetime): ?>
                    <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-lg p-4 mx-4 mt-4">
                        <p class="text-sm text-vert-succes font-medium">
                            ✅ Date choisie :
                            <?php echo e(\Carbon\Carbon::parse($bookingRequest->confirmed_date)->translatedFormat('l d F Y')); ?>

                        </p>
                        <p class="text-xs text-ivoire-text/60 mt-1">L'artiste va fixer l'horaire exact du rendez-vous.</p>
                    </div>
                <?php elseif($bookingRequest->appointment_datetime): ?>
                    <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-lg p-4 mx-4 mt-4">
                        <p class="text-sm text-vert-succes font-medium">
                            ✅ Rendez-vous confirmé :
                            <?php echo e(\Carbon\Carbon::parse($bookingRequest->appointment_datetime)->translatedFormat('l d F Y')); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->scheduled_start_time && $bookingRequest->scheduled_end_time): ?>
                                de <?php echo e($bookingRequest->scheduled_start_time); ?> à <?php echo e($bookingRequest->scheduled_end_time); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="border-t border-titane/30 p-4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sendMessage', $conversation)): ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$bookingRequest->deposit_paid_at): ?>
                            <?php
                                $deadline = null;
                                if ($bookingRequest->deposit_deadline) {
                                    $deadline = is_string($bookingRequest->deposit_deadline)
                                        ? \Carbon\Carbon::parse($bookingRequest->deposit_deadline)
                                        : $bookingRequest->deposit_deadline;
                                } elseif ($conversation && $conversation->deposit_deadline_at) {
                                    $deadline = is_string($conversation->deposit_deadline_at)
                                        ? \Carbon\Carbon::parse($conversation->deposit_deadline_at)
                                        : $conversation->deposit_deadline_at;
                                }
                                $daysRemaining = $deadline ? (int) ceil(now()->diffInHours($deadline) / 24) : null;
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deadline): ?>
                                <div class="bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg p-3 mb-4">
                                    <p class="text-jaune-alerte text-sm">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Les pièces jointes sont désactivées jusqu'au paiement de l'acompte
                                        <span class="block mt-1">
                                            Délai :
                                            <?php echo e($daysRemaining > 0 ? $daysRemaining . ' jour(s) restant(s)' : 'Dernier jour'); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daysRemaining <= 1): ?>
                                                <span class="text-rouge-alerte font-semibold"> - Urgent !</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </span>
                                    </p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <form action="<?php echo e(route('client.message.send', $conversation)); ?>" method="POST"
                            enctype="multipart/form-data" class="space-y-3">
                            <?php echo csrf_field(); ?>

                            <div class="flex gap-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->deposit_paid_at): ?>
                                    <input type="file" name="attachments[]" id="attachments" multiple
                                        accept="image/*,application/pdf" class="hidden">
                                    <button type="button" onclick="document.getElementById('attachments').click()"
                                        class="px-4 py-3 bg-noir-profond text-ivoire-text rounded-lg hover:bg-noir-profond/80 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <textarea name="content" rows="3" placeholder="Votre message..."
                                    class="flex-1 px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none text-sm"
                                    required></textarea>

                                <button type="submit"
                                    class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                    Envoyer
                                </button>
                            </div>

                            
                            <div id="filePreview" class="hidden mt-3 bg-titane/20 rounded-lg p-3 border border-titane/30">
                                <h4 class="text-sm font-semibold text-ivoire-text mb-2">Fichiers à envoyer :</h4>
                                <div id="previewContainer" class="space-y-2"></div>
                            </div>

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->deposit_paid_at): ?>
                                <?php $summary = $bookingRequest->designTrackingSummary(); ?>
                                <div class="mt-4 bg-titane/20 rounded-xl p-4 border border-titane/30">
                                    <h3 class="text-lg font-semibold text-ivoire-text mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-beige-peau" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Suivi des dessins
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="bg-noir-profond/30 rounded-lg p-3">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-ivoire-text/70 text-sm">Dessins reçus</span>
                                                <span class="text-beige-peau font-bold"><?php echo e($summary['designs_sent']); ?></span>
                                            </div>
                                            <div class="w-full bg-titane/30 rounded-full h-2">
                                                <div class="bg-beige-peau h-2 rounded-full transition-all"
                                                    style="width: <?php echo e($summary['designs_included'] > 0 ? min(100, ($summary['designs_sent'] / $summary['designs_included']) * 100) : 0); ?>%">
                                                </div>
                                            </div>
                                            <p class="text-ivoire-text/50 text-xs mt-1">
                                                <?php echo e($summary['designs_included']); ?> inclus —
                                                <?php echo e($summary['designs_remaining']); ?> restant(s)
                                            </p>
                                        </div>

                                        <div class="bg-noir-profond/30 rounded-lg p-3">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-ivoire-text/70 text-sm">
                                                    Modifications
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary['designs_sent'] > 0): ?>
                                                        <span class="text-xs">(dessin
                                                            #<?php echo e($summary['current_design_number']); ?>)</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </span>
                                                <span
                                                    class="text-vert-succes font-bold"><?php echo e($summary['modifications_used_current']); ?></span>
                                            </div>
                                            <div class="w-full bg-titane/30 rounded-full h-2">
                                                <div class="bg-vert-succes h-2 rounded-full transition-all"
                                                    style="width: <?php echo e($summary['modifications_per_design'] > 0 ? min(100, ($summary['modifications_used_current'] / $summary['modifications_per_design']) * 100) : 0); ?>%">
                                                </div>
                                            </div>
                                            <p class="text-ivoire-text/50 text-xs mt-1">
                                                <?php echo e($summary['modifications_per_design']); ?> par dessin —
                                                <?php echo e($summary['modifications_remaining_current']); ?> restante(s)
                                            </p>
                                        </div>

                                        <div class="bg-noir-profond/30 rounded-lg p-3">
                                            <div class="flex items-center mb-2">
                                                <svg class="w-4 h-4 mr-2 text-beige-peau" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-ivoire-text/70 text-sm">Forfait</span>
                                            </div>
                                            <p class="text-ivoire-text/50 text-xs">
                                                <?php echo e($summary['designs_included']); ?> dessin(s) complet(s),
                                                <?php echo e($summary['modifications_per_design']); ?> modif(s) chacun
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-3 pt-3 border-t border-titane/20">
                                        <p class="text-ivoire-text/50 text-xs mb-2">Raccourcis :</p>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" onclick="setNewDesignMessage()"
                                                class="px-3 py-1.5 bg-noir-profond/50 text-ivoire-text/70 rounded-lg text-xs hover:bg-noir-profond/80 hover:text-ivoire-text transition-colors border border-titane/20">
                                                🎨 Demander un nouveau dessin
                                            </button>
                                            <button type="button" onclick="setModificationMessage()"
                                                class="px-3 py-1.5 bg-noir-profond/50 text-ivoire-text/70 rounded-lg text-xs hover:bg-noir-profond/80 hover:text-ivoire-text transition-colors border border-titane/20">
                                                ✏️ Demander une modification
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </form>
                    <?php else: ?>
                        <div class="bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg p-3">
                            <p class="text-jaune-alerte text-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status === \App\Enums\BookingRequestStatus::PENDING): ?>
                                    En attente d'acceptation du projet
                                <?php elseif($bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID): ?>
                                    Chat actif (acompte payé)
                                <?php elseif($bookingRequest->client_payment_deadline && $bookingRequest->client_payment_deadline->lt(now()->subHours(24))): ?>
                                    Le délai de paiement est expiré. Contactez l'artiste pour plus d'informations.
                                <?php else: ?>
                                    Le chat est en cours d'activation
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status->value === 'accepted' && !$bookingRequest->deposit_paid_at): ?>
                <div class="mt-6 bg-titane/20 rounded-xl border border-titane/30 p-6">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>

                    <?php
                        $actionDeadline = null;
                        if ($bookingRequest->deposit_deadline) {
                            $actionDeadline = is_string($bookingRequest->deposit_deadline)
                                ? \Carbon\Carbon::parse($bookingRequest->deposit_deadline)
                                : $bookingRequest->deposit_deadline;
                        } elseif ($conversation && $conversation->deposit_deadline_at) {
                            $actionDeadline = is_string($conversation->deposit_deadline_at)
                                ? \Carbon\Carbon::parse($conversation->deposit_deadline_at)
                                : $conversation->deposit_deadline_at;
                        }
                        $actionDaysRemaining = $actionDeadline
                            ? (int) ceil(now()->diffInHours($actionDeadline) / 24)
                            : null;
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($actionDeadline): ?>
                        <div class="mb-4 p-3 bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg">
                            <p class="text-jaune-alerte text-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Délai de paiement : <?php echo e($actionDeadline->format('d/m/Y à H:i')); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($actionDaysRemaining < 0): ?>
                                    <span class="block mt-1 text-rouge-alerte font-semibold">⚠️ Délai expiré</span>
                                <?php elseif($actionDaysRemaining <= 1): ?>
                                    <span class="block mt-1 text-rouge-alerte font-semibold">⚠️ Urgent - Dernier
                                        jour</span>
                                <?php else: ?>
                                    <span class="block mt-1">(<?php echo e($actionDaysRemaining); ?> jour(s) restant(s))</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="<?php echo e(route('deposit.payment', $bookingRequest)); ?>"
                            class="flex-1 px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors text-center">
                            💳 Payer l'acompte (<?php echo e($bookingRequest->total_deposit_amount); ?>€)
                        </a>

                        <form action="<?php echo e(route('client.booking-request.cancel', $bookingRequest)); ?>" method="POST"
                            class="flex-1">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')"
                                class="w-full px-6 py-3 bg-rouge-alerte text-white rounded-lg font-semibold hover:bg-rouge-alerte/90 transition-colors">
                                ❌ Annuler la demande
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="md:hidden mt-6 space-y-4">
                <div class="bg-titane/20 rounded-xl border border-titane/30 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-titane/30 transition-colors">
                        <h3 class="text-lg font-bold text-ivoire-text">Détails du projet</h3>
                        <svg class="w-5 h-5 text-ivoire-text transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4">
                        <?php echo $__env->make('client._project-details', ['bookingRequest' => $bookingRequest], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable): ?>
                    <div class="bg-titane/20 rounded-xl border border-titane/30 overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full px-6 py-4 flex items-center justify-between hover:bg-titane/30 transition-colors">
                            <h3 class="text-lg font-bold text-ivoire-text">Informations artiste</h3>
                            <svg class="w-5 h-5 text-ivoire-text transition-transform" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="px-6 pb-4">
                            <?php echo $__env->make('client._artist-info', ['bookingRequest' => $bookingRequest], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="hidden md:grid md:grid-cols-2 md:gap-6 md:mt-6">
                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Détails du projet</h3>
                    <?php echo $__env->make('client._project-details', ['bookingRequest' => $bookingRequest], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable): ?>
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h3 class="text-lg font-bold text-ivoire-text mb-4">Informations artiste</h3>
                        <?php echo $__env->make('client._artist-info', ['bookingRequest' => $bookingRequest], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>
    </div>

    
    <?php
        $client = auth()->user()->client;
        $isMinor = $client->birth_date && $client->birth_date->age < 18;
        $tattooer = $bookingRequest->bookable;
    ?>

    <div id="consent-modal-<?php echo e($bookingRequest->id); ?>" class="hidden fixed inset-0 z-[60] overflow-y-auto bg-black/60"
        x-data="{
            step: 1,
            totalSteps: 4,
            isMinor: <?php echo e($isMinor ? 'true' : 'false'); ?>,
            showInfoSheet: false,
            medicalFlags: {
                allergies: false,
                anticoagulant: false,
                diabetes: false,
                cicatrisation: false,
                skin_disease: false,
                vih_hepatite: false,
                pregnant: false,
                roaccutane: false,
                cheloide: false
            }
        }">

        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="bg-gris-fonde w-full sm:max-w-2xl sm:rounded-2xl rounded-t-2xl max-h-[95vh] overflow-y-auto">

                
                <div
                    class="sticky top-0 bg-gris-fonde z-10 p-4 border-b border-titane/20 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-ivoire-text">📝 Consentement éclairé</h2>
                        <p class="text-xs text-titane">Étape <span x-text="step"></span> / <span
                                x-text="totalSteps"></span> — SNAT 2026</p>
                    </div>
                    <button type="button"
                        onclick="this.closest('[id^=consent-modal]').classList.add('hidden'); document.body.style.overflow='';"
                        class="p-2 text-titane hover:text-ivoire-text">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                
                <div class="w-full bg-noir-profond h-1">
                    <div class="bg-beige-peau h-1 transition-all duration-300"
                        :style="'width:' + (step / totalSteps * 100) + '%'"></div>
                </div>

                <form action="<?php echo e(route('client.consent.store', $bookingRequest)); ?>" method="POST"
                    enctype="multipart/form-data" class="p-4 space-y-4" id="consent-form-<?php echo e($bookingRequest->id); ?>"
                    novalidate
                    onsubmit="return prepareAndValidateConsent(<?php echo e($bookingRequest->id); ?>, <?php echo e($isMinor ? 'true' : 'false'); ?>)">
                    <?php echo csrf_field(); ?>

                    
                    <input type="hidden" name="act_type" value="tatouage">
                    <input type="hidden" name="body_zone" value="<?php echo e($bookingRequest->body_zone ?? 'Non précisé'); ?>">
                    <input type="hidden" name="act_description"
                        value="<?php echo e($bookingRequest->description ?? 'Non précisé'); ?>">
                    <input type="hidden" name="total_price"
                        value="<?php echo e($bookingRequest->total_price ?? ($bookingRequest->estimated_total_price ?? 0)); ?>">
                    <input type="hidden" name="deposit_amount"
                        value="<?php echo e($bookingRequest->deposit_amount ?? ($bookingRequest->total_deposit_amount ?? 0)); ?>">

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                        <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-lg p-3">
                            <p class="text-sm font-bold text-rouge-alerte mb-2">⚠️ Erreurs dans le formulaire :</p>
                            <ul class="text-xs text-rouge-alerte space-y-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>• <?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div x-show="step === 1" class="space-y-4">

                        
                        <div class="bg-noir-profond/50 rounded-lg p-3 cursor-pointer hover:bg-noir-profond/70 transition-colors"
                            @click="showInfoSheet = true">
                            <div class="flex items-center gap-2">
                                <span>📄</span>
                                <div class="flex-1">
                                    <p class="text-sm text-ivoire-text font-semibold">Fiche d'information préalable à
                                        l'acte</p>
                                    <p class="text-xs text-titane">Cliquez pour lire</p>
                                </div>
                                <svg class="w-5 h-5 text-beige-peau" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>

                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">1. Votre identité</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-titane block mb-1">Nom complet *</label>
                                <input type="text" name="client_full_name" required
                                    value="<?php echo e(trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''))); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                            </div>
                            <div>
                                <label class="text-xs text-titane block mb-1">Date de naissance *</label>
                                <input type="date" name="client_birth_date" required
                                    value="<?php echo e($client->birth_date?->format('Y-m-d')); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs text-titane block mb-1">Adresse complète *</label>
                            <input type="text" name="client_address" required value="<?php echo e($client->address ?? ''); ?>"
                                class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-titane block mb-1">Téléphone *</label>
                                <input type="tel" name="client_phone" required value="<?php echo e($client->phone ?? ''); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                            </div>
                            <div>
                                <label class="text-xs text-titane block mb-1">Email *</label>
                                <input type="email" name="client_email" required
                                    value="<?php echo e($client->user?->email ?? ($client->email ?? '')); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                            </div>
                        </div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isMinor): ?>
                            <div class="bg-ambre-warning/10 border border-ambre-warning/30 rounded-lg p-4 space-y-3">
                                <input type="hidden" name="is_minor" value="1">
                                <p class="text-sm font-bold text-ambre-warning">⚠️ Client mineur — Autorisation parentale
                                    obligatoire</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Nom du représentant légal *</label>
                                        <input type="text" name="parent_name" required
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Lien de parenté *</label>
                                        <select name="parent_relation" required
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm">
                                            <option value="">Choisir...</option>
                                            <option value="pere">Père</option>
                                            <option value="mere">Mère</option>
                                            <option value="tuteur">Tuteur légal</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">N° pièce d'identité du représentant
                                        *</label>
                                    <input type="text" name="parent_id_number" required
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">📎 Copie pièce d'identité du représentant
                                        *</label>
                                    <input type="file" name="parent_id_document" required accept="image/*,.pdf"
                                        class="w-full text-sm text-ivoire-text file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-ambre-warning/20 file:text-ambre-warning file:font-semibold file:text-xs">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Type de pièce d'identité *</label>
                                        <select name="client_id_type" required
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                            <option value="">Choisir...</option>
                                            <option value="cni">Carte nationale d'identité</option>
                                            <option value="passeport">Passeport</option>
                                            <option value="titre_sejour">Titre de séjour</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Numéro de pièce *</label>
                                        <input type="text" name="client_id_number" required
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">📎 Copie pièce d'identité du client
                                        *</label>
                                    <input type="file" name="client_id_document" required accept="image/*,.pdf"
                                        class="w-full text-sm text-ivoire-text file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-ambre-warning/20 file:text-ambre-warning file:font-semibold file:text-xs">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">✍️ Signature du représentant *</label>
                                    <canvas
                                        class="sig-canvas w-full bg-white rounded-lg border-2 border-ambre-warning/30 cursor-crosshair"
                                        data-signature="parent-<?php echo e($bookingRequest->id); ?>"
                                        style="touch-action: none; height: 100px;"></canvas>
                                    <input type="hidden" name="parent_signature_data"
                                        class="parent-sig-data-<?php echo e($bookingRequest->id); ?>">
                                    <button type="button"
                                        onclick="clearSigCanvas(document.querySelector('[data-signature=\'parent-<?php echo e($bookingRequest->id); ?>\']'))"
                                        class="text-xs text-titane hover:text-rouge-alerte mt-1">Effacer</button>
                                </div>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="is_minor" value="0">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider pt-2">2. Description de
                            l'acte</h3>
                        <div class="bg-noir-profond/50 rounded-lg p-3 space-y-1">
                            <p class="text-sm text-ivoire-text"><strong>Type :</strong>
                                Tatouage</p>
                            <p class="text-xs text-titane"><strong>Zone :</strong>
                                <?php echo e($bookingRequest->body_zone ?? 'Non précisé'); ?> · <strong>Style :</strong>
                                Tatouage</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->description): ?>
                                <p class="text-xs text-titane"><?php echo e($bookingRequest->description); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <button type="button" @click="step = 2"
                            class="w-full px-4 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg text-sm hover:bg-beige-peau/90">
                            Continuer → Questionnaire médical
                        </button>
                    </div>

                    
                    <div x-show="step === 2" class="space-y-4">
                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">3. Questionnaire médical
                        </h3>
                        <p class="text-xs text-titane">Cochez les conditions qui vous concernent :</p>

                        <?php
                            $medicalItems = [
                                'allergies' => [
                                    'label' => 'Allergies connues (métaux, latex, encres…)',
                                    'icon' => '🤧',
                                    'detail' => true,
                                ],
                                'anticoagulant' => [
                                    'label' => 'Traitement anticoagulant',
                                    'icon' => '💉',
                                    'detail' => false,
                                ],
                                'diabetes' => ['label' => 'Diabète', 'icon' => '🩸', 'detail' => false],
                                'cicatrisation' => [
                                    'label' => 'Troubles de cicatrisation',
                                    'icon' => '🩹',
                                    'detail' => false,
                                ],
                                'skin_disease' => [
                                    'label' => 'Maladie de peau (eczéma, psoriasis…)',
                                    'icon' => '🩹',
                                    'detail' => true,
                                ],
                                'vih_hepatite' => ['label' => 'VIH / Hépatite', 'icon' => '🔬', 'detail' => false],
                                'pregnant' => ['label' => 'Grossesse / allaitement', 'icon' => '🤰', 'detail' => false],
                                'roaccutane' => ['label' => 'Roaccutane (< 6 mois)', 'icon' => '💊', 'detail' => false],
                                'cheloide' => [
                                    'label' => 'Antécédents de chéloïdes',
                                    'icon' => '⚕️',
                                    'detail' => false,
                                ],
                            ];
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $medicalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-noir-profond/30 rounded-lg p-3">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="medical_<?php echo e($key); ?>" value="1"
                                        x-model="medicalFlags.<?php echo e($key); ?>"
                                        class="rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau w-5 h-5">
                                    <span class="text-sm text-ivoire-text"><?php echo e($item['icon']); ?>

                                        <?php echo e($item['label']); ?></span>
                                </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['detail']): ?>
                                    <div x-show="medicalFlags.<?php echo e($key); ?>" x-collapse class="mt-2 ml-8">
                                        <input type="text" name="medical_<?php echo e($key); ?>_detail"
                                            placeholder="Précisez..."
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm">
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="bg-noir-profond/30 rounded-lg p-3">
                            <label class="text-xs text-titane block mb-1">Autres pathologies importantes</label>
                            <textarea name="medical_other" rows="2" placeholder="Précisez toute autre condition médicale..."
                                class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm resize-none"></textarea>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" @click="step = 1"
                                class="flex-1 px-4 py-3 bg-noir-profond text-titane font-semibold rounded-lg text-sm">←
                                Retour</button>
                            <button type="button" @click="step = 3"
                                class="flex-1 px-4 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg text-sm">Continuer
                                →</button>
                        </div>
                    </div>

                    
                    <div x-show="step === 3" class="space-y-4">
                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">4. Clause financière
                        </h3>

                        <div class="bg-noir-profond/50 rounded-lg p-3 space-y-2 text-sm text-ivoire-text">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->price_estimate_max ?? $bookingRequest->estimated_total_price): ?>
                                <p>💰 <strong>Prix estimé :</strong>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                        ($bookingRequest->price_estimate_min ?? null) &&
                                            $bookingRequest->price_estimate_min != ($bookingRequest->price_estimate_max ?? null)): ?>
                                        <?php echo e(number_format($bookingRequest->price_estimate_min, 0)); ?>€ -
                                        <?php echo e(number_format($bookingRequest->price_estimate_max, 0)); ?>€
                                    <?php else: ?>
                                        <?php echo e(number_format($bookingRequest->price_estimate_max ?? $bookingRequest->estimated_total_price, 0)); ?>€
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->total_deposit_amount): ?>
                                <p>🔒 <strong>Acompte versé :</strong>
                                    <?php echo e(number_format($bookingRequest->total_deposit_amount, 0)); ?>€
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->deposit_paid_at): ?>
                                        <span class="text-vert-succes">(payé)</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <label class="flex items-start gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">

                            <!-- Valeur réellement envoyée -->
                            <input type="hidden" name="retouche_included" value="1">

                            <!-- Case visible -->
                            <input type="checkbox" checked disabled
                                class="rounded border-titane/30 bg-noir-profond text-beige-peau w-5 h-5 mt-0.5">

                            <span class="text-sm text-ivoire-text">
                                🔄 Retouche incluse dans le prix (à demander dans les 3 semaines après le rendez-vous)
                            </span>
                        </label>

                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider pt-2">5. Autorisation
                            image</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">
                                <input type="radio" name="image_authorization" value="1"
                                    class="border-titane/30 bg-noir-profond text-beige-peau">
                                <span class="text-sm text-ivoire-text">📸 J'autorise l'utilisation de photos à des fins
                                    promotionnelles</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">
                                <input type="radio" name="image_authorization" value="0"
                                    class="border-titane/30 bg-noir-profond text-beige-peau">
                                <span class="text-sm text-ivoire-text">🚫 Je refuse toute utilisation</span>
                            </label>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" @click="step = 2"
                                class="flex-1 px-4 py-3 bg-noir-profond text-titane font-semibold rounded-lg text-sm">←
                                Retour</button>
                            <button type="button" @click="step = 4"
                                class="flex-1 px-4 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg text-sm">Continuer
                                →</button>
                        </div>
                    </div>

                    
                    <div x-show="step === 4" class="space-y-4">
                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">6. Confirmations
                            obligatoires</h3>

                        <div class="space-y-2">
                            <label class="flex items-start gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">
                                <input type="checkbox" name="confirm_medical_sincere" value="1" required
                                    class="rounded border-titane/30 bg-noir-profond text-beige-peau w-5 h-5 mt-0.5">
                                <span class="text-sm text-ivoire-text">Je certifie avoir répondu de manière sincère et
                                    complète au questionnaire médical.</span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">
                                <input type="checkbox" name="confirm_risks_informed" value="1" required
                                    class="rounded border-titane/30 bg-noir-profond text-beige-peau w-5 h-5 mt-0.5">
                                <span class="text-sm text-ivoire-text">Je reconnais avoir reçu toutes les informations
                                    concernant la nature de l'acte, ses risques, ses suites normales et ses complications
                                    éventuelles.</span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">
                                <input type="checkbox" name="confirm_info_sheet_read" value="1" required
                                    class="rounded border-titane/30 bg-noir-profond text-beige-peau w-5 h-5 mt-0.5">
                                <span class="text-sm text-ivoire-text">J'ai lu et compris la <span
                                        class="text-beige-peau underline cursor-pointer"
                                        @click.stop="showInfoSheet = true">fiche d'information préalable</span>.</span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">
                                <input type="checkbox" name="confirm_aftercare_received" value="1" required
                                    class="rounded border-titane/30 bg-noir-profond text-beige-peau w-5 h-5 mt-0.5">
                                <span class="text-sm text-ivoire-text">Une fiche de soins post-acte m'a été remise et
                                    expliquée.</span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">
                                <input type="checkbox" name="confirm_not_intoxicated" value="1" required
                                    class="rounded border-titane/30 bg-noir-profond text-beige-peau w-5 h-5 mt-0.5">
                                <span class="text-sm text-ivoire-text">Je confirme ne pas être sous l'emprise d'alcool ou
                                    de stupéfiants.</span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">
                                <input type="checkbox" name="confirm_over_18_or_authorized" value="1" required
                                    class="rounded border-titane/30 bg-noir-profond text-beige-peau w-5 h-5 mt-0.5">
                                <span class="text-sm text-ivoire-text">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isMinor): ?>
                                        Je confirme disposer d'une autorisation parentale valide.
                                    <?php else: ?>
                                        Je confirme avoir plus de 18 ans.
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer bg-noir-profond/30 rounded-lg p-3">
                                <input type="checkbox" name="confirm_rgpd" value="1" required
                                    class="rounded border-titane/30 bg-noir-profond text-beige-peau w-5 h-5 mt-0.5">
                                <span class="text-sm text-ivoire-text">J'accepte la conservation de mes données
                                    conformément au RGPD.</span>
                            </label>
                        </div>

                        
                        <div class="bg-noir-profond/50 rounded-lg p-3">
                            <p class="text-xs text-ivoire-text/80 italic">
                                « Je déclare accepter l'acte en pleine connaissance de cause et ne pourrai engager la
                                responsabilité du professionnel en cas de complication liée au non-respect des soins
                                post-acte ou à une omission dans mes déclarations médicales. »
                            </p>
                        </div>

                        
                        <div>
                            <label class="text-xs text-titane block mb-1">✏️ Écrivez : « Lu et approuvé, bon pour
                                consentement » *</label>
                            <input type="text" name="handwritten_mention" required
                                placeholder="Lu et approuvé, bon pour consentement"
                                class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                        </div>

                        
                        <div>
                            <label class="text-xs text-titane block mb-2">✍️ Votre signature *</label>
                            <canvas
                                class="sig-canvas w-full bg-white rounded-lg border-2 border-beige-peau/30 cursor-crosshair"
                                data-signature="client-<?php echo e($bookingRequest->id); ?>"
                                style="touch-action: none; height: 120px;"></canvas>
                            <input type="hidden" name="signature_data"
                                class="client-sig-data-<?php echo e($bookingRequest->id); ?>">
                            <button type="button"
                                onclick="clearSigCanvas(document.querySelector('[data-signature=\'client-<?php echo e($bookingRequest->id); ?>\']'))"
                                class="text-xs text-titane hover:text-rouge-alerte mt-1">Effacer la signature</button>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" @click="step = 3"
                                class="flex-1 px-4 py-3 bg-noir-profond text-titane font-semibold rounded-lg text-sm">←
                                Retour</button>
                            <button type="submit"
                                class="flex-1 px-4 py-3 bg-vert-succes text-white font-bold rounded-lg text-sm hover:bg-vert-succes/90">
                                ✅ Signer le consentement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        
        <div x-show="showInfoSheet" x-cloak x-transition class="fixed inset-0 z-[70] overflow-y-auto bg-black/90"
            @keydown.escape.window="showInfoSheet = false">

            <div class="flex items-end sm:items-center justify-center min-h-screen p-0 sm:p-4">
                <div class="bg-gris-fonde w-full sm:max-w-2xl sm:rounded-2xl rounded-t-2xl max-h-[95vh] overflow-y-auto">

                    <div
                        class="sticky top-0 bg-gris-fonde z-10 p-4 border-b border-titane/20 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-ivoire-text">📄 Fiche d'information préalable</h2>
                        <button type="button" @click="showInfoSheet = false"
                            class="p-2 text-titane hover:text-ivoire-text">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-4 space-y-4 text-sm text-ivoire-text/80">

                        
                        <div class="bg-noir-profond/50 rounded-lg p-3">
                            <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">1. Identification du
                                professionnel</p>
                            <p class="text-ivoire-text">
                                <?php echo e($tattooer?->studio_name ?? ($tattooer?->user?->name ?? 'Professionnel')); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer?->user?->name && $tattooer?->studio_name): ?>
                                <p class="text-xs text-titane">Praticien : <?php echo e($tattooer->user->name); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer?->address): ?>
                                <p class="text-xs text-titane"><?php echo e($tattooer->address); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer?->siret): ?>
                                <p class="text-xs text-titane">SIRET : <?php echo e($tattooer->siret); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer?->ars_number): ?>
                                <p class="text-xs text-titane">ARS : <?php echo e($tattooer->ars_number); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer?->insurance_number): ?>
                                <p class="text-xs text-titane">RC Pro : <?php echo e($tattooer->insurance_number); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <h4 class="font-bold text-ivoire-text">2. Objet du document</h4>
                        <p>La présente fiche a pour objet de délivrer une information claire, loyale et appropriée sur la
                            nature de l'acte envisagé, ses bénéfices attendus, ses contraintes, ses risques et ses
                            complications possibles.</p>
                        <p>Elle est remise avant toute réalisation de l'acte, afin de permettre une décision libre et
                            éclairée.</p>

                        <h4 class="font-bold text-ivoire-text">3. Nature de l'acte</h4>
                        <p><strong>Tatouage :</strong> Injection de pigments dans le derme au moyen d'aiguilles stériles à
                            usage unique, entraînant une modification permanente de la peau.</p>
                        <p><strong>Piercing :</strong> Perforation cutanée ou muqueuse afin d'y insérer un bijou.</p>
                        <p><strong>Body modification :</strong> Acte modifiant l'intégrité corporelle (scarification,
                            surface piercing, etc.).</p>

                        <h4 class="font-bold text-ivoire-text">4. Caractère volontaire et esthétique</h4>
                        <p>L'acte est réalisé à visée esthétique et n'a aucune finalité thérapeutique. Le résultat dépend de
                            la morphologie, de la qualité de cicatrisation, du respect des soins post-acte, et de facteurs
                            biologiques individuels. <strong>Aucun résultat esthétique précis ne peut être garanti.</strong>
                        </p>

                        <h4 class="font-bold text-ivoire-text">5. Risques généraux connus</h4>
                        <p>Même lorsque l'acte est pratiqué dans le respect strict des règles d'hygiène : douleur,
                            saignement, œdème, rougeur, infection locale, réaction allergique, inflammation prolongée,
                            retard de cicatrisation, cicatrice hypertrophique ou chéloïde, rejet ou migration (piercing),
                            résultat esthétique différent des attentes subjectives.</p>
                        <p>Dans de rares cas : infection systémique, complications nécessitant traitement médical, séquelles
                            cicatricielles permanentes.</p>
                        <p class="text-ambre-warning font-semibold">Toute complication doit entraîner une consultation
                            médicale immédiate.</p>

                        <h4 class="font-bold text-ivoire-text">6. Contre-indications médicales</h4>
                        <p>L'acte est déconseillé ou contre-indiqué notamment en cas de : grossesse/allaitement, diabète non
                            stabilisé, troubles de la coagulation, traitement anticoagulant, immunodépression, dermatose
                            active, prise d'isotrétinoïne récente, antécédents de chéloïdes.</p>
                        <p>En cas de doute médical, un avis médical préalable est recommandé.</p>

                        <h4 class="font-bold text-ivoire-text">7. Conditions d'hygiène</h4>
                        <p>Le professionnel déclare utiliser du matériel stérile à usage unique, respecter les protocoles
                            d'asepsie, porter des équipements de protection, appliquer les règles de gestion des déchets
                            DASRI, et utiliser des encres conformes à la réglementation européenne REACH.</p>

                        <h4 class="font-bold text-ivoire-text">8. Suites normales</h4>
                        <p>Sensibilité locale, rougeur temporaire, suintement léger, formation de croûtes fines (tatouage),
                            démangeaisons. Durée de cicatrisation variable selon la zone et l'individu.</p>

                        <h4 class="font-bold text-ivoire-text">9. Soins post-acte</h4>
                        <p>Le respect strict des soins post-acte conditionne le résultat final. Le non-respect augmente
                            significativement le risque d'infection, peut altérer le résultat esthétique, et peut engager la
                            responsabilité exclusive du client. Une fiche de soins détaillée est remise séparément.</p>

                        <h4 class="font-bold text-ivoire-text">10. Caractère permanent (Tatouage)</h4>
                        <p>Le tatouage est un acte permanent. Le détatouage est long, coûteux, peut nécessiter plusieurs
                            séances, peut laisser des cicatrices, et n'assure pas un effacement total.</p>

                        <h4 class="font-bold text-ivoire-text">11. Liberté de décision</h4>
                        <p>Le client dispose d'un délai de réflexion libre, du droit de poser toutes questions, et du droit
                            de renoncer à l'acte. Aucune pression commerciale n'est exercée.</p>

                        <h4 class="font-bold text-ivoire-text">12. Traçabilité</h4>
                        <p>Les références des encres / bijoux utilisés sont enregistrées et conservées conformément aux
                            obligations légales.</p>

                        <div class="bg-noir-profond/50 rounded-lg p-3 mt-4">
                            <p class="text-xs text-titane">
                                ⚖️ Références : ARS (déclaration d'activité obligatoire), Code de la santé publique (art.
                                R1311-1 à R1311-11), Normes européennes REACH 2022.
                            </p>
                        </div>

                        <button type="button" @click="showInfoSheet = false"
                            class="w-full px-4 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg text-sm hover:bg-beige-peau/90 mt-4">
                            ✅ J'ai lu cette fiche d'information
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        // ═══ SCROLL AUTO ═══
        document.addEventListener('DOMContentLoaded', function() {
            var container = document.getElementById('messages-container');
            if (container) container.scrollTop = container.scrollHeight;

            // Init signatures
            initAllSignatureCanvases();

            // Réouvrir la modal si des erreurs de validation existent
            <?php if($errors->any()): ?>
                var modal = document.querySelector('[id^="consent-modal-"]');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            <?php endif; ?>
        });

        // ═══ PREVIEW FICHIERS ═══
        document.getElementById('attachments')?.addEventListener('change', function(e) {
            var preview = document.getElementById('filePreview');
            var container = document.getElementById('previewContainer');
            if (!preview || !container) return;
            container.innerHTML = '';
            if (e.target.files.length === 0) {
                preview.classList.add('hidden');
                return;
            }
            preview.classList.remove('hidden');

            Array.from(e.target.files).forEach(function(file, index) {
                var div = document.createElement('div');
                div.className = 'flex items-center justify-between p-2 bg-noir-profond/50 rounded text-sm';

                if (file.type.startsWith('image/')) {
                    var img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'w-12 h-12 object-cover rounded mr-3';
                    div.appendChild(img);
                } else {
                    var icon = document.createElement('div');
                    icon.className = 'w-12 h-12 bg-titane/30 rounded mr-3 flex items-center justify-center';
                    icon.innerHTML =
                        '<svg class="w-6 h-6 text-ivoire-text/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                    div.appendChild(icon);
                }

                var info = document.createElement('div');
                info.className = 'flex-1';
                info.innerHTML = '<div class="text-ivoire-text font-medium">' + file.name +
                    '</div><div class="text-ivoire-text/50 text-xs">' + (file.size / 1024).toFixed(1) +
                    ' KB</div>';
                div.appendChild(info);

                var removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'text-rouge-alerte hover:text-rouge-alerte/80 ml-2';
                removeBtn.innerHTML =
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                removeBtn.onclick = function() {
                    removeFile(index);
                };
                div.appendChild(removeBtn);

                container.appendChild(div);
            });
        });

        function removeFile(index) {
            var input = document.getElementById('attachments');
            var dt = new DataTransfer();
            var files = Array.from(input.files);
            files.splice(index, 1);
            files.forEach(function(file) {
                dt.items.add(file);
            });
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        }

        // ═══ RACCOURCIS MESSAGE ═══
        function setModificationMessage() {
            var textarea = document.querySelector('textarea[name="content"]');
            if (textarea) {
                textarea.value = 'Pourriez-vous apporter les modifications suivantes :';
                textarea.focus();
            }
        }

        function setNewDesignMessage() {
            var textarea = document.querySelector('textarea[name="content"]');
            if (textarea) {
                textarea.value = 'Pourriez-vous me proposer un nouveau dessin pour :';
                textarea.focus();
            }
        }

        // ═══ CANVAS SIGNATURE ═══
        function initAllSignatureCanvases() {
            document.querySelectorAll('.sig-canvas').forEach(function(canvas) {
                if (canvas.dataset.initialized) return;
                var ctx = canvas.getContext('2d');
                var drawing = false;

                var rect = canvas.getBoundingClientRect();
                if (rect.width > 0 && rect.height > 0) {
                    canvas.width = rect.width;
                    canvas.height = rect.height;
                }

                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';

                function getPos(e) {
                    var r = canvas.getBoundingClientRect();
                    var touch = e.touches ? e.touches[0] : e;
                    return {
                        x: touch.clientX - r.left,
                        y: touch.clientY - r.top
                    };
                }

                function startDraw(e) {
                    e.preventDefault();
                    drawing = true;
                    var pos = getPos(e);
                    ctx.beginPath();
                    ctx.moveTo(pos.x, pos.y);
                }

                function draw(e) {
                    if (!drawing) return;
                    e.preventDefault();
                    var pos = getPos(e);
                    ctx.lineTo(pos.x, pos.y);
                    ctx.stroke();
                }

                function stopDraw() {
                    drawing = false;
                }

                canvas.addEventListener('mousedown', startDraw);
                canvas.addEventListener('mousemove', draw);
                canvas.addEventListener('mouseup', stopDraw);
                canvas.addEventListener('mouseleave', stopDraw);
                canvas.addEventListener('touchstart', startDraw, {
                    passive: false
                });
                canvas.addEventListener('touchmove', draw, {
                    passive: false
                });
                canvas.addEventListener('touchend', stopDraw);
                canvas.dataset.initialized = 'true';
            });
        }

        function clearSigCanvas(canvas) {
            if (!canvas) return;
            var ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function prepareAndValidateConsent(brId, isMinor) {
            // Capturer signature client
            var clientCanvas = document.querySelector('[data-signature="client-' + brId + '"]');
            var clientInput = document.querySelector('.client-sig-data-' + brId);
            if (clientCanvas && clientInput) {
                clientInput.value = clientCanvas.toDataURL('image/png');

                // Vérifier que la signature n'est pas vide
                var blank = document.createElement('canvas');
                blank.width = clientCanvas.width;
                blank.height = clientCanvas.height;
                if (clientCanvas.toDataURL() === blank.toDataURL()) {
                    alert('Veuillez signer le consentement avant de valider.');
                    return false;
                }
            }

            // Capturer signature parent si mineur
            if (isMinor) {
                var parentCanvas = document.querySelector('[data-signature="parent-' + brId + '"]');
                var parentInput = document.querySelector('.parent-sig-data-' + brId);
                if (parentCanvas && parentInput) {
                    parentInput.value = parentCanvas.toDataURL('image/png');
                }
            }

            // Vérifier mention manuscrite
            var form = document.getElementById('consent-form-' + brId);
            var mention = form ? form.querySelector('[name="handwritten_mention"]') : null;
            if (mention && !mention.value.trim()) {
                alert('Veuillez écrire la mention « Lu et approuvé, bon pour consentement ».');
                return false;
            }

            // Vérifier les 7 checkboxes obligatoires
            var requiredChecks = [
                'confirm_medical_sincere', 'confirm_risks_informed', 'confirm_info_sheet_read',
                'confirm_aftercare_received', 'confirm_not_intoxicated', 'confirm_over_18_or_authorized', 'confirm_rgpd'
            ];
            for (var i = 0; i < requiredChecks.length; i++) {
                var cb = form ? form.querySelector('[name="' + requiredChecks[i] + '"]') : null;
                if (cb && !cb.checked) {
                    alert('Veuillez cocher toutes les confirmations obligatoires (étape 4).');
                    return false;
                }
            }

            return true; // OK — laisser le form se soumettre
        }

        // Réinit canvas quand modal s'ouvre
        document.querySelectorAll('[id^="consent-modal-"]').forEach(function(modal) {
            new MutationObserver(function(mutations) {
                mutations.forEach(function(m) {
                    if (m.type === 'attributes' && m.attributeName === 'class' && !m.target
                        .classList.contains('hidden')) {
                        setTimeout(initAllSignatureCanvases, 150);
                    }
                });
            }).observe(modal, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/client/chat.blade.php ENDPATH**/ ?>