<?php $__env->startSection('title', 'Demande de projet - Ink&Pik'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-noir-profond min-h-screen py-10 px-4">
        <div class="container mx-auto max-w-3xl">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('booking-request-form', ['bookableId' => $bookableId, 'bookableType' => $bookableType]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-309463885-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\booking-request\show.blade.php ENDPATH**/ ?>