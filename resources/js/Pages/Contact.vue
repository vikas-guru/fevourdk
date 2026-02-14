<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50">
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-br from-teal-900 via-cyan-800 to-blue-900 text-white py-20">
                <div class="absolute inset-0 opacity-10 bg-pattern-dots"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl md:text-5xl font-bold mb-6">Contact Us</h1>
                        <p class="text-xl md:text-2xl max-w-4xl mx-auto text-teal-100 leading-relaxed">
                            Get in touch with FEVOURD-K for collaboration and support
                        </p>
                    </div>
                </div>
            </section>

            <!-- Contact Information -->
            <section class="py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Contact Form -->
                        <div>
                            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                                
                                <form @submit.prevent="submitForm" class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                        <input type="text" v-model="form.name" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                        <input type="email" v-model="form.email" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                        <input type="text" v-model="form.subject" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                        <textarea v-model="form.message" rows="5" required
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                    </div>
                                    
                                    <button type="submit" 
                                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                        Send Message
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Contact Details -->
                        <div>
                            <div class="bg-gradient-to-br from-blue-50 to-teal-50 rounded-2xl shadow-lg p-8 border border-blue-100">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h2>
                                
                                <!-- Office Address -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657l-3.414-3.414-1.414 1.414L12 12l6.586 6.586a2 2 0 002.828 0 1.414-1.414L17.657 16.657z"/>
                                        </svg>
                                        Office Address
                                    </h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <p class="text-gray-700">
                                            <strong>FEVOURD-K</strong><br>
                                            #37, 2nd Floor, 6th Cross, 1st Main<br>
                                            Chamarajapete, Bengaluru<br>
                                            Karnataka - 560018
                                        </p>
                                    </div>
                                </div>

                                <!-- Contact Numbers -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 00-2-2V5a2 2 0 00-2-2h14a2 2 0 012 2z"/>
                                        </svg>
                                        Phone Numbers
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <a href="tel:9448464171" class="bg-white p-4 rounded-lg hover:bg-blue-50 transition-colors group">
                                            <div class="text-blue-600 font-bold">9448464171</div>
                                            <div class="text-gray-500 text-sm">Primary</div>
                                        </a>
                                        <a href="tel:7483159735" class="bg-white p-4 rounded-lg hover:bg-blue-50 transition-colors group">
                                            <div class="text-blue-600 font-bold">7483159735</div>
                                            <div class="text-gray-500 text-sm">Secondary</div>
                                        </a>
                                        <a href="tel:9448190613" class="bg-white p-4 rounded-lg hover:bg-blue-50 transition-colors group">
                                            <div class="text-blue-600 font-bold">9448190613</div>
                                            <div class="text-gray-500 text-sm">Support</div>
                                        </a>
                                        <a href="tel:9986020755" class="bg-white p-4 rounded-lg hover:bg-blue-50 transition-colors group">
                                            <div class="text-blue-600 font-bold">9986020755</div>
                                            <div class="text-gray-500 text-sm">Support</div>
                                        </a>
                                        <a href="tel:9482084090" class="bg-white p-4 rounded-lg hover:bg-blue-50 transition-colors group">
                                            <div class="text-blue-600 font-bold">9482084090</div>
                                            <div class="text-gray-500 text-sm">Support</div>
                                        </a>
                                        <a href="tel:6363626829" class="bg-white p-4 rounded-lg hover:bg-blue-50 transition-colors group">
                                            <div class="text-blue-600 font-bold">6363626829</div>
                                            <div class="text-gray-500 text-sm">Support</div>
                                        </a>
                                        <a href="tel:9353887956" class="bg-white p-4 rounded-lg hover:bg-blue-50 transition-colors group">
                                            <div class="text-blue-600 font-bold">9353887956</div>
                                            <div class="text-gray-500 text-sm">Support</div>
                                        </a>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a15 15 0 01.5.54 1.48 1.48.41.85.59L12 13l-4.17-4.17a15 15 0 01-5.54-1.48-1.48-1.41-.85-.59L12 13l4.17 4.17a15 15 0 015.54 1.48 1.48 1.41.85.59z"/>
                                        </svg>
                                        Email Address
                                    </h3>
                                    <a href="mailto:fevourd.karnataka@gmail.com" 
                                       class="bg-white p-4 rounded-lg hover:bg-blue-50 transition-colors group block">
                                        <div class="text-blue-600 font-bold">fevourd.karnataka@gmail.com</div>
                                        <div class="text-gray-500 text-sm">Click to email us</div>
                                    </a>
                                </div>

                                <!-- Social Media -->
                                <div class="mt-8 pt-8 border-t border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Follow Us</h3>
                                    <div class="flex space-x-4">
                                        <a href="#" class="bg-blue-600 p-3 rounded-lg text-white hover:bg-blue-700 transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s5.373 12-12 12 5.373 0 012 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0v-1.5a1.5 1.5 0 01-3 0z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="bg-green-600 p-3 rounded-lg text-white hover:bg-green-700 transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M4.98 3.5c0 1.381 1.112-2.5 2.5v13C4.98 18.614 2.5 20 2.5 2.5 0 13.114 0 13.114 0 0-1.114-2.5-2.5V6c0-1.381-1.112-2.5-2.5z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const form = ref({
    name: '',
    email: '',
    subject: '',
    message: ''
})

const submitForm = () => {
    console.log('Form submitted:', form.value)
}
</script>

<style scoped>
.bg-pattern-dots {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>
