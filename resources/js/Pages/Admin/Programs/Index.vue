<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    programs: Array,
})

const deleteProgram = (programId) => {
    if (confirm('Are you sure you want to delete this program?')) {
        router.delete(`/admin/programs/${programId}`)
    }
}
</script>

<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Page Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Programs</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage your organization's programs and activities</p>
                </div>
                <a href="/admin/programs/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Add New Program
                </a>
            </div>

            <!-- Programs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="program in programs" :key="program.id" class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div v-if="program.image" class="h-48 bg-gray-200">
                        <img :src="`/storage/${program.image}`" :alt="program.title" class="w-full h-full object-cover">
                    </div>
                    <div v-else class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <div class="text-4xl font-bold mb-2">{{ program.title.charAt(0).toUpperCase() }}</div>
                            <div class="text-sm">{{ program.category }}</div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900">{{ program.title }}</h3>
                                <div class="flex space-x-2">
                                    <span v-if="program.is_featured" class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Featured</span>
                                    <span :class="program.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="text-xs px-2 py-1 rounded-full">
                                        {{ program.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 text-sm line-clamp-3">{{ program.description }}</p>
                        
                        <div class="flex justify-between items-center mt-4">
                            <div class="flex space-x-2">
                                <a :href="`/admin/programs/${program.id}/edit`" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Edit
                                </a>
                                <button @click="deleteProgram(program.id)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete
                                </button>
                            </div>
                            <div class="text-xs text-gray-500">
                                Sort Order: {{ program.sort_order }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="programs.length === 0" class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">📋</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No programs yet</h3>
                <p class="text-gray-600 mb-6">Get started by creating your first program.</p>
                <a href="/admin/programs/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Create Program
                </a>
            </div>
        </div>
    </AdminLayout>
</template>
