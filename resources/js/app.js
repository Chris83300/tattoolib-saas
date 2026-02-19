import './bootstrap';
import './unread-messages';
import { registerSW } from 'virtual:pwa-register';

const VAPID_KEY = 'BHJrySPatoJLDgi11i6Lcd4JJxtv2ZWFb1Gqd3KqWZcukSzkZNm1S46BoYwWsktrbFQ0Ktw6cRcnSbIqXknY5EY';

// Enregistrement du Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('ServiceWorker enregistré avec succès');
            })
            .catch(error => {
                console.log('Échec de l\'enregistrement du ServiceWorker :', error);
            });
    });
}

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
        applicationServerKey: 'VOTRE_CLE_PUBLIQUE_VAPID'
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

// Appeler cette fonction après la connexion de l'utilisateur
// requestNotificationPermission();
