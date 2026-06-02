<template>
    <AppLayout hide-chrome-mobile>
        <div class="fk">
            <!-- ============================ HERO ============================ -->
            <section class="fk-hero">
                <!-- decorative emblem rings (echoes the logo gear) -->
                <div class="fk-hero__rings" aria-hidden="true">
                    <span class="fk-ring fk-ring--1"></span>
                    <span class="fk-ring fk-ring--2"></span>
                    <span class="fk-ring fk-ring--3"></span>
                </div>
                <div class="fk-hero__glow" aria-hidden="true"></div>
                <div class="fk-grain" aria-hidden="true"></div>

                <div class="fk-wrap fk-hero__inner">
                    <div class="fk-hero__copy">
                        <p class="fk-eyebrow fk-rise" style="--d:.05s">
                            <span class="fk-eyebrow__dot"></span>
                            Est. 1982 · Karnataka · An apex body
                        </p>

                        <h1 class="fk-display fk-hero__title">
                            <span class="fk-rise" style="--d:.12s">Voluntary action,</span>
                            <span class="fk-rise fk-hero__accent" style="--d:.2s">for a stronger</span>
                            <span class="fk-rise" style="--d:.28s">Karnataka.</span>
                        </h1>

                        <p class="fk-hero__lede fk-rise" style="--d:.36s">
                            A federation of over
                            <strong>{{ displayStats.organisations }}+ voluntary organisations</strong>
                            advancing social, economic and cultural empowerment of deprived
                            communities — through transparent giving, verified campaigns and a
                            shared commitment to a vibrant democracy.
                        </p>

                        <div class="fk-hero__cta fk-rise" style="--d:.44s">
                            <Link href="/donate" class="fk-btn fk-btn--gold">
                                Donate now
                                <svg viewBox="0 0 24 24" class="fk-btn__arrow"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                            </Link>
                            <Link href="/ngos" class="fk-btn fk-btn--ghost">Explore organisations</Link>
                            <Link href="/register" class="fk-btn fk-btn--link">Join the federation →</Link>
                        </div>

                        <dl class="fk-hero__mini fk-rise" style="--d:.52s">
                            <div><dt>{{ displayStats.districts }}</dt><dd>districts reached</dd></div>
                            <div><dt>{{ displayStats.founded }}</dt><dd>serving since</dd></div>
                            <div><dt>{{ displayStats.beneficiaries }}</dt><dd>lives touched</dd></div>
                        </dl>
                    </div>

                    <div class="fk-hero__emblem fk-rise" style="--d:.3s">
                        <div class="fk-emblem">
                            <img
                                :src="brandLogoSrc"
                                alt="FEVOURD-K — Federation of Voluntary Organisations Karnataka"
                                width="120" height="123"
                                class="fk-emblem__logo"
                                fetchpriority="high" decoding="async"
                            >
                        </div>
                        <p class="fk-emblem__tag">Federation of Voluntary<br>Organisations · Karnataka</p>
                    </div>
                </div>

                <!-- running marquee of the emblem's words -->
                <div class="fk-marquee" aria-hidden="true">
                    <div class="fk-marquee__track">
                        <span v-for="n in 2" :key="n" class="fk-marquee__group">
                            <template v-for="(w, i) in pillarWords" :key="w + n">
                                <span>{{ w }}</span><span class="fk-marquee__star">✦</span>
                            </template>
                        </span>
                    </div>
                </div>
            </section>

            <!-- ============================ STATS ============================ -->
            <section class="fk-stats" data-reveal>
                <div class="fk-wrap">
                    <div class="fk-stats__grid">
                        <article v-for="(s, i) in bigStats" :key="s.label" class="fk-stat" :style="{ '--i': i }">
                            <span class="fk-stat__value fk-display">{{ s.value }}</span>
                            <span class="fk-stat__rule"></span>
                            <span class="fk-stat__label">{{ s.label }}</span>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ============================ PILLARS (from the emblem) ============================ -->
            <section class="fk-pillars">
                <div class="fk-wrap">
                    <header class="fk-section-head" data-reveal>
                        <p class="fk-kicker">The emblem, the mandate</p>
                        <h2 class="fk-display fk-h2">Six pillars cast into our seal</h2>
                        <p class="fk-section-head__sub">
                            Every value on the FEVOURD-K crest is a promise we organise around —
                            binding government, voluntary organisations and people into one circle.
                        </p>
                    </header>

                    <div class="fk-pillars__grid">
                        <article v-for="(p, i) in pillars" :key="p.title" class="fk-pillar" data-reveal :style="{ '--i': i }">
                            <span class="fk-pillar__no">{{ String(i + 1).padStart(2, '0') }}</span>
                            <h3 class="fk-pillar__title">{{ p.title }}</h3>
                            <p class="fk-pillar__desc">{{ p.desc }}</p>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ============================ CAMPAIGNS ============================ -->
            <section class="fk-campaigns">
                <div class="fk-wrap">
                    <header class="fk-section-head fk-section-head--row" data-reveal>
                        <div>
                            <p class="fk-kicker fk-kicker--gold">On the ground, right now</p>
                            <h2 class="fk-display fk-h2 fk-h2--light">Campaigns seeking support</h2>
                        </div>
                        <Link href="/campaigns" class="fk-btn fk-btn--ghost-light">View all campaigns</Link>
                    </header>

                    <div class="fk-camp__grid">
                        <article v-for="(c, i) in campaigns" :key="c.id ?? i" class="fk-camp" data-reveal :style="{ '--i': i }">
                            <div class="fk-camp__media">
                                <img :src="c.featured_image || getCampaignImage(i)" :alt="c.title" loading="lazy" decoding="async">
                                <span class="fk-camp__cat">{{ c.category || 'Community' }}</span>
                            </div>
                            <div class="fk-camp__body">
                                <h3 class="fk-camp__title">{{ c.title }}</h3>
                                <p class="fk-camp__desc">{{ c.description }}</p>
                                <div class="fk-camp__bar"><span :style="{ width: pct(c) + '%' }"></span></div>
                                <div class="fk-camp__meta">
                                    <span><strong>{{ formatCurrency(c.raised_amount) }}</strong> raised</span>
                                    <span>{{ pct(c) }}%</span>
                                </div>
                                <Link :href="c.slug ? `/campaigns/${c.slug}` : '/campaigns'" class="fk-camp__cta">
                                    Support this →
                                </Link>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ============================ FEATURED ORGANISATIONS ============================ -->
            <section v-if="orgs.length" class="fk-orgs">
                <div class="fk-wrap">
                    <header class="fk-section-head fk-section-head--row" data-reveal>
                        <div>
                            <p class="fk-kicker">Verified on the federation</p>
                            <h2 class="fk-display fk-h2">Organisations to follow</h2>
                        </div>
                        <Link href="/ngos" class="fk-btn fk-btn--ghost-light">Explore all organisations</Link>
                    </header>

                    <div class="fk-orgs__grid">
                        <article v-for="(o, i) in orgs" :key="o.id" class="fk-org" data-reveal :style="{ '--i': i, '--accent': o.theme_color }">
                            <div class="fk-org__top">
                                <div class="fk-org__logo">
                                    <img v-if="o.logo" :src="o.logo" :alt="o.name" loading="lazy" decoding="async">
                                    <span v-else>{{ (o.name || '?').slice(0, 1) }}</span>
                                </div>
                                <span class="fk-org__verified">✓ Verified</span>
                            </div>
                            <h3 class="fk-org__name">{{ o.name }}</h3>
                            <p class="fk-org__desc">{{ o.description }}</p>
                            <ul class="fk-org__tags">
                                <li v-for="f in o.focus_areas" :key="f">{{ f }}</li>
                            </ul>
                            <div class="fk-org__social">
                                <span><strong>{{ fmtCount(o.followers_count) }}</strong> followers</span>
                                <span><strong>{{ fmtCount(o.supporters_count) }}</strong> supporters</span>
                            </div>
                            <div class="fk-org__cta">
                                <Link :href="`/${o.slug}`" class="fk-org__visit">Visit website →</Link>
                                <button type="button" class="fk-org__follow" @click="followOrg(o)">＋ Follow</button>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ============================ COMMUNITY FEED ============================ -->
            <section v-if="posts.length" class="fk-feed">
                <div class="fk-wrap">
                    <header class="fk-section-head fk-section-head--row" data-reveal>
                        <div>
                            <p class="fk-kicker fk-kicker--gold">Live from the community</p>
                            <h2 class="fk-display fk-h2">Latest from the federation</h2>
                        </div>
                        <Link href="/feeds" class="fk-btn fk-btn--ghost-light">Open the feed</Link>
                    </header>

                    <div class="fk-feed__grid">
                        <article v-for="(p, i) in posts" :key="p.id" class="fk-feedcard" data-reveal :style="{ '--i': i }">
                            <Link v-if="p.image_url" :href="p.public_url" class="fk-feedcard__media">
                                <img :src="p.image_url" :alt="p.title || p.author" loading="lazy" decoding="async">
                            </Link>
                            <div class="fk-feedcard__body">
                                <div class="fk-feedcard__head">
                                    <span class="fk-feedcard__avatar">
                                        <img v-if="p.logo" :src="p.logo" :alt="p.author">
                                        <span v-else>{{ (p.author || '?').slice(0, 1) }}</span>
                                    </span>
                                    <span class="fk-feedcard__meta">
                                        <span class="fk-feedcard__author">{{ p.author }}</span>
                                        <span class="fk-feedcard__time">{{ timeAgo(p.created_at) }}</span>
                                    </span>
                                </div>
                                <Link v-if="p.title" :href="p.public_url" class="fk-feedcard__title">{{ p.title }}</Link>
                                <p class="fk-feedcard__text">{{ p.excerpt }}</p>
                                <div class="fk-feedcard__foot">
                                    <button type="button" class="fk-feedcard__act" @click="reactToPost(p)" aria-label="React">
                                        <span class="fk-feedcard__heart">♥</span> {{ p.reactions_count }}
                                    </button>
                                    <Link :href="p.public_url" class="fk-feedcard__act">💬 {{ p.comments_count }}</Link>
                                    <Link :href="p.public_url" class="fk-feedcard__more">Read →</Link>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ============================ FOCUS AREAS ============================ -->
            <section class="fk-focus">
                <div class="fk-grain" aria-hidden="true"></div>
                <div class="fk-wrap fk-focus__inner">
                    <div class="fk-focus__lead" data-reveal>
                        <p class="fk-kicker fk-kicker--gold">Where we work</p>
                        <h2 class="fk-display fk-h2 fk-h2--light">Fifteen fronts of<br>development.</h2>
                        <p class="fk-focus__text">
                            From a child's first classroom to a farmer's fair price, our members
                            carry change into every corner of Karnataka — and we keep it accountable.
                        </p>
                        <Link href="/programs" class="fk-btn fk-btn--gold">See our programmes</Link>
                    </div>
                    <ul class="fk-focus__cloud" data-reveal>
                        <li v-for="(f, i) in focusAreas" :key="f" :style="{ '--i': i }">{{ f }}</li>
                    </ul>
                </div>
            </section>

            <!-- ============================ EVENTS ============================ -->
            <section v-if="events.length" class="fk-events">
                <div class="fk-wrap">
                    <header class="fk-section-head" data-reveal>
                        <p class="fk-kicker">Convening the sector</p>
                        <h2 class="fk-display fk-h2">Upcoming events</h2>
                    </header>
                    <article v-for="(e, i) in events" :key="i" class="fk-event" data-reveal>
                        <div class="fk-event__date">
                            <span class="fk-event__cal">✦</span>
                            <span>{{ e.date }}</span>
                        </div>
                        <div class="fk-event__body">
                            <h3 class="fk-event__title">{{ e.title }}</h3>
                            <p class="fk-event__desc">{{ e.description }}</p>
                            <p class="fk-event__loc">📍 {{ e.location }}</p>
                            <p v-if="e.organizers" class="fk-event__org">{{ e.organizers }}</p>
                        </div>
                        <Link :href="e.registration_url || '/events'" class="fk-btn fk-btn--gold fk-event__cta">Register</Link>
                    </article>
                </div>
            </section>

            <!-- ============================ CLOSING CTA ============================ -->
            <section class="fk-cta">
                <div class="fk-hero__glow" aria-hidden="true"></div>
                <div class="fk-grain" aria-hidden="true"></div>
                <div class="fk-wrap fk-cta__inner" data-reveal>
                    <p class="fk-kicker fk-kicker--gold">Join the circle</p>
                    <h2 class="fk-display fk-cta__title">
                        Run a voluntary organisation?<br>Bring it into the federation.
                    </h2>
                    <p class="fk-cta__text">
                        Get verified, raise funds transparently, publish impact, and stand with
                        800+ organisations strengthening Karnataka.
                    </p>
                    <div class="fk-cta__buttons">
                        <Link href="/register" class="fk-btn fk-btn--gold">Register your organisation</Link>
                        <Link href="/about" class="fk-btn fk-btn--ghost">Learn about us</Link>
                        <Link href="/feeds" class="fk-btn fk-btn--link">Browse the community feed →</Link>
                    </div>
                </div>
            </section>

            <!-- ============================ LOGIN PROMPT (guest actions) ============================ -->
            <transition name="fk-modal">
                <div v-if="authPrompt.open" class="fk-authwrap" @click.self="closeAuthPrompt">
                    <div class="fk-auth" role="dialog" aria-modal="true" aria-labelledby="fk-auth-title">
                        <button class="fk-auth__x" @click="closeAuthPrompt" aria-label="Close">✕</button>
                        <div class="fk-auth__emblem"><img :src="brandLogoSrc" alt="" width="52" height="52"></div>
                        <h3 id="fk-auth-title" class="fk-display fk-auth__title">Join the federation</h3>
                        <p class="fk-auth__text">
                            Sign in to <strong>{{ authPrompt.action }}</strong> and stand with
                            800+ voluntary organisations across Karnataka.
                        </p>
                        <div class="fk-auth__btns">
                            <Link href="/login" class="fk-btn fk-btn--gold">Log in</Link>
                            <Link href="/register" class="fk-btn fk-btn--ghost-light">Create account</Link>
                        </div>
                        <button class="fk-auth__later" @click="closeAuthPrompt">Maybe later</button>
                    </div>
                </div>
            </transition>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'

