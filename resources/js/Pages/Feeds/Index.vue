<template>
    <AppLayout>
        <div class="min-h-screen bg-slate-50">
            <!-- Hero: aligned with public Welcome / About enterprise bands -->
            <section class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white">
                <div class="absolute inset-0 opacity-10 bg-pattern-dots" aria-hidden="true" />
                <div class="absolute -right-24 top-0 h-72 w-72 rounded-full bg-blue-500/20 blur-3xl" aria-hidden="true" />
                <div class="absolute -left-20 bottom-0 h-64 w-64 rounded-full bg-indigo-500/15 blur-3xl" aria-hidden="true" />

                <div class="relative z-10 mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
                    <p class="mb-3 text-center text-[10px] font-bold uppercase tracking-[0.2em] text-blue-200/90">
                        Public · Live community
                    </p>
                    <h1 class="text-center text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl">
                        Live NGO feeds
                    </h1>
                    <p class="mx-auto mt-4 max-w-3xl text-center text-base text-blue-100 sm:text-lg">
                        Reactions, comments, and shares on the feed — plus a verified NGO map with Karnataka district boundaries for geographic context.
                    </p>

                    <div class="mx-auto mt-10 grid max-w-4xl grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-5 py-4 text-center backdrop-blur-sm">
                            <p class="text-3xl font-bold tabular-nums text-white">{{ postCount }}</p>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-blue-100/90">Posts in view</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-5 py-4 text-center backdrop-blur-sm">
                            <p class="text-3xl font-bold tabular-nums text-amber-300">{{ ngoCount }}</p>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-blue-100/90">Verified NGOs</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-5 py-4 text-center backdrop-blur-sm">
                            <p class="text-3xl font-bold tabular-nums text-emerald-300">{{ filteredNgos.length }}</p>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-blue-100/90">Map matches</p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 text-slate-50">
                    <svg class="block w-full translate-y-px" viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M0 48V24C240 8 480 0 720 0s480 8 720 24v24H0Z" fill="#f8fafc" />
                    </svg>
                </div>
            </section>

            <div class="relative z-20 mx-auto max-w-7xl -mt-1 space-y-6 px-4 pb-12 sm:px-6 lg:px-8">
                <div
                    v-if="flashSuccess"
                    class="mx-auto max-w-3xl rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 shadow-sm"
                >
                    {{ flashSuccess }}
                </div>
                <div
                    v-if="flashError"
                    class="mx-auto max-w-3xl rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 shadow-sm"
                >
                    {{ flashError }}
                </div>

                <div
                    v-if="feedMeta.can_post_as_ngo"
                    class="mx-auto max-w-3xl rounded-2xl border border-blue-200 bg-white p-4 shadow-md shadow-slate-200/40 space-y-3"
                >
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Quick post as {{ feedMeta.ngo_name || 'your NGO' }}</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Same form lives in your workspace menu under <strong>Post an update</strong> — use whichever you prefer.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <Link
                                href="/ngo/post-update"
                                class="shrink-0 rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition"
                            >
                                Open simple post page
                            </Link>
                            <Link
                                :href="feedMeta.social_connect_url"
                                class="shrink-0 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-800 hover:bg-blue-100 transition"
                            >
                                Auto-share setup
                            </Link>
                        </div>
                    </div>
                    <p v-if="feedMeta.social_connected_platforms?.length" class="text-[11px] text-slate-600">
                        Auto-share after each post:
                        <span class="font-semibold text-slate-800">{{ formatPlatforms(feedMeta.social_connected_platforms) }}</span>
                    </p>
                    <p v-else class="text-[11px] text-slate-600 bg-slate-50 rounded-lg px-3 py-2 border border-slate-100">
                        Optional: copy posts to Facebook / Instagram / LinkedIn from
                        <Link :href="feedMeta.social_connect_url" class="font-semibold text-blue-700 hover:underline">Auto-share (optional)</Link>
                        in the NGO menu — only if someone on your team can complete the technical step once.
                    </p>
                    <form class="space-y-3" @submit.prevent="submitNgoPost">
                        <input
                            v-model="feedForm.title"
                            type="text"
                            maxlength="200"
                            required
                            placeholder="Headline"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <textarea
                            v-model="feedForm.body"
                            required
                            rows="4"
                            maxlength="8000"
                            placeholder="Update your supporters…"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <div>
                            <p class="mb-1 text-xs font-medium text-slate-600">Photos &amp; videos (optional, up to 12)</p>
                            <label class="flex cursor-pointer items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-4 text-xs font-semibold text-blue-700 hover:bg-blue-50/50">
                                Choose files
                                <input
                                    type="file"
                                    multiple
                                    accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime"
                                    class="sr-only"
                                    @change="onFeedMediaPick"
                                >
                            </label>
                            <div v-if="feedSlots.length" class="mt-2 grid grid-cols-3 gap-2 sm:grid-cols-4">
                                <div v-for="(s, i) in feedSlots" :key="s.id" class="relative aspect-square overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                                    <img v-if="s.kind === 'image'" :src="s.url" alt="" class="h-full w-full object-cover">
                                    <video v-else :src="s.url" class="h-full w-full object-cover" muted playsinline />
                                    <button type="button" class="absolute right-0.5 top-0.5 rounded bg-black/60 px-1 text-[10px] text-white" @click="removeFeedSlot(i)">x</button>
                                </div>
                            </div>
                        </div>
                        <p v-if="feedForm.errors.media_files" class="text-xs text-red-600">{{ feedForm.errors.media_files }}</p>
                        <p v-if="feedForm.errors.title" class="text-xs text-red-600">{{ feedForm.errors.title }}</p>
                        <p v-if="feedForm.errors.body" class="text-xs text-red-600">{{ feedForm.errors.body }}</p>
                        <button
                            type="submit"
                            class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:opacity-60 sm:w-auto"
                            :disabled="feedForm.processing"
                        >
                            {{ feedForm.processing ? 'Publishing…' : 'Publish to live feed' }}
                        </button>
                    </form>
                </div>

                <section aria-labelledby="feeds-discover-heading">
                    <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-blue-600">Discover</p>
                            <h2 id="feeds-discover-heading" class="text-lg font-bold text-slate-900 sm:text-xl">
                                Search &amp; view mode
                            </h2>
                            <p class="mt-0.5 max-w-2xl text-sm text-slate-600">
                                Switch between the social feed and the NGO locator. Map loads official Karnataka boundaries (GeoJSON) for a clear regional frame.
                            </p>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-md shadow-slate-200/50 space-y-3">
                        <input
                            v-model="searchText"
                            type="search"
                            autocomplete="off"
                            placeholder="Search posts, NGOs, locations…"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                        >
                        <div class="grid grid-cols-2 gap-2 sm:gap-3">
                            <button
                                type="button"
                                class="mode-btn"
                                :class="{ 'mode-btn-active': activeMode === 'feed' }"
                                @click="activeMode = 'feed'"
                            >
                                Feed
                            </button>
                            <button
                                type="button"
                                class="mode-btn"
                                :class="{ 'mode-btn-active': activeMode === 'ngo' }"
                                @click="activateNgoMode"
                            >
                                Nearby NGOs
                            </button>
                        </div>
                    </div>
                </section>

                <section v-if="activeMode === 'feed'" aria-labelledby="feeds-stream-heading">
                    <div class="mb-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-600">Community</p>
                        <h2 id="feeds-stream-heading" class="text-lg font-bold text-slate-900 sm:text-xl">
                            Live stream
                        </h2>
                    </div>

                    <div class="mx-auto max-w-3xl">
                    <div
                        v-if="filteredPosts.length === 0"
                        class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm"
                    >
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <p class="text-base font-semibold text-slate-800">No posts match your search</p>
                        <p class="mt-2 text-sm text-slate-500">Try clearing the search box or check back as NGOs publish updates.</p>
                    </div>

                    <article
                        v-for="post in filteredPosts"
                        :key="post.id"
                        :data-feed-post="String(post.id)"
                        class="mb-4 last:mb-0 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
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
                            <Link :href="post.public_url || `/feeds/${post.id}`" class="block">
                                <h2 class="text-lg font-semibold text-slate-900 hover:text-blue-800">{{ post.title }}</h2>
                            </Link>
                            <p class="text-sm text-slate-700 mt-1 leading-6">{{ post.body }}</p>
                        </div>

                        <div v-if="post.media?.length" class="border-t border-slate-100 bg-slate-200">
                            <div class="grid grid-cols-1 gap-px sm:grid-cols-2">
                                <template v-for="(item, idx) in post.media" :key="idx">
                                    <img
                                        v-if="item.type === 'image'"
                                        :src="resolveMedia(item.path)"
                                        :alt="`${post.title} ${idx + 1}`"
                                        class="max-h-[360px] w-full bg-slate-100 object-cover"
                                        loading="lazy"
                                        @error="handleImageError"
                                    >
                                    <video
                                        v-else
                                        :src="resolveMedia(item.path)"
                                        class="max-h-[360px] w-full bg-black"
                                        controls
                                        playsinline
                                        preload="metadata"
                                    />
                                </template>
                            </div>
                        </div>
                        <img
                            v-else-if="post.image_url"
                            :src="resolveImage(post.image_url)"
                            :alt="post.title"
                            class="w-full max-h-[420px] object-cover bg-slate-100"
                            @error="handleImageError"
                        >

                        <div class="px-4 py-2 border-t border-slate-100">
                            <div class="flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs text-slate-500 mb-2">
                                <span>
                                    👍 {{ post.reactions.totals.like }} · ❤️ {{ post.reactions.totals.love }} · 🤝 {{ post.reactions.totals.support }}
                                </span>
                                <span>{{ post.views_count ?? 0 }} views · {{ post.comments.length }} comments · {{ post.shares_count }} shares</span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <button class="action-btn" :class="{ 'active-action': post.reactions.my_reaction === 'like' }" @click="react(post.id, 'like')">👍 Like</button>
                                <button class="action-btn" :class="{ 'active-action': post.reactions.my_reaction === 'love' }" @click="react(post.id, 'love')">❤️ Love</button>
                                <button class="action-btn" :class="{ 'active-action': post.reactions.my_reaction === 'support' }" @click="react(post.id, 'support')">🤝 Support</button>
                                <button class="action-btn" :class="{ 'ring-2 ring-blue-200': shareFor === post.id }" @click="toggleShare(post.id)">↗ Share</button>
                            </div>
                            <div v-if="shareFor === post.id" class="mt-2 flex flex-wrap gap-2">
                                <button type="button" class="share-chip" @click="shareVia(post, 'link')">Copy link</button>
                                <button type="button" class="share-chip" @click="shareVia(post, 'whatsapp')">WhatsApp</button>
                                <button type="button" class="share-chip" @click="shareVia(post, 'facebook')">Facebook</button>
                                <button type="button" class="share-chip" @click="shareVia(post, 'linkedin')">LinkedIn</button>
                                <button type="button" class="share-chip" @click="shareVia(post, 'instagram')">Instagram caption</button>
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
                    </div>
                </section>

                <section v-else aria-labelledby="feeds-map-heading">
                    <div class="mb-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-600">Geography</p>
                        <h2 id="feeds-map-heading" class="text-lg font-bold text-slate-900 sm:text-xl">
                            Verified NGOs on the map
                        </h2>
                        <p class="mt-0.5 max-w-2xl text-sm text-slate-600">
                            District boundaries from Karnataka GeoJSON; markers show NGO locations. Use your position to sort by distance.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-md shadow-slate-200/50 space-y-3">
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-800 transition hover:bg-blue-100"
                                @click="useMyLocation"
                            >
                                Use my location
                            </button>
                            <select v-model.number="distanceKm" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800">
                                <option :value="10">Within 10 km</option>
                                <option :value="25">Within 25 km</option>
                                <option :value="50">Within 50 km</option>
                                <option :value="100">Within 100 km</option>
                                <option :value="1000">All Karnataka</option>
                            </select>
                            <select v-model="selectedFocus" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-800">
                                <option value="">All focus areas</option>
                                <option v-for="focus in focusOptions" :key="focus" :value="focus">{{ focus }}</option>
                            </select>
                        </div>
                        <p class="text-xs text-slate-600">
                            Showing <span class="font-semibold text-slate-900">{{ filteredNgos.length }}</span> NGOs for your filters
                            <span v-if="mapLocationDistrictName" class="text-blue-800">
                                · Map highlight: <strong>{{ mapLocationDistrictName }}</strong>
                                <span v-if="userLocation" class="text-slate-500"> (your location)</span>
                                <span v-else-if="filteredNgos.length" class="text-slate-500"> (first listed NGO)</span>
                            </span>
                            <span v-if="mapGeoError" class="text-amber-700"> · Boundary layer unavailable (map still works)</span>
                        </p>
                    </div>

                    <div class="feeds-map-shell relative isolate overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md shadow-slate-200/50 ring-1 ring-slate-100">
                        <div id="ngo-map" class="feeds-map-canvas h-[min(52vh,440px)] min-h-[320px] sm:min-h-[380px]" />
                        <div
                            v-show="mapGeoLoading"
                            class="feeds-map-loader pointer-events-none absolute inset-0 flex flex-col items-center justify-center gap-3 bg-slate-50/95 backdrop-blur-sm"
                            role="status"
                            aria-live="polite"
                            :aria-busy="mapGeoLoading"
                        >
                            <div class="feeds-map-spinner h-10 w-10 rounded-full border-[3px] border-slate-200 border-t-blue-600" />
                            <p class="text-sm font-semibold text-slate-800">Loading map layers</p>
                            <p class="max-w-xs px-4 text-center text-xs text-slate-500">Karnataka district boundaries (GeoJSON)…</p>
                        </div>
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
                </section>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import { computed, nextTick, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const page = usePage()

const props = defineProps({
    posts: { type: Array, default: () => [] },
    ngos: { type: Array, default: () => [] },
    feedMeta: {
        type: Object,
        default: () => ({
            can_post_as_ngo: false,
            ngo_name: null,
            social_connected_platforms: [],
            social_connect_url: '/ngo/social-connect',
        }),
    },
})

const flashSuccess = computed(() => page.props.flash?.success ?? null)
const flashError = computed(() => page.props.flash?.error ?? null)

const feedForm = useForm({
    title: '',
    body: '',
    media_files: [],
})

const shareFor = ref(null)
const feedSlots = ref([])
let feedSlotSeq = 0
const viewObserver = ref(null)
const observedFeedEls = new WeakSet()

const getKarnatakaGeoUrl = () => {
    if (typeof window !== 'undefined') {
        return new URL('/assets/Themes/karnataka.geojson', window.location.origin).href
    }

    return '/assets/Themes/karnataka.geojson'
}

const districtKeyFromProps = (props) => String(props?.dt_code ?? props?.district ?? '')

const pointInRingLngLat = (lng, lat, ring) => {
    let inside = false
    for (let i = 0, j = ring.length - 1; i < ring.length; j = i++) {
        const xi = ring[i][0]
        const yi = ring[i][1]
        const xj = ring[j][0]
        const yj = ring[j][1]
        if ((yi > lat) !== (yj > lat) && lng < ((xj - xi) * (lat - yi)) / (yj - yi) + xi) {
            inside = !inside
        }
    }

    return inside
}

const pointInPolygonLngLat = (lng, lat, geometry) => {
    if (!geometry) return false
    if (geometry.type === 'Polygon') {
        const coords = geometry.coordinates
        if (!coords?.[0]?.length) return false
        if (!pointInRingLngLat(lng, lat, coords[0])) return false
        for (let h = 1; h < coords.length; h++) {
            if (pointInRingLngLat(lng, lat, coords[h])) return false
        }

        return true
    }
    if (geometry.type === 'MultiPolygon') {
        for (const poly of geometry.coordinates || []) {
            if (!poly?.[0]?.length) continue
            if (!pointInRingLngLat(lng, lat, poly[0])) continue
            let inHole = false
            for (let h = 1; h < poly.length; h++) {
                if (pointInRingLngLat(lng, lat, poly[h])) {
                    inHole = true
                    break
                }
            }
            if (!inHole) return true
        }

        return false
    }

    return false
}

const findDistrictKeyAt = (featureCollection, lat, lng) => {
    const lngN = Number(lng)
    const latN = Number(lat)
    for (const f of featureCollection.features || []) {
        if (pointInPolygonLngLat(lngN, latN, f.geometry)) {
            return districtKeyFromProps(f.properties)
        }
    }

    return null
}

const getDistrictPolygonStyle = (feature, highlightedKey) => {
    const key = districtKeyFromProps(feature.properties)
    const isHi = Boolean(highlightedKey && key && key === highlightedKey)

    return {
        color: isHi ? '#1e3a8a' : '#1d4ed8',
        weight: isHi ? 3 : 1.5,
        fillColor: isHi ? '#2563eb' : '#93c5fd',
        fillOpacity: isHi ? 0.4 : 0.2,
    }
}

const activeMode = ref('feed')
const searchText = ref('')
const commentDrafts = reactive({})
const userLocation = ref(null)
const distanceKm = ref(50)
const selectedFocus = ref('')
const mapGeoLoading = ref(false)
const mapGeoError = ref(false)
const highlightedDistrictKey = ref(null)
const mapLocationDistrictName = ref(null)

let map = null
let markersLayer = null
let geoJsonLayer = null
let karnatakaGeoCache = null

const postCount = computed(() => props.posts.length)
const ngoCount = computed(() => props.ngos.length)

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

const mapHighlightAnchor = computed(() => {
    if (userLocation.value?.lat != null && userLocation.value?.lng != null) {
        return { lat: userLocation.value.lat, lng: userLocation.value.lng }
    }
    const withCoords = filteredNgos.value.filter((n) => n.latitude != null && n.longitude != null)
    if (withCoords.length) {
        return { lat: withCoords[0].latitude, lng: withCoords[0].longitude }
    }

    return { lat: 15.3173, lng: 75.7139 }
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

const shareText = (post) => `${post.title} - ${post.body?.slice(0, 120) || ''}`.trim()

const toggleShare = (postId) => {
    shareFor.value = shareFor.value === postId ? null : postId
}

const formatPlatforms = (list) => {
    const labels = { facebook: 'Facebook', instagram: 'Instagram', linkedin: 'LinkedIn', google_business: 'Google Business' }
    return (list || []).map((p) => labels[p] || p).join(', ')
}

const onFeedMediaPick = (e) => {
    const files = Array.from(e.target.files || [])
    e.target.value = ''
    const allowedVideo = ['video/mp4', 'video/webm', 'video/quicktime']
    for (const f of files) {
        if (feedSlots.value.length >= 12) {
            break
        }
        let kind = null
        if (/^image\/(jpeg|jpg|png|webp)$/i.test(f.type)) {
            kind = 'image'
        } else if (allowedVideo.includes(f.type)) {
            kind = 'video'
        }
        if (!kind) {
            continue
        }
        feedSlots.value.push({
            id: ++feedSlotSeq,
            file: f,
            url: URL.createObjectURL(f),
            kind,
        })
    }
}

const removeFeedSlot = (i) => {
    const s = feedSlots.value[i]
    if (s?.url) {
        URL.revokeObjectURL(s.url)
    }
    feedSlots.value.splice(i, 1)
}

const clearFeedSlots = () => {
    feedSlots.value.forEach((s) => URL.revokeObjectURL(s.url))
    feedSlots.value = []
}

const submitNgoPost = () => {
    feedForm.media_files = feedSlots.value.map((s) => s.file)
    feedForm.post('/feeds', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            feedForm.reset()
            feedForm.clearErrors()
            clearFeedSlots()
        },
    })
}

const shareVia = async (post, channel) => {
    const shareUrl = post.public_url || `${window.location.origin}/feeds/${post.id}`
    const text = shareText(post)
    const full = `${text} ${shareUrl}`.trim()

    if (channel === 'link') {
        try {
            if (navigator.share) {
                await navigator.share({ title: post.title, text, url: shareUrl })
            } else {
                await navigator.clipboard.writeText(full)
            }
        } catch {
            try {
                await navigator.clipboard.writeText(full)
            } catch {
                /* ignore */
            }
        }
    } else if (channel === 'whatsapp') {
        window.open(`https://wa.me/?text=${encodeURIComponent(full)}`, '_blank', 'noopener,noreferrer')
    } else if (channel === 'facebook') {
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`, '_blank', 'noopener,noreferrer')
    } else if (channel === 'linkedin') {
        window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl)}`, '_blank', 'noopener,noreferrer')
    } else if (channel === 'instagram') {
        try {
            await navigator.clipboard.writeText(full)
        } catch {
            /* ignore */
        }
    }

    router.post(`/feeds/${post.id}/share`, { channel }, { preserveScroll: true })
    shareFor.value = null
}

