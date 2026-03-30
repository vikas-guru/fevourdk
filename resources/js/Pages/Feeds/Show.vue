<template>
    <AppLayout>
        <div class="min-h-screen bg-slate-50 py-4 sm:py-8">
            <div class="mx-auto max-w-3xl space-y-4 px-3 sm:px-6">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <Link href="/feeds" class="text-sm font-semibold text-blue-700 hover:underline">Back to live feed</Link>
                    <span class="text-xs text-slate-500">{{ post.views_count }} views</span>
                </div>

                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="px-4 pb-3 pt-4">
                        <div class="mb-2 flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                    {{ (post.ngo?.name || post.author?.name || 'F').charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ post.ngo?.name || post.author?.name }}</p>
                                    <p class="text-xs text-slate-500">{{ formatTime(post.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                        <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">{{ post.title }}</h1>
                        <p class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ post.body }}</p>
                    </div>

                    <div v-if="post.media?.length" class="space-y-0 border-t border-slate-100">
                        <template v-for="(item, idx) in post.media" :key="idx">
                            <img
                                v-if="item.type === 'image'"
                                :src="resolveMedia(item.path)"
                                :alt="`${post.title} ${idx + 1}`"
                                class="max-h-[480px] w-full bg-slate-100 object-contain"
                                loading="lazy"
                                @error="handleImgError"
                            >
                            <video
                                v-else
                                :src="resolveMedia(item.path)"
                                class="max-h-[480px] w-full bg-black"
                                controls
                                playsinline
                                preload="metadata"
                            />
                        </template>
                    </div>
                    <img
                        v-else-if="post.image_url"
                        :src="resolveMedia(post.image_url)"
                        :alt="post.title"
                        class="max-h-[480px] w-full bg-slate-100 object-cover"
                        @error="handleImgError"
                    >

                    <div class="border-t border-slate-100 px-4 py-3">
                        <div class="mb-2 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                            <span>
                                Like {{ post.reactions.totals.like }} · Love {{ post.reactions.totals.love }} · Support {{ post.reactions.totals.support }}
                            </span>
                            <span>{{ post.comments.length }} comments · {{ post.shares_count }} shares</span>
                        </div>
                        <div v-if="canEngage" class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                            <button type="button" class="action-btn" :class="{ 'active-action': post.reactions.my_reaction === 'like' }" @click="react('like')">Like</button>
                            <button type="button" class="action-btn" :class="{ 'active-action': post.reactions.my_reaction === 'love' }" @click="react('love')">Love</button>
                            <button type="button" class="action-btn" :class="{ 'active-action': post.reactions.my_reaction === 'support' }" @click="react('support')">Support</button>
                            <button type="button" class="action-btn" @click="copyLink">Copy link</button>
                        </div>
                        <p v-else class="rounded-lg bg-slate-50 px-3 py-2 text-center text-sm text-slate-600">
                            <Link href="/login" class="font-semibold text-blue-700 hover:underline">Log in</Link>
                            to react or comment.
                        </p>
                    </div>

                    <div v-if="canEngage" class="border-t border-slate-100 px-4 pb-4">
                        <div class="mb-3 max-h-60 space-y-2 overflow-auto">
                            <div v-for="c in post.comments" :key="c.id" class="rounded-xl bg-slate-50 px-3 py-2">
                                <p class="text-xs font-semibold text-slate-700">{{ c.user_name }}</p>
                                <p class="text-sm text-slate-700">{{ c.comment }}</p>
                            </div>
                        </div>
                        <form class="flex gap-2" @submit.prevent="sendComment">
                            <input
                                v-model="commentDraft"
                                type="text"
                                maxlength="500"
                                placeholder="Write a comment..."
                                class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <button type="submit" class="rounded-xl bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700">Post</button>
                        </form>
                    </div>
                </article>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'

const props = defineProps({
    post: { type: Object, required: true },
})

const page = usePage()
const commentDraft = ref('')
const canEngage = computed(() => !!page.props.auth?.user)

function formatTime(iso) {
    return new Date(iso).toLocaleString()
}

function resolveMedia(path) {
    if (!path) {
        return '/assets/images/fevourd-k/logo.png'
    }
    if (path.startsWith('http')) {
        return path
    }
    return path.startsWith('/') ? path : `/${path}`
}

function handleImgError(e) {
    e.target.src = '/assets/images/fevourd-k/logo.png'
}

function react(type) {
    router.post(`/feeds/${props.post.id}/react`, { type }, { preserveScroll: true })
}

function sendComment() {
    const t = commentDraft.value?.trim()
    if (!t) {
        return
    }
    router.post(`/feeds/${props.post.id}/comment`, { comment: t }, {
        preserveScroll: true,
        onSuccess: () => { commentDraft.value = '' },
    })
}

function copyLink() {
    const url = props.post.public_url || `${window.location.origin}/feeds/${props.post.id}`
    navigator.clipboard.writeText(url).catch(() => {})
    router.post(`/feeds/${props.post.id}/share`, { channel: 'link' }, { preserveScroll: true })
}

function recordViewOnce() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    fetch(`/feeds/${props.post.id}/view`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': token || '',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
        body: '{}',
    }).catch(() => {})
}

onMounted(() => {
    recordViewOnce()
})
</script>

<style scoped>
.action-btn {
    @apply rounded-lg border border-slate-200 px-2 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50;
}
.active-action {
    @apply border-blue-600 bg-blue-600 text-white;
}
</style>
