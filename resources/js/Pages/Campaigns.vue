<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Active Campaigns</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Support ongoing campaigns and make a difference in communities across Karnataka
                    </p>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select v-model="filters.category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">All Categories</option>
                                <option value="education">Education</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="environment">Environment</option>
                                <option value="women">Women Empowerment</option>
                                <option value="children">Children Welfare</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <select v-model="filters.location" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">All Locations</option>
                                <option value="bangalore">Bangalore</option>
                                <option value="mysore">Mysore</option>
                                <option value="hubli">Hubli</option>
                                <option value="mangalore">Mangalore</option>
                                <option value="belgaum">Belgaum</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select v-model="filters.sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="latest">Latest First</option>
                                <option value="urgent">Urgent First</option>
                                <option value="goal">Goal Amount</option>
                                <option value="progress">Progress</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button @click="resetFilters" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Campaigns Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="campaign in filteredCampaigns" 
                        :key="campaign.id"
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300"
                    >
                        <div class="relative">
                            <img 
                                :src="campaign.image" 
                                :alt="campaign.title"
                                class="w-full h-48 object-cover"
                            >
                            <div class="absolute top-4 left-4">
                                <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ campaign.category }}
                                </span>
                            </div>
                            <div v-if="campaign.urgent" class="absolute top-4 right-4">
                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium animate-pulse">
                                    Urgent
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <img 
                                    :src="campaign.ngo.logo" 
                                    :alt="campaign.ngo.name"
                                    class="w-8 h-8 rounded-full mr-3"
                                >
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ campaign.ngo.name }}</h3>
                                    <p class="text-sm text-gray-500">{{ campaign.location }}</p>
                                </div>
                            </div>
                            
                            <h2 class="text-lg font-bold text-gray-900 mb-2">{{ campaign.title }}</h2>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ campaign.description }}</p>
                            
                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>₹{{ campaign.raised.toLocaleString() }} raised</span>
                                    <span>{{ campaign.progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div 
                                        class="bg-primary-600 h-2 rounded-full transition-all duration-300" 
                                        :style="{ width: `${campaign.progress}%` }"
                                    ></div>
                                </div>
                                <div class="flex justify-between text-sm text-gray-500 mt-1">
                                    <span>Goal: ₹{{ campaign.goal.toLocaleString() }}</span>
                                    <span>{{ campaign.days_left }} days left</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-3">
                                <inertia-link 
                                    :href="`/campaigns/${campaign.slug}`"
                                    class="flex-1 bg-primary-600 text-white text-center py-2 px-4 rounded-md hover:bg-primary-700 transition-colors font-medium"
                                >
                                    View Details
                                </inertia-link>
                                <button 
                                    @click="quickDonate(campaign)"
                                    class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-colors font-medium"
                                >
                                    Donate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredCampaigns.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No campaigns found</h3>
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
    category: '',
    location: '',
    sort: 'latest'
})

const campaigns = [
    {
        id: 1,
        title: 'Education for Rural Children',
        slug: 'education-rural-children',
        description: 'Providing quality education and school supplies to underprivileged children in rural Karnataka.',
        category: 'education',
        location: 'Bangalore',
        goal: 500000,
        raised: 325000,
        progress: 65,
        days_left: 15,
        urgent: true,
        image: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&h=400&fit=crop',
        ngo: {
            name: 'Rural Education Trust',
            logo: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=100&h=100&fit=crop&crop=face'
        }
    },
    {
        id: 2,
        title: 'Clean Water Initiative',
        slug: 'clean-water-initiative',
        description: 'Installing water purification systems in villages to provide access to clean drinking water.',
        category: 'environment',
        location: 'Mysore',
        goal: 750000,
        raised: 450000,
        progress: 60,
        days_left: 22,
        urgent: false,
        image: 'https://images.unsplash.com/photo-1548199973-0360a3a5c192?w=600&h=400&fit=crop',
        ngo: {
            name: 'Water for All Foundation',
            logo: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&h=100&fit=crop&crop=face'
        }
    },
    {
        id: 3,
        title: 'Women Empowerment Program',
        slug: 'women-empowerment-program',
        description: 'Skill development and entrepreneurship training for women in rural communities.',
        category: 'women',
        location: 'Hubli',
        goal: 300000,
        raised: 180000,
        progress: 60,
        days_left: 30,
        urgent: false,
        image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&h=400&fit=crop',
        ngo: {
            name: 'Women Empowerment NGO',
            logo: 'https://images.unsplash.com/photo-1494790108755-2616b332c1ca?w=100&h=100&fit=crop&crop=face'
        }
    },
    {
        id: 4,
        title: 'Healthcare Camps',
        slug: 'healthcare-camps',
        description: 'Free medical check-ups and treatments for underserved communities.',
        category: 'healthcare',
        location: 'Mangalore',
        goal: 400000,
        raised: 280000,
        progress: 70,
        days_left: 10,
        urgent: true,
        image: 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&h=400&fit=crop',
        ngo: {
            name: 'Healthcare Plus',
            logo: 'https://images.unsplash.com/photo-1559839734-f1b5b8d0d5b3?w=100&h=100&fit=crop&crop=face'
        }
    },
    {
        id: 5,
        title: 'Child Nutrition Program',
        slug: 'child-nutrition-program',
        description: 'Providing nutritious meals and supplements to malnourished children.',
        category: 'children',
        location: 'Belgaum',
        goal: 250000,
        raised: 125000,
        progress: 50,
        days_left: 25,
        urgent: false,
        image: 'https://images.unsplash.com/photo-1532938911079-1b618b3e38a7?w=600&h=400&fit=crop',
        ngo: {
            name: 'Child Welfare Society',
            logo: 'https://images.unsplash.com/photo-1544005173-67e0d8bc1d8d?w=100&h=100&fit=crop&crop=face'
        }
    },
    {
        id: 6,
        title: 'Tree Plantation Drive',
        slug: 'tree-plantation-drive',
        description: 'Planting 10,000 trees to combat climate change and improve air quality.',
        category: 'environment',
        location: 'Bangalore',
        goal: 200000,
        raised: 80000,
        progress: 40,
        days_left: 45,
        urgent: false,
        image: 'https://images.unsplash.com/photo-1540206395-68808572332f?w=600&h=400&fit=crop',
        ngo: {
            name: 'Green Earth Foundation',
            logo: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face'
        }
    }
]

const filteredCampaigns = computed(() => {
    let result = [...campaigns]
    
    // Filter by category
    if (filters.value.category) {
        result = result.filter(c => c.category === filters.value.category)
    }
    
    // Filter by location
    if (filters.value.location) {
        result = result.filter(c => c.location.toLowerCase() === filters.value.location.toLowerCase())
    }
    
    // Sort
    switch (filters.value.sort) {
        case 'urgent':
            result.sort((a, b) => (b.urgent ? 1 : 0) - (a.urgent ? 1 : 0))
            break
        case 'goal':
            result.sort((a, b) => b.goal - a.goal)
            break
        case 'progress':
            result.sort((a, b) => b.progress - a.progress)
            break
        default: // latest
            result.sort((a, b) => b.days_left - a.days_left)
    }
    
    return result
})

const resetFilters = () => {
    filters.value = {
        category: '',
        location: '',
        sort: 'latest'
    }
}

const quickDonate = (campaign) => {
    router.visit(`/donate?campaign=${campaign.id}`)
}
</script>
