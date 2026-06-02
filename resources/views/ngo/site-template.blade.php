@php
    $logoUrl = $ngo->logo
        ? (\Illuminate\Support\Str::startsWith($ngo->logo, ['http://', 'https://'])
            ? $ngo->logo
            : asset(\Illuminate\Support\Str::startsWith($ngo->logo, '/') ? ltrim($ngo->logo, '/') : 'storage/'.$ngo->logo))
        : asset('assets/Themes/assets/images/about/vikasana-brand.png');
    $hex = $microsite['theme_hex'] ?? '#2563eb';
    $blogSlides = (int) max(1, ceil(count($microsite['stories']) / 3));

    // Impact counters (template defaults; swap in NGO-specific values via microsite later)
    $impactCards = [
        ['icon' => 'fa-users', 'target' => '5000', 'display' => '5,000+', 'label' => 'Lives touched'],
        ['icon' => 'fa-hands-helping', 'target' => '120', 'display' => '120+', 'label' => 'Programmes run'],
        ['icon' => 'fa-map-marked-alt', 'target' => '50', 'display' => '50+', 'label' => 'Communities reached'],
        ['icon' => 'fa-handshake', 'target' => '30', 'display' => '30+', 'label' => 'Partners'],
        ['icon' => 'fa-user-friends', 'target' => '200', 'display' => '200+', 'label' => 'Volunteers'],
        ['icon' => 'fa-smile', 'target' => '95', 'display' => '95%', 'label' => 'Positive feedback'],
    ];

    // Gallery (template stock activity imagery — replace per NGO when uploads exist)
    $galleryItems = [
        ['img' => 'assets/Themes/assets/images/activities/world-environment-day.jpg', 'size' => 'large', 'title' => 'Field initiatives', 'caption' => 'On-ground programmes with the community'],
        ['img' => 'assets/Themes/assets/images/activities/covid-relief.jpg', 'size' => '', 'title' => 'Relief & support', 'caption' => 'Essential aid for those in need'],
        ['img' => 'assets/Themes/assets/images/activities/childline-awareness.jpg', 'size' => '', 'title' => 'Awareness drives', 'caption' => 'School and youth outreach'],
        ['img' => 'assets/Themes/assets/images/activities/gender-discrimination.jpg', 'size' => '', 'title' => 'Equality & rights', 'caption' => 'Inclusion-focused sessions'],
        ['img' => 'assets/Themes/assets/images/infrastructure/community-center.jpg', 'size' => 'wide', 'title' => 'Community development', 'caption' => 'Building lasting local capacity'],
        ['img' => 'assets/Themes/assets/images/activities/health-camp.jpg', 'size' => '', 'title' => 'Health camps', 'caption' => 'Medical services for rural areas'],
        ['img' => 'assets/Themes/assets/images/infrastructure/village-meeting.jpg', 'size' => '', 'title' => 'Community engagement', 'caption' => 'Village meetings & consultations'],
        ['img' => 'assets/Themes/assets/images/activities/water-resources.jpg', 'size' => '', 'title' => 'Water & environment', 'caption' => 'Sustainable resource projects'],
    ];

    // ---- Archive-3 parity blocks (template defaults; founder name pulled from NGO) ----
    $missionItems = [
        ['icon' => 'fa-graduation-cap', 'title' => 'Education', 'body' => 'Ensuring access to quality learning for underprivileged children', 'n' => '01'],
        ['icon' => 'fa-heartbeat', 'title' => 'Health & Well-being', 'body' => 'Providing healthcare services and awareness programs', 'n' => '02'],
        ['icon' => 'fa-hand-holding-usd', 'title' => 'Economic Development', 'body' => 'Supporting women entrepreneurship and self-help groups', 'n' => '03'],
        ['icon' => 'fa-seedling', 'title' => 'Sustainable Agriculture', 'body' => 'Promoting organic farming and farmer producer organizations', 'n' => '04'],
    ];
    $approachItems = [
        ['icon' => 'fa-infinity', 'title' => 'Sustainable', 'body' => 'Long-term impact through self-sufficient initiatives', 'stat' => '95%', 'stat_label' => 'Success Rate'],
        ['icon' => 'fa-users', 'title' => 'Inclusive', 'body' => 'Reaching the most vulnerable and marginalized groups', 'stat' => '50K+', 'stat_label' => 'People Reached'],
        ['icon' => 'fa-handshake', 'title' => 'Collaborative', 'body' => 'Partnering with NGOs, government agencies, and corporates', 'stat' => '100+', 'stat_label' => 'Partners'],
    ];
    $approachImpact = [
        ['number' => '1M+', 'label' => 'Lives Impacted'],
        ['number' => '500+', 'label' => 'Active Programs'],
        ['number' => '100+', 'label' => 'Partner Organizations'],
    ];
    $achievementItems = [
        ['icon' => 'fa-mountain', 'title' => 'Natural Resource Management', 'body' => 'Treated 20,000+ hectares of dry land for agricultural use', 'number' => '20,000+'],
        ['icon' => 'fa-female', 'title' => 'Women Empowerment', 'body' => 'Established 3,000+ Self-Help Groups (SHGs) supporting women-led businesses', 'number' => '3,000+'],
        ['icon' => 'fa-child', 'title' => 'Child Welfare & Adoption', 'body' => 'Rescued 52+ abandoned infants and facilitated legal adoptions', 'number' => '52+'],
        ['icon' => 'fa-home', 'title' => 'Housing for the Poor', 'body' => 'Facilitated construction of 1,500+ houses for Beedi workers and BPL families', 'number' => '1,500+'],
        ['icon' => 'fa-leaf', 'title' => 'Sustainable Farming', 'body' => 'Helped farmers transition to organic agriculture and obtain certifications', 'number' => '500+'],
        ['icon' => 'fa-tools', 'title' => 'Skill Development', 'body' => 'Trained 500+ rural women annually in entrepreneurship', 'number' => '500+'],
    ];

    $directorName = $ngo->founder_name ?: 'Shri. Mahesh Chandra Guru';
    $directorImg = asset('assets/Themes/assets/images/leadership/guru.jpeg');
    $directorHighlights = [
        ['icon' => 'fa-award', 'strong' => '30+ Years of Service', 'span' => 'Dedicated leadership in social development'],
        ['icon' => 'fa-users', 'strong' => '50K+ Lives Impacted', 'span' => 'Transforming communities across Karnataka'],
        ['icon' => 'fa-handshake', 'strong' => 'State-level Leadership', 'span' => 'Coordinating NGOs through FEVOURD-K'],
    ];
    $directorQualities = [
        ['icon' => 'fa-heart', 'label' => 'Compassionate Leader'],
        ['icon' => 'fa-lightbulb', 'label' => 'Visionary Thinker'],
        ['icon' => 'fa-hands-helping', 'label' => 'Dedicated Servant'],
        ['icon' => 'fa-graduation-cap', 'label' => 'Wise Mentor'],
    ];

    $leaders = [
        ['name' => 'Dr. R L Jagadish', 'role' => 'President', 'img' => 'jaga.avif', 'featured' => false,
            'body' => 'Leading '.$ngo->name.' with vision and expertise in social development, driving strategic initiatives and organizational growth.',
            'tags' => ['Visionary Leader', 'Strategic Planning', 'Social Development']],
        ['name' => $directorName, 'role' => 'Director & Secretary', 'img' => 'guru.jpeg', 'featured' => true,
            'body' => 'Leading '.$ngo->name.' with exceptional vision and unwavering commitment. Also serving as State President of FEVOURD-K, coordinating voluntary organizations across Karnataka.',
            'tags' => ['Dedicated Service', 'State Leadership', '30+ Years Experience']],
        ['name' => 'Shri. Krishnegowda', 'role' => 'Treasurer', 'img' => 'krishna.avif', 'featured' => false,
            'body' => 'Managing financial operations with integrity and ensuring transparency in all monetary matters, maintaining fiscal responsibility.',
            'tags' => ['Financial Expert', 'Transparency', 'Fiscal Management']],
    ];

    $successStories = [
        ['img' => 'assets/Themes/assets/images/impact/success-story-1.jpg', 'title' => 'Environmental Leadership', 'date' => 'June 2020',
            'body' => 'Successfully organized World Environment Day with Police Department collaboration, promoting environmental awareness across Doddakoppalu Mandya. Over 500 community members participated in tree plantation and cleanliness drives.',
            'stats' => [['icon' => 'fa-tree', 'label' => '500+ Trees Planted'], ['icon' => 'fa-users', 'label' => '500+ Participants']]],
        ['img' => 'assets/Themes/assets/images/impact/success-story-2.jpg', 'title' => 'Emergency Response Excellence', 'date' => 'June 2020',
            'body' => 'Distributed 100 grocery kits to needy people and farm laborers during COVID-19 pandemic, reaching the most vulnerable communities. Our rapid response team ensured essential supplies reached remote villages within 48 hours.',
            'stats' => [['icon' => 'fa-box', 'label' => '100+ Kits Distributed'], ['icon' => 'fa-clock', 'label' => '48 Hours Response']]],
        ['img' => 'assets/Themes/assets/images/impact/success-story-3.jpg', 'title' => 'Child Rights Advocacy', 'date' => 'February 2020',
            'body' => 'Conducted Childline school awareness program at Mahadevpura GHPS school, protecting and educating children about their rights. The program reached 200+ students and trained 15 teachers as child protection advocates.',
            'stats' => [['icon' => 'fa-child', 'label' => '200+ Children Reached'], ['icon' => 'fa-chalkboard-teacher', 'label' => '15 Teachers Trained']]],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ngo->name }} | FEVOURD-K</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($ngo->description ?? ''), 160) }}">
    <meta name="theme-color" content="{{ $hex }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/Themes/styles.css') }}">
    <style>
        :root {
            --primary-color: {{ $hex }};
            --secondary-color: {{ $hex }};
            --accent-color: {{ $hex }};
            --linkedin-blue: {{ $hex }};
        }
        .ngo-preview-ribbon {
            position: fixed;
            top: 12px;
            right: 12px;
            z-index: 100000;
            background: #111827;
            color: #fff;
            padding: 8px 14px;
            border-radius: 10px;
            font: 600 12px/1.2 Inter, system-ui, sans-serif;
            box-shadow: 0 4px 14px rgba(0,0,0,.2);
        }
        .ngo-preview-ribbon a {
            color: #93c5fd;
            margin-left: 8px;
        }
        /* Footer "Powered by NGO OS · Vikas Guru" badge */
        .fk-footer-bottom {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }
        .fk-footer-bottom .fk-copy { margin: 0; opacity: .85; }
        .fk-powered {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font: 500 13.5px/1 Inter, system-ui, sans-serif;
            color: rgba(255,255,255,.78);
        }
        .fk-powered__label { letter-spacing: .03em; text-transform: uppercase; font-size: 11px; opacity: .7; }
        .fk-powered__badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 999px;
            font-weight: 800;
            letter-spacing: .02em;
            color: #fff;
            background: linear-gradient(135deg, {{ $hex }}, #111827);
            box-shadow: 0 6px 18px -6px {{ $hex }}99, inset 0 0 0 1px rgba(255,255,255,.14);
        }
        .fk-powered__badge i { font-size: 12px; opacity: .92; }
        .fk-powered__dot { opacity: .45; }
        .fk-powered__by strong {
            background: linear-gradient(90deg, #fff, {{ $hex }});
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }
        @media (max-width: 640px) {
            .fk-footer-bottom { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
    @if($preview)
        <div class="ngo-preview-ribbon">
            Preview
            <a href="{{ url('/ngo/digitalization') }}">Edit content</a>
        </div>
    @endif

    @if(empty($isOwner))
        <div class="fk-follow-bar" style="--fkc: {{ $hex }}">
            <div class="fk-follow-bar__counts">
                <span><strong>{{ number_format($followersCount ?? 0) }}</strong> following</span>
                <span class="fk-follow-bar__dot">·</span>
                <span><strong>{{ number_format($supportersCount ?? 0) }}</strong> supporting</span>
            </div>
            <div class="fk-follow-bar__actions">
                @if(!empty($authed))
                    <form method="POST" action="{{ url('/ngos/'.$ngo->id.'/follow') }}">
                        @csrf
                        <button type="submit" class="fk-fb-btn {{ !empty($isFollowing) ? 'is-on' : '' }}">
                            {!! !empty($isFollowing) ? '&#10003; Following' : '&#43; Follow' !!}
                        </button>
                    </form>
                    <form method="POST" action="{{ url('/ngos/'.$ngo->id.'/support') }}">
                        @csrf
                        <button type="submit" class="fk-fb-btn fk-fb-btn--support {{ !empty($isSupporting) ? 'is-on' : '' }}">
                            {!! !empty($isSupporting) ? '&#9829; Supporting' : '&#9825; Support' !!}
                        </button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="fk-fb-btn">&#43; Follow</a>
                    <a href="{{ url('/login') }}" class="fk-fb-btn fk-fb-btn--support">&#9825; Support</a>
                @endif
            </div>
        </div>
        <style>
            .fk-follow-bar { position: fixed; z-index: 99990; left: 50%; bottom: 18px; transform: translateX(-50%);
                display: flex; align-items: center; gap: 14px; padding: 10px 12px 10px 18px;
                background: rgba(17,24,39,.92); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,.12);
                border-radius: 999px; box-shadow: 0 18px 44px -16px rgba(0,0,0,.55); color: #fff;
                font: 500 14px/1 Inter, system-ui, sans-serif; max-width: calc(100vw - 24px); }
            .fk-follow-bar__counts { display: flex; align-items: center; gap: 8px; white-space: nowrap; color: #d1d5db; font-size: 13px; }
            .fk-follow-bar__counts strong { color: #fff; }
            .fk-follow-bar__dot { opacity: .5; }
            .fk-follow-bar__actions { display: flex; gap: 8px; }
            .fk-follow-bar form { margin: 0; }
            .fk-fb-btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 999px;
                border: 0; cursor: pointer; font: 700 13.5px/1 Inter, system-ui, sans-serif; text-decoration: none;
                background: var(--fkc, #2e7d32); color: #fff; transition: transform .18s ease, filter .18s ease, opacity .18s ease; }
            .fk-fb-btn:hover { transform: translateY(-2px); filter: brightness(1.08); }
            .fk-fb-btn.is-on { background: rgba(255,255,255,.16); color: #fff; }
            .fk-fb-btn--support { background: #e11d48; }
            .fk-fb-btn--support.is-on { background: rgba(225,29,72,.22); color: #fecdd3; }
            @media (max-width: 560px) {
                .fk-follow-bar { flex-direction: column; gap: 10px; border-radius: 22px; left: 12px; right: 12px; transform: none; max-width: none; padding: 14px; }
                .fk-follow-bar__actions { width: 100%; }
                .fk-fb-btn { flex: 1; justify-content: center; }
            }
        </style>
    @endif

    <div class="loader">
        <div class="loader-content">
            <div class="loader-logo">
                <img src="{{ $logoUrl }}" alt="{{ $ngo->name }}">
            </div>
            <div class="loader-text">{{ strtoupper(\Illuminate\Support\Str::limit($ngo->name, 28)) }}</div>
            <div class="loader-subtitle">Community impact</div>
            <div class="loader-progress">
                <div class="loader-progress-bar"></div>
            </div>
            <div class="loader-dots">
                <div class="loader-dot"></div>
                <div class="loader-dot"></div>
                <div class="loader-dot"></div>
            </div>
        </div>
    </div>

    <header class="header">
        <nav class="navbar">
            <div class="nav-content">
                <div class="nav-logo">
                    <img src="{{ $logoUrl }}" alt="{{ $ngo->name }}" class="logo-img">
                    <span class="logo-text">{{ \Illuminate\Support\Str::limit($ngo->name, 18) }}</span>
                </div>
                <ul class="nav-menu">
                    <li class="nav-item"><a href="#home" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="#activities" class="nav-link">Activities</a></li>
                    <li class="nav-item"><a href="#impact" class="nav-link">Impact</a></li>
                    <li class="nav-item"><a href="#gallery" class="nav-link">Gallery</a></li>
                    <li class="nav-item"><a href="#blog" class="nav-link">Stories</a></li>
                    <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
                    <li class="nav-item"><a href="#donate" class="nav-link btn btn-primary">Donate</a></li>
                </ul>
                <div class="hamburger"><span></span><span></span><span></span></div>
                <div class="fullscreen-menu" id="fullscreenMenu">
                    <button class="menu-close" id="menuClose" type="button"><i class="fas fa-times"></i></button>
                    <div class="menu-content">
                        <a href="#home" class="menu-item"><i class="fas fa-home"></i><span>Home</span></a>
                        <a href="#about" class="menu-item"><i class="fas fa-info-circle"></i><span>About</span></a>
                        <a href="#activities" class="menu-item"><i class="fas fa-tasks"></i><span>Activities</span></a>
                        <a href="#impact" class="menu-item"><i class="fas fa-chart-line"></i><span>Impact</span></a>
                        <a href="#gallery" class="menu-item"><i class="fas fa-images"></i><span>Gallery</span></a>
                        <a href="#blog" class="menu-item"><i class="fas fa-blog"></i><span>Stories</span></a>
                        <a href="#contact" class="menu-item"><i class="fas fa-envelope"></i><span>Contact</span></a>
                        <a href="#donate" class="menu-item donate"><i class="fas fa-heart"></i><span>Donate</span></a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <section id="home" class="hero">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-banner-carousel">
            <div class="banner-carousel-container">
                @foreach($microsite['slides'] as $idx => $slide)
                    <div class="banner-slide banner-slide-{{ ($idx % 4) + 1 }}">
                        <div class="banner-content">
                            <h1 class="banner-title">{{ $slide['title'] }}</h1>
                            <h2 class="banner-subtitle">{{ $slide['subtitle'] }}</h2>
                            <p class="banner-description">{{ $slide['description'] }}</p>
                            <div class="banner-stats">
                                @foreach($slide['stats'] as $st)
                                    <div class="banner-stat">
                                        <div class="banner-stat-number">{{ $st['number'] }}</div>
                                        <div class="banner-stat-label">{{ $st['label'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="banner-actions">
                                <a href="#donate" class="btn btn-primary"><i class="fas fa-heart"></i> Donate</a>
                                <a href="#activities" class="btn btn-secondary"><i class="fas fa-tasks"></i> Programmes</a>
                                <a href="#contact" class="btn btn-outline"><i class="fas fa-envelope"></i> Contact</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="banner-indicators">
                @foreach($microsite['slides'] as $idx => $_)
                    <div class="banner-dot {{ $idx === 0 ? 'active' : '' }}"></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="quick-stats">
        <div class="container">
            <div class="stats-grid">
                @foreach($microsite['quick_stats'] as $qs)
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>{{ $qs['title'] }}</h3>
                            <p>{{ $qs['subtitle'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="blog" class="blog">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Stories &amp; updates</h2>
                <p class="section-subtitle">Highlights from our work — edit these under Digitalization → Microsite content</p>
            </div>
            <div class="blog-carousel-container">
                <div class="blog-carousel-track">
                    @foreach($microsite['stories'] as $story)
                        <article class="blog-card">
                            <div class="blog-image">
                                <img src="{{ $story['image'] }}" alt="{{ $story['title'] }}">
                                <div class="blog-category">{{ $story['category'] }}</div>
                                <div class="blog-date">{{ $story['date'] }}</div>
                            </div>
                            <div class="blog-content">
                                <h3>{{ $story['title'] }}</h3>
                                <p>{{ $story['body'] }}</p>
                                <div class="blog-meta">
                                    <span class="author">{{ $ngo->name }}</span>
                                    <span class="read-time">Update</span>
                                    <button type="button" class="read-aloud-btn" aria-label="Read aloud"><i class="fas fa-volume-up"></i></button>
                                </div>
                                <a href="#contact" class="blog-link">Connect with us</a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="blog-carousel-controls">
                    <button type="button" class="blog-carousel-btn blog-carousel-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                    <button type="button" class="blog-carousel-btn blog-carousel-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="blog-carousel-indicators">
                    @for($i = 0; $i < $blogSlides; $i++)
                        <button type="button" class="blog-indicator {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></button>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ $microsite['about']['section_title'] }}</h2>
                <p class="section-subtitle">{{ $microsite['about']['section_subtitle'] }}</p>
            </div>
            <div class="who-we-are">
                <div class="who-we-are-content">
                    <div class="who-we-are-header">
                        <div class="vikasana-logo">
                            <img src="{{ $logoUrl }}" alt="{{ $ngo->name }}">
                        </div>
                        <div class="who-we-are-text">
                            <h3>{{ $microsite['about']['who_heading'] }}</h3>
                            <p>{!! nl2br(e($microsite['about']['who_byline'])) !!}</p>
                        </div>
                    </div>
                    @foreach($microsite['about']['paragraphs'] as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                </div>
                <div class="who-we-are-visual">
                    <div class="vision-quote">
                        <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                        <div class="quote-content">
                            <h4>Our vision</h4>
                            <blockquote>"{{ $microsite['about']['vision_quote'] }}"</blockquote>
                        </div>
                        <div class="quote-icon"><i class="fas fa-quote-right"></i></div>
                    </div>
                    <div class="vikasana-stats">
                        <div class="stat-item">
                            <div class="stat-number" data-target="1">1</div>
                            <div class="stat-label">Mission</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-target="100">100%</div>
                            <div class="stat-label">Commitment</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-target="24">24/7</div>
                            <div class="stat-label">Dedication</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Our Mission -->
            <div class="mission-section">
                <div class="mission-header">
                    <div class="mission-icon-large"><i class="fas fa-bullseye"></i></div>
                    <div class="mission-text">
                        <h3>Our Mission</h3>
                        <p>{{ $microsite['about']['mission_intro'] ?? 'To empower marginalized communities through strategic interventions in:' }}</p>
                    </div>
                </div>
                <div class="mission-grid">
                    @foreach($missionItems as $mi)
                        <div class="mission-item">
                            <div class="mission-icon"><i class="fas {{ $mi['icon'] }}"></i></div>
                            <div class="mission-content">
                                <h4>{{ $mi['title'] }}</h4>
                                <p>{{ $mi['body'] }}</p>
                                <div class="mission-number">{{ $mi['n'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Our Approach -->
            <div class="approach-section">
                <div class="approach-header">
                    <div class="approach-icon-large"><i class="fas fa-route"></i></div>
                    <div class="approach-text">
                        <h3>Our Approach</h3>
                        <p>{{ $ngo->name }} believes in community participation as a key driver of social change. We work hand-in-hand with local communities, fostering a sense of ownership and collective responsibility.</p>
                    </div>
                </div>
                <div class="approach-items">
                    @foreach($approachItems as $ai)
                        <div class="approach-item">
                            <div class="approach-icon"><i class="fas {{ $ai['icon'] }}"></i></div>
                            <div class="approach-content">
                                <h4>{{ $ai['title'] }}</h4>
                                <p>{{ $ai['body'] }}</p>
                                <div class="approach-stats">
                                    <span class="stat-number">{{ $ai['stat'] }}</span>
                                    <span class="stat-label">{{ $ai['stat_label'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="approach-impact">
                    @foreach($approachImpact as $aim)
                        <div class="impact-stat">
                            <div class="impact-number">{{ $aim['number'] }}</div>
                            <div class="impact-label">{{ $aim['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Key Achievements -->
            <div class="achievements-section">
                <h3>Key Achievements &amp; Milestones</h3>
                <div class="achievements-container">
                    <div class="achievements-track">
                        @foreach($achievementItems as $ach)
                            <div class="achievement-item">
                                <div class="achievement-icon"><i class="fas {{ $ach['icon'] }}"></i></div>
                                <h4>{{ $ach['title'] }}</h4>
                                <p>{{ $ach['body'] }}</p>
                                <div class="achievement-number">{{ $ach['number'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Director & Secretary -->
            <div class="guru-tribute">
                <div class="tribute-header">
                    <h3>Director &amp; Secretary</h3>
                    <p>{{ $directorName }} - Leading {{ $ngo->name }} with Excellence</p>
                    <div class="additional-roles">
                        <span class="role-badge">State President, FEVOURD-K</span>
                        <span class="role-description">Federation of Voluntary Organisations for Urban and Rural Development in Karnataka</span>
                    </div>
                </div>
                <div class="tribute-content">
                    <div class="tribute-left">
                        <div class="tribute-image-container">
                            <div class="tribute-image">
                                <img src="{{ $directorImg }}" alt="{{ $directorName }}">
                            </div>
                            <div class="image-caption">
                                <strong>{{ $directorName }}</strong><br>
                                <span>Director &amp; Secretary, {{ \Illuminate\Support\Str::limit($ngo->name, 24) }}</span><br>
                                <span>State President, FEVOURD-K</span>
                            </div>
                        </div>
                    </div>
                    <div class="tribute-right">
                        <div class="tribute-text">
                            <h4>Visionary Leadership &amp; Dedication</h4>
                            <p>{{ $directorName }}, our present Director &amp; Secretary, continues to lead {{ $ngo->name }} with exceptional vision and unwavering commitment. As the State President of FEVOURD-K, he coordinates and leads voluntary organizations across Karnataka, fostering collaboration and amplifying the impact of social development initiatives throughout the state.</p>
                            <div class="leadership-highlights">
                                @foreach($directorHighlights as $h)
                                    <div class="highlight-item">
                                        <i class="fas {{ $h['icon'] }}"></i>
                                        <div class="highlight-content">
                                            <strong>{{ $h['strong'] }}</strong>
                                            <span>{{ $h['span'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="tribute-qualities">
                                @foreach($directorQualities as $q)
                                    <div class="quality-item">
                                        <i class="fas {{ $q['icon'] }}"></i>
                                        <span>{{ $q['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tribute-impact">
                            <h4>Statewide Impact Through FEVOURD-K</h4>
                            <p>Through his leadership at FEVOURD-K, {{ $directorName }} has created a powerful network of voluntary organizations working in harmony for urban and rural development across Karnataka. His strategic vision and collaborative approach have strengthened the NGO sector, enabling thousands of organizations to serve communities more effectively.</p>
                        </div>
                    </div>
                </div>
                <div class="tribute-footer">
                    <blockquote>"True leadership is about serving others with humility, inspiring positive change, and building bridges between organizations for greater collective impact."</blockquote>
                </div>
            </div>

            <div class="presence-section">
                <h3>Our Presence Across Karnataka</h3>
                <div class="karnataka-map-container">
                    <div class="karnataka-map">
                        <div id="karnataka-map-container" class="geojson-map">
                            <svg id="karnataka-svg" class="karnataka-svg" xmlns="http://www.w3.org/2000/svg">
                                <g id="karnataka-path"></g>
                                <g id="district-markers"></g>
                            </svg>
                        </div>
                        <div class="map-legend">
                            <div class="legend-item">
                                <span class="legend-dot active"></span>
                                <span>Active districts</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot hq"></span>
                                <span>Headquarters</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot upcoming"></span>
                                <span>Upcoming expansion</span>
                            </div>
                        </div>
                    </div>
                    <div class="districts-panel">
                        <h4>Where we work</h4>
                        <div class="districts-list">
                            @php
                                $presenceDistricts = $microsite['districts'] ?? ['Mandya (HQ)', 'Mysuru', 'Bengaluru Urban', 'Bengaluru Rural', 'Hassan', 'Tumakuru', 'Kodagu', 'Chamarajanagar'];
                            @endphp
                            @foreach($presenceDistricts as $d)
                                <div class="district-chip active">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $d }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="districts-stats">
                            <div class="stats-row">
                                <span class="stat-value">{{ count($presenceDistricts) }}</span>
                                <span class="stat-desc">Districts engaged</span>
                            </div>
                            <div class="stats-row">
                                <span class="stat-value">{{ $ngo->city?->name ?? 'Karnataka' }}</span>
                                <span class="stat-desc">Base location</span>
                            </div>
                            <div class="stats-row">
                                <span class="stat-value">India</span>
                                <span class="stat-desc">Region</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="activities" class="activities">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our activities</h2>
                <p class="section-subtitle">Programme areas — customise titles and descriptions in Digitalization</p>
            </div>
            <div class="activities-grid">
                @foreach($microsite['programs'] as $prog)
                    <div class="activity-card">
                        <div class="activity-icon">
                            <i class="fas {{ $prog['icon'] }}"></i>
                        </div>
                        <div class="activity-content">
                            <h3>{{ $prog['title'] }}</h3>
                            <p>{{ $prog['body'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="leadership" class="leadership">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Leadership</h2>
                <p class="section-subtitle">Meet the dedicated team behind {{ $ngo->name }}'s success</p>
            </div>
            <div class="leadership-team">
                <h3>Our Leadership Team</h3>
                <div class="team-grid-enhanced">
                    @foreach($leaders as $member)
                        <div class="team-member-card {{ $member['featured'] ? 'featured' : '' }}">
                            <div class="member-image">
                                <img src="{{ asset('assets/Themes/assets/images/leadership/'.$member['img']) }}" alt="{{ $member['name'] }}">
                                @if($member['featured'])
                                    <div class="featured-badge"><i class="fas fa-star"></i><span>Director &amp; Secretary</span></div>
                                @endif
                            </div>
                            <div class="member-info">
                                <h4>{{ $member['name'] }}</h4>
                                <p class="member-role">{{ $member['role'] }}</p>
                                <p>{{ $member['body'] }}</p>
                                <div class="member-qualities">
                                    @foreach($member['tags'] as $tag)
                                        <span class="quality-tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="impact" class="impact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our impact</h2>
                <p class="section-subtitle">Transforming lives and communities through dedicated service</p>
            </div>
            <div class="impact-stats">
                @foreach($impactCards as $ic)
                    <div class="impact-card">
                        <div class="impact-icon">
                            <i class="fas {{ $ic['icon'] }}"></i>
                        </div>
                        <div class="impact-number" data-target="{{ $ic['target'] }}">{{ $ic['display'] }}</div>
                        <div class="impact-label">{{ $ic['label'] }}</div>
                    </div>
                @endforeach
            </div>
            <div class="impact-stories">
                <h3>Success Stories</h3>
                <div class="stories-grid">
                    @foreach($successStories as $ss)
                        <div class="story-card">
                            <div class="story-image">
                                <img src="{{ asset($ss['img']) }}" alt="{{ $ss['title'] }}" loading="lazy">
                            </div>
                            <div class="story-content">
                                <h4>{{ $ss['title'] }}</h4>
                                <p class="story-meta"><i class="fas fa-calendar"></i> {{ $ss['date'] }}</p>
                                <p>{{ $ss['body'] }}</p>
                                <div class="story-stats">
                                    @foreach($ss['stats'] as $sstat)
                                        <span class="stat-item"><i class="fas {{ $sstat['icon'] }}"></i> {{ $sstat['label'] }}</span>
                                    @endforeach
                                </div>
                                <a href="#contact" class="story-link">Connect with us</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="impact-testimonials">
                <h3>Why our work matters</h3>
                <div class="testimonials-grid">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>"{{ $microsite['about']['vision_quote'] }}"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar"><i class="fas fa-quote-right"></i></div>
                            <div class="author-info">
                                <h5>{{ $ngo->name }}</h5>
                                <p>Our guiding vision</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($ngo->description ?? 'Committed to lasting, community-led change across Karnataka.'), 180) }}</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar"><i class="fas fa-users"></i></div>
                            <div class="author-info">
                                <h5>Community first</h5>
                                <p>How we work</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>"Transparent, accountable, and verified on FEVOURD-K — every contribution is tracked and reported."</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar"><i class="fas fa-shield-alt"></i></div>
                            <div class="author-info">
                                <h5>Trust &amp; transparency</h5>
                                <p>Our commitment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="gallery" class="gallery">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Gallery</h2>
                <p class="section-subtitle">Visual stories of our work and impact</p>
            </div>
            <div class="gallery-grid">
                @foreach($galleryItems as $g)
                    <div class="gallery-item {{ $g['size'] }}">
                        <div class="gallery-image">
                            <img src="{{ asset($g['img']) }}" alt="{{ $g['title'] }}" loading="lazy">
                            <div class="gallery-overlay">
                                <div class="gallery-content">
                                    <h4>{{ $g['title'] }}</h4>
                                    <p>{{ $g['caption'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Get in touch</h2>
                <p class="section-subtitle">{{ $microsite['contact_intro'] }}</p>
            </div>
            <div class="contact-enhanced">
                <div class="contact-left">
                    <div class="contact-info-card">
                        <h3>Contact information</h3>
                        <div class="contact-items">
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="contact-details">
                                    <h4>Address</h4>
                                    <p>{{ $ngo->address }}</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                                <div class="contact-details">
                                    <h4>Phone</h4>
                                    <p>{{ $ngo->phone }}</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                                <div class="contact-details">
                                    <h4>Email</h4>
                                    <p><a href="mailto:{{ $ngo->email }}">{{ $ngo->email }}</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="contact-social">
                            @if($ngo->facebook_url)
                                <a href="{{ $ngo->facebook_url }}" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                            @endif
                            @if($ngo->instagram_url)
                                <a href="{{ $ngo->instagram_url }}" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="contact-right">
                    <form id="contactForm" class="contact-form-card">
                        @csrf
                        <h3>Send a message</h3>
                        <p>This form is a preview — please use email or phone to reach the organisation.</p>
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" rows="4" placeholder="Message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section id="donate" class="donate">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ $microsite['donate']['title'] }}</h2>
                <p class="section-subtitle">{{ $microsite['donate']['subtitle'] }}</p>
            </div>
            <div class="donate-enhanced">
                <div class="donate-impact">
                    <div class="impact-header">
                        <div class="impact-icon"><i class="fas fa-heart"></i></div>
                        <h3>Your impact</h3>
                        <p>{{ $microsite['donate']['impact_blurb'] }}</p>
                    </div>
                    <div class="impact-stats">
                        <div class="stat-card">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Transparent reporting</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">FEVOURD-K</div>
                            <div class="stat-label">Verified platform</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">₹</div>
                            <div class="stat-label">Secure checkout</div>
                        </div>
                    </div>
                </div>
                <div class="donate-payment">
                    <div class="payment-header">
                        <h3>Donate via campaigns</h3>
                        <p>Browse live campaigns on FEVOURD-K and complete payment with our trusted partners.</p>
                        <div class="secure-badge"><i class="fas fa-lock"></i><span>Platform checkout</span></div>
                    </div>
                    <a href="{{ route('campaigns.index') }}" class="donate-btn" style="display:inline-flex;align-items:center;justify-content:center;width:100%;margin-top:1rem;text-decoration:none;">
                        <i class="fas fa-heart"></i>
                        View campaigns to donate
                    </a>
                    @if($ngo->slug)
                        <p style="margin-top:1rem;font-size:0.9rem;color:#64748b;text-align:center;">
                            Directory: <a href="{{ url('/ngos/'.$ngo->slug) }}">public listing</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <img src="{{ $logoUrl }}" alt="{{ $ngo->name }}">
                        </div>
                        <h4>{{ $ngo->name }}</h4>
                    </div>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($ngo->description ?? ''), 200) }}</p>
                    <div class="footer-certifications">
                        <div class="cert-item"><i class="fas fa-certificate"></i><span>{{ $ngo->verification_status === 'verified' ? 'Verified NGO' : 'Registered' }}</span></div>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick links</h4>
                    <ul class="footer-links">
                        <li><a href="#about">About</a></li>
                        <li><a href="#activities">Activities</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="{{ route('campaigns.index') }}">Campaigns</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul class="footer-contact">
                        <li><i class="fas fa-map-marker-alt"></i> {{ $ngo->address }}</li>
                        <li><i class="fas fa-phone"></i> {{ $ngo->phone }}</li>
                        <li><i class="fas fa-envelope"></i> {{ $ngo->email }}</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Follow</h4>
                    <div class="footer-social">
                        @if($ngo->facebook_url)
                            <a href="{{ $ngo->facebook_url }}" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if($ngo->instagram_url)
                            <a href="{{ $ngo->instagram_url }}" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="footer-bottom fk-footer-bottom">
                <p class="fk-copy">&copy; {{ date('Y') }} {{ $ngo->name }}. All rights reserved.</p>
                <div class="fk-powered">
                    <span class="fk-powered__label">Powered by</span>
                    <span class="fk-powered__badge">
                        <i class="fas fa-cube"></i> NGO&nbsp;OS
                    </span>
                    <span class="fk-powered__dot">·</span>
                    <span class="fk-powered__by">Crafted by <strong>Vikas&nbsp;Guru</strong></span>
                </div>
            </div>
        </div>
    </footer>

    @if($ngo->tawk_property_id && $ngo->tawk_widget_id)
        <script>
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

    <script>
        window.showNotification = window.showNotification || function (message) {
            if (window.console) console.log(message);
        };
    </script>
    <script src="{{ asset('assets/Themes/script.js') }}"></script>
    <script src="{{ asset('assets/Themes/scroll-animations.js') }}"></script>
    <script src="{{ asset('assets/Themes/karnataka-map.js') }}"></script>
</body>
</html>
