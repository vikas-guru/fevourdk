<template>
    <AppLayout title="NGO Digitalization - FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="digitalization">
            <div class="max-w-5xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 sm:p-8">
                    <h1 class="text-2xl font-bold text-slate-900">Digitalization Control Center</h1>
                    <p class="text-sm text-slate-600 mt-1">
                        Manage free website, custom domain mapping, Tawk chat, social links, and Google Business autopost settings.
                    </p>

                    <form class="mt-6 space-y-6" @submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Generated Website URL</label>
                                <input :value="ngo.website_url || ''" disabled class="w-full rounded-xl border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Custom Domain</label>
                                <input v-model="form.custom_domain" placeholder="www.yourngo.org" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-slate-500 mt-1">Status: {{ ngo.custom_domain_status || 'pending' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Theme Color</label>
                                <input v-model="form.theme_color" type="color" class="h-10 w-full rounded-xl border border-slate-300 px-2 py-1">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">NGO Logo</label>
                                <input type="file" accept="image/*" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm" @change="onLogoChange">
                            </div>
                        </div>

                        <details class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 sm:p-5" open>
                            <summary class="cursor-pointer text-base font-bold text-slate-900">Microsite homepage (Themes layout)</summary>
                            <p class="mt-2 text-xs text-slate-600">
                                Public site and preview use <code class="rounded bg-white px-1">/assets/Themes/</code> styles. Your NGO profile (description, focus areas, founders) fills gaps automatically. Override copy below.
                            </p>
                            <div class="mt-4 space-y-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hero (slide 1)</p>
                                <div class="grid gap-3 md:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Tagline</label>
                                        <input v-model="form.microsite.hero_subtitle" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Intro (overrides short description on hero)</label>
                                        <textarea v-model="form.microsite.hero_description" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Leave empty to use your NGO description"></textarea>
                                    </div>
                                </div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Mission / vision / impact slides</p>
                                <div class="grid gap-3 md:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Mission headline</label>
                                        <input v-model="form.microsite.mission_subtitle" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Mission text</label>
                                        <textarea v-model="form.microsite.mission_description" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Vision headline</label>
                                        <input v-model="form.microsite.vision_subtitle" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Vision text</label>
                                        <textarea v-model="form.microsite.vision_description" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Impact slide title</label>
                                        <input v-model="form.microsite.impact_title" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Impact slide subtitle</label>
                                        <input v-model="form.microsite.impact_subtitle" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Impact text</label>
                                        <textarea v-model="form.microsite.impact_description" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                                    </div>
                                </div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Quick stats bar (4 tiles)</p>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div v-for="i in [1, 2, 3, 4]" :key="'stat'+i" class="rounded-lg border border-slate-200 bg-white p-3">
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Stat {{ i }} title</label>
                                        <input v-model="form.microsite['stat_'+i+'_h']" class="mb-2 w-full rounded border border-slate-300 px-2 py-1 text-sm">
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Stat {{ i }} subtitle</label>
                                        <input v-model="form.microsite['stat_'+i+'_p']" class="w-full rounded border border-slate-300 px-2 py-1 text-sm">
                                    </div>
                                </div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">About block</p>
                                <div class="grid gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Extra paragraph (after main description)</label>
                                        <textarea v-model="form.microsite.about_extra" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Vision quote (sidebar)</label>
                                        <input v-model="form.microsite.about_vision_quote" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Donate section</p>
                                <div class="grid gap-3 md:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Title</label>
                                        <input v-model="form.microsite.donate_title" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Subtitle</label>
                                        <input v-model="form.microsite.donate_subtitle" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Impact blurb</label>
                                        <textarea v-model="form.microsite.donate_impact_blurb" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Contact section intro</label>
                                    <input v-model="form.microsite.contact_intro" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Programme cards (activities)</p>
                                <p class="text-xs text-slate-500">Icon: Font Awesome name without “fa-” (e.g. heart, seedling, graduation-cap). Leave a row empty to skip.</p>
                                <div class="space-y-3">
                                    <div v-for="(p, idx) in form.microsite.programs" :key="idx" class="rounded-lg border border-slate-200 bg-white p-3">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-semibold text-slate-700">Programme {{ idx + 1 }}</span>
                                            <button v-if="form.microsite.programs.length > 1" type="button" class="text-xs text-red-600 hover:underline" @click="removeProgram(idx)">Remove</button>
                                        </div>
                                        <input v-model="p.title" placeholder="Title" class="mt-2 w-full rounded border border-slate-300 px-2 py-1 text-sm">
                                        <textarea v-model="p.body" placeholder="Description" rows="2" class="mt-2 w-full rounded border border-slate-300 px-2 py-1 text-sm"></textarea>
                                        <input v-model="p.icon" placeholder="Icon (e.g. heart)" class="mt-2 w-full rounded border border-slate-300 px-2 py-1 text-sm">
                                    </div>
                                    <button v-if="form.microsite.programs.length < 12" type="button" class="text-sm font-semibold text-blue-600 hover:text-blue-700" @click="addProgram">+ Add programme</button>
                                </div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Story cards (carousel, up to 6)</p>
                                <p class="text-xs text-slate-500">Image: path under public (e.g. assets/Themes/assets/images/blog/climate-action.jpg) or full URL.</p>
                                <div class="space-y-3">
                                    <div v-for="(s, idx) in form.microsite.stories" :key="'st'+idx" class="rounded-lg border border-slate-200 bg-white p-3">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-semibold text-slate-700">Story {{ idx + 1 }}</span>
                                            <button type="button" class="text-xs text-red-600 hover:underline" @click="removeStory(idx)">Remove</button>
                                        </div>
                                        <input v-model="s.title" placeholder="Title" class="mt-2 w-full rounded border border-slate-300 px-2 py-1 text-sm">
                                        <textarea v-model="s.body" placeholder="Body" rows="2" class="mt-2 w-full rounded border border-slate-300 px-2 py-1 text-sm"></textarea>
                                        <div class="mt-2 grid gap-2 sm:grid-cols-3">
                                            <input v-model="s.category" placeholder="Category" class="rounded border border-slate-300 px-2 py-1 text-sm">
                                            <input v-model="s.date" placeholder="Date label" class="rounded border border-slate-300 px-2 py-1 text-sm">
                                            <input v-model="s.image" placeholder="Image path or URL" class="rounded border border-slate-300 px-2 py-1 text-sm">
                                        </div>
                                    </div>
                                    <button v-if="form.microsite.stories.length < 6" type="button" class="text-sm font-semibold text-blue-600 hover:text-blue-700" @click="addStory">+ Add story</button>
                                </div>
                            </div>
                        </details>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tawk Property ID</label>
                                <input v-model="form.tawk_property_id" placeholder="Enter Tawk property id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tawk Widget ID</label>
                                <input v-model="form.tawk_widget_id" placeholder="Enter Tawk widget id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Facebook Page URL</label>
                                <input v-model="form.facebook_url" placeholder="https://facebook.com/yourngo" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Instagram Profile URL</label>
                                <input v-model="form.instagram_url" placeholder="https://instagram.com/yourngo" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Google Business Location ID</label>
                                <input v-model="form.google_business_location_id" placeholder="Location id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="flex items-end">
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                    <input v-model="form.google_business_auto_post" type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    Enable auto-post to Google Business
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" :disabled="form.processing" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60">
                                {{ form.processing ? 'Saving...' : 'Save Digitalization Settings' }}
                            </button>
                            <a :href="previewUrl" target="_blank" class="ml-3 rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Preview Website
                            </a>
                        </div>
                    </form>
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
    previewUrl: String,
})

