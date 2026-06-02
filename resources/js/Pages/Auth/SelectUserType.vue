<template>
    <AppLayout title="Choose Your Role - FEVOURD-K" :hide-chrome-mobile="true">
        <div class="fkr">
            <!-- ============ HERO HEADER ============ -->
            <section class="fkr-hero">
                <div class="fkr-hero__rings" aria-hidden="true">
                    <span class="fkr-ring fkr-ring--1"></span>
                    <span class="fkr-ring fkr-ring--2"></span>
                    <span class="fkr-ring fkr-ring--3"></span>
                </div>
                <div class="fkr-glow" aria-hidden="true"></div>
                <div class="fkr-grain" aria-hidden="true"></div>

                <div class="fkr-wrap fkr-hero__inner">
                    <div class="fkr-emblem fkr-rise" style="--d:.05s">
                        <img :src="logoImage" alt="FEVOURD-K" class="fkr-emblem__logo">
                    </div>
                    <p class="fkr-eyebrow fkr-rise" style="--d:.12s">
                        <span class="fkr-eyebrow__dot"></span> Become a member · It's free
                    </p>
                    <h1 class="fkr-display fkr-hero__title fkr-rise" style="--d:.2s">
                        Choose how you'll<br><span class="fkr-accent-text">make change.</span>
                    </h1>
                    <p class="fkr-hero__sub fkr-rise" style="--d:.28s">
                        One platform, four ways in. Join Karnataka's largest network of
                        verified voluntary organisations, donors and changemakers.
                    </p>
                    <dl class="fkr-hero__stats fkr-rise" style="--d:.36s">
                        <div><dt>800+</dt><dd>Verified NGOs</dd></div>
                        <div><dt>31</dt><dd>Districts</dd></div>
                        <div><dt>50K+</dt><dd>Changemakers</dd></div>
                    </dl>
                </div>
            </section>

            <!-- ============ ROLE CARDS ============ -->
            <section class="fkr-roles">
                <div class="fkr-wrap">
                    <header class="fkr-section-head" data-reveal>
                        <p class="fkr-kicker">Pick your path</p>
                        <h2 class="fkr-display fkr-h2">Who are you joining as?</h2>
                    </header>

                    <div class="fkr-roles__grid">
                        <Link
                            v-for="(r, i) in roles"
                            :key="r.key"
                            :href="r.href"
                            class="fkr-card"
                            data-reveal
                            :style="{ '--accent': accentMap[r.accent], '--i': i }"
                        >
                            <span class="fkr-card__bar"></span>
                            <div class="fkr-card__top">
                                <span class="fkr-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path :d="r.icon" />
                                    </svg>
                                </span>
                                <span class="fkr-card__no fkr-display">{{ r.no }}</span>
                            </div>
                            <h3 class="fkr-card__title fkr-display">{{ r.title }}</h3>
                            <p class="fkr-card__desc">{{ r.desc }}</p>
                            <ul class="fkr-card__tags">
                                <li v-for="t in r.tags" :key="t">{{ t }}</li>
                            </ul>
                            <span v-if="r.badge" class="fkr-card__badge">🤖 {{ r.badge }}</span>
                            <span class="fkr-card__go">{{ r.cta }} <span class="fkr-card__arrow">→</span></span>
                        </Link>
                    </div>
                </div>
            </section>

            <!-- ============ WHY JOIN ============ -->
            <section class="fkr-why">
                <div class="fkr-grain" aria-hidden="true"></div>
                <div class="fkr-wrap">
                    <header class="fkr-section-head" data-reveal>
                        <p class="fkr-kicker fkr-kicker--gold">The FEVOURD-K difference</p>
                        <h2 class="fkr-display fkr-h2 fkr-h2--light">Built on trust, by design</h2>
                    </header>
                    <div class="fkr-why__grid">
                        <article v-for="(b, i) in benefits" :key="b.title" class="fkr-benefit" data-reveal :style="{ '--i': i }">
                            <span class="fkr-benefit__icon" :style="{ '--accent': b.accent }">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path v-for="(d, k) in b.paths" :key="k" :d="d" />
                                </svg>
                            </span>
                            <h3 class="fkr-benefit__title">{{ b.title }}</h3>
                            <p class="fkr-benefit__desc">{{ b.desc }}</p>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ============ LOGIN STRIP ============ -->
            <section class="fkr-login">
                <div class="fkr-wrap fkr-login__inner">
                    <p>Already part of the federation?</p>
                    <Link href="/login" class="fkr-login__btn">Sign in to your account →</Link>
                    <p class="fkr-login__note">🌟 Join 50,000+ changemakers making a difference across Karnataka</p>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { onMounted, onUnmounted } from 'vue'

