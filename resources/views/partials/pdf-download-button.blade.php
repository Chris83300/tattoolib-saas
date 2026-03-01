{{--
    Bouton de téléchargement PDF.
    Paramètres :
      $url   — URL de téléchargement (route('pdf.*', ...))
      $label — Texte du bouton (défaut : "Télécharger PDF")
      $size  — 'sm' | 'xs' (défaut : 'sm')
--}}
@php
    $label = $label ?? 'Télécharger PDF';
    $size  = $size  ?? 'sm';
    $sizeClasses = $size === 'xs'
        ? 'px-2 py-1 text-xs gap-1'
        : 'px-3 py-1.5 text-sm gap-1.5';
@endphp
<a href="{{ $url }}"
   target="_blank"
   class="inline-flex items-center {{ $sizeClasses }} bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau border border-beige-peau/30 hover:border-beige-peau/60 rounded-lg transition-colors font-medium">
    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    {{ $label }}
</a>
