import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            ssr: {
                noExternal: ['inertia-link']
            }
        }),
        vue({
            compilerOptions: {
                isCustomElement: (tag) => tag === 'inertia-link'
            }
        }),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            '@inertiajs/vue3': '@inertiajs/vue3'
        },
    },
    server: {
        host: '127.0.0.1',
        port: 5173,
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
        hmr: {
            overlay: false,
        },
    },
    build: {
        // Remove external configuration to allow proper asset resolution
    },
});
