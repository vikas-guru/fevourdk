<template>
    <AppLayout title="NGO Chat Registration - FEVOURD-K">
        <div class="min-h-screen bg-slate-50 py-4 sm:py-8">
            <div class="mx-auto max-w-3xl px-4">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4 text-white">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-blue-100">Smart NGO Onboarding</p>
                                <h1 class="text-xl font-bold">Chat Registration Assistant</h1>
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
                                    {{ voiceEnabled ? 'Voice ON' : 'Voice OFF' }}
                                </button>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-blue-100">
                            Simple 5-minute flow with location auto-capture and basic registration verification.
                        </p>
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
                        <div class="mb-3 flex items-center justify-between">
                            <p class="text-[11px] text-slate-500">Step {{ stepIndex + 1 }} of {{ steps.length }}</p>
                            <button
                                type="button"
                                class="rounded-lg border border-slate-300 px-2.5 py-1 text-[11px] font-semibold text-slate-700 hover:bg-slate-50"
                                @click="showEditPanel = !showEditPanel"
                            >
                                {{ showEditPanel ? 'Close Edit' : 'Edit Details' }}
                            </button>
                        </div>

                        <div v-if="showEditPanel" class="mb-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="mb-2 text-xs font-semibold text-slate-700">Jump to any field</p>
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                                <button
                                    v-for="(s, i) in editableSteps"
                                    :key="`edit-${s.key}`"
                                    type="button"
                                    class="rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-[11px] text-slate-700 hover:border-blue-300"
                                    @click="goToStep(i)"
                                >
                                    {{ s.label }}
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
                            <p class="mb-2 text-xs font-semibold text-slate-600">Choose a theme color</p>
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
                                Auto-fill Mission Draft
                            </button>
                        </div>

                        <div v-if="currentStep.key === 'focus_areas'" class="mb-3 grid grid-cols-2 gap-2 sm:grid-cols-3">
                            <button
                                v-for="area in focusAreas"
                                :key="area"
                                type="button"
                                class="rounded-xl border px-2.5 py-2 text-xs font-medium transition"
                                :class="selectedFocusAreas.includes(area) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300 hover:border-blue-300'"
                                @click="toggleFocusArea(area)"
                            >
                                {{ area }}
                            </button>
                        </div>

                        <div v-if="currentStep.key === 'address'" class="mb-3">
                            <button
                                type="button"
                                class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition"
                                :disabled="capturingLocation"
                                @click="captureLocation"
                            >
                                {{ capturingLocation ? 'Capturing...' : 'Use Current NGO Location' }}
                            </button>
                        </div>

                        <div v-if="currentStep.key === 'registration_number'" class="mb-3">
                            <button
                                type="button"
                                class="rounded-xl bg-violet-600 px-3 py-2 text-xs font-semibold text-white hover:bg-violet-700 transition"
                                :disabled="verificationLoading || !draft.registration_number"
                                @click="verifyRegistration"
                            >
                                {{ verificationLoading ? 'Verifying...' : 'Verify Registration Number' }}
                            </button>
                            <div v-if="verificationResult" class="mt-2 rounded-xl border border-violet-200 bg-violet-50 p-3 text-xs text-slate-700">
                                <p class="font-semibold text-violet-700">Verification Confidence: {{ verificationResult.confidence }} ({{ verificationResult.score }}/100)</p>
                                <p class="mt-1">{{ verificationResult.note }}</p>
                                <div v-if="verificationResult.checks?.length" class="mt-2">
                                    <p class="font-semibold text-slate-800">Checks</p>
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
                                    <p class="font-semibold text-slate-800">Real-time Providers</p>
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
                            <label class="mb-1 block text-xs font-semibold text-slate-700">Upload Registration Certificate</label>
                            <input
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs"
                                @change="onRegistrationCertificateChange"
                            >
                        </div>

                        <div v-if="currentStep.key === 'pan_card'" class="mb-3">
                            <label class="mb-1 block text-xs font-semibold text-slate-700">Upload PAN Document</label>
                            <input
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs"
                                @change="onPanCardChange"
                            >
                        </div>

                        <div v-if="currentStep.key === 'otp_code'" class="mb-3">
                            <button
                                type="button"
                                class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition"
                                :disabled="sendingOtp || !draft.phone"
                                @click="sendNgoOtp"
                            >
                                {{ sendingOtp ? 'Sending OTP...' : 'Send OTP to phone + email' }}
                            </button>
                            <p class="mt-1 text-[11px] text-slate-500">We will send OTP to your official NGO phone and email.</p>
                        </div>

                        <div class="flex gap-2">
                            <input
                                v-model="input"
                                :placeholder="currentStep.placeholder"
                                class="flex-1 rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :disabled="submitting"
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
                                    {{ listening ? 'Listening...' : 'Voice' }}
                                </span>
                            </button>
                            <button
                                type="button"
                                class="rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                                :disabled="submitting"
                                @click="handleSubmit"
                            >
                                {{ isFinalStep ? (submitting ? 'Submitting...' : 'Register') : 'Next' }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { nextTick, onMounted, reactive, ref, computed, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

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
const speechRecognitionSupported = typeof window !== 'undefined' && !!(window.SpeechRecognition || window.webkitSpeechRecognition)

const legalStructureOptions = [
    { value: 'trust', label: 'Trust' },
    { value: 'society', label: 'Society' },
    { value: 'section8', label: 'Section 8' },
    { value: 'other', label: 'Other' },
]
const themePalette = ['#2563eb', '#1d4ed8', '#0f766e', '#16a34a', '#ea580c', '#dc2626', '#7c3aed', '#0891b2', '#4338ca', '#be123c']

const focusAreas = [
    'Education',
    'Healthcare',
    'Women Empowerment',
    'Child Welfare',
    'Environment',
    'Rural Development',
]

const steps = [
    { key: 'ngo_name', label: 'NGO Name', prompt: 'Welcome! Tell me your NGO name.', placeholder: 'Enter NGO name' },
    { key: 'founder_name', label: 'Founder Name', prompt: 'Please share founder full name.', placeholder: 'Founder full name' },
    { key: 'founder_phone', label: 'Founder Phone', prompt: 'Founder contact phone number?', placeholder: '+91...' },
    { key: 'founder_email', label: 'Founder Email', prompt: 'Founder email (optional).', placeholder: 'founder@email.com' },
    { key: 'cofounder_name', label: 'Co-Founder Name', prompt: 'Co-founder full name (optional).', placeholder: 'Co-founder full name (optional)' },
    { key: 'cofounder_phone', label: 'Co-Founder Phone', prompt: 'Co-founder phone (optional).', placeholder: '+91... (optional)' },
    { key: 'cofounder_email', label: 'Co-Founder Email', prompt: 'Co-founder email (optional).', placeholder: 'cofounder@email.com (optional)' },
    { key: 'legal_structure', label: 'Legal Structure', prompt: 'What is your legal structure? (trust/society/section8/other)', placeholder: 'e.g. section8' },
    { key: 'registration_number', label: 'Registration No.', prompt: 'Please share your NGO registration number.', placeholder: 'Registration number' },
    { key: 'pan', label: 'PAN', prompt: 'Enter NGO PAN number (required).', placeholder: 'PAN number' },
    { key: 'email', label: 'Email', prompt: 'Official NGO email ID?', placeholder: 'ngo@email.com' },
    { key: 'phone', label: 'Phone', prompt: 'Official contact phone number?', placeholder: '+91...' },
    { key: 'address', label: 'Address', prompt: 'Enter NGO address, or use auto-capture button.', placeholder: 'Address' },
    { key: 'city', label: 'City', prompt: 'City name?', placeholder: 'City' },
    { key: 'state_name', label: 'State', prompt: 'State name?', placeholder: 'State' },
    { key: 'postal_code', label: 'Postal Code', prompt: 'Postal code (optional).', placeholder: 'Postal code' },
    { key: 'theme_color', label: 'Theme Color', prompt: 'Pick your NGO theme color from palette.', placeholder: 'Choose from palette' },
    { key: 'description', label: 'Mission', prompt: 'Describe your NGO mission in 1-2 lines.', placeholder: 'Mission/description' },
    { key: 'registration_certificate', label: 'Reg Certificate', prompt: 'Upload registration certificate document.', placeholder: 'Upload registration certificate' },
    { key: 'pan_card', label: 'PAN Document', prompt: 'Upload NGO PAN card/document.', placeholder: 'Upload PAN document' },
    { key: 'otp_code', label: 'OTP', prompt: 'Enter OTP sent to NGO phone/email.', placeholder: 'Enter OTP' },
    { key: 'login_pin', label: 'Login PIN', prompt: 'Create NGO login PIN (minimum 5 digits).', placeholder: 'Create NGO PIN' },
    { key: 'login_pin_confirmation', label: 'Confirm PIN', prompt: 'Confirm NGO login PIN.', placeholder: 'Confirm NGO PIN' },
    { key: 'focus_areas', label: 'Focus Areas', prompt: 'Select focus areas and press Register.', placeholder: 'Select focus areas above' },
]

const stepIndex = ref(0)
const currentStep = computed(() => steps[stepIndex.value])
const isFinalStep = computed(() => stepIndex.value === steps.length - 1)
const editableSteps = computed(() => steps.filter((s) => s.key !== 'focus_areas'))

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

const messages = ref([
    { type: 'bot', text: 'Hi! I will help you complete NGO registration quickly and clearly.' },
    { type: 'bot', text: steps[0].prompt },
])

const getPreferredVoice = () => {
    const voices = speechVoices.value
    if (!voices.length) return null

    const languagePrefix = assistantLanguage.value === 'kn' ? 'kn' : 'en-in'
    const preferredNameHints = assistantLanguage.value === 'kn'
        ? ['google', 'kannada', 'heera', 'female', 'zira']
        : ['google', 'female', 'siri', 'heera', 'zira']

    const candidates = voices.filter((voice) => (voice.lang || '').toLowerCase().startsWith(languagePrefix))
    const best = candidates.find((voice) => {
        const name = (voice.name || '').toLowerCase()
        return preferredNameHints.some((hint) => name.includes(hint))
    }) || candidates[0]

    return best || null
}

const splitForSpeech = (text) => {
    const clean = String(text || '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim()
    if (!clean) return []
    return clean.split(/(?<=[.!?।])\s+/).filter(Boolean)
}

const speakText = (text) => {
    if (!voiceEnabled.value || !('speechSynthesis' in window)) return

    const voice = getPreferredVoice()
    const chunks = splitForSpeech(text)
    if (!chunks.length) return

    window.speechSynthesis.cancel()

    chunks.forEach((chunk, index) => {
        const utterance = new SpeechSynthesisUtterance(chunk)
        utterance.lang = assistantLanguage.value === 'kn' ? 'kn-IN' : 'en-IN'
        // Slightly faster settings for crispness; Kannada was too slow before.
        utterance.rate = assistantLanguage.value === 'kn' ? 1.08 : 1.04
        utterance.pitch = 1.03
        if (voice) {
            utterance.voice = voice
        }
        if (index === 0) {
            utterance.volume = 1
        }
        window.speechSynthesis.speak(utterance)
    })
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
    appendMessage('bot', assistantLanguage.value === 'kn'
        ? 'ಕನ್ನಡ ಧ್ವನಿ ಸಹಾಯಕ ಸಕ್ರಿಯವಾಗಿದೆ. ನೀವು ಈಗ ಕನ್ನಡದಲ್ಲಿ ಮಾತನಾಡಬಹುದು.'
        : 'English voice assistant is active. You can continue in English.')
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

const MAX_UPLOAD_BYTES = 2 * 1024 * 1024

const formatMb = (bytes) => `${(bytes / (1024 * 1024)).toFixed(2)} MB`

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
    if (!file) {
        files[fieldName] = null
        persistDraft()
        return
    }

    if (file.size <= MAX_UPLOAD_BYTES) {
        files[fieldName] = file
        persistDraft()
        return
    }

    if (file.type.startsWith('image/')) {
        appendMessage('bot', `Image is ${formatMb(file.size)}. Compressing to fit upload limit...`)
        const compressed = await compressImageFile(file)
        if (compressed && compressed.size <= MAX_UPLOAD_BYTES) {
            files[fieldName] = compressed
            appendMessage('bot', `Compressed successfully: ${formatMb(file.size)} -> ${formatMb(compressed.size)}.`)
            persistDraft()
            return
        }
    }

    files[fieldName] = null
    appendMessage('bot', `File too large (${formatMb(file.size)}). Please upload a file under 2 MB.`)
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
        return digits.length >= 10 ? null : 'Please enter a valid phone number (at least 10 digits).'
    }
    if (['founder_email', 'cofounder_email'].includes(key)) {
        if (!value) return null
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value || ''))
            ? null
            : 'Please enter a valid email.'
    }
    if (key === 'pan') {
        return /^[A-Z]{5}[0-9]{4}[A-Z]$/.test(String(value || '').toUpperCase())
            ? null
            : 'PAN must be exactly 10 characters in format like ABCDE1234F.'
    }
    if (key === 'email') {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value || ''))
            ? null
            : 'Please enter a valid NGO email ID.'
    }
    if (key === 'phone') {
        const digits = String(value || '').replace(/\D/g, '')
        return digits.length >= 10 ? null : 'Please enter a valid phone number (at least 10 digits).'
    }
    if (key === 'registration_number') {
        return String(value || '').length >= 6 ? null : 'Registration number should be at least 6 characters.'
    }
    return null
}

