<template>
    <AppLayout title="Your website — FEVOURD-K" :hide-chrome="true">
        <NgoWorkspaceShell :ngo="ngo" current-key="digitalization">
            <div class="we">
                <!-- Header -->
                <div class="we__head" data-tour="intro">
                    <div class="min-w-0">
                        <h1 class="we__title">Your website</h1>
                        <p class="we__sub">Type your changes, then tap save — your website updates instantly. No tech skills needed.</p>
                    </div>
                    <div class="we__head-actions">
                        <a v-if="liveUrl" :href="liveUrl" target="_blank" rel="noopener" class="we__btn we__btn--ghost">
                            ↗ Open live site
                        </a>
                    </div>
                </div>

                <!-- Mobile segmented toggle -->
                <div class="we__seg lg:hidden">
                    <button type="button" :class="['we__seg-btn', mobileView === 'edit' && 'is-on']" @click="mobileView = 'edit'">✏️ Edit</button>
                    <button type="button" :class="['we__seg-btn', mobileView === 'preview' && 'is-on']" @click="mobileView = 'preview'; refreshPreview()">👁 Preview</button>
                </div>

                <div class="we__grid">
                    <!-- ============ EDITOR PANEL ============ -->
                    <div class="we__editor" :class="{ 'we__editor--hidden-mobile': mobileView !== 'edit' }" data-tour="editor">
                        <!-- 1. Look -->
                        <EditorSection emoji="🎨" title="Your look" hint="Your logo and main colour appear across the whole site." :open="openKey === 'look'" @toggle="toggle('look')">
                            <label class="we__label">Logo</label>
                            <div class="we__logo-row">
                                <div class="we__logo-thumb">
                                    <img v-if="logoPreview" :src="logoPreview" alt="Logo preview">
                                    <span v-else>{{ initials }}</span>
                                </div>
                                <div>
                                    <label class="we__btn we__btn--soft we__file">
                                        Choose a photo
                                        <input type="file" accept="image/*" class="sr-only" @change="onLogoChange">
                                    </label>
                                    <p class="we__tiny">PNG or JPG. A square logo looks best.</p>
                                </div>
                            </div>

                            <label class="we__label we__mt">Main colour</label>
                            <div class="we__swatches">
                                <button
                                    v-for="c in colourPresets"
                                    :key="c"
                                    type="button"
                                    class="we__swatch"
                                    :class="{ 'is-on': form.theme_color.toLowerCase() === c }"
                                    :style="{ background: c }"
                                    :aria-label="c"
                                    @click="form.theme_color = c"
                                />
                                <label class="we__swatch we__swatch--custom" :style="{ background: form.theme_color }">
                                    <span>＋</span>
                                    <input v-model="form.theme_color" type="color" class="sr-only">
                                </label>
                            </div>
                        </EditorSection>

                        <!-- 2. Welcome banner -->
                        <EditorSection emoji="👋" title="Welcome banner" hint="The big first thing visitors see." :open="openKey === 'hero'" @toggle="toggle('hero')">
                            <FieldText v-model="form.microsite.hero_subtitle" label="Short tagline" eg="e.g. Empowering rural communities since 1998" />
                            <FieldText v-model="form.microsite.hero_subtitle_kn" label="ಕನ್ನಡ — Short tagline (optional)" eg="e.g. 1998 ರಿಂದ ಗ್ರಾಮೀಣ ಸಮುದಾಯಗಳ ಸಬಲೀಕರಣ" kn />
                            <FieldArea v-model="form.microsite.hero_description" label="Welcome message" eg="A warm sentence or two about who you help. Leave empty to use your saved description." />
                            <FieldArea v-model="form.microsite.hero_description_kn" label="ಕನ್ನಡ — Welcome message (optional)" eg="ನೀವು ಯಾರಿಗೆ ಸಹಾಯ ಮಾಡುತ್ತೀರಿ ಎಂಬುದರ ಬಗ್ಗೆ ಒಂದೆರಡು ಸಾಲುಗಳು." kn />
                        </EditorSection>

                        <!-- 3. Mission & vision -->
                        <EditorSection emoji="🎯" title="Mission &amp; vision" hint="What you do, and the change you want to see." :open="openKey === 'mv'" @toggle="toggle('mv')">
                            <FieldText v-model="form.microsite.mission_subtitle" label="Mission headline" eg="e.g. Inclusion through action" />
                            <FieldText v-model="form.microsite.mission_subtitle_kn" label="ಕನ್ನಡ — Mission headline (optional)" eg="e.g. ಕ್ರಿಯೆಯ ಮೂಲಕ ಒಳಗೊಳ್ಳುವಿಕೆ" kn />
                            <FieldArea v-model="form.microsite.mission_description" label="Mission text" eg="What your organisation does, in simple words." />
                            <FieldArea v-model="form.microsite.mission_description_kn" label="ಕನ್ನಡ — Mission text (optional)" eg="ನಿಮ್ಮ ಸಂಸ್ಥೆ ಏನು ಮಾಡುತ್ತದೆ, ಸರಳ ಪದಗಳಲ್ಲಿ." kn />
                            <div class="we__divider" />
                            <FieldText v-model="form.microsite.vision_subtitle" label="Vision headline" eg="e.g. A future that includes everyone" />
                            <FieldText v-model="form.microsite.vision_subtitle_kn" label="ಕನ್ನಡ — Vision headline (optional)" eg="e.g. ಎಲ್ಲರನ್ನೂ ಒಳಗೊಳ್ಳುವ ಭವಿಷ್ಯ" kn />
                            <FieldArea v-model="form.microsite.vision_description" label="Vision text" eg="The world you are working towards." />
                            <FieldArea v-model="form.microsite.vision_description_kn" label="ಕನ್ನಡ — Vision text (optional)" eg="ನೀವು ಕೆಲಸ ಮಾಡುತ್ತಿರುವ ಜಗತ್ತು." kn />
                        </EditorSection>

                        <!-- 4. Key facts -->
                        <EditorSection emoji="🔢" title="Key facts" hint="Four quick facts shown as a strip. Keep them short." :open="openKey === 'facts'" @toggle="toggle('facts')">
                            <div v-for="i in 4" :key="i" class="we__fact">
                                <FieldText v-model="form.microsite['stat_'+i+'_h']" :label="'Fact ' + i + ' — title'" eg="e.g. Registered" />
                                <FieldText v-model="form.microsite['stat_'+i+'_h_kn']" :label="'ಕನ್ನಡ — Fact ' + i + ' title'" eg="e.g. ನೋಂದಾಯಿತ" kn />
                                <FieldText v-model="form.microsite['stat_'+i+'_p']" :label="'Fact ' + i + ' — detail'" eg="e.g. Since 1998" />
                                <FieldText v-model="form.microsite['stat_'+i+'_p_kn']" :label="'ಕನ್ನಡ — Fact ' + i + ' detail'" eg="e.g. 1998 ರಿಂದ" kn />
                            </div>
                        </EditorSection>

                        <!-- 5. About -->
                        <EditorSection emoji="📖" title="About your NGO" hint="Extra detail and an inspiring line." :open="openKey === 'about'" @toggle="toggle('about')">
                            <FieldArea v-model="form.microsite.about_extra" label="Extra paragraph" eg="Anything more you'd like visitors to know." />
                            <FieldArea v-model="form.microsite.about_extra_kn" label="ಕನ್ನಡ — Extra paragraph (optional)" eg="ಸಂದರ್ಶಕರಿಗೆ ತಿಳಿಯಬೇಕಾದ ಹೆಚ್ಚಿನ ಮಾಹಿತಿ." kn />
                            <FieldText v-model="form.microsite.about_vision_quote" label="Inspiring quote" eg="e.g. Humanity is the first step of social change." />
                            <FieldText v-model="form.microsite.about_vision_quote_kn" label="ಕನ್ನಡ — Inspiring quote (optional)" eg="e.g. ಮಾನವೀಯತೆಯೇ ಸಾಮಾಜಿಕ ಬದಲಾವಣೆಯ ಮೊದಲ ಹೆಜ್ಜೆ." kn />
                        </EditorSection>

                        <!-- 6. Programmes -->
                        <EditorSection emoji="🤝" title="What you do" hint="Your programmes or focus areas. Tap an icon to pick one." :open="openKey === 'programs'" @toggle="toggle('programs')">
                            <div v-for="(p, idx) in form.microsite.programs" :key="'p'+idx" class="we__card">
                                <div class="we__card-head">
                                    <span class="we__card-n">Programme {{ idx + 1 }}</span>
                                    <button v-if="form.microsite.programs.length > 1" type="button" class="we__remove" @click="removeProgram(idx)">Remove</button>
                                </div>
                                <FieldText v-model="p.title" label="Name" eg="e.g. Education for all" />
                                <FieldText v-model="p.title_kn" label="ಕನ್ನಡ — Name (optional)" eg="e.g. ಎಲ್ಲರಿಗೂ ಶಿಕ್ಷಣ" kn />
                                <FieldArea v-model="p.body" label="Short description" eg="One line about this programme." />
                                <FieldArea v-model="p.body_kn" label="ಕನ್ನಡ — Short description (optional)" eg="ಈ ಕಾರ್ಯಕ್ರಮದ ಬಗ್ಗೆ ಒಂದು ಸಾಲು." kn />
                                <label class="we__label">Pick an icon</label>
                                <div class="we__icons">
                                    <button
                                        v-for="ic in iconChoices"
                                        :key="ic.icon"
                                        type="button"
                                        class="we__icon"
                                        :class="{ 'is-on': p.icon === ic.icon }"
                                        :title="ic.label"
                                        @click="p.icon = ic.icon"
                                    >{{ ic.emoji }}</button>
                                </div>
                            </div>
                            <button v-if="form.microsite.programs.length < 12" type="button" class="we__add" @click="addProgram">＋ Add a programme</button>
                        </EditorSection>

                        <!-- 7. Stories -->
                        <EditorSection emoji="📰" title="Stories &amp; news" hint="Share recent work with a photo. Leave empty to use sample stories." :open="openKey === 'stories'" @toggle="toggle('stories')">
                            <div v-for="(s, idx) in form.microsite.stories" :key="'s'+idx" class="we__card">
                                <div class="we__card-head">
                                    <span class="we__card-n">Story {{ idx + 1 }}</span>
                                    <button type="button" class="we__remove" @click="removeStory(idx)">Remove</button>
                                </div>
                                <FieldText v-model="s.title" label="Headline" eg="e.g. 200 families received clean water" />
                                <FieldText v-model="s.title_kn" label="ಕನ್ನಡ — Headline (optional)" eg="e.g. 200 ಕುಟುಂಬಗಳಿಗೆ ಶುದ್ಧ ನೀರು" kn />
                                <FieldArea v-model="s.body" label="What happened" eg="A short paragraph about this story." />
                                <FieldArea v-model="s.body_kn" label="ಕನ್ನಡ — What happened (optional)" eg="ಈ ಕಥೆಯ ಬಗ್ಗೆ ಒಂದು ಸಣ್ಣ ಪ್ಯಾರಾ." kn />
                                <div class="we__story-meta">
                                    <FieldText v-model="s.category" label="Category" eg="e.g. Water" />
                                    <FieldText v-model="s.category_kn" label="ಕನ್ನಡ — Category" eg="e.g. ನೀರು" kn />
                                    <FieldText v-model="s.date" label="When" eg="e.g. Mar 2026" />
                                    <FieldText v-model="s.date_kn" label="ಕನ್ನಡ — When" eg="e.g. ಮಾರ್ಚ್ 2026" kn />
                                </div>
                                <label class="we__label">Photo</label>
                                <div class="we__logo-row">
                                    <div class="we__story-thumb">
                                        <img v-if="s.image" :src="s.image" alt="">
                                        <span v-else>No photo</span>
                                    </div>
                                    <label class="we__btn we__btn--soft we__file">
                                        <span v-if="uploadingIdx === idx">Uploading…</span>
                                        <span v-else>{{ s.image ? 'Change photo' : 'Add a photo' }}</span>
                                        <input type="file" accept="image/*" class="sr-only" @change="onStoryPhoto($event, idx)">
                                    </label>
                                </div>
                            </div>
                            <button v-if="form.microsite.stories.length < 6" type="button" class="we__add" @click="addStory">＋ Add a story</button>
                        </EditorSection>

                        <!-- 8. Donations -->
                        <EditorSection emoji="💛" title="Donations" hint="Encourage visitors to support you." :open="openKey === 'donate'" @toggle="toggle('donate')">
                            <FieldText v-model="form.microsite.donate_title" label="Title" eg="e.g. Support our mission" />
                            <FieldText v-model="form.microsite.donate_title_kn" label="ಕನ್ನಡ — Title (optional)" eg="e.g. ನಮ್ಮ ಧ್ಯೇಯವನ್ನು ಬೆಂಬಲಿಸಿ" kn />
                            <FieldText v-model="form.microsite.donate_subtitle" label="Subtitle" eg="One line on why giving matters." />
                            <FieldText v-model="form.microsite.donate_subtitle_kn" label="ಕನ್ನಡ — Subtitle (optional)" eg="ನೀಡುವಿಕೆ ಏಕೆ ಮುಖ್ಯ ಎಂಬ ಒಂದು ಸಾಲು." kn />
                            <FieldArea v-model="form.microsite.donate_impact_blurb" label="Impact note" eg="What a donation makes possible." />
                            <FieldArea v-model="form.microsite.donate_impact_blurb_kn" label="ಕನ್ನಡ — Impact note (optional)" eg="ದೇಣಿಗೆ ಏನನ್ನು ಸಾಧ್ಯವಾಗಿಸುತ್ತದೆ." kn />
                        </EditorSection>

                        <!-- 9. Contact -->
                        <EditorSection emoji="✉️" title="Contact" hint="Invite people to reach out." :open="openKey === 'contact'" @toggle="toggle('contact')">
                            <FieldText v-model="form.microsite.contact_intro" label="Intro line" eg="e.g. We'd love to hear from you." />
                            <FieldText v-model="form.microsite.contact_intro_kn" label="ಕನ್ನಡ — Intro line (optional)" eg="e.g. ನಿಮ್ಮಿಂದ ಕೇಳಲು ನಾವು ಇಷ್ಟಪಡುತ್ತೇವೆ." kn />
                        </EditorSection>

                        <!-- 10. Advanced -->
                        <EditorSection emoji="⚙️" title="Advanced (optional)" hint="Domain, social links and live chat. You can skip all of this." :open="openKey === 'adv'" @toggle="toggle('adv')">
                            <FieldText v-model="form.custom_domain" label="Your own web address" eg="www.yourngo.org" />
                            <FieldText v-model="form.facebook_url" label="Facebook page" eg="https://facebook.com/yourngo" />
                            <FieldText v-model="form.instagram_url" label="Instagram" eg="https://instagram.com/yourngo" />
                            <div class="we__divider" />
                            <p class="we__tiny">Live chat (Tawk.to) — paste the IDs from your Tawk dashboard if you use it.</p>
                            <div class="we__story-meta">
                                <FieldText v-model="form.tawk_property_id" label="Tawk Property ID" eg="optional" />
                                <FieldText v-model="form.tawk_widget_id" label="Tawk Widget ID" eg="optional" />
                            </div>
                        </EditorSection>

                        <div class="we__editor-foot" />
                    </div>

                    <!-- ============ LIVE PREVIEW ============ -->
                    <div class="we__preview" :class="{ 'we__preview--hidden-mobile': mobileView !== 'preview' }" data-tour="preview">
                        <div class="we__preview-bar">
                            <div class="we__dots"><i /><i /><i /></div>
                            <span class="we__preview-url">{{ liveUrl || '/ngo/website-preview' }}</span>
                            <div class="we__device">
                                <button type="button" :class="['we__device-btn', device === 'desktop' && 'is-on']" title="Desktop" @click="device = 'desktop'">🖥</button>
                                <button type="button" :class="['we__device-btn', device === 'mobile' && 'is-on']" title="Phone" @click="device = 'mobile'">📱</button>
                                <button type="button" class="we__device-btn" title="Refresh" @click="refreshPreview">↻</button>
                            </div>
                        </div>
                        <div class="we__frame-wrap" :class="device === 'mobile' && 'is-phone'">
                            <iframe ref="frame" :src="previewUrl" class="we__frame" title="Website preview" />
                        </div>
                    </div>
                </div>

                <!-- Sticky save bar -->
                <div class="we__savebar">
                    <p class="we__savebar-msg">
                        <span v-if="justSaved" class="we__ok">✅ Your website is updated!</span>
                        <span v-else-if="form.isDirty" class="we__dirty">You have unsaved changes</span>
                        <span v-else class="we__tiny">All changes saved</span>
                    </p>
                    <button type="button" class="we__btn we__btn--gold we__save" :disabled="form.processing" @click="save">
                        <span v-if="form.processing">Saving…</span>
                        <span v-else>Save &amp; update my website</span>
                    </button>
                </div>
            </div>
            <DashboardTour ref="tourRef" :steps="steps" :storage-key="storageKey" auto-start />
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import { computed, h, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import DashboardTour from '@/Components/NGO/DashboardTour.vue'
import { useNgoTour } from '@/ngo/useNgoTour'

const props = defineProps({
    ngo: Object,
    previewUrl: { type: String, default: '/ngo/website-preview' },
})

const { tourRef, steps, storageKey } = useNgoTour('digitalization')

/* ---- tiny inline field components (keep this file self-contained) ---- */
const FieldText = (p, { emit }) =>
    h('div', { class: ['we__field', p.kn && 'we__field--kn'] }, [
        h('label', { class: 'we__label' }, p.label),
        h('input', {
            class: ['we__input', p.kn && 'we__input--kn'],
            value: p.modelValue,
            placeholder: p.eg,
            lang: p.kn ? 'kn' : null,
            onInput: (e) => emit('update:modelValue', e.target.value),
        }),
    ])
FieldText.props = ['modelValue', 'label', 'eg', 'kn']
FieldText.emits = ['update:modelValue']

const FieldArea = (p, { emit }) =>
    h('div', { class: ['we__field', p.kn && 'we__field--kn'] }, [
        h('label', { class: 'we__label' }, p.label),
        h('textarea', {
            class: ['we__input', 'we__textarea', p.kn && 'we__input--kn'],
            rows: 3,
            value: p.modelValue,
            placeholder: p.eg,
            lang: p.kn ? 'kn' : null,
            onInput: (e) => emit('update:modelValue', e.target.value),
        }),
    ])
FieldArea.props = ['modelValue', 'label', 'eg', 'kn']
FieldArea.emits = ['update:modelValue']

const EditorSection = (p, { emit, slots }) =>
    h('div', { class: ['we__section', p.open && 'is-open'] }, [
        h('button', { type: 'button', class: 'we__section-head', onClick: () => emit('toggle') }, [
            h('span', { class: 'we__section-emoji' }, p.emoji),
            h('span', { class: 'we__section-titles' }, [
                h('span', { class: 'we__section-title' }, p.title),
                h('span', { class: 'we__section-hint' }, p.hint),
            ]),
            h('span', { class: 'we__chev' }, p.open ? '▾' : '▸'),
        ]),
        p.open ? h('div', { class: 'we__section-body' }, slots.default?.()) : null,
    ])
EditorSection.props = ['emoji', 'title', 'hint', 'open']
EditorSection.emits = ['toggle']

/* ---- state ---- */
const openKey = ref('look')
const mobileView = ref('edit')
const device = ref('desktop')
const frame = ref(null)
const justSaved = ref(false)
const uploadingIdx = ref(null)

function toggle(key) {
    openKey.value = openKey.value === key ? '' : key
}

const initials = computed(() => {
    const n = (props.ngo?.name || 'N').trim().split(/\s+/).filter(Boolean)
    return (n.length >= 2 ? n[0][0] + n[1][0] : (props.ngo?.name || 'N').slice(0, 2)).toUpperCase()
})

const liveUrl = computed(() => (props.ngo?.slug ? `/ngos/${props.ngo.slug}` : null))

const logoPreview = ref(resolveLogo(props.ngo?.logo))
function resolveLogo(raw) {
    if (!raw || typeof raw !== 'string') {
        return null
    }
    if (/^https?:\/\//i.test(raw) || raw.startsWith('/assets/') || raw.startsWith('/storage/')) {
        return raw
    }
    if (raw.startsWith('assets/')) {
        return `/${raw}`
    }
    return `/storage/${raw.replace(/^\/+/, '')}`
}

const colourPresets = ['#2563eb', '#0ea5e9', '#059669', '#16a34a', '#ca8a04', '#ea580c', '#dc2626', '#db2777', '#7c3aed', '#0f766e', '#1e293b']

const iconChoices = [
    { emoji: '🎓', label: 'Education', icon: 'graduation-cap' },
    { emoji: '🤝', label: 'Help', icon: 'hands-helping' },
    { emoji: '🌱', label: 'Growth', icon: 'seedling' },
    { emoji: '❤️', label: 'Health', icon: 'heartbeat' },
    { emoji: '🌳', label: 'Environment', icon: 'tree' },
    { emoji: '🙏', label: 'Care', icon: 'hands' },
    { emoji: '🏠', label: 'Housing', icon: 'home' },
    { emoji: '⚖️', label: 'Rights', icon: 'gavel' },
    { emoji: '👥', label: 'Community', icon: 'users' },
    { emoji: '📚', label: 'Learning', icon: 'book' },
    { emoji: '💧', label: 'Water', icon: 'tint' },
    { emoji: '👶', label: 'Children', icon: 'child' },
    { emoji: '💡', label: 'Ideas', icon: 'lightbulb' },
    { emoji: '🌍', label: 'World', icon: 'globe' },
    { emoji: '🩺', label: 'Medical', icon: 'medkit' },
    { emoji: '⭐', label: 'General', icon: 'star' },
]

/* ---- form (same save contract as before) ---- */
function defaultMicrosite() {
    const ms = props.ngo.digitalization_settings?.microsite ?? {}
    const programs = Array.isArray(ms.programs) && ms.programs.length
        ? ms.programs.map((p) => ({ title: p.title ?? '', title_kn: p.title_kn ?? '', body: p.body ?? '', body_kn: p.body_kn ?? '', icon: (p.icon ?? 'star').replace(/^fa-/, '') }))
        : [
            { title: '', title_kn: '', body: '', body_kn: '', icon: 'graduation-cap' },
            { title: '', title_kn: '', body: '', body_kn: '', icon: 'hands-helping' },
            { title: '', title_kn: '', body: '', body_kn: '', icon: 'seedling' },
        ]
    const stories = Array.isArray(ms.stories)
        ? ms.stories.map((s) => ({ title: s.title ?? '', title_kn: s.title_kn ?? '', body: s.body ?? '', body_kn: s.body_kn ?? '', category: s.category ?? '', category_kn: s.category_kn ?? '', date: s.date ?? '', date_kn: s.date_kn ?? '', image: s.image ?? '' }))
        : []
    return {
        ...ms, // round-trip any Kannada (*_kn) and other saved keys
        hero_subtitle: ms.hero_subtitle ?? '', hero_description: ms.hero_description ?? '',
        mission_subtitle: ms.mission_subtitle ?? '', mission_description: ms.mission_description ?? '',
        vision_subtitle: ms.vision_subtitle ?? '', vision_description: ms.vision_description ?? '',
        impact_title: ms.impact_title ?? '', impact_subtitle: ms.impact_subtitle ?? '', impact_description: ms.impact_description ?? '',
        about_extra: ms.about_extra ?? '', about_vision_quote: ms.about_vision_quote ?? '',
        contact_intro: ms.contact_intro ?? '',
        donate_title: ms.donate_title ?? '', donate_subtitle: ms.donate_subtitle ?? '', donate_impact_blurb: ms.donate_impact_blurb ?? '',
        stat_1_h: ms.stat_1_h ?? '', stat_1_p: ms.stat_1_p ?? '',
        stat_2_h: ms.stat_2_h ?? '', stat_2_p: ms.stat_2_p ?? '',
        stat_3_h: ms.stat_3_h ?? '', stat_3_p: ms.stat_3_p ?? '',
        stat_4_h: ms.stat_4_h ?? '', stat_4_p: ms.stat_4_p ?? '',
        programs, stories,
    }
}

const form = useForm({
    // website_url is intentionally NOT sent: it can be an internal path (e.g. /vikasana)
    // which fails the controller's `nullable|url` rule. The editor doesn't expose it.
    custom_domain: props.ngo.custom_domain || '',
    theme_color: props.ngo.theme_color || '#2563eb',
    tawk_property_id: props.ngo.tawk_property_id || '',
    tawk_widget_id: props.ngo.tawk_widget_id || '',
    facebook_url: props.ngo.facebook_url || '',
    instagram_url: props.ngo.instagram_url || '',
    google_business_location_id: props.ngo.google_business_location_id || '',
    google_business_auto_post: !!props.ngo.google_business_auto_post,
    microsite: defaultMicrosite(),
    logo: null,
})

const addProgram = () => form.microsite.programs.length < 12 && form.microsite.programs.push({ title: '', title_kn: '', body: '', body_kn: '', icon: 'star' })
const removeProgram = (idx) => {
    form.microsite.programs.splice(idx, 1)
    if (form.microsite.programs.length === 0) {
        form.microsite.programs.push({ title: '', title_kn: '', body: '', body_kn: '', icon: 'star' })
    }
}
const addStory = () => form.microsite.stories.length < 6 && form.microsite.stories.push({ title: '', title_kn: '', body: '', body_kn: '', category: '', category_kn: '', date: '', date_kn: '', image: '' })
const removeStory = (idx) => form.microsite.stories.splice(idx, 1)

function onLogoChange(event) {
    const file = event.target.files?.[0] || null
    form.logo = file
    if (file) {
        logoPreview.value = URL.createObjectURL(file)
    }
}

async function onStoryPhoto(event, idx) {
    const file = event.target.files?.[0]
    if (!file) {
        return
    }
    uploadingIdx.value = idx
    try {
        const fd = new FormData()
        fd.append('image', file)
        const res = await window.axios.post('/ngo/website/upload-image', fd, { headers: { 'Content-Type': 'multipart/form-data' } })
        form.microsite.stories[idx].image = res.data.url
    } catch (e) {
        // Fallback: show a local preview even if upload fails, so the user isn't blocked.
        form.microsite.stories[idx].image = URL.createObjectURL(file)
    } finally {
        uploadingIdx.value = null
    }
}

function refreshPreview() {
    const f = frame.value
    if (!f) {
        return
    }
    // Re-assign src to force a reload (cache-busted) — robust across origins.
    f.src = props.previewUrl + (props.previewUrl.includes('?') ? '&' : '?') + 't=' + Date.now()
}

const save = () => {
    justSaved.value = false
    form.transform((data) => {
        const { microsite, ...rest } = data
        const cleanedPrograms = (microsite.programs || [])
            .filter((p) => (p.title || '').trim() !== '')
            .map((p) => ({ title: (p.title || '').trim(), title_kn: (p.title_kn || '').trim(), body: (p.body || '').trim(), body_kn: (p.body_kn || '').trim(), icon: (p.icon || 'star').replace(/^fa-/, '') }))
        const cleanedStories = (microsite.stories || [])
            .filter((s) => (s.title || '').trim() !== '')
            .map((s) => ({ title: (s.title || '').trim(), title_kn: (s.title_kn || '').trim(), body: (s.body || '').trim(), body_kn: (s.body_kn || '').trim(), category: (s.category || '').trim(), category_kn: (s.category_kn || '').trim(), date: (s.date || '').trim(), date_kn: (s.date_kn || '').trim(), image: (s.image || '').trim() }))
        return {
            ...rest,
            microsite_json: JSON.stringify({ ...microsite, programs: cleanedPrograms, stories: cleanedStories }),
            _method: 'put',
        }
    }).post('/ngo/digitalization', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            justSaved.value = true
            refreshPreview()
            if (window.innerWidth < 1024) {
                mobileView.value = 'preview'
            }
            setTimeout(() => { justSaved.value = false }, 4000)
        },
    })
}
</script>

