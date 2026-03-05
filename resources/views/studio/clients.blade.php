@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Fiches clients</h1>
            <p class="text-sm text-titane mt-1">Clients ayant adressé une demande aux artistes de votre studio</p>
        </div>
        <span class="text-sm text-titane">{{ $clients->total() }} client{{ $clients->total() > 1 ? 's' : '' }}</span>
    </div>

    <!-- Recherche -->
    <form method="GET" action="{{ route('studio.clients.index') }}" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Rechercher par nom ou email..."
            class="flex-1 bg-gris-fonde border border-titane/30 rounded-lg px-4 py-2 text-sm text-ivoire-text placeholder-titane focus:outline-none focus:border-beige-peau/50">
        <button type="submit"
            class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">
            Rechercher
        </button>
        @if(request('search'))
            <a href="{{ route('studio.clients.index') }}"
                class="px-4 py-2 bg-gris-fonde border border-titane/30 rounded-lg text-sm text-titane hover:text-ivoire-text transition-colors">
                Réinitialiser
            </a>
        @endif
    </form>

    <!-- Liste clients -->
    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
        @forelse ($clients as $client)
            <a href="{{ route('studio.clients.show', $client) }}"
                class="flex items-center gap-4 p-4 hover:bg-noir-profond/40 transition-colors">

                <!-- Avatar -->
                <div class="w-10 h-10 rounded-full bg-beige-peau/20 flex items-center justify-center shrink-0 overflow-hidden">
                    @if($client->getFirstMediaUrl('avatar'))
                        <img src="{{ $client->getFirstMediaUrl('avatar') }}" alt="{{ $client->display_name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-beige-peau font-bold text-sm">{{ mb_strtoupper(mb_substr($client->first_name ?? $client->pseudo ?? 'C', 0, 1)) }}</span>
                    @endif
                </div>

                <!-- Infos -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate">
                        {{ $client->first_name }} {{ $client->last_name }}
                        @if($client->pseudo && $client->pseudo !== trim($client->first_name . ' ' . $client->last_name))
                            <span class="text-titane font-normal">({{ $client->pseudo }})</span>
                        @endif
                    </p>
                    <p class="text-xs text-titane truncate mt-0.5">
                        {{ $client->email }}
                        @if($client->phone) • {{ $client->phone }} @endif
                    </p>
                </div>

                <!-- Stats demandes -->
                <div class="text-right shrink-0">
                    <p class="text-sm font-semibold text-beige-peau mb-1">
                        {{ $client->booking_requests_count ?? $client->bookingRequests->count() }}
                        demande{{ ($client->booking_requests_count ?? $client->bookingRequests->count()) > 1 ? 's' : '' }}
                    </p>
                    @if($client->is_blacklisted)
                        <span class="text-xs bg-rouge-alerte/10 p-1 rounded-full text-rouge-alerte font-semibold mt-2 ">Liste noire</span>
                    @elseif($client->no_show_count > 0)
                        <span class="text-xs bg-rouge-alerte/10 p-1 rounded-full text-rouge-alerte mt-2">{{ $client->no_show_count }} no-show</span>
                    @else
                        <span class="text-xs text-titane mt-2">{{ $client->created_at?->diffForHumans() }}</span>
                    @endif
                </div>

                <!-- Chevron -->
                <svg class="w-4 h-4 text-titane shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @empty
            <div class="text-center py-12">
                <p class="text-titane text-sm">Aucun client trouvé</p>
                @if(request('search'))
                    <a href="{{ route('studio.clients.index') }}" class="text-beige-peau text-sm hover:underline mt-2 inline-block">Effacer la recherche</a>
                @endif
            </div>
        @endforelse
    </div>

    {{ $clients->links() }}
</div>
@endsection
