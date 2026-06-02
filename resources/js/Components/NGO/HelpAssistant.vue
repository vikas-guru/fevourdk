<template>
    <teleport to="body">
        <!-- Floating launcher -->
        <button
            v-if="!open"
            type="button"
            class="dha-fab"
            :style="{ '--accent': accent }"
            aria-label="Open help"
            @click="openPanel"
        >
            <span class="dha-fab__icon" aria-hidden="true">✦</span>
            <span class="dha-fab__label">Help</span>
            <span v-if="hasTour" class="dha-fab__pulse" aria-hidden="true" />
        </button>

        <!-- Scrim + panel -->
        <transition name="dha-fade">
            <div v-if="open" class="dha-scrim" @click="closePanel" />
        </transition>

        <transition :name="sheetTransition">
            <section
                v-if="open"
                class="dha-panel"
                role="dialog"
                aria-modal="true"
                aria-label="Help center"
                :style="{ '--accent': accent }"
            >
                <!-- Header -->
                <header class="dha-head">
                    <div class="dha-head__id">
                        <span class="dha-avatar" aria-hidden="true">✦</span>
                        <div class="min-w-0">
                            <p class="dha-head__name">Disha</p>
                            <p class="dha-head__role">your guide · here to help</p>
                        </div>
                    </div>
                    <button type="button" class="dha-x" aria-label="Close help" @click="closePanel">×</button>
                </header>

                <!-- Article reader -->
                <div v-if="activeArticle" class="dha-body">
                    <button type="button" class="dha-back" @click="activeArticle = null">← All guides</button>
                    <h3 class="dha-article__title">{{ activeArticle.title }}</h3>
                    <!-- Body is authored, trusted HTML from HelpCenter.php -->
                    <div class="dha-prose" v-html="activeArticle.body" />

                    <div v-if="relatedOf(activeArticle).length" class="dha-related">
                        <p class="dha-related__label">Related</p>
                        <button
                            v-for="r in relatedOf(activeArticle)"
                            :key="r.slug"
                            type="button"
                            class="dha-related__item"
                            @click="openArticle(r)"
                        >
                            <span aria-hidden="true">{{ r.icon }}</span> {{ r.title }}
                        </button>
                    </div>
                </div>

                <!-- Browse / search -->
                <div v-else class="dha-body">
                    <p class="dha-greeting">Hi! How can I help you today?</p>

                    <button v-if="hasTour" type="button" class="dha-tour" @click="takeTour">
                        <span class="dha-tour__icon" aria-hidden="true">✦</span>
                        <span class="dha-tour__text">
                            <span class="dha-tour__title">Take a tour of this page</span>
                            <span class="dha-tour__sub">A quick guided walkthrough</span>
                        </span>
                        <span class="dha-tour__chev" aria-hidden="true">→</span>
                    </button>

                    <div class="dha-search">
                        <span class="dha-search__icon" aria-hidden="true">⌕</span>
                        <input
                            v-model="query"
                            type="search"
                            class="dha-search__input"
                            placeholder="Search guides… e.g. donations, website"
                            aria-label="Search guides"
                        >
                    </div>

                    <div v-if="!query" class="dha-chips">
                        <button
                            v-for="c in categories"
                            :key="c.key"
                            type="button"
                            class="dha-chip"
                            :class="{ 'is-on': activeCat === c.key }"
                            @click="activeCat = activeCat === c.key ? null : c.key"
                        >
                            <span aria-hidden="true">{{ c.icon }}</span> {{ c.label }}
                        </button>
                    </div>

                    <p v-if="loading" class="dha-muted">Loading guides…</p>
                    <p v-else-if="!filtered.length" class="dha-muted">
                        No guides match “{{ query }}”. Try a different word, or open the full help center below.
                    </p>

                    <ul v-else class="dha-list">
                        <li v-for="a in filtered" :key="a.slug">
                            <button type="button" class="dha-card" @click="openArticle(a)">
                                <span class="dha-card__icon" aria-hidden="true">{{ a.icon }}</span>
                                <span class="dha-card__text">
                                    <span class="dha-card__title">{{ a.title }}</span>
                                    <span class="dha-card__excerpt">{{ a.excerpt }}</span>
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Footer -->
                <footer class="dha-foot">
                    <a href="/ngo/help" class="dha-foot__link">Open the full help center →</a>
                </footer>
            </section>
        </transition>
    </teleport>
</template>

<script setup>
import { computed, onBeforeUnmount, ref, watch } from 'vue'
import * as tourController from '@/ngo/tourController'

