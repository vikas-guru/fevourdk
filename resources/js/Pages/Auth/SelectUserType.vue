<template>
    <AppLayout title="Choose Your Role - FEVOURD-K">
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 py-8 sm:py-12 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0 bg-pattern"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Header -->
                <div class="text-center mb-12 sm:mb-16">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl shadow-2xl mb-6 backdrop-blur-sm border border-white/10">
                               <img :src="logoImage" alt="FEVOURD-K" class="w-14 h-10 lg:w-16 lg:h-12 object-contain">
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                        Join Our <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Social Impact</span> Community
                    </h1>
                    <p class="text-xl sm:text-2xl text-blue-200 max-w-4xl mx-auto leading-relaxed">
                        Choose your role to get started with FEVOURD-K - Karnataka's largest network of verified voluntary organisations
                    </p>
                    
                    <!-- Stats Bar -->
                    <div class="flex flex-wrap justify-center gap-8 mt-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">800+</div>
                            <div class="text-sm text-blue-300">Verified NGOs</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">31</div>
                            <div class="text-sm text-blue-300">Districts</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">50K+</div>
                            <div class="text-sm text-blue-300">Active Users</div>
                        </div>
                    </div>
                </div>

                <!-- User Type Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 lg:gap-8">
                    <!-- Individual -->
                    <Link 
                        href="/register/individual"
                        class="group relative bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-500 hover:scale-105 hover:shadow-2xl cursor-pointer"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-green-400/20 to-emerald-600/20 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-500 shadow-xl">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-green-300 transition-colors">
                                Individual Donor
                            </h3>
                            <p class="text-blue-200 leading-relaxed mb-4">
                                Support causes you care about with donations and volunteer your time for social impact.
                            </p>
                            <div class="flex flex-wrap gap-2 justify-center">
                                <span class="px-3 py-1 bg-green-500/20 text-green-300 text-sm rounded-full border border-green-400/30">
                                    ✓ Donate & Volunteer
                                </span>
                                <span class="px-3 py-1 bg-green-500/20 text-green-300 text-sm rounded-full border border-green-400/30">
                                    ✓ Track Impact
                                </span>
                            </div>
                        </div>
                    </Link>

                    <!-- NGO -->
                    <Link href="/register/ngo-chat" class="group relative bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-500 hover:scale-105 hover:shadow-2xl cursor-pointer">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 to-purple-600/20 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-600 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-500 shadow-xl">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-blue-300 transition-colors">
                                NGO Organisation
                            </h3>
                            <p class="text-blue-200 leading-relaxed mb-4">
                                Register your voluntary organisation and access funding, volunteers, and CSR partnerships.
                            </p>
                            <div class="flex flex-wrap gap-2 justify-center mb-6">
                                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 text-sm rounded-full border border-blue-400/30">
                                    ✓ Receive Donations
                                </span>
                                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 text-sm rounded-full border border-blue-400/30">
                                    ✓ CSR Partnerships
                                </span>
                            </div>
                            <div class="inline-flex items-center rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 text-white px-4 py-2 text-sm font-semibold shadow-lg">
                                🤖 Start Smart Chat Registration
                            </div>
                        </div>
                    </Link>

                    <!-- Corporate -->
                    <Link 
                        href="/register/corporate"
                        class="group relative bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-500 hover:scale-105 hover:shadow-2xl cursor-pointer"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-400/20 to-pink-600/20 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-pink-600 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-500 shadow-xl">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-purple-300 transition-colors">
                                Corporate CSR
                            </h3>
                            <p class="text-blue-200 leading-relaxed mb-4">
                                Manage CSR activities, find verified NGOs, and track social impact investments.
                            </p>
                            <div class="flex flex-wrap gap-2 justify-center">
                                <span class="px-3 py-1 bg-purple-500/20 text-purple-300 text-sm rounded-full border border-purple-400/30">
                                    ✓ CSR Compliance
                                </span>
                                <span class="px-3 py-1 bg-purple-500/20 text-purple-300 text-sm rounded-full border border-purple-400/30">
                                    ✓ Impact Tracking
                                </span>
                            </div>
                        </div>
                    </Link>

                    <!-- Volunteer -->
                    <Link 
                        href="/register/volunteer"
                        class="group relative bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-500 hover:scale-105 hover:shadow-2xl cursor-pointer"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/20 to-orange-600/20 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-500 shadow-xl">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3H5a3 3 0 00-3 3v14a3 3 0 003 3h14a3 3 0 003-3v-14a3 3 0 00-3-3H5z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-yellow-300 transition-colors">
                                Volunteer
                            </h3>
                            <p class="text-blue-200 leading-relaxed mb-4">
                                Contribute your time and skills to causes that matter in your community.
                            </p>
                            <div class="flex flex-wrap gap-2 justify-center">
                                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-300 text-sm rounded-full border border-yellow-400/30">
                                    ✓ Flexible Hours
                                </span>
                                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-300 text-sm rounded-full border border-yellow-400/30">
                                    ✓ Skill Development
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Benefits Section -->
                <div class="mt-16 bg-white/10 backdrop-blur-md rounded-3xl p-8 sm:p-12 border border-white/20">
                    <h2 class="text-3xl sm:text-4xl font-bold text-center text-white mb-12">
                        Why Join <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">FEVOURD-K</span>?
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center group">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 border border-blue-400/30">
                                <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Location-Based Discovery</h3>
                            <p class="text-blue-200 leading-relaxed">Find NGOs and causes near you in Karnataka's 31 districts with intelligent matching</p>
                        </div>
                        <div class="text-center group">
                            <div class="w-20 h-20 bg-gradient-to-br from-green-500/20 to-green-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 border border-green-400/30">
                                <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Transparent Donations</h3>
                            <p class="text-blue-200 leading-relaxed">Track every rupee with real-time fund utilization reports and impact metrics</p>
                        </div>
                        <div class="text-center group">
                            <div class="w-20 h-20 bg-gradient-to-br from-purple-500/20 to-purple-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 border border-purple-400/30">
                                <svg class="w-10 h-10 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">CSR Compliance</h3>
                            <p class="text-blue-200 leading-relaxed">Generate compliant reports and certificates for corporate giving with automated tracking</p>
                        </div>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center mt-12">
                    <p class="text-blue-200 text-lg">
                        Already have an account? 
                        <Link href="/login" class="text-white font-bold hover:text-blue-300 transition-colors underline decoration-2 underline-offset-4">
                            Sign In Here
                        </Link>
                    </p>
                </div>
                
                <!-- Footer Info -->
                <div class="text-center mt-16 pt-8 border-t border-white/10">
                    <p class="text-blue-300 text-sm">
                        🌟 Join 50,000+ changemakers making a difference across Karnataka
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Background pattern */
.bg-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Custom animations and effects */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
    50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
}

.group:hover .group-hover\:scale-110 {
    animation: float 3s ease-in-out infinite;
}

/* Glassmorphism effect */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

/* Smooth transitions */
* {
    transition: all 0.3s ease;
}
</style>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
const logoImage = '/assets/images/fevourd-k/logo.png'
import { Link } from '@inertiajs/vue3'
</script>
