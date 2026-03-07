<?php $__env->startSection('title', 'Chat - ' . $bookingRequest->client->full_name); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête avec alertes d'expiration -->
            <div class="mb-6">
                <a href="<?php echo e(route($tattooer->routePrefix() . '.requests')); ?>"
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
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status->value === 'deposit_requested'): ?>
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
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-ivoire-text">Chat avec
                            <?php echo e($bookingRequest->client->user->pseudo ?? ($bookingRequest->client->user->first_name . ' ' . $bookingRequest->client->user->last_name ?? $bookingRequest->client->full_name)); ?>

                        </h1>
                        <p class="text-ivoire-text/70 mt-1">Projet: <?php echo e($bookingRequest->tattoo_description); ?></p>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status->value === 'accepted' && !$bookingRequest->deposit_paid_at): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->deposit_deadline): ?>
                            <?php
                                $deadline = is_string($bookingRequest->deposit_deadline)
                                    ? \Carbon\Carbon::parse($bookingRequest->deposit_deadline)
                                    : $bookingRequest->deposit_deadline;
                                $daysRemaining = (int) ceil(now()->diffInHours($deadline) / 24);
                            ?>
                            <div class="px-4 py-2 bg-orange-attention/10 border border-orange-attention/30 rounded-lg">
                                <p class="text-orange-attention text-sm">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Délai : <span
                                        class="text-ivoire-text"><?php echo e($daysRemaining > 0 ? $daysRemaining . ' jour(s) restant(s)' : 'Dernier jour'); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daysRemaining < 0): ?>
                                        <span class="block mt-1 text-rouge-alerte font-semibold">⚠️ Délai expiré</span>
                                    <?php elseif($daysRemaining <= 1): ?>
                                        <span class="block mt-1 text-rouge-alerte font-semibold">⚠️ Urgent</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            </div>
                        <?php elseif($bookingRequest->conversation && $bookingRequest->conversation->deposit_deadline_at): ?>
                            <?php
                                $deadline = is_string($bookingRequest->conversation->deposit_deadline_at)
                                    ? \Carbon\Carbon::parse($bookingRequest->conversation->deposit_deadline_at)
                                    : $bookingRequest->conversation->deposit_deadline_at;
                                $daysRemaining = (int) ceil(now()->diffInHours($deadline) / 24);
                            ?>
                            <div class="px-4 py-2 bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg">
                                <p class="text-jaune-alerte text-sm">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Délai : <span
                                        class="text-ivoire-text"><?php echo e($daysRemaining > 0 ? $daysRemaining . ' jour(s) restant(s)' : 'Dernier jour'); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daysRemaining < 0): ?>
                                        <span class="block mt-1 text-rouge-alerte font-semibold">⚠️ Délai expiré</span>
                                    <?php elseif($daysRemaining <= 1): ?>
                                        <span class="block mt-1 text-rouge-alerte font-semibold">⚠️ Urgent</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Gestion des dessins et modifications -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                    $bookingRequest->deposit_paid_at &&
                        (!$tattooer->isPiercer() || ($bookingRequest->included_design_versions ?? 0) > 0)): ?>
                    <div class="mt-4 bg-titane/20 rounded-xl p-4 border border-titane/30">
                        <h3 class="text-lg font-semibold text-ivoire-text mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-beige-peau" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Gestion des dessins
                        </h3>

                        <!-- Délais avant fermeture du chat -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$bookingRequest->deposit_paid_at && isset($expiryInfo) && $expiryInfo['time_remaining']): ?>
                            <div class="mb-4 bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-jaune-alerte" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-jaune-alerte font-medium">
                                            Temps restant : <?php echo e($expiryInfo['time_remaining']); ?>

                                        </span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiryInfo['expiry_type'] === 'deposit_pending'): ?>
                                        <a href="<?php echo e(route('booking-request.deposit.request', $bookingRequest)); ?>"
                                            class="px-3 py-1 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">
                                            Payer l'acompte
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($expiryInfo['warning_message'])): ?>
                                    <p class="text-jaune-alerte/80 text-sm mt-2"><?php echo e($expiryInfo['warning_message']); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php
                            $summary = $bookingRequest->designTrackingSummary();
                        ?>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Dessins complets -->
                            <div class="bg-noir-profond/30 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-ivoire-text/70 text-sm">Dessins utilisés</span>
                                    <span class="text-beige-peau font-bold"><?php echo e($summary['designs_sent']); ?></span>
                                </div>
                                <div class="w-full bg-titane/30 rounded-full h-2">
                                    <div class="bg-beige-peau h-2 rounded-full transition-all"
                                        style="width: <?php echo e($summary['designs_included'] > 0 ? min(100, ($summary['designs_sent'] / $summary['designs_included']) * 100) : 0); ?>%">
                                    </div>
                                </div>
                                <p class="text-ivoire-text/50 text-xs mt-1">
                                    <?php echo e($summary['designs_included']); ?> inclus — <?php echo e($summary['designs_remaining']); ?>

                                    restant(s)
                                </p>
                            </div>

                            <!-- Modifications du dessin en cours -->
                            <div class="bg-noir-profond/30 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-ivoire-text/70 text-sm">
                                        Modifications
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary['designs_sent'] > 0): ?>
                                            <span class="text-xs">(dessin #<?php echo e($summary['current_design_number']); ?>)</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                    <span
                                        class="text-beige-peau font-bold"><?php echo e($summary['modifications_used_current']); ?></span>
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

                            <!-- Résumé forfait -->
                            <div class="bg-noir-profond/30 rounded-lg p-3">
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 mr-2 text-beige-peau" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-ivoire-text/70 text-sm">Forfait</span>
                                </div>
                                <p class="text-ivoire-text/50 text-xs">
                                    <?php echo e($summary['designs_included']); ?> dessin(s) complet(s),
                                    <?php echo e($summary['modifications_per_design']); ?> modif(s) chacun
                                </p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$summary['can_send_new_design'] && !$summary['can_send_modification']): ?>
                                    <p class="text-ambre-warning text-xs mt-1">⚠️ Forfait épuisé</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>


                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
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
                <div id="messages-container" class="h-96 overflow-y-auto p-4 sm:p-6 space-y-4">

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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                    ($bookingRequest->status->value === 'accepted' || $bookingRequest->status->value === 'deposit_paid') &&
                                        $bookingRequest->conversation &&
                                        $bookingRequest->conversation->status->value === 'active'): ?>
                                    Chat ouvert
                                <?php else: ?>
                                    Chat fermé
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </h3>
                            <p class="text-ivoire-text/70 text-sm">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status->value !== 'accepted'): ?>
                                    Le chat sera disponible lorsque le projet sera accepté
                                <?php elseif(!$bookingRequest->conversation): ?>
                                    La conversation n'a pas été créée
                                <?php elseif($bookingRequest->conversation->status->value !== 'active'): ?>
                                    La conversation est fermée
                                <?php else: ?>
                                    Le chat est ouvert pour discuter avec le client
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="text-xs text-ivoire-text/50 mb-2">
                            Affichage de <?php echo e($messages->count()); ?> message(s)
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->sender_type === 'system'): ?>
                                
                                <div class="flex justify-center mb-4">
                                    <div class="max-w-sm">
                                        <div
                                            class="bg-titane/20 border border-titane/30 text-ivoire-text/80 rounded-lg px-4 py-2 text-center">
                                            <p class="text-sm whitespace-pre-wrap">
                                                <?php echo e(preg_replace('/\[CONSENT_FORM:\d+\]/i', '', $message->content)); ?></p>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_contains($message->content, 'choisi la date')): ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                                    (auth()->user()->isTattooer() || auth()->user()->isPiercer()) &&
                                                        $bookingRequest->confirmed_date &&
                                                        !$bookingRequest->appointment_datetime): ?>
                                                    <?php
                                                        $routePrefix = auth()->user()->isTattooer()
                                                            ? 'tattooer'
                                                            : 'pierceur';
                                                    ?>
                                                    <a href="<?php echo e(route($routePrefix . '.calendar')); ?>?book=<?php echo e($bookingRequest->id); ?>&date=<?php echo e(\Carbon\Carbon::parse($bookingRequest->confirmed_date)->format('Y-m-d')); ?>&period=<?php echo e($bookingRequest->confirmed_period ?? 'morning'); ?>"
                                                        class="inline-flex items-center gap-2 mt-3 px-4 py-2.5 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition">
                                                        📅 Fixer le rendez-vous →
                                                    </a>
                                                <?php elseif($bookingRequest->appointment_datetime): ?>
                                                    <p class="text-xs text-vert-succes mt-2">
                                                        ✅ RDV fixé :
                                                        <?php echo e(\Carbon\Carbon::parse($bookingRequest->appointment_datetime)->translatedFormat('d/m/Y')); ?>

                                                        de <?php echo e($bookingRequest->scheduled_start_time); ?> à
                                                        <?php echo e($bookingRequest->scheduled_end_time); ?>

                                                    </p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            
                                            <?php
                                                $hasRefusalMessage =
                                                    str_contains($message->content ?? '', 'aucune des dates') ||
                                                    str_contains($message->content ?? '', 'alternatives') ||
                                                    str_contains(
                                                        $message->content ?? '',
                                                        'Le client ne peut à aucune des dates proposées',
                                                    ) ||
                                                    str_contains(
                                                        $message->content ?? '',
                                                        '⚠️ Le client ne peut à aucune des dates proposées',
                                                    );
                                            ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasRefusalMessage): ?>
                                                <?php
                                                    $canRepropose =
                                                        $bookingRequest &&
                                                        !in_array($bookingRequest->status->value ?? '', [
                                                            'cancelled',
                                                            'completed',
                                                            'expired',
                                                            'rejected',
                                                        ]);
                                                ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canRepropose): ?>
                                                    <div class="mt-3">
                                                        <button onclick="Livewire.dispatch('openModal')"
                                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl text-sm font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            📅 Proposer de nouvelles dates
                                                        </button>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <p class="text-xs text-ivoire-text/40 mt-1 text-center">
                                            <?php echo e($message->created_at->format('H:i')); ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                
                                <div
                                    class="flex <?php echo e($message->sender_type === 'client' ? 'justify-start' : 'justify-end'); ?> mb-4">
                                    <div class="max-w-xs sm:max-w-md lg:max-w-md">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->sender_type === 'client'): ?>
                                            <div class="flex items-start gap-3 mb-3">
                                                <img src="<?php echo e($bookingRequest->client->user->getFirstMediaUrl('avatar')); ?>"
                                                    alt="Avatar de <?php echo e($bookingRequest->client->full_name); ?>"
                                                    class="w-10 h-10 rounded-full object-cover border-2 border-titane/30">
                                                <div class="flex-1">
                                                    <div class="font-semibold text-ivoire-text">
                                                        <?php echo e($bookingRequest->client->pseudo); ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div
                                            class="<?php echo e($message->sender_type === 'client' ? 'bg-noir-profond text-ivoire-text' : 'bg-beige-peau text-noir-profond'); ?> rounded-lg px-3 py-2 sm:px-4">
                                            <p class="text-sm whitespace-pre-wrap break-words">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty(trim($message->content))): ?>
                                                    <?php echo e(preg_replace('/\[CONSENT_FORM:\d+\]/i', '', $message->content)); ?>

                                                <?php elseif($message->getMedia('attachments')->isNotEmpty()): ?>
                                                    <span class="text-ivoire-text/60 italic">Dessin envoyé</span>
                                                <?php else: ?>
                                                    <span class="text-ivoire-text/60 italic">Message vide</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </p>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->getMedia('attachments')->isNotEmpty()): ?>
                                                <div class="mt-2 space-y-1">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $message->getMedia('attachments'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_starts_with($media->mime_type, 'image/')): ?>
                                                            <img src="<?php echo e($media->getUrl()); ?>" alt="Pièce jointe"
                                                                class="rounded max-w-full h-auto cursor-pointer hover:opacity-90"
                                                                onclick="window.open('<?php echo e($media->getUrl()); ?>', '_blank')">
                                                        <?php else: ?>
                                                            <a href="<?php echo e($media->getUrl()); ?>" target="_blank"
                                                                class="block text-xs underline break-all">
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
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Zone de saisie -->
                <div class="border-t border-titane/30 p-3 sm:p-4">
                    <div class="bg-titane/20 rounded-xl p-4 sm:p-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($bookingRequest->status->value, ['accepted', 'deposit_paid', 'date_confirmed']) &&
                                $bookingRequest->conversation &&
                                $bookingRequest->conversation->status->value === 'active'): ?>
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


                            <form action="<?php echo e(route($tattooer->routePrefix() . '.message.send', $bookingRequest)); ?>"
                                method="POST" enctype="multipart/form-data" id="messageForm" x-data="messageForm()"
                                @submit="handleSubmit($event)">
                                <?php echo csrf_field(); ?>

                                
                                <input type="hidden" name="design_type" x-model="designType">
                                <input type="hidden" name="coverage_type" x-model="coverageType">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->deposit_paid_at): ?>
                                    
                                    <div x-show="files.length > 0" x-cloak class="mb-3 flex flex-wrap gap-2 px-2">
                                        <template x-for="(file, index) in files" :key="index">
                                            <div
                                                class="relative bg-noir-profond/50 rounded-lg px-3 py-1.5 flex items-center gap-2 text-sm text-ivoire-text/80">
                                                <span x-text="file.name" class="max-w-[150px] truncate"></span>
                                                <button type="button" @click="removeFile(index)"
                                                    class="text-rouge-alerte hover:text-rouge-alerte/80 text-lg leading-none">&times;</button>
                                            </div>
                                        </template>
                                    </div>

                                    
                                    <div x-show="files.length > 0 && !designType" x-cloak class="mb-3 px-2">
                                        <p class="text-ivoire-text/70 text-sm mb-2">Quel type d'envoi ?</p>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" @click="selectDesignType('new_design')"
                                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all border"
                                                :class="canSendDesign
                                                    ?
                                                    'border-vert-succes/50 text-vert-succes hover:bg-vert-succes/10' :
                                                    'border-ambre-warning/50 text-ambre-warning hover:bg-ambre-warning/10'">
                                                🎨 Nouveau dessin
                                                <span x-text="canSendDesign ? '(inclus)' : '(hors forfait)'"
                                                    class="text-xs ml-1"></span>
                                            </button>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->designs_sent_count > 0): ?>
                                                <button type="button" @click="selectDesignType('modification')"
                                                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all border"
                                                    :class="canSendModif
                                                        ?
                                                        'border-beige-peau/50 text-beige-peau hover:bg-beige-peau/10' :
                                                        'border-ambre-warning/50 text-ambre-warning hover:bg-ambre-warning/10'">
                                                    ✏️ Modification
                                                    <span x-text="canSendModif ? '(inclus)' : '(hors forfait)'"
                                                        class="text-xs ml-1"></span>
                                                </button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>

                                    
                                    <div x-show="designType && files.length > 0 && coverageType" x-cloak
                                        class="mb-3 px-2">
                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="text-vert-succes" x-show="coverageType === 'included'">✅</span>
                                            <span class="text-ambre-warning"
                                                x-show="coverageType !== 'included' && coverageType !== ''">⚠️</span>
                                            <span class="text-ivoire-text" x-text="designTypeLabel"></span>
                                            <button type="button" @click="resetDesignType()"
                                                class="text-titane hover:text-ivoire-text text-xs underline ml-2">
                                                Changer
                                            </button>
                                        </div>
                                    </div>

                                    
                                    <div x-show="showOverage" x-cloak
                                        class="mb-3 px-2 bg-ambre-warning/5 border border-ambre-warning/20 rounded-lg p-3">
                                        <p class="text-ambre-warning text-sm mb-2">⚠️ Limite du forfait atteinte pour ce
                                            type d'envoi</p>
                                        <p class="text-ivoire-text/60 text-xs mb-3">Le dessin sera envoyé gratuitement
                                            (hors forfait).</p>
                                        <button type="button" @click="setOverage('send_free')"
                                            class="px-4 py-2 border border-titane/30 rounded-lg text-sm text-ivoire-text hover:bg-gris-fonde transition-colors">
                                            📤 Confirmer et envoyer
                                        </button>
                                    </div>

                                    
                                    <div class="flex flex-col sm:flex-row items-end gap-2">
                                        <input type="file" name="attachments[]" id="attachments" multiple
                                            accept="image/*,application/pdf" class="hidden"
                                            @change="handleFileSelect($event)">

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                            (auth()->user()->tattooer && auth()->user()->tattooer->isPro()) ||
                                                (auth()->user()->piercer && auth()->user()->piercer->isPro())): ?>
                                            <button type="button"
                                                onclick="document.getElementById('attachments').click()"
                                                class="px-4 py-3 bg-noir-profond text-ivoire-text rounded-lg hover:bg-noir-profond/80 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                            </button>
                                        <?php else: ?>
                                            <div class="relative group">
                                                <button type="button" disabled
                                                    class="px-4 py-3 bg-noir-profond/50 text-titane/40 rounded-lg cursor-not-allowed transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                </button>
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-10">
                                                    <div
                                                        class="bg-gris-fonde border border-beige-peau/30 rounded-lg px-3 py-2 text-xs text-ivoire-text whitespace-nowrap shadow-lg">
                                                        🔒 Upload images — <a
                                                            href="<?php echo e(route($tattooer->routePrefix() . '.subscription.plans')); ?>"
                                                            class="text-beige-peau font-semibold hover:underline">Plan PRO
                                                            requis</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <textarea name="content" rows="2" placeholder="Votre message..."
                                            class="flex-1 w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none text-sm"></textarea>

                                        <button type="submit"
                                            class="px-4 sm:px-6 py-2 sm:py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors whitespace-nowrap"
                                            :disabled="files.length > 0 && !coverageType"
                                            :class="files.length > 0 && !coverageType ? 'opacity-50 cursor-not-allowed' : ''">
                                            Envoyer
                                        </button>
                                    </div>
                                <?php else: ?>
                                    
                                    <div class="flex flex-col sm:flex-row items-end gap-2">
                                        <textarea name="content" rows="2" placeholder="Votre message..." required
                                            class="flex-1 w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none text-sm"></textarea>
                                        <button type="submit"
                                            class="px-4 sm:px-6 py-2 sm:py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors whitespace-nowrap">
                                            Envoyer
                                        </button>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </form>

                            <script>
                                function messageForm() {
                                    return {
                                        files: [],
                                        designType: '',
                                        coverageType: '',
                                        surchargeAmount: null,
                                        showOverage: false,
                                        designTypeLabel: '',
                                        canSendDesign: <?php echo e($bookingRequest->canSendNewDesign() ? 'true' : 'false'); ?>,
                                        canSendModif: <?php echo e($bookingRequest->canSendModification() ? 'true' : 'false'); ?>,

                                        handleFileSelect(event) {
                                            this.files = Array.from(event.target.files);
                                            this.resetDesignType();
                                        },

                                        removeFile(index) {
                                            this.files.splice(index, 1);
                                            if (this.files.length === 0) {
                                                this.resetDesignType();
                                                document.getElementById('attachments').value = '';
                                            }
                                        },

                                        selectDesignType(type) {
                                            this.designType = type;
                                            this.showOverage = false;

                                            const isIncluded = (type === 'new_design' && this.canSendDesign) ||
                                                (type === 'modification' && this.canSendModif);

                                            if (isIncluded) {
                                                this.coverageType = 'included';
                                                this.designTypeLabel = type === 'new_design' ?
                                                    '🎨 Nouveau dessin (inclus dans le forfait)' :
                                                    '✏️ Modification (incluse dans le forfait)';
                                            } else {
                                                this.showOverage = true;
                                                this.coverageType = '';
                                                this.designTypeLabel = type === 'new_design' ?
                                                    '🎨 Nouveau dessin (hors forfait)' :
                                                    '✏️ Modification (hors forfait)';
                                            }
                                        },

                                        setOverage(type) {
                                            this.coverageType = 'send_free';
                                            this.showOverage = false;
                                            this.designTypeLabel += ' — envoi gratuit hors forfait';
                                        },

                                        resetDesignType() {
                                            this.designType = '';
                                            this.coverageType = '';
                                            this.surchargeAmount = null;
                                            this.showOverage = false;
                                            this.designTypeLabel = '';
                                        },

                                        handleSubmit(event) {
                                            if (this.files.length > 0 && !this.designType) {
                                                event.preventDefault();
                                                alert('Veuillez choisir le type d\'envoi (nouveau dessin ou modification).');
                                                return;
                                            }

                                            // Protection contre double soumission
                                            const submitButton = event.target.querySelector('button[type="submit"]');
                                            if (submitButton.disabled) {
                                                event.preventDefault();
                                                return;
                                            }

                                            // Désactiver le bouton pendant la soumission
                                            submitButton.disabled = true;
                                            submitButton.textContent = 'Envoi en cours...';

                                            // Réactiver après 5 secondes (en cas d'erreur)
                                            setTimeout(() => {
                                                submitButton.disabled = false;
                                                submitButton.textContent = 'Envoyer';
                                            }, 5000);
                                        }
                                    }
                                }
                            </script>
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
                            <svg class="w-5 h-5 text-ivoire-text transition-transform" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Emplacement:</span>
                                    <span class="text-ivoire-text"><?php echo e($bookingRequest->body_zone); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Style:</span>
                                    <span
                                        class="text-ivoire-text"><?php echo e($bookingRequest->tattoo_style ?? 'Non défini'); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->description): ?>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-ivoire-text/70">Description:</span>
                                        <span
                                            class="text-ivoire-text text-xs"><?php echo e(Str::limit($bookingRequest->description, 100)); ?></span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Estimation tattoo:</span>
                                    <span class="text-ivoire-text">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->price_range_min && $bookingRequest->price_range_max): ?>
                                            <?php echo e(number_format($bookingRequest->price_range_min, 0)); ?>€ -
                                            <?php echo e(number_format($bookingRequest->price_range_max, 0)); ?>€
                                        <?php elseif($bookingRequest->price_estimate_max): ?>
                                            <?php echo e(number_format($bookingRequest->price_estimate_max, 2, ',', ' ')); ?> €
                                        <?php else: ?>
                                            Non défini
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Date demande:</span>
                                    <span
                                        class="text-ivoire-text"><?php echo e($bookingRequest->created_at->format('d/m/Y à H:i')); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Statut:</span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                <?php echo e(match ($bookingRequest->status->value) {
                                    'pending' => 'bg-jaune-alerte/20 text-jaune-alerte',
                                    'accepted' => 'bg-vert-succes/20 text-vert-succes',
                                    'deposit_requested' => 'bg-jaune-alerte/20 text-jaune-alerte',
                                    'deposit_paid' => 'bg-vert-succes/20 text-vert-succes',
                                    'date_confirmed' => 'bg-vert-succes/20 text-vert-succes',
                                    'in_progress' => 'bg-beige-peau/20 text-beige-peau',
                                    'completed' => 'bg-vert-succes/20 text-vert-succes',
                                    'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                    default => 'bg-gris-fonde/20 text-ivoire-text',
                                }); ?>">
                                        <?php echo e(match ($bookingRequest->status->value) {
                                            'pending' => '⏳ En attente',
                                            'accepted' => '✅ Acceptée',
                                            'deposit_requested' => '⏳ Acompte attendu',
                                            'deposit_paid' => '💰 Acompte payé',
                                            'in_progress' => '🎨 En cours',
                                            'completed' => '✅ Terminé',
                                            'cancelled' => '❌ Annulée',
                                            default => ucfirst($bookingRequest->status->value),
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
                            <svg class="w-5 h-5 text-ivoire-text transition-transform" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Email:</span>
                                    <span class="text-ivoire-text"><?php echo e($bookingRequest->client->user->email); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Téléphone:</span>
                                    <span
                                        class="text-ivoire-text"><?php echo e($bookingRequest->client->phone ?: 'Non renseigné'); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Âge:</span>
                                    <span class="text-ivoire-text"><?php echo e($bookingRequest->client->birth_date->age); ?>

                                        ans</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Version desktop (toujours visible) -->
                <div class="hidden md:grid md:grid-cols-2 md:gap-6 md:mt-6">
                    <!-- Infos demande -->
                    <div class="mt-6 bg-gris-fonde rounded-xl border border-titane/30 p-6">
                        <h3 class="text-lg font-bold text-ivoire-text mb-4">📋 Détails de la demande</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold text-ivoire-text/80 mb-2">Zone du tatouage</h4>
                                    <p class="text-ivoire-text"><?php echo e($bookingRequest->body_zone); ?></p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-ivoire-text/80 mb-2">Style</h4>
                                    <p class="text-ivoire-text"><?php echo e($bookingRequest->tattoo_style ?? 'Non défini'); ?></p>
                                </div>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->description): ?>
                                <div>
                                    <h4 class="font-semibold text-ivoire-text/80 mb-2">Description</h4>
                                    <p class="text-ivoire-text"><?php echo e($bookingRequest->description); ?></p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold text-ivoire-text/80 mb-2">Estimation tattoo</h4>
                                    <p class="text-ivoire-text">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->price_range_min && $bookingRequest->price_range_max): ?>
                                            <?php echo e(number_format($bookingRequest->price_range_min, 0)); ?>€ -
                                            <?php echo e(number_format($bookingRequest->price_range_max, 0)); ?>€
                                        <?php elseif($bookingRequest->estimated_total_price): ?>
                                            <?php echo e(number_format($bookingRequest->estimated_total_price, 2, ',', ' ')); ?> €
                                        <?php else: ?>
                                            Non défini
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-ivoire-text/80 mb-2">Date de la demande</h4>
                                    <p class="text-ivoire-text"><?php echo e($bookingRequest->created_at->format('d/m/Y à H:i')); ?>

                                    </p>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-ivoire-text/80 mb-2">Statut</h4>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            <?php echo e(match ($bookingRequest->status->value) {
                                'pending' => 'bg-jaune-alerte/20 text-jaune-alerte',
                                'accepted' => 'bg-vert-succes/20 text-vert-succes',
                                'deposit_requested' => 'bg-jaune-alerte/20 text-jaune-alerte',
                                'deposit_paid' => 'bg-vert-succes/20 text-vert-succes',
                                'in_progress' => 'bg-beige-peau/20 text-beige-peau',
                                'completed' => 'bg-vert-succes/20 text-vert-succes',
                                'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                default => 'bg-gris-fonde/20 text-ivoire-text',
                            }); ?>">
                                    <?php echo e(match ($bookingRequest->status->value) {
                                        'pending' => '⏳ En attente',
                                        'accepted' => '✅ Acceptée',
                                        'deposit_requested' => '⏳ Acompte attendu',
                                        'deposit_paid' => '💰 Acompte payé',
                                        'in_progress' => '🎨 En cours',
                                        'completed' => '✅ Terminé',
                                        'cancelled' => '❌ Annulée',
                                        default => ucfirst($bookingRequest->status->value),
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
                                <span
                                    class="text-ivoire-text"><?php echo e($bookingRequest->client->phone ?: 'Non renseigné'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Âge:</span>
                                <span class="text-ivoire-text"><?php echo e($bookingRequest->client->birth_date->age); ?> ans</span>
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

    
    <div id="repropose-dates-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-noir-profond/70" onclick="closeReproposeDatesModal()"></div>

        <div
            class="relative bg-gris-fonde rounded-2xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-titane/20 mx-auto my-8">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                    📅 Proposer de nouvelles dates
                </h3>
                <button onclick="closeReproposeDatesModal()"
                    class="text-ivoire-text/50 hover:text-ivoire-text transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="bg-noir-profond/50 rounded-xl p-4 mb-5">
                <p class="text-ivoire-text/60 text-sm">
                    Le client a refusé les dates précédentes.
                    Sélectionnez 1 à 3 nouvelles dates disponibles à lui proposer.
                </p>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($bookingRequest)): ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('components.availability-calendar', ['tattooerId' => $bookingRequest->bookable_id,'mode' => 'multi-max-3','showPeriodSelector' => true]);

$key = 'calendar-repropose-' . $bookingRequest->id;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3215193526-0', 'calendar-repropose-' . $bookingRequest->id);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div id="selected-dates-display"
                class="hidden bg-vert-succes/10 rounded-xl p-4 mb-5 border border-vert-succes/20">
                <p class="text-sm text-vert-succes font-semibold mb-2">
                    <span id="dates-count">0</span> date<span id="dates-plural">s</span> sélectionnée<span
                        id="dates-plural-2">s</span> :
                </p>
                <div id="selected-dates-list" class="flex flex-wrap gap-2">
                    <!-- Dates will be inserted here by JavaScript -->
                </div>
            </div>

            
            <form
                action="<?php echo e(route($tattooer->routePrefix() . '.booking-requests.repropose-dates', $bookingRequest ?? 0)); ?>"
                method="POST" id="repropose-dates-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="proposed_dates" id="repropose-dates-input" value="[]">

                <div class="flex justify-end gap-3 pt-4 border-t border-titane/20">
                    <button type="button" onclick="closeReproposeDatesModal()"
                        class="px-4 py-2.5 border border-titane/30 text-ivoire-text/70 rounded-xl text-sm hover:bg-titane/10 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" id="submit-dates-btn" disabled
                        class="px-5 py-2.5 bg-beige-peau text-noir-profond rounded-xl text-sm font-semibold hover:bg-beige-peau/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Envoyer les nouvelles dates
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <?php if(isset($bookingRequest) && $bookingRequest): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tattooer.repropose-dates-modal', ['bookingRequestId' => $bookingRequest->id]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3215193526-1', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\tattooer\message-show.blade.php ENDPATH**/ ?>