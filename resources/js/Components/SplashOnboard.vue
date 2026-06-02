<template>
    <Teleport to="body">
        <Transition name="fkso-fade">
            <div v-if="visible" class="fkso" role="dialog" aria-modal="true" aria-label="Welcome to FEVOURD-K">
                <div class="fkso-grain" aria-hidden="true"></div>

                <button type="button" class="fkso-skip-x" @click="finish" aria-label="Skip and close">×</button>

                <div class="fkso-card">
                    <!-- brand mark is shown on every step -->
                    <div class="fkso-brand">
                        <span class="fkso-logo-ring">
                            <img :src="logo" alt="" class="fkso-logo" />
                        </span>
                    </div>

                    <Transition name="fkso-step" mode="out-in">
                        <!-- STEP 0 · SPLASH -->
                        <div v-if="step === 'splash'" key="splash" class="fkso-step">
                            <p class="fkso-eyebrow"><span class="fkso-dot"></span> Welcome</p>
                            <h1 class="fkso-display fkso-title">FEVOURD-K</h1>
                            <p class="fkso-tagline">Empowering Karnataka’s NGOs</p>
                            <button type="button" class="fkso-btn fkso-btn--gold" @click="step = 'notify'">
                                Get started →
                            </button>
                        </div>

                        <!-- STEP 1 · NOTIFICATIONS -->
                        <div v-else-if="step === 'notify'" key="notify" class="fkso-step">
                            <div class="fkso-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                            </div>
                            <h2 class="fkso-display fkso-step__title">Stay updated</h2>
                            <p class="fkso-step__hint">Get notified about NGOs near you, campaigns and updates. You can change this anytime.</p>
                            <p v-if="notifyMsg" class="fkso-status">{{ notifyMsg }}</p>
                            <div class="fkso-actions">
                                <button type="button" class="fkso-btn fkso-btn--gold" :disabled="notifyBusy" @click="askNotifications">
                                    {{ notifyBusy ? 'Asking…' : 'Allow notifications' }}
                                </button>
                                <button type="button" class="fkso-btn fkso-btn--text" @click="step = 'locate'">Not now</button>
                            </div>
                        </div>

                        <!-- STEP 2 · LOCATION -->
                        <div v-else key="locate" class="fkso-step">
                            <div class="fkso-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <h2 class="fkso-display fkso-step__title">Find NGOs near you</h2>
                            <p class="fkso-step__hint">Share your location once and we’ll prefill your city &amp; state across the app — always editable.</p>

                            <p v-if="locateMsg" class="fkso-status">{{ locateMsg }}</p>

                            <!-- detected / manual override fields (shown after a detect attempt) -->
                            <div v-if="showFields" class="fkso-fields">
                                <div class="fkso-field">
                                    <label>City / Town</label>
                                    <input v-model.trim="cityInput" type="text" placeholder="e.g. Bengaluru" />
                                </div>
                                <div class="fkso-field">
                                    <label>State</label>
                                    <input v-model.trim="stateInput" type="text" placeholder="e.g. Karnataka" />
                                </div>
                            </div>

                            <div class="fkso-actions">
                                <button v-if="!showFields" type="button" class="fkso-btn fkso-btn--gold" :disabled="locateBusy" @click="detectLocation">
                                    {{ locateBusy ? 'Detecting…' : 'Use my location' }}
                                </button>
                                <button v-else type="button" class="fkso-btn fkso-btn--gold" @click="saveLocationAndFinish">
                                    Save &amp; continue
                                </button>

                                <button v-if="!showFields" type="button" class="fkso-btn fkso-btn--text" @click="showFields = true">
                                    Enter manually
                                </button>
                                <button v-else type="button" class="fkso-btn fkso-btn--text" @click="finish">Skip</button>
                            </div>
                        </div>
                    </Transition>

                    <button v-if="step === 'splash'" type="button" class="fkso-microskip" @click="finish">Skip intro</button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useLocation } from '@/composables/useLocation'

// Parent gates the reveal (e.g. only after the app loader hides) so the splash
// doesn't flash over the loading screen.
const props = defineProps({
    start: { type: Boolean, default: true },
})

const logo = '/assets/images/fevourd-k/logo.png'
const ONBOARDED_KEY = 'fevourdk_onboarded'
const isBrowser = typeof window !== 'undefined'

const { set: setLocation, get: getLocation } = useLocation()

const visible = ref(false)
const step = ref('splash') // 'splash' | 'notify' | 'locate'

const notifyBusy = ref(false)
const notifyMsg = ref('')

const locateBusy = ref(false)
const locateMsg = ref('')
const showFields = ref(false)
const cityInput = ref('')
const stateInput = ref('')
let detectedDistrict = ''
let detectedLat = null
let detectedLng = null

