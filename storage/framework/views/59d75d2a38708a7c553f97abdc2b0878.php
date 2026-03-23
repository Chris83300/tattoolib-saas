<div class="space-y-6">

    <?php
        $checklist   = $studio->getOnboardingChecklist();
        $progress    = $studio->onboardingProgress();
        $showChecklist = $studio->onTrial() && !$studio->onboardingComplete();

        function trendBadge(float $pct): string {
            if ($pct > 0) return '<span class="text-xs font-semibold" style="color:#4ade80">↑ ' . abs($pct) . '%</span>';
            if ($pct < 0) return '<span class="text-xs font-semibold" style="color:#f87171">↓ ' . abs($pct) . '%</span>';
            return '<span class="text-xs" style="color:rgba(255,248,240,0.3)">= 0%</span>';
        }
    ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showChecklist): ?>
    <div class="bg-gris-fonde rounded-xl p-4 md:p-6 border border-beige-peau/20">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-bold text-beige-peau uppercase tracking-wider">Démarrage rapide</h2>
                <p class="text-xs text-titane mt-0.5">Configurez votre studio en quelques étapes</p>
            </div>
            <span class="text-sm font-bold text-beige-peau"><?php echo e($progress); ?>%</span>
        </div>
        <div class="w-full bg-noir-profond rounded-full h-2 mb-4">
            <div class="bg-beige-peau h-2 rounded-full transition-all duration-500" style="width: <?php echo e($progress); ?>%"></div>
        </div>
        <div class="space-y-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $checklist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-3 py-2 <?php echo e($step['done'] ? 'opacity-60' : ''); ?>">
                <span class="text-lg"><?php echo e($step['done'] ? '✅' : $step['icon']); ?></span>
                <span class="text-sm <?php echo e($step['done'] ? 'text-titane line-through' : 'text-ivoire-text font-medium'); ?>">
                    <?php echo e($step['label']); ?>

                </span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$step['done']): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($step['key']):
                    case ('logo'): ?>    <a href="<?php echo e(route('studio.settings')); ?>" class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a> <?php break; ?>
                    <?php case ('artist'): ?>  <a href="<?php echo e(route('studio.artists.create')); ?>" class="ml-auto text-xs text-beige-peau hover:underline">Ajouter →</a> <?php break; ?>
                    <?php case ('payment'): ?> <a href="<?php echo e(route('studio.settings')); ?>" class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a> <?php break; ?>
                    <?php case ('profile'): ?> <a href="<?php echo e(route('studio.settings')); ?>" class="ml-auto text-xs text-beige-peau hover:underline">Personnaliser →</a> <?php break; ?>
                    <?php case ('booking'): ?> <span class="ml-auto text-xs text-titane">En attente...</span> <?php break; ?>
                <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Tableau de bord</h1>
            <p class="text-sm text-titane mt-1"><?php echo e($studio->name); ?></p>
        </div>
        <?php echo $__env->make('partials.export-buttons', ['type' => 'studio', 'year' => now()->year], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">CA ce mois</p>
            <p class="text-2xl font-bold text-beige-peau"><?php echo e(number_format($stats['revenue_this_month'], 2, ',', ' ')); ?>€</p>
            <div class="flex items-center gap-1 mt-1">
                <?php echo trendBadge($stats['revenue_change_pct']); ?>

                <span class="text-[10px] text-ivoire-text/40">vs mois dernier</span>
            </div>
        </div>

        
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">RDV ce mois</p>
            <p class="text-2xl font-bold text-ivoire-text"><?php echo e($stats['bookings_this_month']); ?></p>
            <div class="flex items-center gap-1 mt-1">
                <?php echo trendBadge($stats['bookings_month_change_pct']); ?>

                <span class="text-[10px] text-ivoire-text/40">vs mois dernier</span>
            </div>
        </div>

        
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">Cette semaine</p>
            <p class="text-2xl font-bold text-ivoire-text"><?php echo e($stats['bookings_this_week']); ?></p>
            <div class="flex items-center gap-1 mt-1">
                <?php echo trendBadge($stats['bookings_week_change_pct']); ?>

                <span class="text-[10px] text-ivoire-text/40">vs semaine passée</span>
            </div>
        </div>

        
        <a href="<?php echo e(route('studio.requests')); ?>"
           class="bg-gris-fonde rounded-xl p-5 border <?php echo e($stats['pending_bookings'] > 0 ? 'border-orange-terre-cuite/40' : 'border-titane/20'); ?> hover:border-beige-peau/40 transition-colors">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">En attente</p>
            <div class="flex items-center gap-2">
                <p class="text-2xl font-bold <?php echo e($stats['pending_bookings'] > 0 ? 'text-orange-terre-cuite' : 'text-ivoire-text'); ?>">
                    <?php echo e($stats['pending_bookings']); ?>

                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['pending_bookings'] > 0): ?>
                <span class="w-2 h-2 rounded-full animate-pulse" style="background:#c2410c"></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <p class="text-[10px] text-titane mt-1">demandes à traiter</p>
        </a>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        
        <div x-data='{
            chart: <?php echo json_encode($revenueChart, 15, 512) ?>,
            get maxValue() { return Math.max(...this.chart.datasets.flatMap(d => d.data), 1); }
        }' class="bg-gris-fonde rounded-xl p-6 border border-titane/10">
            <h3 class="text-sm font-semibold text-beige-peau mb-3">Revenus par artiste — 6 derniers mois</h3>

            <div class="flex flex-wrap gap-3 mb-4">
                <template x-for="ds in chart.datasets" :key="ds.label">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" :style="'background:' + ds.color"></span>
                        <span class="text-xs text-ivoire-text/60" x-text="ds.label"></span>
                    </div>
                </template>
            </div>

            <div class="flex items-end gap-1 h-40 overflow-x-auto pb-2">
                <template x-for="(label, monthIdx) in chart.labels" :key="monthIdx">
                    <div class="flex-1 flex flex-col items-center gap-1 min-w-[52px]">
                        <div class="flex items-end gap-0.5 h-32">
                            <template x-if="chart.datasets.length === 0">
                                <div class="w-4 h-1 rounded-t" style="background:rgba(233,198,160,0.2)"></div>
                            </template>
                            <template x-for="(ds, dsIdx) in chart.datasets" :key="dsIdx">
                                <div class="w-3 rounded-t transition-all duration-500"
                                     :style="'height:' + Math.max((ds.data[monthIdx] / maxValue * 120), ds.data[monthIdx] > 0 ? 4 : 0) + 'px; background:' + ds.color"
                                     :title="ds.label + ': ' + ds.data[monthIdx] + '€'">
                                </div>
                            </template>
                        </div>
                        <span class="text-[9px] text-ivoire-text/40 text-center leading-tight" x-text="label"></span>
                    </div>
                </template>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($revenueChart['datasets'])): ?>
            <p class="text-xs text-titane text-center py-4">Aucune donnée de revenu disponible</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div x-data="{ chart: <?php echo json_encode($bookingsChart, 15, 512) ?>, get max() { return Math.max(...this.chart.map(c => c.count), 1); } }"
             class="bg-gris-fonde rounded-xl p-6 border border-titane/10">
            <h3 class="text-sm font-semibold text-beige-peau mb-3">Demandes par mois — 6 derniers mois</h3>
            <div class="flex items-end gap-2 h-40 pb-2">
                <template x-for="item in chart" :key="item.month">
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <span class="text-[9px] text-ivoire-text/50 font-semibold" x-text="item.count > 0 ? item.count : ''"></span>
                        <div class="w-full rounded-t transition-all duration-500"
                             :style="'height:' + Math.max((item.count / max * 120), item.count > 0 ? 4 : 2) + 'px; background: rgba(233,198,160,' + (item.count > 0 ? '0.7' : '0.15') + ')'"
                             :title="item.month + ': ' + item.count + ' RDV'">
                        </div>
                        <span class="text-[9px] text-ivoire-text/40 text-center leading-tight" x-text="item.month"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artistStats->count() > 0): ?>
    <div class="bg-gris-fonde rounded-xl border border-titane/10 overflow-hidden">
        <div class="p-4 flex items-center justify-between border-b border-titane/10">
            <h3 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Performance par artiste</h3>
            <a href="<?php echo e(route('studio.artists')); ?>" class="text-xs text-beige-peau hover:underline">Gérer →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-[10px] text-titane uppercase tracking-wider border-b border-titane/10">
                        <th class="px-4 py-3 text-left">Artiste</th>
                        <th class="px-3 py-3 text-center">RDV (mois)</th>
                        <th class="px-3 py-3 text-center">Tendance</th>
                        <th class="px-3 py-3 text-right">CA (mois)</th>
                        <th class="px-3 py-3 text-center hidden lg:table-cell">Note</th>
                        <th class="px-3 py-3 text-center hidden lg:table-cell">Stripe</th>
                        <th class="px-3 py-3 text-center hidden lg:table-cell">Clients</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-titane/5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $artistStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($artist['pending'] > 0 ? 'bg-orange-terre-cuite/5' : ''); ?> hover:bg-noir-profond/30 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: <?php echo e($artist['color']); ?>"></span>
                                <div>
                                    <p class="font-semibold text-ivoire-text text-xs"><?php echo e($artist['pseudo'] ?: $artist['name']); ?></p>
                                    <p class="text-[10px] text-titane"><?php echo e($artist['type']); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-center">
                            <span class="font-bold text-ivoire-text"><?php echo e($artist['bookings_this_month']); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist['pending'] > 0): ?>
                            <span class="ml-1 text-[10px] text-orange-terre-cuite font-semibold">(<?php echo e($artist['pending']); ?> att.)</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="px-3 py-3 text-center">
                            <?php echo trendBadge($artist['bookings_change_pct']); ?>

                        </td>
                        <td class="px-3 py-3 text-right font-semibold text-beige-peau text-xs">
                            <?php echo e(number_format($artist['revenue_this_month'], 2, ',', ' ')); ?>€
                            <div class="text-[10px] mt-0.5"><?php echo trendBadge($artist['revenue_change_pct']); ?></div>
                        </td>
                        <td class="px-3 py-3 text-center hidden lg:table-cell text-xs text-titane">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist['avg_rating'] > 0): ?>
                            <span class="text-yellow-400">★</span> <?php echo e($artist['avg_rating']); ?>

                            <span class="text-[10px]">(<?php echo e($artist['reviews_count']); ?>)</span>
                            <?php else: ?>
                            <span class="text-titane/40">—</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="px-3 py-3 text-center hidden lg:table-cell">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist['stripe_connected']): ?>
                            <span class="text-[10px] px-1.5 py-0.5 rounded" style="background:rgba(74,222,128,0.15);color:#4ade80">Actif</span>
                            <?php else: ?>
                            <span class="text-[10px] text-titane/50">—</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="px-3 py-3 text-center hidden lg:table-cell text-xs text-titane"><?php echo e($artist['clients_count']); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($upcomingAppointments->count() > 0): ?>
    <div class="bg-gris-fonde rounded-xl border border-titane/10 overflow-hidden">
        <div class="p-4 flex items-center justify-between border-b border-titane/10">
            <h3 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Prochains RDV</h3>
            <a href="<?php echo e(route('studio.planning')); ?>" class="text-xs text-beige-peau hover:underline">Planning →</a>
        </div>
        <div class="divide-y divide-titane/5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $date = $booking->confirmed_date ?? $booking->appointment_datetime?->toDateString();
                $clientName = trim(($booking->client?->first_name ?? '') . ' ' . ($booking->client?->last_name ?? '')) ?: 'Client';
                $artistName = $booking->bookable?->user?->name ?? 'Artiste';
            ?>
            <a href="<?php echo e(route('studio.demandes.show', $booking)); ?>"
               class="flex items-center gap-4 px-4 py-3 hover:bg-noir-profond/30 transition-colors">
                <div class="text-center w-10 flex-shrink-0">
                    <p class="text-[10px] text-titane uppercase">
                        <?php echo e($date ? \Carbon\Carbon::parse($date)->format('M') : '—'); ?>

                    </p>
                    <p class="text-xl font-bold text-beige-peau leading-tight">
                        <?php echo e($date ? \Carbon\Carbon::parse($date)->format('d') : '—'); ?>

                    </p>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate"><?php echo e($clientName); ?></p>
                    <p class="text-xs text-titane">→ <?php echo e($artistName); ?></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->total_price): ?>
                <span class="text-xs text-beige-peau font-semibold flex-shrink-0">
                    <?php echo e(number_format((float)$booking->total_price, 0, ',', ' ')); ?>€
                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gris-fonde rounded-xl p-4 border border-titane/10 text-center">
            <p class="text-2xl font-bold text-ivoire-text"><?php echo e($stats['total_clients']); ?></p>
            <p class="text-xs text-titane mt-1">Clients uniques</p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4 border border-titane/10 text-center">
            <p class="text-lg font-bold text-beige-peau"><?php echo e(number_format($stats['total_deposits'], 0, ',', ' ')); ?>€</p>
            <p class="text-xs text-titane mt-1">Acomptes encaissés</p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4 border border-titane/10 text-center">
            <p class="text-2xl font-bold text-ivoire-text"><?php echo e($stats['completed_bookings']); ?></p>
            <p class="text-xs text-titane mt-1">RDV complétés</p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4 border border-titane/10 text-center">
            <p class="text-lg font-bold text-beige-peau"><?php echo e(number_format($stats['total_revenue'], 0, ',', ' ')); ?>€</p>
            <p class="text-xs text-titane mt-1">CA total</p>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <a href="<?php echo e(route('studio.comptabilite')); ?>"
           class="flex items-center gap-3 bg-gris-fonde rounded-xl p-4 border border-titane/20 hover:border-beige-peau/40 transition-colors">
            <span class="text-2xl">📊</span>
            <div>
                <p class="text-sm font-semibold text-ivoire-text">Comptabilité</p>
                <p class="text-xs text-titane">Transactions & exports</p>
            </div>
        </a>
        <a href="<?php echo e(route('studio.conversations')); ?>"
           class="flex items-center gap-3 bg-gris-fonde rounded-xl p-4 border border-titane/20 hover:border-beige-peau/40 transition-colors">
            <span class="text-2xl">💬</span>
            <div>
                <p class="text-sm font-semibold text-ivoire-text">Conversations</p>
                <p class="text-xs text-titane">Chats de vos artistes</p>
            </div>
        </a>
        <a href="<?php echo e(route('studio.clients.index')); ?>"
           class="flex items-center gap-3 bg-gris-fonde rounded-xl p-4 border border-titane/20 hover:border-beige-peau/40 transition-colors">
            <span class="text-2xl">👥</span>
            <div>
                <p class="text-sm font-semibold text-ivoire-text">Fiches clients</p>
                <p class="text-xs text-titane"><?php echo e($stats['total_clients']); ?> client(s)</p>
            </div>
        </a>
    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/livewire/studio/dashboard.blade.php ENDPATH**/ ?>