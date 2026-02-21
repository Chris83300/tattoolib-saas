<div class="h-screen flex flex-col bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b px-6 py-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-[#0A0A0A]">Mon calendrier</h1>
            <div class="flex items-center space-x-4">
                <!-- Bouton créer événement -->
                <button wire:click="openCreateModal"
                    class="px-4 py-2 bg-[#D4B59E] text-white rounded-md hover:bg-[#C4A68E] transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter un événement
                </button>

                <!-- Légende -->
                <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-[#06D6A0] rounded-full mr-1"></div>
                        <span>RDV</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-[#F77F00] rounded-full mr-1"></div>
                        <span>Pause</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-[#E63946] rounded-full mr-1"></div>
                        <span>Vacances</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-[#2E3440] rounded-full mr-1"></div>
                        <span>Fermé</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages flash -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-6 mt-4 rounded">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(session()->has('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mt-4 rounded">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Calendrier -->
    <div class="flex-1 p-6">
        <div wire:ignore class="h-full bg-white rounded-lg shadow-lg">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Modal Détails Événement -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showEventModal && $selectedEvent): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-[#0A0A0A]">
                        <?php echo e($selectedEvent->getTitle()); ?>

                    </h3>
                    <button wire:click="$set('showEventModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Type -->
                    <div>
                        <span class="text-sm text-gray-600">Type:</span>
                        <span class="ml-2 px-2 py-1 rounded text-xs font-medium"
                            style="background-color: <?php echo e($selectedEvent->color); ?>20; color: <?php echo e($selectedEvent->color); ?>">
                            <?php echo e($selectedEvent->type_label); ?>

                        </span>
                    </div>

                    <!-- Dates -->
                    <div>
                        <span class="text-sm text-gray-600">Début:</span>
                        <span class="ml-2"><?php echo e($selectedEvent->start_datetime->format('d/m/Y H:i')); ?></span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Fin:</span>
                        <span class="ml-2"><?php echo e($selectedEvent->end_datetime->format('d/m/Y H:i')); ?></span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Durée:</span>
                        <span class="ml-2"><?php echo e($selectedEvent->getFormattedDuration()); ?></span>
                    </div>

                    <!-- Infos RDV -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedEvent->type === 'appointment' && $selectedEvent->bookingRequest): ?>
                        <div class="border-t pt-4">
                            <h4 class="font-medium text-gray-900 mb-2">Détails du rendez-vous</h4>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-gray-600">Client:</span>
                                    <span class="ml-2"><?php echo e($selectedEvent->bookingRequest->client->full_name); ?></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Projet:</span>
                                    <span
                                        class="ml-2"><?php echo e($selectedEvent->bookingRequest->tattoo_description); ?></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Emplacement:</span>
                                    <span class="ml-2"><?php echo e($selectedEvent->bookingRequest->tattoo_location); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedEvent->bookingRequest->deposit_amount): ?>
                                    <div>
                                        <span class="text-gray-600">Acompte:</span>
                                        <span
                                            class="ml-2"><?php echo e(number_format($selectedEvent->bookingRequest->deposit_amount, 2)); ?>€</span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedEvent->bookingRequest->isDepositPaid()): ?>
                                            <span class="text-green-600 ml-1">✓ Payé</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Notes -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedEvent->notes): ?>
                        <div>
                            <span class="text-sm text-gray-600">Notes:</span>
                            <p class="mt-1 text-sm"><?php echo e($selectedEvent->notes); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 mt-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedEvent->canBeDeleted()): ?>
                        <button wire:click="deleteEvent(<?php echo e($selectedEvent->id); ?>)"
                            class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-md transition-colors">
                            Supprimer
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button wire:click="$set('showEventModal', false)"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Modal Création Événement -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCreateModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-[#0A0A0A]">Nouvel événement</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="createEvent" class="space-y-4">
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type d'événement *</label>
                        <select wire:model="eventType"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                            <option value="break">Pause</option>
                            <option value="vacation">Vacances</option>
                            <option value="closure">Fermé</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['eventType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Titre -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
        <input type="text" wire:model="eventTitle"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['eventTitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Dates -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date de début *</label>
            <input type="date" wire:model="eventStartDate"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['eventStartDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Heure de début *</label>
            <input type="time" wire:model="eventStartTime"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['eventStartTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin *</label>
            <input type="date" wire:model="eventEndDate"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['eventEndDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Heure de fin *</label>
            <input type="time" wire:model="eventEndTime"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['eventEndTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Notes -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
        <textarea wire:model="eventNotes" rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]"></textarea>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['eventNotes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <button type="button" wire:click="$set('showCreateModal', false)"
            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
            Annuler
        </button>
        <button type="submit" wire:loading.attr="disabled"
            class="px-4 py-2 bg-[#D4B59E] text-white rounded-md hover:bg-[#C4A68E] transition-colors disabled:opacity-50">
            <span wire:loading.remove>Créer</span>
            <span wire:loading>Création...</span>
        </button>
    </div>
    </form>
    </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00',
                events: <?php echo \Illuminate\Support\Js::from($events)->toHtml() ?>,
                editable: true,
                selectable: true,
                eventClick: function(info) {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('showEventDetails', info.event.id);
                },
                select: function(info) {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('openCreateModal', {
                        start_date: info.startStr.split('T')[0],
                        start_time: info.startStr.split('T')[1].substring(0, 5),
                        end_date: info.endStr.split('T')[0],
                        end_time: info.endStr.split('T')[1].substring(0, 5),
                        type: 'break'
                    });
                },
                eventDrop: function(info) {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('updateEvent', info.event.id, {
                        start: info.event.start.toISOString(),
                        end: info.event.end.toISOString()
                    });
                },
                eventResize: function(info) {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('updateEvent', info.event.id, {
                        start: info.event.start.toISOString(),
                        end: info.event.end.toISOString()
                    });
                }
            });

            calendar.render();

            // Écouter les événements Livewire
            document.addEventListener('livewire:init', () => {
                Livewire.on('eventCreated', () => {
                    calendar.refetchEvents();
                });

                Livewire.on('eventUpdated', () => {
                    calendar.refetchEvents();
                });

                Livewire.on('eventDeleted', () => {
                    calendar.refetchEvents();
                });
            });
        });
    </script>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\tattooer-calendar.blade.php ENDPATH**/ ?>