<style>
/* NOT scoped on purpose: EditorSection/FieldText/FieldArea are separate inline
   components, and scoped styles don't reach a child component's internal DOM.
   All selectors are `we__`-prefixed to avoid global collisions. */
.we { position: relative; padding-bottom: 5.5rem; }

.we__head { display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 0.75rem; }
.we__title { font-size: 1.6rem; font-weight: 800; color: #0f172a; letter-spacing: -0.01em; }
.we__sub { margin-top: 0.25rem; max-width: 42rem; font-size: 0.9rem; color: #475569; }
.we__head-actions { display: flex; gap: 0.5rem; }

.we__seg { margin-top: 1rem; display: flex; gap: 0.25rem; border-radius: 0.75rem; background: #e2e8f0; padding: 0.25rem; }
.we__seg-btn { flex: 1; border-radius: 0.5rem; padding: 0.5rem; font-size: 0.85rem; font-weight: 700; color: #475569; }
.we__seg-btn.is-on { background: #fff; color: #0f172a; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.12); }

.we__grid { margin-top: 1.25rem; display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
@media (min-width: 1024px) { .we__grid { grid-template-columns: minmax(0, 26rem) minmax(0, 1fr); align-items: start; } }

/* Editor */
.we__editor { display: flex; flex-direction: column; gap: 0.65rem; }
@media (max-width: 1023px) { .we__editor--hidden-mobile { display: none; } }

.we__section { overflow: hidden; border-radius: 1rem; border: 1px solid #e2e8f0; background: #fff; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04); }
.we__section.is-open { border-color: #cbd5e1; box-shadow: 0 12px 28px -18px rgba(15, 23, 42, 0.3); }
.we__section-head { display: flex; width: 100%; align-items: center; gap: 0.75rem; padding: 0.9rem 1rem; text-align: left; }
.we__section-emoji { display: flex; height: 2.25rem; width: 2.25rem; flex-shrink: 0; align-items: center; justify-content: center; border-radius: 0.7rem; background: #f1f5f9; font-size: 1.1rem; }
.we__section-titles { display: flex; min-width: 0; flex-direction: column; }
.we__section-title { font-size: 0.95rem; font-weight: 700; color: #0f172a; }
.we__section-hint { font-size: 0.74rem; color: #64748b; }
.we__chev { margin-left: auto; color: #94a3b8; }
.we__section-body { padding: 0 1rem 1.1rem; }

.we__field { margin-top: 0.7rem; }
.we__label { display: block; margin-bottom: 0.28rem; font-size: 0.78rem; font-weight: 700; color: #334155; }
.we__input { width: 100%; border-radius: 0.6rem; border: 1px solid #cbd5e1; padding: 0.6rem 0.7rem; font-size: 0.95rem; color: #0f172a; }
.we__input::placeholder { color: #94a3b8; font-size: 0.82rem; }
.we__input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
.we__textarea { resize: vertical; line-height: 1.4; }

/* Optional Kannada companion fields — Kannada font + subtle gold marker so they read as "extra" */
.we__field--kn { margin-top: 0.35rem; }
.we__field--kn .we__label { color: #92740a; font-weight: 600; }
.we__input--kn { font-family: 'Noto Sans Kannada', 'Inter', system-ui, sans-serif; border-style: dashed; border-color: #e6c454; background: #fffdf5; }
.we__input--kn::placeholder { font-family: 'Noto Sans Kannada', 'Inter', sans-serif; }
.we__input--kn:focus { border-color: #f2b40c; border-style: solid; box-shadow: 0 0 0 3px rgba(242, 180, 12, 0.18); }

.we__tiny { margin-top: 0.3rem; font-size: 0.72rem; color: #94a3b8; }
.we__divider { margin: 0.9rem 0; border-top: 1px dashed #e2e8f0; }
.we__mt { margin-top: 1rem; }

/* Logo + colour */
.we__logo-row { display: flex; align-items: center; gap: 0.85rem; }
.we__logo-thumb, .we__story-thumb { display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 0.7rem; border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; font-weight: 700; }
.we__logo-thumb { height: 3.5rem; width: 3.5rem; }
.we__story-thumb { height: 3rem; width: 4.5rem; font-size: 0.7rem; }
.we__logo-thumb img, .we__story-thumb img { height: 100%; width: 100%; object-fit: cover; }
.we__file { cursor: pointer; }

.we__swatches { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.we__swatch { height: 2rem; width: 2rem; border-radius: 9999px; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer; transition: transform 0.12s ease; }
.we__swatch:hover { transform: scale(1.1); }
.we__swatch.is-on { box-shadow: 0 0 0 2px #0f172a; }
.we__swatch--custom { display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; text-shadow: 0 1px 2px rgba(0,0,0,0.4); }

/* Icons */
.we__icons { display: grid; grid-template-columns: repeat(8, 1fr); gap: 0.35rem; }
@media (max-width: 420px) { .we__icons { grid-template-columns: repeat(6, 1fr); } }
.we__icon { aspect-ratio: 1; border-radius: 0.55rem; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 1.1rem; cursor: pointer; transition: transform 0.1s ease; }
.we__icon:hover { transform: translateY(-1px); }
.we__icon.is-on { border-color: #2563eb; background: #eff6ff; box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25); }

/* Cards (programmes / stories) */
.we__card { margin-top: 0.7rem; border-radius: 0.8rem; border: 1px solid #e2e8f0; background: #f8fafc; padding: 0.8rem; }
.we__card-head { display: flex; align-items: center; justify-content: space-between; }
.we__card-n { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; }
.we__remove { font-size: 0.72rem; font-weight: 700; color: #dc2626; }
.we__remove:hover { text-decoration: underline; }
.we__fact { margin-top: 0.6rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
.we__story-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
.we__add { margin-top: 0.7rem; font-size: 0.85rem; font-weight: 700; color: #2563eb; }
.we__add:hover { color: #1d4ed8; }
.we__editor-foot { height: 0.5rem; }

/* Buttons */
.we__btn { display: inline-flex; align-items: center; justify-content: center; border-radius: 0.7rem; padding: 0.55rem 0.9rem; font-size: 0.85rem; font-weight: 700; transition: transform 0.12s ease, background 0.12s ease; }
.we__btn:active { transform: scale(0.97); }
.we__btn--ghost { border: 1px solid #cbd5e1; color: #334155; background: #fff; }
.we__btn--ghost:hover { background: #f8fafc; }
.we__btn--soft { background: #eff6ff; color: #1d4ed8; }
.we__btn--soft:hover { background: #dbeafe; }
.we__btn--gold { background: linear-gradient(180deg, #f7c948, #f2b40c); color: #2a1c00; box-shadow: 0 12px 26px -12px rgba(242, 180, 12, 0.9); }
.we__btn--gold:hover { transform: translateY(-1px); }
.we__btn--gold:disabled { opacity: 0.7; cursor: wait; }

/* Preview */
.we__preview { position: sticky; top: 1rem; overflow: hidden; border-radius: 1rem; border: 1px solid #e2e8f0; background: #fff; box-shadow: 0 12px 30px -18px rgba(15, 23, 42, 0.3); }
@media (max-width: 1023px) { .we__preview--hidden-mobile { display: none; } .we__preview { position: relative; } }
.we__preview-bar { display: flex; align-items: center; gap: 0.6rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc; padding: 0.55rem 0.75rem; }
.we__dots { display: flex; gap: 0.3rem; }
.we__dots i { height: 0.6rem; width: 0.6rem; border-radius: 9999px; background: #cbd5e1; }
.we__preview-url { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border-radius: 0.5rem; background: #fff; padding: 0.25rem 0.6rem; font-size: 0.72rem; color: #64748b; border: 1px solid #e2e8f0; }
.we__device { display: flex; gap: 0.2rem; }
.we__device-btn { border-radius: 0.45rem; padding: 0.25rem 0.45rem; font-size: 0.9rem; }
.we__device-btn.is-on { background: #e2e8f0; }
.we__frame-wrap { height: min(78vh, 900px); background: #fff; }
.we__frame-wrap.is-phone { display: flex; justify-content: center; background: #eef2f7; padding: 1rem 0; }
.we__frame { height: 100%; width: 100%; border: 0; }
.we__frame-wrap.is-phone .we__frame { width: 390px; height: 100%; border: 8px solid #0f172a; border-radius: 1.75rem; box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.5); }

/* Save bar */
.we__savebar { position: fixed; bottom: 0; left: 0; right: 0; z-index: 30; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; border-top: 1px solid #e2e8f0; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(8px); padding: 0.7rem 1rem; padding-bottom: max(0.7rem, env(safe-area-inset-bottom)); }
@media (min-width: 1024px) { .we__savebar { left: 17.5rem; } }
.we__savebar-msg { font-size: 0.82rem; }
.we__ok { font-weight: 700; color: #059669; }
.we__dirty { font-weight: 600; color: #b45309; }
.we__save { padding: 0.7rem 1.3rem; font-size: 0.92rem; }
.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
</style>
