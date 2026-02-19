<div class="min-h-screen bg-noir-profond">

    <!-- Container principal -->
    <div class="container mx-auto px-4 py-8 max-w-4xl">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-beige-peau font-display text-2xl font-bold">
                Mes réservations
            </h1>
            <p class="text-ivoire-text/70 text-sm mt-2">
                Gérez vos rendez-vous et demandes de réservation
            </p>
        </div>

        <!-- Liste des réservations -->
        <div class="space-y-4">

            <?php
                $bookingRequests = \App\Models\BookingRequest::where('client_id', auth()->user()->client->id)
                    ->with(['bookable.user', 'appointment'])
                    ->latest('created_at')
                    ->get();
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequests->isEmpty()): ?>
                <!-- État vide -->
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-noir-profond rounded-full mb-4">
                        <svg class="w-8 h-8 text-ivoire-text/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">Aucune réservation</h3>
                    <p class="text-ivoire-text/70 mb-6 max-w-md mx-auto">
                        Vous n'avez pas encore de demande de tatouage en cours.
                    </p>

                    <a href="<?php echo e(route('marketplace.index')); ?>"
                        class="inline-flex items-center gap-2 bg-beige-peau text-noir-profond px-6 py-3 rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Découvrir les artistes
                    </a>
                </div>
            <?php else: ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bookingRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-gris-fonde rounded-xl p-6 border border-titane/20">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <!-- Avatar artiste -->
                                    <div
                                        class="w-12 h-12 bg-beige-peau/20 rounded-full flex items-center justify-center">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable && $bookingRequest->bookable->user->getFirstMediaUrl('avatar')): ?>
                                            <img src="<?php echo e($bookingRequest->bookable->user->getFirstMediaUrl('avatar')); ?>"
                                                alt="<?php echo e($bookingRequest->bookable->user->pseudo); ?>"
                                                class="w-12 h-12 rounded-full object-cover">
                                        <?php else: ?>
                                            <span class="text-beige-peau font-bold text-lg">
                                                <?php echo e(substr($bookingRequest->bookable->user->pseudo ?? 'Artiste', 0, 2)); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <h3 class="text-ivoire-text font-semibold">
                                            <?php echo e($bookingRequest->bookable->user->pseudo ?? 'Artiste non assigné'); ?>

                                        </h3>
                                        <p class="text-ivoire-text/70 text-sm">
                                            <?php echo e($bookingRequest->bookable_type === 'App\Models\Tattooer' ? 'Tatoueur' : 'Pierceur'); ?>

                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div>
                                        <span class="text-ivoire-text/50 text-xs">Date de demande</span>
                                        <p class="text-ivoire-text font-medium">
                                            <?php echo e($bookingRequest->created_at->format('d/m/Y')); ?></p>
                                    </div>
                                    <div>
                                        <span class="text-ivoire-text/50 text-xs">RDV</span>
                                        <p class="text-ivoire-text font-medium">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->appointment && $bookingRequest->appointment->appointment_datetime): ?>
                                                <?php echo e($bookingRequest->appointment->appointment_datetime->format('d/m/Y H:i')); ?>

                                            <?php else: ?>
                                                Non planifié
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-ivoire-text/50 text-xs">Statut</span>
                                        <span
                                            class="inline-block px-2 py-1 text-xs rounded-full font-semibold
                                                <?php switch($bookingRequest->status->value):
                                                    case ('pending'): ?>
                                                        bg-gris-fonde text-ivoire-text/80
                                                    <?php break; ?>

                                                    <?php case ('accepted'): ?>
                                                        bg-beige-peau/20 text-beige-peau border border-beige-peau/30
                                                    <?php break; ?>

                                                    <?php case ('awaiting_deposit'): ?>
                                                        bg-ambre-warning/20 text-ambre-warning border border-ambre-warning/30
                                                    <?php break; ?>

                                                    <?php case ('deposit_paid'): ?>
                                                        bg-vert-succes/20 text-vert-succes border border-vert-succes/30
                                                    <?php break; ?>

                                                    <?php case ('design_sent'): ?>
                                                        bg-titane/30 text-ivoire-text/80
                                                    <?php break; ?>

                                                    <?php case ('date_confirmed'): ?>
                                                        bg-vert-succes/20 text-vert-succes border border-vert-succes/30
                                                    <?php break; ?>

                                                    <?php case ('confirmed'): ?>
                                                        bg-vert-succes/20 text-vert-succes border border-vert-succes/30
                                                    <?php break; ?>

                                                    <?php case ('completed'): ?>
                                                        bg-vert-succes/20 text-vert-succes border border-vert-succes/30
                                                    <?php break; ?>

                                                    <?php case ('cancelled'): ?>
                                                        bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30
                                                    <?php break; ?>

                                                    <?php default: ?>
                                                        bg-titane/30 text-ivoire-text/80
                                                <?php endswitch; ?>
                                            ">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($bookingRequest->status->value):
                                                case ('pending'): ?>
                                                    En attente
                                                <?php break; ?>

                                                <?php case ('accepted'): ?>
                                                    Acceptée
                                                <?php break; ?>

                                                <?php case ('awaiting_deposit'): ?>
                                                    Acompte attendu
                                                <?php break; ?>

                                                <?php case ('deposit_paid'): ?>
                                                    Acompte payé
                                                <?php break; ?>

                                                <?php case ('design_sent'): ?>
                                                    Dessin envoyé
                                                <?php break; ?>

                                                <?php case ('date_confirmed'): ?>
                                                    📅 Date confirmée
                                                <?php break; ?>

                                                <?php case ('confirmed'): ?>
                                                    Confirmé
                                                <?php break; ?>

                                                <?php case ('completed'): ?>
                                                    Terminé
                                                <?php break; ?>

                                                <?php case ('cancelled'): ?>
                                                    Annulé
                                                <?php break; ?>

                                                <?php default: ?>
                                                    <?php echo e(ucfirst($bookingRequest->status->value)); ?>

                                            <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-ivoire-text/50 text-xs">Design</span>
                                        <p class="text-ivoire-text text-sm mt-1">
                                            <?php echo e(\Illuminate\Support\Str::limit($bookingRequest->tattoo_description ?? 'Non spécifié', 80)); ?>

                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-ivoire-text/50 text-xs">Localisation</span>
                                        <p class="text-ivoire-text text-sm mt-1">
                                            <?php echo e($bookingRequest->tattoo_location ?? 'Non spécifiée'); ?>

                                        </p>
                                    </div>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->estimated_total_price): ?>
                                    <div class="mt-2">
                                        <span class="text-ivoire-text/50 text-xs">Budget estimé</span>
                                        <p class="text-ivoire-text text-sm mt-1">
                                            💰 <?php echo e(number_format($bookingRequest->estimated_total_price, 0)); ?>€
                                        </p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="flex flex-col gap-2 ml-4">
                                <a href="<?php echo e(route('client.booking-request.show', $bookingRequest)); ?>"
                                    class="px-3 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond text-sm font-medium rounded-lg transition-colors text-center">
                                    Voir détails
                                </a>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status === 'accepted' && $bookingRequest->conversation): ?>
                                    <a href="<?php echo e(route('client.chat', $bookingRequest->conversation->id)); ?>"
                                        class="px-3 py-2 border border-titane/30 text-ivoire-text text-sm font-medium rounded-lg hover:border-beige-peau transition-colors text-center">
                                        Contacter
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/livewire/client/bookings.blade.php ENDPATH**/ ?>