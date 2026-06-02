<template>
    <Teleport to="body">
        <SeoHead />
        <div
            id="fevourd-login-root"
            class="login-page"
            :class="{ 'login-page--layout-wide': isLayoutWide }"
        >
            <div class="login-page__blobs" aria-hidden="true">
                <div class="login-page__blob login-page__blob--1"></div>
                <div class="login-page__blob login-page__blob--2"></div>
                <div class="login-page__blob login-page__blob--3"></div>
            </div>

            <div class="login-page__shell" :class="{ 'login-page__shell--wide': isLayoutWide }">
                <aside v-show="isLayoutWide" class="login-page__brand" :aria-hidden="!isLayoutWide">
                    <div class="login-page__brand-inner">
                        <div class="login-page__brand-row">
                            <div class="login-page__logo-box">
                                <img :src="logoImage" alt="FEVOURD-K" class="login-page__logo-img">
                            </div>
                            <div>
                                <p class="login-page__brand-kicker">FEVOURD-K</p>
                                <p class="login-page__brand-title">Karnataka NGO &amp; donor hub</p>
                            </div>
                        </div>
                        <h2 class="login-page__welcome">Welcome back</h2>
                        <p class="login-page__lead">
                            Sign in to manage campaigns, donations, and your organisation — all in one place.
                        </p>
                        <div class="login-page__lottie-box">
                            <div class="login-page__lottie-desktop-wrap">
                                <dotlottie-player
                                    src="/assets/lottie/login%20screen.lottie"
                                    background="transparent"
                                    speed="1"
                                    style="width: 100%; height: 100%; max-width: 420px"
                                    loop
                                    autoplay
                                ></dotlottie-player>
                            </div>
                        </div>
                    </div>
                </aside>

                <main class="login-page__main">
                    <div class="login-page__card">
                        <div v-show="!isLayoutWide" class="login-page__mobile-hero">
                            <div class="login-page__logo-box login-page__logo-box--center">
                                <img :src="logoImage" alt="FEVOURD-K" class="login-page__logo-img">
                            </div>
                            <div class="login-page__lottie-mobile-outer">
                                <div class="login-page__lottie-mobile-wrap">
                                    <dotlottie-player
                                        src="/assets/lottie/login%20screen.lottie"
                                        background="transparent"
                                        speed="1"
                                        style="width: 100%; height: 100%"
                                        loop
                                        autoplay
                                    ></dotlottie-player>
                                </div>
                            </div>
                            <h1 class="login-page__h1">Sign In</h1>
                            <p class="login-page__sub">Welcome back to FEVOURD-K</p>
                        </div>

                        <div v-show="isLayoutWide" class="login-page__desktop-head">
                            <h1 class="login-page__h1">Sign In</h1>
                            <p class="login-page__sub login-page__sub--desktop">
                                Enter your email or phone and login PIN to continue.
                            </p>
                        </div>

                        <form class="login-page__form" @submit.prevent="submit">
                            <div>
                                <label for="login" class="login-page__label">Email or phone number</label>
                                <input
                                    id="login"
                                    type="text"
                                    v-model="form.login"
                                    autocomplete="username"
                                    required
                                    class="login-page__input"
                                    :class="{ 'login-page__input--invalid': $page.props.errors.login }"
                                    placeholder="Enter email or phone number"
                                >
                            </div>

                            <div>
                                <label for="password" class="login-page__label">Login PIN</label>
                                <input
                                    id="password"
                                    type="password"
                                    v-model="form.password"
                                    autocomplete="current-password"
                                    required
                                    class="login-page__input"
                                    :class="{ 'login-page__input--invalid': $page.props.errors.password }"
                                    placeholder="Enter login PIN"
                                >
                            </div>

                            <button type="submit" :disabled="processing" class="login-page__submit">
                                {{ processing ? 'Signing in...' : 'Sign In' }}
                            </button>
                        </form>

                        <div class="login-page__links">
                            <Link href="/register" class="login-page__link login-page__link--primary">
                                Register
                            </Link>
                            <span class="login-page__sep">|</span>
                            <Link href="/forgot-password" class="login-page__link">
                                Forgot PIN
                            </Link>
                        </div>

                        <p class="login-page__legal">FEVOURD-K&trade; All Rights Reserved</p>
                    </div>
                </main>
            </div>

            <Transition name="login-error-modal-t">
                <div
                    v-if="errorModalOpen"
                    class="login-error-modal"
                    role="alertdialog"
                    aria-modal="true"
                    aria-labelledby="login-error-title"
                    aria-describedby="login-error-desc"
                >
                    <div
                        class="login-error-modal__backdrop"
                        aria-hidden="true"
                        @click="closeErrorModal"
                    ></div>
                    <div class="login-error-modal__panel" @click.stop>
                        <button
                            type="button"
                            class="login-error-modal__icon-close"
                            aria-label="Close"
                            @click="closeErrorModal"
                        >
                            <XMarkIcon class="login-error-modal__icon-close-svg" />
                        </button>
                        <div class="login-error-modal__icon-wrap" aria-hidden="true">
                            <ExclamationTriangleIcon class="login-error-modal__icon" />
                        </div>
                        <h2 id="login-error-title" class="login-error-modal__title">Couldn’t sign you in</h2>
                        <p id="login-error-desc" class="login-error-modal__message">{{ errorModalMessage }}</p>
                        <button
                            ref="errorModalPrimaryRef"
                            type="button"
                            class="login-error-modal__primary"
                            @click="closeErrorModal"
                        >
                            Try again
                        </button>
                    </div>
                </div>
            </Transition>
        </div>
    </Teleport>
