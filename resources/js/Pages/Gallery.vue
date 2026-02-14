<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Gallery</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Explore the impact and activities of FEVOURD-K and our partner NGOs across Karnataka
                    </p>
                </div>

                <!-- Gallery Categories -->
                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    <button 
                        v-for="category in categories" 
                        :key="category.id"
                        @click="selectedCategory = category.id"
                        :class="selectedCategory === category.id ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-6 py-2 rounded-full border border-gray-300 transition-colors font-medium"
                    >
                        {{ category.name }}
                    </button>
                </div>

                <!-- Gallery Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div 
                        v-for="item in filteredGallery" 
                        :key="item.id"
                        class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer"
                        @click="openImage(item)"
                    >
                        <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                            <img 
                                :src="item.image" 
                                :alt="item.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                <h3 class="font-semibold text-lg">{{ item.title }}</h3>
                                <p class="text-sm opacity-90">{{ item.category }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredGallery.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No images found</h3>
                    <p class="mt-1 text-sm text-gray-500">No images available in this category yet.</p>
                </div>

                <!-- Lightbox Modal -->
                <div v-if="selectedImage" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90" @click="closeImage">
                    <div class="relative max-w-6xl max-h-[90vh] mx-4">
                        <img 
                            :src="selectedImage.image" 
                            :alt="selectedImage.title"
                            class="max-w-full max-h-[90vh] object-contain rounded-lg"
                        >
                        <button 
                            @click="closeImage"
                            class="absolute top-4 right-4 text-white bg-black/50 rounded-full p-2 hover:bg-black/70 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="absolute bottom-4 left-4 right-4 text-white text-center">
                            <h3 class="text-xl font-semibold mb-1">{{ selectedImage.title }}</h3>
                            <p class="text-sm opacity-90">{{ selectedImage.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const selectedCategory = ref('all')
const selectedImage = ref(null)

const categories = [
    { id: 'all', name: 'All' },
    { id: 'events', name: 'Events' },
    { id: 'campaigns', name: 'Campaigns' },
    { id: 'ngos', name: 'NGOs' },
    { id: 'impact', name: 'Impact Stories' },
    { id: 'volunteers', name: 'Volunteers' }
]

const gallery = [
    {
        id: 1,
        title: 'CSR/CSO Conclave 2026',
        category: 'events',
        image: 'https://images.unsplash.com/photo-1540575169166-79a169795a1c?w=800&h=600&fit=crop',
        description: 'Two-day regional event focused on environmental sustainability and climate change'
    },
    {
        id: 2,
        title: 'Education Campaign',
        category: 'campaigns',
        image: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&h=600&fit=crop',
        description: 'Supporting education initiatives across Karnataka'
    },
    {
        id: 3,
        title: 'NGO Partnership Meet',
        category: 'ngos',
        image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=600&fit=crop',
        description: 'Building partnerships with NGOs across the state'
    },
    {
        id: 4,
        title: 'Clean Water Initiative',
        category: 'impact',
        image: 'https://images.unsplash.com/photo-1548199973-0360a3a5c192?w=800&h=600&fit=crop',
        description: 'Providing clean water access to rural communities'
    },
    {
        id: 5,
        title: 'Volunteer Drive',
        category: 'volunteers',
        image: 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=800&h=600&fit=crop',
        description: 'Volunteers working together for community development'
    },
    {
        id: 6,
        title: 'Healthcare Camp',
        category: 'campaigns',
        image: 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&h=600&fit=crop',
        description: 'Free healthcare camps in rural areas'
    },
    {
        id: 7,
        title: 'Environmental Conservation',
        category: 'impact',
        image: 'https://images.unsplash.com/photo-1540206395-6880857a32f8?w=800&h=600&fit=crop',
        description: 'Tree plantation and environmental awareness programs'
    },
    {
        id: 8,
        title: 'Women Empowerment',
        category: 'campaigns',
        image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&h=600&fit=crop',
        description: 'Empowering women through skill development programs'
    }
]

const filteredGallery = computed(() => {
    if (selectedCategory.value === 'all') {
        return gallery
    }
    return gallery.filter(item => item.category === selectedCategory.value)
})

const openImage = (image) => {
    selectedImage.value = image
}

const closeImage = () => {
    selectedImage.value = null
}
</script>
