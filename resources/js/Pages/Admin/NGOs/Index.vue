<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({ ngos: Object })
const processingId = ref(null)

const approve = (id) => {
    processingId.value = id
    router.post(`/admin/ngos/${id}/verify`, {}, {
        preserveScroll: true,
        onFinish: () => {
            processingId.value = null
        },
    })
}

const reject = (id) => {
    processingId.value = id
    router.post(`/admin/ngos/${id}/reject`, {}, {
        preserveScroll: true,
        onFinish: () => {
            processingId.value = null
        },
    })
}
</script>

<template>
    <AdminLayout title="NGOs">
        <div class="bg-white border rounded-lg overflow-hidden">
            <div class="p-4 border-b">
                <h1 class="text-lg font-semibold">NGO Approvals & Directory</h1>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">NGO</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Location</th>
                        <th class="px-4 py-2 text-left">Docs</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ngo in ngos.data" :key="ngo.id" class="border-t">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ ngo.name }}</p>
                            <p class="text-xs text-gray-500">{{ ngo.email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ ngo.verification_status }}</td>
                        <td class="px-4 py-3">{{ ngo.city?.name || '-' }}, {{ ngo.state?.name || '-' }}</td>
                        <td class="px-4 py-3">{{ ngo.documents_count || 0 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Link :href="`/admin/ngos/${ngo.id}`" class="rounded-md border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 hover:bg-blue-100">View</Link>
                                <button class="rounded-md bg-emerald-600 px-2.5 py-1 text-xs font-medium text-white hover:bg-emerald-700 disabled:opacity-60" :disabled="processingId === ngo.id || ngo.verification_status === 'verified'" @click="approve(ngo.id)">
                                    {{ processingId === ngo.id ? 'Approving...' : 'Approve' }}
                                </button>
                                <button class="rounded-md bg-rose-600 px-2.5 py-1 text-xs font-medium text-white hover:bg-rose-700 disabled:opacity-60" :disabled="processingId === ngo.id || ngo.verification_status === 'suspended'" @click="reject(ngo.id)">
                                    {{ processingId === ngo.id ? 'Updating...' : 'Reject' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
