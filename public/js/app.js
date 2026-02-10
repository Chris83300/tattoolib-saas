// JavaScript principal pour Ink&Pik SaaS
console.log('Ink&Pik SaaS - App.js loaded');

// Fonctions utilitaires
window.InkPik = {
    // Initialisation
    init: function() {
        console.log('Ink&Pik initialisé');
        this.setupEventListeners();
        this.setupAnimations();
    },

    // Configuration des écouteurs d'événements
    setupEventListeners: function() {
        // Menu mobile
        const mobileMenuBtn = document.querySelector('[data-mobile-menu]');
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', this.toggleMobileMenu);
        }

        // Fermeture des menus
        document.addEventListener('click', function(e) {
            if (e.target.closest('[data-mobile-menu]')) return;
            
            const mobileMenu = document.querySelector('[data-mobile-menu-content]');
            if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });
    },

    // Configuration des animations
    setupAnimations: function() {
        // Animations de scroll
        this.setupSmoothScroll();
        
        // Animations d'apparition
        this.setupFadeInAnimations();
    },

    // Toggle menu mobile
    toggleMobileMenu: function() {
        const menu = document.querySelector('[data-mobile-menu-content]');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    },

    // Scroll doux
    setupSmoothScroll: function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    },

    // Animations d'apparition
    setupFadeInAnimations: function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        });

        document.querySelectorAll('[data-animate]').forEach(el => {
            observer.observe(el);
        });
    },

    // Helper pour les notifications
    showNotification: function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${
            type === 'success' ? 'bg-vert-succes text-noir-profond' :
            type === 'error' ? 'bg-rouge-alerte text-ivoire-text' :
            type === 'warning' ? 'bg-ambre-warning text-noir-profond' :
            'bg-gris-fonde text-ivoire-text'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-ivoire-text/70 hover:text-ivoire-text">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto-suppression après 5 secondes
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    },

    // Helper pour les requêtes AJAX
    ajax: function(url, options = {}) {
        const defaults = {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        };

        return fetch(url, { ...defaults, ...options })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                this.showNotification('Erreur de connexion', 'error');
                throw error;
            });
    }
};

// Initialisation au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    InkPik.init();
});
