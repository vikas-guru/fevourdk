<template>
    <AppLayout title="Auto-share to social — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="social-connect">
            <div class="mx-auto max-w-3xl space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h1 class="text-2xl font-bold text-slate-900">Auto-share to Facebook, Instagram, or LinkedIn</h1>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        <strong>You do not need this to post.</strong> Use
                        <Link href="/ngo/post-update" class="font-semibold text-blue-700 underline decoration-blue-200 hover:text-blue-800">Post an update</Link>
                        for the normal, simple way — your story appears on the live feed right away.
                    </p>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        This page is only if you want the <em>same</em> updates copied to your social pages. Someone who already manages your Facebook Page,
                        Instagram, or LinkedIn company profile can complete the technical section below once.
                    </p>
                    <ul class="mt-4 list-inside list-disc space-y-1 text-sm text-slate-600">
                        <li>Facebook and LinkedIn can share text and a link.</li>
                        <li>Instagram needs a <strong>photo</strong> on each update you want to cross-post.</li>
                    </ul>
                </div>

                <section
                    v-for="key in platformOrder"
                    :key="key"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
                >
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">{{ friendlyTitle(key) }}</h2>
                            <p class="mt-1 text-sm text-slate-600">
                                <span v-if="channels[key].connected" class="font-semibold text-emerald-700">Saved — ready to use</span>
                                <span v-else>Not set up yet</span>
                            </p>
                        </div>
                    </div>

                    <form class="mt-4 space-y-4" @submit.prevent="savePlatform(key)">
                        <label class="flex cursor-pointer items-start gap-3 text-sm text-slate-800">
                            <input
                                v-model="local[key].auto_post_enabled"
                                type="checkbox"
                                class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span>
                                When we publish an update from FEVOURD-K, also try to publish it on {{ friendlyTitle(key) }}.
                            </span>
                        </label>

                        <details class="group rounded-xl border border-slate-200 bg-slate-50/50 open:bg-white open:shadow-sm">
                            <summary class="cursor-pointer list-none px-4 py-3 text-sm font-semibold text-slate-800 marker:hidden [&::-webkit-details-marker]:hidden">
                                <span class="flex items-center justify-between gap-2">
                                    <span>Technical setup — for whoever runs your social or website</span>
                                    <span class="text-xs font-normal text-slate-500 group-open:hidden">Show</span>
                                    <span class="hidden text-xs font-normal text-slate-500 group-open:inline">Hide</span>
                                </span>
                            </summary>
                            <div class="space-y-4 border-t border-slate-100 px-4 py-4 text-sm">
                                <p class="text-xs leading-relaxed text-slate-500">
                                    {{ technicalHint(key) }}
                                    <a
                                        v-if="helpLink(key)"
                                        :href="helpLink(key)"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="ml-1 font-semibold text-blue-700 hover:underline"
                                    >Read official instructions ↗</a>
                                </p>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-600">Page or profile name (optional, for your records)</label>
                                    <input
                                        v-model="local[key].account_handle"
                                        type="text"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="@yourorganisation"
                                    >
                                </div>

                                <div v-if="key === 'facebook'">
                                    <label class="mb-1 block text-xs font-medium text-slate-600">Facebook Page ID (numbers only)</label>
                                    <input
                                        v-model="local[key].page_id"
                                        type="text"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="From Page settings → About, or Meta tools"
                                    >
                                </div>

                                <div v-if="key === 'instagram'">
                                    <label class="mb-1 block text-xs font-medium text-slate-600">Instagram Business account ID</label>
                                    <input
                                        v-model="local[key].instagram_account_id"
                                        type="text"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="From Meta Business Suite / developer tools"
                                    >
                                </div>

                                <div v-if="key === 'linkedin'">
                                    <label class="mb-1 block text-xs font-medium text-slate-600">LinkedIn organisation ID (numbers)</label>
                                    <input
                                        v-model="local[key].linkedin_organization_id"
                                        type="text"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Company page admin URL or LinkedIn developer tools"
                                    >
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-600">Access token (secret code from Meta or LinkedIn)</label>
                                    <input
                                        v-model="local[key].access_token"
                                        type="password"
                                        autocomplete="off"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        :placeholder="channels[key].connected ? 'Leave blank to keep the current token' : 'Paste the token once — we store it encrypted'"
                                    >
                                </div>

                                <p v-if="key === 'google_business'" class="text-xs text-amber-800">
                                    Google Business auto-post is not active on the platform yet.
                                </p>
                            </div>
                        </details>

                        <p v-if="page.props.errors && firstErrorFor(key)" class="text-sm text-red-600">
                            {{ firstErrorFor(key) }}
                        </p>

                        <div class="flex flex-wrap gap-2 pt-2">
                            <button
                                type="submit"
                                class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
                                :disabled="saving === key"
                            >
                                {{ saving === key ? 'Saving…' : 'Save' }}
                            </button>
                            <button
                                v-if="channels[key].connected"
                                type="button"
                                class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                                @click="disconnect(key)"
                            >
                                Remove connection
                            </button>
                        </div>
                    </form>
                </section>

                <p v-if="page.props.flash?.success" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                    {{ page.props.flash.success }}
                </p>
                <p v-if="page.props.flash?.error" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                    {{ page.props.flash.error }}
                </p>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { reactive, ref, watch } from 'vue'

