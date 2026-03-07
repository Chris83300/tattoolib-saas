<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-noir-profond">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
        
        <nav class="mb-8">
            <a href="<?php echo e(url('/')); ?>" class="text-sm text-titane hover:text-beige-peau transition-colors">
                ← Retour à l'accueil
            </a>
        </nav>

        
        <header class="mb-10">
            <h1 class="text-2xl sm:text-3xl font-bold text-ivoire-text"><?php echo $__env->yieldContent('legal-title'); ?></h1>
            <p class="text-sm text-titane mt-2">
                Dernière mise à jour : <?php echo $__env->yieldContent('legal-date', now()->format('d/m/Y')); ?>
            </p>
        </header>

        
        <article class="prose prose-invert prose-sm max-w-none
            prose-headings:text-ivoire-text prose-headings:font-semibold
            prose-h2:text-xl prose-h2:mt-10 prose-h2:mb-4 prose-h2:border-b prose-h2:border-titane/20 prose-h2:pb-2
            prose-h3:text-lg prose-h3:mt-6 prose-h3:mb-3
            prose-p:text-titane prose-p:leading-relaxed
            prose-li:text-titane
            prose-strong:text-ivoire-text
            prose-a:text-beige-peau prose-a:no-underline hover:prose-a:underline
            prose-table:text-titane prose-th:text-ivoire-text prose-th:font-semibold
            prose-td:border-titane/20 prose-th:border-titane/20">
            <?php echo $__env->yieldContent('legal-content'); ?>
        </article>

        
        <footer class="mt-16 pt-8 border-t border-titane/20">
            <h3 class="text-sm font-semibold text-ivoire-text/60 uppercase tracking-wider mb-4">Documents juridiques</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a href="<?php echo e(route('legal.mentions-legales')); ?>" class="text-sm text-titane hover:text-beige-peau transition-colors">Mentions légales</a>
                <a href="<?php echo e(route('legal.cgu')); ?>" class="text-sm text-titane hover:text-beige-peau transition-colors">CGU</a>
                <a href="<?php echo e(route('legal.cgv-artistes')); ?>" class="text-sm text-titane hover:text-beige-peau transition-colors">CGV Artistes</a>
                <a href="<?php echo e(route('legal.cgv-clients')); ?>" class="text-sm text-titane hover:text-beige-peau transition-colors">CGV Clients</a>
                <a href="<?php echo e(route('legal.politique-confidentialite')); ?>" class="text-sm text-titane hover:text-beige-peau transition-colors">Politique de confidentialité</a>
                <a href="<?php echo e(route('legal.politique-cookies')); ?>" class="text-sm text-titane hover:text-beige-peau transition-colors">Politique de cookies</a>
            </div>
        </footer>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\legal\layout.blade.php ENDPATH**/ ?>