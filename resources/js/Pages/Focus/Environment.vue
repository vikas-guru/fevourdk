<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Environment</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Protecting Karnataka's natural heritage through conservation, sustainable practices, and environmental education.
                    </p>
                </div>

                <!-- Stats Section -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">1M+</div>
                        <div class="text-gray-600">Trees Planted</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">50+</div>
                        <div class="text-gray-600">Water Conservation Projects</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">200+</div>
                        <div class="text-gray-600">Villages Covered</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">75+</div>
                        <div class="text-gray-600">Environmental NGOs</div>
                    </div>
                </div>

                <!-- Programs Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Conservation Initiatives</h2>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Afforestation and reforestation programs
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Wildlife habitat protection and restoration
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Biodiversity monitoring and research
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Sustainable Development</h2>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Renewable energy promotion and installation
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Waste management and recycling programs
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Sustainable agriculture and organic farming
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="bg-green-600 rounded-lg p-8 text-center text-white">
                    <h2 class="text-3xl font-bold mb-4">Protect Our Environment</h2>
                    <p class="text-xl mb-6">Join us in creating a sustainable future for Karnataka through environmental conservation efforts.</p>
                    <div class="space-x-4">
                        <inertia-link href="/donate" class="inline-block bg-white text-green-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 transition-colors">
                            Support Green Initiatives
                        </inertia-link>
                        <inertia-link href="/ngos" class="inline-block border-2 border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-white hover:text-green-600 transition-colors">
                            Environmental NGOs
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
