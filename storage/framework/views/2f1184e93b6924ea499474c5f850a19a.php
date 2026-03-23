<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['artist', 'studioArtist']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['artist', 'studioArtist']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // Déterminer si on utilise les données du marketplace (tableau) ou du studio (objet)
    $isMarketplace = isset($artist) && is_array($artist);
    $data = $isMarketplace ? (object) $artist : $studioArtist;

    // Normaliser les données pour compatibilité
    if ($isMarketplace) {
        $name = $artist['name'] ?? 'Artiste';
        $type = $artist['type'] ?? 'tattooer';
        $avatar = $artist['avatar_url'] ?? null;
        $banner = $artist['banner_url'] ?? null;
        $city = $artist['city'] ?? null;
        $studioName = $artist['studio_name'] ?? null;
        $bio = $artist['bio'] ?? null;
    } else {
        $name = $studioArtist->artist_name ?: $studioArtist->user?->name ?? 'Artiste';
        $type = $studioArtist->artisan_type;
        $avatar = $studioArtist->user?->getFirstMediaUrl('avatar');

        // Banner : priorité au modèle Tattooer/Piercer
        $artisanModel = null;
        if ($studioArtist->user) {
            $artisanModel =
                $studioArtist->artisan_type === 'piercer'
                    ? \App\Models\Piercer::where('user_id', $studioArtist->user_id)->first()
                    : \App\Models\Tattooer::where('user_id', $studioArtist->user_id)->first();
        }
        $banner = $artisanModel?->getFirstMediaUrl('banner') ?: null;

        $city = $studioArtist->studio?->city;
        $studioName = $studioArtist->studio?->name;
        $bio = $artisanModel?->bio ?? ($studioArtist->user?->bio ?? null);
    }
?>

