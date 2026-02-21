<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Studio - Tattoolib SaaS'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'noir-profond': '#0a0a0a',
                        'ivoire-text': '#f8f8f8',
                        'beige-peau': '#f5e6d3',
                        'gris-fonde': '#1a1a1a',
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-noir-profond text-ivoire-text">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Studio -->
        <nav class="bg-gris-fonde border-b border-ivoire-text/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="<?php echo e(route('studio.dashboard')); ?>" class="text-beige-peau font-bold text-xl">Studio</a>
                        <div class="ml-10 flex space-x-4">
                            <a href="<?php echo e(route('studio.artists')); ?>" class="text-ivoire-text hover:text-beige-peau">Artistes</a>
                            <a href="<?php echo e(route('studio.planning')); ?>" class="text-ivoire-text hover:text-beige-peau">Planning</a>
                            <a href="<?php echo e(route('studio.requests')); ?>" class="text-ivoire-text hover:text-beige-peau">Demandes</a>
                            <a href="<?php echo e(route('studio.transactions')); ?>" class="text-ivoire-text hover:text-beige-peau">Transactions</a>
                            <a href="<?php echo e(route('studio.stats')); ?>" class="text-ivoire-text hover:text-beige-peau">Stats</a>
                            <a href="<?php echo e(route('studio.exports')); ?>" class="text-ivoire-text hover:text-beige-peau">Exports</a>
                            <a href="<?php echo e(route('studio.settings')); ?>" class="text-ivoire-text hover:text-beige-peau">Paramètres</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-ivoire-text/70"><?php echo e(Auth::user()->name); ?></span>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-ivoire-text hover:text-beige-peau">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer -->
        <footer class="bg-gris-fonde border-t border-ivoire-text/20 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-ivoire-text/70">
                    &copy; <?php echo e(date('Y')); ?> Tattoolib SaaS. Tous droits réservés.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\layouts\studio.blade.php ENDPATH**/ ?>