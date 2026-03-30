<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    ngo: Object,
    rows: Array,
    isNgoAdmin: Boolean,
    canManageTreasury: Boolean,
})

function rowClass(r) {
    if (r.kind === 'scheduled_payment') return 'bg-amber-50/40'
    return r.direction === 'credit' ? 'bg-emerald-50/20' : ''
}
</script>

<template>
    <AppLayout title="Cashbook">
        <NgoWorkspaceShell :ngo="ngo" current-key="finance-activity">
            <div class="mx-auto max-w-6xl space-y-6 pb-12">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">All movements</h1>
                        <p class="text-sm text-slate-600">Ledger lines (donations, claims, outbound, manual) plus scheduled payments not yet paid.</p>
                    </div>
                    <Link href="/ngo/finance" class="text-sm font-semibold text-indigo-600 hover:underline">← Finance hub</Link>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                                <th class="px-4 py-3 text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="r in rows" :key="r.id" :class="rowClass(r)">
                                <td class="px-4 py-2 whitespace-nowrap text-slate-700">{{ r.date }}</td>
                                <td class="px-4 py-2">
                                    <span v-if="r.kind === 'scheduled_payment'" class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-900">Scheduled</span>
                                    <span v-else class="rounded-full px-2 py-0.5 text-[10px] font-bold" :class="r.direction === 'credit' ? 'bg-emerald-100 text-emerald-900' : 'bg-red-100 text-red-900'">{{ r.direction }}</span>
                                </td>
                                <td class="px-4 py-2 text-slate-600">{{ r.category }}</td>
                                <td class="max-w-xs truncate px-4 py-2 text-slate-700">{{ r.description || '—' }}</td>
                                <td class="px-4 py-2 text-right font-semibold tabular-nums" :class="r.direction === 'credit' ? 'text-emerald-800' : 'text-red-800'">
                                    {{ r.direction === 'credit' ? '+' : '−' }}₹{{ Number(r.amount).toLocaleString() }}
                                </td>
                                <td class="px-4 py-2 text-right text-slate-500 tabular-nums">
                                    {{ r.balance_after != null ? '₹'+Number(r.balance_after).toLocaleString() : '—' }}
                                </td>
                            </tr>
                            <tr v-if="!(rows || []).length">
                                <td colspan="6" class="px-4 py-10 text-center text-slate-500">No activity yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>
