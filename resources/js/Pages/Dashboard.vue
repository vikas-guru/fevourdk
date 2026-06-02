<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user

defineProps({
  followedNgos: { type: Array, default: () => [] },
  feed: { type: Array, default: () => [] },
})

const initials = (name) => (name || '?').split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()

const timeAgo = (iso) => {
  if (!iso) return ''
  const d = (Date.now() - new Date(iso).getTime()) / 86400000
  if (d < 1) return 'today'
  if (d < 2) return 'yesterday'
  return `${Math.floor(d)} days ago`
}
</script>

<template>
  <div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-20">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 flex justify-between h-16 items-center">
        <Link href="/" class="text-xl font-extrabold text-slate-900">FEVOURD-K</Link>
        <div class="flex items-center gap-4">
          <span class="text-sm text-slate-500 hidden sm:inline">Hi, {{ user?.name?.split(' ')[0] }}</span>
          <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-white grid place-items-center text-sm font-bold">{{ initials(user?.name) }}</div>
          <Link href="/logout" method="post" as="button" class="text-sm text-slate-500 hover:text-rose-600">Logout</Link>
        </div>
      </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
      <!-- Welcome -->
      <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Welcome back, {{ user?.name?.split(' ')[0] }} 👋</h1>
        <p class="mt-1 text-slate-500">Here's the latest from the organisations you follow.</p>
      </div>

      <!-- NGOs you follow -->
      <section class="mb-8">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-bold text-slate-900">NGOs you follow</h2>
          <Link href="/ngos" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Discover more →</Link>
        </div>

        <div v-if="followedNgos.length" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
          <Link v-for="n in followedNgos" :key="n.id" :href="`/${n.slug}`"
                class="group bg-white rounded-2xl border border-slate-200 p-4 hover:shadow-md hover:-translate-y-0.5 transition-all">
            <div class="w-12 h-12 rounded-xl grid place-items-center mb-3 overflow-hidden"
                 :style="{ backgroundColor: (n.theme_color || '#2e7d32') + '1a' }">
              <img v-if="n.logo" :src="n.logo" :alt="n.name" class="w-10 h-10 object-contain" />
              <span v-else class="text-base font-bold" :style="{ color: n.theme_color || '#2e7d32' }">{{ initials(n.name) }}</span>
            </div>
            <p class="font-semibold text-slate-800 text-sm leading-snug line-clamp-2">{{ n.name }}</p>
            <span v-if="n.is_supporting" class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-rose-600">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18l-1.45-1.32C3.4 12.36 0 9.28 0 5.5 0 2.42 2.42 0 5.5 0 7.24 0 8.91.81 10 2.09 11.09.81 12.76 0 14.5 0 17.58 0 20 2.42 20 5.5c0 3.78-3.4 6.86-8.55 11.18L10 18z"/></svg>
              Supporting
            </span>
          </Link>
        </div>

        <div v-else class="bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center">
          <p class="text-slate-600 mb-3">You're not following any NGOs yet.</p>
          <Link href="/ngos" class="inline-block bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-emerald-700">Browse NGOs to follow</Link>
        </div>
      </section>

      <!-- Activity feed -->
      <section>
        <h2 class="text-lg font-bold text-slate-900 mb-3">Latest activity</h2>

        <div v-if="feed.length" class="space-y-4">
          <article v-for="post in feed" :key="post.id" class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-4 flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-slate-100 grid place-items-center overflow-hidden shrink-0">
                <img v-if="post.ngo?.logo" :src="post.ngo.logo" :alt="post.ngo?.name" class="w-9 h-9 object-contain" />
                <span v-else class="text-xs font-bold text-slate-500">{{ initials(post.ngo?.name) }}</span>
              </div>
              <div class="min-w-0">
                <Link :href="`/${post.ngo?.slug}`" class="font-semibold text-slate-800 text-sm hover:underline">{{ post.ngo?.name }}</Link>
                <p class="text-xs text-slate-400">{{ timeAgo(post.created_at) }}</p>
              </div>
            </div>
            <img v-if="post.image_url" :src="post.image_url" :alt="post.title" class="w-full max-h-80 object-cover" />
            <div class="p-4">
              <h3 class="font-bold text-slate-900 mb-1">{{ post.title }}</h3>
              <p class="text-slate-600 text-sm leading-relaxed">{{ post.body }}</p>
              <p class="text-xs text-slate-400 mt-3">{{ (post.views_count || 0).toLocaleString() }} views</p>
            </div>
          </article>
        </div>

        <div v-else class="bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center text-slate-500">
          Follow an NGO to see their updates here.
        </div>
      </section>
    </div>
  </div>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