const goToStep = (visibleIndex) => {
    const step = editableSteps.value[visibleIndex]
    if (!step) return
    const idx = steps.findIndex((s) => s.key === step.key)
    if (idx < 0) return
    stepIndex.value = idx
    input.value = draft[step.key] || ''
    showEditPanel.value = false
    appendMessage('bot', `Editing: ${step.label}`)
}

const handleSubmit = async () => {
    if (submitting.value) return

    if (currentStep.value.key === 'focus_areas') {
        if (!selectedFocusAreas.value.length) {
            appendMessage('bot', 'Please select at least one focus area.')
            return
        }
        await submitRegistration()
        return
    }

    if (currentStep.value.key === 'registration_certificate') {
        if (!files.registration_certificate) {
            appendMessage('bot', 'Please upload the registration certificate to continue.')
            return
        }
        appendMessage('user', `Uploaded: ${files.registration_certificate.name}`)
        input.value = ''
        stepIndex.value += 1
        appendMessage('bot', steps[stepIndex.value].prompt)
        return
    }

    if (currentStep.value.key === 'pan_card') {
        if (!files.pan_card) {
            appendMessage('bot', 'Please upload the PAN document to continue.')
            return
        }
        appendMessage('user', `Uploaded: ${files.pan_card.name}`)
        input.value = ''
        stepIndex.value += 1
        appendMessage('bot', steps[stepIndex.value].prompt)
        return
    }

    let value = normalizeValue(currentStep.value.key, input.value)
    if (!value && draft[currentStep.value.key]) {
        value = draft[currentStep.value.key]
    }

    if (!value && !['postal_code', 'founder_email', 'cofounder_name', 'cofounder_phone', 'cofounder_email'].includes(currentStep.value.key)) {
        appendMessage('bot', 'Please enter a valid value to continue.')
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
            appendMessage('bot', 'Please choose a color from the palette.')
            return
        }
    }

    if (currentStep.value.key === 'login_pin' && String(value).length < 5) {
        appendMessage('bot', 'Login PIN should be at least 5 digits/characters.')
        return
    }

    if (currentStep.value.key === 'login_pin_confirmation' && value !== draft.login_pin) {
        appendMessage('bot', 'PIN confirmation does not match. Please enter again.')
        return
    }

    draft[currentStep.value.key] = value
    if (['otp_code', 'login_pin', 'login_pin_confirmation'].includes(currentStep.value.key)) {
        appendMessage('user', '••••••')
    } else {
        appendMessage('user', value || '(skipped)')
    }
    input.value = ''

    if (!isFinalStep.value) {
        stepIndex.value += 1
        appendMessage('bot', steps[stepIndex.value].prompt)
    }
    persistDraft()
}

