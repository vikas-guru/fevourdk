<template>
    <AppLayout title="NGO workspace — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="dashboard">
                <!-- Welcome (first visit after registration) -->
                <div
                    v-if="showWelcomeBanner"
                    class="relative mb-6 overflow-hidden rounded-2xl border border-emerald-200/80 bg-gradient-to-br from-emerald-50 via-white to-blue-50 p-6 shadow-sm"
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
                    class="mb-6 rounded-2xl border border-amber-200 bg-amber-50/90 p-5 shadow-sm"
                >
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-800">!</div>
                        <div>
                            <h3 class="text-base font-bold text-amber-950">Activation in progress</h3>
                            <p class="mt-1 text-sm leading-relaxed text-amber-900/90">
                                Your registration is complete. We are reviewing your profile and documents. Once your account is
                                <strong>activated</strong>, you can publish campaigns, collect donations, and access payouts — explore the menu
                                on the left to familiarise yourself meanwhile.
                            </p>
                        </div>
                    </div>
                </div>

                <div v-else class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-5 shadow-sm">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-bold text-emerald-950">You’re verified</h3>
                            <p class="text-sm text-emerald-900/85">Full access to campaigns, donations, and tools.</p>
                        </div>
                        <Link
                            href="/campaigns/create"
                            class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
                        >
                            Start a campaign
                        </Link>
                    </div>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Overview</h1>
                <p class="mt-1 text-sm text-slate-600">Your impact at a glance</p>

                <!-- Stats -->
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active campaigns</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.campaigns }}</p>
                        <Link href="/ngo/campaigns" class="mt-3 inline-block text-xs font-semibold text-blue-600 hover:text-blue-700">Manage →</Link>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total raised</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">₹{{ Number(stats.totalRaised || 0).toLocaleString('en-IN') }}</p>
                        <p class="mt-1 text-xs text-slate-500">This month: ₹{{ Number(stats.thisMonth || 0).toLocaleString('en-IN') }}</p>
                        <Link href="/ngo/donations" class="mt-3 inline-block text-xs font-semibold text-emerald-600 hover:text-emerald-700">Donations →</Link>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Supporters</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.donors }}</p>
                        <p class="mt-3 text-xs text-slate-500">Unique donors (completed)</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ledger balance</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">₹{{ Number(ledgerSummary.current_balance || 0).toLocaleString('en-IN') }}</p>
                        <div class="mt-3 flex flex-wrap gap-x-3 gap-y-1 text-xs font-semibold">
                            <Link href="/ngo/ledger" class="text-violet-600 hover:text-violet-700">Ledger →</Link>
                            <Link href="/ngo/banking" class="text-slate-600 hover:text-slate-800">Banking →</Link>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Field teams</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">GPS</p>
                        <div class="mt-3 flex flex-wrap gap-x-3 gap-y-1 text-xs font-semibold">
                            <Link href="/ngo/field" class="text-blue-600 hover:text-blue-700">Command map →</Link>
                            <Link href="/ngo/field/app" class="text-emerald-600 hover:text-emerald-700">Field app →</Link>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm xl:col-span-1">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Office & assets</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ inventoryTotal }}</p>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ inventorySummary.fixed_assets }} assets · {{ inventorySummary.consumables }} consumables
                            <span v-if="inventorySummary.low_stock > 0" class="font-semibold text-amber-700"> · {{ inventorySummary.low_stock }} low stock</span>
                        </p>
                        <Link href="/ngo/office/inventory" class="mt-3 inline-block text-xs font-semibold text-slate-700 hover:text-slate-900">Manage inventory →</Link>
                    </div>
                </div>

                <!-- Money in vs spent (ledger) -->
                <section class="mt-10">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Money in &amp; money spent</h2>
                            <p class="mt-1 text-sm text-slate-600">
                                Credits and debits from your <strong>ledger</strong> — donations posted as credits; expenses, payouts, and manual lines as debits.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <Link
                                href="/ngo/finance/activity"
                                class="inline-flex rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
                            >
                                All movements
                            </Link>
                            <Link
                                href="/ngo/ledger"
                                class="inline-flex rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-violet-700 shadow-sm hover:bg-violet-50"
                            >
                                Manual ledger
                            </Link>
                        </div>
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50/50 p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-800">Credited (all time)</p>
                            <p class="mt-1 text-2xl font-bold text-emerald-950">₹{{ Number(transparency.lifetime_credited || 0).toLocaleString('en-IN') }}</p>
                            <p class="mt-1 text-[11px] text-emerald-900/80">This month: ₹{{ Number(transparency.this_month_credited || 0).toLocaleString('en-IN') }}</p>
                        </div>
                        <div class="rounded-2xl border border-rose-200/80 bg-rose-50/50 p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-rose-800">Spent (all time)</p>
                            <p class="mt-1 text-2xl font-bold text-rose-950">₹{{ Number(transparency.lifetime_spent || 0).toLocaleString('en-IN') }}</p>
                            <p class="mt-1 text-[11px] text-rose-900/80">This month: ₹{{ Number(transparency.this_month_spent || 0).toLocaleString('en-IN') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:col-span-2 lg:col-span-2">
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
                <section class="mt-10">
                    <h2 class="text-lg font-bold text-slate-900">What you get on FEVOURD-K</h2>
                    <p class="mt-1 text-sm text-slate-600">Built for transparency, fundraising, and digital presence.</p>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <Link
                            v-for="card in advantageCards"
                            :key="card.title"
                            :href="card.href"
                            class="group block rounded-2xl border border-slate-200/80 bg-white p-5 text-left shadow-sm transition hover:border-slate-300 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        >
                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl text-white"
                                :style="{ backgroundColor: accentColor }"
                            >
                                <span class="text-lg" aria-hidden="true">{{ card.icon }}</span>
                            </div>
                            <h3 class="font-semibold text-slate-900">{{ card.title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ card.body }}</p>
                            <span class="mt-4 inline-flex text-xs font-semibold text-blue-600 group-hover:text-blue-700">
                                {{ card.cta }} →
                            </span>
                        </Link>
                    </div>
                </section>

                <!-- Recent donations -->
                <section class="mt-10">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-lg font-bold text-slate-900">Recent donations</h2>
                        <Link href="/ngo/donations" class="text-sm font-semibold text-blue-600 hover:text-blue-700">View all</Link>
                    </div>
                    <div class="mt-4 rounded-2xl border border-slate-200/80 bg-white shadow-sm">
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
                            <Link
                                href="/campaigns/create"
                                class="mt-4 inline-flex rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                            >
                                Create campaign
                            </Link>
                        </div>
                    </div>
                </section>

                <!-- Quick links -->
                <section class="mt-10 pb-8">
                    <h2 class="text-lg font-bold text-slate-900">Quick links</h2>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <Link
                            href="/campaigns/create"
                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50"
                        >
                            New campaign
                        </Link>
                        <Link
                            href="/ngo/profile"
                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50"
                        >
                            Edit profile
                        </Link>
                        <Link
                            href="/ngo/analytics"
                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50"
                        >
                            Website analytics
                        </Link>
                        <Link
                            href="/ngo/documents"
                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50"
                        >
                            Documents
                        </Link>
                        <Link
                            href="/ngo/office/inventory"
                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50"
                        >
                            Office & assets
                        </Link>
                        <Link
                            href="/feeds"
                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50"
                        >
                            Community feed
                        </Link>
                    </div>
                </section>
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'

const props = defineProps({
    ngo: { type: Object, required: true },
    stats: {
        type: Object,
        default: () => ({ campaigns: 0, totalRaised: 0, donors: 0, thisMonth: 0 }),
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

const inventoryTotal = computed(() => {
    const s = props.inventorySummary || {}
    return (s.fixed_assets || 0) + (s.consumables || 0)
})

const welcomeDismissed = ref(false)

const accentColor = computed(() => {
    const c = props.ngo?.theme_color
    return typeof c === 'string' && /^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(c) ? c : '#2563eb'
})

const isVerified = computed(() => props.ngo?.verification_status === 'verified')
const isActive = computed(() => props.ngo?.is_active !== false)

const needsActivationNotice = computed(() => !isVerified.value || !isActive.value)

const showWelcomeBanner = computed(() => props.welcomeAfterRegistration && !welcomeDismissed.value)

function dismissWelcome() {
    welcomeDismissed.value = true
}

const advantageCards = computed(() => {
    const slug = props.ngo?.slug
    const micrositeHref = slug ? `/ngos/${slug}` : '/ngos'

    return [
        {
            icon: '🌐',
            title: 'Free NGO microsite',
            body: 'A public page for your mission, campaigns, and impact — included so donors can find and trust you.',
            href: micrositeHref,
            cta: 'View public page',
        },
        {
            icon: '💳',
            title: 'Fundraising & campaigns',
            body: 'Create campaigns, track performance, and receive donations with clear reporting.',
            href: '/ngo/campaigns',
            cta: 'Manage campaigns',
        },
        {
            icon: '📊',
            title: 'Transparency & ledger',
            body: 'Structured ledger and donation history to stay accountable to supporters and regulators.',
            href: '/ngo/ledger',
            cta: 'Open ledger',
        },
        {
            icon: '📁',
            title: 'Document centre',
            body: 'Store registration, PAN, and compliance files in one secure place.',
            href: '/ngo/documents',
            cta: 'Open documents',
        },
        {
            icon: '📦',
            title: 'Office & assets',
            body: 'Register laptops, furniture, vehicles, and consumables — location, custodian, warranty, and reorder alerts.',
            href: '/ngo/office/inventory',
            cta: 'Manage inventory',
        },
        {
            icon: '🚀',
            title: 'Digitalization hub',
            body: 'Tools and settings to grow your online presence and connect channels.',
            href: '/ngo/digitalization',
            cta: 'Digitalization',
        },
        {
            icon: '🤝',
            title: 'Karnataka voluntary network',
            body: 'Part of FEVOURD-K — connecting voluntary organisations across the state.',
            href: '/feeds',
            cta: 'Community feed',
        },
    ]
})

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
}

function formatShortDate(d) {
    if (!d) {
        return ''
    }
    return new Date(d + 'T12:00:00').toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>

