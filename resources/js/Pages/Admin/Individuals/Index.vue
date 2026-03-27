<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({ users: Object })
const approve = (id) => router.post(`/admin/individuals/${id}/approve`)
</script>

<template>
    <AdminLayout title="Individuals">
        <div class="bg-white border rounded-lg overflow-hidden">
            <div class="p-4 border-b">
                <h1 class="text-lg font-semibold">Individuals Review</h1>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email/Phone</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.id" class="border-t">
                        <td class="px-4 py-3">{{ user.name }}</td>
                        <td class="px-4 py-3">{{ user.email }} · {{ user.phone || '-' }}</td>
                        <td class="px-4 py-3">{{ user.is_active ? 'active' : 'pending' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Link :href="`/admin/individuals/${user.id}`" class="rounded-md border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 hover:bg-blue-100">View</Link>
                                <button class="rounded-md bg-emerald-600 px-2.5 py-1 text-xs font-medium text-white hover:bg-emerald-700" @click="approve(user.id)">Approve</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
