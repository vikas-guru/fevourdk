<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50">
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-br from-purple-900 via-indigo-800 to-blue-900 text-white py-20">
                <div class="absolute inset-0 opacity-10 bg-pattern-dots"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl md:text-5xl font-bold mb-6">Meet Our Team</h1>
                        <p class="text-xl md:text-2xl max-w-4xl mx-auto text-purple-100 leading-relaxed">
                            Dedicated leadership driving positive change across Karnataka
                        </p>
                    </div>
                </div>
            </section>

            <!-- Leadership Team -->
            <section class="py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Board Members</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                            Experienced leaders committed to strengthening voluntary organizations
                        </p>
                    </div>

                    <!-- Executive Leadership -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl shadow-lg border border-blue-100 text-center">
                            <div class="bg-blue-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 007-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Mahesh Chandra Guru</h3>
                            <p class="text-blue-600 font-semibold mb-4">State President</p>
                            <p class="text-gray-600">Leading the federation with vision and commitment to voluntary sector development.</p>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl shadow-lg border border-green-100 text-center">
                            <div class="bg-green-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Bhadrish B T</h3>
                            <p class="text-green-600 font-semibold mb-4">State Secretary</p>
                            <p class="text-gray-600">Ensuring organizational excellence and administrative efficiency.</p>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl shadow-lg border border-purple-100 text-center">
                            <div class="bg-purple-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2 2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Amali Naik</h3>
                            <p class="text-purple-600 font-semibold mb-4">State Treasurer</p>
                            <p class="text-gray-600">Managing financial resources with transparency and accountability.</p>
                        </div>
                    </div>

                    <!-- Vice Presidents -->
                    <div class="text-center mb-12">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Vice Presidents by Division</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Jayashree Hiremath</h4>
                            <p class="text-orange-600 font-semibold mb-3">Vice President</p>
                            <p class="text-gray-600 text-sm">Bringing innovative approaches to organizational leadership.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Shoba Rani</h4>
                            <p class="text-orange-600 font-semibold mb-3">Directors</p>
                            <p class="text-gray-600 text-sm">Expertise in governance and strategic planning.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">VN Murthy</h4>
                            <p class="text-orange-600 font-semibold mb-3">Directors</p>
                            <p class="text-gray-600 text-sm">Experience in policy advocacy and program management.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Dr. Babu Sajjan</h4>
                            <p class="text-orange-600 font-semibold mb-3">Vice President - Bangalore Division</p>
                            <p class="text-gray-600 text-sm">Leadership in urban development initiatives.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">HF Akki</h4>
                            <p class="text-orange-600 font-semibold mb-3">Vice President - Shivamogga Division</p>
                            <p class="text-gray-600 text-sm">Focus on rural development and community empowerment.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">A.S Prema</h4>
                            <p class="text-orange-600 font-semibold mb-3">Vice President - Mysore Division</p>
                            <p class="text-gray-600 text-sm">Dedication to women's empowerment and social justice.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Anil Kumar Beldar</h4>
                            <p class="text-orange-600 font-semibold mb-3">Vice President - Kalaburagi Division</p>
                            <p class="text-gray-600 text-sm">Expertise in environmental sustainability.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Pushpalatha</h4>
                            <p class="text-orange-600 font-semibold mb-3">Directors</p>
                            <p class="text-gray-600 text-sm">Knowledge in capacity building and training.</p>
                        </div>
                    </div>

                    <!-- Regional Vice Presidents -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Vijaya Nesargi</h4>
                            <p class="text-orange-600 font-semibold mb-3">Directors</p>
                            <p class="text-gray-600 text-sm">Experience in disaster management and relief operations.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Krishnamurthy MS</h4>
                            <p class="text-green-600 font-semibold mb-3">Program Manager</p>
                            <p class="text-gray-600 text-sm">Coordinating statewide programs and initiatives.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Lijo R</h4>
                            <p class="text-green-600 font-semibold mb-3">Training Manager</p>
                            <p class="text-gray-600 text-sm">Developing and implementing capacity building programs.</p>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Edward Thomas</h4>
                            <p class="text-orange-600 font-semibold mb-3">Vice President - Bangalore Division</p>
                            <p class="text-gray-600 text-sm">Leadership in organizational development.</p>
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