const props = defineProps({
    accent: { type: String, default: '#f2b40c' },
})

const open = ref(false)
const loading = ref(false)
const loaded = ref(false)
const categories = ref([])
const articles = ref([])
const query = ref('')
const activeCat = ref(null)
const activeArticle = ref(null)

const hasTour = tourController.hasTour

const isMobile = () => typeof window !== 'undefined' && window.innerWidth < 640
const sheetTransition = computed(() => (isMobile() ? 'dha-sheet' : 'dha-pop'))

const filtered = computed(() => {
    const q = query.value.trim().toLowerCase()
    let list = articles.value
    if (q) {
        list = list.filter((a) =>
            `${a.title} ${a.excerpt} ${a.keywords || ''}`.toLowerCase().includes(q),
        )
    } else if (activeCat.value) {
        list = list.filter((a) => a.category === activeCat.value)
    }
    return list
})

function relatedOf(article) {
    const slugs = article?.related || []
    return slugs
        .map((s) => articles.value.find((a) => a.slug === s))
        .filter(Boolean)
}

async function load() {
    if (loaded.value || loading.value) {
        return
    }
    loading.value = true
    try {
        const res = await fetch('/ngo/help/articles', {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        })
        const data = await res.json()
        categories.value = data.categories || []
        articles.value = data.articles || []
        loaded.value = true
    } catch {
        // Leave lists empty; the panel shows the full-help-center link as fallback.
    } finally {
        loading.value = false
    }
}

function lockScroll(on) {
    if (typeof document === 'undefined') {
        return
    }
    document.documentElement.style.overflow = on ? 'hidden' : ''
}

function openPanel() {
    open.value = true
    lockScroll(true)
    load()
}

function closePanel() {
    open.value = false
    lockScroll(false)
    activeArticle.value = null
}

function openArticle(a) {
    activeArticle.value = a
}

function takeTour() {
    closePanel()
    // Let the panel finish closing + scroll unlock before the spotlight measures.
    setTimeout(() => tourController.start(), 220)
}

// Esc closes
function onKey(e) {
    if (e.key === 'Escape' && open.value) {
        closePanel()
    }
}
if (typeof window !== 'undefined') {
    window.addEventListener('keydown', onKey)
}
onBeforeUnmount(() => {
    if (typeof window !== 'undefined') {
        window.removeEventListener('keydown', onKey)
    }
    lockScroll(false)
})

// Reset category when typing a query.
watch(query, (v) => {
    if (v) {
        activeCat.value = null
    }
})
</script>

