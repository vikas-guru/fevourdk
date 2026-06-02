/**
 * FevourdK portal i18n — lightweight, no-dependency Kannada (ಕನ್ನಡ) ⇄ English.
 *
 * Philosophy mirrors the public microsite: the Kannada string sits INLINE next to
 * the English at the call site — `t('Home', 'ಮುಖಪುಟ')` — so there is no separate
 * key catalog that can drift out of sync. `lang` is a Vue ref, so `t()` used in a
 * template re-renders reactively when the language flips.
 *
 * Persists to localStorage + a path=/ cookie; auto-detects navigator.language on
 * first visit (kn* => Kannada). Toggles `html.lang-kn` for the Kannada font stack.
 */
import { ref } from 'vue'

const KEY = 'fk_lang'
const ONE_YEAR = 60 * 60 * 24 * 365

function readCookie(name) {
    const m = typeof document !== 'undefined'
        ? document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]+)'))
        : null
    return m ? decodeURIComponent(m[1]) : null
}

function valid(l) {
    return l === 'kn' || l === 'en'
}

function detect() {
    if (typeof window === 'undefined') {
        return 'en'
    }
    try {
        const q = new URLSearchParams(window.location.search).get('lang')
        if (valid(q)) return q
    } catch (e) { /* ignore */ }
    let s = null
    try { s = localStorage.getItem(KEY) } catch (e) { /* private mode */ }
    if (!valid(s)) s = readCookie(KEY)
    if (valid(s)) return s
    const nav = (navigator.language || navigator.userLanguage || '').toLowerCase()
    return nav.indexOf('kn') === 0 ? 'kn' : 'en'
}

export const lang = ref(detect())

function applyHtml(l) {
    if (typeof document === 'undefined') return
    const el = document.documentElement
    el.classList.toggle('lang-kn', l === 'kn')
    el.lang = l
}

export function setLang(l) {
    if (!valid(l)) return
    lang.value = l
    try { localStorage.setItem(KEY, l) } catch (e) { /* ignore */ }
    if (typeof document !== 'undefined') {
        document.cookie = `${KEY}=${l};path=/;max-age=${ONE_YEAR};samesite=lax`
    }
    applyHtml(l)
}

export function toggleLang() {
    setLang(lang.value === 'kn' ? 'en' : 'kn')
}

/** Inline translate: returns Kannada when active (and provided), else English. */
export function t(en, kn) {
    return lang.value === 'kn' && kn ? kn : en
}

// Apply the resolved language on module load (before first paint of the SPA).
applyHtml(lang.value)
