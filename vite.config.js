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
                'resources/js/app.js',
                'resources/js/noir-bg.js',
                'resources/js/site.js',
                'resources/js/terminal.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
