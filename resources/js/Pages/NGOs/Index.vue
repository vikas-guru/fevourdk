<template>
    <AppLayout hide-chrome-mobile>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-br from-[#0b2c33] via-[#103a52] to-[#0c1e3a] text-white overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0 bg-pattern-dots"></div>
                </div>

                <!-- Floating Elements -->
                <div class="absolute top-10 -left-10 w-60 h-60 sm:w-72 sm:h-72 bg-teal-500/20 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-10 -right-10 w-72 h-72 sm:w-96 sm:h-96 bg-amber-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>

                <div class="relative max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 pt-12 pb-16 sm:py-20 lg:py-24">
                    <div class="text-center">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 mb-5 sm:mb-7 rounded-full bg-white/10 border border-white/20 backdrop-blur-sm text-amber-300 text-[11px] sm:text-xs font-semibold uppercase tracking-[0.14em]">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.7-9.3a1 1 0 00-1.4-1.4L9 10.6 7.7 9.3a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z" clip-rule="evenodd" /></svg>
                            FEVOURD-K Verified Directory
                        </span>
                        <h1 class="ngo-hero__title text-[2rem] leading-tight sm:text-5xl lg:text-6xl font-semibold mb-4 sm:mb-6">
                            Verified NGOs
                        </h1>
                        <p class="text-base sm:text-xl lg:text-2xl text-blue-100/90 max-w-2xl lg:max-w-4xl mx-auto leading-relaxed mb-8 sm:mb-12">
                            Discover and connect with verified NGOs across Karnataka — organisations
                            making a real difference in their communities.
                        </p>

                        <!-- NGO Stats -->
                        <div class="grid grid-cols-3 gap-3 sm:gap-12 max-w-md sm:max-w-none mx-auto sm:flex sm:justify-center">
                            <div class="text-center">
                                <div class="ngo-hero__stat text-3xl sm:text-4xl font-semibold text-amber-400">{{ stats.total }}<span class="text-amber-400/70">+</span></div>
                                <div class="text-blue-200/80 text-[11px] sm:text-sm mt-1">Verified NGOs</div>
                            </div>
                            <div class="text-center">
                                <div class="ngo-hero__stat text-3xl sm:text-4xl font-semibold text-teal-300">{{ stats.districts }}</div>
                                <div class="text-blue-200/80 text-[11px] sm:text-sm mt-1">Districts</div>
                            </div>
                            <div class="text-center">
                                <div class="ngo-hero__stat text-3xl sm:text-4xl font-semibold text-sky-300">{{ stats.focusAreas }}</div>
                                <div class="text-blue-200/80 text-[11px] sm:text-sm mt-1">Focus Areas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wave Bottom -->
                <div class="absolute bottom-0 left-0 right-0 leading-[0]">
                    <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                        <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="#F9FAFB"/>
                    </svg>
                </div>
            </section>

            <!-- Filters Section -->
            <section class="py-4 sm:py-8 lg:py-12 bg-white/90 backdrop-blur-md sticky top-0 z-40 border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-3 lg:gap-0">
                        <!-- Search + mobile filter toggle -->
                        <div class="flex items-center gap-2.5 w-full lg:w-96">
                            <div class="relative flex-1">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search NGOs..."
                                    class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 shadow-sm"
                                >
                                <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <!-- Mobile-only filter toggle -->
                            <button
                                type="button"
                                @click="showFilters = !showFilters"
                                class="lg:hidden relative inline-flex items-center justify-center gap-1.5 px-4 h-[50px] rounded-xl border border-gray-300 bg-white text-gray-700 font-semibold text-sm shadow-sm active:scale-95 transition"
                                :class="{ 'ring-2 ring-teal-500 border-transparent': showFilters }"
                                :aria-expanded="showFilters"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M6 12h12M10 20h4" /></svg>
                                <span>Filters</span>
                                <span v-if="activeFilterCount" class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 px-1 grid place-items-center rounded-full bg-amber-500 text-white text-[11px] font-bold">{{ activeFilterCount }}</span>
                            </button>
                        </div>

                        <!-- Filter Options: stacked grid on mobile (toggle), inline row on desktop -->
                        <div
                            :class="showFilters ? 'grid' : 'hidden'"
                            class="grid-cols-1 sm:grid-cols-2 gap-2.5 lg:!flex lg:gap-3 lg:items-center"
                        >
                            <select v-model="selectedState" @change="onStateChange" class="ngo-filter w-full lg:w-auto">
                                <option value="">All States</option>
                                <option v-for="state in states" :key="state.id" :value="state.id">{{ state.name }}</option>
                            </select>

                            <select v-model="selectedDistrict" @change="onDistrictChange" class="ngo-filter w-full lg:w-auto">
                                <option value="">All Districts</option>
                                <option v-for="district in districts" :key="district.id" :value="district.id">{{ district.name }}</option>
                            </select>

                            <select v-model="selectedFocusArea" class="ngo-filter w-full lg:w-auto">
                                <option value="">All Focus Areas</option>
                                <option value="education">Education</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="environment">Environment</option>
                                <option value="women">Women Empowerment</option>
                                <option value="children">Children Welfare</option>
                                <option value="disability">Disability</option>
                                <option value="community">Community Development</option>
                            </select>

                            <select v-model="sortBy" class="ngo-filter w-full lg:w-auto">
                                <option value="newest">Newest First</option>
                                <option value="name">Alphabetical</option>
                                <option value="supporters">Most Supported</option>
                                <option value="followers">Most Followed</option>
                            </select>

                            <!-- Clear (mobile only, when filters active) -->
                            <button
                                v-if="activeFilterCount"
                                type="button"
                                @click="clearFilters"
                                class="lg:hidden col-span-full inline-flex items-center justify-center gap-1.5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 font-semibold text-sm active:scale-95 transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                Clear filters
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- NGOs Grid -->
            <section class="py-8 sm:py-12 lg:py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Results Count -->
                    <div class="mb-5 sm:mb-8">
                        <p class="text-sm sm:text-base text-gray-600">
                            Showing <span class="font-semibold text-gray-900">{{ filteredNGOs.data.length }}</span> of
                            <span class="font-semibold text-gray-900">{{ ngos.total }}</span> verified NGOs
                        </p>
                    </div>

                    <!-- NGOs Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-7">
                        <article v-for="(ngo, i) in filteredNGOs.data" :key="ngo.id"
                                 class="ngo-card group"
                                 :style="cardStyle(ngo, i)">

                            <!-- Banner -->
                            <div class="ngo-card__banner">
                                <div class="ngo-card__mesh"></div>
                                <div class="ngo-card__grain"></div>
                                <div class="ngo-card__sheen"></div>

                                <span v-if="primaryArea(ngo)" class="ngo-card__kicker">{{ primaryArea(ngo) }}</span>

                                <span class="ngo-card__verified" title="Verified by FEVOURD-K">
                                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.7-9.3a1 1 0 00-1.4-1.4L9 10.6 7.7 9.3a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z" clip-rule="evenodd" /></svg>
                                    Verified
                                </span>
                            </div>

                            <!-- Logo medallion -->
                            <div class="ngo-card__crest">
                                <img v-if="ngo.logo" :src="ngo.logo" :alt="ngo.name">
                                <span v-else>{{ initials(ngo.name) }}</span>
                            </div>

                            <!-- Body -->
                            <div class="ngo-card__body">
                                <h3 class="ngo-card__name">{{ ngo.name }}</h3>
                                <p class="ngo-card__loc">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><circle cx="12" cy="11" r="2.5" /></svg>
                                    {{ loc(ngo) }}
                                </p>

                                <p class="ngo-card__mission">{{ ngo.description }}</p>

                                <div class="ngo-card__chips" v-if="chips(ngo).length">
                                    <span v-for="area in chips(ngo)" :key="area" class="ngo-chip">{{ area }}</span>
                                    <span v-if="extra(ngo) > 0" class="ngo-chip ngo-chip--more">+{{ extra(ngo) }}</span>
                                </div>

                                <div class="ngo-card__stats">
                                    <div>
                                        <span class="num">{{ ngo.followers_count || 0 }}</span>
                                        <span class="lbl">Followers</span>
                                    </div>
                                    <div>
                                        <span class="num">{{ ngo.supporters_count || 0 }}</span>
                                        <span class="lbl">Supporters</span>
                                    </div>
                                    <div>
                                        <span class="num">{{ joinedYear(ngo) }}</span>
                                        <span class="lbl">Since</span>
                                    </div>
                                </div>

                                <div class="ngo-card__actions">
                                    <Link :href="`/ngos/${ngo.slug}`" class="ngo-btn ngo-btn--view">
                                        View profile
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6" /></svg>
                                    </Link>
                                    <button @click="toggleFollow(ngo)"
                                            :class="fs(ngo).following ? 'is-active' : ''"
                                            class="ngo-btn ngo-btn--follow"
                                            :title="fs(ngo).following ? 'Following' : 'Follow'">
                                        <svg viewBox="0 0 20 20" fill="currentColor"><path :d="fs(ngo).following ? 'M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4a1 1 0 011.4-1.4l3.3 3.3 7.3-7.3a1 1 0 011.4 0z' : 'M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z'" /></svg>
                                        <span>{{ fs(ngo).following ? 'Following' : 'Follow' }}</span>
                                    </button>
                                    <button @click="toggleSupport(ngo)"
                                            :class="fs(ngo).supporting ? 'is-active' : ''"
                                            class="ngo-btn ngo-btn--support"
                                            :title="fs(ngo).supporting ? 'Supporting' : 'Support'">
                                        <svg viewBox="0 0 24 24" :fill="fs(ngo).supporting ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    </button>
                                </div>
                            </div>
                        </article>
                    </div>
                    
                    <!-- Pagination -->
                    <div v-if="filteredNGOs.last_page > 1" class="mt-10">
                        <div class="flex justify-center items-center gap-2 sm:gap-3">
                            <Link
                                :href="filteredNGOs.prev_page_url || ''"
                                class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-colors"
                                :class="{ 'opacity-40 pointer-events-none': !filteredNGOs.prev_page_url }"
                            >
                                Prev
                            </Link>

                            <span class="px-3 sm:px-4 py-2.5 text-gray-600 text-sm whitespace-nowrap">
                                Page {{ filteredNGOs.current_page }} of {{ filteredNGOs.last_page }}
                            </span>

                            <Link
                                :href="filteredNGOs.next_page_url || ''"
                                class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-colors"
                                :class="{ 'opacity-40 pointer-events-none': !filteredNGOs.next_page_url }"
                            >
                                Next
                            </Link>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div v-if="filteredNGOs.data.length === 0" class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No NGOs found</h3>
                        <p class="text-gray-600">Try adjusting your search or filters to find NGOs in your area.</p>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="py-14 sm:py-20 bg-gradient-to-r from-[#0b2c33] via-[#103a52] to-[#0c1e3a] text-white">
                <div class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 text-center">
                    <h2 class="ngo-hero__title text-3xl sm:text-4xl font-semibold mb-4 sm:mb-6">Join Our NGO Network</h2>
                    <p class="text-base sm:text-xl mb-8 sm:mb-12 text-blue-100 leading-relaxed">
                        Are you an NGO looking to make a bigger impact? Join FEVOURD-K and connect with donors, 
                        volunteers, and partners across Karnataka.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <Link href="/register/ngo" class="bg-gradient-to-r from-yellow-400 to-orange-500 text-blue-900 px-8 py-4 rounded-xl font-bold hover:from-yellow-500 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0 3h.01M12 9l-3 3m0 0l-3 3m3-6h.01M9 16H3m0 0h18m0-6v6m0-6V3m0 6h18" />
                            </svg>
                            Register Your NGO
                        </Link>
                        <Link href="/donate" class="bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-bold hover:bg-white/20 transition-all duration-300 border border-white/20">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Support NGOs
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    ngos: {
        type: Object,
        required: true
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, districts: 0, focusAreas: 0 })
    },
    followState: {
        type: Object,
        default: () => ({})
    },
    authed: {
        type: Boolean,
        default: false
    }
})

