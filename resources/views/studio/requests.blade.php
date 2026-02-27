@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-ivoire-text">Demandes</h1>
        <p class="text-sm text-titane mt-1">Toutes les demandes adressées aux artistes de votre studio</p>
    </div>

    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
        @forelse ($requests as $request)
            <div class="p-4 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-sm font-semibold text-ivoire-text truncate">
                            {{ $request->client?->user?->name ?? 'Client' }}
                        </p>
                        @php
                            $status = is_object($request->status) ? $request->status->value : $request->status;
                        @endphp
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                            {{ in_array($status, ['pending']) ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                            {{ in_array($status, ['accepted', 'deposit_paid']) ? 'bg-vert-validation/20 text-vert-validation' : '' }}
                            {{ in_array($status, ['completed']) ? 'bg-blue-500/20 text-blue-400' : '' }}
                            {{ in_array($status, ['cancelled', 'declined']) ? 'bg-rouge-alerte/20 text-rouge-alerte' : '' }}
                            {{ !in_array($status, ['pending','accepted','deposit_paid','completed','cancelled','declined']) ? 'bg-titane/20 text-titane' : '' }}">
                            {{ $status }}
                        </span>
                    </div>
                    <p class="text-xs text-titane mt-0.5">
                        → {{ $request->bookable?->user?->name ?? 'Artiste' }}
                        ({{ $request->bookable instanceof \App\Models\Piercer ? '💎' : '🎨' }})
                        • {{ $request->created_at?->diffForHumans() }}
                    </p>
                </div>
                @if ($request->deposit_amount)
                    <span class="text-sm font-semibold text-beige-peau shrink-0">
                        {{ number_format($request->deposit_amount / 100, 2) }}€
                    </span>
                @endif
            </div>
        @empty
            <p class="text-sm text-titane text-center py-8">Aucune demande</p>
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
@endsection