const alreadyOnboarded = () => {
    if (!isBrowser) return true
    try { return window.localStorage.getItem(ONBOARDED_KEY) === '1' } catch (e) { return false }
}

function maybeShow() {
    if (props.start && !alreadyOnboarded()) {
        // seed the manual-override fields from any previously stored location
        const existing = getLocation()
        cityInput.value = existing.city || ''
        stateInput.value = existing.state || ''
        visible.value = true
    }
}

// Reveal once the parent says it's safe to start.
watch(() => props.start, maybeShow, { immediate: true })

/** Set the onboarded flag and close — always reachable via Skip on every step. */
function finish() {
    if (isBrowser) {
        try { window.localStorage.setItem(ONBOARDED_KEY, '1') } catch (e) { /* noop */ }
    }
    visible.value = false
}

async function askNotifications() {
    if (!isBrowser || !('Notification' in window)) {
        notifyMsg.value = 'Notifications aren’t supported on this device.'
        // brief pause so the message is readable, then advance
        window.setTimeout(() => { step.value = 'locate' }, 900)
        return
    }
    notifyBusy.value = true
    try {
        const result = await Notification.requestPermission()
        notifyMsg.value = result === 'granted'
            ? 'Great — you’re subscribed.'
            : 'No problem, you can enable this later.'
    } catch (e) {
        notifyMsg.value = 'Couldn’t request permission — you can enable this later.'
    } finally {
        notifyBusy.value = false
        window.setTimeout(() => { step.value = 'locate' }, 900)
    }
}

/** Defensively pull a district-ish name out of BigDataCloud's response. */
function extractDistrict(data) {
    const admin = data?.localityInfo?.administrative
    if (Array.isArray(admin)) {
        // district usually sits around adminLevel 5/6 in India
        const byLevel = admin.find((a) => a.adminLevel === 5 || a.adminLevel === 6)
        if (byLevel?.name) return byLevel.name
    }
    return data?.locality || ''
}

