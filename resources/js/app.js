import './bootstrap';
import collapse from '@alpinejs/collapse';

import './unread-messages';
import { registerSW } from 'virtual:pwa-register';

// Enregistrement PWA Service Worker (vite-plugin-pwa)
const updateSW = registerSW({
    onNeedRefresh() {
        if (confirm('Nouvelle version disponible. Mettre à jour ?')) {
            updateSW(true);
        }
    },
    onOfflineReady() {
        console.log('Ink&Pik est prête pour le mode hors ligne');
    },
});

// Composant Alpine global — bannière consentement cookies (CNIL)
document.addEventListener('alpine:init', () => {
    Alpine.data('cookieConsent', () => ({
        showBanner: false,
        showDetails: false,
        analytics: false,
        thirdParty: false,

        init() {
            try {
                const consent = this.getCookie('cookie_consent');
                if (!consent) {
                    this.showBanner = true;
                }
            } catch (e) {
                console.warn('Cookie consent init error:', e);
            }
        },

        acceptAll() {
            this.setConsent({ necessary: true, analytics: true, thirdParty: true });
        },

        acceptNecessaryOnly() {
            this.setConsent({ necessary: true, analytics: false, thirdParty: false });
        },

        rejectAll() {
            this.setConsent({ necessary: true, analytics: false, thirdParty: false });
        },

        setConsent(preferences) {
            try {
                const value = JSON.stringify({
                    ...preferences,
                    timestamp: new Date().toISOString(),
                    version: '1.0',
                });
                // Cookie valide 13 mois (conformité CNIL)
                const expires = new Date(Date.now() + 395 * 24 * 60 * 60 * 1000).toUTCString();
                document.cookie = `cookie_consent=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
                this.showBanner = false;
                window.dispatchEvent(new CustomEvent('cookie-consent-updated', { detail: preferences }));
            } catch (e) {
                console.warn('Cookie consent setConsent error:', e);
            }
        },

        getCookie(name) {
            try {
                const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
                return match ? decodeURIComponent(match[2]) : null;
            } catch (e) {
                console.warn('Cookie consent getCookie error:', e);
                return null;
            }
        },
    }));
});

// Clé VAPID publique — lue depuis la meta tag injectée par le layout (config/env)
const VAPID_KEY = document.querySelector('meta[name="vapid-public-key"]')?.content || '';

// Enregistrer le plugin collapse sur l'instance Alpine fournie par Livewire
document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);
});

// ⚠️ Enregistrement SW géré par vite-plugin-pwa (registerSW ci-dessus)

// Demande de permission pour les notifications
export function requestNotificationPermission() {
    if ('Notification' in window) {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                console.log('Permission de notification accordée');
                // Enregistrer le service worker pour les notifications push
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.ready.then(registration => {
                        // Récupérer le token FCM et l'envoyer au serveur
                        getFCMToken();
                    });
                }
            }
        });
    }
}

// Fonction pour récupérer le token FCM
async function getFCMToken() {
    const serviceWorkerRegistration = await navigator.serviceWorker.ready;
    const token = await serviceWorkerRegistration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: VAPID_KEY
    });

    // Envoyer le token à votre serveur
    await fetch('/api/fcm-token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ token })
    });
}
// PWA Install Prompt
document.addEventListener('alpine:init', () => {
    Alpine.data('pwaInstall', () => ({
        showPrompt: false,
        deferredPrompt: null,

        init() {
            // Ne jamais afficher sur les pages auth
            const authPages = ['/login', '/register', '/forgot-password', '/reset-password'];
            const currentPath = window.location.pathname;
            if (authPages.some(page => currentPath.startsWith(page))) return;

            // Ne plus afficher si déjà installé
            if (localStorage.getItem('pwa-installed')) return;
            if (sessionStorage.getItem('pwa-dismissed-session')) return;

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                this.showPrompt = true;
            });

            window.addEventListener('appinstalled', () => {
                localStorage.setItem('pwa-installed', 'true');
                this.showPrompt = false;
            });
        },

        async install() {
            if (!this.deferredPrompt) return;
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                localStorage.setItem('pwa-installed', 'true');
            }
            this.deferredPrompt = null;
            this.showPrompt = false;
        },

        dismiss() {
            sessionStorage.setItem('pwa-dismissed-session', 'true');
            this.showPrompt = false;
        }
    }));
});

// Appeler cette fonction après la connexion de l'utilisateur
// requestNotificationPermission();
