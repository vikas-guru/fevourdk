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
</body>
</html>
