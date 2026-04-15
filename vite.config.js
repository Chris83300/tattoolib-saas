import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/stripe-deposit.js',
                'resources/js/tattooer-calendar.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
        VitePWA({
            registerType: 'autoUpdate',
            scope: '/',
            base: '/',
            includeAssets: ['favicon.ico', 'images/*.png'],
            manifest: {
                name: 'Ink&Pik - Marketplace Arts Corporels',
                short_name: 'Ink&Pik',
                description: 'Marketplace et logiciel pour tatoueurs, pierceurs et studios en France',
                scope: '/',
                start_url: '/',
                display: 'standalone',
                background_color: '#0A0A0A',
                theme_color: '#D4B59E',
                orientation: 'portrait-primary',
                lang: 'fr-FR',
                categories: ['business', 'lifestyle', 'health'],
                icons: [
                    {
                        src: '/images/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'any'
                    },
                    {
                        src: '/images/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'maskable'
                    },
                    {
                        src: '/images/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any'
                    },
                    {
                        src: '/images/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'maskable'
                    }
                ],
                shortcuts: [
                    {
                        name: 'Marketplace',
                        short_name: 'Explorer',
                        url: '/marketplace',
                        icons: [{ src: '/images/icon-192x192.png', sizes: '192x192' }]
                    }
                ]
            },
            workbox: {
                navigateFallback: null,
                globPatterns: ['**/*.{js,css,ico,png,svg,woff2}'],
                globIgnores: [
                    '**/manifest.webmanifest',  // ← exclure
                    '**/manifest.json',
                ],
                navigateFallbackDenylist: [
                    /^\/admin/,
                    /^\/stripe/,
                    /^\/webhooks/,
                    /^\/livewire/,
                ],
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/fonts\.googleapis\.com\/.*/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'google-fonts-cache',
                            expiration: { maxEntries: 10, maxAgeSeconds: 60 * 60 * 24 * 365 },
                            cacheableResponse: { statuses: [0, 200] },
                        },
                    },
                    {
                        urlPattern: /^https:\/\/fonts\.gstatic\.com\/.*/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'gstatic-fonts-cache',
                            expiration: { maxEntries: 10, maxAgeSeconds: 60 * 60 * 24 * 365 },
                            cacheableResponse: { statuses: [0, 200] },
                        },
                    },
                    {
                        urlPattern: /\/api\/.*/i,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'api-cache',
                            expiration: { maxEntries: 50, maxAgeSeconds: 60 * 60 },
                            cacheableResponse: { statuses: [0, 200] },
                        },
                    },
                    {
                        urlPattern: /\.(?:png|jpg|jpeg|svg|gif|webp)$/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'images-cache',
                            expiration: { maxEntries: 100, maxAgeSeconds: 60 * 60 * 24 * 30 },
                            cacheableResponse: { statuses: [0, 200] },
                        },
                    },
                ],
            },
        })
    ],
    server: {
        host: '127.0.0.1', // ⚡ FORCE IPv4 (plus d'IPv6)
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: '127.0.0.1', // HMR sur IPv4 aussi
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
