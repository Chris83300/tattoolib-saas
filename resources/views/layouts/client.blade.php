<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Ink&Pik</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&family=Satoshi:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-noir-profond">

    <div class="flex min-h-screen max-w-full overflow-x-hidden">

        <!-- Sidebar Desktop (cachée sur mobile) -->
        <aside
            class="hidden lg:flex lg:flex-col lg:w-64 bg-gris-fonde border-r border-titane/20 fixed h-full top-0 left-0 z-10">

            <!-- Logo -->
            <div class="p-6 border-b border-titane/20">
                <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Ink&Pik" class="w-12 h-12">
                    </div>
                    <span class="text-ivoire-text font-bold text-lg">Ink&Pik</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('home') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="font-semibold">Accueil</span>
                </a>

                <a href="{{ route('marketplace.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('marketplace.*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2zm14 0V9a2 2 0 00-2-2M5 11a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2z">
                        </path>
                    </svg>
                    <span class="font-semibold">Marketplace</span>
                </a>

                <a href="{{ route('client.booking-requests') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.booking-requests*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span class="font-semibold">Mes demandes</span>
                </a>

                <a href="{{ route('client.messages') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.messages*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span class="font-semibold">Messages</span>
                    @if (($clientUnreadCount ?? 0) > 0)
                        <span
                            class="ml-auto bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs font-bold">
                            {{ $clientUnreadCount > 99 ? '99+' : $clientUnreadCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('client.reviews') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.reviews*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    <span class="font-semibold">Mes avis</span>
                </a>

                <a href="{{ route('client.complaints') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.complaints*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <span class="font-semibold">Reclamations</span>
                </a>

                <a href="{{ route('client.profile') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.profile') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>
                    </svg>
                    <span class="font-semibold">Mon profil</span>
                </a>

                <a href="{{ route('client.settings') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.settings') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 002.573 1.066c1.543-.94 3.31-.826 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                    </svg>
                    <span class="font-semibold">Paramètres</span>
                </a>
            </nav>

            @php
                // Récupérer l'utilisateur et charger la relation client de manière plus robuste
$user = auth()->user();
$client = null;

try {
    // Essayer de récupérer le client directement
    if ($user->id) {
        $client = \App\Models\Client::where('user_id', $user->id)->first();
    }
} catch (\Exception $e) {
    // En cas d'erreur, on continue sans avatar
                    $client = null;
                }
            @endphp

            <!-- User info -->
            <div class="p-4 border-t border-titane/20">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-noir-profond">
                    @if ($client)
                        @php
                            $avatarUrl = $client->getFirstMediaUrl('avatar');
                            // Si pas d'avatar, utiliser l'image par défaut
                            if (!$avatarUrl || $avatarUrl === 'http://tattoolib-saas.test/images/default-avatar.png') {
                                $avatarUrl = null; // Forcer l'affichage de l'avatar par défaut
                            }
                        @endphp
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="Avatar" class="w-10 h-10 rounded-full">
                        @else
                            <svg class="w-10 h-10 text-beige-peau" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    @else
                        <svg class="w-10 h-10 text-beige-peau" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd"></path>
                        </svg>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-ivoire-text font-semibold truncate">{{ $user->name }}</p>
                        <p class="text-ivoire-text/60 text-xs">Client</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-ivoire-text/60 hover:text-rouge-alerte transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 overflow-x-hidden overflow-y-auto min-w-0 w-full h-screen">

            <!-- Content -->
            <div class="p-4 lg:p-8 pb-24 lg:pb-8 max-w-full overflow-y-auto">
                @yield('content')
                @include('partials.footer-legal')
            </div>
        </main>

        <!-- Bottom Navigation Mobile (visible sur toutes les pages) -->
        <x-ui.bottom-nav class="lg:hidden" />
    </div>

    <!-- ═══ MODAL AVIS ═══ -->
    <div id="review-modal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
        onclick="if(event.target===this)closeReviewModal()">
        <div class="bg-gris-fonde rounded-xl p-6 w-full max-w-md shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-ivoire-text">⭐ Votre avis</h3>
                <button onclick="closeReviewModal()" class="text-titane hover:text-ivoire-text transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="review-form" class="space-y-4">
                @csrf
                <input type="hidden" name="reviewable_id" id="review-br-id">
                <input type="hidden" name="reviewable_type" value="App\Models\BookingRequest">

                <!-- Étoiles -->
                <div>
                    <label class="text-sm text-ivoire-text/60 block mb-2">Note *</label>
                    <div class="flex gap-1" id="star-container"></div>
                    <input type="hidden" name="rating" id="review-rating" required>
                </div>

                <!-- Commentaire -->
                <div>
                    <label class="text-sm text-ivoire-text/60 block mb-1">Commentaire</label>
                    <textarea name="comment" id="review-comment" rows="4" placeholder="Comment s'est passée votre séance ?"
                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau resize-none"></textarea>
                </div>

                <!-- Bouton submit -->
                <button type="submit" id="review-submit-btn"
                    class="w-full px-4 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors">
                    Envoyer mon avis
                </button>
            </form>
        </div>
    </div>

    <script>
        (function() {
            var currentRating = 0;

            // Créer les étoiles
            var container = document.getElementById('star-container');
            if (container) {
                for (var i = 1; i <= 5; i++) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.dataset.star = i;
                    btn.textContent = '★';
                    btn.className = 'text-3xl text-titane/40 hover:text-ambre-warning transition-colors cursor-pointer';
                    btn.addEventListener('click', (function(rating) {
                        return function() {
                            setRating(rating);
                        };
                    })(i));
                    container.appendChild(btn);
                }
            }

            function setRating(n) {
                currentRating = n;
                document.getElementById('review-rating').value = n;
                document.querySelectorAll('#star-container button').forEach(function(btn) {
                    var star = parseInt(btn.dataset.star);
                    btn.className = star <= n ?
                        'text-3xl text-ambre-warning transition-colors cursor-pointer' :
                        'text-3xl text-titane/40 hover:text-ambre-warning transition-colors cursor-pointer';
                });
            }

            window.openReviewModal = function(brId) {
                document.getElementById('review-br-id').value = brId;
                document.getElementById('review-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                currentRating = 0;
                document.getElementById('review-rating').value = '';
                document.getElementById('review-comment').value = '';
                document.querySelectorAll('#star-container button').forEach(function(btn) {
                    btn.className =
                        'text-3xl text-titane/40 hover:text-ambre-warning transition-colors cursor-pointer';
                });
            };

            window.closeReviewModal = function() {
                document.getElementById('review-modal').classList.add('hidden');
                document.body.style.overflow = '';
            };

            // Soumission
            var form = document.getElementById('review-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var brId = document.getElementById('review-br-id').value;
                    var rating = document.getElementById('review-rating').value;
                    var comment = document.getElementById('review-comment').value;

                    if (!rating || rating < 1) {
                        alert('Veuillez sélectionner une note (1 à 5 étoiles)');
                        return;
                    }

                    var btn = document.getElementById('review-submit-btn');
                    btn.disabled = true;
                    btn.textContent = 'Envoi en cours...';

                    fetch('/client/reviews/' + brId, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({
                                reviewable_id: parseInt(brId),
                                reviewable_type: 'App\\Models\\BookingRequest',
                                rating: parseInt(rating),
                                comment: comment
                            })
                        })
                        .then(function(res) {
                            return res.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                closeReviewModal();
                                // Remplacer le bouton par un message de succès
                                var successDiv = document.createElement('div');
                                successDiv.className =
                                    'bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-3 my-3 text-center';
                                successDiv.innerHTML =
                                    '<p class="text-sm text-vert-succes">✅ Merci pour votre avis !</p>';

                                var reviewBtn = document.querySelector('[onclick*="openReviewModal(' +
                                    brId + ')"]');
                                if (reviewBtn) {
                                    var parent = reviewBtn.closest('.bg-beige-peau\\/10') || reviewBtn
                                        .parentElement;
                                    parent.replaceWith(successDiv);
                                }

                                alert('✅ Merci ! Votre avis a été enregistré.');
                            } else {
                                alert(data.message || 'Erreur lors de l\'envoi');
                            }
                        })
                        .catch(function(err) {
                            console.error(err);
                            alert('Erreur réseau');
                        })
                        .finally(function() {
                            btn.disabled = false;
                            btn.textContent = 'Envoyer mon avis';
                        });
                });
            }

            // Fermer avec Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('review-modal').classList.contains(
                        'hidden')) {
                    closeReviewModal();
                }
            });
        })();
    </script>

    @stack('scripts')
    @livewireScripts

    {{-- Chat support admin (bouton flottant) --}}
    @auth
        @livewire('admin-chat')
    @endauth
</body>

</html>