const brandLogoSrc = '/assets/images/fevourd-k/logo.png'

const props = defineProps({
    stats: { type: Object, default: () => ({}) },
    featuredCampaigns: { type: Array, default: () => [] },
    featuredNgos: { type: Array, default: () => [] },
    latestPosts: { type: Array, default: () => [] },
    upcomingEvents: { type: Array, default: () => [] },
})

const orgs = computed(() => props.featuredNgos ?? [])
const posts = computed(() => props.latestPosts ?? [])
const fmtCount = (n) => (n >= 1000 ? (n / 1000).toFixed(1).replace('.0', '') + 'k' : String(n ?? 0))

/* ---- guest gating: prompt sign-in before any member action ---- */
const page = usePage()
const isGuest = computed(() => !page.props.auth?.user)
const authPrompt = ref({ open: false, action: '' })
const openAuthPrompt = (action) => { authPrompt.value = { open: true, action } }
const closeAuthPrompt = () => { authPrompt.value.open = false }
// Run `fn` if signed in; otherwise show the friendly login prompt.
const requireAuth = (action, fn) => {
    if (isGuest.value) { openAuthPrompt(action); return }
    fn?.()
}
const followOrg = (o) => requireAuth(`follow ${o.name}`, () => {
    router.post(`/ngos/${o.id}/follow`, {}, { preserveScroll: true, preserveState: false })
})
const reactToPost = (post) => requireAuth('react to this post', () => {
    router.visit(post.public_url)
})
const timeAgo = (iso) => {
    if (!iso) return ''
    const d = (Date.now() - new Date(iso).getTime()) / 1000
    if (d < 3600) return Math.max(1, Math.round(d / 60)) + 'm ago'
    if (d < 86400) return Math.round(d / 3600) + 'h ago'
    if (d < 604800) return Math.round(d / 86400) + 'd ago'
    return new Date(iso).toLocaleDateString('en-IN', { day: 'numeric', month: 'short' })
}

