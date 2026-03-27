<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({ user: Object })
const approve = () => router.post(`/admin/individuals/${props.user.id}/approve`)
</script>

<template>
    <AdminLayout title="Individual Details">
        <div class="space-y-4">
            <div class="bg-white border rounded-lg p-4">
                <h1 class="text-lg font-semibold">{{ user.name }}</h1>
                <p class="text-sm text-gray-600">{{ user.email }} · {{ user.phone || '-' }}</p>
                <p class="text-sm mt-2">Type: {{ user.user_type }}</p>
                <p class="text-sm">Status: {{ user.is_active ? 'active' : 'pending' }}</p>
                <div class="mt-3">
                    <button class="rounded bg-emerald-600 px-3 py-2 text-white text-sm" @click="approve">Approve Individual</button>
                </div>
            </div>

            <div class="bg-white border rounded-lg p-4">
                <h2 class="font-semibold mb-2">Donor Stats</h2>
                <p class="text-sm">Total Donated: {{ user.donor?.total_donated ?? 0 }}</p>
                <p class="text-sm">Donation Count: {{ user.donor?.donation_count ?? 0 }}</p>
                <p class="text-sm">Recent Donations: {{ user.donations?.length ?? 0 }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
