import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/home.css',
                'resources/css/system.css',
                'resources/css/protocolos-effects.css',
                'resources/css/arquivos.css',
                'resources/css/terminal.css',
                'resources/css/admin.css',
                'resources/css/background.css',
                'resources/js/app.js',
                'resources/js/noir-bg-lazy.js',
                'resources/js/site.js',
                'resources/js/terminal.js',
                'resources/js/tesseract-widget.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    three: ['three'],
                },
            },
        },
    },
});