/* ---- words from the emblem ---- */
const pillarWords = ['Independence', 'Government', 'Voluntary Organisations', 'Equality', 'Development', 'People']

const pillars = [
    { title: 'Independence', desc: 'A non-partisan voice for civil society, free to hold power to account.' },
    { title: 'Government', desc: 'A constructive bridge between the state and grassroots organisations.' },
    { title: 'Voluntary Organisations', desc: '800+ verified members, federated for collective strength.' },
    { title: 'Equality', desc: 'Standing with the deprived — by caste, gender, ability and means.' },
    { title: 'Development', desc: 'Social, economic and cultural empowerment, measured by real impact.' },
    { title: 'People', desc: 'Communities at the centre — never beneficiaries, always partners.' },
]

const focusAreas = [
    'Children’s Rights', 'Education', 'Healthcare', 'Environment', 'Climate Action',
    'Women & SHGs', 'Farmers & FPOs', 'Rural Development', 'Disability Inclusion',
    'Livelihoods', 'Water & Sanitation', 'Community Development', 'Disaster Response',
    'Democratic Participation', 'Cultural Heritage',
]

/* ---- stats with sensible federation fallbacks (so an empty DB still reads true) ---- */
// FEVOURD-K is an apex federation of 800+ voluntary organisations (the figure the
// copy speaks to). Only a fraction are digitally onboarded, so use the federation
// size as a floor rather than showing the small onboarded count (e.g. "3+").
const displayStats = computed(() => ({
    organisations: Math.max(Number(props.stats?.ngos) || 0, 800),
    districts: 31,
    founded: 1982,
    beneficiaries: '1,00,000+',
}))

