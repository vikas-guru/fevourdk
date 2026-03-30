<template>
    <AppLayout title="NGO Ledger - FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="ledger">
            <div class="max-w-6xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">NGO Ledger</h1>
                    <p class="text-sm text-slate-600 mt-1">Track every credit/debit entry with running balance.</p>
                    <p class="mt-3 text-lg font-semibold text-emerald-700">Current Balance: ₹{{ Number(currentBalance || 0).toLocaleString() }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Add Ledger Entry</h2>
                    <form class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end" @submit.prevent="addEntry">
                        <input v-model="form.entry_date" type="date" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <select v-model="form.type" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="credit">Credit</option>
                            <option value="debit">Debit</option>
                        </select>
                        <input v-model="form.category" placeholder="Category" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <input v-model="form.amount" type="number" min="0.01" step="0.01" placeholder="Amount" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <button type="submit" :disabled="form.processing" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60">
                            {{ form.processing ? 'Adding...' : 'Add' }}
                        </button>
                    </form>
                    <input v-model="form.description" placeholder="Description (optional)" class="mt-3 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-200">
                        <h2 class="font-semibold text-slate-900">Ledger Entries</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Date</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Type</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Category</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Description</th>
                                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Amount</th>
                                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Balance After</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="entry in entries" :key="entry.id" class="border-t border-slate-100">
                                    <td class="px-4 py-3 text-slate-700">{{ entry.entry_date }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-1 text-xs font-semibold" :class="entry.type === 'credit' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'">
                                            {{ entry.type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ entry.category }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ entry.description || '-' }}</td>
                                    <td class="px-4 py-3 text-right font-medium" :class="entry.type === 'credit' ? 'text-emerald-700' : 'text-red-700'">
                                        {{ entry.type === 'credit' ? '+' : '-' }} ₹{{ Number(entry.amount).toLocaleString() }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-slate-800">₹{{ Number(entry.balance_after).toLocaleString() }}</td>
                                </tr>
                                <tr v-if="entries.length === 0">
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">No ledger entries yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'

const props = defineProps({
    ngo: Object,
    entries: Array,
    currentBalance: Number,
})

const form = useForm({
    entry_date: new Date().toISOString().slice(0, 10),
    type: 'credit',
    category: 'manual_adjustment',
    description: '',
    amount: '',
})

const addEntry = () => {
    form.post('/ngo/ledger', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('amount', 'description')
        },
    })
}
</script>