const formatTime = (isoDate) => new Date(isoDate).toLocaleString()

const resolveImage = (url) => url || '/assets/images/fevourd-k/logo.png'

const resolveMedia = (path) => {
    if (!path) {
        return '/assets/images/fevourd-k/logo.png'
    }
    if (path.startsWith('http')) {
        return path
    }
    return path.startsWith('/') ? path : `/${path}`
}

const handleImageError = (event) => {
    event.target.src = '/assets/images/fevourd-k/logo.png'
}

const bindFeedViewTracking = () => {
    if (!viewObserver.value) {
        return
    }
    document.querySelectorAll('[data-feed-post]').forEach((el) => {
        if (observedFeedEls.has(el)) {
            return
        }
        observedFeedEls.add(el)
        viewObserver.value.observe(el)
    })
}

const setupFeedViewObserver = () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    const seenIds = new Set()
    viewObserver.value = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return
                }
                const id = entry.target.getAttribute('data-feed-post')
                if (!id || seenIds.has(id)) {
                    return
                }
                seenIds.add(id)
                fetch(`/feeds/${id}/view`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': token || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: '{}',
                }).catch(() => {})
            })
        },
        { threshold: 0.35, rootMargin: '0px' },
    )
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

const fitMapToContent = () => {
    if (!map) return
    const bounds = L.latLngBounds([])
    if (geoJsonLayer) {
        bounds.extend(geoJsonLayer.getBounds())
    }
    filteredNgos.value.forEach((ngo) => {
        if (ngo.latitude != null && ngo.longitude != null) {
            bounds.extend([ngo.latitude, ngo.longitude])
        }
    })
    if (bounds.isValid()) {
        map.fitBounds(bounds, { padding: [48, 48], maxZoom: 10 })
    }
}

