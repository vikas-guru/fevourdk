<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Children's Rights</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Protecting and empowering children through education, healthcare, and advocacy programs across Karnataka.
                    </p>
                </div>

                <!-- Stats Section -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">50,000+</div>
                        <div class="text-gray-600">Children Reached</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">200+</div>
                        <div class="text-gray-600">Schools Supported</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">15</div>
                        <div class="text-gray-600">Districts Covered</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">100+</div>
                        <div class="text-gray-600">NGO Partners</div>
                    </div>
                </div>

                <!-- Programs Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Education Programs</h2>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                School enrollment drives and retention programs
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Digital learning initiatives and computer literacy
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                After-school tutoring and mentoring programs
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Health & Nutrition</h2>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                School health check-ups and medical camps
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Mid-day meal programs and nutrition supplements
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Immunization drives and health awareness
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="bg-primary-600 rounded-lg p-8 text-center text-white">
                    <h2 class="text-3xl font-bold mb-4">Support Children's Rights</h2>
                    <p class="text-xl mb-6">Your contribution can help us reach more children and create lasting change in their lives.</p>
                    <div class="space-x-4">
                        <inertia-link href="/donate" class="inline-block bg-white text-primary-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 transition-colors">
                            Donate Now
                        </inertia-link>
                        <inertia-link href="/ngos" class="inline-block border-2 border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-white hover:text-primary-600 transition-colors">
                            Partner NGOs
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
</script>