function defaultMicrosite() {
    const ms = props.ngo.digitalization_settings?.microsite ?? {}
    const programs = Array.isArray(ms.programs) && ms.programs.length
        ? ms.programs.map((p) => ({
            title: p.title ?? '',
            body: p.body ?? '',
            icon: (p.icon ?? 'star').replace(/^fa-/, ''),
        }))
        : [
            { title: '', body: '', icon: 'graduation-cap' },
            { title: '', body: '', icon: 'hands-helping' },
            { title: '', body: '', icon: 'seedling' },
        ]
    const stories = Array.isArray(ms.stories)
        ? ms.stories.map((s) => ({
            title: s.title ?? '',
            body: s.body ?? '',
            category: s.category ?? '',
            date: s.date ?? '',
            image: s.image ?? '',
        }))
        : []

    return {
        hero_subtitle: ms.hero_subtitle ?? '',
        hero_description: ms.hero_description ?? '',
        mission_subtitle: ms.mission_subtitle ?? '',
        mission_description: ms.mission_description ?? '',
        vision_subtitle: ms.vision_subtitle ?? '',
        vision_description: ms.vision_description ?? '',
        impact_title: ms.impact_title ?? '',
        impact_subtitle: ms.impact_subtitle ?? '',
        impact_description: ms.impact_description ?? '',
        about_extra: ms.about_extra ?? '',
        about_vision_quote: ms.about_vision_quote ?? '',
        contact_intro: ms.contact_intro ?? '',
        donate_title: ms.donate_title ?? '',
        donate_subtitle: ms.donate_subtitle ?? '',
        donate_impact_blurb: ms.donate_impact_blurb ?? '',
        stat_1_h: ms.stat_1_h ?? '',
        stat_1_p: ms.stat_1_p ?? '',
        stat_2_h: ms.stat_2_h ?? '',
        stat_2_p: ms.stat_2_p ?? '',
        stat_3_h: ms.stat_3_h ?? '',
        stat_3_p: ms.stat_3_p ?? '',
        stat_4_h: ms.stat_4_h ?? '',
        stat_4_p: ms.stat_4_p ?? '',
        programs,
        stories,
    }
}

