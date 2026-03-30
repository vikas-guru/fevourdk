<template>
    <AppLayout title="NGO Chat Registration - FEVOURD-K">
        <div class="min-h-screen bg-slate-50 py-4 sm:py-8">
            <div class="mx-auto max-w-3xl px-4">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4 text-white">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-blue-100">{{ ui.headerKicker }}</p>
                                <h1 class="text-xl font-bold">{{ ui.headerTitle }}</h1>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="rounded-xl bg-white/20 px-3 py-1.5 text-xs font-semibold hover:bg-white/30 transition"
                                    @click="toggleAssistantLanguage"
                                >
                                    {{ assistantLanguage === 'kn' ? 'ಕನ್ನಡ' : 'English' }}
                                </button>
                                <button
                                    type="button"
                                    class="rounded-xl bg-white/20 px-3 py-1.5 text-xs font-semibold hover:bg-white/30 transition"
                                    @click="toggleVoice"
                                >
                                    {{ voiceEnabled ? ui.voiceOn : ui.voiceOff }}
                                </button>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-blue-100">
                            {{ ui.headerSubtitle }}
                        </p>
                        <div class="mt-3 w-full">
                            <div class="mb-1 flex items-center justify-between gap-2 text-[11px] font-medium text-blue-100/95">
                                <span>{{ ui.progressLabel }}</span>
                                <span>{{ progressPercent }}%</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-white/15">
                                <div
                                    class="h-full rounded-full bg-white transition-[width] duration-500 ease-out shadow-sm"
                                    :style="{ width: progressPercent + '%' }"
                                    role="progressbar"
                                    :aria-valuenow="progressPercent"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                />
                            </div>
                        </div>
                    </div>

                    <div ref="chatContainer" class="h-[52vh] overflow-y-auto bg-slate-50 p-4 space-y-3">
                        <div
                            v-for="(message, idx) in messages"
                            :key="idx"
                            class="flex"
                            :class="message.type === 'bot' ? 'justify-start' : 'justify-end'"
                        >
                            <div
                                class="max-w-[88%] rounded-2xl px-4 py-3 text-sm shadow-sm"
                                :class="message.type === 'bot' ? 'bg-white border border-slate-200 text-slate-700' : 'bg-blue-600 text-white'"
                                v-html="message.text"
                            />
                        </div>
                    </div>

                    <div class="border-t border-slate-200 p-4">
                        <div class="mb-3 flex flex-wrap items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-[11px] text-slate-500">
                                    {{ ui.stepOf(stepIndex + 1, stepCount) }}
                                    <span v-if="isOptionalTextStep" class="text-slate-400"> · {{ ui.optionalShort }}</span>
                                </p>
                                <p v-if="lastCloudSavedText" class="text-[11px] text-emerald-600">{{ lastCloudSavedText }}</p>
                                <p v-if="cloudSaveError" class="text-[11px] text-rose-600">{{ cloudSaveError }}</p>
                            </div>
                            <button
                                type="button"
                                class="rounded-lg border border-slate-300 px-2.5 py-1 text-[11px] font-semibold text-slate-700 hover:bg-slate-50 shrink-0"
                                @click="showEditPanel = !showEditPanel"
                            >
                                {{ showEditPanel ? ui.closeEdit : ui.editDetails }}
                            </button>
                        </div>

                        <div v-if="showEditPanel" class="mb-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="mb-2 text-xs font-semibold text-slate-700">{{ ui.jumpToField }}</p>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <button
                                    v-for="(s, i) in editableSteps"
                                    :key="`edit-${s.key}`"
                                    type="button"
                                    class="rounded-lg border px-2.5 py-2 text-left text-[11px] font-medium transition hover:opacity-95"
                                    :class="editStepButtonClass(s.key)"
                                    @click="goToStep(i)"
                                >
                                    <span class="flex items-start justify-between gap-2">
                                        <span class="min-w-0 leading-snug">
                                            <span class="text-slate-800">{{ s.label }}</span>
                                            <span v-if="isOptionalStepKey(s.key)" class="mt-0.5 block text-[10px] font-normal text-slate-500">{{ ui.optionalFieldBadge }}</span>
                                        </span>
                                        <span
                                            class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide"
                                            :class="editStepBadgeClass(s.key)"
                                        >{{ editStepBadgeText(s.key) }}</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div v-if="currentStep.key === 'legal_structure'" class="mb-3 grid grid-cols-2 gap-2 sm:grid-cols-4">
                            <button
                                v-for="option in legalStructureOptions"
                                :key="option.value"
                                type="button"
                                class="rounded-xl border px-2.5 py-2 text-xs font-medium transition"
                                :class="draft.legal_structure === option.value ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300 hover:border-blue-300'"
                                @click="selectLegalStructure(option.value)"
                            >
                                {{ option.label }}
                            </button>
                        </div>

                        <div v-if="currentStep.key === 'theme_color'" class="mb-3">
                            <p class="mb-2 text-xs font-semibold text-slate-600">{{ ui.chooseThemeColor }}</p>
                            <div class="grid grid-cols-5 gap-2 sm:grid-cols-10">
                                <button
                                    v-for="color in themePalette"
                                    :key="color"
                                    type="button"
                                    class="h-8 w-full rounded-lg border-2 transition"
                                    :style="{ backgroundColor: color }"
                                    :class="draft.theme_color === color ? 'border-slate-900' : 'border-transparent hover:border-slate-400'"
                                    @click="selectThemeColor(color)"
                                />
                            </div>
                        </div>

                        <div v-if="currentStep.key === 'description'" class="mb-3">
                            <button
                                type="button"
                                class="rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700 transition"
                                @click="autoFillMission"
                            >
                                {{ ui.autoFillMission }}
                            </button>
                        </div>

                        <div v-if="currentStep.key === 'focus_areas'" class="mb-3 grid grid-cols-2 gap-2 sm:grid-cols-3">
                            <button
                                v-for="area in focusAreas"
                                :key="area.value"
                                type="button"
                                class="rounded-xl border px-2.5 py-2 text-xs font-medium transition"
                                :class="selectedFocusAreas.includes(area.value) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300 hover:border-blue-300'"
                                @click="toggleFocusArea(area.value)"
                            >
                                {{ area.label }}
                            </button>
                        </div>

                        <div v-if="currentStep.key === 'address'" class="mb-3">
                            <button
                                type="button"
                                class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition"
                                :disabled="capturingLocation"
                                @click="captureLocation"
                            >
                                {{ capturingLocation ? ui.capturingLocation : ui.useCurrentLocation }}
                            </button>
                        </div>

                        <div v-if="currentStep.key === 'registration_number'" class="mb-3">
                            <button
                                type="button"
                                class="rounded-xl bg-violet-600 px-3 py-2 text-xs font-semibold text-white hover:bg-violet-700 transition"
                                :disabled="verificationLoading || !draft.registration_number"
                                @click="verifyRegistration"
                            >
                                {{ verificationLoading ? ui.verifying : ui.verifyRegistrationNumber }}
                            </button>
                            <div v-if="verificationResult" class="mt-2 rounded-xl border border-violet-200 bg-violet-50 p-3 text-xs text-slate-700">
                                <p class="font-semibold text-violet-700">{{ ui.verifyConfidence }}: {{ verificationResult.confidence }} ({{ verificationResult.score }}/100)</p>
                                <p class="mt-1">{{ verificationResult.note }}</p>
                                <div v-if="verificationResult.checks?.length" class="mt-2">
                                    <p class="font-semibold text-slate-800">{{ ui.checksLabel }}</p>
                                    <ul class="mt-1 space-y-1">
                                        <li v-for="(check, index) in verificationResult.checks" :key="`check-${index}`" class="flex items-center gap-2">
                                            <span
                                                class="inline-block h-2 w-2 rounded-full"
                                                :class="check.status === 'pass' ? 'bg-emerald-500' : (check.status === 'fail' ? 'bg-rose-500' : 'bg-amber-500')"
                                            />
                                            <span>{{ check.name }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div v-if="verificationResult.providers?.length" class="mt-3">
                                    <p class="font-semibold text-slate-800">{{ ui.providersLabel }}</p>
                                    <ul class="mt-1 space-y-1">
                                        <li v-for="(provider, index) in verificationResult.providers" :key="`provider-${index}`">
                                            <span class="font-medium">{{ provider.name }}</span>
                                            - {{ provider.status }}
                                            <span v-if="provider.latency_ms">({{ provider.latency_ms }} ms)</span>
                                            <span class="text-slate-500">: {{ provider.details }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-2">
                                    <a
                                        v-for="link in verificationResult.official_links"
                                        :key="link.url"
                                        :href="link.url"
                                        target="_blank"
                                        class="mr-3 underline text-violet-700"
                                    >{{ link.label }}</a>
                                </div>
                            </div>
                        </div>

                        <div v-if="currentStep.key === 'registration_certificate'" class="mb-3">
                            <label class="mb-1 block text-xs font-semibold text-slate-700">{{ ui.uploadRegCert }}</label>
                            <p class="mb-1 text-[11px] text-slate-500">{{ ui.uploadDocHint }}</p>
                            <input
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs"
                                @change="onRegistrationCertificateChange"
                            >
                            <p v-if="docSelectionError && currentStep.key === 'registration_certificate'" class="mt-1 text-[11px] font-medium text-rose-600">{{ docSelectionError }}</p>
                        </div>

                        <div v-if="currentStep.key === 'pan_card'" class="mb-3">
                            <label class="mb-1 block text-xs font-semibold text-slate-700">{{ ui.uploadPan }}</label>
                            <p class="mb-1 text-[11px] text-slate-500">{{ ui.uploadDocHint }}</p>
                            <input
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs"
                                @change="onPanCardChange"
                            >
                            <p v-if="docSelectionError && currentStep.key === 'pan_card'" class="mt-1 text-[11px] font-medium text-rose-600">{{ docSelectionError }}</p>
                        </div>

                        <div
                            v-if="isOptionalTextStep"
                            class="mb-3 flex flex-wrap items-center justify-between gap-2 rounded-xl border border-dashed border-slate-200 bg-slate-50/90 px-3 py-2.5"
                        >
                            <p class="min-w-0 flex-1 text-[11px] leading-snug text-slate-600">{{ ui.optionalStepHint }}</p>
                            <button
                                type="button"
                                class="shrink-0 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 disabled:opacity-50"
                                :disabled="submitting || otpVerifying"
                                @click="skipOptionalStep"
                            >
                                {{ ui.skipStep }}
                            </button>
                        </div>

                        <div v-if="currentStep.key === 'otp_code'" class="mb-3">
                            <button
                                type="button"
                                class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition"
                                :disabled="sendingOtp || !draft.phone"
                                @click="sendNgoOtp"
                            >
                                {{ sendingOtp ? ui.sendingOtp : ui.sendOtp }}
                            </button>
                            <p class="mt-1 text-[11px] text-slate-500">{{ ui.otpHint }}</p>
                            <p class="mt-1 text-[11px] font-medium text-amber-800/90">{{ ui.otpNextVerifies }}</p>
                            <p v-if="otpVerified" class="mt-1 text-[11px] font-semibold text-emerald-700">{{ ui.otpVerifiedBadge }}</p>
                        </div>

                        <div class="flex gap-2">
                            <input
                                v-model="input"
                                :placeholder="currentStep.placeholder"
                                class="flex-1 rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :disabled="submitting || otpVerifying"
                                @keydown.enter.prevent="handleSubmit"
                            >
                            <button
                                type="button"
                                class="rounded-2xl border border-slate-300 px-3 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-50"
                                :disabled="submitting || !speechRecognitionSupported || listening"
                                @click="startVoiceInput"
                            >
                                <span class="inline-flex items-center gap-1">
                                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4">
                                        <path d="M12 3a3 3 0 0 0-3 3v6a3 3 0 1 0 6 0V6a3 3 0 0 0-3-3Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M19 11a7 7 0 1 1-14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                        <path d="M12 18v3M8.5 21h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                    </svg>
                                    {{ listening ? ui.listening : ui.voice }}
                                </span>
                            </button>
                            <button
                                type="button"
                                class="rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                                :disabled="submitting || otpVerifying"
                                @click="handleSubmit"
                            >
                                {{ isFinalStep ? (submitting ? ui.submitting : ui.register) : ui.next }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <Teleport to="body">
            <Transition name="ngo-chat-loader-t">
                <div
                    v-if="actionLoadingVisible"
                    class="ngo-chat-action-loader"
                    role="status"
                    aria-live="polite"
                    aria-busy="true"
                >
                    <div class="ngo-chat-action-loader__backdrop" aria-hidden="true" />
                    <div class="ngo-chat-action-loader__panel">
                        <img
                            :src="brandLogoUrl"
                            alt=""
                            class="ngo-chat-action-loader__logo"
                            width="120"
                            height="120"
                            decoding="async"
                        >
                        <p class="ngo-chat-action-loader__text">{{ actionLoadingMessage }}</p>
                    </div>
                </div>
            </Transition>
            <Transition name="ngo-chat-err-t">
                <div
                    v-if="errorModalOpen"
                    class="ngo-chat-err-modal"
                    role="alertdialog"
                    aria-modal="true"
                    aria-labelledby="ngo-chat-err-title"
                >
                    <div class="ngo-chat-err-modal__backdrop" aria-hidden="true" @click="closeErrorModal"></div>
                    <div class="ngo-chat-err-modal__panel" @click.stop>
                        <h2 id="ngo-chat-err-title" class="ngo-chat-err-modal__title">{{ ui.errorModalTitle }}</h2>
                        <p class="ngo-chat-err-modal__msg">{{ errorModalMessage }}</p>
                        <button type="button" class="ngo-chat-err-modal__btn" @click="closeErrorModal">{{ ui.errorModalOk }}</button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AppLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { nextTick, onBeforeUnmount, onMounted, reactive, ref, computed, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import brandLogoUrl from '/public/assets/images/fevourd-k/logo.png'

const page = usePage()
const ERROR_SOUND_URL = '/assets/sounds/login-error.wav'

const DRAFT_KEY = 'ngo-chat-registration-draft-v2'
const DRAFT_ID_KEY = 'ngo-chat-registration-draft-id-v2'
const chatContainer = ref(null)
const input = ref('')
const submitting = ref(false)
const verificationLoading = ref(false)
const verificationResult = ref(null)
const capturingLocation = ref(false)
const sendingOtp = ref(false)
const voiceEnabled = ref(true)
const assistantLanguage = ref('kn')
const showEditPanel = ref(false)
const selectedFocusAreas = ref([])
const listening = ref(false)
const recognitionRef = ref(null)
const speechVoices = ref([])
const noSpeechRetryCount = ref(0)
const draftId = ref('')
const syncTimer = ref(null)
const lastCloudSavedAt = ref(null)
const cloudSaveError = ref('')
const errorModalOpen = ref(false)
const errorModalMessage = ref('')
let errorSoundAudio = null
const loadedFromResumeLink = ref(false)
const speechRecognitionSupported = typeof window !== 'undefined' && !!(window.SpeechRecognition || window.webkitSpeechRecognition)
/** After phone + email; blocks Edit jumps and “Next” until /register/ngo-chat/verify-otp succeeds. */
const otpVerified = ref(false)
const otpVerifying = ref(false)
const docSelectionError = ref('')
const ngoChatOtpLength = computed(() => {
    const n = Number(page.props?.ngoChatOtpLength)
    return Number.isFinite(n) && n > 0 ? n : 6
})

const actionLoadingVisible = computed(
    () => sendingOtp.value || otpVerifying.value || submitting.value || verificationLoading.value,
)

const actionLoadingMessage = computed(() => {
    if (submitting.value) {
        return txt('loadingSubmitting')
    }
    if (otpVerifying.value) {
        return txt('loadingVerifyOtp')
    }
    if (sendingOtp.value) {
        return txt('loadingSendOtp')
    }
    if (verificationLoading.value) {
        return txt('loadingVerifyReg')
    }
    return ''
})

const optionalStepKeys = new Set([
    'founder_email',
    'cofounder_name',
    'cofounder_phone',
    'cofounder_email',
    'postal_code',
])

function isStepFieldFilled(key) {
    if (key === 'registration_certificate') {
        return !!files.registration_certificate
    }
    if (key === 'pan_card') {
        return !!files.pan_card
    }
    const v = draft[key]
    if (v === null || v === undefined) {
        return false
    }
    if (typeof v === 'string') {
        return v.trim() !== ''
    }
    return true
}

function isStepCompleteForEdit(key) {
    if (optionalStepKeys.has(key)) {
        if (isStepFieldFilled(key)) {
            return true
        }
        const idx = stepDefs.findIndex((s) => s.key === key)
        return idx >= 0 && stepIndex.value > idx
    }
    return isStepFieldFilled(key)
}

function editStepStatus(key) {
    const idx = stepDefs.findIndex((s) => s.key === key)
    if (idx < 0) {
        return 'pending'
    }
    if (stepIndex.value === idx) {
        return 'current'
    }
    if (stepIndex.value > idx) {
        return isStepCompleteForEdit(key) ? 'complete' : 'pending'
    }
    return 'pending'
}

function editStepButtonClass(key) {
    const st = editStepStatus(key)
    if (st === 'complete') {
        return 'border-emerald-300 bg-emerald-50/90 hover:border-emerald-400'
    }
    if (st === 'current') {
        return 'border-blue-500 bg-blue-50 ring-2 ring-blue-200'
    }
    return 'border-slate-300 bg-white hover:border-blue-300'
}

function editStepBadgeClass(key) {
    const st = editStepStatus(key)
    if (st === 'complete') {
        return 'bg-emerald-600 text-white'
    }
    if (st === 'current') {
        return 'bg-blue-600 text-white'
    }
    return 'bg-slate-200 text-slate-700'
}

function editStepBadgeText(key) {
    const st = editStepStatus(key)
    if (st === 'complete') {
        return ui.value.editBadgeDone
    }
    if (st === 'current') {
        return ui.value.editBadgeCurrent
    }
    return ui.value.editBadgePending
}

const lastCloudSavedText = computed(() => {
    if (!lastCloudSavedAt.value) {
        return ''
    }
    try {
        const d = new Date(lastCloudSavedAt.value)
        const t = d.toLocaleString()
        return assistantLanguage.value === 'kn' ? `ಕ್ಲೌಡ್‌ಗೆ ಉಳಿಸಲಾಗಿದೆ · ${t}` : `Saved to cloud · ${t}`
    } catch {
        return ''
    }
})

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

function showErrorModal(msg) {
    errorModalMessage.value = msg
    errorModalOpen.value = true
    nextTick(playErrorSound)
}

function closeErrorModal() {
    errorModalOpen.value = false
}

function parseJsonResponse(text) {
    if (!text) {
        return {}
    }
    try {
        return JSON.parse(text)
    } catch {
        return {}
    }
}

function buildOtpGatePayload() {
    return {
        passed: !!(otpVerified.value && String(draft.phone || '').trim()),
        phone: otpVerified.value ? String(draft.phone || '').trim() : '',
    }
}

function buildDraftRequestPayload() {
    return {
        draft_id: draftId.value || null,
        draft: { ...draft },
        selected_focus_areas: [...selectedFocusAreas.value],
        step_index: stepIndex.value,
        messages: messages.value.slice(-40),
        otp_gate: buildOtpGatePayload(),
    }
}

const themePalette = ['#2563eb', '#1d4ed8', '#0f766e', '#16a34a', '#ea580c', '#dc2626', '#7c3aed', '#0891b2', '#4338ca', '#be123c']

/** Bilingual registration steps: TTS and chat use `prompt` in the active language so voices match the text. */
const stepDefs = [
    { key: 'ngo_name', en: { label: 'NGO Name', prompt: 'Welcome! Tell me your NGO name.', placeholder: 'Enter NGO name' }, kn: { label: 'ಎನ್‌ಜಿಒ ಹೆಸರು', prompt: 'ಸ್ವಾಗತ! ನಿಮ್ಮ ಎನ್‌ಜಿಒ ಸಂಸ್ಥೆಯ ಹೆಸರನ್ನು ಹೇಳಿ.', placeholder: 'ಎನ್‌ಜಿಒ ಹೆಸರು ನಮೂದಿಸಿ' } },
    { key: 'founder_name', en: { label: 'Founder Name', prompt: 'Please share the founder’s full name.', placeholder: 'Founder full name' }, kn: { label: 'ಸ್ಥಾಪಕರ ಹೆಸರು', prompt: 'ದಯವಿಟ್ಟು ಸ್ಥಾಪಕರ ಪೂರ್ಣ ಹೆಸರನ್ನು ತಿಳಿಸಿ.', placeholder: 'ಸ್ಥಾಪಕರ ಪೂರ್ಣ ಹೆಸರು' } },
    { key: 'founder_phone', en: { label: 'Founder Phone', prompt: 'Founder contact phone number?', placeholder: '+91…' }, kn: { label: 'ಸ್ಥಾಪಕರ ಫೋನ್', prompt: 'ಸ್ಥಾಪಕರ ಸಂಪರ್ಕ ಫೋನ್ ಸಂಖ್ಯೆ ಯಾವುದು?', placeholder: '+91…' } },
    { key: 'founder_email', en: { label: 'Founder Email', prompt: 'Founder email (optional).', placeholder: 'founder@email.com' }, kn: { label: 'ಸ್ಥಾಪಕರ ಇಮೇಲ್', prompt: 'ಸ್ಥಾಪಕರ ಇಮೇಲ್ (ಐಚ್ಛಿಕ).', placeholder: 'founder@email.com' } },
    { key: 'cofounder_name', en: { label: 'Co-Founder Name', prompt: 'Co-founder full name (optional).', placeholder: 'Co-founder full name (optional)' }, kn: { label: 'ಸಹ-ಸ್ಥಾಪಕರ ಹೆಸರು', prompt: 'ಸಹ-ಸ್ಥಾಪಕರ ಪೂರ್ಣ ಹೆಸರು (ಐಚ್ಛಿಕ).', placeholder: 'ಸಹ-ಸ್ಥಾಪಕರ ಹೆಸರು' } },
    { key: 'cofounder_phone', en: { label: 'Co-Founder Phone', prompt: 'Co-founder phone (optional).', placeholder: '+91… (optional)' }, kn: { label: 'ಸಹ-ಸ್ಥಾಪಕರ ಫೋನ್', prompt: 'ಸಹ-ಸ್ಥಾಪಕರ ಫೋನ್ (ಐಚ್ಛಿಕ).', placeholder: '+91…' } },
    { key: 'cofounder_email', en: { label: 'Co-Founder Email', prompt: 'Co-founder email (optional).', placeholder: 'cofounder@email.com (optional)' }, kn: { label: 'ಸಹ-ಸ್ಥಾಪಕರ ಇಮೇಲ್', prompt: 'ಸಹ-ಸ್ಥಾಪಕರ ಇಮೇಲ್ (ಐಚ್ಛಿಕ).', placeholder: 'cofounder@email.com' } },
    { key: 'legal_structure', en: { label: 'Legal Structure', prompt: 'What is your legal structure? Choose Trust, Society, Section 8, or Other below.', placeholder: 'e.g. section8' }, kn: { label: 'ಕಾನೂನು ರಚನೆ', prompt: 'ನಿಮ್ಮ ಕಾನೂನು ರಚನೆ ಯಾವುದು? ಕೆಳಗಿನ ಬಟನ್‌ಗಳಿಂದ ಟ್ರಸ್ಟ್, ಸೊಸೈಟಿ, ಸೆಕ್ಷನ್ 8 ಅಥವಾ ಇತರೆ ಆಯ್ಕೆಮಾಡಿ.', placeholder: 'ಉದಾ. section8' } },
    { key: 'registration_number', en: { label: 'Registration No.', prompt: 'Please share your NGO registration number.', placeholder: 'Registration number' }, kn: { label: 'ನೋಂದಣಿ ಸಂಖ್ಯೆ', prompt: 'ನಿಮ್ಮ ಎನ್‌ಜಿಒ ನೋಂದಣಿ ಸಂಖ್ಯೆಯನ್ನು ನಮೂದಿಸಿ.', placeholder: 'ನೋಂದಣಿ ಸಂಖ್ಯೆ' } },
    { key: 'pan', en: { label: 'PAN', prompt: 'Enter the NGO PAN number (required).', placeholder: 'PAN number' }, kn: { label: 'ಪ್ಯಾನ್', prompt: 'ಎನ್‌ಜಿಒ ಪ್ಯಾನ್ ಸಂಖ್ಯೆಯನ್ನು ನಮೂದಿಸಿ (ಅಗತ್ಯ).', placeholder: 'ಪ್ಯಾನ್ ಸಂಖ್ಯೆ' } },
    { key: 'email', en: { label: 'Email', prompt: 'Official NGO email address?', placeholder: 'ngo@email.com' }, kn: { label: 'ಇಮೇಲ್', prompt: 'ಅಧಿಕೃತ ಎನ್‌ಜಿಒ ಇಮೇಲ್ ವಿಳಾಸ ಯಾವುದು?', placeholder: 'ngo@email.com' } },
    { key: 'phone', en: { label: 'Phone', prompt: 'Official contact phone number?', placeholder: '+91…' }, kn: { label: 'ಫೋನ್', prompt: 'ಅಧಿಕೃತ ಸಂಪರ್ಕ ಫೋನ್ ಸಂಖ್ಯೆ ಯಾವುದು?', placeholder: '+91…' } },
    { key: 'otp_code', en: { label: 'OTP', prompt: 'Send OTP, then enter the code. Next verifies it — you cannot continue until it is correct.', placeholder: 'Enter OTP' }, kn: { label: 'ಒಟಿಪಿ', prompt: 'ಒಟಿಪಿ ಕಳುಹಿಸಿ, ನಂತರ ಕೋಡ್ ನಮೂದಿಸಿ. ಮುಂದೆ ಒತ್ತಿದಾಗ ಪರಿಶೀಲಿಸಲಾಗುತ್ತದೆ.', placeholder: 'ಒಟಿಪಿ ನಮೂದಿಸಿ' } },
    { key: 'address', en: { label: 'Address', prompt: 'Enter the NGO address, or tap “Use current location”.', placeholder: 'Address' }, kn: { label: 'ವಿಳಾಸ', prompt: 'ಎನ್‌ಜಿಒ ವಿಳಾಸ ನಮೂದಿಸಿ, ಅಥವಾ “ಪ್ರಸ್ತುತ ಸ್ಥಳ” ಬಟನ್ ಒತ್ತಿ.', placeholder: 'ವಿಳಾಸ' } },
    { key: 'city', en: { label: 'City', prompt: 'City name?', placeholder: 'City' }, kn: { label: 'ನಗರ', prompt: 'ನಗರದ ಹೆಸರು ಯಾವುದು?', placeholder: 'ನಗರ' } },
    { key: 'state_name', en: { label: 'State', prompt: 'State name?', placeholder: 'State' }, kn: { label: 'ರಾಜ್ಯ', prompt: 'ರಾಜ್ಯದ ಹೆಸರು ಯಾವುದು?', placeholder: 'ರಾಜ್ಯ' } },
    { key: 'postal_code', en: { label: 'Postal Code', prompt: 'Postal code (optional).', placeholder: 'Postal code' }, kn: { label: 'ಅಂಚೆ ಕೋಡ್', prompt: 'ಅಂಚೆ ಕೋಡ್ (ಐಚ್ಛಿಕ).', placeholder: 'ಅಂಚೆ ಕೋಡ್' } },
    { key: 'theme_color', en: { label: 'Theme Color', prompt: 'Pick your NGO theme colour from the palette below.', placeholder: 'Choose from palette' }, kn: { label: 'ಥೀಮ್ ಬಣ್ಣ', prompt: 'ಕೆಳಗಿನ ಪಟ್ಟಿಯಿಂದ ನಿಮ್ಮ ಎನ್‌ಜಿಒ ಥೀಮ್ ಬಣ್ಣ ಆಯ್ಕೆಮಾಡಿ.', placeholder: 'ಪಟ್ಟಿಯಿಂದ ಆಯ್ಕೆ' } },
    { key: 'description', en: { label: 'Mission', prompt: 'Describe your NGO mission in one or two lines.', placeholder: 'Mission / description' }, kn: { label: 'ಧ್ಯೇಯ', prompt: 'ನಿಮ್ಮ ಎನ್‌ಜಿಒ ಧ್ಯೇಯವನ್ನು ಒಂದು ಅಥವಾ ಎರಡು ಸಾಲುಗಳಲ್ಲಿ ವಿವರಿಸಿ.', placeholder: 'ಧ್ಯೇಯ / ವಿವರಣೆ' } },
    { key: 'registration_certificate', en: { label: 'Reg. certificate', prompt: 'Upload registration certificate (PDF, JPG or PNG — max 5 MB).', placeholder: 'Upload registration certificate' }, kn: { label: 'ನೋಂದಣಿ ಪ್ರಮಾಣಪತ್ರ', prompt: 'ನೋಂದಣಿ ಪ್ರಮಾಣಪತ್ರ ಅಪ್‌ಲೋಡ್ (PDF, JPG ಅಥವಾ PNG — ಗರಿಷ್ಠ 5 MB).', placeholder: 'ಪ್ರಮಾಣಪತ್ರ ಅಪ್‌ಲೋಡ್' } },
    { key: 'pan_card', en: { label: 'PAN document', prompt: 'Upload PAN document (PDF, JPG or PNG — max 5 MB).', placeholder: 'Upload PAN document' }, kn: { label: 'ಪ್ಯಾನ್ ದಾಖಲೆ', prompt: 'ಪ್ಯಾನ್ ದಾಖಲೆ ಅಪ್‌ಲೋಡ್ (PDF, JPG ಅಥವಾ PNG — ಗರಿಷ್ಠ 5 MB).', placeholder: 'ಪ್ಯಾನ್ ದಾಖಲೆ ಅಪ್‌ಲೋಡ್' } },
    { key: 'login_pin', en: { label: 'Login PIN', prompt: 'Create an NGO login PIN (at least 5 digits).', placeholder: 'Create NGO PIN' }, kn: { label: 'ಲಾಗಿನ್ ಪಿನ್', prompt: 'ಎನ್‌ಜಿಒ ಲಾಗಿನ್ ಪಿನ್ ರಚಿಸಿ (ಕನಿಷ್ಠ 5 ಅಂಕೆಗಳು).', placeholder: 'ಲಾಗಿನ್ ಪಿನ್' } },
    { key: 'login_pin_confirmation', en: { label: 'Confirm PIN', prompt: 'Confirm your NGO login PIN.', placeholder: 'Confirm NGO PIN' }, kn: { label: 'ಪಿನ್ ದೃಢೀಕರಣ', prompt: 'ಲಾಗಿನ್ ಪಿನ್ ಅನ್ನು ಮತ್ತೊಮ್ಮೆ ನಮೂದಿಸಿ.', placeholder: 'ಪಿನ್ ದೃಢೀಕರಣ' } },
    { key: 'focus_areas', en: { label: 'Focus areas', prompt: 'Select focus areas, then press Register.', placeholder: 'Select focus areas above' }, kn: { label: 'ಕೇಂದ್ರೀಕೃತ ಕ್ಷೇತ್ರಗಳು', prompt: 'ಕ್ಷೇತ್ರಗಳನ್ನು ಆಯ್ಕೆ ಮಾಡಿ, ನಂತರ ನೋಂದಣಿ ಒತ್ತಿ.', placeholder: 'ಮೇಲೆ ಕ್ಷೇತ್ರಗಳನ್ನು ಆಯ್ಕೆಮಾಡಿ' } },
]

const stepCount = stepDefs.length
const OTP_STEP_INDEX = stepDefs.findIndex((s) => s.key === 'otp_code')

const localizedSteps = computed(() => {
    const lang = assistantLanguage.value === 'kn' ? 'kn' : 'en'
    return stepDefs.map((s) => ({
        key: s.key,
        label: s[lang].label,
        prompt: s[lang].prompt,
        placeholder: s[lang].placeholder,
    }))
})

const legalStructureOptions = computed(() => {
    const lang = assistantLanguage.value === 'kn' ? 'kn' : 'en'
    if (lang === 'kn') {
        return [
            { value: 'trust', label: 'ಟ್ರಸ್ಟ್' },
            { value: 'society', label: 'ಸೊಸೈಟಿ' },
            { value: 'section8', label: 'ಸೆಕ್ಷನ್ 8' },
            { value: 'other', label: 'ಇತರೆ' },
        ]
    }
    return [
        { value: 'trust', label: 'Trust' },
        { value: 'society', label: 'Society' },
        { value: 'section8', label: 'Section 8' },
        { value: 'other', label: 'Other' },
    ]
})

const focusAreaDefs = [
    { en: 'Education', kn: 'ಶಿಕ್ಷಣ' },
    { en: 'Healthcare', kn: 'ಆರೋಗ್ಯ' },
    { en: 'Women Empowerment', kn: 'ಮಹಿಳಾ ಸಬಲೀಕರಣ' },
    { en: 'Child Welfare', kn: 'ಮಕ್ಕಳ ಕಲ್ಯಾಣ' },
    { en: 'Environment', kn: 'ಪರಿಸರ' },
    { en: 'Rural Development', kn: 'ಗ್ರಾಮೀಣ ಅಭಿವೃದ್ಧಿ' },
]

/** Labels are translated; values stay English for API/storage. */
const focusAreas = computed(() => {
    const lang = assistantLanguage.value === 'kn' ? 'kn' : 'en'
    return focusAreaDefs.map((f) => ({ value: f.en, label: f[lang] }))
})

const stepIndex = ref(0)
const currentStep = computed(() => localizedSteps.value[stepIndex.value])
const isFinalStep = computed(() => stepIndex.value === stepDefs.length - 1)

const isOptionalTextStep = computed(() => optionalStepKeys.has(currentStep.value?.key || ''))

function isOptionalStepKey(key) {
    return optionalStepKeys.has(key)
}

/** Step-based progress: step 1 of N ≈ 1/N … last step 100%. */
const progressPercent = computed(() => {
    if (stepCount <= 0) {
        return 100
    }
    return Math.min(100, Math.round(((stepIndex.value + 1) / stepCount) * 100))
})

/**
 * Keep the text field in sync with `draft` for the current step (fixes empty input after Edit → Next).
 */
function syncInputFromDraftForCurrentStep() {
    const key = currentStep.value?.key
    if (!key) {
        return
    }
    if (['registration_certificate', 'pan_card', 'focus_areas'].includes(key)) {
        input.value = ''
        return
    }
    if (key === 'theme_color') {
        input.value = draft.theme_color || ''
        return
    }
    const raw = draft[key]
    if (raw === null || raw === undefined) {
        input.value = ''
        return
    }
    input.value = String(raw)
}
const editableSteps = computed(() => localizedSteps.value.filter((s) => s.key !== 'focus_areas'))

function formatTxt(key, vars = {}) {
    let s = txt(key)
    Object.entries(vars).forEach(([k, v]) => {
        s = s.split(`{${k}}`).join(String(v))
    })
    return s
}

function txt(key) {
    const lang = assistantLanguage.value === 'kn' ? 'kn' : 'en'
    const table = STR[lang]
    return table[key] ?? STR.en[key]
}

const ui = computed(() => ({
    headerKicker: txt('headerKicker'),
    headerTitle: txt('headerTitle'),
    headerSubtitle: txt('headerSubtitle'),
    voiceOn: txt('voiceOn'),
    voiceOff: txt('voiceOff'),
    editDetails: txt('editDetails'),
    closeEdit: txt('closeEdit'),
    jumpToField: txt('jumpToField'),
    chooseThemeColor: txt('chooseThemeColor'),
    autoFillMission: txt('autoFillMission'),
    useCurrentLocation: txt('useCurrentLocation'),
    capturingLocation: txt('capturingLocation'),
    verifyRegistrationNumber: txt('verifyRegistrationNumber'),
    verifying: txt('verifying'),
    uploadRegCert: txt('uploadRegCert'),
    uploadPan: txt('uploadPan'),
    sendOtp: txt('sendOtp'),
    sendingOtp: txt('sendingOtp'),
    otpHint: txt('otpHint'),
    otpNextVerifies: txt('otpNextVerifies'),
    otpVerifiedBadge: txt('otpVerifiedBadge'),
    uploadDocHint: txt('uploadDocHint'),
    voice: txt('voice'),
    listening: txt('listening'),
    next: txt('next'),
    register: txt('register'),
    submitting: txt('submitting'),
    stepOf: (cur, tot) => (assistantLanguage.value === 'kn' ? `ಹಂತ ${cur} / ${tot}` : `Step ${cur} of ${tot}`),
    progressLabel: txt('progressLabel'),
    verifyConfidence: txt('verifyConfidence'),
    checksLabel: txt('checksLabel'),
    providersLabel: txt('providersLabel'),
    errorModalTitle: txt('errorModalTitle'),
    errorModalOk: txt('errorModalOk'),
    editBadgeDone: txt('editBadgeDone'),
    editBadgeCurrent: txt('editBadgeCurrent'),
    editBadgePending: txt('editBadgePending'),
    skipStep: txt('skipStep'),
    optionalStepHint: txt('optionalStepHint'),
    optionalShort: txt('optionalShort'),
    optionalFieldBadge: txt('optionalFieldBadge'),
}))

const STR = {
    en: {
        headerKicker: 'Smart NGO onboarding',
        headerTitle: 'Chat registration assistant',
        headerSubtitle: 'Simple 5-minute flow with location capture and basic verification.',
        progressLabel: 'Registration progress',
        voiceOn: 'Voice on',
        voiceOff: 'Voice off',
        editDetails: 'Edit details',
        closeEdit: 'Close edit',
        jumpToField: 'Jump to any field',
        chooseThemeColor: 'Choose a theme colour',
        autoFillMission: 'Auto-fill mission draft',
        useCurrentLocation: 'Use current NGO location',
        capturingLocation: 'Capturing…',
        verifyRegistrationNumber: 'Verify registration number',
        verifying: 'Verifying…',
        uploadRegCert: 'Upload registration certificate',
        uploadPan: 'Upload PAN document',
        sendOtp: 'Send OTP to phone + email',
        sendingOtp: 'Sending OTP…',
        otpHint: 'We will send OTP to your official NGO phone and email.',
        otpNextVerifies: 'Tap Next to verify the code. Wrong codes stay on this step.',
        otpVerifiedBadge: 'Phone verified — you can continue.',
        uploadDocHint: 'PDF, JPG or PNG only. Maximum file size 5 MB.',
        voice: 'Voice',
        listening: 'Listening…',
        next: 'Next',
        register: 'Register',
        submitting: 'Submitting…',
        introHi: 'Hi! I will help you complete NGO registration quickly and clearly.',
        languageToKn: 'Kannada assistant is on. You can speak or type in Kannada.',
        languageToEn: 'English assistant is on. You can continue in English.',
        voiceInputOn: 'Voice input is on. You can speak in Kannada or English.',
        voiceInputOff: 'Voice input is not supported on this device.',
        forThisStepUseUi: 'For this step, please use the buttons or upload area on screen.',
        compressing: 'Image is {size}. Compressing to fit upload limit…',
        compressedOk: 'Compressed successfully: {from} → {to}.',
        fileTooLarge: 'File too large ({size}). Maximum upload size is 5 MB.',
        fileTypeNotAllowed: 'Please choose a PDF, JPG, or PNG file.',
        completeOtpFirst: 'Verify your OTP on the OTP step before continuing.',
        otpWrongLen: 'Enter the full {len}-digit OTP.',
        otpVerifiedOk: 'OTP verified. Continuing…',
        selectFocus: 'Please select at least one focus area.',
        uploadRegContinue: 'Please upload the registration certificate to continue.',
        uploadPanContinue: 'Please upload the PAN document to continue.',
        enterValid: 'Please enter a valid value to continue.',
        pickPaletteColour: 'Please choose a colour from the palette.',
        pinShort: 'Login PIN must be at least 5 digits or characters.',
        pinMismatch: 'PIN confirmation does not match. Please enter again.',
        editing: 'Editing: {label}',
        errPhone: 'Please enter a valid phone number (at least 10 digits).',
        errEmail: 'Please enter a valid email.',
        errPan: 'PAN must be 10 characters like ABCDE1234F.',
        errNgoEmail: 'Please enter a valid NGO email.',
        errRegNo: 'Registration number should be at least 6 characters.',
        draftRestored: 'Draft restored. Continue where you left off.',
        locationUnsupported: 'Location is not supported on this device.',
        locationCaptured: 'Location captured and address filled. You can edit city, state, or postal code in the next steps.',
        locationPartial: 'Location captured, but address lookup failed. You can enter the address manually.',
        locationDenied: 'Could not capture location. Please enter the address manually.',
        needPhoneOtp: 'Please provide the official phone number before requesting OTP.',
        otpFail: 'Failed to send OTP. Please try again.',
        otpUnavailable: 'OTP service unavailable. Please try again shortly.',
        otpSentOk: 'OTP sent successfully.',
        verifyDone: 'Verification finished with <strong>{conf}</strong> confidence in ~{ms} ms.',
        verifyUnavailable: 'Verification is unavailable. You can continue registration.',
        regFail: 'Registration failed. Please check details and try again.',
        regSuccess: 'Registration submitted! Your NGO is in the verification queue. Redirecting to login…',
        workspace: 'Creating your NGO workspace…',
        website: 'Preparing your website experience…',
        dashboard: 'Finalising dashboard setup…',
        submitRetry: 'Unable to submit now. Please try again shortly.',
        phoneBeforeOtp: 'Please provide official phone number before requesting OTP.',
        verifyConfidence: 'Verification confidence',
        checksLabel: 'Checks',
        providersLabel: 'Real-time providers',
        errorModalTitle: 'Something went wrong',
        errorModalOk: 'OK',
        editBadgeDone: 'Done',
        editBadgeCurrent: 'Now',
        editBadgePending: 'Pending',
        skipStep: 'Skip',
        optionalStepHint: 'This field is optional. Use Skip, or leave it empty and press Next.',
        optionalShort: 'optional',
        optionalFieldBadge: 'Optional — can skip',
        skippedMarker: '(skipped)',
        skippedOptional: 'Skipped {label}. Moving on.',
        loadingSendOtp: 'Sending OTP and email…',
        loadingVerifyOtp: 'Verifying your code…',
        loadingSubmitting: 'Registering your NGO and sending login details…',
        loadingVerifyReg: 'Checking registration number…',
        cloudSavedAt: 'Saved to cloud · {time}',
        cloudSaveFailed: 'Could not sync to cloud (still saved on this device).',
        draftResumeLoaded: 'Your saved registration was loaded. Continue below.',
        draftResumeInvalid: 'This resume link is invalid or expired. Starting a fresh form.',
        errorNetwork: 'Cannot reach the server. Check that Laravel is running (e.g. php artisan serve) and your connection.',
        errorServer: 'The server returned an error. Please try again.',
    },
    kn: {
        headerKicker: 'ಬುದ್ಧಿವಂತ ಎನ್‌ಜಿಒ ಆರಂಭ',
        headerTitle: 'ಚಾಟ್ ಮೂಲಕ ನೋಂದಣಿ ಸಹಾಯಕ',
        headerSubtitle: 'ಸ್ಥಳ ಸೆರೆ ಮತ್ತು ಮೂಲಭೂತ ಪರಿಶೀಲನೆಯೊಂದಿಗೆ ಸರಳ ಸುಮಾರು 5 ನಿಮಿಷದ ಹಂತಗಳು.',
        progressLabel: 'ನೋಂದಣಿ ಪ್ರಗತಿ',
        voiceOn: 'ಧ್ವನಿ ಆನ್',
        voiceOff: 'ಧ್ವನಿ ಆಫ್',
        editDetails: 'ವಿವರಗಳನ್ನು ತಿದ್ದಿ',
        closeEdit: 'ತಿದ್ದು ಮುಚ್ಚಿ',
        jumpToField: 'ಯಾವುದೇ ಕ್ಷೇತ್ರಕ್ಕೆ ಹೋಗಿ',
        chooseThemeColor: 'ಥೀಮ್ ಬಣ್ಣ ಆಯ್ಕೆಮಾಡಿ',
        autoFillMission: 'ಧ್ಯೇಯವನ್ನು ಸ್ವಯಂ ಭರ್ತಿ',
        useCurrentLocation: 'ಪ್ರಸ್ತುತ ಸ್ಥಳ ಬಳಸಿ',
        capturingLocation: 'ಸೆರೆಹಿಡಿಯಲಾಗುತ್ತಿದೆ…',
        verifyRegistrationNumber: 'ನೋಂದಣಿ ಸಂಖ್ಯೆ ಪರಿಶೀಲಿಸಿ',
        verifying: 'ಪರಿಶೀಲಿಸಲಾಗುತ್ತಿದೆ…',
        uploadRegCert: 'ನೋಂದಣಿ ಪ್ರಮಾಣಪತ್ರ ಅಪ್‌ಲೋಡ್',
        uploadPan: 'ಪ್ಯಾನ್ ದಾಖಲೆ ಅಪ್‌ಲೋಡ್',
        sendOtp: 'ಫೋನ್ + ಇಮೇಲ್‌ಗೆ ಒಟಿಪಿ ಕಳುಹಿಸಿ',
        sendingOtp: 'ಒಟಿಪಿ ಕಳುಹಿಸಲಾಗುತ್ತಿದೆ…',
        otpHint: 'ಅಧಿಕೃತ ಎನ್‌ಜಿಒ ಫೋನ್ ಮತ್ತು ಇಮೇಲ್‌ಗೆ ಒಟಿಪಿ ಕಳುಹಿಸುತ್ತೇವೆ.',
        otpNextVerifies: 'ಪರಿಶೀಲಿಸಲು ಮುಂದೆ ಒತ್ತಿ. ತಪ್ಪಾದ ಕೋಡ್‌ಗೆ ಈ ಹಂತದಲ್ಲೇ ಉಳಿಯಿರಿ.',
        otpVerifiedBadge: 'ಫೋನ್ ಪರಿಶೀಲಿತ — ಮುಂದುವರಿಯಬಹುದು.',
        uploadDocHint: 'PDF, JPG ಅಥವಾ PNG ಮಾತ್ರ. ಗರಿಷ್ಠ 5 MB.',
        voice: 'ಧ್ವನಿ',
        listening: 'ಕೇಳಲಾಗುತ್ತಿದೆ…',
        next: 'ಮುಂದೆ',
        register: 'ನೋಂದಣಿ',
        submitting: 'ಸಲ್ಲಿಸಲಾಗುತ್ತಿದೆ…',
        introHi: 'ನಮಸ್ಕಾರ! ಎನ್‌ಜಿಒ ನೋಂದಣಿಯನ್ನು ವೇಗವಾಗಿ ಮತ್ತು ಸ್ಪಷ್ಟವಾಗಿ ಪೂರ್ಣಗೊಳಿಸಲು ನಾನು ಸಹಾಯ ಮಾಡುತ್ತೇನೆ.',
        languageToKn: 'ಕನ್ನಡ ಸಹಾಯಕ ಸಕ್ರಿಯ. ನೀವು ಕನ್ನಡದಲ್ಲಿ ಮಾತನಾಡಬಹುದು ಅಥವಾ ಟೈಪ್ ಮಾಡಬಹುದು.',
        languageToEn: 'ಇಂಗ್ಲಿಷ್ ಸಹಾಯಕ ಸಕ್ರಿಯ. ಇಂಗ್ಲಿಷ್‌ನಲ್ಲಿ ಮುಂದುವರಿಯಬಹುದು.',
        voiceInputOn: 'ಧ್ವನಿ ಇನ್‌ಪುಟ್ ಸಕ್ರಿಯ. ಕನ್ನಡ ಅಥವಾ ಇಂಗ್ಲಿಷ್‌ನಲ್ಲಿ ಮಾತನಾಡಬಹುದು.',
        voiceInputOff: 'ಈ ಸಾಧನದಲ್ಲಿ ಧ್ವನಿ ಇನ್‌ಪುಟ್ ಬೆಂಬಲಿತವಾಗಿಲ್ಲ.',
        forThisStepUseUi: 'ಈ ಹಂತಕ್ಕೆ ಪರದೆಯಲ್ಲಿನ ಬಟನ್‌ಗಳು ಅಥವಾ ಅಪ್‌ಲೋಡ್ ಪ್ರದೇಶ ಬಳಸಿ.',
        compressing: 'ಚಿತ್ರದ ಗಾತ್ರ {size}. ಅಪ್‌ಲೋಡ್ ಮಿತಿಗೆ ಸರಿಹೊಂದಿಸಲು ಸಂಕುಚಿಸಲಾಗುತ್ತಿದೆ…',
        compressedOk: 'ಯಶಸ್ವಿಯಾಗಿ ಸಂಕುಚಿಸಲಾಗಿದೆ: {from} → {to}.',
        fileTooLarge: 'ಫೈಲ್ ತುಂಬಾ ದೊಡ್ಡದು ({size}). ಗರಿಷ್ಠ 5 MB.',
        fileTypeNotAllowed: 'ದಯವಿಟ್ಟು PDF, JPG ಅಥವಾ PNG ಫೈಲ್ ಆಯ್ಕೆಮಾಡಿ.',
        completeOtpFirst: 'ಮುಂದುವರಿಯುವ ಮೊದಲು ಒಟಿಪಿ ಹಂತದಲ್ಲಿ ಕೋಡ್ ಪರಿಶೀಲಿಸಿ.',
        otpWrongLen: 'ಸಂಪೂರ್ಣ {len} ಅಂಕಿಯ ಒಟಿಪಿ ನಮೂದಿಸಿ.',
        otpVerifiedOk: 'ಒಟಿಪಿ ಪರಿಶೀಲಿತ. ಮುಂದುವರಿಸಲಾಗುತ್ತಿದೆ…',
        selectFocus: 'ಕನಿಷ್ಠ ಒಂದು ಕ್ಷೇತ್ರವನ್ನು ಆಯ್ಕೆಮಾಡಿ.',
        uploadRegContinue: 'ಮುಂದುವರಿಯಲು ನೋಂದಣಿ ಪ್ರಮಾಣಪತ್ರ ಅಪ್‌ಲೋಡ್ ಮಾಡಿ.',
        uploadPanContinue: 'ಮುಂದುವರಿಯಲು ಪ್ಯಾನ್ ದಾಖಲೆ ಅಪ್‌ಲೋಡ್ ಮಾಡಿ.',
        enterValid: 'ಮುಂದುವರಿಸಲು ಸರಿಯಾದ ಮೌಲ್ಯ ನಮೂದಿಸಿ.',
        pickPaletteColour: 'ಪಟ್ಟಿಯಿಂದ ಬಣ್ಣ ಆಯ್ಕೆಮಾಡಿ.',
        pinShort: 'ಲಾಗಿನ್ ಪಿನ್ ಕನಿಷ್ಠ 5 ಅಂಕೆಗಳು ಅಥವಾ ಅಕ್ಷರಗಳಾಗಿರಬೇಕು.',
        pinMismatch: 'ಪಿನ್ ಹೊಂದಾಣಿಕೆಯಾಗುತ್ತಿಲ್ಲ. ಮತ್ತೆ ನಮೂದಿಸಿ.',
        editing: 'ತಿದ್ದು: {label}',
        errPhone: 'ಸರಿಯಾದ ಫೋನ್ ಸಂಖ್ಯೆ ನಮೂದಿಸಿ (ಕನಿಷ್ಠ 10 ಅಂಕೆಗಳು).',
        errEmail: 'ಸರಿಯಾದ ಇಮೇಲ್ ನಮೂದಿಸಿ.',
        errPan: 'ಪ್ಯಾನ್ 10 ಅಕ್ಷರಗಳಾಗಿರಬೇಕು, ಉದಾ. ABCDE1234F.',
        errNgoEmail: 'ಸರಿಯಾದ ಎನ್‌ಜಿಒ ಇಮೇಲ್ ನಮೂದಿಸಿ.',
        errRegNo: 'ನೋಂದಣಿ ಸಂಖ್ಯೆ ಕನಿಷ್ಠ 6 ಅಕ್ಷರಗಳಾಗಿರಬೇಕು.',
        draftRestored: 'ಡ್ರಾಫ್ಟ್ ಮರುಸ್ಥಾಪಿಸಲಾಗಿದೆ. ನಿಲ್ಲಿಸಿದ ಕಡೆಯಿಂದ ಮುಂದುವರಿಸಿ.',
        locationUnsupported: 'ಈ ಸಾಧನದಲ್ಲಿ ಸ್ಥಳ ಬೆಂಬಲಿತವಾಗಿಲ್ಲ.',
        locationCaptured: 'ಸ್ಥಳ ಸೆರೆಹಿಡಿಯಲಾಗಿದೆ ಮತ್ತು ವಿಳಾಸ ಭರ್ತಿಯಾಗಿದೆ. ಮುಂದಿನ ಹಂತಗಳಲ್ಲಿ ನಗರ, ರಾಜ್ಯ ಅಥವಾ ಅಂಚೆ ಕೋಡ್ ತಿದ್ದಬಹುದು.',
        locationPartial: 'ಸ್ಥಳ ಸೆರೆಹಿಡಿಯಲಾಗಿದೆ, ಆದರೆ ವಿಳಾಸ ಹುಡುಕಾಟ ವಿಫಲ. ಕೈಯಾರೆ ವಿಳಾಸ ನಮೂದಿಸಿ.',
        locationDenied: 'ಸ್ಥಳ ಸೆರೆಹಿಡಿಯಲಾಗಲಿಲ್ಲ. ವಿಳಾಸ ಕೈಯಾರೆ ನಮೂದಿಸಿ.',
        needPhoneOtp: 'ಒಟಿಪಿ ಕೇಳುವ ಮೊದಲು ಅಧಿಕೃತ ಫೋನ್ ಸಂಖ್ಯೆ ನೀಡಿ.',
        otpFail: 'ಒಟಿಪಿ ಕಳುಹಿಸಲು ವಿಫಲ. ಮತ್ತೆ ಪ್ರಯತ್ನಿಸಿ.',
        otpUnavailable: 'ಒಟಿಪಿ ಸೇವೆ ಲಭ್ಯವಿಲ್ಲ. ಸ್ವಲ್ಪ ಹೊತ್ತಿನಲ್ಲಿ ಪ್ರಯತ್ನಿಸಿ.',
        otpSentOk: 'ಒಟಿಪಿ ಯಶಸ್ವಿಯಾಗಿ ಕಳುಹಿಸಲಾಗಿದೆ.',
        verifyDone: 'ಪರಿಶೀಲನೆ ಪೂರ್ಣ — <strong>{conf}</strong> ನಂಬಿಕೆ, ಸುಮಾರು {ms} ms.',
        verifyUnavailable: 'ಪರಿಶೀಲನೆ ಲಭ್ಯವಿಲ್ಲ. ನೋಂದಣಿ ಮುಂದುವರಿಸಬಹುದು.',
        regFail: 'ನೋಂದಣಿ ವಿಫಲ. ವಿವರಗಳನ್ನು ಪರಿಶೀಲಿಸಿ ಮತ್ತು ಮತ್ತೆ ಪ್ರಯತ್ನಿಸಿ.',
        regSuccess: 'ನೋಂದಣಿ ಸಲ್ಲಿಸಲಾಗಿದೆ! ನಿಮ್ಮ ಎನ್‌ಜಿಒ ಪರಿಶೀಲನಾ ಕತಾರದಲ್ಲಿದೆ. ಲಾಗಿನ್‌ಗೆ ಮರುನಿರ್ದೇಶಿಸಲಾಗುತ್ತಿದೆ…',
        workspace: 'ನಿಮ್ಮ ಎನ್‌ಜಿಒ ಕಾರ್ಯಸ್ಥಳವನ್ನು ರಚಿಸಲಾಗುತ್ತಿದೆ…',
        website: 'ವೆಬ್ ಅನುಭವವನ್ನು ಸಿದ್ಧಪಡಿಸಲಾಗುತ್ತಿದೆ…',
        dashboard: 'ಡ್ಯಾಶ್‌ಬೋರ್ಡ್ ಸಿದ್ಧಪಡಿಸಲಾಗುತ್ತಿದೆ…',
        submitRetry: 'ಈಗ ಸಲ್ಲಿಸಲು ಸಾಧ್ಯವಿಲ್ಲ. ಸ್ವಲ್ಪ ಹೊತ್ತಿನಲ್ಲಿ ಪ್ರಯತ್ನಿಸಿ.',
        phoneBeforeOtp: 'ಒಟಿಪಿ ಮೊದಲು ಅಧಿಕೃತ ಫೋನ್ ಸಂಖ್ಯೆ ನೀಡಿ.',
        verifyConfidence: 'ಪರಿಶೀಲನೆ ನಂಬಿಕೆ',
        checksLabel: 'ಪರಿಶೀಲನೆಗಳು',
        providersLabel: 'ಲೈವ್ ಪೂರೈಕೆದಾರರು',
        errorModalTitle: 'ಯಾವುದೋ ತಪ್ಪಾಗಿದೆ',
        errorModalOk: 'ಸರಿ',
        editBadgeDone: 'ಪೂರ್ಣ',
        editBadgeCurrent: 'ಈಗ',
        editBadgePending: 'ಬಾಕಿ',
        skipStep: 'ಬಿಟ್ಟುಬಿಡಿ',
        optionalStepHint: 'ಈ ಕ್ಷೇತ್ರ ಐಚ್ಛಿಕ. “ಬಿಟ್ಟುಬಿಡಿ” ಒತ್ತಿ, ಅಥವಾ ಖಾಲಿ ಬಿಟ್ಟು ಮುಂದೆ ಒತ್ತಿ.',
        optionalShort: 'ಐಚ್ಛಿಕ',
        optionalFieldBadge: 'ಐಚ್ಛಿಕ — ಬಿಟ್ಟುಬಿಡಬಹುದು',
        skippedMarker: '(ಬಿಟ್ಟುಬಿಡಲಾಗಿದೆ)',
        skippedOptional: '{label} ಬಿಟ್ಟುಬಿಡಲಾಗಿದೆ. ಮುಂದುವರಿಸಲಾಗುತ್ತಿದೆ.',
        loadingSendOtp: 'ಒಟಿಪಿ ಮತ್ತು ಇಮೇಲ್ ಕಳುಹಿಸಲಾಗುತ್ತಿದೆ…',
        loadingVerifyOtp: 'ಕೋಡ್ ಪರಿಶೀಲಿಸಲಾಗುತ್ತಿದೆ…',
        loadingSubmitting: 'ನೋಂದಣಿ ಮತ್ತು ಲಾಗಿನ್ ವಿವರಗಳ ಇಮೇಲ್…',
        loadingVerifyReg: 'ನೋಂದಣಿ ಸಂಖ್ಯೆ ಪರಿಶೀಲಿಸಲಾಗುತ್ತಿದೆ…',
        cloudSavedAt: 'ಕ್ಲೌಡ್‌ಗೆ ಉಳಿಸಲಾಗಿದೆ · {time}',
        cloudSaveFailed: 'ಕ್ಲೌಡ್ ಸಿಂಕ್ ವಿಫಲ (ಈ ಸಾಧನದಲ್ಲಿ ಇನ್ನೂ ಉಳಿದಿದೆ).',
        draftResumeLoaded: 'ಉಳಿಸಿದ ನೋಂದಣಿ ಲೋಡ್ ಆಗಿದೆ. ಕೆಳಗೆ ಮುಂದುವರಿಸಿ.',
        draftResumeInvalid: 'ಮರುಪ್ರಾರಂಭ ಲಿಂಕ್ ಅಮಾನ್ಯ. ಹೊಸ ಫಾರ್ಮ್ ಆರಂಭಿಸಲಾಗುತ್ತಿದೆ.',
        errorNetwork: 'ಸರ್ವರ್ ತಲುಪಲು ಸಾಧ್ಯವಿಲ್ಲ. Laravel ಚಾಲ್ತಿಯಲ್ಲಿದೆಯೇ ಎಂದು ಪರಿಶೀಲಿಸಿ.',
        errorServer: 'ಸರ್ವರ್ ದೋಷ. ಮತ್ತೆ ಪ್ರಯತ್ನಿಸಿ.',
    },
}

const draft = reactive({
    ngo_name: '',
    founder_name: '',
    founder_phone: '',
    founder_email: '',
    cofounder_name: '',
    cofounder_phone: '',
    cofounder_email: '',
    legal_structure: 'other',
    registration_number: '',
    pan: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state_name: '',
    postal_code: '',
    theme_color: '#2563eb',
    latitude: null,
    longitude: null,
    description: '',
    otp_code: '',
    login_pin: '',
    login_pin_confirmation: '',
})
const files = reactive({
    registration_certificate: null,
    pan_card: null,
})

const messages = ref([])

function resetChatToLanguage() {
    messages.value = [
        { type: 'bot', text: txt('introHi') },
        { type: 'bot', text: localizedSteps.value[0].prompt },
    ]
}

const getPreferredVoice = () => {
    let voices = speechVoices.value.length ? speechVoices.value : []
    if (!voices.length && typeof window !== 'undefined' && window.speechSynthesis) {
        voices = window.speechSynthesis.getVoices() || []
    }
    if (!voices.length) return null

    const wantsKn = assistantLanguage.value === 'kn'
    const candidates = voices.filter((v) => {
        const L = (v.lang || '').toLowerCase()
        return wantsKn ? L.startsWith('kn') : L.startsWith('en')
    })
    const hints = wantsKn
        ? ['kannada', 'kn-in', 'google', 'female']
        : ['google', 'female', 'siri', 'samantha', 'zira', 'veena', 'daniel', 'aaron']

    const best = candidates.find((v) => {
        const name = (v.name || '').toLowerCase()
        return hints.some((hint) => name.includes(hint))
    }) || candidates[0]

    return best || null
}

const splitForSpeech = (text) => {
    const clean = String(text || '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim()
    if (!clean) return []
    return clean.split(/(?<=[.!?।॥])\s+/).filter(Boolean)
}

const speakText = (text) => {
    if (!voiceEnabled.value || !('speechSynthesis' in window)) return

    if (!speechVoices.value.length) {
        speechVoices.value = window.speechSynthesis.getVoices() || []
    }

    const chunks = splitForSpeech(text)
    if (!chunks.length) return

    window.speechSynthesis.cancel()

    const voice = getPreferredVoice()
    const lang = assistantLanguage.value === 'kn' ? 'kn-IN' : 'en-IN'
    const rate = assistantLanguage.value === 'kn' ? 1.05 : 1.04

    let i = 0
    const speakNext = () => {
        if (i >= chunks.length) return
        const utterance = new SpeechSynthesisUtterance(chunks[i])
        i += 1
        utterance.lang = lang
        utterance.rate = rate
        utterance.pitch = 1.03
        if (voice) {
            utterance.voice = voice
        }
        utterance.onend = speakNext
        window.speechSynthesis.speak(utterance)
    }
    speakNext()
}

const appendMessage = (type, text) => {
    messages.value.push({ type, text })
    if (type === 'bot') {
        speakText(text)
    }
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight
        }
    })
}

const toggleVoice = () => {
    voiceEnabled.value = !voiceEnabled.value
    if (!voiceEnabled.value && 'speechSynthesis' in window) {
        window.speechSynthesis.cancel()
    }
}

const toggleAssistantLanguage = () => {
    assistantLanguage.value = assistantLanguage.value === 'kn' ? 'en' : 'kn'
    appendMessage('bot', assistantLanguage.value === 'kn' ? txt('languageToKn') : txt('languageToEn'))
    appendMessage('bot', localizedSteps.value[stepIndex.value].prompt)
}

const selectLegalStructure = (value) => {
    input.value = value
    handleSubmit()
}

const selectThemeColor = (color) => {
    input.value = color
    handleSubmit()
}

const onRegistrationCertificateChange = (event) => {
    const file = event.target.files?.[0] || null
    handleDocumentSelection(file, 'registration_certificate')
}

const onPanCardChange = (event) => {
    const file = event.target.files?.[0] || null
    handleDocumentSelection(file, 'pan_card')
}

const MAX_UPLOAD_BYTES = 5 * 1024 * 1024

const formatMb = (bytes) => `${(bytes / (1024 * 1024)).toFixed(2)} MB`

const ALLOWED_DOC_MIMES = new Set(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])

function isAllowedDocumentFile(file) {
    const name = (file.name || '').toLowerCase()
    const extOk = /\.(pdf|jpe?g|png)$/i.test(name)
    const mime = (file.type || '').toLowerCase()
    const mimeOk = !mime || ALLOWED_DOC_MIMES.has(mime)
    return extOk && mimeOk
}

const compressImageFile = async (file) => {
    const dataUrl = await new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onload = () => resolve(reader.result)
        reader.onerror = reject
        reader.readAsDataURL(file)
    })

    const img = await new Promise((resolve, reject) => {
        const image = new Image()
        image.onload = () => resolve(image)
        image.onerror = reject
        image.src = dataUrl
    })

    const canvas = document.createElement('canvas')
    const maxDimension = 1600
    const scale = Math.min(1, maxDimension / Math.max(img.width, img.height))
    canvas.width = Math.max(1, Math.floor(img.width * scale))
    canvas.height = Math.max(1, Math.floor(img.height * scale))
    const ctx = canvas.getContext('2d')
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height)

    let quality = 0.88
    let blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', quality))
    while (blob && blob.size > MAX_UPLOAD_BYTES && quality > 0.5) {
        quality -= 0.08
        blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', quality))
    }

    if (!blob) return null
    return new File([blob], `${file.name.replace(/\.[^.]+$/, '')}-compressed.jpg`, { type: 'image/jpeg' })
}

