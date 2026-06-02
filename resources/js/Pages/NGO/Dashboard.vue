<template>
    <AppLayout title="NGO workspace — FEVOURD-K" :hide-chrome="true">
        <NgoWorkspaceShell :ngo="ngo" current-key="dashboard">
            <!-- Welcome (first visit after registration) -->
            <div
                v-if="showWelcomeBanner"
                class="reveal relative mb-6 overflow-hidden rounded-2xl border border-emerald-200/80 bg-gradient-to-br from-emerald-50 via-white to-blue-50 p-6 shadow-sm"
            >
                <button
                    type="button"
                    class="absolute right-3 top-3 rounded-lg p-1 text-slate-400 hover:bg-white/80 hover:text-slate-600"
                    aria-label="Dismiss"
                    @click="dismissWelcome"
                >
                    ✕
                </button>
                <h2 class="text-lg font-bold text-slate-900 sm:text-xl">Welcome to FEVOURD-K</h2>
                <p class="mt-2 max-w-3xl text-sm leading-relaxed text-slate-600">
                    Your NGO workspace is ready. Below is what you get on the platform — most of it is <strong>free</strong> as part of the Karnataka voluntary ecosystem.
                    When your account is <strong>activated and verified</strong> by our team, you can run campaigns, receive donations, and use every tool without limits.
                </p>
            </div>

            <!-- Activation status -->
            <div
                v-if="needsActivationNotice"
                class="reveal mb-6 rounded-2xl border border-amber-200 bg-amber-50/90 p-5 shadow-sm"
            >
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-800">!</div>
                    <div>
                        <h3 class="text-base font-bold text-amber-950">Activation in progress</h3>
                        <p class="mt-1 text-sm leading-relaxed text-amber-900/90">
                            Your registration is complete. We are reviewing your profile and documents. Once your account is
                            <strong>activated</strong>, you can publish campaigns, collect donations, and access payouts — explore the menu
                            to familiarise yourself meanwhile.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Branded overview hero with glowing logo medallion -->
            <header class="fkbrand-hero reveal relative mb-6 overflow-hidden rounded-3xl p-6 shadow-xl sm:p-8" data-tour="hero">
                <div class="fkbrand-hero__grain" aria-hidden="true"></div>
                <div class="fkbrand-hero__sheen" aria-hidden="true"></div>

                <div class="relative z-10 flex flex-col gap-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-4">
                            <!-- Glowing medallion -->
                            <div class="fkmedallion" aria-hidden="true">
                                <div class="fkmedallion__ring"></div>
                                <div class="fkmedallion__inner">
                                    <img
                                        v-if="logoUrl"
                                        :src="logoUrl"
                                        :alt="ngo.name"
                                        class="h-full w-full rounded-full object-cover"
                                        @error="logoFailed = true"
                                    >
                                    <span v-else class="fkbrand-display text-2xl font-semibold text-white">{{ initials }}</span>
                                </div>
                            </div>

                            <div class="min-w-0">
                                <p class="inline-flex items-center gap-2 text-[0.7rem] font-semibold uppercase tracking-[0.14em] text-[#f7c948]">
                                    <span class="h-2 w-2 rounded-full bg-[#f2b40c] shadow-[0_0_0_4px_rgba(242,180,12,0.2)]"></span>
                                    NGO Workspace
                                </p>
                                <h1 class="fkbrand-display mt-1.5 text-3xl font-semibold leading-tight tracking-tight text-white sm:text-[2.6rem]">
                                    {{ ngo.name || 'Overview' }}
                                </h1>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span
                                        v-if="isVerified && isActive"
                                        class="inline-flex items-center gap-1 rounded-full bg-[#f2b40c]/15 px-2.5 py-1 text-[0.68rem] font-bold uppercase tracking-wide text-[#f7c948] ring-1 ring-[#f2b40c]/40"
                                    >
                                        ✓ Verified
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1 rounded-full bg-amber-400/15 px-2.5 py-1 text-[0.68rem] font-bold uppercase tracking-wide text-amber-200 ring-1 ring-amber-300/30">
                                        Under review
                                    </span>
                                    <span v-if="memberSinceYear" class="text-xs text-blue-100/70">Since {{ memberSinceYear }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex shrink-0 flex-wrap gap-2">
                            <button
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-3.5 py-2 text-xs font-semibold text-white backdrop-blur transition hover:bg-white/20"
                                @click="startTour"
                            >
                                <span aria-hidden="true">✦</span> Take a tour
                            </button>
                            <Link
                                href="/ngo/digitalization"
                                class="inline-flex items-center gap-1 rounded-full bg-[#f2b40c] px-4 py-2 text-sm font-bold text-[#2a1c00] shadow-[0_14px_30px_-14px_rgba(242,180,12,0.85)] transition hover:-translate-y-0.5"
                            >
                                Customise website →
                            </Link>
                        </div>
                    </div>

                    <!-- Hero stat ribbon: headline real numbers with count-up -->
                    <div class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl bg-white/10 sm:grid-cols-4" data-tour="ribbon">
                        <div class="bg-white/[0.04] px-4 py-3.5 backdrop-blur-sm">
                            <p class="text-[0.62rem] font-semibold uppercase tracking-wider text-blue-100/70">Total raised</p>
                            <p class="fkbrand-display mt-1 text-xl font-semibold text-white sm:text-2xl">₹{{ inr(disp.totalRaised) }}</p>
                        </div>
                        <div class="bg-white/[0.04] px-4 py-3.5 backdrop-blur-sm">
                            <p class="text-[0.62rem] font-semibold uppercase tracking-wider text-blue-100/70">This month</p>
                            <p class="fkbrand-display mt-1 text-xl font-semibold text-white sm:text-2xl">₹{{ inr(disp.thisMonth) }}</p>
                        </div>
                        <div class="bg-white/[0.04] px-4 py-3.5 backdrop-blur-sm">
                            <p class="text-[0.62rem] font-semibold uppercase tracking-wider text-blue-100/70">Community</p>
                            <p class="fkbrand-display mt-1 text-xl font-semibold text-white sm:text-2xl">{{ num(disp.reach) }}</p>
                        </div>
                        <div class="bg-white/[0.04] px-4 py-3.5 backdrop-blur-sm">
                            <p class="text-[0.62rem] font-semibold uppercase tracking-wider text-blue-100/70">Feed views</p>
                            <p class="fkbrand-display mt-1 text-xl font-semibold text-white sm:text-2xl">{{ num(disp.views) }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <div v-if="!needsActivationNotice" class="reveal mb-6 flex flex-col gap-2 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-bold text-emerald-950">You're verified</h3>
                    <p class="text-sm text-emerald-900/85">Full access to campaigns, donations, and tools.</p>
                </div>
                <Link
                    href="/campaigns/create"
                    class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
                >
                    Start a campaign
                </Link>
            </div>

            <!-- Stat cards -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" data-tour="stats">
                <div class="ff-card reveal" style="--d: 0ms">
                    <p class="ff-card__label">Active campaigns</p>
                    <p class="ff-card__value fkbrand-display">{{ num(disp.campaigns) }}</p>
                    <Link href="/ngo/campaigns" class="ff-card__cta">Manage →</Link>
                </div>
                <div class="ff-card reveal" style="--d: 60ms">
                    <p class="ff-card__label">Total raised</p>
                    <p class="ff-card__value fkbrand-display">₹{{ inr(disp.totalRaised) }}</p>
                    <p class="ff-card__sub">This month · ₹{{ inr(disp.thisMonth) }}</p>
                    <Link href="/ngo/donations" class="ff-card__cta ff-card__cta--emerald">Donations →</Link>
                </div>
                <div class="ff-card reveal" style="--d: 120ms">
                    <p class="ff-card__label">Donor supporters</p>
                    <p class="ff-card__value fkbrand-display">{{ num(disp.donors) }}</p>
                    <p class="ff-card__sub">Unique donors (completed)</p>
                </div>
                <div class="ff-card reveal" style="--d: 180ms">
                    <p class="ff-card__label">Community reach</p>
                    <p class="ff-card__value fkbrand-display">{{ num(disp.reach) }}</p>
                    <p class="ff-card__sub">{{ num(disp.followers) }} followers · {{ num(disp.supporters) }} supporters</p>
                    <Link href="/feeds" class="ff-card__cta">Community feed →</Link>
                </div>
                <div class="ff-card reveal" style="--d: 240ms">
                    <p class="ff-card__label">Feed reach</p>
                    <p class="ff-card__value fkbrand-display">{{ num(disp.views) }}</p>
                    <p class="ff-card__sub">{{ num(disp.posts) }} posts · {{ num(disp.reactions) }} reactions</p>
                    <Link href="/ngo/feed-studio" class="ff-card__cta ff-card__cta--indigo">Feed studio →</Link>
                </div>
                <div class="ff-card reveal" style="--d: 300ms">
                    <p class="ff-card__label">Ledger balance</p>
                    <p class="ff-card__value fkbrand-display">₹{{ inr(disp.balance) }}</p>
                    <div class="mt-3 flex flex-wrap gap-x-3 gap-y-1 text-xs font-semibold">
                        <Link href="/ngo/ledger" class="text-violet-600 hover:text-violet-700">Ledger →</Link>
                        <Link href="/ngo/banking" class="text-slate-600 hover:text-slate-800">Banking →</Link>
                    </div>
                </div>
                <div class="ff-card reveal" style="--d: 360ms">
                    <p class="ff-card__label">Field teams</p>
                    <p class="ff-card__value fkbrand-display text-2xl">GPS</p>
                    <div class="mt-3 flex flex-wrap gap-x-3 gap-y-1 text-xs font-semibold">
                        <Link href="/ngo/field" class="text-blue-600 hover:text-blue-700">Command map →</Link>
                        <Link href="/ngo/field/app" class="text-emerald-600 hover:text-emerald-700">Field app →</Link>
                    </div>
                </div>
                <div class="ff-card reveal" style="--d: 420ms">
                    <p class="ff-card__label">Office & assets</p>
                    <p class="ff-card__value fkbrand-display">{{ num(disp.inventory) }}</p>
                    <p class="ff-card__sub">
                        {{ inventorySummary.fixed_assets }} assets · {{ inventorySummary.consumables }} consumables
                        <span v-if="inventorySummary.low_stock > 0" class="font-semibold text-amber-700"> · {{ inventorySummary.low_stock }} low</span>
                    </p>
                    <Link href="/ngo/office/inventory" class="ff-card__cta">Manage inventory →</Link>
                </div>
            </div>

            <!-- Money in vs spent (ledger) -->
            <section class="mt-10" data-tour="money">
                <div class="reveal flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="ff-h2 fkbrand-display">Money in &amp; money spent</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            Credits and debits from your <strong>ledger</strong> — donations posted as credits; expenses, payouts, and manual lines as debits.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Link href="/ngo/finance/activity" class="ff-pill">All movements</Link>
                        <Link href="/ngo/ledger" class="ff-pill ff-pill--violet">Manual ledger</Link>
                    </div>
                </div>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="reveal rounded-2xl border border-emerald-200/80 bg-emerald-50/50 p-4 shadow-sm" style="--d: 0ms">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-800">Credited (all time)</p>
                        <p class="fkbrand-display mt-1 text-2xl font-bold text-emerald-950">₹{{ Number(transparency.lifetime_credited || 0).toLocaleString('en-IN') }}</p>
                        <p class="mt-1 text-[11px] text-emerald-900/80">This month: ₹{{ Number(transparency.this_month_credited || 0).toLocaleString('en-IN') }}</p>
                    </div>
                    <div class="reveal rounded-2xl border border-rose-200/80 bg-rose-50/50 p-4 shadow-sm" style="--d: 80ms">
                        <p class="text-xs font-semibold uppercase tracking-wide text-rose-800">Spent (all time)</p>
                        <p class="fkbrand-display mt-1 text-2xl font-bold text-rose-950">₹{{ Number(transparency.lifetime_spent || 0).toLocaleString('en-IN') }}</p>
                        <p class="mt-1 text-[11px] text-rose-900/80">This month: ₹{{ Number(transparency.this_month_spent || 0).toLocaleString('en-IN') }}</p>
                    </div>
                    <div class="reveal rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:col-span-2" style="--d: 160ms">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Recent spending</p>
                        <ul v-if="transparency.recent_spending?.length" class="mt-2">
                            <li
                                v-for="row in transparency.recent_spending"
                                :key="row.id"
                                class="flex flex-wrap items-start justify-between gap-2 border-b border-slate-100 py-2 text-sm last:border-0"
                            >
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] text-slate-400">{{ formatShortDate(row.entry_date) }}</p>
                                    <p class="text-slate-700">
                                        <span class="font-medium text-slate-900">{{ row.category }}</span>
                                        <span v-if="row.description" class="text-slate-500"> — {{ row.description }}</span>
                                    </p>
                                </div>
                                <span class="shrink-0 font-semibold text-rose-700">−₹{{ Number(row.amount).toLocaleString('en-IN') }}</span>
                            </li>
                        </ul>
                        <p v-else class="mt-3 text-sm text-slate-500">No spending recorded in the ledger yet. Debits appear from expense claims, outbound payments, or manual ledger entries.</p>
                    </div>
                </div>
            </section>

            <!-- Platform advantages -->
            <section class="mt-10" data-tour="advantages">
                <h2 class="reveal ff-h2 fkbrand-display">What you get on FEVOURD-K</h2>
                <p class="reveal mt-1 text-sm text-slate-600">Built for transparency, fundraising, and digital presence.</p>
                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="(card, i) in advantageCards"
                        :key="card.title"
                        :href="card.href"
                        class="ff-adv reveal group"
                        :style="{ '--d': i * 60 + 'ms' }"
                    >
                        <div class="ff-adv__icon" :style="{ backgroundColor: accentColor }">
                            <span aria-hidden="true">{{ card.icon }}</span>
                        </div>
                        <h3 class="font-semibold text-slate-900">{{ card.title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ card.body }}</p>
                        <span class="mt-4 inline-flex text-xs font-semibold text-blue-600 group-hover:text-blue-700">{{ card.cta }} →</span>
                    </Link>
                </div>
            </section>

            <!-- Recent donations -->
            <section class="mt-10" data-tour="donations">
                <div class="reveal flex items-center justify-between gap-3">
                    <h2 class="ff-h2 fkbrand-display">Recent donations</h2>
                    <Link href="/ngo/donations" class="text-sm font-semibold text-blue-600 hover:text-blue-700">View all</Link>
                </div>
                <div class="reveal mt-4 rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                    <ul v-if="recentDonations.length" class="divide-y divide-slate-100">
                        <li
                            v-for="donation in recentDonations"
                            :key="donation.id"
                            class="flex flex-wrap items-center justify-between gap-3 px-4 py-4 sm:px-5"
                        >
                            <div>
                                <p class="font-medium text-slate-900">{{ donation.donor_name }}</p>
                                <p class="text-sm text-slate-500">{{ donation.campaign_title }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-emerald-700">₹{{ Number(donation.amount).toLocaleString('en-IN') }}</p>
                                <p class="text-xs text-slate-400">{{ formatDate(donation.created_at) }}</p>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="px-5 py-12 text-center">
                        <p class="text-sm text-slate-600">No donations yet. After activation, create a campaign to start receiving support.</p>
                        <Link href="/campaigns/create" class="mt-4 inline-flex rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                            Create campaign
                        </Link>
                    </div>
                </div>
            </section>

            <!-- Quick links -->
            <section class="mt-10 pb-8" data-tour="post">
                <h2 class="reveal ff-h2 fkbrand-display">Quick links</h2>
                <div class="reveal mt-4 flex flex-wrap gap-2">
                    <Link href="/ngo/post-update" class="ff-quick ff-quick--primary">＋ Post an update</Link>
                    <Link href="/campaigns/create" class="ff-quick">New campaign</Link>
                    <Link href="/ngo/profile" class="ff-quick">Edit profile</Link>
                    <Link href="/ngo/analytics" class="ff-quick">Website analytics</Link>
                    <Link href="/ngo/documents" class="ff-quick">Documents</Link>
                    <Link href="/ngo/office/inventory" class="ff-quick">Office & assets</Link>
                    <Link href="/feeds" class="ff-quick">Community feed</Link>
                </div>
            </section>
        </NgoWorkspaceShell>

        <!-- First-run guided tour -->
        <DashboardTour ref="tour" :steps="tourSteps" :auto-start="autoTour" storage-key="ngo_dashboard_tour_v1" />
    </AppLayout>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import DashboardTour from '@/Components/NGO/DashboardTour.vue'

const props = defineProps({
    ngo: { type: Object, required: true },
    stats: {
        type: Object,
        default: () => ({ campaigns: 0, totalRaised: 0, donors: 0, thisMonth: 0, followers: 0, supporters: 0 }),
    },
    feed: {
        type: Object,
        default: () => ({ posts: 0, views: 0, reactions: 0 }),
    },
    recentDonations: { type: Array, default: () => [] },
    ledgerSummary: {
        type: Object,
        default: () => ({ current_balance: 0, entries_count: 0 }),
    },
    welcomeAfterRegistration: { type: Boolean, default: false },
    inventorySummary: {
        type: Object,
        default: () => ({ fixed_assets: 0, consumables: 0, low_stock: 0 }),
    },
    transparency: {
        type: Object,
        default: () => ({
            lifetime_credited: 0,
            lifetime_spent: 0,
            this_month_credited: 0,
            this_month_spent: 0,
            ledger_donation_credits: 0,
            recent_spending: [],
        }),
    },
})

/* ---------- logo (fix /storage//assets 403) ---------- */
const logoFailed = ref(false)
const logoUrl = computed(() => {
    if (logoFailed.value) {
        return null
    }
    const raw = props.ngo?.logo
    if (!raw || typeof raw !== 'string') {
        return null
    }
    if (/^https?:\/\//i.test(raw) || raw.startsWith('/assets/') || raw.startsWith('assets/')) {
        return raw.startsWith('assets/') ? `/${raw}` : raw
    }
    if (raw.startsWith('/storage/')) {
        return raw
    }
    return `/storage/${raw.replace(/^\/+/, '')}`
})

const initials = computed(() => {
    const n = (props.ngo?.name || 'N').trim()
    const parts = n.split(/\s+/).filter(Boolean)
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase()
    }
    return n.slice(0, 2).toUpperCase()
})

/* ---------- derived numbers ---------- */
const reachTarget = computed(() => (props.stats.followers || 0) + (props.stats.supporters || 0))
const inventoryTarget = computed(() => (props.inventorySummary.fixed_assets || 0) + (props.inventorySummary.consumables || 0))

const accentColor = computed(() => {
    const c = props.ngo?.theme_color
    return typeof c === 'string' && /^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(c) ? c : '#2563eb'
})

const isVerified = computed(() => props.ngo?.verification_status === 'verified')
const isActive = computed(() => props.ngo?.is_active !== false)
const needsActivationNotice = computed(() => !isVerified.value || !isActive.value)

const memberSinceYear = computed(() => {
    const d = props.ngo?.created_at
    if (!d) {
        return null
    }
    const y = new Date(d).getFullYear()
    return Number.isFinite(y) ? y : null
})

const welcomeDismissed = ref(false)
const showWelcomeBanner = computed(() => props.welcomeAfterRegistration && !welcomeDismissed.value)
function dismissWelcome() {
    welcomeDismissed.value = true
}

/* ---------- count-up (rAF tween, reduced-motion aware) ---------- */
const disp = reactive({
    totalRaised: 0,
    thisMonth: 0,
    reach: 0,
    views: 0,
    campaigns: 0,
    donors: 0,
    followers: 0,
    supporters: 0,
    posts: 0,
    reactions: 0,
    balance: 0,
    inventory: 0,
})

const inr = (n) => Number(Math.round(n) || 0).toLocaleString('en-IN')
const num = (n) => Number(Math.round(n) || 0).toLocaleString('en-IN')

const prefersReduced = () =>
    typeof window !== 'undefined' && window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches

function tween(key, to, duration = 1100) {
    if (!to || prefersReduced()) {
        disp[key] = to || 0
        return
    }
    const start = performance.now()
    const from = disp[key]
    const ease = (t) => 1 - Math.pow(1 - t, 3)
    const step = (now) => {
        const p = Math.min(1, (now - start) / duration)
        disp[key] = from + (to - from) * ease(p)
        if (p < 1) {
            requestAnimationFrame(step)
        } else {
            disp[key] = to
        }
    }
    requestAnimationFrame(step)
}

let counted = false
function runCounts() {
    if (counted) {
        return
    }
    counted = true
    tween('totalRaised', props.stats.totalRaised || 0)
    tween('thisMonth', props.stats.thisMonth || 0)
    tween('reach', reachTarget.value)
    tween('views', props.feed.views || 0)
    tween('campaigns', props.stats.campaigns || 0, 800)
    tween('donors', props.stats.donors || 0, 800)
    tween('followers', props.stats.followers || 0, 800)
    tween('supporters', props.stats.supporters || 0, 800)
    tween('posts', props.feed.posts || 0, 800)
    tween('reactions', props.feed.reactions || 0, 800)
    tween('balance', props.ledgerSummary.current_balance || 0)
    tween('inventory', inventoryTarget.value, 800)
}

/* ---------- reveal-on-scroll ---------- */
const tour = ref(null)
const autoTour = ref(true) // first-time visitors only — DashboardTour gates on localStorage
function startTour() {
    tour.value?.start()
}

onMounted(() => {
    runCounts() // hero ribbon is above the fold; start immediately

    const els = Array.from(document.querySelectorAll('.reveal'))
    if (prefersReduced() || !('IntersectionObserver' in window)) {
        els.forEach((el) => el.classList.add('is-in'))
        return
    }
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-in')
                    io.unobserve(entry.target)
                }
            })
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.08 },
    )
    els.forEach((el) => io.observe(el))
})

