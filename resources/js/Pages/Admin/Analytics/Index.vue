<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    pageVisits: { type: Array, default: () => [] },
    totalPageViews: { type: Number, default: 0 },
    avgDailyPageViews: { type: Number, default: 0 },
    userRegistrationTrends: { type: Array, default: () => [] },
    topPaths: { type: Array, default: () => [] },
    deviceDistribution: { type: Array, default: () => [] },
    uniqueSessions30d: { type: Number, default: 0 },
    pageViewsByAuth: { type: Object, default: () => ({ logged_in: 0, guest: 0 }) },
    topCountries: { type: Array, default: () => [] },
    topBrowsers: { type: Array, default: () => [] },
    topOs: { type: Array, default: () => [] },
    userRoleDistribution: { type: Array, default: () => [] },
    topReferrers: { type: Array, default: () => [] },
})

const visitsMax = computed(() => {
    const rows = [...(props.pageVisits || [])]
    if (!rows.length) return 1
    return Math.max(1, ...rows.map((r) => Number(r.visits) || 0))
})

const pathsMax = computed(() => {
    const rows = [...(props.topPaths || [])]
    if (!rows.length) return 1
    return Math.max(1, ...rows.map((r) => Number(r.visits) || 0))
})

const devicesMax = computed(() => {
    const rows = [...(props.deviceDistribution || [])]
    if (!rows.length) return 1
    return Math.max(1, ...rows.map((r) => Number(r.count) || 0))
})

const countriesMax = computed(() => {
    const rows = [...(props.topCountries || [])]
    if (!rows.length) return 1
    return Math.max(1, ...rows.map((r) => Number(r.count) || 0))
})

const referrersMax = computed(() => {
    const rows = [...(props.topReferrers || [])]
    if (!rows.length) return 1
    return Math.max(1, ...rows.map((r) => Number(r.count) || 0))
})

const osMax = computed(() => {
    const rows = [...(props.topOs || [])]
    if (!rows.length) return 1
    return Math.max(1, ...rows.map((r) => Number(r.count) || 0))
})

const registrationMax = computed(() => {
    const rows = props.userRegistrationTrends || []
    return Math.max(1, ...rows.map((r) => Number(r.count) || 0))
})

const authTotal = computed(() => {
    const a = props.pageViewsByAuth || {}
    return (Number(a.logged_in) || 0) + (Number(a.guest) || 0)
})
</script>