// Social graph helpers — follow / support an NGO (works for every role).
const fs = (ngo) => props.followState?.[ngo.id] || { following: false, supporting: false }

// ── Card presentation helpers ───────────────────────────────────────────────
// Per-card banner hue rotates within a teal→blue family for subtle variety.
const cardStyle = (ngo, i) => ({
    '--hue': 150 + ((Number(ngo.id) * 37) % 70),
    '--delay': `${Math.min(i, 11) * 70}ms`,
})

const initials = (name = '') => name.trim().split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase() || '★'

// Build location from the eager-loaded state/district/city relations (real data).
const loc = (ngo) => {
    const parts = [ngo.city?.name, ngo.district?.name, ngo.state?.name]
        .filter(Boolean)
        .filter((v, i, a) => v !== a[i - 1])
    return parts.length ? parts.slice(0, 2).join(', ') : 'Karnataka, India'
}

const areas = (ngo) => Array.isArray(ngo.focus_areas) ? ngo.focus_areas.filter(Boolean) : []
const primaryArea = (ngo) => areas(ngo)[0] || ''
const chips = (ngo) => areas(ngo).slice(0, 3)
const extra = (ngo) => Math.max(0, areas(ngo).length - 3)
const joinedYear = (ngo) => {
    const y = ngo.created_at ? new Date(ngo.created_at).getFullYear() : null
    return y && !Number.isNaN(y) ? y : '—'
}

