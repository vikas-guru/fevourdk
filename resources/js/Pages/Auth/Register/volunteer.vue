<template>
    <AppLayout title="Volunteer Registration - FEVOURD-K" :hide-chrome-mobile="true">
        <div class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 py-6 sm:py-10">
            <div class="max-w-2xl mx-auto px-4 sm:px-6">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="mx-auto mb-3 w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-500 grid place-items-center shadow-lg shadow-orange-500/30">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-[0.15em] text-orange-600">Give your time</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1.5">Become a volunteer</h1>
                    <p class="text-gray-500 mt-2 text-sm sm:text-base max-w-md mx-auto">Lend your skills to causes near you across Karnataka — flexible hours, real impact.</p>
                </div>

                <form @submit.prevent="submitForm" class="reg-form bg-white rounded-3xl shadow-xl shadow-slate-300/30 ring-1 ring-slate-100 p-5 sm:p-8 space-y-5">
                    <div>
                        <label class="reg-label">Full Name *</label>
                        <input type="text" v-model="form.name" class="w-full" placeholder="Your full name" required>
                    </div>

                    <div>
                        <label class="reg-label">Email *</label>
                        <input type="email" v-model="form.email" class="w-full" placeholder="you@example.com" required>
                    </div>

                    <div>
                        <label class="reg-label">Phone *</label>
                        <div class="flex gap-2">
                            <div class="flex items-center gap-1.5 px-3 border border-gray-200 rounded-xl bg-slate-50 text-gray-700 shrink-0 select-none cursor-default">
                                <span class="text-lg" aria-hidden="true">🇮🇳</span>
                                <span class="font-semibold">+91</span>
                            </div>
                            <input type="tel" inputmode="numeric" v-model="form.phone" class="flex-1 min-w-0" placeholder="9876543210" required>
                        </div>
                    </div>

                    <div>
                        <label class="reg-label">Areas of interest</label>
                        <p class="text-xs text-gray-500 mb-2.5 -mt-1">Tap the causes you care about</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            <label
                                v-for="opt in interestOptions" :key="opt.value"
                                class="flex items-center justify-center px-3 py-3 rounded-xl border cursor-pointer select-none text-center transition-all active:scale-[.98]"
                                :class="form.interests.includes(opt.value) ? 'bg-orange-500 border-orange-500 text-white shadow-md shadow-orange-500/25' : 'bg-white border-gray-200 text-gray-700 hover:border-orange-300'"
                            >
                                <input type="checkbox" :value="opt.value" v-model="form.interests" class="sr-only">
                                <span class="text-sm font-medium">{{ opt.label }}</span>
                            </label>
                        </div>
                    </div>

                    <label class="flex items-start gap-3 p-3.5 rounded-xl bg-orange-50/60 border border-orange-100 cursor-pointer select-none">
                        <input type="checkbox" v-model="form.terms_accepted" class="mt-0.5 w-5 h-5 rounded accent-orange-500 shrink-0" required>
                        <span class="text-sm text-gray-600 leading-snug">I agree to the <span class="font-semibold text-orange-600">Terms</span> &amp; <span class="font-semibold text-orange-600">Privacy Policy</span>.</span>
                    </label>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full px-6 py-3.5 bg-orange-500 text-white rounded-xl font-semibold shadow-lg shadow-orange-500/30 hover:bg-orange-600 active:scale-95 disabled:bg-gray-300 disabled:shadow-none disabled:cursor-not-allowed transition-all"
                    >
                        <span v-if="form.processing">Registering…</span>
                        <span v-else>Register as volunteer</span>
                    </button>
                </form>

                <p class="text-center mt-6">
                    <Link href="/register" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back to role selection</Link>
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const form = useForm({
    name: '',
    email: '',
    phone: '',
    interests: [],
    terms_accepted: false
})

const interestOptions = [
    { value: 'education', label: 'Education' },
    { value: 'healthcare', label: 'Healthcare' },
    { value: 'environment', label: 'Environment' },
]

const submitForm = () => {
    form.post('/register/volunteer', {
        onSuccess: () => {
            router.push('/dashboard')
        }
    })
}
</script>

<style scoped>
.reg-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}

/* App-grade fields with the volunteer (orange) accent */
.reg-form :deep(input[type='text']),
.reg-form :deep(input[type='email']),
.reg-form :deep(input[type='tel']) {
    border: 1px solid #e6eaf1;
    border-radius: 0.85rem;
    background-color: #f8fafc;
    font-size: 16px;            /* prevents iOS zoom-on-focus */
    padding: 0.8rem 1rem;
    color: #0f172a;
    transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
}
.reg-form :deep(input:focus) {
    outline: none;
    background-color: #fff;
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.16);
}
.reg-form :deep(input::placeholder) { color: #9ca3af; }
</style>