const handleDocumentSelection = async (file, fieldName) => {
    docSelectionError.value = ''
    if (!file) {
        files[fieldName] = null
        persistDraft()
        return
    }

    if (!isAllowedDocumentFile(file)) {
        files[fieldName] = null
        const msg = txt('fileTypeNotAllowed')
        docSelectionError.value = msg
        appendMessage('bot', msg)
        showErrorModal(msg)
        persistDraft()
        return
    }

    if (file.size > MAX_UPLOAD_BYTES && !file.type.startsWith('image/')) {
        files[fieldName] = null
        const msg = formatTxt('fileTooLarge', { size: formatMb(file.size) })
        docSelectionError.value = msg
        appendMessage('bot', msg)
        showErrorModal(msg)
        persistDraft()
        return
    }

    if (file.size <= MAX_UPLOAD_BYTES) {
        files[fieldName] = file
        persistDraft()
        return
    }

    if (file.type.startsWith('image/')) {
        appendMessage('bot', formatTxt('compressing', { size: formatMb(file.size) }))
        const compressed = await compressImageFile(file)
        if (compressed && compressed.size <= MAX_UPLOAD_BYTES) {
            files[fieldName] = compressed
            appendMessage('bot', formatTxt('compressedOk', { from: formatMb(file.size), to: formatMb(compressed.size) }))
            persistDraft()
            return
        }
    }

    files[fieldName] = null
    const msg = formatTxt('fileTooLarge', { size: formatMb(file.size) })
    docSelectionError.value = msg
    appendMessage('bot', msg)
    showErrorModal(msg)
    persistDraft()
}

