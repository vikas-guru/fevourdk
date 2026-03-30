import './bootstrap';
import '../css/app.css';

import { createApp, h, nextTick } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { Ziggy } from './ziggy';

createInertiaApp({
    title: (title) => `${title} Fevourd-K`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        if (page === undefined) {
            throw new Error(`Page not found: ${name}`);
        }
        return page.default || page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .provide('ziggy', Ziggy)
            .component('inertia-link', Link)
            .mount(el);

        nextTick(() => {
            document.getElementById('initial-page-loader')?.remove();
        });
    },
    progress: {
        color: '#3b82f6',
    },
});

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch((error) => {
            console.warn('Service worker registration failed:', error);
        });
    });
}
