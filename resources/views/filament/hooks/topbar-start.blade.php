{{--
    RENDER HOOK : PanelsRenderHook::TOPBAR_START
    Position : Début de la barre du haut, avant le logo
    Utilisation : Indicateur d'environnement, mode debug...

    Exemple badge environnement dev :
--}}
@if (app()->environment('local'))
<span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium
             bg-orange-100 text-orange-700 rounded-lg mr-3">
    🔧 DEV
</span>
@endif
