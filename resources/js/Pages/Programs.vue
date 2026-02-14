<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    programs: Array,
})

const getCategoryColor = (category) => {
    const colors = {
        'Capacity Building': 'bg-blue-100 text-blue-800',
        'Women Empowerment': 'bg-pink-100 text-pink-800',
        'Education & Child Welfare': 'bg-green-100 text-green-800',
        'Health & Well-being': 'bg-red-100 text-red-800',
        'Environmental Sustainability': 'bg-emerald-100 text-emerald-800',
        'Rural Development': 'bg-yellow-100 text-yellow-800',
        'Disaster Management': 'bg-orange-100 text-orange-800',
    }
    return colors[category] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h1 class="text-4xl font-bold mb-4">Programs & Activities</h1>
                    <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                        FEVOURD-K is committed to empowering marginalized communities and strengthening NGOs through impactful programs and activities. Our initiatives focus on sustainable development, capacity building, and social transformation across Karnataka.
                    </p>
                </div>
            </div>
        </div>

        <!-- Programs Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Featured Programs -->
            <div v-if="programs.filter(p => p.is_featured).length > 0" class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Featured Programs</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div v-for="program in programs.filter(p => p.is_featured)" :key="program.id" class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div v-if="program.image" class="h-64">
                            <img :src="`/storage/${program.image}`" :alt="program.title" class="w-full h-full object-cover">
                        </div>
                        <div v-else class="h-64 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <div class="text-white text-center">
                                <div class="text-5xl font-bold mb-2">{{ program.title.charAt(0) }}</div>
                                <div class="text-lg">{{ program.category }}</div>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-2xl font-bold text-gray-900">{{ program.title }}</h3>
                                <span :class="getCategoryColor(program.category)" class="text-sm px-3 py-1 rounded-full">
                                    {{ program.category }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 text-lg mb-6">{{ program.description }}</p>
                            
                            <div v-if="program.content" class="prose max-w-none text-gray-700 mb-6" v-html="program.content"></div>
                            
                            <div class="flex justify-between items-center">
                                <Link href="/contact" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                                    Get Involved
                                </Link>
                                <span class="text-sm text-gray-500">Sort Order: {{ program.sort_order }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Programs -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">All Programs</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="program in programs.filter(p => !p.is_featured)" :key="program.id" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div v-if="program.image" class="h-48">
                            <img :src="`/storage/${program.image}`" :alt="program.title" class="w-full h-full object-cover">
                        </div>
                        <div v-else class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <div class="text-white text-center">
                                <div class="text-4xl font-bold mb-2">{{ program.title.charAt(0) }}</div>
                                <div class="text-sm">{{ program.category }}</div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-900">{{ program.title }}</h3>
                                <span :class="getCategoryColor(program.category)" class="text-xs px-2 py-1 rounded-full">
                                    {{ program.category }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ program.description }}</p>
                            
                            <div class="flex justify-between items-center">
                                <Link href="/contact" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Learn More →
                                </Link>
                                <span class="text-xs text-gray-500">{{ program.sort_order }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="programs.length === 0" class="text-center py-16">
                <div class="text-gray-400 text-6xl mb-4">📋</div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No programs available</h3>
                <p class="text-gray-600">Check back soon for updates on our programs and activities.</p>
            </div>
        </div>
    </div>
</template>
