<template>
    <teleport to="body">
        <transition name="fkt-fade">
            <div
                v-if="active"
                class="fkt-root"
                role="dialog"
                aria-modal="true"
                :aria-label="current ? current.title : 'Guided tour'"
            >
                <!-- Spotlight: a dimmed backdrop with a glowing cut-out over the target -->
                <div class="fkt-backdrop" @click="skip" />
                <div
                    class="fkt-spotlight"
                    :style="spotlightStyle"
                    aria-hidden="true"
                />

                <!-- Tooltip card -->
                <div ref="card" class="fkt-card" :style="cardStyle">
                    <div class="fkt-card__beam" aria-hidden="true" />
                    <p class="fkt-card__eyebrow">
                        <span class="fkt-card__dot" /> Quick tour · {{ index + 1 }} / {{ steps.length }}
                    </p>
                    <h3 class="fkt-card__title">{{ current?.title }}</h3>
                    <p class="fkt-card__body">{{ current?.body }}</p>

                    <div class="fkt-card__dots" aria-hidden="true">
                        <span
                            v-for="(s, i) in steps"
                            :key="i"
                            class="fkt-card__pip"
                            :class="{ 'is-on': i === index, 'is-done': i < index }"
                        />
                    </div>

                    <div class="fkt-card__actions">
                        <button type="button" class="fkt-btn fkt-btn--ghost" @click="skip">
                            Skip
                        </button>
                        <div class="fkt-card__nav">
                            <button
                                v-if="index > 0"
                                type="button"
                                class="fkt-btn fkt-btn--soft"
                                @click="prev"
                            >
                                Back
                            </button>
                            <button type="button" class="fkt-btn fkt-btn--gold" @click="next">
                                {{ index === steps.length - 1 ? 'Got it' : 'Next' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </teleport>
</template>

<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue'

const props = defineProps({
    steps: { type: Array, required: true }, // [{ selector, title, body, placement? }]
    storageKey: { type: String, default: 'ngo_dashboard_tour_v1' },
    autoStart: { type: Boolean, default: false },
})

const active = ref(false)
const index = ref(0)
const rect = ref(null)
const card = ref(null)
const cardSize = ref({ w: 320, h: 200 })

const current = computed(() => props.steps[index.value] || null)
const PAD = 8

const reduceMotion = () =>
    typeof window !== 'undefined' &&
    window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches

function alreadySeen() {
    try {
        return localStorage.getItem(props.storageKey) === 'done'
    } catch {
        return false
    }
}

function markSeen() {
    try {
        localStorage.setItem(props.storageKey, 'done')
    } catch {
        /* ignore */
    }
}

async function focusStep() {
    const step = current.value
    if (!step) {
        return
    }
    const el = document.querySelector(step.selector)
    if (!el) {
        // Skip steps whose target isn't on the page.
        if (index.value < props.steps.length - 1) {
            index.value += 1
            await focusStep()
        } else {
            finish()
        }
        return
    }
    el.scrollIntoView({
        behavior: reduceMotion() ? 'auto' : 'smooth',
        block: 'center',
        inline: 'nearest',
    })
    // Wait a tick for smooth-scroll to settle before measuring.
    await new Promise((r) => setTimeout(r, reduceMotion() ? 0 : 320))
    measure()
}

function measure() {
    const step = current.value
    if (!step) {
        return
    }
    const el = document.querySelector(step.selector)
    if (!el) {
        return
    }
    const r = el.getBoundingClientRect()
    rect.value = { top: r.top, left: r.left, width: r.width, height: r.height }
    if (card.value) {
        cardSize.value = { w: card.value.offsetWidth, h: card.value.offsetHeight }
    }
}

const spotlightStyle = computed(() => {
    if (!rect.value) {
        return { opacity: 0 }
    }
    const r = rect.value
    return {
        top: `${r.top - PAD}px`,
        left: `${r.left - PAD}px`,
        width: `${r.width + PAD * 2}px`,
        height: `${r.height + PAD * 2}px`,
        opacity: 1,
    }
})

const cardStyle = computed(() => {
    if (!rect.value) {
        return { opacity: 0 }
    }
    const r = rect.value
    const vw = window.innerWidth
    const vh = window.innerHeight
    const { w, h } = cardSize.value
    const gap = 16
    const isMobile = vw < 640

    let top
    let left

    if (isMobile) {
        // Mobile: pin the card to the bottom, full-width-ish, centered.
        left = Math.max(12, (vw - Math.min(w, vw - 24)) / 2)
        const below = r.top + r.height + gap
        const fitsBelow = below + h < vh - 12
        top = fitsBelow ? below : Math.max(12, r.top - h - gap)
        if (!fitsBelow && r.top - h - gap < 12) {
            top = vh - h - 12 // last resort: float above the keyboard-safe bottom
        }
    } else {
        // Desktop: prefer below, flip above if it would overflow.
        const below = r.top + r.height + gap
        top = below + h < vh - 12 ? below : Math.max(12, r.top - h - gap)
        left = Math.min(Math.max(12, r.left), vw - w - 12)
    }

    return { top: `${top}px`, left: `${left}px`, opacity: 1 }
})

function onReflow() {
    measure()
}

async function start() {
    active.value = true
    index.value = 0
    document.documentElement.style.overflow = 'hidden'
    await nextTick()
    await focusStep()
    window.addEventListener('resize', onReflow)
    window.addEventListener('scroll', onReflow, true)
}

async function next() {
    if (index.value >= props.steps.length - 1) {
        finish()
        return
    }
    index.value += 1
    await focusStep()
}

async function prev() {
    if (index.value === 0) {
        return
    }
    index.value -= 1
    await focusStep()
}

function teardown() {
    active.value = false
    document.documentElement.style.overflow = ''
    window.removeEventListener('resize', onReflow)
    window.removeEventListener('scroll', onReflow, true)
}

function finish() {
    markSeen()
    teardown()
}

function skip() {
    markSeen()
    teardown()
}

defineExpose({ start })

onMounted(() => {
    if (props.autoStart && !alreadySeen()) {
        // Let the page paint + reveal animations begin first.
        setTimeout(start, 650)
    }
})

onBeforeUnmount(teardown)
</script>

<style scoped>
.fkt-root {
    position: fixed;
    inset: 0;
    z-index: 80;
}

.fkt-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(8, 16, 40, 0.62);
    backdrop-filter: blur(1.5px);
}

.fkt-spotlight {
    position: absolute;
    border-radius: 18px;
    /* The huge spread shadow is what actually dims everything *except* the hole. */
    box-shadow:
        0 0 0 9999px rgba(8, 16, 40, 0.62),
        0 0 0 2px rgba(242, 180, 12, 0.9),
        0 0 34px 6px rgba(242, 180, 12, 0.45);
    transition:
        top 0.32s cubic-bezier(0.22, 1, 0.36, 1),
        left 0.32s cubic-bezier(0.22, 1, 0.36, 1),
        width 0.32s cubic-bezier(0.22, 1, 0.36, 1),
        height 0.32s cubic-bezier(0.22, 1, 0.36, 1);
    pointer-events: none;
}

.fkt-card {
    position: absolute;
    width: min(21rem, calc(100vw - 1.5rem));
    overflow: hidden;
    border-radius: 18px;
    border: 1px solid rgba(242, 180, 12, 0.35);
    background: linear-gradient(160% 160% at 0% 0%, #102a6e 0%, #0b1c4f 60%, #081435 100%);
    padding: 1.1rem 1.15rem 1rem;
    color: #eef2ff;
    box-shadow: 0 24px 60px -22px rgba(5, 12, 40, 0.9);
    transition:
        top 0.32s cubic-bezier(0.22, 1, 0.36, 1),
        left 0.32s cubic-bezier(0.22, 1, 0.36, 1);
}

.fkt-card__beam {
    position: absolute;
    inset: -40% -10% auto -10%;
    height: 120px;
    background: radial-gradient(60% 80% at 30% 0%, rgba(242, 180, 12, 0.3), transparent 70%);
    pointer-events: none;
}

.fkt-card__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.66rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #f7c948;
}

.fkt-card__dot {
    height: 7px;
    width: 7px;
    border-radius: 9999px;
    background: #f2b40c;
    box-shadow: 0 0 0 4px rgba(242, 180, 12, 0.22);
}

.fkt-card__title {
    margin-top: 0.55rem;
    font-family: 'Fraunces', 'Playfair Display', Georgia, serif;
    font-size: 1.28rem;
    font-weight: 600;
    line-height: 1.15;
    color: #ffffff;
}

.fkt-card__body {
    margin-top: 0.4rem;
    font-size: 0.86rem;
    line-height: 1.5;
    color: rgba(207, 218, 255, 0.92);
}

.fkt-card__dots {
    margin-top: 0.85rem;
    display: flex;
    gap: 0.32rem;
}

.fkt-card__pip {
    height: 5px;
    width: 18px;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.18);
    transition: background 0.2s ease;
}

