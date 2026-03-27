<template>
    <AppLayout title="NGO Digitalization - FEVOURD-K">
        <div class="min-h-screen bg-slate-50 py-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6">
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
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    ngo: Object,
    previewUrl: String,
})

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
    digitalization_settings: props.ngo.digitalization_settings || {},
    logo: null,
})

const save = () => {
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post('/ngo/digitalization', {
        forceFormData: true,
    })
}

const onLogoChange = (event) => {
    form.logo = event.target.files?.[0] || null
}
</script>
