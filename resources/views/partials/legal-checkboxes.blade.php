{{-- Cases à cocher légales — à inclure dans les formulaires d'inscription --}}
{{-- Paramètres : $isPro (bool) pour afficher les CGV artistes --}}
<div class="space-y-3 pt-2 border-t border-titane/10">
    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" wire:model="acceptCgu"
            class="mt-0.5 w-4 h-4 rounded border-titane/50 bg-noir-profond text-beige-peau focus:ring-beige-peau flex-shrink-0">
        <span class="text-xs text-titane leading-relaxed">
            J'ai lu et j'accepte les
            <a href="{{ route('legal.cgu') }}" target="_blank" class="text-beige-peau hover:underline">Conditions Générales d'Utilisation</a>
            @if ($isPro ?? false)
                et les <a href="{{ route('legal.cgv-artistes') }}" target="_blank" class="text-beige-peau hover:underline">Conditions Générales de Vente</a>
            @endif
            <span class="text-rouge-alerte">*</span>
        </span>
    </label>
    @error('acceptCgu')
        <p class="text-rouge-alerte text-xs pl-7">{{ $message }}</p>
    @enderror

    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" wire:model="acceptPrivacy"
            class="mt-0.5 w-4 h-4 rounded border-titane/50 bg-noir-profond text-beige-peau focus:ring-beige-peau flex-shrink-0">
        <span class="text-xs text-titane leading-relaxed">
            J'ai lu et j'accepte la
            <a href="{{ route('legal.politique-confidentialite') }}" target="_blank" class="text-beige-peau hover:underline">Politique de confidentialité</a>
            <span class="text-rouge-alerte">*</span>
        </span>
    </label>
    @error('acceptPrivacy')
        <p class="text-rouge-alerte text-xs pl-7">{{ $message }}</p>
    @enderror
</div>
