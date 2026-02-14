<template>
    <AppLayout title="Forgot Password - FEVOURD-K">
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 py-8 sm:py-12 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0 bg-pattern"></div>
            </div>
            
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="bg-white/10 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
                    <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[600px]">
                        <!-- Left Side - Info -->
                        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-8 lg:p-16 flex flex-col justify-center relative">
                            <!-- Animated Background Elements -->
                            <div class="absolute inset-0 overflow-hidden">
                                <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
                                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
                            </div>
                            
                            <div class="relative z-10">
                                <!-- Logo -->
                                <div class="mb-8">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20">
                                       <img :src="logoImage" alt="FEVOURD-K" class="w-14 h-10 object-contain">
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="space-y-6">
                                    <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4">Forgot Password?</h1>
                                    <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                                        No worries! We'll help you reset your password and get you back to making an impact.
                                    </p>
                                    
                                    <!-- Support Info -->
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-white">Quick Reset</h3>
                                                <p class="text-sm text-blue-200">Email delivered in seconds</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-white">Secure Process</h3>
                                                <p class="text-sm text-blue-200">Protected reset link</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-white">24/7 Support</h3>
                                                <p class="text-sm text-blue-200">We're here to help</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Form -->
                        <div class="p-8 lg:p-16 relative bg-white/5 backdrop-blur-sm">
                            <div class="max-w-md w-full mx-auto">
                                <!-- Header -->
                                <div class="text-center mb-8">
                                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-3">Reset Password</h2>
                                    <p class="text-blue-200 text-lg">Enter your email to receive reset instructions</p>
                                </div>
                                
                                <form class="space-y-6" @submit.prevent="submit">
                                    <!-- Success Message -->
                                    <div v-if="success" class="p-4 bg-green-500/20 border border-green-400/30 rounded-xl">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <h4 class="font-semibold text-green-200">Email Sent!</h4>
                                                <p class="text-sm text-green-300">
                                                    Check your inbox for password reset instructions
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Error Messages -->
                                    <div v-if="$page.props.errors.email" class="p-4 bg-red-500/20 border border-red-400/30 rounded-xl">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0c-1.657 0-3 .895-3 2s1.343 2 3 2 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <h4 class="font-semibold text-red-200">Error</h4>
                                                <p class="text-sm text-red-300">
                                                    {{ $page.props.errors.email }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Email Field -->
                                    <div v-if="!success">
                                        <label for="email" class="block text-sm font-medium text-blue-200 mb-2">
                                            Email address
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                                </svg>
                                            </div>
                                            <input
                                                id="email"
                                                name="email"
                                                type="email"
                                                autocomplete="email"
                                                required
                                                v-model="form.email"
                                                class="appearance-none relative block w-full pl-10 pr-3 py-3 border border-white/20 bg-white/10 backdrop-blur-sm placeholder-blue-300 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all duration-200"
                                                placeholder="Enter your email address"
                                            >
                                        </div>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div v-if="!success">
                                        <button
                                            type="submit"
                                            :disabled="processing"
                                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105"
                                        >
                                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                                <svg v-if="!processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <svg v-else class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                            {{ processing ? 'Sending...' : 'Send Reset Link' }}
                                        </button>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div v-if="processing" class="mt-4">
                                        <div class="w-full bg-white/20 rounded-full h-2 overflow-hidden">
                                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-2 rounded-full animate-pulse" style="width: 60%; animation: progress 2s ease-in-out infinite;"></div>
                                        </div>
                                        <p class="text-center text-sm text-blue-200 mt-2">Sending reset instructions...</p>
                                    </div>
                                </form>
                                
                                <!-- Back to Login -->
                                <div class="mt-8 text-center">
                                    <p class="text-blue-200">
                                        Remember your password? 
                                        <Link href="/login" class="text-white font-bold hover:text-blue-300 transition-colors">
                                            Sign In
                                        </Link>
                                    </p>
                                </div>
                                
                                <!-- Additional Help -->
                                <div v-if="success" class="mt-8 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                                    <h3 class="text-white font-semibold mb-2">Didn't receive the email?</h3>
                                    <ul class="text-blue-200 text-sm space-y-1">
                                        <li>• Check your spam/junk folder</li>
                                        <li>• Verify the email address is correct</li>
                                        <li>• Wait a few minutes for delivery</li>
                                        <li>• Contact support if needed</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import logoImage from '/public/assets/images/fevourd-k/logo.png'

const form = reactive({
    email: '',
})

const processing = ref(false)
const success = ref(false)

const submit = () => {
    processing.value = true
    success.value = false
    
    Inertia.post('/forgot-password', form, {
        onSuccess: () => {
            processing.value = false
            success.value = true
        },
        onError: () => {
            processing.value = false
            success.value = false
        }
    })
}
</script>

<style scoped>
/* Background pattern */
.bg-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Custom animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes progress {
    0% { width: 0%; }
    50% { width: 80%; }
    100% { width: 100%; }
}

@keyframes scale-in {
    0% { 
        opacity: 0;
        transform: scale(0.9);
    }
    100% { 
        opacity: 1;
        transform: scale(1);
    }
}

.animate-scale-in {
    animation: scale-in 0.3s ease-out;
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
