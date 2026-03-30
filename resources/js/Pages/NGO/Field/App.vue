<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    ngo: Object,
    myTasks: Array,
    activeSession: { type: Object, default: null },
})

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

const sessionId = ref(props.activeSession?.id ?? null)
const selectedTaskId = ref(props.activeSession?.field_task_id ?? '')
const tracking = ref(!!props.activeSession)
const errorMsg = ref('')
const busy = ref(false)

const queue = ref([])
const lastFlush = ref(Date.now())
const runningDistanceM = ref(0)
let lastLat = null
let lastLng = null
let watchId = null

const currentSpeedKmh = ref(null)
const maxSpeedKmh = ref(0)

function haversineM(lat1, lon1, lat2, lon2) {
    const R = 6371000
    const toR = (d) => (d * Math.PI) / 180
    const dLat = toR(lat2 - lat1)
    const dLon = toR(lon2 - lon1)
    const a =
        Math.sin(dLat / 2) ** 2 +
        Math.cos(toR(lat1)) * Math.cos(toR(lat2)) * Math.sin(dLon / 2) ** 2
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))
}

const distanceKm = computed(() => (runningDistanceM.value / 1000).toFixed(2))

async function flushQueue() {
    if (!sessionId.value || queue.value.length === 0) return
    const batch = queue.value.splice(0, queue.value.length)
    try {
        await axios.post(
            `/ngo/field/sessions/${sessionId.value}/points`,
            { points: batch },
            {
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                },
            }
        )
    } catch (e) {
        console.error(e)
        queue.value.unshift(...batch)
    }
    lastFlush.value = Date.now()
}

function enqueuePosition(pos) {
    const { latitude, longitude, accuracy, speed, heading, altitude } = pos.coords
    const speedMs = speed != null && !Number.isNaN(speed) ? speed : null
    if (speedMs != null) {
        const kmh = speedMs * 3.6
        currentSpeedKmh.value = Math.round(kmh * 10) / 10
        if (kmh > maxSpeedKmh.value) {
            maxSpeedKmh.value = Math.round(kmh * 10) / 10
        }
    } else {
        currentSpeedKmh.value = null
    }

    if (lastLat != null && lastLng != null) {
        runningDistanceM.value += haversineM(lastLat, lastLng, latitude, longitude)
    }
    lastLat = latitude
    lastLng = longitude

    queue.value.push({
        latitude,
        longitude,
        recorded_at: new Date(pos.timestamp || Date.now()).toISOString(),
        accuracy_m: accuracy != null ? accuracy : null,
        speed_ms: speedMs,
        heading: heading != null && !Number.isNaN(heading) ? heading : null,
        altitude_m: altitude != null && !Number.isNaN(altitude) ? altitude : null,
    })

    if (queue.value.length >= 8 || Date.now() - lastFlush.value > 15000) {
        flushQueue()
    }
}

function startWatch() {
    if (!navigator.geolocation) {
        errorMsg.value = 'Geolocation is not available in this browser.'
        return
    }
    watchId = navigator.geolocation.watchPosition(
        enqueuePosition,
        (err) => {
            errorMsg.value = err.message || 'Location permission denied.'
        },
        { enableHighAccuracy: true, maximumAge: 5000, timeout: 20000 }
    )
}

function stopWatch() {
    if (watchId != null) {
        navigator.geolocation.clearWatch(watchId)
        watchId = null
    }
}

async function startSession() {
    errorMsg.value = ''
    busy.value = true
    try {
        const payload = {}
        if (selectedTaskId.value) {
            payload.field_task_id = Number(selectedTaskId.value)
        }
        const { data } = await axios.post('/ngo/field/sessions', payload, {
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf(),
            },
        })
        sessionId.value = data.session.id
        tracking.value = true
        lastLat = null
        lastLng = null
        runningDistanceM.value = 0
        maxSpeedKmh.value = 0
        startWatch()
    } catch (e) {
        errorMsg.value = e.response?.data?.message || 'Could not start session.'
    } finally {
        busy.value = false
    }
}

