<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
    <div class="max-w-4xl w-full">
        <!-- Header Studio -->
        <div class="relative">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->banner_url): ?>
                <img src="<?php echo e($studio->banner_url); ?>" alt="<?php echo e($studio->name); ?>" class="w-full h-48 object-cover">
            <?php else: ?>
                <div class="h-48 bg-gris-fonde flex items-center justify-center">
                    <h1 class="text-3xl font-bold text-beige-peau"><?php echo e($studio->name); ?></h1>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Contenu Studio -->
        <div class="bg-gris-fonde rounded-xl p-8 mt-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Infos Studio -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-noir-profond rounded-xl p-6 border border-ivoire-text/20">
                        <h2 class="text-xl font-bold text-beige-peau mb-4"><?php echo e($studio->name); ?></h2>
                        <p class="text-ivoire-text/70"><?php echo e($studio->bio); ?></p>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->address): ?>
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold text-beige-peau mb-2">Adresse</h3>
                                <p class="text-ivoire-text">
                                    <?php echo e($studio->address); ?><br>
                                    <?php echo e($studio->postal_code); ?> <?php echo e($studio->city); ?><br>
                                    <?php echo e($studio->country); ?>

                                </p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->phone): ?>
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold text-beige-peau mb-2">Contact</h3>
                                <p class="text-ivoire-text">
                                    <?php echo e($studio->phone); ?><br>
                                    <?php echo e($studio->email); ?>

                                </p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->website): ?>
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold text-beige-peau mb-2">Site web</h3>
                                <a href="<?php echo e($studio->website); ?>" target="_blank" class="text-beige-peau hover:underline"><?php echo e($studio->website); ?></a>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->opening_hours): ?>
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold text-beige-peau mb-2">Horaires</h3>
                                <div class="text-ivoire-text">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $studio->opening_hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $hours): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex justify-between">
                                            <span class="font-semibold"><?php echo e($day); ?></span>
                                            <span><?php echo e($hours); ?></span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                
                <!-- Artistes du Studio -->
                <div class="lg:col-span-1">
                    <h2 class="text-xl font-bold text-beige-peau mb-4">Nos Artistes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $studio->artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist->user): ?>
                                <div class="bg-noir-profond rounded-lg p-4 border border-ivoire-text/20">
                                    <div class="flex items-center space-x-4">
                                        <img src="<?php echo e($artist->user->getMedia('avatar')->getUrl()); ?>" alt="<?php echo e($artist->user->name); ?>" class="w-16 h-16 rounded-full object-cover">
                                        <div>
                                            <h3 class="text-lg font-semibold text-beige-peau"><?php echo e($artist->user->name); ?></h3>
                                            <p class="text-ivoire-text/70"><?php echo e($artist->role); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\marketplace\studio-show.blade.php ENDPATH**/ ?>