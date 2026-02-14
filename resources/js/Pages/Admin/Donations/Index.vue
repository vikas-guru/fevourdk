<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    donations: Object,
})

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 0,
    }).format(amount || 0)
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString()
}
</script>

<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Page Header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Donations</h1>
                <p class="mt-1 text-sm text-gray-600">Track and manage all platform donations</p>
            </div>

            <!-- Donations Table -->
            <div class="bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Donor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Campaign
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="donation in donations.data" :key="donation.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    #{{ donation.id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-green-500 to-blue-500 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ donation.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ donation.user?.name || 'Anonymous' }}</div>
                                            <div class="text-xs text-gray-500">{{ donation.user?.email || 'No email' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ donation.campaign?.title || 'General Donation' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">
                                        {{ formatCurrency(donation.amount) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(donation.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div v-if="donations.data.length === 0" class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">💰</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No donations yet</h3>
                    <p class="text-gray-600">Donations will appear here once users start contributing to campaigns.</p>
                </div>
                
                <!-- Pagination -->
                <div v-if="donations.links" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <a :href="donations.prev_page_url" 
                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                        <a :href="donations.next_page_url" 
                           class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ donations.from || 0 }}</span>
                                to
                                <span class="font-medium">{{ donations.to || 0 }}</span>
                                of
                                <span class="font-medium">{{ donations.total }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <a v-for="link in donations.links" :key="link.label" :href="link.url"
                                   :class="[
                                       link.active 
                                           ? 'bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                                           : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                                   ]">
                                    <span v-html="link.label"></span>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
