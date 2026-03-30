<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
    ngo: Object,
    payments: Array,
    staffUsers: Array,
    categories: Array,
    isNgoAdmin: Boolean,
    canManageTreasury: Boolean,
})

const page = usePage()

const form = useForm({
    payee_user_id: '',
    payee_name: '',
    category: '',
    amount: '',
    payment_method: 'bank_transfer',
    status: 'scheduled',
    utr_reference: '',
    notes: '',
})

function submit() {
    form.post('/ngo/finance/payments', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            form.payment_method = 'bank_transfer'
            form.status = 'scheduled'
        },
    })
}

function markPaid(id) {
    router.patch(`/ngo/finance/payments/${id}/paid`, { utr_reference: '' }, { preserveScroll: true })
}
</script>

<template>
    <AppLayout title="Payments">
        <NgoWorkspaceShell :ngo="ngo" current-key="finance-payments">
            <div class="mx-auto max-w-5xl space-y-8 pb-12">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Outbound payments</h1>
                        <p class="text-sm text-slate-600">Pay staff, interns, vendors, or stipends. Paid items post a debit to the ledger automatically.</p>
                    </div>
                    <Link href="/ngo/finance" class="text-sm font-semibold text-indigo-600 hover:underline">← Finance hub</Link>
                </div>

                <div v-if="canManageTreasury" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-900">New payment</h2>
                    <form class="mt-3 grid gap-3 sm:grid-cols-2" @submit.prevent="submit">
                        <select v-model="form.payee_user_id" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Pay employee (optional)</option>
                            <option v-for="u in staffUsers" :key="u.id" :value="u.id">{{ u.name }} — {{ u.email }}</option>
                        </select>
                        <input v-model="form.payee_name" placeholder="Or payee name (vendor / other)" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <select v-model="form.category" required class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option disabled value="">Category *</option>
                            <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
                        </select>
                        <input v-model="form.amount" type="number" min="0.01" step="0.01" required placeholder="Amount INR *" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <select v-model="form.payment_method" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="bank_transfer">Bank transfer</option>
                            <option value="upi">UPI</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                        <select v-model="form.status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="scheduled">Scheduled (not yet in ledger)</option>
                            <option value="paid">Paid now (post to ledger)</option>
                        </select>
                        <input v-model="form.utr_reference" placeholder="UTR / reference (optional)" class="sm:col-span-2 rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <textarea v-model="form.notes" rows="2" placeholder="Notes" class="sm:col-span-2 rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                        <button type="submit" :disabled="form.processing" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Save</button>
                    </form>
                </div>
                <p v-else class="text-sm text-slate-500">Only NGO admin or finance role can record payments.</p>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-4 py-3 font-semibold">Recent</div>
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-600">
                            <tr>
                                <th class="px-4 py-2">Payee</th>
                                <th class="px-4 py-2">Category</th>
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Recorded</th>
                                <th v-if="canManageTreasury" class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="p in payments" :key="p.id">
                                <td class="px-4 py-2">
                                    <span class="font-medium">{{ p.payee_name || p.payee_user?.name || '—' }}</span>
                                    <span v-if="p.payee_user?.email" class="block text-xs text-slate-500">{{ p.payee_user.email }}</span>
                                </td>
                                <td class="px-4 py-2">{{ p.category }}</td>
                                <td class="px-4 py-2 font-semibold">₹{{ Number(p.amount).toLocaleString() }}</td>
                                <td class="px-4 py-2">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold" :class="p.status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900'">{{ p.status }}</span>
                                </td>
                                <td class="px-4 py-2 text-xs text-slate-500">{{ p.recorded_by?.name }}</td>
                                <td v-if="canManageTreasury" class="px-4 py-2">
                                    <button
                                        v-if="p.status === 'scheduled'"
                                        type="button"
                                        class="text-xs font-semibold text-emerald-700 hover:underline"
                                        @click="markPaid(p.id)"
                                    >
                                        Mark paid
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!(payments || []).length">
                                <td :colspan="canManageTreasury ? 6 : 5" class="px-4 py-8 text-center text-slate-500">No payments yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p v-if="page.props.flash?.success" class="text-sm font-medium text-emerald-600">{{ page.props.flash.success }}</p>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>
