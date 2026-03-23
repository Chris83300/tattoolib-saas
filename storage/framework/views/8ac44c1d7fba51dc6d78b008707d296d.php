<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description" content="Ink&Pik - Marketplace professionnelle pour tatoueurs, pierceurs et studios. Artistes vérifiés, conformité ARS, paiements sécurisés.">
    <meta name="theme-color" content="#D4B59E">
    
    <!-- PWA Meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Ink&Pik">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/build/manifest.webmanifest" crossorigin="use-credentials">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/icon-192x192.png')); ?>">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-noir-profond text-ivoire-text safe-top safe-bottom">
    
    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <!-- Footer -->
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- PWA Install Prompt -->
    <?php echo $__env->make('partials.pwa-install-prompt', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/layouts/guest.blade.php ENDPATH**/ ?>