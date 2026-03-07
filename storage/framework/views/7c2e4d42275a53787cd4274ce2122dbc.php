<?php $__env->startSection('title', 'Reçu de prestation'); ?>
<?php $__env->startSection('doc-type', 'Reçu de prestation'); ?>
<?php $__env->startSection('doc-date', $generatedAt->format('d/m/Y')); ?>
<?php $__env->startSection('doc-ref', 'REF-REC-' . str_pad($booking->id ?? '0', 6, '0', STR_PAD_LEFT)); ?>

<?php $__env->startSection('content'); ?>

    <div class="alert-box">
        <strong>Information :</strong> Ce document est un reçu de prestation et ne constitue pas une facture
        au sens fiscal du terme. Pour toute demande de facture, veuillez contacter le professionnel directement.
    </div>

    <h2>Parties</h2>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Client</div>
            <div class="info-value"><?php echo e(trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')) ?: '—'); ?></div>
            <div class="info-label">Email</div>
            <div class="info-value"><?php echo e($client?->email ?? '—'); ?></div>
            <div class="info-label">Téléphone</div>
            <div class="info-value"><?php echo e($client?->phone ?? '—'); ?></div>
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
            <div class="info-label">Studio</div>
            <div class="info-value">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isStudio): ?>
                    <?php echo e($professional?->name ?? '—'); ?>

                <?php else: ?>
                    <?php echo e($professional?->studio?->name ?? 'Indépendant'); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <h2>Détail de la prestation</h2>
    <div class="info-grid">
        <div class="info-col">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->appointment_datetime): ?>
                <div class="info-label">Date du rendez-vous</div>
                <div class="info-value"><?php echo e($booking->appointment_datetime->format('d/m/Y à H:i')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->body_zone): ?>
                <div class="info-label">Zone corporelle</div>
                <div class="info-value"><?php echo e($booking->body_zone); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="info-col">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->tattoo_size): ?>
                <div class="info-label">Taille</div>
                <div class="info-value"><?php echo e($booking->tattoo_size); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->description): ?>
                <div class="info-label">Description</div>
                <div class="info-value"><?php echo e($booking->description); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <h2>Récapitulatif financier</h2>
    <table>
        <thead>
            <tr>
                <th>Poste</th>
                <th style="text-align: right;">Montant</th>
                <th style="text-align: right;">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->total_deposit_amount || $booking->deposit_amount): ?>
                <?php $depositAmount = $booking->total_deposit_amount ?? $booking->deposit_amount; ?>
                <tr>
                    <td>Acompte</td>
                    <td style="text-align: right;"><?php echo e(number_format($depositAmount, 2, ',', ' ')); ?> €</td>
                    <td style="text-align: right;"><?php echo e($booking->deposit_paid_at?->format('d/m/Y') ?? '—'); ?></td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->balance_amount): ?>
                <tr>
                    <td>Solde</td>
                    <td style="text-align: right;"><?php echo e(number_format($booking->balance_amount, 2, ',', ' ')); ?> €</td>
                    <td style="text-align: right;"><?php echo e($booking->balance_paid_at?->format('d/m/Y') ?? '—'); ?></td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->total_price): ?>
                <tr>
                    <td><strong>Total prestation</strong></td>
                    <td style="text-align: right;"><strong><?php echo e(number_format($booking->total_price, 2, ',', ' ')); ?>

                            €</strong></td>
                    <td></td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->deposit_paid_at): ?>
        <div class="alert-box mt-10">
            <strong>Acompte reçu :</strong> Le paiement de l'acompte a été confirmé le
            <?php echo e($booking->deposit_paid_at->format('d/m/Y à H:i')); ?>.
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->balance_paid_at): ?>
                Le solde a été réglé le <?php echo e($booking->balance_paid_at->format('d/m/Y à H:i')); ?>.
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
            <div class="signature-label">Date et signature (accusé de réception)</div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\pdf\receipt.blade.php ENDPATH**/ ?>