const autoFillMission = () => {
    const ngoName = (draft.ngo_name || '').toLowerCase()
    if (ngoName.includes('vikasana')) {
        input.value = 'Founded in 1984 by Shri. Kesavan Namboodiri, Vikasana was built on the belief that self-reliance is key to empowerment. Our mission is to uplift vulnerable communities by addressing their economic, educational, health, and infrastructural needs through women empowerment, sustainable practices, and long-term community development.'
        return
    }

    input.value = `Our mission at ${draft.ngo_name || 'our NGO'} is to build self-reliant and resilient communities through inclusive education, health, livelihood support, women empowerment, and sustainable development initiatives.`
}

const normalizeValue = (key, value) => {
    if (!value) return ''
    if (key === 'legal_structure') return value.toLowerCase().trim()
    if (key === 'pan') return value.toUpperCase().trim()
    return value.trim()
}

const validateStepValue = (key, value) => {
    if (['founder_phone', 'cofounder_phone'].includes(key)) {
        const digits = String(value || '').replace(/\D/g, '')
        if (key === 'cofounder_phone' && digits.length === 0) {
            return null
        }
        return digits.length >= 10 ? null : txt('errPhone')
    }
    if (['founder_email', 'cofounder_email'].includes(key)) {
        if (!value) return null
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value || ''))
            ? null
            : txt('errEmail')
    }
    if (key === 'pan') {
        return /^[A-Z]{5}[0-9]{4}[A-Z]$/.test(String(value || '').toUpperCase())
            ? null
            : txt('errPan')
    }
    if (key === 'email') {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value || ''))
            ? null
            : txt('errNgoEmail')
    }
    if (key === 'phone') {
        const digits = String(value || '').replace(/\D/g, '')
        return digits.length >= 10 ? null : txt('errPhone')
    }
    if (key === 'registration_number') {
        return String(value || '').length >= 6 ? null : txt('errRegNo')
    }
    return null
}

