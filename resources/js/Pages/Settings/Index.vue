<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
                    </div>
                    
                    <form @submit.prevent="updateSettings" class="p-6 space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notifications</h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input v-model="form.notifications" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700">Enable notifications</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input v-model="form.email_notifications" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700">Email notifications</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input v-model="form.sms_notifications" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700">SMS notifications</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Preferences</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                                    <select v-model="form.language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        <option value="">Select language</option>
                                        <option value="en">English</option>
                                        <option value="kn">Kannada</option>
                                        <option value="hi">Hindi</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                    <select v-model="form.timezone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        <option value="">Select timezone</option>
                                        <option value="Asia/Kolkata">India Standard Time (IST)</option>
                                        <option value="UTC">UTC</option>
                                    </select>
                                </div>
                            </div>
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
    notifications: true,
    email_notifications: true,
    sms_notifications: false,
    language: 'en',
    timezone: 'Asia/Kolkata'
})

const processing = ref(false)

const updateSettings = () => {
    processing.value = true
    router.put('/settings', form.value, {
        onSuccess: () => {
            processing.value = false
        },
        onError: () => {
            processing.value = false
        }
    })
}
</script>
