@extends('layouts.studio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-ivoire-text mb-6">Planning Consolidé</h1>
    
    <!-- Filtres -->
    <div class="bg-gris-fonde rounded-xl p-4 mb-6">
        <div class="flex flex-wrap gap-4">
            <select class="bg-noir-profond text-ivoire-text border border-ivoire-text/20 rounded-lg px-4 py-2">
                <option>Tous les artistes</option>
                @foreach($studio->artists as $artist)
                    <option>{{ $artist->user->name }}</option>
                @endforeach
            </select>
            
            <div class="flex gap-2">
                <button class="bg-beige-peau text-noir-profond px-4 py-2 rounded-lg">Jour</button>
                <button class="bg-gris-fonde text-ivoire-text px-4 py-2 rounded-lg">Semaine</button>
                <button class="bg-gris-fonde text-ivoire-text px-4 py-2 rounded-lg">Mois</button>
            </div>
        </div>
    </div>
    
    <!-- Calendrier -->
    <div class="bg-gris-fonde rounded-xl p-6">
        <div class="grid grid-cols-7 gap-2 mb-4">
            <div class="text-center text-ivoire-text/70 font-semibold">Lun</div>
            <div class="text-center text-ivoire-text/70 font-semibold">Mar</div>
            <div class="text-center text-ivoire-text/70 font-semibold">Mer</div>
            <div class="text-center text-ivoire-text/70 font-semibold">Jeu</div>
            <div class="text-center text-ivoire-text/70 font-semibold">Ven</div>
            <div class="text-center text-ivoire-text/70 font-semibold">Sam</div>
            <div class="text-center text-ivoire-text/70 font-semibold">Dim</div>
        </div>
        
        <div class="grid grid-cols-7 gap-2">
            @for($day = 1; $day <= 31; $day++)
                <div class="bg-noir-profond rounded-lg p-2 min-h-[100px] border border-ivoire-text/20">
                    <div class="text-sm text-ivoire-text/70 mb-1">{{ $day }}</div>
                    <div class="space-y-1">
                        <!-- Exemple de RDV -->
                        <div class="bg-blue-500/20 border border-blue-500/50 rounded p-1 text-xs">
                            <div class="text-blue-300">10:00</div>
                            <div class="text-ivoire-text">Jean</div>
                        </div>
                        <div class="bg-green-500/20 border border-green-500/50 rounded p-1 text-xs">
                            <div class="text-green-300">14:30</div>
                            <div class="text-ivoire-text">Marie</div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
    
    <!-- Légende -->
    <div class="mt-4 flex flex-wrap gap-4">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-blue-500/20 border border-blue-500/50 rounded"></div>
            <span class="text-ivoire-text/70 text-sm">Artiste 1</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-green-500/20 border border-green-500/50 rounded"></div>
            <span class="text-ivoire-text/70 text-sm">Artiste 2</span>
        </div>
    </div>
</div>
@endsection
