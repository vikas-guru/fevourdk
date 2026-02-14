<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const activeTab = ref('overview')
const showCreateModal = ref(false)
const saving = ref(false)

const stats = reactive({
    total_users: 15420,
    total_ngos: 847,
    total_campaigns: 156,
    total_donations: 28475000,
    total_donors: 3247,
    new_donors_this_month: 187,
    avg_donation: 2500
})

const settings = reactive({
    platform_commission: 5,
    min_donation: 100,
    auto_approve_ngos: 'manual',
    featured_duration: 30,
    email_notifications: true,
    sms_notifications: false
})

const campaigns = reactive([
    {
        id: 1,
        title: 'Clean Water for Rural Schools',
        description: 'Providing clean drinking water to 50 rural schools across Karnataka',
        goal_amount: 500000,
        raised_amount: 325000,
        progress_percentage: 65,
        featured_image: '/assets/images/campaigns/water.jpg',
        target_amount: 500000,
        end_date: '2024-03-15',
        duration: 45,
        status: 'active'
    },
    {
        id: 2,
        title: 'Education for Every Child',
        description: 'Supporting education initiatives for underprivileged children in urban areas',
        goal_amount: 1000000,
        raised_amount: 750000,
        progress_percentage: 75,
        featured_image: '/assets/images/campaigns/education.jpg',
        target_amount: 1000000,
        end_date: '2024-06-30',
        duration: 90,
        status: 'active'
    },
    {
        id: 3,
        title: 'Healthcare Access Program',
        description: 'Mobile healthcare units for remote villages',
        goal_amount: 750000,
        raised_amount: 600000,
        progress_percentage: 80,
        featured_image: '/assets/images/campaigns/healthcare.jpg',
        target_amount: null,
        end_date: null,
        duration: 60,
        status: 'active'
    }
])

const donors = reactive([
    {
        id: 1,
        name: 'Priya Sharma',
        avatar: '/assets/images/donors/priya.jpg',
        total_donations: 12500,
        total_amount: 12500,
        total_donations: 12,
        is_recurring: true,
        is_verified: true
    },
    {
        id: 2,
        name: 'Ramesh Kumar',
        avatar: null,
        total_donations: 8500,
        total_amount: 8500,
        total_donations: 3,
        is_recurring: false,
        is_verified: true
    },
    {
        id: 3,
        name: 'Sunita Reddy',
        avatar: '/assets/images/donors/sunita.jpg',
        total_donations: 15000,
        total_amount: 15000,
        total_donations: 8,
        is_recurring: false,
        is_verified: false
    },
    {
        id: 4,
        name: 'Anand Patel',
        avatar: null,
        total_donations: 5000,
        total_amount: 5000,
        total_donations: 5,
        is_recurring: false,
        is_verified: false
    },
    {
        id: 5,
        name: 'Meera Gowda',
        avatar: '/assets/images/donors/meera.jpg',
        total_donations: 25000,
        total_amount: 25000,
        total_donations: 15,
        is_recurring: true,
        is_verified: true
    },
    {
        id: 6,
        name: 'Vikas Singh',
        avatar: null,
        total_donations: 7500,
        total_amount: 7500,
        total_donations: 6,
        is_recurring: false,
        is_verified: false
    }
])

const recentActivity = reactive([
    {
        id: 1,
        title: 'New NGO Registration',
        description: 'Sneha Children Foundation registered',
        time: '2 hours ago'
    },
    {
        id: 2,
        title: 'Campaign Milestone',
        description: 'Clean Water campaign reached 65%',
        time: '5 hours ago'
    },
    {
        id: 3,
        title: 'Donation Spike',
        description: 'Record donations in last hour',
        time: '1 hour ago'
    }
])

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 0,
    }).format(amount || 0)
}

const createCampaign = () => {
    showCreateModal.value = true
}

const exportCampaigns = () => {
    // Export functionality
    console.log('Exporting campaigns...')
}

const saveSettings = async () => {
    saving.value = true
    try {
        await router.post('/admin/settings', settings)
        // Show success message
        setTimeout(() => {
            saving.value = false
        }, 2000)
    } catch (error) {
        console.error('Error saving settings:', error)
        saving.value = false
    }
}
</script>

