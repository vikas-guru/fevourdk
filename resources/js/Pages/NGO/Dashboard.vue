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
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active campaigns</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.campaigns }}</p>
                        <Link href="/ngo/campaigns" class="mt-3 inline-block text-xs font-semibold text-blue-600 hover:text-blue-700">Manage →</Link>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total raised</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">₹{{ Number(stats.totalRaised || 0).toLocaleString('en-IN') }}</p>
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
                </div>

                <!-- Platform advantages -->
                <section class="mt-10">
                    <h2 class="text-lg font-bold text-slate-900">What you get on FEVOURD-K</h2>
                    <p class="mt-1 text-sm text-slate-600">Built for transparency, fundraising, and digital presence.</p>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="card in advantageCards"
                            :key="card.title"
                            class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:border-slate-300 hover:shadow-md"
                        >
                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl text-white"
                                :style="{ backgroundColor: accentColor }"
                            >
                                <span class="text-lg" aria-hidden="true">{{ card.icon }}</span>
                            </div>
                            <h3 class="font-semibold text-slate-900">{{ card.title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ card.body }}</p>
                        </div>
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

const advantageCards = [
    {
        icon: '🌐',
        title: 'Free NGO microsite',
        body: 'A public page for your mission, campaigns, and impact — included so donors can find and trust you.',
    },
    {
        icon: '💳',
        title: 'Fundraising & campaigns',
        body: 'Create campaigns, track performance, and receive donations with clear reporting.',
    },
    {
        icon: '📊',
        title: 'Transparency & ledger',
        body: 'Structured ledger and donation history to stay accountable to supporters and regulators.',
    },
    {
        icon: '📁',
        title: 'Document centre',
        body: 'Store registration, PAN, and compliance files in one secure place.',
    },
    {
        icon: '🚀',
        title: 'Digitalization hub',
        body: 'Tools and settings to grow your online presence and connect channels.',
    },
    {
        icon: '🤝',
        title: 'Karnataka voluntary network',
        body: 'Part of FEVOURD-K — connecting voluntary organisations across the state.',
    },
]

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
}
</script>

