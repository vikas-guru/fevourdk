<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
                    </div>
                    
                    <form @submit.prevent="updateProfile" class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input v-model="form.name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <div v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input v-model="form.email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <div v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input v-model="form.phone" type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <div v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                            <textarea v-model="form.bio" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                            <div v-if="errors.bio" class="text-red-600 text-sm mt-1">{{ errors.bio }}</div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <inertia-link href="/dashboard" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </inertia-link>
                            <button type="submit" :disabled="processing" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 disabled:bg-gray-400">
                                {{ processing ? 'Saving...' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    user: Object,
    errors: Object
})

const form = ref({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone || '',
    bio: props.user.bio || ''
})

const processing = ref(false)

const updateProfile = () => {
    processing.value = true
    router.put('/profile', form.value, {
        onSuccess: () => {
            processing.value = false
        },
        onError: () => {
            processing.value = false
        }
    })
}
</script>
