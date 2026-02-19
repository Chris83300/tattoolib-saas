<?php $__env->startSection('content'); ?>
    <div class="space-y-6">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="bg-vert-succes/20 border border-vert-succes/50 text-vert-succes px-4 py-3 rounded-lg">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="bg-ambre-warning/20 border border-ambre-warning/50 text-ambre-warning px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div id="upload-feedback" class="hidden px-4 py-3 rounded-lg"></div>

        <!-- Header -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <h1 class="text-2xl font-bold text-ivoire-text mb-2">Mon Portfolio</h1>
            <p class="text-ivoire-text/70">Gérez vos photos de réalisations</p>
        </div>

        <!-- Tabs -->
        <div class="bg-gris-fonde rounded-xl p-2">
            <div class="flex gap-2">
                <button onclick="switchTab('tattoos')"
                    class="tab-btn flex-1 px-4 py-2 rounded-lg font-semibold transition-colors" data-tab="tattoos">
                    🎨 Tattoos (<?php echo e($tattoos->count()); ?>)
                </button>
                <button onclick="switchTab('drawings')"
                    class="tab-btn flex-1 px-4 py-2 rounded-lg font-semibold transition-colors" data-tab="drawings">
                    ✏️ Dessins (<?php echo e($drawings->count()); ?>)
                </button>
                <button onclick="switchTab('before-after')"
                    class="tab-btn flex-1 px-4 py-2 rounded-lg font-semibold transition-colors" data-tab="before-after">
                    📸 Avant/Après (<?php echo e($beforeAfter->count()); ?>)
                </button>
            </div>
        </div>

        <!-- Tab Content: Tattoos -->
        <div id="tab-tattoos" class="tab-content">
            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-ivoire-text">Photos de tattoos</h2>
                    <label
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold cursor-pointer hover:bg-beige-peau/90">
                        + Ajouter photos
                        <input type="file" accept="image/*" multiple class="hidden"
                            onchange="uploadImages(this, 'portfolio')">
                    </label>
                </div>

                <div id="tattoos-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tattoos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative group aspect-square rounded-lg overflow-hidden bg-noir-profond"
                            draggable="true" data-media-id="<?php echo e($media->id); ?>">
                            <img src="<?php echo e($media->getUrl()); ?>" alt="Tattoo" class="w-full h-full object-cover">

                            <!-- Overlay actions -->
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button onclick="viewImage('<?php echo e($media->getUrl()); ?>')"
                                    class="p-2 bg-beige-peau text-noir-profond rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                                <button onclick="deleteImage(<?php echo e($media->id); ?>)"
                                    class="p-2 bg-rouge-alerte text-noir-profond rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattoos->isEmpty()): ?>
                        <div class="col-span-full text-center py-12 text-ivoire-text/60">
                            Aucune photo de tattoo. Ajoutez-en pour enrichir votre portfolio !
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tab Content: Drawings -->
        <div id="tab-drawings" class="tab-content hidden">
            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-ivoire-text">Dessins & Sketches</h2>
                    <label
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold cursor-pointer hover:bg-beige-peau/90">
                        + Ajouter dessins
                        <input type="file" accept="image/*" multiple class="hidden"
                            onchange="uploadImages(this, 'drawings')">
                    </label>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $drawings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative group aspect-square rounded-lg overflow-hidden bg-noir-profond">
                            <img src="<?php echo e($media->getUrl()); ?>" alt="Dessin" class="w-full h-full object-cover">

                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button onclick="viewImage('<?php echo e($media->getUrl()); ?>')"
                                    class="p-2 bg-beige-peau text-noir-profond rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteImage(<?php echo e($media->id); ?>)"
                                    class="p-2 bg-rouge-alerte text-noir-profond rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($drawings->isEmpty()): ?>
                        <div class="col-span-full text-center py-12 text-ivoire-text/60">
                            Aucun dessin. Montrez vos créations !
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tab Content: Before/After -->
        <div id="tab-before-after" class="tab-content hidden">
            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-ivoire-text mb-1">Photos Avant/Après</h2>
                        <p class="text-ivoire-text/60 text-sm">Uploadez vos photos par paires (avant puis après)</p>
                    </div>
                    <button onclick="openBeforeAfterModal()"
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90">
                        + Ajouter paire
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <?php
                        $pairs = collect();

                        // Paires robustes (nouveau format): groupé par pair_id + type
                        $withPairId = $beforeAfter
                            ->filter(fn($m) => (string) $m->getCustomProperty('pair_id'))
                            ->groupBy(fn($m) => (string) $m->getCustomProperty('pair_id'));

                        foreach ($withPairId as $pairId => $items) {
                            $before = $items->first(fn($m) => $m->getCustomProperty('type') === 'before');
                            $after = $items->first(fn($m) => $m->getCustomProperty('type') === 'after');
                            if ($before && $after) {
                                $pairs->push([$before, $after]);
                            }
                        }

                        // Fallback legacy: paires par ordre d'upload (2 par 2)
                        $legacy = $beforeAfter
                            ->filter(fn($m) => !(string) $m->getCustomProperty('pair_id'))
                            ->sortBy('id')
                            ->values()
                            ->chunk(2)
                            ->map(fn($chunk) => $chunk->values());

                        foreach ($legacy as $pair) {
                            if ($pair->count() === 2) {
                                $pairs->push([$pair[0], $pair[1]]);
                            }
                        }
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($pair) && count($pair) === 2): ?>
                            <div class="bg-noir-profond rounded-xl p-4">
                                <!-- Before/After Slider -->
                                <div class="relative aspect-video rounded-lg overflow-hidden mb-4 before-after-slider">
                                    <img src="<?php echo e($pair[0]->getUrl()); ?>"
                                        class="absolute inset-0 w-full h-full object-cover before-image" alt="Avant">
                                    <img src="<?php echo e($pair[1]->getUrl()); ?>"
                                        class="absolute inset-0 w-full h-full object-cover after-image" alt="Après"
                                        style="clip-path: inset(0 50% 0 0);">

                                    <!-- Slider -->
                                    <div class="absolute inset-y-0 left-1/2 w-1 bg-white z-10 slider-line"></div>
                                    <div
                                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-full flex items-center justify-center cursor-ew-resize slider-handle z-20">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>

                                    <!-- Labels -->
                                    <div class="absolute top-2 left-2 px-2 py-1 bg-black/60 text-white text-xs rounded">
                                        AVANT</div>
                                    <div class="absolute top-2 right-2 px-2 py-1 bg-black/60 text-white text-xs rounded">
                                        APRÈS</div>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button onclick="deleteBeforeAfterPair(<?php echo e($pair[0]->id); ?>, <?php echo e($pair[1]->id); ?>)"
                                        class="px-3 py-1 bg-rouge-alerte/20 text-rouge-alerte rounded text-sm">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pairs->isEmpty()): ?>
                        <div class="col-span-full text-center py-12 text-ivoire-text/60">
                            Aucune photo avant/après. Montrez l'évolution de vos réalisations !
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Upload Before/After -->
    <div id="before-after-modal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
        <div class="bg-gris-fonde rounded-xl p-6 max-w-2xl w-full">
            <h3 class="text-xl font-bold text-ivoire-text mb-4">Ajouter photo Avant/Après</h3>
            <div id="before-after-feedback" class="hidden px-4 py-3 rounded-lg mb-4"></div>
            <form id="before-after-form" action="<?php echo e(route('tattooer.portfolio.before-after.store')); ?>" method="POST"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-2 gap-4 mb-6">

                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Photo Avant</label>
                        <label
                            class="block aspect-square border-2 border-dashed border-titane/30 rounded-lg cursor-pointer hover:border-beige-peau transition-colors">
                            <input type="file" name="after" accept="image/*" class="hidden"
                                onchange="previewImage(this, 'after-preview')">
                            <div id="after-preview"
                                class="w-full h-full flex items-center justify-center text-ivoire-text/60">
                                <span>+ Choisir photo</span>
                            </div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Photo Après</label>
                        <label
                            class="block aspect-square border-2 border-dashed border-titane/30 rounded-lg cursor-pointer hover:border-beige-peau transition-colors">
                            <input type="file" name="before" accept="image/*" class="hidden"
                                onchange="previewImage(this, 'before-preview')">
                            <div id="before-preview"
                                class="w-full h-full flex items-center justify-center text-ivoire-text/60">
                                <span>+ Choisir photo</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold">
                        Ajouter
                    </button>
                    <button type="button" onclick="closeBeforeAfterModal()"
                        class="flex-1 px-4 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-semibold">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Tabs
            function switchTab(tabName) {
                try {
                    localStorage.setItem('tattooer_portfolio_active_tab', tabName);
                } catch (e) {}

                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.tab-btn').forEach(el => {
                    el.classList.remove('bg-beige-peau', 'text-noir-profond');
                    el.classList.add('text-ivoire-text');
                });

                document.getElementById('tab-' + tabName).classList.remove('hidden');
                document.querySelector(`[data-tab="${tabName}"]`).classList.add('bg-beige-peau', 'text-noir-profond');
                document.querySelector(`[data-tab="${tabName}"]`).classList.remove('text-ivoire-text');
            }

            document.addEventListener('DOMContentLoaded', () => {
                // Init first tab
                let initialTab = 'tattoos';
                try {
                    const saved = localStorage.getItem('tattooer_portfolio_active_tab');
                    if (saved) initialTab = saved;
                } catch (e) {}
                switchTab(initialTab);

                // Before/After Slider
                document.querySelectorAll('.before-after-slider').forEach(slider => {
                    const handle = slider.querySelector('.slider-handle');
                    const afterImage = slider.querySelector('.after-image');
                    const sliderLine = slider.querySelector('.slider-line');

                    let isDragging = false;

                    // Mobile-friendly: prevent page scroll while dragging
                    slider.style.touchAction = 'none';
                    handle.style.touchAction = 'none';

                    const updateFromClientX = (clientX) => {
                        const rect = slider.getBoundingClientRect();
                        const x = Math.max(0, Math.min(clientX - rect.left, rect.width));
                        const percent = (x / rect.width) * 100;

                        afterImage.style.clipPath = `inset(0 ${100 - percent}% 0 0)`;
                        handle.style.left = `${percent}%`;
                        sliderLine.style.left = `${percent}%`;
                    };

                    handle.addEventListener('pointerdown', (e) => {
                        isDragging = true;
                        try {
                            handle.setPointerCapture(e.pointerId);
                        } catch (err) {}
                        updateFromClientX(e.clientX);
                        e.preventDefault();
                    });

                    handle.addEventListener('pointermove', (e) => {
                        if (!isDragging) return;
                        updateFromClientX(e.clientX);
                        e.preventDefault();
                    });

                    handle.addEventListener('pointerup', (e) => {
                        isDragging = false;
                        try {
                            handle.releasePointerCapture(e.pointerId);
                        } catch (err) {}
                        e.preventDefault();
                    });

                    handle.addEventListener('pointercancel', () => {
                        isDragging = false;
                    });
                });

                // Submit before/after via AJAX to get proper feedback
                const beforeAfterForm = document.getElementById('before-after-form');
                if (beforeAfterForm) {
                    beforeAfterForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const feedback = document.getElementById('upload-feedback');
                        const modalFeedback = document.getElementById('before-after-feedback');
                        const formData = new FormData(beforeAfterForm);

                        const beforeInput = beforeAfterForm.querySelector('input[type="file"][name="before"]');
                        const afterInput = beforeAfterForm.querySelector('input[type="file"][name="after"]');

                        const beforeCount = beforeInput && beforeInput.files ? beforeInput.files.length : 0;
                        const afterCount = afterInput && afterInput.files ? afterInput.files.length : 0;

                        if (beforeCount < 1 || afterCount < 1) {
                            if (modalFeedback) {
                                modalFeedback.classList.remove('hidden');
                                modalFeedback.classList.remove('bg-vert-succes/20', 'border-vert-succes/50',
                                    'text-vert-succes');
                                modalFeedback.classList.add('bg-ambre-warning/20', 'border',
                                    'border-ambre-warning/50', 'text-ambre-warning');
                                modalFeedback.textContent =
                                    'Merci de sélectionner une photo AVANT et une photo APRÈS.';
                            }

                            feedback.classList.remove('hidden');
                            feedback.classList.remove('bg-vert-succes/20', 'border-vert-succes/50',
                                'text-vert-succes');
                            feedback.classList.add('bg-ambre-warning/20', 'border', 'border-ambre-warning/50',
                                'text-ambre-warning');
                            feedback.textContent = 'Merci de sélectionner une photo AVANT et une photo APRÈS.';
                            return;
                        }

                        if (modalFeedback) {
                            modalFeedback.classList.remove('hidden');
                            modalFeedback.classList.remove('bg-vert-succes/20', 'border-vert-succes/50',
                                'text-vert-succes');
                            modalFeedback.classList.add('bg-ambre-warning/20', 'border',
                                'border-ambre-warning/50', 'text-ambre-warning');
                            modalFeedback.textContent = 'Upload en cours...';
                        }

                        feedback.classList.remove('hidden');
                        feedback.classList.remove('bg-vert-succes/20', 'border-vert-succes/50',
                            'text-vert-succes');
                        feedback.classList.add('bg-ambre-warning/20', 'border', 'border-ambre-warning/50',
                            'text-ambre-warning');
                        feedback.textContent = 'Upload en cours...';

                        fetch(beforeAfterForm.action, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Accept': 'application/json'
                            },
                            body: formData
                        }).then(async (res) => {
                            let data = null;
                            let rawText = null;

                            try {
                                data = await res.json();
                            } catch (e) {
                                try {
                                    rawText = await res.text();
                                } catch (e2) {
                                    rawText = null;
                                }
                            }

                            if (!res.ok) {
                                const messageFromJson = (data && (data.message || (data.errors &&
                                        Object.values(data
                                            .errors)[0] && Object.values(data.errors)[0][0]
                                    ))) ?
                                    (data.message || (Object.values(data.errors)[0][0])) :
                                    null;

                                const messageFromText = rawText ? rawText.slice(0, 200) : null;
                                const message = messageFromJson || messageFromText ||
                                    `Upload impossible (HTTP ${res.status}).`;

                                feedback.classList.remove('hidden');
                                feedback.classList.remove('bg-vert-succes/20',
                                    'border-vert-succes/50',
                                    'text-vert-succes');
                                feedback.classList.add('bg-ambre-warning/20', 'border',
                                    'border-ambre-warning/50', 'text-ambre-warning');
                                feedback.textContent = message;

                                if (modalFeedback) {
                                    modalFeedback.classList.remove('hidden');
                                    modalFeedback.classList.remove('bg-vert-succes/20',
                                        'border-vert-succes/50',
                                        'text-vert-succes');
                                    modalFeedback.classList.add('bg-ambre-warning/20', 'border',
                                        'border-ambre-warning/50', 'text-ambre-warning');
                                    modalFeedback.textContent = message;
                                }
                                return;
                            }

                            feedback.classList.remove('hidden');
                            feedback.classList.remove('bg-ambre-warning/20',
                                'border-ambre-warning/50',
                                'text-ambre-warning');
                            feedback.classList.add('bg-vert-succes/20', 'border',
                                'border-vert-succes/50',
                                'text-vert-succes');
                            feedback.textContent = (data && data.message) ? data.message :
                                'Photos avant/après ajoutées avec succès';

                            if (modalFeedback) {
                                modalFeedback.classList.remove('hidden');
                                modalFeedback.classList.remove('bg-ambre-warning/20',
                                    'border-ambre-warning/50',
                                    'text-ambre-warning');
                                modalFeedback.classList.add('bg-vert-succes/20', 'border',
                                    'border-vert-succes/50',
                                    'text-vert-succes');
                                modalFeedback.textContent = (data && data.message) ? data.message :
                                    'Photos avant/après ajoutées avec succès';
                            }

                            try {
                                localStorage.setItem('tattooer_portfolio_active_tab',
                                    'before-after');
                            } catch (e) {}
                            closeBeforeAfterModal();
                            location.reload();
                        }).catch((err) => {
                            feedback.classList.remove('hidden');
                            feedback.classList.remove('bg-vert-succes/20', 'border-vert-succes/50',
                                'text-vert-succes');
                            feedback.classList.add('bg-ambre-warning/20', 'border',
                                'border-ambre-warning/50', 'text-ambre-warning');
                            feedback.textContent =
                                `Erreur réseau : ${err && err.message ? err.message : 'Upload impossible.'}`;

                            if (modalFeedback) {
                                modalFeedback.classList.remove('hidden');
                                modalFeedback.classList.remove('bg-vert-succes/20',
                                    'border-vert-succes/50', 'text-vert-succes');
                                modalFeedback.classList.add('bg-ambre-warning/20', 'border',
                                    'border-ambre-warning/50', 'text-ambre-warning');
                                modalFeedback.textContent =
                                    `Erreur réseau : ${err && err.message ? err.message : 'Upload impossible.'}`;
                            }
                        });
                    });
                }
            });

            // Upload images
            function uploadImages(input, collection) {
                const feedback = document.getElementById('upload-feedback');

                const formData = new FormData();
                Array.from(input.files).forEach(file => {
                    formData.append('images[]', file);
                });
                formData.append('collection', collection);

                fetch('<?php echo e(route('tattooer.portfolio.upload')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    },
                    body: formData
                }).then(async (res) => {
                    let data = null;
                    try {
                        data = await res.json();
                    } catch (e) {
                        data = null;
                    }

                    if (!res.ok) {
                        const message = (data && (data.message || (data.errors && Object.values(data.errors)[0] &&
                                Object.values(data.errors)[0][0]))) ?
                            (data.message || (Object.values(data.errors)[0][0])) :
                            'Upload impossible. Vérifie la limite FREE (20) ou le format des images.';

                        feedback.classList.remove('hidden');
                        feedback.classList.remove('bg-vert-succes/20', 'border-vert-succes/50', 'text-vert-succes');
                        feedback.classList.add('bg-ambre-warning/20', 'border', 'border-ambre-warning/50',
                            'text-ambre-warning');
                        feedback.textContent = message;
                        input.value = '';
                        return;
                    }

                    feedback.classList.remove('hidden');
                    feedback.classList.remove('bg-ambre-warning/20', 'border-ambre-warning/50',
                        'text-ambre-warning');
                    feedback.classList.add('bg-vert-succes/20', 'border', 'border-vert-succes/50',
                        'text-vert-succes');
                    feedback.textContent = (data && data.message) ? data.message : 'Images uploadées avec succès';
                    location.reload();
                });
            }

            // Delete image
            function deleteImage(mediaId) {
                if (!confirm('Supprimer cette image ?')) return;

                const feedback = document.getElementById('upload-feedback');

                fetch(`/tattooer/portfolio/${mediaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    }
                }).then(async (res) => {
                    let data = null;
                    try {
                        data = await res.json();
                    } catch (e) {
                        data = null;
                    }

                    if (!res.ok) {
                        feedback.classList.remove('hidden');
                        feedback.classList.remove('bg-vert-succes/20', 'border-vert-succes/50', 'text-vert-succes');
                        feedback.classList.add('bg-ambre-warning/20', 'border', 'border-ambre-warning/50',
                            'text-ambre-warning');
                        feedback.textContent = 'Suppression impossible.';
                        return;
                    }

                    feedback.classList.remove('hidden');
                    feedback.classList.remove('bg-ambre-warning/20', 'border-ambre-warning/50',
                        'text-ambre-warning');
                    feedback.classList.add('bg-vert-succes/20', 'border', 'border-vert-succes/50',
                        'text-vert-succes');
                    feedback.textContent = (data && data.message) ? data.message : 'Image supprimée avec succès';
                    location.reload();
                });
            }

            // Modal Before/After
            function openBeforeAfterModal() {
                document.getElementById('before-after-modal').classList.remove('hidden');
            }

            function closeBeforeAfterModal() {
                document.getElementById('before-after-modal').classList.add('hidden');
            }

            // Delete before/after pair
            function deleteBeforeAfterPair(beforeId, afterId) {
                if (!confirm('Supprimer cette paire avant/après ?')) return;

                const feedback = document.getElementById('upload-feedback');

                fetch(`/tattooer/portfolio/before-after/${beforeId}/${afterId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    }
                }).then(async (res) => {
                    let data = null;
                    try {
                        data = await res.json();
                    } catch (e) {
                        data = null;
                    }

                    if (!res.ok) {
                        feedback.classList.remove('hidden');
                        feedback.classList.remove('bg-vert-succes/20', 'border-vert-succes/50', 'text-vert-succes');
                        feedback.classList.add('bg-ambre-warning/20', 'border', 'border-ambre-warning/50',
                            'text-ambre-warning');
                        feedback.textContent = 'Suppression impossible.';
                        return;
                    }

                    feedback.classList.remove('hidden');
                    feedback.classList.remove('bg-ambre-warning/20', 'border-ambre-warning/50',
                        'text-ambre-warning');
                    feedback.classList.add('bg-vert-succes/20', 'border', 'border-vert-succes/50',
                        'text-vert-succes');
                    feedback.textContent = (data && data.message) ? data.message :
                        'Photos avant/après supprimées avec succès';
                    location.reload();
                });
            }

            // Preview
            function previewImage(input, previewId) {
                const preview = document.getElementById(previewId);
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg">`;
                    };
                    reader.readAsDataURL(file);
                }
            }

            // View image
            function viewImage(imageUrl) {
                const lightbox = document.createElement('div');
                lightbox.className = 'fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4';
                lightbox.innerHTML = `
        <img src="${imageUrl}" class="max-w-full max-h-full rounded-lg">
        <button onclick="this.parentElement.remove()" class="absolute top-4 right-4 text-white text-2xl">×</button>
    `;
                document.body.appendChild(lightbox);
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/tattooer/portfolio.blade.php ENDPATH**/ ?>