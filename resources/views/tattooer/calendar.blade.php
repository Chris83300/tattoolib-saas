@extends('layouts.tattooer')

{{-- FullCalendar CSS chargé dans le <head> via @stack('styles') --}}
@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet'
          integrity='sha384-a+uEN0SA9NPmaoLnc5WsmxDS2SgEiUlzTisgqmWvaOsxzPREhiatn8EIBuY/Zsl2' crossorigin='anonymous' />
    <style>
        /* FullCalendar — thème sombre Ink&Pik */
        .fc { color: rgb(255 251 245); }

        .fc .fc-toolbar-title,
        .fc .fc-button,
        .fc .fc-col-header-cell-cushion,    
        .fc .fc-timegrid-axis-cushion,
        .fc .fc-timegrid-slot-label-cushion,
        .fc .fc-daygrid-day-number {
            color: rgb(255 255 255) !important;
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
@endpush

@section('content')
    <div class="container-custom px-4 h-full">
        <div class="max-w-6xl mx-auto h-full flex flex-col">

            @if (session('success'))
                <div class="bg-vert-succes/20 border border-vert-succes/50 text-vert-succes px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-ambre-warning/20 border border-ambre-warning/50 text-ambre-warning px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Header -->
            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-ivoire-text mb-2">Mon Calendrier</h1>
                        <p class="text-ivoire-text/70">Gérez vos rendez-vous et disponibilités</p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="button" data-action="create-event" data-event-type="appointment"
                            class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90">
                            + Ajouter RDV
                        </button>
                        <button type="button" data-action="create-event" data-event-type="break"
                            class="px-4 py-2 bg-ambre-warning/20 text-ambre-warning border border-ambre-warning rounded-lg font-semibold hover:bg-ambre-warning/30">
                            + Repos
                        </button>
                        <button type="button" data-action="create-event" data-event-type="vacation"
                            class="px-4 py-2 bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte rounded-lg font-semibold hover:bg-rouge-alerte/30">
                            + Vacances
                        </button>
                        <button type="button" data-action="create-event" data-event-type="closure"
                            class="px-4 py-2 bg-titane/20 text-titane border border-titane rounded-lg font-semibold hover:bg-titane/30">
                            + Fermeture
                        </button>
                    </div>
                </div>
            </div>

            <!-- Légende -->
            <div class="bg-gris-fonde rounded-xl p-4 mt-4">
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
            <div class="bg-gris-fonde rounded-xl p-6 mt-4">
                <div id="calendar" style="min-height: 600px;"></div>
            </div>

        </div>

        <!-- Modal Créer Événement -->
        <div id="create-event-modal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <div class="bg-gris-fonde rounded-xl p-6 max-w-md w-full">
                <h3 class="text-xl font-bold text-ivoire-text mb-4">Nouvel événement</h3>
                <form id="create-event-form" action="{{ route($tattooer->routePrefix() . '.calendar.store') }}"
                    method="POST">
                    @csrf
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
                        <button type="button" data-action="close-modal"
                            class="flex-1 px-4 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-semibold">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>

        </div>

        {{-- Quick Booking Modal (Livewire) --}}
        <livewire:tattooer.quick-booking-modal />

        {{-- Modal Détail Événement Calendrier (Alpine) --}}
        <div x-data="{
            open: false,
            event: {},
            confirmDelete: false,
            get isAppointment() { return this.event.type === 'appointment'; },
            get isPast() {
                if (!this.event.end) return false;
                return new Date(this.event.end) < new Date();
            }
        }" @open-calendar-modal.window="event = $event.detail; open = true; confirmDelete = false"
            x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-noir-profond/70" @click="open = false"></div>

            {{-- Contenu modal --}}
            <div class="relative bg-gris-fonde rounded-2xl p-6 max-w-lg w-full shadow-2xl border border-titane/20"
                @click.away="open = false">

                {{-- Header --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full" :style="'background-color:' + event.color"></div>
                        <h3 class="text-lg font-bold text-ivoire-text" x-text="event.title"></h3>
                    </div>
                    <button type="button" @click="open = false"
                        class="text-ivoire-text/50 hover:text-ivoire-text transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Date/heure --}}
                <div class="bg-noir-profond rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2 text-sm text-ivoire-text/80 mb-1">
                        <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span x-text="event.start"></span>
                        <template x-if="event.end">
                            <span>&rarr; <span x-text="event.end"></span></span>
                        </template>
                    </div>
                </div>

                {{-- Infos RDV --}}
                <template x-if="isAppointment">
                    <div class="space-y-3 mb-5">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-ivoire-text/60">Client</span>
                            <span class="text-ivoire-text font-medium"
                                x-text="event.clientPseudo || event.clientName || 'Non renseigné'"></span>
                        </div>
                        <template x-if="event.bodyZone">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-ivoire-text/60">Zone / Taille</span>
                                <span class="text-ivoire-text"
                                    x-text="event.bodyZone + (event.tattooSize ? ' · ' + event.tattooSize + ' cm' : '')"></span>
                            </div>
                        </template>
                        <template x-if="event.totalPrice > 0">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-ivoire-text/60">Prix total estimé</span>
                                <span class="text-ivoire-text font-semibold"
                                    x-text="event.totalPrice.toFixed(2) + ' €'"></span>
                            </div>
                        </template>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-ivoire-text/60">Acompte</span>
                            <template x-if="event.depositPaid">
                                <span class="px-2 py-0.5 bg-vert-succes/20 text-vert-succes rounded-full text-xs font-bold">
                                    Payé &middot; <span x-text="event.depositAmount.toFixed(2)"></span> &euro;
                                </span>
                            </template>
                            <template x-if="!event.depositPaid">
                                <span class="px-2 py-0.5 bg-jaune-alerte/20 text-jaune-alerte rounded-full text-xs font-bold">
                                    En attente
                                </span>
                            </template>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-ivoire-text/60">Statut</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                                :class="{
                                    'bg-vert-succes/20 text-vert-succes':   event.status === 'completed',
                                    'bg-beige-peau/20 text-beige-peau':     event.status === 'scheduled' || event.status === 'confirmed',
                                    'bg-rouge-alerte/20 text-rouge-alerte': event.status === 'cancelled' || event.status?.includes('no_show'),
                                }"
                                x-text="{
                                    scheduled:        'Planifié',
                                    confirmed:        'Confirmé',
                                    completed:        'Terminé',
                                    cancelled:        'Annulé',
                                    no_show_client:   'No-show client',
                                    no_show_artist:   'No-show artiste',
                                }[event.status] || event.status">
                            </span>
                        </div>
                        <template x-if="event.notes">
                            <div class="text-sm">
                                <span class="text-ivoire-text/60 block mb-1">Notes</span>
                                <p class="text-ivoire-text/80 text-xs bg-noir-profond rounded-lg p-2"
                                    x-text="event.notes"></p>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Infos non-RDV --}}
                <template x-if="!isAppointment">
                    <div class="mb-5">
                        <template x-if="event.notes">
                            <p class="text-sm text-ivoire-text/70" x-text="event.notes"></p>
                        </template>
                        <template x-if="!event.notes">
                            <p class="text-sm text-ivoire-text/50 italic">Aucune note pour cet événement.</p>
                        </template>
                    </div>
                </template>

                {{-- Boutons d'action --}}
                <div class="flex flex-wrap gap-3 pt-4 border-t border-titane/20">

                    <template x-if="isAppointment && event.bookingRequestId">
                        <a :href="'/tattooer/requests/' + event.bookingRequestId"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl text-sm font-semibold hover:bg-beige-peau/90 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Voir la demande
                        </a>
                    </template>

                    <button type="button" @click="open = false"
                        class="inline-flex items-center gap-2 px-4 py-2.5 border border-titane/30 text-ivoire-text/70 rounded-xl text-sm hover:bg-titane/10 transition-colors">
                        Fermer
                    </button>

                    <template x-if="!confirmDelete">
                        <button type="button" @click="confirmDelete = true"
                            class="ml-auto inline-flex items-center gap-2 px-4 py-2.5 text-rouge-alerte border border-rouge-alerte/30 rounded-xl text-sm hover:bg-rouge-alerte/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer
                        </button>
                    </template>

                    <template x-if="confirmDelete">
                        <div class="ml-auto flex items-center gap-2">
                            <template x-if="isAppointment && event.depositPaid">
                                <p class="text-xs text-rouge-alerte mr-2">Acompte payé — remboursement selon conditions</p>
                            </template>
                            <button type="button" @click="confirmDelete = false"
                                class="px-3 py-2 border border-titane/30 text-ivoire-text/70 rounded-lg text-xs hover:bg-titane/10 transition-colors">
                                Annuler
                            </button>
                            <form :action="'/tattooer/calendar/' + event.id" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-2 bg-rouge-alerte text-white rounded-lg text-xs font-medium hover:bg-rouge-alerte/90 transition-colors">
                                    Confirmer la suppression
                                </button>
                            </form>
                        </div>
                    </template>

                </div>
            </div>
        </div>

    </div>{{-- /container-custom --}}
@endsection

@push('scripts')
    {{-- FullCalendar JS (version unique, cohérente avec la CSS) --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'
            integrity='sha384-5JIwZN3kuxX2zKsavvNmbZ3zhZZMUtu/eQiK3BbXukpSXp0Cd2ZP4OAYKx7mrPgI' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales-all.global.min.js'
            integrity='sha384-U+2r7WQ8VGpkF1MxtmPBsTuSpuzfg7LfDgkU1S7Vi/LKJP2eyJim4hUlXogUzHeV' crossorigin='anonymous'></script>

    {{-- Configuration passée depuis PHP vers le module JS --}}
    <script nonce="{{ csp_nonce() }}">
        window.CalendarConfig = {
            events:    @json($events),
            csrfToken: '{{ csrf_token() }}',
            storeUrl:  '{{ route($tattooer->routePrefix() . '.calendar.store') }}',
        };
    </script>

    @vite('resources/js/tattooer-calendar.js')
@endpush
