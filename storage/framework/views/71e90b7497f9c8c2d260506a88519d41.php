<?php $__env->startSection('title', 'Fiche de traçabilité'); ?>
<?php $__env->startSection('doc-type', 'Fiche de traçabilité'); ?>
<?php $__env->startSection('doc-date', $generatedAt->format('d/m/Y')); ?>
<?php $__env->startSection('doc-ref', 'REF-TR-' . str_pad($record->id ?? '0', 6, '0', STR_PAD_LEFT)); ?>

<?php $__env->startSection('content'); ?>

    <div class="alert-box">
        <strong>⚕ Obligation légale :</strong> Ce document est établi conformément aux obligations de traçabilité
        applicables aux tatoueurs (identification des lots d'encres, aiguilles, et équipements stériles).
    </div>

    <h2>Informations de la session</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Date de procédure</div>
            <div class="info-value"><?php echo e($record->procedure_date?->format('d/m/Y') ?? '—'); ?></div>
            <div class="info-label">Heure de début</div>
            <div class="info-value"><?php echo e($record->procedure_start_time?->format('H:i') ?? '—'); ?></div>
            <div class="info-label">Heure de fin</div>
            <div class="info-value"><?php echo e($record->procedure_end_time?->format('H:i') ?? '—'); ?></div>
        </div>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->room_number): ?>
                <div class="info-label">Numéro de salle</div>
                <div class="info-value"><?php echo e($record->room_number); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
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

    <h2>Aiguilles utilisées</h2>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($needles)): ?>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Marque</th>
                    <th>N° de lot</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $needles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $needle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($needle['type'] ?? 'Aiguille'); ?></td>
                        <td><?php echo e($needle['brand'] ?? '—'); ?></td>
                        <td><?php echo e($needle['lot_number'] ?? '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="info-value text-muted">Aucune aiguille enregistrée.</div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <h2>Encres utilisées</h2>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($inks)): ?>
        <table>
            <thead>
                <tr>
                    <th>Marque</th>
                    <th>Couleur</th>
                    <th>N° de lot</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $inks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($ink['brand'] ?? '—'); ?></td>
                        <td><?php echo e($ink['color'] ?? '—'); ?></td>
                        <td><?php echo e($ink['lot_number'] ?? '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="info-value text-muted">Aucune encre enregistrée.</div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php
        $sterileEquipment = is_array($record->sterile_equipment) ? $record->sterile_equipment : [];
        $sterilizationDate = $sterileEquipment['sterilization_date'] ?? null;
        $sterilizationLotNumber = $sterileEquipment['sterilization_lot_number'] ?? null;
        $autoclaveCycleNumber = $sterileEquipment['autoclave_cycle_number'] ?? null;
    ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
        $sterilizationDate ||
            $sterilizationLotNumber ||
            $autoclaveCycleNumber ||
            $record->autoclave_batch_number ||
            $record->autoclave_test_date): ?>
        <h2>Stérilisation</h2>
        <div class="info-grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sterilizationDate): ?>
                <div class="info-col">
                    <div class="info-label">Date de stérilisation</div>
                    <div class="info-value"><?php echo e(\Carbon\Carbon::parse($sterilizationDate)->format('d/m/Y')); ?></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sterilizationLotNumber): ?>
                <div class="info-col">
                    <div class="info-label">N° de lot stérilisation</div>
                    <div class="info-value"><?php echo e($sterilizationLotNumber); ?></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($autoclaveCycleNumber): ?>
                <div class="info-col">
                    <div class="info-label">N° de cycle autoclave</div>
                    <div class="info-value"><?php echo e($autoclaveCycleNumber); ?></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->autoclave_batch_number): ?>
                <div class="info-col">
                    <div class="info-label">N° de lot autoclave</div>
                    <div class="info-value"><?php echo e($record->autoclave_batch_number); ?></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->autoclave_test_date): ?>
                <div class="info-col">
                    <div class="info-label">Date du test autoclave</div>
                    <div class="info-value"><?php echo e($record->autoclave_test_date->format('d/m/Y')); ?></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->getMedia('lot_photos')->count() > 0): ?>
        <h2>Photos des lots</h2>
        <div class="info-value text-muted">Photos des lots et équipements uploadées dans le système.</div>
        <div class="info-value">
            Nombre de photos : <?php echo e($record->getMedia('lot_photos')->count()); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->procedure_notes || $record->client_condition_notes || $record->equipment_notes): ?>
        <h2>Observations</h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->procedure_notes): ?>
            <h3>Notes de procédure</h3>
            <div class="info-value"><?php echo e($record->procedure_notes); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->client_condition_notes): ?>
            <h3>État du client</h3>
            <div class="info-value"><?php echo e($record->client_condition_notes); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->equipment_notes): ?>
            <h3>Notes équipement</h3>
            <div class="info-value"><?php echo e($record->equipment_notes); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="info-grid mt-10">
        <div class="info-col">
            <div class="info-label">Traçabilité vérifiée par l'artiste</div>
            <div class="info-value"><?php echo e($record->tattooer_verified_traceability ? 'Oui' : 'Non'); ?></div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->verified_at): ?>
            <div class="info-col">
                <div class="info-label">Vérifié le</div>
                <div class="info-value"><?php echo e($record->verified_at->format('d/m/Y à H:i')); ?></div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="signature-block mt-20">
        <div class="signature-col">
            <strong>Le professionnel :</strong>
            <div class="signature-line"></div>
            <div class="signature-label">Date et signature</div>
        </div>
        <div class="signature-spacer"></div>
        <div class="signature-col">
            <strong>Le client :</strong>
            <div class="signature-line"></div>
            <div class="signature-label">Date et signature</div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/pdf/traceability-record.blade.php ENDPATH**/ ?>