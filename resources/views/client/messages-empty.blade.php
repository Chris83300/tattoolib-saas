@extends('layouts.client')

@section('title', 'Mes conversations')

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-ivoire-text mb-4">
                    💬 Mes conversations
                </h1>
                <p class="text-ivoire-text/70 text-lg">
                    Échangez avec vos tatoueurs et perceurs
                </p>
            </div>

            <!-- État vide -->
            <div class="bg-gris-fonde rounded-xl p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-beige-peau/10 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-beige-peau/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-ivoire-text mb-3">
                    Aucune conversation
                </h2>

                <p class="text-ivoire-text/60 mb-8 max-w-md mx-auto">
                    Vous n'avez pas encore de messages avec vos artistes. Commencez par faire une demande de réservation !
                </p>

                <div class="space-y-4">
                    <a href="{{ route('client.booking-requests') }}"
                        class="inline-flex items-center px-6 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond rounded-xl font-semibold transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouvelle demande
                    </a>

                    <a href="{{ route('marketplace.index') }}"
                        class="inline-flex items-center px-6 py-3 border border-beige-peau/30 text-beige-peau hover:bg-beige-peau/10 rounded-xl font-semibold transition-colors ml-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Trouver un artiste
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
