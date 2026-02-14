<template>
    <AppLayout title="Corporate Registration - FEVOURD-K">
        <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Corporate CSR Registration</h1>
                        <p class="text-gray-600 mb-6">Register your company for CSR partnerships and social impact initiatives</p>
                        
                        <!-- Redirect to CSR Registration -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                            <div class="flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-green-800 mb-2">Redirecting to CSR Registration</h2>
                            <p class="text-green-700 mb-6">You will be redirected to the comprehensive CSR registration form in a moment...</p>
                            
                            <div class="flex justify-center">
                                <Link href="/csr/register" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
                                    Go to CSR Registration
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </Link>
                            </div>
                        </div>
                        
                        <!-- Auto redirect script -->
                        <div class="text-center text-sm text-gray-500">
                            If you are not redirected automatically, 
                            <Link href="/csr/register" class="text-green-600 hover:text-green-700 underline">click here</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Auto redirect to CSR registration after 2 seconds
onMounted(() => {
    setTimeout(() => {
        window.location.href = '/csr/register'
    }, 2000)
})
</script>
