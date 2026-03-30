<template>
    <AppLayout title="Donations & spending — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="donations">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Donations &amp; spending</h1>
                    <p class="mt-1 max-w-2xl text-sm text-slate-600">
                        See support received and, in the same place, what has been <strong>spent</strong> per your ledger (claims, payouts, manual debits).
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <Link
                        href="/ngo/finance/activity"
                        class="inline-flex rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
                    >
                        All movements
                    </Link>
                    <Link
                        href="/ngo/ledger"
                        class="inline-flex rounded-xl border border-violet-200 bg-violet-50 px-3 py-2 text-xs font-semibold text-violet-800 hover:bg-violet-100"
                    >
                        Ledger
                    </Link>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Donations (recorded)</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-700">₹{{ Number(completedDonationsTotal || 0).toLocaleString('en-IN') }}</p>
                    <p class="mt-1 text-[11px] text-slate-500">Completed gifts in this list</p>
                </div>
                <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50/40 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-800">Ledger credits (in)</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-950">₹{{ Number(transparency.lifetime_credited || 0).toLocaleString('en-IN') }}</p>
                    <p class="mt-1 text-[11px] text-emerald-900/80">
                        Donation lines: ₹{{ Number(transparency.ledger_donation_credits || 0).toLocaleString('en-IN') }}
                    </p>
                </div>
                <div class="rounded-2xl border border-rose-200/80 bg-rose-50/40 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-rose-800">Ledger debits (spent)</p>
                    <p class="mt-1 text-2xl font-bold text-rose-950">₹{{ Number(transparency.lifetime_spent || 0).toLocaleString('en-IN') }}</p>
                    <p class="mt-1 text-[11px] text-rose-900/80">
                        This month: ₹{{ Number(transparency.this_month_spent || 0).toLocaleString('en-IN') }}
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ledger balance</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">₹{{ Number(ledgerCurrentBalance || 0).toLocaleString('en-IN') }}</p>
                    <p class="mt-1 text-[11px] text-slate-500">After all credits &amp; debits</p>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-lg font-bold text-slate-900">Recent spending (ledger)</h2>
                <p class="mt-1 text-sm text-slate-600">Latest debits — full history under Finance → All movements.</p>
                <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Date</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Category</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Detail</th>
                                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in transparency.recent_spending" :key="row.id" class="border-t border-slate-100">
                                    <td class="px-4 py-3 text-slate-600">{{ formatShortDate(row.entry_date) }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ row.category }}</td>
                                    <td class="max-w-xs truncate px-4 py-3 text-slate-600" :title="row.description || ''">{{ row.description || '—' }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-rose-700">₹{{ Number(row.amount).toLocaleString('en-IN') }}</td>
                                </tr>
                                <tr v-if="!transparency.recent_spending?.length">
                                    <td colspan="4" class="px-4 py-10 text-center text-slate-500">
                                        No spending in the ledger yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <h2 class="text-lg font-bold text-slate-900">Donations received</h2>
                <p class="mt-1 text-sm text-slate-600">Every completed gift to your organisation.</p>
            </div>

            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Date</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Supporter</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Campaign</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="d in donations.data" :key="d.id" class="border-t border-slate-100">
                                <td class="px-4 py-3 text-slate-700">{{ formatDate(d.created_at) }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ donorLabel(d) }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ d.campaign?.title || '—' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ d.status }}</td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-700">₹{{ Number(d.amount).toLocaleString('en-IN') }}</td>
                            </tr>
                            <tr v-if="!donations.data?.length">
                                <td colspan="5" class="px-4 py-10 text-center text-slate-500">No donations yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="donations.prev_page_url || donations.next_page_url" class="flex justify-end gap-2 border-t border-slate-100 px-4 py-3">
                    <Link
                        v-if="donations.prev_page_url"
                        :href="donations.prev_page_url"
                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="donations.next_page_url"
                        :href="donations.next_page_url"
                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'

defineProps({
    ngo: { type: Object, required: true },
    donations: { type: Object, required: true },
    completedDonationsTotal: { type: Number, default: 0 },
    ledgerCurrentBalance: { type: Number, default: 0 },
    transparency: {
        type: Object,
        default: () => ({
            lifetime_credited: 0,
            lifetime_spent: 0,
            this_month_credited: 0,
            this_month_spent: 0,
            ledger_donation_credits: 0,
            recent_spending: [],
        }),
    },
})

function donorLabel(d) {
    if (d.is_anonymous) {
        return 'Anonymous'
    }
    const u = d.donor?.user
    if (u?.name) {
        return u.name
    }
    return 'Supporter'
}

function formatDate(iso) {
    if (!iso) {
        return '—'
    }
    return new Date(iso).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}

function formatShortDate(d) {
    if (!d) {
        return '—'
    }
    return new Date(d + 'T12:00:00').toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>
