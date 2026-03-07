<?php $__env->startSection('title', 'Fiche client complète'); ?>
<?php $__env->startSection('doc-type', 'Récapitulatif fiche client'); ?>
<?php $__env->startSection('doc-date', $generatedAt->format('d/m/Y')); ?>
<?php $__env->startSection('doc-ref', 'REF-CS-' . str_pad($client->id ?? '0', 6, '0', STR_PAD_LEFT)); ?>

<?php $__env->startSection('content'); ?>

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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client?->address): ?>
                <div class="info-label">Adresse</div>
                <div class="info-value"><?php echo e($client->address); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <h2>Professionnel référent</h2>
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
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheets->isNotEmpty()): ?>
        <h2>Fiches de soins (<?php echo e($careSheets->count()); ?>)</h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $careSheets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="border: 1px solid #e0d5c8; border-radius: 4px; padding: 10px; margin-bottom: 10px;">
                <div class="info-grid">
                    <div class="info-col">
                        <div class="info-label">Date</div>
                        <div class="info-value"><?php echo e($cs->created_at?->format('d/m/Y') ?? '—'); ?></div>
                        <div class="info-label">Description</div>
                        <div class="info-value"><?php echo e($cs->tattoo_description ?? '—'); ?></div>
                    </div>
                    <div class="info-col">
                        <div class="info-label">Zone</div>
                        <div class="info-value"><?php echo e($cs->tattoo_location ?? '—'); ?></div>
                        <div class="info-label">Technique</div>
                        <div class="info-value"><?php echo e($cs->technique_used ?? '—'); ?></div>
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cs->healing_estimated_date || $cs->first_touchup_date): ?>
                    <div class="info-grid">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cs->healing_estimated_date): ?>
                            <div class="info-col">
                                <div class="info-label">Cicatrisation estimée</div>
                                <div class="info-value"><?php echo e($cs->healing_estimated_date->format('d/m/Y')); ?></div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cs->first_touchup_date): ?>
                            <div class="info-col">
                                <div class="info-label">Retouche prévue</div>
                                <div class="info-value"><?php echo e($cs->first_touchup_date->format('d/m/Y')); ?></div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentForms->isNotEmpty()): ?>
        <h2>Consentements signés (<?php echo e($consentForms->count()); ?>)</h2>
        <table>
            <thead>
                <tr>
                    <th>Date signature</th>
                    <th>Type d'acte</th>
                    <th>Zone</th>
                    <th>Mineur</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $consentForms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($cf->signed_at?->format('d/m/Y') ?? ($cf->created_at?->format('d/m/Y') ?? '—')); ?></td>
                        <td><?php echo e($cf->act_type ?? '—'); ?></td>
                        <td><?php echo e($cf->body_zone ?? '—'); ?></td>
                        <td><?php echo e($cf->is_minor ? 'Oui' : 'Non'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($traceRecords->isNotEmpty()): ?>
        <h2>Fiches de traçabilité (<?php echo e($traceRecords->count()); ?>)</h2>
        <table>
            <thead>
                <tr>
                    <th>Date procédure</th>
                    <th>Aiguilles</th>
                    <th>Encres</th>
                    <th>N° lot autoclave</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $traceRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($tr->procedure_date?->format('d/m/Y') ?? '—'); ?></td>
                        <td><?php echo e(count($tr->needles_used ?? [])); ?></td>
                        <td><?php echo e(count($tr->inks_used ?? [])); ?></td>
                        <td><?php echo e($tr->autoclave_batch_number ?? '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($careSheets->isEmpty() && $consentForms->isEmpty() && $traceRecords->isEmpty()): ?>
        <div class="alert-box mt-10">
            Aucun document enregistré pour ce client.
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="info-grid mt-20">
        <div class="info-col">
            <div class="info-label">Document généré le</div>
            <div class="info-value"><?php echo e($generatedAt->format('d/m/Y à H:i')); ?></div>
        </div>
        <div class="info-col">
            <div class="info-label">Total documents</div>
            <div class="info-value">
                <?php echo e($careSheets->count()); ?> fiche(s) de soins,
                <?php echo e($consentForms->count()); ?> consentement(s),
                <?php echo e($traceRecords->count()); ?> traçabilité(s)
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\pdf\client-summary.blade.php ENDPATH**/ ?>