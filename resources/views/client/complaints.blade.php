@extends('layouts.client')

@section('title', 'Réclamations')

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-6">
                <a href="{{ route('client.dashboard') }}"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour au tableau de bord
                </a>

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-ivoire-text">Réclamations</h1>
                        <p class="text-ivoire-text/70 mt-1">Suivez l'état de vos réclamations</p>
                    </div>
                </div>
            </div>

            <!-- Formulaire de réclamation -->
            <div class="bg-titane/20 rounded-xl p-6 border border-titane/30 mb-8">
                <h2 class="text-xl font-bold text-ivoire-text mb-4">Nouvelle réclamation</h2>
                <form action="{{ route('client.complaints.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Type de réclamation</label>
                        <select name="type"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau">
                            <option value="">Choisir un type...</option>
                            <option value="no_show">No-show (artiste absent)</option>
                            <option value="quality">Qualité du travail</option>
                            <option value="hygiene">Hygiène</option>
                            <option value="payment">Paiement</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau"
                            placeholder="Décrivez votre réclamation..."></textarea>
                    </div>
                    <div>
                        <label class="block text-ivoire-text font-semibold mb-2">Demande concernée (optionnel)</label>
                        <select name="booking_request_id"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau">
                            <option value="">Choisir une demande...</option>
                            @foreach (auth()->user()->client->bookingRequests as $bookingRequest)
                                <option value="{{ $bookingRequest->id }}">{{ $bookingRequest->description }} -
                                    {{ $bookingRequest->bookable->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-rouge-alerte hover:bg-rouge-alerte/90 text-noir-profond rounded-lg font-semibold transition-colors">
                            Soumettre la réclamation
                        </button>
                    </div>
                </form>
            </div>

            <!-- Liste des réclamations -->
            <div class="space-y-4">
                @if ($complaints->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-titane/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-ivoire-text/30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-ivoire-text mb-2">Aucune réclamation</h3>
                        <p class="text-ivoire-text/60">Vous n'avez pas encore soumis de réclamation.</p>
                    </div>
                @else
                    @foreach ($complaints as $complaint)
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-ivoire-text">
                                        {{ $complaint->type_label }}
                                    </h3>
                                    <p class="text-ivoire-text/70 text-sm">
                                        Soumis le {{ $complaint->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                        @switch($complaint->status)
                                            @case('pending')
                                                bg-jaune-alerte/20 text-jaune-alerte
                                            @break
                                            @case('investigating')
                                                bg-ambre-warning/20 text-ambre-warning
                                            @break
                                            @case('resolved')
                                                bg-vert-succes/20 text-vert-succes
                                            @break
                                            @case('rejected')
                                                bg-rouge-alerte/20 text-rouge-alerte
                                            @break
                                            @default
                                                bg-titane/20 text-titane
                                        @endswitch
                                    >
                                        {{ $complaint->status_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-ivoire-text
                                        mb-4">
                                        {{ $complaint->description }}
                                </div>
                                @if ($complaint->admin_notes)
                                    <div class="bg-noir-profond rounded-lg p-4 border border-titane/30">
                                        <h4 class="text-sm font-semibold text-ivoire-text mb-2">Notes de l'administrateur
                                        </h4>
                                        <p class="text-ivoire-text/70 text-sm">{{ $complaint->admin_notes }}</p>
                                    </div>
                                @endif
                                @if ($complaint->resolved_at)
                                    <div class="text-ivoire-text/70 text-sm">
                                        Résolu le {{ $complaint->resolved_at->format('d/m/Y à H:i') }}
                                    </div>
                                @endif
                            </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
