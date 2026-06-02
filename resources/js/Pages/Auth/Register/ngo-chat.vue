<template>
    <AppLayout title="Register your NGO - FEVOURD-K" :hide-chrome-mobile="true">
        <div class="fks">
            <div class="fks-grain" aria-hidden="true"></div>
            <div class="fks-glow" aria-hidden="true"></div>

            <div class="fks-shell">
                <!-- ============ BRAND PANEL ============ -->
                <aside class="fks-brand">
                    <div class="fks-brand__rings" aria-hidden="true">
                        <span></span><span></span><span></span>
                    </div>
                    <div class="fks-emblem">
                        <img :src="logoImage" alt="FEVOURD-K">
                    </div>
                    <p class="fks-eyebrow"><span class="fks-dot"></span> NGO registration</p>
                    <h1 class="fks-display fks-brand__title">Everything your <span class="fks-ngo">NGO</span> needs to thrive.</h1>
                    <p class="fks-brand__sub">
                        Join FEVOURD-K free and unlock the tools to raise funds, get discovered by
                        donors and corporates, and run your whole organisation — all in one place.
                    </p>
                    <div class="fks-perks-teaser">
                        <button type="button" class="fks-perks-btn" @click="haptic(10); showBenefits = true">
                            <span class="fks-perks-btn__spark" aria-hidden="true">✨</span>
                            See everything you get
                            <span class="fks-perks-btn__arrow" aria-hidden="true">→</span>
                        </button>
                        <p class="fks-perks-note">Free to join · 10+ tools to fund &amp; run your NGO</p>
                    </div>
                </aside>

                <!-- ============ FORM CARD ============ -->
                <section class="fks-card">
                    <header class="fks-card__head">
                        <h2 class="fks-display">Create your NGO account</h2>
                        <p>Step 1 of 2 · just the essentials</p>
                    </header>

                    <div v-if="generalError" class="fks-alert" role="alert">
                        <span class="fks-alert__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.48 14.7A1.5 1.5 0 003.11 21h17.78a1.5 1.5 0 001.3-2.44l-8.48-14.7a1.5 1.5 0 00-2.6 0z"/></svg>
                        </span>
                        <span class="fks-alert__msg">{{ generalError }}</span>
                    </div>

                    <form class="fks-form" @submit.prevent="submit">
                        <div class="fks-field">
                            <label>Organisation name</label>
                            <input v-model.trim="form.ngo_name" type="text" placeholder="e.g. Grameen Vikas Trust" autocomplete="organization">
                            <small v-if="errors.ngo_name" class="fks-err">{{ errors.ngo_name[0] }}</small>
                        </div>

                        <div class="fks-field">
                            <label>Contact person</label>
                            <input v-model.trim="form.founder_name" type="text" placeholder="Full name of the person registering" autocomplete="name">
                            <small v-if="errors.founder_name" class="fks-err">{{ errors.founder_name[0] }}</small>
                        </div>

                        <div class="fks-field">
                            <label>Email address</label>
                            <input v-model.trim="form.email" type="email" placeholder="you@organisation.org" autocomplete="email">
                            <small v-if="errors.email" class="fks-err">{{ errors.email[0] }}</small>
                        </div>

                        <div class="fks-field">
                            <label>Mobile number</label>
                            <div class="fks-otp-row">
                                <span class="fks-cc" aria-hidden="true">🇮🇳 +91</span>
                                <input v-model.trim="form.phone" type="tel" inputmode="numeric" maxlength="10" placeholder="10-digit mobile" autocomplete="tel" :disabled="otpSent">
                            </div>
                            <button type="button" class="fks-otp-btn fks-otp-btn--block" :disabled="otpLoading || !form.phone" @click="sendOtp">
                                {{ otpSent ? 'Resend OTP' : (otpLoading ? 'Sending…' : 'Send OTP') }}
                            </button>
                            <small v-if="errors.phone" class="fks-err">{{ errors.phone[0] }}</small>
                            <small v-if="pilotOtp" class="fks-hint">Demo OTP: <strong>{{ pilotOtp }}</strong></small>
                            <small v-else-if="otpSent" class="fks-hint">We’ve sent a code to your mobile{{ form.email ? ' and email' : '' }}.</small>
                        </div>

                        <div v-if="otpSent" class="fks-field">
                            <label>Enter OTP</label>
                            <input v-model.trim="form.otp_code" type="text" inputmode="numeric" maxlength="8" placeholder="••••" class="fks-otp-code">
                            <small v-if="errors.otp_code" class="fks-err">{{ errors.otp_code[0] }}</small>
                        </div>

                        <div class="fks-grid2">
                            <div class="fks-field">
                                <label>Create login PIN</label>
                                <div class="fks-pass">
                                    <input v-model="form.password" :type="showPw ? 'text' : 'password'" inputmode="numeric" maxlength="6" placeholder="6-digit PIN" autocomplete="new-password" class="fks-pin" @input="form.password = form.password.replace(/\D/g, '').slice(0, 6)">
                                    <button type="button" class="fks-pass__toggle" @click="haptic(8); showPw = !showPw" :aria-label="showPw ? 'Hide password' : 'Show password'">
                                        <svg v-if="!showPw" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S5.5 5.5 12 5.5 21.5 12 21.5 12 18.5 18.5 12 18.5 2.5 12 2.5 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a3 3 0 004.2 4.2M9.9 4.7A9.8 9.8 0 0112 4.5c6.5 0 9.5 6.5 9.5 6.5a16 16 0 01-3 3.9M6.1 6.1A16 16 0 002.5 11s3 6.5 9.5 6.5a9.6 9.6 0 003.1-.5"/></svg>
                                    </button>
                                </div>
                                <small v-if="errors.password" class="fks-err">{{ errors.password[0] }}</small>
                            </div>
                            <div class="fks-field">
                                <label>Confirm PIN</label>
                                <div class="fks-pass">
                                    <input v-model="form.password_confirmation" :type="showPw ? 'text' : 'password'" inputmode="numeric" maxlength="6" placeholder="Re-enter PIN" autocomplete="new-password" class="fks-pin" @input="form.password_confirmation = form.password_confirmation.replace(/\D/g, '').slice(0, 6)">
                                </div>
                                <small v-if="form.password_confirmation && form.password !== form.password_confirmation" class="fks-err">PINs don’t match.</small>
                                <small v-else-if="form.password_confirmation && form.password === form.password_confirmation" class="fks-ok">PINs match ✓</small>
                            </div>
                        </div>

                        <label class="fks-terms">
                            <input v-model="form.accept_terms" type="checkbox">
                            <span>I agree to the <a href="/legal/terms" target="_blank">Terms</a> and <a href="/legal/privacy" target="_blank">Privacy Policy</a>.</span>
                        </label>
                        <small v-if="errors.accept_terms" class="fks-err">{{ errors.accept_terms[0] }}</small>

                        <button type="submit" class="fks-submit" :disabled="submitting || !form.accept_terms">
                            {{ submitting ? 'Creating your account…' : 'Create account & continue' }}
                            <span v-if="!submitting" class="fks-submit__arrow">→</span>
                        </button>

                        <p class="fks-signin">
                            Already registered?
                            <Link href="/login">Sign in</Link>
                        </p>
                    </form>
                </section>
            </div>
        </div>
        <!-- Everything-you-get modal -->
        <Teleport to="body">
            <Transition name="fks-modal">
                <div v-if="showBenefits" class="fks-modal" role="dialog" aria-modal="true" @click.self="showBenefits = false">
                    <div class="fks-modal__panel">
                        <button type="button" class="fks-modal__close" aria-label="Close" @click="showBenefits = false">×</button>
                        <p class="fks-modal__eyebrow">Free with your account</p>
                        <h3 class="fks-display fks-modal__title">Everything you get</h3>
                        <p class="fks-modal__sub">10 tools to fund, grow and run your NGO — included from day one, at no cost.</p>
                        <ul class="fks-modal__grid">
                            <li v-for="(b, i) in benefits" :key="b.title" class="fks-perk" :style="{ '--i': i }">
                                <span class="fks-perk__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" :d="icons[b.icon]" /></svg></span>
                                <div><strong>{{ b.title }}</strong><em>{{ b.desc }}</em></div>
                            </li>
                        </ul>
                        <button type="button" class="fks-modal__cta" @click="showBenefits = false">Sounds great — let’s go →</button>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Validation error popup -->
        <Teleport to="body">
            <Transition name="fks-emodal">
                <div v-if="showErrorModal" class="fks-emodal" role="alertdialog" aria-modal="true" @click.self="showErrorModal = false">
                    <div class="fks-emodal__panel">
                        <div class="fks-emodal__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.48 14.7A1.5 1.5 0 003.11 21h17.78a1.5 1.5 0 001.3-2.44l-8.48-14.7a1.5 1.5 0 00-2.6 0z"/></svg>
                        </div>
                        <h3 class="fks-display fks-emodal__title">{{ errorItems.length ? 'Please fix these' : 'Hold on' }}</h3>
                        <p v-if="!errorItems.length" class="fks-emodal__msg">{{ generalError }}</p>
                        <ul v-else class="fks-emodal__list">
                            <li v-for="(e, i) in errorItems" :key="i">{{ e }}</li>
                        </ul>
                        <button type="button" class="fks-emodal__btn" @click="showErrorModal = false">Got it</button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { computed, reactive, ref } from 'vue'