const advantageCards = computed(() => {
    const slug = props.ngo?.slug
    const micrositeHref = slug ? `/ngos/${slug}` : '/ngos'
    return [
        { icon: '🌐', title: 'Free NGO microsite', body: 'A public page for your mission, campaigns, and impact — included so donors can find and trust you.', href: micrositeHref, cta: 'View public page' },
        { icon: '💳', title: 'Fundraising & campaigns', body: 'Create campaigns, track performance, and receive donations with clear reporting.', href: '/ngo/campaigns', cta: 'Manage campaigns' },
        { icon: '📊', title: 'Transparency & ledger', body: 'Structured ledger and donation history to stay accountable to supporters and regulators.', href: '/ngo/ledger', cta: 'Open ledger' },
        { icon: '📁', title: 'Document centre', body: 'Store registration, PAN, and compliance files in one secure place.', href: '/ngo/documents', cta: 'Open documents' },
        { icon: '📦', title: 'Office & assets', body: 'Register laptops, furniture, vehicles, and consumables — location, custodian, warranty, and reorder alerts.', href: '/ngo/office/inventory', cta: 'Manage inventory' },
        { icon: '🚀', title: 'Digitalization hub', body: 'Tools and settings to grow your online presence and connect channels.', href: '/ngo/digitalization', cta: 'Digitalization' },
    ]
})

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}

