<template>
    <AppLayout title="NGO donations — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="donations">
            <h1 class="text-2xl font-bold text-slate-900">Donations</h1>
            <p class="mt-1 text-sm text-slate-600">All gifts recorded for your organisation.</p>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
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
</script>
