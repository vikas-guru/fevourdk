<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>Fevaourd-K - NGO CSR Citizen Engagement Platform</title>
    
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
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1d4ed8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Fevourd-K">
    <link rel="apple-touch-icon" href="/icons/icon-192.svg">
    <link rel="icon" type="image/png" href="/assets/images/fevourd-k/logo.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="font-sans antialiased bg-gray-50">
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

    {{-- PWA install FAB: vanilla JS so it works even when Vite dev server is off (php artisan serve only) --}}
    <div
        id="fevourd-pwa-fab-blade"
        style="display:none;position:fixed;bottom:20px;right:20px;z-index:2147483647;align-items:center;gap:10px;font-family:system-ui,-apple-system,sans-serif;"
        role="region"
        aria-label="Install app"
    >
        <button
            type="button"
            id="fevourd-pwa-install-btn"
            style="display:inline-flex;align-items:center;gap:8px;height:56px;padding:0 20px;border:0;border-radius:9999px;font-size:14px;font-weight:600;color:#fff;cursor:pointer;background:linear-gradient(90deg,#2563eb,#4f46e5);box-shadow:0 10px 25px rgba(37,99,235,0.35);"
        >
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Install app
        </button>
        <button
            type="button"
            id="fevourd-pwa-install-dismiss"
            aria-label="Dismiss"
            style="width:40px;height:40px;border-radius:9999px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:20px;line-height:1;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,0.08);"
        >
            ×
        </button>
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
            });
            window.addEventListener('appinstalled', function () {
                window.__fevourdDeferredInstallPrompt = null;
                shell.style.display = 'none';
            });

            shell.style.display = 'flex';

            closeBtn.addEventListener('click', function () {
                try {
                    localStorage.setItem(DISMISS, String(Date.now() + 7 * 24 * 60 * 60 * 1000));
                } catch (err) {}
                shell.style.display = 'none';
            });

            btn.addEventListener('click', function () {
                var p = window.__fevourdDeferredInstallPrompt;
                if (p) {
                    p.prompt();
                    p.userChoice.then(function () {
                        window.__fevourdDeferredInstallPrompt = null;
                    });
                    return;
                }
                var ios = /iphone|ipad|ipod/i.test(navigator.userAgent || '');
                if (ios) {
                    window.alert('To install on iPhone/iPad: tap Share, then “Add to Home Screen”, then Add.');
                } else {
                    window.alert('To install: in Chrome or Edge use the install icon in the address bar, or the menu (⋮) → Install app / Install FEVOURD-K.');
                }
            });
        })();
    </script>
</body>
</html>