const bigStats = computed(() => [
    { value: displayStats.value.organisations + '+', label: 'Voluntary organisations' },
    { value: displayStats.value.districts, label: 'Districts across Karnataka' },
    { value: displayStats.value.founded, label: 'Serving the sector since' },
    { value: displayStats.value.beneficiaries, label: 'Lives touched' },
])

/* ---- campaigns: real prop first, sample fallback ---- */
const sampleCampaigns = [
    { id: 's1', title: 'Clean Water for Rural Schools', category: 'Water & Sanitation', description: 'Drinking water and sanitation for 50 rural schools across Karnataka — better health and learning for thousands of children.', target_amount: 500000, raised_amount: 325000, progress_percentage: 65, featured_image: null, slug: null },
    { id: 's2', title: 'Education for Every Child', category: 'Education', description: 'Books, uniforms and digital learning for underprivileged children in urban slums and remote taluks.', target_amount: 1000000, raised_amount: 750000, progress_percentage: 75, featured_image: null, slug: null },
    { id: 's3', title: 'Healthcare on Wheels', category: 'Healthcare', description: 'Mobile medical units and health camps reaching the last village — essential care where it is scarcest.', target_amount: 750000, raised_amount: 600000, progress_percentage: 80, featured_image: null, slug: null },
]
const campaigns = computed(() => (props.featuredCampaigns?.length ? props.featuredCampaigns : sampleCampaigns))

const events = computed(() => props.upcomingEvents ?? [])

/* ---- helpers ---- */
const formatCurrency = (amount) =>
    new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 0 }).format(amount || 0)

const pct = (c) => {
    if (c.progress_percentage != null) return Math.round(c.progress_percentage)
    const target = c.target_amount || c.goal_amount || 0
    return target ? Math.min(100, Math.round((c.raised_amount / target) * 100)) : 0
}

const getCampaignImage = (index) => {
    const images = [
        'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=900&h=650&fit=crop',
        'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=900&h=650&fit=crop',
        'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=900&h=650&fit=crop',
        'https://images.unsplash.com/photo-1497486751825-1233686d5d80?w=900&h=650&fit=crop',
        'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=900&h=650&fit=crop',
        'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=900&h=650&fit=crop',
    ]
    return images[index % images.length]
}

/* ---- reveal-on-scroll (content visible by default; JS only enhances) ---- */
let observer = null
let safety = null
onMounted(() => {
    const els = Array.from(document.querySelectorAll('[data-reveal]'))
    if (!('IntersectionObserver' in window) || !els.length) return
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
    // safety: if anything is still hidden after 2.5s, force-reveal it
    safety = setTimeout(() => els.forEach((el) => el.classList.add('reveal-in')), 2500)
})
onUnmounted(() => {
    if (observer) observer.disconnect()
    if (safety) clearTimeout(safety)
})
</script>

<style>
/* global (unscoped) — fonts + tokens */
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700;9..144,900&family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap');

.fk {
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
    --font-display: 'Fraunces', 'Playfair Display', Georgia, 'Times New Roman', serif;
    --font-body: 'Hanken Grotesk', ui-sans-serif, system-ui, 'Segoe UI', sans-serif;

    font-family: var(--font-body);
    color: var(--char);
    background: var(--paper);
    overflow-x: clip;
}
.fk .fk-display { font-family: var(--font-display); font-optical-sizing: auto; }
.fk-wrap { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 clamp(20px, 5vw, 56px); }

/* grain + entrance */
.fk-grain {
    position: absolute; inset: 0; pointer-events: none; opacity: .5; mix-blend-mode: overlay;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
}
@keyframes fkRise { from { opacity: 0; transform: translateY(26px); } to { opacity: 1; transform: none; } }
.fk-rise { opacity: 0; animation: fkRise .8s cubic-bezier(.2,.7,.2,1) both; animation-delay: var(--d, 0s); }
.reveal-init { opacity: 0; transform: translateY(28px); }
.reveal-in { opacity: 1; transform: none; transition: opacity .7s ease, transform .7s cubic-bezier(.2,.7,.2,1); transition-delay: calc(var(--i, 0) * 70ms); }
@media (prefers-reduced-motion: reduce) {
    .fk-rise, .reveal-init, .reveal-in { animation: none !important; opacity: 1 !important; transform: none !important; transition: none !important; }
}