const logoImage = '/assets/images/fevourd-k/logo.png'

const form = reactive({
    ngo_name: '', founder_name: '', email: '', phone: '',
    otp_code: '', password: '', password_confirmation: '', accept_terms: false,
})

const errors = ref({})
const generalError = ref('')
const otpSent = ref(false)
const otpLoading = ref(false)
const pilotOtp = ref('')
const submitting = ref(false)
const showPw = ref(false)
const showBenefits = ref(false)
const showErrorModal = ref(false)
const errorItems = computed(() => {
    const out = []
    for (const k in errors.value) {
        const v = errors.value[k]
        if (Array.isArray(v)) v.forEach((m) => m && out.push(String(m)))
        else if (v) out.push(String(v))
    }
    return out
})

const icons = {
    heart: 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z',
    globe: 'M12 21a9 9 0 100-18 9 9 0 000 18zm-9-9h18M12 3a15 15 0 010 18M12 3a15 15 0 000 18',
    building: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5',
    users: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
    wallet: 'M21 12a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3',
    map: 'M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z',
    megaphone: 'M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59',
    chart: 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
    shield: 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286z',
    share: 'M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z',
}

const benefits = [
    { icon: 'heart', title: 'Receive donations', desc: 'Verified campaigns with transparent, real-time fund reports.' },
    { icon: 'globe', title: 'A free NGO website', desc: 'Auto-generated microsite with stories, programmes & a donate button.' },
    { icon: 'building', title: 'CSR partnerships', desc: 'Get discovered by companies and unlock CSR budgets.' },
    { icon: 'users', title: 'Volunteers & supporters', desc: 'Build a following — recruit volunteers and grow a community.' },
    { icon: 'wallet', title: 'Finance & treasury', desc: 'Ledger, payouts, bank accounts & payment gateways built in.' },
    { icon: 'map', title: 'Field teams & HR', desc: 'Staff, leave and GPS-tracked field visits — one login.' },
    { icon: 'megaphone', title: 'Campaigns & feed', desc: 'Launch campaigns and post to a live community feed.' },
    { icon: 'chart', title: 'Analytics & impact', desc: 'Track donations, reach and impact with clear dashboards.' },
    { icon: 'shield', title: 'Verified trust badge', desc: 'Get verified so donors and corporates trust you instantly.' },
    { icon: 'share', title: 'Auto social posting', desc: 'Push updates to Facebook, Instagram & LinkedIn automatically.' },
]

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