async function detectLocation() {
    if (!isBrowser || !navigator.geolocation) {
        locateMsg.value = 'Location isn’t available — please enter it manually.'
        showFields.value = true
        return
    }
    locateBusy.value = true
    locateMsg.value = 'Detecting your location…'

    navigator.geolocation.getCurrentPosition(
        async (pos) => {
            detectedLat = Number(pos.coords.latitude.toFixed(7))
            detectedLng = Number(pos.coords.longitude.toFixed(7))
            try {
                const res = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${detectedLat}&longitude=${detectedLng}&localityLanguage=en`)
                const data = await res.json()
                cityInput.value = data.city || data.locality || ''
                stateInput.value = data.principalSubdivision || ''
                detectedDistrict = extractDistrict(data)
                locateMsg.value = cityInput.value || stateInput.value
                    ? 'Found it — check and correct if needed.'
                    : 'Couldn’t read your address — please enter it manually.'
            } catch (e) {
                locateMsg.value = 'Location captured, but address lookup failed — enter it manually.'
            } finally {
                locateBusy.value = false
                showFields.value = true
            }
        },
        () => {
            locateBusy.value = false
            locateMsg.value = 'Location permission denied — you can enter it manually.'
            showFields.value = true
        },
        { enableHighAccuracy: true, timeout: 12000, maximumAge: 60000 }
    )
}

function saveLocationAndFinish() {
    setLocation({
        city: cityInput.value,
        state: stateInput.value,
        district: detectedDistrict,
        lat: detectedLat,
        lng: detectedLng,
        source: detectedLat != null ? 'geolocation' : 'manual',
    })
    finish()
}
</script>

<style>
.fkso {
    --ink: #0d1f5c; --ink-2: #11286e; --ink-deep: #081640;
    --gold: #f2b40c; --gold-soft: #f7c948;
    --font-display: 'Fraunces','Playfair Display',Georgia,serif;
    --font-body: 'Hanken Grotesk',ui-sans-serif,system-ui,'Segoe UI',sans-serif;
    position: fixed; inset: 0; z-index: 100002; isolation: isolate; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    padding: 24px; font-family: var(--font-body); color: #f4eeda;
    background: radial-gradient(120% 120% at 85% -10%, #1b3aa0 0%, var(--ink-2) 40%, var(--ink-deep) 100%);
}
.fkso-grain {
    position: absolute; inset: 0; z-index: -1; opacity: .5; mix-blend-mode: overlay; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
}
.fkso-display { font-family: var(--font-display); font-optical-sizing: auto; }

.fkso-skip-x {
    position: absolute; top: 16px; right: 16px; width: 40px; height: 40px; border-radius: 50%;
    border: none; background: rgba(247,240,223,.12); color: #f4eeda; font-size: 1.5rem; line-height: 1;
    cursor: pointer; transition: background .2s ease; z-index: 2;
}
.fkso-skip-x:hover { background: rgba(247,240,223,.22); }

.fkso-card { position: relative; width: 100%; max-width: 380px; text-align: center; }

.fkso-brand { margin-bottom: 1.6rem; }
.fkso-logo-ring {
    display: inline-grid; place-items: center; width: 96px; height: 96px; border-radius: 50%;
    background: rgba(247,240,223,.1); border: 1px solid rgba(242,180,12,.4);
    box-shadow: 0 0 0 8px rgba(242,180,12,.08); animation: fksoFloat 4s ease-in-out infinite;
}
.fkso-logo { width: 58px; height: 58px; object-fit: contain; }
@keyframes fksoFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }

.fkso-eyebrow {
    display: inline-flex; align-items: center; gap: .55em; font-size: .74rem; font-weight: 600;
    letter-spacing: .14em; text-transform: uppercase; color: var(--gold-soft); margin: 0 0 .6rem;
}
.fkso-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold); box-shadow: 0 0 0 4px rgba(242,180,12,.2); }

.fkso-title { font-size: clamp(2rem, 8vw, 2.8rem); font-weight: 600; letter-spacing: -.02em; margin: 0; line-height: 1.05; }
.fkso-tagline { color: #d6d3c4; font-size: 1rem; margin: .5rem 0 2rem; }

.fkso-icon {
    display: inline-grid; place-items: center; width: 64px; height: 64px; margin: 0 auto 1.1rem;
    border-radius: 18px; background: rgba(242,180,12,.12); color: var(--gold-soft);
}
.fkso-icon svg { width: 30px; height: 30px; }

.fkso-step__title { font-size: clamp(1.5rem, 6vw, 1.9rem); font-weight: 600; margin: 0; letter-spacing: -.01em; }
.fkso-step__hint { color: #c9c6b7; line-height: 1.55; font-size: .95rem; margin: .6rem auto 1.4rem; max-width: 32ch; }
.fkso-status { color: var(--gold-soft); font-size: .9rem; font-weight: 500; margin: 0 0 1.2rem; min-height: 1.2em; }

.fkso-fields { display: flex; flex-direction: column; gap: .9rem; margin: 0 0 1.4rem; text-align: left; }
.fkso-field { display: flex; flex-direction: column; gap: .35rem; }
.fkso-field label { font-size: .8rem; font-weight: 600; color: #d6d3c4; }
.fkso-field input {
    width: 100%; padding: .8em 1em; border-radius: 12px; border: 1px solid rgba(247,240,223,.28);
    background: rgba(247,240,223,.06); color: #fff; font-family: inherit; font-size: 16px;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.fkso-field input::placeholder { color: rgba(247,240,223,.45); }
.fkso-field input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(242,180,12,.18); }

.fkso-actions { display: flex; flex-direction: column; gap: .6rem; align-items: stretch; }
.fkso-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: .4em;
    padding: .9em 1.5em; border-radius: 999px; border: none; font: inherit; font-weight: 700;
    font-size: .96rem; cursor: pointer; transition: transform .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease;
}
.fkso-btn:disabled { opacity: .6; cursor: progress; }
.fkso-btn--gold { background: var(--gold); color: #2a1c00; box-shadow: 0 14px 30px -14px rgba(242,180,12,.75); }
.fkso-btn--gold:hover:not(:disabled) { transform: translateY(-2px); }
.fkso-btn--gold:active:not(:disabled) { transform: scale(.98); }
.fkso-btn--text { background: transparent; color: #c9c6b7; }
.fkso-btn--text:hover { color: #fff; }

.fkso-microskip {
    display: block; margin: 1.4rem auto 0; background: none; border: none; color: rgba(247,240,223,.5);
    font: inherit; font-size: .82rem; cursor: pointer; text-decoration: underline; text-underline-offset: 3px;
}
.fkso-microskip:hover { color: #f4eeda; }

/* transitions */
.fkso-fade-enter-active, .fkso-fade-leave-active { transition: opacity .35s ease; }
.fkso-fade-enter-from, .fkso-fade-leave-to { opacity: 0; }
.fkso-step-enter-active, .fkso-step-leave-active { transition: opacity .25s ease, transform .25s ease; }
.fkso-step-enter-from { opacity: 0; transform: translateY(8px); }
.fkso-step-leave-to { opacity: 0; transform: translateY(-8px); }

@media (prefers-reduced-motion: reduce) {
    .fkso-logo-ring { animation: none; }
    .fkso-fade-enter-active, .fkso-fade-leave-active,
    .fkso-step-enter-active, .fkso-step-leave-active { transition: opacity .15s ease; }
    .fkso-step-enter-from, .fkso-step-leave-to { transform: none; }
}

@media (max-width: 380px) {
    .fkso { padding: 16px; }
    .fkso-logo-ring { width: 84px; height: 84px; }
    .fkso-logo { width: 50px; height: 50px; }
}
</style>