/* ============ HERO ============ */
.fk-hero {
    position: relative; isolation: isolate; overflow: hidden;
    background: radial-gradient(120% 120% at 85% -10%, #1b3aa0 0%, var(--ink-2) 38%, var(--ink-deep) 100%);
    color: #f4eeda; padding: clamp(56px, 9vw, 120px) 0 0;
}
.fk-hero__glow { position: absolute; z-index: -1; width: 60vw; height: 60vw; right: -12vw; top: -16vw;
    background: radial-gradient(circle, rgba(242,180,12,.28), rgba(242,180,12,0) 62%); filter: blur(8px); }
.fk-hero__rings { position: absolute; z-index: -1; right: -6vw; top: 50%; transform: translateY(-50%); width: min(46vw, 620px); aspect-ratio: 1; opacity: .55; }
.fk-ring { position: absolute; inset: 0; border-radius: 50%; border: 1.5px dashed rgba(242,180,12,.4); }
.fk-ring--2 { inset: 11%; border-style: solid; border-color: rgba(193,42,99,.35); }
.fk-ring--3 { inset: 24%; border-color: rgba(31,138,91,.4); animation: fkSpin 60s linear infinite; }
@keyframes fkSpin { to { transform: rotate(360deg); } }

.fk-hero__inner { position: relative; display: grid; grid-template-columns: 1.35fr .85fr; gap: clamp(28px, 5vw, 72px); align-items: center; padding-bottom: clamp(44px, 6vw, 80px); }
.fk-eyebrow { display: inline-flex; align-items: center; gap: .6em; font-size: .8rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: var(--gold-soft); }
.fk-eyebrow__dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold); box-shadow: 0 0 0 4px rgba(242,180,12,.2); }
.fk-hero__title { font-weight: 600; font-size: clamp(2.6rem, 6.4vw, 5.3rem); line-height: .98; letter-spacing: -.02em; margin: .28em 0 0; }
.fk-hero__title span { display: block; }
.fk-hero__accent { color: var(--gold); font-style: italic; font-weight: 500; }
.fk-hero__lede { max-width: 42ch; margin-top: 1.4rem; font-size: clamp(1.02rem, 1.5vw, 1.22rem); line-height: 1.6; color: #d9d6c6; }
.fk-hero__lede strong { color: #fff; font-weight: 700; }
.fk-hero__cta { display: flex; flex-wrap: wrap; align-items: center; gap: .85rem; margin-top: 2rem; }
.fk-hero__mini { display: flex; flex-wrap: wrap; gap: clamp(1.4rem, 4vw, 3rem); margin-top: 2.6rem; padding-top: 1.6rem; border-top: 1px solid rgba(244,238,218,.16); }
.fk-hero__mini dt { font-family: var(--font-display); font-size: clamp(1.5rem, 3vw, 2.1rem); font-weight: 600; color: var(--gold-soft); line-height: 1; }
.fk-hero__mini dd { margin: .35rem 0 0; font-size: .82rem; letter-spacing: .03em; color: #c7c4b4; }

.fk-hero__emblem { display: flex; flex-direction: column; align-items: center; gap: 1.1rem; }
.fk-emblem { position: relative; display: grid; place-items: center; width: clamp(180px, 22vw, 264px); aspect-ratio: 1; border-radius: 50%;
    background: radial-gradient(circle at 50% 38%, rgba(247,240,223,.16), rgba(247,240,223,.04) 70%);
    box-shadow: inset 0 0 0 1px rgba(242,180,12,.3), 0 30px 60px -24px rgba(0,0,0,.6); }
.fk-emblem::before { content: ''; position: absolute; inset: -10px; border-radius: 50%; border: 2px dotted rgba(242,180,12,.45); animation: fkSpin 48s linear infinite; }
.fk-emblem__logo { width: 74%; height: auto; filter: drop-shadow(0 10px 22px rgba(0,0,0,.45)); }
.fk-emblem__tag { font-family: var(--font-display); font-style: italic; font-size: 1rem; text-align: center; color: #e7e3d2; line-height: 1.35; }

/* marquee */
.fk-marquee { border-top: 1px solid rgba(242,180,12,.22); border-bottom: 1px solid rgba(242,180,12,.22); background: rgba(8,22,64,.5); overflow: hidden; }
.fk-marquee__track { display: flex; width: max-content; animation: fkMarquee 32s linear infinite; }
.fk-marquee__group { display: flex; align-items: center; }
.fk-marquee__group span { font-family: var(--font-display); font-style: italic; font-weight: 500; font-size: clamp(1rem, 1.8vw, 1.5rem); color: rgba(247,240,223,.78); padding: .9rem 1.1rem; white-space: nowrap; }
.fk-marquee__star { color: var(--gold); font-style: normal; }
@keyframes fkMarquee { to { transform: translateX(-50%); } }

/* ============ BUTTONS ============ */
.fk-btn { display: inline-flex; align-items: center; gap: .5em; padding: .85em 1.5em; border-radius: 999px; font-weight: 600; font-size: .95rem; letter-spacing: .01em; text-decoration: none; transition: transform .25s ease, box-shadow .25s ease, background .25s ease, color .25s ease; }
.fk-btn--gold { background: var(--gold); color: #2a1c00; box-shadow: 0 14px 30px -12px rgba(242,180,12,.7); }
.fk-btn--gold:hover { transform: translateY(-3px); box-shadow: 0 20px 38px -12px rgba(242,180,12,.85); }
.fk-btn__arrow { width: 1.1em; height: 1.1em; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; transition: transform .25s ease; }
.fk-btn--gold:hover .fk-btn__arrow { transform: translateX(4px); }
.fk-btn--ghost { background: rgba(247,240,223,.08); color: #f4eeda; border: 1px solid rgba(247,240,223,.34); }
.fk-btn--ghost:hover { background: rgba(247,240,223,.16); transform: translateY(-3px); }
.fk-btn--ghost-light { background: transparent; color: var(--ink); border: 1px solid rgba(13,31,92,.3); }
.fk-btn--ghost-light:hover { background: var(--ink); color: var(--paper); transform: translateY(-3px); }
.fk-btn--link { background: transparent; color: var(--gold-soft); padding-left: .4em; padding-right: .4em; }
.fk-btn--link:hover { color: var(--gold); }

/* ============ STATS ============ */
.fk-stats { background: var(--paper); padding: clamp(48px, 7vw, 92px) 0; }
.fk-stats__grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1px; background: rgba(13,31,92,.14); border: 1px solid rgba(13,31,92,.14); border-radius: 18px; overflow: hidden; }
.fk-stat { background: var(--paper); padding: clamp(22px, 3vw, 40px); display: flex; flex-direction: column; gap: .7rem; }
.fk-stat__value { font-size: clamp(2.4rem, 5vw, 4rem); font-weight: 600; line-height: .9; letter-spacing: -.02em; color: var(--ink); }
.fk-stat__rule { width: 38px; height: 4px; border-radius: 2px; background: var(--gold); }
.fk-stat:nth-child(2) .fk-stat__rule { background: var(--magenta); }
.fk-stat:nth-child(3) .fk-stat__rule { background: var(--emerald); }
.fk-stat:nth-child(4) .fk-stat__rule { background: var(--ink); }
.fk-stat__label { font-size: .92rem; font-weight: 500; color: #5a5d68; max-width: 16ch; }

/* ============ SECTION HEADS ============ */
.fk-section-head { max-width: 640px; margin: 0 auto clamp(36px, 5vw, 60px); text-align: center; }
.fk-section-head--row { max-width: none; margin: 0 0 clamp(32px, 4vw, 52px); display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; text-align: left; }
.fk-kicker { font-size: .78rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--magenta); margin: 0 0 .8rem; }
.fk-kicker--gold { color: var(--gold); }
.fk-h2 { font-weight: 600; font-size: clamp(1.9rem, 4vw, 3.1rem); line-height: 1.04; letter-spacing: -.02em; color: var(--ink); margin: 0; }
.fk-h2--light { color: #f4eeda; }
.fk-section-head__sub { margin: 1rem 0 0; color: #5a5d68; font-size: 1.05rem; line-height: 1.6; }

/* ============ PILLARS ============ */
.fk-pillars { padding: clamp(56px, 8vw, 104px) 0; background: linear-gradient(var(--paper), var(--paper-2)); }
.fk-pillars__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: clamp(16px, 2vw, 26px); }
.fk-pillar { position: relative; padding: clamp(24px, 3vw, 38px); border-radius: 18px; background: #fffdf6; border: 1px solid rgba(13,31,92,.1); overflow: hidden; transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease; }
.fk-pillar::after { content: ''; position: absolute; left: 0; top: 0; width: 4px; height: 100%; background: var(--gold); transform: scaleY(0); transform-origin: top; transition: transform .4s ease; }
.fk-pillar:hover { transform: translateY(-6px); box-shadow: 0 26px 50px -28px rgba(13,31,92,.4); border-color: transparent; }
.fk-pillar:hover::after { transform: scaleY(1); }
.fk-pillar__no { font-family: var(--font-display); font-size: 1rem; font-weight: 600; color: var(--magenta); }
.fk-pillar__title { font-family: var(--font-display); font-size: clamp(1.3rem, 2.2vw, 1.7rem); font-weight: 600; color: var(--ink); margin: .5rem 0 .6rem; letter-spacing: -.01em; }
.fk-pillar__desc { margin: 0; color: #565a66; line-height: 1.55; font-size: .98rem; }

/* ============ CAMPAIGNS ============ */
.fk-campaigns { padding: clamp(56px, 8vw, 104px) 0; background: var(--ink); position: relative; }
.fk-camp__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: clamp(18px, 2.4vw, 30px); }
.fk-camp { display: flex; flex-direction: column; background: #fffdf6; border-radius: 20px; overflow: hidden; box-shadow: 0 30px 60px -34px rgba(0,0,0,.6); transition: transform .4s cubic-bezier(.2,.7,.2,1); }
.fk-camp:hover { transform: translateY(-8px); }
.fk-camp__media { position: relative; aspect-ratio: 16/10; overflow: hidden; }
.fk-camp__media img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
.fk-camp:hover .fk-camp__media img { transform: scale(1.06); }
.fk-camp__cat { position: absolute; left: 14px; top: 14px; padding: .35em .9em; border-radius: 999px; background: rgba(13,31,92,.86); color: var(--gold-soft); font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; backdrop-filter: blur(4px); }
.fk-camp__body { display: flex; flex-direction: column; gap: .7rem; padding: clamp(18px, 2.2vw, 26px); flex: 1; }
.fk-camp__title { font-family: var(--font-display); font-size: 1.32rem; font-weight: 600; line-height: 1.18; color: var(--ink); margin: 0; }
.fk-camp__desc { margin: 0; color: #565a66; font-size: .92rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.fk-camp__bar { height: 8px; border-radius: 999px; background: #e7ddc4; overflow: hidden; margin-top: auto; }
.fk-camp__bar span { display: block; height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--gold), var(--magenta)); }
.fk-camp__meta { display: flex; justify-content: space-between; align-items: baseline; font-size: .86rem; color: #6a6e7a; }
.fk-camp__meta strong { color: var(--ink); font-size: 1.02rem; }
.fk-camp__cta { margin-top: .4rem; align-self: flex-start; font-weight: 700; color: var(--magenta); text-decoration: none; transition: color .2s ease; }
.fk-camp__cta:hover { color: var(--ink); }

/* ============ FOCUS ============ */
.fk-focus { position: relative; isolation: isolate; overflow: hidden; padding: clamp(56px, 8vw, 110px) 0;
    background: radial-gradient(120% 120% at 10% 0%, #134a35 0%, #0e3a2a 50%, #0a2a20 100%); color: #eef3ee; }
.fk-focus__inner { display: grid; grid-template-columns: .9fr 1.1fr; gap: clamp(28px, 5vw, 64px); align-items: center; }
.fk-focus__text { margin: 1.2rem 0 2rem; color: #c9d6cd; line-height: 1.65; font-size: 1.06rem; max-width: 40ch; }
.fk-focus .fk-h2--light { color: #f4eeda; }
.fk-focus__cloud { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: .7rem; }
.fk-focus__cloud li { padding: .6em 1.1em; border-radius: 999px; border: 1px solid rgba(244,238,218,.28); background: rgba(244,238,218,.05); font-weight: 500; font-size: .96rem; transition: transform .25s ease, background .25s ease, color .25s ease, border-color .25s ease; }
.fk-focus__cloud li:hover { transform: translateY(-3px); background: var(--gold); color: #2a1c00; border-color: var(--gold); }

/* ============ EVENTS ============ */
.fk-events { padding: clamp(56px, 8vw, 104px) 0; background: var(--paper); }
.fk-event { display: grid; grid-template-columns: auto 1fr auto; gap: clamp(18px, 3vw, 40px); align-items: center; padding: clamp(22px, 3vw, 38px); border-radius: 20px; background: #fffdf6; border: 1px solid rgba(13,31,92,.12); }
.fk-event__date { display: flex; flex-direction: column; align-items: center; gap: .4rem; padding-right: clamp(18px, 3vw, 40px); border-right: 1px solid rgba(13,31,92,.12); font-family: var(--font-display); font-weight: 600; color: var(--ink); text-align: center; max-width: 12ch; }
.fk-event__cal { color: var(--gold); font-size: 1.4rem; }
.fk-event__title { font-family: var(--font-display); font-size: clamp(1.3rem, 2.4vw, 1.8rem); font-weight: 600; color: var(--ink); margin: 0 0 .5rem; line-height: 1.15; }
.fk-event__desc { margin: 0 0 .7rem; color: #565a66; line-height: 1.55; }
.fk-event__loc { margin: 0; font-weight: 600; color: var(--magenta); font-size: .92rem; }
.fk-event__org { margin: .3rem 0 0; color: #7a7e8a; font-size: .86rem; font-style: italic; }
.fk-event__cta { align-self: center; }

/* ============ CLOSING CTA ============ */
.fk-cta { position: relative; isolation: isolate; overflow: hidden; padding: clamp(64px, 9vw, 128px) 0; text-align: center;
    background: radial-gradient(120% 130% at 50% -20%, #1b3aa0 0%, var(--ink-2) 42%, var(--ink-deep) 100%); color: #f4eeda; }
.fk-cta__inner { max-width: 760px; margin: 0 auto; }
.fk-cta__title { font-weight: 600; font-size: clamp(2rem, 4.6vw, 3.4rem); line-height: 1.06; letter-spacing: -.02em; margin: .4rem 0 1.2rem; }
.fk-cta__text { color: #d4d1c2; font-size: 1.1rem; line-height: 1.6; max-width: 52ch; margin: 0 auto 2.2rem; }
.fk-cta__buttons { display: flex; flex-wrap: wrap; justify-content: center; gap: .85rem; }

/* ============ FEATURED ORGANISATIONS ============ */
.fk-orgs { padding: clamp(56px, 8vw, 104px) 0; background: linear-gradient(var(--paper-2), var(--paper)); }
.fk-orgs__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: clamp(18px, 2.4vw, 28px); }
.fk-org { position: relative; display: flex; flex-direction: column; gap: .9rem; padding: clamp(22px, 2.6vw, 32px); border-radius: 22px; background: #fffdf6; border: 1px solid rgba(13,31,92,.1); overflow: hidden; transition: transform .4s cubic-bezier(.2,.7,.2,1), box-shadow .4s ease; }
.fk-org::before { content: ''; position: absolute; inset: 0 0 auto 0; height: 5px; background: var(--accent, var(--emerald)); opacity: .9; }
.fk-org:hover { transform: translateY(-8px); box-shadow: 0 30px 60px -32px rgba(13,31,92,.45); }
.fk-org__top { display: flex; align-items: center; justify-content: space-between; }
.fk-org__logo { width: 64px; height: 64px; border-radius: 16px; display: grid; place-items: center; overflow: hidden; background: color-mix(in srgb, var(--accent, var(--emerald)) 12%, #fff); }
.fk-org__logo img { width: 80%; height: 80%; object-fit: contain; }
.fk-org__logo span { font-family: var(--font-display); font-size: 1.6rem; font-weight: 700; color: var(--accent, var(--emerald)); }
.fk-org__verified { font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: var(--emerald); background: rgba(31,138,91,.1); padding: .35em .8em; border-radius: 999px; }
.fk-org__name { font-family: var(--font-display); font-size: clamp(1.2rem, 2vw, 1.5rem); font-weight: 600; line-height: 1.2; color: var(--ink); margin: 0; }
.fk-org__desc { margin: 0; color: #565a66; font-size: .92rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.fk-org__tags { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: .4rem; }
.fk-org__tags li { font-size: .76rem; font-weight: 600; color: var(--ink); background: rgba(13,31,92,.07); padding: .35em .8em; border-radius: 999px; }
.fk-org__social { display: flex; gap: 1.4rem; padding-top: .2rem; font-size: .86rem; color: #6a6e7a; }
.fk-org__social strong { color: var(--ink); }
.fk-org__cta { display: flex; align-items: center; justify-content: space-between; gap: .6rem; margin-top: auto; padding-top: .6rem; border-top: 1px solid rgba(13,31,92,.08); }
.fk-org__visit { font-weight: 700; color: var(--magenta); text-decoration: none; transition: color .2s ease; }
.fk-org__visit:hover { color: var(--ink); }
.fk-org__follow { font-weight: 700; font-size: .9rem; color: #fff; background: var(--ink); padding: .5em 1.1em; border-radius: 999px; text-decoration: none; transition: transform .2s ease, background .2s ease; }
.fk-org__follow:hover { transform: translateY(-2px); background: var(--accent, var(--emerald)); }

.fk-org__follow { font-family: inherit; border: none; cursor: pointer; }

/* ============ COMMUNITY FEED ============ */
.fk-feed { padding: clamp(56px, 8vw, 104px) 0; background: linear-gradient(var(--paper), var(--paper-2)); }
.fk-feed__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: clamp(18px, 2.4vw, 28px); }
.fk-feedcard { display: flex; flex-direction: column; background: #fffdf6; border-radius: 20px; border: 1px solid rgba(13,31,92,.1); overflow: hidden; transition: transform .4s cubic-bezier(.2,.7,.2,1), box-shadow .4s ease; }
.fk-feedcard:hover { transform: translateY(-6px); box-shadow: 0 28px 56px -30px rgba(13,31,92,.42); }
.fk-feedcard__media { display: block; aspect-ratio: 16/10; overflow: hidden; }
.fk-feedcard__media img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
.fk-feedcard:hover .fk-feedcard__media img { transform: scale(1.05); }
.fk-feedcard__body { display: flex; flex-direction: column; gap: .65rem; padding: clamp(18px, 2.2vw, 24px); flex: 1; }
.fk-feedcard__head { display: flex; align-items: center; gap: .6rem; }
.fk-feedcard__avatar { width: 38px; height: 38px; border-radius: 50%; overflow: hidden; flex: none; display: grid; place-items: center; background: rgba(13,31,92,.08); font-family: var(--font-display); font-weight: 700; color: var(--ink); }
.fk-feedcard__avatar img { width: 100%; height: 100%; object-fit: cover; }
.fk-feedcard__meta { display: flex; flex-direction: column; line-height: 1.2; min-width: 0; }
.fk-feedcard__author { font-weight: 700; color: var(--ink); font-size: .92rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.fk-feedcard__time { font-size: .76rem; color: #8a8e9a; }
.fk-feedcard__title { font-family: var(--font-display); font-size: 1.16rem; font-weight: 600; line-height: 1.22; color: var(--ink); text-decoration: none; }
.fk-feedcard__title:hover { color: var(--magenta); }
.fk-feedcard__text { margin: 0; color: #565a66; font-size: .92rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.fk-feedcard__foot { display: flex; align-items: center; gap: 1rem; margin-top: auto; padding-top: .7rem; border-top: 1px solid rgba(13,31,92,.08); font-size: .86rem; }
.fk-feedcard__act { display: inline-flex; align-items: center; gap: .35rem; background: none; border: none; padding: 0; cursor: pointer; font: inherit; font-size: .86rem; color: #6a6e7a; text-decoration: none; transition: color .2s ease; }
.fk-feedcard__act:hover { color: var(--magenta); }
.fk-feedcard__heart { color: var(--magenta); font-size: 1rem; }
.fk-feedcard__more { margin-left: auto; font-weight: 700; color: var(--magenta); text-decoration: none; }
.fk-feedcard__more:hover { color: var(--ink); }

/* ============ LOGIN PROMPT MODAL ============ */
.fk-authwrap { position: fixed; inset: 0; z-index: 80; display: grid; place-items: center; padding: 20px; background: rgba(8,22,64,.55); backdrop-filter: blur(6px); }
.fk-auth { position: relative; width: 100%; max-width: 380px; text-align: center; background: var(--paper); border-radius: 24px; padding: clamp(28px, 5vw, 40px) clamp(22px, 4vw, 34px) clamp(20px, 4vw, 28px); box-shadow: 0 40px 80px -30px rgba(0,0,0,.6); border: 1px solid rgba(242,180,12,.3); }
.fk-auth__x { position: absolute; top: 14px; right: 16px; background: none; border: none; font-size: 1.05rem; color: #8a8e9a; cursor: pointer; line-height: 1; }
.fk-auth__x:hover { color: var(--ink); }
.fk-auth__emblem { width: 72px; height: 72px; margin: 0 auto 1rem; border-radius: 50%; display: grid; place-items: center; background: radial-gradient(circle at 50% 38%, rgba(242,180,12,.18), rgba(242,180,12,.04) 70%); box-shadow: inset 0 0 0 1px rgba(242,180,12,.35); }
.fk-auth__emblem img { width: 52px; height: auto; }
.fk-auth__title { font-size: 1.6rem; font-weight: 600; color: var(--ink); margin: 0 0 .5rem; }
.fk-auth__text { margin: 0 0 1.4rem; color: #565a66; font-size: .96rem; line-height: 1.55; }
.fk-auth__text strong { color: var(--ink); }
.fk-auth__btns { display: flex; flex-direction: column; gap: .65rem; }
.fk-auth__btns .fk-btn { width: 100%; justify-content: center; }
.fk-auth__later { margin-top: .9rem; background: none; border: none; cursor: pointer; font: inherit; font-size: .86rem; color: #8a8e9a; }
.fk-auth__later:hover { color: var(--ink); }
.fk-modal-enter-active, .fk-modal-leave-active { transition: opacity .25s ease; }
.fk-modal-enter-active .fk-auth, .fk-modal-leave-active .fk-auth { transition: transform .3s cubic-bezier(.2,.8,.2,1), opacity .25s ease; }
.fk-modal-enter-from, .fk-modal-leave-to { opacity: 0; }
.fk-modal-enter-from .fk-auth, .fk-modal-leave-to .fk-auth { transform: translateY(16px) scale(.96); opacity: 0; }

/* ============ RESPONSIVE ============ */
@media (max-width: 980px) {
    .fk-orgs__grid { grid-template-columns: 1fr 1fr; }
    .fk-hero__inner { grid-template-columns: 1fr; }
    .fk-hero__emblem { order: -1; }
    .fk-focus__inner { grid-template-columns: 1fr; }
    .fk-pillars__grid, .fk-camp__grid, .fk-feed__grid { grid-template-columns: repeat(2, 1fr); }
    .fk-stats__grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 620px) {
    .fk-section-head--row { flex-direction: column; align-items: flex-start; }
    .fk-pillars__grid, .fk-camp__grid, .fk-orgs__grid, .fk-feed__grid { grid-template-columns: 1fr; }
    .fk-org__cta { flex-direction: column; align-items: stretch; }
    .fk-org__follow { text-align: center; }
    .fk-event { grid-template-columns: 1fr; text-align: left; }
    .fk-event__date { flex-direction: row; border-right: none; border-bottom: 1px solid rgba(13,31,92,.12); padding: 0 0 .8rem; max-width: none; }

    /* Chrome is hidden on mobile — let the hero own the top of the screen,
       respecting the device safe-area (notch) for an app-like feel. */
    .fk-hero { padding-top: calc(env(safe-area-inset-top, 0px) + 26px); }
    .fk-hero__emblem { margin-bottom: .4rem; }

    /* Denser, dashboard-style stats: 2×2 instead of four tall blocks. */
    .fk-stats { padding: 40px 0; }
    .fk-stats__grid { grid-template-columns: repeat(2, 1fr); border-radius: 16px; }
    .fk-stat { padding: 20px 16px; gap: .5rem; }
    .fk-stat__value { font-size: clamp(1.5rem, 7vw, 2.4rem); white-space: nowrap; }
    .fk-stat__label { font-size: .8rem; max-width: 18ch; }

    /* App-like full-width tap targets in the hero. */
    .fk-hero__cta { flex-direction: column; align-items: stretch; gap: .7rem; margin-top: 1.7rem; }
    .fk-hero__cta .fk-btn { width: 100%; justify-content: center; padding-top: .95em; padding-bottom: .95em; }
    .fk-hero__cta .fk-btn--link { width: auto; padding: .2rem 0 0; }
    .fk-hero__mini { gap: 1.2rem 1.8rem; }

    /* Tighten the closing CTA buttons the same way. */
    .fk-cta__buttons { flex-direction: column; align-items: stretch; }
    .fk-cta__buttons .fk-btn { width: 100%; justify-content: center; }
    .fk-cta__buttons .fk-btn--link { width: auto; }
}
@media (max-width: 430px) {
    .fk-emblem { width: 156px; }
    .fk-hero__title { font-size: clamp(2.15rem, 9.4vw, 2.9rem); }
    .fk-hero__lede { font-size: 1rem; }
    .fk-marquee__group span { font-size: 1rem; padding: .7rem .85rem; }
}
</style>
