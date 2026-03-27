<template>
    <AppLayout>
        <div class="min-h-screen bg-slate-50 py-4 sm:py-8">
            <div class="max-w-4xl mx-auto px-3 sm:px-6 space-y-4">
                <div class="rounded-2xl bg-gradient-to-r from-blue-700 to-indigo-800 text-white p-5">
                    <h1 class="text-2xl font-bold">Live NGO Feeds</h1>
                    <p class="text-blue-100 text-sm mt-1">Simple social feed with reactions, comments, shares, and NGO map view.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm space-y-3">
                    <input
                        v-model="searchText"
                        type="text"
                        placeholder="Search posts, NGOs, locations..."
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <div class="grid grid-cols-2 gap-2">
                        <button class="mode-btn" :class="{ 'mode-btn-active': activeMode === 'feed' }" @click="activeMode = 'feed'">Feed</button>
                        <button class="mode-btn" :class="{ 'mode-btn-active': activeMode === 'ngo' }" @click="activateNgoMode">Nearby NGOs</button>
                    </div>
                </div>

                <template v-if="activeMode === 'feed'">
                    <article
                        v-for="post in filteredPosts"
                        :key="post.id"
                        class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
                    >
                        <div class="px-4 pt-4 pb-3">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">
                                        {{ (post.ngo?.name || post.author?.name || 'F').charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ post.ngo?.name || post.author?.name }}</p>
                                        <p class="text-xs text-slate-500">{{ formatTime(post.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                            <h2 class="text-lg font-semibold text-slate-900">{{ post.title }}</h2>
                            <p class="text-sm text-slate-700 mt-1 leading-6">{{ post.body }}</p>
                        </div>

                        <img
                            v-if="post.image_url"
                            :src="resolveImage(post.image_url)"
                            :alt="post.title"
                            class="w-full max-h-[420px] object-cover bg-slate-100"
                            @error="handleImageError"
                        >

                        <div class="px-4 py-2 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                                <span>
                                    👍 {{ post.reactions.totals.like }} · ❤️ {{ post.reactions.totals.love }} · 🤝 {{ post.reactions.totals.support }}
                                </span>
                                <span>{{ post.comments.length }} comments · {{ post.shares_count }} shares</span>
                            </div>
                            <div class="grid grid-cols-4 gap-2">
                                <button class="action-btn" :class="{ 'active-action': post.reactions.my_reaction === 'like' }" @click="react(post.id, 'like')">👍 Like</button>
                                <button class="action-btn" :class="{ 'active-action': post.reactions.my_reaction === 'love' }" @click="react(post.id, 'love')">❤️ Love</button>
                                <button class="action-btn" :class="{ 'active-action': post.reactions.my_reaction === 'support' }" @click="react(post.id, 'support')">🤝 Support</button>
                                <button class="action-btn" @click="share(post)">↗ Share</button>
                            </div>
                        </div>

                        <div class="px-4 pb-4">
                            <div class="space-y-2 mb-3 max-h-44 overflow-auto">
                                <div v-for="comment in post.comments" :key="comment.id" class="rounded-xl bg-slate-50 px-3 py-2">
                                    <p class="text-xs font-semibold text-slate-700">{{ comment.user_name }}</p>
                                    <p class="text-sm text-slate-700">{{ comment.comment }}</p>
                                </div>
                            </div>
                            <form class="flex gap-2" @submit.prevent="submitComment(post.id)">
                                <input
                                    v-model="commentDrafts[post.id]"
                                    type="text"
                                    maxlength="500"
                                    placeholder="Write a comment..."
                                    class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                <button type="submit" class="rounded-xl bg-blue-600 text-white text-sm px-3 py-2 hover:bg-blue-700 transition">
                                    Post
                                </button>
                            </form>
                        </div>
                    </article>
                </template>

                <template v-else>
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm space-y-3">
                        <div class="flex flex-wrap gap-2">
                            <button
                                class="rounded-xl border border-blue-200 bg-blue-50 text-blue-700 px-3 py-2 text-xs font-semibold hover:bg-blue-100 transition"
                                @click="useMyLocation"
                            >
                                Use My Location
                            </button>
                            <select v-model.number="distanceKm" class="rounded-xl border border-slate-300 px-3 py-2 text-xs">
                                <option :value="10">Within 10 km</option>
                                <option :value="25">Within 25 km</option>
                                <option :value="50">Within 50 km</option>
                                <option :value="100">Within 100 km</option>
                                <option :value="1000">All</option>
                            </select>
                            <select v-model="selectedFocus" class="rounded-xl border border-slate-300 px-3 py-2 text-xs">
                                <option value="">All Focus Areas</option>
                                <option v-for="focus in focusOptions" :key="focus" :value="focus">{{ focus }}</option>
                            </select>
                        </div>
                        <p class="text-xs text-slate-500">
                            Showing <span class="font-semibold">{{ filteredNgos.length }}</span> NGOs in your selected range.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <div id="ngo-map" class="h-[360px] sm:h-[420px] rounded-xl"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <article
                            v-for="ngo in filteredNgos"
                            :key="ngo.id"
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
                        >
                            <div class="flex items-center gap-3">
                                <img :src="ngo.logo || '/assets/images/fevourd-k/logo.png'" class="w-10 h-10 rounded-lg object-cover bg-slate-100" @error="handleImageError">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ ngo.name }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ ngo.distance_km !== null ? `${ngo.distance_km.toFixed(1)} km away` : `Lat: ${ngo.latitude.toFixed(3)}, Lng: ${ngo.longitude.toFixed(3)}` }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ ngo.description || 'Verified NGO impact profile.' }}</p>
                            <div class="mt-2 flex flex-wrap gap-1">
                                <span v-for="focus in (ngo.focus_areas || []).slice(0, 3)" :key="focus" class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ focus }}
                                </span>
                            </div>
                        </article>
                    </div>
                </template>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed, nextTick, onMounted, reactive, ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
    posts: { type: Array, default: () => [] },
    ngos: { type: Array, default: () => [] },
})