const toggleFollow = (ngo) => {
    if (!props.authed) { router.visit('/login'); return }
    router.post(`/ngos/${ngo.id}/follow`, {}, { preserveScroll: true, preserveState: false })
}

const toggleSupport = (ngo) => {
    if (!props.authed) { router.visit('/login'); return }
    router.post(`/ngos/${ngo.id}/support`, {}, { preserveScroll: true, preserveState: false })
}

// Reactive data
const searchQuery = ref('')
const selectedState = ref('')
const selectedDistrict = ref('')
const selectedFocusArea = ref('')
const sortBy = ref('newest')

const states = ref([])
const districts = ref([])

// Mobile filter drawer + active-filter awareness
const showFilters = ref(false)
const activeFilterCount = computed(() =>
    [selectedState.value, selectedDistrict.value, selectedFocusArea.value].filter(Boolean).length
    + (sortBy.value !== 'newest' ? 1 : 0)
)
const clearFilters = () => {
    selectedState.value = ''
    selectedDistrict.value = ''
    selectedFocusArea.value = ''
    sortBy.value = 'newest'
    districts.value = []
}

// Computed properties
const filteredNGOs = computed(() => {
    let filtered = props.ngos.data

    // Search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(ngo => 
            ngo.name.toLowerCase().includes(query) ||
            ngo.description.toLowerCase().includes(query)
        )
    }

    // State filter
    if (selectedState.value) {
        filtered = filtered.filter(ngo => ngo.state_id == selectedState.value)
    }

    // District filter
    if (selectedDistrict.value) {
        filtered = filtered.filter(ngo => ngo.district_id == selectedDistrict.value)
    }

    // Focus area filter
    if (selectedFocusArea.value) {
        filtered = filtered.filter(ngo => 
            ngo.focus_areas && ngo.focus_areas.includes(selectedFocusArea.value)
        )
    }

    // Sort
    switch (sortBy.value) {
        case 'name':
            filtered.sort((a, b) => a.name.localeCompare(b.name))
            break
        case 'supporters':
            filtered.sort((a, b) => (b.supporters_count || 0) - (a.supporters_count || 0))
            break
        case 'followers':
            filtered.sort((a, b) => (b.followers_count || 0) - (a.followers_count || 0))
            break
        case 'newest':
        default:
            filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
            break
    }

    return {
        data: filtered,
        current_page: props.ngos.current_page,
        last_page: props.ngos.last_page,
        prev_page_url: props.ngos.prev_page_url,
        next_page_url: props.ngos.next_page_url,
        total: props.ngos.total
    }
})

