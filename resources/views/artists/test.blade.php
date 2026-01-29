@extends('layouts.app')

@section('title', 'Test Profil')

@section('content')
<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-gris-fonde rounded-xl p-8 text-center">
        <h1 class="text-2xl font-bold text-ivoire-text mb-4">
            Test Profil - {{ $artist->user->name }}
        </h1>
        <p class="text-ivoire-text/70 mb-4">
            Slug: {{ $artist->slug }}
        </p>
        <p class="text-ivoire-text/70 mb-4">
            Statut: {{ $artist->user->status }}
        </p>
        <p class="text-beige-peau mb-4">
            @if(request()->get('preview') === 'true')
                MODE PRÉVISUALISATION ACTIVÉ
            @else
                MODE NORMAL
            @endif
        </p>
        <a href="{{ route('tattooer.dashboard') }}" class="text-beige-peau underline">
            ← Retour au dashboard
        </a>
    </div>
</div>
@endsection
