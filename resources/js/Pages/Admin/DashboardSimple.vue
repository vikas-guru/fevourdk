<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    stats: Object,
    donationStats: Object,
    recentUsers: Array,
    recentNGOs: Array,
    recentCampaigns: Array,
})
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="mt-2 text-gray-600">Welcome to the Fevourd-K Admin Panel</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">Total Users</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ (stats?.total_users || 0).toLocaleString() }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">Total NGOs</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ (stats?.total_ngos || 0).toLocaleString() }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">Campaigns</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ (stats?.total_campaigns || 0).toLocaleString() }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">Donations</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ (stats?.total_donations || 0).toLocaleString() }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <Link href="/admin/ngos/pending" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center">
                        Review Pending NGOs
                    </Link>
                    <Link href="/admin/settings" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-center">
                        System Settings
                    </Link>
                    <Link href="/" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-center">
                        View Public Site
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