async function endSession() {
    if (!sessionId.value) return
    errorMsg.value = ''
    busy.value = true
    stopWatch()
    await flushQueue()
    try {
        await axios.post(
            `/ngo/field/sessions/${sessionId.value}/end`,
            {},
            {
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                },
            }
        )
        sessionId.value = null
        tracking.value = false
        lastLat = null
        lastLng = null
        runningDistanceM.value = 0
        currentSpeedKmh.value = null
        maxSpeedKmh.value = 0
        window.location.reload()
    } catch (e) {
        errorMsg.value = e.response?.data?.message || 'Could not end session.'
        tracking.value = true
        startWatch()
    } finally {
        busy.value = false
    }
}

onMounted(() => {
    if (props.activeSession?.id) {
        sessionId.value = props.activeSession.id
        tracking.value = true
        startWatch()
    }
})

onBeforeUnmount(() => {
    stopWatch()
    flushQueue()
})

watch(tracking, (on) => {
    if (!on) {
        stopWatch()
    }
})
</script>

<template>
    <AppLayout title="Field tracker">
        <div class="mx-auto min-h-[calc(100vh-6rem)] max-w-lg px-3 pb-10 pt-4">
            <div class="mb-4 flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Field PWA</p>
                    <h1 class="text-xl font-bold text-slate-900">{{ ngo.name }}</h1>
                </div>
                <Link href="/ngo/field" class="text-sm font-semibold text-blue-600 hover:underline">Command</Link>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-gradient-to-b from-slate-900 to-slate-800 p-5 text-white shadow-xl">
                <p class="text-center text-xs font-medium uppercase tracking-widest text-slate-400">Speed (GPS)</p>
                <p class="mt-1 text-center text-5xl font-black tabular-nums">
                    {{ currentSpeedKmh != null ? currentSpeedKmh : '—' }}
                    <span class="text-2xl font-bold text-slate-400">km/h</span>
                </p>
                <p class="mt-2 text-center text-xs text-slate-400">Peak this session: {{ maxSpeedKmh }} km/h</p>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-slate-500">Distance (approx.)</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ distanceKm }} km</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase text-slate-500">Status</p>
                    <p class="mt-1 text-lg font-bold" :class="tracking ? 'text-emerald-600' : 'text-slate-600'">
                        {{ tracking ? 'Tracking' : 'Stopped' }}
                    </p>
                </div>
            </div>

            <p v-if="errorMsg" class="mt-3 rounded-xl bg-red-50 px-3 py-2 text-sm text-red-800">{{ errorMsg }}</p>

            <div v-if="!tracking" class="mt-5 space-y-3">
                <label class="block text-sm font-medium text-slate-700">Link to task (optional)</label>
                <select v-model="selectedTaskId" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm">
                    <option value="">No specific task</option>
                    <option v-for="t in myTasks" :key="t.id" :value="t.id">{{ t.title }}</option>
                </select>
                <button
                    type="button"
                    :disabled="busy"
                    class="w-full rounded-2xl bg-emerald-600 py-3.5 text-base font-bold text-white shadow-lg hover:bg-emerald-700 disabled:opacity-50"
                    @click="startSession"
                >
                    {{ busy ? 'Starting…' : 'Start field session' }}
                </button>
                <p class="text-center text-xs text-slate-500">
                    Allow location “always” or while using the app for best live trail. Add this page to your home screen for a standalone field app.
                </p>
            </div>

            <div v-else class="mt-5 space-y-3">
                <button
                    type="button"
                    :disabled="busy"
                    class="w-full rounded-2xl bg-red-600 py-3.5 text-base font-bold text-white shadow-lg hover:bg-red-700 disabled:opacity-50"
                    @click="endSession"
                >
                    {{ busy ? 'Ending…' : 'End session & save route' }}
                </button>
                <p class="text-center text-xs text-slate-500">Points upload in the background (throttled). Ending flushes the queue and closes the linked task.</p>
            </div>
        </div>
    </AppLayout>
</template>
