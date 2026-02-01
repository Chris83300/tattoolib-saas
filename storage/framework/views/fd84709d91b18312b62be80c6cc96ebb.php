<?php $__env->startSection('content'); ?>
    <div class="space-y-6 lg:ml-60">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="bg-vert-succes/20 border border-vert-succes/50 text-vert-succes px-4 py-3 rounded-lg">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="bg-ambre-warning/20 border border-ambre-warning/50 text-ambre-warning px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Header -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text mb-2">Mon Calendrier</h1>
                    <p class="text-ivoire-text/70">Gérez vos rendez-vous et disponibilités</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button onclick="openCreateEventModal('appointment')"
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90">
                        + Ajouter RDV
                    </button>
                    <button onclick="openCreateEventModal('break')"
                        class="px-4 py-2 bg-ambre-warning/20 text-ambre-warning border border-ambre-warning rounded-lg font-semibold hover:bg-ambre-warning/30">
                        + Repos
                    </button>
                    <button onclick="openCreateEventModal('vacation')"
                        class="px-4 py-2 bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte rounded-lg font-semibold hover:bg-rouge-alerte/30">
                        + Vacances
                    </button>
                    <button onclick="openCreateEventModal('closure')"
                        class="px-4 py-2 bg-titane/20 text-titane border border-titane rounded-lg font-semibold hover:bg-titane/30">
                        + Fermeture
                    </button>
                </div>
            </div>
        </div>

        <!-- Légende -->
        <div class="bg-gris-fonde rounded-xl p-4">
            <div class="flex flex-wrap gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-vert-succes rounded"></div>
                    <span class="text-ivoire-text/70">Rendez-vous confirmé</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-ambre-warning rounded"></div>
                    <span class="text-ivoire-text/70">Pause / Repos</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-rouge-alerte rounded"></div>
                    <span class="text-ivoire-text/70">Vacances / Fermé</span>
                </div>
            </div>
        </div>

        <!-- Calendrier -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div id="calendar"></div>
        </div>

    </div>

    <!-- Modal Créer Événement -->
    <div id="create-event-modal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
        <div class="bg-gris-fonde rounded-xl p-6 max-w-md w-full">
            <h3 class="text-xl font-bold text-ivoire-text mb-4">Nouvel événement</h3>
            <form id="create-event-form" action="<?php echo e(route('tattooer.calendar.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" id="event-type">

                <div class="space-y-4">
                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Type</label>
                        <select id="event-type-select"
                            class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                            <option value="appointment">Rendez-vous</option>
                            <option value="break">Repos</option>
                            <option value="vacation">Vacances</option>
                            <option value="closure">Fermeture</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Titre</label>
                        <input type="text" name="title" required
                            class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-ivoire-text font-semibold mb-2">Date début</label>
                            <input type="datetime-local" name="start_datetime" required
                                class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-ivoire-text font-semibold mb-2">Date fin</label>
                            <input type="datetime-local" name="end_datetime" required
                                class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Notes (optionnel)</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90">
                        Créer
                    </button>
                    <button type="button" onclick="closeCreateEventModal()"
                        class="flex-1 px-4 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-semibold">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/fr.global.min.js'></script>

        <style>
            /* FullCalendar readability on dark Ink&Pik background */
            .fc {
                color: rgb(255 251 245);
                /* ivoire-text */
            }

            .fc .fc-toolbar-title,
            .fc .fc-button,
            .fc .fc-col-header-cell-cushion,
            .fc .fc-timegrid-axis-cushion,
            .fc .fc-timegrid-slot-label-cushion,
            .fc .fc-daygrid-day-number {
                color: rgb(255 251 245) !important;
            }

            .fc .fc-button {
                background: rgba(46, 52, 64, 0.35) !important;
                border: 1px solid rgba(46, 52, 64, 0.8) !important;
            }

            .fc .fc-button:hover {
                background: rgba(46, 52, 64, 0.55) !important;
            }

            .fc .fc-button-primary:not(:disabled).fc-button-active,
            .fc .fc-button-primary:not(:disabled):active {
                background: rgba(212, 181, 158, 0.25) !important;
                border-color: rgba(212, 181, 158, 0.6) !important;
            }

            .fc-theme-standard td,
            .fc-theme-standard th,
            .fc-theme-standard .fc-scrollgrid {
                border-color: rgba(46, 52, 64, 0.5) !important;
            }

            .fc .fc-event-title,
            .fc .fc-event-time {
                color: rgb(255 251 245) !important;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                const events = <?php echo json_encode($events, 15, 512) ?>;
                const typeSelectEl = document.getElementById('event-type-select');
                const hiddenTypeEl = document.getElementById('event-type');
                let lastCreateType = 'appointment';

                const isMobile = () => window.matchMedia('(max-width: 767px)').matches;

                function syncType(type) {
                    lastCreateType = type;
                    hiddenTypeEl.value = type;
                    if (typeSelectEl) {
                        typeSelectEl.value = type;
                    }
                }

                function defaultTitleForType(type) {
                    if (type === 'break') return 'Repos';
                    if (type === 'vacation') return 'Vacances';
                    if (type === 'closure') return 'Fermeture';
                    return 'Rendez-vous';
                }

                if (typeSelectEl) {
                    typeSelectEl.addEventListener('change', function() {
                        syncType(this.value);
                        const titleInput = document.querySelector('input[name="title"]');
                        if (titleInput && (!titleInput.value || titleInput.value === 'Rendez-vous' || titleInput
                                .value === 'Repos' || titleInput.value === 'Vacances' || titleInput.value ===
                                'Fermeture')) {
                            titleInput.value = defaultTitleForType(this.value);
                        }
                    });
                }

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: isMobile() ? 'timeGridDay' : 'timeGridWeek',
                    locale: 'fr',
                    headerToolbar: {
                        left: isMobile() ? 'prev,next' : 'prev,next today',
                        center: 'title',
                        right: isMobile() ? 'timeGridDay,dayGridMonth' : 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    slotMinTime: '08:00:00',
                    slotMaxTime: '20:00:00',
                    slotDuration: '00:30:00',
                    height: isMobile() ? 'auto' : undefined,
                    expandRows: true,
                    events: events,
                    editable: true,
                    selectable: true,

                    // Cliquer sur un événement
                    eventClick: function(info) {
                        const eventId = String(info.event.id);

                        if (confirm('Supprimer cet événement ?')) {
                            fetch(`/tattooer/calendar/${eventId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                    'Content-Type': 'application/json'
                                }
                            }).then(async (res) => {
                                if (!res.ok) {
                                    const data = await res.json();
                                    alert('Erreur lors de la suppression: ' + (data.error ||
                                        'Erreur inconnue'));
                                    return;
                                }
                                const data = await res.json();
                                if (data.success) {
                                    info.event.remove();
                                    alert('✅ ' + (data.message || 'Événement supprimé'));
                                } else {
                                    alert('❌ ' + (data.error || 'Échec de la suppression'));
                                }
                            }).catch(error => {
                                console.error('Erreur réseau:', error);
                                alert('❌ Erreur réseau: ' + error.message);
                            });
                        }
                    },

                    // Drag & drop
                    eventDrop: function(info) {
                        const eventId = String(info.event.id);
                        const isCustomEvent = /^[0-9]+$/.test(eventId);

                        if (!isCustomEvent) {
                            info.revert();
                            return;
                        }

                        fetch(`/tattooer/calendar/${eventId}`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                start_datetime: info.event.start.toISOString(),
                                end_datetime: info.event.end.toISOString()
                            })
                        });
                    },

                    // Sélection pour créer
                    select: function(info) {
                        document.querySelector('[name="start_datetime"]').value =
                            info.start.toISOString().slice(0, 16);
                        document.querySelector('[name="end_datetime"]').value =
                            info.end.toISOString().slice(0, 16);
                        openCreateEventModal(lastCreateType);
                    }
                });

                calendar.render();

                // Re-render on resize (mobile <-> desktop)
                window.addEventListener('resize', () => {
                    calendar.setOption('initialView', isMobile() ? 'timeGridDay' : 'timeGridWeek');
                    calendar.setOption('headerToolbar', {
                        left: isMobile() ? 'prev,next' : 'prev,next today',
                        center: 'title',
                        right: isMobile() ? 'timeGridDay,dayGridMonth' :
                            'dayGridMonth,timeGridWeek,timeGridDay'
                    });
                    calendar.updateSize();
                });
            });

            function openCreateEventModal(type) {
                const hiddenTypeEl = document.getElementById('event-type');
                const typeSelectEl = document.getElementById('event-type-select');
                const titleInput = document.querySelector('input[name="title"]');

                hiddenTypeEl.value = type;
                if (typeSelectEl) {
                    typeSelectEl.value = type;
                }
                if (titleInput && !titleInput.value) {
                    if (type === 'break') titleInput.value = 'Repos';
                    else if (type === 'vacation') titleInput.value = 'Vacances';
                    else if (type === 'closure') titleInput.value = 'Fermeture';
                    else titleInput.value = 'Rendez-vous';
                }
                document.getElementById('create-event-modal').classList.remove('hidden');
            }

            function closeCreateEventModal() {
                document.getElementById('create-event-modal').classList.add('hidden');
            }
        </script>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/tattooer/calendar.blade.php ENDPATH**/ ?>