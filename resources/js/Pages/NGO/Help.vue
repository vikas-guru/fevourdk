<template>
    <AppLayout title="Help & guides">
        <NgoWorkspaceShell :ngo="ngo" current-key="help">
            <div class="mx-auto max-w-4xl">
                <!-- Hero -->
                <header class="hlp-hero reveal">
                    <p class="hlp-hero__eyebrow"><span aria-hidden="true">✦</span> Help center</p>
                    <h1 class="hlp-hero__title">Ask Disha — guides for everything you do</h1>
                    <p class="hlp-hero__sub">
                        Plain-language how-tos for running your organisation on FevourdK. Search a topic, or browse by category below.
                    </p>

                    <div class="hlp-search">
                        <span class="hlp-search__icon" aria-hidden="true">⌕</span>
                        <input
                            v-model="query"
                            type="search"
                            class="hlp-search__input"
                            placeholder="Search guides… e.g. donations, edit website, ledger"
                            aria-label="Search guides"
                        >
                    </div>
                </header>

                <!-- Article reader -->
                <article v-if="active" class="hlp-reader reveal">
                    <button type="button" class="hlp-back" @click="active = null">← All guides</button>
                    <p class="hlp-reader__cat">{{ catLabel(active.category) }}</p>
                    <h2 class="hlp-reader__title">{{ active.title }}</h2>
                    <div class="hlp-prose" v-html="active.body" />

                    <div v-if="relatedOf(active).length" class="hlp-related">
                        <p class="hlp-related__label">Related guides</p>
                        <div class="hlp-related__grid">
                            <button
                                v-for="r in relatedOf(active)"
                                :key="r.slug"
                                type="button"
                                class="hlp-related__item"
                                @click="open(r)"
                            >
                                <span aria-hidden="true">{{ r.icon }}</span> {{ r.title }}
                            </button>
                        </div>
                    </div>
                </article>

                <!-- Browse -->
                <div v-else>
                    <div v-if="!query" class="hlp-chips reveal">
                        <button
                            type="button"
                            class="hlp-chip"
                            :class="{ 'is-on': activeCat === null }"
                            @click="activeCat = null"
                        >
                            All
                        </button>
                        <button
                            v-for="c in categories"
                            :key="c.key"
                            type="button"
                            class="hlp-chip"
                            :class="{ 'is-on': activeCat === c.key }"
                            @click="activeCat = activeCat === c.key ? null : c.key"
                        >
                            <span aria-hidden="true">{{ c.icon }}</span> {{ c.label }}
                        </button>
                    </div>

                    <p v-if="!filtered.length" class="hlp-empty">
                        No guides match “{{ query }}”. Try a different word.
                    </p>

                    <div v-else class="hlp-grid">
                        <button
                            v-for="a in filtered"
                            :key="a.slug"
                            type="button"
                            class="hlp-card reveal"
                            @click="open(a)"
                        >
                            <span class="hlp-card__icon" aria-hidden="true">{{ a.icon }}</span>
                            <span class="hlp-card__title">{{ a.title }}</span>
                            <span class="hlp-card__excerpt">{{ a.excerpt }}</span>
                            <span class="hlp-card__cta">Read guide →</span>
                        </button>
                    </div>
                </div>

                <p class="hlp-foot">
                    Still stuck? Tap the <strong>✦ Help</strong> button anytime for a guided tour of the page you’re on.
                </p>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'

const props = defineProps({
    ngo: { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    articles: { type: Array, default: () => [] },
})

const query = ref('')
const activeCat = ref(null)
const active = ref(null)

const filtered = computed(() => {
    const q = query.value.trim().toLowerCase()
    let list = props.articles
    if (q) {
        list = list.filter((a) =>
            `${a.title} ${a.excerpt} ${a.keywords || ''}`.toLowerCase().includes(q),
        )
    } else if (activeCat.value) {
        list = list.filter((a) => a.category === activeCat.value)
    }
    return list
})

function catLabel(key) {
    return props.categories.find((c) => c.key === key)?.label || ''
}

function relatedOf(article) {
    return (article?.related || [])
        .map((s) => props.articles.find((a) => a.slug === s))
        .filter(Boolean)
}

function open(a) {
    active.value = a
    if (typeof window !== 'undefined') {
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}

/* Lightweight reveal-on-scroll, matching the Impact Dossier language. */
onMounted(() => {
    if (typeof window === 'undefined' || !('IntersectionObserver' in window)) {
        document.querySelectorAll('.reveal').forEach((el) => el.classList.add('is-in'))
        return
    }
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add('is-in')
                    io.unobserve(e.target)
                }
            })
        },
        { threshold: 0.08 },
    )
    document.querySelectorAll('.reveal').forEach((el) => io.observe(el))
})
</script>

