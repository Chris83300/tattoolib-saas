<?php $__env->startSection('title', 'Détails de la demande'); ?>

<?php $__env->startSection('content'); ?>
    <div x-data="{ showModal: false }" x-init="$listen('booking-accepted', () => {
        window.location.reload();
    })" class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-8">
                <a href="<?php echo e(route('tattooer.requests')); ?>"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux demandes
                </a>

                <h1 class="text-3xl font-bold text-ivoire-text">Détails de la demande</h1>
            </div>

            <!-- Contenu principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Informations client -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h2 class="text-xl font-bold text-ivoire-text mb-4">Informations client</h2>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Nom complet :</span>
                                <span class="text-ivoire-text font-semibold">
                                    <?php echo e($bookingRequest->client?->first_name); ?> <?php echo e($bookingRequest->client?->last_name); ?>

                                </span>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->client?->pseudo): ?>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Pseudo :</span>
                                    <span class="text-ivoire-text"><?php echo e($bookingRequest->client->pseudo); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Email :</span>
                                <span
                                    class="text-ivoire-text"><?php echo e($bookingRequest->client?->user?->email ?: 'Non renseigné'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Téléphone :</span>
                                <span
                                    class="text-ivoire-text"><?php echo e($bookingRequest->client?->phone ?: 'Non renseigné'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Date de naissance :</span>
                                <span class="text-ivoire-text">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->client?->birth_date): ?>
                                        <?php echo e($bookingRequest->client->birth_date->format('d/m/Y')); ?>

                                        (<?php echo e($bookingRequest->client->birth_date->age); ?> ans)
                                    <?php else: ?>
                                        Non renseignée
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->client?->address): ?>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Adresse :</span>
                                    <span class="text-ivoire-text"><?php echo e($bookingRequest->client->address); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- Détails du projet -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h2 class="text-xl font-bold text-ivoire-text mb-4">Détails du projet</h2>

                        <div class="space-y-4">
                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Description :</span>
                                <p class="text-ivoire-text"><?php echo e($bookingRequest->description); ?></p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Emplacement :</span>
                                    <span class="text-ivoire-text font-semibold"><?php echo e($bookingRequest->body_zone); ?></span>
                                </div>
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Taille :</span>
                                    <span class="text-ivoire-text font-semibold"><?php echo e($bookingRequest->tattoo_size); ?></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Budget estimé :</span>
                                    <span
                                        class="text-ivoire-text font-semibold"><?php echo e($bookingRequest->estimated_total_price ? number_format($bookingRequest->estimated_total_price, 2, ',', ' ') . ' €' : 'Non défini'); ?></span>
                                </div>
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Date souhaitée :</span>
                                    <span class="text-ivoire-text font-semibold">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->preferred_date): ?>
                                            <?php echo e($bookingRequest->preferred_date->format('d/m/Y')); ?>

                                        <?php else: ?>
                                            Non définie
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Statut :</span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                <?php if($bookingRequest->status->value === 'pending'): ?> bg-jaune-alerte/20 text-jaune-alerte
                                <?php elseif($bookingRequest->status->value === 'accepted'): ?> bg-vert-succes/20 text-vert-succes
                                <?php elseif($bookingRequest->status->value === 'in_progress'): ?> bg-beige-peau/20 text-beige-peau
                                <?php elseif($bookingRequest->status->value === 'completed'): ?> bg-vert-succes/20 text-vert-succes
                                <?php elseif($bookingRequest->status->value === 'cancelled'): ?> bg-rouge-alerte/20 text-rouge-alerte <?php endif; ?>">
                                    <?php echo e(ucfirst($bookingRequest->status->value)); ?>

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
                </div>

                <!-- Colonne latérale -->
                <div class="space-y-6">
                    <!-- Actions -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status->value === 'pending'): ?>
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>

                            <div class="space-y-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status->value === 'pending'): ?>
                                    <button type="button"
                                        onclick="Livewire.dispatch('open-accept-modal', { bookingRequestId: <?php echo e($bookingRequest->id); ?> })"
                                        class="px-4 py-2 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors">
                                        ✓ Accepter
                                    </button>

                                    <form action="<?php echo e(route('tattooer.request-reject', $bookingRequest)); ?>" method="POST"
                                        class="inline">
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
                    <?php elseif(in_array($bookingRequest->status, ['accepted', 'awaiting_deposit', 'deposit_paid', 'design_sent', 'confirmed'])): ?>
                        <!-- Ici : AFFICHER les détails de la demande (infos remplies dans modal) -->
                        <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">📋 Détails de votre proposition</h3>

                            <div class="space-y-4">
                                <!-- 💰 Fourchette prix -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->price_range_min || $bookingRequest->price_range_max): ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💰 Tarif estimé</h4>
                                        <p class="text-ivoire-text">
                                            Entre <span
                                                class="font-bold text-beige-peau"><?php echo e(number_format($bookingRequest->price_range_min, 2, ',', ' ')); ?>

                                                €</span>
                                            et <span
                                                class="font-bold text-beige-peau"><?php echo e(number_format($bookingRequest->price_range_max, 2, ',', ' ')); ?>

                                                €</span>
                                        </p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- 📅 Dates proposées -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                    $bookingRequest->proposed_dates &&
                                        is_array($bookingRequest->proposed_dates) &&
                                        count($bookingRequest->proposed_dates) > 0): ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">📅 Dates proposées</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequest->proposed_dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span
                                                    class="px-3 py-1 bg-beige-peau/20 text-beige-peau rounded-full text-sm font-medium">
                                                    <?php echo e(\Carbon\Carbon::parse($date)->format('l d/m/Y')); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- 💬 Message tattooer -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->tattooer_notes): ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💬 Votre message</h4>
                                        <p class="text-ivoire-text/80 whitespace-pre-wrap">
                                            <?php echo e($bookingRequest->tattooer_notes); ?></p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Chat + Actions -->
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30 mt-4">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>
                            <div class="space-y-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->conversation): ?>
                                    <a href="<?php echo e(route('tattooer.message.show', $bookingRequest)); ?>"
                                        class="block w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-xl font-bold text-center hover:bg-beige-peau/90 transition-all">
                                        💬 Chat avec le client
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <form action="<?php echo e(route('tattooer.request-reject', $bookingRequest)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                        class="w-full px-4 py-3 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl font-semibold text-center hover:bg-rouge-alerte/30 transition-all"
                                        onclick="return confirm('Annuler ce projet ? Cette action est irréversible.')">
                                        ✕ Annuler le projet
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php elseif($bookingRequest->status->value === 'completed'): ?>
                        <!-- Afficher résumé final -->
                        <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">✅ Projet terminé</h3>
                            <p class="text-ivoire-text">Ce projet a été réalisé avec succès.</p>
                        </div>
                    <?php elseif(in_array($bookingRequest->status->value, ['cancelled', 'rejected'])): ?>
                        <!-- Afficher statut final avec raison -->
                        <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">❌ Projet annulé</h3>
                            <p class="text-ivoire-text">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->cancellation_reason): ?>
                                    Raison : <?php echo e($bookingRequest->cancellation_reason); ?>

                                <?php else: ?>
                                    Ce projet a été annulé.
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
                                Proposition faite au client
                            </h3>

                            <div class="space-y-4">
                                <!-- 💰 Fourchette prix -->
                                <div class="bg-noir-profond/50 rounded-lg p-4">
                                    <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💰 Tarif estimé</h4>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->price_estimate_min || $bookingRequest->price_estimate_max): ?>
                                        <p class="text-ivoire-text">
                                            Entre <span
                                                class="font-bold text-beige-peau"><?php echo e(number_format($bookingRequest->price_estimate_min, 2, ',', ' ')); ?>

                                                €</span>
                                            et <span
                                                class="font-bold text-beige-peau"><?php echo e(number_format($bookingRequest->price_estimate_max, 2, ',', ' ')); ?>

                                                €</span>
                                        </p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->estimated_total_price): ?>
                                            <p class="text-ivoire-text/60 text-sm mt-1">
                                                Estimation finale : <span
                                                    class="text-ivoire-text font-semibold"><?php echo e(number_format($bookingRequest->estimated_total_price, 2, ',', ' ')); ?>

                                                    €</span>
                                            </p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-ivoire-text/60">Tarif non encore défini</p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- 📅 Sélection des dates -->
                                <div class="bg-noir-profond/50 rounded-lg p-4">
                                    <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">📅 Dates proposées</h4>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->proposed_dates && !empty($bookingRequest->proposed_dates)): ?>
                                        <div class="space-y-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequest->proposed_dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-ivoire-text">
                                                        <?php echo e(\Carbon\Carbon::parse($date['date'])->format('d/m/Y')); ?>

                                                    </span>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($date['period']): ?>
                                                        <span
                                                            class="px-2 py-1 bg-beige-peau/20 text-beige-peau text-xs rounded-full">
                                                            <?php echo e($date['period'] === 'morning' ? 'Matin' : ($date['period'] === 'afternoon' ? 'Après-midi' : 'Soir')); ?>

                                                        </span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->date_selection_deadline): ?>
                                            <p class="text-ivoire-text/60 text-xs mt-2">
                                                Date limite de sélection :
                                                <?php echo e(\Carbon\Carbon::parse($bookingRequest->date_selection_deadline)->format('d/m/Y à H:i')); ?>

                                            </p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-ivoire-text/60">Dates non encore proposées</p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- 🎨 Phase création -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->included_design_versions): ?>
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
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->design_modification_rules): ?>
                                                <div class="col-span-2">
                                                    <span class="text-ivoire-text/60">Règles de modification :</span>
                                                    <p class="text-ivoire-text mt-1">
                                                        <?php echo e($bookingRequest->design_modification_rules); ?></p>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Acompte -->
                                <div class="bg-noir-profond/50 rounded-lg p-4">
                                    <h4 class="font-semibold text-ivoire-text mb-2">Acompte</h4>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->total_deposit_amount): ?>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-ivoire-text/60">Montant :</span>
                                                <span
                                                    class="text-ivoire-text font-semibold"><?php echo e(number_format($bookingRequest->total_deposit_amount, 2, ',', ' ')); ?>€</span>
                                            </div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->deposit_deadline): ?>
                                                <div class="flex justify-between">
                                                    <span class="text-ivoire-text/60">Date limite :</span>
                                                    <span class="text-ivoire-text">
                                                        <?php echo e(is_string($bookingRequest->deposit_deadline)
                                                            ? \Carbon\Carbon::parse($bookingRequest->deposit_deadline)->format('d/m/Y')
                                                            : $bookingRequest->deposit_deadline->format('d/m/Y')); ?>

                                                    </span>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->deposit_covers_description): ?>
                                                <div>
                                                    <span class="text-ivoire-text/60">Ce que couvre l'acompte :</span>
                                                    <p class="text-ivoire-text mt-1">
                                                        <?php echo e($bookingRequest->deposit_covers_description ? 'Dessin et RDV' : 'Non spécifié'); ?>

                                                    </p>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-ivoire-text/60">Acompte non encore défini</p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
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
                                            <?php echo e($bookingRequest->accepted_at->format('d/m/Y à H:i')); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->cancelled_at): ?>
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-rouge-alerte rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-ivoire-text font-semibold">Demande refusée</p>
                                        <p class="text-ivoire-text/70 text-sm">
                                            <?php echo e($bookingRequest->cancelled_at->format('d/m/Y à H:i')); ?></p>
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
    <div id="lightbox" class="fixed inset-0 z-50 bg-noir-profond/95 backdrop-blur-sm hidden" onclick="closeLightbox()">
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

        // Fermer avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>

    <!-- Modal d'acceptation Livewire -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tattooer.accept-booking-modal', []);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1363916394-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/tattooer/request-show.blade.php ENDPATH**/ ?>