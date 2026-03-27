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
                            <button
                                type="button"
                                class="rounded-xl bg-white/20 px-3 py-1.5 text-xs font-semibold hover:bg-white/30 transition"
                                @click="toggleVoice"
                            >
                                {{ voiceEnabled ? 'Voice ON' : 'Voice OFF' }}
                            </button>
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
                                class="rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                                :disabled="submitting"
                                @click="handleSubmit"
                            >
                                {{ isFinalStep ? (submitting ? 'Submitting...' : 'Register') : 'Next' }}
                            </button>
                        </div>

                        <p class="mt-2 text-[11px] text-slate-500">
                            Step {{ stepIndex + 1 }} of {{ steps.length }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { nextTick, onMounted, reactive, ref, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const chatContainer = ref(null)
const input = ref('')
const submitting = ref(false)
const verificationLoading = ref(false)
const verificationResult = ref(null)
const capturingLocation = ref(false)
const voiceEnabled = ref(true)
const selectedFocusAreas = ref([])

const focusAreas = [
    'Education',
    'Healthcare',
    'Women Empowerment',
    'Child Welfare',
    'Environment',
    'Rural Development',
]

const steps = [
    { key: 'ngo_name', prompt: 'Welcome! Tell me your NGO name.', placeholder: 'Enter NGO name' },
    { key: 'legal_structure', prompt: 'What is your legal structure? (trust/society/section8/other)', placeholder: 'e.g. section8' },
    { key: 'registration_number', prompt: 'Please share your NGO registration number.', placeholder: 'Registration number' },
    { key: 'pan', prompt: 'Enter PAN number (optional but recommended).', placeholder: 'PAN (optional)' },
    { key: 'email', prompt: 'Official NGO email ID?', placeholder: 'ngo@email.com' },
    { key: 'phone', prompt: 'Official contact phone number?', placeholder: '+91...' },
    { key: 'address', prompt: 'Enter NGO address, or use auto-capture button.', placeholder: 'Address' },
    { key: 'city', prompt: 'City name?', placeholder: 'City' },
    { key: 'state_name', prompt: 'State name?', placeholder: 'State' },
    { key: 'postal_code', prompt: 'Postal code (optional).', placeholder: 'Postal code' },
    { key: 'description', prompt: 'Describe your NGO mission in 1-2 lines.', placeholder: 'Mission/description' },
    { key: 'focus_areas', prompt: 'Select focus areas and press Register.', placeholder: 'Select focus areas above' },
]

const stepIndex = ref(0)
const currentStep = computed(() => steps[stepIndex.value])
const isFinalStep = computed(() => stepIndex.value === steps.length - 1)

const draft = reactive({
    ngo_name: '',
    legal_structure: 'other',
    registration_number: '',
    pan: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state_name: '',
    postal_code: '',
    latitude: null,
    longitude: null,
    description: '',
})

const messages = ref([
    { type: 'bot', text: 'Hi! I will help you complete NGO registration quickly and clearly.' },
    { type: 'bot', text: steps[0].prompt },
])

const appendMessage = (type, text) => {
    messages.value.push({ type, text })
    if (type === 'bot' && voiceEnabled.value && 'speechSynthesis' in window) {
        const u = new SpeechSynthesisUtterance(text.replace(/<[^>]+>/g, ''))
        u.rate = 0.95
        window.speechSynthesis.cancel()
        window.speechSynthesis.speak(u)
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

const normalizeValue = (key, value) => {
    if (!value) return ''
    if (key === 'legal_structure') return value.toLowerCase().trim()
    return value.trim()
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

    const value = normalizeValue(currentStep.value.key, input.value)
    if (!value && !['pan', 'postal_code'].includes(currentStep.value.key)) {
        appendMessage('bot', 'Please enter a valid value to continue.')
        return
    }

    draft[currentStep.value.key] = value
    appendMessage('user', value || '(skipped)')
    input.value = ''

    if (!isFinalStep.value) {
        stepIndex.value += 1
        appendMessage('bot', steps[stepIndex.value].prompt)
    }
}

const toggleFocusArea = (area) => {
    if (selectedFocusAreas.value.includes(area)) {
        selectedFocusAreas.value = selectedFocusAreas.value.filter((item) => item !== area)
    } else {
        selectedFocusAreas.value.push(area)
    }
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
                appendMessage('bot', 'Location captured and fields auto-filled. You can edit before submitting.')
                input.value = draft.address
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
            }),
        })
        const data = await response.json()
        verificationResult.value = data.verification || null
        appendMessage('bot', `Verification completed with <strong>${data.verification?.confidence ?? 'low'}</strong> confidence.`)
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
        const payload = {
            ...draft,
            focus_areas: selectedFocusAreas.value,
            accept_terms: true,
        }
        const response = await fetch('/register/ngo-chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: JSON.stringify(payload),
        })

        const data = await response.json()
        if (!response.ok || !data.success) {
            appendMessage('bot', data.message || 'Registration failed. Please check details and retry.')
            return
        }

        appendMessage('bot', 'Registration submitted successfully! Your NGO is now in verification queue. Redirecting to login...')
        setTimeout(() => {
            window.location.href = '/login'
        }, 1400)
    } catch (error) {
        appendMessage('bot', 'Unable to submit now. Please retry in a moment.')
    } finally {
        submitting.value = false
    }
}

onMounted(() => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight
        }
    })
})
</script>
