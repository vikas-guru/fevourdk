<template>
    <AppLayout title="Complete your NGO profile - FEVOURD-K" :hide-chrome-mobile="true">
        <div class="fkw">
            <div class="fkw-grain" aria-hidden="true"></div>
            <div class="fkw-wrap">
                <!-- ============ RAIL ============ -->
                <aside class="fkw-rail">
                    <p class="fkw-eyebrow"><span class="fkw-dot"></span> Quick setup · Step {{ current + 1 }} of {{ steps.length }}</p>
                    <h1 class="fkw-display fkw-rail__title">Finish setting up<br>{{ ngo.name }}</h1>
                    <p class="fkw-rail__sub">Just the essentials — everything’s optional and saved as you go. You can finish the rest anytime from your dashboard.</p>

                    <div class="fkw-progress">
                        <div class="fkw-progress__bar"><span :style="{ width: completion.percent + '%' }"></span></div>
                        <span class="fkw-progress__pct"><b>{{ completion.percent }}%</b> complete</span>
                    </div>

                    <button v-if="stepKey !== 'review' && voiceSupported" type="button" class="fkw-voice-cta" @click="startVoice">
                        <span class="fkw-voice-cta__mic">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 14a3 3 0 003-3V6a3 3 0 00-6 0v5a3 3 0 003 3z" /><path d="M19 11a1 1 0 10-2 0 5 5 0 01-10 0 1 1 0 10-2 0 7 7 0 006 6.92V21a1 1 0 102 0v-3.08A7 7 0 0019 11z" /></svg>
                        </span>
                        <span class="fkw-voice-cta__txt">
                            <strong>Set up by voice · ಧ್ವನಿ</strong>
                            <em>I’ll ask in English or ಕನ್ನಡ — just speak, no typing.</em>
                        </span>
                        <span class="fkw-voice-cta__go" aria-hidden="true">→</span>
                    </button>

                    <ol class="fkw-steps">
                        <li
                            v-for="(s, i) in steps"
                            :key="s.key"
                            :class="{ 'is-active': i === current, 'is-done': isStepDone(s) }"
                            @click="goTo(i)"
                        >
                            <span class="fkw-steps__mark">
                                <svg v-if="isStepDone(s)" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                                <template v-else>{{ i + 1 }}</template>
                            </span>
                            <span class="fkw-steps__label">{{ s.label }}</span>
                        </li>
                    </ol>

                    <Link :href="dashboardUrl" class="fkw-rail__skip">Skip — I’ll finish later →</Link>
                </aside>

                <!-- ============ STEP PANEL ============ -->
                <section class="fkw-panel">
                    <p v-if="generalError" class="fkw-alert">{{ generalError }}</p>

                    <!-- 1 · IDENTITY -->
                    <div v-if="stepKey === 'identity'" class="fkw-step">
                        <h2 class="fkw-display fkw-step__title">Your organisation</h2>
                        <p class="fkw-step__hint">The basics donors look for — add what you have, skip the rest.</p>
                        <div class="fkw-grid2">
                            <div class="fkw-field">
                                <label>Registration number</label>
                                <input v-model.trim="form.registration_number" type="text" placeholder="Trust / Society reg. no.">
                                <small v-if="errors.registration_number" class="fkw-err">{{ errors.registration_number[0] }}</small>
                            </div>
                            <div class="fkw-field">
                                <label>PAN</label>
                                <input v-model.trim="form.pan" type="text" maxlength="20" placeholder="AAAAA0000A" style="text-transform:uppercase">
                                <small v-if="errors.pan" class="fkw-err">{{ errors.pan[0] }}</small>
                            </div>
                        </div>
                        <div class="fkw-field">
                            <label>Address line 1</label>
                            <input v-model.trim="form.address_line1" type="text" placeholder="Building / street / door no.">
                        </div>
                        <div class="fkw-field">
                            <label>Address line 2 <span class="fkw-opt">(optional)</span></label>
                            <input v-model.trim="form.address_line2" type="text" placeholder="Area / landmark">
                        </div>

                        <!-- 📍 Smart location: search + detect + map picker (free OpenStreetMap) -->
                        <div class="fkw-loc">
                            <div class="fkw-loc__head">
                                <div class="fkw-loc__title">
                                    <span class="fkw-loc__pin">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </span>
                                    <div>
                                        <strong>Where are you based?</strong>
                                        <small>Search a place, use your location, or drop the pin — we set your city &amp; state.</small>
                                    </div>
                                </div>
                                <button type="button" class="fkw-loc__btn" :disabled="locating" @click="useMyLocation">
                                    <svg v-if="locating" class="fkw-spin" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                    <svg v-else viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
                                    {{ locating ? 'Locating…' : 'Use my location' }}
                                </button>
                            </div>

                            <!-- Search any place / NGO / town -->
                            <div class="fkw-loc__search">
                                <svg class="fkw-loc__searchic" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                                <input
                                    v-model="searchText"
                                    type="text"
                                    placeholder="e.g. Vikasana Mandya, or Bengaluru"
                                    autocomplete="off"
                                    @input="onSearchInput"
                                    @keydown.enter.prevent="runSearch"
                                    @focus="searchOpen = searchResults.length > 0"
                                    @blur="closeSearchSoon"
                                >
                                <span v-if="searching" class="fkw-loc__searchspin">
                                    <svg class="fkw-spin" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                </span>
                                <ul v-if="searchOpen && searchResults.length" class="fkw-loc__results">
                                    <li v-for="(r, i) in searchResults" :key="i" @mousedown.prevent="selectResult(r)">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span>{{ r.label }}</span>
                                    </li>
                                </ul>
                            </div>

                            <p v-if="locMsg" class="fkw-loc__msg" :class="'is-' + locMsgType">{{ locMsg }}</p>

                            <div ref="mapEl" class="fkw-loc__map"></div>

                            <!-- Resolved location (replaces the old City/State text inputs) -->
                            <div class="fkw-loc__resolved">
                                <div class="fkw-loc__chip">
                                    <small>City / Town</small>
                                    <strong>{{ form.city || '—' }}</strong>
                                </div>
                                <div class="fkw-loc__chip">
                                    <small>State</small>
                                    <strong>{{ form.state_name || '—' }}</strong>
                                </div>
                                <div class="fkw-loc__chip fkw-loc__chip--input">
                                    <small>Pincode</small>
                                    <input v-model.trim="form.pincode" type="text" inputmode="numeric" maxlength="6" placeholder="6-digit PIN">
                                </div>
                            </div>
                            <small v-if="errors.city" class="fkw-err" style="padding:0 1.1rem .7rem;display:block">{{ errors.city[0] }}</small>
                        </div>
                    </div>

                    <!-- 2 · WORK -->
                    <div v-else-if="stepKey === 'work'" class="fkw-step">
                        <h2 class="fkw-display fkw-step__title">Your work</h2>
                        <p class="fkw-step__hint">What you do, the causes you focus on, and a logo if you have one.</p>
                        <div class="fkw-field">
                            <label>Short description</label>
                            <textarea v-model.trim="form.description" rows="4" placeholder="What does your organisation do, and who does it serve?"></textarea>
                            <small v-if="errors.description" class="fkw-err">{{ errors.description[0] }}</small>
                        </div>
                        <div class="fkw-field">
                            <label>Focus areas <span class="fkw-muted">({{ form.focus_areas.length }} selected)</span></label>
                            <div class="fkw-chips">
                                <button
                                    v-for="opt in focusOptions"
                                    :key="opt"
                                    type="button"
                                    class="fkw-chip"
                                    :class="{ 'is-on': form.focus_areas.includes(opt) }"
                                    @click="toggleFocus(opt)"
                                >{{ opt }}</button>
                            </div>
                        </div>
                        <div class="fkw-grid2">
                            <div class="fkw-file">
                                <label>Logo <span class="fkw-muted">(optional)</span></label>
                                <input type="file" accept="image/png,image/jpeg" @change="pick($event, 'logo')">
                                <small v-if="files.logo" class="fkw-hint">Selected: {{ files.logo.name }}</small>
                            </div>
                            <div class="fkw-file">
                                <label>Reg. certificate <span class="fkw-muted">(optional)</span></label>
                                <input type="file" accept=".pdf,image/png,image/jpeg" @change="pick($event, 'registration_certificate')">
                                <small v-if="files.registration_certificate" class="fkw-hint">Selected: {{ files.registration_certificate.name }}</small>
                                <small v-if="errors.registration_certificate" class="fkw-err">{{ errors.registration_certificate[0] }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- 3 · REVIEW -->
                    <div v-else class="fkw-step">
                        <h2 class="fkw-display fkw-step__title">You’re all set 🎉</h2>
                        <p class="fkw-step__hint">Submit for verification, or jump into your dashboard and finish later.</p>
                        <ul class="fkw-review">
                            <li :class="{ ok: completion.steps.legal || completion.steps.address }"><span>Organisation details</span>{{ (completion.steps.legal || completion.steps.address) ? 'Added' : 'Pending' }}</li>
                            <li :class="{ ok: completion.steps.about }"><span>Your work</span>{{ completion.steps.about ? 'Added' : 'Pending' }}</li>
                            <li :class="{ ok: completion.steps.documents }"><span>Documents</span>{{ completion.steps.documents ? 'Uploaded' : 'Pending' }}</li>
                        </ul>
                        <p class="fkw-review__note">You can edit any of this anytime from your NGO dashboard.</p>
                    </div>

                    <!-- ACTIONS -->
                    <div class="fkw-actions">
                        <button v-if="current > 0" type="button" class="fkw-btn fkw-btn--ghost" @click="back" :disabled="busy">Back</button>
                        <div class="fkw-actions__right">
                            <button v-if="stepKey !== 'review'" type="button" class="fkw-btn fkw-btn--text" @click="skip" :disabled="busy">Skip</button>
                            <button v-if="stepKey !== 'review'" type="button" class="fkw-btn fkw-btn--gold" @click="saveAndNext" :disabled="busy">
                                {{ busy ? 'Saving…' : 'Save & continue' }}
                            </button>
                            <button v-else type="button" class="fkw-btn fkw-btn--gold" @click="finalize" :disabled="busy">
                                {{ busy ? 'Submitting…' : 'Submit for verification' }}
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Voice setup overlay -->
        <Teleport to="body">
            <Transition name="fkv">
                <div v-if="voiceOpen" class="fkv" role="dialog" aria-modal="true">
                    <div class="fkv__panel">
                        <button type="button" class="fkv__x" @click="stopVoice" aria-label="Close">×</button>
                        <div class="fkv__lang" role="group" aria-label="Voice language">
                            <button type="button" :class="{ on: lang === 'en' }" @click="setLang('en')">English</button>
                            <button type="button" :class="{ on: lang === 'kn' }" @click="setLang('kn')">ಕನ್ನಡ</button>
                        </div>
                        <div class="fkv__mic" :class="{ listening }">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 14a3 3 0 003-3V6a3 3 0 00-6 0v5a3 3 0 003 3z" /><path d="M19 11a1 1 0 10-2 0 5 5 0 01-10 0 1 1 0 10-2 0 7 7 0 006 6.92V21a1 1 0 102 0v-3.08A7 7 0 0019 11z" /></svg>
                        </div>
                        <p class="fkv__q">{{ voiceQ }}</p>
                        <p class="fkv__interim">{{ interim || (listening ? (lang === 'kn' ? 'ಕೇಳುತ್ತಿದೆ…' : 'Listening…') : (lang === 'kn' ? 'ಒಂದು ಕ್ಷಣ…' : 'One moment…')) }}</p>
                        <p v-if="lang === 'kn' && !knVoiceAvailable()" class="fkv__note">🔊 ಈ ಸಾಧನದಲ್ಲಿ ಕನ್ನಡ ಧ್ವನಿ ಇಲ್ಲ — ಪ್ರಶ್ನೆ ಮೇಲೆ ತೋರಿಸಲಾಗಿದೆ. ನೀವು ಕನ್ನಡದಲ್ಲಿ ಮಾತನಾಡಬಹುದು.</p>
                        <button type="button" class="fkv__stop" @click="stopVoice">{{ lang === 'kn' ? 'ಧ್ವನಿ ಸೆಟಪ್ ನಿಲ್ಲಿಸಿ' : 'Stop voice setup' }}</button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { reactive, ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useLocation } from '@/composables/useLocation'

const props = defineProps({
    ngo: { type: Object, required: true },
    focusOptions: { type: Array, default: () => [] },
    completion: { type: Object, default: () => ({ steps: {}, percent: 0 }) },
    dashboardUrl: { type: String, default: '/ngo/dashboard' },
})

// 3 simple UI steps; each persists the underlying backend steps (contract unchanged).
const steps = [
    { key: 'identity', label: 'Your organisation', saves: ['legal', 'address'] },
    { key: 'work', label: 'Your work', saves: ['about', 'documents'] },
    { key: 'review', label: 'Review & submit', saves: [] },
]

const current = ref(0)
const stepKey = computed(() => steps[current.value].key)
const completion = reactive({ ...props.completion, steps: { ...props.completion.steps } })

const form = reactive({
    registration_number: props.ngo.registration_number || '',
    pan: props.ngo.pan || '',
    legal_structure: '',
    address: props.ngo.address || '',
    address_line1: props.ngo.address || '',
    address_line2: '',
    pincode: props.ngo.postal_code || props.ngo.pincode || '',
    city: props.ngo.city || '',
    state_name: '',
    description: props.ngo.description || '',
    focus_areas: Array.isArray(props.ngo.focus_areas) ? [...props.ngo.focus_areas] : [],
})
const files = reactive({ logo: null, registration_certificate: null, pan_card: null })

// Prefill city/state from the globally-detected location (still fully editable).
const { get: getStoredLocation, set: setStoredLocation } = useLocation()
const stored = getStoredLocation()
if (!form.city && stored.city) form.city = stored.city
if (!form.state_name && stored.state) form.state_name = stored.state

/* ============ SMART LOCATION: geolocation + free OSM map picker ============ */
const KA_CENTER = { lat: 15.3173, lng: 75.7139 } // Karnataka centroid fallback
const mapEl = ref(null)
const mapReady = ref(false)
const locating = ref(false)
const locMsg = ref('')
const locMsgType = ref('info') // 'info' | 'ok' | 'warn'
const picked = reactive({ lat: stored.lat || null, lng: stored.lng || null, label: '' })

let leaflet = null      // L namespace once loaded
let map = null
let marker = null

const setMsg = (text, type = 'info') => { locMsg.value = text; locMsgType.value = type }

// Lazy-load Leaflet (CSS + JS) from CDN exactly once.
function loadLeaflet() {
    if (window.L) return Promise.resolve(window.L)
    if (window.__fkLeafletLoading) return window.__fkLeafletLoading
    window.__fkLeafletLoading = new Promise((resolve, reject) => {
        if (!document.querySelector('link[data-leaflet]')) {
            const css = document.createElement('link')
            css.rel = 'stylesheet'
            css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
            css.setAttribute('data-leaflet', '1')
            document.head.appendChild(css)
        }
        const s = document.createElement('script')
        s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
        s.async = true
        s.onload = () => resolve(window.L)
        s.onerror = () => reject(new Error('Leaflet failed to load'))
        document.head.appendChild(s)
    })
    return window.__fkLeafletLoading
}

/* ---- Google Maps Platform (Places + Geocoding) for accurate search & full address ----
   NOTE: a browser key is public by design — RESTRICT it in Google Cloud Console
   (HTTP referrers: 127.0.0.1:8000, fevourdk.online) and enable only
   Maps JavaScript API + Places API + Geocoding API. Falls back to free OSM if unavailable. */
const GMAPS_KEY = 'AIzaSyDjz8dCm4MMlLTACIFkd3B8k0F-roEQjAI'
let gReady = null
let gAuto = null, gGeocoder = null, gPlaces = null, gToken = null

function loadGoogle() {
    if (window.google?.maps?.places) return Promise.resolve(window.google)
    if (gReady) return gReady
    gReady = new Promise((resolve, reject) => {
        window.__fkGmapsCb = () => resolve(window.google)
        const s = document.createElement('script')
        s.src = `https://maps.googleapis.com/maps/api/js?key=${GMAPS_KEY}&libraries=places&loading=async&callback=__fkGmapsCb`
        s.async = true
        s.onerror = () => reject(new Error('Google Maps failed to load'))
        document.head.appendChild(s)
    })
    return gReady
}

async function gInit() {
    // Once Google has rejected the key (billing/restriction), stop calling it —
    // every Places/Geocoding call would just re-log the same error. Use OSM instead.
    if (googleMapBroken) throw new Error('google-disabled')
    const g = await loadGoogle()
    if (!gAuto) gAuto = new g.maps.places.AutocompleteService()
    if (!gGeocoder) gGeocoder = new g.maps.Geocoder()
    if (!gPlaces) gPlaces = new g.maps.places.PlacesService(document.createElement('div'))
    return g
}

// Turn Google address_components into our structured fields.
function parseGoogle(components = [], formatted = '') {
    const get = (type) => components.find((c) => (c.types || []).includes(type))?.long_name || ''
    const streetNo = get('street_number')
    const route = get('route')
    const premise = get('premise') || get('subpremise')
    const line1 = [premise, [streetNo, route].filter(Boolean).join(' ')].filter(Boolean).join(', ')
    const line2 = get('neighborhood') || get('sublocality_level_1') || get('sublocality') || ''
    const city = get('locality') || get('postal_town') || get('administrative_area_level_3') || get('administrative_area_level_2') || ''
    const state = get('administrative_area_level_1') || ''
    const postcode = get('postal_code') || ''
    return { line1, line2, city, state, postcode, label: formatted || '' }
}

async function googlePredict(q) {
    try {
        await gInit()
        gToken = gToken || new window.google.maps.places.AutocompleteSessionToken()
        return await new Promise((resolve) => {
            gAuto.getPlacePredictions(
                { input: q, componentRestrictions: { country: 'in' }, sessionToken: gToken },
                (preds, status) => resolve(status === 'OK' && preds ? preds.map((p) => ({ label: p.description, place_id: p.place_id })) : [])
            )
        })
    } catch (e) { return [] }
}

async function googleDetails(placeId) {
    try {
        await gInit()
        return await new Promise((resolve) => {
            gPlaces.getDetails(
                { placeId, fields: ['address_component', 'geometry', 'formatted_address'], sessionToken: gToken },
                (place, status) => {
                    gToken = null
                    if (status !== 'OK' || !place) return resolve(null)
                    const parsed = parseGoogle(place.address_components || [], place.formatted_address)
                    parsed.lat = place.geometry?.location?.lat?.()
                    parsed.lng = place.geometry?.location?.lng?.()
                    resolve(parsed)
                }
            )
        })
    } catch (e) { return null }
}

async function googleReverse(lat, lng) {
    try {
        await gInit()
        return await new Promise((resolve) => {
            gGeocoder.geocode({ location: { lat, lng } }, (results, status) => {
                if (status !== 'OK' || !results?.length) return resolve(null)
                resolve(parseGoogle(results[0].address_components || [], results[0].formatted_address))
            })
        })
    } catch (e) { return null }
}

// Reverse geocode (lat/lng → full address) — Google first, OSM Nominatim fallback.
async function reverseGeocode(lat, lng) {
    const g = await googleReverse(lat, lng)
    if (g && (g.city || g.state || g.line1)) return g
    try {
        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } })
        if (!res.ok) return null
        const data = await res.json()
        const a = data.address || {}
        // Line 1: prefer a named place/POI + street; fall back to the most specific area.
        const named = data.name || a.amenity || a.building || a.office || a.shop || a.tourism || a.hospital || a.place || ''
        const street = [a.house_number, a.road].filter(Boolean).join(' ')
        let line1 = [named, street].filter(Boolean).join(', ')
        if (!line1) line1 = a.quarter || a.neighbourhood || a.suburb || a.hamlet || a.village || a.city_district || a.county || ''
        // Line 2: the best area/locality that isn't already shown in line 1.
        const areas = [a.neighbourhood, a.suburb, a.quarter, a.city_district, a.hamlet, a.village].filter(Boolean)
        const line2 = areas.find((p) => p && !line1.includes(p)) || ''
        const cleanPlace = (s) => (s || '').replace(/\s+(taluk|tehsil|taluka|district|division|mandal)$/i, '').trim()
        const city = cleanPlace(a.city || a.town || a.village || a.municipality || a.county || a.state_district || '')
        const state = a.state || ''
        const postcode = a.postcode || ''
        // best place names to look a pincode up by, if OSM didn't give one
        const pinCandidates = [a.village, a.suburb, a.neighbourhood, a.town, a.city, a.county].map(cleanPlace).filter(Boolean)
        return { line1, line2, city, state, postcode, pinCandidates, label: data.display_name || '' }
    } catch (e) {
        return null
    }
}

