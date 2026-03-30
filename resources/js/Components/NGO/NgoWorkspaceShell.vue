<template>
    <div class="flex min-h-[calc(100vh-5rem)] flex-col bg-slate-50 lg:flex-row">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 py-3 lg:hidden">
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-900">{{ ngo.name }}</p>
                <p class="text-xs text-slate-500">{{ financeOnlyNav ? 'Treasury workspace' : 'NGO workspace' }}</p>
            </div>
            <button
                type="button"
                class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700"
                @click="mobileNavOpen = true"
            >
                Menu
            </button>
        </div>

        <div
            v-if="mobileNavOpen"
            class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"
            aria-hidden="true"
            @click="mobileNavOpen = false"
        />

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-[17.5rem] flex-col border-r border-slate-200/80 bg-white shadow-xl transition-transform duration-200 lg:static lg:z-0 lg:shadow-none"
            :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div
                class="border-b border-slate-100 px-5 py-6"
                :style="{ borderTopWidth: '3px', borderTopColor: accentColor }"
            >
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-100 bg-slate-50 text-lg font-bold text-slate-600"
                    >
                        <img
                            v-if="ngo.logo"
                            :src="'/storage/' + ngo.logo"
                            :alt="ngo.name"
                            class="h-full w-full object-cover"
                        >
                        <span v-else>{{ initials }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold leading-tight text-slate-900">{{ ngo.name }}</p>
                        <p class="mt-1 text-[11px] font-medium uppercase tracking-wide text-slate-500">Your organisation</p>
                        <span
                            class="mt-2 inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide"
                            :class="statusBadgeClass"
                        >
                            {{ statusLabel }}
                        </span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-4">
                <template v-if="financeOnlyNav">
                    <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-emerald-700">Finance login</p>
                    <Link
                        href="/ngo/finance"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        :class="{ 'bg-emerald-50 text-emerald-900': currentKey === 'finance-hub' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-slate-700">₹</span>
                        Finance hub
                    </Link>
                    <Link
                        href="/ngo/finance/activity"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        :class="{ 'bg-blue-50 text-blue-800': currentKey === 'finance-activity' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">≡</span>
                        All movements
                    </Link>
                    <Link
                        href="/ngo/finance/payments"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        :class="{ 'bg-blue-50 text-blue-800': currentKey === 'finance-payments' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">↗</span>
                        Pay staff / vendors
                    </Link>
                    <Link
                        href="/ngo/finance/claims"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        :class="{ 'bg-blue-50 text-blue-800': currentKey === 'finance-claims' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">🧾</span>
                        Expense claims
                    </Link>
                    <Link
                        href="/ngo/donations"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        :class="{ 'bg-blue-50 text-blue-800': currentKey === 'donations' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">♥</span>
                        Donations in
                    </Link>
                    <Link
                        href="/ngo/banking"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        :class="{ 'bg-blue-50 text-blue-800': currentKey === 'banking' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">🏦</span>
                        Bank accounts
                    </Link>
                    <Link
                        href="/ngo/ledger"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        :class="{ 'bg-blue-50 text-blue-800': currentKey === 'ledger' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">📒</span>
                        Ledger
                    </Link>
                    <Link
                        href="/ngo/dashboard"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        :class="{ 'bg-blue-50 text-blue-800': currentKey === 'dashboard' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">⌂</span>
                        Org snapshot
                    </Link>
                    <Link
                        href="/ngo/profile"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        :class="{ 'bg-blue-50 text-blue-800': currentKey === 'profile' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">⚙</span>
                        Profile
                    </Link>
                </template>
                <template v-else>
                <Link
                    href="/ngo/dashboard"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'dashboard' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">⌂</span>
                    Overview
                </Link>

                <p class="px-3 pb-1 pt-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Finance & treasury</p>
                <Link
                    href="/ngo/finance"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-emerald-50 text-emerald-900': currentKey === 'finance-hub' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-slate-700">₹</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>Finance hub</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">Balances · cashbook · settings</span>
                    </span>
                </Link>
                <Link
                    href="/ngo/finance/activity"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'finance-activity' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">≡</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>All movements</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">Every rupee in & out</span>
                    </span>
                </Link>
                <Link
                    href="/ngo/finance/payments"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'finance-payments' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">↗</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>Outbound payments</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">Salary · vendor · stipend</span>
                    </span>
                </Link>
                <Link
                    href="/ngo/finance/claims"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'finance-claims' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">🧾</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>Expense claims</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">Auto-posts to ledger</span>
                    </span>
                </Link>
                <Link
                    href="/ngo/banking"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'banking' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">🏦</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>Banking & gateways</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">Accounts · Razorpay etc.</span>
                    </span>
                </Link>
                <Link
                    href="/ngo/ledger"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'ledger' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">📒</span>
                    Manual ledger lines
                </Link>

                <p class="px-3 pb-1 pt-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">HRMS & site teams</p>
                <Link
                    href="/ngo/hr"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'hr-index' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-slate-700">HR</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>HRMS overview</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">One login · people · leave · visits</span>
                    </span>
                </Link>
                <Link
                    href="/ngo/hr/team"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'hr-team' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">👥</span>
                    Team & employees
                </Link>
                <Link
                    href="/ngo/hr/leaves"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'hr-leaves' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">📅</span>
                    Leave
                </Link>
                <Link
                    href="/ngo/field"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'field' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">📍</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>Site visits & tasks</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">Map · assign · live routes</span>
                    </span>
                </Link>
                <Link
                    href="/ngo/field/app"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'field-app' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">🛰</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>Field tracker</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">GPS trail (same login)</span>
                    </span>
                </Link>

                <div class="mx-0.5 mb-3 space-y-1 rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white p-3 shadow-sm">
                    <Link
                        href="/ngo/post-update"
                        class="flex flex-col gap-0.5 rounded-lg px-2 py-2.5 text-sm font-bold text-blue-900 hover:bg-white/70"
                        :class="{ 'bg-white shadow-sm ring-1 ring-blue-100': currentKey === 'post-update' }"
                        @click="mobileNavOpen = false"
                    >
                        <span class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-sm text-white">＋</span>
                            Post an update
                        </span>
                        <span class="pl-10 text-[11px] font-normal text-blue-800/80">Goes to the live community feed</span>
                    </Link>
                    <Link
                        href="/feeds"
                        class="block rounded-lg px-2 py-2 text-center text-xs font-semibold text-slate-600 hover:bg-white/60 hover:text-slate-900"
                        @click="mobileNavOpen = false"
                    >
                        View community feed →
                    </Link>
                    <Link
                        href="/ngo/feed-studio"
                        class="block rounded-lg px-2 py-1.5 text-center text-xs font-semibold text-indigo-700 hover:bg-white/60"
                        :class="{ 'bg-white/80 ring-1 ring-indigo-100': currentKey === 'feed-studio' }"
                        @click="mobileNavOpen = false"
                    >
                        Feed studio (analytics)
                    </Link>
                </div>

                <Link
                    href="/ngo/analytics"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'analytics' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">📈</span>
                    Analytics
                </Link>
                <Link
                    href="/ngo/campaigns"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'campaigns' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">◎</span>
                    Campaigns
                </Link>
                <Link
                    href="/ngo/donations"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'donations' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">₹</span>
                    Donations
                </Link>
                <Link
                    href="/ngo/documents"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'documents' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">▤</span>
                    Documents
                </Link>
                <Link
                    href="/ngo/office/inventory"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'office-inventory' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">📦</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>Office & assets</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">Inventory · low-stock hints</span>
                    </span>
                </Link>
                <Link
                    href="/ngo/digitalization"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'digitalization' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">⚡</span>
                    Digitalization
                </Link>
                <Link
                    href="/ngo/social-connect"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'social-connect' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">🔗</span>
                    <span class="flex min-w-0 flex-col leading-tight">
                        <span>Auto-share (optional)</span>
                        <span class="truncate text-[10px] font-normal text-slate-500">Facebook · Instagram · LinkedIn</span>
                    </span>
                </Link>
                <Link
                    href="/ngo/profile"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    :class="{ 'bg-blue-50 text-blue-800': currentKey === 'profile' }"
                    @click="mobileNavOpen = false"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">⚙</span>
                    Profile
                </Link>
                </template>
            </nav>

            <div class="border-t border-slate-100 p-4">
                <a
                    v-if="publicWebsiteUrl"
                    :href="publicWebsiteUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-3 py-2.5 text-xs font-semibold text-white hover:bg-slate-800"
                >
                    View public website
                </a>
                <p v-else class="text-center text-[11px] text-slate-500">Website link will appear after activation.</p>
            </div>
        </aside>

        <main class="flex-1 overflow-x-hidden px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
    ngo: { type: Object, required: true },
    currentKey: {
        type: String,
        default: 'dashboard',
    },
})

