@props(['type' => 'artist', 'year' => now()->year])

<div class="flex flex-wrap items-center gap-3">
    @if ($type === 'artist')
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gris-fonde text-titane hover:text-beige-peau border border-titane/20 hover:border-beige-peau/30 rounded-lg transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter
            </button>
            <div
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-60 bg-gris-fonde border border-titane/20 rounded-lg shadow-lg z-50 py-1"
            >
                <a href="{{ route('export.artist.transactions', ['format' => 'xlsx']) }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Transactions (Excel)</span>
                </a>
                <a href="{{ route('export.artist.transactions', ['format' => 'csv']) }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Transactions (CSV)</span>
                </a>
                <div class="border-t border-titane/10 my-1"></div>
                <a href="{{ route('export.artist.full', ['year' => $year]) }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Comptabilité {{ $year }} (Excel)</span>
                </a>
                <a href="{{ route('export.artist.monthly', ['year' => $year, 'format' => 'csv']) }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Récap mensuel {{ $year }} (CSV)</span>
                </a>
                <a href="{{ route('export.artist.monthly', ['year' => $year - 1, 'format' => 'csv']) }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Récap mensuel {{ $year - 1 }} (CSV)</span>
                </a>
            </div>
        </div>
    @elseif ($type === 'studio')
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gris-fonde text-titane hover:text-beige-peau border border-titane/20 hover:border-beige-peau/30 rounded-lg transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter Studio
            </button>
            <div
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-60 bg-gris-fonde border border-titane/20 rounded-lg shadow-lg z-50 py-1"
            >
                <a href="{{ route('export.studio.transactions', ['format' => 'xlsx']) }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Transactions (Excel)</span>
                </a>
                <a href="{{ route('export.studio.transactions', ['format' => 'csv']) }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Transactions (CSV)</span>
                </a>
            </div>
        </div>
    @endif
</div>
