<?php $__env->startSection('title', 'Mes avis'); ?>

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
                        <h1 class="text-3xl font-bold text-ivoire-text">Mes avis</h1>
                        <p class="text-ivoire-text/70 mt-1">Consultez et gérez vos avis</p>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30 text-center">
                    <div class="text-2xl font-bold text-beige-peau mb-1"><?php echo e($reviews->count()); ?></div>
                    <div class="text-ivoire-text/60 text-xs">Total avis</div>
                </div>
                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30 text-center">
                    <div class="text-2xl font-bold text-vert-succes mb-1"><?php echo e($reviews->where('rating', '>=', 4)->count()); ?></div>
                    <div class="text-ivoire-text/60 text-xs">Avis 4-5 étoiles</div>
                </div>
                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30 text-center">
                    <div class="text-2xl font-bold text-ambre-warning mb-1"><?php echo e($reviews->where('rating', 3)->count()); ?></div>
                    <div class="text-ivoire-text/60 text-xs">Avis 3 étoiles</div>
                </div>
                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30 text-center">
                    <div class="text-2xl font-bold text-rouge-alerte mb-1"><?php echo e($reviews->where('rating', '<=', 2)->count()); ?></div>
                    <div class="text-ivoire-text/60 text-xs">Avis 1-2 étoiles</div>
                </div>
            </div>

            <!-- Liste des avis -->
            <div class="space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($reviews->isEmpty()): ?>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-titane/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-ivoire-text/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 0-1.605-.56-1.86-1.34l-.328.328c.321.321.621.642 1.024.925 1.41 1.524.925 1.41 1.524.925 2.023 0 2.023-.925 2.023-2.023 0-1.098-.925-2.023-2.023-2.023zm0 3.48c.3-.921 0-1.605-.56-1.86-1.34l-.328.328c.321.321.621.642 1.024.925 1.41 1.524.925 1.41 1.524.925 2.023 0 2.023-.925 2.023-2.023 0-1.098-.925-2.023-2.023-2.023z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-ivoire-text mb-2">Aucun avis</h3>
                        <p class="text-ivoire-text/60">Vous n'avez pas encore laissé d'avis.</p>
                    </div>
                <?php else: ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full overflow-hidden bg-noir-profond border border-beige-peau/30">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->tattooer->getFirstMediaUrl('avatar')): ?>
                                            <img src="<?php echo e($review->tattooer->getFirstMediaUrl('avatar')); ?>" alt="<?php echo e($review->tattooer->user->name); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-ivoire-text/30" fill="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-ivoire-text"><?php echo e($review->tattooer->user->name); ?></h3>
                                        <p class="text-ivoire-text/70 text-sm"><?php echo e($review->tattooer->user->pseudo ?? $review->tattooer->user->first_name . ' ' . $review->tattooer->user->last_name); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                        <span class="text-<?php echo e($i <= $review->rating ? 'beige-peau' : 'ivoire-text/30'); ?>">
                                            ⭐
                                        </span>
                                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <div class="text-ivoire-text mb-4">
                                <?php echo e($review->comment); ?>

                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->tattooer_response): ?>
                                <div class="bg-noir-profond rounded-lg p-4 border border-titane/30">
                                    <h4 class="text-sm font-semibold text-beige-peau mb-2">Réponse du tattooer</h4>
                                    <p class="text-ivoire-text/70 text-sm"><?php echo e($review->tattooer_response); ?></p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="text-ivoire-text/50 text-xs">
                                Avis laissé le <?php echo e($review->created_at->format('d/m/Y à H:i')); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client\reviews.blade.php ENDPATH**/ ?>