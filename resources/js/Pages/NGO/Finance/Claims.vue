<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'

defineProps({
    ngo: Object,
    claims: Array,
    isNgoAdmin: Boolean,
    canApproveClaims: Boolean,
    categories: Array,
})

const page = usePage()

const form = useForm({
    amount: '',
    category: '',
    description: '',
    receipt: null,
})

function pickReceipt(e) {
    const f = e.target.files?.[0]
    form.receipt = f || null
}

function submitClaim() {
    form.post('/ngo/finance/claims', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            form.receipt = null
        },
    })
}

function decide(id, status) {
    router.patch(`/ngo/finance/claims/${id}`, { status }, { preserveScroll: true })
}
</script>

<template>
    <AppLayout title="Expense claims">
        <NgoWorkspaceShell :ngo="ngo" current-key="finance-claims">
            <div class="mx-auto max-w-5xl space-y-8 pb-10">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Expense claims</h1>
                        <p class="text-sm text-slate-600">Submit field or office expenses. Approvals post automatically to the cashbook (ledger debit).</p>
                    </div>
                    <div class="flex flex-wrap gap-3 text-sm font-semibold">
                        <Link href="/ngo/finance" class="text-indigo-600 hover:underline">Finance hub</Link>
                        <Link href="/ngo/finance/activity" class="text-violet-600 hover:underline">All movements</Link>
                        <Link href="/ngo/ledger" class="text-violet-600 hover:underline">Ledger</Link>
                        <Link href="/ngo/banking" class="text-slate-600 hover:underline">Banking</Link>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-900">New claim</h2>
                    <form class="mt-3 grid gap-3 sm:grid-cols-2" @submit.prevent="submitClaim">
                        <input v-model="form.amount" type="number" min="0.01" step="0.01" required placeholder="Amount (INR)" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <select v-model="form.category" required class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option disabled value="">Category</option>
                            <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
                        </select>
                        <textarea v-model="form.description" rows="2" placeholder="Description" class="sm:col-span-2 rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                        <div class="sm:col-span-2">
                            <label class="text-xs font-medium text-slate-600">Receipt (optional)</label>
                            <input type="file" accept=".jpg,.jpeg,.png,.pdf" class="mt-1 block w-full text-sm" @change="pickReceipt">
                        </div>
                        <button type="submit" :disabled="form.processing" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Submit</button>
                    </form>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-4 py-3 font-semibold">Claims</div>
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-600">
                            <tr>
                                <th class="px-4 py-2">Who</th>
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Category</th>
                                <th class="px-4 py-2">Status</th>
                                <th v-if="canApproveClaims" class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="c in claims" :key="c.id">
                                <td class="px-4 py-2">{{ c.user?.name }}</td>
                                <td class="px-4 py-2 font-medium">₹{{ Number(c.amount).toLocaleString() }}</td>
                                <td class="px-4 py-2">{{ c.category }}</td>
                                <td class="px-4 py-2">
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold">{{ c.status }}</span>
                                </td>
                                <td v-if="canApproveClaims" class="space-x-2 px-4 py-2 whitespace-nowrap">
                                    <template v-if="c.status === 'pending'">
                                        <button type="button" class="text-xs font-semibold text-emerald-600" @click="decide(c.id, 'approved')">Approve</button>
                                        <button type="button" class="text-xs font-semibold text-red-600" @click="decide(c.id, 'rejected')">Reject</button>
                                    </template>
                                </td>
                            </tr>
                            <tr v-if="!(claims || []).length">
                                <td :colspan="canApproveClaims ? 5 : 4" class="px-4 py-8 text-center text-slate-500">No claims.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p v-if="page.props.flash?.success" class="text-sm font-medium text-emerald-600">{{ page.props.flash.success }}</p>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>
