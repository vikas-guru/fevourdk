<template>
    <AppLayout hide-chrome-mobile>
        <Head :title="ngo.name" />

        <div class="ngo-profile" :style="rootStyle">
            <!-- ── Hero ─────────────────────────────────────────────────── -->
            <header class="np-hero">
                <div class="np-hero__mesh"></div>
                <div class="np-hero__grain"></div>
                <img v-if="ngo.logo" class="np-hero__watermark" :src="ngo.logo" alt="" aria-hidden="true" @error="(e)=>e.target.style.display='none'">
                <div class="np-hero__rings" aria-hidden="true"><span></span><span></span></div>

                <div class="np-hero__inner">
                    <Link href="/ngos" class="np-back">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m6 6l-6-6 6-6" /></svg>
                        All NGOs
                    </Link>

                    <div class="np-hero__head">
                        <div class="np-crestwrap">
                            <div class="np-crest">
                                <img v-if="ngo.logo" :src="ngo.logo" :alt="ngo.name">
                                <span v-else>{{ initials }}</span>
                            </div>
                            <span class="np-crest__since">Since {{ joinedYear }}</span>
                        </div>

                        <div class="np-hero__id">
                            <div class="np-badges">
                                <span v-if="ngo.verification_status === 'verified'" class="np-verified">
                                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.7-9.3a1 1 0 00-1.4-1.4L9 10.6 7.7 9.3a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z" clip-rule="evenodd" /></svg>
                                    Verified organisation
                                </span>
                                <span v-if="primaryArea" class="np-kicker">{{ primaryArea }}</span>
                            </div>
                            <h1 class="np-title">{{ ngo.name }}</h1>
                            <p class="np-loc">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><circle cx="12" cy="11" r="2.5" /></svg>
                                {{ locationText }}
                            </p>
                        </div>

                        <div class="np-actions">
                            <button @click="toggleFollow" :class="state.following ? 'is-active' : ''" class="np-btn np-btn--follow">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path :d="state.following ? 'M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4a1 1 0 011.4-1.4l3.3 3.3 7.3-7.3a1 1 0 011.4 0z' : 'M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z'" /></svg>
                                {{ state.following ? 'Following' : 'Follow' }}
                            </button>
                            <button @click="toggleSupport" :class="state.supporting ? 'is-active' : ''" class="np-btn np-btn--support">
                                <svg viewBox="0 0 24 24" :fill="state.supporting ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                {{ state.supporting ? 'Supporting' : 'Support' }}
                            </button>
                            <a v-if="ngo.website" :href="ngo.website" target="_blank" rel="noopener" class="np-btn np-btn--site">
                                Visit site
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M9 7h8v8" /></svg>
                            </a>
                            <button @click="share" class="np-btn np-btn--ghost" :title="shared ? 'Link copied' : 'Share'">
                                <svg v-if="!shared" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path stroke-linecap="round" d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4"/></svg>
                                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span class="np-hide-sm">{{ shared ? 'Copied' : 'Share' }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stat ribbon straddles hero + body -->
                <div class="np-stats">
                    <div v-for="s in stats" :key="s.label" class="np-stat">
                        <span class="np-stat__ic">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" :d="s.icon" /></svg>
                        </span>
                        <div class="np-stat__txt">
                            <span class="np-stat__num">{{ s.value }}</span>
                            <span class="np-stat__lbl">{{ s.label }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ── Body ─────────────────────────────────────────────────── -->
            <div class="np-body">
                <main class="np-main">
                    <section class="np-card">
                        <h2 class="np-h2"><span class="np-h2__bar"></span>About the organisation</h2>
                        <p class="np-prose">{{ ngo.description || 'This organisation has not added a detailed description yet.' }}</p>
                    </section>

                    <section v-if="areas.length" class="np-card">
                        <h2 class="np-h2"><span class="np-h2__bar"></span>Focus areas</h2>
                        <div class="np-areas">
                            <span v-for="a in areas" :key="a" class="np-area">
                                <span class="np-area__dot"></span>{{ a }}
                            </span>
                        </div>
                    </section>

                    <section v-if="ngo.founder_name || ngo.cofounder_name" class="np-card">
                        <h2 class="np-h2"><span class="np-h2__bar"></span>Leadership</h2>
                        <div class="np-people">
                            <div v-if="ngo.founder_name" class="np-person">
                                <div class="np-person__av">{{ personInitials(ngo.founder_name) }}</div>
                                <div>
                                    <p class="np-person__role">Founder</p>
                                    <p class="np-person__name">{{ ngo.founder_name }}</p>
                                </div>
                            </div>
                            <div v-if="ngo.cofounder_name" class="np-person">
                                <div class="np-person__av np-person__av--alt">{{ personInitials(ngo.cofounder_name) }}</div>
                                <div>
                                    <p class="np-person__role">Co-founder</p>
                                    <p class="np-person__name">{{ ngo.cofounder_name }}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>

                <aside class="np-side">
                    <section class="np-card np-contact">
                        <h2 class="np-h2"><span class="np-h2__bar"></span>Contact</h2>
                        <ul class="np-contact__list">
                            <li v-if="ngo.email">
                                <span class="np-ic">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </span>
                                <a :href="`mailto:${ngo.email}`">{{ ngo.email }}</a>
                            </li>
                            <li v-if="ngo.phone">
                                <span class="np-ic">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.3a1 1 0 01.97.757l1 3.6a1 1 0 01-.27.98l-1.5 1.5a14 14 0 006.36 6.36l1.5-1.5a1 1 0 01.98-.27l3.6 1a1 1 0 01.76.97V19a2 2 0 01-2 2A16 16 0 013 5z" /></svg>
                                </span>
                                <a :href="`tel:${ngo.phone}`">{{ ngo.phone }}</a>
                            </li>
                            <li v-if="ngo.address" class="np-contact__addr">
                                <span class="np-ic">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><circle cx="12" cy="11" r="2.5" /></svg>
                                </span>
                                <span>
                                    {{ ngo.address }}
                                    <a :href="directionsUrl" target="_blank" rel="noopener" class="np-directions">Get directions →</a>
                                </span>
                            </li>
                            <li v-if="ngo.website">
                                <span class="np-ic">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18" /></svg>
                                </span>
                                <a :href="ngo.website" target="_blank" rel="noopener">{{ prettyUrl(ngo.website) }}</a>
                            </li>
                        </ul>

                        <div v-if="ngo.facebook_url || ngo.instagram_url" class="np-social">
                            <a v-if="ngo.facebook_url" :href="ngo.facebook_url" target="_blank" rel="noopener" aria-label="Facebook">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0022 12z"/></svg>
                            </a>
                            <a v-if="ngo.instagram_url" :href="ngo.instagram_url" target="_blank" rel="noopener" aria-label="Instagram">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                            </a>
                        </div>
                    </section>

                    <section v-if="ngo.registration_number" class="np-card np-reg">
                        <h2 class="np-h2"><span class="np-h2__bar"></span>Registration</h2>
                        <dl class="np-reg__list">
                            <div><dt>Reg. number</dt><dd>{{ ngo.registration_number }}</dd></div>
                            <div v-if="ngo.state?.name"><dt>State</dt><dd>{{ ngo.state.name }}</dd></div>
                            <div><dt>On FEVOURD-K since</dt><dd>{{ joinedYear }}</dd></div>
                        </dl>
                    </section>

                    <section class="np-card np-give">
                        <h3 class="np-give__title">Stand with {{ shortName }}</h3>
                        <p class="np-give__copy">Your support helps this organisation reach more people across Karnataka.</p>
                        <button @click="toggleSupport" :class="state.supporting ? 'is-active' : ''" class="np-give__btn">
                            <svg viewBox="0 0 24 24" :fill="state.supporting ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            {{ state.supporting ? 'You support this NGO' : 'Support this NGO' }}
                        </button>
                        <Link href="/donate" class="np-give__donate">Make a donation →</Link>
                    </section>
                </aside>
            </div>

            <!-- ── Impact band ──────────────────────────────────────────── -->
            <section v-if="hasCampaigns" class="np-impact js-reveal">
                <div class="np-impact__inner">
                    <p class="np-impact__eyebrow">Real impact, transparently reported</p>
                    <div class="np-impact__grid">
                        <div v-for="it in impactItems" :key="it.key" class="np-impact__cell">
                            <span class="np-impact__ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" :d="it.icon" /></svg>
                            </span>
                            <span class="np-impact__num">{{ impactDisplay(it) }}</span>
                            <span class="np-impact__lbl">{{ it.label }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ── Campaigns ────────────────────────────────────────────── -->
            <section v-if="hasCampaigns" class="np-camps">
                <div class="np-camps__head">
                    <div>
                        <h2 class="np-h2"><span class="np-h2__bar"></span>Active campaigns</h2>
                        <p class="np-camps__sub">Back a cause and watch it reach its goal.</p>
                    </div>
                    <Link href="/donate" class="np-camps__all">Donate now →</Link>
                </div>

                <div class="np-camps__grid">
                    <article v-for="(c, i) in campaigns" :key="c.id" class="np-camp js-reveal" :style="{ '--d': `${i * 90}ms` }">
                        <div class="np-camp__media">
                            <img v-if="c.featured_image && imgOk('c' + c.id)" :src="c.featured_image" :alt="c.title" loading="lazy" @error="onImgError('c' + c.id)">
                            <div v-else class="np-camp__media-fallback"></div>
                            <span v-if="c.focus_areas && c.focus_areas.length" class="np-camp__tag">{{ c.focus_areas[0] }}</span>
                            <span v-if="c.days_left > 0" class="np-camp__days">{{ c.days_left }} days left</span>
                        </div>
                        <div class="np-camp__body">
                            <h3 class="np-camp__title">{{ c.title }}</h3>

                            <div class="np-camp__bar" :style="{ '--pct': `${c.progress}%` }">
                                <span class="np-camp__fill"></span>
                            </div>
                            <div class="np-camp__meta">
                                <span class="np-camp__raised">{{ formatINR(c.raised_amount) }}</span>
                                <span class="np-camp__pct">{{ c.progress }}%</span>
                            </div>
                            <div class="np-camp__sub2">
                                <span>of {{ formatINR(c.target_amount) }} goal</span>
                                <span>{{ c.donor_count }} donors</span>
                            </div>

                            <Link href="/donate" class="np-camp__btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.8 6.6a5 5 0 00-7.1 0L12 8.3l-1.7-1.7a5 5 0 10-7.1 7.1L12 22l8.8-8.8a5 5 0 000-7.1z" /></svg>
                                Donate
                            </Link>
                        </div>
                    </article>
                </div>

                <!-- Recent supporters ticker -->
                <div v-if="recentDonations.length" class="np-ticker js-reveal">
                    <span class="np-ticker__pulse"></span>
                    <span class="np-ticker__lead">Recent support</span>
                    <div class="np-ticker__items">
                        <span v-for="(d, i) in recentDonations" :key="i" class="np-ticker__item">
                            <strong>{{ d.name }}</strong> gave <strong>{{ formatINR(d.amount) }}</strong>
                        </span>
                    </div>
                </div>
            </section>

            <!-- ── Latest updates ───────────────────────────────────────── -->
            <section v-if="updates.length" class="np-updates">
                <h2 class="np-h2"><span class="np-h2__bar"></span>Latest updates</h2>
                <div class="np-updates__grid">
                    <article v-for="(u, i) in updates" :key="i" class="np-update js-reveal" :style="{ '--d': `${i * 90}ms` }">
                        <div class="np-update__media">
                            <img v-if="u.image && imgOk('u' + i)" :src="u.image" :alt="u.title" loading="lazy" @error="onImgError('u' + i)">
                            <div v-else class="np-update__media-fallback"></div>
                        </div>
                        <div class="np-update__body">
                            <p class="np-update__when">{{ u.ago }} · {{ u.views.toLocaleString() }} views</p>
                            <h3 class="np-update__title">{{ u.title }}</h3>
                            <p class="np-update__text">{{ u.body }}</p>
                        </div>
                    </article>
                </div>
            </section>

            <!-- ── Location map ─────────────────────────────────────────── -->
            <section v-if="hasLocation" class="np-mapwrap">
                <div class="np-map">
                    <div class="np-map__head">
                        <div>
                            <h2 class="np-h2"><span class="np-h2__bar"></span>Where they're based</h2>
                            <p class="np-map__loc">{{ locationText }}</p>
                        </div>
                        <a :href="directionsUrl" target="_blank" rel="noopener" class="np-map__btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.4 2.3a1 1 0 01-1.4-.9V6.6a1 1 0 01.6-.9L9 3m0 17l6-3m-6 3V3m6 14l5.4 2.3a1 1 0 001.4-.9V5.6a1 1 0 00-.6-.9L15 2.4m0 14.6V2.4m0 0L9 5" /></svg>
                            Directions
                        </a>
                    </div>
                    <div class="np-map__frame">
                        <iframe :src="mapEmbedUrl" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Location map"></iframe>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, reactive, ref, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
    ngo: { type: Object, required: true },
    follow: { type: Object, default: () => ({ following: false, supporting: false }) },
    authed: { type: Boolean, default: false },
    campaigns: { type: Array, default: () => [] },
    impact: { type: Object, default: () => ({}) },
    recentDonations: { type: Array, default: () => [] },
    updates: { type: Array, default: () => [] },
})

// ── Money formatting (Indian lakh/crore short form) ─────────────────────────
const formatINR = (n) => {
    n = Number(n) || 0
    if (n >= 1e7) return `₹${(n / 1e7).toFixed(n % 1e7 === 0 ? 0 : 2)} Cr`
    if (n >= 1e5) return `₹${(n / 1e5).toFixed(n % 1e5 === 0 ? 0 : 1)} L`
    if (n >= 1e3) return `₹${(n / 1e3).toFixed(0)}K`
    return `₹${Math.round(n)}`
}

const hasCampaigns = computed(() => Array.isArray(props.campaigns) && props.campaigns.length > 0)

// Gracefully degrade broken/missing images to the branded gradient fallback.
const badImg = ref(new Set())
const imgOk = (key) => !badImg.value.has(key)
const onImgError = (key) => { const s = new Set(badImg.value); s.add(key); badImg.value = s }

// Animated impact counters — value eases from 0 → target when scrolled into view.
const impactItems = reactive([
    { key: 'total_raised', label: 'Raised so far', money: true, icon: 'M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6', target: 0, cur: 0 },
    { key: 'total_donors', label: 'Generous donors', money: false, icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', target: 0, cur: 0 },
    { key: 'active_campaigns', label: 'Live campaigns', money: false, icon: 'M4 5a1 1 0 011-1h11l-2 3 2 3H5a1 1 0 01-1-1zM5 21V11', target: 0, cur: 0 },
    { key: 'villages', label: 'Villages reached', money: false, suffix: '+', target: 100, cur: 0, icon: 'M3 21h18M5 21V8l5-4 5 4v13M9 21v-5h2v5' },
])

const impactDisplay = (it) => {
    if (it.money) return formatINR(it.cur)
    return Math.round(it.cur).toLocaleString('en-IN') + (it.suffix || '')
}

let observers = []
const easeCount = (it, duration = 1300) => {
    const start = performance.now()
    const from = 0, to = it.target
    const step = (now) => {
        const p = Math.min(1, (now - start) / duration)
        const eased = 1 - Math.pow(1 - p, 3)
        it.cur = from + (to - from) * eased
        if (p < 1) requestAnimationFrame(step)
        else it.cur = to
    }
    requestAnimationFrame(step)
}

onMounted(() => {
    impactItems[0].target = Number(props.impact?.total_raised || 0)
    impactItems[1].target = Number(props.impact?.total_donors || 0)
    impactItems[2].target = Number(props.impact?.active_campaigns || 0)

    if (typeof IntersectionObserver === 'undefined') return

    // Generic reveal: add .is-in once visible (drives entrance + progress fills).
    const revealOb = new IntersectionObserver((entries, ob) => {
        entries.forEach((e) => {
            if (e.isIntersecting) { e.target.classList.add('is-in'); ob.unobserve(e.target) }
        })
    }, { threshold: 0.18 })
    document.querySelectorAll('.js-reveal').forEach((el) => revealOb.observe(el))
    observers.push(revealOb)

    // Impact band — kick off the count-up once.
    const band = document.querySelector('.np-impact')
    if (band) {
        const countOb = new IntersectionObserver((entries, ob) => {
            entries.forEach((e) => {
                if (e.isIntersecting) { impactItems.forEach((it) => easeCount(it)); ob.disconnect() }
            })
        }, { threshold: 0.3 })
        countOb.observe(band)
        observers.push(countOb)
    }
})

onBeforeUnmount(() => { observers.forEach((o) => o.disconnect()); observers = [] })

const state = reactive({ following: !!props.follow.following, supporting: !!props.follow.supporting })

const rootStyle = computed(() => ({ '--hue': 150 + ((Number(props.ngo.id) * 37) % 70) }))

const initials = computed(() =>
    (props.ngo.name || '').trim().split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase() || '★')

const shortName = computed(() => (props.ngo.name || '').split(/\s+/).slice(0, 2).join(' '))

const areas = computed(() => Array.isArray(props.ngo.focus_areas) ? props.ngo.focus_areas.filter(Boolean) : [])
const primaryArea = computed(() => areas.value[0] || '')

const locationText = computed(() => {
    // Dedupe consecutive identical names — Indian district HQs often share the
    // district name (e.g. city "Mandya" in district "Mandya").
    const parts = [props.ngo.city?.name, props.ngo.district?.name, props.ngo.state?.name]
        .filter(Boolean)
        .filter((v, i, a) => v !== a[i - 1])
    return parts.length ? parts.join(', ') : 'Karnataka, India'
})

const joinedYear = computed(() => {
    const y = props.ngo.created_at ? new Date(props.ngo.created_at).getFullYear() : null
    return y && !Number.isNaN(y) ? y : '—'
})

const stats = computed(() => [
    { label: 'Followers', value: props.ngo.followers_count ?? 0, icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
    { label: 'Supporters', value: props.ngo.supporters_count ?? 0, icon: 'M20.8 6.6a5 5 0 00-7.1 0L12 8.3l-1.7-1.7a5 5 0 10-7.1 7.1L12 22l8.8-8.8a5 5 0 000-7.1z' },
    { label: 'Focus areas', value: areas.value.length, icon: 'M12 22a10 10 0 100-20 10 10 0 000 20zm0-5a5 5 0 100-10 5 5 0 000 10zm0-3a2 2 0 100-4 2 2 0 000 4z' },
    { label: 'Since', value: joinedYear.value, icon: 'M8 2v4M16 2v4M3 10h18M5 6h14a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z' },
])

const personInitials = (name = '') =>
    name.trim().split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase() || '•'

const prettyUrl = (url = '') => url.replace(/^https?:\/\//, '').replace(/\/$/, '')

const mapQuery = computed(() => {
    if (props.ngo.latitude && props.ngo.longitude) return `${props.ngo.latitude},${props.ngo.longitude}`
    // Prefer the full street address — it usually carries a pincode, which pins
    // accurately. The city relation can be stale/mismatched (e.g. a Mandya org
    // tagged to Bangalore), so only fall back to it when there's no address.
    if (props.ngo.address) return `${props.ngo.address}, ${props.ngo.state?.name || 'India'}`
    return [props.ngo.city?.name, props.ngo.district?.name, props.ngo.state?.name, 'India'].filter(Boolean).join(', ')
})
const hasLocation = computed(() => !!(props.ngo.address || props.ngo.city?.name || props.ngo.state?.name))
// Google Maps embed by query — no API key, works without stored coordinates.
const mapEmbedUrl = computed(() => `https://www.google.com/maps?q=${encodeURIComponent(mapQuery.value)}&z=12&output=embed`)
const directionsUrl = computed(() => `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(mapQuery.value || props.ngo.name)}`)

const shared = ref(false)
const share = async () => {
    const url = typeof window !== 'undefined' ? window.location.href : ''
    const data = { title: props.ngo.name, text: `${props.ngo.name} on FEVOURD-K`, url }
    try {
        if (navigator.share) { await navigator.share(data); return }
        await navigator.clipboard.writeText(url)
        shared.value = true
        setTimeout(() => { shared.value = false }, 2000)
    } catch (e) { /* user cancelled share — ignore */ }
}

const toggleFollow = () => {
    if (!props.authed) { router.visit('/login'); return }
    state.following = !state.following
    router.post(`/ngos/${props.ngo.id}/follow`, {}, {
        preserveScroll: true, preserveState: true,
        onError: () => { state.following = !state.following },
    })
}

const toggleSupport = () => {
    if (!props.authed) { router.visit('/login'); return }
    state.supporting = !state.supporting
    router.post(`/ngos/${props.ngo.id}/support`, {}, {
        preserveScroll: true, preserveState: true,
        onError: () => { state.supporting = !state.supporting },
    })
}
</script>

<style scoped>
.ngo-profile {
    --hue: 168;
    --accent: 38 92% 50%;
    --ink: hsl(var(--hue) 45% 18%);
    background: linear-gradient(180deg, #f6f8f9 0%, #eef2f4 100%);
    min-height: 100vh;
    padding-bottom: 64px;
    font-feature-settings: "ss01";
}

/* Hero */
.np-hero {
    position: relative;
    overflow: hidden;
    background: hsl(var(--hue) 45% 14%);
    padding-bottom: 56px;
}
.np-hero__mesh {
    position: absolute; inset: -15%;
    background:
        radial-gradient(50% 60% at 12% 18%, hsl(var(--hue) 70% 40% / .95), transparent 60%),
        radial-gradient(55% 70% at 88% 8%, hsl(calc(var(--hue) + 28) 72% 44% / .9), transparent 62%),
        radial-gradient(60% 90% at 80% 100%, hsl(var(--accent) / .4), transparent 60%),
        linear-gradient(135deg, hsl(var(--hue) 52% 20%), hsl(calc(var(--hue) - 20) 48% 12%));
}
.np-hero__grain {
    position: absolute; inset: 0; opacity: .3; mix-blend-mode: overlay;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
}
.np-hero__watermark {
    position: absolute; right: -4%; top: 50%; transform: translateY(-50%);
    width: min(42vw, 420px); height: auto; aspect-ratio: 1;
    object-fit: contain; opacity: .07; filter: grayscale(1) brightness(2.4);
    pointer-events: none; z-index: 0;
}
.np-hero__rings { position: absolute; right: -8%; top: 30%; width: min(40vw, 380px); aspect-ratio: 1; opacity: .5; pointer-events: none; z-index: 0; }
.np-hero__rings span { position: absolute; inset: 0; border-radius: 50%; border: 1.5px dashed hsl(40 96% 62% / .4); }
.np-hero__rings span:last-child { inset: 16%; border-style: solid; border-color: rgba(255,255,255,.14); }
.np-hero__inner {
    position: relative;
    z-index: 1;
    max-width: 1120px;
    margin: 0 auto;
    padding: 26px 24px 0;
}
.np-back {
    display: inline-flex; align-items: center; gap: 7px;
    color: rgba(255,255,255,.82); font-size: 13.5px; font-weight: 600;
    padding: 7px 13px 7px 10px; border-radius: 999px;
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16);
    backdrop-filter: blur(6px); transition: background .2s ease;
}
.np-back:hover { background: rgba(255,255,255,.16); }
.np-back svg { width: 16px; height: 16px; }

.np-hero__head {
    display: flex; align-items: flex-end; gap: 22px;
    margin-top: 30px; flex-wrap: wrap;
}
.np-crestwrap { position: relative; flex: none; }
.np-crestwrap::before {
    content: ''; position: absolute; inset: -14px; border-radius: 34px; z-index: -1;
    background: radial-gradient(circle, hsl(40 96% 60% / .55), transparent 68%);
    filter: blur(6px);
}
.np-crest__since {
    position: absolute; left: 50%; bottom: -11px; transform: translateX(-50%);
    white-space: nowrap; padding: 3px 11px; border-radius: 999px;
    font-size: 10.5px; font-weight: 800; letter-spacing: .03em;
    color: var(--ink); background: hsl(40 96% 66%);
    border: 1.5px solid #fff; box-shadow: 0 6px 14px -6px hsl(40 90% 45% / .85);
}
.np-crest {
    width: 104px; height: 104px; flex: none;
    border-radius: 26px; background: #fff;
    display: grid; place-items: center; overflow: hidden;
    border: 3px solid hsl(40 96% 62%);
    box-shadow: 0 20px 44px -14px rgba(0,0,0,.6), 0 0 0 6px rgba(255,255,255,.14);
}
.np-crest img { width: 100%; height: 100%; object-fit: cover; }
.np-crest span {
    font-family: 'Fraunces', Georgia, serif; font-weight: 600;
    font-size: 38px; color: hsl(var(--hue) 45% 28%);
}
.np-hero__id { flex: 1; min-width: 260px; }
.np-badges { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
.np-verified {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; color: hsl(40 95% 92%);
    padding: 5px 11px 5px 8px; border-radius: 999px;
    background: hsl(var(--accent) / .2); border: 1px solid hsl(var(--accent) / .5);
}
.np-verified svg { width: 15px; height: 15px; color: hsl(40 96% 70%); }
.np-kicker {
    display: inline-flex; align-items: center;
    font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    color: #fff; padding: 5px 11px; border-radius: 999px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.24);
}
.np-title {
    font-family: 'Fraunces', Georgia, serif; font-weight: 600;
    font-size: clamp(28px, 4vw, 42px); line-height: 1.08;
    color: #fff; letter-spacing: -.015em; margin: 0 0 8px;
}
.np-loc {
    display: flex; align-items: center; gap: 6px;
    color: rgba(255,255,255,.78); font-size: 14.5px; font-weight: 500;
}
.np-loc svg { width: 16px; height: 16px; color: hsl(40 90% 72%); }

.np-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.np-btn {
    display: inline-flex; align-items: center; gap: 8px;
    height: 46px; padding: 0 18px; border-radius: 13px;
    font-size: 14.5px; font-weight: 600; cursor: pointer;
    border: 1px solid transparent; transition: transform .18s ease, background .2s ease, box-shadow .2s ease;
}
.np-btn:active { transform: scale(.97); }
.np-btn svg { width: 17px; height: 17px; }
.np-btn--follow {
    color: #fff; background: rgba(255,255,255,.12);
    border-color: rgba(255,255,255,.28); backdrop-filter: blur(6px);
}
.np-btn--follow:hover { background: rgba(255,255,255,.2); }
.np-btn--follow.is-active { background: #fff; color: var(--ink); border-color: #fff; }
.np-btn--support {
    color: var(--ink); background: hsl(40 96% 66%);
    box-shadow: 0 12px 26px -12px hsl(40 90% 50% / .9);
}
.np-btn--support:hover { background: hsl(40 96% 70%); }
.np-btn--support.is-active {
    color: #fff; background: linear-gradient(135deg, hsl(347 78% 54%), hsl(12 82% 56%));
}
.np-btn--site {
    color: #fff; background: transparent; border-color: rgba(255,255,255,.3);
}
.np-btn--site:hover { background: rgba(255,255,255,.1); }
.np-btn--ghost {
    color: #fff; background: rgba(255,255,255,.08);
    border-color: rgba(255,255,255,.2); backdrop-filter: blur(6px);
}
.np-btn--ghost:hover { background: rgba(255,255,255,.16); }

.np-directions {
    display: inline-block; margin-top: 4px;
    font-size: 12.5px; font-weight: 700;
    color: hsl(var(--hue) 55% 38%) !important;
}
.np-directions:hover { text-decoration: underline; }

/* Stat ribbon */
.np-stats {
    position: relative;
    z-index: 1;
    max-width: 1120px; margin: 34px auto -28px; padding: 0 24px;
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;
}
.np-stat {
    display: flex; align-items: center; gap: 13px;
    background: #fff; border: 1px solid rgba(15,23,42,.06);
    border-radius: 18px; padding: 15px 18px; min-width: 0;
    box-shadow: 0 18px 36px -22px rgba(15,23,42,.4);
    transition: transform .25s ease, box-shadow .25s ease;
}
.np-stat:hover { transform: translateY(-3px); box-shadow: 0 22px 42px -22px rgba(15,23,42,.5); }
.np-stat__txt { min-width: 0; }
.np-stat__ic {
    width: 42px; height: 42px; flex: none; border-radius: 13px;
    display: grid; place-items: center; color: hsl(var(--hue) 52% 38%);
    background: hsl(var(--hue) 55% 95%); border: 1px solid hsl(var(--hue) 45% 90%);
}
.np-stat__ic svg { width: 21px; height: 21px; }
.np-stat__num {
    display: block; font-family: 'Fraunces', Georgia, serif; font-weight: 600;
    font-size: 28px; line-height: 1; color: var(--ink); white-space: nowrap;
}
.np-stat__lbl {
    display: block; margin-top: 6px; font-size: 11px; font-weight: 600;
    letter-spacing: .04em; text-transform: uppercase; color: #94a3b8; white-space: nowrap;
}

/* Body */
.np-body {
    max-width: 1120px; margin: 0 auto; padding: 56px 24px 0;
    display: grid; grid-template-columns: 1.7fr 1fr; gap: 26px; align-items: start;
}
.np-card {
    background: #fff; border: 1px solid rgba(15,23,42,.06);
    border-radius: 22px; padding: 26px 28px; margin-bottom: 22px;
    box-shadow: 0 1px 2px rgba(15,23,42,.03), 0 18px 40px -28px rgba(15,23,42,.28);
}
.np-h2 {
    display: flex; align-items: center; gap: 11px;
    font-size: 13px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    color: #64748b; margin: 0 0 16px;
}
.np-h2__bar {
    width: 22px; height: 3px; border-radius: 3px;
    background: linear-gradient(90deg, hsl(var(--hue) 60% 42%), hsl(40 95% 60%));
}
.np-prose {
    font-size: 16px; line-height: 1.72; color: #374151; white-space: pre-line;
}

.np-areas { display: flex; flex-wrap: wrap; gap: 9px; }
.np-area {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 14px 8px 12px; border-radius: 11px;
    font-size: 13.5px; font-weight: 600; color: hsl(var(--hue) 42% 28%);
    background: hsl(var(--hue) 55% 96%); border: 1px solid hsl(var(--hue) 45% 88%);
}
.np-area__dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: linear-gradient(135deg, hsl(var(--hue) 60% 42%), hsl(40 95% 58%));
}

.np-people { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.np-person {
    display: flex; align-items: center; gap: 13px;
    padding: 14px; border-radius: 16px;
    background: #fafbfc; border: 1px solid rgba(15,23,42,.05);
}
.np-person__av {
    width: 46px; height: 46px; flex: none; border-radius: 13px;
    display: grid; place-items: center; color: #fff; font-weight: 700; font-size: 16px;
    background: linear-gradient(135deg, hsl(var(--hue) 58% 36%), hsl(calc(var(--hue) + 22) 56% 42%));
}
.np-person__av--alt { background: linear-gradient(135deg, hsl(40 90% 52%), hsl(20 86% 54%)); }
.np-person__role { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: #94a3b8; margin: 0; }
.np-person__name { font-size: 15.5px; font-weight: 600; color: #1f2937; margin: 2px 0 0; }

/* Sidebar */
.np-side { position: sticky; top: 88px; }
.np-contact__list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 13px; }
.np-contact__list li { display: flex; align-items: center; gap: 12px; font-size: 14px; color: #475569; }
.np-contact__addr { align-items: flex-start; }
.np-contact__addr span:last-child { line-height: 1.5; padding-top: 3px; }
.np-contact__list a { color: hsl(var(--hue) 50% 32%); font-weight: 600; word-break: break-word; }
.np-contact__list a:hover { text-decoration: underline; }
.np-ic {
    width: 36px; height: 36px; flex: none; border-radius: 11px;
    display: grid; place-items: center; color: hsl(var(--hue) 50% 38%);
    background: hsl(var(--hue) 55% 96%); border: 1px solid hsl(var(--hue) 45% 90%);
}
.np-ic svg { width: 18px; height: 18px; }
.np-social { display: flex; gap: 10px; margin-top: 18px; padding-top: 18px; border-top: 1px solid #eef2f4; }
.np-social a {
    width: 38px; height: 38px; border-radius: 11px; display: grid; place-items: center;
    color: #64748b; background: #f5f7f8; border: 1px solid #e7ebee; transition: all .2s ease;
}
.np-social a:hover { color: hsl(var(--hue) 55% 36%); background: hsl(var(--hue) 55% 96%); }
.np-social svg { width: 18px; height: 18px; }

.np-reg__list { display: flex; flex-direction: column; gap: 0; }
.np-reg__list > div {
    display: flex; justify-content: space-between; align-items: center; gap: 12px;
    padding: 11px 0; border-bottom: 1px dashed #e7ebee;
}
.np-reg__list > div:last-child { border-bottom: 0; }
.np-reg__list dt { font-size: 13px; color: #94a3b8; }
.np-reg__list dd { font-size: 14px; font-weight: 700; color: #1f2937; margin: 0; text-align: right; }

.np-give {
    background: linear-gradient(150deg, hsl(var(--hue) 48% 16%), hsl(calc(var(--hue) - 18) 44% 11%));
    border: none; color: #fff; position: relative; overflow: hidden;
}
.np-give::after {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(70% 90% at 90% 0%, hsl(var(--accent) / .35), transparent 60%);
    pointer-events: none;
}
.np-give__title { font-family: 'Fraunces', Georgia, serif; font-weight: 600; font-size: 22px; margin: 0 0 8px; position: relative; }
.np-give__copy { font-size: 14px; line-height: 1.6; color: rgba(255,255,255,.75); margin: 0 0 18px; position: relative; }
.np-give__btn {
    position: relative; width: 100%; height: 48px; border-radius: 13px; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    font-size: 15px; font-weight: 700; color: var(--ink); border: none;
    background: hsl(40 96% 66%); transition: transform .18s ease, background .2s ease;
}
.np-give__btn:hover { background: hsl(40 96% 70%); }
.np-give__btn:active { transform: scale(.98); }
.np-give__btn.is-active { color: #fff; background: linear-gradient(135deg, hsl(347 78% 54%), hsl(12 82% 56%)); }
.np-give__btn svg { width: 18px; height: 18px; }
.np-give__donate {
    position: relative; display: block; text-align: center; margin-top: 13px;
    font-size: 13.5px; font-weight: 600; color: rgba(255,255,255,.8);
}
.np-give__donate:hover { color: #fff; }

/* Map band */
.np-mapwrap { max-width: 1120px; margin: 6px auto 0; padding: 0 24px; }
.np-map {
    background: #fff; border: 1px solid rgba(15,23,42,.06); border-radius: 22px;
    overflow: hidden; box-shadow: 0 1px 2px rgba(15,23,42,.03), 0 18px 40px -28px rgba(15,23,42,.28);
}
.np-map__head {
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    padding: 22px 26px 16px;
}
.np-map__head .np-h2 { margin-bottom: 6px; }
.np-map__loc { font-size: 15px; font-weight: 600; color: #334155; margin: 0; }
.np-map__btn {
    display: inline-flex; align-items: center; gap: 7px; flex: none;
    height: 42px; padding: 0 16px; border-radius: 12px; font-size: 14px; font-weight: 600;
    color: #fff; background: linear-gradient(135deg, hsl(var(--hue) 58% 36%), hsl(calc(var(--hue) + 22) 56% 42%));
    box-shadow: 0 10px 20px -10px hsl(var(--hue) 60% 30% / .8);
}
.np-map__btn svg { width: 17px; height: 17px; }
.np-map__frame { position: relative; height: 340px; border-top: 1px solid rgba(15,23,42,.06); }
.np-map__frame iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; filter: saturate(1.05); }

/* ── Reveal-on-scroll (entrance) ──────────────────────────────────────────── */
.js-reveal { opacity: 0; transform: translateY(22px); transition: opacity .7s cubic-bezier(.2,.8,.2,1), transform .7s cubic-bezier(.2,.8,.2,1); transition-delay: var(--d, 0ms); }
.js-reveal.is-in { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) { .js-reveal { opacity: 1; transform: none; } }

/* ── Impact band ──────────────────────────────────────────────────────────── */
.np-impact { max-width: 1120px; margin: 40px auto 0; padding: 0 24px; }
.np-impact__inner {
    position: relative; overflow: hidden;
    border-radius: 26px; padding: 30px 28px;
    background: linear-gradient(135deg, hsl(var(--hue) 50% 16%), hsl(calc(var(--hue) - 20) 46% 11%));
    box-shadow: 0 30px 60px -30px hsl(var(--hue) 60% 20% / .7);
}
.np-impact__inner::after {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background:
        radial-gradient(50% 70% at 85% 0%, hsl(var(--accent) / .3), transparent 60%),
        radial-gradient(60% 90% at 8% 100%, hsl(var(--hue) 70% 45% / .35), transparent 60%);
}
.np-impact__eyebrow {
    position: relative; text-align: center; margin: 0 0 22px;
    font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
    color: hsl(40 90% 72%);
}
.np-impact__grid { position: relative; display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.np-impact__cell { text-align: center; display: flex; flex-direction: column; align-items: center; gap: 6px; }
.np-impact__ic {
    width: 44px; height: 44px; border-radius: 14px; display: grid; place-items: center; margin-bottom: 4px;
    color: hsl(40 96% 72%); background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16);
}
.np-impact__ic svg { width: 22px; height: 22px; }
.np-impact__num {
    font-family: 'Fraunces', Georgia, serif; font-weight: 600; font-size: clamp(26px, 3.4vw, 38px);
    line-height: 1; color: #fff; letter-spacing: -.01em;
}
.np-impact__lbl { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.7); }

/* ── Campaigns ────────────────────────────────────────────────────────────── */
.np-camps { max-width: 1120px; margin: 44px auto 0; padding: 0 24px; }
.np-camps__head { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 20px; }
.np-camps__head .np-h2 { margin-bottom: 4px; }
.np-camps__sub { font-size: 14.5px; color: #64748b; margin: 0; }
.np-camps__all { flex: none; font-size: 14px; font-weight: 700; color: hsl(var(--hue) 55% 36%); }
.np-camps__all:hover { text-decoration: underline; }
.np-camps__grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }

.np-camp {
    display: flex; flex-direction: column; background: #fff; border-radius: 20px; overflow: hidden;
    border: 1px solid rgba(15,23,42,.06); box-shadow: 0 1px 2px rgba(15,23,42,.03), 0 18px 40px -28px rgba(15,23,42,.3);
    transition: transform .4s cubic-bezier(.2,.8,.2,1), box-shadow .4s cubic-bezier(.2,.8,.2,1);
}
.np-camp.is-in:hover { transform: translateY(-6px); box-shadow: 0 1px 2px rgba(15,23,42,.03), 0 30px 56px -26px hsl(var(--hue) 50% 30% / .42); }
.np-camp__media { position: relative; height: 168px; overflow: hidden; background: hsl(var(--hue) 30% 90%); }
.np-camp__media img { width: 100%; height: 100%; object-fit: cover; transition: transform .7s cubic-bezier(.2,.8,.2,1); }
.np-camp:hover .np-camp__media img { transform: scale(1.06); }
.np-camp__media-fallback { width: 100%; height: 100%; background: linear-gradient(135deg, hsl(var(--hue) 55% 40%), hsl(calc(var(--hue) + 25) 55% 46%)); }
.np-camp__media::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,.35), transparent 45%); }
.np-camp__tag {
    position: absolute; top: 12px; left: 12px; z-index: 2;
    padding: 5px 11px; border-radius: 999px; font-size: 11px; font-weight: 700;
    color: var(--ink); background: hsl(40 96% 66%); box-shadow: 0 6px 14px -6px rgba(0,0,0,.4);
}
.np-camp__days {
    position: absolute; bottom: 12px; right: 12px; z-index: 2;
    padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600;
    color: #fff; background: rgba(15,23,42,.55); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,.18);
}
.np-camp__body { padding: 16px 18px 18px; display: flex; flex-direction: column; flex: 1; }
.np-camp__title {
    font-family: 'Fraunces', Georgia, serif; font-weight: 600; font-size: 17.5px; line-height: 1.25;
    color: #111827; margin: 0 0 14px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 44px;
}
.np-camp__bar { height: 9px; border-radius: 999px; background: hsl(var(--hue) 30% 92%); overflow: hidden; }
.np-camp__fill {
    display: block; height: 100%; width: 0; border-radius: 999px;
    background: linear-gradient(90deg, hsl(var(--hue) 60% 42%), hsl(40 95% 58%));
    transition: width 1.3s cubic-bezier(.2,.8,.2,1); transition-delay: .15s;
}
.np-camp.is-in .np-camp__fill { width: var(--pct); }
.np-camp__meta { display: flex; align-items: baseline; justify-content: space-between; margin-top: 10px; }
.np-camp__raised { font-family: 'Fraunces', Georgia, serif; font-weight: 600; font-size: 19px; color: hsl(var(--hue) 45% 24%); }
.np-camp__pct { font-size: 13px; font-weight: 700; color: hsl(var(--hue) 50% 42%); }
.np-camp__sub2 { display: flex; justify-content: space-between; margin-top: 3px; font-size: 12.5px; color: #94a3b8; }
.np-camp__btn {
    margin-top: 15px; height: 44px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    font-size: 14.5px; font-weight: 700; color: #fff;
    background: linear-gradient(135deg, hsl(var(--hue) 58% 36%), hsl(calc(var(--hue) + 22) 56% 42%));
    box-shadow: 0 12px 24px -12px hsl(var(--hue) 60% 30% / .8); transition: transform .18s ease, box-shadow .2s ease;
}
.np-camp__btn:hover { box-shadow: 0 16px 30px -12px hsl(var(--hue) 60% 30% / .9); }
.np-camp__btn:active { transform: scale(.98); }
.np-camp__btn svg { width: 17px; height: 17px; }

/* Recent supporters ticker */
.np-ticker {
    display: flex; align-items: center; gap: 12px; margin-top: 18px; padding: 13px 18px;
    background: #fff; border: 1px solid rgba(15,23,42,.06); border-radius: 14px; overflow: hidden;
    box-shadow: 0 14px 34px -26px rgba(15,23,42,.3);
}
.np-ticker__pulse { width: 9px; height: 9px; border-radius: 50%; background: #22c55e; flex: none; box-shadow: 0 0 0 0 rgba(34,197,94,.5); animation: np-pulse 1.8s infinite; }
@keyframes np-pulse { 0% { box-shadow: 0 0 0 0 rgba(34,197,94,.5); } 70% { box-shadow: 0 0 0 8px rgba(34,197,94,0); } 100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); } }
.np-ticker__lead { flex: none; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #64748b; }
.np-ticker__items { display: flex; gap: 26px; overflow: hidden; white-space: nowrap; mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent); }
.np-ticker__item { font-size: 13.5px; color: #475569; flex: none; }
.np-ticker__item strong { color: hsl(var(--hue) 45% 28%); font-weight: 700; }

/* ── Updates ──────────────────────────────────────────────────────────────── */
.np-updates { max-width: 1120px; margin: 44px auto 0; padding: 0 24px; }
.np-updates__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-top: 6px; }
.np-update {
    background: #fff; border: 1px solid rgba(15,23,42,.06); border-radius: 18px; overflow: hidden;
    box-shadow: 0 1px 2px rgba(15,23,42,.03), 0 16px 36px -28px rgba(15,23,42,.28);
    transition: transform .4s cubic-bezier(.2,.8,.2,1), box-shadow .4s cubic-bezier(.2,.8,.2,1);
}
.np-update.is-in:hover { transform: translateY(-5px); box-shadow: 0 1px 2px rgba(15,23,42,.03), 0 26px 50px -26px rgba(15,23,42,.4); }
.np-update__media { height: 150px; overflow: hidden; background: hsl(var(--hue) 30% 90%); }
.np-update__media img { width: 100%; height: 100%; object-fit: cover; transition: transform .7s cubic-bezier(.2,.8,.2,1); }
.np-update:hover .np-update__media img { transform: scale(1.06); }
.np-update__media-fallback { width: 100%; height: 100%; background: linear-gradient(135deg, hsl(var(--hue) 50% 42%), hsl(calc(var(--hue) + 25) 52% 48%)); }
.np-update__body { padding: 15px 17px 18px; }
.np-update__when { font-size: 11.5px; font-weight: 600; color: #94a3b8; margin: 0 0 6px; }
.np-update__title { font-weight: 700; font-size: 15.5px; line-height: 1.3; color: #1f2937; margin: 0 0 7px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.np-update__text { font-size: 13.5px; line-height: 1.55; color: #64748b; margin: 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

@media (max-width: 880px) {
    .np-camps__grid { grid-template-columns: 1fr 1fr; }
    .np-updates__grid { grid-template-columns: 1fr; }
    .np-impact__grid { grid-template-columns: repeat(2, 1fr); gap: 22px 12px; }
}
@media (max-width: 560px) {
    .np-impact { padding: 0 16px; margin-top: 30px; }
    .np-impact__inner { padding: 24px 18px; border-radius: 20px; }
    .np-camps, .np-updates { padding: 0 16px; margin-top: 34px; }
    .np-camps__grid { grid-template-columns: 1fr; gap: 16px; }
    .np-camps__head { gap: 10px; }
    .np-ticker { flex-wrap: wrap; }
    .np-ticker__items { gap: 18px; }
}

.np-hide-sm { display: inline; }

@media (max-width: 880px) {
    .np-body { grid-template-columns: 1fr; padding-top: 50px; }
    .np-side { position: static; }
    .np-stats { grid-template-columns: repeat(2, 1fr); margin-bottom: -22px; }
    .np-people { grid-template-columns: 1fr; }
}

@media (max-width: 560px) {
    .np-hero { padding-bottom: 48px; }
    .np-hero__inner { padding: 20px 16px 0; }
    .np-hero__head { gap: 16px; margin-top: 22px; }
    .np-crest { width: 78px; height: 78px; border-radius: 20px; }
    .np-crest span { font-size: 30px; }
    .np-title { font-size: 25px; }
    /* Let the name/badges sit beside the logo instead of wrapping below it */
    .np-hero__head { align-items: flex-start; }
    .np-hero__id { min-width: 0; flex: 1; }
    .np-crest { width: 72px; height: 72px; border-radius: 18px; }
    .np-crest span { font-size: 28px; }
    .np-title { font-size: 22px; }
    /* 2×2 grid: Follow · Support / Visit site · Share — all equal, no squishing */
    .np-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 9px; width: 100%; }
    .np-btn { width: 100%; min-width: 0; justify-content: center; height: 46px; padding: 0 12px; }
    .np-stats { gap: 10px; padding: 0 16px; }
    .np-stat { padding: 13px 14px; border-radius: 15px; }
    .np-stat__num { font-size: 23px; }
    .np-body { padding: 50px 16px 0; gap: 18px; }
    .np-card { padding: 20px 18px; border-radius: 18px; margin-bottom: 16px; }
    .np-prose { font-size: 15px; }
    .np-give__title { font-size: 20px; }
    .np-mapwrap { padding: 0 16px; }
    .np-map__head { flex-direction: column; align-items: flex-start; gap: 12px; padding: 18px 18px 14px; }
    .np-map__btn { width: 100%; justify-content: center; }
    .np-map__frame { height: 260px; }
}
</style>
