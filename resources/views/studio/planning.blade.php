@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Planning</h1>
    <p class="text-sm text-titane">Vue globale des rendez-vous de tous vos artistes</p>

    {{-- TODO: Calendrier global avec les RDV de tous les artistes --}}
    <div class="bg-gris-fonde rounded-xl p-6 text-center">
        <p class="text-sm text-titane">📅 Le planning global sera disponible prochainement.</p>
        <p class="text-xs text-titane/60 mt-2">En attendant, chaque artiste peut gérer son propre calendrier depuis son dashboard.</p>
    </div>

    @if (isset($artists) && $artists->count() > 0)
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">👥 Artistes actifs</h2>
            <div class="space-y-2">
                @foreach ($artists as $sa)
                    @if ($sa->user)
                        <div class="flex items-center gap-3 py-2 border-b border-titane/10 last:border-0">
                            <img src="{{ $sa->user->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}"
                                alt="{{ $sa->user->name }}" class="w-8 h-8 rounded-full object-cover">
                            <span class="text-sm text-ivoire-text">{{ $sa->user->name }}</span>
                            <span class="text-xs text-titane">{{ $sa->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