// India Post directory — resolve a 6-digit PIN from a place name when the map didn't give one.
async function lookupPincode(candidates = []) {
    const seen = new Set()
    for (const raw of candidates) {
        const q = (raw || '').replace(/\b(taluk|tehsil|district|rural|urban|city|town)\b/gi, '').trim()
        if (q.length < 3 || seen.has(q.toLowerCase())) continue
        seen.add(q.toLowerCase())
        try {
            // Same-origin proxy → no CORS; backend queries India Post.
            const res = await fetch(`/tools/pincode?q=${encodeURIComponent(q)}`)
            const data = await res.json()
            if (data?.pincode) return data.pincode
        } catch (e) { /* try next candidate */ }
    }
    return ''
}

// Write a fully-resolved address into the form + move the map pin.
async function applyResolved(addr) {
    if (Number.isFinite(addr.lat) && Number.isFinite(addr.lng)) {
        picked.lat = addr.lat; picked.lng = addr.lng
        await ensureMap(addr.lat, addr.lng)
        moveMarker(addr.lat, addr.lng)
        centerMap(addr.lat, addr.lng, 15)
    }
    if (addr.line1) form.address_line1 = addr.line1
    if (addr.line2) form.address_line2 = addr.line2
    if (addr.city) form.city = addr.city
    if (addr.state) form.state_name = addr.state
    if (addr.postcode) form.pincode = addr.postcode
    picked.label = [addr.city, addr.state].filter(Boolean).join(', ') || addr.label
    setStoredLocation({ lat: addr.lat, lng: addr.lng, city: addr.city, state: addr.state, source: 'manual' })
    setMsg(`Set to ${picked.label || 'location'}. Drag the pin to fine-tune.`, 'ok')
    if (!form.pincode) {
        const pin = await lookupPincode([...(addr.pinCandidates || []), addr.line2, addr.city])
        if (pin) form.pincode = pin
    }
}

