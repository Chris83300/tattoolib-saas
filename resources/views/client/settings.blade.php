@extends('layouts.client')

@section('content')
    <livewire:client.settings />

    {{-- Section RGPD — export des données personnelles --}}
    <div class="mt-6 bg-gris-fonde rounded-xl p-4 md:p-6">
        <h3 class="text-base font-semibold text-ivoire-text mb-1">Vos données personnelles</h3>
        <p class="text-sm text-ivoire-text/60 mb-4">
            Conformément au RGPD (Art. 20), vous pouvez télécharger une copie de toutes vos données
            personnelles stockées sur Ink&amp;Pik. Maximum 3 exports par heure.
        </p>
        <a href="{{ route('client.gdpr.export') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gris-fonde border border-titane/30 text-ivoire-text text-sm rounded-lg hover:border-beige-peau/50 transition">
            Télécharger mes données (JSON)
        </a>
    </div>
@endsection
