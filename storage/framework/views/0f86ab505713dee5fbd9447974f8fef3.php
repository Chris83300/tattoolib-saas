<?php $__env->startSection('title', 'Créer un client'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Header -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route($tattooer->routePrefix() . '.clients')); ?>" class="p-2 rounded-lg hover:bg-noir-profond transition-colors">
                    <svg class="w-5 h-5 text-titane" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text">Créer une fiche client</h1>
                    <p class="text-ivoire-text/70">Ajouter un nouveau client manuellement</p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <form action="<?php echo e(route($tattooer->routePrefix() . '.clients.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">
                            Prénom
                        </label>
                        <input type="text" name="first_name" required
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="Jean">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">
                            Nom
                        </label>
                        <input type="text" name="last_name" required
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="Dupont">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">
                            Pseudo
                        </label>
                        <input type="text" name="pseudo" required
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="Dupont">
                    </div>
                </div>

                <!-- Contact -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">
                            Email
                        </label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="jean.dupont@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">
                            Téléphone
                        </label>
                        <input type="tel" name="phone"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="06 12 34 56 78">
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">
                            Date de naissance
                        </label>
                        <input type="date" name="birth_date"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">
                            Adresse
                        </label>
                        <input type="text" name="address"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="123 Rue du Salon, 75011 Paris">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-semibold text-ivoire-text mb-2">
                        Notes privées
                    </label>
                    <textarea name="notes" rows="4"
                        class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-y"
                        placeholder="Allergies, préférences, comportement au salon, informations utiles..."></textarea>
                    <p class="text-xs text-titane mt-1">
                        Visibles uniquement par vous. Ces notes vous aideront à mieux connaître votre client.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4">
                    <a href="<?php echo e(route($tattooer->routePrefix() . '.clients')); ?>"
                        class="px-6 py-3 border border-titane/30 text-titane rounded-lg font-semibold hover:bg-noir-profond transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-bold hover:bg-beige-peau/90 transition-colors active:scale-95">
                        ✅ Créer la fiche client
                    </button>
                </div>
            </form>
        </div>

        <!-- Info -->
        <div class="bg-titane/10 rounded-xl p-4 border border-titane/20">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-beige-peau flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-ivoire-text/80">
                    <p class="font-semibold text-beige-peau mb-1">Fonctionnalité PRO</p>
                    <p>La création manuelle de fiches clients vous permet d'ajouter des clients sans qu'ils aient à passer
                        par une demande de réservation.</p>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\tattooer\clients-create.blade.php ENDPATH**/ ?>