const mapSpokenLegalStructure = (spoken) => {
    const value = spoken.toLowerCase().replace(/\s+/g, '')
    if (value.includes('section8') || value.includes('sectioneight')) return 'section8'
    if (value.includes('society')) return 'society'
    if (value.includes('trust')) return 'trust'
    return 'other'
}

const startVoiceInput = () => {
    if (!speechRecognitionSupported || listening.value) return
    if (['registration_certificate', 'pan_card', 'focus_areas', 'theme_color'].includes(currentStep.value.key)) {
        appendMessage('bot', 'For this step, please use the visible options/upload controls.')
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
        appendMessage('bot', 'Location is not supported on this device.')
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
                appendMessage('bot', 'Location captured and auto-filled. You can keep these values or edit city/state/postal in the next steps.')
                input.value = draft.address
                persistDraft()
            }
        } catch (error) {
            appendMessage('bot', 'Location captured, but address auto-fill failed. You can enter manually.')
        } finally {
            capturingLocation.value = false
        }
    }, () => {
        capturingLocation.value = false
        appendMessage('bot', 'Could not capture location. Please enter address manually.')
    })
}

const sendNgoOtp = async () => {
    if (!draft.phone) {
        appendMessage('bot', 'Please provide official phone number before requesting OTP.')
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
        const data = await response.json()
        if (!response.ok || !data.success) {
            appendMessage('bot', data.message || 'Failed to send OTP. Please try again.')
            return
        }
        appendMessage('bot', data.message || 'OTP sent successfully.')
    } catch (error) {
        appendMessage('bot', 'OTP service unavailable. Please retry in a moment.')
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
        const data = await response.json()
        verificationResult.value = data.verification || null
        appendMessage(
            'bot',
            `Verification completed with <strong>${data.verification?.confidence ?? 'low'}</strong> confidence in ~${data.verification?.runtime_ms ?? '-'} ms.`
        )
    } catch (error) {
        appendMessage('bot', 'Verification service is currently unavailable. You can continue registration.')
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

        const data = await response.json()
        if (!response.ok || !data.success) {
            const firstError = data.errors ? Object.values(data.errors)?.[0]?.[0] : null
            appendMessage('bot', firstError || data.message || 'Registration failed. Please check details and retry.')
            return
        }

        appendMessage('bot', 'Registration submitted successfully! Your NGO is now in verification queue. Redirecting to login...')
        clearDraft()
        appendMessage('bot', 'Creating your NGO workspace...')
        setTimeout(() => appendMessage('bot', 'Creating your beautiful website experience...'), 1700)
        setTimeout(() => appendMessage('bot', 'Finalizing dashboard setup...'), 3300)
        setTimeout(() => {
            window.location.href = data.dashboard_url || '/dashboard'
        }, 5000)
    } catch (error) {
        appendMessage('bot', 'Unable to submit now. Please retry in a moment.')
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
        savedAt: new Date().toISOString(),
    }
    window.localStorage.setItem(DRAFT_KEY, JSON.stringify(payload))
    if (syncTimer.value) {
        clearTimeout(syncTimer.value)
    }
    syncTimer.value = setTimeout(() => {
        saveDraftToDb()
    }, 900)
}

