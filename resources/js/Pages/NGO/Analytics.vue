<template>
    <AppLayout title="Website analytics — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="analytics">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Website analytics</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Visits to your public listing and slug microsite (anonymous GET page views). IPs are not shown; regions come from edge headers when available.
                    </p>
                </div>
                <div v-if="analytics.tracked_paths_hint?.listing" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-600">
                    <span class="font-semibold text-slate-800">Tracked paths:</span>
                    <code class="ml-1 rounded bg-slate-100 px-1">{{ analytics.tracked_paths_hint.listing }}</code>,
                    <code class="rounded bg-slate-100 px-1">{{ analytics.tracked_paths_hint.microsite }}</code>
                </div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Page views</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ fmt(analytics.summary.total_views) }}</p>
                    <p class="text-xs text-slate-500">All time</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Sessions</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ fmt(analytics.summary.unique_sessions) }}</p>
                    <p class="text-xs text-slate-500">Distinct session id</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Visitors (IP)</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ fmt(analytics.summary.unique_visitors_ip) }}</p>
                    <p class="text-xs text-slate-500">Distinct addresses</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">7 days</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-700">{{ fmt(analytics.summary.views_last_7_days) }}</p>
                    <p class="text-xs text-slate-500">Recent views</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">30 days</p>
                    <p class="mt-1 text-2xl font-bold text-blue-700">{{ fmt(analytics.summary.views_last_30_days) }}</p>
                    <p class="text-xs text-slate-500">Recent views</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Supporters</p>
                    <p class="mt-1 text-2xl font-bold text-violet-700">{{ fmt(analytics.supporters.completed_donors) }}</p>
                    <p class="text-xs text-slate-500">{{ analytics.supporters.label }}</p>
                </div>
            </div>

            <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50/90 p-4 text-sm text-amber-950">
                <p class="font-semibold">Subscribers</p>
                <p class="mt-1 text-amber-900/90">
                    {{ analytics.subscribers.note }}
                    <span v-if="analytics.subscribers.count != null" class="ml-1 font-bold">{{ analytics.subscribers.count }}</span>
                </p>
            </div>

            <div class="mt-8 grid gap-6 xl:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Traffic (last 30 days)</h2>
                    <p class="text-xs text-slate-500">Bar height = page views per day</p>
                    <div class="mt-4 flex h-44 items-end gap-px sm:gap-0.5">
                        <div
                            v-for="d in analytics.series30"
                            :key="d.date"
                            class="group relative flex flex-1 flex-col justify-end"
                        >
                            <div
                                class="w-full min-w-[2px] rounded-t bg-gradient-to-t from-blue-600 to-indigo-400 transition hover:from-blue-700 hover:to-indigo-500"
                                :style="{ height: barHeight(d.views) + '%' }"
                            />
                            <div class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-1 hidden -translate-x-1/2 rounded bg-slate-900 px-2 py-1 text-[10px] text-white group-hover:block">
                                {{ d.date }}: {{ d.views }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-between text-[10px] text-slate-400">
                        <span>{{ analytics.series30[0]?.date }}</span>
                        <span>{{ analytics.series30[analytics.series30.length - 1]?.date }}</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">World map (by country)</h2>
                    <p class="text-xs text-slate-500">Circle size ≈ volume of views. Only countries with known coordinates are plotted.</p>
                    <div
                        ref="mapContainer"
                        class="mt-3 h-80 w-full overflow-hidden rounded-xl border border-slate-200 bg-slate-100"
                    />
                    <p v-if="!analytics.geo_points?.length" class="mt-2 text-center text-sm text-slate-500">No geo-tagged views yet (country headers often come from Cloudflare or your reverse proxy).</p>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900">Top pages</h2>
                    <ul class="mt-3 divide-y divide-slate-100 text-sm">
                        <li v-for="row in analytics.top_paths" :key="row.path" class="flex justify-between gap-2 py-2">
                            <code class="truncate text-xs text-slate-700">{{ row.path }}</code>
                            <span class="shrink-0 font-semibold text-slate-900">{{ row.views }}</span>
                        </li>
                        <li v-if="!analytics.top_paths?.length" class="py-6 text-center text-slate-500">No data yet.</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900">Device &amp; browser</h2>
                    <div class="mt-3 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold text-slate-500">Device</p>
                            <ul class="mt-2 space-y-1 text-sm">
                                <li v-for="row in analytics.by_device" :key="row.device_type" class="flex justify-between gap-2">
                                    <span class="truncate capitalize">{{ row.device_type }}</span>
                                    <span class="font-medium text-slate-800">{{ row.views }}</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500">Browser</p>
                            <ul class="mt-2 space-y-1 text-sm">
                                <li v-for="row in analytics.by_browser" :key="row.browser_name" class="flex justify-between gap-2">
                                    <span class="truncate">{{ row.browser_name }}</span>
                                    <span class="font-medium text-slate-800">{{ row.views }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900">Region (top)</h2>
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-xs uppercase text-slate-500">
                                    <th class="pb-2 pr-2">Country</th>
                                    <th class="pb-2 pr-2">Region</th>
                                    <th class="pb-2 text-right">Views</th>
                                    <th class="pb-2 text-right">Sessions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, i) in analytics.by_region" :key="i" class="border-b border-slate-50">
                                    <td class="py-2 pr-2 font-medium">{{ row.country_code }}</td>
                                    <td class="py-2 pr-2 text-slate-600">{{ row.region }}</td>
                                    <td class="py-2 text-right">{{ row.views }}</td>
                                    <td class="py-2 text-right text-slate-500">{{ row.sessions }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900">City (top)</h2>
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-xs uppercase text-slate-500">
                                    <th class="pb-2 pr-2">City</th>
                                    <th class="pb-2 pr-2">Region</th>
                                    <th class="pb-2 pr-2">Country</th>
                                    <th class="pb-2 text-right">Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, i) in analytics.by_city" :key="i" class="border-b border-slate-50">
                                    <td class="py-2 pr-2 font-medium">{{ row.city }}</td>
                                    <td class="py-2 pr-2 text-slate-600">{{ row.region }}</td>
                                    <td class="py-2 pr-2">{{ row.country_code }}</td>
                                    <td class="py-2 text-right">{{ row.views }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-bold text-slate-900">Recent visits</h2>
                <p class="text-xs text-slate-500">Latest hits — no raw IPs stored in this view.</p>
                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs uppercase text-slate-500">
                                <th class="pb-2 pr-2">Time</th>
                                <th class="pb-2 pr-2">Path</th>
                                <th class="pb-2 pr-2">Location</th>
                                <th class="pb-2">Device</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, i) in analytics.recent" :key="i" class="border-b border-slate-50">
                                <td class="py-2 pr-2 whitespace-nowrap text-slate-600">{{ formatTime(row.viewed_at) }}</td>
                                <td class="py-2 pr-2"><code class="text-xs">{{ row.path }}</code></td>
                                <td class="py-2 pr-2 text-slate-600">{{ locationLabel(row) }}</td>
                                <td class="py-2 capitalize">{{ row.device_type }}</td>
                            </tr>
                            <tr v-if="!analytics.recent?.length">
                                <td colspan="4" class="py-8 text-center text-slate-500">No visits recorded yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'

const props = defineProps({
    ngo: { type: Object, required: true },
    analytics: { type: Object, required: true },
})

const mapContainer = ref(null)
let mapInstance = null
let markersLayer = null

const maxSeries = computed(() => {
    const s = props.analytics.series30 || []
    const m = Math.max(0, ...s.map((d) => d.views))
    return m > 0 ? m : 1
})

function barHeight(views) {
    return Math.max(2, (views / maxSeries.value) * 100)
}

function fmt(n) {
    return new Intl.NumberFormat('en-IN').format(n ?? 0)
}

function formatTime(iso) {
    try {
        return new Date(iso).toLocaleString('en-IN', {
            day: 'numeric',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit',
        })
    } catch {
        return iso
    }
}

function locationLabel(row) {
    const parts = [row.city, row.region, row.country_code].filter(Boolean)
    return parts.length ? parts.join(' · ') : '—'
}

function hexToRgb(hex) {
    const h = String(hex || '').replace('#', '')
    if (h.length === 3) {
        const r = parseInt(h[0] + h[0], 16)
        const g = parseInt(h[1] + h[1], 16)
        const b = parseInt(h[2] + h[2], 16)
        return { r, g, b }
    }
    if (h.length === 6) {
        return {
            r: parseInt(h.slice(0, 2), 16),
            g: parseInt(h.slice(2, 4), 16),
            b: parseInt(h.slice(4, 6), 16),
        }
    }
    return { r: 37, g: 99, b: 235 }
}

function initMap() {
    const el = mapContainer.value
    if (!el) {
        return
    }
    if (mapInstance) {
        mapInstance.remove()
        mapInstance = null
        markersLayer = null
    }

    const points = props.analytics.geo_points || []
    const { r, g, b } = hexToRgb(props.ngo.theme_color)
    const color = `rgb(${r},${g},${b})`

    mapInstance = L.map(el, { scrollWheelZoom: true }).setView([22, 77], 3)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
    }).addTo(mapInstance)

    markersLayer = L.layerGroup().addTo(mapInstance)

    const maxV = Math.max(1, props.analytics.max_country_views || 1)
    for (const p of points) {
        const t = p.views / maxV
        const radius = 5 + Math.round(Math.sqrt(t) * 22)
        const m = L.circleMarker([p.lat, p.lng], {
            radius,
            color,
            weight: 2,
            fillColor: color,
            fillOpacity: 0.55,
        })
        m.bindPopup(
            `<div style="font-family:system-ui,sans-serif;font-size:13px"><strong>${p.code}</strong><br>${p.views} views<br>${p.sessions} est. sessions</div>`,
        )
        m.addTo(markersLayer)
    }

    if (points.length) {
        const bounds = L.latLngBounds(points.map((p) => [p.lat, p.lng]))
        mapInstance.fitBounds(bounds.pad(0.2))
    }
}

onMounted(() => {
    nextTick(() => initMap())
})

watch(
    () => props.analytics.geo_points,
    () => nextTick(() => initMap()),
    { deep: true },
)

onUnmounted(() => {
    if (mapInstance) {
        mapInstance.remove()
        mapInstance = null
    }
})
</script>
