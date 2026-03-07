<?php $__env->startSection('title', 'Paiement réussi - Ink&Pik'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Carte de succès -->
            <div class="bg-titane/20 rounded-xl border border-titane/30 p-8 text-center">
                <!-- Icône de succès -->
                <div class="inline-flex items-center justify-center w-20 h-20 bg-vert-succes/20 rounded-full mb-6">
                    <svg class="w-10 h-10 text-vert-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-ivoire-text mb-4">
                    ✅ Paiement réussi !
                </h1>

                <p class="text-ivoire-text/70 mb-8 text-lg">
                    Votre acompte de <span
                        class="text-beige-peau font-semibold"><?php echo e(number_format($bookingRequest->total_deposit_amount, 0)); ?>€</span>
                    a été payé avec succès.
                </p>

                <!-- Détails de la réservation -->
                <div class="bg-noir-profond rounded-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-ivoire-text mb-4">📋 Réservation confirmée</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Artiste:</span>
                                <span class="text-ivoire-text"><?php echo e($bookingRequest->bookable->user->pseudo); ?></span>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->tattoo_size): ?>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Taille:</span>
                                    <span class="text-ivoire-text"><?php echo e($bookingRequest->tattoo_size); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->body_zone): ?>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Zone:</span>
                                    <span class="text-ivoire-text"><?php echo e($bookingRequest->body_zone); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->price_range_min && $bookingRequest->price_range_max): ?>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Estimation tattoo:</span>
                                    <span class="text-ivoire-text"><?php echo e(number_format($bookingRequest->price_range_min, 0)); ?>€
                                        - <?php echo e(number_format($bookingRequest->price_range_max, 0)); ?>€</span>
                                </div>
                            <?php elseif($bookingRequest->estimated_total_price): ?>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Estimation tattoo:</span>
                                    <span
                                        class="text-ivoire-text"><?php echo e(number_format($bookingRequest->estimated_total_price, 0)); ?>€</span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Statut:</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-vert-succes/20 text-vert-succes">
                                    ✅ Acompte payé
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Acompte payé:</span>
                                <span class="text-vert-succes font-semibold">
                                    <?php echo e(number_format($bookingRequest->total_deposit_amount, 0)); ?>€
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prochaines étapes -->
                <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-vert-succes mb-4">🎯 Prochaines étapes</h3>

                    <div class="space-y-3 text-left">
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 bg-vert-succes rounded-full flex items-center justify-center text-xs font-bold text-noir-profond mr-3 mt-0.5">
                                1
                            </div>
                            <div>
                                <p class="text-ivoire-text font-medium">L'artiste vous contactera</p>
                                <p class="text-ivoire-text/70 text-sm">Pour finaliser les détails du projet et fixer la date
                                    du rendez-vous</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 bg-vert-succes rounded-full flex items-center justify-center text-xs font-bold text-noir-profond mr-3 mt-0.5">
                                2
                            </div>
                            <div>
                                <p class="text-ivoire-text font-medium">Chat permanent activé</p>
                                <p class="text-ivoire-text/70 text-sm">
                                    Vous pouvez communiquer directement avec l'artiste via le chat.
                                    N'hésitez pas à envoyer au besoin des références supplémentaires, demander des
                                    ajustements ou
                                    partager vos idées pour finaliser votre projet.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 bg-vert-succes rounded-full flex items-center justify-center text-xs font-bold text-noir-profond mr-3 mt-0.5">
                                3
                            </div>
                            <div>
                                <p class="text-ivoire-text font-medium">Rendez-vous confirmé</p>
                                <p class="text-ivoire-text/70 text-sm">Le solde sera à payer le jour du rendez-vous</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('client.chat', $bookingRequest->conversation)); ?>"
                        class="inline-flex items-center justify-center px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.009 9.009 0 00-2.617-.656L4 19l1.383-5.344A9.002 9.002 0 016 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Discuter avec l'artiste
                    </a>

                    <a href="<?php echo e(route('client.booking-requests')); ?>"
                        class="inline-flex items-center justify-center px-6 py-3 bg-titane text-ivoire-text rounded-lg font-semibold hover:bg-titane/80 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Mes réservations
                    </a>
                </div>

                <!-- Informations importantes -->
                <div class="mt-8 p-4 bg-noir-profond rounded-lg">
                    <h4 class="text-ivoire-text font-medium mb-2">📧 Confirmation envoyée</h4>
                    <p class="text-ivoire-text/70 text-sm">
                        Un email de confirmation a été envoyé à votre adresse email.
                        Conservez-le pour vos archives.
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client\deposit-success.blade.php ENDPATH**/ ?>