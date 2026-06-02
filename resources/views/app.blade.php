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
    <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700,9..144,500,9..144,600&display=swap" rel="stylesheet">
    
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
<body class="font-sans antialiased bg-gray-50 overflow-x-hidden">
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

    {{-- PWA install: browser-native prompt when available, guided fallback otherwise --}}
    <div
        id="fevourd-pwa-fab-blade"
        style="display:none;position:fixed;inset:0;z-index:2147483647;margin:0;padding:16px;font-family:system-ui,-apple-system,sans-serif;line-height:1.35;background:rgba(15,23,42,0.58);backdrop-filter:blur(6px);align-items:center;justify-content:center;"
        role="dialog"
        aria-modal="true"
        aria-labelledby="fevourd-pwa-install-title"
    >
        <div style="width:min(430px,calc(100vw - 32px));box-shadow:0 28px 70px rgba(15,23,42,0.35);border:1px solid rgba(226,232,240,0.96);border-radius:24px;background:#fff;color:#0f172a;overflow:hidden;">
            <div style="position:relative;padding:18px 18px 14px;background:linear-gradient(135deg,#eff6ff,#f8fafc 54%,#ecfeff);">
                <button
                    type="button"
                    id="fevourd-pwa-install-dismiss"
                    aria-label="Dismiss install prompt"
                    style="position:absolute;top:12px;right:12px;display:flex;align-items:center;justify-content:center;width:34px;height:34px;min-width:34px;min-height:34px;padding:0;border-radius:9999px;border:1px solid #dbe4f0;background:rgba(255,255,255,0.86);color:#64748b;font-size:19px;line-height:1;cursor:pointer;"
                >
                    ×
                </button>
                <div style="display:flex;align-items:center;gap:13px;padding-right:38px;">
                    <div style="display:flex;align-items:center;justify-content:center;width:52px;height:52px;min-width:52px;border-radius:16px;background:#2563eb;color:#fff;box-shadow:0 10px 22px rgba(37,99,235,0.32);">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v6m-3-3 3 3 3-3"/></svg>
                    </div>
                    <div style="min-width:0;flex:1;">
                        <p id="fevourd-pwa-install-title" style="margin:0;font-size:20px;font-weight:800;color:#0f172a;letter-spacing:0;">Install FEVOURD-K</p>
                        <p id="fevourd-pwa-install-help" style="margin:5px 0 0;font-size:13px;font-weight:600;color:#475569;">Checking install support…</p>
                    </div>
                </div>
            </div>
            <div style="padding:16px 18px 18px;">
                <div id="fevourd-pwa-install-steps" style="display:block;margin:0 0 14px;padding:12px;border-radius:16px;background:#f8fafc;border:1px solid #e2e8f0;color:#334155;">
                    <p style="margin:0 0 8px;font-size:13px;font-weight:800;color:#0f172a;">Install from this popup</p>
                    <ol style="margin:0;padding-left:20px;font-size:13px;font-weight:600;color:#475569;">
                        <li id="fevourd-pwa-step-one" style="margin:0 0 5px;">Tap Install app below.</li>
                        <li id="fevourd-pwa-step-two" style="margin:0 0 5px;">Approve the browser install prompt.</li>
                        <li id="fevourd-pwa-step-three" style="margin:0;">Open FEVOURD-K from your home screen.</li>
                    </ol>
                </div>
                <button
                    type="button"
                    id="fevourd-pwa-install-btn"
                    style="display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;height:46px;min-height:46px;padding:0 16px;border:0;border-radius:14px;font-size:14px;font-weight:800;color:#fff;cursor:pointer;background:#2563eb;white-space:nowrap;box-shadow:0 10px 22px rgba(37,99,235,0.24);"
                >
                    Continue
                </button>
                <button
                    type="button"
                    id="fevourd-pwa-install-later"
                    style="display:inline-flex;align-items:center;justify-content:center;width:100%;height:38px;margin-top:8px;padding:0;border:0;background:transparent;color:#64748b;font-size:13px;font-weight:700;cursor:pointer;"
                >
                    Maybe later
                </button>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var DISMISS = 'fevourd-pwa-install-dismiss-blade-v2';
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
            var laterBtn = document.getElementById('fevourd-pwa-install-later');
            var help = document.getElementById('fevourd-pwa-install-help');
            var stepOne = document.getElementById('fevourd-pwa-step-one');
            var stepTwo = document.getElementById('fevourd-pwa-step-two');
            var stepThree = document.getElementById('fevourd-pwa-step-three');
            if (!shell || !btn || !closeBtn || !laterBtn || !help || !stepOne || !stepTwo || !stepThree) return;

            if (isStandalone() || isDismissed()) return;

            window.__fevourdDeferredInstallPrompt = window.__fevourdDeferredInstallPrompt || null;
            var nativePromptReady = false;

            function platform() {
                var ua = navigator.userAgent || '';
                if (/iphone|ipad|ipod/i.test(ua)) return 'ios';
                if (/android/i.test(ua)) return 'android';
                return 'desktop';
            }

            // Desktop/laptop: behave like a normal website — no intrusive install
            // modal. Users can still install via the browser's address-bar icon.
            // The guided popup is only useful on mobile (no visible install icon).
            function isMobileDevice() {
                return platform() !== 'desktop'
                    || window.matchMedia('(max-width: 820px) and (pointer: coarse)').matches;
            }

            function showInstallPrompt() {
                if (isStandalone() || isDismissed()) return;
                if (!isMobileDevice()) return;
                shell.style.display = 'flex';
            }

            function setSteps(first, second, third) {
                stepOne.textContent = first;
                stepTwo.textContent = second;
                stepThree.textContent = third;
            }

            function setNativeReady() {
                nativePromptReady = true;
                btn.textContent = 'Install app';
                help.textContent = 'Ready now. Tap the button and approve the browser prompt.';
                setSteps('Tap Install app below.', 'Choose Install in the browser prompt.', 'Open FEVOURD-K from your home screen.');
            }

            function setManualReady() {
                if (nativePromptReady) return;
                var device = platform();
                if (device === 'ios') {
                    btn.textContent = 'Got it';
                    help.textContent = 'iPhone and iPad install from the Share menu.';
                    setSteps('Tap the Share button in Safari.', 'Choose Add to Home Screen.', 'Tap Add to finish.');
                    return;
                }
                if (device === 'android') {
                    btn.textContent = 'Got it';
                    help.textContent = 'Use your browser menu if the native prompt is not shown.';
                    setSteps('Tap the browser menu button.', 'Choose Install app or Add to Home screen.', 'Confirm Add or Install.');
                    return;
                }
                btn.textContent = 'Got it';
                help.textContent = 'Use the install icon in your browser bar.';
                setSteps('Look for the install icon in the address bar.', 'Choose Install FEVOURD-K.', 'Launch it from your apps.');
            }

            window.addEventListener('beforeinstallprompt', function (e) {
                e.preventDefault();
                window.__fevourdDeferredInstallPrompt = e;
                setNativeReady();
                showInstallPrompt();
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

            function dismissForNow() {
                try {
                    localStorage.setItem(DISMISS, String(Date.now() + 24 * 60 * 60 * 1000));
                } catch (err) {}
                shell.style.display = 'none';
            }

            closeBtn.addEventListener('click', dismissForNow);
            laterBtn.addEventListener('click', dismissForNow);

            btn.addEventListener('click', function () {
                var p = window.__fevourdDeferredInstallPrompt;
                if (!p) {
                    setManualReady();
                    return;
                }
                window.__fevourdDeferredInstallPrompt = null;
                nativePromptReady = false;
                Promise.resolve(p.prompt()).then(function () {
                    return p.userChoice;
                }).then(function (choice) {
                    if (choice && choice.outcome === 'accepted') {
                        window.__fevourdDeferredInstallPrompt = null;
                        shell.style.display = 'none';
                    } else {
                        btn.textContent = 'Install app';
                        nativePromptReady = true;
                        help.textContent = 'Install was dismissed. Tap again if you want to retry.';
                    }
                }).catch(function () {
                    window.__fevourdDeferredInstallPrompt = null;
                    nativePromptReady = false;
                    setManualReady();
                });
            });

            setTimeout(function () {
                if (!window.__fevourdDeferredInstallPrompt) {
                    setManualReady();
                }
                showInstallPrompt();
            }, 1600);

            if (navigator.serviceWorker && navigator.serviceWorker.ready) {
                navigator.serviceWorker.ready.then(function (registration) {
                    if (registration && typeof registration.update === 'function') {
                        registration.update().catch(function () {});
                    }
                }).catch(function () {});
            }
        })();
    </script>
</body>
</html>
