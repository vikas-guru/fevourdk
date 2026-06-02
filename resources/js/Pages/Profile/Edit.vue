<template>
    <AppShell title="My profile — FEVOURD-K">
        <div class="min-h-screen bg-slate-50 py-8 sm:py-12">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="fkbrand-hero relative overflow-hidden rounded-2xl p-6 text-white shadow-lg">
                    <div class="fkbrand-hero__grain" aria-hidden="true"></div>
                    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="inline-flex items-center gap-2 text-[0.7rem] font-semibold uppercase tracking-[0.14em] text-[#f7c948]">
                                <span class="h-2 w-2 rounded-full bg-[#f2b40c] shadow-[0_0_0_4px_rgba(242,180,12,0.2)]"></span>
                                Profile Center
                            </p>
                            <h1 class="fkbrand-display mt-2 text-2xl font-semibold sm:text-3xl">Your Impact Identity</h1>
                            <p class="text-blue-100/80 mt-1 text-sm">Keep your profile updated to unlock smarter recommendations.</p>
                        </div>
                        <div v-if="loyaltyMeta" class="rounded-xl bg-white/10 px-4 py-3 border border-[#f2b40c]/40 min-w-[220px]">
                            <p class="text-[#f7c948] text-xs font-semibold uppercase tracking-wide">Social Cause Level</p>
                            <p class="text-lg font-bold">{{ loyaltyMeta.level }}</p>
                            <p class="text-xs text-blue-100/80">{{ loyaltyMeta.social_cause_points }} points · Rank #{{ loyaltyMeta.leaderboard_rank }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="space-y-4">
                        <div class="bg-white rounded-2xl border border-slate-200 p-5">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Avatar</p>
                            <div class="avatar-3d w-24 h-24 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4" :class="selectedAvatar">
                                {{ form.name?.charAt(0)?.toUpperCase() || 'F' }}
                            </div>
                            <div class="grid grid-cols-4 gap-2">
                                <button
                                    v-for="avatar in avatarThemes"
                                    :key="avatar.className"
                                    type="button"
                                    @click="selectedAvatar = avatar.className"
                                    class="h-8 rounded-full border transition"
                                    :class="[avatar.className, selectedAvatar === avatar.className ? 'ring-2 ring-offset-2 ring-[#f2b40c] border-[#f2b40c]' : 'border-transparent']"
                                    :title="avatar.label"
                                />
                            </div>
                        </div>

                        <div v-if="loyaltyMeta" class="bg-white rounded-2xl border border-slate-200 p-5">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Social Cause Progress</p>
                            <div class="flex items-end justify-between mb-2">
                                <p class="text-xl font-bold text-[#0d1f5c]">{{ loyaltyMeta.social_cause_points }} pts</p>
                                <p class="text-xs text-slate-500">Next: {{ loyaltyMeta.next_milestone }} pts</p>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-2 bg-gradient-to-r from-[#f2b40c] to-[#f7c948] rounded-full" :style="{ width: loyaltyPercent + '%' }"></div>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">{{ loyaltyMeta.next_milestone_gap }} points to next level</p>
                            <div class="mt-3 grid grid-cols-3 gap-2 text-[11px]">
                                <div class="rounded-lg bg-slate-50 border border-slate-200 p-2 text-center">
                                    <p class="font-semibold text-slate-800">{{ loyaltyMeta.reactions_count }}</p>
                                    <p class="text-slate-500">Reacts</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 border border-slate-200 p-2 text-center">
                                    <p class="font-semibold text-slate-800">{{ loyaltyMeta.comments_count }}</p>
                                    <p class="text-slate-500">Comments</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 border border-slate-200 p-2 text-center">
                                    <p class="font-semibold text-slate-800">{{ loyaltyMeta.shares_count }}</p>
                                    <p class="text-slate-500">Shares</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2 bg-white shadow-sm rounded-2xl border border-slate-200">
                        <div class="px-6 py-4 border-b border-slate-200">
                            <h2 class="text-xl font-semibold text-slate-900">Edit Profile</h2>
                        </div>

                        <form @submit.prevent="updateProfile" class="p-6 space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Name</label>
                                    <input v-model="form.name" type="text" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                    <input v-model="form.email" type="email" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email }}</div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                                    <input v-model="form.phone" type="tel" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone }}</div>
                                </div>
                            </div>

                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                                <p class="text-sm font-semibold text-slate-700 mb-1">Profile Completion</p>
                                <p class="text-xs text-slate-500 mb-3">Higher completion improves matching, trust, and discoverability.</p>
                                <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-2 bg-gradient-to-r from-[#f2b40c] to-[#f7c948] rounded-full" :style="{ width: (loyaltyMeta?.completion_percent || 0) + '%' }"></div>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">{{ loyaltyMeta?.completion_percent || 0 }}% completed</p>
                            </div>

                            <div class="flex justify-end gap-3">
                                <Link href="/dashboard" class="px-4 py-2.5 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50">
                                    Cancel
                                </Link>
                                <button type="submit" :disabled="processing" class="px-5 py-2.5 bg-[#f2b40c] text-[#2a1c00] font-bold rounded-xl shadow-[0_14px_30px_-14px_rgba(242,180,12,0.85)] transition hover:-translate-y-0.5 disabled:translate-y-0 disabled:opacity-60 disabled:shadow-none">
                                    {{ processing ? 'Saving...' : 'Save Changes' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-5">
                    <p class="text-lg font-semibold text-slate-900 mb-3">Social Cause Leaderboard</p>
                    <div class="space-y-2">
                        <div
                            v-for="(member, index) in leaderboard"
                            :key="member.id"
                            class="flex items-center justify-between rounded-xl border px-3 py-2 cursor-pointer transition"
                            :class="member.id === user.id ? 'border-[#f2b40c]/60 bg-[#f2b40c]/10' : 'border-slate-200 bg-white hover:border-slate-300'"
                            @click="openMember(member)"
                        >
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 text-xs font-bold flex items-center justify-center">
                                    #{{ index + 1 }}
                                </span>
                                <div
                                    class="w-8 h-8 rounded-full border text-sm flex items-center justify-center"
                                    :class="avatarGenderClass(member.gender)"
                                >
                                    {{ avatarEmoji(member.gender) }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-slate-800">{{ member.name }}</p>
                                    <span v-if="member.id === user.id" class="text-[10px] px-2 py-0.5 rounded-full bg-[#f2b40c]/20 text-[#8a6d12] border border-[#f2b40c]/40 font-semibold">You</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-[#0d1f5c]">{{ member.social_cause_points || 0 }} pts</p>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="selectedMember" class="bg-white rounded-2xl border border-slate-200 p-5 hidden sm:block">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="avatar-3d w-16 h-16 rounded-full flex items-center justify-center text-2xl border"
                                :class="avatarGenderClass(selectedMember.gender)"
                            >
                                {{ avatarEmoji(selectedMember.gender) }}
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-slate-900">{{ selectedMember.name }}</p>
                                <p class="text-sm text-slate-500">{{ selectedMember.social_cause_points }} social cause points</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="text-xs text-slate-500 hover:text-slate-700"
                            @click="selectedMember = null"
                        >
                            Close
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Email</p>
                            <p class="text-sm font-medium text-slate-800">{{ selectedMember.email || 'Not shared' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Phone (masked)</p>
                            <p class="text-sm font-medium text-slate-800">{{ maskPhone(selectedMember.phone) }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Gender Avatar</p>
                            <p class="text-sm font-medium text-slate-800">{{ selectedMember.gender || 'Not set' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Location</p>
                            <p class="text-sm font-medium text-slate-800">{{ formatLocation(selectedMember) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="selectedMember"
                class="sm:hidden fixed inset-0 z-[70] bg-black/50 backdrop-blur-sm flex items-end"
                @click.self="selectedMember = null"
            >
                <div class="w-full rounded-t-3xl bg-white p-5 border-t border-slate-200 shadow-2xl">
                    <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-4"></div>
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="avatar-3d w-14 h-14 rounded-full flex items-center justify-center text-2xl border"
                                :class="avatarGenderClass(selectedMember.gender)"
                            >
                                {{ avatarEmoji(selectedMember.gender) }}
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-slate-900">{{ selectedMember.name }}</p>
                                <p class="text-xs text-slate-500">{{ selectedMember.social_cause_points }} social cause points</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="text-xs text-slate-500 hover:text-slate-700"
                            @click="selectedMember = null"
                        >
                            Close
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-2 mt-4">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Email</p>
                            <p class="text-sm font-medium text-slate-800">{{ selectedMember.email || 'Not shared' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Phone (masked)</p>
                            <p class="text-sm font-medium text-slate-800">{{ maskPhone(selectedMember.phone) }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Location</p>
                            <p class="text-sm font-medium text-slate-800">{{ formatLocation(selectedMember) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppShell from '@/Layouts/AppShell.vue'

const props = defineProps({
    user: Object,
    errors: Object,
    leaderboard: {
        type: Array,
        default: () => [],
    },
})
const errors = computed(() => props.errors || {})

const page = usePage()
const loyaltyMeta = computed(() => page.props.auth?.profile ?? null)

const form = ref({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone || '',
})

const processing = ref(false)
const avatarThemes = [
    { label: 'Ocean', className: 'bg-gradient-to-br from-blue-600 to-cyan-500' },
    { label: 'Royal', className: 'bg-gradient-to-br from-indigo-600 to-violet-500' },
    { label: 'Sunset', className: 'bg-gradient-to-br from-orange-500 to-red-500' },
    { label: 'Forest', className: 'bg-gradient-to-br from-emerald-600 to-green-500' },
]
const selectedAvatar = ref(avatarThemes[0].className)
const selectedMember = ref(null)
const loyaltyPercent = computed(() => {
    if (!loyaltyMeta.value) {
        return 0
    }
    const max = loyaltyMeta.value.next_milestone || 1
    return Math.min(100, Math.round((loyaltyMeta.value.social_cause_points / max) * 100))
})

const updateProfile = () => {
    processing.value = true
    router.put('/profile', form.value, {
        onSuccess: () => {
            processing.value = false
        },
        onError: () => {
            processing.value = false
        }
    })
}

const maskPhone = (phone) => {
    if (!phone) {
        return 'Not shared'
    }
    const digits = String(phone).replace(/\D/g, '')
    if (digits.length < 6) {
        return 'Not shared'
    }
    return `${digits.slice(0, 2)}******${digits.slice(-2)}`
}

const avatarEmoji = (gender) => {
    const g = (gender || '').toLowerCase()
    if (g.startsWith('m')) return '👨'
    if (g.startsWith('f')) return '👩'
    return '🙂'
}

const avatarGenderClass = (gender) => {
    const g = (gender || '').toLowerCase()
    if (g.startsWith('m')) return 'bg-gradient-to-br from-blue-100 to-indigo-200 border-blue-300'
    if (g.startsWith('f')) return 'bg-gradient-to-br from-pink-100 to-rose-200 border-pink-300'
    return 'bg-gradient-to-br from-slate-100 to-slate-200 border-slate-300'
}

const formatLocation = (member) => {
    const parts = [member.city_name, member.district_name, member.state_name].filter(Boolean)
    return parts.length ? parts.join(', ') : 'Not available'
}

const openMember = (member) => {
    selectedMember.value = member
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap');

.fkbrand-display { font-family: 'Fraunces','Playfair Display',Georgia,serif; font-optical-sizing: auto; }

/* Branded ink hero, matching the NGO setup wizard / splash */
.fkbrand-hero { background: radial-gradient(120% 120% at 85% -10%, #1b3aa0 0%, #11286e 42%, #081640 100%); }
.fkbrand-hero__grain {
    position: absolute; inset: 0; z-index: 0; opacity: .45; mix-blend-mode: overlay; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
}

.avatar-3d {
    box-shadow:
        inset -8px -10px 18px rgba(255, 255, 255, 0.22),
        inset 8px 10px 18px rgba(15, 23, 42, 0.22),
        0 16px 28px rgba(30, 64, 175, 0.28);
    transform: perspective(240px) rotateX(6deg);
}
</style>
