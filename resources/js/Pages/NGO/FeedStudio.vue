<template>
    <AppLayout title="Feed studio — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="feed-studio">
            <div class="mx-auto max-w-6xl space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8" data-tour="intro">
                    <h1 class="text-2xl font-bold text-slate-900">Feed studio</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-relaxed text-slate-600">
                        Analytics for updates you published to the live community feed: views, reactions, comments, shares, and the SEO summary we generate for each public link.
                    </p>
                    <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-5">
                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Posts</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">{{ totals.posts }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Views</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">{{ totals.views }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Reactions</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">{{ totals.reactions }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Comments</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">{{ totals.comments }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 sm:col-span-1 col-span-2">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Shares</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">{{ totals.shares }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" data-tour="compose">
                    <div class="border-b border-slate-100 px-4 py-3 sm:px-6">
                        <h2 class="text-sm font-bold text-slate-900">Your posts</h2>
                        <p class="text-xs text-slate-500">Up to 100 most recent. Views count when someone opens the public post page or scrolls to your card on the main feed (once per browser session).</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Title</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Date</th>
                                    <th class="px-4 py-3 text-center">Views</th>
                                    <th class="px-4 py-3 text-center">👍</th>
                                    <th class="px-4 py-3 text-center">❤️</th>
                                    <th class="px-4 py-3 text-center">🤝</th>
                                    <th class="px-4 py-3 text-center">Comments</th>
                                    <th class="px-4 py-3 text-center">Shares</th>
                                    <th class="px-4 py-3 text-center">Media</th>
                                    <th class="px-4 py-3">Public page / SEO</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="row in posts" :key="row.id" class="hover:bg-slate-50/80">
                                    <td class="max-w-[200px] px-4 py-3">
                                        <p class="font-semibold text-slate-900 line-clamp-2">{{ row.title }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500 line-clamp-2">{{ row.body_preview }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-xs text-slate-600">{{ formatDate(row.created_at) }}</td>
                                    <td class="px-4 py-3 text-center font-semibold text-slate-800">{{ row.views_count }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700">{{ row.likes }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700">{{ row.loves }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700">{{ row.supports }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700">{{ row.comments_count }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700">{{ row.shares_count }}</td>
                                    <td class="px-4 py-3 text-center text-slate-600">{{ row.media_count }}</td>
                                    <td class="px-4 py-3 align-top">
                                        <a
                                            :href="row.public_url"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-xs font-semibold text-blue-700 hover:underline"
                                        >Open public page ↗</a>
                                        <p v-if="row.seo_description" class="mt-2 text-[11px] leading-snug text-slate-500 line-clamp-3" :title="row.seo_description">
                                            <span class="font-medium text-slate-600">Meta:</span> {{ row.seo_description }}
                                        </p>
                                        <p v-if="row.seo_keywords" class="mt-1 text-[10px] text-slate-400 line-clamp-2" :title="row.seo_keywords">
                                            {{ row.seo_keywords }}
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-if="!posts.length" class="px-6 py-12 text-center text-sm text-slate-500">
                        No posts yet.
                        <Link href="/ngo/post-update" class="font-semibold text-blue-700 hover:underline">Post an update</Link>
                    </p>
                </div>
            </div>
            <DashboardTour ref="tourRef" :steps="steps" :storage-key="storageKey" auto-start />
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import DashboardTour from '@/Components/NGO/DashboardTour.vue'
import { useNgoTour } from '@/ngo/useNgoTour'
import { Link } from '@inertiajs/vue3'

defineProps({
    ngo: { type: Object, required: true },
    posts: { type: Array, default: () => [] },
    totals: { type: Object, required: true },
})

const { tourRef, steps, storageKey } = useNgoTour('feed-studio')

function formatDate(iso) {
    return new Date(iso).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
