<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('fevourd.seo.default_title') }}</title>
    <meta name="description" content="{{ config('fevourd.seo.default_description') }}">
    <meta name="keywords" content="{{ config('fevourd.seo.keywords') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('fevourd.seo.site_name') }}">
    <meta property="og:title" content="{{ config('fevourd.seo.default_title') }}">
    <meta property="og:description" content="{{ config('fevourd.seo.default_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ config('fevourd.brand.logo_url') ?: rtrim(config('app.url'), '/').'/'.ltrim(config('fevourd.brand.logo_public_path'), '/') }}">
    <meta name="twitter:card" content="summary_large_image">
    @php
        $fevourdOrgLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('fevourd.seo.organization_name'),
            'alternateName' => config('fevourd.seo.site_name'),
            'url' => rtrim(config('app.url'), '/'),
            'logo' => config('fevourd.brand.logo_url') ?: rtrim(config('app.url'), '/').'/'.ltrim(config('fevourd.brand.logo_public_path'), '/'),
            'description' => config('fevourd.seo.default_description'),
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($fevourdOrgLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    
    <!-- Favicon -->
    <!-- <link rel="icon" type="image/svg+xml" href="/favicon.svg"> -->
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Razorpay Script -->
    <script src="https://checkout.razorpay.com/v1/razorpay.js"></script>
    
    <!-- PWA / home-screen (see web.dev/add-to-homescreen) -->
    <meta name="theme-color" content="#1d4ed8" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="FEVOURD-K">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="FEVOURD-K">
    <link rel="apple-touch-icon" href="{{ asset(config('fevourd.brand.logo_public_path')) }}">
    <link rel="icon" type="image/png" href="{{ asset(config('fevourd.brand.logo_public_path')) }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (config('services.adsense.enabled') && filled(config('services.adsense.client_id')))
        <meta name="google-adsense-account" content="{{ config('services.adsense.client_id') }}">
        <script>
            window.__FEVOURD_ADSENSE__ = true;
        </script>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('services.adsense.client_id') }}" crossorigin="anonymous"></script>
    @endif
</head>
<body class="font-sans antialiased bg-gray-50">
    {{-- Installed PWA on mobile: land on login instead of marketing home (browser mobile still sees /) --}}
    <script>
        (function () {
            try {
                var standalone =
                    window.matchMedia('(display-mode: standalone)').matches ||
                    window.navigator.standalone === true;
                var mobile = window.matchMedia('(max-width: 768px)').matches;
                if (standalone && mobile && location.pathname === '/') {
                    location.replace('/login?source=pwa');
                }
            } catch (e) {}
        })();
    </script>
    {{-- Shown until Vue mounts (no Tailwind: must work before @vite CSS loads) --}}
    <div
        id="initial-page-loader"
        style="position:fixed;inset:0;z-index:99998;display:flex;align-items:center;justify-content:center;background:linear-gradient(to bottom right,#2563eb,#1e40af);color:#fff;font-family:Inter,system-ui,sans-serif;"
    >
        <div style="text-align:center;padding:1.5rem;">
            <div style="width:3rem;height:3rem;margin:0 auto 1rem;border:3px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:fevourd-spin 0.8s linear infinite;"></div>
            <p style="font-size:1rem;font-weight:600;margin:0;">FEVOURD-K</p>
            <p style="font-size:0.875rem;opacity:0.9;margin:0.5rem 0 0;">Loading…</p>
        </div>
    </div>
    <style>@keyframes fevourd-spin{to{transform:rotate(360deg)}}</style>
    @inertia
    
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    {{-- PWA install: only visible when browser fires beforeinstallprompt (avoids dead button + extra layout gap) --}}
    <div
        id="fevourd-pwa-fab-blade"
        style="display:none;position:fixed;bottom:max(12px,env(safe-area-inset-bottom,0px));right:max(12px,env(safe-area-inset-right,0px));z-index:2147483647;margin:0;padding:0;font-family:system-ui,-apple-system,sans-serif;line-height:0;"
        role="region"
        aria-label="Install app"
    >
        <div style="display:flex;align-items:center;gap:6px;margin:0;padding:0;box-shadow:0 4px 16px rgba(15,23,42,0.18);border-radius:9999px;">
            <button
                type="button"
                id="fevourd-pwa-install-btn"
                style="display:inline-flex;align-items:center;justify-content:center;gap:6px;height:44px;min-height:44px;padding:0 14px;border:0;border-radius:9999px;font-size:13px;font-weight:600;color:#fff;cursor:pointer;background:linear-gradient(90deg,#2563eb,#4f46e5);white-space:nowrap;"
            >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Install app
            </button>
            <button
                type="button"
                id="fevourd-pwa-install-dismiss"
                aria-label="Dismiss install prompt"
                style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;min-width:36px;min-height:36px;padding:0;border-radius:9999px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:18px;line-height:1;cursor:pointer;"
            >
                ×
            </button>
        </div>
    </div>
    <script>
        (function () {
            var DISMISS = 'fevourd-pwa-install-dismiss-blade-v1';
            function isStandalone() {
                if (window.matchMedia('(display-mode: standalone)').matches) return true;
                if (window.navigator.standalone === true) return true;
                return false;
            }
            function isDismissed() {
                try {
                    var until = parseInt(localStorage.getItem(DISMISS) || '0', 10);
                    return Date.now() < until;
                } catch (e) {
                    return false;
                }
            }
            var shell = document.getElementById('fevourd-pwa-fab-blade');
            var btn = document.getElementById('fevourd-pwa-install-btn');
            var closeBtn = document.getElementById('fevourd-pwa-install-dismiss');
            if (!shell || !btn || !closeBtn) return;

            if (isStandalone() || isDismissed()) return;

            window.__fevourdDeferredInstallPrompt = window.__fevourdDeferredInstallPrompt || null;

            window.addEventListener('beforeinstallprompt', function (e) {
                e.preventDefault();
                window.__fevourdDeferredInstallPrompt = e;
                shell.style.display = 'block';
            });

            window.addEventListener('appinstalled', function () {
                window.__fevourdDeferredInstallPrompt = null;
                shell.style.display = 'none';
                try {
                    if (location.pathname.indexOf('/login') !== 0) {
                        location.replace('/login?source=pwa');
                    }
                } catch (err) {}
            });

            closeBtn.addEventListener('click', function () {
                try {
                    localStorage.setItem(DISMISS, String(Date.now() + 7 * 24 * 60 * 60 * 1000));
                } catch (err) {}
                shell.style.display = 'none';
            });

            btn.addEventListener('click', function () {
                var p = window.__fevourdDeferredInstallPrompt;
                if (!p) {
                    return;
                }
                p.prompt();
                p.userChoice.then(function (choice) {
                    if (!choice || choice.outcome !== 'accepted') {
                        window.__fevourdDeferredInstallPrompt = p;
                    } else {
                        window.__fevourdDeferredInstallPrompt = null;
                    }
                });
            });
        })();
    </script>
</body>
</html>
