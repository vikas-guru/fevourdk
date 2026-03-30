<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, nextTick, onBeforeUnmount, onMounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
    profile: { type: Object, required: true },
    entrySource: { type: String, default: 'users' },
    canApproveIndividual: { type: Boolean, default: false },
    activityLog: { type: Array, default: () => [] },
    activitySummary: { type: Object, default: () => ({}) },
})

const page = usePage()

const selfId = computed(() => page.props.auth?.user?.id)
const isSelf = computed(() => selfId.value && Number(selfId.value) === Number(props.profile?.id))

const money = (v) => new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(v || 0)

const fmtDate = (d) => (d ? new Date(d).toLocaleString() : '—')

let map = null

onMounted(async () => {
    const lat = props.profile?.latitude
    const lng = props.profile?.longitude
    if (lat == null || lng == null || Number(lat) === 0 || Number(lng) === 0) return

    await nextTick()
    const el = document.getElementById('admin-user-loc-map')
    if (!el) return

    map = L.map('admin-user-loc-map').setView([Number(lat), Number(lng)], 11)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 20,
    }).addTo(map)
    L.circleMarker([Number(lat), Number(lng)], {
        radius: 11,
        weight: 2,
        color: '#1d4ed8',
        fillColor: '#3b82f6',
        fillOpacity: 0.9,
    })
        .addTo(map)
        .bindPopup('Coordinates from user profile (registration / location capture).')
})

onBeforeUnmount(() => {
    if (map) {
        map.remove()
        map = null
    }
})

const toggleBlock = () => {
    if (isSelf.value) return
    const msg = props.profile.is_active
        ? 'Block this user? They will not be able to sign in.'
        : 'Unblock this user? They can sign in again.'
    if (!confirm(msg)) return
    router.post(`/admin/users/${props.profile.id}/toggle-block`, {}, { preserveScroll: true })
}

const approveIndividual = () => {
    router.post(`/admin/individuals/${props.profile.id}/approve`, {}, { preserveScroll: true })
}

const donorDonations = computed(() => props.profile?.donor?.donations ?? [])
</script>

