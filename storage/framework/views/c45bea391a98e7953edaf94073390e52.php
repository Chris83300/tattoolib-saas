<?php $__env->startSection('title', 'Demandes de projet'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tattooer.accept-booking-modal', []);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3595890375-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

        <!-- Header + Onglets -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text mb-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isPiercer()): ?>
                            Demandes de piercing
                        <?php else: ?>
                            Demandes de projet
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </h1>
                    <p class="text-ivoire-text/70">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isPiercer()): ?>
                            Gérez vos demandes de piercing
                        <?php else: ?>
                            Gérez vos demandes de réservation
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- ONGLETS -->
            <div class="flex gap-2 border-b border-titane/30 pb-4 mb-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            'pending' => 'En attente',
            'accepted' => 'Acceptées',
            'confirmed' => 'Confirmées',
            'completed' => 'Terminées',
            'expired' => 'Expirées',
            'cancelled' => 'Annulées',
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route($tattooer->routePrefix() . '.requests')); ?>?status=<?php echo e($key); ?>"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-all relative
                              <?php echo e($filter === $key ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text/60 hover:text-ivoire-text'); ?>">
                        <?php echo e($label); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($counts[$key] ?? 0) > 0): ?>
                            <span
                                class="ml-2 bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs"><?php echo e($counts[$key]); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Filtres -->
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="search-client" placeholder="Rechercher un client..."
                        class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:outline-none">
                </div>

                <select id="filter-status"
                    class="px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                    <option value="">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="accepted">Acceptées</option>
                    <option value="rejected">Refusées</option>
                    <option value="expired">Expirées</option>
                    <option value="cancelled">Annulées</option>
                </select>
            </div>
        </div>

        <!-- Stats rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
                $statusCounts = $requests->groupBy('status')->map->count();
            ?>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-ambre-warning mb-1">
                    <?php echo e($statusCounts->get('pending', 0)); ?>

                </div>
                <div class="text-ivoire-text/60 text-xs">En attente</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-beige-peau mb-1">
                    <?php echo e($statusCounts->get('accepted', 0)); ?>

                </div>
                <div class="text-ivoire-text/60 text-xs">Acceptées</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-vert-succes mb-1">
                    <?php echo e($statusCounts->get('completed', 0)); ?>

                </div>
                <div class="text-ivoire-text/60 text-xs">Terminées</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-rouge-alerte mb-1">
                    <?php echo e($statusCounts->get('cancelled', 0)); ?>

                </div>
                <div class="text-ivoire-text/60 text-xs">Annulées</div>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-gris-fonde rounded-xl p-6 border border-titane/30 hover:border-beige-peau/30 transition-colors"
                    data-status="<?php echo e($request->status); ?>">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Image client -->
                        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-cuivre/60 bg-beige-peau/10">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->client->user && $request->client->user->hasMedia('avatar')): ?>
                                <img src="<?php echo e($request->client->user->getFirstMediaUrl('avatar')); ?>"
                                    alt="Avatar de <?php echo e($request->client->first_name); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-ivoire-text/40">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Infos demande -->
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2 mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-ivoire-text mb-1">
                                        <?php echo e($request->client->pseudo); ?>

                                    </h3>
                                    <p class="text-ivoire-text/70 text-sm">
                                        <?php echo e($request->client->user->email); ?> • <?php echo e($request->client->phone); ?>

                                    </p>
                                </div>

                                <!-- Badge statut -->
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold inline-block
                            <?php echo e(match ($request->status->value) {
                                'pending' => 'bg-ambre-warning/20 text-ambre-warning',
                                'accepted' => 'bg-beige-peau/20 text-beige-peau',
                                'rejected' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                'completed' => 'bg-vert-succes/20 text-vert-succes',
                                'cancelled' => 'bg-titane/20 text-ivoire-text',
                                default => 'bg-titane/20 text-ivoire-text/60',
                            }); ?>">
                                    <?php echo e(match ($request->status->value) {
                                        'pending' => '⏳ En attente',
                                        'accepted' => '✓ Acceptée',
                                        'rejected' => '✕ Refusée',
                                        'completed' => '✅ Terminée',
                                        'cancelled' => '❌ Annulée',
                                        default => $request->status->value,
                                    }); ?>

                                </span>
                            </div>

                            <!-- Description projet -->
                            <div class="mb-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isPiercer()): ?>
                                    <?php
                                        $descriptionLines = explode("\n", $request->description);
                                        $typeLine = collect($descriptionLines)->first(
                                            fn($line) => str_contains($line, 'Type :'),
                                        );
                                        $precisionsLine = collect($descriptionLines)->first(
                                            fn($line) => str_contains($line, 'Précisions :'),
                                        );
                                        $specialRequestLine = collect($descriptionLines)->first(
                                            fn($line) => str_contains($line, 'Demande spécifique :'),
                                        );
                                    ?>
                                    <p class="text-ivoire-text/80 line-clamp-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($typeLine): ?>
                                            <strong>Type de piercing :</strong> <?php echo e(str_replace('Type : ', '', $typeLine)); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($precisionsLine): ?>
                                            <br><strong>Précisions :</strong>
                                            <?php echo e(str_replace('Précisions : ', '', $precisionsLine)); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($specialRequestLine): ?>
                                            <br><strong>Demande spécifique :</strong>
                                            <?php echo e(str_replace('Demande spécifique : ', '', $specialRequestLine)); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                <?php else: ?>
                                    <p class="text-ivoire-text/80 line-clamp-2">
                                        <strong>Projet :</strong> <?php echo e($request->description); ?>

                                    </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Détails -->
                            <div class="flex flex-wrap gap-4 text-sm text-ivoire-text/60 mb-4">
                                <span>📍 <?php echo e($request->body_zone); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isPiercer()): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->total_deposit_amount): ?>
                                        <span>� Acompte :
                                            <?php echo e(number_format($request->total_deposit_amount, 2, ',', ' ')); ?>€</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                    <span>� <?php echo e($request->tattoo_size); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->estimated_total_price): ?>
                                        <span>💰 <?php echo e(number_format($request->estimated_total_price, 2, ',', ' ')); ?>€</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->preferred_date): ?>
                                    <span>📅 <?php echo e($request->preferred_date->format('d/m/Y')); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span>🕒 <?php echo e($request->created_at->diffForHumans()); ?></span>
                            </div>

                            <!-- Images référence (si présentes) -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->getMedia('reference_images')->isNotEmpty()): ?>
                                <div class="flex gap-2 mb-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $request->getMedia('reference_images')->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img src="<?php echo e($media->getUrl()); ?>" alt="Référence"
                                            class="w-16 h-16 rounded-lg object-cover cursor-pointer hover:ring-2 hover:ring-beige-peau"
                                            onclick="openLightbox('<?php echo e($media->getUrl()); ?>')">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($request->getMedia('reference_images')->count() > 4): ?>
                                        <div
                                            class="w-16 h-16 rounded-lg bg-noir-profond flex items-center justify-center text-ivoire-text/60 text-xs">
                                            +<?php echo e($request->getMedia('reference_images')->count() - 4); ?>

                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2">
                                <a href="<?php echo e(route($tattooer->routePrefix() . '.request.show', $request)); ?>"
                                    class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                    Voir détails
                                </a>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->status->value === 'pending'): ?>
                                    <button type="button"
                                        onclick="Livewire.dispatch('open-accept-modal', { bookingRequestId: <?php echo e($request->id); ?> })"
                                        class="px-4 py-2 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors">
                                        ✓ Accepter
                                    </button>

                                    <form action="<?php echo e(route($tattooer->routePrefix() . '.request-reject', $request)); ?>"
                                        method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                            class="px-4 py-2 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-lg font-semibold hover:bg-rouge-alerte/30 transition-colors"
                                            onclick="return confirm('Refuser cette demande ?')">
                                            ✕ Refuser
                                        </button>
                                    </form>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-gris-fonde rounded-xl p-12 text-center">
                    <div class="text-6xl mb-4">📭</div>
                    <h3 class="text-xl font-semibold text-ivoire-text mb-2">Aucune demande</h3>
                    <p class="text-ivoire-text/60">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isPiercer()): ?>
                            Vous n'avez pas encore reçu de demandes de piercing.
                        <?php else: ?>
                            Vous n'avez pas encore reçu de demandes de projet.
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Lightbox -->
    <div id="lightbox" class="fixed inset-0 z-50 bg-noir-profond/95 backdrop-blur-sm hidden" onclick="closeLightbox()">
        <button class="absolute top-4 right-4 text-ivoire-text hover:text-rouge-alerte text-4xl"
            onclick="closeLightbox()">×</button>
        <div class="flex items-center justify-center h-full p-4">
            <img id="lightbox-image" src="" alt="Image agrandie"
                class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <script>
        function openLightbox(imageUrl) {
            document.getElementById('lightbox-image').src = imageUrl;
            document.getElementById('lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });

        // Filtrage
        document.getElementById('filter-status').addEventListener('change', function() {
            const status = this.value;
            const cards = document.querySelectorAll('[data-status]');

            cards.forEach(card => {
                if (!status || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Recherche
        document.getElementById('search-client').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            const cards = document.querySelectorAll('.bg-gris-fonde.rounded-xl.p-6');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(search)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/tattooer/requests.blade.php ENDPATH**/ ?>