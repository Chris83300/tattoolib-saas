@extends('layouts.tattooer')

@section('content')
    <div class="space-y-4 md:space-y-6">

        <!-- Messages Flash -->
        @if (session('success'))
            <div class="bg-vert-succes/20 border border-vert-succes/50 text-vert-succes px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rouge-alerte/20 border border-rouge-alerte/50 text-rouge-alerte px-4 py-3 rounded-lg">
                {{ session('error') }}
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
        <div>
            <h1 class="text-xl md:text-3xl font-bold text-ivoire-text mb-2">
                Paramètres
            </h1>
            <p class="text-ivoire-text/70 text-sm md:text-base">
                Gérez votre profil et vos préférences
            </p>
        </div>

        <!-- Tabs -->
        <div class="bg-gris-fonde rounded-xl p-2 -mx-4 px-4 md:mx-0 md:px-2">
            <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 hide-scrollbar snap-x snap-mandatory">
                <button onclick="switchTab('profile')"
                    class="tab-btn min-h-11 px-4 py-2.5 md:py-2 rounded-lg font-semibold whitespace-nowrap snap-start flex-shrink-0 text-sm md:text-base"
                    data-tab="profile">
                    👤 Profil
                </button>
                <button onclick="switchTab('schedule')"
                    class="tab-btn min-h-11 px-4 py-2.5 md:py-2 rounded-lg font-semibold whitespace-nowrap snap-start flex-shrink-0 text-sm md:text-base"
                    data-tab="schedule">
                    🕐 Horaires
                </button>
                <button onclick="switchTab('stripe')"
                    class="tab-btn min-h-11 px-4 py-2.5 md:py-2 rounded-lg font-semibold whitespace-nowrap snap-start flex-shrink-0 text-sm md:text-base"
                    data-tab="stripe">
                    💳 Stripe
                </button>
                <button onclick="switchTab('password')"
                    class="tab-btn min-h-11 px-4 py-2.5 md:py-2 rounded-lg font-semibold whitespace-nowrap snap-start flex-shrink-0 text-sm md:text-base"
                    data-tab="password">
                    🔒 Sécurité
                </button>
            </div>
        </div>

        <!-- TAB: Profil -->
        <div id="tab-profile" class="tab-content">
            <form action="{{ route('tattooer.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-6">

                    <!-- Avatar -->
                    <div>
                        <label class="block font-semibold text-ivoire-text mb-3">Photo de profil</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <img id="avatar-preview"
                                src="{{ $tattooer->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}"
                                alt="Avatar" class="w-24 h-24 rounded-full object-cover">

                            <div class="flex flex-col gap-2">
                                <label
                                    class="min-h-11 px-4 py-3 md:py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold cursor-pointer hover:bg-beige-peau/90 transition-colors inline-block text-center text-sm md:text-base active:scale-95">
                                    Changer photo
                                    <input type="file" name="avatar" accept="image/*" class="hidden"
                                        onchange="previewAvatar(this)">
                                </label>
                                @if ($tattooer->hasMedia('avatar'))
                                    <button type="button" onclick="deleteAvatar()"
                                        class="min-h-11 px-4 py-3 md:py-2 bg-rouge-alerte/20 text-rouge-alerte rounded-lg font-semibold hover:bg-rouge-alerte/30 transition-colors text-sm md:text-base active:scale-95">
                                        Supprimer
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Infos de base -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-semibold text-ivoire-text mb-2">Pseudo *</label>
                            <input type="text" name="pseudo" value="{{ $tattooer->user->name ?? '' }}" required
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau text-sm md:text-base">
                            <p class="text-xs text-ivoire-text/60 mt-1">Affiché sur votre profil public</p>
                        </div>

                        <div>
                            <label class="block font-semibold text-ivoire-text mb-2">Nom du salon</label>
                            <input type="text" name="studio_name" value="{{ $tattooer->studio_name ?? '' }}"
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau text-sm md:text-base">
                        </div>

                        <div>
                            <label class="block font-semibold text-ivoire-text mb-2">Email *</label>
                            <input type="email" name="email" value="{{ $tattooer->user->email }}" required
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau text-sm md:text-base">
                        </div>

                        <div>
                            <label class="block font-semibold text-ivoire-text mb-2">Téléphone *</label>
                            <input type="tel" name="phone" value="{{ $tattooer->phone ?? '' }}" required
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau text-sm md:text-base">
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-ivoire-text mb-2">Adresse</label>
                            <input type="text" name="address" value="{{ $tattooer->address ?? '' }}"
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau text-sm md:text-base">
                        </div>

                        <div>
                            <label class="block font-semibold text-ivoire-text mb-2">Ville *</label>
                            <input type="text" name="city" value="{{ $tattooer->city ?? '' }}" required
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau text-sm md:text-base">
                        </div>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block font-semibold text-ivoire-text mb-2">Bio / Présentation</label>
                        <textarea name="bio" rows="6" maxlength="1000"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau text-sm md:text-base"
                            onkeyup="updateCharCount(this, 'bio-count')">{{ $tattooer->bio ?? '' }}</textarea>
                        <p class="text-xs text-ivoire-text/60 mt-1">
                            <span id="bio-count">{{ strlen($tattooer->bio ?? '') }}</span>/1000 caractères
                        </p>
                    </div>

                    <!-- Styles pratiqués -->
                    <div>
                        <label class="block font-semibold text-ivoire-text mb-2">Styles pratiqués</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $allStyles = [
                                    'Réalisme',
                                    'Japonais',
                                    'Traditionnel',
                                    'Neo-traditionnel',
                                    'Tribal',
                                    'Dotwork',
                                    'Géométrique',
                                    'Aquarelle',
                                    'Blackwork',
                                    'Lettrage',
                                    'Minimaliste',
                                    'Ornementale',
                                ];
                                $currentStyles = is_array($tattooer->styles)
                                    ? $tattooer->styles
                                    : json_decode($tattooer->styles ?? '[]', true) ?? [];
                            @endphp

                            @foreach ($allStyles as $style)
                                <label
                                    class="flex items-center gap-2 p-3 bg-noir-profond rounded-lg cursor-pointer hover:bg-noir-profond/80 transition-colors active:scale-95">
                                    <input type="checkbox" name="styles[]" value="{{ $style }}"
                                        {{ in_array($style, $currentStyles) ? 'checked' : '' }}
                                        class="w-4 h-4 text-beige-peau focus:ring-beige-peau">
                                    <span class="text-ivoire-text text-sm">{{ $style }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bouton sauvegarder -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="w-full md:w-auto min-h-11 px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors text-sm md:text-base active:scale-95">
                            💾 Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- TAB: Horaires -->
        <div id="tab-schedule" class="tab-content hidden">
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                <h3 class="text-xl font-bold text-ivoire-text mb-4">Horaires d'ouverture</h3>

                <form action="{{ route('tattooer.settings.update-schedule') }}" method="POST">
                    @csrf

                    @php
                        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                        $workingHoursByDay = $tattooer->workingHours
                            ? $tattooer->workingHours->keyBy('day_of_week')
                            : collect();
                        $schedule = [
                            'lundi' => $workingHoursByDay->get(1),
                            'mardi' => $workingHoursByDay->get(2),
                            'mercredi' => $workingHoursByDay->get(3),
                            'jeudi' => $workingHoursByDay->get(4),
                            'vendredi' => $workingHoursByDay->get(5),
                            'samedi' => $workingHoursByDay->get(6),
                            'dimanche' => $workingHoursByDay->get(0),
                        ];
                    @endphp

                    <div class="space-y-4">
                        @foreach ($days as $day)
                            <div class="flex flex-col md:flex-row md:items-center gap-4 p-4 bg-noir-profond rounded-lg">
                                <div class="w-32">
                                    <span class="font-semibold text-ivoire-text">{{ $day }}</span>
                                </div>

                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="schedule[{{ strtolower($day) }}][open]" value="1"
                                        {{ $schedule[strtolower($day)]->is_open ?? false ? 'checked' : '' }}
                                        onchange="toggleDayInputs(this, '{{ strtolower($day) }}')" class="w-4 h-4">
                                    <span class="text-ivoire-text/70">Ouvert</span>
                                </label>

                                <div id="{{ strtolower($day) }}-inputs"
                                    class="flex flex-col sm:flex-row sm:items-center gap-4 {{ !($schedule[strtolower($day)]->is_open ?? false) ? 'hidden' : '' }}">
                                    <input type="time" name="schedule[{{ strtolower($day) }}][start]"
                                        value="{{ $schedule[strtolower($day)]->start_time ?? '09:00' }}"
                                        class="px-3 py-2 bg-gris-fonde border border-titane/30 rounded text-ivoire-text">
                                    <span class="text-ivoire-text/60 flex items-center">à</span>
                                    <input type="time" name="schedule[{{ strtolower($day) }}][end]"
                                        value="{{ $schedule[strtolower($day)]->end_time ?? '18:00' }}"
                                        class="px-3 py-2 bg-gris-fonde border border-titane/30 rounded text-ivoire-text">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="w-full md:w-auto min-h-11 px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors text-sm md:text-base active:scale-95">
                            💾 Enregistrer les horaires
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB: Stripe Connect -->
        <div id="tab-stripe" class="tab-content hidden">
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                <h3 class="text-xl font-bold text-ivoire-text mb-4">Configuration Stripe Connect</h3>

                @if ($tattooer->stripe_connect_id ?? false)
                    <div class="bg-vert-succes/20 border border-vert-succes/30 rounded-xl p-6 mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-8 h-8 text-vert-succes" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h4 class="font-bold text-vert-succes text-lg">Compte Stripe connecté</h4>
                                <p class="text-vert-succes/80 text-sm">Vous pouvez recevoir des paiements</p>
                            </div>
                        </div>

                        <a href="#" target="_blank"
                            class="inline-block px-6 py-3 bg-noir-profond text-ivoire-text rounded-lg font-semibold hover:bg-noir-profond/80 transition-colors">
                            📊 Accéder au dashboard Stripe
                        </a>
                    </div>
                @else
                    <div class="bg-ambre-warning/20 border border-ambre-warning/30 rounded-xl p-6 mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-8 h-8 text-ambre-warning" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <div>
                                <h4 class="font-bold text-ambre-warning text-lg">Compte Stripe non connecté</h4>
                                <p class="text-ambre-warning/80 text-sm">Connectez Stripe pour recevoir des paiements en
                                    ligne</p>
                            </div>
                        </div>

                        <a href="#"
                            class="inline-block px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                            🔗 Connecter Stripe Connect
                        </a>
                    </div>
                @endif

                <div class="bg-noir-profond rounded-xl p-6">
                    <h4 class="font-semibold text-ivoire-text mb-3">Avantages Stripe Connect</h4>
                    <ul class="space-y-2 text-ivoire-text/70">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Paiements sécurisés par carte bancaire</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Virements automatiques sur votre compte</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Dashboard de suivi des paiements</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- TAB: Mot de passe -->
        <div id="tab-password" class="tab-content hidden">
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                <h3 class="text-xl font-bold text-ivoire-text mb-4">Changer mon mot de passe</h3>

                <form action="{{ route('tattooer.settings.update-password') }}" method="POST" class="max-w-md">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block font-semibold text-ivoire-text mb-2">Mot de passe actuel</label>
                            <input type="password" name="current_password" required
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        </div>

                        <div>
                            <label class="block font-semibold text-ivoire-text mb-2">Nouveau mot de passe</label>
                            <input type="password" name="password" required minlength="8"
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            <p class="text-xs text-ivoire-text/60 mt-1">Minimum 8 caractères</p>
                        </div>

                        <div>
                            <label class="block font-semibold text-ivoire-text mb-2">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full md:w-auto min-h-11 px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors text-sm md:text-base active:scale-95">
                            🔒 Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('scripts')
        <style>
            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
        </style>

        <script>
            function switchTab(tabName) {
                try {
                    localStorage.setItem('tattooer_settings_active_tab', tabName);
                } catch (e) {}

                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.tab-btn').forEach(el => {
                    el.classList.remove('bg-beige-peau', 'text-noir-profond');
                    el.classList.add('text-ivoire-text');
                });

                const content = document.getElementById('tab-' + tabName);
                const btn = document.querySelector(`[data-tab="${tabName}"]`);
                if (content) content.classList.remove('hidden');
                if (btn) {
                    btn.classList.add('bg-beige-peau', 'text-noir-profond');
                    try {
                        btn.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'center'
                        });
                    } catch (e) {}
                }
            }

            let initialTab = 'profile';
            try {
                const saved = localStorage.getItem('tattooer_settings_active_tab');
                if (saved) initialTab = saved;
            } catch (e) {}
            switchTab(initialTab);

            function previewAvatar(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        document.getElementById('avatar-preview').src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function updateCharCount(textarea, countId) {
                document.getElementById(countId).textContent = textarea.value.length;
            }

            function toggleDayInputs(checkbox, day) {
                const inputs = document.getElementById(day + '-inputs');
                if (checkbox.checked) {
                    inputs.classList.remove('hidden');
                } else {
                    inputs.classList.add('hidden');
                }
            }

            function deleteAvatar() {
                if (confirm('Supprimer votre photo de profil ?')) {
                    // Implémentation de la suppression
                    alert('Fonction de suppression à implémenter');
                }
            }
        </script>
    @endpush
@endsection