const goToStep = (visibleIndex) => {
    const step = editableSteps.value[visibleIndex]
    if (!step) return
    const idx = stepDefs.findIndex((s) => s.key === step.key)
    if (idx < 0) return
    if (idx > OTP_STEP_INDEX && !otpVerified.value) {
        const msg = txt('completeOtpFirst')
        appendMessage('bot', msg)
        showErrorModal(msg)
        stepIndex.value = OTP_STEP_INDEX
        showEditPanel.value = false
        nextTick(() => syncInputFromDraftForCurrentStep())
        persistDraft()
        void flushDraftToServer()
        return
    }
    stepIndex.value = idx
    showEditPanel.value = false
    appendMessage('bot', formatTxt('editing', { label: step.label }))
    persistDraft()
    void flushDraftToServer()
}

function skipOptionalStep() {
    if (submitting.value || otpVerifying.value) {
        return
    }
    if (!optionalStepKeys.has(currentStep.value.key)) {
        return
    }
    const key = currentStep.value.key
    draft[key] = ''
    input.value = ''
    appendMessage('user', txt('skippedMarker'))
    appendMessage('bot', formatTxt('skippedOptional', { label: currentStep.value.label }))
    if (!isFinalStep.value) {
        stepIndex.value += 1
        appendMessage('bot', localizedSteps.value[stepIndex.value].prompt)
    }
    persistDraft()
    void flushDraftToServer()
}