const activeMode = ref('feed')
const searchText = ref('')
const commentDrafts = reactive({})
const userLocation = ref(null)
const distanceKm = ref(50)
const selectedFocus = ref('')
let map = null
let markersLayer = null

const filteredPosts = computed(() => {
    if (!searchText.value.trim()) {
        return props.posts
    }
    const q = searchText.value.toLowerCase()
    return props.posts.filter((post) =>
        post.title?.toLowerCase().includes(q) ||
        post.body?.toLowerCase().includes(q) ||
        post.ngo?.name?.toLowerCase().includes(q)
    )
})

const ngosWithDistance = computed(() => {
    return props.ngos.map((ngo) => {
        const distance = userLocation.value
            ? haversineKm(userLocation.value.lat, userLocation.value.lng, ngo.latitude, ngo.longitude)
            : null
        return { ...ngo, distance_km: distance }
    })
})

const focusOptions = computed(() => {
    const set = new Set()
    props.ngos.forEach((ngo) => (ngo.focus_areas || []).forEach((focus) => set.add(focus)))
    return Array.from(set).sort((a, b) => a.localeCompare(b))
})

const filteredNgos = computed(() => {
    const q = searchText.value.trim().toLowerCase()

    return ngosWithDistance.value.filter((ngo) => {
        if (q && !(
            ngo.name?.toLowerCase().includes(q) ||
            ngo.description?.toLowerCase().includes(q)
        )) {
            return false
        }
        if (selectedFocus.value && !(ngo.focus_areas || []).includes(selectedFocus.value)) {
            return false
        }
        if (ngo.distance_km !== null && ngo.distance_km > distanceKm.value) {
            return false
        }
        return true
    }).sort((a, b) => {
        if (a.distance_km === null && b.distance_km === null) return 0
        if (a.distance_km === null) return 1
        if (b.distance_km === null) return -1
        return a.distance_km - b.distance_km
    })
})

const react = (postId, type) => {
    router.post(`/feeds/${postId}/react`, { type }, { preserveScroll: true })
}

const submitComment = (postId) => {
    const comment = commentDrafts[postId]?.trim()
    if (!comment) return

    router.post(`/feeds/${postId}/comment`, { comment }, {
        preserveScroll: true,
        onSuccess: () => { commentDrafts[postId] = '' },
    })
}

const share = async (post) => {
    const shareUrl = `${window.location.origin}/feeds`
    const text = `${post.title} - ${post.body?.slice(0, 120) || ''}`
    if (navigator.share) {
        try {
            await navigator.share({ title: post.title, text, url: shareUrl })
            router.post(`/feeds/${post.id}/share`, { channel: 'link' }, { preserveScroll: true })
            return
        } catch (error) {}
    }
    await navigator.clipboard.writeText(`${text} ${shareUrl}`)
    router.post(`/feeds/${post.id}/share`, { channel: 'link' }, { preserveScroll: true })
}

const formatTime = (isoDate) => new Date(isoDate).toLocaleString()

const resolveImage = (url) => url || '/assets/images/fevourd-k/logo.png'

const handleImageError = (event) => {
    event.target.src = '/assets/images/fevourd-k/logo.png'
}

const renderMapMarkers = () => {
    if (!map || !markersLayer) return
    markersLayer.clearLayers()
    filteredNgos.value.forEach((ngo) => {
        const marker = L.marker([ngo.latitude, ngo.longitude])
        marker.bindPopup(`<strong>${ngo.name}</strong><br/>${ngo.description || 'Impact NGO'}`)
        marker.addTo(markersLayer)
    })
}

const ensureMap = async () => {
    await nextTick()
    if (map) {
        map.invalidateSize()
        renderMapMarkers()
        return
    }

    const mapElement = document.getElementById('ngo-map')
    if (!mapElement) return

    map = L.map('ngo-map').setView([15.3173, 75.7139], 6)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map)

    markersLayer = L.layerGroup().addTo(map)
    renderMapMarkers()
}

const activateNgoMode = async () => {
    activeMode.value = 'ngo'
    await ensureMap()
}

const useMyLocation = () => {
    if (!navigator.geolocation) return
    navigator.geolocation.getCurrentPosition(
        async (position) => {
            userLocation.value = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            }
            await ensureMap()
        },
        () => {},
        { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
    )
}

const haversineKm = (lat1, lon1, lat2, lon2) => {
    const toRad = (deg) => (deg * Math.PI) / 180
    const R = 6371
    const dLat = toRad(lat2 - lat1)
    const dLon = toRad(lon2 - lon1)
    const a =
        Math.sin(dLat / 2) ** 2 +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2
    return 2 * R * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))
}

watch([searchText, selectedFocus, distanceKm, userLocation, activeMode], async () => {
    if (activeMode.value === 'ngo') {
        await ensureMap()
    }
})

onMounted(async () => {
    if (new URLSearchParams(window.location.search).get('view') === 'ngo') {
        await activateNgoMode()
    }
})
</script>

<style scoped>
.action-btn {
    @apply rounded-lg border border-slate-200 px-2 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 transition;
}
.active-action {
    @apply bg-blue-600 border-blue-600 text-white;
}
.mode-btn {
    @apply rounded-xl border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition;
}
.mode-btn-active {
    @apply bg-blue-600 border-blue-600 text-white;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
