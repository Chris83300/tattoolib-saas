<div x-data="{ showPrompt: false, deferredPrompt: null }" 
     x-init="
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Ne montrer que si pas déjà installé et après 30 secondes
            setTimeout(() => {
                if (!localStorage.getItem('pwa-installed') && !localStorage.getItem('pwa-dismissed')) {
                    showPrompt = true;
                }
            }, 30000);
        });
        
        window.addEventListener('appinstalled', () => {
            localStorage.setItem('pwa-installed', 'true');
            showPrompt = false;
        });
     "
     x-show="showPrompt && !localStorage.getItem('pwa-installed')"
     x-cloak
     class="fixed bottom-20 left-4 right-4 md:left-auto md:right-4 md:w-80 bg-gris-fonde border border-titane/20 rounded-lg shadow-lg z-40 p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4">
    
    <div class="flex items-start gap-3">
        <!-- Icon -->
        <div class="w-12 h-12 bg-beige-peau/10 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-beige-peau" viewBox="0 0 32 32" fill="currentColor">
                <path d="M8 4C8 4 8 8 12 8C16 8 16 4 16 4C16 4 16 8 20 8C24 8 24 4 24 4L24 12C24 16 20 20 16 20C12 20 8 16 8 12Z"/>
                <circle cx="16" cy="24" r="2" fill="currentColor"/>
            </svg>
        </div>
        
        <!-- Content -->
        <div class="flex-1">
            <h3 class="text-ivoire-text font-semibold mb-1">Installez Ink&Pik</h3>
            <p class="text-ivoire-text/70 text-sm mb-3">
                Accédez à l'application en un clic, même hors ligne
            </p>
            
            <!-- Actions -->
            <div class="flex gap-2">
                <button @click="deferredPrompt.prompt(); deferredPrompt.userChoice.then((choiceResult) => { showPrompt = false; });" 
                        class="flex-1 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold py-2 px-4 rounded transition-colors text-sm">
                    Installer
                </button>
                <button @click="showPrompt = false; localStorage.setItem('pwa-dismissed', 'true');" 
                        class="text-ivoire-text/50 hover:text-ivoire-text py-2 px-3 text-sm transition-colors">
                    Plus tard
                </button>
            </div>
        </div>
    </div>
</div>