</template>

<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import { ExclamationTriangleIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { Link, router, usePage } from '@inertiajs/vue3'
import { nextTick, onMounted, onUnmounted, reactive, ref, watch } from 'vue'
const logoImage = '/assets/images/fevourd-k/logo.png'

/** Short synthetic tone bundled in repo (no third-party sample rights). Replace with your own MP3 if you prefer. */
const ERROR_SOUND_URL = '/assets/sounds/login-error.wav'

const page = usePage()

/**
 * Stacked login only for real handsets. Desktop OS in the UA wins first — do not trust
 * navigator.userAgentData.mobile alone (Chrome can misreport; breaks MacBook + Incognito).
 */
function isPhoneLikeUserAgent() {
    if (typeof navigator === 'undefined') {
        return false
    }

    const ua = String(navigator.userAgent || '')

    // macOS / MacBook / Safari / Chrome / Firefox / Edge / Arc (never iPhone/iPod in same UA)
    if (/Macintosh|Mac OS X/i.test(ua) && !/iPhone|iPod/i.test(ua)) {
        return false
    }

    // Windows desktop (not Windows Phone)
    if (/Windows NT|Win64|WOW64/i.test(ua) && !/Windows Phone|IEMobile|WPDesktop/i.test(ua)) {
        return false
    }

    // Chrome OS, desktop Linux
    if (/CrOS/i.test(ua)) {
        return false
    }
    if ((/X11; Linux x86_64|Linux x86_64|Linux x86\b/i.test(ua) || /X11; Ubuntu/i.test(ua)) && !/Android/i.test(ua)) {
        return false
    }

    // Explicit phones (UA string)
    if (/iPhone|iPod/i.test(ua)) {
        return true
    }

    if (/Android/i.test(ua) && /Mobile/i.test(ua)) {
        return true
    }

    if (/webOS|BlackBerry|BB10|IEMobile|Opera Mini/i.test(ua)) {
        return true
    }

    if (/Opera Mobi/i.test(ua)) {
        return true
    }

    // iPad / Android tablet → use wide layout
    if (/iPad/i.test(ua)) {
        return false
    }
    if (/Android/i.test(ua)) {
        return false
    }

    // Unknown UA: prefer wide (dev machines, new browsers)
    return false
}

function readLayoutWideFromUa() {
    return !isPhoneLikeUserAgent()
}

const form = reactive({
    login: '',
    password: '',
})

const processing = ref(false)

const errorModalOpen = ref(false)
const errorModalMessage = ref('')
const errorModalPrimaryRef = ref(null)

let errorSoundAudio = null

function playErrorSound() {
    if (typeof window === 'undefined' || typeof Audio === 'undefined') {
        return
    }
    if (window.matchMedia?.('(prefers-reduced-motion: reduce)').matches) {
        return
    }
    try {
        if (!errorSoundAudio) {
            errorSoundAudio = new Audio(ERROR_SOUND_URL)
            errorSoundAudio.preload = 'auto'
        }
        errorSoundAudio.currentTime = 0
        void errorSoundAudio.play().catch(() => {})
    } catch {
        /* ignore */
    }
}

function normalizeFieldError(value) {
    if (value === undefined || value === null || value === '') {
        return ''
    }
    return Array.isArray(value) ? String(value[0] || '') : String(value)
}

function messageFromLoginErrors(errors) {
    if (!errors) {
        return ''
    }
    const loginMsg = normalizeFieldError(errors.login)
    const pwdMsg = normalizeFieldError(errors.password)
    return loginMsg || pwdMsg || ''
}

function closeErrorModal() {
    errorModalOpen.value = false
}

watch(
    () => [page.props.errors?.login, page.props.errors?.password],
    () => {
        const msg = messageFromLoginErrors(page.props.errors)
        if (!msg) {
            return
        }
        errorModalMessage.value = msg
        errorModalOpen.value = true
        nextTick(() => {
            playErrorSound()
            errorModalPrimaryRef.value?.focus?.()
        })
    },
    { flush: 'post', immediate: true },
)

const isLayoutWide = ref(
    typeof window !== 'undefined' ? readLayoutWideFromUa() : false
)

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

let escapeOnErrorModal = null

watch(errorModalOpen, (open) => {
    if (escapeOnErrorModal) {
        window.removeEventListener('keydown', escapeOnErrorModal)
        escapeOnErrorModal = null
    }
    if (!open) {
        return
    }
    escapeOnErrorModal = (e) => {
        if (e.key === 'Escape') {
            e.preventDefault()
            closeErrorModal()
        }
    }
    window.addEventListener('keydown', escapeOnErrorModal)
})

const submit = () => {
    processing.value = true
    router.post(
        '/login',
        { login: form.login, password: form.password },
        {
            preserveScroll: true,
            onFinish: () => {
                processing.value = false
            },
        },
    )
}

onMounted(() => {
    isLayoutWide.value = readLayoutWideFromUa()
    ensureDotLottiePlayer()
    document.documentElement.classList.add('login-route-overflow-lock')
})

onUnmounted(() => {
    document.documentElement.classList.remove('login-route-overflow-lock')
    if (escapeOnErrorModal) {
        window.removeEventListener('keydown', escapeOnErrorModal)
        escapeOnErrorModal = null
    }
})
</script>

<style scoped>
/* Teleported to <body>. Wide vs stacked layout from user agent (see isPhoneLikeUserAgent), not screen width. */

/* Fixed overlay: Teleport is appended after #app, which has min-height: 100dvh — without this,
   the document becomes ~2 viewports tall and scrolling reveals a second “login” band. */
.login-page {
    position: fixed;
    inset: 0;
    z-index: 100;
    box-sizing: border-box;
    width: 100%;
    min-height: 100vh;
    min-height: 100dvh;
    background: #fff;
    overflow-x: hidden;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

.login-page__blobs {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.login-page__blob {
    position: absolute;
    border-radius: 9999px;
    filter: blur(48px);
    opacity: 0.7;
}

.login-page__blob--1 {
    top: -6rem;
    left: -5rem;
    width: 18rem;
    height: 18rem;
    background: #dbeafe;
}

.login-page__blob--2 {
    top: 33%;
    right: -5rem;
    width: 18rem;
    height: 18rem;
    background: #e0e7ff;
}

.login-page__blob--3 {
    bottom: -5rem;
    left: 25%;
    width: 20rem;
    height: 20rem;
    background: #ede9fe;
    opacity: 0.6;
}

/* Shell: column by default (phone) */
.login-page__shell {
    position: relative;
    z-index: 10;
    box-sizing: border-box;
    width: 100%;
    min-height: 100vh;
    min-height: 100dvh;
    display: flex;
    flex-direction: column;
    align-items: stretch;
}

.login-page__shell--wide {
    flex-direction: row !important;
}

/* Brand column (visibility via v-show; no display !important here) */
.login-page__brand {
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 44%;
    min-width: 0;
    min-height: 100vh;
    min-height: 100dvh;
    padding: 2.5rem 2rem 2.5rem 2.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 45%, #eef2ff 100%);
    border-right: 1px solid rgba(226, 232, 240, 0.9);
}

@media (min-width: 1280px) {
    .login-page__brand {
        width: 46%;
        padding: 3rem 2.5rem 3rem 4.5rem;
    }
}

.login-page__brand-inner {
    width: 100%;
    max-width: 36rem;
}

.login-page__brand-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.login-page__logo-box {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 4rem;
    height: 4rem;
    border-radius: 1rem;
    border: 1px solid #dbeafe;
    background: #fff;
    box-shadow: 0 1px 2px rgb(0 0 0 / 0.05);
}

.login-page--layout-wide .login-page__brand .login-page__logo-box {
    width: 4.5rem;
    height: 4.5rem;
}

.login-page__logo-box--center {
    margin: 0 auto 0.25rem;
    width: 6rem;
    height: 6rem;
    border-radius: 1.5rem;
}

.login-page__logo-img {
    width: 2.75rem;
    height: 2.75rem;
    object-fit: contain;
}

.login-page__logo-box--center .login-page__logo-img {
    width: 3.5rem;
    height: 3.5rem;
}

.login-page__brand-kicker {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: #2563eb;
}

.login-page__brand-title {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
}

.login-page__welcome {
    margin: 0;
    font-size: 1.875rem;
    font-weight: 700;
    line-height: 1.2;
    color: #0f172a;
    letter-spacing: -0.02em;
}

@media (min-width: 1280px) {
    .login-page__welcome {
        font-size: 2.25rem;
    }
}

.login-page__lead {
    margin: 0.75rem 0 0;
    font-size: 1rem;
    line-height: 1.6;
    color: #475569;
    max-width: 28rem;
}

@media (min-width: 1280px) {
    .login-page__lead {
        font-size: 1.125rem;
    }
}

.login-page__lottie-box {
    margin-top: 2rem;
    width: 100%;
    max-width: 28rem;
    padding: 1rem;
    border-radius: 1rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    background: rgba(255, 255, 255, 0.65);
    box-shadow: 0 1px 3px rgb(0 0 0 / 0.06);
}

@media (min-width: 1280px) {
    .login-page__lottie-box {
        max-width: 32rem;
    }
}

.login-page__lottie-desktop-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 240px;
}

@media (min-width: 1024px) {
    .login-page__lottie-desktop-wrap {
        height: 280px;
    }
}

@media (min-width: 1280px) {
    .login-page__lottie-desktop-wrap {
        height: 300px;
    }
}

.login-page__main {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
    width: 100%;
    min-width: 0;
    padding: 1.5rem 1rem 2rem;
    min-height: 0;
}

.login-page--layout-wide .login-page__main {
    padding: 2rem 1.5rem;
}

@media (min-width: 1280px) {
    .login-page--layout-wide .login-page__main {
        padding: 3rem 4rem;
    }
}

.login-page__card {
    box-sizing: border-box;
    width: 100%;
    max-width: 26rem;
    border-radius: 1.5rem;
    border: 1px solid #e2e8f0;
    background: #fff;
    padding: 1.5rem 1.25rem 1.75rem;
    box-shadow:
        0 10px 15px -3px rgb(0 0 0 / 0.08),
        0 4px 6px -4px rgb(0 0 0 / 0.06);
}

@media (min-width: 480px) {
    .login-page__card {
        padding: 2rem;
    }
}

.login-page--layout-wide .login-page__card {
    max-width: 28rem;
    box-shadow:
        0 25px 50px -12px rgb(0 0 0 / 0.12),
        0 12px 24px -8px rgb(0 0 0 / 0.08);
}

@media (min-width: 1280px) {
    .login-page--layout-wide .login-page__card {
        max-width: 36rem;
        padding: 2.5rem;
    }
}

/* Mobile hero vs desktop heading */
.login-page__mobile-hero {
    text-align: center;
    margin-bottom: 1rem;
}

.login-page__desktop-head {
    margin-bottom: 1.75rem;
}

.login-page__lottie-mobile-outer {
    display: flex;
    justify-content: center;
    margin-bottom: 0.5rem;
}

.login-page__lottie-mobile-wrap {
    width: 100%;
    max-width: 320px;
    height: 220px;
}

@media (min-width: 480px) {
    .login-page__lottie-mobile-wrap {
        height: 260px;
    }
}

.login-page__h1 {
    margin: 0;
    font-size: 1.875rem;
    font-weight: 700;
    color: #0f172a;
}

@media (min-width: 480px) {
    .login-page__h1 {
        font-size: 2.25rem;
    }
}

.login-page__sub {
    margin: 0.25rem 0 0;
    font-size: 0.9375rem;
    color: #64748b;
}

.login-page__sub--desktop {
    margin-top: 0.5rem;
    font-size: 1rem;
}

.login-page__form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.login-page__label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #334155;
}

.login-page__input {
    box-sizing: border-box;
    width: 100%;
    border-radius: 1rem;
    border: 1px solid #e2e8f0;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    color: #0f172a;
    background: #fff;
    transition:
        border-color 0.15s,
        box-shadow 0.15s;
}

.login-page--layout-wide .login-page__input {
    padding: 0.9375rem 1rem;
}

.login-page__input::placeholder {
    color: #94a3b8;
}

.login-page__input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgb(59 130 246 / 0.25);
}

