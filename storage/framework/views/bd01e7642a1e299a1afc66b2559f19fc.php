<?php $__env->startSection('title', 'Détails de la demande'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-6">
                <a href="<?php echo e(route('client.booking-requests')); ?>"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à mes demandes
                </a>

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-ivoire-text">Détails de la demande</h1>
                        <p class="text-ivoire-text/70 mt-1">Artiste: <span
                                class="font-semibold text-cuivre"><?php echo e($bookingRequest->bookable->user->pseudo); ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Détails du projet -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h2 class="text-xl font-bold text-ivoire-text mb-4">Détails du projet</h2>

                        <div class="space-y-4">
                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Description :</span>
                                <p class="text-ivoire-text"><?php echo e($bookingRequest->description ?: 'Non définie'); ?></p>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable_type === 'App\\Models\\Piercer'): ?>
                                <!-- Champs spécifiques aux piercings -->
                                <div class="bg-noir-profond/30 rounded-lg p-4">
                                    <h4 class="text-ivoire-text font-semibold mb-3">💍 Détails du piercing</h4>
                                    <div class="space-y-2 text-sm">
                                        
                                        <?php
                                            $description = $bookingRequest->description;
                                            $type = '';
                                            $precision = '';
                                            $specialRequest = '';

                                            // Extraire les informations de la description formatée
                                            if (preg_match('/Type\s*:\s*([^\n]+)/', $description, $matches)) {
                                                $type = trim($matches[1]);
                                            }
                                            if (preg_match('/Précisions\s*:\s*([^\n]+)/', $description, $matches)) {
                                                $precision = trim($matches[1]);
                                            }
                                            if (
                                                preg_match(
                                                    '/Demande spécifique\s*:\s*([^\n]+)/',
                                                    $description,
                                                    $matches,
                                                )
                                            ) {
                                                $specialRequest = trim($matches[1]);
                                            }
                                        ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($type): ?>
                                            <div class="flex justify-between">
                                                <span class="text-ivoire-text/60">Type :</span>
                                                <span class="text-ivoire-text font-semibold"><?php echo e($type); ?></span>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($precision): ?>
                                            <div class="flex justify-between">
                                                <span class="text-ivoire-text/60">Précisions :</span>
                                                <span class="text-ivoire-text font-semibold"><?php echo e($precision); ?></span>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($specialRequest): ?>
                                            <div>
                                                <span class="text-ivoire-text/60 block mb-1">Demande spécifique :</span>
                                                <span class="text-ivoire-text"><?php echo e($specialRequest); ?></span>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($type && $bookingRequest->bookable && method_exists($bookingRequest->bookable, 'getPricingForType')): ?>
                                            <?php
                                                $price = $bookingRequest->bookable->getPricingForType($type);
                                            ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($price): ?>
                                                <div class="mt-3 pt-3 border-t border-titane/30">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-ivoire-text/60">Tarif :</span>
                                                        <span
                                                            class="text-beige-peau font-bold text-lg"><?php echo e(number_format($price, 2, ',', ' ')); ?>

                                                            €</span>
                                                    </div>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Champs spécifiques aux tatouages -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-ivoire-text/70 block mb-1">Emplacement :</span>
                                        <span
                                            class="text-ivoire-text font-semibold"><?php echo e($bookingRequest->body_zone ?: 'Non défini'); ?></span>
                                    </div>
                                    <div>
                                        <span class="text-ivoire-text/70 block mb-1">Taille :</span>
                                        <span
                                            class="text-ivoire-text font-semibold"><?php echo e($bookingRequest->tattoo_size ?: 'Non défini'); ?></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-ivoire-text/70 block mb-1">Prix estimé :</span>
                                        <span class="text-ivoire-text font-semibold">
                                            <?php echo e($bookingRequest->estimated_total_price ? number_format($bookingRequest->estimated_total_price, 2, ',', ' ') . ' €' : 'Non défini'); ?>

                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-ivoire-text/70 block mb-1">Date souhaitée :</span>
                                        <span class="text-ivoire-text font-semibold">
                                            <?php echo e($bookingRequest->preferred_date ? $bookingRequest->preferred_date->format('d/m/Y') : 'Non définie'); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Statut :</span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    <?php if($bookingRequest->status->value === 'pending'): ?> bg-jaune-alerte/20 text-jaune-alerte border border-jaune-alerte/30
                                    <?php elseif($bookingRequest->status->value === 'accepted'): ?> bg-beige-peau/20 text-beige-peau border border-beige-peau/30
                                    <?php elseif($bookingRequest->status->value === 'deposit_requested'): ?> bg-ambre-warning/20 text-ambre-warning border border-ambre-warning/30
                                    <?php elseif($bookingRequest->status->value === 'deposit_paid'): ?> bg-vert-succes/20 text-vert-succes border border-vert-succes/30
                                    <?php elseif($bookingRequest->status->value === 'date_confirmed'): ?> bg-beige-peau/20 text-beige-peau border border-beige-peau/30
                                    <?php elseif($bookingRequest->status->value === 'completed'): ?> bg-vert-succes/20 text-vert-succes border border-vert-succes/30
                                    <?php elseif($bookingRequest->status->value === 'rejected'): ?> bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30
                                    <?php elseif($bookingRequest->status->value === 'cancelled'): ?> bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30
                                    <?php elseif($bookingRequest->status->value === 'expired'): ?> bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30
                                    <?php elseif($bookingRequest->status->value === 'no_show'): ?> bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30 <?php endif; ?>">
                                    <?php echo e($bookingRequest->status->label()); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Images de référence -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->getMedia('reference_images')->isNotEmpty()): ?>
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h2 class="text-xl font-bold text-ivoire-text mb-4">Images de référence</h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequest->getMedia('reference_images'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond">
                                        <img src="<?php echo e($media->getUrl()); ?>" alt="Référence"
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-300 cursor-pointer"
                                            onclick="openLightbox('<?php echo e($media->getUrl()); ?>')">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



                    <!-- SECTION ACTIONS -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($bookingRequest->status->value, [
                            \App\Enums\BookingRequestStatus::ACCEPTED->value,
                            \App\Enums\BookingRequestStatus::DEPOSIT_REQUESTED->value,
                        ]) && !$bookingRequest->deposit_paid_at): ?>
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>
                            <div class="space-y-3">

                                <!-- BOUTON PAYER ACOMPTE -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->total_deposit_amount && $bookingRequest->total_deposit_amount > 0): ?>
                                    <a href="<?php echo e(route('deposit.payment', $bookingRequest)); ?>"
                                        class="block w-full px-4 py-3 bg-vert-succes text-noir-profond rounded-xl font-bold text-center hover:bg-vert-succes/90 transition-all">
                                        💰 Payer l'acompte —
                                        <?php echo e(number_format($bookingRequest->total_deposit_amount, 2, ',', ' ')); ?> €
                                    </a>

                                    <!-- Délai paiement -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->client_payment_deadline): ?>
                                        <p class="text-ivoire-text/60 text-sm text-center">
                                            Délai : jusqu'au
                                            <?php echo e($bookingRequest->client_payment_deadline->format('d/m/Y')); ?>

                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- BOUTON ANNULER -->
                                <form action="<?php echo e(route('client.booking-request.cancel', $bookingRequest)); ?>"
                                    method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                        onclick="return confirm('Annuler cette demande ? Cette action est irréversible.')"
                                        class="block w-full px-4 py-3 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl font-semibold text-center hover:bg-rouge-alerte/30 transition-all">
                                        ✕ Annuler la demande
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Si acompte déjà payé, montrer le chat + option annulation différente -->
                    <?php elseif($bookingRequest->deposit_paid_at): ?>
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>
                            <div class="space-y-3">
                                <!-- Acompte payé - badge confirmation -->
                                <div
                                    class="bg-vert-succes/10 border border-vert-succes/30 rounded-lg px-4 py-2 text-center">
                                    <span class="text-vert-succes font-semibold">✓ Acompte payé</span>
                                </div>

                                <!-- Chat avec l'artiste -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->conversation): ?>
                                    <a href="<?php echo e(route('client.chat', $bookingRequest->conversation)); ?>"
                                        class="block w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-xl font-bold text-center hover:bg-beige-peau/90 transition-all">
                                        💬 Discuter avec l'artiste
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Annulation après acompte -->
                                <form action="<?php echo e(route('client.booking-request.cancel', $bookingRequest)); ?>"
                                    method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                        onclick="return confirm('Annuler après paiement d\'acompte implique des conditions de remboursement. Continuer ?')"
                                        class="block w-full px-4 py-3 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl font-semibold text-center hover:bg-rouge-alerte/30 transition-all">
                                        ✕ Annuler la demande
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Colonne latérale -->
                <div class="space-y-6">
                    <!-- Informations artiste -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h3 class="text-lg font-bold text-ivoire-text mb-4">Artiste</h3>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-beige-peau/10">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable->user->getFirstMediaUrl('avatar')): ?>
                                    <img src="<?php echo e($bookingRequest->bookable->user->getFirstMediaUrl('avatar')); ?>"
                                        alt="Avatar de <?php echo e($bookingRequest->bookable->user->pseudo); ?>"
                                        class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-beige-peau rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-noir-profond" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <p class="font-semibold text-ivoire-text"><?php echo e($bookingRequest->bookable->user->pseudo); ?></p>
                                <p class="text-sm text-ivoire-text/70">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable_type === 'App\\Models\\Tattooer'): ?>
                                        Tatoueur indépendant
                                    <?php elseif($bookingRequest->bookable_type === 'App\\Models\\StudioArtist'): ?>
                                        Artiste de studio
                                    <?php elseif($bookingRequest->bookable_type === 'App\\Models\\Piercer'): ?>
                                        Piercer professionnel
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable->studio_name): ?>
                            <div class="mb-3">
                                <span class="text-ivoire-text/70 text-sm">Studio :</span>
                                <p class="text-ivoire-text font-semibold"><?php echo e($bookingRequest->bookable->studio_name); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable->specialties && $bookingRequest->bookable->specialties->isNotEmpty()): ?>
                            <div>
                                <span class="text-ivoire-text/70 text-sm">Spécialités :</span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequest->bookable->specialties->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specialty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="px-2 py-1 bg-beige-peau/20 text-beige-peau text-xs rounded-full">
                                            <?php echo e($specialty->name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <!-- PROPOSITION TATTOOER — visible dès que status >= accepted -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($bookingRequest->status->value, [
                            \App\Enums\BookingRequestStatus::ACCEPTED->value,
                            \App\Enums\BookingRequestStatus::DEPOSIT_REQUESTED->value,
                            \App\Enums\BookingRequestStatus::DEPOSIT_PAID->value,
                            \App\Enums\BookingRequestStatus::DATE_CONFIRMED->value,
                            \App\Enums\BookingRequestStatus::COMPLETED->value,
                        ])): ?>
                        <div
                            class="bg-gradient-to-br from-vert-succes/10 to-vert-succes/5 rounded-xl p-6 border border-vert-succes/30">
                            <h3 class="text-xl font-bold text-ivoire-text mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-vert-succes" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Proposition du <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable_type === 'App\\Models\\Piercer'): ?>
                                    piercer
                                <?php else: ?>
                                    tattooer
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </h3>

                            <div class="space-y-4">
                                <!-- 💰 Fourchette prix -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable_type === 'App\\Models\\Piercer' && $bookingRequest->total_deposit_amount): ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💰 Tarif</h4>
                                        <p class="text-ivoire-text">
                                            <span
                                                class="font-bold text-beige-peau"><?php echo e(number_format($bookingRequest->total_deposit_amount, 2, ',', ' ')); ?>€</span>
                                        </p>
                                    </div>
                                <?php elseif($bookingRequest->price_estimate_min || $bookingRequest->price_estimate_max): ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💰 Tarif estimé</h4>
                                        <p class="text-ivoire-text">
                                            Entre <span
                                                class="font-bold text-beige-peau"><?php echo e(number_format($bookingRequest->price_estimate_min, 2, ',', ' ')); ?>

                                                €</span>
                                            et <span
                                                class="font-bold text-beige-peau"><?php echo e(number_format($bookingRequest->price_estimate_max, 2, ',', ' ')); ?>

                                                €</span>
                                        </p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->price_estimate_max): ?>
                                            <p class="text-ivoire-text/60 text-sm mt-1">
                                                Estimation finale : <span
                                                    class="text-ivoire-text font-semibold"><?php echo e(number_format($bookingRequest->price_estimate_max, 2, ',', ' ')); ?>

                                                    €</span>
                                            </p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- 📅 Sélection de dates -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                    $bookingRequest->status->value === 'deposit_paid' &&
                                        $bookingRequest->proposed_dates &&
                                        !$bookingRequest->confirmed_date): ?>

                                    <div class="bg-gris-fonde rounded-xl p-4 border border-beige-peau/20 mt-4">
                                        <h4 class="font-semibold text-ivoire-text mb-2">📅 Choisissez votre date de
                                            rendez-vous</h4>
                                        <p class="text-sm text-titane mb-4">
                                            L'artiste vous propose <?php echo e(count($bookingRequest->proposed_dates)); ?> date(s).
                                            Sélectionnez celle qui vous convient — l'artiste fixera ensuite l'horaire exact.
                                        </p>

                                        <div class="space-y-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequest->proposed_dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $proposalDate = \Carbon\Carbon::parse($proposal['date']);
                                                    $periodLabel = match ($proposal['period'] ?? '') {
                                                        'morning' => '☀️ Matin',
                                                        'afternoon' => '🌤️ Après-midi',
                                                        'evening' => '🌙 Soirée',
                                                        default => '🔄 Flexible',
                                                    };
                                                    $medal = match ($index) {
                                                        0 => '🥇',
                                                        1 => '🥈',
                                                        2 => '🥉',
                                                        default => '📅',
                                                    };
                                                ?>

                                                <form
                                                    action="<?php echo e(route('client.booking-request.select-date', $bookingRequest)); ?>"
                                                    method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="index" value="<?php echo e($index); ?>">
                                                    <button type="submit"
                                                        onclick="return confirm('Confirmer la date du <?php echo e($proposalDate->translatedFormat('l d F Y')); ?> (<?php echo e(strip_tags($periodLabel)); ?>) ?')"
                                                        class="w-full flex items-center justify-between p-4 rounded-lg border border-titane/30
                                                               hover:border-beige-peau hover:bg-beige-peau/10 cursor-pointer transition-all">
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-2xl"><?php echo e($medal); ?></span>
                                                            <div class="text-left">
                                                                <p class="text-ivoire-text font-medium">
                                                                    <?php echo e($proposalDate->translatedFormat('l d F Y')); ?>

                                                                </p>
                                                                <p class="text-xs text-titane"><?php echo e($periodLabel); ?></p>
                                                            </div>
                                                        </div>
                                                        <span class="text-beige-peau font-bold text-sm">Choisir →</span>
                                                    </button>
                                                </form>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>

                                        <form
                                            action="<?php echo e(route('client.booking-request.request-alternatives', $bookingRequest)); ?>"
                                            method="POST" class="mt-3">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit"
                                                class="text-xs text-titane underline hover:text-ivoire-text">
                                                Aucune date ne me convient — demander d'autres propositions
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Date confirmée (en attente de fixation horaire par le tattooer) -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->confirmed_date && !$bookingRequest->appointment_datetime): ?>
                                    <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-4 mt-4">
                                        <h4 class="font-semibold text-vert-succes mb-1">✅ Date choisie</h4>
                                        <p class="text-ivoire-text">
                                            <?php echo e(\Carbon\Carbon::parse($bookingRequest->confirmed_date)->translatedFormat('l d F Y')); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->confirmed_period): ?>
                                                —
                                                <?php echo e(match ($bookingRequest->confirmed_period) {
                                                    'morning' => '☀️ Matin',
                                                    'afternoon' => '🌤️ Après-midi',
                                                    'evening' => '🌙 Soirée',
                                                    default => '',
                                                }); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                        <p class="text-xs text-titane mt-1">⏳ L'artiste va fixer l'horaire exact. Vous
                                            serez notifié.</p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- RDV confirmé avec horaire -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->appointment_datetime): ?>
                                    <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-4 mt-4">
                                        <h4 class="font-semibold text-vert-succes mb-1">✅ Rendez-vous confirmé</h4>
                                        <p class="text-ivoire-text">
                                            <?php echo e(\Carbon\Carbon::parse($bookingRequest->appointment_datetime)->translatedFormat('l d F Y')); ?>

                                            de <?php echo e($bookingRequest->scheduled_start_time); ?> à
                                            <?php echo e($bookingRequest->scheduled_end_time); ?>

                                        </p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Affichage simple si statut différent -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                    $bookingRequest->status !== 'deposit_paid' &&
                                        $bookingRequest->proposed_dates &&
                                        is_array($bookingRequest->proposed_dates) &&
                                        count($bookingRequest->proposed_dates) > 0): ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">📅 Dates proposées</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequest->proposed_dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateProposed): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span
                                                    class="px-3 py-1 bg-beige-peau/20 text-beige-peau rounded-full text-sm font-medium">
                                                    <?php echo e(\Carbon\Carbon::parse($dateProposed['date'])->format('l d/m/Y')); ?>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dateProposed['period']): ?>
                                                        -
                                                        <?php echo e($dateProposed['period'] === 'morning' ? 'Matin' : 'Après-midi'); ?>

                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- 🎨 Phase création -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->included_design_versions && $bookingRequest->bookable_type !== 'App\\Models\\Piercer'): ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">🎨 Phase création</h4>
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <span class="text-ivoire-text/60">Dessins inclus :</span>
                                                <span
                                                    class="text-ivoire-text font-semibold ml-2"><?php echo e($bookingRequest->included_design_versions); ?></span>
                                            </div>
                                            <div>
                                                <span class="text-ivoire-text/60">Modifs/dessin :</span>
                                                <span
                                                    class="text-ivoire-text font-semibold ml-2"><?php echo e($bookingRequest->modifications_per_design ?? 2); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Acompte -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->total_deposit_amount): ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text mb-2">Acompte</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-ivoire-text/60">Montant :</span>
                                                <span
                                                    class="text-ivoire-text font-semibold"><?php echo e(number_format($bookingRequest->total_deposit_amount, 2, ',', ' ')); ?>€</span>
                                            </div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->client_payment_deadline): ?>
                                                <div class="flex justify-between">
                                                    <span class="text-ivoire-text/60">Date limite :</span>
                                                    <span class="text-ivoire-text">
                                                        <?php echo e(is_string($bookingRequest->client_payment_deadline)
                                                            ? \Carbon\Carbon::parse($bookingRequest->client_payment_deadline)->format('d/m/Y')
                                                            : $bookingRequest->client_payment_deadline->format('d/m/Y')); ?>

                                                    </span>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- SECTION ACTIONS - Pour statut ACCEPTED -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status->value === \App\Enums\BookingRequestStatus::ACCEPTED->value): ?>
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>
                            <div class="space-y-3">
                                <!-- Chat avec l'artiste -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->conversation): ?>
                                    <a href="<?php echo e(route('client.chat', $bookingRequest->conversation)); ?>"
                                        class="block w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-xl font-bold text-center hover:bg-beige-peau/90 transition-all">
                                        💬 Discuter avec l'artiste
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Annulation -->
                                <form action="<?php echo e(route('client.booking-request.cancel', $bookingRequest)); ?>"
                                    method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                        onclick="return confirm('Annuler cette demande ? Cette action est irréversible.')"
                                        class="block w-full px-4 py-3 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl font-semibold text-center hover:bg-rouge-alerte/30 transition-all">
                                        ✕ Annuler la demande
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Timeline -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h3 class="text-lg font-bold text-ivoire-text mb-4">Historique</h3>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-beige-peau rounded-full mt-2"></div>
                                <div>
                                    <p class="text-ivoire-text font-semibold">Demande créée</p>
                                    <p class="text-ivoire-text/70 text-sm">
                                        <?php echo e($bookingRequest->created_at->format('d/m/Y à H:i')); ?></p>
                                </div>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->accepted_at): ?>
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-vert-succes rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-ivoire-text font-semibold">Demande acceptée</p>
                                        <p class="text-ivoire-text/70 text-sm">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->accepted_at): ?>
                                                <?php echo e(is_string($bookingRequest->accepted_at) ? \Carbon\Carbon::parse($bookingRequest->accepted_at)->format('d/m/Y à H:i') : $bookingRequest->accepted_at->format('d/m/Y à H:i')); ?>

                                            <?php else: ?>
                                                Non défini
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->cancelled_at): ?>
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-rouge-alerte rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-ivoire-text font-semibold">Demande refusée</p>
                                        <p class="text-ivoire-text/70 text-sm">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->cancelled_at): ?>
                                                <?php echo e(is_string($bookingRequest->cancelled_at) ? \Carbon\Carbon::parse($bookingRequest->cancelled_at)->format('d/m/Y à H:i') : $bookingRequest->cancelled_at->format('d/m/Y à H:i')); ?>

                                            <?php else: ?>
                                                Non défini
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox -->
    <script>
        function openLightbox(imageUrl) {
            const lightbox = document.createElement('div');
            lightbox.className = 'fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-4';
            lightbox.onclick = () => lightbox.remove();

            const img = document.createElement('img');
            img.src = imageUrl;
            img.className = 'max-w-full max-h-full object-contain rounded-lg';

            lightbox.appendChild(img);
            document.body.appendChild(lightbox);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client\booking-request-show.blade.php ENDPATH**/ ?>