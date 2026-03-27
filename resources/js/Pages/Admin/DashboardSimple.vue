<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    stats: Object,
    donationStats: Object,
    recentNGOs: Array,
    recentIndividuals: Array,
    totalPageViews: Number,
    avgDailyPageViews: Number,
    topPaths: Array,
    deviceDistribution: Array,
})

const money = (v) => new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(v || 0)
</script>

<template>
    <AdminLayout title="Admin Dashboard">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg border p-4">
                    <p class="text-xs text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold">{{ stats?.total_users || 0 }}</p>
                </div>
                <div class="bg-white rounded-lg border p-4">
                    <p class="text-xs text-gray-500">Total NGOs</p>
                    <p class="text-2xl font-bold">{{ stats?.total_ngos || 0 }}</p>
                </div>
                <div class="bg-white rounded-lg border p-4">
                    <p class="text-xs text-gray-500">Total Donation Amount</p>
                    <p class="text-2xl font-bold">{{ money(stats?.total_donation_amount) }}</p>
                </div>
                <div class="bg-white rounded-lg border p-4">
                    <p class="text-xs text-gray-500">Page Views (30d)</p>
                    <p class="text-2xl font-bold">{{ totalPageViews || 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg border p-4">
                    <h2 class="font-semibold mb-3">Approvals Queue</h2>
                    <div class="flex flex-wrap gap-2">
                        <Link href="/admin/ngos" class="rounded bg-blue-600 px-3 py-2 text-white text-sm">NGOs ({{ stats?.pending_ngos || 0 }} pending)</Link>
                        <Link href="/admin/individuals" class="rounded bg-emerald-600 px-3 py-2 text-white text-sm">Individuals ({{ stats?.pending_individuals || 0 }} pending)</Link>
                    </div>
                </div>
                <div class="bg-white rounded-lg border p-4">
                    <h2 class="font-semibold mb-3">Website Analytics</h2>
                    <p class="text-sm text-gray-600">Avg Daily Views: <span class="font-semibold text-gray-900">{{ avgDailyPageViews || 0 }}</span></p>
                    <div class="mt-2 text-sm">
                        <p class="font-medium mb-1">Top Devices</p>
                        <div v-for="row in deviceDistribution || []" :key="row.device_type" class="flex justify-between border-b py-1">
                            <span>{{ row.device_type }}</span>
                            <span>{{ row.count }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg border p-4">
                    <h2 class="font-semibold mb-3">Recent NGOs</h2>
                    <div v-for="ngo in recentNGOs || []" :key="ngo.id" class="flex items-center justify-between border-b py-2">
                        <div>
                            <p class="text-sm font-medium">{{ ngo.name }}</p>
                            <p class="text-xs text-gray-500">{{ ngo.email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-1 rounded bg-amber-100 text-amber-800">{{ ngo.verification_status }}</span>
                            <Link :href="`/admin/ngos/${ngo.id}`" class="text-xs text-blue-600">View</Link>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border p-4">
                    <h2 class="font-semibold mb-3">Recent Individuals</h2>
                    <div v-for="u in recentIndividuals || []" :key="u.id" class="flex items-center justify-between border-b py-2">
                        <div>
                            <p class="text-sm font-medium">{{ u.name }}</p>
                            <p class="text-xs text-gray-500">{{ u.email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-1 rounded" :class="u.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700'">
                                {{ u.is_active ? 'active' : 'pending' }}
                            </span>
                            <Link :href="`/admin/individuals/${u.id}`" class="text-xs text-blue-600">View</Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border p-4">
                <h2 class="font-semibold mb-3">Top Website Paths (30d)</h2>
                <div v-for="row in topPaths || []" :key="row.path" class="flex justify-between border-b py-1 text-sm">
                    <span class="text-gray-700 truncate">{{ row.path }}</span>
                    <span class="font-medium">{{ row.visits }}</span>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
