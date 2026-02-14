<template>
    <AppLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 text-white overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0 bg-pattern-dots"></div>
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
                
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-3xl shadow-2xl mb-8 backdrop-blur-sm border border-white/20 transform hover:scale-110 transition-all duration-300">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-bold mb-6 bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                            Donor Wall of Honor
                        </h1>
                        <p class="text-xl lg:text-2xl text-blue-200 max-w-4xl mx-auto leading-relaxed mb-8">
                            Celebrating the generous hearts who make our mission possible. 
                            Every contribution creates ripples of positive change across Karnataka.
                        </p>
                        
                        <!-- Impact Stats -->
                        <div class="flex justify-center space-x-12 mb-12">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-yellow-400">{{ totalDonors.toLocaleString() }}+</div>
                                <div class="text-blue-200 text-sm">Total Donors</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold text-green-400">₹{{ formatCurrency(totalAmount) }}</div>
                                <div class="text-blue-200 text-sm">Total Donated</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold text-purple-400">{{ livesImpacted.toLocaleString() }}+</div>
                                <div class="text-blue-200 text-sm">Lives Impacted</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Wave Bottom -->
                <div class="absolute bottom-0 left-0 right-0">
                    <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="#F9FAFB"/>
                    </svg>
                </div>
            </section>

            <!-- Filters Section -->
            <section class="py-12 bg-white/80 backdrop-blur-sm sticky top-0 z-40 border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
                        <!-- Search -->
                        <div class="relative w-full lg:w-96">
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search donors..." 
                                class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm"
                            >
                            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        
                        <!-- Filter Options -->
                        <div class="flex space-x-4">
                            <select v-model="selectedCategory" class="px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm">
                                <option value="">All Categories</option>
                                <option value="individual">Individual</option>
                                <option value="corporate">Corporate</option>
                                <option value="foundation">Foundation</option>
                                <option value="ngo">NGO</option>
                            </select>
                            
                            <select v-model="sortBy" class="px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm">
                                <option value="highest">Highest Amount</option>
                                <option value="recent">Most Recent</option>
                                <option value="name">Alphabetical</option>
                                <option value="campaigns">Most Campaigns</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Donors -->
            <section class="py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Champions</h2>
                        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                            Our most generous supporters who have made extraordinary contributions to our mission
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="donor in featuredDonors" :key="donor.id" 
                             class="group bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-yellow-200 overflow-hidden">
                            <!-- Donor Header -->
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-6 text-white">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-2xl font-bold mr-4">
                                            {{ donor.name.charAt(0) }}
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold">{{ donor.name }}</h3>
                                            <p class="text-yellow-100">{{ donor.type }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold">₹{{ formatCurrency(donor.amount) }}</div>
                                        <div class="text-sm text-yellow-100">Total Donation</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Donor Details -->
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="text-center p-4 bg-white rounded-xl">
                                        <div class="text-2xl font-bold text-orange-600">{{ donor.campaigns }}</div>
                                        <div class="text-sm text-gray-600">Campaigns Supported</div>
                                    </div>
                                    <div class="text-center p-4 bg-white rounded-xl">
                                        <div class="text-2xl font-bold text-yellow-600">{{ donor.years }}</div>
                                        <div class="text-sm text-gray-600">Years Supporting</div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">Impact Areas</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="area in donor.impactAreas" :key="area" 
                                              class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                            {{ area }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <button class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-6 py-3 rounded-xl font-bold hover:from-yellow-500 hover:to-orange-600 transition-all duration-300 transform hover:scale-105">
                                        View Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- All Donors Grid -->
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">All Our Donors</h2>
                        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                            Every contribution matters. Meet the amazing people and organizations supporting our cause.
                        </p>
                    </div>
                    
                    <!-- Results Count -->
                    <div class="mb-8">
                        <p class="text-gray-600">
                            Showing <span class="font-semibold text-gray-900">{{ filteredDonors.length }}</span> of 
                            <span class="font-semibold text-gray-900">{{ allDonors.length }}</span> donors
                        </p>
                    </div>
                    
                    <!-- Donors Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div v-for="donor in filteredDonors" :key="donor.id" 
                             class="group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 p-6">
                            <!-- Donor Avatar -->
                            <div class="flex items-center mb-4">
                                <div :class="getDonorAvatarClass(donor.category)" 
                                     class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    {{ donor.name.charAt(0) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ donor.name }}
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ donor.type }}</p>
                                </div>
                            </div>
                            
                            <!-- Donation Info -->
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Total Donated</span>
                                    <span class="font-bold text-green-600">₹{{ formatCurrency(donor.amount) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Campaigns</span>
                                    <span class="font-semibold text-gray-900">{{ donor.campaigns }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Since</span>
                                    <span class="font-semibold text-gray-900">{{ donor.year }}</span>
                                </div>
                            </div>
                            
                            <!-- Recent Activity -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Last donation: {{ donor.lastDonation }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Load More Button -->
                    <div class="text-center mt-12">
                        <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                            Load More Donors
                        </button>
                    </div>
                </div>
            </section>

            <!-- Donation Trends -->
            <section class="py-20 bg-gradient-to-br from-blue-50 to-purple-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Donation Trends</h2>
                        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                            See how our community's generosity has grown over time
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Monthly Trend -->
                        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Monthly Growth</h3>
                                    <p class="text-sm text-gray-500">+23% this month</p>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-green-600 mb-2">₹{{ formatCurrency(monthlyGrowth) }}</div>
                            <div class="text-sm text-gray-600">Average monthly donations</div>
                        </div>
                        
                        <!-- Top Categories -->
                        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Top Category</h3>
                                    <p class="text-sm text-gray-500">Most supported</p>
                                </div>
                            </div>
                            <div class="text-2xl font-bold text-blue-600 mb-2">Education</div>
                            <div class="text-sm text-gray-600">45% of all donations</div>
                        </div>
                        
                        <!-- New Donors -->
                        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">New Donors</h3>
                                    <p class="text-sm text-gray-500">This month</p>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-yellow-600 mb-2">{{ newDonorsThisMonth }}</div>
                            <div class="text-sm text-gray-600">New supporters joined</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="py-20 bg-gradient-to-r from-blue-900 via-purple-900 to-indigo-900 text-white">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-4xl font-bold mb-6">Join Our Community of Givers</h2>
                    <p class="text-xl mb-12 text-blue-100 leading-relaxed">
                        Become part of our growing family of donors. Your contribution, no matter the size, 
                        helps us create lasting change in communities across Karnataka.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <button class="bg-gradient-to-r from-yellow-400 to-orange-500 text-blue-900 px-8 py-4 rounded-xl font-bold hover:from-yellow-500 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Donate Now
                        </button>
                        <button class="bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-bold hover:bg-white/20 transition-all duration-300 border border-white/20">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Learn More
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

// Featured donors data
const featuredDonors = ref([
    {
        id: 1,
        name: 'TechCorp India',
        type: 'Corporate Partner',
        amount: 1200000,
        campaigns: 8,
        years: 5,
        impactAreas: ['Education', 'Healthcare', 'Environment'],
        category: 'corporate'
    },
    {
        id: 2,
        name: 'Bangalore Cares Foundation',
        type: 'Foundation',
        amount: 850000,
        campaigns: 12,
        years: 7,
        impactAreas: ['Children Welfare', 'Women Empowerment', 'Water'],
        category: 'foundation'
    },
    {
        id: 3,
        name: 'Rajesh Kumar',
        type: 'Individual Philanthropist',
        amount: 650000,
        campaigns: 6,
        years: 4,
        impactAreas: ['Education', 'Healthcare'],
        category: 'individual'
    }
])

// All donors data
const allDonors = ref([
    // Individual donors
    { id: 4, name: 'Priya Sharma', type: 'Individual', amount: 75000, campaigns: 3, year: 2022, lastDonation: '2 weeks ago', category: 'individual' },
    { id: 5, name: 'Amit Patel', type: 'Individual', amount: 45000, campaigns: 2, year: 2023, lastDonation: '1 month ago', category: 'individual' },
    { id: 6, name: 'Neha Reddy', type: 'Individual', amount: 35000, campaigns: 4, year: 2021, lastDonation: '3 days ago', category: 'individual' },
    { id: 7, name: 'Vikram Singh', type: 'Individual', amount: 28000, campaigns: 2, year: 2023, lastDonation: '1 week ago', category: 'individual' },
    { id: 8, name: 'Anjali Gupta', type: 'Individual', amount: 22000, campaigns: 3, year: 2022, lastDonation: '2 months ago', category: 'individual' },
    { id: 9, name: 'Karthik Nair', type: 'Individual', amount: 18000, campaigns: 1, year: 2023, lastDonation: '5 days ago', category: 'individual' },
    { id: 10, name: 'Meera Iyer', type: 'Individual', amount: 15000, campaigns: 2, year: 2021, lastDonation: '3 weeks ago', category: 'individual' },
    { id: 11, name: 'Suresh Kumar', type: 'Individual', amount: 12000, campaigns: 1, year: 2023, lastDonation: '1 month ago', category: 'individual' },
    
    // Corporate donors
    { id: 12, name: 'SME Association', type: 'Corporate', amount: 150000, campaigns: 5, year: 2020, lastDonation: '1 week ago', category: 'corporate' },
    { id: 13, name: 'Digital Solutions Ltd', type: 'Corporate', amount: 95000, campaigns: 3, year: 2022, lastDonation: '2 weeks ago', category: 'corporate' },
    { id: 14, name: 'StartupHub', type: 'Corporate', amount: 68000, campaigns: 2, year: 2023, lastDonation: '5 days ago', category: 'corporate' },
    { id: 15, name: 'Local Business Group', type: 'Corporate', amount: 55000, campaigns: 4, year: 2021, lastDonation: '3 weeks ago', category: 'corporate' },
    { id: 16, name: 'Innovation Labs', type: 'Corporate', amount: 42000, campaigns: 2, year: 2023, lastDonation: '1 month ago', category: 'corporate' },
    
    // Foundation donors
    { id: 17, name: 'Education First Foundation', type: 'Foundation', amount: 320000, campaigns: 8, year: 2019, lastDonation: '2 weeks ago', category: 'foundation' },
    { id: 18, name: 'Green Earth Foundation', type: 'Foundation', amount: 280000, campaigns: 6, year: 2020, lastDonation: '1 month ago', category: 'foundation' },
    { id: 19, name: 'Community Care Trust', type: 'Foundation', amount: 180000, campaigns: 5, year: 2021, lastDonation: '3 days ago', category: 'foundation' },
    
    // NGO donors
    { id: 20, name: 'Helping Hands NGO', type: 'NGO', amount: 95000, campaigns: 4, year: 2022, lastDonation: '1 week ago', category: 'ngo' },
    { id: 21, name: 'Rural Development Society', type: 'NGO', amount: 78000, campaigns: 3, year: 2021, lastDonation: '2 weeks ago', category: 'ngo' }
])

// Filters and search
const searchQuery = ref('')
const selectedCategory = ref('')
const sortBy = ref('highest')

// Statistics
const totalDonors = ref(2847)
const totalAmount = ref(15600000)
const livesImpacted = ref(125000)
const monthlyGrowth = ref(1250000)
const newDonorsThisMonth = ref(47)

// Computed properties
const filteredDonors = computed(() => {
    let filtered = allDonors.value

    // Search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(donor => 
            donor.name.toLowerCase().includes(query) ||
            donor.type.toLowerCase().includes(query)
        )
    }

    // Category filter
    if (selectedCategory.value) {
        filtered = filtered.filter(donor => donor.category === selectedCategory.value)
    }

    // Sort
    switch (sortBy.value) {
        case 'highest':
            filtered.sort((a, b) => b.amount - a.amount)
            break
        case 'recent':
            filtered.sort((a, b) => b.year - a.year)
            break
        case 'name':
            filtered.sort((a, b) => a.name.localeCompare(b.name))
            break
        case 'campaigns':
            filtered.sort((a, b) => b.campaigns - a.campaigns)
            break
    }

    return filtered
})

// Get donor avatar class based on category
const getDonorAvatarClass = (category) => {
    const classes = {
        individual: 'bg-gradient-to-br from-blue-400 to-purple-500',
        corporate: 'bg-gradient-to-br from-green-400 to-emerald-500',
        foundation: 'bg-gradient-to-br from-yellow-400 to-orange-500',
        ngo: 'bg-gradient-to-br from-pink-400 to-red-500'
    }
    return classes[category] || 'bg-gradient-to-br from-gray-400 to-gray-500'
}

// Format currency
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 0,
    }).format(amount || 0)
}
</script>

<style scoped>
.bg-pattern-dots {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Custom animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}
</style>
