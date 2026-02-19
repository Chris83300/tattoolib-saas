// Gestion des messages non-lus et demandes en attente en temps réel
class UnreadMessagesManager {
    constructor() {
        this.unreadCountElement = document.getElementById('unread-messages-count');
        this.pendingCountElement = document.getElementById('pending-requests-count');
        this.pendingCountMobileElement = document.getElementById('pending-requests-count-mobile');
        this.currentUnreadCount = parseInt(this.unreadCountElement?.textContent || '0');
        this.currentPendingCount = parseInt(this.pendingCountElement?.textContent || '0');
        this.refreshInterval = 30000; // 30 secondes
        this.init();
    }

    init() {
        if (!this.unreadCountElement && !this.pendingCountElement) return;

        // Démarrer le rafraîchissement automatique
        this.startAutoRefresh();

        // Écouter les événements Livewire (si disponible)
        if (typeof window.Livewire !== 'undefined') {
            window.Livewire.hook('message.processed', () => {
                this.refreshCounts();
            });
        }

        // Écouter les événements de focus de la fenêtre
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.refreshCounts();
            }
        });

        // Écouter les clics sur les messages et demandes
        document.addEventListener('click', (e) => {
            if (e.target.closest('a[href*="messages"]')) {
                setTimeout(() => this.refreshCounts(), 1000);
            }
            if (e.target.closest('a[href*="requests"]')) {
                setTimeout(() => this.refreshCounts(), 1000);
            }
        });
    }

    startAutoRefresh() {
        setInterval(() => {
            this.refreshCounts();
        }, this.refreshInterval);
    }

    async refreshCounts() {
        try {
            const response = await fetch('/api/messages/unread-count', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                const data = await response.json();
                this.updateUnreadCount(data.unreadCount);
                this.updatePendingCount(data.pendingCount || 0);
            }
        } catch (error) {
            console.error('Erreur lors du rafraîchissement des compteurs:', error);
        }
    }

    updateUnreadCount(newCount) {
        if (!this.unreadCountElement) return;

        const oldCount = this.currentUnreadCount;
        this.currentUnreadCount = newCount;

        if (newCount > 0) {
            this.unreadCountElement.textContent = newCount;
            this.unreadCountElement.style.display = 'inline-flex';

            // Animation si le nombre a changé
            if (oldCount !== newCount) {
                this.unreadCountElement.classList.add('animate-pulse');
                setTimeout(() => {
                    this.unreadCountElement.classList.remove('animate-pulse');
                }, 1000);
            }
        } else {
            this.unreadCountElement.style.display = 'none';
        }
    }

    updatePendingCount(newCount) {
        const elements = [
            this.pendingCountElement,
            this.pendingCountMobileElement
        ];

        elements.forEach(element => {
            if (!element) return;

            const oldCount = this.currentPendingCount;
            this.currentPendingCount = newCount;

            if (newCount > 0) {
                element.textContent = newCount;
                element.style.display = element.id.includes('mobile') ? 'flex' : 'inline-flex';

                // Animation si le nombre a changé
                if (oldCount !== newCount) {
                    element.classList.add('animate-pulse');
                    setTimeout(() => {
                        element.classList.remove('animate-pulse');
                    }, 1000);
                }
            } else {
                element.style.display = 'none';
            }
        });
    }

    // Méthode pour marquer manuellement les messages comme lus
    markAsRead() {
        this.updateUnreadCount(0);
    }

    // Méthode pour mettre à jour manuellement les demandes
    updateRequests(count) {
        this.updatePendingCount(count);
    }
}

// Initialiser le gestionnaire quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    window.unreadMessagesManager = new UnreadMessagesManager();
});
