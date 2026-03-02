<?php $__env->startSection('title', 'Payer l\'acompte - ' . $bookingRequest->bookable->user->name); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- En-tête -->
            <div class="text-center mb-8">
                <a href="<?php echo e(route('client.booking-request.show', $bookingRequest)); ?>"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux détails
                </a>

                <h1 class="text-3xl font-bold text-ivoire-text mb-4">
                    💰 Payer l'acompte
                </h1>
                <p class="text-ivoire-text/70">
                    Finalisez votre réservation avec <?php echo e($bookingRequest->bookable->user->name); ?>

                </p>
            </div>

            <!-- Carte principale -->
            <div class="bg-titane/20 rounded-xl border border-titane/30 p-8 mb-6">
                <!-- Détails de la réservation -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-ivoire-text mb-4">📋 Détails de la réservation</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Artiste:</span>
                                <span class="text-ivoire-text"><?php echo e($bookingRequest->bookable->user->name); ?></span>
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
                                <span class="text-ivoire-text/70">Acompte requis:</span>
                                <span class="text-beige-peau font-semibold">
                                    <?php echo e(number_format($bookingRequest->total_deposit_amount, 0)); ?>€
                                </span>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->client_payment_deadline): ?>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Date limite:</span>
                                    <span
                                        class="<?php echo e($bookingRequest->client_payment_deadline->isPast() ? 'text-rouge-alerte' : 'text-jaune-alerte'); ?>">
                                        <?php echo e($bookingRequest->client_payment_deadline->format('d/m/Y H:i')); ?>

                                    </span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Alertes -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->client_payment_deadline && $bookingRequest->client_payment_deadline->isPast()): ?>
                    <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-3 text-rouge-alerte" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-rouge-alerte mb-1">⚠️ Délai dépassé</h3>
                                <p class="text-rouge-alerte text-sm">
                                    Le délai de paiement est expiré. Contactez l'artiste pour plus d'informations.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php elseif($bookingRequest->client_payment_deadline && $bookingRequest->client_payment_deadline->diffInDays(now(), false) <= 2): ?>
                    <?php
                    $diff = now()->diff($bookingRequest->client_payment_deadline);
                    $timeLeft = $diff->days > 0 ? $diff->days . ' jour(s)' : $diff->h . ' heure(s)';
                    ?>
                    <div class="bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-3 text-jaune-alerte" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-jaune-alerte mb-1">⏰ Urgent</h3>
                                <p class="text-jaune-alerte text-sm">
                                    Plus que <?php echo e($timeLeft); ?> pour payer l'acompte !
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Résumé du paiement -->
                <div class="bg-noir-profond rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-ivoire-text mb-4">💳 Résumé du paiement</h3>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-ivoire-text/70">
                            <span>Acompte pour réservation avec <?php echo e($bookingRequest->bookable->user->name); ?></span>
                            <span><?php echo e(number_format($bookingRequest->total_deposit_amount, 0)); ?>€</span>
                        </div>

                        <div class="border-t border-titane/30 pt-2">
                            <div class="flex justify-between text-lg font-semibold text-ivoire-text">
                                <span>Total à payer</span>
                                <span
                                    class="text-beige-peau"><?php echo e(number_format($bookingRequest->total_deposit_amount, 0)); ?>€</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-sm text-ivoire-text/50">
                        <p>💡 Le paiement sera sécurisé via Stripe et traité immédiatement.</p>
                        <p>Une fois l'acompte payé, votre réservation sera confirmée.</p>
                    </div>
                </div>

                <!-- Bouton de paiement -->
                <div class="text-center">
                    <button id="pay-deposit-btn"
                        class="inline-flex items-center px-8 py-4 bg-beige-peau text-noir-profond rounded-lg font-semibold text-lg hover:bg-beige-peau/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Payer <?php echo e(number_format($bookingRequest->total_deposit_amount, 0)); ?>€ avec Stripe
                    </button>

                    <div class="mt-4 flex items-center justify-center space-x-4 text-sm text-ivoire-text/50">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Paiement sécurisé
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Traitement immédiat
                        </div>
                    </div>
                    </form>
                </div>

                <!-- Informations supplémentaires -->
                <div class="text-center text-ivoire-text/50 text-sm">
                    <p class="mb-2">
                        ❓ Des questions ?
                        <a href="<?php echo e(route('client.booking-request.show', $bookingRequest)); ?>"
                            class="text-beige-peau hover:text-beige-peau/80">
                            Contactez l'artiste via le chat
                        </a>
                    </p>
                    <p>
                        📧 Pour toute assistance technique : support@tattoolib-saas.com
                    </p>
                </div>
            </div>
        </div>
    <?php $__env->stopSection(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            // Vérifier que la clé Stripe est disponible
            const stripePublishableKey = '<?php echo e($stripeKey); ?>';
            console.log('Stripe publishable key:', stripePublishableKey);

            document.addEventListener('DOMContentLoaded', function() {
                if (!stripePublishableKey) {
                    console.error('Stripe publishable key is empty');
                    alert('Configuration Stripe manquante. Veuillez contacter le support.');
                    return;
                }

                const payButton = document.getElementById('pay-deposit-btn');

                payButton.addEventListener('click', async function() {
                    payButton.disabled = true;
                    payButton.innerHTML = '⏳ Chargement...';

                    try {
                        const response = await fetch('<?php echo e(route('deposit.process', $bookingRequest)); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                            },
                            body: JSON.stringify({})
                        });

                        const data = await response.json();

                        if (data.sessionId) {
                            const stripe = Stripe(stripePublishableKey);

                            const {
                                error
                            } = await stripe.redirectToCheckout({
                                sessionId: data.sessionId
                            });

                            if (error) {
                                throw error;
                            }
                        } else {
                            throw new Error('Session non créée');
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        payButton.disabled = false;
                        payButton.innerHTML =
                            '💳 Payer <?php echo e(number_format($bookingRequest->total_deposit_amount, 0)); ?>€ avec Stripe';
                        alert('Une erreur est survenue: ' + error.message);
                    }
                });
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/client/deposit-payment.blade.php ENDPATH**/ ?>