<template>
    <AdminLayout title="Admin Dashboard - FEVOURD-K">
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0 bg-pattern"></div>
            </div>
            
            <div class="relative z-10">
                <!-- Header -->
                <div class="bg-white/10 backdrop-blur-md border-b border-white/20">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between h-16">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h1 class="text-2xl font-bold text-white">Admin Dashboard</h1>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-white/20 text-white' : 'text-blue-200 hover:text-white'" class="px-4 py-2 rounded-lg transition-colors">
                                    Overview
                                </button>
                                <button @click="activeTab = 'campaigns'" :class="activeTab === 'campaigns' ? 'bg-white/20 text-white' : 'text-blue-200 hover:text-white'" class="px-4 py-2 rounded-lg transition-colors">
                                    Campaigns
                                </button>
                                <button @click="activeTab = 'donors'" :class="activeTab === 'donors' ? 'bg-white/20 text-white' : 'text-blue-200 hover:text-white'" class="px-4 py-2 rounded-lg transition-colors">
                                    Donors
                                </button>
                                <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'bg-white/20 text-white' : 'text-blue-200 hover:text-white'" class="px-4 py-2 rounded-lg transition-colors">
                                    Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <!-- Overview Tab -->
                    <div v-if="activeTab === 'overview'" class="space-y-8">
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ stats.total_users || 0 }}</div>
                                    <div class="text-sm text-blue-200">Total Users</div>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-green-600 mb-2">{{ stats.total_ngos || 0 }}</div>
                                    <div class="text-sm text-green-200">Verified NGOs</div>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-purple-600 mb-2">{{ stats.total_campaigns || 0 }}</div>
                                    <div class="text-sm text-purple-200">Active Campaigns</div>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-orange-600 mb-2">₹{{ formatCurrency(stats.total_donations) }}</div>
                                    <div class="text-sm text-orange-200">Total Donations</div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
                            <h2 class="text-2xl font-bold text-white mb-6">Recent Activity</h2>
                            <div class="space-y-4">
                                <div v-for="activity in recentActivity" :key="activity.id" class="flex items-center justify-between p-4 bg-white/5 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-white">{{ activity.title }}</div>
                                            <div class="text-xs text-blue-200">{{ activity.description }}</div>
                                        </div>
                                    </div>
                                    <div class="text-sm text-blue-200">{{ activity.time }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Campaigns Tab -->
                <div v-if="activeTab === 'campaigns'" class="space-y-8">
                    <!-- Campaign Actions -->
                    <div class="flex flex-wrap gap-4 mb-8">
                        <button @click="showCreateModal = true" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m0-4.01L12 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                            Create New Campaign
                        </button>
                        <button @click="exportCampaigns" class="bg-white/10 backdrop-blur-sm text-blue-200 px-6 py-3 rounded-xl font-medium hover:bg-white/20 transition-all duration-300 border border-white/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0v1m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Export Campaigns
                        </button>
                    </div>

                    <!-- Campaigns List -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div v-for="campaign in campaigns" :key="campaign.id" class="bg-white/10 backdrop-blur-md rounded-2xl overflow-hidden border border-white/20 hover:bg-white/15 transition-all duration-300 hover:shadow-2xl">
                            <div class="relative">
                                <img v-if="campaign.featured_image" :src="campaign.featured_image" :alt="campaign.title" class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute top-4 right-4">
                                    <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-1">
                                        <div class="text-sm font-semibold text-gray-900">{{ campaign.progress_percentage || 0 }}% Funded</div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-white mb-2">{{ campaign.title }}</h3>
                                        <p class="text-blue-200 mb-4">{{ campaign.description }}</p>
                                        <div class="flex items-center space-x-4 mb-4">
                                            <span class="text-sm text-blue-300">Goal: ₹{{ formatCurrency(campaign.goal_amount) }}</span>
                                            <span class="text-sm text-green-400">Raised: ₹{{ formatCurrency(campaign.raised_amount) }}</span>
                                        </div>
                                        <div class="flex items-center space-x-4 text-sm text-blue-300">
                                            <span>🎯 {{ campaign.target_amount ? 'Target' : 'Time-bound' }}</span>
                                            <span v-if="campaign.target_amount">Ends: {{ campaign.end_date }}</span>
                                            <span v-if="!campaign.target_amount">Duration: {{ campaign.duration }} days</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-white/20 rounded-full h-3 overflow-hidden mb-4">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500" :style="{ width: `${campaign.progress_percentage || 0}%` }"></div>
                                    </div>
                                    <div class="flex space-x-3">
                                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            View Details
                                        </button>
                                        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                                            Edit Campaign
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donors Tab -->
                <div v-if="activeTab === 'donors'" class="space-y-8">
                    <!-- Donor Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600 mb-2">{{ stats.total_donors || 0 }}</div>
                                <div class="text-sm text-green-200">Total Donors</div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600 mb-2">{{ stats.new_donors_this_month || 0 }}</div>
                                <div class="text-sm text-blue-200">New This Month</div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600 mb-2">₹{{ formatCurrency(stats.avg_donation) }}</div>
                                <div class="text-sm text-purple-200">Average Donation</div>
                            </div>
                        </div>
                    </div>

                    <!-- Donor Wall -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
                        <h2 class="text-2xl font-bold text-white mb-6">Donor Wall</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="donor in donors" :key="donor.id" class="bg-white/5 rounded-xl p-6 border border-white/20 hover:bg-white/10 transition-all duration-300">
                                <div class="flex items-center space-x-4 mb-4">
                                    <img v-if="donor.avatar" :src="donor.avatar" :alt="donor.name" class="w-16 h-16 rounded-full object-cover">
                                    <div v-else class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-xl font-bold">{{ donor.name.charAt(0) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-lg font-semibold text-white">{{ donor.name }}</div>
                                        <div class="text-sm text-blue-200">{{ donor.total_donations }} donations • ₹{{ formatCurrency(donor.total_amount) }}</div>
                                    </div>
                                </div>
                                <div class="text-sm text-blue-200">
                                    <span v-if="donor.is_recurring">🔄 Recurring Donor</span>
                                    <span v-if="donor.is_verified">✅ Verified Donor</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Overview -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Analytics Overview</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-blue-900">Total Page Views (30 days)</p>
                                    <p class="text-xs text-blue-700">Last 30 days activity</p>
                                </div>
                                <p class="text-2xl font-bold text-blue-600">{{ (totalPageViews || 0).toLocaleString() }}</p>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-green-900">Daily Average</p>
                                    <p class="text-xs text-green-700">Average per day</p>
                                </div>
                                <p class="text-2xl font-bold text-green-600">{{ (avgDailyPageViews || 0).toLocaleString() }}</p>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-purple-900">Active Programs</p>
                                    <p class="text-xs text-purple-700">Currently running</p>
                                </div>
                                <p class="text-2xl font-bold text-purple-600">{{ (stats?.active_programs || 0).toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
/* Background pattern */
.bg-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>
