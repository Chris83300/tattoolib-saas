{{--
    RENDER HOOK : PanelsRenderHook::SIDEBAR_FOOTER
    Position : Épinglé tout en bas de la sidebar
    Utilisation : Version app, infos admin connecté, copyright...
--}}
<div class="px-4 py-3 border-t border-gray-700/30">
    <div class="flex items-center justify-between">
        <span class="text-xs text-gray-500">
            Ink&amp;Pik Admin
        </span>
        <span class="text-xs text-gray-600">
            v1.0.0
        </span>
    </div>
    @php $user = auth()->user(); @endphp
    @if ($user)
    <p class="text-xs text-gray-600 truncate mt-0.5">
        {{ $user->name ?? $user->email }}
    </p>
    @endif
</div>
