<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    ngo: { type: Object, required: true },
    showFullBankNumbers: { type: Boolean, default: false },
    canConfigureBank: { type: Boolean, default: false },
})

const page = usePage()
const editingId = ref(null)

const addForm = useForm({
    bank_name: '',
    account_number: '',
    account_holder_name: '',
    ifsc_code: '',
    branch_address: '',
})

const editForm = useForm({
    bank_name: '',
    account_number: '',
    account_holder_name: '',
    ifsc_code: '',
    branch_address: '',
})

function maskAccount(num, full) {
    const s = String(num || '').replace(/\s/g, '')
    if (!s.length) return '—'
    if (full) return s.replace(/(.{4})/g, '$1 ').trim()
    if (s.length <= 4) return s
    return '····' + s.slice(-4)
}

function startEdit(acct) {
    editingId.value = acct.id
    editForm.bank_name = acct.bank_name || ''
    editForm.account_number = acct.account_number || ''
    editForm.account_holder_name = acct.account_holder_name || ''
    editForm.ifsc_code = acct.ifsc_code || ''
    editForm.branch_address = acct.branch_address || ''
}

function cancelEdit() {
    editingId.value = null
    editForm.reset()
}

function saveEdit(id) {
    editForm.put(`/ngo/finance/bank-accounts/${id}`, {
        preserveScroll: true,
        onSuccess: () => cancelEdit(),
    })
}

function destroyAccount(id) {
    if (!confirm('Remove this bank account from the workspace?')) return
    router.delete(`/ngo/finance/bank-accounts/${id}`, { preserveScroll: true })
}

function submitAdd() {
    addForm.post('/ngo/finance/bank-accounts', {
        preserveScroll: true,
        onSuccess: () => addForm.reset(),
    })
}
</script>

<template>
    <AppLayout title="NGO banking — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="banking">
            <div class="mx-auto max-w-3xl space-y-8 pb-10">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Banking and gateways</h1>
                        <p class="mt-1 text-sm text-slate-600">Accounts for payouts and provider configuration. Turn on full numbers from the <Link href="/ngo/finance" class="font-semibold text-indigo-600 hover:underline">finance hub</Link> (admin only).</p>
                    </div>
                    <Link href="/ngo/finance/activity" class="text-sm font-semibold text-slate-600 hover:underline">Cashbook →</Link>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Bank accounts</h2>
                    <p v-if="!showFullBankNumbers" class="mt-1 text-xs text-slate-500">Account numbers are masked unless your NGO admin enables full display for admin &amp; finance users.</p>

                    <div v-if="canConfigureBank" class="mt-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/80 p-4">
                        <h3 class="text-sm font-semibold text-slate-800">Add account</h3>
                        <form class="mt-3 grid gap-2 sm:grid-cols-2" @submit.prevent="submitAdd">
                            <input v-model="addForm.bank_name" required placeholder="Bank name *" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <input v-model="addForm.account_holder_name" required placeholder="Account holder *" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <input v-model="addForm.account_number" required placeholder="Account number *" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <input v-model="addForm.ifsc_code" required placeholder="IFSC *" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <textarea v-model="addForm.branch_address" rows="2" placeholder="Branch address" class="sm:col-span-2 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            <button type="submit" :disabled="addForm.processing" class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-bold text-white">Add account</button>
                        </form>
                    </div>

                    <div v-if="!(ngo.bank_accounts || []).length" class="mt-3 text-sm text-slate-500">No bank accounts added yet.</div>
                    <ul v-else class="mt-4 space-y-3">
                        <li
                            v-for="acct in ngo.bank_accounts"
                            :key="acct.id"
                            class="rounded-xl border border-slate-100 bg-slate-50/80 px-4 py-3 text-sm"
                        >
                            <template v-if="editingId === acct.id && canConfigureBank">
                                <div class="grid gap-2 sm:grid-cols-2">
                                    <input v-model="editForm.bank_name" class="rounded-lg border border-slate-300 px-2 py-1.5 text-xs">
                                    <input v-model="editForm.account_holder_name" class="rounded-lg border border-slate-300 px-2 py-1.5 text-xs">
                                    <input v-model="editForm.account_number" class="rounded-lg border border-slate-300 px-2 py-1.5 text-xs">
                                    <input v-model="editForm.ifsc_code" class="rounded-lg border border-slate-300 px-2 py-1.5 text-xs">
                                    <textarea v-model="editForm.branch_address" rows="2" class="sm:col-span-2 rounded-lg border border-slate-300 px-2 py-1.5 text-xs" />
                                </div>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <button type="button" class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white" :disabled="editForm.processing" @click="saveEdit(acct.id)">Save</button>
                                    <button type="button" class="rounded border border-slate-300 px-2 py-1 text-xs" @click="cancelEdit">Cancel</button>
                                </div>
                            </template>
                            <template v-else>
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ acct.bank_name }}</p>
                                        <p class="text-slate-700">{{ acct.account_holder_name }}</p>
                                        <p class="font-mono text-slate-600">Account {{ maskAccount(acct.account_number, showFullBankNumbers) }}</p>
                                        <p class="text-slate-600">IFSC {{ acct.ifsc_code }}</p>
                                        <p v-if="acct.branch_address" class="mt-1 text-xs text-slate-500">{{ acct.branch_address }}</p>
                                        <p class="mt-1 text-xs font-semibold uppercase text-slate-500">{{ acct.verification_status }}</p>
                                    </div>
                                    <div v-if="canConfigureBank" class="flex gap-2 text-xs font-semibold">
                                        <button type="button" class="text-indigo-600 hover:underline" @click="startEdit(acct)">Edit</button>
                                        <button type="button" class="text-red-600 hover:underline" @click="destroyAccount(acct.id)">Remove</button>
                                    </div>
                                </div>
                            </template>
                        </li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Payment gateways</h2>
                    <p class="mt-1 text-xs text-slate-500">API keys and secrets are stored on the server only and are not shown here.</p>
                    <div v-if="!(ngo.payment_gateways || []).length" class="mt-3 text-sm text-slate-500">No gateways configured.</div>
                    <ul v-else class="mt-4 space-y-3">
                        <li
                            v-for="gw in ngo.payment_gateways"
                            :key="gw.id"
                            class="rounded-xl border border-slate-100 px-4 py-3 text-sm"
                        >
                            <p class="font-bold text-slate-900">{{ gw.gateway_type }}</p>
                            <p class="text-slate-600">Merchant ID: {{ gw.merchant_id }}</p>
                            <p class="mt-1 text-xs font-semibold" :class="gw.is_active ? 'text-emerald-700' : 'text-slate-500'">
                                {{ gw.is_active ? 'Active' : 'Inactive' }}
                            </p>
                        </li>
                    </ul>
                </div>

                <p v-if="page.props.flash?.success" class="text-sm font-medium text-emerald-600">{{ page.props.flash.success }}</p>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>