// Apply a chosen point: move pin, autofill fields, persist, geocode.
async function applyPoint(lat, lng, { recenter = true, geocode = true } = {}) {
    picked.lat = lat; picked.lng = lng
    moveMarker(lat, lng)
    if (recenter) centerMap(lat, lng, 15)
    setStoredLocation({ lat, lng, source: 'geolocation' })
    if (!geocode) return
    setMsg('Looking up your area…', 'info')
    const geo = await reverseGeocode(lat, lng)
    if (geo) {
        // Reflect the dragged/clicked pin: update address lines + pincode (keep old only if the new lookup is blank).
        form.address_line1 = geo.line1 || form.address_line1
        form.address_line2 = geo.line2 || ''
        if (geo.city) form.city = geo.city
        if (geo.state) form.state_name = geo.state
        form.pincode = geo.postcode || form.pincode
        picked.label = [geo.city, geo.state].filter(Boolean).join(', ')
        setStoredLocation({ city: geo.city, state: geo.state })
        setMsg(`Got it — ${picked.label || 'location set'}. Drag the pin if it’s not exact.`, 'ok')
        // No PIN from the map? Resolve one from the India Post directory by place name.
        if (!form.pincode) {
            const pin = await lookupPincode([...(geo.pinCandidates || []), geo.line2, geo.city])
            if (pin) form.pincode = pin
        }
    } else {
        setMsg('Pin placed. Couldn’t auto-name the area — please check the address fields.', 'warn')
    }
}

/* ---- Provider-agnostic map controls (Google when billed, Leaflet otherwise) ---- */
let usingGoogle = false
let gmap = null, gmarker = null
let googleMapBroken = false

function moveMarker(lat, lng) {
    if (usingGoogle && gmarker) gmarker.setPosition({ lat, lng })
    else if (marker) marker.setLatLng([lat, lng])
}
function centerMap(lat, lng, zoom = 15) {
    if (usingGoogle && gmap) { gmap.setCenter({ lat, lng }); gmap.setZoom(zoom) }
    else if (map) map.setView([lat, lng], zoom)
}

