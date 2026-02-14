<template>
    <AppLayout title="NGO Dashboard - FEVOURD-K">
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Welcome Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 mb-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Welcome to Your NGO Dashboard</h1>
                            <p class="text-blue-100">{{ ngo.name }}</p>
                            <div class="mt-4 flex items-center space-x-4">
                                <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                                    {{ ngo.verification_status === 'verified' ? 'Verified' : 'Pending Verification' }}
                                </span>
                                <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                                    ID: {{ ngo.id }}
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <img v-if="ngo.logo" :src="'/storage/' + ngo.logo" :alt="ngo.name" class="w-24 h-24 rounded-full border-4 border-white/30">
                            <div v-else class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-building text-3xl text-white/60"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Notice -->
                <div v-if="ngo.verification_status !== 'verified'" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mr-4"></i>
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-800">Verification Pending</h3>
                            <p class="text-yellow-700 mt-1">Your NGO is currently under verification. You'll be notified once the process is complete. Some features may be limited until verification.</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hand-holding-usd text-blue-600 text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">{{ stats.campaigns }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Active Campaigns</h3>
                        <p class="text-gray-600 text-sm mt-1">Manage your fundraising campaigns</p>
                        <inertia-link href="/ngo/campaigns" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-3 inline-block">
                            View Campaigns →
                        </inertia-link>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-donate text-green-600 text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">₹{{ stats.totalRaised.toLocaleString() }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Total Raised</h3>
                        <p class="text-gray-600 text-sm mt-1">Lifetime donations received</p>
                        <inertia-link href="/ngo/donations" class="text-green-600 hover:text-green-700 text-sm font-medium mt-3 inline-block">
                            View Donations →
                        </inertia-link>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-purple-600 text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">{{ stats.donors }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Total Donors</h3>
                        <p class="text-gray-600 text-sm mt-1">People who supported your cause</p>
                        <inertia-link href="/ngo/supporters" class="text-purple-600 hover:text-purple-700 text-sm font-medium mt-3 inline-block">
                            View Supporters →
                        </inertia-link>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-globe text-orange-600 text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">Live</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Your Website</h3>
                        <p class="text-gray-600 text-sm mt-1">Public-facing website</p>
                        <a :href="ngo.website_url" target="_blank" class="text-orange-600 hover:text-orange-700 text-sm font-medium mt-3 inline-block">
                            Visit Website →
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Recent Donations -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-bold text-gray-900">Recent Donations</h2>
                                <inertia-link href="/ngo/donations" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    View All
                                </inertia-link>
                            </div>
                            
                            <div v-if="recentDonations.length > 0" class="space-y-4">
                                <div v-for="donation in recentDonations" :key="donation.id" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-donate text-green-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ donation.donor_name }}</p>
                                            <p class="text-sm text-gray-600">{{ donation.campaign_title }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-green-600">₹{{ donation.amount.toLocaleString() }}</p>
                                        <p class="text-xs text-gray-500">{{ formatDate(donation.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-else class="text-center py-8 text-gray-500">
                                <i class="fas fa-donate text-4xl text-gray-300 mb-4"></i>
                                <p>No donations yet. Start a campaign to begin receiving donations!</p>
                                <inertia-link href="/ngo/campaigns/create" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Create Campaign
                                </inertia-link>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links & Resources -->
                    <div class="space-y-6">
                        <!-- Quick Actions -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
                            <div class="space-y-3">
                                <inertia-link href="/ngo/campaigns/create" class="block w-full text-left p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <div class="flex items-center">
                                        <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                                        <span class="font-medium text-gray-900">Create New Campaign</span>
                                    </div>
                                </inertia-link>
                                
                                <inertia-link href="/ngo/profile/edit" class="block w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <i class="fas fa-edit text-gray-600 mr-3"></i>
                                        <span class="font-medium text-gray-900">Edit NGO Profile</span>
                                    </div>
                                </inertia-link>
                                
                                <inertia-link href="/ngo/documents" class="block w-full text-left p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-upload text-yellow-600 mr-3"></i>
                                        <span class="font-medium text-gray-900">Manage Documents</span>
                                    </div>
                                </inertia-link>
                                
                                <inertia-link href="/ngo/banking" class="block w-full text-left p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                    <div class="flex items-center">
                                        <i class="fas fa-university text-green-600 mr-3"></i>
                                        <span class="font-medium text-gray-900">Banking Settings</span>
                                    </div>
                                </inertia-link>
                            </div>
                        </div>

                        <!-- Resources -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Resources</h2>
                            <div class="space-y-3">
                                <a href="#" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <i class="fas fa-book text-blue-600 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">NGO Guide</p>
                                            <p class="text-xs text-gray-600">Learn how to use the platform</p>
                                        </div>
                                    </div>
                                </a>
                                
                                <a href="#" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <i class="fas fa-video text-purple-600 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">Video Tutorials</p>
                                            <p class="text-xs text-gray-600">Step-by-step guides</p>
                                        </div>
                                    </div>
                                </a>
                                
                                <a href="#" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <i class="fas fa-headset text-green-600 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">Support Center</p>
                                            <p class="text-xs text-gray-600">Get help when you need it</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events/Deadlines -->
                <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Upcoming Events & Deadlines</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Campaign Review</p>
                                    <p class="text-xs text-gray-600">Monthly review</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Next review in 5 days</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-file-alt text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Document Renewal</p>
                                    <p class="text-xs text-gray-600">Annual compliance</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Due in 30 days</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-chart-line text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Impact Report</p>
                                    <p class="text-xs text-gray-600">Quarterly report</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Submit by month end</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    ngo: Object,
    stats: Object,
    recentDonations: Array
})

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-IN', { 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric' 
    })
}
</script>
