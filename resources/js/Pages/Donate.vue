<template>
    <AppLayout title="Donate - FEVOURD-K">
        <div
            class="min-h-screen py-6 sm:py-10 relative overflow-hidden"
            :class="isStandaloneMode ? 'bg-slate-50' : 'bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900'"
        >
            <!-- Background Pattern -->
            <div v-if="!isStandaloneMode" class="absolute inset-0 opacity-10">
                <div class="absolute inset-0 bg-pattern"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Hero Section -->
                <div v-if="!isStandaloneMode" class="text-center mb-12 sm:mb-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl shadow-2xl mb-6 backdrop-blur-sm border border-white/10">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                        Make a <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Difference</span> Today
                    </h1>
                    <p class="text-xl sm:text-2xl text-blue-200 max-w-4xl mx-auto leading-relaxed">
                        Your generous donation helps us support NGOs across Karnataka in their mission to create positive change in communities.
                    </p>
                    
                    <!-- Impact Stats -->
                    <div class="flex flex-wrap justify-center gap-8 mt-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">₹2.5Cr+</div>
                            <div class="text-sm text-blue-300">Donations Raised</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">50K+</div>
                            <div class="text-sm text-blue-300">Lives Impacted</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">31</div>
                            <div class="text-sm text-blue-300">Districts Covered</div>
                        </div>
                    </div>
                </div>

                <div v-if="isStandaloneMode" class="mb-5">
                    <h1 class="text-2xl font-bold text-slate-900">Quick Donate</h1>
                    <p class="text-sm text-slate-600 mt-1">Choose a campaign and complete your donation in seconds.</p>
                </div>

                <!-- Featured Campaigns -->
                <div v-if="featuredCampaigns.length > 0" class="mb-12">
                    <h2 v-if="isStandaloneMode" class="text-xl font-bold text-slate-900 mb-4">Choose Campaign</h2>
                    <h2 v-else class="text-3xl font-bold text-white mb-8 text-center">Featured Campaigns</h2>
                    <div class="max-w-xl mx-auto mb-6">
                        <input
                            v-model="campaignSearch"
                            type="text"
                            placeholder="Filter campaigns by title or NGO"
                            class="w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="isStandaloneMode ? 'border border-slate-300 bg-white text-slate-900 placeholder-slate-400' : 'border border-white/20 bg-white/10 backdrop-blur-sm placeholder-blue-300 text-white'"
                        >
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                        <div v-for="campaign in filteredFeaturedCampaigns" :key="campaign.id" class="group rounded-2xl overflow-hidden border transition-all duration-500 hover:shadow-xl" :class="isStandaloneMode ? 'bg-white border-slate-200' : 'bg-white/10 backdrop-blur-md border-white/20 hover:bg-white/15 hover:scale-105 hover:shadow-2xl'">
                            <div class="relative">
                                <img v-if="campaign.featured_image" :src="campaign.featured_image" :alt="campaign.title" class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-1">
                                        <div class="text-sm font-semibold text-gray-900">{{ campaign.progress_percentage || 0 }}% Funded</div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-3 transition-colors" :class="isStandaloneMode ? 'text-slate-900 group-hover:text-blue-600' : 'text-white group-hover:text-blue-300'">{{ campaign.title }}</h3>
                                <p class="leading-relaxed mb-4" :class="isStandaloneMode ? 'text-slate-600' : 'text-blue-200'">{{ campaign.description }}</p>
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm mb-2" :class="isStandaloneMode ? 'text-slate-500' : 'text-blue-300'">
                                        <span>Raised: ₹{{ campaign.raised_amount?.toLocaleString() || 0 }}</span>
                                        <span>Goal: ₹{{ campaign.goal_amount?.toLocaleString() || 0 }}</span>
                                    </div>
                                    <div class="w-full rounded-full h-3 overflow-hidden" :class="isStandaloneMode ? 'bg-slate-200' : 'bg-white/20'">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500" :style="{ width: `${campaign.progress_percentage || 0}%` }"></div>
                                    </div>
                                </div>
                                <inertia-link :href="`/campaigns/${campaign.slug}`" class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Donate Now
                                </inertia-link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Donate Form -->
                <div class="rounded-3xl p-6 sm:p-8 lg:p-10 border shadow-lg" :class="isStandaloneMode ? 'bg-white border-slate-200' : 'bg-white/10 backdrop-blur-xl border-white/20 shadow-2xl'">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-7 text-center" :class="isStandaloneMode ? 'text-slate-900' : 'text-white'">Quick Donate</h2>
                    
                    <form @submit.prevent="processDonation" class="space-y-8">
                        <!-- Campaign Selection -->
                        <div>
                            <label class="block text-sm font-medium mb-3" :class="isStandaloneMode ? 'text-slate-700' : 'text-blue-200'">Select Campaign</label>
                            <select v-model="form.campaign_id" class="w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200" :class="isStandaloneMode ? 'border border-slate-300 bg-white text-slate-900' : 'border border-white/20 bg-white/10 backdrop-blur-sm text-white'">
                                <option value="" :class="isStandaloneMode ? 'bg-white text-slate-900' : 'bg-gray-800 text-white'">Choose a campaign...</option>
                                <option v-for="campaign in filteredFeaturedCampaigns" :key="campaign.id" :value="campaign.id" :class="isStandaloneMode ? 'bg-white text-slate-900' : 'bg-gray-800 text-white'">
                                    {{ campaign.title }}
                                </option>
                            </select>
                        </div>

                        <!-- Donation Amount -->
                        <div>
                            <label class="block text-sm font-medium mb-3" :class="isStandaloneMode ? 'text-slate-700' : 'text-blue-200'">Donation Amount (₹)</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                <button type="button" @click="form.amount = 500" :class="isStandaloneMode ? (form.amount === 500 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300') : (form.amount === 500 ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent' : 'bg-white/10 text-blue-200 border border-white/20')" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border transition-all duration-200">
                                    ₹500
                                </button>
                                <button type="button" @click="form.amount = 1000" :class="isStandaloneMode ? (form.amount === 1000 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300') : (form.amount === 1000 ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent' : 'bg-white/10 text-blue-200 border border-white/20')" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border transition-all duration-200">
                                    ₹1,000
                                </button>
                                <button type="button" @click="form.amount = 5000" :class="isStandaloneMode ? (form.amount === 5000 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300') : (form.amount === 5000 ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent' : 'bg-white/10 text-blue-200 border border-white/20')" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border transition-all duration-200">
                                    ₹5,000
                                </button>
                                <button type="button" @click="form.amount = 10000" :class="isStandaloneMode ? (form.amount === 10000 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300') : (form.amount === 10000 ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent' : 'bg-white/10 text-blue-200 border border-white/20')" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border transition-all duration-200">
                                    ₹10,000
                                </button>
                            </div>
                            <input v-model.number="form.amount" type="number" placeholder="Enter custom amount" class="w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200" :class="isStandaloneMode ? 'border border-slate-300 bg-white text-slate-900 placeholder-slate-400' : 'border border-white/20 bg-white/10 backdrop-blur-sm placeholder-blue-300 text-white'">
                        </div>

                        <!-- Donor Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-3" :class="isStandaloneMode ? 'text-slate-700' : 'text-blue-200'">Full Name</label>
                                <input v-model="form.name" type="text" placeholder="Enter your name" class="w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200" :class="isStandaloneMode ? 'border border-slate-300 bg-white text-slate-900 placeholder-slate-400' : 'border border-white/20 bg-white/10 backdrop-blur-sm placeholder-blue-300 text-white'">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-3" :class="isStandaloneMode ? 'text-slate-700' : 'text-blue-200'">Email</label>
                                <input v-model="form.email" type="email" placeholder="Enter your email" class="w-full px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200" :class="isStandaloneMode ? 'border border-slate-300 bg-white text-slate-900 placeholder-slate-400' : 'border border-white/20 bg-white/10 backdrop-blur-sm placeholder-blue-300 text-white'">
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium mb-3" :class="isStandaloneMode ? 'text-slate-700' : 'text-blue-200'">Payment Method</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button type="button" @click="form.payment_method = 'razorpay'" :class="isStandaloneMode ? (form.payment_method === 'razorpay' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300') : (form.payment_method === 'razorpay' ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent' : 'bg-white/10 text-blue-200 border border-white/20')" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41 1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                    Razorpay
                                </button>
                                <button type="button" @click="form.payment_method = 'upi'" :class="isStandaloneMode ? (form.payment_method === 'upi' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300') : (form.payment_method === 'upi' ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent' : 'bg-white/10 text-blue-200 border border-white/20')" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41 1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                    UPI
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" :disabled="!form.campaign_id || !form.amount || !form.name || !form.email || !form.payment_method || processing" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-xl font-bold hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105">
                            <span class="flex items-center justify-center">
                                <svg v-if="!processing" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg v-else class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ processing ? 'Processing...' : 'Proceed to Payment' }}
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Trust Badges -->
                <div v-if="!isStandaloneMode" class="mt-12 text-center">
                    <div class="inline-flex items-center space-x-8 bg-white/10 backdrop-blur-sm rounded-2xl px-8 py-4 border border-white/20">
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-green-400 text-sm font-medium">Secure Payment</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="text-blue-400 text-sm font-medium">Tax Deductible</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="text-purple-400 text-sm font-medium">80G Tax Benefit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    featuredCampaigns: Array
})
const page = usePage()

