<template>
    <AppLayout :title="title" :hide-chrome="true">
        <div class="fksh flex min-h-screen flex-col lg:flex-row">
            <!-- Mobile top bar -->
            <div class="fksh-topbar lg:hidden">
                <Link href="/" class="flex items-center gap-2">
                    <img :src="logo" alt="" class="h-8 w-8 object-contain" />
                    <span class="fksh-brand-word">FEVOURD-K</span>
                </Link>
                <button type="button" class="fksh-burger" aria-label="Open menu" @click="drawerOpen = true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            <!-- Scrim -->
            <Transition name="fksh-fade">
                <div v-if="drawerOpen" class="fksh-scrim lg:hidden" aria-hidden="true" @click="drawerOpen = false" />
            </Transition>

            <!-- Sidebar -->
            <aside class="fksh-aside" :class="drawerOpen ? 'is-open' : ''">
                <div class="fksh-grain" aria-hidden="true"></div>

                <!-- Brand -->
                <div class="fksh-head">
                    <Link href="/" class="fksh-brand" @click="drawerOpen = false">
                        <span class="fksh-brand__ring"><img :src="logo" alt="" /></span>
                        <span class="fksh-brand__text">
                            <span class="fksh-brand__name">FEVOURD-K</span>
                            <span class="fksh-brand__sub">Karnataka NGO hub</span>
                        </span>
                    </Link>
                    <button type="button" class="fksh-x lg:hidden" aria-label="Close menu" @click="drawerOpen = false">×</button>
                </div>

                <!-- Account -->
                <div v-if="user" class="fksh-account">
                    <span class="fksh-avatar">{{ initials }}</span>
                    <span class="fksh-account__text">
                        <span class="fksh-account__name">{{ user.name }}</span>
                        <span class="fksh-account__role">{{ roleLabel }}</span>
                    </span>
                </div>

                <!-- Nav -->
                <nav class="fksh-nav">
                    <template v-for="group in navGroups" :key="group.label">
                        <p class="fksh-group">{{ group.label }}</p>
                        <Link
                            v-for="item in group.items"
                            :key="item.href"
                            :href="item.href"
                            class="fksh-link"
                            :class="{ 'is-active': isActive(item.href) }"
                            @click="drawerOpen = false"
                        >
                            <span class="fksh-link__icon" v-html="item.icon" aria-hidden="true"></span>
                            <span class="fksh-link__label">{{ item.label }}</span>
                        </Link>
                    </template>
                </nav>

                <!-- Footer actions -->
                <div class="fksh-foot">
                    <Link v-if="!user" href="/login" class="fksh-cta">Log in</Link>
                    <button v-else type="button" class="fksh-logout" @click="logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                        Sign out
                    </button>
                </div>
            </aside>

            <!-- Content -->
            <main class="fksh-main">
                <slot />
            </main>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    title: { type: String, default: '' },
})

const logo = '/assets/images/fevourd-k/logo.png'
const page = usePage()
const drawerOpen = ref(false)

const user = computed(() => page.props.auth?.user ?? null)
const roles = computed(() => (page.props.auth?.roles ?? []).map((r) => (typeof r === 'string' ? r : r?.name)).filter(Boolean))
const has = (r) => roles.value.includes(r)

const currentPath = computed(() => {
    const url = page.url || '/'
    return url.split('?')[0]
})
const isActive = (href) => {
    const p = currentPath.value
    if (href === '/') return p === '/'
    return p === href || p.startsWith(href + '/')
}

const initials = computed(() => {
    const n = (user.value?.name || 'F').trim()
    const parts = n.split(/\s+/).filter(Boolean)
    return (parts.length >= 2 ? parts[0][0] + parts[1][0] : n.slice(0, 2)).toUpperCase()
})

const roleLabel = computed(() => {
    if (has('corporate_csr_manager')) return 'CSR partner'
    if (has('ngo_admin') || has('ngo_staff')) return 'NGO workspace'
    if (has('ngo_finance')) return 'NGO treasury'
    if (has('donor')) return 'Supporter'
    return 'Member'
})

const dashboardHref = computed(() => {
    if (has('corporate_csr_manager')) return '/csr/dashboard'
    if (has('ngo_admin') || has('ngo_staff')) return '/ngo/dashboard'
    if (has('ngo_finance')) return '/ngo/finance'
    if (has('donor')) return '/donor/dashboard'
    return '/dashboard'
})

