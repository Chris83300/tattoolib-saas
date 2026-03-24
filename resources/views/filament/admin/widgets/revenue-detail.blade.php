<x-filament-widgets::widget>
    <x-filament::section heading="Détail revenus plateforme" icon="heroicon-o-banknotes">
        @php $data = $this->getData(); @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Colonne gauche : Abonnements --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    📋 Abonnements actifs
                </h4>

                <div class="space-y-2">
                    {{-- STARTER --}}
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">STARTER</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $data['subscriptions']['starter_count'] }}
                            </span>
                            <span class="text-xs text-gray-500 ml-1">
                                × 9,99€ = {{ number_format($data['subscriptions']['starter_count'] * 9.99, 2, ',', ' ') }}€
                            </span>
                        </div>
                    </div>

                    {{-- PRO --}}
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-primary-500"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">PRO</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $data['subscriptions']['pro_count'] }}
                            </span>
                            <span class="text-xs text-gray-500 ml-1">
                                × 29,99€ = {{ number_format($data['subscriptions']['pro_count'] * 29.99, 2, ',', ' ') }}€
                            </span>
                        </div>
                    </div>

                    {{-- STUDIO --}}
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">STUDIO</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $data['subscriptions']['studio_count'] }}
                            </span>
                            <span class="text-xs text-gray-500 ml-1">
                                × 59,99€ = {{ number_format($data['subscriptions']['studio_count'] * 59.99, 2, ',', ' ') }}€
                            </span>
                        </div>
                    </div>

                    @if ($data['subscriptions']['studio_extra_count'] > 0)
                        {{-- EXTRA ARTISTES --}}
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-300"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Artistes supp.</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $data['subscriptions']['studio_extra_count'] }}
                                </span>
                                <span class="text-xs text-gray-500 ml-1">
                                    × 24,99€ = {{ number_format($data['subscriptions']['studio_extra_count'] * 24.99, 2, ',', ' ') }}€
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Total MRR --}}
                    <div class="flex items-center justify-between p-3 rounded-lg bg-primary-50 dark:bg-primary-500/10 border border-primary-200 dark:border-primary-500/20 mt-2">
                        <span class="text-sm font-semibold text-primary-700 dark:text-primary-400">MRR Total</span>
                        <span class="text-lg font-bold text-primary-700 dark:text-primary-400">
                            {{ number_format($data['subscriptions']['mrr'], 2, ',', ' ') }} €/mois
                        </span>
                    </div>

                    <p class="text-xs text-gray-400 mt-1">
                        ARR estimé : {{ number_format($data['subscriptions']['arr'], 2, ',', ' ') }} €/an
                    </p>
                </div>
            </div>

            {{-- Colonne droite : Commissions + CA Plateforme --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    💰 Revenus commissions
                </h4>

                <div class="space-y-2">
                    {{-- Commission totale --}}
                    <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Commissions 7% (total)</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ number_format($data['commissions']['total'], 2, ',', ' ') }} €
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $data['commissions']['count'] }} transactions commissionnées
                            @if ($data['commissions']['avg'] > 0)
                                · moy. {{ number_format($data['commissions']['avg'], 2, ',', ' ') }} €/tx
                            @endif
                        </p>
                    </div>

                    {{-- Commission ce mois --}}
                    <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Commissions ce mois</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ number_format($data['commissions_trend']['this_month'], 2, ',', ' ') }} €
                            </span>
                        </div>
                        <div class="flex items-center gap-1 mt-1">
                            @if ($data['commissions_trend']['change_pct'] >= 0)
                                <span class="text-xs font-semibold text-success-600 dark:text-success-400">
                                    ↑ +{{ $data['commissions_trend']['change_pct'] }}%
                                </span>
                            @else
                                <span class="text-xs font-semibold text-danger-600 dark:text-danger-400">
                                    ↓ {{ $data['commissions_trend']['change_pct'] }}%
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">vs mois dernier ({{ number_format($data['commissions_trend']['last_month'], 2, ',', ' ') }} €)</span>
                        </div>
                    </div>
                </div>

                {{-- CA Total Plateforme --}}
                <div class="mt-4 p-4 rounded-xl bg-success-50 dark:bg-success-500/10 border border-success-200 dark:border-success-500/20">
                    <p class="text-xs font-medium text-success-700 dark:text-success-400 mb-1">
                        💎 CA TOTAL PLATEFORME
                    </p>
                    <p class="text-2xl font-bold text-success-700 dark:text-success-400">
                        {{ number_format($data['commissions']['total'] + $data['subscriptions']['mrr'], 2, ',', ' ') }} €
                    </p>
                    <p class="text-xs text-success-600/70 dark:text-success-400/70 mt-1">
                        Commissions ({{ number_format($data['commissions']['total'], 2, ',', ' ') }} €)
                        + MRR ({{ number_format($data['subscriptions']['mrr'], 2, ',', ' ') }} €/mois)
                    </p>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
