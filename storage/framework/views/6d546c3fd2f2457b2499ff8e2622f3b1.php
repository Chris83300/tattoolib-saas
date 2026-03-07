<?php $__env->startSection('title', 'Consentement éclairé'); ?>
<?php $__env->startSection('doc-type', 'Formulaire de consentement éclairé'); ?>
<?php $__env->startSection('doc-date', $generatedAt->format('d/m/Y')); ?>
<?php $__env->startSection('doc-ref', 'REF-CF-' . str_pad($consentForm->id ?? '0', 6, '0', STR_PAD_LEFT)); ?>

<?php $__env->startSection('content'); ?>

    <div class="alert-box">
        <strong>⚕ Obligation légale :</strong> Conformément à l'article R.1311-12 du Code de la Santé Publique,
        le client doit être informé des risques auxquels il s'expose avant la réalisation de l'acte.
    </div>

    <h2>Identité du client</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Nom complet</div>
            <div class="info-value"><?php echo e($consentForm->client_full_name ?? ($consentForm->full_name ?? '—')); ?></div>
            <div class="info-label">Date de naissance</div>
            <div class="info-value">
                <?php echo e($consentForm->client_birth_date?->format('d/m/Y') ?? ($consentForm->birth_date?->format('d/m/Y') ?? '—')); ?>

            </div>
            <div class="info-label">Pièce d'identité</div>
            <div class="info-value"><?php echo e($consentForm->client_id_type ?? ($consentForm->id_document_type ?? '—')); ?> —
                <?php echo e($consentForm->client_id_number ?? ($consentForm->id_document_number ?? '—')); ?></div>
        </div>
        <div class="info-col">
            <div class="info-label">Adresse</div>
            <div class="info-value"><?php echo e($consentForm->client_address ?? ($consentForm->address ?? '—')); ?></div>
            <div class="info-label">Téléphone</div>
            <div class="info-value"><?php echo e($consentForm->client_phone ?? ($consentForm->phone ?? '—')); ?></div>
            <div class="info-label">Email</div>
            <div class="info-value"><?php echo e($consentForm->client_email ?? ($consentForm->email ?? '—')); ?></div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->is_minor): ?>
        <div class="alert-box">
            <strong>Client mineur :</strong> <?php echo e($consentForm->parent_name ?? '—'); ?>

            (<?php echo e($consentForm->parent_relation ?? '—'); ?>) — N° pièce d'identité : <?php echo e($consentForm->parent_id_number ?? '—'); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <h2>Identité du professionnel</h2>
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
            <div class="info-value">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isStudio): ?>
                    <?php echo e($professional?->name ?? '—'); ?>

                <?php else: ?>
                    <?php echo e($professional?->studio_name ?? ($consentForm->studio?->name ?? 'Indépendant')); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <h2>Acte prévu</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Type d'acte</div>
            <div class="info-value"><?php echo e($consentForm->act_type ?? '—'); ?></div>
            <div class="info-label">Description</div>
            <div class="info-value"><?php echo e($consentForm->act_description ?? '—'); ?></div>
        </div>
        <div class="info-col">
            <div class="info-label">Zone corporelle</div>
            <div class="info-value"><?php echo e($consentForm->body_zone ?? ($consentForm->body_area ?? '—')); ?></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->total_price): ?>
                <div class="info-label">Prix total</div>
                <div class="info-value"><?php echo e(number_format($consentForm->total_price, 2, ',', ' ')); ?> €</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <h2>Déclarations médicales</h2>
    <table>
        <thead>
            <tr>
                <th>Question</th>
                <th>Réponse</th>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->medical_allergies_detail || $consentForm->medical_skin_disease_detail || $consentForm->medical_other): ?>
                    <th>Détail</th>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Allergies</td>
                <td><?php echo e($consentForm->medical_allergies ? 'Oui' : 'Non'); ?></td>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->medical_allergies_detail): ?>
                    <td class="text-small"><?php echo e($consentForm->medical_allergies_detail); ?></td>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tr>
            <tr>
                <td>Traitement anticoagulant</td>
                <td><?php echo e($consentForm->medical_anticoagulant ? 'Oui' : 'Non'); ?></td>
            </tr>
            <tr>
                <td>Diabète</td>
                <td><?php echo e($consentForm->medical_diabetes ? 'Oui' : 'Non'); ?></td>
            </tr>
            <tr>
                <td>Problème de cicatrisation</td>
                <td><?php echo e($consentForm->medical_cicatrisation ? 'Oui' : 'Non'); ?></td>
            </tr>
            <tr>
                <td>Maladie de peau</td>
                <td><?php echo e($consentForm->medical_skin_disease ? 'Oui' : 'Non'); ?></td>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->medical_skin_disease_detail): ?>
                    <td class="text-small"><?php echo e($consentForm->medical_skin_disease_detail); ?></td>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tr>
            <tr>
                <td>VIH / Hépatite</td>
                <td><?php echo e($consentForm->medical_vih_hepatite ? 'Oui' : 'Non'); ?></td>
            </tr>
            <tr>
                <td>Grossesse</td>
                <td><?php echo e($consentForm->medical_pregnant ? 'Oui' : 'Non'); ?></td>
            </tr>
            <tr>
                <td>Roaccutane</td>
                <td><?php echo e($consentForm->medical_roaccutane ? 'Oui' : 'Non'); ?></td>
            </tr>
            <tr>
                <td>Tendance aux chéloïdes</td>
                <td><?php echo e($consentForm->medical_cheloide ? 'Oui' : 'Non'); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->medical_other): ?>
                <tr>
                    <td>Autre condition</td>
                    <td colspan="2"><?php echo e($consentForm->medical_other); ?></td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <h2>Confirmations et consentement</h2>
    <ul class="checklist">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->confirm_medical_sincere): ?>
            <li class="checked">☑ Informations médicales fournies sincèrement et complètement</li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->confirm_risks_informed): ?>
            <li class="checked">☑ Informé(e) des risques liés à la prestation</li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->confirm_info_sheet_read): ?>
            <li class="checked">☑ Fiche d'information lue et comprise</li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->confirm_aftercare_received): ?>
            <li class="checked">☑ Consignes de soins post-prestation reçues</li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->confirm_not_intoxicated): ?>
            <li class="checked">☑ Déclare ne pas être sous l'emprise de substances</li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->confirm_over_18_or_authorized): ?>
            <li class="checked">☑ Majeur(e) ou autorisé(e) par un représentant légal</li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->image_authorization): ?>
            <li class="checked">☑ Autorise la prise de photos à des fins professionnelles</li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </ul>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->handwritten_mention): ?>
        <div class="info-grid mt-10">
            <div class="info-col">
                <div class="info-label">Mention manuscrite</div>
                <div class="info-value"><?php echo e($consentForm->handwritten_mention); ?></div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="signature-block mt-20">
        <div class="signature-col">
            <strong>Le professionnel :</strong>
            <div class="signature-line">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->signed_at): ?>
                    <div class="text-small text-muted" style="padding-top: 5px;">Signé le
                        <?php echo e($consentForm->signed_at->format('d/m/Y à H:i')); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="signature-label">Date et signature</div>
        </div>
        <div class="signature-spacer"></div>
        <div class="signature-col">
            <strong>Le client :</strong>
            <div class="signature-line">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->signature_data): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="<?php echo e($consentForm->signature_data); ?>" alt="Signature du client"
                            style="max-height: 60px; max-width: 200px;" />
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForm->signed_at): ?>
                        <div class="text-small text-muted" style="padding-top: 5px;">Signé le
                            <?php echo e($consentForm->signed_at->format('d/m/Y à H:i')); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="signature-label">Date, signature et mention « lu et approuvé »</div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\pdf\consent-form.blade.php ENDPATH**/ ?>