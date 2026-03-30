<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
    stats: { type: Object, default: () => ({}) },
    donationStats: { type: Object, default: () => ({}) },
    recentNGOs: { type: Array, default: () => [] },
    recentIndividuals: { type: Array, default: () => [] },
    recentUsers: { type: Array, default: () => [] },
    recentCampaigns: { type: Array, default: () => [] },
    monthlyDonations: { type: Array, default: () => [] },
    ngoStatusDistribution: { type: Array, default: () => [] },
    topCampaigns: { type: Array, default: () => [] },
    totalPageViews: { type: Number, default: 0 },
    avgDailyPageViews: { type: Number, default: 0 },
    mapUsers: { type: Array, default: () => [] },
    mapNgos: { type: Array, default: () => [] },
})

const section = ref('overview')
const filterShowUsers = ref(true)
const filterShowNgos = ref(true)
const filterUserType = ref('')
const filterDistrict = ref('')
const filterActiveOnly = ref(false)
const filterVerifiedOnly = ref(false)
const filterNgoStatus = ref('')

let map = null
let markersLayer = null
let geoOutline = null
let karnatakaGeoCache = null

const money = (v) => new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(v || 0)

const esc = (s) =>
    String(s ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/"/g, '&quot;')

const districtOptions = computed(() => {
    const set = new Set()
    props.mapUsers.forEach((u) => {
        if (u.district_name?.trim()) set.add(u.district_name.trim())
    })
    return Array.from(set).sort((a, b) => a.localeCompare(b))
})

const userTypeOptions = computed(() => {
    const set = new Set()
    props.mapUsers.forEach((u) => {
        if (u.user_type) set.add(u.user_type)
    })
    return Array.from(set).sort()
})

const filteredMapUsers = computed(() => {
    return props.mapUsers.filter((u) => {
        if (filterUserType.value && u.user_type !== filterUserType.value) return false
        if (filterDistrict.value && (u.district_name || '').trim() !== filterDistrict.value) return false
        if (filterActiveOnly.value && !u.is_active) return false
        if (filterVerifiedOnly.value && !u.email_verified) return false
        return true
    })
})

const filteredMapNgos = computed(() => {
    return props.mapNgos.filter((n) => {
        if (filterNgoStatus.value && n.verification_status !== filterNgoStatus.value) return false
        return true
    })
})

const mapStats = computed(() => ({
    usersShown: filterShowUsers.value ? filteredMapUsers.value.length : 0,
    ngosShown: filterShowNgos.value ? filteredMapNgos.value.length : 0,
    usersTotal: props.mapUsers.length,
    ngosTotal: props.mapNgos.length,
}))

const renderMapLayers = () => {
    if (!map || !markersLayer) return
    markersLayer.clearLayers()

    if (filterShowUsers.value) {
        filteredMapUsers.value.forEach((u) => {
            const m = L.circleMarker([u.latitude, u.longitude], {
                radius: 6,
                weight: 2,
                color: '#1d4ed8',
                fillColor: '#3b82f6',
                fillOpacity: 0.85,
            })
            const roles = (u.roles || []).join(', ') || '—'
            m.bindPopup(
                `<div class="text-xs space-y-1" style="min-width:180px"><strong class="text-slate-900">${esc(u.name)}</strong><br/><span class="text-slate-600">User</span><br/>${esc(u.email)}<br/><span class="text-slate-500">Type:</span> ${esc(u.user_type)}<br/><span class="text-slate-500">Roles:</span> ${esc(roles)}<br/><span class="text-slate-500">${u.is_active ? 'Active' : 'Inactive'} · ${u.email_verified ? 'Verified' : 'Unverified'}</span>${u.district_name ? `<br/><span class="text-slate-500">${esc(u.district_name)}${u.state_name ? `, ${esc(u.state_name)}` : ''}</span>` : ''}<br/><a class="text-blue-600 font-semibold" href="/admin/individuals/${u.id}">Open profile</a></div>`,
            )
            m.addTo(markersLayer)
        })
    }

    if (filterShowNgos.value) {
        filteredMapNgos.value.forEach((n) => {
            const m = L.circleMarker([n.latitude, n.longitude], {
                radius: 7,
                weight: 2,
                color: '#047857',
                fillColor: '#10b981',
                fillOpacity: 0.9,
            })
            m.bindPopup(
                `<div class="text-xs space-y-1" style="min-width:180px"><strong class="text-slate-900">${esc(n.name)}</strong><br/><span class="text-slate-600">NGO</span><br/>${esc(n.email)}<br/><span class="text-slate-500">Status:</span> ${esc(n.verification_status)}<br/><a class="text-blue-600 font-semibold" href="/admin/ngos/${n.id}">Open NGO</a></div>`,
            )
            m.addTo(markersLayer)
        })
    }

    const bounds = L.latLngBounds([])
    if (geoOutline) {
        bounds.extend(geoOutline.getBounds())
    }
    filteredMapUsers.value.forEach((u) => {
        if (filterShowUsers.value) bounds.extend([u.latitude, u.longitude])
    })
    filteredMapNgos.value.forEach((n) => {
        if (filterShowNgos.value) bounds.extend([n.latitude, n.longitude])
    })
    if (bounds.isValid()) {
        map.fitBounds(bounds, { padding: [40, 40], maxZoom: 9 })
    }
}

const loadKarnatakaOutline = async () => {
    if (!map || geoOutline || karnatakaGeoCache === false) return
    try {
        if (!karnatakaGeoCache) {
            const res = await fetch(new URL('/assets/Themes/karnataka.geojson', window.location.origin).href)
            if (!res.ok) throw new Error('geo')
            karnatakaGeoCache = await res.json()
        }
        geoOutline = L.geoJSON(karnatakaGeoCache, {
            style: { color: '#6366f1', weight: 1, fillColor: '#a5b4fc', fillOpacity: 0.06 },
        })
        geoOutline.addTo(map)
        if (typeof geoOutline.bringToBack === 'function') geoOutline.bringToBack()
    } catch {
        karnatakaGeoCache = false
    }
}

const ensureAdminMap = async () => {
    await nextTick()
    const el = document.getElementById('admin-dashboard-map')
    if (!el || section.value !== 'map') return

    if (!map) {
        map = L.map('admin-dashboard-map', { scrollWheelZoom: true }).setView([15.3173, 75.7139], 7)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20,
        }).addTo(map)
        markersLayer = L.layerGroup().addTo(map)
        await loadKarnatakaOutline()
    } else {
        map.invalidateSize()
    }
    renderMapLayers()
}