const updateDistrictHighlight = () => {
    if (!geoJsonLayer || !karnatakaGeoCache) return
    const { lat, lng } = mapHighlightAnchor.value
    const hKey = findDistrictKeyAt(karnatakaGeoCache, lat, lng)
    highlightedDistrictKey.value = hKey
    const hit = karnatakaGeoCache.features.find((x) => districtKeyFromProps(x.properties) === hKey)
    mapLocationDistrictName.value = hit?.properties?.district || null
    geoJsonLayer.eachLayer((layer) => {
        if (layer.feature) {
            layer.setStyle(getDistrictPolygonStyle(layer.feature, hKey))
        }
    })
}

const loadKarnatakaBoundaryIntoMap = async () => {
    if (!map || geoJsonLayer) return

    const minLoaderMs = 550
    const started = typeof performance !== 'undefined' ? performance.now() : Date.now()

    mapGeoLoading.value = true
    mapGeoError.value = false
    await nextTick()
    await new Promise((resolve) => {
        requestAnimationFrame(() => requestAnimationFrame(() => resolve()))
    })

    try {
        const geoUrl = getKarnatakaGeoUrl()
        if (!karnatakaGeoCache) {
            const res = await fetch(geoUrl, { credentials: 'same-origin' })
            if (!res.ok) {
                throw new Error('geo fetch failed')
            }
            karnatakaGeoCache = await res.json()
        }
        geoJsonLayer = L.geoJSON(karnatakaGeoCache, {
            style: (feature) => getDistrictPolygonStyle(feature, null),
            onEachFeature: (feature, layer) => {
                const name = feature.properties?.district
                if (name) {
                    layer.bindTooltip(name, { sticky: true, className: 'feeds-map-dist-tooltip' })
                }
            },
        })
        geoJsonLayer.addTo(map)
        if (typeof geoJsonLayer.bringToBack === 'function') {
            geoJsonLayer.bringToBack()
        }
        updateDistrictHighlight()
    } catch {
        mapGeoError.value = true
    } finally {
        const now = typeof performance !== 'undefined' ? performance.now() : Date.now()
        const elapsed = now - started
        if (elapsed < minLoaderMs) {
            await new Promise((r) => setTimeout(r, minLoaderMs - elapsed))
        }
        mapGeoLoading.value = false
    }
}