// NGO pin as an SVG data-URL (used for the Google Maps marker).
function googlePinUrl() {
    const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 52" width="40" height="52"><defs><linearGradient id="g" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffd34d"/><stop offset="1" stop-color="#e0a106"/></linearGradient></defs><path d="M20 1C10 1 2 9 2 19c0 12 14 26 18 31 4-5 18-19 18-31C38 9 30 1 20 1z" fill="url(#g)" stroke="#fff" stroke-width="2"/><circle cx="20" cy="18" r="11" fill="#14213d"/><path d="M20 25c-3-2-6-3.6-6-6.4 0-1.5 1.2-2.6 2.7-2.6 1 0 1.8.5 2.3 1.2l1 .1 1-.1c.5-.7 1.3-1.2 2.3-1.2 1.5 0 2.7 1.1 2.7 2.6 0 2.8-3 4.4-6 6.4z" fill="#ffd34d"/></svg>`
    return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg)
}

function buildGoogleMap(lat, lng) {
    const g = window.google
    usingGoogle = true
    gmap = new g.maps.Map(mapEl.value, {
        center: { lat, lng },
        zoom: picked.lat ? 15 : 7,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false,
        gestureHandling: 'cooperative',
        clickableIcons: false,
    })
    gmarker = new g.maps.Marker({
        position: { lat, lng },
        map: gmap,
        draggable: true,
        title: 'Drag to your exact location',
        icon: { url: googlePinUrl(), scaledSize: new g.maps.Size(40, 52), anchor: new g.maps.Point(20, 50) },
    })
    gmarker.addListener('dragend', () => {
        const p = gmarker.getPosition()
        applyPoint(p.lat(), p.lng(), { recenter: false })
    })
    gmap.addListener('click', (e) => applyPoint(e.latLng.lat(), e.latLng.lng(), { recenter: false }))
    mapReady.value = true
}

// Tear down a broken Google map and show the free Leaflet/CARTO map instead.
function fallbackToLeaflet(reason) {
    if (googleMapBroken && map) return
    googleMapBroken = true
    usingGoogle = false
    gmap = null; gmarker = null
    if (mapEl.value) mapEl.value.replaceChildren()
    setMsg(reason || 'Showing the standard map (enable Google billing for Google Maps).', 'warn')
    ensureLeafletMap(picked.lat || KA_CENTER.lat, picked.lng || KA_CENTER.lng)
}

// gm_authFailure only fires for INVALID KEY/referrer — NOT for billing. Billing
// errors are merely logged, so we also watch console.error for the known codes.
let gErrWatch = false
function watchGoogleErrors() {
    if (gErrWatch || typeof window === 'undefined') return
    gErrWatch = true
    const orig = console.error.bind(console)
    console.error = function (...args) {
        try {
            const s = args.map((a) => (typeof a === 'string' ? a : '')).join(' ')
            if (/BillingNotEnabled|ApiNotActivated|RefererNotAllowed|InvalidKey|ApiProjectMapError|MissingKeyMapError|ExpiredKey/.test(s)) {
                fallbackToLeaflet()
            }
        } catch (e) { /* never let logging break */ }
        return orig(...args)
    }
}

if (typeof window !== 'undefined') {
    window.gm_authFailure = () => fallbackToLeaflet()
}

// Primary entry point — Google Maps first, Leaflet fallback.
async function ensureMap(lat, lng) {
    if (!mapEl.value) return
    if (gmap || map) { centerMap(lat, lng, picked.lat ? 15 : 7); moveMarker(lat, lng); return }
    if (!googleMapBroken) {
        try {
            watchGoogleErrors()
            await loadGoogle()
            if (window.google?.maps && !googleMapBroken) { buildGoogleMap(lat, lng); return }
        } catch (e) { /* fall through to Leaflet */ }
    }
    await ensureLeafletMap(lat, lng)
}

async function ensureLeafletMap(lat, lng) {
    if (!mapEl.value || map) return
    try {
        leaflet = await loadLeaflet()
    } catch (e) {
        setMsg('Map couldn’t load (offline?). You can still search or type your area.', 'warn')
        return
    }
    map = leaflet.map(mapEl.value, { zoomControl: true, scrollWheelZoom: false }).setView([lat, lng], picked.lat ? 13 : 6)
    leaflet.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        maxZoom: 20,
        subdomains: 'abcd',
        attribution: '© OpenStreetMap · © CARTO',
    }).addTo(map)
    marker = leaflet.marker([lat, lng], { draggable: true, icon: ngoIcon() }).addTo(map)
    marker.on('dragend', () => {
        const p = marker.getLatLng()
        applyPoint(p.lat, p.lng, { recenter: false })
    })
    map.on('click', (e) => applyPoint(e.latlng.lat, e.latlng.lng, { recenter: false }))
    mapReady.value = true
    const fix = () => map && map.invalidateSize()
    ;[60, 250, 600, 1200].forEach((t) => setTimeout(fix, t))
}

// A custom, on-brand NGO map pin (gold teardrop + caring-hands glyph).
function ngoIcon() {
    const html = `
        <div class="fk-ngopin">
            <svg viewBox="0 0 40 52" width="40" height="52" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="fkpin" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#ffd34d"/>
                        <stop offset="1" stop-color="#e0a106"/>
                    </linearGradient>
                </defs>
                <path d="M20 1C10 1 2 9 2 19c0 12 14 26 18 31 4-5 18-19 18-31C38 9 30 1 20 1z"
                      fill="url(#fkpin)" stroke="#fff" stroke-width="2"/>
                <circle cx="20" cy="18" r="11" fill="#14213d"/>
                <path d="M20 25c-3-2-6-3.6-6-6.4 0-1.5 1.2-2.6 2.7-2.6 1 0 1.8.5 2.3 1.2l1 .1 1-.1c.5-.7 1.3-1.2 2.3-1.2 1.5 0 2.7 1.1 2.7 2.6 0 2.8-3 4.4-6 6.4z"
                      fill="#ffd34d"/>
            </svg>
        </div>`
    return leaflet.divIcon({
        html,
        className: 'fk-ngopin-wrap',
        iconSize: [40, 52],
        iconAnchor: [20, 50],
        popupAnchor: [0, -46],
    })
}

/* ============ PLACE SEARCH (free OSM Nominatim forward geocoding) ============ */
const searchText = ref('')
const searching = ref(false)
const searchResults = ref([])
const searchOpen = ref(false)
let searchTimer = null

async function geocodeOnce(q) {
    try {
        const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&countrycodes=in&addressdetails=1&limit=6&q=${encodeURIComponent(q)}`
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } })
        if (!res.ok) return []
        const data = await res.json()
        return (Array.isArray(data) ? data : []).map((d) => {
            const a = d.address || {}
            return {
                lat: parseFloat(d.lat),
                lng: parseFloat(d.lon),
                label: d.display_name,
                city: a.city || a.town || a.village || a.suburb || a.county || a.state_district || '',
                state: a.state || '',
                postcode: a.postcode || '',
            }
        }).filter((r) => Number.isFinite(r.lat) && Number.isFinite(r.lng))
    } catch (e) {
        return []
    }
}

// Nominatim is a PLACE gazetteer (it doesn't know NGO names like "Vikasana").
// So if the full phrase fails, progressively drop leading words to keep the
// trailing place name — "Vikasana Mandya" → "Mandya" → resolves to Mandya, Karnataka.
async function forwardGeocode(query) {
    const q = (query || '').trim()
    if (q.length < 3) return []
    const words = q.split(/\s+/)
    const candidates = []
    for (let i = 0; i < words.length; i++) {
        const c = words.slice(i).join(' ')
        if (c.length >= 3 && !candidates.includes(c)) candidates.push(c)
    }
    // also try the last two words as a focused place guess
    if (words.length > 2) {
        const tail = words.slice(-2).join(' ')
        if (!candidates.includes(tail)) candidates.push(tail)
    }
    for (const c of candidates) {
        const results = await geocodeOnce(c)
        if (results.length) return results
    }
    return []
}

function closeSearchSoon() {
    setTimeout(() => { searchOpen.value = false }, 160)
}

// Prefer Google Places autocomplete (accurate, knows establishments/NGOs);
// fall back to OSM Nominatim if Google isn't available.
async function suggest(q) {
    const g = await googlePredict(q)
    if (g.length) return g
    return forwardGeocode(q)
}

function onSearchInput() {
    searchOpen.value = true
    if (searchTimer) clearTimeout(searchTimer)
    const q = searchText.value
    if (q.trim().length < 3) { searchResults.value = []; searching.value = false; return }
    searching.value = true
    searchTimer = setTimeout(async () => {
        searchResults.value = await suggest(q)
        searching.value = false
        searchOpen.value = true
    }, 350)
}

async function runSearch() {
    if (searchTimer) clearTimeout(searchTimer)
    searching.value = true
    const results = await suggest(searchText.value)
    searchResults.value = results
    searching.value = false
    if (results.length) selectResult(results[0])
    else setMsg('No match found — try a nearby town, or use your location / the map.', 'warn')
}

async function selectResult(r) {
    searchOpen.value = false
    searchResults.value = []
    searchText.value = (r.label || '').split(',').slice(0, 2).join(',')
    setMsg('Fetching full address…', 'info')

    // Google prediction → fetch rich Place Details (street, area, city, state, PIN, coords).
    if (r.place_id) {
        const d = await googleDetails(r.place_id)
        if (d) { await applyResolved(d); return }
    }

    // Nominatim result → has coords + city/state/PIN; enrich address lines via reverse.
    if (Number.isFinite(r.lat) && Number.isFinite(r.lng)) {
        await applyResolved({ ...r, label: r.label })
        if (!form.address_line1) {
            const rev = await reverseGeocode(r.lat, r.lng)
            if (rev?.line1) form.address_line1 = rev.line1
            if (rev?.line2 && !form.address_line2) form.address_line2 = rev.line2
        }
        return
    }

    setMsg('Couldn’t resolve that place — try another, or use your location.', 'warn')
}