<style scoped>
/* ---------- Launcher ---------- */
.dha-fab {
    position: fixed;
    right: 1rem;
    bottom: calc(1rem + env(safe-area-inset-bottom, 0px));
    z-index: 70;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1rem 0.7rem 0.85rem;
    border-radius: 9999px;
    border: 1px solid rgba(242, 180, 12, 0.5);
    background: linear-gradient(160deg, #102a6e 0%, #0b1c4f 70%, #081435 100%);
    color: #fff;
    box-shadow: 0 16px 38px -16px rgba(5, 12, 40, 0.85);
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
/* Keep clear of the mobile bottom tab bar */
@media (max-width: 1024px) {
    .dha-fab {
        bottom: calc(5.25rem + env(safe-area-inset-bottom, 0px));
    }
}
.dha-fab:hover {
    transform: translateY(-2px);
}
.dha-fab__icon {
    display: grid;
    place-items: center;
    height: 1.5rem;
    width: 1.5rem;
    border-radius: 9999px;
    background: linear-gradient(180deg, #f7c948, #f2b40c);
    color: #2a1c00;
    font-size: 0.85rem;
    font-weight: 800;
}
.dha-fab__label {
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.01em;
}
.dha-fab__pulse {
    position: absolute;
    top: 0.35rem;
    right: 0.45rem;
    height: 8px;
    width: 8px;
    border-radius: 9999px;
    background: #f2b40c;
    box-shadow: 0 0 0 0 rgba(242, 180, 12, 0.6);
    animation: dha-pulse 2.2s infinite;
}
@keyframes dha-pulse {
    0% { box-shadow: 0 0 0 0 rgba(242, 180, 12, 0.55); }
    70% { box-shadow: 0 0 0 10px rgba(242, 180, 12, 0); }
    100% { box-shadow: 0 0 0 0 rgba(242, 180, 12, 0); }
}

/* ---------- Scrim ---------- */
.dha-scrim {
    position: fixed;
    inset: 0;
    z-index: 71;
    background: rgba(8, 16, 40, 0.5);
    backdrop-filter: blur(2px);
}

/* ---------- Panel ---------- */
.dha-panel {
    position: fixed;
    z-index: 72;
    display: flex;
    flex-direction: column;
    background: #fbf8f1; /* paper */
    color: #1b2440; /* ink */
    overflow: hidden;
}
/* Desktop: anchored card bottom-right */
@media (min-width: 640px) {
    .dha-panel {
        right: 1.25rem;
        bottom: 1.25rem;
        width: 23rem;
        max-height: min(38rem, calc(100vh - 2.5rem));
        border-radius: 22px;
        border: 1px solid rgba(27, 36, 64, 0.1);
        box-shadow: 0 30px 70px -24px rgba(5, 12, 40, 0.55);
    }
}
/* Mobile: bottom sheet */
@media (max-width: 639px) {
    .dha-panel {
        left: 0;
        right: 0;
        bottom: 0;
        max-height: 88vh;
        border-radius: 22px 22px 0 0;
        box-shadow: 0 -20px 60px -20px rgba(5, 12, 40, 0.5);
        padding-bottom: env(safe-area-inset-bottom, 0px);
    }
}

.dha-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.95rem 1rem;
    background: linear-gradient(160deg, #102a6e 0%, #0b1c4f 75%, #081435 100%);
    color: #fff;
}
.dha-head__id {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    min-width: 0;
}
.dha-avatar {
    display: grid;
    place-items: center;
    height: 2.1rem;
    width: 2.1rem;
    border-radius: 9999px;
    background: linear-gradient(180deg, #f7c948, #f2b40c);
    color: #2a1c00;
    font-weight: 800;
    box-shadow: 0 0 0 4px rgba(242, 180, 12, 0.18);
}
.dha-head__name {
    font-family: 'Fraunces', 'Playfair Display', Georgia, serif;
    font-size: 1.15rem;
    font-weight: 600;
    line-height: 1;
}
.dha-head__role {
    font-size: 0.7rem;
    color: rgba(207, 218, 255, 0.85);
    margin-top: 0.15rem;
}
.dha-x {
    flex-shrink: 0;
    height: 2rem;
    width: 2rem;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    font-size: 1.3rem;
    line-height: 1;
    cursor: pointer;
}
.dha-x:hover {
    background: rgba(255, 255, 255, 0.22);
}

.dha-body {
    flex: 1;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    padding: 1rem;
}

.dha-greeting {
    font-family: 'Fraunces', 'Playfair Display', Georgia, serif;
    font-size: 1.1rem;
    color: #1b2440;
    margin-bottom: 0.8rem;
}

.dha-tour {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    padding: 0.8rem 0.9rem;
    border-radius: 14px;
    border: 1px solid rgba(242, 180, 12, 0.45);
    background: linear-gradient(180deg, #fff7e3, #fdeec4);
    text-align: left;
    cursor: pointer;
    transition: transform 0.12s ease;
    margin-bottom: 0.9rem;
}
.dha-tour:hover {
    transform: translateY(-1px);
}
.dha-tour__icon {
    display: grid;
    place-items: center;
    height: 2rem;
    width: 2rem;
    flex-shrink: 0;
    border-radius: 9999px;
    background: linear-gradient(180deg, #f7c948, #f2b40c);
    color: #2a1c00;
    font-weight: 800;
}
.dha-tour__text {
    display: flex;
    flex-direction: column;
    min-width: 0;
    flex: 1;
}
.dha-tour__title {
    font-weight: 700;
    font-size: 0.9rem;
    color: #5a3d00;
}
.dha-tour__sub {
    font-size: 0.74rem;
    color: #8a6a1e;
}
.dha-tour__chev {
    color: #b3801a;
    font-weight: 700;
}

.dha-search {
    position: relative;
    margin-bottom: 0.8rem;
}
.dha-search__icon {
    position: absolute;
    left: 0.7rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a0bf;
    font-size: 1rem;
}
.dha-search__input {
    width: 100%;
    padding: 0.65rem 0.8rem 0.65rem 2.1rem;
    border-radius: 12px;
    border: 1px solid rgba(27, 36, 64, 0.14);
    background: #fff;
    font-size: 16px; /* no iOS zoom */
    color: #1b2440;
}
.dha-search__input:focus {
    outline: none;
    border-color: rgba(242, 180, 12, 0.7);
    box-shadow: 0 0 0 3px rgba(242, 180, 12, 0.18);
}

.dha-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 0.9rem;
}
.dha-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.35rem 0.65rem;
    border-radius: 9999px;
    border: 1px solid rgba(27, 36, 64, 0.14);
    background: #fff;
    font-size: 0.76rem;
    font-weight: 600;
    color: #41506f;
    cursor: pointer;
    transition: all 0.12s ease;
}
.dha-chip.is-on {
    border-color: transparent;
    background: #102a6e;
    color: #fff;
}

.dha-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.dha-card {
    width: 100%;
    display: flex;
    align-items: flex-start;
    gap: 0.7rem;
    padding: 0.75rem 0.8rem;
    border-radius: 14px;
    border: 1px solid rgba(27, 36, 64, 0.1);
    background: #fff;
    text-align: left;
    cursor: pointer;
    transition: transform 0.12s ease, border-color 0.12s ease;
}
.dha-card:hover {
    transform: translateY(-1px);
    border-color: rgba(242, 180, 12, 0.6);
}
.dha-card__icon {
    flex-shrink: 0;
    font-size: 1.25rem;
    line-height: 1.4;
}
.dha-card__text {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.dha-card__title {
    font-weight: 700;
    font-size: 0.9rem;
    color: #1b2440;
}
.dha-card__excerpt {
    font-size: 0.78rem;
    color: #5d6885;
    line-height: 1.4;
}

.dha-muted {
    font-size: 0.85rem;
    color: #6b7795;
    padding: 0.5rem 0.1rem;
    line-height: 1.5;
}

/* Article reader */
.dha-back {
    font-size: 0.8rem;
    font-weight: 700;
    color: #b3801a;
    cursor: pointer;
    margin-bottom: 0.6rem;
}
.dha-article__title {
    font-family: 'Fraunces', 'Playfair Display', Georgia, serif;
    font-size: 1.3rem;
    font-weight: 600;
    color: #1b2440;
    margin-bottom: 0.6rem;
    line-height: 1.2;
}
.dha-prose {
    font-size: 0.9rem;
    line-height: 1.6;
    color: #303c5c;
}
.dha-prose :deep(p) {
    margin-bottom: 0.7rem;
}
.dha-prose :deep(ul),
.dha-prose :deep(ol) {
    margin: 0 0 0.7rem 1.1rem;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}
.dha-prose :deep(ul) { list-style: disc; }
.dha-prose :deep(ol) { list-style: decimal; }
.dha-prose :deep(strong) { color: #1b2440; }
.dha-prose :deep(em) { color: #6b5219; font-style: italic; }

.dha-related {
    margin-top: 1rem;
    border-top: 1px dashed rgba(27, 36, 64, 0.15);
    padding-top: 0.8rem;
}
.dha-related__label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #94a0bf;
    margin-bottom: 0.5rem;
}
.dha-related__item {
    display: block;
    width: 100%;
    text-align: left;
    font-size: 0.84rem;
    font-weight: 600;
    color: #102a6e;
    padding: 0.4rem 0;
    cursor: pointer;
}
.dha-related__item:hover {
    color: #b3801a;
}

.dha-foot {
    border-top: 1px solid rgba(27, 36, 64, 0.1);
    padding: 0.8rem 1rem;
    background: #fff;
    text-align: center;
}
.dha-foot__link {
    font-size: 0.82rem;
    font-weight: 700;
    color: #102a6e;
}
.dha-foot__link:hover {
    color: #b3801a;
}

/* ---------- Transitions ---------- */
.dha-fade-enter-active,
.dha-fade-leave-active {
    transition: opacity 0.2s ease;
}
.dha-fade-enter-from,
.dha-fade-leave-to {
    opacity: 0;
}
.dha-sheet-enter-active,
.dha-sheet-leave-active {
    transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1);
}
.dha-sheet-enter-from,
.dha-sheet-leave-to {
    transform: translateY(100%);
}
.dha-pop-enter-active,
.dha-pop-leave-active {
    transition: transform 0.2s ease, opacity 0.2s ease;
}
.dha-pop-enter-from,
.dha-pop-leave-to {
    opacity: 0;
    transform: translateY(12px) scale(0.98);
}

@media (prefers-reduced-motion: reduce) {
    .dha-fab,
    .dha-tour,
    .dha-card,
    .dha-sheet-enter-active,
    .dha-sheet-leave-active,
    .dha-pop-enter-active,
    .dha-pop-leave-active {
        transition: none;
    }
    .dha-fab__pulse {
        animation: none;
    }
}
</style>
