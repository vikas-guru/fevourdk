<template>
    <AppLayout title="Post an update — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="post-update">
            <div class="mx-auto max-w-2xl space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h1 class="text-2xl font-bold text-slate-900">Post an update</h1>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Add photos or short videos (up to 12 files). Your story goes to the <strong>live community feed</strong>.
                        We automatically add search-friendly title, description, and keywords for the public link.
                    </p>
                </div>

                <div
                    v-if="page.props.flash?.success"
                    class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900"
                >
                    {{ page.props.flash.success }}
                </div>
                <div
                    v-if="page.props.flash?.error"
                    class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900"
                >
                    {{ page.props.flash.error }}
                </div>

                <form class="space-y-5 rounded-2xl border border-blue-100 bg-white p-6 shadow-sm sm:p-8" @submit.prevent="submit">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Headline</label>
                        <input
                            v-model="form.title"
                            type="text"
                            maxlength="200"
                            required
                            placeholder="e.g. School kits distributed in Raichur"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <p v-if="form.errors.title" class="mt-1 text-xs text-red-600">{{ form.errors.title }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Your message</label>
                        <textarea
                            v-model="form.body"
                            required
                            rows="6"
                            maxlength="8000"
                            placeholder="Share what you did, who it helped, and how people can support you…"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <p v-if="form.errors.body" class="mt-1 text-xs text-red-600">{{ form.errors.body }}</p>
                    </div>

                    <div>
                        <p class="mb-1 text-sm font-medium text-slate-700">Photos and videos</p>
                        <p class="mb-2 text-xs text-slate-500">JPG, PNG, WebP up to 5 MB each · MP4, WebM, MOV up to 50 MB each · max 12 items</p>
                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/80 px-4 py-8 transition hover:border-blue-300 hover:bg-blue-50/30">
                            <span class="text-sm font-semibold text-blue-700">Tap to add files</span>
                            <span class="mt-1 text-xs text-slate-500">You can select many at once</span>
                            <input
                                type="file"
                                multiple
                                accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime"
                                class="sr-only"
                                @change="onPick"
                            >
                        </label>
                        <p v-if="form.errors.media_files" class="mt-2 text-xs text-red-600">{{ form.errors.media_files }}</p>

                        <div v-if="slots.length" class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <div
                                v-for="(s, i) in slots"
                                :key="s.id"
                                class="group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-100 shadow-sm"
                            >
                                <img
                                    v-if="s.kind === 'image'"
                                    :src="s.url"
                                    alt=""
                                    class="aspect-square w-full object-cover"
                                >
                                <video
                                    v-else
                                    :src="s.url"
                                    class="aspect-square w-full object-cover"
                                    muted
                                    playsinline
                                />
                                <button
                                    type="button"
                                    class="absolute right-2 top-2 rounded-full bg-slate-900/75 px-2 py-0.5 text-xs font-bold text-white opacity-0 transition group-hover:opacity-100"
                                    @click="removeSlot(i)"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-700 disabled:opacity-60 sm:w-auto sm:px-8"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Publishing…' : 'Publish update' }}
                    </button>
                </form>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 text-sm text-slate-600">
                    <p class="font-semibold text-slate-800">See how posts perform</p>
                    <p class="mt-1 leading-relaxed">
                        Open <Link href="/ngo/feed-studio" class="font-semibold text-indigo-700 hover:underline">Feed studio</Link>
                        for views, reactions, comments, shares, and SEO text for each update.
                    </p>
                    <p class="mt-3">
                        <Link
                            href="/ngo/social-connect"
                            class="font-semibold text-blue-700 hover:underline"
                        >Auto-share to Facebook / Instagram</Link>
                        <span class="mx-2 text-slate-300">|</span>
                        <Link href="/feeds" class="font-semibold text-slate-700 hover:underline">Live feed</Link>
                    </p>
                </div>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({
    ngo: { type: Object, required: true },
})

const page = usePage()

const form = useForm({
    title: '',
    body: '',
    from_ngo_workspace: true,
    media_files: [],
})

const slots = ref([])
let slotSeq = 0

function onPick(e) {
    const files = Array.from(e.target.files || [])
    e.target.value = ''
    const allowedVideo = ['video/mp4', 'video/webm', 'video/quicktime']
    for (const f of files) {
        if (slots.value.length >= 12) {
            break
        }
        let kind = null
        if (/^image\/(jpeg|jpg|png|webp)$/i.test(f.type)) {
            kind = 'image'
        } else if (allowedVideo.includes(f.type)) {
            kind = 'video'
        }
        if (!kind) {
            continue
        }
        slots.value.push({
            id: ++slotSeq,
            file: f,
            url: URL.createObjectURL(f),
            kind,
        })
    }
}

function removeSlot(i) {
    const s = slots.value[i]
    if (s?.url) {
        URL.revokeObjectURL(s.url)
    }
    slots.value.splice(i, 1)
}

function clearSlots() {
    slots.value.forEach((s) => URL.revokeObjectURL(s.url))
    slots.value = []
}

function submit() {
    form.media_files = slots.value.map((s) => s.file)
    form.post('/feeds', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            form.clearErrors()
            clearSlots()
        },
    })
}
</script>
