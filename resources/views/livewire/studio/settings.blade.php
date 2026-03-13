<div
    x-data="{
        tab: new URLSearchParams(window.location.search).get('tab') || 'informations',
        paymentMode: '{{ old('payment_mode', $studio->payment_mode ?? 'artist_direct') }}'
    }"
    class="space-y-4"
>
    {{-- ═══ FLASH MESSAGES ═══ --}}
    @if (session('success'))
        <div class="bg-vert-succes/20 border border-vert-succes/50 text-vert-succes px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-rouge-alerte/20 border border-rouge-alerte/50 text-rouge-alerte px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif
    @if (session('info'))
        <div class="bg-ambre-warning/20 border border-ambre-warning/50 text-ambre-warning px-4 py-3 rounded-lg text-sm">
            {{ session('info') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-rouge-alerte/20 border border-rouge-alerte/50 text-rouge-alerte px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ═══ TITRE ═══ --}}
    <h1 class="text-2xl font-bold text-ivoire-text">Paramètres du studio</h1>

    {{-- ═══ NAVIGATION DES ONGLETS ═══ --}}
    <div class="bg-gris-fonde rounded-xl p-2">
        <div class="flex gap-2 overflow-x-auto pb-1 hide-scrollbar">
            <button
                @click="tab = 'informations'"
                :class="tab === 'informations' ? 'bg-beige-peau text-noir-profond' : 'text-titane hover:text-ivoire-text'"
                class="px-4 py-2.5 rounded-lg font-semibold whitespace-nowrap text-sm transition-colors flex-shrink-0"
            >
                🏢 Informations
            </button>
            <button
                @click="tab = 'paiement'"
                :class="tab === 'paiement' ? 'bg-beige-peau text-noir-profond' : 'text-titane hover:text-ivoire-text'"
                class="px-4 py-2.5 rounded-lg font-semibold whitespace-nowrap text-sm transition-colors flex-shrink-0"
            >
                💳 Paiement
                @if (!$studio->payment_mode)
                    <span class="ml-1 inline-block w-2 h-2 rounded-full bg-ambre-warning"></span>
                @endif
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--  FORMULAIRE PRINCIPAL (Informations + mode paiement)   --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <form action="{{ route('studio.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- ────────────────────────────────────────────────── --}}
        {{--  ONGLET : INFORMATIONS                            --}}
        {{-- ────────────────────────────────────────────────── --}}
        <div x-show="tab === 'informations'" class="space-y-4">

            {{-- Informations de base --}}
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
                <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">🏢 Informations</h2>

                <div>
                    <label class="text-xs text-titane block mb-1">Nom du studio *</label>
                    <input type="text" name="name" value="{{ old('name', $studio->name) }}" required
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                    @error('name')
                        <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs text-titane block mb-1">Description</label>
                    <textarea name="description" rows="4" placeholder="Présentez votre studio..."
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none resize-none">{{ old('description', $studio->description) }}</textarea>
                </div>

                <div>
                    <label class="text-xs text-titane block mb-1">Adresse</label>
                    <input type="text" name="address" value="{{ old('address', $studio->address) }}"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <label class="text-xs text-titane block mb-1">Ville</label>
                        <input type="text" name="city" value="{{ old('city', $studio->city) }}"
                            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                    </div>
                    <div class="w-full sm:w-32">
                        <label class="text-xs text-titane block mb-1">Code postal</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $studio->postal_code) }}"
                            maxlength="5"
                            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <label class="text-xs text-titane block mb-1">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $studio->phone) }}"
                            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs text-titane block mb-1">Email professionnel</label>
                        <input type="email" name="email" value="{{ old('email', $studio->email) }}"
                            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <label class="text-xs text-titane block mb-1">Site web</label>
                        <input type="url" name="website" value="{{ old('website', $studio->website) }}"
                            placeholder="https://..."
                            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs text-titane block mb-1">SIRET</label>
                        <input type="text" name="siret" value="{{ old('siret', $studio->siret) }}" maxlength="14"
                            placeholder="14 chiffres"
                            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- Photos --}}
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
                <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">📸 Photos</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-titane block mb-2">Logo</label>
                        @if ($studio->getFirstMediaUrl('logo'))
                            <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="Logo"
                                class="w-24 h-24 rounded-lg object-cover mb-2">
                        @endif
                        <input type="file" name="logo" accept="image/*"
                            class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
                    </div>
                    <div>
                        <label class="text-xs text-titane block mb-2">Photo de couverture</label>
                        @if ($studio->getFirstMediaUrl('cover'))
                            <img src="{{ $studio->getFirstMediaUrl('cover') }}" alt="Couverture"
                                class="w-full h-24 rounded-lg object-cover mb-2">
                        @endif
                        <input type="file" name="cover" accept="image/*"
                            class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
                    </div>
                </div>

                <div>
                    <label class="text-xs text-titane block mb-2">Photos du salon (multiples)</label>
                    @if ($studio->getMedia('photos')->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-2">
                            @foreach ($studio->getMedia('photos') as $photo)
                                <div class="relative group">
                                    <img src="{{ $photo->getUrl() }}" alt="Photo salon"
                                        class="w-20 h-20 rounded-lg object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="photos[]" accept="image/*" multiple
                        class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
                </div>
            </div>

            {{-- Horaires --}}
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6" x-data="{
                daysOrder: ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'],
                hours: {{ Js::from(
                    $studio->opening_hours ?? [
                        'lundi'     => ['open' => '09:00', 'close' => '19:00', 'closed' => true],
                        'mardi'     => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                        'mercredi'  => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                        'jeudi'     => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                        'vendredi'  => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                        'samedi'    => ['open' => '10:00', 'close' => '18:00', 'closed' => false],
                        'dimanche'  => ['open' => '', 'close' => '', 'closed' => true],
                    ],
                ) }},
                toggleDay(dayName, isClosed) {
                    const day = this.hours[dayName];
                    if (isClosed) {
                        day.open = '';
                        day.close = '';
                    } else {
                        if (!day.open)  day.open  = dayName === 'samedi' ? '10:00' : '09:00';
                        if (!day.close) day.close = dayName === 'samedi' ? '18:00' : '19:00';
                    }
                }
            }">
                <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-4">🕐 Horaires d'ouverture</h2>
                <div class="space-y-2">
                    <template x-for="dayName in daysOrder" :key="dayName">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="w-24 text-sm text-ivoire-text capitalize" x-text="dayName"></span>
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="checkbox" :name="'opening_hours[' + dayName + '][closed]'"
                                    :checked="hours[dayName].closed"
                                    @change="hours[dayName].closed = $event.target.checked; toggleDay(dayName, $event.target.checked)"
                                    class="rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                                <span class="text-xs text-titane">Fermé</span>
                                <input type="hidden" :name="'opening_hours[' + dayName + '][open]'"
                                    :value="hours[dayName].open || ''">
                                <input type="hidden" :name="'opening_hours[' + dayName + '][close]'"
                                    :value="hours[dayName].close || ''">
                            </label>
                            <template x-if="!hours[dayName].closed">
                                <div class="flex items-center gap-1">
                                    <input type="time" :name="'opening_hours[' + dayName + '][open]'"
                                        x-model="hours[dayName].open"
                                        class="px-2 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                                    <span class="text-titane text-xs">→</span>
                                    <input type="time" :name="'opening_hours[' + dayName + '][close]'"
                                        x-model="hours[dayName].close"
                                        class="px-2 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Bouton sauvegarder --}}
            <button type="submit"
                class="w-full sm:w-auto px-6 py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
                Sauvegarder
            </button>
        </div>

        {{-- ────────────────────────────────────────────────── --}}
        {{--  ONGLET : PAIEMENT — Mode + Commission            --}}
        {{-- ────────────────────────────────────────────────── --}}
        <div x-show="tab === 'paiement'" class="space-y-4">

            {{-- Mode de paiement --}}
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
                <div>
                    <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-1">💳 Mode de paiement</h2>
                    <p class="text-xs text-titane">Comment les clients paient-ils les prestations de vos artistes ?</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Studio géré --}}
                    <label class="cursor-pointer" @click="paymentMode = 'studio_managed'">
                        <input type="radio" name="payment_mode" value="studio_managed"
                            {{ old('payment_mode', $studio->payment_mode ?? '') === 'studio_managed' ? 'checked' : '' }}
                            x-model="paymentMode"
                            class="peer hidden">
                        <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 transition-colors h-full">
                            <p class="font-semibold text-ivoire-text text-sm">🏦 Géré par le studio</p>
                            <p class="text-xs text-titane mt-1">
                                Le studio encaisse tout via son compte Stripe Connect.
                                Vous reversez aux artistes selon votre accord.
                            </p>
                        </div>
                    </label>

                    {{-- Paiement direct artiste --}}
                    <label class="cursor-pointer" @click="paymentMode = 'artist_direct'">
                        <input type="radio" name="payment_mode" value="artist_direct"
                            {{ old('payment_mode', $studio->payment_mode ?? 'artist_direct') === 'artist_direct' ? 'checked' : '' }}
                            x-model="paymentMode"
                            class="peer hidden">
                        <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 transition-colors h-full">
                            <p class="font-semibold text-ivoire-text text-sm">👤 Direct par artiste</p>
                            <p class="text-xs text-titane mt-1">
                                Chaque artiste a son propre compte Stripe Connect.
                                Vous prélevez une commission sur chaque paiement.
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Commission (affiché seulement en mode artist_direct) --}}
            <div x-show="paymentMode === 'artist_direct'" class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
                <div>
                    <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-1">📊 Commission artiste</h2>
                    <p class="text-xs text-titane">
                        Pourcentage prélevé sur chaque paiement client en faveur du studio.
                        Laissez vide ou à 0 pour ne prélever aucune commission.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="relative flex-1 max-w-xs">
                        <input
                            type="number"
                            name="artist_commission_rate"
                            value="{{ old('artist_commission_rate', $studio->artist_commission_rate) }}"
                            min="0" max="99.99" step="0.01"
                            placeholder="ex : 10"
                            class="w-full px-3 py-2.5 pr-8 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none"
                        >
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-titane text-sm font-semibold">%</span>
                    </div>
                    <p class="text-xs text-titane">
                        0 % = aucune commission prélevée
                    </p>
                </div>

                @if ($studio->artist_commission_rate !== null)
                    <div class="flex items-center gap-2 px-3 py-2 bg-beige-peau/10 border border-beige-peau/30 rounded-lg">
                        <span class="text-beige-peau text-sm">ℹ️</span>
                        <p class="text-xs text-beige-peau">
                            Commission actuelle :
                            <strong>{{ number_format($studio->artist_commission_rate, 2) }} %</strong>
                            par paiement client.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Note info en mode studio_managed --}}
            <div x-show="paymentMode === 'studio_managed'"
                class="bg-ambre-warning/10 border border-ambre-warning/30 rounded-xl p-4 text-xs text-ambre-warning space-y-1">
                <p class="font-semibold">⚠️ Mode Studio géré activé</p>
                <p>Les artistes rattachés à ce studio ne pourront pas configurer leur propre Stripe Connect.
                   Tous les paiements clients seront redirigés vers le compte Stripe du studio ci-dessous.</p>
            </div>

            {{-- Bouton sauvegarder --}}
            <button type="submit"
                class="w-full sm:w-auto px-6 py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
                Sauvegarder les paramètres de paiement
            </button>
        </div>

    </form>{{-- /form principal --}}

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--  STRIPE CONNECT STUDIO (hors form principal)           --}}
    {{--  Visible uniquement dans l'onglet Paiement             --}}
    {{--  et seulement en mode studio_managed                   --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'paiement' && paymentMode === 'studio_managed'" x-cloak class="space-y-4">

        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <div>
                <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-1">🔗 Compte Stripe du studio</h2>
                <p class="text-xs text-titane">
                    Pour encaisser les paiements clients, votre studio doit avoir un compte Stripe Connect actif.
                </p>
            </div>

            @if ($studio->stripe_onboarding_complete && $studio->stripe_account_id)
                {{-- ✅ Compte actif --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 bg-vert-succes/10 border border-vert-succes/30 rounded-xl">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <span class="text-2xl">✅</span>
                        <div>
                            <p class="font-semibold text-vert-succes text-sm">Compte Stripe actif</p>
                            <p class="text-xs text-titane mt-0.5">
                                ID : <code class="text-beige-peau">{{ $studio->stripe_account_id }}</code>
                            </p>
                        </div>
                    </div>
                    <form action="{{ route('studio.stripe.connect') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2.5 bg-noir-profond border border-titane/30 text-ivoire-text rounded-lg text-sm font-semibold hover:border-beige-peau transition-colors">
                            📊 Gérer sur Stripe
                        </button>
                    </form>
                </div>

            @elseif ($studio->stripe_account_id && !$studio->stripe_onboarding_complete)
                {{-- ⏳ Onboarding en cours --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 bg-ambre-warning/10 border border-ambre-warning/30 rounded-xl">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <span class="text-2xl">⏳</span>
                        <div>
                            <p class="font-semibold text-ambre-warning text-sm">Configuration en attente</p>
                            <p class="text-xs text-titane mt-0.5">
                                L'onboarding Stripe n'est pas encore terminé.
                                Cliquez pour reprendre la configuration.
                            </p>
                        </div>
                    </div>
                    <form action="{{ route('studio.stripe.connect') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2.5 bg-ambre-warning text-noir-profond rounded-lg text-sm font-semibold hover:bg-ambre-warning/90 transition-colors">
                            ▶️ Reprendre la configuration
                        </button>
                    </form>
                </div>

            @else
                {{-- ❌ Pas encore connecté --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-xl">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <span class="text-2xl">💳</span>
                        <div>
                            <p class="font-semibold text-ivoire-text text-sm">Aucun compte Stripe configuré</p>
                            <p class="text-xs text-titane mt-0.5">
                                Connectez un compte Stripe Express pour encaisser les paiements clients
                                directement sur le compte du studio.
                            </p>
                        </div>
                    </div>
                    <form action="{{ route('studio.stripe.connect') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2.5 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">
                            🔗 Connecter Stripe
                        </button>
                    </form>
                </div>
            @endif

            {{-- Infos pratiques --}}
            <div class="bg-noir-profond rounded-xl p-4 space-y-2">
                <p class="text-xs font-semibold text-ivoire-text">Comment ça fonctionne ?</p>
                <ul class="space-y-1.5 text-xs text-titane">
                    <li class="flex items-start gap-2">
                        <span class="text-beige-peau mt-0.5">›</span>
                        Le client paie l'acompte ou le solde → l'argent arrive sur <strong class="text-ivoire-text">le compte Stripe du studio</strong>.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-beige-peau mt-0.5">›</span>
                        Les artistes n'ont <strong class="text-ivoire-text">pas besoin</strong> de configurer leur propre Stripe Connect.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-beige-peau mt-0.5">›</span>
                        Vous reversez ensuite les montants aux artistes selon votre accord interne.
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ═══ 2FA ═══ --}}
    <div x-show="tab === 'informations'">
        @include('partials.two-factor-settings')
    </div>

</div>
