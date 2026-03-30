<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'

const props = defineProps({
    ngo: Object,
    tasks: Array,
    activeSessions: Array,
    isNgoAdmin: Boolean,
    staffUsers: Array,
})

const page = usePage()
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

const mapEl = ref(null)
let map = null
let markersLayer = null
let routeLayer = null

const selectedSessionId = ref(null)
const trailPoints = ref([])
const loadingTrail = ref(false)

const taskForm = useForm({
    title: '',
    description: '',
    assignee_id: '',
    priority: 'normal',
    due_at: '',
})

const submitTask = () => {
    taskForm.post('/ngo/field/tasks', { preserveScroll: true, onSuccess: () => taskForm.reset() })
}

const completeTask = (taskId) => {
    if (!confirm('Mark this task completed?')) return
    router.patch(`/ngo/field/tasks/${taskId}`, { status: 'completed' }, { preserveScroll: true })
}

const cancelTask = (taskId) => {
    if (!confirm('Cancel this task?')) return
    router.patch(`/ngo/field/tasks/${taskId}`, { status: 'cancelled' }, { preserveScroll: true })
}

async function loadTrail(sessionId) {
    loadingTrail.value = true
    selectedSessionId.value = sessionId
    try {
        const { data } = await axios.get(`/ngo/field/sessions/${sessionId}/trail`, {
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
        })
        trailPoints.value = data.points || []
        drawTrail()
    } catch (e) {
        console.error(e)
        trailPoints.value = []
    } finally {
        loadingTrail.value = false
    }
}

function drawTrail() {
    if (!map || !routeLayer) return
    routeLayer.clearLayers()
    const pts = trailPoints.value
    if (pts.length < 2) return
    const latlngs = pts.map((p) => [p.lat, p.lng])
    L.polyline(latlngs, { color: '#2563eb', weight: 4, opacity: 0.85 }).addTo(routeLayer)
    map.fitBounds(latlngs, { padding: [40, 40], maxZoom: 15 })
}

function initMap() {
    if (!mapEl.value) return
    map = L.map(mapEl.value).setView([15.3173, 75.7139], 7)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OSM &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 19,
    }).addTo(map)
    markersLayer = L.layerGroup().addTo(map)
    routeLayer = L.layerGroup().addTo(map)
    refreshMarkers()
}

function refreshMarkers() {
    if (!map || !markersLayer) return
    markersLayer.clearLayers()
    for (const s of props.activeSessions || []) {
        const lp = s.last_point
        if (!lp) continue
        const m = L.circleMarker([lp.lat, lp.lng], {
            radius: 10,
            color: '#1d4ed8',
            fillColor: '#3b82f6',
            fillOpacity: 0.9,
        })
        m.bindPopup(`<strong>${s.user?.name || 'Staff'}</strong><br/>${s.task?.title || 'Open field session'}`)
        m.on('click', () => loadTrail(s.id))
        m.addTo(markersLayer)
    }
}

onMounted(async () => {
    await nextTick()
    initMap()
})

watch(
    () => props.activeSessions,
    () => {
        refreshMarkers()
        if (selectedSessionId.value) {
            loadTrail(selectedSessionId.value)
        }
    },
    { deep: true }
)

onBeforeUnmount(() => {
    map?.remove()
    map = null
})
</script>

