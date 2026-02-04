<x-filament-panels::page>

    <!-- Header Studio -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ auth()->user()->studio->name }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Plan STUDIO • {{ auth()->user()->studio->artists->count() }} artiste(s)
                </p>
            </div>

            <div class="flex gap-3">
                <x-filament::button href="{{ route('marketplace.studio.show', auth()->user()->studio->slug) }}"
                    target="_blank" icon="heroicon-o-arrow-top-right-on-square" color="gray">
                    Voir profil public
                </x-filament::button>

                <x-filament::button href="/admin/studio/resources/studio-artists/create" icon="heroicon-o-plus">
                    Ajouter un artiste
                </x-filament::button>
            </div>
        </div>
    </div>

    <!-- Widgets -->
    <x-filament-widgets::widgets :widgets="$this->getWidgets()" :columns="$this->getColumns()" />

</x-filament-panels::page>
