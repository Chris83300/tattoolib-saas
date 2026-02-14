<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? 'Ink&Pik'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Livewire -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="bg-noir-profond antialiased">
    
    <!-- Header site public avec navigation adaptative -->
    <?php if (isset($component)) { $__componentOriginala231aefbc1626acae338242c7a979fdb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala231aefbc1626acae338242c7a979fdb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.navigation','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.navigation'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala231aefbc1626acae338242c7a979fdb)): ?>
<?php $attributes = $__attributesOriginala231aefbc1626acae338242c7a979fdb; ?>
<?php unset($__attributesOriginala231aefbc1626acae338242c7a979fdb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala231aefbc1626acae338242c7a979fdb)): ?>
<?php $component = $__componentOriginala231aefbc1626acae338242c7a979fdb; ?>
<?php unset($__componentOriginala231aefbc1626acae338242c7a979fdb); ?>
<?php endif; ?>
    
    <!-- Contenu principal (slot Livewire) -->
    <main>
        <?php echo e($slot); ?>

    </main>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/components/layouts/livewire-site.blade.php ENDPATH**/ ?>