// SVG icon set (stroke, currentColor) — compact inline paths.
const ic = {
    home: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>',
    feed: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>',
    ngos: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-5h6v5"/></svg>',
    campaign: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a1 1 0 001 1h3l5 4V6L7 10H4a1 1 0 00-1 1z"/><path d="M16 9a4 4 0 010 6"/></svg>',
    event: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>',
    gallery: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>',
    grid: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>',
    heart: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 5.6a5 5 0 00-7.1 0L12 7.3l-1.7-1.7a5 5 0 10-7.1 7.1L12 21.5l8.8-8.8a5 5 0 000-7.1z"/></svg>',
    building: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M9 7h2M13 7h2M9 11h2M13 11h2M9 15h2M13 15h2"/></svg>',
    user: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0116 0"/></svg>',
}

const navGroups = computed(() => {
    const groups = [
        {
            label: 'Discover',
            items: [
                { label: 'Home', href: '/', icon: ic.home },
                { label: 'Community feed', href: '/feeds', icon: ic.feed },
                { label: 'NGOs', href: '/ngos', icon: ic.ngos },
                { label: 'Campaigns', href: '/campaigns', icon: ic.campaign },
                { label: 'Events', href: '/events', icon: ic.event },
                { label: 'Gallery', href: '/gallery', icon: ic.gallery },
            ],
        },
    ]

    const workspace = { label: 'Workspace', items: [] }
    if (user.value) {
        workspace.items.push({ label: 'My dashboard', href: dashboardHref.value, icon: ic.grid })
    }
    if (has('ngo_admin') || has('ngo_staff') || has('ngo_finance')) {
        workspace.items.push({ label: 'NGO workspace', href: '/ngo/dashboard', icon: ic.building })
    }
    if (has('corporate_csr_manager')) {
        workspace.items.push({ label: 'CSR portal', href: '/csr/dashboard', icon: ic.building })
    } else {
        workspace.items.push({ label: 'For companies (CSR)', href: '/csr/register', icon: ic.building })
    }
    workspace.items.push({ label: 'Donate', href: '/donate', icon: ic.heart })
    if (workspace.items.length) groups.push(workspace)

    if (user.value) {
        groups.push({ label: 'Account', items: [{ label: 'My profile', href: '/profile', icon: ic.user }] })
    }
    return groups
})

