<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <!-- Header -->
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
            Paiements
        </h1>
        <p class="text-ivoire-text/70">
            Gérez vos transactions et votre compte Stripe Connect
        </p>
    </div>
    
    <!-- Stats KPI -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-noir-profond rounded-xl p-6 border border-titane/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-beige-peau/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm text-ivoire-text/60">Total gagné</span>
            </div>
            <div class="text-2xl font-bold text-ivoire-text">
                <?php echo e(number_format($paymentStats['total_earned'], 2, ',', ' ')); ?> €
            </div>
            <p class="text-sm text-ivoire-text/50 mt-2">
                Depuis le début
            </p>
        </div>
        
        <div class="bg-noir-profond rounded-xl p-6 border border-titane/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-vert-succes/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-vert-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span class="text-sm text-ivoire-text/60">Ce mois</span>
            </div>
            <div class="text-2xl font-bold text-vert-succes">
                <?php echo e(number_format($paymentStats['this_month'], 2, ',', ' ')); ?> €
            </div>
            <p class="text-sm text-ivoire-text/50 mt-2">
                <?php echo e(now()->format('F Y')); ?>

            </p>
        </div>
        
        <div class="bg-noir-profond rounded-xl p-6 border border-titane/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-ambre-warning/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-ambre-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm text-ivoire-text/60">Acomptes en attente</span>
            </div>
            <div class="text-2xl font-bold text-ambre-warning">
                <?php echo e(number_format($paymentStats['pending_deposits'], 2, ',', ' ')); ?> €
            </div>
            <p class="text-sm text-ivoire-text/50 mt-2">
                En attente de paiement
            </p>
        </div>
    </div>
    
    <!-- Stripe Connect -->
    <div class="bg-gris-fonde rounded-xl p-6">
        <h2 class="text-xl font-semibold text-ivoire-text mb-4">
            Stripe Connect
        </h2>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer->stripe_connect_account_id): ?>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-noir-profond rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-vert-succes/20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-vert-succes" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-ivoire-text">Compte connecté</h3>
                            <p class="text-sm text-ivoire-text/60">Votre compte Stripe est actif</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-titane text-ivoire-text rounded-lg hover:bg-titane/80 transition-colors">
                        Gérer le compte
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-noir-profond rounded-lg">
                        <h4 class="font-semibold text-ivoire-text mb-2">Solde disponible</h4>
                        <p class="text-2xl font-bold text-vert-succes">0,00 €</p>
                        <p class="text-sm text-ivoire-text/50">Disponible immédiatement</p>
                    </div>
                    
                    <div class="p-4 bg-noir-profond rounded-lg">
                        <h4 class="font-semibold text-ivoire-text mb-2">En attente</h4>
                        <p class="text-2xl font-bold text-ambre-warning">0,00 €</p>
                        <p class="text-sm text-ivoire-text/50">En cours de traitement</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                    Connectez votre compte Stripe
                </h3>
                <p class="text-ivoire-text/60 mb-4">
                    Recevez vos paiements directement sur votre compte bancaire
                </p>
                <button class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                    Connecter Stripe Connect
                </button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    
    <!-- Transactions récentes -->
    <div class="bg-gris-fonde rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-ivoire-text">
                Transactions récentes
            </h2>
            <button class="px-4 py-2 bg-titane text-ivoire-text rounded-lg hover:bg-titane/80 transition-colors">
                Exporter
            </button>
        </div>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payments->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-titane/30">
                            <th class="text-left py-3 px-4 text-ivoire-text/60 font-medium">Date</th>
                            <th class="text-left py-3 px-4 text-ivoire-text/60 font-medium">Client</th>
                            <th class="text-left py-3 px-4 text-ivoire-text/60 font-medium">Description</th>
                            <th class="text-left py-3 px-4 text-ivoire-text/60 font-medium">Méthode</th>
                            <th class="text-right py-3 px-4 text-ivoire-text/60 font-medium">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b border-titane/20 hover:bg-noir-profond/50 transition-colors">
                                <td class="py-3 px-4 text-ivoire-text">
                                    <?php echo e($payment->tattoo_date->format('d/m/Y')); ?>

                                </td>
                                <td class="py-3 px-4 text-ivoire-text">
                                    <?php echo e($payment->client->first_name); ?> <?php echo e($payment->client->last_name); ?>

                                </td>
                                <td class="py-3 px-4 text-ivoire-text">
                                    <?php echo e($payment->description); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payment->body_location): ?>
                                        <span class="text-xs text-ivoire-text/60 ml-2">
                                            (<?php echo e($payment->body_location); ?>)
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 bg-titane/20 text-titane rounded-full text-xs font-medium">
                                        <?php echo e($payment->payment_method); ?>

                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right font-semibold text-vert-succes">
                                    <?php echo e(number_format($payment->total_paid, 2, ',', ' ')); ?> €
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                <?php echo e($payments->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                    Aucune transaction
                </h3>
                <p class="text-ivoire-text/60">
                    Vous n'avez pas encore de transactions enregistrées.
                </p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Gestion des actions Stripe Connect (placeholder)
document.querySelectorAll('button').forEach(button => {
    if (button.textContent.includes('Connecter') || button.textContent.includes('Gérer')) {
        button.addEventListener('click', function() {
            // Placeholder pour l'intégration Stripe Connect
            alert('Fonctionnalité Stripe Connect à implémenter');
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\tattooer\payments.blade.php ENDPATH**/ ?>