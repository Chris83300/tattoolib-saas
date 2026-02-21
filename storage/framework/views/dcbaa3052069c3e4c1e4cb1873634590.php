<?php $__env->startSection('title', 'Mes conversations'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
                Messages
            </h1>
            <p class="text-ivoire-text/70">
                Conversations avec vos artistes
            </p>
        </div>

        <!-- Liste des conversations -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversations->count() > 0): ?>
                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('client.chat', $conversation)); ?>"
                            class="block p-4 bg-noir-profond rounded-lg hover:bg-noir-profond/80 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <!-- Avatar -->
                                        <div
                                            class="w-10 h-10 rounded-full overflow-hidden bg-titane/30 flex items-center justify-center">
                                            <?php
                                                $artist = $conversation->bookingRequest?->bookable;
                                                $artistUser = $artist?->user;
                                            ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artistUser && $artistUser->getFirstMedia('avatar')): ?>
                                                <img src="<?php echo e($artistUser->getFirstMedia('avatar')->getUrl()); ?>"
                                                    alt="<?php echo e($artistUser->name); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <svg class="w-5 h-5 text-ivoire-text/40" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <!-- Nom de l'artiste -->
                                            <h3 class="font-semibold text-ivoire-text">
                                                <?php echo e($artistUser->pseudo ?? 'Artiste inconnu'); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist): ?>
                                                    <span class="text-ivoire-text/60 text-sm ml-2">
                                                        <?php echo e(class_basename($artist)); ?>

                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </h3>
                                            <p class="text-sm text-ivoire-text/60">
                                                <?php echo e($conversation->bookingRequest?->description ? Str::limit($conversation->bookingRequest->description, 50) : 'Nouvelle demande de projet'); ?>

                                            </p>
                                        </div>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversation->messages->count() > 0): ?>
                                        <?php
                                            $lastMessage = $conversation->messages->first();
                                        ?>
                                        <div class="mt-2">
                                            <p class="text-sm text-ivoire-text/70">
                                                <?php echo e($lastMessage->content ? Str::limit($lastMessage->content, 80) : 'Message sans texte'); ?>

                                            </p>
                                            <p class="text-xs text-ivoire-text/50 mt-1">
                                                <?php echo e($lastMessage->created_at->format('d/m/Y à H:i')); ?>

                                            </p>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <!-- Badge statut acompte -->
                                    <?php
                                        $br = $conversation->bookingRequest;
                                    ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br): ?>
                                        <div class="flex flex-col gap-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->deposit_paid_at): ?>
                                                <span
                                                    class="px-2.5 py-0.5 bg-vert-succes/20 text-vert-succes rounded-full text-xs font-bold">
                                                    💰 Acompte payé
                                                </span>
                                            <?php elseif(in_array($br->status->value, ['accepted', 'awaiting_deposit']) && $br->deposit_amount): ?>
                                                <span
                                                    class="px-2.5 py-0.5 bg-jaune-alerte/20 text-jaune-alerte rounded-full text-xs font-bold">
                                                    ⏳ Acompte en attente
                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <!-- Badge statut demande -->
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    <?php switch($br->status->value):
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
                                                        <?php case ('rejected'): ?>
                                                            bg-rouge-alerte/20 text-rouge-alerte
                                                        <?php break; ?>


                                                        <?php default: ?>
                                                            bg-titane/20 text-titane
                                                    <?php endswitch; ?>
                                                ">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($br->status->value):
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

                                                    <?php case ('rejected'): ?>
                                                        Rejeté
                                                    <?php break; ?>

                                                    <?php default: ?>
                                                        <?php echo e(ucfirst($br->status->value)); ?>

                                                <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php echo e($br->status->label()); ?>

                                            </span>

                                            <!-- Bouton laisser un avis pour les demandes terminées -->
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                                $br->isCompleted() &&
                                                    !\App\Models\Review::where('booking_request_id', $br->id)->where('client_user_id', auth()->id())->exists()): ?>
                                                <button onclick="openReviewModal(<?php echo e($br->id); ?>)"
                                                    class="px-2.5 py-0.5 bg-beige-peau/20 text-beige-peau rounded-full text-xs font-bold hover:bg-beige-peau/30 transition-colors">
                                                    ⭐ Laisser un avis
                                                </button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php switch($br->status->value):
                                        case ('deposit_paid'): ?>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($br->status->value):
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
                                            <?php echo e(ucfirst($br->status->value)); ?>

                                    <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                </div>


                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversation->unread_count > 0): ?>
                                    <span
                                        class="bg-rouge-alerte text-noir-profond px-2 py-1 rounded-full text-xs font-bold">
                                        <?php echo e($conversation->unread_count); ?>

                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                Aucune conversation
            </h3>
            <p class="text-ivoire-text/60">
                Vous n'avez pas encore de messages avec vos artistes.
            </p>
            <div class="mt-6 space-x-4">
                <a href="<?php echo e(route('client.booking-requests')); ?>"
                    class="inline-flex items-center px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond rounded-lg font-medium transition-colors">
                    Nouvelle demande
                </a>
                <a href="<?php echo e(route('marketplace.index')); ?>"
                    class="inline-flex items-center px-4 py-2 border border-beige-peau/30 text-beige-peau hover:bg-beige-peau/10 rounded-lg font-medium transition-colors">
                    Trouver un artiste
                </a>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Auto-rafraîchissement toutes les 30 secondes pour les nouveaux messages
            setInterval(() => {
                // Optionnel : recharger la page pour voir les nouveaux messages
                // window.location.reload();
            }, 30000);
        </script>

        <script>
            function openReviewModal(bookingRequestId) {
                // Créer la modal
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-noir-profond/90 z-50 flex items-center justify-center p-4';
                modal.innerHTML = `
                <div class="bg-gris-fonde rounded-xl p-6 max-w-md w-full max-w-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-ivoire-text">Laisser un avis</h3>
                        <button onclick="closeReviewModal()" class="text-ivoire-text/60 hover:text-ivoire-text/80">
                            ✕
                        </button>
                    </div>
                    <form onsubmit="submitReview(event, ${bookingRequestId})">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-ivoire-text font-semibold mb-2">Note (1-5 étoiles)</label>
                                <div class="flex gap-2">
                                    <button type="button" onclick="setRating(1)" class="w-12 h-12 rounded-lg border border-titane/30 bg-noir-profond text-ivoire-text hover:bg-titane/20 transition-colors">⭐</button>
                                    <button type="button" onclick="setRating(2)" class="w-12 h-12 rounded-lg border border-titane/30 bg-noir-profond text-ivoire-text hover:bg-titane/20 transition-colors">⭐</button>
                                    <button type="button" onclick="setRating(3)" class="w-12 h-12 rounded-lg border border-titane/30 bg-noir-profond text-ivoire-text hover:bg-titane/20 transition-colors">⭐</button>
                                    <button type="button" onclick="setRating(4)" class="w-12 h-12 rounded-lg border border-titane/30 bg-noir-profond text-ivoire-text hover:bg-titane/20 transition-colors">⭐</button>
                                    <button type="button" onclick="setRating(5)" class="w-12 h-12 rounded-lg border border-titane/30 bg-noir-profond text-ivoire-text hover:bg-titane/20 transition-colors">⭐</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-ivoire-text font-semibold mb-2">Commentaire</label>
                                <textarea name="comment" rows="4" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau"></textarea>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="submit" class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                    Envoyer l'avis
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            `;
                document.body.appendChild(modal);
            }

            function closeReviewModal() {
                const modal = document.querySelector('[onclick*="closeReviewModal"]');
                if (modal) {
                    modal.remove();
                }
            }

            function setRating(rating) {
                const buttons = document.querySelectorAll('[onclick^="setRating("]');
                buttons.forEach((btn, index) => {
                    btn.classList.remove('border-beige-peau', 'bg-beige-peau');
                    btn.classList.add('border-titane/30');
                    if (index < rating) {
                        btn.classList.add('border-beige-peau', 'bg-beige-peau');
                    }
                });
            }

            function submitReview(event, bookingRequestId) {
                event.preventDefault();
                const formData = new FormData(event.target);
                const rating = document.querySelectorAll('[onclick^="setRating("].border-beige-peau').length;

                if (rating === 0) {
                    alert('Veuillez sélectionner une note (1-5 étoiles)');
                    return;
                }

                fetch(`/client/reviews/${bookingRequestId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        body: JSON.stringify({
                            rating: rating,
                            comment: formData.get('comment')
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Avis envoyé avec succès !', 'success');
                            closeReviewModal();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showNotification(data.message || 'Erreur lors de l\'envoi de l\'avis', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Erreur lors de l\'envoi de l\'avis', 'error');
                    });
            }

            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white font-semibold z-50 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
                notification.textContent = message;
                document.body.appendChild(notification);
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client\messages.blade.php ENDPATH**/ ?>