const mobileNavOpen = ref(false)
const page = usePage()

const financeOnlyNav = computed(() => {
    const raw = page.props.auth?.roles ?? []
    const names = raw.map((r) => (typeof r === 'string' ? r : r?.name)).filter(Boolean)
    return names.includes('ngo_finance') && ! names.includes('ngo_admin') && ! names.includes('ngo_staff')
})

const accentColor = computed(() => {
    const c = props.ngo?.theme_color
    return typeof c === 'string' && /^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(c) ? c : '#2563eb'
})

const initials = computed(() => {
    const n = (props.ngo?.name || 'N').trim()
    const parts = n.split(/\s+/).filter(Boolean)
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase()
    }
    return n.slice(0, 2).toUpperCase()
})

const publicWebsiteUrl = computed(() => {
    if (props.ngo?.website_url) {
        return props.ngo.website_url
    }
    if (props.ngo?.slug) {
        return `/ngos/${props.ngo.slug}`
    }
    return null
})

const isVerified = computed(() => props.ngo?.verification_status === 'verified')
const isActive = computed(() => props.ngo?.is_active !== false)

const statusLabel = computed(() => {
    if (!isActive.value) {
        return 'Pending activation'
    }
    if (!isVerified.value) {
        return 'Under verification'
    }
    return 'Verified'
})

const statusBadgeClass = computed(() => {
    if (isVerified.value && isActive.value) {
        return 'bg-emerald-100 text-emerald-800'
    }
    return 'bg-amber-100 text-amber-900'
})
</script>