.login-page__input--invalid {
    border-color: #f87171;
}

.login-page__input--invalid:focus {
    box-shadow: 0 0 0 3px rgb(248 113 113 / 0.25);
}

.login-page__submit {
    margin-top: 0.25rem;
    width: 100%;
    border: none;
    border-radius: 1rem;
    padding: 0.9375rem 1rem;
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    background: linear-gradient(90deg, #2563eb, #7c3aed);
    box-shadow: 0 10px 15px -3px rgb(37 99 235 / 0.35);
    transition:
        filter 0.15s,
        opacity 0.15s;
}

.login-page__submit:hover:not(:disabled) {
    filter: brightness(1.05);
}

.login-page__submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.login-page__links {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.25rem;
    margin-top: 2rem;
    font-size: 0.875rem;
}

.login-page__link {
    font-weight: 600;
    color: #475569;
    text-decoration: underline;
    text-underline-offset: 4px;
}

.login-page__link:hover {
    color: #0f172a;
}

.login-page__link--primary {
    color: #1d4ed8;
}

.login-page__link--primary:hover {
    color: #1e40af;
}

.login-page__sep {
    color: #cbd5e1;
}

.login-page__legal {
    margin: 2rem 0 0;
    text-align: center;
    font-size: 11px;
    color: #64748b;
}

/* ===================================================================
   PREMIUM MOBILE (stacked) — only the phone layout, desktop untouched.
   Immersive brand backdrop, a floating glass card, softer inputs.
   =================================================================== */
.login-page:not(.login-page--layout-wide) {
    background: radial-gradient(135% 95% at 50% -8%, #1f47c0 0%, #14307e 42%, #0a1a4d 100%);
}
.login-page:not(.login-page--layout-wide) .login-page__blob {
    filter: blur(60px);
}
.login-page:not(.login-page--layout-wide) .login-page__blob--1 { background: #3b82f6; opacity: 0.4; top: -7rem; left: -6rem; }
.login-page:not(.login-page--layout-wide) .login-page__blob--2 { background: #8b5cf6; opacity: 0.34; }
.login-page:not(.login-page--layout-wide) .login-page__blob--3 { background: #f2b40c; opacity: 0.2; }

.login-page:not(.login-page--layout-wide) .login-page__main {
    padding: 1.25rem 1.1rem calc(1.5rem + env(safe-area-inset-bottom));
}

.login-page:not(.login-page--layout-wide) .login-page__card {
    border: 1px solid rgba(255, 255, 255, 0.55);
    border-radius: 1.85rem;
    background: rgba(255, 255, 255, 0.98);
    padding: 1.6rem 1.4rem 1.85rem;
    box-shadow:
        0 34px 64px -22px rgba(6, 14, 46, 0.65),
        0 8px 20px -10px rgba(6, 14, 46, 0.4);
}

.login-page:not(.login-page--layout-wide) .login-page__mobile-hero {
    margin-bottom: 1.25rem;
}

.login-page:not(.login-page--layout-wide) .login-page__logo-box--center {
    width: 4.75rem;
    height: 4.75rem;
    border-radius: 1.4rem;
    border: 1px solid #e0e7ff;
    background: linear-gradient(140deg, #eff6ff 0%, #ede9fe 100%);
    box-shadow: 0 12px 26px -12px rgba(37, 99, 235, 0.55);
}

.login-page:not(.login-page--layout-wide) .login-page__lottie-mobile-wrap {
    max-width: 260px;
    height: 168px;
}

.login-page:not(.login-page--layout-wide) .login-page__h1 {
    font-size: 1.8rem;
    letter-spacing: -0.02em;
    background: linear-gradient(90deg, #2563eb, #7c3aed);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.login-page:not(.login-page--layout-wide) .login-page__label {
    font-weight: 600;
    color: #475569;
}

.login-page:not(.login-page--layout-wide) .login-page__input {
    padding: 1rem 1.05rem;
    border-radius: 0.95rem;
    border-color: #e6eaf2;
    background: #f8fafc;
    font-size: 1.02rem;
}
.login-page:not(.login-page--layout-wide) .login-page__input:focus {
    background: #fff;
}

.login-page:not(.login-page--layout-wide) .login-page__submit {
    padding: 1.05rem 1rem;
    border-radius: 0.95rem;
    font-size: 1.03rem;
    letter-spacing: 0.01em;
    box-shadow: 0 18px 32px -14px rgba(37, 99, 235, 0.65);
}
.login-page:not(.login-page--layout-wide) .login-page__submit:active:not(:disabled) {
    transform: translateY(1px);
}

.login-page:not(.login-page--layout-wide) .login-page__links {
    margin-top: 1.6rem;
}
.login-page:not(.login-page--layout-wide) .login-page__legal {
    margin-top: 1.5rem;
    color: #94a3b8;
}

/* Error dialog (above blobs + card) */
.login-error-modal {
    position: fixed;
    inset: 0;
    z-index: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: max(1rem, env(safe-area-inset-top)) max(1rem, env(safe-area-inset-right))
        max(1rem, env(safe-area-inset-bottom)) max(1rem, env(safe-area-inset-left));
    box-sizing: border-box;
    pointer-events: none;
}

.login-error-modal__backdrop {
    position: absolute;
    inset: 0;
    background: rgb(15 23 42 / 0.45);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    pointer-events: auto;
}

.login-error-modal__panel {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: min(26rem, 100%);
    border-radius: 1.25rem;
    border: 1px solid #e2e8f0;
    background: #fff;
    padding: 1.5rem 1.25rem 1.5rem;
    box-shadow:
        0 25px 50px -12px rgb(0 0 0 / 0.2),
        0 0 0 1px rgb(255 255 255 / 0.06) inset;
    pointer-events: auto;
    text-align: center;
}

@media (min-width: 640px) {
    .login-error-modal__panel {
        padding: 2rem 2rem 1.75rem;
        border-radius: 1.5rem;
    }
}

.login-error-modal__icon-close {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    padding: 0;
    border: none;
    border-radius: 9999px;
    background: #f1f5f9;
    color: #64748b;
    cursor: pointer;
    transition:
        background 0.15s,
        color 0.15s;
}

.login-error-modal__icon-close:hover {
    background: #e2e8f0;
    color: #0f172a;
}

.login-error-modal__icon-close-svg {
    width: 1.25rem;
    height: 1.25rem;
}

.login-error-modal__icon-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 3.5rem;
    height: 3.5rem;
    margin: 0 auto 1rem;
    border-radius: 9999px;
    background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
    border: 1px solid #fed7aa;
}

.login-error-modal__icon {
    width: 2rem;
    height: 2rem;
    color: #ea580c;
}

.login-error-modal__title {
    margin: 0 0 0.75rem;
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.3;
    color: #0f172a;
    letter-spacing: -0.02em;
}

@media (min-width: 640px) {
    .login-error-modal__title {
        font-size: 1.375rem;
    }
}

.login-error-modal__message {
    margin: 0 0 1.5rem;
    font-size: 0.9375rem;
    line-height: 1.55;
    color: #475569;
}

.login-error-modal__primary {
    width: 100%;
    border: none;
    border-radius: 0.875rem;
    padding: 0.875rem 1.25rem;
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    background: linear-gradient(90deg, #2563eb, #4f46e5);
    box-shadow: 0 4px 14px rgb(37 99 235 / 0.35);
    transition:
        filter 0.15s,
        transform 0.1s;
}

.login-error-modal__primary:hover {
    filter: brightness(1.05);
}

.login-error-modal__primary:active {
    transform: scale(0.99);
}

.login-error-modal-t-enter-active,
.login-error-modal-t-leave-active {
    transition: opacity 0.22s ease;
}

.login-error-modal-t-enter-active .login-error-modal__panel,
.login-error-modal-t-leave-active .login-error-modal__panel {
    transition:
        transform 0.22s ease,
        opacity 0.22s ease;
}

.login-error-modal-t-enter-from,
.login-error-modal-t-leave-to {
    opacity: 0;
}

.login-error-modal-t-enter-from .login-error-modal__panel,
.login-error-modal-t-leave-to .login-error-modal__panel {
    transform: scale(0.96) translateY(0.5rem);
    opacity: 0.85;
}

@media (prefers-reduced-motion: reduce) {
    .login-error-modal-t-enter-active,
    .login-error-modal-t-leave-active,
    .login-error-modal-t-enter-active .login-error-modal__panel,
    .login-error-modal-t-leave-active .login-error-modal__panel {
        transition: none;
    }

    .login-error-modal-t-enter-from .login-error-modal__panel,
    .login-error-modal-t-leave-to .login-error-modal__panel {
        transform: none;
        opacity: 1;
    }
}
</style>

<style>
/* Global: only while Login is mounted (class toggled in script). Stops body/#app from scrolling behind the fixed overlay. */
html.login-route-overflow-lock,
html.login-route-overflow-lock body {
    overflow: hidden;
    height: 100%;
}
</style>
