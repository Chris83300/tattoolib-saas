<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('studio.artists')); ?>" class="text-titane hover:text-ivoire-text transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-ivoire-text">
                <?php echo e($studioArtist->artist_name ?: $studioArtist->user?->name ?? 'Artiste'); ?>

            </h1>
            <p class="text-sm text-beige-peau">
                <?php echo e($studioArtist->artisan_type === 'piercer' ? 'Pierceur' : 'Tatoueur'); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studioArtist->joined_at): ?> • Rejoint le <?php echo e($studioArtist->joined_at->format('d/m/Y')); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Toggle actif/inactif -->
            <form action="<?php echo e(route('studio.artists.toggle', $studioArtist)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <button type="submit"
                    class="px-3 py-1.5 text-xs rounded-lg font-semibold transition-colors
                        <?php echo e($studioArtist->is_active
                            ? 'bg-rouge-alerte/20 text-rouge-alerte hover:bg-rouge-alerte/30'
                            : 'bg-vert-validation/20 text-vert-validation hover:bg-vert-validation/30'); ?>">
                    <?php echo e($studioArtist->is_active ? 'Désactiver' : 'Activer'); ?>

                </button>
            </form>

            <!-- Retirer du studio -->
            <form action="<?php echo e(route('studio.artists.remove', $studioArtist)); ?>" method="POST"
                onsubmit="return confirm('Retirer cet artiste du studio ?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit"
                    class="px-3 py-1.5 text-xs rounded-lg font-semibold bg-titane/20 text-titane hover:bg-rouge-alerte/20 hover:text-rouge-alerte transition-colors">
                    Retirer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Infos artiste -->
        <div class="space-y-4">
            <div class="bg-gris-fonde rounded-xl p-5 space-y-3">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Informations</h2>

                <div class="flex justify-between text-sm">
                    <span class="text-titane">Statut</span>
                    <span class="<?php echo e($studioArtist->is_active ? 'text-vert-succes' : 'text-rouge-alerte'); ?> font-semibold">
                        <?php echo e($studioArtist->is_active ? 'Actif' : 'Inactif'); ?>

                    </span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studioArtist->user?->email): ?>
                    <div class="text-sm">
                        <p class="text-titane mb-0.5">Email</p>
                        <a href="mailto:<?php echo e($studioArtist->user->email); ?>" class="text-ivoire-text/80 hover:text-beige-peau transition-colors text-xs">
                            <?php echo e($studioArtist->user->email); ?>

                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studioArtist->role): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">Rôle</span>
                        <span class="text-ivoire-text"><?php echo e(ucfirst($studioArtist->role)); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Stats -->
            <div class="bg-gris-fonde rounded-xl p-5">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide mb-3">Statistiques</h2>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">Demandes reçues</span>
                        <span class="text-ivoire-text font-semibold"><?php echo e($stats['total_requests']); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">Terminées</span>
                        <span class="text-vert-succes font-semibold"><?php echo e($stats['completed_requests']); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">En attente</span>
                        <span class="<?php echo e($stats['pending_requests'] > 0 ? 'text-yellow-400' : 'text-ivoire-text'); ?> font-semibold">
                            <?php echo e($stats['pending_requests']); ?>

                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">Clients uniques</span>
                        <span class="text-ivoire-text font-semibold"><?php echo e($stats['unique_clients']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Paiements reçus -->
            <div class="bg-gris-fonde rounded-xl p-5">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide mb-3">Paiements reçus</h2>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">Acomptes encaissés</span>
                        <span class="text-ivoire-text font-semibold"><?php echo e(number_format($stats['total_deposits'], 2, ',', ' ')); ?> €</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">CA terminé</span>
                        <span class="text-beige-peau font-semibold"><?php echo e(number_format($stats['total_revenue'], 2, ',', ' ')); ?> €</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prochains RDV + Dernières demandes -->
        <div class="lg:col-span-2 space-y-4">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($upcomingAppointments->count() > 0): ?>
            <div class="bg-gris-fonde rounded-xl p-4">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide mb-3">Prochains rendez-vous</h2>
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-3 bg-noir-profond/40 rounded-lg">
                        <div>
                            <p class="text-sm text-ivoire-text"><?php echo e($appt->client?->first_name); ?> <?php echo e($appt->client?->last_name); ?></p>
                            <?php
                                $rdvDate = $appt->confirmed_date
                                    ? \Carbon\Carbon::parse($appt->confirmed_date)
                                    : ($appt->appointment_datetime ? \Carbon\Carbon::parse($appt->appointment_datetime) : null);
                            ?>
                            <p class="text-xs text-titane"><?php echo e($rdvDate?->translatedFormat('l d M Y') ?? '—'); ?></p>
                        </div>
                        <a href="<?php echo e(route('studio.demandes.show', $appt)); ?>" class="text-xs text-beige-peau hover:underline">Détails →</a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
                <div class="p-4 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">
                        Dernières demandes
                    </h2>
                    <a href="<?php echo e(route('studio.requests')); ?>" class="text-xs text-beige-peau hover:underline">
                        Toutes →
                    </a>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $status = is_object($request->status) ? $request->status->value : $request->status;
                    ?>
                    <a href="<?php echo e(route('studio.demandes.show', $request)); ?>" class="flex items-center gap-3 p-4 hover:bg-noir-profond/40 transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-ivoire-text truncate">
                                <?php echo e($request->client?->first_name); ?> <?php echo e($request->client?->last_name); ?>

                            </p>
                            <p class="text-xs text-titane mt-0.5"><?php echo e($request->created_at?->diffForHumans()); ?></p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold shrink-0
                            <?php echo e($status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : ''); ?>

                            <?php echo e(in_array($status, ['accepted', 'deposit_paid', 'date_confirmed']) ? 'bg-vert-succes/20 text-vert-succes' : ''); ?>

                            <?php echo e(in_array($status, ['completed', 'fully_completed']) ? 'bg-vert-succes/20 text-vert-succes' : ''); ?>

                            <?php echo e(in_array($status, ['cancelled', 'rejected', 'no_show']) ? 'bg-rouge-alerte/20 text-rouge-alerte' : ''); ?>

                            <?php echo e(!in_array($status, ['pending','accepted','deposit_paid','date_confirmed','completed','fully_completed','cancelled','rejected', 'no_show']) ? 'bg-titane/20 text-titane' : ''); ?>">
                            <?php echo e(str_replace('_', ' ', ucfirst($status))); ?>

                        </span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-titane text-center py-8">Aucune demande</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\artist-show.blade.php ENDPATH**/ ?>