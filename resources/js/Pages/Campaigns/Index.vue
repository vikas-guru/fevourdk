<template>
    <AppLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <!-- Hero Section -->
            <section v-if="!isStandaloneMode" class="relative bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 text-white overflow-hidden">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-bold mb-6 bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                            All Campaigns
                        </h1>
                        <p class="text-xl lg:text-2xl text-blue-200 max-w-4xl mx-auto leading-relaxed mb-8">
                            Discover impactful campaigns making a real difference across Karnataka. 
                            Support causes that matter and help transform communities.
                        </p>
                        
                        <!-- Campaign Stats -->
                        <div class="flex justify-center space-x-12 mb-12">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-yellow-400">{{ campaigns.length }}+</div>
                                <div class="text-blue-200 text-sm">Active Campaigns</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold text-green-400">₹{{ formatCurrency(totalRaised) }}</div>
                                <div class="text-blue-200 text-sm">Total Raised</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold text-purple-400">{{ totalCampaignDonors }}+</div>
                                <div class="text-blue-200 text-sm">Total Donors</div>
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
            <section class="py-6 bg-white/90 backdrop-blur-sm sticky top-0 z-40 border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div v-if="isStandaloneMode" class="mb-4">
                        <h1 class="text-2xl font-bold text-gray-900">Campaigns</h1>
                        <p class="text-sm text-gray-500">Filter and support active causes quickly.</p>
                    </div>
                    <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
                        <!-- Search -->
                        <div class="relative w-full lg:w-96">
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search campaigns..." 
                                class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm"
                            >
                            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="flex space-x-4">
                            <select v-model="selectedCategory" class="px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm">
                                <option value="">All Categories</option>
                                <option value="education">Education</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="environment">Environment</option>
                                <option value="water">Water & Sanitation</option>
                                <option value="women">Women Empowerment</option>
                                <option value="children">Children Welfare</option>
                            </select>
                            
                            <select v-model="sortBy" class="px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm">
                                <option value="newest">Newest First</option>
                                <option value="ending">Ending Soon</option>
                                <option value="popular">Most Popular</option>
                                <option value="funded">Most Funded</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Campaigns Grid -->
            <section class="py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Results Count -->
                    <div class="mb-8">
                        <p class="text-gray-600">
                            Showing <span class="font-semibold text-gray-900">{{ filteredCampaigns.length }}</span> of 
                            <span class="font-semibold text-gray-900">{{ campaigns.length }}</span> campaigns
                        </p>
                    </div>
                    
                    <!-- Campaigns Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="campaign in filteredCampaigns" :key="campaign.id" 
                             class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                            
                            <!-- Campaign Image -->
                            <div class="relative h-64 overflow-hidden">
                                <img :src="getCampaignImage(campaign.id)" 
                                     :alt="campaign.title" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                
                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                
                                <!-- Category Badge -->
                                <div class="absolute top-4 left-4">
                                    <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-1">
                                        <div class="text-sm font-semibold text-gray-900">{{ campaign.category || 'General' }}</div>
                                    </div>
                                </div>
                                
                                <!-- Urgency Badge -->
                                <div v-if="campaign.days_left <= 7" class="absolute top-4 right-4">
                                    <div class="bg-red-500 text-white rounded-lg px-3 py-1 shadow-lg animate-pulse">
                                        <div class="text-xs font-bold">{{ campaign.days_left }} days left</div>
                                    </div>
                                </div>
                                
                                <!-- Progress Badge -->
                                <div class="absolute bottom-4 left-4">
                                    <div class="bg-green-500 text-white rounded-lg px-3 py-1 shadow-lg">
                                        <div class="text-sm font-bold">{{ campaign.progress_percentage }}% Funded</div>
                                    </div>
                                </div>
                                
                                <!-- Social Sharing Button -->
                                <div class="absolute top-4 right-4 left-auto" v-if="campaign.days_left > 7">
                                    <button @click="shareCampaign(campaign)" class="bg-white/90 backdrop-blur-sm text-gray-700 p-2 rounded-lg hover:bg-white transition-colors shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Campaign Content -->
                            <div class="p-6">
                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                                    {{ campaign.title }}
                                </h3>
                                
                                <!-- Description -->
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ campaign.description }}
                                </p>
                                
                                <!-- NGO Info -->
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ campaign.ngo_name || 'NGO Name' }}</div>
                                        <div class="text-xs text-gray-500">Verified Organization</div>
                                    </div>
                                </div>
                                
                                <!-- Progress -->
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-600">Raised</span>
                                        <span class="text-sm font-semibold text-gray-900">₹{{ formatCurrency(campaign.raised_amount) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-600">Goal</span>
                                        <span class="text-sm font-semibold text-gray-900">₹{{ formatCurrency(campaign.goal_amount) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full transition-all duration-500" 
                                             :style="{ width: `${campaign.progress_percentage}%` }"></div>
                                    </div>
                                </div>
                                
                                <!-- Stats -->
                                <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a1.998 1.998 0 010-2.828l4.244-4.244a1.998 1.998 0 012.828 0l4.244 4.244a1.998 1.998 0 010 2.828z" />
                                        </svg>
                                        <span>{{ campaign.donors_count }} donors</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ campaign.days_left }} days left</span>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex space-x-3">
                                    <button class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white py-3 px-4 rounded-xl font-bold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Donate
                                    </button>
                                    <Link :href="`/campaigns/${campaign.slug}`" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-xl font-bold hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        View
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- No Results -->
                    <div v-if="filteredCampaigns.length === 0" class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No campaigns found</h3>
                        <p class="text-gray-600">Try adjusting your search or filters to find what you're looking for.</p>
                    </div>
                </div>
            </section>

            <!-- Donor Wall Section -->
            <section v-if="!isStandaloneMode" class="py-20 bg-gradient-to-br from-blue-50 via-purple-50 to-indigo-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl shadow-2xl mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Donor Wall of Honor</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                            Celebrating the generous individuals and organizations who make our work possible. 
                            Every contribution, big or small, creates lasting change in communities across Karnataka.
                        </p>
                    </div>
                    
                    <!-- Donor Categories -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                        <!-- Top Donors -->
                        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Star Donors</h3>
                                    <p class="text-sm text-gray-500">₹50,000 and above</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div v-for="donor in topDonors" :key="donor.id" class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                            {{ donor.name.charAt(0) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ donor.name }}</div>
                                            <div class="text-sm text-gray-500">{{ donor.type }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-orange-600">₹{{ formatCurrency(donor.amount) }}</div>
                                        <div class="text-xs text-gray-500">{{ donor.campaigns }} campaigns</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Regular Donors -->
                        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Supporting Donors</h3>
                                    <p class="text-sm text-gray-500">₹10,000 - ₹49,999</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div v-for="donor in regularDonors" :key="donor.id" class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                            {{ donor.name.charAt(0) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 text-sm">{{ donor.name }}</div>
                                            <div class="text-xs text-gray-500">{{ donor.type }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-blue-600 text-sm">₹{{ formatCurrency(donor.amount) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Donors -->
                        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Recent Contributions</h3>
                                    <p class="text-sm text-gray-500">Last 7 days</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div v-for="donor in recentDonors" :key="donor.id" class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                            {{ donor.name.charAt(0) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 text-sm">{{ donor.name }}</div>
                                            <div class="text-xs text-gray-500">{{ donor.timeAgo }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-green-600 text-sm">₹{{ formatCurrency(donor.amount) }}</div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Donor Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                        <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-gray-100">
                            <div class="text-3xl font-bold text-yellow-600 mb-2">{{ totalDonors.toLocaleString() }}+</div>
                            <div class="text-gray-600 font-medium">Total Donors</div>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-gray-100">
                            <div class="text-3xl font-bold text-blue-600 mb-2">{{ totalAmount.toLocaleString() }}</div>
                            <div class="text-gray-600 font-medium">Total Donated</div>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-gray-100">
                            <div class="text-3xl font-bold text-green-600 mb-2">{{ activeCampaigns }}</div>
                            <div class="text-gray-600 font-medium">Active Campaigns</div>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-gray-100">
                            <div class="text-3xl font-bold text-purple-600 mb-2">{{ livesImpacted.toLocaleString() }}+</div>
                            <div class="text-gray-600 font-medium">Lives Impacted</div>
                        </div>
                    </div>
                    
                    <!-- Call to Action -->
                    <div class="text-center">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white">
                            <h3 class="text-2xl font-bold mb-4">Join Our Community of Givers</h3>
                            <p class="text-blue-100 mb-6 max-w-2xl mx-auto">
                                Become part of our donor wall and make a lasting impact. Every contribution helps us reach more people and create positive change.
                            </p>
                            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                                <button class="bg-white text-blue-600 px-8 py-3 rounded-xl font-bold hover:bg-gray-100 transition-colors">
                                    Donate Now
                                </button>
                                <Link href="/donors" class="bg-blue-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-400 transition-colors">
                                    View All Donors
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
    campaigns: {
        type: Array,
        default: () => [],
    },
})

// Sample campaigns data
const campaigns = ref([
    {
        id: 1,
        title: 'Clean Water for Rural Schools',
        description: 'Providing clean drinking water and sanitation facilities to 50 rural schools across Karnataka, ensuring better health and education outcomes for children.',
        category: 'Water',
        goal_amount: 500000,
        raised_amount: 325000,
        progress_percentage: 65,
        donors_count: 127,
        days_left: 15,
        duration: 45,
        slug: 'clean-water-rural-schools',
        ngo_name: 'Water for Karnataka',
        created_at: '2024-01-15'
    },
    {
        id: 2,
        title: 'Education for Every Child',
        description: 'Supporting education initiatives for underprivileged children in urban slums, providing books, uniforms, and digital learning resources.',
        category: 'Education',
        goal_amount: 1000000,
        raised_amount: 750000,
        progress_percentage: 75,
        donors_count: 245,
        days_left: 30,
        duration: 90,
        slug: 'education-every-child',
        ngo_name: 'Education First NGO',
        created_at: '2024-01-20'
    },
    {
        id: 3,
        title: 'Healthcare Access Program',
        description: 'Mobile healthcare units and medical camps for remote villages, providing essential medical services and health awareness programs.',
        category: 'Healthcare',
        goal_amount: 750000,
        raised_amount: 600000,
        progress_percentage: 80,
        donors_count: 189,
        days_left: 22,
        duration: 60,
        slug: 'healthcare-access-program',
        ngo_name: 'Rural Health Initiative',
        created_at: '2024-01-10'
    },
    {
        id: 4,
        title: 'Women Empowerment Workshop',
        description: 'Skill development and entrepreneurship training for women in rural areas, helping them achieve financial independence.',
        category: 'Women',
        goal_amount: 300000,
        raised_amount: 180000,
        progress_percentage: 60,
        donors_count: 89,
        days_left: 45,
        duration: 75,
        slug: 'women-empowerment-workshop',
        ngo_name: 'Women Rise Collective',
        created_at: '2024-01-25'
    },
    {
        id: 5,
        title: 'Plant a Million Trees',
        description: 'Environmental conservation initiative to plant one million trees across Karnataka to combat climate change and restore ecosystems.',
        category: 'Environment',
        goal_amount: 2000000,
        raised_amount: 1200000,
        progress_percentage: 60,
        donors_count: 567,
        days_left: 60,
        duration: 120,
        slug: 'plant-million-trees',
        ngo_name: 'Green Karnataka',
        created_at: '2024-01-05'
    },
    {
        id: 6,
        title: 'Children Nutrition Program',
        description: 'Providing nutritious meals and supplements to malnourished children in tribal areas to improve health and development.',
        category: 'Children',
        goal_amount: 400000,
        raised_amount: 350000,
        progress_percentage: 87,
        donors_count: 156,
        days_left: 8,
        duration: 30,
        slug: 'children-nutrition-program',
        ngo_name: 'Child Welfare Society',
        created_at: '2024-01-18'
    }
])

// Filters and search
const searchQuery = ref('')
const selectedCategory = ref('')
const sortBy = ref('newest')
const isStandaloneMode = ref(false)

// Computed properties
const filteredCampaigns = computed(() => {
    let filtered = campaigns.value

    // Search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(campaign => 
            campaign.title.toLowerCase().includes(query) ||
            campaign.description.toLowerCase().includes(query) ||
            campaign.ngo_name.toLowerCase().includes(query)
        )
    }

    // Category filter
    if (selectedCategory.value) {
        filtered = filtered.filter(campaign => campaign.category === selectedCategory.value)
    }

    // Sort
    switch (sortBy.value) {
        case 'ending':
            filtered.sort((a, b) => a.days_left - b.days_left)
            break
        case 'popular':
            filtered.sort((a, b) => b.donors_count - a.donors_count)
            break
        case 'funded':
            filtered.sort((a, b) => b.progress_percentage - a.progress_percentage)
            break
        case 'newest':
        default:
            filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
            break
    }

    return filtered
})

const totalRaised = computed(() => {
    return campaigns.value.reduce((sum, campaign) => sum + campaign.raised_amount, 0)
})

const totalCampaignDonors = computed(() => {
    return campaigns.value.reduce((sum, campaign) => sum + campaign.donors_count, 0)
})

// Get campaign image
const getCampaignImage = (campaignId) => {
    const images = [
        'https://images.unsplash.com/photo-1578632292335-df3abbb0d586?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1542831371-11b2e40b1b24?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1559028012-481c89fa3d2d?w=800&h=600&fit=crop'
    ]
    return images[(campaignId - 1) % images.length]
}

// Format currency
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 0,
    }).format(amount || 0)
}

// Donor wall data
const topDonors = ref([
    { id: 1, name: 'Rajesh Kumar', type: 'Individual', amount: 75000, campaigns: 3 },
    { id: 2, name: 'TechCorp India', type: 'Corporate', amount: 120000, campaigns: 5 },
    { id: 3, name: 'Priya Sharma', type: 'Individual', amount: 50000, campaigns: 2 },
    { id: 4, name: 'Bangalore Cares Foundation', type: 'Foundation', amount: 200000, campaigns: 8 }
])

const regularDonors = ref([
    { id: 5, name: 'Amit Patel', type: 'Individual', amount: 25000 },
    { id: 6, name: 'SME Association', type: 'Corporate', amount: 35000 },
    { id: 7, name: 'Neha Reddy', type: 'Individual', amount: 15000 },
    { id: 8, name: 'Local Business Group', type: 'Corporate', amount: 45000 },
    { id: 9, name: 'Karthik Nair', type: 'Individual', amount: 20000 }
])

const recentDonors = ref([
    { id: 10, name: 'Anjali Gupta', amount: 5000, timeAgo: '2 hours ago' },
    { id: 11, name: 'StartupHub', amount: 10000, timeAgo: '5 hours ago' },
    { id: 12, name: 'Vikram Singh', amount: 3000, timeAgo: '1 day ago' },
    { id: 13, name: 'Digital Solutions Ltd', amount: 15000, timeAgo: '2 days ago' },
    { id: 14, name: 'Meera Iyer', amount: 7500, timeAgo: '3 days ago' }
])

// Donor statistics
const totalDonors = ref(1247)
const totalAmount = ref(8750000)
const activeCampaigns = ref(6)
const livesImpacted = ref(45000)

// Social sharing functionality
const shareCampaign = (campaign) => {
    const url = `${window.location.origin}/campaigns/${campaign.slug}`
    const text = `Support "${campaign.title}" - ${campaign.description.substring(0, 100)}...`
    
    if (navigator.share) {
        // Native sharing on mobile
        navigator.share({
            title: campaign.title,
            text: text,
            url: url
        }).catch(err => console.log('Error sharing:', err))
    } else {
        // Fallback for desktop - copy to clipboard
        navigator.clipboard.writeText(`${text} ${url}`).then(() => {
            // Show success message
            showShareSuccess()
        }).catch(err => {
            console.error('Error copying to clipboard:', err)
        })
    }
}

const showShareSuccess = () => {
    // Create a temporary success message
    const message = document.createElement('div')
    message.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse'
    message.textContent = 'Campaign link copied to clipboard!'
    document.body.appendChild(message)
    
    // Remove after 3 seconds
    setTimeout(() => {
        message.remove()
    }, 3000)
}

onMounted(() => {
    if (props.campaigns.length > 0) {
        campaigns.value = props.campaigns
    }
    isStandaloneMode.value =
        window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true
})
</script>

<style scoped>
.bg-pattern-dots {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 3;
    -o-line-clamp: 3;
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
