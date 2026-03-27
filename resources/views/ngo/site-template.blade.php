<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ngo->name }} | NGO Website</title>
    <meta name="description" content="{{ $ngo->description }}">
    <link rel="stylesheet" href="/assets/Themes/styles.css">
    <style>
        :root {
            --ngo-primary: {{ $ngo->theme_color ?: '#2563eb' }};
        }
        .ngo-theme-bg { background-color: var(--ngo-primary) !important; }
        .ngo-theme-text { color: var(--ngo-primary) !important; }
        .ngo-theme-border { border-color: var(--ngo-primary) !important; }
    </style>
</head>
<body>
    @if($preview)
        <div style="position:fixed;top:10px;right:10px;z-index:9999;background:#111827;color:white;padding:8px 12px;border-radius:8px;font:600 12px/1.2 Inter,Arial,sans-serif;">
            Preview Mode
        </div>
    @endif

    <header class="ngo-theme-bg" style="color:white;padding:18px 24px;">
        <div style="max-width:1140px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:16px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <img src="{{ $ngo->logo ? asset('storage/'.$ngo->logo) : asset('/assets/images/fevourd-k/logo.png') }}" alt="{{ $ngo->name }}" style="width:54px;height:54px;border-radius:12px;object-fit:cover;background:white;padding:4px;">
                <div>
                    <h1 style="margin:0;font-size:22px;">{{ $ngo->name }}</h1>
                    <p style="margin:2px 0 0;opacity:.9;font-size:13px;">Powered by FEVOURD-K NGO Digitalization</p>
                </div>
            </div>
            <nav style="display:flex;gap:12px;flex-wrap:wrap;">
                <a href="#about" style="color:white;text-decoration:none;">About</a>
                <a href="#impact" style="color:white;text-decoration:none;">Impact</a>
                <a href="#contact" style="color:white;text-decoration:none;">Contact</a>
            </nav>
        </div>
    </header>

    <main style="max-width:1140px;margin:0 auto;padding:24px;">
        <section id="about" style="display:grid;grid-template-columns:1fr;gap:24px;align-items:center;">
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:22px;">
                <h2 class="ngo-theme-text" style="margin-top:0;">About {{ $ngo->name }}</h2>
                <p style="line-height:1.65;color:#334155;">{{ $ngo->description ?: 'We are a mission-driven NGO working for inclusive social development.' }}</p>
                <p style="color:#475569;margin-bottom:0;"><strong>Focus Areas:</strong> {{ is_array($ngo->focus_areas) ? implode(', ', $ngo->focus_areas) : 'Community Impact' }}</p>
            </div>
            <div>
                <img src="/assets/Themes/assets/images/about/impact.jpg" alt="NGO Impact" style="width:100%;border-radius:16px;max-height:420px;object-fit:cover;">
            </div>
        </section>

        <section id="impact" style="margin-top:28px;">
            <h3 class="ngo-theme-text">Our Programs</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;">
                <article style="border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;background:white;">
                    <img src="/assets/Themes/assets/images/activities/health-camp.jpg" alt="" style="width:100%;height:140px;object-fit:cover;">
                    <div style="padding:12px;">
                        <h4 style="margin:0 0 6px;">Community Health</h4>
                        <p style="margin:0;color:#475569;font-size:14px;">Medical camps and health awareness drives.</p>
                    </div>
                </article>
                <article style="border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;background:white;">
                    <img src="/assets/Themes/assets/images/blog/education-programs.jpg" alt="" style="width:100%;height:140px;object-fit:cover;">
                    <div style="padding:12px;">
                        <h4 style="margin:0 0 6px;">Education</h4>
                        <p style="margin:0;color:#475569;font-size:14px;">Support for learning and school success.</p>
                    </div>
                </article>
                <article style="border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;background:white;">
                    <img src="/assets/Themes/assets/images/activities/world-environment-day.jpg" alt="" style="width:100%;height:140px;object-fit:cover;">
                    <div style="padding:12px;">
                        <h4 style="margin:0 0 6px;">Environment</h4>
                        <p style="margin:0;color:#475569;font-size:14px;">Green initiatives and climate action.</p>
                    </div>
                </article>
            </div>
        </section>

        <section id="contact" style="margin-top:28px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:18px;">
            <h3 class="ngo-theme-text" style="margin-top:0;">Contact</h3>
            <p style="margin:0 0 8px;"><strong>Email:</strong> {{ $ngo->email }}</p>
            <p style="margin:0 0 8px;"><strong>Phone:</strong> {{ $ngo->phone }}</p>
            <p style="margin:0;"><strong>Address:</strong> {{ $ngo->address }}</p>
        </section>
    </main>

    <footer class="ngo-theme-bg" style="color:white;padding:14px 24px;margin-top:24px;">
        <div style="max-width:1140px;margin:0 auto;display:flex;justify-content:space-between;gap:8px;flex-wrap:wrap;">
            <span>{{ $ngo->name }} - Community Impact Platform</span>
            <span>Digitalized by FEVOURD-K</span>
        </div>
    </footer>

    @if($ngo->tawk_property_id && $ngo->tawk_widget_id)
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
            (function() {
                var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = "https://embed.tawk.to/{{ $ngo->tawk_property_id }}/{{ $ngo->tawk_widget_id }}";
                s1.charset = "UTF-8";
                s1.setAttribute("crossorigin", "*");
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
    @endif
</body>
</html>
