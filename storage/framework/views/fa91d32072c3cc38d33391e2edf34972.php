<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Paramètres du studio</h1>

    <form action="<?php echo e(route('studio.settings.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">🏢 Informations</h2>

            <div>
                <label class="text-xs text-titane block mb-1">Nom du studio *</label>
                <input type="text" name="name" value="<?php echo e(old('name', $studio->name)); ?>" required
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-rouge-alerte mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Description</label>
                <textarea name="description" rows="4" placeholder="Présentez votre studio..."
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none resize-none"><?php echo e(old('description', $studio->description)); ?></textarea>
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Adresse</label>
                <input type="text" name="address" value="<?php echo e(old('address', $studio->address)); ?>"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Ville</label>
                    <input type="text" name="city" value="<?php echo e(old('city', $studio->city)); ?>"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                </div>
                <div class="w-full sm:w-32">
                    <label class="text-xs text-titane block mb-1">Code postal</label>
                    <input type="text" name="postal_code" value="<?php echo e(old('postal_code', $studio->postal_code)); ?>"
                        maxlength="5"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Téléphone</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone', $studio->phone)); ?>"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                </div>
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Email professionnel</label>
                    <input type="email" name="email" value="<?php echo e(old('email', $studio->email)); ?>"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Site web</label>
                    <input type="url" name="website" value="<?php echo e(old('website', $studio->website)); ?>"
                        placeholder="https://..."
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                </div>
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">SIRET</label>
                    <input type="text" name="siret" value="<?php echo e(old('siret', $studio->siret)); ?>" maxlength="14"
                        placeholder="14 chiffres"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                </div>
            </div>
        </div>

        
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">📸 Photos</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-titane block mb-2">Logo</label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->getFirstMediaUrl('logo')): ?>
                        <img src="<?php echo e($studio->getFirstMediaUrl('logo')); ?>" alt="Logo"
                            class="w-24 h-24 rounded-lg object-cover mb-2">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
                </div>
                <div>
                    <label class="text-xs text-titane block mb-2">Photo de couverture</label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->getFirstMediaUrl('cover')): ?>
                        <img src="<?php echo e($studio->getFirstMediaUrl('cover')); ?>" alt="Couverture"
                            class="w-full h-24 rounded-lg object-cover mb-2">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <input type="file" name="cover" accept="image/*"
                        class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
                </div>
            </div>
            <div>
                <label class="text-xs text-titane block mb-2">Photos du salon (multiples)</label>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->getMedia('photos')->count() > 0): ?>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $studio->getMedia('photos'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="relative group">
                                <img src="<?php echo e($photo->getUrl()); ?>" alt="Photo salon"
                                    class="w-20 h-20 rounded-lg object-cover">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <input type="file" name="photos[]" accept="image/*" multiple
                    class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
            </div>
        </div>

        
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">💳 Paiement</h2>
            <p class="text-xs text-titane">Comment les clients paient-ils les prestations de vos artistes ?</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="payment_mode" value="studio_managed"
                        <?php echo e(old('payment_mode', $studio->payment_mode ?? 'artist_direct') === 'studio_managed' ? 'checked' : ''); ?>

                        class="peer hidden">
                    <div
                        class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 transition-colors">
                        <p class="font-semibold text-ivoire-text text-sm">🏦 Géré par le studio</p>
                        <p class="text-xs text-titane mt-1">Le studio encaisse tout via un seul compte Stripe Connect.
                            Vous reversez aux artistes.</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="payment_mode" value="artist_direct"
                        <?php echo e(old('payment_mode', $studio->payment_mode ?? '') === 'artist_direct' ? 'checked' : ''); ?>

                        class="peer hidden">
                    <div
                        class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 transition-colors">
                        <p class="font-semibold text-ivoire-text text-sm">👤 Direct par artiste</p>
                        <p class="text-xs text-titane mt-1">Chaque artiste a son propre Stripe Connect. Vous supervisez
                            seulement.</p>
                    </div>
                </label>
            </div>
        </div>

        
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6" x-data="{
            daysOrder: ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'],
            hours: <?php echo e(Js::from(
                $studio->opening_hours ?? [
                    'lundi' => ['open' => '09:00', 'close' => '19:00', 'closed' => true],
                    'mardi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'mercredi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'jeudi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'vendredi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'samedi' => ['open' => '10:00', 'close' => '18:00', 'closed' => false],
                    'dimanche' => ['open' => '', 'close' => '', 'closed' => true],
                ],
            )); ?>,
            toggleDay(dayName, isClosed) {
                const day = this.hours[dayName];
                if (isClosed) {
                    // Si on ferme le jour, vider les heures
                    day.open = '';
                    day.close = '';
                } else {
                    // Si on ouvre le jour, restaurer les heures par défaut
                    if (!day.open || day.open === '') {
                        day.open = dayName === 'samedi' ? '10:00' : '09:00';
                    }
                    if (!day.close || day.close === '') {
                        day.close = dayName === 'samedi' ? '18:00' : '19:00';
                    }
                }
            }
        }">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-4">🕐 Horaires d'ouverture
            </h2>
            <div class="space-y-2">
                <template x-for="dayName in daysOrder" :key="dayName">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="w-24 text-sm text-ivoire-text capitalize" x-text="dayName"></span>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="checkbox" :name="'opening_hours[' + dayName + '][closed]'"
                                :checked="hours[dayName].closed"
                                @change="hours[dayName].closed = $event.target.checked; toggleDay(dayName, $event.target.checked)"
                                class="rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                            <span class="text-xs text-titane">Fermé</span>
                            <!-- Champs cachés pour s'assurer que les valeurs sont toujours envoyées -->
                            <input type="hidden" :name="'opening_hours[' + dayName + '][open]'"
                                :value="hours[dayName].open || ''">
                            <input type="hidden" :name="'opening_hours[' + dayName + '][close]'"
                                :value="hours[dayName].close || ''">
                        </label>
                        <template x-if="!hours[dayName].closed">
                            <div class="flex items-center gap-1">
                                <input type="time" :name="'opening_hours[' + dayName + '][open]'"
                                    x-model="hours[dayName].open"
                                    class="px-2 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                                <span class="text-titane text-xs">→</span>
                                <input type="time" :name="'opening_hours[' + dayName + '][close]'"
                                    x-model="hours[dayName].close"
                                    class="px-2 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        
        <button type="submit"
            class="w-full sm:w-auto px-6 py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
            Sauvegarder
        </button>
    </form>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/livewire/studio/settings.blade.php ENDPATH**/ ?>