const form = useForm({
    website_url: props.ngo.website_url || '',
    custom_domain: props.ngo.custom_domain || '',
    theme_color: props.ngo.theme_color || '#2563eb',
    tawk_property_id: props.ngo.tawk_property_id || '',
    tawk_widget_id: props.ngo.tawk_widget_id || '',
    facebook_url: props.ngo.facebook_url || '',
    instagram_url: props.ngo.instagram_url || '',
    google_business_location_id: props.ngo.google_business_location_id || '',
    google_business_auto_post: !!props.ngo.google_business_auto_post,
    microsite: defaultMicrosite(),
    logo: null,
})

const addProgram = () => {
    if (form.microsite.programs.length >= 12) {
        return
    }
    form.microsite.programs.push({ title: '', body: '', icon: 'star' })
}

const removeProgram = (idx) => {
    form.microsite.programs.splice(idx, 1)
    if (form.microsite.programs.length === 0) {
        form.microsite.programs.push({ title: '', body: '', icon: 'star' })
    }
}

const addStory = () => {
    if (form.microsite.stories.length >= 6) {
        return
    }
    form.microsite.stories.push({ title: '', body: '', category: '', date: '', image: '' })
}

const removeStory = (idx) => {
    form.microsite.stories.splice(idx, 1)
}

const save = () => {
    form.transform((data) => {
        const { microsite, ...rest } = data
        const cleanedPrograms = (microsite.programs || [])
            .filter((p) => (p.title || '').trim() !== '')
            .map((p) => ({
                title: (p.title || '').trim(),
                body: (p.body || '').trim(),
                icon: (p.icon || 'star').replace(/^fa-/, ''),
            }))
        const cleanedStories = (microsite.stories || [])
            .filter((s) => (s.title || '').trim() !== '')
            .map((s) => ({
                title: (s.title || '').trim(),
                body: (s.body || '').trim(),
                category: (s.category || '').trim(),
                date: (s.date || '').trim(),
                image: (s.image || '').trim(),
            }))
        const payload = {
            ...microsite,
            programs: cleanedPrograms,
            stories: cleanedStories,
        }
        return {
            ...rest,
            microsite_json: JSON.stringify(payload),
            _method: 'put',
        }
    }).post('/ngo/digitalization', {
        forceFormData: true,
    })
}

const onLogoChange = (event) => {
    form.logo = event.target.files?.[0] || null
}
</script>