// The reliable permission trigger — must run inside a user gesture (button click).
function useMyLocation() {
    if (!('geolocation' in navigator)) {
        setMsg('Your browser can’t share location. Please type your city & state.', 'warn')
        return
    }
    locating.value = true
    setMsg('Requesting location access…', 'info')
    navigator.geolocation.getCurrentPosition(
        async (pos) => {
            const { latitude, longitude } = pos.coords
            await ensureMap(latitude, longitude)
            await applyPoint(latitude, longitude)
            locating.value = false
        },
        (err) => {
            locating.value = false
            if (err && err.code === 1) {
                setMsg('Location permission was blocked. Allow it in your browser’s site settings, then tap again — or pick on the map.', 'warn')
            } else {
                setMsg('Couldn’t get your location. Drop a pin on the map or type your city & state.', 'warn')
            }
            // Still show a map so they can pick manually.
            ensureMap(picked.lat || KA_CENTER.lat, picked.lng || KA_CENTER.lng)
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
    )
}

onMounted(async () => {
    // Always show a map so the section feels alive; center on stored pin or Karnataka.
    await ensureMap(picked.lat || KA_CENTER.lat, picked.lng || KA_CENTER.lng)
    if (picked.lat) {
        setMsg(`Saved location: ${[form.city, form.state_name].filter(Boolean).join(', ') || 'pinned'}.`, 'ok')
    } else {
        setMsg('Tap “Use my location” to auto-fill, or drop a pin on the map.', 'info')
        // If permission was already granted before, locate silently (no extra prompt).
        try {
            if (navigator.permissions?.query) {
                const st = await navigator.permissions.query({ name: 'geolocation' })
                if (st.state === 'granted') useMyLocation()
            }
        } catch (e) { /* permissions API unsupported — the button still works */ }
    }
})

onBeforeUnmount(() => {
    if (map) { map.remove(); map = null; marker = null }
    if (gmap) { gmap = null; gmarker = null; if (mapEl.value) mapEl.value.replaceChildren() }
})

const errors = ref({})
const generalError = ref('')
const busy = ref(false)

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

const isStepDone = (s) => Array.isArray(s.saves) && s.saves.length > 0 && s.saves.every((k) => completion.steps[k])

const toggleFocus = (opt) => {
    const i = form.focus_areas.indexOf(opt)
    if (i === -1) form.focus_areas.push(opt)
    else form.focus_areas.splice(i, 1)
}
const pick = (e, key) => { files[key] = e.target.files?.[0] || null }

const goTo = (i) => { if (!busy.value) { errors.value = {}; generalError.value = ''; current.value = i } }
const back = () => goTo(Math.max(0, current.value - 1))
const skip = () => goTo(Math.min(steps.length - 1, current.value + 1))

/* ============ VOICE SETUP (Web Speech API) ============ */
const voiceSupported = typeof window !== 'undefined' && !!(window.SpeechRecognition || window.webkitSpeechRecognition)
const voiceOpen = ref(false)
const voiceQ = ref('')
const listening = ref(false)
const interim = ref('')
const lang = ref('en')          // 'en' | 'kn' — Karnataka-first, English default
let recog = null
let runToken = 0                // cancels an in-flight voice loop when language changes / overlay closes

const langCode = (l) => (l === 'kn' ? 'kn-IN' : 'en-IN')

// Bilingual prompts per UI step (only voice-friendly text fields).
const voiceScript = {
    identity: [
        { field: 'registration_number', q: { en: 'What is your organisation registration number?', kn: 'ನಿಮ್ಮ ಸಂಸ್ಥೆಯ ನೋಂದಣಿ ಸಂಖ್ಯೆ ಎಷ್ಟು?' } },
        { field: 'city', q: { en: 'Which city are you based in?', kn: 'ನೀವು ಯಾವ ನಗರದಲ್ಲಿದ್ದೀರಿ?' } },
        { field: 'state_name', q: { en: 'And which state?', kn: 'ಯಾವ ರಾಜ್ಯ?' } },
    ],
    work: [
        { field: 'description', q: { en: 'In a sentence or two, what does your organisation do?', kn: 'ಒಂದೆರಡು ವಾಕ್ಯಗಳಲ್ಲಿ, ನಿಮ್ಮ ಸಂಸ್ಥೆ ಏನು ಮಾಡುತ್ತದೆ?' } },
    ],
}
const doneMsg = { en: 'All done — review and tap Save & continue.', kn: 'ಆಯಿತು — ಪರಿಶೀಲಿಸಿ "ಉಳಿಸಿ ಮತ್ತು ಮುಂದುವರಿಸಿ" ಒತ್ತಿ.' }
const doneSpeak = { en: 'Got it. Please review and continue.', kn: 'ಆಯಿತು. ದಯವಿಟ್ಟು ಪರಿಶೀಲಿಸಿ ಮುಂದುವರಿಸಿ.' }

const wait = (ms) => new Promise((r) => setTimeout(r, ms))

/* ---- voice picking: pick a *natural* voice instead of the buzzy OS default ---- */
let voices = []
function loadVoices() {
    try { voices = window.speechSynthesis?.getVoices() || [] } catch (e) { voices = [] }
}
// Voices load asynchronously — they're often empty on first read and arrive on this event.
if (typeof window !== 'undefined' && window.speechSynthesis) {
    loadVoices()
    try { window.speechSynthesis.addEventListener('voiceschanged', loadVoices) } catch (e) { /* noop */ }
}
function bestVoice(l) {
    if (!voices.length) loadVoices()
    const prefix = l === 'kn' ? 'kn' : 'en'
    const cands = voices.filter((v) => (v.lang || '').toLowerCase().startsWith(prefix))
    if (!cands.length) return null
    const score = (v) => {
        const name = (v.name || '').toLowerCase()
        let s = 0
        if ((v.lang || '').toLowerCase() === langCode(l)) s += 100   // exact region (kn-IN / en-IN)
        if (/natural|neural|enhanced|premium/.test(name)) s += 40    // far smoother engines
        if (/google/.test(name)) s += 25
        if (/microsoft/.test(name)) s += 18
        if (l === 'en' && /india|en-in|ravi|heera|prabhat|kavya|aditi/.test(name)) s += 12
        if (v.localService === false) s += 5                         // cloud voices tend to be richer
        return s
    }
    return cands.slice().sort((a, b) => score(b) - score(a))[0] || null
}
// True when the device can actually *speak* Kannada (vs. only recognise it).
const knVoiceAvailable = () => !!bestVoice('kn')

function speak(text, l) {
    return new Promise((resolve) => {
        try {
            if (!window.speechSynthesis) return resolve()
            const v = bestVoice(l)
            // Kannada chosen but no Kannada voice on this device → stay silent; the text is on screen.
            if (l === 'kn' && !v) return resolve()
            window.speechSynthesis.cancel()
            const u = new SpeechSynthesisUtterance(text)
            if (v) u.voice = v
            u.lang = v?.lang || langCode(l)
            u.rate = 0.95; u.pitch = 1.02; u.volume = 1   // a touch slower + warmer = less robotic
            u.onend = resolve; u.onerror = resolve
            window.speechSynthesis.speak(u)
        } catch (e) { resolve() }
    })
}

function listenOnce() {
    return new Promise((resolve) => {
        const SR = window.SpeechRecognition || window.webkitSpeechRecognition
        if (!SR) return resolve('')
        const r = new SR()
        r.lang = langCode(lang.value); r.interimResults = true; r.maxAlternatives = 1; r.continuous = false
        let finalT = ''
        listening.value = true
        r.onresult = (e) => {
            let t = ''
            for (let i = 0; i < e.results.length; i++) {
                t += e.results[i][0].transcript
                if (e.results[i].isFinal) finalT += e.results[i][0].transcript
            }
            interim.value = t
        }
        r.onerror = () => { listening.value = false; resolve(finalT.trim()) }
        r.onend = () => { listening.value = false; resolve((finalT || interim.value || '').trim()) }
        recog = r
        try { r.start() } catch (e) { listening.value = false; resolve('') }
    })
}

function startVoice() {
    if (!voiceSupported) { generalError.value = 'Voice input needs Chrome. Please type instead.'; return }
    const script = voiceScript[stepKey.value]
    if (!script || !script.length) return
    loadVoices()
    voiceOpen.value = true
    runVoice()
}

// Guided loop; `runToken` lets a language switch (or Stop) cancel an in-flight run cleanly.
async function runVoice() {
    const myRun = ++runToken
    const script = voiceScript[stepKey.value]
    if (!script || !script.length) { voiceOpen.value = false; return }
    interim.value = ''
    for (const item of script) {
        if (!voiceOpen.value || myRun !== runToken) return
        voiceQ.value = item.q[lang.value]
        interim.value = ''
        await speak(item.q[lang.value], lang.value)
        if (!voiceOpen.value || myRun !== runToken) return
        await wait(150)
        const ans = await listenOnce()
        if (myRun !== runToken) return
        if (ans) form[item.field] = ans
        await wait(250)
    }
    if (voiceOpen.value && myRun === runToken) {
        voiceQ.value = doneMsg[lang.value]
        await speak(doneSpeak[lang.value], lang.value)
        await wait(700)
        if (myRun === runToken) voiceOpen.value = false
    }
}

function setLang(l) {
    if (l === lang.value) return
    lang.value = l
    // interrupt whatever's speaking/listening and re-run this step in the new language
    try { recog?.stop() } catch (e) { /* noop */ }
    try { window.speechSynthesis?.cancel() } catch (e) { /* noop */ }
    if (voiceOpen.value) runVoice()
}

function stopVoice() {
    runToken++           // cancel any in-flight runVoice loop
    voiceOpen.value = false
    listening.value = false
    interim.value = ''
    try { recog?.stop() } catch (e) { /* noop */ }
    try { window.speechSynthesis?.cancel() } catch (e) { /* noop */ }
}

const buildBody = (key) => {
    if (key === 'documents') {
        const fd = new FormData()
        fd.append('step', 'documents')
        if (files.logo) fd.append('logo', files.logo)
        if (files.registration_certificate) fd.append('registration_certificate', files.registration_certificate)
        if (files.pan_card) fd.append('pan_card', files.pan_card)
        return { body: fd, json: false, skipIfEmpty: !files.logo && !files.registration_certificate && !files.pan_card }
    }
    const payload = { step: key }
    if (key === 'legal') Object.assign(payload, { registration_number: form.registration_number, pan: form.pan, legal_structure: form.legal_structure })
    if (key === 'address') {
        const fullAddress = [form.address_line1, form.address_line2].map((s) => (s || '').trim()).filter(Boolean).join(', ')
        Object.assign(payload, {
            address: fullAddress || form.address,
            city: form.city,
            state_name: form.state_name,
            postal_code: form.pincode || '',
            latitude: picked.lat ?? '',
            longitude: picked.lng ?? '',
        })
    }
    if (key === 'about') Object.assign(payload, { description: form.description, focus_areas: form.focus_areas })
    return { body: JSON.stringify(payload), json: true, skipIfEmpty: false }
}

// Persist every backend step grouped under the current UI step.
const saveCurrent = async () => {
    const ui = steps[current.value]
    for (const bkey of ui.saves) {
        const { body, json, skipIfEmpty } = buildBody(bkey)
        if (skipIfEmpty) continue
        const headers = { Accept: 'application/json', 'X-CSRF-TOKEN': csrf() }
        if (json) headers['Content-Type'] = 'application/json'
        const res = await fetch('/register/ngo/setup/step', { method: 'POST', headers, body })
        const data = await res.json()
        if (!res.ok || !data.success) {
            errors.value = data.errors || {}
            generalError.value = data.message || (Object.keys(errors.value).length ? 'Please fix the highlighted fields.' : 'Could not save. Please try again.')
            return false
        }
        if (data.completion) { completion.steps = data.completion.steps; completion.percent = data.completion.percent }
    }
    return true
}

const saveAndNext = async () => {
    errors.value = {}
    generalError.value = ''
    busy.value = true
    try {
        const ok = await saveCurrent()
        if (ok) current.value = Math.min(steps.length - 1, current.value + 1)
    } catch (e) {
        generalError.value = 'Network error while saving. Please try again.'
    } finally {
        busy.value = false
    }
}

const finalize = async () => {
    generalError.value = ''
    busy.value = true
    try {
        const res = await fetch('/register/ngo/setup/finalize', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() },
            body: JSON.stringify({}),
        })
        const data = await res.json()
        if (!res.ok || !data.success) { generalError.value = data.message || 'Could not submit. Please try again.'; return }
        window.location.href = data.redirect_url || props.dashboardUrl
    } catch (e) {
        generalError.value = 'Network error while submitting. Please try again.'
    } finally {
        busy.value = false
    }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap');

