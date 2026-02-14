<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50">
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white py-20">
                <div class="absolute inset-0 opacity-10 bg-pattern-dots"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl md:text-5xl font-bold mb-6">About FEVOURD-K</h1>
                        <p class="text-xl md:text-2xl max-w-4xl mx-auto text-blue-100 leading-relaxed">
                            Federation of Voluntary Organisations for Urban and Rural Development in Karnataka
                        </p>
                    </div>
                </div>
                
                <!-- Wave Bottom -->
                <div class="absolute bottom-0 left-0 right-0">
                    <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="#F9FAFB"/>
                    </svg>
                </div>
            </section>

            <!-- Introduction -->
            <section class="py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">About Our FEVOURD-K</h2>
                            <div class="prose prose-lg text-gray-700 leading-relaxed">
                                <p>
                                    The Federation of Voluntary Organisations for Urban and Rural Development in Karnataka (FEVOURD-K) founded in 1982 by visionary civil society leaders, FEVOURD-K is a powerful federation of over <strong>800 voluntary organizations</strong> driven by constitutional values of justice, liberty, fraternity, and human dignity. Headquartered in Bangalore, we operate across all <strong>31 districts of Karnataka</strong>, uniting diverse communities in a shared mission to create a socially just, environmentally sustainable, and economically equitable state.
                                </p>
                                <p>
                                    FEVOURD-K is dedicated to empowering marginalized groups, conserving environment, and fostering a vibrant democracy. Through grassroots action, policy advocacy, and civic engagement, we work to strengthen Karnataka's developmental indices and champion the rights and dignity of all. We believe an independent and robust voluntary sector is essential for sustainable development and democratic progress. Together, we strive to build an inclusive future where every individual can thrive and contribute meaningfully to society.
                                </p>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                            <div class="grid grid-cols-2 gap-6 text-center">
                                <div class="p-4 bg-blue-50 rounded-xl">
                                    <div class="text-3xl font-bold text-blue-600 mb-2">800+</div>
                                    <div class="text-sm text-gray-600">Voluntary Organizations</div>
                                </div>
                                <div class="p-4 bg-green-50 rounded-xl">
                                    <div class="text-3xl font-bold text-green-600 mb-2">31</div>
                                    <div class="text-sm text-gray-600">Districts Covered</div>
                                </div>
                                <div class="p-4 bg-purple-50 rounded-xl">
                                    <div class="text-3xl font-bold text-purple-600 mb-2">1982</div>
                                    <div class="text-sm text-gray-600">Founded</div>
                                </div>
                                <div class="p-4 bg-orange-50 rounded-xl">
                                    <div class="text-3xl font-bold text-orange-600 mb-2">1L+</div>
                                    <div class="text-sm text-gray-600">Lives Impacted</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mission & Vision -->
            <section class="py-20 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Mission -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl border border-blue-100">
                            <div class="flex items-center mb-6">
                                <div class="bg-blue-600 p-3 rounded-xl mr-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7m0 0v7l9-11h-7v-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Our Mission</h3>
                            </div>
                            <ul class="space-y-4 text-gray-700">
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>Collective Voice:</strong> Ensure solidarity among voluntary organizations for collective voice and action on issues that affect the sector.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>Organizational Strengthening:</strong> Strengthen voluntary organizations to help them better accomplish their missions.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>Civil Society Promotion:</strong> Promote a robust civil society to protect democratic values and to advance human progress.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>Inter-Sectoral Collaboration:</strong> Create inter-sectoral synergies for innovations, shared resources, and effective impact.</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Vision -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl border border-green-100">
                            <div class="flex items-center mb-6">
                                <div class="bg-green-600 p-3 rounded-xl mr-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Our Vision</h3>
                            </div>
                            <ul class="space-y-4 text-gray-700">
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>Unified Voice:</strong> A unified and influential collective voice of NGOs that actively engages in shaping policies, improving governance, and ensuring fair allocation of resources for community welfare.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>Community Empowerment:</strong> Empowering marginalized communities through practical skill-building and active participation.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>Inclusive Society:</strong> Promotion of a just, equitable, and inclusive society where every individual enjoys dignity, equal opportunities, and full recognition of their rights without discrimination.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Values -->
            <section class="py-20 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Core Values</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                            The principles that guide our work and shape our approach to social change
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 text-center border border-gray-100">
                            <div class="bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <span class="text-2xl font-bold text-blue-600">01</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Empowerment</h3>
                            <p class="text-gray-600">Strengthening organizations to achieve their missions and create lasting impact in communities.</p>
                        </div>

                        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 text-center border border-gray-100">
                            <div class="bg-green-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <span class="text-2xl font-bold text-green-600">03</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Solidarity</h3>
                            <p class="text-gray-600">Unity and collective action among voluntary organizations for greater impact.</p>
                        </div>

                        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 text-center border border-gray-100">
                            <div class="bg-purple-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <span class="text-2xl font-bold text-purple-600">04</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Democracy</h3>
                            <p class="text-gray-600">Protecting democratic values and promoting human progress through civic engagement.</p>
                        </div>

                        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 text-center border border-gray-100">
                            <div class="bg-orange-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <span class="text-2xl font-bold text-orange-600">05</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Collaboration</h3>
                            <p class="text-gray-600">Fostering inter-sectoral partnerships for innovation and sustainable impact.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Administrative Structure -->
            <section class="py-20 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Administrative Structure</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                            Our governance structure ensures transparency, accountability, and representation across all regions
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl border border-blue-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Leadership Team</h3>
                            <ul class="space-y-3 text-gray-700">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4-6m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    President
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4-6m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Secretary
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4-6m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Treasurer
                                </li>
                            </ul>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl border border-green-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Board Members</h3>
                            <ul class="space-y-3 text-gray-700">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3a4 4 0 00-8 0z"/>
                                    </svg>
                                    15 Board Members
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3a4 4 0 00-8 0z"/>
                                    </svg>
                                    3 from each region
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3a4 4 0 00-8 0z"/>
                                    </svg>
                                    External experts
                                </li>
                            </ul>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl border border-purple-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Secretariat</h3>
                            <ul class="space-y-3 text-gray-700">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4-6m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    State Training Manager
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4-6m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Resource Mapping Manager
                                </li>
                            </ul>
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
