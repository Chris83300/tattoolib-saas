<?php $__env->startSection('title', 'Tarifs — Ink&Pik'); ?>
<?php $__env->startSection('meta-description', 'Découvrez les plans Ink&Pik pour tatoueurs, pierceurs et studios. 14 jours d\'essai gratuit sans carte bancaire.'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-16 space-y-16">

    
    <div class="text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-ivoire-text mb-4">Tarifs Ink&Pik</h1>
        <p class="text-lg text-titane max-w-2xl mx-auto">
            Des plans adaptés aux artistes indépendants et aux studios. 14 jours d'essai gratuit sur tous les plans, sans carte bancaire.
        </p>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        
        <div class="bg-gris-fonde rounded-2xl p-6 border border-titane/20 flex flex-col">
            <div class="mb-6">
                <span class="px-3 py-1 bg-titane/20 text-titane text-xs font-bold rounded-full">STARTER</span>
                <p class="text-3xl font-bold text-ivoire-text mt-3">9,99€<span class="text-sm font-normal text-titane">/mois</span></p>
                <p class="text-xs text-vert-succes mt-1">🎁 14 jours d'essai gratuit — sans CB</p>
            </div>
            <ul class="space-y-2 text-sm text-ivoire-text/80 flex-1 mb-6">
                <li class="flex items-center gap-2">✅ Profil artiste vérifié</li>
                <li class="flex items-center gap-2">✅ Visible dans la marketplace</li>
                <li class="flex items-center gap-2">✅ Gestion des demandes & RDV</li>
                <li class="flex items-center gap-2">✅ Messagerie client</li>
                <li class="flex items-center gap-2">✅ Acompte sécurisé (Stripe)</li>
                <li class="flex items-center gap-2">✅ Notifications automatiques</li>
                <li class="flex items-center gap-2 text-rouge-alerte/80">❌ Commission 7% par prestation</li>
                <li class="flex items-center gap-2 text-titane">❌ Fiches clients avancées</li>
                <li class="flex items-center gap-2 text-titane">❌ Analytics & statistiques</li>
            </ul>
            <a href="<?php echo e(route('register')); ?>"
                class="block text-center px-4 py-3 bg-titane/20 text-ivoire-text rounded-xl font-semibold text-sm hover:bg-titane/30 transition-colors">
                Commencer gratuitement
            </a>
        </div>

        
        <div class="bg-gris-fonde rounded-2xl p-6 border-2 border-beige-peau relative flex flex-col">
            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 bg-beige-peau text-noir-profond text-xs font-bold rounded-full whitespace-nowrap">
                ⭐ PLUS POPULAIRE
            </div>
            <div class="mb-6">
                <span class="px-3 py-1 bg-beige-peau/20 text-beige-peau text-xs font-bold rounded-full">PRO</span>
                <p class="text-3xl font-bold text-beige-peau mt-3">29,99€<span class="text-sm font-normal text-titane">/mois</span></p>
                <p class="text-xs text-vert-succes mt-1">🎁 14 jours d'essai gratuit — sans CB</p>
            </div>
            <ul class="space-y-2 text-sm text-ivoire-text/80 flex-1 mb-6">
                <li class="flex items-center gap-2">✅ Tout le plan Starter</li>
                <li class="flex items-center gap-2 text-vert-succes font-semibold">✅ Commission 0%</li>
                <li class="flex items-center gap-2 text-vert-succes">✅ Mise en avant marketplace</li>
                <li class="flex items-center gap-2 text-vert-succes">✅ Fiches clients & traçabilité</li>
                <li class="flex items-center gap-2 text-vert-succes">✅ Consentements & soins</li>
                <li class="flex items-center gap-2 text-vert-succes">✅ Export PDF complet</li>
                <li class="flex items-center gap-2 text-vert-succes">✅ Export comptabilité CSV/Excel</li>
                <li class="flex items-center gap-2 text-vert-succes">✅ Badge PRO vérifié</li>
                <li class="flex items-center gap-2 text-vert-succes">✅ Support prioritaire</li>
            </ul>
            <a href="<?php echo e(route('register')); ?>"
                class="block text-center px-4 py-3 bg-beige-peau text-noir-profond rounded-xl font-bold text-sm hover:bg-beige-peau/90 transition-colors">
                Commencer gratuitement
            </a>
        </div>

        
        <div class="bg-gris-fonde rounded-2xl p-6 border border-titane/20 flex flex-col">
            <div class="mb-6">
                <span class="px-3 py-1 bg-cuivre/20 text-cuivre text-xs font-bold rounded-full">STUDIO</span>
                <p class="text-3xl font-bold text-ivoire-text mt-3">59,99€<span class="text-sm font-normal text-titane">/mois</span></p>
                <p class="text-xs text-titane mt-1">+ 24,99€/artiste supplémentaire</p>
                <p class="text-xs text-vert-succes mt-1">🎁 14 jours d'essai gratuit — sans CB</p>
            </div>
            <ul class="space-y-2 text-sm text-ivoire-text/80 flex-1 mb-6">
                <li class="flex items-center gap-2">✅ Tout le plan Pro</li>
                <li class="flex items-center gap-2">✅ 1 artiste inclus</li>
                <li class="flex items-center gap-2">✅ Gestion multi-artistes</li>
                <li class="flex items-center gap-2">✅ Dashboard studio centralisé</li>
                <li class="flex items-center gap-2">✅ Planning global</li>
                <li class="flex items-center gap-2">✅ Statistiques & revenus</li>
                <li class="flex items-center gap-2">✅ Profil studio marketplace</li>
                <li class="flex items-center gap-2">✅ Facturation centralisée</li>
                <li class="flex items-center gap-2">✅ Panel Filament avancé</li>
            </ul>
            <a href="<?php echo e(route('register')); ?>"
                class="block text-center px-4 py-3 bg-titane/20 text-ivoire-text rounded-xl font-semibold text-sm hover:bg-titane/30 transition-colors">
                Commencer gratuitement
            </a>
        </div>
    </div>

    
    <div class="max-w-2xl mx-auto space-y-4">
        <h2 class="text-2xl font-bold text-ivoire-text text-center mb-8">Questions fréquentes</h2>

        <details class="bg-gris-fonde rounded-xl border border-titane/20 p-5">
            <summary class="font-semibold text-ivoire-text cursor-pointer">L'essai gratuit nécessite-t-il une carte bancaire ?</summary>
            <p class="text-titane text-sm mt-3">Non. Les 14 jours d'essai sont entièrement gratuits et ne nécessitent aucune carte bancaire. Vous choisissez votre plan à la fin de l'essai.</p>
        </details>

        <details class="bg-gris-fonde rounded-xl border border-titane/20 p-5">
            <summary class="font-semibold text-ivoire-text cursor-pointer">Que se passe-t-il à la fin de l'essai ?</summary>
            <p class="text-titane text-sm mt-3">Si vous ne souscrivez pas à un abonnement, votre profil est masqué de la marketplace. Vos données sont conservées. Vous pouvez vous abonner à tout moment pour réactiver votre compte.</p>
        </details>

        <details class="bg-gris-fonde rounded-xl border border-titane/20 p-5">
            <summary class="font-semibold text-ivoire-text cursor-pointer">Comment fonctionne la commission de 7% du plan Starter ?</summary>
            <p class="text-titane text-sm mt-3">Sur le plan Starter, Ink&Pik prélève 7% de commission sur chaque transaction (acompte et solde) via Stripe Application Fee. Vous recevez 93% directement sur votre compte Stripe Connect. En passant au plan PRO, la commission est supprimée.</p>
        </details>

        <details class="bg-gris-fonde rounded-xl border border-titane/20 p-5">
            <summary class="font-semibold text-ivoire-text cursor-pointer">Puis-je changer de plan à tout moment ?</summary>
            <p class="text-titane text-sm mt-3">Oui. Vous pouvez passer de Starter à PRO ou résilier votre abonnement à tout moment. Les résiliations prennent effet à la fin de la période mensuelle en cours.</p>
        </details>

        <details class="bg-gris-fonde rounded-xl border border-titane/20 p-5">
            <summary class="font-semibold text-ivoire-text cursor-pointer">Qu'est-ce que le programme bêta-testeur ?</summary>
            <p class="text-titane text-sm mt-3">Les artistes et studios ayant participé au lancement d'Ink&Pik bénéficient d'une réduction de -30% à vie sur leur abonnement, tant que celui-ci reste actif sans interruption. Cette réduction est automatiquement appliquée.</p>
        </details>
    </div>

    
    <div class="text-center bg-gris-fonde rounded-2xl p-8 border border-beige-peau/20">
        <h2 class="text-2xl font-bold text-ivoire-text mb-3">Prêt à rejoindre Ink&Pik ?</h2>
        <p class="text-titane mb-6">14 jours gratuits, sans carte bancaire. Annulable à tout moment.</p>
        <a href="<?php echo e(route('register')); ?>"
            class="inline-block px-8 py-3.5 bg-beige-peau text-noir-profond font-bold rounded-xl hover:bg-beige-peau/90 transition-colors">
            Créer mon compte gratuitement
        </a>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\pricing.blade.php ENDPATH**/ ?>