<template>
    <AppLayout title="Field operations">
        <NgoWorkspaceShell :ngo="ngo" current-key="field">
            <div class="mx-auto max-w-6xl space-y-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Field operations</h1>
                        <p class="text-sm text-slate-600">Live staff locations, routes, and task assignment.</p>
                    </div>
                    <Link
                        href="/ngo/field/app"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800"
                    >
                        Open field tracker (mobile / PWA)
                    </Link>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-4 py-3">
                            <h2 class="font-semibold text-slate-900">Live map</h2>
                            <p class="text-xs text-slate-500">Click a marker or row to load the GPS trail (where recorded).</p>
                        </div>
                        <div ref="mapEl" class="h-80 w-full lg:h-[420px]" />
                        <p v-if="loadingTrail" class="px-4 py-2 text-xs text-slate-500">Loading route…</p>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <h2 class="font-semibold text-slate-900">Active sessions</h2>
                            <ul class="mt-3 divide-y divide-slate-100 text-sm">
                                <li
                                    v-for="s in activeSessions"
                                    :key="s.id"
                                    class="flex cursor-pointer items-center justify-between py-2 hover:bg-slate-50"
                                    @click="loadTrail(s.id)"
                                >
                                    <div>
                                        <p class="font-medium text-slate-800">{{ s.user?.name }}</p>
                                        <p class="text-xs text-slate-500">{{ s.task?.title || 'No linked task' }}</p>
                                    </div>
                                    <span class="text-xs text-blue-600">Route →</span>
                                </li>
                                <li v-if="!(activeSessions || []).length" class="py-6 text-center text-slate-500">No active field sessions.</li>
                            </ul>
                        </div>

                        <div v-if="isNgoAdmin" class="rounded-2xl border border-indigo-200 bg-indigo-50/40 p-4 shadow-sm">
                            <h2 class="font-semibold text-indigo-950">Assign field task</h2>
                            <form class="mt-3 space-y-2" @submit.prevent="submitTask">
                                <input
                                    v-model="taskForm.title"
                                    required
                                    placeholder="Title (e.g. Village survey — Bagalkot)"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                                >
                                <textarea
                                    v-model="taskForm.description"
                                    rows="2"
                                    placeholder="Instructions (optional)"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"
                                />
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    <select v-model="taskForm.assignee_id" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                        <option value="">Anyone / open</option>
                                        <option v-for="u in staffUsers" :key="u.id" :value="u.id">{{ u.name }} ({{ u.email }})</option>
                                    </select>
                                    <select v-model="taskForm.priority" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                        <option value="low">Low</option>
                                        <option value="normal">Normal</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                                <input v-model="taskForm.due_at" type="datetime-local" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                <button
                                    type="submit"
                                    :disabled="taskForm.processing"
                                    class="w-full rounded-xl bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                                >
                                    {{ taskForm.processing ? 'Saving…' : 'Create task' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-4 py-3">
                        <h2 class="font-semibold text-slate-900">Tasks</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-600">
                                <tr>
                                    <th class="px-4 py-2">Title</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Assignee</th>
                                    <th class="px-4 py-2">Due</th>
                                    <th v-if="isNgoAdmin" class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="t in tasks" :key="t.id" class="border-t border-slate-100">
                                    <td class="px-4 py-2">
                                        <p class="font-medium text-slate-800">{{ t.title }}</p>
                                        <p v-if="t.description" class="text-xs text-slate-500 line-clamp-2">{{ t.description }}</p>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">{{ t.status }}</span>
                                    </td>
                                    <td class="px-4 py-2 text-slate-600">{{ t.assignee?.name || '—' }}</td>
                                    <td class="px-4 py-2 text-slate-600">{{ t.due_at ? new Date(t.due_at).toLocaleString() : '—' }}</td>
                                    <td v-if="isNgoAdmin" class="space-x-2 px-4 py-2 whitespace-nowrap">
                                        <button
                                            v-if="t.status !== 'completed' && t.status !== 'cancelled'"
                                            type="button"
                                            class="text-xs font-semibold text-emerald-600 hover:underline"
                                            @click="completeTask(t.id)"
                                        >
                                            Complete
                                        </button>
                                        <button
                                            v-if="t.status !== 'cancelled'"
                                            type="button"
                                            class="text-xs font-semibold text-red-600 hover:underline"
                                            @click="cancelTask(t.id)"
                                        >
                                            Cancel
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="!(tasks || []).length">
                                    <td :colspan="isNgoAdmin ? 5 : 4" class="px-4 py-8 text-center text-slate-500">No tasks yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <p v-if="page.props.flash?.success" class="text-sm font-medium text-emerald-600">{{ page.props.flash.success }}</p>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<style scoped>
:deep(.leaflet-container) {
    z-index: 0;
    font-family: inherit;
}
</style>
