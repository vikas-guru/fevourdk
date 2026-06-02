<template>
    <AppLayout title="NGO profile — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="profile">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900" data-tour="intro">Organisation profile</h1>
            <p class="mt-1 text-sm text-slate-600">Verification, banking summary, public reach, and what FEVOURD-K includes.</p>

            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm lg:col-span-2" data-tour="details">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500">Details</h2>
                    <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="text-slate-500">Legal name</dt>
                            <dd class="font-semibold text-slate-900">{{ ngo.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Registration</dt>
                            <dd class="font-medium text-slate-800">{{ ngo.registration_number || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">PAN</dt>
                            <dd class="font-medium text-slate-800">{{ ngo.pan || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">City</dt>
                            <dd class="font-medium text-slate-800">{{ ngo.city?.name || '—' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-slate-500">Contact</dt>
                            <dd class="font-medium text-slate-800">{{ ngo.email }} · {{ ngo.phone }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-slate-500">Public URLs</dt>
                            <dd class="mt-1 flex flex-wrap gap-2">
                                <a
                                    v-if="ngo.slug"
                                    :href="'/ngos/' + ngo.slug"
                                    class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >Directory listing</a>
                                <span v-if="ngo.slug && ngo.website_url" class="text-slate-300">|</span>
                                <a
                                    v-if="ngo.website_url"
                                    :href="ngo.website_url"
                                    class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >Microsite</a>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500">Public site traffic</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Anonymous page views on your directory listing and slug microsite paths. In-app pages are excluded.
                    </p>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex justify-between">
                            <span class="text-slate-600">Total views</span>
                            <span class="font-bold text-slate-900">{{ websiteAnalytics.total_views }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-slate-600">Unique sessions (estimate)</span>
                            <span class="font-bold text-slate-900">{{ websiteAnalytics.unique_sessions }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-slate-600">Last 7 days</span>
                            <span class="font-bold text-slate-900">{{ websiteAnalytics.views_last_7_days }}</span>
                        </li>
                    </ul>
                    <p class="mt-4 text-xs text-slate-500">
                        Dedicated “subscriber” counts (email/WhatsApp lists) are not stored here yet; we can add capture on the microsite later.
                    </p>
                    <Link
                        href="/ngo/analytics"
                        class="mt-3 inline-flex text-sm font-semibold text-blue-600 hover:text-blue-700"
                    >
                        Open full analytics (map, regions, devices) →
                    </Link>
                </div>
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500">Documents</h2>
                        <Link href="/ngo/documents" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Manage</Link>
                    </div>
                    <ul v-if="(ngo.documents || []).length" class="mt-3 divide-y divide-slate-100 text-sm">
                        <li v-for="doc in ngo.documents" :key="doc.id" class="flex justify-between py-2">
                            <span class="font-medium text-slate-800">{{ doc.document_type }}</span>
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">{{ doc.status }}</span>
                        </li>
                    </ul>
                    <p v-else class="mt-3 text-sm text-slate-500">No documents uploaded yet.</p>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500">Banking and payouts</h2>
                        <Link href="/ngo/banking" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Manage</Link>
                    </div>
                    <p v-if="!(ngo.bank_accounts || []).length" class="mt-3 text-sm text-slate-500">No bank accounts on file yet.</p>
                    <ul v-else class="mt-3 space-y-2 text-sm">
                        <li v-for="acct in ngo.bank_accounts" :key="acct.id" class="rounded-lg border border-slate-100 bg-slate-50/80 px-3 py-2">
                            <p class="font-semibold text-slate-900">{{ acct.bank_name }}</p>
                            <p class="text-slate-600">····{{ maskAccount(acct.account_number) }} · {{ acct.verification_status }}</p>
                        </li>
                    </ul>
                    <p class="mt-4 text-xs font-bold uppercase tracking-wide text-slate-500">Payment gateways</p>
                    <ul v-if="(ngo.payment_gateways || []).length" class="mt-2 space-y-1 text-sm text-slate-700">
                        <li v-for="gw in ngo.payment_gateways" :key="gw.id">
                            {{ gw.gateway_type }}
                            <span class="text-slate-500">— {{ gw.is_active ? 'active' : 'inactive' }}</span>
                        </li>
                    </ul>
                    <p v-else class="mt-2 text-sm text-slate-500">None configured.</p>
                </div>
            </div>

            <section class="mt-10 rounded-2xl border border-emerald-200/80 bg-gradient-to-br from-emerald-50/90 to-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">Included for NGOs on FEVOURD-K (free tier)</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Core tools are provided as part of the Karnataka voluntary ecosystem programme — no separate SaaS fee for standard use.
                </p>
                <ul class="mt-4 grid gap-2 text-sm text-slate-700 sm:grid-cols-2">
                    <li v-for="item in freeIncludes" :key="item" class="flex gap-2">
                        <span class="text-emerald-600" aria-hidden="true">✓</span>
                        <span>{{ item }}</span>
                    </li>
                </ul>
            </section>

            <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">Roadmap and connectors</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Custom domain mapping, Tawk chat, Facebook and Instagram links, and Google Business auto-post settings live under
                    <Link href="/ngo/digitalization" class="font-semibold text-blue-600 hover:text-blue-700">Digitalization</Link>.
                    One-click auto-publish of new campaigns or posts to Meta (Facebook/Instagram Graph API) is the next connector layer. Today you can deep-link your channels; OAuth and scheduled cross-posting can build on the same settings.
                </p>
            </section>
            <DashboardTour ref="tourRef" :steps="steps" :storage-key="storageKey" auto-start />
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import DashboardTour from '@/Components/NGO/DashboardTour.vue'
import { useNgoTour } from '@/ngo/useNgoTour'

const { tourRef, steps, storageKey } = useNgoTour('profile')

defineProps({
    ngo: { type: Object, required: true },
    websiteAnalytics: {
        type: Object,
        default: () => ({
            total_views: 0,
            unique_sessions: 0,
            views_last_7_days: 0,
        }),
    },
})

const freeIncludes = [
    'NGO workspace with sidebar navigation',
    'Public directory page and optional slug microsite',
    'Campaign creation and donation tracking',
    'Document vault for compliance files',
    'Bank accounts and payment gateway records (secrets never sent to the browser)',
    'Ledger for transparency',
    'Basic public traffic stats on this page',
    'Digitalization hub: theme, logo, custom domain request, chat and social URLs',
]

function maskAccount(num) {
    const s = String(num || '').replace(/\s/g, '')
    if (s.length <= 4) {
        return s || '—'
    }
    return s.slice(-4)
}
</script>