function formatShortDate(d) {
    if (!d) {
        return ''
    }
    return new Date(d + 'T12:00:00').toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}

const tourSteps = [
    { selector: '[data-tour="hero"]', title: 'Your organisation, front and centre', body: 'This is your branded workspace. Your logo, verification status, and headline impact numbers live here. The gold button lets you customise your public website anytime.' },
    { selector: '[data-tour="ribbon"]', title: 'Impact at a glance', body: 'Total raised, this month, community reach, and feed views — your four headline numbers, always live and real.' },
    { selector: '[data-tour="stats"]', title: 'Everything you manage', body: 'Campaigns, donations, community, feed reach, ledger balance, field teams, and assets. Tap any card to jump straight to that tool.' },
    { selector: '[data-tour="money"]', title: 'Full money transparency', body: 'Every rupee in and out, straight from your ledger. Donations come in as credits; expenses and payouts as debits — nothing hidden.' },
    { selector: '[data-tour="post"]', title: 'Share your impact', body: 'Post an update and it goes live to the community feed — growing your reach and the numbers up top. That is the whole loop!' },
]
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap');

.fkbrand-display { font-family: 'Fraunces', 'Playfair Display', Georgia, serif; font-optical-sizing: auto; }

/* ---------- Hero ---------- */
.fkbrand-hero { background: radial-gradient(120% 120% at 85% -10%, #1b3aa0 0%, #11286e 42%, #081640 100%); }
.fkbrand-hero__grain {
    position: absolute; inset: 0; z-index: 0; opacity: 0.45; mix-blend-mode: overlay; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
}
.fkbrand-hero__sheen {
    position: absolute; inset: 0; z-index: 1; pointer-events: none;
    background: linear-gradient(105deg, transparent 30%, rgba(255, 255, 255, 0.08) 45%, transparent 60%);
    transform: translateX(-100%); animation: fk-sheen 7s ease-in-out 1.2s infinite;
}
@keyframes fk-sheen { 0% { transform: translateX(-100%); } 35%, 100% { transform: translateX(120%); } }

/* ---------- Glowing logo medallion ---------- */
.fkmedallion { position: relative; height: 4.5rem; width: 4.5rem; flex-shrink: 0; }
@media (min-width: 640px) { .fkmedallion { height: 5.25rem; width: 5.25rem; } }
.fkmedallion__ring {
    position: absolute; inset: -4px; border-radius: 9999px;
    background: conic-gradient(from 0deg, #f2b40c, #f7c948, #fff3cf, #f2b40c);
    filter: blur(0.5px); animation: fk-spin 7s linear infinite;
    box-shadow: 0 0 26px 4px rgba(242, 180, 12, 0.5);
}
.fkmedallion__inner {
    position: absolute; inset: 3px; display: flex; align-items: center; justify-content: center;
    overflow: hidden; border-radius: 9999px;
    background: radial-gradient(circle at 35% 30%, #1b3aa0, #081640);
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.4);
}
@keyframes fk-spin { to { transform: rotate(360deg); } }

/* ---------- Stat cards (ff-) ---------- */
.ff-card {
    position: relative; overflow: hidden; border-radius: 1rem;
    border: 1px solid rgb(226 232 240 / 0.8); background: #fff; padding: 1.25rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.ff-card::before {
    content: ""; position: absolute; inset: 0 0 auto 0; height: 3px;
    background: linear-gradient(90deg, #f2b40c, #f7c948); opacity: 0.35; transition: opacity 0.2s ease;
}
.ff-card:hover { transform: translateY(-3px); box-shadow: 0 18px 36px -18px rgba(15, 23, 42, 0.28); border-color: rgb(203 213 225); }
.ff-card:hover::before { opacity: 1; }
.ff-card__label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: rgb(100 116 139); }
.ff-card__value { margin-top: 0.5rem; font-size: 1.85rem; font-weight: 600; line-height: 1.05; color: rgb(15 23 42); }
.ff-card__sub { margin-top: 0.35rem; font-size: 0.72rem; color: rgb(100 116 139); }
.ff-card__cta { margin-top: 0.75rem; display: inline-block; font-size: 0.72rem; font-weight: 600; color: rgb(37 99 235); }
.ff-card__cta:hover { color: rgb(29 78 216); }
.ff-card__cta--emerald { color: rgb(5 150 105); }
.ff-card__cta--indigo { color: rgb(79 70 229); }

/* ---------- Section heading ---------- */
.ff-h2 { font-size: 1.35rem; font-weight: 600; color: rgb(15 23 42); }

/* ---------- Pills & quick links ---------- */
.ff-pill { display: inline-flex; border-radius: 0.75rem; border: 1px solid rgb(226 232 240); background: #fff; padding: 0.5rem 0.75rem; font-size: 0.72rem; font-weight: 600; color: rgb(51 65 85); box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04); }
.ff-pill:hover { background: rgb(248 250 252); }
.ff-pill--violet { color: rgb(109 40 217); }
.ff-pill--violet:hover { background: rgb(245 243 255); }

.ff-quick { border-radius: 9999px; border: 1px solid rgb(226 232 240); background: #fff; padding: 0.5rem 1rem; font-size: 0.82rem; font-weight: 500; color: rgb(51 65 85); box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04); transition: transform 0.15s ease, background 0.15s ease; }
.ff-quick:hover { background: rgb(248 250 252); transform: translateY(-1px); }
.ff-quick--primary { border-color: transparent; background: linear-gradient(180deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 12px 24px -14px rgba(37, 99, 235, 0.9); }

/* ---------- Advantage cards ---------- */
.ff-adv {
    display: block; border-radius: 1rem; border: 1px solid rgb(226 232 240 / 0.8); background: #fff; padding: 1.25rem;
    text-align: left; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.ff-adv:hover { transform: translateY(-3px); border-color: rgb(203 213 225); box-shadow: 0 18px 36px -18px rgba(15, 23, 42, 0.28); }
.ff-adv__icon { margin-bottom: 0.75rem; display: flex; height: 2.5rem; width: 2.5rem; align-items: center; justify-content: center; border-radius: 0.75rem; color: #fff; font-size: 1.125rem; }

/* ---------- Reveal-on-scroll ---------- */
.reveal { opacity: 0; transform: translateY(18px); transition: opacity 0.6s ease, transform 0.6s cubic-bezier(0.22, 1, 0.36, 1); transition-delay: var(--d, 0ms); }
.reveal.is-in { opacity: 1; transform: none; }

@media (prefers-reduced-motion: reduce) {
    .fkbrand-hero__sheen, .fkmedallion__ring { animation: none; }
    .reveal { opacity: 1; transform: none; transition: none; }
}
</style>
