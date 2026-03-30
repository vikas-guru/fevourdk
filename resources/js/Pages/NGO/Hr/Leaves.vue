<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
    ngo: Object,
    leaveTypes: Array,
    leaveRequests: Array,
    isNgoAdmin: Boolean,
})

const page = usePage()

const typeForm = useForm({
    name: '',
    default_days_per_year: '',
    is_paid: true,
})

const requestForm = useForm({
    leave_type_id: '',
    start_date: '',
    end_date: '',
    reason: '',
})

function submitType() {
    typeForm.post('/ngo/hr/leave-types', {
        preserveScroll: true,
        onSuccess: () => typeForm.reset(),
    })
}

function destroyType(id) {
    if (!confirm('Remove this leave type?')) return
    router.delete(`/ngo/hr/leave-types/${id}`, { preserveScroll: true })
}

function submitRequest() {
    requestForm.post('/ngo/hr/leaves', {
        preserveScroll: true,
        onSuccess: () => requestForm.reset(),
    })
}

function decide(id, status) {
    router.patch(`/ngo/hr/leaves/${id}`, { status, decision_notes: '' }, { preserveScroll: true })
}

function fmtDate(d) {
    if (!d) return '—'
    return String(d).slice(0, 10)
}
</script>

<template>
    <AppLayout title="Leave">
        <NgoWorkspaceShell :ngo="ngo" current-key="hr-leaves">
            <div class="mx-auto max-w-5xl space-y-8 pb-10">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Leave</h1>
                        <p class="text-sm text-slate-600">Types, balances context, and approval workflow for your NGO.</p>
                    </div>
                    <div class="flex gap-3 text-sm font-semibold">
                        <Link href="/ngo/hr" class="text-indigo-600 hover:underline">HRMS overview</Link>
                        <Link href="/ngo/hr/team" class="text-slate-600 hover:underline">Team →</Link>
                    </div>
                </div>

                <div v-if="isNgoAdmin" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-900">Add leave type</h2>
                    <form class="mt-3 flex flex-wrap items-end gap-3" @submit.prevent="submitType">
                        <div>
                            <label class="text-xs font-medium text-slate-600">Name</label>
                            <input v-model="typeForm.name" required class="mt-1 block rounded-xl border border-slate-300 px-3 py-2 text-sm" placeholder="e.g. Casual leave">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-600">Days / year (optional)</label>
                            <input v-model="typeForm.default_days_per_year" type="number" min="0" max="365" class="mt-1 w-28 rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input v-model="typeForm.is_paid" type="checkbox" class="rounded border-slate-300">
                            Paid
                        </label>
                        <button type="submit" :disabled="typeForm.processing" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Add type</button>
                    </form>
                    <p v-if="typeForm.hasErrors" class="mt-2 text-xs text-red-600">{{ typeForm.errors.name || typeForm.errors.default_days_per_year }}</p>
                </div>

                <div v-if="(leaveTypes || []).length" class="flex flex-wrap gap-2">
                    <span
                        v-for="t in leaveTypes"
                        :key="t.id"
                        class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-800"
                    >
                        {{ t.name }}
                        <span v-if="t.default_days_per_year != null" class="text-slate-500">({{ t.default_days_per_year }} d/yr)</span>
                        <span v-if="!t.is_paid" class="text-amber-700">unpaid</span>
                        <button
                            v-if="isNgoAdmin"
                            type="button"
                            class="ml-1 text-red-600 hover:underline"
                            @click="destroyType(t.id)"
                        >
                            ×
                        </button>
                    </span>
                </div>
                <p v-else class="text-sm text-slate-500">No leave types yet. NGO admin should add at least one.</p>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-900">Request leave</h2>
                    <form class="mt-3 grid gap-3 sm:grid-cols-2" @submit.prevent="submitRequest">
                        <div class="sm:col-span-2">
                            <label class="text-xs font-medium text-slate-600">Type</label>
                            <select v-model="requestForm.leave_type_id" required class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                <option disabled value="">Select</option>
                                <option v-for="t in leaveTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-600">From</label>
                            <input v-model="requestForm.start_date" type="date" required class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-600">To</label>
                            <input v-model="requestForm.end_date" type="date" required class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-xs font-medium text-slate-600">Reason (optional)</label>
                            <textarea v-model="requestForm.reason" rows="2" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                        </div>
                        <button type="submit" :disabled="requestForm.processing" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Submit request</button>
                    </form>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-4 py-3 font-semibold text-slate-900">
                        {{ isNgoAdmin ? 'All requests' : 'My requests' }}
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-600">
                                <tr>
                                    <th v-if="isNgoAdmin" class="px-4 py-2">Employee</th>
                                    <th class="px-4 py-2">Type</th>
                                    <th class="px-4 py-2">Dates</th>
                                    <th class="px-4 py-2">Days</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th v-if="isNgoAdmin" class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="r in leaveRequests" :key="r.id">
                                    <td v-if="isNgoAdmin" class="px-4 py-2">
                                        <span class="font-medium text-slate-900">{{ r.user?.name }}</span>
                                        <span class="block text-xs text-slate-500">{{ r.user?.email }}</span>
                                    </td>
                                    <td class="px-4 py-2">{{ r.leave_type?.name }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ fmtDate(r.start_date) }} → {{ fmtDate(r.end_date) }}</td>
                                    <td class="px-4 py-2">{{ r.days }}</td>
                                    <td class="px-4 py-2">
                                        <span
                                            class="rounded-full px-2 py-0.5 text-xs font-semibold"
                                            :class="{
                                                'bg-amber-100 text-amber-900': r.status === 'pending',
                                                'bg-emerald-100 text-emerald-800': r.status === 'approved',
                                                'bg-red-100 text-red-800': r.status === 'rejected',
                                            }"
                                        >{{ r.status }}</span>
                                    </td>
                                    <td v-if="isNgoAdmin" class="space-x-2 px-4 py-2 whitespace-nowrap">
                                        <template v-if="r.status === 'pending'">
                                            <button type="button" class="text-xs font-semibold text-emerald-600 hover:underline" @click="decide(r.id, 'approved')">Approve</button>
                                            <button type="button" class="text-xs font-semibold text-red-600 hover:underline" @click="decide(r.id, 'rejected')">Reject</button>
                                        </template>
                                    </td>
                                </tr>
                                <tr v-if="!(leaveRequests || []).length">
                                    <td :colspan="isNgoAdmin ? 6 : 4" class="px-4 py-8 text-center text-slate-500">No leave requests yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <p v-if="page.props.flash?.success" class="text-sm font-medium text-emerald-600">{{ page.props.flash.success }}</p>
                <p v-if="page.props.flash?.error" class="text-sm font-medium text-red-600">{{ page.props.flash.error }}</p>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>