const ensureMap = async () => {
    await nextTick()
    const mapElement = document.getElementById('ngo-map')
    if (!mapElement) return

    if (map) {
        map.invalidateSize()
        renderMapMarkers()
        updateDistrictHighlight()
        fitMapToContent()

        return
    }

    map = L.map('ngo-map', { scrollWheelZoom: true }).setView([15.3173, 75.7139], 7)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 20,
    }).addTo(map)

    await loadKarnatakaBoundaryIntoMap()

    if (!markersLayer) {
        markersLayer = L.layerGroup().addTo(map)
    }
    renderMapMarkers()
    fitMapToContent()
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

watch(
    [userLocation, filteredNgos],
    async () => {
        if (activeMode.value !== 'ngo' || !geoJsonLayer) return
        await nextTick()
        updateDistrictHighlight()
    },
    { deep: true },
)

watch(
    () => props.posts,
    async () => {
        await nextTick()
        bindFeedViewTracking()
    },
    { deep: true },
)

onBeforeUnmount(() => {
    viewObserver.value?.disconnect()
    if (map) {
        map.remove()
        map = null
        markersLayer = null
        geoJsonLayer = null
    }
    mapLocationDistrictName.value = null
    highlightedDistrictKey.value = null
})

onMounted(async () => {
    setupFeedViewObserver()
    await nextTick()
    bindFeedViewTracking()
    if (new URLSearchParams(window.location.search).get('view') === 'ngo') {
        await activateNgoMode()
    }
})
</script>

<style scoped>
.bg-pattern-dots {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.feeds-map-shell {
    isolation: isolate;
}

.feeds-map-canvas {
    position: relative;
    z-index: 0;
}

.feeds-map-loader {
    z-index: 20000;
}

.feeds-map-spinner {
    animation: feeds-map-spin 0.75s linear infinite;
}

@keyframes feeds-map-spin {
    to {
        transform: rotate(360deg);
    }
}

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
.share-chip {
    @apply rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-700 hover:bg-slate-100 transition;
}

:deep(.feeds-map-dist-tooltip) {
    border-radius: 0.5rem !important;
    border: 1px solid rgb(226 232 240) !important;
    background: #fff !important;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.08) !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    color: rgb(15 23 42) !important;
    padding: 4px 8px !important;
}
</style>
