<template>
    <AppLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 text-white overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0 bg-pattern-dots"></div>
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
                
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-400 to-purple-500 rounded-3xl shadow-2xl mb-8 backdrop-blur-sm border border-white/20 transform hover:scale-110 transition-all duration-300">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5-10v4a1 1 0 011-1h2a1 1 0 011 1v4m-6 0a1 1 0 00-1 1h2a1 1 0 011 1v4m0 0V8a2 2 0 002 2h2a2 2 0 002-2V6a2 2 0 00-2-2H2a2 2 0 00-2-2v2z" />
                            </svg>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-bold mb-6 bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                            Verified NGOs
                        </h1>
                        <p class="text-xl lg:text-2xl text-blue-200 max-w-4xl mx-auto leading-relaxed mb-8">
                            Discover and connect with verified NGOs across Karnataka. 
                            These organizations are making a real difference in their communities.
                        </p>
                        
                        <!-- NGO Stats -->
                        <div class="flex justify-center space-x-12 mb-12">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-yellow-400">{{ ngos.total }}+</div>
                                <div class="text-blue-200 text-sm">Verified NGOs</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold text-green-400">{{ ngos.per_page }}</div>
                                <div class="text-blue-200 text-sm">Showing Now</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold text-purple-400">{{ ngos.states_count }}</div>
                                <div class="text-blue-200 text-sm">States Covered</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Wave Bottom -->
                <div class="absolute bottom-0 left-0 right-0">
                    <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="#F9FAFB"/>
                    </svg>
                </div>
            </section>

            <!-- Filters Section -->
            <section class="py-12 bg-white/80 backdrop-blur-sm sticky top-0 z-40 border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
                        <!-- Search -->
                        <div class="relative w-full lg:w-96">
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search NGOs..." 
                                class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm"
                            >
                            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        
                        <!-- Filter Options -->
                        <div class="flex space-x-4">
                            <select v-model="selectedState" @change="onStateChange" class="px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm">
                                <option value="">All States</option>
                                <option v-for="state in states" :key="state.id" :value="state.id">{{ state.name }}</option>
                            </select>
                            
                            <select v-model="selectedDistrict" @change="onDistrictChange" class="px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm">
                                <option value="">All Districts</option>
                                <option v-for="district in districts" :key="district.id" :value="district.id">{{ district.name }}</option>
                            </select>
                            
                            <select v-model="selectedFocusArea" class="px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm">
                                <option value="">All Focus Areas</option>
                                <option value="education">Education</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="environment">Environment</option>
                                <option value="women">Women Empowerment</option>
                                <option value="children">Children Welfare</option>
                                <option value="disability">Disability</option>
                                <option value="community">Community Development</option>
                            </select>
                            
                            <select v-model="sortBy" class="px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm">
                                <option value="newest">Newest First</option>
                                <option value="name">Alphabetical</option>
                                <option value="campaigns">Most Campaigns</option>
                                <option value="verified">Recently Verified</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- NGOs Grid -->
            <section class="py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Results Count -->
                    <div class="mb-8">
                        <p class="text-gray-600">
                            Showing <span class="font-semibold text-gray-900">{{ filteredNGOs.length }}</span> of 
                            <span class="font-semibold text-gray-900">{{ ngos.total }}</span> verified NGOs
                        </p>
                    </div>
                    
                    <!-- NGOs Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="ngo in filteredNGOs.data" :key="ngo.id" 
                             class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                            
                            <!-- NGO Header -->
                            <div class="relative h-48 bg-gradient-to-br from-blue-500 to-purple-600 p-6">
                                <!-- NGO Logo -->
                                <div class="absolute top-4 left-4">
                                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                        <img v-if="ngo.logo" :src="ngo.logo" :alt="ngo.name" class="w-12 h-12 object-contain">
                                        <svg v-else class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Verification Badge -->
                                <div class="absolute top-4 right-4">
                                    <div class="bg-green-500 text-white rounded-lg px-3 py-1 shadow-lg">
                                        <div class="text-xs font-bold flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010-1.414l-8 8a1 1 0 01-1.414 0l-8 8a1 1 0 01-1.414 0l8-8a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- NGO Name -->
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-xl font-bold text-white">{{ ngo.name }}</h3>
                                    <p class="text-blue-100 text-sm">{{ ngo.getShortLocation }}</p>
                                </div>
                            </div>
                            
                            <!-- NGO Content -->
                            <div class="p-6">
                                <!-- Focus Areas -->
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Focus Areas</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="area in ngo.focus_areas" :key="area" 
                                              class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                            {{ area }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Description -->
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ ngo.description }}
                                </p>
                                
                                <!-- Location Info -->
                                <div class="flex items-center mb-4 text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a1.998 1.998 0 010-2.828l4.244-4.244a1.998 1.998 0 012.828 0l4.244 4.244a1.998 1.998 0 010 2.828z" />
                                    </svg>
                                    <span>{{ ngo.getFullLocation }}</span>
                                </div>
                                
                                <!-- Stats -->
                                <div class="grid grid-cols-3 gap-4 mb-6">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">{{ ngo.campaigns_count || 0 }}</div>
                                        <div class="text-xs text-gray-500">Campaigns</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-600">{{ ngo.donors_count || 0 }}</div>
                                        <div class="text-xs text-gray-500">Donors</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-purple-600">{{ ngo.years_active || 0 }}</div>
                                        <div class="text-xs text-gray-500">Years Active</div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex space-x-3">
                                    <Link :href="`/ngos/${ngo.slug}`" class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-xl font-bold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        View Profile
                                    </Link>
                                    <button class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white py-3 px-4 rounded-xl font-bold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Donate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div v-if="filteredNGOs.last_page > 1" class="mt-8">
                        <div class="flex justify-center">
                            <Link 
                                :href="filteredNGOs.prev_page_url" 
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                                :class="{ 'opacity-50 cursor-not-allowed': !filteredNGOs.prev_page_url }"
                            >
                                Previous
                            </Link>
                            
                            <span class="px-4 py-2 text-gray-700">
                                Page {{ filteredNGOs.current_page }} of {{ filteredNGOs.last_page }}
                            </span>
                            
                            <Link 
                                :href="filteredNGOs.next_page_url" 
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                                :class="{ 'opacity-50 cursor-not-allowed': !filteredNGOs.next_page_url }"
                            >
                                Next
                            </Link>
                        </div>
                    </div>
                    
                    <!-- No Results -->
                    <div v-if="filteredNGOs.length === 0" class="text-center py-16">
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
            <section class="py-20 bg-gradient-to-r from-blue-900 via-purple-900 to-indigo-900 text-white">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-4xl font-bold mb-6">Join Our NGO Network</h2>
                    <p class="text-xl mb-12 text-blue-100 leading-relaxed">
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
    }
})

// Reactive data
const searchQuery = ref('')
const selectedState = ref('')
const selectedDistrict = ref('')
const selectedFocusArea = ref('')
const sortBy = ref('newest')

const states = ref([])
const districts = ref([])

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
        case 'campaigns':
            filtered.sort((a, b) => (b.campaigns_count || 0) - (a.campaigns_count || 0))
            break
        case 'verified':
            filtered.sort((a, b) => new Date(b.verified_at) - new Date(a.verified_at))
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

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 3;
    -o-line-clamp: 3;
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
