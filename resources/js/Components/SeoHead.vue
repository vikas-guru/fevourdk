<template>
    <Head :title="fullTitle">
        <meta head-key="description" name="description" :content="description">
        <meta head-key="keywords" name="keywords" :content="keywords">
        <link head-key="canonical" rel="canonical" :href="canonicalUrl">

        <meta head-key="og:site_name" property="og:site_name" :content="ogSiteName">
        <meta head-key="og:title" property="og:title" :content="ogTitle">
        <meta head-key="og:description" property="og:description" :content="ogDescription">
        <meta head-key="og:type" property="og:type" :content="ogType">
        <meta head-key="og:url" property="og:url" :content="canonicalUrl">
        <meta v-if="ogImage" head-key="og:image" property="og:image" :content="ogImage">
        <meta head-key="og:locale" property="og:locale" :content="locale">

        <meta head-key="twitter:card" name="twitter:card" :content="twitterCard">
        <meta head-key="twitter:title" name="twitter:title" :content="ogTitle">
        <meta head-key="twitter:description" name="twitter:description" :content="ogDescription">
        <meta v-if="ogImage" head-key="twitter:image" name="twitter:image" :content="ogImage">
        <meta v-if="twitterSite" head-key="twitter:site" name="twitter:site" :content="twitterSite">

        <meta v-if="noindex" head-key="robots" name="robots" content="noindex, nofollow">
    </Head>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()

const merged = computed(() => ({
    ...page.props.seoDefaults,
    ...(page.props.seo || {}),
}))

const fullTitle = computed(() => {
    const m = merged.value
    const t = m.title || m.default_title
    if (m.no_title_suffix) {
        return t
    }
    if (String(t).includes('FEVOURD-K')) {
        return t
    }
    const suffix = m.title_suffix || ' | FEVOURD-K'

    return `${t}${suffix}`
})

const description = computed(() => merged.value.description || merged.value.default_description)
const keywords = computed(() => merged.value.keywords || '')

const canonicalUrl = computed(() => {
    const base = String(merged.value.site_url || '').replace(/\/$/, '')
    let path = merged.value.canonicalPath
    if (path == null || path === '') {
        const u = page.url || '/'
        path = String(u).split('?')[0] || '/'
    }
    if (!path.startsWith('/')) {
        path = `/${path}`
    }

    return base + path
})

const ogSiteName = computed(() => merged.value.og_site_name || merged.value.site_name || 'FEVOURD-K')
const ogTitle = computed(() => merged.value.og_title || merged.value.title || merged.value.default_title)
const ogDescription = computed(() => merged.value.og_description || merged.value.description || merged.value.default_description)
const ogType = computed(() => merged.value.og_type || 'website')
const ogImage = computed(() => merged.value.og_image || merged.value.og_image_default || '')
const locale = computed(() => merged.value.locale || 'en_IN')
const twitterCard = computed(() => merged.value.twitter_card || 'summary_large_image')
const twitterSite = computed(() => merged.value.twitter_site || '')
const noindex = computed(() => !!merged.value.noindex)
</script>
