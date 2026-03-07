<?php $__env->startSection('title', 'Réclamations'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-6">
                <a href="<?php echo e(route('client.profile')); ?>"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour au profil
                </a>

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-ivoire-text">Réclamations</h1>
                        <p class="text-ivoire-text/70 mt-1">Suivez l'état de vos réclamations</p>
                    </div>
                </div>
            </div>

            <!-- Formulaire de réclamation -->
            <div class="bg-titane/20 rounded-xl p-6 border border-titane/30 mb-8">
                <h2 class="text-xl font-bold text-ivoire-text mb-4">Nouvelle réclamation</h2>
                <form action="<?php echo e(route('client.complaints.store')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Type de réclamation</label>
                        <select name="type"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau">
                            <option value="">Choisir un type...</option>
                            <option value="no_show">No-show (artiste absent)</option>
                            <option value="quality">Qualité du travail</option>
                            <option value="hygiene">Hygiène</option>
                            <option value="payment">Paiement</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau"
                            placeholder="Décrivez votre réclamation..."></textarea>
                    </div>
                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Demande concernée (optionnel)</label>
                        <select name="booking_request_id"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau">
                            <option value="">Choisir une demande...</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = auth()->user()->client->bookingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bookingRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($bookingRequest->id); ?>"><?php echo e($bookingRequest->description); ?> -
                                    <?php echo e($bookingRequest->bookable->user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-rouge-alerte hover:bg-rouge-alerte/90 text-noir-profond rounded-lg font-semibold transition-colors">
                            Soumettre la réclamation
                        </button>
                    </div>
                </form>
            </div>

            <!-- Liste des réclamations -->
            <div class="space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaints->isEmpty()): ?>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-titane/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-ivoire-text/30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-ivoire-text mb-2">Aucune réclamation</h3>
                        <p class="text-ivoire-text/60">Vous n'avez pas encore soumis de réclamation.</p>
                    </div>
                <?php else: ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-ivoire-text">
                                        <?php echo e($complaint->type_label); ?>

                                    </h3>
                                    <p class="text-ivoire-text/70 text-sm">
                                        Soumis le <?php echo e($complaint->created_at->format('d/m/Y à H:i')); ?>

                                    </p>
                                </div>
                                <div>
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                        <?php switch($complaint->status):
                                            case ('pending'): ?>
                                                bg-jaune-alerte/20 text-jaune-alerte
                                            <?php break; ?>
                                            <?php case ('investigating'): ?>
                                                bg-ambre-warning/20 text-ambre-warning
                                            <?php break; ?>
                                            <?php case ('resolved'): ?>
                                                bg-vert-succes/20 text-vert-succes
                                            <?php break; ?>
                                            <?php case ('rejected'): ?>
                                                bg-rouge-alerte/20 text-rouge-alerte
                                            <?php break; ?>
                                            <?php default: ?>
                                                bg-titane/20 text-titane
                                        <?php endswitch; ?>
                                    >
                                        <?php echo e($complaint->status_label); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="text-ivoire-text
                                        mb-4">
                                        <?php echo e($complaint->description); ?>

                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaint->admin_notes): ?>
                                    <div class="bg-noir-profond rounded-lg p-4 border border-titane/30">
                                        <h4 class="text-sm font-semibold text-ivoire-text mb-2">Notes de l'administrateur
                                        </h4>
                                        <p class="text-ivoire-text/70 text-sm"><?php echo e($complaint->admin_notes); ?></p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($complaint->resolved_at): ?>
                                    <div class="text-ivoire-text/70 text-sm">
                                        Résolu le <?php echo e($complaint->resolved_at->format('d/m/Y à H:i')); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client\complaints.blade.php ENDPATH**/ ?>