const props = defineProps({
    ngo: { type: Object, required: true },
    channels: { type: Object, required: true },
    help: { type: Object, default: () => ({}) },
})

const page = usePage()
const platformOrder = ['facebook', 'instagram', 'linkedin', 'google_business']
const saving = ref(null)

function friendlyTitle(key) {
    const map = {
        facebook: 'Facebook Page',
        instagram: 'Instagram',
        linkedin: 'LinkedIn company page',
        google_business: 'Google Business Profile',
    }
    return map[key] || key
}

function technicalHint(key) {
    const hints = {
        facebook: 'Needs a Page ID and a token that can post on that Page.',
        instagram: 'Needs the Business account ID and a token with Instagram publishing permission. Each post must include a photo.',
        linkedin: 'Needs your organisation ID and a token that can post on behalf of the company page.',
        google_business: 'For future use.',
    }
    return hints[key] || ''
}

const emptyLocal = () => ({
    account_handle: '',
    access_token: '',
    auto_post_enabled: false,
    page_id: '',
    instagram_account_id: '',
    linkedin_organization_id: '',
})

const local = reactive({
    facebook: emptyLocal(),
    instagram: emptyLocal(),
    linkedin: emptyLocal(),
    google_business: emptyLocal(),
})

function syncFromProps() {
    platformOrder.forEach((key) => {
        const c = props.channels[key] || {}
        local[key].account_handle = c.account_handle ?? ''
        local[key].access_token = ''
        local[key].auto_post_enabled = Boolean(c.auto_post_enabled)
        local[key].page_id = c.page_id ?? ''
        local[key].instagram_account_id = c.instagram_account_id ?? ''
        local[key].linkedin_organization_id = c.linkedin_organization_id ?? ''
    })
}

watch(() => props.channels, syncFromProps, { deep: true, immediate: true })

function helpLink(key) {
    const map = { facebook: props.help?.meta, instagram: props.help?.instagram, linkedin: props.help?.linkedin }
    return map[key] || null
}

function payloadFor(key) {
    const L = local[key]
    const data = {
        account_handle: L.account_handle || null,
        auto_post_enabled: L.auto_post_enabled,
        page_id: L.page_id || null,
        instagram_account_id: L.instagram_account_id || null,
        linkedin_organization_id: L.linkedin_organization_id || null,
    }
    if (L.access_token?.trim()) {
        data.access_token = L.access_token.trim()
    }
    return data
}

function savePlatform(key) {
    saving.value = key
    router.put(`/ngo/social-connect/${key}`, payloadFor(key), {
        preserveScroll: true,
        onFinish: () => { saving.value = null },
        onSuccess: () => {
            local[key].access_token = ''
        },
    })
}

function disconnect(key) {
    if (!window.confirm(`Remove ${friendlyTitle(key)} from FEVOURD-K? You can add it again later.`)) {
        return
    }
    router.delete(`/ngo/social-connect/${key}`, { preserveScroll: true })
}

function firstErrorFor(key) {
    const err = page.props.errors || {}
    const keys = [
        'account_handle',
        'access_token',
        'auto_post_enabled',
        'page_id',
        'instagram_account_id',
        'linkedin_organization_id',
    ]
    for (const k of keys) {
        if (err[k]) {
            return Array.isArray(err[k]) ? err[k][0] : err[k]
        }
    }
    return null
}
</script>