const logoImage = '/assets/images/fevourd-k/logo.png'

const accentMap = { emerald: '#1f8a5b', gold: '#d99405', magenta: '#c12a63', ink: '#0d1f5c' }

const roles = [
    {
        key: 'individual', href: '/register/individual', accent: 'emerald', no: '01',
        title: 'Individual Donor',
        desc: 'Support causes you care about with transparent donations and give your time as a volunteer.',
        tags: ['Donate & volunteer', 'Track your impact'], cta: 'Continue as donor',
        icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    },
    {
        key: 'ngo', href: '/register/ngo-chat', accent: 'gold', no: '02',
        title: 'NGO / Voluntary Org',
        desc: 'Register your organisation and unlock funding, volunteers and CSR partnerships.',
        tags: ['Receive donations', 'CSR partnerships'], cta: 'Start registration',
        badge: 'Smart chat registration',
        icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    },
    {
        key: 'corporate', href: '/register/corporate', accent: 'magenta', no: '03',
        title: 'Corporate CSR',
        desc: 'Manage CSR activity, discover verified NGOs and track social-impact investments end to end.',
        tags: ['CSR compliance', 'Impact tracking'], cta: 'Continue as corporate',
        icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    },
    {
        key: 'volunteer', href: '/register/volunteer', accent: 'ink', no: '04',
        title: 'Volunteer',
        desc: 'Lend your time and skills to causes that matter, right where you live in Karnataka.',
        tags: ['Flexible hours', 'Skill development'], cta: 'Continue as volunteer',
        icon: 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z',
    },
]

const benefits = [
    {
        title: 'Location-based discovery', accent: '#d99405',
        desc: 'Find NGOs and causes near you across Karnataka’s 31 districts with intelligent matching.',
        paths: ['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
    },
    {
        title: 'Transparent donations', accent: '#1f8a5b',
        desc: 'Track every rupee with real-time fund-utilisation reports and verifiable impact metrics.',
        paths: ['M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    },
    {
        title: 'CSR compliance', accent: '#c12a63',
        desc: 'Generate compliant reports and certificates for corporate giving, tracked automatically.',
        paths: ['M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
    },
]

/* reveal-on-scroll + scroll-spy "active" card — visible by default, JS only enhances */
let observer = null
let activeObserver = null
let safety = null
onMounted(() => {
    if (!('IntersectionObserver' in window)) return

    // Entrance reveals
    const els = Array.from(document.querySelectorAll('[data-reveal]'))
    if (els.length) {
        els.forEach((el) => el.classList.add('reveal-init'))
        observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-in')
                    observer.unobserve(entry.target)
                }
            })
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' })
        els.forEach((el) => observer.observe(el))
        safety = setTimeout(() => els.forEach((el) => el.classList.add('reveal-in')), 2500)
    }

    // Scroll-spy: the role card crossing the viewport centre lights up like hover
    // (gives touch users the interactive feel hover can't on mobile).
    if (window.innerWidth <= 1024) {
        const cards = Array.from(document.querySelectorAll('.fkr-card'))
        activeObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                entry.target.classList.toggle('is-active', entry.isIntersecting)
            })
        }, { rootMargin: '-44% 0px -44% 0px', threshold: 0 })
        cards.forEach((c) => activeObserver.observe(c))
    }
})
onUnmounted(() => {
    if (observer) observer.disconnect()
    if (activeObserver) activeObserver.disconnect()
    if (safety) clearTimeout(safety)
})
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap');