const handleSubmit = async () => {
    if (submitting.value) return

    if (currentStep.value.key === 'focus_areas') {
        if (!selectedFocusAreas.value.length) {
            appendMessage('bot', txt('selectFocus'))
            return
        }
        await submitRegistration()
        return
    }

    if (currentStep.value.key === 'registration_certificate') {
        if (!files.registration_certificate) {
            appendMessage('bot', txt('uploadRegContinue'))
            return
        }
        appendMessage('user', `Uploaded: ${files.registration_certificate.name}`)
        stepIndex.value += 1
        appendMessage('bot', localizedSteps.value[stepIndex.value].prompt)
        persistDraft()
        void flushDraftToServer()
        return
    }

    if (currentStep.value.key === 'pan_card') {
        if (!files.pan_card) {
            appendMessage('bot', txt('uploadPanContinue'))
            return
        }
        appendMessage('user', `Uploaded: ${files.pan_card.name}`)
        stepIndex.value += 1
        appendMessage('bot', localizedSteps.value[stepIndex.value].prompt)
        persistDraft()
        void flushDraftToServer()
        return
    }

    if (currentStep.value.key === 'otp_code') {
        const otp = String(input.value || '').replace(/\D/g, '')
        const len = ngoChatOtpLength.value
        if (otp.length !== len) {
            const msg = formatTxt('otpWrongLen', { len: String(len) })
            appendMessage('bot', msg)
            showErrorModal(msg)
            return
        }
        otpVerifying.value = true
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            const response = await fetch('/register/ngo-chat/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    phone: draft.phone,
                    otp_code: otp,
                }),
            })
            const raw = await response.text()
            const data = parseJsonResponse(raw)
            if (!response.ok || !data.success) {
                const msg = data.message || data.errors?.otp_code?.[0] || txt('otpFail')
                appendMessage('bot', msg)
                showErrorModal(msg)
                return
            }
            otpVerified.value = true
            draft.otp_code = otp
            appendMessage('user', '••••••')
            appendMessage('bot', data.message || txt('otpVerifiedOk'))
            if (!isFinalStep.value) {
                stepIndex.value += 1
                appendMessage('bot', localizedSteps.value[stepIndex.value].prompt)
            }
            persistDraft()
            void flushDraftToServer()
        } catch {
            appendMessage('bot', txt('otpUnavailable'))
            showErrorModal(txt('errorNetwork'))
        } finally {
            otpVerifying.value = false
        }
        return
    }

    let value = normalizeValue(currentStep.value.key, input.value)
    if (!value && draft[currentStep.value.key]) {
        value = draft[currentStep.value.key]
    }

    if (!value && !['postal_code', 'founder_email', 'cofounder_name', 'cofounder_phone', 'cofounder_email'].includes(currentStep.value.key)) {
        appendMessage('bot', txt('enterValid'))
        return
    }

    const stepValidationError = validateStepValue(currentStep.value.key, value)
    if (stepValidationError) {
        appendMessage('bot', stepValidationError)
        return
    }

    if (currentStep.value.key === 'theme_color') {
        const ok = themePalette.includes(value)
        if (!ok) {
            appendMessage('bot', txt('pickPaletteColour'))
            return
        }
    }

    if (currentStep.value.key === 'login_pin' && String(value).length < 5) {
        appendMessage('bot', txt('pinShort'))
        return
    }

    if (currentStep.value.key === 'login_pin_confirmation' && value !== draft.login_pin) {
        appendMessage('bot', txt('pinMismatch'))
        return
    }

    draft[currentStep.value.key] = value
    if (['otp_code', 'login_pin', 'login_pin_confirmation'].includes(currentStep.value.key)) {
        appendMessage('user', '••••••')
    } else {
        appendMessage('user', value || txt('skippedMarker'))
    }

    if (!isFinalStep.value) {
        stepIndex.value += 1
        appendMessage('bot', localizedSteps.value[stepIndex.value].prompt)
    }
    persistDraft()
    void flushDraftToServer()
}

