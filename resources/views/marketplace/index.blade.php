@extends('layouts.app')

@section('title', 'Marketplace - Trouver un artiste')

@section('content')
    <!-- Hero Section Marketplace -->
    <section class="bg-noir-profond py-16 px-4">
        <div class="container-custom px-4">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-display font-bold text-beige-peau mb-6">
                    Trouvez l'artiste<br>
                    <span class="text-titane">fait pour vous</span>
                </h1>
                <p class="text-xl text-ivoire-text/70 mb-8 max-w-2xl mx-auto">
                    Des artistes vérifiés et professionnels près de chez vous
                </p>

                <!-- Stats rapides -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto mb-12">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-beige-peau" id="total-artists">-</div>
                        <div class="text-ivoire-text/60 text-sm">Artistes</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-beige-peau" id="total-users">-</div>
                        <div class="text-ivoire-text/60 text-sm">Utilisateurs</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-beige-peau" id="total-studios">-</div>
                        <div class="text-ivoire-text/60 text-sm">Studios</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-beige-peau" id="total-appointments">-</div>
                        <div class="text-ivoire-text/60 text-sm">Rendez-vous</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Recherche, Filtres et Résultats (Livewire) -->
    <section class="bg-gris-fonde py-8 px-4 min-h-screen">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">
                @livewire('marketplace.marketplace-search')
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', function () {
            // Charger les stats du hero
            fetch('/api/marketplace/stats')
                .then(r => r.json())
                .then(stats => {
                    document.getElementById('total-artists').textContent     = stats.total_artists     || '-';
                    document.getElementById('total-users').textContent       = stats.total_users       || '-';
                    document.getElementById('total-studios').textContent     = stats.total_studios     || '-';
                    document.getElementById('total-appointments').textContent= stats.total_appointments|| '-';
                })
                .catch(() => {});
        });
    </script>
@endpush