/** Light haptic feedback — works on Android Chrome/most Android browsers (no-op on iOS Safari & desktop). */
const haptic = (pattern = 12) => {
    try { navigator.vibrate?.(pattern) } catch (e) { /* unsupported */ }
}

const sendOtp = async () => {
    errors.value = {}
    generalError.value = ''
    haptic(15)
    if (!form.phone) { errors.value = { phone: ['Please enter your mobile number first.'] }; return }
    otpLoading.value = true
    try {
        const res = await fetch('/auth/send-otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() },
            body: JSON.stringify({ phone: form.phone, email: form.email || null }),
        })
        const data = await res.json()
        if (!res.ok || !data.success) {
            errors.value = data.errors || {}
            generalError.value = data.message || 'Could not send OTP. Please try again.'
            return
        }
        otpSent.value = true
        haptic([12, 45, 12])
        pilotOtp.value = data.pilot && data.otp_plain ? data.otp_plain : ''
    } catch (e) {
        generalError.value = 'Network error while sending OTP. Please try again.'
    } finally {
        otpLoading.value = false
    }
}

const submit = async () => {
    errors.value = {}
    generalError.value = ''
    if (!otpSent.value) {
        generalError.value = 'Please verify your mobile number with an OTP first.'
        haptic([20, 60, 20]); showErrorModal.value = true
        return
    }
    haptic(15)
    submitting.value = true
    try {
        const res = await fetch('/register/ngo/signup', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() },
            body: JSON.stringify({ ...form }),
        })
        const data = await res.json()
        if (!res.ok || !data.success) {
            errors.value = data.errors || {}
            generalError.value = data.message || (Object.keys(errors.value).length ? 'Please fix the highlighted fields below.' : 'Could not create your account. Please review the form.')
            haptic([20, 60, 20])
            showErrorModal.value = true
            return
        }
        haptic([15, 50, 15, 50, 25])
        window.location.href = data.redirect_url || '/ngo/dashboard'
    } catch (e) {
        generalError.value = 'Network error while creating your account. Please try again.'
        showErrorModal.value = true
    } finally {
        submitting.value = false
    }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap');

