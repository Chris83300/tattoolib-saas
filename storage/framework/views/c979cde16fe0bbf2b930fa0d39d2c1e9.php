<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('studio-calendar');
    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        height: 'auto',
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste',
        },
        events: '<?php echo e(route("studio.planning.events")); ?>',
        eventClick: function(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        },
        eventDidMount: function(info) {
            const props = info.event.extendedProps;
            info.el.setAttribute('title',
                props.artist + '\n' + props.client + '\n' + props.type + ' · ' + props.status
            );
        },
        noEventsText: 'Aucun rendez-vous confirmé',
    });

    calendar.render();

    // Filtre artiste
    document.getElementById('artist-filter')?.addEventListener('change', function () {
        const val = this.value;
        calendar.getEvents().forEach(e => {
            if (!val || e.extendedProps.artist === val) {
                e.setProp('display', 'auto');
            } else {
                e.setProp('display', 'none');
            }
        });
    });
});
</script>
<style>
#studio-calendar {
    --fc-border-color: rgba(180,164,140,0.15);
    --fc-button-bg-color: #2A2A2A;
    --fc-button-border-color: rgba(180,164,140,0.3);
    --fc-button-hover-bg-color: #8B7355;
    --fc-button-active-bg-color: #8B7355;
    --fc-today-bg-color: rgba(139,115,85,0.12);
    --fc-page-bg-color: #1A1A1A;
    --fc-neutral-bg-color: #2A2A2A;
    --fc-list-event-hover-bg-color: rgba(139,115,85,0.1);
    color: #FFF8F0;
}
#studio-calendar .fc-button {
    font-size: 0.75rem;
    padding: 0.35rem 0.75rem;
}
#studio-calendar .fc-toolbar-title {
    font-size: 1rem;
    color: #FFF8F0;
}
#studio-calendar .fc-col-header-cell-cushion,
#studio-calendar .fc-daygrid-day-number,
#studio-calendar .fc-list-day-text,
#studio-calendar .fc-list-day-side-text {
    color: rgba(255,248,240,0.7);
    text-decoration: none;
}
#studio-calendar .fc-daygrid-day-number:hover {
    color: #8B7355;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Planning</h1>
            <p class="text-sm text-titane mt-1">Vue globale des rendez-vous confirmés de vos artistes</p>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artists->count() > 1): ?>
            <select id="artist-filter"
                class="bg-gris-fonde border border-titane/30 rounded-lg px-3 py-2 text-sm text-ivoire-text focus:outline-none focus:border-beige-peau/50">
                <option value="">Tous les artistes</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sa->user): ?>
                        <option value="<?php echo e($sa->user->name); ?>"><?php echo e($sa->user->name); ?></option>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Légende artistes -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artists->count() > 0): ?>
        <?php
            $colors = ['#8B7355', '#6B9E78', '#7B8FA1', '#A07850', '#9E6B6B', '#6B7F9E'];
        ?>
        <div class="flex flex-wrap gap-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $sa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sa->user): ?>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: <?php echo e($colors[$i % count($colors)]); ?>"></div>
                        <span class="text-xs text-ivoire-text/70"><?php echo e($sa->user->name); ?></span>
                        <span class="text-xs text-titane"><?php echo e($sa->artisan_type === 'piercer' ? '💎' : '🎨'); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Calendrier -->
    <div class="bg-gris-fonde rounded-xl p-4 lg:p-6">
        <div id="studio-calendar"></div>
    </div>

    <?php if($artists->count() === 0): ?>
        <div class="bg-gris-fonde rounded-xl p-6 text-center">
            <p class="text-sm text-titane">Aucun artiste actif. <a href="<?php echo e(route('studio.artists.create')); ?>" class="text-beige-peau hover:underline">Ajouter un artiste →</a></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\planning.blade.php ENDPATH**/ ?>