<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import DashboardTour from '@/Components/NGO/DashboardTour.vue'
import { useNgoTour } from '@/ngo/useNgoTour'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    ngo: Object,
    stats: Object,
    isNgoAdmin: Boolean,
    canManageTreasury: Boolean,
})

const prefForm = useForm({
    finance_show_full_bank_numbers: props.ngo?.finance_show_full_bank_numbers ?? false,
})

function savePrefs() {
    prefForm.put('/ngo/finance/preferences', { preserveScroll: true })
}

const { tourRef, steps, storageKey } = useNgoTour('finance-hub')
</script>

<template>
    <AppLayout title="Finance">
        <NgoWorkspaceShell :ngo="ngo" current-key="finance-hub">
            <div class="mx-auto max-w-5xl space-y-8 pb-12">
                <div data-tour="intro">
                    <h1 class="text-2xl font-bold text-slate-900">Finance & treasury</h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-600">
                        Same platform login as the rest of your NGO. Assign the <strong>ngo_finance</strong> role for a dedicated treasury user
                        (they only see finance screens). Every donation, approved claim, and outbound payment flows into the cashbook.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-tour="balances">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase text-slate-500">Ledger balance</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">₹{{ Number(stats?.ledger_balance ?? 0).toLocaleString() }}</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase text-emerald-800">In (30 days)</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-900">₹{{ Number(stats?.inflow_30d ?? 0).toLocaleString() }}</p>
                    </div>
                    <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase text-amber-900">Out (30 days)</p>
                        <p class="mt-2 text-2xl font-bold text-amber-950">₹{{ Number(stats?.outflow_30d ?? 0).toLocaleString() }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase text-slate-500">Pending claims</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ stats?.pending_claims ?? 0 }}</p>
                        <Link href="/ngo/finance/claims" class="mt-2 inline-block text-xs font-semibold text-indigo-600 hover:underline">Review →</Link>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:col-span-2">
                        <p class="text-xs font-bold uppercase text-slate-500">Scheduled outbound</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">₹{{ Number(stats?.scheduled_payments_total ?? 0).toLocaleString() }}</p>
                        <Link href="/ngo/finance/payments" class="mt-2 inline-block text-xs font-semibold text-indigo-600 hover:underline">Payments →</Link>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 text-sm font-semibold" data-tour="actions">
                    <Link href="/ngo/finance/activity" class="rounded-xl bg-slate-900 px-4 py-2 text-white hover:bg-slate-800">View all movements</Link>
                    <Link href="/ngo/finance/payments" class="rounded-xl border border-slate-300 px-4 py-2 text-slate-800 hover:bg-slate-50">Record payment</Link>
                    <Link href="/ngo/banking" class="rounded-xl border border-slate-300 px-4 py-2 text-slate-800 hover:bg-slate-50">Banking</Link>
                    <Link href="/ngo/ledger" class="rounded-xl border border-slate-300 px-4 py-2 text-slate-800 hover:bg-slate-50">Manual ledger</Link>
                </div>

                <div v-if="isNgoAdmin" class="rounded-2xl border border-indigo-200 bg-indigo-50/60 p-5">
                    <h2 class="font-semibold text-indigo-950">Bank number display</h2>
                    <p class="mt-1 text-xs text-indigo-900/90">
                        When enabled, NGO admins and finance users see full account numbers on the Banking page (still over HTTPS — only share screens in trusted environments).
                    </p>
                    <form class="mt-3 flex flex-wrap items-center gap-3" @submit.prevent="savePrefs">
                        <label class="flex items-center gap-2 text-sm text-indigo-950">
                            <input v-model="prefForm.finance_show_full_bank_numbers" type="checkbox" class="rounded border-slate-300">
                            Show full account numbers to admin &amp; finance
                        </label>
                        <button type="submit" :disabled="prefForm.processing" class="rounded-lg bg-indigo-700 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-800">Save</button>
                    </form>
                </div>
            </div>
            <DashboardTour ref="tourRef" :steps="steps" :storage-key="storageKey" auto-start />
        </NgoWorkspaceShell>
    </AppLayout>
</template>
