

<div class="mx-3 mb-3 p-3 rounded-xl bg-gray-800/50 dark:bg-gray-800/80 border border-gray-700/50">
    <div class="grid grid-cols-2 gap-2">
        <div class="text-center">
            <p class="text-lg font-bold text-white"><?php echo e(\Illuminate\Support\Facades\Cache::remember('admin.sidebar.pending_requests', 120, fn() => \App\Models\BookingRequest::where('status', 'pending')->count())); ?></p>
            <p class="text-xs text-gray-400">En attente</p>
        </div>
        <div class="text-center">
            <p class="text-lg font-bold text-orange-400">
                <?php echo e(\Illuminate\Support\Facades\Cache::remember('admin.sidebar.pending_refunds', 120, fn() => \App\Models\BookingRequest::where('status', 'cancelled')->whereNull('refund_processed_at')->whereNotNull('deposit_paid_at')->count())); ?>

            </p>
            <p class="text-xs text-gray-400">Remboursements</p>
        </div>
    </div>
</div>

<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/filament/hooks/sidebar-nav-start.blade.php ENDPATH**/ ?>