.fkr {
    --ink: #0d1f5c;
    --ink-2: #11286e;
    --ink-deep: #081640;
    --gold: #f2b40c;
    --gold-soft: #f7c948;
    --magenta: #c12a63;
    --emerald: #1f8a5b;
    --paper: #f7f0df;
    --paper-2: #efe6cd;
    --char: #1c2230;
    --font-display: 'Fraunces', 'Playfair Display', Georgia, serif;
    --font-body: 'Hanken Grotesk', ui-sans-serif, system-ui, 'Segoe UI', sans-serif;
    font-family: var(--font-body);
    color: var(--char);
    background: var(--paper);
    overflow-x: clip;
}
.fkr-display { font-family: var(--font-display); font-optical-sizing: auto; }
.fkr-wrap { width: 100%; max-width: 1160px; margin: 0 auto; padding: 0 clamp(20px, 5vw, 56px); }

.fkr-grain { position: absolute; inset: 0; pointer-events: none; opacity: .5; mix-blend-mode: overlay;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E"); }

@keyframes fkrRise { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: none; } }
.fkr-rise { opacity: 0; animation: fkrRise .8s cubic-bezier(.2,.7,.2,1) both; animation-delay: var(--d, 0s); }
.reveal-init { opacity: 0; transform: translateY(26px); }
.reveal-in { opacity: 1; transform: none; transition: opacity .7s ease, transform .7s cubic-bezier(.2,.7,.2,1); transition-delay: calc(var(--i, 0) * 70ms); }
@media (prefers-reduced-motion: reduce) { .fkr-rise, .reveal-init, .reveal-in { animation: none !important; opacity: 1 !important; transform: none !important; transition: none !important; } }
@keyframes fkrSpin { to { transform: rotate(360deg); } }

