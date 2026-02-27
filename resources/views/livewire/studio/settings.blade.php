<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Paramètres du studio</h1>

    <form action="{{ route('studio.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ═══ INFORMATIONS DE BASE ═══ --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">🏢 Informations</h2>

            <div>
                <label class="text-xs text-titane block mb-1">Nom du studio *</label>
                <input type="text" name="name" value="{{ old('name', $studio->name) }}" required
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                @error('name') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
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
                    <input type="text" name="postal_code" value="{{ old('postal_code', $studio->postal_code) }}" maxlength="5"
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
                    <input type="url" name="website" value="{{ old('website', $studio->website) }}" placeholder="https://..."
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                </div>
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">SIRET</label>
                    <input type="text" name="siret" value="{{ old('siret', $studio->siret) }}" maxlength="14" placeholder="14 chiffres"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                </div>
            </div>
        </div>

        {{-- ═══ PHOTOS ═══ --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">📸 Photos</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-titane block mb-2">Logo</label>
                    @if ($studio->getFirstMediaUrl('logo'))
                        <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="Logo" class="w-24 h-24 rounded-lg object-cover mb-2">
                    @endif
                    <input type="file" name="logo" accept="image/*"
                        class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
                </div>
                <div>
                    <label class="text-xs text-titane block mb-2">Photo de couverture</label>
                    @if ($studio->getFirstMediaUrl('cover'))
                        <img src="{{ $studio->getFirstMediaUrl('cover') }}" alt="Couverture" class="w-full h-24 rounded-lg object-cover mb-2">
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
                                <img src="{{ $photo->getUrl() }}" alt="Photo salon" class="w-20 h-20 rounded-lg object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="file" name="photos[]" accept="image/*" multiple
                    class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
            </div>
        </div>

        {{-- ═══ MODÈLE DE PAIEMENT ═══ --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">💳 Paiement</h2>
            <p class="text-xs text-titane">Comment les clients paient-ils les prestations de vos artistes ?</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="payment_mode" value="centralized"
                        {{ (old('payment_mode', $studio->payment_mode ?? 'centralized')) === 'centralized' ? 'checked' : '' }} class="peer hidden">
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 transition-colors">
                        <p class="font-semibold text-ivoire-text text-sm">🏦 Centralisé</p>
                        <p class="text-xs text-titane mt-1">Le studio encaisse tout via un seul compte Stripe Connect. Vous reversez aux artistes.</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="payment_mode" value="distributed"
                        {{ (old('payment_mode', $studio->payment_mode ?? '')) === 'distributed' ? 'checked' : '' }} class="peer hidden">
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 transition-colors">
                        <p class="font-semibold text-ivoire-text text-sm">👤 Distribué</p>
                        <p class="text-xs text-titane mt-1">Chaque artiste a son propre Stripe Connect. Vous supervisez seulement.</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- ═══ HORAIRES ═══ --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6" x-data="{
            hours: {{ Js::from($studio->opening_hours ?? [
                'lundi'    => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'mardi'    => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'mercredi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'jeudi'    => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'vendredi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'samedi'   => ['open' => '10:00', 'close' => '18:00', 'closed' => false],
                'dimanche' => ['open' => '',      'close' => '',      'closed' => true],
            ]) }}
        }">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-4">🕐 Horaires d'ouverture</h2>
            <div class="space-y-2">
                <template x-for="(day, name) in hours" :key="name">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="w-24 text-sm text-ivoire-text capitalize" x-text="name"></span>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="checkbox" :name="'opening_hours[' + name + '][closed]'" x-model="day.closed"
                                class="rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                            <span class="text-xs text-titane">Fermé</span>
                        </label>
                        <template x-if="!day.closed">
                            <div class="flex items-center gap-1">
                                <input type="time" :name="'opening_hours[' + name + '][open]'" x-model="day.open"
                                    class="px-2 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                                <span class="text-titane text-xs">→</span>
                                <input type="time" :name="'opening_hours[' + name + '][close]'" x-model="day.close"
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
    </form>
</div>