const mapSpokenLegalStructure = (spoken) => {
    const value = spoken.toLowerCase().replace(/\s+/g, '')
    if (value.includes('section8') || value.includes('sectioneight') || spoken.includes('ಸೆಕ್ಷನ್')) return 'section8'
    if (value.includes('society') || spoken.includes('ಸೊಸೈಟಿ')) return 'society'
    if (value.includes('trust') || spoken.includes('ಟ್ರಸ್ಟ್')) return 'trust'
    return 'other'
}

const startVoiceInput = () => {
    if (!speechRecognitionSupported || listening.value) return
    if (['registration_certificate', 'pan_card', 'focus_areas', 'theme_color'].includes(currentStep.value.key)) {
        appendMessage('bot', txt('forThisStepUseUi'))
        return
    }

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition
    const recognition = new SpeechRecognition()
    recognition.lang = assistantLanguage.value === 'kn' ? 'kn-IN' : 'en-IN'
    recognition.interimResults = true
    recognition.continuous = true
    recognition.maxAlternatives = 1
    recognitionRef.value = recognition
    listening.value = true
    noSpeechRetryCount.value = 0

    recognition.onresult = (event) => {
        const result = event.results?.[event.results.length - 1]
        const spoken = result?.[0]?.transcript?.trim() || ''
        if (!spoken) return

        if (result?.isFinal) {
            if (currentStep.value.key === 'legal_structure') {
                input.value = mapSpokenLegalStructure(spoken)
            } else {
                input.value = spoken
            }
            recognition.stop()
        }
    }

    recognition.onerror = (event) => {
        if (event?.error === 'no-speech' && noSpeechRetryCount.value < 1) {
            noSpeechRetryCount.value += 1
            appendMessage('bot', assistantLanguage.value === 'kn'
                ? 'ಶಬ್ದ ಸಿಗಲಿಲ್ಲ, ಮತ್ತೆ ಕೇಳುತ್ತಿದ್ದೇನೆ...'
                : 'Did not catch that, listening again...')
            recognition.stop()
            setTimeout(() => {
                if (!listening.value) {
                    startVoiceInput()
                }
            }, 250)
            return
        }
        appendMessage('bot', assistantLanguage.value === 'kn'
            ? 'ಧ್ವನಿ ಇನ್‌ಪುಟ್ ವಿಫಲವಾಗಿದೆ. ದಯವಿಟ್ಟು ಟೈಪ್ ಮಾಡಿ.'
            : 'Voice input failed. Please type the value.')
    }

    recognition.onend = () => {
        listening.value = false
        recognitionRef.value = null
    }

    recognition.start()
}

