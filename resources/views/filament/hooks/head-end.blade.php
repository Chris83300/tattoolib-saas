{{--
    RENDER HOOK : PanelsRenderHook::HEAD_END
    Position : Avant </head>
    Utilisation : Meta tags supplémentaires, polices (via <link>, pas @import CSS)

    ⚠️ NE PAS mettre de <style> ici pour styler les classes fi-*
    → Les styles sont dans resources/css/filament/admin/theme.css (chargé via ->viteTheme())
      et s'appliquent APRÈS les styles Filament, garantissant la bonne spécificité.
--}}

{{-- Police Inter via <link> (plus performant que @import dans CSS) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet">