/* ============ HERO ============ */
.fkr-hero { position: relative; isolation: isolate; overflow: hidden; text-align: center; color: #f4eeda;
    background: radial-gradient(120% 120% at 50% -20%, #1b3aa0 0%, var(--ink-2) 42%, var(--ink-deep) 100%);
    padding: clamp(48px, 8vw, 96px) 0 clamp(56px, 8vw, 96px); }
.fkr-glow { position: absolute; z-index: -1; width: 70vw; height: 70vw; left: 50%; top: -34vw; transform: translateX(-50%);
    background: radial-gradient(circle, rgba(242,180,12,.22), rgba(242,180,12,0) 62%); }
.fkr-hero__rings { position: absolute; z-index: -1; left: 50%; top: 46%; transform: translate(-50%,-50%); width: min(78vw, 760px); aspect-ratio: 1; opacity: .4; }
.fkr-ring { position: absolute; inset: 0; border-radius: 50%; border: 1.5px dashed rgba(242,180,12,.4); }
.fkr-ring--2 { inset: 12%; border-style: solid; border-color: rgba(193,42,99,.32); }
.fkr-ring--3 { inset: 26%; border-color: rgba(31,138,91,.36); animation: fkrSpin 70s linear infinite; }
.fkr-hero__inner { position: relative; display: flex; flex-direction: column; align-items: center; }
.fkr-emblem { position: relative; display: grid; place-items: center; width: clamp(84px, 12vw, 116px); aspect-ratio: 1; border-radius: 50%; margin-bottom: 1.4rem;
    background: radial-gradient(circle at 50% 38%, rgba(247,240,223,.16), rgba(247,240,223,.04) 70%);
    box-shadow: inset 0 0 0 1px rgba(242,180,12,.3), 0 24px 50px -22px rgba(0,0,0,.6); }
.fkr-emblem::before { content: ''; position: absolute; width: clamp(104px, 14vw, 140px); aspect-ratio: 1; border-radius: 50%; border: 2px dotted rgba(242,180,12,.4); animation: fkrSpin 50s linear infinite; }
.fkr-emblem__logo { width: 64%; height: auto; filter: drop-shadow(0 8px 18px rgba(0,0,0,.45)); }
.fkr-eyebrow { display: inline-flex; align-items: center; gap: .55em; font-size: .78rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: var(--gold-soft); margin: 0; }
.fkr-eyebrow__dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold); box-shadow: 0 0 0 4px rgba(242,180,12,.2); }
.fkr-hero__title { font-weight: 600; font-size: clamp(2.3rem, 6vw, 4.4rem); line-height: 1; letter-spacing: -.02em; margin: 1rem 0 0; }
.fkr-accent-text { color: var(--gold); font-style: italic; font-weight: 500; }
.fkr-hero__sub { max-width: 52ch; margin: 1.3rem auto 0; font-size: clamp(1rem, 1.5vw, 1.2rem); line-height: 1.6; color: #d6d3c4; }
.fkr-hero__stats { display: flex; flex-wrap: wrap; justify-content: center; gap: clamp(1.6rem, 5vw, 3.4rem); margin: 2.4rem 0 0; padding-top: 1.8rem; border-top: 1px solid rgba(244,238,218,.16); }
.fkr-hero__stats dt { font-family: var(--font-display); font-size: clamp(1.6rem, 3vw, 2.3rem); font-weight: 600; color: var(--gold-soft); line-height: 1; }
.fkr-hero__stats dd { margin: .35rem 0 0; font-size: .82rem; letter-spacing: .03em; color: #c7c4b4; }

/* ============ SECTION HEADS ============ */
.fkr-section-head { text-align: center; max-width: 620px; margin: 0 auto clamp(34px, 5vw, 56px); }
.fkr-kicker { font-size: .76rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--magenta); margin: 0 0 .7rem; }
.fkr-kicker--gold { color: var(--gold); }
.fkr-h2 { font-weight: 600; font-size: clamp(1.8rem, 4vw, 2.9rem); line-height: 1.05; letter-spacing: -.02em; color: var(--ink); margin: 0; }
.fkr-h2--light { color: #f4eeda; }

/* ============ ROLE CARDS ============ */
.fkr-roles { padding: clamp(54px, 8vw, 100px) 0; background: linear-gradient(var(--paper), var(--paper-2)); }
.fkr-roles__grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: clamp(16px, 2vw, 24px); }
.fkr-card { position: relative; display: flex; flex-direction: column; padding: clamp(22px, 2.4vw, 32px); border-radius: 20px; background: #fffdf6; border: 1px solid rgba(13,31,92,.1); text-decoration: none; overflow: hidden; isolation: isolate;
    transition: transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s ease, border-color .35s ease; }
.fkr-card__bar { position: absolute; left: 0; top: 0; width: 100%; height: 5px; background: var(--accent); transform: scaleX(0); transform-origin: left; transition: transform .4s ease; }
.fkr-card:hover { transform: translateY(-8px); box-shadow: 0 30px 60px -30px color-mix(in srgb, var(--accent) 60%, #0d1f5c); border-color: transparent; }
.fkr-card:hover .fkr-card__bar { transform: scaleX(1); }
.fkr-card__top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.1rem; }
.fkr-card__icon { display: grid; place-items: center; width: 56px; height: 56px; border-radius: 16px; color: var(--accent);
    background: color-mix(in srgb, var(--accent) 12%, transparent); transition: transform .35s ease, background .35s ease, color .35s ease; }
.fkr-card__icon svg { width: 28px; height: 28px; }
.fkr-card:hover .fkr-card__icon { transform: scale(1.08) rotate(-3deg); background: var(--accent); color: #fff; }
.fkr-card__no { font-size: 1.4rem; font-weight: 600; color: color-mix(in srgb, var(--accent) 55%, #b9b09a); }
.fkr-card__title { font-size: 1.34rem; font-weight: 600; line-height: 1.15; color: var(--ink); margin: 0 0 .55rem; letter-spacing: -.01em; }
.fkr-card__desc { margin: 0 0 1rem; color: #5a5e6a; font-size: .94rem; line-height: 1.5; }
.fkr-card__tags { list-style: none; margin: 0 0 1.1rem; padding: 0; display: flex; flex-wrap: wrap; gap: .45rem; }
.fkr-card__tags li { font-size: .76rem; font-weight: 600; padding: .35em .8em; border-radius: 999px; color: color-mix(in srgb, var(--accent) 78%, #2a2a2a);
    background: color-mix(in srgb, var(--accent) 12%, transparent); border: 1px solid color-mix(in srgb, var(--accent) 30%, transparent); }
.fkr-card__tags li::before { content: '✓ '; }
.fkr-card__badge { display: inline-flex; align-self: flex-start; margin-bottom: 1rem; padding: .4em .85em; border-radius: 10px; font-size: .76rem; font-weight: 700; color: #2a1c00; background: linear-gradient(90deg, var(--gold), var(--gold-soft)); }
.fkr-card__go { margin-top: auto; display: inline-flex; align-items: center; gap: .4em; font-weight: 700; font-size: .92rem; color: var(--accent); }
.fkr-card__arrow { transition: transform .25s ease; }
.fkr-card:hover .fkr-card__arrow { transform: translateX(5px); }

/* Scroll-activated card (touch): mirrors hover + a soft accent glow ring */
.fkr-card.is-active { transform: translateY(-8px); box-shadow: 0 30px 60px -30px color-mix(in srgb, var(--accent) 60%, #0d1f5c); border-color: transparent; }
.fkr-card.is-active .fkr-card__bar { transform: scaleX(1); }
.fkr-card.is-active .fkr-card__icon { transform: scale(1.1) rotate(-3deg); background: var(--accent); color: #fff; }
.fkr-card.is-active .fkr-card__arrow { transform: translateX(6px); }
.fkr-card.is-active .fkr-card__no { color: var(--accent); }
.fkr-card.is-active::after {
    content: ''; position: absolute; inset: 0; border-radius: 20px; pointer-events: none;
    box-shadow: inset 0 0 0 1.5px color-mix(in srgb, var(--accent) 45%, transparent);
    animation: fkrGlow 2.2s ease-in-out infinite;
}
@keyframes fkrGlow { 0%, 100% { opacity: .35; } 50% { opacity: .9; } }

/* Living hero touches */
.fkr-emblem__logo { animation: fkrFloat 5s ease-in-out infinite; }
@keyframes fkrFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
.fkr-eyebrow__dot { animation: fkrDotPulse 1.9s ease-out infinite; }
@keyframes fkrDotPulse {
    0% { box-shadow: 0 0 0 0 rgba(242, 180, 12, 0.55); }
    100% { box-shadow: 0 0 0 11px rgba(242, 180, 12, 0); }
}

@media (prefers-reduced-motion: reduce) {
    .fkr-card.is-active::after, .fkr-emblem__logo, .fkr-eyebrow__dot { animation: none !important; }
}

/* ============ WHY ============ */
.fkr-why { position: relative; isolation: isolate; overflow: hidden; padding: clamp(54px, 8vw, 104px) 0;
    background: radial-gradient(120% 120% at 85% 0%, #16266b 0%, var(--ink) 45%, var(--ink-deep) 100%); color: #eef0f7; }
.fkr-why__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: clamp(18px, 3vw, 36px); }
.fkr-benefit { padding: clamp(22px, 2.6vw, 34px); border-radius: 18px; background: rgba(247,240,223,.05); border: 1px solid rgba(247,240,223,.12); text-align: left; }
.fkr-benefit__icon { display: grid; place-items: center; width: 60px; height: 60px; border-radius: 16px; margin-bottom: 1.2rem; color: var(--accent);
    background: color-mix(in srgb, var(--accent) 16%, transparent); border: 1px solid color-mix(in srgb, var(--accent) 36%, transparent); }
.fkr-benefit__icon svg { width: 30px; height: 30px; }
.fkr-benefit__title { font-family: var(--font-display); font-size: 1.28rem; font-weight: 600; color: #fff; margin: 0 0 .55rem; }
.fkr-benefit__desc { margin: 0; color: #c3c7d6; line-height: 1.55; font-size: .95rem; }

/* ============ LOGIN STRIP ============ */
.fkr-login { padding: clamp(48px, 7vw, 84px) 0; background: var(--paper); text-align: center; }
.fkr-login__inner { display: flex; flex-direction: column; align-items: center; gap: 1rem; }
.fkr-login__inner > p:first-child { margin: 0; font-family: var(--font-display); font-size: clamp(1.3rem, 3vw, 1.9rem); font-weight: 600; color: var(--ink); }
.fkr-login__btn { display: inline-flex; align-items: center; gap: .5em; padding: .9em 1.8em; border-radius: 999px; font-weight: 700; text-decoration: none; color: var(--paper); background: var(--ink); box-shadow: 0 16px 34px -16px rgba(13,31,92,.7); transition: transform .25s ease, box-shadow .25s ease; }
.fkr-login__btn:hover { transform: translateY(-3px); box-shadow: 0 22px 40px -16px rgba(13,31,92,.8); }
.fkr-login__note { margin: .6rem 0 0; color: #7a7e8a; font-size: .9rem; }

/* ============ RESPONSIVE ============ */
@media (max-width: 1040px) { .fkr-roles__grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 860px) { .fkr-why__grid { grid-template-columns: 1fr; } }

/* ===== Premium mobile (phones) ===== */
@media (max-width: 560px) {
    .fkr-roles__grid { grid-template-columns: 1fr; gap: 14px; }

    /* Tighter rhythm so the page isn't an endless scroll */
    .fkr-hero { padding: 40px 0 46px; }
    .fkr-roles { padding: 40px 0 50px; }
    .fkr-why { padding: 46px 0; }
    .fkr-login { padding: 40px 0 calc(48px + env(safe-area-inset-bottom)); }
    .fkr-section-head { margin-bottom: 26px; }
    .fkr-hero__stats { gap: 1.5rem; margin-top: 1.9rem; padding-top: 1.4rem; }

    /* Compact, tap-friendly role cards; accent bar always on (no hover on touch) */
    .fkr-card { padding: 18px; border-radius: 18px; box-shadow: 0 14px 30px -22px rgba(13, 31, 92, .5); }
    .fkr-card__bar { transform: scaleX(1); height: 4px; }
    .fkr-card__top { margin-bottom: .85rem; }
    .fkr-card__icon { width: 48px; height: 48px; border-radius: 13px; background: color-mix(in srgb, var(--accent) 14%, transparent); }
    .fkr-card__icon svg { width: 24px; height: 24px; }
    .fkr-card__no { font-size: 1.25rem; }
    .fkr-card__title { font-size: 1.22rem; margin-bottom: .4rem; }
    .fkr-card__desc { font-size: .92rem; margin-bottom: .85rem; }
    .fkr-card__tags { margin-bottom: .9rem; }
    .fkr-card__go { margin-top: .2rem; font-size: .96rem; }
    .fkr-card:active { transform: translateY(-2px) scale(.992); }

    .fkr-why__grid { gap: 14px; }
    .fkr-benefit { padding: 20px; }
}

@media (max-width: 380px) {
    .fkr-hero__stats { gap: 1.1rem; }
    .fkr-card__desc { font-size: .89rem; }
}

/* ===== App-like focused onboarding on phones — surface the choices fast ===== */
@media (max-width: 560px) {
    .fkr-hero { padding: 30px 0 34px; }
    .fkr-emblem { width: 70px; margin-bottom: .85rem; }
    .fkr-hero__title { font-size: 1.95rem; margin-top: .7rem; }
    .fkr-hero__sub { font-size: .94rem; margin-top: .8rem; max-width: 38ch; }
    .fkr-hero__stats { display: none; }            /* drop marketing stats so role cards sit higher */
    .fkr-section-head { margin-bottom: 18px; }
    .fkr-h2 { font-size: 1.55rem; }

    /* Role cards become clean, tappable app options with a clear CTA button */
    .fkr-card { padding: 16px; }
    .fkr-card__desc { margin-bottom: .85rem; }
    .fkr-card__go {
        margin-top: .5rem;
        justify-content: center;
        padding: .8em 1em;
        border-radius: 13px;
        color: #fff;
        background: var(--accent);
        box-shadow: 0 10px 22px -12px var(--accent);
    }
    .fkr-card__go,
    .fkr-card__go .fkr-card__arrow { color: #fff; }
    .fkr-card:active { transform: scale(.99); }
}
</style>