<style scoped>
.hlp-hero {
    border-radius: 24px;
    padding: 1.6rem 1.3rem;
    background: linear-gradient(160deg, #102a6e 0%, #0b1c4f 72%, #081435 100%);
    color: #fff;
    overflow: hidden;
    position: relative;
}
@media (min-width: 640px) {
    .hlp-hero { padding: 2.2rem 2rem; }
}
.hlp-hero__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #f7c948;
}
.hlp-hero__title {
    margin-top: 0.5rem;
    font-family: 'Fraunces', 'Playfair Display', Georgia, serif;
    font-size: 1.7rem;
    font-weight: 600;
    line-height: 1.12;
}
@media (min-width: 640px) {
    .hlp-hero__title { font-size: 2.1rem; }
}
.hlp-hero__sub {
    margin-top: 0.6rem;
    max-width: 42ch;
    font-size: 0.92rem;
    line-height: 1.55;
    color: rgba(207, 218, 255, 0.9);
}
.hlp-search {
    position: relative;
    margin-top: 1.1rem;
}
.hlp-search__icon {
    position: absolute;
    left: 0.85rem;
    top: 50%;
    transform: translateY(-50%);
    color: #8e9bbf;
    font-size: 1.1rem;
}
.hlp-search__input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.4rem;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.96);
    font-size: 16px;
    color: #1b2440;
}
.hlp-search__input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(242, 180, 12, 0.5);
}

.hlp-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    margin: 1.4rem 0 1.2rem;
}
.hlp-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 0.8rem;
    border-radius: 9999px;
    border: 1px solid rgba(27, 36, 64, 0.14);
    background: #fff;
    font-size: 0.8rem;
    font-weight: 600;
    color: #41506f;
    cursor: pointer;
    transition: all 0.12s ease;
}
.hlp-chip.is-on {
    border-color: transparent;
    background: #102a6e;
    color: #fff;
}

.hlp-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.8rem;
}
@media (min-width: 640px) {
    .hlp-grid { grid-template-columns: 1fr 1fr; gap: 1rem; }
    .hlp-chips { margin-top: 1.6rem; }
}
.hlp-card {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.3rem;
    padding: 1.1rem;
    border-radius: 18px;
    border: 1px solid rgba(27, 36, 64, 0.1);
    background: #fff;
    text-align: left;
    cursor: pointer;
    transition: transform 0.14s ease, border-color 0.14s ease, box-shadow 0.14s ease;
}
.hlp-card:hover {
    transform: translateY(-2px);
    border-color: rgba(242, 180, 12, 0.6);
    box-shadow: 0 18px 40px -22px rgba(5, 12, 40, 0.4);
}
.hlp-card__icon { font-size: 1.6rem; }
.hlp-card__title {
    font-family: 'Fraunces', 'Playfair Display', Georgia, serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: #1b2440;
}
.hlp-card__excerpt {
    font-size: 0.85rem;
    line-height: 1.5;
    color: #5d6885;
}
.hlp-card__cta {
    margin-top: 0.4rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: #b3801a;
}

.hlp-empty {
    margin: 2rem 0;
    text-align: center;
    color: #6b7795;
}

/* Reader */
.hlp-reader {
    margin-top: 1.4rem;
    padding: 1.5rem 1.3rem;
    border-radius: 20px;
    border: 1px solid rgba(27, 36, 64, 0.1);
    background: #fff;
}
@media (min-width: 640px) {
    .hlp-reader { padding: 2rem; }
}
.hlp-back {
    font-size: 0.82rem;
    font-weight: 700;
    color: #b3801a;
    cursor: pointer;
}
.hlp-reader__cat {
    margin-top: 0.8rem;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #94a0bf;
}
.hlp-reader__title {
    margin-top: 0.3rem;
    font-family: 'Fraunces', 'Playfair Display', Georgia, serif;
    font-size: 1.7rem;
    font-weight: 600;
    line-height: 1.18;
    color: #1b2440;
}
.hlp-prose {
    margin-top: 1rem;
    font-size: 0.95rem;
    line-height: 1.65;
    color: #303c5c;
}
.hlp-prose :deep(p) { margin-bottom: 0.85rem; }
.hlp-prose :deep(ul),
.hlp-prose :deep(ol) {
    margin: 0 0 0.85rem 1.2rem;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.hlp-prose :deep(ul) { list-style: disc; }
.hlp-prose :deep(ol) { list-style: decimal; }
.hlp-prose :deep(strong) { color: #1b2440; }
.hlp-prose :deep(em) { color: #6b5219; font-style: italic; }

.hlp-related {
    margin-top: 1.6rem;
    border-top: 1px dashed rgba(27, 36, 64, 0.15);
    padding-top: 1rem;
}
.hlp-related__label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #94a0bf;
    margin-bottom: 0.6rem;
}
.hlp-related__grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.hlp-related__item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.5rem 0.8rem;
    border-radius: 12px;
    border: 1px solid rgba(27, 36, 64, 0.12);
    background: #faf7f0;
    font-size: 0.84rem;
    font-weight: 600;
    color: #102a6e;
    cursor: pointer;
}
.hlp-related__item:hover {
    border-color: rgba(242, 180, 12, 0.6);
    color: #b3801a;
}

.hlp-foot {
    margin: 2rem 0 1rem;
    text-align: center;
    font-size: 0.86rem;
    color: #6b7795;
}

/* Reveal */
.reveal {
    opacity: 0;
    transform: translateY(14px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.reveal.is-in {
    opacity: 1;
    transform: none;
}
@media (prefers-reduced-motion: reduce) {
    .reveal { opacity: 1; transform: none; transition: none; }
    .hlp-card { transition: none; }
}
</style>
