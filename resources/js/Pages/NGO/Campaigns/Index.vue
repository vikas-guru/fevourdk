<template>
    <AppLayout title="NGO campaigns — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="campaigns">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Campaigns</h1>
                    <p class="mt-1 text-sm text-slate-600">Fundraising campaigns for your organisation.</p>
                </div>
                <Link
                    href="/campaigns/create"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    New campaign
                </Link>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Title</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700">Raised</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700">Donations</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="c in campaigns.data" :key="c.id" class="border-t border-slate-100">
                                <td class="px-4 py-3 font-medium text-slate-900">{{ c.title }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ c.status }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-700">
                                    ₹{{ Number(c.donations_sum_amount ?? c.raised_amount ?? 0).toLocaleString('en-IN') }}
                                </td>
                                <td class="px-4 py-3 text-right text-slate-700">{{ c.donations_count ?? 0 }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a
                                        :href="'/campaigns/' + c.slug"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-700"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >View</a>
                                </td>
                            </tr>
                            <tr v-if="!campaigns.data?.length">
                                <td colspan="5" class="px-4 py-10 text-center text-slate-500">No campaigns yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="campaigns.prev_page_url || campaigns.next_page_url" class="flex justify-end gap-2 border-t border-slate-100 px-4 py-3">
                    <Link
                        v-if="campaigns.prev_page_url"
                        :href="campaigns.prev_page_url"
                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="campaigns.next_page_url"
                        :href="campaigns.next_page_url"
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
    campaigns: { type: Object, required: true },
})
</script>