.fkw {
    --ink: #0d1f5c; --ink-2: #11286e; --ink-deep: #081640;
    --gold: #f2b40c; --gold-soft: #f7c948; --magenta: #c12a63; --emerald: #1f8a5b;
    --paper: #f7f0df; --char: #1c2230;
    --font-display: 'Fraunces','Playfair Display',Georgia,serif;
    --font-body: 'Hanken Grotesk',ui-sans-serif,system-ui,'Segoe UI',sans-serif;
    position: relative; isolation: isolate; overflow: hidden; font-family: var(--font-body); color: var(--char);
    min-height: 100%; padding: clamp(24px, 4vw, 64px) clamp(16px, 4vw, 48px);
    background: radial-gradient(120% 120% at 85% -10%, #1b3aa0 0%, var(--ink-2) 40%, var(--ink-deep) 100%);
}
.fkw-display { font-family: var(--font-display); font-optical-sizing: auto; }
.fkw-grain { position: absolute; inset: 0; z-index: -1; opacity: .5; mix-blend-mode: overlay; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E"); }

.fkw-wrap { width: 100%; max-width: 1080px; margin: 0 auto; display: grid; grid-template-columns: .82fr 1.18fr; gap: clamp(20px, 3vw, 40px); align-items: start; }

/* rail */
.fkw-rail { color: #f4eeda; padding: clamp(12px, 2vw, 28px) clamp(8px, 1.5vw, 18px); position: sticky; top: 24px; }
.fkw-eyebrow { display: inline-flex; align-items: center; gap: .55em; font-size: .74rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: var(--gold-soft); margin: 0; }
.fkw-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold); box-shadow: 0 0 0 4px rgba(242,180,12,.2); }
.fkw-rail__title { font-weight: 600; font-size: clamp(1.6rem, 3vw, 2.5rem); line-height: 1.04; letter-spacing: -.02em; margin: .8rem 0 0; }
.fkw-rail__sub { color: #d6d3c4; line-height: 1.55; font-size: .98rem; margin: .9rem 0 1.6rem; max-width: 36ch; }
.fkw-progress { margin-top: 1.25rem; margin-bottom: 1.6rem; }
.fkw-progress__bar { position: relative; height: 10px; border-radius: 999px; background: rgba(247,240,223,.16); overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,.28); }
.fkw-progress__bar span { position: relative; display: block; height: 100%; border-radius: 999px; overflow: hidden; background: linear-gradient(90deg, var(--gold), var(--gold-soft)); box-shadow: 0 0 12px rgba(242,180,12,.55), inset 0 1px 0 rgba(255,255,255,.5); transition: width .6s cubic-bezier(.2,.7,.2,1); }
/* shimmer sweep, clipped to the filled portion */
.fkw-progress__bar span::after { content: ""; position: absolute; inset: 0; background: linear-gradient(100deg, transparent 18%, rgba(255,255,255,.55) 50%, transparent 82%); transform: translateX(-100%); animation: fkwShimmer 2.8s ease-in-out infinite; }
@keyframes fkwShimmer { 0% { transform: translateX(-100%); } 55%, 100% { transform: translateX(100%); } }
.fkw-progress__pct { display: inline-block; margin-top: .6rem; font-size: .8rem; color: #d6d3c4; font-weight: 600; letter-spacing: .01em; }
.fkw-progress__pct b { color: var(--gold-soft); font-weight: 800; }
@media (prefers-reduced-motion: reduce) { .fkw-progress__bar span::after { animation: none; } .fkw-progress__bar span { transition: none; } }
.fkw-steps { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: .3rem; }
.fkw-steps li { display: flex; align-items: center; gap: .8rem; padding: .6rem .7rem; border-radius: 12px; cursor: pointer; color: #c7c4b4; transition: background .2s ease, color .2s ease; }
.fkw-steps li:hover { background: rgba(247,240,223,.06); }
.fkw-steps li.is-active { background: rgba(247,240,223,.1); color: #fff; }
.fkw-steps__mark { display: grid; place-items: center; width: 28px; height: 28px; border-radius: 50%; border: 1px solid rgba(247,240,223,.3); font-size: .82rem; font-weight: 700; flex-shrink: 0; }
.fkw-steps li.is-done .fkw-steps__mark { background: var(--emerald); border-color: var(--emerald); color: #fff; }
.fkw-steps__mark svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round; }
.fkw-steps__label { font-weight: 500; font-size: .96rem; }
.fkw-rail__skip { display: inline-block; margin-top: 1.6rem; color: var(--gold-soft); font-weight: 600; font-size: .9rem; text-decoration: none; }
.fkw-rail__skip:hover { color: var(--gold); }

/* panel */
.fkw-panel { background: #fffdf6; border-radius: 24px; padding: clamp(24px, 3vw, 44px); box-shadow: 0 40px 80px -30px rgba(0,0,0,.55); min-height: 420px; display: flex; flex-direction: column; }
.fkw-alert { background: #fdecef; color: #a01446; border: 1px solid #f3c4d2; border-radius: 12px; padding: .7rem .9rem; font-size: .9rem; margin: 0 0 1.2rem; }
.fkw-step { flex: 1; }
.fkw-step__title { font-size: clamp(1.5rem, 2.6vw, 2.1rem); font-weight: 600; color: var(--ink); margin: 0; letter-spacing: -.01em; }
.fkw-step__hint { margin: .4rem 0 1.6rem; color: #6a6e7a; font-size: .96rem; }
.fkw-field { display: flex; flex-direction: column; gap: .4rem; margin-bottom: 1.1rem; }
.fkw-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.fkw-field label, .fkw-file label { font-size: .84rem; font-weight: 600; color: #3a3f4c; }
.fkw-muted { color: #9a8e6e; font-weight: 500; }
.fkw-field input, .fkw-field select, .fkw-field textarea { width: 100%; padding: .85em 1em; border-radius: 12px; border: 1px solid #ddd2b6; background: #fdfbf3; font-family: inherit; font-size: 16px; color: var(--char); transition: border-color .2s ease, box-shadow .2s ease; }
.fkw-field input:focus, .fkw-field select:focus, .fkw-field textarea:focus { outline: none; border-color: var(--ink); box-shadow: 0 0 0 3px rgba(13,31,92,.12); }
.fkw-chips { display: flex; flex-wrap: wrap; gap: .5rem; }
.fkw-chip { padding: .55em 1em; border-radius: 999px; border: 1px solid #ddd2b6; background: #fdfbf3; font: inherit; font-size: .86rem; font-weight: 600; color: #4a4e5a; cursor: pointer; transition: all .2s ease; }
.fkw-chip:hover { border-color: var(--ink); }
.fkw-chip:active { transform: scale(.97); }
.fkw-chip.is-on { background: var(--ink); border-color: var(--ink); color: var(--paper); box-shadow: 0 8px 18px -10px rgba(13,31,92,.6); }
.fkw-file { display: flex; flex-direction: column; gap: .4rem; margin-bottom: 1.2rem; }
.fkw-file input[type=file] { font-size: .9rem; color: #4a4e5a; }
.fkw-file input[type=file]::file-selector-button { margin-right: .8rem; padding: .5em 1em; border-radius: 10px; border: 1px solid var(--ink); background: var(--ink); color: var(--paper); font-weight: 600; cursor: pointer; }
.fkw-err { color: #c0285f; font-size: .8rem; }
.fkw-hint { color: var(--emerald); font-size: .82rem; }
.fkw-review { list-style: none; margin: 0 0 1rem; padding: 0; display: flex; flex-direction: column; gap: .5rem; }
.fkw-review li { display: flex; align-items: center; justify-content: space-between; padding: .8em 1em; border-radius: 12px; background: #f5efdc; border: 1px solid #e6dcc0; font-weight: 600; font-size: .88rem; color: #8a6d12; }
.fkw-review li.ok { background: #e8f4ec; border-color: #bfe2cc; color: var(--emerald); }
.fkw-review li span { color: var(--char); }
.fkw-review__note { color: #6a6e7a; font-size: .88rem; margin: 0; }

.fkw-actions { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-top: 1.8rem; padding-top: 1.4rem; border-top: 1px solid #ece2c6; }
.fkw-actions__right { display: flex; align-items: center; gap: .6rem; margin-left: auto; }
.fkw-btn { display: inline-flex; align-items: center; justify-content: center; gap: .4em; padding: .85em 1.5em; border-radius: 999px; border: none; font: inherit; font-weight: 700; font-size: .94rem; line-height: 1; white-space: nowrap; cursor: pointer; transition: transform .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease; }
.fkw-btn:disabled { opacity: .6; cursor: progress; }
.fkw-btn--gold { background: var(--gold); color: #2a1c00; box-shadow: 0 14px 30px -14px rgba(242,180,12,.75); }
.fkw-btn--gold:hover:not(:disabled) { transform: translateY(-2px); }
.fkw-btn--gold:active:not(:disabled) { transform: scale(.97); }
.fkw-btn--ghost { background: transparent; color: var(--ink); border: 1px solid rgba(13,31,92,.3); }
.fkw-btn--ghost:hover:not(:disabled) { background: rgba(13,31,92,.06); }
.fkw-btn--text { background: transparent; color: #6a6e7a; }
.fkw-btn--text:hover:not(:disabled) { color: var(--ink); }

/* voice CTA in the rail */
.fkw-voice-cta { display: flex; align-items: center; gap: .8rem; width: 100%; text-align: left; margin: 0 0 1.4rem; padding: .85rem 1rem; border-radius: 16px; border: 1px solid rgba(242,180,12,.4); background: rgba(242,180,12,.1); color: #f4eeda; cursor: pointer; transition: transform .25s ease, background .25s ease, box-shadow .25s ease; }
.fkw-voice-cta:hover { transform: translateY(-2px); background: rgba(242,180,12,.18); box-shadow: 0 16px 34px -18px rgba(242,180,12,.6); }
.fkw-voice-cta:active { transform: scale(.99); }
.fkw-voice-cta__mic { flex-shrink: 0; display: grid; place-items: center; width: 40px; height: 40px; border-radius: 50%; background: var(--gold); color: #2a1c00; }
.fkw-voice-cta__mic svg { width: 21px; height: 21px; }
.fkw-voice-cta__txt { display: flex; flex-direction: column; line-height: 1.25; }
.fkw-voice-cta__txt strong { font-weight: 700; font-size: .96rem; }
.fkw-voice-cta__txt em { font-style: normal; color: #c9c6b7; font-size: .8rem; }
.fkw-voice-cta__go { margin-left: auto; color: var(--gold-soft); font-weight: 700; font-size: 1.1rem; }

/* voice overlay */
.fkv { position: fixed; inset: 0; z-index: 100001; display: flex; align-items: center; justify-content: center; padding: 24px; background: rgba(8,16,40,.72); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); font-family: var(--font-body); }
.fkv__panel { position: relative; width: 100%; max-width: 380px; text-align: center; color: #f4eeda; }
.fkv__x { position: absolute; top: -10px; right: -4px; width: 38px; height: 38px; border-radius: 50%; border: none; background: rgba(255,255,255,.12); color: #fff; font-size: 1.4rem; line-height: 1; cursor: pointer; }
.fkv__x:hover { background: rgba(255,255,255,.22); }
.fkv__mic { width: 96px; height: 96px; margin: 0 auto 1.4rem; display: grid; place-items: center; border-radius: 50%; background: var(--gold); color: #2a1c00; box-shadow: 0 0 0 0 rgba(242,180,12,.5); }
.fkv__mic svg { width: 44px; height: 44px; }
.fkv__mic.listening { animation: fkvPulse 1.4s ease-out infinite; }
@keyframes fkvPulse { 0% { box-shadow: 0 0 0 0 rgba(242,180,12,.5); } 100% { box-shadow: 0 0 0 28px rgba(242,180,12,0); } }
.fkv__lang { display: inline-flex; gap: 2px; margin: 0 auto 1.5rem; padding: 3px; border-radius: 999px; background: rgba(247,240,223,.1); border: 1px solid rgba(247,240,223,.18); }
.fkv__lang button { padding: .42em 1.1em; border: none; border-radius: 999px; background: transparent; color: #d6d3c4; font: inherit; font-weight: 600; font-size: .86rem; cursor: pointer; transition: background .2s ease, color .2s ease; }
.fkv__lang button.on { background: var(--gold); color: #2a1c00; box-shadow: 0 6px 16px -8px rgba(242,180,12,.7); }
.fkv__q { font-family: var(--font-display); font-size: 1.4rem; font-weight: 600; line-height: 1.25; margin: 0 0 .6rem; }
.fkv__note { font-size: .82rem; line-height: 1.5; color: #c9c6b7; margin: 0 0 1.2rem; }
.fkv__interim { min-height: 1.5em; color: var(--gold-soft); font-size: 1rem; font-weight: 500; margin: 0 0 1.6rem; }
.fkv__stop { padding: .7em 1.5em; border-radius: 999px; border: 1px solid rgba(247,240,223,.4); background: rgba(247,240,223,.08); color: #f4eeda; font: inherit; font-weight: 600; cursor: pointer; transition: background .2s ease; }
.fkv__stop:hover { background: rgba(247,240,223,.16); }
.fkv-enter-active, .fkv-leave-active { transition: opacity .25s ease; }
.fkv-enter-from, .fkv-leave-to { opacity: 0; }
@media (prefers-reduced-motion: reduce) { .fkv__mic.listening { animation: none; } }

@media (max-width: 900px) {
    .fkw { padding: 16px 16px 0; }
    .fkw-wrap { grid-template-columns: 1fr; gap: 14px; }
    /* compact mobile hero — the progress bar carries the context */
    .fkw-rail { position: static; padding: 4px 0 0; }
    .fkw-rail__sub { display: none; }
    .fkw-rail__title { font-size: 1.5rem; margin-bottom: .35rem; }
    /* sub is hidden on mobile, so the bar needs its own breathing room above */
    .fkw-progress { margin-top: 1.2rem; margin-bottom: 1.2rem; }
    .fkw-steps { display: none; }
    .fkw-rail__skip { margin-top: .9rem; }
    .fkw-panel { padding: 22px 18px; border-radius: 22px 22px 0 0; min-height: 0; }
    /* sticky action bar */
    .fkw-actions { position: sticky; bottom: 0; z-index: 5; gap: .5rem; background: #fffdf6; margin: 1.4rem -18px 0; padding: 1rem 18px calc(1rem + env(safe-area-inset-bottom)); box-shadow: 0 -10px 24px -14px rgba(0,0,0,.2); }
    .fkw-actions__right { gap: .25rem; }
    /* keep all three controls on one line; the primary action gets priority space */
    .fkw-actions .fkw-btn { padding: .85em 1.15em; font-size: .92rem; }
    .fkw-actions .fkw-btn--text { padding-left: .6em; padding-right: .6em; }
    .fkw-actions .fkw-btn--gold { flex: 0 1 auto; min-width: 9.5rem; box-shadow: 0 12px 26px -14px rgba(242,180,12,.85); }
}
@media (max-width: 520px) { .fkw-grid2 { grid-template-columns: 1fr; gap: .9rem; } }

/* ============ Smart location card ============ */
.fkw-loc {
    margin-top: 1.25rem;
    border: 1px solid rgba(20,33,61,.10);
    border-radius: 18px;
    background: linear-gradient(180deg, #fffdf6 0%, #fbf7ea 100%);
    box-shadow: 0 14px 40px -26px rgba(20,33,61,.45);
    overflow: hidden;
}
.fkw-loc__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 1rem 1.1rem;
}
.fkw-loc__title { display: flex; align-items: center; gap: .75rem; min-width: 0; }
.fkw-loc__pin {
    flex: 0 0 auto;
    display: inline-flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 12px; color: #fff;
    background: linear-gradient(135deg, #f2b40c, #b8860b);
    box-shadow: 0 8px 18px -8px rgba(242,180,12,.9);
}
.fkw-loc__title strong { display: block; font-size: 1rem; color: #14213d; line-height: 1.2; }
.fkw-loc__title small { display: block; color: #6b7280; font-size: .82rem; margin-top: 2px; }
.fkw-loc__btn {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .7em 1.1em; border: 0; border-radius: 999px; cursor: pointer;
    font-weight: 700; font-size: .9rem; color: #14213d; white-space: nowrap;
    background: linear-gradient(135deg, #ffd34d, #f2b40c);
    box-shadow: 0 10px 22px -12px rgba(242,180,12,.95);
    transition: transform .18s ease, filter .18s ease, opacity .18s ease;
}
.fkw-loc__btn:hover:not(:disabled) { transform: translateY(-2px); filter: brightness(1.04); }
.fkw-loc__btn:disabled { opacity: .7; cursor: progress; }
.fkw-spin { animation: fkw-spin 0.8s linear infinite; }
@keyframes fkw-spin { to { transform: rotate(360deg); } }
.fkw-loc__msg {
    margin: 0; padding: .55rem 1.1rem; font-size: .85rem; line-height: 1.45;
    border-top: 1px solid rgba(20,33,61,.06);
}
.fkw-loc__msg.is-info { color: #4b5563; background: rgba(20,33,61,.03); }
.fkw-loc__msg.is-ok { color: #166534; background: rgba(22,101,52,.08); }
.fkw-loc__msg.is-warn { color: #9a3412; background: rgba(154,52,18,.08); }
.fkw-loc__map {
    height: 290px; width: 100%; background: #e8eef0;
    border-top: 1px solid rgba(20,33,61,.06);
    border-bottom: 1px solid rgba(20,33,61,.06);
}
.fkw-loc__map :deep(.leaflet-container) { font: inherit; background: #e8eef0; }
.fkw-loc__map :deep(.leaflet-control-attribution) { font-size: 10px; }

/* search box + results — sits ABOVE the Leaflet map (which builds its own stacking context) */
.fkw-loc__search { position: relative; z-index: 1200; margin: 0 1.1rem 0; }
.fkw-loc__map { position: relative; z-index: 0; }
.fkw-loc__search input {
    width: 100%; padding: .7rem .85rem .7rem 2.2rem;
    border: 1px solid rgba(20,33,61,.16); border-radius: 12px;
    font-size: .92rem; color: #14213d; background: #fff;
    transition: border-color .15s ease, box-shadow .15s ease;
}
.fkw-loc__search input:focus { outline: none; border-color: #f2b40c; box-shadow: 0 0 0 3px rgba(242,180,12,.18); }
.fkw-loc__searchic { position: absolute; left: .8rem; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
.fkw-loc__searchspin { position: absolute; right: .8rem; top: 50%; transform: translateY(-50%); color: #b8860b; }
.fkw-loc__results {
    position: absolute; z-index: 30; left: 0; right: 0; top: calc(100% + 6px);
    margin: 0; padding: .3rem; list-style: none;
    background: #fff; border: 1px solid rgba(20,33,61,.12); border-radius: 12px;
    box-shadow: 0 18px 40px -16px rgba(20,33,61,.5); max-height: 260px; overflow-y: auto;
}
.fkw-loc__results li {
    display: flex; align-items: flex-start; gap: .5rem;
    padding: .55rem .6rem; border-radius: 9px; cursor: pointer;
    font-size: .85rem; color: #1f2937; line-height: 1.35;
}
.fkw-loc__results li:hover { background: #fbf3da; }
.fkw-loc__results li svg { color: #b8860b; margin-top: 2px; flex: 0 0 auto; }

/* resolved city/state chips (replace old inputs) */
.fkw-loc__resolved { display: grid; grid-template-columns: repeat(3, 1fr); gap: .6rem; padding: .9rem 1.1rem; }
.fkw-loc__chip {
    border: 1px solid rgba(20,33,61,.10); border-radius: 12px; padding: .55rem .8rem;
    background: #fff;
}
.fkw-loc__chip small { display: block; font-size: .68rem; letter-spacing: .04em; text-transform: uppercase; color: #9ca3af; }
.fkw-loc__chip strong { display: block; font-size: .98rem; color: #14213d; margin-top: 2px; }
.fkw-loc__chip--input input {
    width: 100%; border: 0; padding: 2px 0 0; margin-top: 2px;
    font-size: .98rem; font-weight: 700; color: #14213d; background: transparent;
}
.fkw-loc__chip--input input:focus { outline: none; }
.fkw-loc__chip--input { transition: border-color .15s ease, box-shadow .15s ease; }
.fkw-loc__chip--input:focus-within { border-color: #f2b40c; box-shadow: 0 0 0 3px rgba(242,180,12,.16); }
.fkw-opt { font-weight: 400; color: #9ca3af; font-size: .82em; }

/* custom NGO map pin */
:deep(.fk-ngopin-wrap) { background: none; border: 0; }
:deep(.fk-ngopin) { filter: drop-shadow(0 6px 6px rgba(20,33,61,.35)); transition: transform .15s ease; }
:deep(.fk-ngopin):hover { transform: translateY(-2px) scale(1.04); }
:deep(.leaflet-marker-draggable) { cursor: grab; }
:deep(.leaflet-marker-draggable:active) { cursor: grabbing; }

@media (max-width: 520px) {
    .fkw-loc__head { gap: .75rem; }
    .fkw-loc__btn { width: 100%; justify-content: center; }
    .fkw-loc__map { height: 240px; }
    .fkw-loc__resolved { grid-template-columns: 1fr; }
}
</style>
