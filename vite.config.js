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
            ],
            refresh: true,
        }),
        tailwindcss(),
        VitePWA({
            registerType: 'autoUpdate',
            includeAssets: ['favicon.ico', 'images/*.png'],
            manifest: {
                name: 'Ink&Pik - Marketplace Arts Corporels',
                short_name: 'Ink&Pik',
                description: 'Marketplace et logiciel pour tatoueurs, pierceurs et studios',
                start_url: '/',
                display: 'standalone',
                background_color: '#0A0A0A',
                theme_color: '#D4B59E',
                orientation: 'portrait-primary',
                icons: [
                    {
                        src: '/images/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: '/images/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any maskable'
                    }
                ]
            }
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
