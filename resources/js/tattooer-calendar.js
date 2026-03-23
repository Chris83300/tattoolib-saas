/**
 * Tattooer Calendar — FullCalendar 6.x
 * Dépend de window.CalendarConfig défini par la blade :
 *   { events, csrfToken, storeUrl }
 */

// ─── Notification toast ───────────────────────────────────────────────────────

function showNotification(message, type = 'info') {
    const colors = {
        success: ['bg-vert-succes', 'text-white'],
        error:   ['bg-rouge-alerte', 'text-white'],
        info:    ['bg-beige-peau', 'text-noir-profond'],
    };

    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full';
    notification.classList.add(...(colors[type] ?? colors.info));

    const inner = document.createElement('div');
    inner.className = 'flex items-center gap-3';

    const text = document.createElement('span');
    text.className = 'font-medium';
    text.textContent = message; // textContent — pas d'XSS

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'ml-4 hover:opacity-75';
    btn.dataset.action = 'close-notification';
    btn.setAttribute('aria-label', 'Fermer');
    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
        + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
        + '</svg>';

    inner.appendChild(text);
    inner.appendChild(btn);
    notification.appendChild(inner);
    document.body.appendChild(notification);

    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// ─── Modal créer événement ────────────────────────────────────────────────────

function openCreateEventModal(type) {
    const hiddenTypeEl  = document.getElementById('event-type');
    const typeSelectEl  = document.getElementById('event-type-select');
    const titleInput    = document.querySelector('input[name="title"]');
    const autoTitles    = ['Rendez-vous', 'Repos', 'Vacances', 'Fermeture'];

    if (hiddenTypeEl)  hiddenTypeEl.value  = type;
    if (typeSelectEl)  typeSelectEl.value  = type;

    if (titleInput && (!titleInput.value || autoTitles.includes(titleInput.value))) {
        const defaults = { break: 'Repos', vacation: 'Vacances', closure: 'Fermeture' };
        titleInput.value = defaults[type] ?? 'Rendez-vous';
    }

    document.getElementById('create-event-modal')?.classList.remove('hidden');
}

function closeCreateEventModal() {
    document.getElementById('create-event-modal')?.classList.add('hidden');
}

// ─── Initialisation calendrier ────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', function () {
    const config = window.CalendarConfig;

    if (!config) {
        console.error('[Calendar] window.CalendarConfig manquant');
        return;
    }

    // Boutons "Ajouter RDV / Repos / ..."
    document.querySelectorAll('[data-action="create-event"]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openCreateEventModal(this.dataset.eventType);
        });
    });

    // Fermeture modal création
    document.querySelector('[data-action="close-modal"]')
        ?.addEventListener('click', closeCreateEventModal);

    // Fermeture toast par délégation
    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-action="close-notification"]')) {
            e.target.closest('.fixed')?.remove();
        }
    });

    // Paramètres URL pour ouverture automatique Quick Booking
    const urlParams = new URLSearchParams(window.location.search);
    window.bookingParams = {
        bookingRequestId: urlParams.get('book'),
        bookingDate:      urlParams.get('date'),
        bookingPeriod:    urlParams.get('period'),
    };

    // Vérification du conteneur
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error('[Calendar] Élément #calendar introuvable');
        return;
    }

    if (typeof FullCalendar === 'undefined') {
        console.error('[Calendar] FullCalendar non chargé');
        return;
    }

    // Synchronisation type ↔ sélect ↔ champ caché
    const typeSelectEl = document.getElementById('event-type-select');
    const hiddenTypeEl = document.getElementById('event-type');
    let lastCreateType = 'appointment';

    function syncType(type) {
        lastCreateType = type;
        if (hiddenTypeEl) hiddenTypeEl.value = type;
        if (typeSelectEl) typeSelectEl.value = type;
    }

    if (typeSelectEl) {
        typeSelectEl.addEventListener('change', function () {
            syncType(this.value);
            const titleInput   = document.querySelector('input[name="title"]');
            const autoTitles   = ['Rendez-vous', 'Repos', 'Vacances', 'Fermeture'];
            const defaults     = { break: 'Repos', vacation: 'Vacances', closure: 'Fermeture' };
            if (titleInput && (!titleInput.value || autoTitles.includes(titleInput.value))) {
                titleInput.value = defaults[this.value] ?? 'Rendez-vous';
            }
        });
    }

    const isMobile = () => window.matchMedia('(max-width: 767px)').matches;

    // ─── Instanciation FullCalendar ──────────────────────────────────────────

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView:   isMobile() ? 'timeGridDay' : 'dayGridMonth',
        locale:        'fr',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,timeGridDay',
        },
        timeZone:   'Europe/Paris',
        events:     config.events,
        editable:   true,
        selectable: true,

        // Clic sur un événement → modal Alpine détail
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            info.jsEvent.stopPropagation();

            const event = info.event;
            const props = event.extendedProps ?? {};

            window.dispatchEvent(new CustomEvent('open-calendar-modal', {
                detail: {
                    id:               event.id,
                    title:            event.title,
                    start:            event.start
                        ? event.start.toLocaleString('fr-FR', { dateStyle: 'long', timeStyle: 'short' })
                        : '',
                    end:              event.end
                        ? event.end.toLocaleString('fr-FR', { timeStyle: 'short' })
                        : '',
                    type:             props.type             ?? 'appointment',
                    appointmentId:    props.appointment_id   ?? null,
                    bookingRequestId: props.booking_request_id ?? null,
                    clientName:       props.client_name      ?? '',
                    clientPseudo:     props.client_pseudo    ?? '',
                    bodyZone:         props.body_zone        ?? '',
                    tattooSize:       props.tattoo_size      ?? '',
                    depositPaid:      props.deposit_paid     ?? props.depositPaid ?? false,
                    depositAmount:    props.deposit_amount   ?? 0,
                    totalPrice:       props.total_price      ?? 0,
                    status:           props.status           ?? 'scheduled',
                    notes:            props.notes            ?? '',
                    color:            event.backgroundColor  ?? '#D4B59E',
                },
            }));
        },

        // Drag & drop — uniquement pour les CalendarEvents numériques
        eventDrop: function (info) {
            const eventId = String(info.event.id);

            if (!/^[0-9]+$/.test(eventId)) {
                info.revert();
                return;
            }

            fetch(`/tattooer/calendar/${encodeURIComponent(eventId)}`, {
                method:  'PATCH',
                headers: {
                    'X-CSRF-TOKEN':  config.csrfToken,
                    'Content-Type':  'application/json',
                },
                body: JSON.stringify({
                    start_datetime: info.event.start.toISOString(),
                    end_datetime:   info.event.end ? info.event.end.toISOString() : null,
                }),
            }).catch(err => console.error('[Calendar] eventDrop error:', err));
        },

        // Sélection plage → pré-remplir les dates de la modal création
        select: function (info) {
            const startEl = document.querySelector('[name="start_datetime"]');
            const endEl   = document.querySelector('[name="end_datetime"]');
            if (startEl) startEl.value = info.start.toISOString().slice(0, 16);
            if (endEl)   endEl.value   = info.end.toISOString().slice(0, 16);
            openCreateEventModal(lastCreateType);
        },
    });

    calendar.render();
    window.calendarInstance = calendar;

    // Responsive : recalcul vue au resize
    window.addEventListener('resize', () => {
        calendar.setOption('initialView', isMobile() ? 'timeGridDay' : 'timeGridWeek');
        calendar.setOption('headerToolbar', {
            left:   isMobile() ? 'prev,next' : 'prev,next today',
            center: 'title',
            right:  isMobile()
                ? 'timeGridDay,dayGridMonth'
                : 'dayGridMonth,timeGridWeek,timeGridDay',
        });
        calendar.updateSize();
    });

    // ─── Auto-ouverture Quick Booking depuis ?book=&date=&period= ────────────

    const { bookingRequestId, bookingDate, bookingPeriod } = window.bookingParams;

    if (bookingRequestId && bookingDate) {
        const id = parseInt(bookingRequestId, 10);
        if (!isNaN(id)) {
            const tryDispatch = () => {
                if (window.Livewire) {
                    window.Livewire.dispatch('open-booking-from-chat', {
                        bookingRequestId: id,
                        date:   bookingDate,
                        period: bookingPeriod ?? 'morning',
                    });
                } else {
                    setTimeout(tryDispatch, 300);
                }
            };
            setTimeout(tryDispatch, 500);
            window.history.replaceState({}, '', window.location.pathname);
        }
    }

    // ─── Soumission formulaire création événement ────────────────────────────

    const form = document.getElementById('create-event-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const data = Object.fromEntries(new FormData(this));

            fetch(config.storeUrl, {
                method:  'POST',
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken,
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                },
                body: JSON.stringify(data),
            })
                .then(r => r.json())
                .then(result => {
                    if (result.success) {
                        window.calendarInstance.addEvent(result.event);
                        closeCreateEventModal();
                        form.reset();
                        showNotification(result.message ?? 'Événement créé', 'success');
                    } else {
                        showNotification(result.error ?? 'Erreur lors de la création', 'error');
                    }
                })
                .catch(err => {
                    console.error('[Calendar] form submit error:', err);
                    showNotification('Erreur réseau : ' + err.message, 'error');
                });
        });
    }
});

// ─── Rafraîchissement FullCalendar après création RDV via Livewire ────────────

document.addEventListener('livewire:initialized', () => {
    Livewire.on('refresh-quick-booking', () => {
        window.calendarInstance?.refetchEvents();
    });
});
