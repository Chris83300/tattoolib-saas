<?php if (isset($component)) { $__componentOriginalb525200bfa976483b4eaa0b7685c6e24 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-widgets::components.widget','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-widgets::widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => ['heading' => 'Détail revenus plateforme','icon' => 'heroicon-o-banknotes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Détail revenus plateforme','icon' => 'heroicon-o-banknotes']); ?>
        <?php $data = $this->getData(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    📋 Abonnements actifs
                </h4>

                <div class="space-y-2">
                    
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">STARTER</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                <?php echo e($data['subscriptions']['starter_count']); ?>

                            </span>
                            <span class="text-xs text-gray-500 ml-1">
                                × 9,99€ = <?php echo e(number_format($data['subscriptions']['starter_count'] * 9.99, 2, ',', ' ')); ?>€
                            </span>
                        </div>
                    </div>

                    
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-primary-500"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">PRO</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                <?php echo e($data['subscriptions']['pro_count']); ?>

                            </span>
                            <span class="text-xs text-gray-500 ml-1">
                                × 29,99€ = <?php echo e(number_format($data['subscriptions']['pro_count'] * 29.99, 2, ',', ' ')); ?>€
                            </span>
                        </div>
                    </div>

                    
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">STUDIO</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                <?php echo e($data['subscriptions']['studio_count']); ?>

                            </span>
                            <span class="text-xs text-gray-500 ml-1">
                                × 59,99€ = <?php echo e(number_format($data['subscriptions']['studio_count'] * 59.99, 2, ',', ' ')); ?>€
                            </span>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['subscriptions']['studio_extra_count'] > 0): ?>
                        
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-300"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Artistes supp.</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    <?php echo e($data['subscriptions']['studio_extra_count']); ?>

                                </span>
                                <span class="text-xs text-gray-500 ml-1">
                                    × 24,99€ = <?php echo e(number_format($data['subscriptions']['studio_extra_count'] * 24.99, 2, ',', ' ')); ?>€
                                </span>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div class="flex items-center justify-between p-3 rounded-lg bg-primary-50 dark:bg-primary-500/10 border border-primary-200 dark:border-primary-500/20 mt-2">
                        <span class="text-sm font-semibold text-primary-700 dark:text-primary-400">MRR Total</span>
                        <span class="text-lg font-bold text-primary-700 dark:text-primary-400">
                            <?php echo e(number_format($data['subscriptions']['mrr'], 2, ',', ' ')); ?> €/mois
                        </span>
                    </div>

                    <p class="text-xs text-gray-400 mt-1">
                        ARR estimé : <?php echo e(number_format($data['subscriptions']['arr'], 2, ',', ' ')); ?> €/an
                    </p>
                </div>
            </div>

            
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    💰 Revenus commissions
                </h4>

                <div class="space-y-2">
                    
                    <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Commissions 7% (total)</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                <?php echo e(number_format($data['commissions']['total'], 2, ',', ' ')); ?> €
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            <?php echo e($data['commissions']['count']); ?> transactions commissionnées
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['commissions']['avg'] > 0): ?>
                                · moy. <?php echo e(number_format($data['commissions']['avg'], 2, ',', ' ')); ?> €/tx
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>

                    
                    <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Commissions ce mois</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                <?php echo e(number_format($data['commissions_trend']['this_month'], 2, ',', ' ')); ?> €
                            </span>
                        </div>
                        <div class="flex items-center gap-1 mt-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['commissions_trend']['change_pct'] >= 0): ?>
                                <span class="text-xs font-semibold text-success-600 dark:text-success-400">
                                    ↑ +<?php echo e($data['commissions_trend']['change_pct']); ?>%
                                </span>
                            <?php else: ?>
                                <span class="text-xs font-semibold text-danger-600 dark:text-danger-400">
                                    ↓ <?php echo e($data['commissions_trend']['change_pct']); ?>%
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <span class="text-xs text-gray-400">vs mois dernier (<?php echo e(number_format($data['commissions_trend']['last_month'], 2, ',', ' ')); ?> €)</span>
                        </div>
                    </div>
                </div>

                
                <div class="mt-4 p-4 rounded-xl bg-success-50 dark:bg-success-500/10 border border-success-200 dark:border-success-500/20">
                    <p class="text-xs font-medium text-success-700 dark:text-success-400 mb-1">
                        💎 CA TOTAL PLATEFORME
                    </p>
                    <p class="text-2xl font-bold text-success-700 dark:text-success-400">
                        <?php echo e(number_format($data['commissions']['total'] + $data['subscriptions']['mrr'], 2, ',', ' ')); ?> €
                    </p>
                    <p class="text-xs text-success-600/70 dark:text-success-400/70 mt-1">
                        Commissions (<?php echo e(number_format($data['commissions']['total'], 2, ',', ' ')); ?> €)
                        + MRR (<?php echo e(number_format($data['subscriptions']['mrr'], 2, ',', ' ')); ?> €/mois)
                    </p>
                </div>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $attributes = $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $component = $__componentOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/filament/admin/widgets/revenue-detail.blade.php ENDPATH**/ ?>