watch(section, async (s) => {
    if (s === 'map') {
        await ensureAdminMap()
    }
})

watch(
    [
        filterShowUsers,
        filterShowNgos,
        filterUserType,
        filterDistrict,
        filterActiveOnly,
        filterVerifiedOnly,
        filterNgoStatus,
        filteredMapUsers,
        filteredMapNgos,
    ],
    async () => {
        if (section.value === 'map' && map) {
            await nextTick()
            renderMapLayers()
        }
    },
    { deep: true },
)

onBeforeUnmount(() => {
    if (map) {
        map.remove()
        map = null
        markersLayer = null
        geoOutline = null
    }
})
</script>

<template>
    <AdminLayout title="Admin Dashboard">
        <div class="admin-dash space-y-8 pb-10">
            <!-- Hero -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 px-6 py-8 text-white shadow-xl sm:px-10 sm:py-10">
                <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-500/25 blur-3xl" />
                <div class="pointer-events-none absolute -bottom-16 -left-16 h-56 w-56 rounded-full bg-violet-500/20 blur-3xl" />
                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-indigo-200/90">Super admin</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Platform command center</h1>
                        <p class="mt-2 max-w-xl text-sm text-indigo-100/90">
                            Live KPIs and user &amp; NGO geography. Full traffic analytics live under <strong class="text-white">Analytics</strong> in the sidebar.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            class="rounded-xl px-4 py-2.5 text-sm font-semibold transition"
                            :class="section === 'overview' ? 'bg-white text-slate-900 shadow' : 'bg-white/10 text-white hover:bg-white/15'"
                            @click="section = 'overview'"
                        >
                            Overview
                        </button>
                        <button
                            type="button"
                            class="rounded-xl px-4 py-2.5 text-sm font-semibold transition"
                            :class="section === 'map' ? 'bg-white text-slate-900 shadow' : 'bg-white/10 text-white hover:bg-white/15'"
                            @click="section = 'map'"
                        >
                            Live map
                        </button>
                        <Link
                            href="/admin/analytics"
                            class="rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/15"
                        >
                            Open analytics →
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Overview -->
            <div v-show="section === 'overview'" class="space-y-8">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total users</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900">{{ stats?.total_users ?? 0 }}</p>
                        <p class="mt-1 text-xs text-slate-500">Accounts on platform</p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:border-emerald-200 hover:shadow-md">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">NGOs</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-emerald-700">{{ stats?.total_ngos ?? 0 }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ stats?.verified_ngos ?? 0 }} verified · {{ stats?.pending_ngos ?? 0 }} pending</p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:border-amber-200 hover:shadow-md">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Donations (completed)</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-amber-700">{{ money(stats?.total_donation_amount) }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ stats?.total_donations ?? 0 }} transactions</p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:border-violet-200 hover:shadow-md">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Page views (30d)</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-violet-700">{{ totalPageViews ?? 0 }}</p>
                        <p class="mt-1 text-xs text-slate-500">~{{ avgDailyPageViews ?? 0 }} / day avg</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">Approvals &amp; queues</h2>
                        <p class="mt-1 text-sm text-slate-600">Jump into verification workflows.</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <Link
                                href="/admin/ngos"
                                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                            >
                                NGOs
                                <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs">{{ stats?.pending_ngos ?? 0 }} pending</span>
                            </Link>
                            <Link
                                href="/admin/individuals"
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                            >
                                Individuals
                                <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs">{{ stats?.pending_individuals ?? 0 }} attention</span>
                            </Link>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">Website analytics</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            Traffic, devices, countries, top paths, referrers, and registration trends — all on the dedicated analytics screen.
                        </p>
                        <Link
                            href="/admin/analytics"
                            class="mt-4 inline-flex rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-violet-700"
                        >
                            Go to analytics
                        </Link>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">Recent NGOs</h2>
                        <div class="mt-4 divide-y divide-slate-100">
                            <div v-for="ngo in recentNGOs || []" :key="ngo.id" class="flex items-center justify-between py-3 first:pt-0">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ ngo.name }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ ngo.email }}</p>
                                </div>
                                <div class="ml-3 flex shrink-0 items-center gap-2">
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold uppercase text-amber-800">{{ ngo.verification_status }}</span>
                                    <Link :href="`/admin/ngos/${ngo.id}`" class="text-xs font-semibold text-indigo-600 hover:underline">View</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">Recent individuals</h2>
                        <div class="mt-4 divide-y divide-slate-100">
                            <div v-for="u in recentIndividuals || []" :key="u.id" class="flex items-center justify-between py-3 first:pt-0">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ u.name }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ u.email }}</p>
                                </div>
                                <div class="ml-3 flex shrink-0 items-center gap-2">
                                    <span
                                        class="rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase"
                                        :class="u.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'"
                                    >
                                        {{ u.is_active ? 'active' : 'inactive' }}
                                    </span>
                                    <Link :href="`/admin/individuals/${u.id}`" class="text-xs font-semibold text-indigo-600 hover:underline">View</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">Top campaigns (by donations)</h2>
                        <div class="mt-4 space-y-3">
                            <div v-for="c in topCampaigns || []" :key="c.id" class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-3 py-2">
                                <div class="min-w-0 pr-2">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ c.title }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ c.ngo?.name }}</p>
                                </div>
                                <span class="shrink-0 text-xs font-bold text-indigo-600">{{ c.donations_count }} gifts</span>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">NGO verification mix</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                v-for="row in ngoStatusDistribution || []"
                                :key="row.status"
                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm"
                            >
                                <span class="font-medium capitalize text-slate-700">{{ row.status }}</span>
                                <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-bold text-indigo-800">{{ row.count }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div v-show="section === 'map'" class="space-y-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Users &amp; NGOs on the map</h2>
                            <p class="mt-1 text-sm text-slate-600">
                                Blue dots = users with stored coordinates · Green = NGO headquarters. Karnataka outline loads when available.
                            </p>
                            <p class="mt-2 text-xs text-slate-500">
                                Showing <strong>{{ mapStats.usersShown }}</strong> users and <strong>{{ mapStats.ngosShown }}</strong> NGOs
                                (dataset: {{ mapStats.usersTotal }} users / {{ mapStats.ngosTotal }} NGOs with lat/lng).
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3 text-xs">
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 font-medium text-slate-700">
                                <span class="h-2.5 w-2.5 rounded-full bg-blue-500" /> Users
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 font-medium text-slate-700">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500" /> NGOs
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-3 border-t border-slate-100 pt-5">
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-800">
                            <input v-model="filterShowUsers" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            Users
                        </label>
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-800">
                            <input v-model="filterShowNgos" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            NGOs
                        </label>
                        <select v-model="filterUserType" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800">
                            <option value="">All user types</option>
                            <option v-for="t in userTypeOptions" :key="t" :value="t">{{ t }}</option>
                        </select>
                        <select v-model="filterDistrict" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800">
                            <option value="">All districts (users)</option>
                            <option v-for="d in districtOptions" :key="d" :value="d">{{ d }}</option>
                        </select>
                        <select v-model="filterNgoStatus" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800">
                            <option value="">All NGO statuses</option>
                            <option value="pending">pending</option>
                            <option value="verified">verified</option>
                            <option value="rejected">rejected</option>
                        </select>
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-800">
                            <input v-model="filterActiveOnly" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            Active users only
                        </label>
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-800">
                            <input v-model="filterVerifiedOnly" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            Email verified users
                        </label>
                    </div>
                </div>

                <div
                    id="admin-dashboard-map"
                    class="admin-dash-map h-[min(70vh,520px)] min-h-[360px] w-full overflow-hidden rounded-2xl border border-slate-800/20 shadow-lg ring-1 ring-slate-200"
                />
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.admin-dash-map {
    z-index: 0;
}
.admin-dash :deep(.leaflet-container) {
    font-family: inherit;
    border-radius: 1rem;
}
</style>
