import './bootstrap';
import '../css/app.css';

import { createApp, h, nextTick } from 'vue';
import { createInertiaApp, Link, router } from '@inertiajs/vue3';
import { Ziggy } from './ziggy';
import GlobalErrorModal from './Components/GlobalErrorModal.vue';

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
        createApp({ render: () => [h(App, props), h(GlobalErrorModal)] })
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

/**
 * Installed PWA + mobile viewport: marketing home (/) should open login — matches app.blade inline script.
 * Handles Inertia client-side visits to / (browser mobile is unchanged).
 */
function redirectPwaMobileHomeToLogin() {
    if (typeof window === 'undefined') {
        return;
    }
    const standalone =
        window.matchMedia('(display-mode: standalone)').matches ||
        window.navigator.standalone === true;
    if (!standalone) {
        return;
    }
    if (!window.matchMedia('(max-width: 768px)').matches) {
        return;
    }
    if (window.location.pathname !== '/') {
        return;
    }
    router.replace('/login?source=pwa');
}

router.on('finish', () => {
    requestAnimationFrame(() => {
        redirectPwaMobileHomeToLogin();
    });
});

// AdSense: after Inertia client navigations, ask the loader to process any new <ins class="adsbygoogle"> units.
if (typeof window !== 'undefined' && window.__FEVOURD_ADSENSE__) {
    router.on('finish', () => {
        requestAnimationFrame(() => {
            try {
                (window.adsbygoogle = window.adsbygoogle || []).push({});
            } catch {
                /* no unfilled slots or blocked (e.g. localhost / ad blocker) */
            }
        });
    });
}

if ('serviceWorker' in navigator) {
    const host = window.location.hostname;
    const isLocalhost = host === 'localhost' || host === '127.0.0.1' || host === '::1';

    if (isLocalhost) {
        // Prevent stale/offline-cache behavior during local development.
        navigator.serviceWorker.getRegistrations().then((registrations) => {
            registrations.forEach((registration) => registration.unregister());
        }).catch(() => {});

        // Also clear all runtime caches in local to avoid stale offline responses.
        if (window.caches && typeof window.caches.keys === 'function') {
            window.caches.keys().then((keys) => {
                keys.forEach((key) => window.caches.delete(key));
            }).catch(() => {});
        }
    } else {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', { updateViaCache: 'none' })
                .then((registration) => registration.update())
                .catch((error) => {
                    console.warn('Service worker registration failed:', error);
                });
        });
    }
}
