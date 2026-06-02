/**
 * useLocation — a tiny module-level reactive store for the user's detected/chosen location,
 * persisted to localStorage so it survives reloads and prefills forms across the app.
 *
 * Shape: { city, state, district, lat, lng, source }
 *   source: 'geolocation' | 'manual' | '' (where the value came from)
 *
 * All window/localStorage access is SSR-guarded (Inertia can server-render).
 */
import { reactive, readonly, computed } from 'vue'

const STORAGE_KEY = 'fevourdk_location'

const EMPTY = { city: '', state: '', district: '', lat: null, lng: null, source: '' }

const isBrowser = typeof window !== 'undefined'

function load() {
    if (!isBrowser) return { ...EMPTY }
    try {
        const raw = window.localStorage.getItem(STORAGE_KEY)
        if (!raw) return { ...EMPTY }
        const parsed = JSON.parse(raw)
        return { ...EMPTY, ...(parsed && typeof parsed === 'object' ? parsed : {}) }
    } catch (e) {
        return { ...EMPTY }
    }
}

// Module-level singleton: every import shares this same reactive object.
const state = reactive(load())

function persist() {
    if (!isBrowser) return
    try {
        window.localStorage.setItem(STORAGE_KEY, JSON.stringify({ ...state }))
    } catch (e) {
        /* storage full / blocked — non-fatal, the in-memory store still works this session */
    }
}

/** Merge in partial location data and persist. Pass only the keys you have. */
function set(patch = {}) {
    if (!patch || typeof patch !== 'object') return
    for (const key of Object.keys(EMPTY)) {
        if (key in patch && patch[key] !== undefined && patch[key] !== null && patch[key] !== '') {
            state[key] = patch[key]
        }
    }
    persist()
}

/** Wipe the stored location (keeps the onboarded flag untouched). */
function clear() {
    Object.assign(state, EMPTY)
    if (isBrowser) {
        try { window.localStorage.removeItem(STORAGE_KEY) } catch (e) { /* noop */ }
    }
}

const hasLocation = computed(() => !!(state.city || state.state || state.district))

export function useLocation() {
    return {
        // readonly so consumers mutate only through set()/clear()
        location: readonly(state),
        hasLocation,
        get: () => ({ ...state }),
        set,
        clear,
    }
}

export default useLocation