.fkt-card__pip.is-on {
    background: #f2b40c;
}

.fkt-card__pip.is-done {
    background: rgba(242, 180, 12, 0.5);
}

.fkt-card__actions {
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
}

.fkt-card__nav {
    display: flex;
    gap: 0.5rem;
}

.fkt-btn {
    cursor: pointer;
    border-radius: 10px;
    padding: 0.5rem 0.9rem;
    font-size: 0.8rem;
    font-weight: 700;
    transition:
        transform 0.15s ease,
        background 0.15s ease;
}

.fkt-btn:active {
    transform: scale(0.96);
}

.fkt-btn--ghost {
    color: rgba(196, 208, 255, 0.75);
}

.fkt-btn--ghost:hover {
    color: #fff;
}

.fkt-btn--soft {
    background: rgba(255, 255, 255, 0.1);
    color: #e6ecff;
}

.fkt-btn--soft:hover {
    background: rgba(255, 255, 255, 0.18);
}

.fkt-btn--gold {
    background: linear-gradient(180deg, #f7c948, #f2b40c);
    color: #2a1c00;
    box-shadow: 0 12px 26px -12px rgba(242, 180, 12, 0.9);
}

.fkt-btn--gold:hover {
    transform: translateY(-1px);
}

.fkt-fade-enter-active,
.fkt-fade-leave-active {
    transition: opacity 0.25s ease;
}

.fkt-fade-enter-from,
.fkt-fade-leave-to {
    opacity: 0;
}

@media (prefers-reduced-motion: reduce) {
    .fkt-spotlight,
    .fkt-card {
        transition: none;
    }
}
</style>