function logout() {
    drawerOpen.value = false
    router.post('/logout')
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap');

.fksh {
    --ink: #0d1f5c; --ink-2: #11286e; --ink-deep: #081640;
    --gold: #f2b40c; --gold-soft: #f7c948;
    --font-display: 'Fraunces','Playfair Display',Georgia,serif;
    background: #f5f3ee;
}

/* mobile top bar */
.fksh-topbar {
    position: sticky; top: 0; z-index: 30; display: flex; align-items: center; justify-content: space-between;
    padding: .7rem 1rem; background: radial-gradient(120% 160% at 90% -20%, #1b3aa0 0%, var(--ink-2) 45%, var(--ink-deep) 100%);
    color: #f4eeda;
}
.fksh-brand-word { font-family: var(--font-display); font-weight: 600; font-size: 1.05rem; letter-spacing: .01em; white-space: nowrap; flex-shrink: 0; }
.fksh-burger { display: grid; place-items: center; width: 40px; height: 40px; border-radius: 12px; border: 1px solid rgba(247,240,223,.22); background: rgba(247,240,223,.08); color: #f4eeda; cursor: pointer; }
.fksh-burger svg { width: 22px; height: 22px; }

.fksh-scrim { position: fixed; inset: 0; z-index: 40; background: rgba(8,16,40,.55); backdrop-filter: blur(2px); }

/* sidebar */
.fksh-aside {
    position: fixed; inset: 0 auto 0 0; z-index: 50; display: flex; flex-direction: column; width: 17.5rem; max-width: 84vw;
    overflow: hidden; color: #e9e6da; isolation: isolate;
    background: radial-gradient(120% 90% at 110% -10%, #1b3aa0 0%, var(--ink-2) 42%, var(--ink-deep) 100%);
    box-shadow: 0 30px 60px -20px rgba(8,16,40,.7);
    transform: translateX(-100%); transition: transform .26s cubic-bezier(.2,.7,.2,1);
}
.fksh-aside.is-open { transform: translateX(0); }
.fksh-grain {
    position: absolute; inset: 0; z-index: -1; opacity: .4; mix-blend-mode: overlay; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
}

.fksh-head { display: flex; align-items: center; gap: .5rem; padding: 1.25rem 1.1rem 1rem; }
.fksh-brand { display: flex; align-items: center; gap: .7rem; text-decoration: none; color: inherit; min-width: 0; }
.fksh-brand__ring { display: grid; place-items: center; width: 44px; height: 44px; border-radius: 14px; background: rgba(247,240,223,.1); border: 1px solid rgba(242,180,12,.4); flex-shrink: 0; }
.fksh-brand__ring img { width: 26px; height: 26px; object-fit: contain; }
.fksh-brand__text { display: flex; flex-direction: column; line-height: 1.1; min-width: 0; }
.fksh-brand__name { font-family: var(--font-display); font-weight: 600; font-size: 1.15rem; color: #fff; white-space: nowrap; }
.fksh-brand__sub { font-size: .72rem; color: var(--gold-soft); font-weight: 600; letter-spacing: .04em; }
.fksh-x { margin-left: auto; width: 34px; height: 34px; border-radius: 50%; border: none; background: rgba(247,240,223,.1); color: #f4eeda; font-size: 1.4rem; line-height: 1; cursor: pointer; }

.fksh-account { display: flex; align-items: center; gap: .65rem; margin: 0 1.1rem .4rem; padding: .7rem .8rem; border-radius: 14px; background: rgba(247,240,223,.06); border: 1px solid rgba(247,240,223,.1); }
.fksh-avatar { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, var(--gold), var(--gold-soft)); color: #2a1c00; font-weight: 800; font-size: .9rem; flex-shrink: 0; }
.fksh-account__text { display: flex; flex-direction: column; line-height: 1.2; min-width: 0; }
.fksh-account__name { font-weight: 700; font-size: .9rem; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.fksh-account__role { font-size: .72rem; color: var(--gold-soft); font-weight: 600; }

.fksh-nav { flex: 1; overflow-y: auto; padding: .6rem .7rem 1rem; }
.fksh-group { margin: .9rem .6rem .35rem; font-size: .66rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: rgba(247,240,223,.45); }
.fksh-group:first-child { margin-top: .2rem; }
.fksh-link { position: relative; display: flex; align-items: center; gap: .75rem; padding: .62rem .7rem; border-radius: 12px; color: #d6d3c4; font-weight: 600; font-size: .92rem; text-decoration: none; transition: background .18s ease, color .18s ease; }
.fksh-link:hover { background: rgba(247,240,223,.08); color: #fff; }
.fksh-link.is-active { background: rgba(242,180,12,.14); color: #fff; }
.fksh-link.is-active::before { content: ""; position: absolute; left: -.7rem; top: 50%; transform: translateY(-50%); width: 4px; height: 60%; border-radius: 0 4px 4px 0; background: var(--gold); }
.fksh-link__icon { display: grid; place-items: center; width: 34px; height: 34px; border-radius: 10px; background: rgba(247,240,223,.07); color: var(--gold-soft); flex-shrink: 0; }
.fksh-link.is-active .fksh-link__icon { background: var(--gold); color: #2a1c00; }
.fksh-link__icon :deep(svg), .fksh-link__icon svg { width: 18px; height: 18px; }
.fksh-link__label { min-width: 0; }

.fksh-foot { padding: .8rem 1.1rem calc(1rem + env(safe-area-inset-bottom)); border-top: 1px solid rgba(247,240,223,.1); }
.fksh-logout, .fksh-cta { display: flex; width: 100%; align-items: center; justify-content: center; gap: .5rem; padding: .75rem 1rem; border-radius: 999px; font: inherit; font-weight: 700; font-size: .9rem; cursor: pointer; transition: background .2s ease, transform .2s ease; }
.fksh-logout { border: 1px solid rgba(247,240,223,.25); background: rgba(247,240,223,.06); color: #f4eeda; }
.fksh-logout:hover { background: rgba(247,240,223,.14); }
.fksh-logout svg { width: 18px; height: 18px; }
.fksh-cta { border: none; background: var(--gold); color: #2a1c00; box-shadow: 0 14px 30px -14px rgba(242,180,12,.85); text-decoration: none; }
.fksh-cta:hover { transform: translateY(-1px); }

/* content */
.fksh-main { flex: 1; min-width: 0; }

/* desktop: static sidebar, no transform */
@media (min-width: 1024px) {
    .fksh-aside { position: sticky; top: 0; height: 100vh; transform: none; max-width: none; box-shadow: none; border-right: 1px solid rgba(8,16,40,.15); }
    .fksh-scrim { display: none; }
}

@media (prefers-reduced-motion: reduce) {
    .fksh-aside { transition: none; }
}
</style>
