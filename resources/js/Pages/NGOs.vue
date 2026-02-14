<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Our Partner NGOs</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Discover and connect with verified NGOs working across Karnataka to create positive change
                    </p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">800+</div>
                        <div class="text-gray-600">Total NGOs</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">31</div>
                        <div class="text-gray-600">Districts</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-accent-600 mb-2">15+</div>
                        <div class="text-gray-600">Focus Areas</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">100K+</div>
                        <div class="text-gray-600">Lives Impacted</div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Focus Area</label>
                            <select v-model="filters.focus_area" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">All Focus Areas</option>
                                <option value="education">Education</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="environment">Environment</option>
                                <option value="women">Women Empowerment</option>
                                <option value="children">Children Welfare</option>
                                <option value="rural">Rural Development</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                            <select v-model="filters.district" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">All Districts</option>
                                <option value="bangalore">Bangalore</option>
                                <option value="mysore">Mysore</option>
                                <option value="hubli">Hubli</option>
                                <option value="mangalore">Mangalore</option>
                                <option value="belgaum">Belgaum</option>
                                <option value="gulbarga">Gulbarga</option>
                                <option value="bellary">Bellary</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Verification Status</label>
                            <select v-model="filters.verification_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">All NGOs</option>
                                <option value="verified">Verified Only</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button @click="resetFilters" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- NGOs Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="ngo in filteredNGOs" 
                        :key="ngo.id"
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300"
                    >
                        <div class="relative">
                            <img 
                                :src="ngo.logo" 
                                :alt="ngo.name"
                                class="w-full h-48 object-cover bg-gray-100"
                            >
                            <div class="absolute top-4 right-4">
                                <span 
                                    :class="ngo.verification_status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                    class="px-3 py-1 rounded-full text-sm font-medium"
                                >
                                    {{ ngo.verification_status === 'verified' ? '✓ Verified' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ ngo.name }}</h2>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ ngo.description }}</p>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ ngo.district }}, {{ ngo.state }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ ngo.focus_area }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Since {{ ngo.founded_year }}
                                </div>
                            </div>
                            
                            <div class="flex space-x-3">
                                <inertia-link 
                                    :href="`/ngos/${ngo.slug}`"
                                    class="flex-1 bg-primary-600 text-white text-center py-2 px-4 rounded-md hover:bg-primary-700 transition-colors font-medium"
                                >
                                    View Profile
                                </inertia-link>
                                <button 
                                    @click="supportNGO(ngo)"
                                    class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-colors font-medium"
                                >
                                    Support
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredNGOs.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No NGOs found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or check back later.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const filters = ref({
    focus_area: '',
    district: '',
    verification_status: ''
})

const ngos = [
    {
        id: 1,
        name: 'Rural Education Trust',
        slug: 'rural-education-trust',
        description: 'Dedicated to providing quality education to underprivileged children in rural Karnataka through school infrastructure development, teacher training, and scholarship programs.',
        focus_area: 'education',
        district: 'Bangalore',
        state: 'Karnataka',
        founded_year: 2010,
        verification_status: 'verified',
        logo: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=400&fit=crop'
    },
    {
        id: 2,
        name: 'Water for All Foundation',
        slug: 'water-for-all-foundation',
        description: 'Working towards ensuring clean drinking water access to all rural communities through water purification systems, well restoration, and water conservation education.',
        focus_area: 'environment',
        district: 'Mysore',
        state: 'Karnataka',
        founded_year: 2015,
        verification_status: 'verified',
        logo: 'https://images.unsplash.com/photo-1548199973-0360a3a5c192?w=600&h=400&fit=crop'
    },
    {
        id: 3,
        name: 'Women Empowerment NGO',
        slug: 'women-empowerment-ngo',
        description: 'Empowering women through skill development programs, microfinance initiatives, and awareness campaigns to promote gender equality and economic independence.',
        focus_area: 'women',
        district: 'Hubli',
        state: 'Karnataka',
        founded_year: 2012,
        verification_status: 'verified',
        logo: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&h=400&fit=crop'
    },
    {
        id: 4,
        name: 'Healthcare Plus',
        slug: 'healthcare-plus',
        description: 'Providing affordable healthcare services to rural communities through mobile medical camps, health awareness programs, and partnerships with local hospitals.',
        focus_area: 'healthcare',
        district: 'Mangalore',
        state: 'Karnataka',
        founded_year: 2008,
        verification_status: 'verified',
        logo: 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&h=400&fit=crop'
    },
    {
        id: 5,
        name: 'Child Welfare Society',
        slug: 'child-welfare-society',
        description: 'Working for the welfare of children through nutrition programs, education support, healthcare initiatives, and protection from exploitation and abuse.',
        focus_area: 'children',
        district: 'Belgaum',
        state: 'Karnataka',
        founded_year: 2016,
        verification_status: 'pending',
        logo: 'https://images.unsplash.com/photo-1532938911079-1b618b3e38a7?w=600&h=400&fit=crop'
    },
    {
        id: 6,
        name: 'Green Earth Foundation',
        slug: 'green-earth-foundation',
        description: 'Environmental conservation organization focused on tree plantation, wildlife protection, renewable energy promotion, and environmental education in schools.',
        focus_area: 'environment',
        district: 'Bangalore',
        state: 'Karnataka',
        founded_year: 2014,
        verification_status: 'verified',
        logo: 'https://images.unsplash.com/photo-1540206395-68808572332f?w=600&h=400&fit=crop'
    }
]

const filteredNGOs = computed(() => {
    let result = [...ngos]
    
    // Filter by focus area
    if (filters.value.focus_area) {
        result = result.filter(ngo => ngo.focus_area === filters.value.focus_area)
    }
    
    // Filter by district
    if (filters.value.district) {
        result = result.filter(ngo => ngo.district.toLowerCase() === filters.value.district.toLowerCase())
    }
    
    // Filter by verification status
    if (filters.value.verification_status) {
        result = result.filter(ngo => ngo.verification_status === filters.value.verification_status)
    }
    
    return result
})

const resetFilters = () => {
    filters.value = {
        focus_area: '',
        district: '',
        verification_status: ''
    }
}

const supportNGO = (ngo) => {
    router.visit(`/donate?ngo=${ngo.id}`)
}
</script>
