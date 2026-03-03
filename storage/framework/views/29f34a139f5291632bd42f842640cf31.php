<div>
    <!-- Header -->
    <div class="mb-6">
        <a href="<?php echo e(route('client.profile')); ?>"
            class="text-ivoire-text/70 text-sm hover:text-beige-peau mb-4 inline-block">
            ← Retour à mon profil
        </a>
        <h1 class="text-beige-peau font-display text-2xl font-bold">
            Paramètres du compte
        </h1>
    </div>

    <!-- Notifications -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
        <div class="mb-4 p-4 bg-vert-succes/20 border border-vert-succes/30 rounded-lg">
            <p class="text-vert-succes"><?php echo e(session()->get('success')); ?></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Formulaire paramètres -->
    <div class="bg-gris-fonde rounded-xl p-6 space-y-6">


        <!-- Avatar et pseudo -->
        <div>
            <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                Avatar et pseudo
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Avatar -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Photo de profil
                    </label>
                    <form action="<?php echo e(route('client.settings.update-avatar')); ?>" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="flex items-center gap-4">
                            <img id="avatar-preview"
                                src="<?php echo e(auth()->user()->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png')); ?>"
                                alt="Avatar" class="w-20 h-20 rounded-full object-cover">

                            <div class="flex flex-col gap-2">
                                <label
                                    class="min-h-11 px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold cursor-pointer hover:bg-beige-peau/90 transition-colors inline-block text-center text-sm active:scale-95">
                                    Changer photo (5MB max)
                                    <input type="file" name="avatar" accept="image/*" class="hidden"
                                        onchange="previewAvatar(this)">
                                </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasMedia('avatar')): ?>
                                    <button type="button" onclick="deleteAvatar()"
                                        class="min-h-11 px-4 py-2 bg-rouge-alerte/20 text-rouge-alerte rounded-lg font-semibold hover:bg-rouge-alerte/30 transition-colors text-sm active:scale-95">
                                        Supprimer
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <button type="submit"
                                    class="min-h-11 px-4 py-2 bg-titane/20 text-ivoire-text border border-titane/30 rounded-lg font-semibold hover:bg-titane/30 transition-colors text-sm active:scale-95">
                                    Enregistrer la photo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Pseudo -->
                <div>
                    <label class="block font-semibold text-ivoire-text mb-2">Pseudo</label>
                    <input type="text" name="pseudo" value="<?php echo e(auth()->user()->pseudo ?? ''); ?>"
                        class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau text-sm md:text-base">
                    <p class="text-xs text-ivoire-text/60 mt-1">Affiché sur votre profil public</p>
                </div>
            </div>

            <!-- Date de naissance -->
            <div class="mt-6">
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Date de naissance
                </label>
                <input type="date" wire:model="birth_date" max="<?php echo e(now()->subYears(16)->format('Y-m-d')); ?>"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:outline-none">
                <p class="text-ivoire-text/50 text-xs mt-1">
                    Vous devez avoir au moins 16 ans
                </p>
            </div>

            <div class="mt-4">
                <button wire:click="updateProfile"
                    class="px-6 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                    Enregistrer les modifications
                </button>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="border-t border-titane/20 pt-6">
            <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                Informations personnelles
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Nom complet
                    </label>
                    <input type="text"
                        value="<?php echo e(auth()->user()->first_name ?? ''); ?> <?php echo e(auth()->user()->last_name ?? ''); ?>" disabled
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 opacity-60">
                </div>

                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Email
                    </label>
                    <input type="email" value="<?php echo e(auth()->user()->email); ?>" disabled
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 opacity-60">
                </div>
            </div>

            <p class="text-ivoire-text/50 text-sm mt-2">
                Pour modifier vos informations personnelles, contactez le support.
            </p>
        </div>

        <!-- Sécurité -->
        <div class="border-t border-titane/20 pt-6">
            <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                Sécurité
            </h2>

            <div class="space-y-3">
                <button
                    class="w-full text-left px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg hover:border-beige-peau transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-ivoire-text">Changer le mot de passe</span>
                        <svg class="w-5 h-5 text-ivoire-text/50" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </button>

                <button
                    class="w-full text-left px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg hover:border-beige-peau transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-ivoire-text">Activer l'authentification à deux facteurs</span>
                        <svg class="w-5 h-5 text-ivoire-text/50" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </button>
            </div>
        </div>

        <!-- Préférences -->
        <div class="border-t border-titane/20 pt-6">
            <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                Préférences
            </h2>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-ivoire-text">Recevoir les notifications par email</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-noir-profond border border-titane/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-beige-peau">
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Danger zone -->
        <div class="border-t border-rouge-alerte/20 pt-6">
            <h2 class="text-rouge-alerte font-display font-bold text-lg mb-4">
                Zone de danger
            </h2>

            <div class="space-y-3">
                <button wire:click="confirmDeleteAccount" type="button"
                    class="w-full text-left px-4 py-3 bg-noir-profond border border-rouge-alerte/30 rounded-lg hover:border-rouge-alerte transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-rouge-alerte">Supprimer mon compte</span>
                        <svg class="w-5 h-5 text-rouge-alerte/50" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </button>
            </div>

            <p class="text-ivoire-text/50 text-sm mt-2">
                La suppression de votre compte est irréversible.
            </p>
        </div>
    </div>

        <?php
        $__scriptKey = '2162360471-0';
        ob_start();
    ?>
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('profile-updated', (message) => {
                    // Afficher une notification de succès
                    const notification = document.createElement('div');
                    notification.className =
                        'fixed top-4 right-4 p-4 bg-vert-succes/20 border border-vert-succes/30 rounded-lg text-vert-succes z-50';
                    notification.innerHTML = `<p>${message}</p>`;
                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                });

                Livewire.on('show-confirm-dialog', (data) => {
                    // Créer et afficher la modale de confirmation
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
                    modal.innerHTML = `
                <div class="bg-noir-profond rounded-xl p-6 max-w-md mx-auto border border-rouge-alerte/30">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-rouge-alerte/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-rouge-alerte" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-ivoire-text mb-2">${data.title}</h3>
                            <p class="text-ivoire-text/80 mb-4">${data.message}</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button onclick="window.livewire.find('confirm-delete').call()"
                            class="px-4 py-2 bg-rouge-alerte text-white rounded-lg hover:bg-rouge-alerte/80 transition-colors">
                            ${data.confirmText}
                        </button>
                        <button onclick="window.livewire.find('cancel-delete').call()"
                            class="px-4 py-2 bg-gris-fonde text-ivoire-text rounded-lg hover:bg-gris-fonde/80 transition-colors">
                            ${data.cancelText}
                        </button>
                    </div>
                </div>
            `;
                    document.body.appendChild(modal);

                    // Fermer la modale au clic en dehors
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            modal.remove();
                        }
                    });
                });

                Livewire.on('hide-confirm-dialog', () => {
                    // Cacher la modale
                    const modal = document.querySelector('.fixed.inset-0');
                    if (modal) {
                        modal.remove();
                    }
                });

                Livewire.on('account-deleted', (message) => {
                    // Cacher la modale et rediriger
                    const modal = document.querySelector('.fixed.inset-0');
                    if (modal) {
                        modal.remove();
                    }
                    window.location.href = '/';
                });

                Livewire.on('account-delete-error', (message) => {
                    // Afficher une notification d'erreur
                    const notification = document.createElement('div');
                    notification.className =
                        'fixed top-4 right-4 p-4 bg-rouge-alerte/20 border border-rouge-alerte/30 rounded-lg text-rouge-alerte z-50';
                    notification.innerHTML = `<p>${message}</p>`;
                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                });
            });
        </script>
        <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('avatar-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function deleteAvatar() {
            if (confirm('Supprimer votre photo de profil ?')) {
                fetch('<?php echo e(route('client.settings.delete-avatar')); ?>', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Erreur lors de la suppression de l\'avatar');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors de la suppression de l\'avatar');
                    });
            }
        }
    </script>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/livewire/client/settings.blade.php ENDPATH**/ ?>