// Methods
const loadStates = async () => {
    try {
        const response = await fetch('/api/locations/states')
        const data = await response.json()
        states.value = data.states
    } catch (error) {
        console.error('Error loading states:', error)
    }
}

const loadDistricts = async (stateId) => {
    if (!stateId) {
        districts.value = []
        return
    }
    
    try {
        const response = await fetch(`/api/locations/districts?state_id=${stateId}`)
        const data = await response.json()
        districts.value = data.districts
    } catch (error) {
        console.error('Error loading districts:', error)
    }
}

const onStateChange = () => {
    selectedDistrict.value = ''
    loadDistricts(selectedState.value)
}

const onDistrictChange = () => {
    // District change logic handled by computed property
}

// Lifecycle
onMounted(() => {
    loadStates()
})
</script>

<style scoped>
.bg-pattern-dots {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Fraunces display type for the hero (matches the Impact Dossier cards) */
.ngo-hero__title {
    font-family: 'Fraunces', Georgia, serif;
    letter-spacing: -0.02em;
}
.ngo-hero__stat {
    font-family: 'Fraunces', Georgia, serif;
    line-height: 1;
}

/* Filter selects — shared look across the mobile drawer + desktop row */
.ngo-filter {
    appearance: none;
    -webkit-appearance: none;
    padding: 0.7rem 2.25rem 0.7rem 0.9rem;
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%236b7280' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    border: 1px solid #d1d5db;
    border-radius: 0.75rem;
    color: #111827;
    font-size: 0.875rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: box-shadow .2s ease, border-color .2s ease;
}
.ngo-filter:focus {
    outline: none;
    border-color: transparent;
    box-shadow: 0 0 0 2px hsl(174 62% 45%);
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 3;
    -o-line-clamp: 3;
}

/* ── Impact Dossier card ──────────────────────────────────────────────────── */
.ngo-card {
    --accent: 38 92% 50%;            /* saffron/amber accent (HSL parts) */
    --hue: 168;                       /* overridden per-card */
    position: relative;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid rgba(15, 23, 42, 0.06);
    box-shadow:
        0 1px 2px rgba(15, 23, 42, 0.04),
        0 12px 28px -12px rgba(15, 23, 42, 0.16);
    overflow: hidden;
    transition: transform .45s cubic-bezier(.2, .8, .2, 1), box-shadow .45s cubic-bezier(.2, .8, .2, 1);
    opacity: 0;
    transform: translateY(18px);
    animation: ngo-rise .7s cubic-bezier(.2, .8, .2, 1) forwards;
    animation-delay: var(--delay, 0ms);
    will-change: transform;
}
@keyframes ngo-rise {
    to { opacity: 1; transform: translateY(0); }
}
@media (prefers-reduced-motion: reduce) {
    .ngo-card { animation: none; opacity: 1; transform: none; }
}
.ngo-card:hover {
    transform: translateY(-8px);
    box-shadow:
        0 1px 2px rgba(15, 23, 42, 0.04),
        0 30px 60px -18px hsl(var(--hue) 60% 30% / .42),
        0 0 0 1px hsl(var(--hue) 50% 60% / .12);
}

/* Banner */
.ngo-card__banner {
    position: relative;
    height: 118px;
    overflow: hidden;
    background: hsl(var(--hue) 45% 16%);
}
.ngo-card__mesh {
    position: absolute;
    inset: -20%;
    background:
        radial-gradient(60% 80% at 18% 20%, hsl(var(--hue) 72% 42% / .95), transparent 60%),
        radial-gradient(55% 75% at 85% 15%, hsl(calc(var(--hue) + 28) 75% 46% / .9), transparent 62%),
        radial-gradient(70% 90% at 75% 95%, hsl(var(--accent) / .55), transparent 60%),
        linear-gradient(135deg, hsl(var(--hue) 55% 22%), hsl(calc(var(--hue) - 20) 50% 14%));
    transition: transform 1.1s cubic-bezier(.2, .8, .2, 1);
}
.ngo-card:hover .ngo-card__mesh { transform: scale(1.08) rotate(-1deg); }
.ngo-card__grain {
    position: absolute;
    inset: 0;
    opacity: .35;
    mix-blend-mode: overlay;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
}
.ngo-card__sheen {
    position: absolute;
    top: 0; left: -60%;
    width: 50%; height: 100%;
    background: linear-gradient(100deg, transparent, rgba(255,255,255,.35), transparent);
    transform: skewX(-18deg);
    transition: left .8s cubic-bezier(.2, .8, .2, 1);
}
.ngo-card:hover .ngo-card__sheen { left: 130%; }

.ngo-card__kicker {
    position: absolute;
    top: 14px; left: 16px;
    z-index: 2;
    padding: 5px 11px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #fff;
    background: rgba(255, 255, 255, .14);
    border: 1px solid rgba(255, 255, 255, .28);
    border-radius: 999px;
    backdrop-filter: blur(6px);
    max-width: 62%;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.ngo-card__verified {
    position: absolute;
    top: 14px; right: 16px;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px 5px 8px;
    font-size: 11px;
    font-weight: 700;
    color: hsl(38 95% 96%);
    background: hsl(var(--accent) / .22);
    border: 1px solid hsl(var(--accent) / .55);
    border-radius: 999px;
    backdrop-filter: blur(6px);
}
.ngo-card__verified svg { width: 14px; height: 14px; color: hsl(40 96% 70%); }

/* Logo medallion overlapping the banner */
.ngo-card__crest {
    position: relative;
    z-index: 3;
    width: 76px; height: 76px;
    margin: -38px 0 0 24px;
    border-radius: 20px;
    display: grid;
    place-items: center;
    background: #fff;
    border: 1px solid rgba(15, 23, 42, .06);
    box-shadow: 0 10px 24px -10px hsl(var(--hue) 50% 25% / .5);
    overflow: hidden;
    transition: transform .45s cubic-bezier(.2, .8, .2, 1);
}
.ngo-card:hover .ngo-card__crest { transform: translateY(-3px) rotate(-2deg); }
.ngo-card__crest img { width: 100%; height: 100%; object-fit: cover; }
.ngo-card__crest span {
    font-family: 'Fraunces', Georgia, serif;
    font-weight: 600;
    font-size: 26px;
    color: hsl(var(--hue) 45% 28%);
}

/* Body */
.ngo-card__body { padding: 14px 24px 22px; display: flex; flex-direction: column; flex: 1; }
.ngo-card__name {
    font-family: 'Fraunces', Georgia, serif;
    font-weight: 600;
    font-size: 21px;
    line-height: 1.18;
    color: #111827;
    letter-spacing: -.01em;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ngo-card__loc {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    font-weight: 500;
    color: #6b7280;
    margin-bottom: 12px;
}
.ngo-card__loc svg { width: 14px; height: 14px; color: hsl(var(--hue) 55% 45%); flex: none; }
.ngo-card__mission {
    font-size: 13.5px;
    line-height: 1.55;
    color: #4b5563;
    margin-bottom: 14px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 42px;
}
.ngo-card__chips { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px; }
.ngo-chip {
    padding: 4px 10px;
    font-size: 11.5px;
    font-weight: 600;
    color: hsl(var(--hue) 40% 30%);
    background: hsl(var(--hue) 55% 96%);
    border: 1px solid hsl(var(--hue) 45% 88%);
    border-radius: 8px;
}
.ngo-chip--more { color: #6b7280; background: #f3f4f6; border-color: #e5e7eb; }

/* Stats strip */
.ngo-card__stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    margin-bottom: 18px;
    border: 1px solid rgba(15, 23, 42, .06);
    border-radius: 14px;
    overflow: hidden;
    background: #fcfcfd;
}
.ngo-card__stats > div {
    text-align: center;
    padding: 11px 4px;
    min-width: 0;
    border-right: 1px solid rgba(15, 23, 42, .06);
}
.ngo-card__stats > div:last-child { border-right: 0; }
.ngo-card__stats .num {
    display: block;
    font-family: 'Fraunces', Georgia, serif;
    font-weight: 600;
    font-size: 19px;
    color: hsl(var(--hue) 45% 24%);
    line-height: 1;
    white-space: nowrap;
}
.ngo-card__stats .lbl {
    display: block;
    margin-top: 4px;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .02em;
    text-transform: uppercase;
    color: #9ca3af;
    white-space: nowrap;
}

/* Actions */
.ngo-card__actions { display: flex; gap: 8px; margin-top: auto; }
.ngo-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    height: 44px;
    border-radius: 13px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    transition: transform .2s ease, background .2s ease, box-shadow .2s ease, color .2s ease;
}
.ngo-btn:active { transform: scale(.97); }
.ngo-btn--view {
    flex: 1;
    color: #fff;
    background: linear-gradient(135deg, hsl(var(--hue) 60% 34%), hsl(calc(var(--hue) + 22) 58% 40%));
    box-shadow: 0 10px 20px -10px hsl(var(--hue) 60% 30% / .8);
}
.ngo-btn--view svg { width: 17px; height: 17px; transition: transform .25s ease; }
.ngo-btn--view:hover { box-shadow: 0 14px 26px -10px hsl(var(--hue) 60% 30% / .9); }
.ngo-btn--view:hover svg { transform: translateX(3px); }
.ngo-btn--follow {
    padding: 0 14px;
    color: hsl(var(--hue) 45% 30%);
    background: hsl(var(--hue) 55% 96%);
    border-color: hsl(var(--hue) 45% 86%);
}
.ngo-btn--follow svg { width: 16px; height: 16px; }
.ngo-btn--follow:hover { background: hsl(var(--hue) 55% 92%); }
.ngo-btn--follow.is-active {
    color: #fff;
    background: linear-gradient(135deg, hsl(var(--hue) 60% 34%), hsl(calc(var(--hue) + 22) 58% 40%));
    border-color: transparent;
}
.ngo-btn--support {
    width: 44px;
    color: hsl(347 80% 50%);
    background: hsl(347 90% 97%);
    border-color: hsl(347 80% 90%);
}
.ngo-btn--support svg { width: 18px; height: 18px; }
.ngo-btn--support:hover { background: hsl(347 90% 94%); }
.ngo-btn--support.is-active {
    color: #fff;
    background: linear-gradient(135deg, hsl(347 78% 52%), hsl(12 82% 56%));
    border-color: transparent;
    box-shadow: 0 10px 20px -10px hsl(347 78% 50% / .8);
}

/* Custom animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}
</style>