<template>
    <AdminLayout :title="`User · ${profile.name}`">
        <div class="space-y-6 pb-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <Link href="/admin/users" class="text-sm font-medium text-indigo-600 hover:underline">← Users</Link>
                        <span v-if="entrySource === 'individuals'" class="text-slate-300">|</span>
                        <Link v-if="entrySource === 'individuals'" href="/admin/individuals" class="text-sm font-medium text-indigo-600 hover:underline">Individuals</Link>
                    </div>
                    <h1 class="mt-2 text-2xl font-bold text-slate-900">{{ profile.name }}</h1>
                    <p class="text-sm text-slate-600">{{ profile.email }} · {{ profile.phone || 'No phone' }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span
                            class="rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase"
                            :class="profile.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'"
                        >
                            {{ profile.is_active ? 'Active' : 'Blocked' }}
                        </span>
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">{{ profile.user_type || '—' }}</span>
                        <span v-if="profile.email_verified_at" class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">Email verified</span>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <Link
                        :href="`/admin/users/${profile.id}/edit`"
                        class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50"
                    >
                        Edit user
                    </Link>
                    <button
                        v-if="!isSelf"
                        type="button"
                        class="rounded-xl px-4 py-2 text-sm font-semibold text-white shadow-sm"
                        :class="profile.is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700'"
                        @click="toggleBlock"
                    >
                        {{ profile.is_active ? 'Block account' : 'Unblock account' }}
                    </button>
                    <button
                        v-if="canApproveIndividual && !profile.is_active"
                        type="button"
                        class="rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700"
                        @click="approveIndividual"
                    >
                        Approve individual
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
                    <h2 class="text-lg font-bold text-slate-900">Profile &amp; device snapshot</h2>
                    <dl class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-500">Roles</dt>
                            <dd class="mt-0.5 text-slate-800">{{ (profile.roles || []).map((r) => r.name).join(', ') || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-500">Last login</dt>
                            <dd class="mt-0.5 text-slate-800">{{ fmtDate(profile.last_login_at) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-500">Location (text)</dt>
                            <dd class="mt-0.5 text-slate-800">
                                {{ [profile.address, profile.city_name, profile.district_name, profile.state_name, profile.postal_code].filter(Boolean).join(', ') || '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-500">Coordinates</dt>
                            <dd class="mt-0.5 font-mono text-slate-800">
                                {{ profile.latitude && profile.longitude ? `${profile.latitude}, ${profile.longitude}` : '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-500">Device (last captured)</dt>
                            <dd class="mt-0.5 text-slate-800">{{ profile.device_type || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-500">Browser / OS</dt>
                            <dd class="mt-0.5 text-slate-800">{{ profile.browser_name || '—' }} · {{ profile.os_name || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-500">IP (last captured)</dt>
                            <dd class="mt-0.5 font-mono text-slate-800">{{ profile.ip_address || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-500">NGO link</dt>
                            <dd class="mt-0.5">
                                <Link v-if="profile.ngo_id" :href="`/admin/ngos/${profile.ngo_id}`" class="font-semibold text-indigo-600 hover:underline">Open NGO #{{ profile.ngo_id }}</Link>
                                <span v-else class="text-slate-500">—</span>
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Activity (analytics)</h2>
                    <p class="mt-1 text-xs text-slate-500">Where this account uses the site (logged page views).</p>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li class="flex justify-between border-b border-slate-100 py-1">
                            <span class="text-slate-600">Last 7 days</span>
                            <span class="font-bold text-slate-900">{{ activitySummary?.views_7d ?? 0 }}</span>
                        </li>
                        <li class="flex justify-between border-b border-slate-100 py-1">
                            <span class="text-slate-600">Last 30 days</span>
                            <span class="font-bold text-slate-900">{{ activitySummary?.views_30d ?? 0 }}</span>
                        </li>
                        <li class="flex justify-between py-1">
                            <span class="text-slate-600">Last seen</span>
                            <span class="font-semibold text-slate-800">{{ fmtDate(activitySummary?.last_seen) }}</span>
                        </li>
                    </ul>
                    <div v-if="(activitySummary?.top_paths_30d || []).length" class="mt-4">
                        <p class="text-xs font-bold uppercase text-slate-500">Top paths (30d)</p>
                        <ul class="mt-2 space-y-1 text-xs">
                            <li v-for="row in activitySummary.top_paths_30d" :key="row.path" class="flex justify-between gap-2">
                                <span class="truncate font-mono text-slate-700">{{ row.path }}</span>
                                <span class="shrink-0 font-semibold text-indigo-600">{{ row.count }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div v-if="profile.latitude && profile.longitude && Number(profile.latitude) !== 0 && Number(profile.longitude) !== 0" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">Map · profile coordinates</h2>
                <p class="text-xs text-slate-500">Same location used on the admin dashboard map for this user.</p>
                <div id="admin-user-loc-map" class="mt-3 h-64 w-full overflow-hidden rounded-xl ring-1 ring-slate-200" />
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-3">
                    <h2 class="text-lg font-bold text-slate-900">Recent page views (90d)</h2>
                    <p class="text-xs text-slate-500">Path, device, geo from analytics pipeline when available.</p>
                </div>
                <div class="max-h-[420px] overflow-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                        <thead class="sticky top-0 bg-slate-50">
                            <tr>
                                <th class="px-3 py-2 font-semibold text-slate-600">When</th>
                                <th class="px-3 py-2 font-semibold text-slate-600">Path</th>
                                <th class="px-3 py-2 font-semibold text-slate-600">Device</th>
                                <th class="px-3 py-2 font-semibold text-slate-600">Location</th>
                                <th class="px-3 py-2 font-semibold text-slate-600">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="row in activityLog || []" :key="row.id" class="hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-3 py-2 text-slate-700">{{ fmtDate(row.viewed_at) }}</td>
                                <td class="max-w-[200px] truncate px-3 py-2 font-mono text-slate-800" :title="row.path">{{ row.path }}</td>
                                <td class="px-3 py-2 text-slate-600">{{ row.device_type || '—' }} · {{ row.browser_name || '—' }}</td>
                                <td class="px-3 py-2 text-slate-600">{{ row.city || row.region || '—' }} {{ row.country_code ? `(${row.country_code})` : '' }}</td>
                                <td class="px-3 py-2 font-mono text-slate-500">{{ row.ip_address || '—' }}</td>
                            </tr>
                            <tr v-if="!(activityLog || []).length">
                                <td colspan="5" class="px-3 py-8 text-center text-sm text-slate-500">No recorded views for this user yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="(donorDonations || []).length" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">Donations (via donor profile)</h2>
                <ul class="mt-3 divide-y divide-slate-100 text-sm">
                    <li v-for="d in donorDonations" :key="d.id" class="flex flex-wrap items-center justify-between gap-2 py-2">
                        <span class="font-medium text-slate-800">{{ money(d.amount) }}</span>
                        <span class="text-slate-600">{{ d.campaign?.title || 'Campaign' }}</span>
                        <span class="text-xs text-slate-500">{{ fmtDate(d.created_at || d.donated_at) }}</span>
                    </li>
                </ul>
            </div>

            <div v-if="profile.donor" class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="text-sm font-bold text-slate-800">Donor summary</h2>
                <p class="mt-1 text-sm text-slate-600">Total given: {{ money(profile.donor.total_donated) }} · Count: {{ profile.donor.donation_count ?? 0 }}</p>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
#admin-user-loc-map {
    z-index: 0;
}
</style>
