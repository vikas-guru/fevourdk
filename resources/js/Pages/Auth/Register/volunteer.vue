<template>
    <AppLayout title="Volunteer Registration - FEVOURD-K">
        <div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50 py-12">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Volunteer Registration</h1>
                    <p class="text-gray-600">Join our network of volunteers making a difference</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
                    <form @submit.prevent="submitForm">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" v-model="form.name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" v-model="form.email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                                <input type="tel" v-model="form.phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Areas of Interest</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="form.interests" value="education" class="mr-2">
                                        Education
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="form.interests" value="healthcare" class="mr-2">
                                        Healthcare
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="form.interests" value="environment" class="mr-2">
                                        Environment
                                    </label>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" v-model="form.terms_accepted" class="mr-2" required>
                                <label class="text-sm text-gray-600">I agree to the terms and conditions</label>
                            </div>
                            
                            <button type="submit" :disabled="form.processing" class="w-full bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                <span v-if="form.processing">Registering...</span>
                                <span v-else>Register as Volunteer</span>
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-6">
                        <inertia-link href="/register" class="text-gray-600 hover:text-gray-800 transition-colors">
                            ← Back to Role Selection
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const form = useForm({
    name: '',
    email: '',
    phone: '',
    interests: [],
    terms_accepted: false
})

const submitForm = () => {
    form.post('/register/volunteer', {
        onSuccess: () => {
            router.push('/dashboard')
        }
    })
}
</script>
