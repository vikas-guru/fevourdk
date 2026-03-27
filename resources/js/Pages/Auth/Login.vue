<template>
    <div class="min-h-screen bg-white relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -left-20 w-72 h-72 bg-blue-100/70 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -right-20 w-72 h-72 bg-indigo-100/70 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 left-1/4 w-80 h-80 bg-purple-100/60 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md max-h-[calc(100vh-1.5rem)] overflow-y-auto rounded-3xl border border-slate-200 bg-white shadow-xl p-6 sm:p-8">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-white border border-blue-100 shadow-sm mb-1">
                        <img :src="logoImage" alt="FEVOURD-K" class="w-14 h-14 object-contain">
                    </div>
                    <div class="mb-2">
                        <dotlottie-player
                            src="/assets/lottie/login%20screen.lottie"
                            background="transparent"
                            speed="1"
                            style="width: 100%; height: 340px"
                            loop
                            autoplay
                        ></dotlottie-player>
                    </div>
                    <h1 class="text-4xl font-bold text-slate-900">Sign In</h1>
                    <p class="text-slate-500 mt-1">Welcome back to FEVOURD-K</p>
                </div>

                <form class="space-y-3.5" @submit.prevent="submit">
                    <div v-if="$page.props.errors.login || $page.props.errors.password" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                        <p class="text-sm text-red-700">
                            {{ $page.props.errors.login || $page.props.errors.password }}
                        </p>
                    </div>

                    <div>
                        <label for="login" class="block text-sm font-medium text-slate-700 mb-2">
                            Email or phone number
                        </label>
                        <input
                            id="login"
                            type="text"
                            v-model="form.login"
                            autocomplete="username"
                            required
                            class="w-full rounded-2xl border px-4 py-3 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                            :class="$page.props.errors.login ? 'border-red-400 focus:ring-red-300' : 'border-white/50'"
                            placeholder="Enter email or phone number"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                            Login PIN
                        </label>
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            autocomplete="current-password"
                            required
                            class="w-full rounded-2xl border px-4 py-3 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                            :class="$page.props.errors.password ? 'border-red-400 focus:ring-red-300' : 'border-white/50'"
                            placeholder="Enter login PIN"
                        >
                    </div>

                    <button
                        type="submit"
                        :disabled="processing"
                        class="mt-2 w-full rounded-2xl py-3.5 px-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold shadow-lg hover:from-blue-700 hover:to-purple-700 disabled:opacity-60 disabled:cursor-not-allowed transition"
                    >
                        {{ processing ? 'Signing in...' : 'Sign In' }}
                    </button>
                </form>

                <div class="mt-6 flex items-center justify-center gap-5 text-sm">
                    <Link
                        href="/register/individual"
                        class="font-semibold text-blue-700 hover:text-blue-800 transition underline underline-offset-4"
                    >
                        Register
                    </Link>
                    <span class="text-slate-300">|</span>
                    <Link
                        href="/forgot-password"
                        class="font-semibold text-slate-600 hover:text-slate-800 transition underline underline-offset-4"
                    >
                        Forgot PIN
                    </Link>
                </div>

                <p class="mt-6 text-center text-[11px] text-slate-500">
                    FEVOURD-K&trade; All Rights Reserved
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/vue3'
import logoImage from '/public/assets/images/fevourd-k/logo.png'

const form = reactive({
    login: '',
    password: '',
})

const processing = ref(false)

const ensureDotLottiePlayer = () => {
    if (customElements.get('dotlottie-player')) {
        return
    }

    const existingScript = document.querySelector('script[data-dotlottie-player="1"]')
    if (existingScript) {
        return
    }

    const script = document.createElement('script')
    script.type = 'module'
    script.src = 'https://cdn.jsdelivr.net/npm/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs'
    script.setAttribute('data-dotlottie-player', '1')
    document.head.appendChild(script)
}

const submit = () => {
    processing.value = true
    Inertia.post('/login', form, {
        onFinish: () => {
            processing.value = false
        },
    })
}

onMounted(() => {
    ensureDotLottiePlayer()
})
</script>

<style scoped></style>