const toggleFocusArea = (area) => {
    if (selectedFocusAreas.value.includes(area)) {
        selectedFocusAreas.value = selectedFocusAreas.value.filter((item) => item !== area)
    } else {
        selectedFocusAreas.value.push(area)
    }
    persistDraft()
}

const captureLocation = () => {
    if (!navigator.geolocation) {
        appendMessage('bot', txt('locationUnsupported'))
        return
    }
    capturingLocation.value = true
    navigator.geolocation.getCurrentPosition(async (position) => {
        draft.latitude = position.coords.latitude
        draft.longitude = position.coords.longitude
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${draft.latitude}&lon=${draft.longitude}`)
            if (res.ok) {
                const data = await res.json()
                const a = data.address || {}
                draft.address = data.display_name || draft.address
                draft.city = a.city || a.town || a.village || draft.city
                draft.state_name = a.state || draft.state_name
                draft.postal_code = a.postcode || draft.postal_code
                appendMessage('bot', txt('locationCaptured'))
                input.value = draft.address
                persistDraft()
            }
        } catch (error) {
            appendMessage('bot', txt('locationPartial'))
        } finally {
            capturingLocation.value = false
        }
    }, () => {
        capturingLocation.value = false
        appendMessage('bot', txt('locationDenied'))
    })
}

const sendNgoOtp = async () => {
    if (!draft.phone) {
        appendMessage('bot', txt('needPhoneOtp'))
        return
    }

    sendingOtp.value = true
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        const response = await fetch('/auth/send-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                phone: draft.phone,
                email: draft.email || null,
            }),
        })
        const raw = await response.text()
        const data = parseJsonResponse(raw)
        if (!response.ok || !data.success) {
            const msg = data.message || txt('otpFail')
            appendMessage('bot', msg)
            showErrorModal(msg)
            return
        }
        appendMessage('bot', data.message || txt('otpSentOk'))
        otpVerified.value = false
        draft.otp_code = ''
        input.value = ''
        persistDraft()
    } catch (error) {
        appendMessage('bot', txt('otpUnavailable'))
        showErrorModal(txt('errorNetwork'))
    } finally {
        sendingOtp.value = false
    }
}

const verifyRegistration = async () => {
    if (!draft.registration_number) return
    verificationLoading.value = true
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        const response = await fetch('/register/ngo-chat/verify-registration', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                registration_number: draft.registration_number,
                legal_structure: draft.legal_structure || 'other',
                pan: draft.pan || '',
                ngo_name: draft.ngo_name || '',
                state_name: draft.state_name || '',
            }),
        })
        const raw = await response.text()
        const data = parseJsonResponse(raw)
        if (!response.ok) {
            const msg = data.message || txt('verifyUnavailable')
            appendMessage('bot', msg)
            showErrorModal(msg)
            verificationResult.value = null
            return
        }
        verificationResult.value = data.verification || null
        appendMessage(
            'bot',
            formatTxt('verifyDone', {
                conf: data.verification?.confidence ?? 'low',
                ms: String(data.verification?.runtime_ms ?? '-'),
            }),
        )
    } catch (error) {
        appendMessage('bot', txt('verifyUnavailable'))
        showErrorModal(txt('errorNetwork'))
    } finally {
        verificationLoading.value = false
    }
}

const submitRegistration = async () => {
    submitting.value = true
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        const payload = new FormData()
        Object.entries(draft).forEach(([key, value]) => {
            payload.append(key, value ?? '')
        })
        payload.append('focus_areas', JSON.stringify(selectedFocusAreas.value))
        payload.append('accept_terms', '1')
        if (files.registration_certificate) {
            payload.append('registration_certificate', files.registration_certificate)
        }
        if (files.pan_card) {
            payload.append('pan_card', files.pan_card)
        }
        const response = await fetch('/register/ngo-chat', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token || '',
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: payload,
        })

        const raw = await response.text()
        const data = parseJsonResponse(raw)
        if (!response.ok || !data.success) {
            const errors = data.errors && typeof data.errors === 'object' ? data.errors : {}
            const errKeys = Object.keys(errors)
            const fieldToStep = {
                registration_certificate: 'registration_certificate',
                pan_card: 'pan_card',
                otp_code: 'otp_code',
                phone: 'phone',
                email: 'email',
                founder_email: 'founder_email',
            }
            const firstKey = errKeys.find((k) => fieldToStep[k])
            if (firstKey) {
                const sk = fieldToStep[firstKey]
                const si = stepDefs.findIndex((s) => s.key === sk)
                if (si >= 0) {
                    stepIndex.value = si
                    if (sk === 'otp_code') {
                        otpVerified.value = false
                    }
                    nextTick(() => syncInputFromDraftForCurrentStep())
                }
            } else if (data.message && /otp/i.test(String(data.message))) {
                stepIndex.value = OTP_STEP_INDEX
                otpVerified.value = false
                nextTick(() => syncInputFromDraftForCurrentStep())
            }
            const firstError = errKeys.length ? errors[errKeys[0]]?.[0] : null
            const msg = firstError || data.message || txt('regFail')
            appendMessage('bot', msg)
            showErrorModal(msg)
            return
        }

        appendMessage('bot', txt('regSuccess'))
        clearDraft()
        const target = data.dashboard_url || '/ngo/dashboard'
        window.location.assign(target)
    } catch (error) {
        const msg = txt('errorNetwork')
        appendMessage('bot', txt('submitRetry'))
        showErrorModal(msg)
    } finally {
        submitting.value = false
    }
}

const persistDraft = () => {
    const payload = {
        draft: { ...draft },
        selectedFocusAreas: [...selectedFocusAreas.value],
        stepIndex: stepIndex.value,
        messages: messages.value.slice(-40),
        otp_gate: buildOtpGatePayload(),
        savedAt: new Date().toISOString(),
    }
    window.localStorage.setItem(DRAFT_KEY, JSON.stringify(payload))
    if (syncTimer.value) {
        clearTimeout(syncTimer.value)
    }
    syncTimer.value = setTimeout(() => {
        saveDraftToDb()
    }, 450)
}

const getFirstIncompleteStepIndex = () => {
    for (let i = 0; i < stepDefs.length; i++) {
        const key = stepDefs[i].key
        if (optionalStepKeys.has(key)) {
            continue
        }
        if (key === 'otp_code') {
            if (!otpVerified.value) {
                return i
            }
            continue
        }
        if (key === 'registration_certificate') {
            if (!files.registration_certificate) {
                return i
            }
            continue
        }
        if (key === 'pan_card') {
            if (!files.pan_card) {
                return i
            }
            continue
        }
        if (key === 'focus_areas') {
            if (!selectedFocusAreas.value.length) {
                return i
            }
            continue
        }
        const v = draft[key]
        if (!v || String(v).trim() === '') {
            return i
        }
    }
    return stepDefs.length - 1
}

const hydrateDraftPayload = (parsed) => {
    if (!parsed) return
    if (parsed?.draft) {
        Object.assign(draft, parsed.draft)
    }
    if (Array.isArray(parsed?.selectedFocusAreas)) {
        selectedFocusAreas.value = parsed.selectedFocusAreas
    }
    if (Array.isArray(parsed?.messages) && parsed.messages.length) {
        messages.value = parsed.messages
    }
    const og = parsed?.otp_gate
    if (og?.passed && String(og.phone || '').trim() === String(draft.phone || '').trim()) {
        otpVerified.value = true
    } else {
        otpVerified.value = false
    }
    const fallbackStep = getFirstIncompleteStepIndex()
    if (Number.isInteger(parsed?.stepIndex) && parsed.stepIndex >= 0 && parsed.stepIndex < stepDefs.length) {
        stepIndex.value = Math.min(parsed.stepIndex, fallbackStep)
    } else {
        stepIndex.value = fallbackStep
    }
    nextTick(() => syncInputFromDraftForCurrentStep())
}

const restoreDraft = async () => {
    const idFromStorage = window.localStorage.getItem(DRAFT_ID_KEY)
    if (idFromStorage) {
        draftId.value = idFromStorage
    }

    const hasLocalDraft = !!window.localStorage.getItem(DRAFT_KEY)
    const hasDbDraftRef = !!draftId.value
    if (!hasLocalDraft && !hasDbDraftRef) return

    const shouldRestore = window.confirm(
        assistantLanguage.value === 'kn'
            ? 'ಉಳಿಸಿದ ಎನ್‌ಜಿಒ ನೋಂದಣಿ ಡ್ರಾಫ್ಟ್ ಸಿಕ್ಕಿದೆ. ಅದನ್ನು ಮರುಸ್ಥಾಪಿಸಲು ಬಯಸುವಿರಾ?'
            : 'We found a saved NGO registration draft. Do you want to restore it?',
    )
    if (!shouldRestore) {
        clearDraft()
        resetChatToLanguage()
        return
    }

    const raw = window.localStorage.getItem(DRAFT_KEY)
    if (raw) {
        try {
            const parsed = JSON.parse(raw)
            hydrateDraftPayload(parsed)
            appendMessage('bot', txt('draftRestored'))
        } catch (error) {
            window.localStorage.removeItem(DRAFT_KEY)
        }
    }

    if (draftId.value) {
        await loadDraftFromDb()
    }
}

const saveDraftToDb = async () => {
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        const response = await fetch('/register/ngo-chat/draft', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: JSON.stringify(buildDraftRequestPayload()),
        })
        const raw = await response.text()
        const data = parseJsonResponse(raw)
        if (response.ok && data?.draft_id) {
            draftId.value = data.draft_id
            window.localStorage.setItem(DRAFT_ID_KEY, data.draft_id)
            lastCloudSavedAt.value = data.saved_at || new Date().toISOString()
            cloudSaveError.value = ''
        } else {
            cloudSaveError.value = txt('cloudSaveFailed')
        }
    } catch (error) {
        cloudSaveError.value = txt('cloudSaveFailed')
    }
}

function flushDraftToServer() {
    if (syncTimer.value) {
        clearTimeout(syncTimer.value)
        syncTimer.value = null
    }
    return saveDraftToDb()
}

function keepaliveSaveDraft() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    if (!token || typeof fetch === 'undefined') {
        return
    }
    try {
        fetch('/register/ngo-chat/draft', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: JSON.stringify(buildDraftRequestPayload()),
            keepalive: true,
        }).catch(() => {})
    } catch {
        /* ignore */
    }
}

const loadDraftFromDb = async () => {
    try {
        const response = await fetch(`/register/ngo-chat/draft/${encodeURIComponent(draftId.value)}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        if (!response.ok) return
        const raw = await response.text()
        const data = parseJsonResponse(raw)
        const payload = data?.payload || {}
        const normalized = {
            draft: payload.draft || {},
            selectedFocusAreas: payload.selected_focus_areas || [],
            stepIndex: payload.step_index ?? 0,
            messages: payload.messages || [],
            otp_gate: payload.otp_gate || { passed: false, phone: '' },
        }
        hydrateDraftPayload(normalized)
        lastCloudSavedAt.value = data?.saved_at || null
        persistDraft()
    } catch (error) {
        // Continue without blocking.
    }
}

const clearDraft = () => {
    window.localStorage.removeItem(DRAFT_KEY)
    window.localStorage.removeItem(DRAFT_ID_KEY)
}

async function tryLoadResumeFromEmailLink() {
    const token = page.props?.ngoChatResumeToken
    if (!token || typeof token !== 'string') {
        return false
    }
    try {
        const res = await fetch(`/register/ngo-chat/draft-by-resume/${encodeURIComponent(token)}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        const raw = await res.text()
        const data = parseJsonResponse(raw)
        if (!res.ok || !data.success || !data.draft_id) {
            return 'invalid'
        }
        draftId.value = data.draft_id
        window.localStorage.setItem(DRAFT_ID_KEY, data.draft_id)
        const p = data.payload || {}
        hydrateDraftPayload({
            draft: p.draft || {},
            selectedFocusAreas: p.selected_focus_areas || [],
            stepIndex: p.step_index ?? 0,
            messages: p.messages || [],
            otp_gate: p.otp_gate || { passed: false, phone: '' },
        })
        persistDraft()
        loadedFromResumeLink.value = true
        lastCloudSavedAt.value = data.saved_at || null
        appendMessage('bot', txt('draftResumeLoaded'))
        return true
    } catch {
        return 'invalid'
    }
}

const onPageLeaveSave = () => keepaliveSaveDraft()

onMounted(async () => {
    if ('speechSynthesis' in window) {
        const loadVoices = () => {
            speechVoices.value = window.speechSynthesis.getVoices() || []
        }
        loadVoices()
        window.speechSynthesis.onvoiceschanged = loadVoices
    }

    const resume = await tryLoadResumeFromEmailLink()
    if (resume === true) {
        /* hydrated from email link */
    } else if (resume === 'invalid') {
        resetChatToLanguage()
        appendMessage('bot', txt('draftResumeInvalid'))
    } else {
        const hasLocalDraft = !!window.localStorage.getItem(DRAFT_KEY)
        const hasDraftId = !!window.localStorage.getItem(DRAFT_ID_KEY)
        if (!hasLocalDraft && !hasDraftId) {
            resetChatToLanguage()
        }
        restoreDraft()
    }

    window.addEventListener('beforeunload', onPageLeaveSave)
    window.addEventListener('pagehide', onPageLeaveSave)

    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight
        }
    })

    if (speechRecognitionSupported) {
        appendMessage('bot', txt('voiceInputOn'))
    } else {
        appendMessage('bot', txt('voiceInputOff'))
    }
})

onBeforeUnmount(() => {
    window.removeEventListener('beforeunload', onPageLeaveSave)
    window.removeEventListener('pagehide', onPageLeaveSave)
})

watch(
    stepIndex,
    () => {
        docSelectionError.value = ''
        nextTick(() => syncInputFromDraftForCurrentStep())
    },
    { immediate: true },
)

watch(
    () => draft.phone,
    (phone, prev) => {
        if (prev === undefined || prev === null) {
            return
        }
        if (String(phone || '').trim() !== String(prev || '').trim()) {
            otpVerified.value = false
            draft.otp_code = ''
        }
    },
)

watch(
    () => ({
        d: { ...draft },
        stepIndex: stepIndex.value,
        regCert: files.registration_certificate,
        panDoc: files.pan_card,
        focus: [...selectedFocusAreas.value],
    }),
    () => persistDraft(),
    { deep: true },
)
</script>

<style scoped>
.ngo-chat-err-modal {
    position: fixed;
    inset: 0;
    z-index: 99990;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    box-sizing: border-box;
    pointer-events: none;
}
.ngo-chat-err-modal__backdrop {
    position: absolute;
    inset: 0;
    background: rgb(15 23 42 / 0.45);
    backdrop-filter: blur(6px);
    pointer-events: auto;
}
.ngo-chat-err-modal__panel {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 22rem;
    border-radius: 1rem;
    border: 1px solid #e2e8f0;
    background: #fff;
    padding: 1.25rem 1.25rem 1rem;
    box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.2);
    pointer-events: auto;
    text-align: center;
}
.ngo-chat-err-modal__title {
    margin: 0 0 0.5rem;
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
}
.ngo-chat-err-modal__msg {
    margin: 0 0 1rem;
    font-size: 0.875rem;
    line-height: 1.5;
    color: #475569;
    text-align: left;
}
.ngo-chat-err-modal__btn {
    width: 100%;
    border: none;
    border-radius: 0.75rem;
    padding: 0.65rem 1rem;
    font-size: 0.9375rem;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(90deg, #2563eb, #4f46e5);
    cursor: pointer;
}
.ngo-chat-err-t-enter-active,
.ngo-chat-err-t-leave-active {
    transition: opacity 0.2s ease;
}
.ngo-chat-err-t-enter-from,
.ngo-chat-err-t-leave-to {
    opacity: 0;
}

.ngo-chat-action-loader {
    position: fixed;
    inset: 0;
    z-index: 99989;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    box-sizing: border-box;
    pointer-events: none;
}
.ngo-chat-action-loader__backdrop {
    position: absolute;
    inset: 0;
    background: rgb(15 23 42 / 0.5);
    backdrop-filter: blur(8px);
    pointer-events: auto;
}
.ngo-chat-action-loader__panel {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    padding: 1.75rem 2rem 1.5rem;
    max-width: 20rem;
    width: 100%;
    border-radius: 1.125rem;
    border: 1px solid #e2e8f0;
    background: #fff;
    box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
    pointer-events: auto;
    text-align: center;
}
.ngo-chat-action-loader__logo {
    width: 5.5rem;
    height: 5.5rem;
    object-fit: contain;
    animation: ngo-chat-brand-pulse 1.35s ease-in-out infinite;
    filter: drop-shadow(0 4px 14px rgb(37 99 235 / 0.2));
}
.ngo-chat-action-loader__text {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.45;
    color: #334155;
}
@keyframes ngo-chat-brand-pulse {
    0%,
    100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.82;
        transform: scale(1.08);
    }
}
.ngo-chat-loader-t-enter-active,
.ngo-chat-loader-t-leave-active {
    transition: opacity 0.22s ease;
}
.ngo-chat-loader-t-enter-active .ngo-chat-action-loader__panel,
.ngo-chat-loader-t-leave-active .ngo-chat-action-loader__panel {
    transition: transform 0.22s ease, opacity 0.22s ease;
}
.ngo-chat-loader-t-enter-from,
.ngo-chat-loader-t-leave-to {
    opacity: 0;
}
.ngo-chat-loader-t-enter-from .ngo-chat-action-loader__panel,
.ngo-chat-loader-t-leave-to .ngo-chat-action-loader__panel {
    opacity: 0;
    transform: scale(0.96);
}
</style>
