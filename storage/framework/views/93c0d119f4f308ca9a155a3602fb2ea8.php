<?php $__env->startSection('title', 'Fiche de soins'); ?>
<?php $__env->startSection('doc-type', 'Fiche de soins post-prestation'); ?>
<?php $__env->startSection('doc-date', $generatedAt->format('d/m/Y')); ?>
<?php $__env->startSection('doc-ref', 'REF-CS-' . str_pad($careSheet->id ?? '0', 6, '0', STR_PAD_LEFT)); ?>

<?php $__env->startSection('content'); ?>

    <div class="alert-box">
        <strong>⚕ Obligation légale :</strong> Ce document est remis conformément à l'article R.1311-12 du Code de la Santé
        Publique.
        Il détaille les précautions à respecter après la réalisation de la prestation.
    </div>

    <h2>Informations du client</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Nom complet</div>
            <div class="info-value"><?php echo e(trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')) ?: '—'); ?></div>
            <div class="info-label">Date de naissance</div>
            <div class="info-value"><?php echo e($client?->birth_date?->format('d/m/Y') ?? '—'); ?></div>
        </div>
        <div class="info-col">
            <div class="info-label">Email</div>
            <div class="info-value"><?php echo e($client?->email ?? '—'); ?></div>
            <div class="info-label">Téléphone</div>
            <div class="info-value"><?php echo e($client?->phone ?? '—'); ?></div>
        </div>
    </div>

    <h2>Informations du professionnel</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label"><?php echo e($isStudio ? 'Studio' : 'Artiste'); ?></div>
            <div class="info-value">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isStudio): ?>
                    <?php echo e($professional?->name ?? '—'); ?>

                <?php else: ?>
                    <?php echo e($professional?->user?->name ?? '—'); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="info-label">SIRET</div>
            <div class="info-value"><?php echo e($professional?->siret ?? '—'); ?></div>
        </div>
        <div class="info-col">
            <div class="info-label">Studio</div>
            <div class="info-value"><?php echo e($careSheet->studio?->name ?? ($isStudio ? $professional?->name : 'Indépendant')); ?>

            </div>
            <div class="info-label">Date de la prestation</div>
            <div class="info-value"><?php echo e($careSheet->created_at?->format('d/m/Y') ?? '—'); ?></div>
        </div>
    </div>

    <h2>Détail du tatouage</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Description</div>
            <div class="info-value"><?php echo e($careSheet->tattoo_description ?? '—'); ?></div>
            <div class="info-label">Zone corporelle</div>
            <div class="info-value"><?php echo e($careSheet->tattoo_location ?? '—'); ?></div>
        </div>
        <div class="info-col">
            <div class="info-label">Taille</div>
            <div class="info-value"><?php echo e($careSheet->tattoo_size ?? '—'); ?></div>
            <div class="info-label">Technique utilisée</div>
            <div class="info-value"><?php echo e($careSheet->technique_used ?? '—'); ?></div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->allergies_details || $careSheet->skin_conditions_details || $careSheet->medications_details): ?>
        <h2>Informations médicales déclarées</h2>
        <div class="alert-box">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->allergies_details): ?>
                <strong>Allergies :</strong> <?php echo e($careSheet->allergies_details); ?><br>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->skin_conditions_details): ?>
                <strong>Conditions de peau :</strong> <?php echo e($careSheet->skin_conditions_details); ?><br>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->medications_details): ?>
                <strong>Médicaments :</strong> <?php echo e($careSheet->medications_details); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->has_diabetes): ?>
                <br><strong>Diabète :</strong> Oui
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->has_blood_disorders): ?>
                <br><strong>Trouble sanguin :</strong> Oui
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->is_pregnant): ?>
                <br><strong>Grossesse :</strong> Oui
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->products_used): ?>
        <h3>Produits utilisés</h3>
        <div class="info-value"><?php echo e($careSheet->products_used); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->ink_colors_used): ?>
        <h3>Couleurs d'encre utilisées</h3>
        <div class="info-value"><?php echo e($careSheet->ink_colors_used); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <h2>Soins du pansement</h2>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->bandage_type || $careSheet->bandage_removal_time): ?>
        <div class="info-grid">
            <div class="info-col">
                <div class="info-label">Type de pansement</div>
                <div class="info-value"><?php echo e($careSheet->bandage_type ?? '—'); ?></div>
            </div>
            <div class="info-col">
                <div class="info-label">Retrait du pansement</div>
                <div class="info-value">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->bandage_removal_time): ?>
                        <?php echo e($careSheet->bandage_removal_time->format('d/m/Y à H:i')); ?>

                    <?php else: ?>
                        —
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <h2>Consignes de soins post-prestation</h2>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->immediate_care_instructions): ?>
        <h3>Soins immédiats (instructions personnalisées)</h3>
        <div class="info-value"><?php echo e($careSheet->immediate_care_instructions); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->washing_instructions): ?>
        <h3>Instructions de nettoyage</h3>
        <div class="info-value"><?php echo e($careSheet->washing_instructions); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->moisturizing_instructions): ?>
        <h3>Hydratation</h3>
        <div class="info-value"><?php echo e($careSheet->moisturizing_instructions); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->activity_restrictions): ?>
        <h3>Restrictions d'activités</h3>
        <div class="info-value"><?php echo e($careSheet->activity_restrictions); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->sun_exposure_warnings): ?>
        <h3>Exposition solaire</h3>
        <div class="info-value"><?php echo e($careSheet->sun_exposure_warnings); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$careSheet->immediate_care_instructions && !$careSheet->washing_instructions): ?>
        <ul class="checklist">
            <li>Garder le pansement initial pendant 2 à 4 heures minimum</li>
            <li>Laver délicatement la zone à l'eau tiède et au savon doux (pH neutre, sans parfum)</li>
            <li>Sécher en tamponnant avec un papier absorbant propre (ne pas frotter)</li>
            <li>Appliquer une fine couche de crème cicatrisante 2 à 3 fois par jour</li>
            <li>Ne pas gratter, ne pas arracher les croûtes ou peaux mortes</li>
            <li>Éviter les bains (piscine, mer, baignoire) pendant 3 à 4 semaines</li>
            <li>Éviter l'exposition directe au soleil pendant au moins 4 semaines</li>
            <li>Surveiller tout signe d'infection et consulter un médecin si nécessaire</li>
        </ul>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheet->healing_estimated_date || $careSheet->first_touchup_date): ?>
        <h3>Dates de suivi</h3>
        <div class="info-grid">
            <div class="info-col">
                <div class="info-label">Cicatrisation estimée</div>
                <div class="info-value"><?php echo e($careSheet->healing_estimated_date?->format('d/m/Y') ?? '—'); ?></div>
            </div>
            <div class="info-col">
                <div class="info-label">Retouche prévue</div>
                <div class="info-value"><?php echo e($careSheet->first_touchup_date?->format('d/m/Y') ?? '—'); ?></div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="alert-box mt-20">
        <strong>⚠ Important :</strong> En cas de réaction anormale (douleur persistante, gonflement excessif, écoulement
        purulent, fièvre),
        consultez immédiatement un médecin ou rendez-vous aux urgences. Vous pouvez également signaler tout effet
        indésirable
        sur le portail national des signalements : <strong>signalement.social-sante.gouv.fr</strong>
    </div>

    <div class="signature-block mt-20">
        <div class="signature-col">
            <strong>L'artiste :</strong>
            <div class="signature-line"></div>
            <div class="signature-label">Signature</div>
        </div>
        <div class="signature-spacer"></div>
        <div class="signature-col">
            <strong>Le client :</strong>
            <div class="signature-line"></div>
            <div class="signature-label">Signature (lu et approuvé)</div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\pdf\care-sheet.blade.php ENDPATH**/ ?>