<template>
    <AdminLayout title="Website analytics">
        <div class="space-y-8 pb-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-violet-600">Super admin</p>
                    <h1 class="text-2xl font-bold text-slate-900">Website &amp; traffic analytics</h1>
                    <p class="mt-1 text-sm text-slate-600">Last 30 days unless noted. Data comes from <code class="rounded bg-slate-100 px-1 text-xs">analytics_page_views</code>.</p>
                </div>
                <Link href="/admin/dashboard" class="text-sm font-semibold text-indigo-600 hover:underline">← Back to dashboard</Link>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-violet-50 to-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-violet-600">Total views (30d)</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ totalPageViews ?? 0 }}</p>
                    <p class="mt-1 text-xs text-slate-500">~{{ avgDailyPageViews ?? 0 }} / day on days with traffic</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-sky-50 to-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-sky-600">Unique sessions</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ uniqueSessions30d ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-emerald-600">Logged-in views</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ pageViewsByAuth?.logged_in ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-amber-600">Guest views</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ pageViewsByAuth?.guest ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Daily traffic (last 30 days)</h2>
                    <div class="mt-6 flex h-48 items-end gap-1 overflow-x-auto pb-2">
                        <div
                            v-for="row in [...(pageVisits || [])].reverse()"
                            :key="row.date"
                            class="flex min-w-[18px] flex-1 flex-col items-center justify-end gap-1"
                        >
                            <div
                                class="w-full max-w-[28px] rounded-t-md bg-gradient-to-t from-indigo-600 to-violet-400 transition hover:opacity-90"
                                :style="{ height: `${Math.max(8, (Number(row.visits) / visitsMax) * 100)}%` }"
                                :title="`${row.date}: ${row.visits}`"
                            />
                            <span class="max-w-[48px] truncate text-[9px] text-slate-400">{{ String(row.date).slice(5) }}</span>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Auth mix</h2>
                    <div class="mt-4 space-y-4">
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Logged in</span>
                                <span class="font-bold text-slate-900">{{ pageViewsByAuth?.logged_in ?? 0 }}</span>
                            </div>
                            <div class="mt-1 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-emerald-500"
                                    :style="{ width: authTotal ? `${((pageViewsByAuth?.logged_in || 0) / authTotal) * 100}%` : '0%' }"
                                />
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Guests</span>
                                <span class="font-bold text-slate-900">{{ pageViewsByAuth?.guest ?? 0 }}</span>
                            </div>
                            <div class="mt-1 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-amber-400"
                                    :style="{ width: authTotal ? `${((pageViewsByAuth?.guest || 0) / authTotal) * 100}%` : '0%' }"
                                />
                            </div>
                        </div>
                    </div>
                    <h3 class="mt-8 text-sm font-bold text-slate-800">User registrations (6 mo)</h3>
                    <div class="mt-3 space-y-2">
                        <div v-for="row in userRegistrationTrends || []" :key="row.month" class="flex items-center gap-2 text-sm">
                            <span class="w-16 shrink-0 text-xs text-slate-500">{{ row.month }}</span>
                            <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-indigo-500"
                                    :style="{ width: `${(Number(row.count) / registrationMax) * 100}%` }"
                                />
                            </div>
                            <span class="w-8 text-right text-xs font-semibold text-slate-700">{{ row.count }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Top paths (30d)</h2>
                    <div class="mt-4 space-y-3">
                        <div v-for="row in topPaths || []" :key="row.path" class="space-y-1">
                            <div class="flex justify-between gap-2 text-xs">
                                <span class="truncate font-mono text-slate-700" :title="row.path">{{ row.path }}</span>
                                <span class="shrink-0 font-bold text-indigo-600">{{ row.visits }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-indigo-500" :style="{ width: `${(Number(row.visits) / pathsMax) * 100}%` }" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Top referrers (30d)</h2>
                    <p class="text-xs text-slate-500">Empty referrer counted as direct.</p>
                    <div class="mt-4 space-y-3">
                        <div v-for="(row, idx) in topReferrers || []" :key="idx" class="space-y-1">
                            <div class="flex justify-between gap-2 text-xs">
                                <span class="truncate text-slate-700" :title="row.referrer || ''">{{ row.referrer || '(direct / none)' }}</span>
                                <span class="shrink-0 font-bold text-slate-800">{{ row.count }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-slate-600" :style="{ width: `${(Number(row.count) / referrersMax) * 100}%` }" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Devices (30d)</h2>
                    <div class="mt-4 space-y-3">
                        <div v-for="row in deviceDistribution || []" :key="row.device_type" class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="capitalize text-slate-700">{{ row.device_type }}</span>
                                <span class="font-bold text-slate-900">{{ row.count }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-violet-500" :style="{ width: `${(Number(row.count) / devicesMax) * 100}%` }" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Operating systems (30d)</h2>
                    <div class="mt-4 space-y-3">
                        <div v-for="row in topOs || []" :key="row.os_name" class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-700">{{ row.os_name }}</span>
                                <span class="font-bold text-slate-900">{{ row.count }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-cyan-500" :style="{ width: `${(Number(row.count) / osMax) * 100}%` }" />
                            </div>
                        </div>
                        <p v-if="!(topOs || []).length" class="text-sm text-slate-500">No OS data yet.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Top countries (30d)</h2>
                    <div class="mt-4 space-y-3">
                        <div v-for="row in topCountries || []" :key="row.country_code" class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="font-mono font-semibold text-slate-800">{{ row.country_code }}</span>
                                <span class="font-bold text-slate-900">{{ row.count }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-teal-500" :style="{ width: `${(Number(row.count) / countriesMax) * 100}%` }" />
                            </div>
                        </div>
                        <p v-if="!(topCountries || []).length" class="text-sm text-slate-500">No country data (geo not captured on views).</p>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Browsers (30d)</h2>
                    <div class="mt-4 space-y-2">
                        <div v-for="row in topBrowsers || []" :key="row.browser_name" class="flex items-center justify-between border-b border-slate-100 py-2 text-sm">
                            <span class="text-slate-700">{{ row.browser_name }}</span>
                            <span class="font-bold text-indigo-600">{{ row.count }}</span>
                        </div>
                        <p v-if="!(topBrowsers || []).length" class="text-sm text-slate-500">No browser data yet.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">Role distribution (Spatie)</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span
                        v-for="row in userRoleDistribution || []"
                        :key="row.role"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                    >
                        <span class="font-medium text-slate-800">{{ row.role }}</span>
                        <span class="rounded-lg bg-indigo-600 px-2 py-0.5 text-xs font-bold text-white">{{ row.count }}</span>
                    </span>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