const getFirstIncompleteStepIndex = () => {
    const requiredKeys = [
        'ngo_name',
        'founder_name',
        'founder_phone',
        'legal_structure',
        'registration_number',
        'pan',
        'email',
        'phone',
        'address',
        'city',
        'state_name',
        'theme_color',
        'description',
        'otp_code',
        'login_pin',
        'login_pin_confirmation',
    ]

    for (const key of requiredKeys) {
        if (!draft[key] || String(draft[key]).trim() === '') {
            return steps.findIndex((s) => s.key === key)
        }
    }

    if (!selectedFocusAreas.value.length) {
        return steps.findIndex((s) => s.key === 'focus_areas')
    }

    return steps.length - 1
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
    const fallbackStep = getFirstIncompleteStepIndex()
    if (Number.isInteger(parsed?.stepIndex) && parsed.stepIndex >= 0 && parsed.stepIndex < steps.length) {
        stepIndex.value = Math.min(parsed.stepIndex, fallbackStep)
    } else {
        stepIndex.value = fallbackStep
    }
}

const restoreDraft = async () => {
    const idFromStorage = window.localStorage.getItem(DRAFT_ID_KEY)
    if (idFromStorage) {
        draftId.value = idFromStorage
    }

    const hasLocalDraft = !!window.localStorage.getItem(DRAFT_KEY)
    const hasDbDraftRef = !!draftId.value
    if (!hasLocalDraft && !hasDbDraftRef) return

    const shouldRestore = window.confirm('We found a saved NGO registration draft. Do you want to restore it?')
    if (!shouldRestore) {
        clearDraft()
        return
    }

    const raw = window.localStorage.getItem(DRAFT_KEY)
    if (raw) {
        try {
            const parsed = JSON.parse(raw)
            hydrateDraftPayload(parsed)
            appendMessage('bot', 'Draft restored. Continue from where you left off.')
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
            body: JSON.stringify({
                draft_id: draftId.value || null,
                draft: { ...draft },
                selected_focus_areas: [...selectedFocusAreas.value],
                step_index: stepIndex.value,
                messages: messages.value.slice(-40),
            }),
        })
        const data = await response.json()
        if (response.ok && data?.draft_id) {
            draftId.value = data.draft_id
            window.localStorage.setItem(DRAFT_ID_KEY, data.draft_id)
        }
    } catch (error) {
        // Silent fallback to localStorage only.
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
        const data = await response.json()
        const payload = data?.payload || {}
        const normalized = {
            draft: payload.draft || {},
            selectedFocusAreas: payload.selected_focus_areas || [],
            stepIndex: payload.step_index ?? 0,
            messages: payload.messages || [],
        }
        hydrateDraftPayload(normalized)
        window.localStorage.setItem(DRAFT_KEY, JSON.stringify({
            draft: { ...draft },
            selectedFocusAreas: [...selectedFocusAreas.value],
            stepIndex: stepIndex.value,
            messages: messages.value.slice(-40),
            savedAt: new Date().toISOString(),
        }))
    } catch (error) {
        // Continue without blocking.
    }
}

const clearDraft = () => {
    window.localStorage.removeItem(DRAFT_KEY)
    window.localStorage.removeItem(DRAFT_ID_KEY)
}

onMounted(() => {
    if ('speechSynthesis' in window) {
        const loadVoices = () => {
            speechVoices.value = window.speechSynthesis.getVoices() || []
        }
        loadVoices()
        window.speechSynthesis.onvoiceschanged = loadVoices
    }

    restoreDraft()
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight
        }
    })

    if (speechRecognitionSupported) {
        appendMessage('bot', 'Voice input is enabled. ಕನ್ನಡ ಅಥವಾ English ನಲ್ಲಿ ಮಾತನಾಡಬಹುದು.')
    } else {
        appendMessage('bot', 'Voice input is not supported on this device.')
    }
})

watch(
    () => ({ ...draft, stepIndex: stepIndex.value }),
    () => persistDraft(),
    { deep: true }
)
</script>