const form = ref({
    campaign_id: '',
    amount: '',
    name: '',
    email: '',
    payment_method: ''
})

const processing = ref(false)
const isStandaloneMode = ref(
    window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true
)
const campaignSearch = ref('')

const filteredFeaturedCampaigns = computed(() => {
    if (!campaignSearch.value.trim()) {
        return props.featuredCampaigns
    }

    const query = campaignSearch.value.toLowerCase()
    return props.featuredCampaigns.filter((campaign) =>
        campaign.title?.toLowerCase().includes(query) ||
        campaign.ngo?.name?.toLowerCase().includes(query)
    )
})

onMounted(() => {
    const user = page.props.auth?.user
    if (user) {
        form.value.name = user.name || ''
        form.value.email = user.email || ''
    }
})

const processDonation = async () => {
    if (processing.value) return
    
    processing.value = true
    
    try {
        if (form.value.payment_method === 'razorpay') {
            await initiateRazorpayPayment()
        } else {
            // Handle other payment methods
            await router.post('/donations/process', form.value)
        }
    } catch (error) {
        console.error('Payment error:', error)
    } finally {
        processing.value = false
    }
}

const initiateRazorpayPayment = () => {
    return new Promise((resolve, reject) => {
        const options = {
            key: 'rzp_test_YourKeyHere', // Replace with your Razorpay key
            amount: form.value.amount * 100, // Amount in paise
            currency: 'INR',
            name: 'FEVOURD-K',
            description: 'Donation for Social Impact',
            image: '/assets/images/fevourd-k/logo.png',
            handler: function (response) {
                // Payment successful
                router.post('/donations/process', {
                    ...form.value,
                    razorpay_payment_id: response.razorpay_payment_id,
                    payment_status: 'success'
                })
                resolve(response)
            },
            prefill: {
                name: form.value.name,
                email: form.value.email,
            },
            theme: {
                color: '#3b82f6',
                backdrop_color: '#1e293b'
            },
            modal: {
                ondismiss: function() {
                    processing.value = false
                    reject('Payment cancelled')
                }
            }
        }
        
        const rzp = new Razorpay(options)
        rzp.open()
    })
}
</script>
