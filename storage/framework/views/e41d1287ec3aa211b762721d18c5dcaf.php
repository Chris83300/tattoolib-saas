<?php $__env->startSection('title', 'Gérer mon abonnement - Ink&Pik'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-ivoire-text">Gérer mon abonnement</h1>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="bg-vert-succes/20 border border-vert-succes/30 text-vert-succes rounded-xl p-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl p-4">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
            <div class="bg-beige-peau/20 border border-beige-peau/30 text-beige-peau rounded-xl p-4">
                <?php echo e(session('info')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="bg-gris-fonde rounded-xl p-6 border border-titane/20">
            <h2 class="text-lg font-bold text-ivoire-text mb-2">Mon plan actuel</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist->isPro()): ?>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-beige-peau text-noir-profond rounded-full text-sm font-bold">PRO</span>
                    <span class="text-ivoire-text/70">29,99€/mois · Commission 0%</span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeSubscription?->isOnGracePeriod()): ?>
                    <div class="mt-3 bg-ambre-warning/10 border border-ambre-warning/30 rounded-lg p-3">
                        <p class="text-sm text-ambre-warning">
                            ⚠️ Abonnement annulé. Accès PRO jusqu'au
                            <strong><?php echo e($activeSubscription->ends_at->translatedFormat('d F Y')); ?></strong>
                        </p>
                        <form action="<?php echo e(route($artist->routePrefix() . '.subscription.resume')); ?>" method="POST"
                            class="mt-2">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="px-4 py-2 bg-vert-succes text-white rounded-lg text-sm font-semibold hover:bg-vert-succes/90">
                                Réactiver mon abonnement
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a href="<?php echo e(route($artist->routePrefix() . '.subscription.manage')); ?>"
                            class="px-4 py-2 bg-titane/20 text-ivoire-text rounded-lg text-sm font-semibold hover:bg-titane/30 transition-colors">
                            💳 Gérer le paiement
                        </a>
                        <form action="<?php echo e(route($artist->routePrefix() . '.subscription.cancel')); ?>" method="POST"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ? Vous gardez l\'accès PRO jusqu\'à la fin de la période.')">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="px-4 py-2 border border-rouge-alerte/30 text-rouge-alerte rounded-lg text-sm hover:bg-rouge-alerte/10 transition-colors">
                                Annuler l'abonnement
                            </button>
                        </form>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <?php
                    $trialService = app(\App\Services\TrialService::class);
                    $daysLeft = $trialService->trialDaysRemaining($artist);
                    $isOnTrial = $daysLeft > 0 && !$artist->is_subscribed;
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist->is_subscribed && $artist->current_plan === 'starter'): ?>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-titane/30 text-titane rounded-full text-sm font-bold">STARTER</span>
                        <span class="text-ivoire-text/70">9,99€/mois · Commission 7%</span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a href="<?php echo e(route($artist->routePrefix() . '.subscription.manage')); ?>"
                            class="px-4 py-2 bg-titane/20 text-ivoire-text rounded-lg text-sm font-semibold hover:bg-titane/30 transition-colors">
                            💳 Gérer le paiement
                        </a>
                        <form action="<?php echo e(route($artist->routePrefix() . '.subscription.cancel')); ?>" method="POST"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ?')">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="px-4 py-2 border border-rouge-alerte/30 text-rouge-alerte rounded-lg text-sm hover:bg-rouge-alerte/10 transition-colors">
                                Annuler l'abonnement
                            </button>
                        </form>
                    </div>
                <?php elseif($isOnTrial): ?>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-titane/30 text-titane rounded-full text-sm font-bold">STARTER</span>
                        <span class="text-ivoire-text/70">Essai gratuit</span>
                    </div>
                    <p class="text-sm text-titane mt-1">🎁 <?php echo e($daysLeft); ?> jour<?php echo e($daysLeft > 1 ? 's' : ''); ?> restant<?php echo e($daysLeft > 1 ? 's' : ''); ?> — Activez votre abonnement pour ne pas perdre l'accès.</p>
                <?php elseif($artist->is_blocked): ?>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-rouge-alerte/20 text-rouge-alerte rounded-full text-sm font-bold">BLOQUÉ</span>
                    </div>
                    <p class="text-sm text-rouge-alerte mt-1">🔒 Essai expiré — choisissez un plan pour réactiver votre profil</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->is_beta_tester): ?>
                <div class="flex items-center gap-2 px-3 py-2 bg-beige-peau/10 border border-beige-peau/30 rounded-lg mt-3">
                    <span class="text-beige-peau">🏆</span>
                    <p class="text-xs text-titane">
                        <strong class="text-beige-peau">Bêta-testeur</strong> — -30% à vie appliqué automatiquement
                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            
            <div
                class="bg-gris-fonde rounded-xl p-6 border <?php echo e($artist->isFree() ? 'border-titane/40' : 'border-titane/20'); ?>">
                <h3 class="text-xl font-bold text-ivoire-text mb-1">Starter</h3>
                <p class="text-3xl font-bold text-ivoire-text mb-1">9,99€<span
                        class="text-sm font-normal text-titane">/mois</span></p>
                <p class="text-xs text-vert-succes mb-4">🎁 14 jours d'essai gratuit — sans CB</p>

                <ul class="space-y-2 text-sm text-ivoire-text/80 mb-6">
                    <li class="flex items-center gap-2">✅ Profil marketplace</li>
                    <li class="flex items-center gap-2">✅ Réception de demandes</li>
                    <li class="flex items-center gap-2">✅ Chat client</li>
                    <li class="flex items-center gap-2">✅ Calendrier</li>
                    <li class="flex items-center gap-2">✅ Paiements sécurisés</li>
                    <li class="flex items-center gap-2 text-rouge-alerte/80">❌ Commission 7% par transaction</li>
                    <li class="flex items-center gap-2 text-ivoire-text/40">❌ Fiche client avancée</li>
                    <li class="flex items-center gap-2 text-ivoire-text/40">❌ Analytics</li>
                </ul>

                <?php
                    $isStarterSubscribed = $artist->is_subscribed && $artist->current_plan === 'starter';
                    $isProSubscribed = $artist->is_subscribed && $artist->current_plan === 'pro';
                    $isNotSubscribed = !$artist->is_subscribed;
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isStarterSubscribed): ?>
                    <div class="px-4 py-2.5 bg-titane/20 text-titane rounded-lg text-center text-sm font-semibold">
                        Plan actuel
                    </div>
                <?php elseif($isNotSubscribed): ?>
                    <form action="<?php echo e(route($artist->routePrefix() . '.subscription.subscribe')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="plan" value="starter">
                        <button type="submit"
                            class="w-full px-4 py-3 bg-titane/30 text-ivoire-text border border-titane/40 rounded-lg font-bold text-sm hover:bg-titane/50 transition-colors active:scale-95">
                            Activer le plan Starter
                        </button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="bg-gris-fonde rounded-xl p-6 border-2 border-beige-peau relative">
                <div
                    class="absolute -top-3 left-4 px-3 py-0.5 bg-beige-peau text-noir-profond text-xs font-bold rounded-full">
                    RECOMMANDÉ
                </div>

                <h3 class="text-xl font-bold text-ivoire-text mb-1">PRO</h3>
                <p class="text-3xl font-bold text-beige-peau mb-1">29,99€<span
                        class="text-sm font-normal text-titane">/mois</span></p>
                <p class="text-xs text-vert-succes mb-4">🎁 14 jours d'essai gratuit — sans CB</p>

                <ul class="space-y-2 text-sm text-ivoire-text/80 mb-6">
                    <li class="flex items-center gap-2"><span class="text-sm text-vert-succes">✓</span> Tout le plan Starter
                    </li>
                    <li class="flex items-center gap-2 font-semibold"><span class="text-sm text-vert-succes">✓</span>
                        Commission 0%</li>
                    <li class="flex items-center gap-2"><span class="text-sm text-vert-succes">✓</span> Fiche client
                        (automatique) + manuelle</li>
                    <li class="flex items-center gap-2"><span class="text-sm text-vert-succes">✓</span> Traçabilité complète
                    </li>
                    <li class="flex items-center gap-2"><span class="text-sm text-vert-succes">✓</span> Analytics &
                        statistiques</li>
                    <li class="flex items-center gap-2"><span class="text-sm text-vert-succes">✓</span> Support prioritaire
                    </li>
                    <li class="flex items-center gap-2"><span class="text-sm text-vert-succes">✓</span> Portfolio illimité
                    </li>
                    <li class="flex items-center gap-2"><span class="text-sm text-vert-succes">✓</span> Export PDF fiches
                        clients</li>
                    <li class="flex items-center gap-2"><span class="text-sm text-vert-succes">✓</span> Export CSV/Excel
                        comptabilité</li>
                </ul>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isProSubscribed && !$activeSubscription?->isOnGracePeriod()): ?>
                    <div class="px-4 py-2.5 bg-beige-peau/20 text-beige-peau rounded-lg text-center text-sm font-semibold">
                        ✅ Plan actuel
                    </div>
                <?php else: ?>
                    <form action="<?php echo e(route($artist->routePrefix() . '.subscription.subscribe')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="plan" value="pro">
                        <button type="submit"
                            class="w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-lg font-bold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                            <?php echo e($isStarterSubscribed ? 'Passer au plan PRO' : 'Activer le plan PRO'); ?>

                        </button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p class="text-xs text-titane text-center mt-2">Annulable à tout moment · Paiement sécurisé Stripe</p>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$artist->is_subscribed || $artist->current_plan === 'starter'): ?>
            <div class="bg-gris-fonde rounded-xl p-6 border border-titane/20">
                <h3 class="text-lg font-bold text-beige-peau mb-3">"Les artistes PRO économisent en moyenne 150€/mois en
                    commission sur leurs réservations."</h3>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/tattooer/subscription-plans.blade.php ENDPATH**/ ?>