<div
    class="bg-noir-profond rounded-[2rem] border border-titane/40
           shadow-lg shadow-electric-blue/30 overflow-hidden
           hover:ring-2 hover:ring-beige-peau hover:shadow-cuivre/50
           transition-all relative m-2 mb-4">

    
    <div class="absolute top-2 left-2 space-y-1 z-10">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isMarketplace): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($artist['has_compliance_badge'])): ?>
                <span class="px-2 py-0.5 text-sm font-bold bg-vert-succes/20 text-vert-succes rounded-full block">✓
                    Conforme hygiène</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isMarketplace && isset($artist['siret_verified']) && $artist['siret_verified']): ?>
        <?php elseif(!$isMarketplace && $studioArtist->is_active): ?>
            <span class="bg-vert-succes/20 text-vert-succes text-xs px-2 py-1 rounded-full font-semibold">Actif</span>
        <?php elseif(!$isMarketplace && !$studioArtist->is_active): ?>
            <span
                class="bg-rouge-alerte/20 text-rouge-alerte text-xs px-2 py-1 rounded-full font-semibold">Inactif</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="h-64 md:h-80 bg-gradient-to-br from-titane/40 to-noir-profond relative overflow-hidden">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($banner)): ?>
            <img src="<?php echo e($banner); ?>" alt="Bannière de <?php echo e($name); ?>" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 via-noir-profond/40 to-transparent"></div>
        <?php elseif($isMarketplace && !empty($artist['banner_url'])): ?>
            <img src="<?php echo e($artist['banner_url']); ?>" alt="Bannière de <?php echo e($name); ?>"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 via-noir-profond/40 to-transparent">
            </div>
        <?php else: ?>
            <!-- Bannière par défaut améliorée -->
            <div class="absolute inset-0 bg-gradient-to-br from-beige-peau/30 via-titane/40 to-noir-profond"></div>
            <div class="absolute inset-0 bg-black/30"></div>

            <!-- Icône ou motif décoratif -->
            <div class="absolute inset-0 flex items-center justify-center">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($type === 'piercer'): ?>
                    <svg class="w-24 h-24 text-beige-peau/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                    </svg>
                <?php else: ?>
                    <svg class="w-24 h-24 text-beige-peau/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Texte du type d'artiste -->
            <div class="absolute bottom-4 left-4">
                <span class="text-beige-peau/40 text-sm font-semibold">
                    <?php echo e($type === 'piercer' ? 'Pierceur' : 'Tatoueur'); ?>

                </span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="px-4 -mt-12 relative z-20">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            
            <div
                class="w-32 h-32 md:w-36 md:h-36 rounded-full border-2 border-titane/30 shadow-lg shadow-titane/20 overflow-hidden bg-titane/40 flex items-center justify-center flex-shrink-0">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($avatar)): ?>
                    <img src="<?php echo e($avatar); ?>" alt="<?php echo e($name); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <svg class="w-16 h-16 md:w-18 md:h-18 text-ivoire-text/30" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="flex-1 mt-4 flex flex-wrap justify-center lg:mt-20 md:mt-20 gap-2 text-xs text-ivoire-text">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isMarketplace): ?>
                    <!-- Stats marketplace -->
                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">

                        <span><?php echo e($artist['experience_years'] ?? 'N/A'); ?> ans</span> <span class="text-titane">
                            d'expérience</span>
                    </div>

                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                        <span><?php echo e($artist['min_price'] ? 'À partir de ' . $artist['min_price'] . '€' : 'N/A'); ?></span>
                    </div>

                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                        <span><?php echo e($artist['wait_time'] ?? 'N/A'); ?></span><span class="text-titane"> d'attente</span>
                    </div>
                <?php else: ?>
                    <!-- Stats studio -->
                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span><?php echo e($type === 'piercer' ? ' Pierceur' : ' Tatoueur'); ?></span>
                    </div>

                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>Rejoint <?php echo e($studioArtist->joined_at?->format('d/m/Y') ?? 'N/A'); ?></span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$studioArtist->user): ?>
                        <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-ambre-warning"> En attente</span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="p-4 pt-2">

        
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-4">
            <div class="text-center sm:text-left">
                <h3 class="text-beige-peau font-semibold text-lg mb-1">
                    <?php echo e($name); ?>

                </h3>
                <p class="text-ivoire-text text-sm mb-1">
                    <?php echo e($type === 'piercer' ? 'Pierceur' : 'Tatoueur'); ?>

                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($studioName)): ?>
                    <p class="text-beige-peau text-sm mb-1">
                        <?php echo e($studioName); ?>

                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($city)): ?>
                    <p class="text-titane text-sm">
                        <?php echo e($city); ?>

                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="text-center sm:text-right">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isMarketplace): ?>
                    <!-- Rating marketplace -->
                    <div class="flex items-center justify-center sm:justify-end gap-1 text-beige-peau text-sm mb-1">
                        ⭐ <?php echo e(number_format($artist['average_rating'], 1)); ?> (<?php echo e($artist['total_reviews']); ?> avis)
                    </div>
                    <div class="text-ivoire-text/60 text-xs">
                        <?php echo e($artist['total_reviews']); ?> avis
                    </div>
                <?php else: ?>
                    <!-- Statut studio -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studioArtist->user): ?>
                        <div
                            class="flex items-center justify-center sm:justify-end gap-1 text-vert-succes text-sm mb-1">
                            ✅ Actif
                        </div>
                        <div class="text-ivoire-text/60 text-xs">
                            Profil complet
                        </div>
                    <?php else: ?>
                        <div
                            class="flex items-center justify-center sm:justify-end gap-1 text-ambre-warning text-sm mb-1">
                            ⏳ En attente
                        </div>
                        <div class="text-ivoire-text/60 text-xs">
                            Invitation envoyée
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isMarketplace && isset($artist['styles'])): ?>
            <div class="flex flex-wrap justify-center gap-3 mb-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $artist['styles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge-style text-xs bg-beige-peau/10 text-beige-peau px-2 py-1 rounded-full">
                        <?php echo e($style); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($bio)): ?>
            <div class="text-ivoire-text text-sm mb-3 line-clamp-2">
                <?php echo e(Str::limit($bio, 100)); ?>

            </div>
        <?php elseif(!$isMarketplace && !$studioArtist->user): ?>
            <div class="text-titane text-sm mb-3 italic">
                En attente d'acceptation de l'invitation...
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="flex flex-col sm:flex-row gap-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isMarketplace): ?>
                <!-- Actions marketplace -->
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'sm','href' => ''.e(route('marketplace.show.artist', $artist['slug'])).'','class' => 'flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'sm','href' => ''.e(route('marketplace.show.artist', $artist['slug'])).'','class' => 'flex-1']); ?>
                    Voir le profil
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'sm','href' => ''.e(route('booking-request.form', ['bookableId' => $artist['id'], 'bookableType' => $artist['type']])).'','class' => 'flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'sm','href' => ''.e(route('booking-request.form', ['bookableId' => $artist['id'], 'bookableType' => $artist['type']])).'','class' => 'flex-1']); ?>
                    Contacter
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
            <?php else: ?>
                <!-- Actions studio -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studioArtist->user): ?>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'sm','href' => ''.e(route('studio.artists.show', $studioArtist)).'','class' => 'flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'sm','href' => ''.e(route('studio.artists.show', $studioArtist)).'','class' => 'flex-1']); ?>
                        Voir la gestion
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'sm','href' => ''.e(route('studio.artists')).'','class' => 'flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'sm','href' => ''.e(route('studio.artists')).'','class' => 'flex-1']); ?>
                        Gérer l'invitation
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form method="POST" action="<?php echo e(route('studio.artists.toggle', $studioArtist)); ?>" class="flex-1">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'sm','type' => 'submit','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'sm','type' => 'submit','class' => 'w-full']); ?>
                        <?php echo e($studioArtist->is_active ? 'Désactiver' : 'Activer'); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/components/ui/artistCard.blade.php ENDPATH**/ ?>