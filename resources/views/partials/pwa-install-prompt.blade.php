<div x-data="pwaInstall()"
     x-init="init()"
     x-show="showPrompt"
     x-cloak
     class="fixed bottom-20 left-4 right-4 md:left-auto md:right-4 md:w-80 bg-gris-fonde border border-cuivre/20 rounded-xl shadow-lg shadow-cuivre/20 z-40 p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4">
    <div class="flex items-start gap-3">
        <div class="flex items-center justify-center flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="Ink&Pik" class="w-10 h-10">
        </div>
        <div class="flex-1">
            <h3 class="text-beige-peau font-semibold mb-1">Installez Ink&Pik</h3>
            <p class="text-ivoire-text/90 text-sm mb-3">
                Accédez à l'application en un clic, même hors ligne
            </p>
            <div class="flex gap-2">
                <x-ui.button variant="primary" size="md" class="w-1/2" @click="install()">
                    Installer
                </x-ui.button>
                <x-ui.button variant="secondary" size="md" @click="dismiss()">
                    Plus tard
                </x-ui.button>
            </div>
        </div>
    </div>
</div>
