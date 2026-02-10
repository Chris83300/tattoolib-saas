<div class="w-full">

    {{-- Header navigation mois --}}
    <div class="flex items-center justify-between mb-4">
        <button type="button" wire:click="previousMonth"
            class="p-2 text-titane hover:text-ivoire-text transition rounded-lg hover:bg-gris-fonde">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h3 class="text-lg font-semibold text-ivoire-text capitalize">
            {{ $monthName }} {{ $currentYear }}
        </h3>
        <button type="button" wire:click="nextMonth"
            class="p-2 text-titane hover:text-ivoire-text transition rounded-lg hover:bg-gris-fonde">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    {{-- Jours de la semaine --}}
    <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs text-titane font-medium">
        <span>Lu</span><span>Ma</span><span>Me</span><span>Je</span><span>Ve</span><span>Sa</span><span>Di</span>
    </div>

    {{-- Grille jours --}}
    <div class="grid grid-cols-7 gap-1">
        @foreach ($calendarDays as $day)
            @if ($readOnly)
                {{-- Read-only mode: use div instead of button --}}
                <div
                    class="
                        relative h-12 rounded-lg flex flex-col items-center justify-center text-sm
                        {{ $day['is_today'] ? 'border-2 border-titane' : '' }}
                        {{ $day['outside_month'] ? 'opacity-20' : '' }}
                        {{ $day['status'] === 'unavailable' ? 'opacity-30' : '' }}
                        {{ $day['status'] === 'past' ? 'opacity-20' : '' }}
                    ">
                    <span class="text-ivoire-text font-medium z-10">{{ $day['day_number'] }}</span>

                    @if (!$day['outside_month'] && $day['status'] !== 'past' && $day['status'] !== 'unavailable')
                        <div class="flex gap-0.5 mt-0.5">
                            <span
                                class="w-1.5 h-1.5 rounded-full {{ $day['morning_available'] ? 'bg-vert-succes' : 'bg-rouge-alerte/50' }}"></span>
                            <span
                                class="w-1.5 h-1.5 rounded-full {{ $day['afternoon_available'] ? 'bg-vert-succes' : 'bg-rouge-alerte/50' }}"></span>
                        </div>
                    @endif
                </div>
            @else
                {{-- Normal mode: clickable button --}}
                <button type="button" wire:click="selectDate('{{ $day['date'] }}')" @disabled($day['status'] === 'unavailable' || $day['status'] === 'past' || $day['outside_month'])
                    class="
                        relative h-12 rounded-lg flex flex-col items-center justify-center text-sm transition-all
                        {{ $day['selected'] ? 'ring-2 ring-beige-peau bg-beige-peau/10' : '' }}
                        {{ $day['is_today'] ? 'border-2 border-titane' : '' }}
                        {{ $day['outside_month'] ? 'opacity-20 cursor-default' : '' }}
                        {{ $day['status'] === 'unavailable' ? 'opacity-30 cursor-not-allowed' : '' }}
                        {{ $day['status'] === 'past' ? 'opacity-20 cursor-not-allowed' : '' }}
                        {{ in_array($day['status'], ['fully_available', 'morning_only', 'afternoon_only']) ? 'hover:bg-gris-fonde cursor-pointer' : '' }}
                    ">
                    <span class="text-ivoire-text font-medium z-10">{{ $day['day_number'] }}</span>

                    @if (!$day['outside_month'] && $day['status'] !== 'past' && $day['status'] !== 'unavailable')
                        <div class="flex gap-0.5 mt-0.5">
                            <span
                                class="w-1.5 h-1.5 rounded-full {{ $day['morning_available'] ? 'bg-vert-succes' : 'bg-rouge-alerte/50' }}"></span>
                            <span
                                class="w-1.5 h-1.5 rounded-full {{ $day['afternoon_available'] ? 'bg-vert-succes' : 'bg-rouge-alerte/50' }}"></span>
                        </div>
                    @endif
                </button>
            @endif
        @endforeach
    </div>

    {{-- Légende --}}
    <div class="flex items-center gap-4 mt-3 text-xs text-titane">
        <div class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-vert-succes"></span> Disponible
        </div>
        <div class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-rouge-alerte/50"></span> Occupé
        </div>
        <div class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-titane/30"></span> Indisponible
        </div>
    </div>

    {{-- Sélecteur période (si activé et dates sélectionnées) --}}
    @if ($showPeriodSelector && count($selectedDates) > 0)
        <div class="mt-4 space-y-2">
            @foreach ($selectedDates as $index => $sel)
                <div class="flex items-center gap-3 bg-gris-fonde rounded-lg p-3">
                    <span class="text-sm text-ivoire-text font-medium">
                        {{ \Carbon\Carbon::parse($sel['date'])->translatedFormat('D d M Y') }}
                    </span>
                    <select wire:model.live="selectedDates.{{ $index }}.period"
                        class="bg-noir-profond border border-titane/30 rounded-lg px-3 py-1.5 text-sm text-ivoire-text">
                        <option value="">Flexible</option>
                        @if ($dayAvailability[$sel['date']]['morning'] ?? false)
                            <option value="morning">Matin</option>
                        @endif
                        @if ($dayAvailability[$sel['date']]['afternoon'] ?? false)
                            <option value="afternoon">Après-midi</option>
                        @endif
                    </select>
                    <button type="button" wire:click="removeDate({{ $index }})"
                        class="text-rouge-alerte hover:text-rouge-alerte/80 ml-auto text-lg">
                        &times;
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
