<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50">
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-br from-indigo-900 via-purple-800 to-pink-900 text-white py-20">
                <div class="absolute inset-0 opacity-10 bg-pattern-dots"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl md:text-5xl font-bold mb-6">Our Partners</h1>
                        <p class="text-xl md:text-2xl max-w-4xl mx-auto text-indigo-100 leading-relaxed">
                            Collaborating for greater impact across Karnataka
                        </p>
                    </div>
                </div>
            </section>

            <!-- Partners Section -->
            <section class="py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Partners & Sponsors</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                            Working together with organizations and individuals committed to social development
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Government Partners -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2 2H7a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7z"/>
                                </svg>
                                Government Partners
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-2">Karnataka Government</h4>
                                        <p class="text-gray-600">State government departments supporting voluntary sector initiatives</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-2">District Administrations</h4>
                                        <p class="text-gray-600">Local governance bodies collaborating on community projects</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Corporate Partners -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Corporate Sponsors
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-2">CSR Initiatives</h4>
                                        <p class="text-gray-600">Corporate social responsibility programs and funding support</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-2">Private Sector Support</h4>
                                        <p class="text-gray-600">Business organizations supporting community development</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- International Partners -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2h1.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2.5 2.5 0 002.5-2.5V3.935"/>
                                </svg>
                                International Organizations
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2h1.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2.5 2.5 0 002.5-2.5V3.935"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-2">UN Agencies</h4>
                                        <p class="text-gray-600">United Nations programs supporting sustainable development</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2h1.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2.5 2.5 0 002.5-2.5V3.935"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-2">Funding Agencies</h4>
                                        <p class="text-gray-600">International development partners and donors</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Partnership Benefits -->
                    <div class="mt-16 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Why Partner With Us?</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="text-center">
                                <div class="bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3H5a3 3 0 00-3 3v14a3 3 0 003 3h14a3 3 0 003-3v-14a3 3 0 00-3-3H5z"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Amplified Impact</h4>
                                <p class="text-gray-600">Reach more communities and create greater change together</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-green-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Resource Sharing</h4>
                                <p class="text-gray-600">Access to funding, expertise, and networks</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-purple-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2h1.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2.5 2.5 0 002.5-2.5V3.935"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Credibility</h4>
                                <p class="text-gray-600">Association with trusted FEVOURD-K network</p>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <div class="text-center mt-12">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Become Our Partner</h3>
                        <p class="text-lg text-gray-600 mb-8 max-w-3xl mx-auto">
                            Join us in our mission to strengthen voluntary organizations across Karnataka
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                            <button class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Partnership Inquiry
                            </button>
                            <button class="bg-green-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                                Support Our Mission
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<style scoped>
.bg-pattern-dots {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>