.fks {
    --ink: #0d1f5c; --ink-2: #11286e; --ink-deep: #081640;
    --gold: #f2b40c; --gold-soft: #f7c948; --magenta: #c12a63; --emerald: #1f8a5b;
    --paper: #f7f0df; --char: #1c2230;
    --font-display: 'Fraunces','Playfair Display',Georgia,serif;
    --font-body: 'Hanken Grotesk',ui-sans-serif,system-ui,'Segoe UI',sans-serif;
    position: relative; isolation: isolate; overflow: hidden; font-family: var(--font-body); color: var(--char);
    min-height: 100%; padding: clamp(28px, 5vw, 72px) clamp(16px, 4vw, 48px);
    background: radial-gradient(120% 120% at 80% -10%, #1b3aa0 0%, var(--ink-2) 40%, var(--ink-deep) 100%);
}
.fks-display { font-family: var(--font-display); font-optical-sizing: auto; }
.fks-grain { position: absolute; inset: 0; z-index: -1; opacity: .5; mix-blend-mode: overlay; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E"); }
.fks-glow { position: absolute; z-index: -1; width: 60vw; height: 60vw; right: -10vw; top: -16vw; pointer-events: none;
    background: radial-gradient(circle, rgba(242,180,12,.22), rgba(242,180,12,0) 62%); }

.fks-shell { width: 100%; max-width: 1080px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1.05fr; gap: clamp(20px, 3vw, 40px); align-items: stretch; }

/* brand panel */
.fks-brand { position: relative; overflow: hidden; color: #f4eeda; padding: clamp(26px, 3vw, 46px); display: flex; flex-direction: column; }
.fks-brand__rings { position: absolute; inset: 0; z-index: 0; opacity: .4; pointer-events: none; }
.fks-brand__rings span { position: absolute; right: -8%; top: 30%; width: 380px; height: 380px; border-radius: 50%; border: 1.5px dashed rgba(242,180,12,.4); }
.fks-brand__rings span:nth-child(2) { right: 2%; top: 38%; width: 280px; height: 280px; border-style: solid; border-color: rgba(193,42,99,.34); }
.fks-brand__rings span:nth-child(3) { right: 10%; top: 46%; width: 190px; height: 190px; border-color: rgba(31,138,91,.4); }
.fks-emblem { position: relative; z-index: 1; display: grid; place-items: center; width: 92px; height: 92px; border-radius: 50%; margin-bottom: 1.4rem;
    background: radial-gradient(circle at 50% 38%, rgba(247,240,223,.16), rgba(247,240,223,.04) 70%);
    box-shadow: inset 0 0 0 1px rgba(242,180,12,.3), 0 24px 50px -22px rgba(0,0,0,.6); }
.fks-emblem img { width: 64%; height: auto; filter: drop-shadow(0 8px 18px rgba(0,0,0,.45)); }
.fks-eyebrow { position: relative; z-index: 1; display: inline-flex; align-items: center; gap: .55em; font-size: .76rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: var(--gold-soft); margin: 0; }
.fks-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold); box-shadow: 0 0 0 4px rgba(242,180,12,.2); }
.fks-brand__title { position: relative; z-index: 1; font-weight: 600; font-size: clamp(1.9rem, 3.4vw, 3rem); line-height: 1.02; letter-spacing: -.02em; margin: .9rem 0 0; }
.fks-brand__sub { position: relative; z-index: 1; color: #d6d3c4; line-height: 1.6; font-size: 1.02rem; margin: 1.1rem 0 1.6rem; max-width: 40ch; }
.fks-brand__points { position: relative; z-index: 1; list-style: none; margin: auto 0 0; padding: 0; display: flex; flex-direction: column; gap: .8rem; }
.fks-brand__points li { display: flex; align-items: center; gap: .8rem; font-weight: 500; color: #eae7d8; }
.fks-brand__points span { display: grid; place-items: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(242,180,12,.16); color: var(--gold-soft); font-weight: 700; font-size: .85rem; border: 1px solid rgba(242,180,12,.34); }

/* form card */
.fks-card { background: #fffdf6; border-radius: 24px; padding: clamp(24px, 3vw, 42px); box-shadow: 0 40px 80px -30px rgba(0,0,0,.55); }
.fks-card__head h2 { font-size: clamp(1.5rem, 2.6vw, 2rem); font-weight: 600; color: var(--ink); margin: 0; letter-spacing: -.01em; }
.fks-card__head p { margin: .35rem 0 1.4rem; color: #8a6d12; font-weight: 600; font-size: .82rem; letter-spacing: .04em; text-transform: uppercase; }
.fks-alert { display: flex; align-items: flex-start; gap: .7rem; background: linear-gradient(#fff5f7, #fdecef); color: #a01446; border: 1px solid #f3c4d2; border-left: 4px solid #e0285f; border-radius: 14px; padding: .85rem 1rem; font-size: .9rem; margin: 0 0 1.1rem; box-shadow: 0 12px 26px -16px rgba(193,42,99,.5); animation: fksShake .5s cubic-bezier(.36,.07,.19,.97); }
.fks-alert__icon { flex-shrink: 0; display: grid; place-items: center; width: 22px; height: 22px; color: #e0285f; }
.fks-alert__icon svg { width: 20px; height: 20px; }
.fks-alert__msg { line-height: 1.45; font-weight: 600; padding-top: .05rem; }
@keyframes fksShake { 10%, 90% { transform: translateX(-1px); } 20%, 80% { transform: translateX(2px); } 30%, 50%, 70% { transform: translateX(-4px); } 40%, 60% { transform: translateX(4px); } }
.fks-form { display: flex; flex-direction: column; gap: 1rem; }
.fks-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.fks-field { display: flex; flex-direction: column; gap: .4rem; }
.fks-field label { font-size: .82rem; font-weight: 600; color: #3a3f4c; }
.fks-field input { width: 100%; padding: .85em 1em; border-radius: 12px; border: 1px solid #ddd2b6; background: #fdfbf3; font-family: inherit; font-size: 16px; color: var(--char); transition: border-color .2s ease, box-shadow .2s ease; }
.fks-field input:focus { outline: none; border-color: var(--ink); box-shadow: 0 0 0 3px rgba(13,31,92,.12); }
.fks-field input:disabled { background: #f0ead8; color: #8b8675; }
.fks-otp-row { display: flex; gap: .55rem; align-items: stretch; }
.fks-otp-row input { flex: 1; min-width: 0; padding: .95em 1em; font-size: 1.05rem; letter-spacing: .03em; }
.fks-cc { display: inline-flex; align-items: center; padding: 0 .95em; border-radius: 12px; border: 1px solid #ddd2b6; background: #f3ead2; color: #3a3f4c; font-weight: 700; font-size: 1rem; white-space: nowrap; user-select: none; cursor: default; }
.fks-otp-btn.fks-otp-btn--block { width: 100%; margin-top: .25rem; padding: 1.05em 1.2em; font-size: .98rem; }

/* animated gold "NGO" in the headline */
.fks-ngo { background: linear-gradient(100deg, var(--gold) 0%, var(--gold-soft) 30%, #fff6d0 50%, var(--gold-soft) 70%, var(--gold) 100%); background-size: 250% auto; -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; color: transparent; animation: fksNgoShimmer 3.6s linear infinite; }
@keyframes fksNgoShimmer { from { background-position: 0% center; } to { background-position: 250% center; } }
.fks-otp-btn { flex-shrink: 0; padding: 0 1.1em; border-radius: 12px; border: 1px solid var(--ink); background: var(--ink); color: var(--paper); font-weight: 600; font-size: .88rem; cursor: pointer; transition: transform .2s ease, opacity .2s ease; }
.fks-otp-btn:hover:not(:disabled) { transform: translateY(-2px); }
.fks-otp-btn:disabled { opacity: .5; cursor: not-allowed; }
.fks-err { display: flex; align-items: center; gap: .35em; color: #c0285f; font-size: .8rem; font-weight: 600; animation: fksErrIn .35s ease both; }
.fks-err::before { content: '⚠'; font-size: .95em; line-height: 1; }
@keyframes fksErrIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }
/* any field with an error gets a red border + a shake (uses :has) */
.fks-field:has(.fks-err) input { border-color: #e0285f !important; background: #fdf3f6; animation: fksShake .42s cubic-bezier(.36,.07,.19,.97); }
.fks-field:has(.fks-err) input:focus { box-shadow: 0 0 0 3px rgba(224,40,95,.18) !important; }
@media (prefers-reduced-motion: reduce) {
    .fks-alert, .fks-err, .fks-field:has(.fks-err) input { animation: none !important; }
}
.fks-hint { color: #5a5e6a; font-size: .82rem; }
.fks-hint strong { color: var(--emerald); letter-spacing: .08em; }
.fks-terms { display: flex; align-items: flex-start; gap: .6rem; font-size: .9rem; color: #4a4e5a; margin-top: .2rem; }
.fks-terms input { margin-top: .2rem; width: 18px; height: 18px; accent-color: var(--ink); }
.fks-terms a { color: var(--magenta); font-weight: 600; }
.fks-submit { display: inline-flex; align-items: center; justify-content: center; gap: .5em; margin-top: .6rem; padding: 1em 1.4em; border: none; border-radius: 999px; background: var(--gold); color: #2a1c00; font: inherit; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 16px 34px -14px rgba(242,180,12,.75); transition: transform .25s ease, box-shadow .25s ease, opacity .2s ease; }
.fks-submit:hover:not(:disabled) { transform: translateY(-3px); box-shadow: 0 22px 42px -14px rgba(242,180,12,.9); }
.fks-submit:disabled { opacity: .65; cursor: progress; }
.fks-submit__arrow { transition: transform .2s ease; }
.fks-submit:hover:not(:disabled) .fks-submit__arrow { transform: translateX(4px); }
.fks-signin { text-align: center; margin: .4rem 0 0; color: #5a5e6a; font-size: .92rem; }
.fks-signin a { color: var(--ink); font-weight: 700; }

/* enhancements */
.fks-otp-btn { white-space: nowrap; }
.fks-pass { position: relative; }
.fks-pass input { padding-right: 2.8em; }
.fks-pass__toggle { position: absolute; right: .4em; top: 50%; transform: translateY(-50%); display: grid; place-items: center; width: 2.1em; height: 2.1em; border: none; background: none; color: #8a8470; cursor: pointer; padding: 0; }
.fks-pass__toggle svg { width: 20px; height: 20px; }
.fks-pass__toggle:hover { color: var(--ink); }
/* PIN-style fields */
.fks-field .fks-pin { text-align: center; font-size: 1.3rem; font-weight: 700; letter-spacing: .45em; padding-left: 3em; padding-right: 2.8em; }
.fks-field .fks-pin::placeholder { letter-spacing: normal; font-size: 1rem; font-weight: 400; }
.fks-field .fks-otp-code { text-align: center; font-size: 1.45rem; font-weight: 700; letter-spacing: .5em; padding-left: 1.4em; color: var(--ink); }
.fks-field .fks-otp-code::placeholder { letter-spacing: .35em; color: #cbbfa0; font-size: 1.1rem; }
.fks-ok { color: #1f8a5b; font-size: .8rem; font-weight: 600; }

/* ============ living brand panel ============ */
/* drifting background glow — the panel feels like it's breathing */
.fks-glow { animation: fksDrift 16s ease-in-out infinite alternate; }
@keyframes fksDrift { 0% { transform: translate(0, 0) scale(1); opacity: .75; } 100% { transform: translate(-6vw, 4vw) scale(1.18); opacity: 1; } }

/* emblem: gentle float + two counter-rotating orbit rings + soft halo */
.fks-emblem { animation: fksFloat 5.5s ease-in-out infinite; }
.fks-emblem::before { content: ''; position: absolute; inset: -13px; border-radius: 50%; border: 1.5px dashed rgba(242,180,12,.5); animation: fksSpin 22s linear infinite; }
.fks-emblem::after { content: ''; position: absolute; inset: -27px; border-radius: 50%; border: 1px solid rgba(31,138,91,.32); animation: fksSpin 34s linear infinite reverse; }
@keyframes fksFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
@keyframes fksSpin { to { transform: rotate(360deg); } }

/* eyebrow dot radar pulse */
.fks-dot { animation: fksDotPulse 1.9s ease-out infinite; }
@keyframes fksDotPulse { 0% { box-shadow: 0 0 0 0 rgba(242,180,12,.55); } 100% { box-shadow: 0 0 0 11px rgba(242,180,12,0); } }

/* teaser button keeps the panel light */
.fks-perks-teaser { margin: auto 0 0; }
.fks-perks-btn { display: inline-flex; align-items: center; gap: .55em; padding: .9em 1.5em; border-radius: 999px; border: 1px solid rgba(242,180,12,.45); background: rgba(242,180,12,.12); color: #f7c948; font: inherit; font-size: 1rem; font-weight: 700; cursor: pointer; transition: transform .25s ease, background .25s ease, box-shadow .25s ease; }
.fks-perks-btn:hover { transform: translateY(-3px); background: rgba(242,180,12,.2); box-shadow: 0 18px 36px -16px rgba(242,180,12,.6); }
.fks-perks-btn__arrow { transition: transform .25s ease; }
.fks-perks-btn:hover .fks-perks-btn__arrow { transform: translateX(4px); }
.fks-perks-note { margin: .85rem 0 0; color: #c7c4b5; font-size: .85rem; }
/* interactive: shimmer sweep + twinkling spark */
.fks-perks-btn { position: relative; overflow: hidden; }
.fks-perks-btn::after { content: ''; position: absolute; top: 0; left: -75%; width: 50%; height: 100%; background: linear-gradient(100deg, transparent, rgba(255,255,255,.4), transparent); transform: skewX(-20deg); animation: fksShine 3.4s ease-in-out infinite; pointer-events: none; }
@keyframes fksShine { 0% { left: -75%; } 55%, 100% { left: 145%; } }
.fks-perks-btn__spark { display: inline-block; animation: fksTwinkle 1.9s ease-in-out infinite; }
@keyframes fksTwinkle { 0%, 100% { transform: scale(1) rotate(0); opacity: .85; } 50% { transform: scale(1.3) rotate(16deg); opacity: 1; } }
@media (prefers-reduced-motion: reduce) { .fks-perks-btn::after, .fks-perks-btn__spark { animation: none !important; } }

/* everything-you-get modal */
.fks-modal { position: fixed; inset: 0; z-index: 200; display: flex; align-items: flex-end; justify-content: center; background: rgba(8,16,40,.55); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
@media (min-width: 720px) { .fks-modal { align-items: center; padding: 24px; } }
.fks-modal__panel { position: relative; width: 100%; max-width: 660px; max-height: 92vh; overflow-y: auto; background: #fffdf6; border-radius: 28px 28px 0 0; padding: 30px 20px calc(28px + env(safe-area-inset-bottom)); box-shadow: 0 -24px 64px -22px rgba(0,0,0,.55); }
@media (min-width: 720px) { .fks-modal__panel { border-radius: 28px; padding: 38px; } }
.fks-modal__close { position: absolute; top: 14px; right: 14px; display: grid; place-items: center; width: 38px; height: 38px; border-radius: 50%; border: none; background: #f0ead8; color: #5a5e6a; font-size: 1.5rem; line-height: 1; cursor: pointer; transition: background .2s ease; }
.fks-modal__close:hover { background: #e6ddc4; }
.fks-modal__eyebrow { margin: 0; color: #8a6d12; font-weight: 700; font-size: .72rem; letter-spacing: .14em; text-transform: uppercase; }
.fks-modal__title { font-size: clamp(1.6rem, 5vw, 2.1rem); font-weight: 600; color: var(--ink); margin: .3rem 0 .35rem; letter-spacing: -.02em; }
.fks-modal__sub { margin: 0 0 1.4rem; color: #5a5e6a; font-size: .94rem; line-height: 1.5; }
.fks-modal__grid { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: 1fr; gap: .65rem; }
@media (min-width: 560px) { .fks-modal__grid { grid-template-columns: 1fr 1fr; } }
.fks-perk { display: flex; align-items: flex-start; gap: .75rem; padding: .8rem; border-radius: 16px; background: #faf6ea; border: 1px solid #efe6cd; opacity: 0; transform: translateY(14px); animation: fksPerkIn .5s cubic-bezier(.2,.7,.2,1) forwards; animation-delay: calc(var(--i) * 55ms + .12s); }
.fks-perk__icon { flex-shrink: 0; display: grid; place-items: center; width: 38px; height: 38px; border-radius: 11px; background: rgba(31,138,91,.12); color: var(--emerald); border: 1px solid rgba(31,138,91,.25); }
.fks-perk__icon svg { width: 20px; height: 20px; }
.fks-perk strong { display: block; color: var(--ink); font-weight: 700; font-size: .92rem; }
.fks-perk em { display: block; font-style: normal; color: #6a6e7a; font-size: .82rem; line-height: 1.4; margin-top: .12rem; }
@keyframes fksPerkIn { to { opacity: 1; transform: none; } }
.fks-modal__cta { margin-top: 1.4rem; width: 100%; padding: 1em; border: none; border-radius: 999px; background: var(--gold); color: #2a1c00; font: inherit; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 14px 30px -12px rgba(242,180,12,.7); transition: transform .2s ease; }
.fks-modal__cta:hover { transform: translateY(-2px); }
.fks-modal-enter-active, .fks-modal-leave-active { transition: opacity .3s ease; }
.fks-modal-enter-active .fks-modal__panel, .fks-modal-leave-active .fks-modal__panel { transition: transform .38s cubic-bezier(.2,.8,.2,1), opacity .3s ease; }
.fks-modal-enter-from, .fks-modal-leave-to { opacity: 0; }
.fks-modal-enter-from .fks-modal__panel, .fks-modal-leave-to .fks-modal__panel { transform: translateY(48px); opacity: .5; }

/* validation error popup */
.fks-emodal { position: fixed; inset: 0; z-index: 300; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(8,16,40,.55); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
.fks-emodal__panel { width: 100%; max-width: 400px; background: #fffdf6; border-radius: 24px; padding: 30px 24px 24px; text-align: center; box-shadow: 0 30px 70px -20px rgba(0,0,0,.55); }
.fks-emodal__icon { width: 60px; height: 60px; margin: 0 auto 1rem; display: grid; place-items: center; border-radius: 50%; background: linear-gradient(135deg, #fff1f4, #ffe0e8); border: 1px solid #f7c4d2; color: #e0285f; animation: fksShake .5s cubic-bezier(.36,.07,.19,.97); }
.fks-emodal__icon svg { width: 32px; height: 32px; }
.fks-emodal__title { font-size: 1.4rem; font-weight: 600; color: var(--ink); margin: 0 0 .35rem; letter-spacing: -.01em; }
.fks-emodal__msg { color: #5a5e6a; font-size: .95rem; line-height: 1.5; margin: 0 0 1.3rem; }
.fks-emodal__list { list-style: none; margin: .5rem 0 1.3rem; padding: 0; text-align: left; display: flex; flex-direction: column; gap: .5rem; max-height: 42vh; overflow-y: auto; }
.fks-emodal__list li { display: flex; align-items: flex-start; gap: .55rem; background: #fdf3f6; border: 1px solid #f6dde4; border-radius: 12px; padding: .7rem .85rem; color: #8a1c43; font-size: .9rem; line-height: 1.4; }
.fks-emodal__list li::before { content: '!'; flex-shrink: 0; display: grid; place-items: center; width: 18px; height: 18px; margin-top: 1px; border-radius: 50%; background: #e0285f; color: #fff; font-size: .72rem; font-weight: 700; }
.fks-emodal__btn { width: 100%; padding: .95em; border: none; border-radius: 999px; background: var(--ink); color: var(--paper); font: inherit; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform .2s ease; }
.fks-emodal__btn:hover { transform: translateY(-2px); }
.fks-emodal-enter-active, .fks-emodal-leave-active { transition: opacity .25s ease; }
.fks-emodal-enter-active .fks-emodal__panel, .fks-emodal-leave-active .fks-emodal__panel { transition: transform .32s cubic-bezier(.2,.9,.3,1.25), opacity .25s ease; }
.fks-emodal-enter-from, .fks-emodal-leave-to { opacity: 0; }
.fks-emodal-enter-from .fks-emodal__panel, .fks-emodal-leave-to .fks-emodal__panel { transform: scale(.9) translateY(12px); opacity: .5; }
@media (prefers-reduced-motion: reduce) { .fks-emodal__icon { animation: none !important; } }

@media (prefers-reduced-motion: reduce) {
    .fks-glow, .fks-emblem, .fks-emblem::before, .fks-emblem::after, .fks-dot, .fks-perk, .fks-ngo {
        animation: none !important; opacity: 1 !important;
    }
}

@media (max-width: 860px) {
    .fks { padding-top: 16px; }
    .fks-shell { grid-template-columns: 1fr; gap: 10px; }
    /* centered, balanced hero on phones */
    .fks-brand { order: -1; padding: 4px 0 0; text-align: center; align-items: center; }
    .fks-eyebrow { justify-content: center; }
    .fks-perks-teaser { width: 100%; display: flex; flex-direction: column; align-items: center; }
    .fks-brand__rings { display: none; }
    /* compact hero so the signup form is visible up top */
    .fks-emblem { width: 52px; height: 52px; margin-bottom: .65rem; }
    .fks-emblem::before { inset: -8px; }
    .fks-emblem::after { inset: -15px; }
    .fks-brand__title { font-size: 1.5rem; line-height: 1.08; margin-top: .55rem; }
    .fks-brand__title br { display: none; }
    .fks-brand__sub { display: none; }
    .fks-perks-teaser { margin: 1.6rem 0 0; }
    .fks-perks-note { margin-top: .5rem; font-size: .8rem; }
}
@media (max-width: 520px) { .fks-grid2 { grid-template-columns: 1fr; } }
</style>
