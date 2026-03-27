<script setup>
import { computed, nextTick, onMounted, ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({ ngo: Object })
const processing = ref(false)
const mapElement = ref(null)

const statusBadgeClass = computed(() => {
    if (props.ngo.verification_status === 'verified') return 'bg-emerald-100 text-emerald-700'
    if (props.ngo.verification_status === 'suspended') return 'bg-rose-100 text-rose-700'
    return 'bg-amber-100 text-amber-700'
})

const logoUrl = computed(() => {
    const logo = props.ngo.logo
    if (!logo) return null
    return logo.startsWith('http') ? logo : `/storage/${logo}`
})

const hasCoordinates = computed(() => {
    return props.ngo.latitude !== null && props.ngo.longitude !== null
})

const normalizeDocumentPath = (path) => {
    if (!path) return '#'
    return path.startsWith('http') ? path : `/storage/${path}`
}

const approve = () => {
    processing.value = true
    router.post(`/admin/ngos/${props.ngo.id}/verify`, {}, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false
        },
    })
}

const reject = () => {
    processing.value = true
    router.post(`/admin/ngos/${props.ngo.id}/reject`, {}, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false
        },
    })
}

onMounted(async () => {
    if (!hasCoordinates.value || !mapElement.value) return
    await nextTick()
    const L = await import('leaflet')
    const lat = Number(props.ngo.latitude)
    const lng = Number(props.ngo.longitude)
    const map = L.map(mapElement.value).setView([lat, lng], 14)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map)

    const popup = `
        <div style="min-width:220px">
            <strong>${props.ngo.name}</strong><br/>
            <span>${props.ngo.description || 'No description available'}</span>
        </div>
    `
    L.marker([lat, lng]).addTo(map).bindPopup(popup).openPopup()
})
</script>

<template>
    <AdminLayout title="NGO Details">
        <div class="space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">NGO Review</h1>
                    <p class="text-sm text-slate-500">Review registration details, contact data, documents and map location.</p>
                </div>
                <Link href="/admin/ngos" class="rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Back to list</Link>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">
                <div class="rounded-xl border bg-white p-4 lg:col-span-2">
                    <div class="flex items-start gap-4">
                        <img v-if="logoUrl" :src="logoUrl" alt="NGO logo" class="h-20 w-20 rounded-lg object-cover border">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-lg font-semibold text-slate-900">{{ ngo.name }}</h2>
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold" :class="statusBadgeClass">
                                    {{ ngo.verification_status }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-slate-600">{{ ngo.email }} · {{ ngo.phone }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ ngo.address }}</p>
                            <p class="mt-2 text-sm text-slate-700">{{ ngo.description || 'No mission/description provided.' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <button class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700 disabled:opacity-60" :disabled="processing || ngo.verification_status === 'verified'" @click="approve">
                            {{ processing ? 'Updating...' : 'Approve NGO' }}
                        </button>
                        <button class="rounded-md bg-rose-600 px-3 py-2 text-sm font-medium text-white hover:bg-rose-700 disabled:opacity-60" :disabled="processing || ngo.verification_status === 'suspended'" @click="reject">
                            {{ processing ? 'Updating...' : 'Reject NGO' }}
                        </button>
                    </div>
                </div>

                <div class="rounded-xl border bg-white p-4">
                    <h3 class="text-sm font-semibold text-slate-900">Registration Snapshot</h3>
                    <dl class="mt-3 space-y-2 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Registration No.</dt>
                            <dd class="font-medium text-slate-900">{{ ngo.registration_number || '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">PAN</dt>
                            <dd class="font-medium text-slate-900">{{ ngo.pan || '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">City/State</dt>
                            <dd class="font-medium text-slate-900">{{ ngo.city?.name || '-' }}, {{ ngo.state?.name || '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Website URL</dt>
                            <dd class="font-medium text-slate-900">{{ ngo.website_url || '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="grid gap-5 lg:grid-cols-2">
                <div class="rounded-xl border bg-white p-4">
                    <h3 class="text-sm font-semibold text-slate-900">Founder / Co-founder</h3>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-lg border border-slate-200 p-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Founder</p>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ ngo.founder_name || '-' }}</p>
                            <p class="text-sm text-slate-700">{{ ngo.founder_phone || '-' }}</p>
                            <p class="text-sm text-slate-700">{{ ngo.founder_email || '-' }}</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 p-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Co-founder</p>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ ngo.cofounder_name || '-' }}</p>
                            <p class="text-sm text-slate-700">{{ ngo.cofounder_phone || '-' }}</p>
                            <p class="text-sm text-slate-700">{{ ngo.cofounder_email || '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-white p-4">
                    <h3 class="text-sm font-semibold text-slate-900">Focus Areas</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span v-for="area in ngo.focus_areas || []" :key="area" class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                            {{ area }}
                        </span>
                        <p v-if="!(ngo.focus_areas || []).length" class="text-sm text-slate-500">No focus areas added.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border bg-white p-4">
                <h3 class="mb-2 text-sm font-semibold text-slate-900">Location Map (Leaflet)</h3>
                <div v-if="hasCoordinates" ref="mapElement" class="h-72 rounded-lg border"></div>
                <p v-else class="text-sm text-slate-500">No coordinates found for this NGO profile.</p>
            </div>

            <div class="rounded-xl border bg-white p-4">
                <h3 class="mb-3 text-sm font-semibold text-slate-900">Uploaded Documents</h3>
                <div v-if="!ngo.documents?.length" class="text-sm text-slate-500">No documents uploaded.</div>
                <div v-for="doc in ngo.documents || []" :key="doc.id" class="flex items-center justify-between border-t py-3 text-sm">
                    <div>
                        <p class="font-medium text-slate-900">{{ doc.document_type }}</p>
                        <p class="text-xs text-slate-500">Status: {{ doc.status }}</p>
                    </div>
                    <a :href="normalizeDocumentPath(doc.file_path)" target="_blank" class="rounded border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 hover:bg-blue-100">Open</a>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
@import 'leaflet/dist/leaflet.css';
</style>
