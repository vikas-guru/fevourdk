<template>
    <div class="min-h-screen bg-gray-50">
        <SeoHead />
        <!-- Loader: Teleport to body so it is never covered by nav (also z-50) or page stacking contexts -->
        <Teleport to="body">
            <div
                v-if="isLoading"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-gradient-to-br from-primary-600 to-primary-800"
                aria-busy="true"
                aria-live="polite"
            >
                <div class="text-center text-white">
                    <div class="mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full backdrop-blur-sm mb-6">
                            <img :src="logoImage" alt="FEVOURD-K" class="w-12 h-12 object-contain">
                        </div>
                        <h1 class="text-xl font-bold mb-2">FEVOURD-K</h1>
                        <p class="text-xl opacity-90">Empowering Karnataka's NGOs</p>
                    </div>

                    <div class="max-w-md mx-auto px-6">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <p class="text-sm font-medium mb-2 opacity-80">Thought of the Day</p>
                            <p class="text-lg italic leading-relaxed">"{{ currentThought.text }}"</p>
                            <p class="text-sm mt-3 opacity-80">- {{ currentThought.author }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="flex justify-center space-x-2">
                            <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0s"></div>
                            <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 shadow-2xl border-b border-blue-500/20 sticky top-0 z-50 backdrop-blur-lg">
        <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="flex justify-between items-center h-20">

                <!-- Logo -->
                <div class="flex items-center flex-1">
                    <Link href="/" class="flex items-center space-x-4 group transform hover:scale-105 transition-all duration-300">
                        <div class="w-14 h-14 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-2xl group-hover:bg-white/20 transition-all duration-300 border border-white/20">
                        <img :src="logoImage" alt="FEVOURD-K" class="w-12 h-12 object-contain">
                        </div>
                        <div class="flex flex-col">
                            <span class="text font-bold text-white tracking-tight">FEVOURD-K</span>
                            <p class="text-sm text-blue-200 font-medium hidden sm:block">Empowering Karnataka</p>
                        </div>
                    </Link>
                </div>

            <!-- Desktop Menu -->
            <div v-if="!isInstalledPwa" class="hidden lg:flex items-center space-x-3 flex-1 justify-center">

                <Link href="/" class="nav-link-modern">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7 7 7-7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </Link>

                <!-- Explore Dropdown -->
                <div class="relative">
                    <button
                        @click="toggleExplore"
                        class="nav-link-modern"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Explore
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 transform scale-95 -translate-y-2"
                        enter-to-class="opacity-100 transform scale-100 translate-y-0"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100 transform scale-100 translate-y-0"
                        leave-to-class="opacity-0 transform scale-95 -translate-y-2"
                    >
                        <div
                            v-if="showExploreMenu"
                            class="absolute left-0 mt-3 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl ring-2 ring-blue-500/20 z-[999] border border-white/50"
                        >
                            <div class="p-2">
                                <Link href="/ngos" class="dropdown-item-modern">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">NGOs</div>
                                        <div class="text-xs text-gray-500">Verified organizations</div>
                                    </div>
                                </Link>
                                <Link href="/campaigns" class="dropdown-item-modern">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Campaigns</div>
                                        <div class="text-xs text-gray-500">Active fundraising</div>
                                    </div>
                                </Link>
                                <Link href="/events" class="dropdown-item-modern">
                                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Events</div>
                                        <div class="text-xs text-gray-500">Upcoming activities</div>
                                    </div>
                                </Link>
                                <Link href="/gallery" class="dropdown-item-modern">
                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Gallery</div>
                                        <div class="text-xs text-gray-500">Photos & videos</div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </transition>
                </div>

                <Link href="/csr/register" class="btn-modern-green-compact">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    CSR
                </Link>

                <Link href="/donate" class="btn-modern-accent">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Donate
                </Link>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4 flex-1 justify-end">

                <button
                    v-if="showPwaShell"
                    @click="toggleAppSidebar"
                    class="lg:hidden p-2 rounded-lg bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 transition-colors"
                    aria-label="Toggle app sidebar"
                >
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16"></path>
                    </svg>
                </button>

                <!-- Mobile Menu Button -->
                <button
                    v-if="!showPwaShell"
                    @click="toggleMobileMenu"
                    class="lg:hidden p-2 rounded-lg bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 transition-colors"
                >
                    <svg v-if="!showMobileMenu" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg v-else class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Search (Desktop Only) -->
                <div v-if="!isInstalledPwa" class="hidden lg:block">
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Search..." 
                            class="w-48 pl-10 pr-3 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg text-white placeholder-blue-200 text-sm focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300"
                        >
                        <svg class="absolute left-3 top-2.5 h-3.5 w-3.5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Guest (Desktop Only) -->
                <div v-if="!$page.props.auth.user && !isInstalledPwa" class="hidden lg:flex items-center space-x-4">
                    <Link href="/login" class="nav-link-modern">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login
                    </Link>
                    <Link href="/register" class="btn-modern-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Register
                    </Link>
                </div>

                <!-- Notifications + account menu (desktop; was v-if/v-else-if bug — profile never showed) -->
                <div v-if="currentUser && !isInstalledPwa" class="hidden lg:flex items-center gap-1 shrink-0">
                <div class="relative">
                    <button
                        type="button"
                        @click="toggleNotifications"
                        class="p-2 rounded-lg bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 transition-colors relative"
                        aria-label="Open notifications"
                    >
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            v-if="unreadNotificationsCount > 0"
                            class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-[10px] text-white flex items-center justify-center font-semibold"
                        >
                            {{ unreadNotificationsCount > 9 ? '9+' : unreadNotificationsCount }}
                        </span>
                    </button>

                    <transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 transform scale-95 -translate-y-2"
                        enter-to-class="opacity-100 transform scale-100 translate-y-0"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100 transform scale-100 translate-y-0"
                        leave-to-class="opacity-0 transform scale-95 -translate-y-2"
                    >
                        <div
                            v-if="showNotificationMenu"
                            class="absolute right-0 mt-3 w-96 max-w-[92vw] bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl ring-2 ring-blue-500/20 z-[999] border border-white/50"
                        >
                            <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-900">Notifications</p>
                                <button
                                    v-if="unreadNotificationsCount > 0"
                                    type="button"
                                    class="text-xs text-blue-600 hover:text-blue-700 font-medium"
                                    @click="markAllNotificationsRead"
                                >
                                    Mark all read
                                </button>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <button
                                    v-for="notification in notifications"
                                    :key="notification.id"
                                    type="button"
                                    class="w-full text-left px-4 py-3 border-b border-slate-100 hover:bg-slate-50 transition"
                                    :class="!notification.read_at ? 'bg-blue-50/70' : ''"
                                    @click="openNotification(notification)"
                                >
                                    <p class="text-sm font-semibold text-slate-900">{{ notification.title }}</p>
                                    <p class="text-xs text-slate-600 mt-0.5">{{ notification.body }}</p>
                                    <p class="text-[11px] text-slate-500 mt-1">{{ formatNotificationTime(notification.created_at) }}</p>
                                </button>
                                <div v-if="notifications.length === 0" class="px-4 py-8 text-center text-sm text-slate-500">
                                    No notifications yet.
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>

                <div class="relative profile-menu-root">
                    <button
                        type="button"
                        class="flex items-center gap-1.5 rounded-full pl-1 pr-2 py-1 bg-white/10 backdrop-blur-sm border border-white/25 hover:bg-white/20 transition-all ring-offset-2 ring-offset-blue-700 focus:outline-none focus:ring-2 focus:ring-white/50"
                        aria-label="Account menu"
                        :aria-expanded="showUserMenu"
                        @click="toggleUser"
                    >
                        <span class="relative flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-amber-400 to-orange-500 shadow-md ring-2 ring-white/30">
                            <img
                                v-if="headerAvatarUrl"
                                :src="headerAvatarUrl"
                                alt=""
                                class="h-full w-full object-cover"
                            >
                            <span v-else class="text-sm font-bold text-white">{{ userInitial }}</span>
                        </span>
                        <svg class="hidden h-4 w-4 text-white/90 sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 transform scale-95 -translate-y-2"
                        enter-to-class="opacity-100 transform scale-100 translate-y-0"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100 transform scale-100 translate-y-0"
                        leave-to-class="opacity-0 transform scale-95 -translate-y-2"
                    >
                        <div
                            v-if="showUserMenu"
                            class="absolute right-0 mt-2 w-[min(100vw-1.5rem,22rem)] bg-white rounded-2xl shadow-2xl ring-2 ring-blue-500/20 z-[999] border border-slate-100 overflow-hidden flex flex-col max-h-[min(92vh,36rem)]"
                        >
                            <div class="shrink-0 bg-gradient-to-br from-blue-600 to-indigo-700 px-4 py-3.5 text-white">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-full bg-white/20 ring-2 ring-white/40">
                                        <img
                                            v-if="headerAvatarUrl"
                                            :src="headerAvatarUrl"
                                            alt=""
                                            class="h-full w-full object-cover"
                                        >
                                        <span v-else class="text-base font-bold leading-none">{{ userInitial }}</span>
                                    </span>
                                    <div class="min-w-0 flex-1 space-y-1">
                                        <p class="truncate text-sm font-semibold leading-tight tracking-tight text-white">
                                            {{ currentUser.name }}
                                        </p>
                                        <p class="truncate text-xs leading-snug text-blue-100/95">
                                            {{ currentUser.email }}
                                        </p>
                                        <span class="inline-flex w-fit rounded-full bg-white/15 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white/95">
                                            {{ primaryRoleLabel }}
                                        </span>
                                    </div>
                                </div>
                                <div
                                    v-if="profileMeta"
                                    class="mt-3 flex items-center justify-between gap-3 border-t border-white/15 pt-3 text-[11px] leading-none text-blue-100"
                                >
                                    <div class="min-w-0">
                                        <span class="text-white/65">Profile</span>
                                        <span class="ml-1 font-semibold tabular-nums text-white">{{ profileMeta.completion_percent ?? 0 }}%</span>
                                    </div>
                                    <div class="h-3 w-px shrink-0 bg-white/25" aria-hidden="true" />
                                    <div class="min-w-0 text-right">
                                        <span class="text-white/65">Cause points</span>
                                        <span class="ml-1 font-semibold tabular-nums text-white">{{ profileMeta.social_cause_points ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="account-dropdown-scroll min-h-0 flex-1 overflow-y-auto overscroll-contain p-1.5">
                                <p class="px-2.5 pb-1 pt-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-400">Go to</p>
                                <Link :href="getDashboardUrl()" :class="accountMenuItemClass(getDashboardUrl())" @click="showUserMenu = false">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7 7 7-7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold leading-snug text-slate-900">Dashboard</div>
                                        <div class="mt-0.5 text-xs leading-snug text-slate-500">Your workspace home</div>
                                    </div>
                                </Link>
                                <Link href="/feeds" :class="accountMenuItemClass('/feeds')" @click="showUserMenu = false">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h8m-8 4h6M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold leading-snug text-slate-900">Live NGO feeds</div>
                                        <div class="mt-0.5 text-xs leading-snug text-slate-500">Community updates &amp; map</div>
                                    </div>
                                </Link>

                                <template v-if="isRole(['ngo_admin', 'ngo_staff'])">
                                    <div class="my-2 border-t border-slate-100" />
                                    <p class="px-2.5 pb-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">NGO</p>
                                    <Link href="/ngo/post-update" :class="accountMenuItemClass('/ngo/post-update')" @click="showUserMenu = false">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold leading-snug text-slate-900">Post an update</div>
                                            <div class="mt-0.5 text-xs leading-snug text-slate-500">Publish to the live feed</div>
                                        </div>
                                    </Link>
                                    <Link href="/ngo/feed-studio" :class="accountMenuItemClass('/ngo/feed-studio')" @click="showUserMenu = false">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-700">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold leading-snug text-slate-900">Feed studio</div>
                                            <div class="mt-0.5 text-xs leading-snug text-slate-500">Views, likes &amp; SEO</div>
                                        </div>
                                    </Link>
                                    <Link href="/ngo/dashboard" :class="accountMenuItemClass('/ngo/dashboard')" @click="showUserMenu = false">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7 7 7-7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold leading-snug text-slate-900">NGO workspace</div>
                                            <div class="mt-0.5 text-xs leading-snug text-slate-500">Campaigns, donations &amp; more</div>
                                        </div>
                                    </Link>
                                </template>

                                <template v-if="isRole(['donor'])">
                                    <div class="my-2 border-t border-slate-100" />
                                    <Link href="/donor/dashboard" :class="accountMenuItemClass('/donor/dashboard')" @click="showUserMenu = false">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-700">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold leading-snug text-slate-900">Donor dashboard</div>
                                            <div class="mt-0.5 text-xs leading-snug text-slate-500">Giving history</div>
                                        </div>
                                    </Link>
                                </template>

                                <template v-if="isRole(['corporate_csr_manager'])">
                                    <div class="my-2 border-t border-slate-100" />
                                    <Link href="/csr/dashboard" :class="accountMenuItemClass('/csr/dashboard')" @click="showUserMenu = false">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-teal-100 text-teal-700">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold leading-snug text-slate-900">CSR dashboard</div>
                                            <div class="mt-0.5 text-xs leading-snug text-slate-500">Corporate programmes</div>
                                        </div>
                                    </Link>
                                </template>

                                <template v-if="isRole(['super_admin', 'state_admin'])">
                                    <div class="my-2 border-t border-slate-100" />
                                    <Link href="/admin/dashboard" :class="accountMenuItemClass('/admin/dashboard')" @click="showUserMenu = false">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-200 text-slate-800">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold leading-snug text-slate-900">Admin</div>
                                            <div class="mt-0.5 text-xs leading-snug text-slate-500">Platform management</div>
                                        </div>
                                    </Link>
                                </template>

                                <div class="my-2 border-t border-slate-100" />
                                <p class="px-2.5 pb-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">Account</p>
                                <Link href="/profile" :class="accountMenuItemClass('/profile')" @click="showUserMenu = false">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-purple-100 text-purple-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold leading-snug text-slate-900">Profile</div>
                                        <div class="mt-0.5 text-xs leading-snug text-slate-500">Name, photo &amp; details</div>
                                    </div>
                                </Link>
                                <Link href="/settings" :class="accountMenuItemClass('/settings')" @click="showUserMenu = false">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold leading-snug text-slate-900">Settings</div>
                                        <div class="mt-0.5 text-xs leading-snug text-slate-500">Preferences &amp; security</div>
                                    </div>
                                </Link>

                                <div class="mt-2 border-t border-slate-100 p-1.5">
                                    <button
                                        type="button"
                                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50"
                                        @click="logoutFromMenu"
                                    >
                                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Log out
                                    </button>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
                </div>

            </div>

        </div>
    </div>
</nav>

<!-- PWA App Sidebar -->
<div
    v-if="showPwaShell && showAppSidebar"
    class="lg:hidden fixed inset-0 z-[60] bg-black/40 backdrop-blur-sm"
    @click="showAppSidebar = false"
>
    <aside class="w-[88%] max-w-sm h-full bg-slate-50 shadow-2xl p-4 flex flex-col" @click.stop>
        <div class="flex justify-end mb-1">
            <button @click="showAppSidebar = false" class="p-1.5 rounded-md hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div
            v-if="currentUser"
            class="rounded-3xl border border-slate-200 bg-white p-4 mb-4 shadow-sm"
        >
            <div class="flex items-start gap-3">
                <div class="relative w-12 h-12 rounded-2xl text-white flex items-center justify-center shadow-md overflow-hidden border" :class="profileAvatarShellClass">
                    <div ref="profileLottieRef" class="absolute inset-0"></div>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ currentUser.name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ currentUser.email }}</p>
                    <div v-if="profileMeta" class="mt-1 inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700 border border-blue-100">
                        {{ profileMeta.social_cause_points ?? 0 }} cause points
                    </div>
                </div>
                <Link href="/profile" @click="showAppSidebar = false" class="ml-auto rounded-lg border border-slate-200 px-2 py-1 text-[11px] font-semibold text-slate-700 hover:bg-slate-100 transition">
                    View
                </Link>
            </div>
            <div v-if="profileMeta" class="mt-3 rounded-xl bg-slate-50 border border-slate-200 px-3 py-2">
                <div class="flex items-center justify-between text-xs">
                    <p class="font-semibold text-slate-700">Profile Completion</p>
                    <p class="font-bold text-blue-700">{{ roadmapProgressPercent }}%</p>
                </div>
                <div class="mt-2 h-2 rounded-full bg-slate-200 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-500 transition-all duration-700" :style="{ width: `${roadmapProgressPercent}%` }"></div>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto pr-1">
            <div v-for="section in appSidebarLinks" :key="section.section" class="mb-4">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 mb-2">{{ section.section }}</p>
                <div class="space-y-1">
                    <Link
                        v-for="item in section.items"
                        :key="item.href"
                        :href="item.href"
                        @click="showAppSidebar = false"
                        class="group flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm transition border"
                        :class="isActiveLink(item.href) ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:border-blue-200 hover:bg-blue-50/50'"
                    >
                        <span
                            class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold transition"
                            :class="isActiveLink(item.href) ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-blue-100 group-hover:text-blue-700'"
                        >
                            {{ item.icon }}
                        </span>
                        <span class="font-medium">{{ item.label }}</span>
                        <svg
                            class="w-4 h-4 ml-auto opacity-70"
                            :class="isActiveLink(item.href) ? 'text-white' : 'text-slate-400 group-hover:text-blue-500'"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </Link>
                </div>
            </div>

            <div class="rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 p-3.5 mb-4 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-emerald-200/40 blur-xl"></div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-700 mb-1.5">Impact Roadmap</p>
                <div class="flex items-end justify-between">
                    <p class="text-sm font-semibold text-slate-900">Mission Progress</p>
                    <p class="text-xs font-bold text-emerald-700">{{ roadmapCompletedCount }}/{{ roadmapSteps.length }} done</p>
                </div>
                <div class="mt-2 h-2 rounded-full bg-emerald-100 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 transition-all duration-700 roadmap-progress-stripes" :style="{ width: `${roadmapProgressPercent}%` }"></div>
                </div>
                <div class="mt-3 space-y-1.5">
                    <div
                        v-for="step in roadmapSteps"
                        :key="step.id"
                        class="rounded-xl px-2.5 py-2 border flex items-center gap-2.5"
                        :class="step.completed ? 'bg-white/90 border-emerald-200' : 'bg-white/60 border-emerald-100'"
                    >
                        <span
                            class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold"
                            :class="step.completed ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500'"
                        >
                            {{ step.completed ? '✓' : step.id }}
                        </span>
                        <p class="text-xs leading-4" :class="step.completed ? 'text-slate-800' : 'text-slate-600'">{{ step.label }}</p>
                    </div>
                </div>

                <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 px-2.5 py-2 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] text-amber-700 font-semibold">Reward</p>
                        <p class="text-xs text-slate-700">Complete all steps to collect +120 points</p>
                    </div>
                    <button
                        type="button"
                        class="rounded-lg px-2.5 py-1.5 text-[11px] font-semibold transition"
                        :disabled="!canCollectRoadmapReward || roadmapRewardCollected"
                        :class="canCollectRoadmapReward && !roadmapRewardCollected ? 'bg-amber-500 text-white hover:bg-amber-600' : 'bg-slate-200 text-slate-500 cursor-not-allowed'"
                        @click="collectRoadmapReward"
                    >
                        {{ roadmapRewardCollected ? 'Collected' : 'Collect' }}
                    </button>
                </div>

                <transition name="reward-pop">
                    <div
                        v-if="showRewardBurst"
                        class="pointer-events-none absolute inset-0 flex items-center justify-center"
                    >
                        <div class="reward-chip">+120</div>
                    </div>
                </transition>
            </div>
        </div>

        <button
            v-if="currentUser"
            @click="logout"
            class="mt-2 w-full rounded-xl bg-red-50 border border-red-200 text-red-600 px-3 py-2.5 text-sm font-semibold hover:bg-red-100 transition"
        >
            Logout
        </button>
    </aside>
</div>

<!-- Mobile Menu Popup -->
<div v-if="showMobileMenu && !isInstalledPwa" class="lg:hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-6 m-4 max-w-sm w-full animate-scale-in mobile-menu-container" @click.stop>
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg mb-4">
                <img :src="logoImage" alt="FEVOURD-K" class="w-12 h-12 object-contain">
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">FEVOURD-K</h3>
            <p class="text-gray-600">Empowering Karnataka's NGOs</p>
        </div>
        
        <div class="space-y-3">
            <inertia-link 
                href="/" 
                class="w-full flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7 7 7-7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Home
            </inertia-link>
            
            <inertia-link 
                href="/ngos" 
                class="w-full flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                NGOs
            </inertia-link>
            
            <inertia-link 
                href="/campaigns" 
                class="w-full flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Campaigns
            </inertia-link>
            
            <inertia-link 
                href="/donate" 
                class="w-full flex items-center px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-medium rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-200"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Donate
            </inertia-link>
            
            <div v-if="!$page.props.auth.user" class="border-t pt-3 space-y-3">
                <inertia-link 
                    href="/login" 
                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login
                </inertia-link>
                
                <inertia-link 
                    href="/register" 
                    class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Register
                </inertia-link>
            </div>
            
            <button 
                @click="showMobileMenu = false"
                class="w-full px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors"
            >
                Close
            </button>
        </div>
    </div>
</div>

        <!-- Page Content -->
        <main :class="{ 'pb-20': showPwaShell }">
            <slot />
        </main>
        
        <!-- Footer -->
        <footer v-if="!isInstalledPwa" class="bg-gray-900 text-white" :class="{ 'mb-16 lg:mb-0': showPwaShell }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Organization Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold mb-4">FEVOURD-K</h3>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Empowering Karnataka's NGOs through digital innovation and strategic partnerships.
                        </p>
                        <div class="space-y-2 pt-4">
                            <a href="mailto:fevourd.karnataka@gmail.com" class="flex items-center text-gray-400 hover:text-white text-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 01-2.83-2.83l-3.72 3.72V7a2 2 0 00-2-2H4a2 2 0 00-2-2v8a2 2 0 002 2h14a2 2 0 002 2z" />
                                </svg>
                                fevourd.karnataka@gmail.com
                            </a>
                            <div class="flex space-x-4">
                                <a href="tel:9448464171" class="text-gray-400 hover:text-white text-sm">
                                    +91 94484 64171
                                </a>
                                <a href="tel:7483159735" class="text-gray-400 hover:text-white text-sm">
                                    +91 74831 59735
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li>
                                <inertia-link href="/about" class="text-gray-300 hover:text-white text-sm">
                                    About Us
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link href="/ngos" class="text-gray-300 hover:text-white text-sm">
                                    NGOs
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link href="/campaigns" class="text-gray-300 hover:text-white text-sm">
                                    Campaigns
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link href="/events" class="text-gray-300 hover:text-white text-sm">
                                    Events
                                </inertia-link>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Focus Areas -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold mb-4">Focus Areas</h3>
                        <ul class="space-y-2">
                            <li>
                                <inertia-link href="/focus/children" class="text-gray-300 hover:text-white text-sm">
                                    Children's Rights
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link href="/focus/environment" class="text-gray-300 hover:text-white text-sm">
                                    Environment
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link href="/focus/community" class="text-gray-300 hover:text-white text-sm">
                                    Community Development
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link href="/focus/disability" class="text-gray-300 hover:text-white text-sm">
                                    Disability
                                </inertia-link>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Legal & Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold mb-4">Information</h3>
                        <ul class="space-y-2">
                            <li>
                                <inertia-link href="/terms" class="text-gray-300 hover:text-white text-sm">
                                    Terms & Conditions
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link href="/privacy" class="text-gray-300 hover:text-white text-sm">
                                    Privacy Policy
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link href="/accessibility" class="text-gray-300 hover:text-white text-sm">
                                    Accessibility
                                </inertia-link>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Bottom Bar -->
                <div class="border-t border-gray-800 mt-8 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-gray-400 text-sm">
                            2025 FEVOURD-K. All rights reserved.
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-400">
                            <span>Powered by</span>
                            <a href="https://www.virtualngoconnect.com/" target="_blank" class="hover:text-white">
                                VNC
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div
            v-if="showPwaShell"
            class="lg:hidden fixed bottom-0 left-0 right-0 z-[55] border-t border-blue-100 bg-white/95 backdrop-blur-xl shadow-[0_-8px_24px_rgba(15,23,42,0.12)]"
        >
            <div class="grid gap-1 px-2 py-2" :style="{ gridTemplateColumns: `repeat(${roleBasedMobileTabs.length}, minmax(0, 1fr))` }">
                <Link
                    v-for="tab in roleBasedMobileTabs"
                    :key="tab.key"
                    :href="tab.href"
                    class="rounded-xl px-2 py-1.5 text-center text-[10px] font-medium transition"
                    :class="isActiveLink(tab.href) ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-blue-50'"
                >
                    <div class="mx-auto mb-0.5 flex h-5 w-5 items-center justify-center">
                        <svg v-if="tab.key === 'dashboard'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7 7 7-7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <svg v-else-if="tab.key === 'feeds'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h8m-8 4h6M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                        </svg>
                        <svg v-else-if="tab.key === 'ngo_nearby'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0L6.343 16.657a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg v-else-if="tab.key === 'campaigns'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <svg v-else-if="tab.key === 'donate'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg v-else-if="tab.key === 'ngo'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <svg v-else-if="tab.key === 'csr'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                        </svg>
                        <svg v-else-if="tab.key === 'explore'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    {{ tab.label }}
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import { ref, onMounted, onUnmounted, computed, watch, nextTick } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import lottie from 'lottie-web'
/** Served from public/ — avoids Vite path issues for install / standalone */
const logoImage = '/assets/images/fevourd-k/logo.png'

const showExploreMenu = ref(false)
const showUserMenu = ref(false)
const showMobileMenu = ref(false)
const showAppSidebar = ref(false)
const showNotificationMenu = ref(false)
const isLoading = ref(true)
const isStandaloneMode = ref(false)
const showRewardBurst = ref(false)
const roadmapRewardCollected = ref(false)
const profileLottieRef = ref(null)
let profileLottieInstance = null
const page = usePage()

const currentUser = computed(() => page.props.auth?.user ?? null)
const currentRoles = computed(() => page.props.auth?.roles ?? [])
const profileMeta = computed(() => page.props.auth?.profile ?? null)
const notifications = computed(() => page.props.auth?.notifications ?? [])
const unreadNotificationsCount = computed(() => page.props.auth?.unread_notifications_count ?? 0)
const currentPath = computed(() => page.url || window.location.pathname)
const isMobileViewport = ref(false)
const showPwaShell = computed(() => !!currentUser.value && (isStandaloneMode.value || isMobileViewport.value))
const isInstalledPwa = computed(() => isStandaloneMode.value)
const profileGender = computed(() => String(currentUser.value?.gender ?? '').toLowerCase())
const profileAvatarLottiePath = computed(() => {
    if (profileGender.value.includes('female') || profileGender.value === 'f') {
        return 'https://assets2.lottiefiles.com/packages/lf20_jtbfg2nb.json'
    }
    if (profileGender.value.includes('male') || profileGender.value === 'm') {
        return 'https://assets7.lottiefiles.com/packages/lf20_touohxv0.json'
    }
    return 'https://assets10.lottiefiles.com/packages/lf20_jcikwtux.json'
})
const profileAvatarShellClass = computed(() => {
    if (profileGender.value.includes('female') || profileGender.value === 'f') {
        return 'bg-gradient-to-br from-pink-500 via-rose-500 to-fuchsia-500 border-rose-300'
    }
    if (profileGender.value.includes('male') || profileGender.value === 'm') {
        return 'bg-gradient-to-br from-blue-600 via-indigo-600 to-sky-600 border-blue-300'
    }
    return 'bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600 border-indigo-300'
})

const isRole = (roleNames = []) => roleNames.some((roleName) => currentRoles.value.includes(roleName))

const headerAvatarUrl = computed(() => {
    const a = currentUser.value?.avatar
    if (!a) {
        return null
    }
    const s = String(a)
    if (s.startsWith('http://') || s.startsWith('https://')) {
        return s
    }
    if (s.startsWith('/')) {
        return s
    }

    return `/storage/${s}`
})

const userInitial = computed(() => {
    const n = currentUser.value?.name?.trim() || '?'

    return n.charAt(0).toUpperCase()
})

const primaryRoleLabel = computed(() => {
    const roles = currentRoles.value
    const map = {
        super_admin: 'Admin',
        state_admin: 'State admin',
        ngo_admin: 'NGO admin',
        ngo_staff: 'NGO member',
        donor: 'Donor',
        corporate_csr_manager: 'CSR',
    }
    for (const r of roles) {
        if (map[r]) {
            return map[r]
        }
    }

    return roles[0] ? String(roles[0]).replace(/_/g, ' ') : 'Member'
})

const roleBasedMobileTabs = computed(() => {
    const tabs = [
        { label: 'Home', href: getDashboardUrl(), key: 'dashboard' },
        { label: 'Feeds', href: '/feeds', key: 'feeds' },
        { label: 'NGO', href: '/feeds?view=ngo', key: 'ngo_nearby' },
    ]

    if (isRole(['donor'])) {
        tabs.push({ label: 'Donate', href: '/donate', key: 'donate' })
    } else if (isRole(['ngo_admin', 'ngo_staff'])) {
        tabs.push({ label: 'NGO', href: '/ngo/dashboard', key: 'ngo' })
    } else if (isRole(['corporate_csr_manager'])) {
        tabs.push({ label: 'CSR', href: '/csr/dashboard', key: 'csr' })
    } else {
        tabs.push({ label: 'Explore', href: '/ngos', key: 'explore' })
    }

    tabs.push({ label: 'Profile', href: '/profile', key: 'profile' })
    return tabs
})

const appSidebarLinks = computed(() => {
    const links = [
        { section: 'Discover', items: [
            { label: 'Live Feeds', href: '/feeds', icon: 'LF' },
            { label: 'Nearby NGOs', href: '/feeds?view=ngo', icon: 'NG' },
            { label: 'Campaigns', href: '/campaigns', icon: 'CP' },
            { label: 'NGOs', href: '/ngos', icon: 'NO' },
            { label: 'Events', href: '/events', icon: 'EV' },
        ] },
    ]

    if (isRole(['donor'])) {
        links.push({ section: 'Donor Actions', items: [
            { label: 'Donor Dashboard', href: '/donor/dashboard', icon: 'DB' },
            { label: 'Donate Now', href: '/donate', icon: 'DN' },
            { label: 'Saved Campaigns', href: '/campaigns', icon: 'SV' },
        ] })
    }

    if (isRole(['ngo_admin', 'ngo_staff'])) {
        links.push({ section: 'NGO Workspace', items: [
            { label: 'NGO Dashboard', href: '/ngo/dashboard', icon: 'ND' },
            { label: 'Campaign Manager', href: '/ngo/campaigns', icon: 'CM' },
            { label: 'Digitalization', href: '/ngo/digitalization', icon: 'DG' },
            { label: 'Ledger', href: '/ngo/ledger', icon: 'LG' },
            { label: 'Documents', href: '/ngo/documents', icon: 'DC' },
        ] })
    }

    if (isRole(['corporate_csr_manager'])) {
        links.push({ section: 'CSR Workspace', items: [
            { label: 'CSR Dashboard', href: '/csr/dashboard', icon: 'CD' },
            { label: 'Create Initiative', href: '/csr/register', icon: 'CI' },
        ] })
    }

    links.push({ section: 'Account', items: [
        { label: 'Profile', href: '/profile', icon: 'PR' },
        { label: 'Settings', href: '/settings', icon: 'ST' },
    ] })

    return links
})

const roadmapSteps = computed(() => {
    const reactions = Number(profileMeta.value?.reactions_count ?? 0)
    const comments = Number(profileMeta.value?.comments_count ?? 0)
    const shares = Number(profileMeta.value?.shares_count ?? 0)
    const donations = Number(profileMeta.value?.donations_count ?? 0)
    const completion = Number(profileMeta.value?.completion_percent ?? 0)

    return [
        { id: 1, label: 'Complete profile to 80%+', completed: completion >= 80 },
        { id: 2, label: 'React or comment on live NGO feeds', completed: reactions + comments >= 1 },
        { id: 3, label: 'Share an impact update', completed: shares >= 1 },
        { id: 4, label: 'Donate to any active campaign', completed: donations >= 1 },
    ]
})

const roadmapCompletedCount = computed(() => roadmapSteps.value.filter((step) => step.completed).length)
const roadmapProgressPercent = computed(() => Math.round((roadmapCompletedCount.value / Math.max(roadmapSteps.value.length, 1)) * 100))
const canCollectRoadmapReward = computed(() => roadmapCompletedCount.value === roadmapSteps.value.length)

const collectRoadmapReward = () => {
    if (!canCollectRoadmapReward.value || roadmapRewardCollected.value) {
        return
    }
    roadmapRewardCollected.value = true
    showRewardBurst.value = true
    localStorage.setItem(`fevourdk_roadmap_reward_collected_${currentUser.value?.id ?? 'guest'}`, '1')
    window.setTimeout(() => {
        showRewardBurst.value = false
    }, 1400)
}

const initProfileLottie = async () => {
    await nextTick()
    if (!profileLottieRef.value || profileLottieInstance) {
        return
    }
    profileLottieInstance = lottie.loadAnimation({
        container: profileLottieRef.value,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: profileAvatarLottiePath.value,
        rendererSettings: {
            preserveAspectRatio: 'xMidYMid slice',
        },
    })
    profileLottieInstance.addEventListener('data_failed', () => {
        destroyProfileLottie()
        profileLottieInstance = lottie.loadAnimation({
            container: profileLottieRef.value,
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets10.lottiefiles.com/packages/lf20_jcikwtux.json',
            rendererSettings: {
                preserveAspectRatio: 'xMidYMid slice',
            },
        })
    })
}

const destroyProfileLottie = () => {
    if (profileLottieInstance) {
        profileLottieInstance.destroy()
        profileLottieInstance = null
    }
}

// Thoughts of the day
const thoughts = [
    { text: "The best way to find yourself is to lose yourself in the service of others.", author: "Mahatma Gandhi" },
    { text: "No one has ever become poor by giving.", author: "Anne Frank" },
    { text: "ಸೇವೆಯೇ ಸತ್ಯವಾದ ನಾಯಕತ್ವ – True leadership begins with service.", author: "Unknown" },
{ text: "ನಮ್ಮ ನಾಡು, ನಮ್ಮ ಜವಾಬ್ದಾರಿ – Our nation is our responsibility.", author: "Unknown" },
{ text: "ಒಗ್ಗಟ್ಟಿನಲ್ಲಿ ಶಕ್ತಿ ಇದೆ – Unity is our greatest strength.", author: "Unknown" },
{ text: "ಸಮಾಜಕ್ಕಾಗಿ ಮಾಡಿದ ಸೇವೆ ಎಂದಿಗೂ ವ್ಯರ್ಥವಾಗುವುದಿಲ್ಲ – Service to society never goes in vain.", author: "Unknown" },
{ text: "ಬದಲಾವಣೆ ನಮ್ಮಿಂದಲೇ ಪ್ರಾರಂಭವಾಗುತ್ತದೆ – Change begins with us.", author: "Unknown" },
{ text: "ಕೈಜೋಡಿಸಿದಾಗ ಕನಸುಗಳು ನಿಜವಾಗುತ್ತವೆ – Together, dreams become reality.", author: "Unknown" },
{ text: "ಸಣ್ಣ ಸಹಾಯವೂ ದೊಡ್ಡ ಬದಲಾವಣೆಗೆ ಕಾರಣವಾಗಬಹುದು – Even a small help can create big change.", author: "Unknown" },
{ text: "ನಮ್ಮ ಭಾಷೆ ನಮ್ಮ ಆತ್ಮ – Our language is our identity.", author: "Unknown" },
{ text: "ಸೇವೆಯ ಮನಸ್ಸು ಸಮಾಜವನ್ನು ಬೆಳಗಿಸುತ್ತದೆ – A heart for service lights up society.", author: "Unknown" },
{ text: "ಯುವಶಕ್ತಿ ದೇಶದ ಭವಿಷ್ಯ – Youth power shapes the nation.", author: "Unknown" },
{ text: "ಇತರರಿಗೆ ನೆರವಾಗುವುದು ಮಾನವತ್ವದ ಗುರುತು – Helping others is the mark of humanity.", author: "Unknown" },
{ text: "ಒಬ್ಬರ ಬೆಳವಣಿಗೆ ಎಲ್ಲರ ಬೆಳವಣಿಗೆ – Growth of one is growth of all.", author: "Unknown" },
{ text: "ನಾಡು ಬೆಳೆವುದೇ ಜನರ ಸೇವೆಯಿಂದ – A nation grows through public service.", author: "Unknown" },
{ text: "ನಮ್ಮ ಸಂಸ್ಕೃತಿ ನಮ್ಮ ಶಕ್ತಿ – Our culture is our strength.", author: "Unknown" },
{ text: "ಒಗ್ಗಟ್ಟಿನ ಹೆಜ್ಜೆ ಸಮಾಜದ ಜಯ – United steps lead to social victory.", author: "Unknown" },
{ text: "ಸೇವೆ ಮಾಡುವ ಕೈಗಳು ಪ್ರಾರ್ಥಿಸುವ ತುಟಿಗಳಿಗಿಂತ ಶ್ರೇಷ್ಠ – Serving hands are holier than praying lips.", author: "Mother Teresa" },
{ text: "ನಿಜವಾದ ಸಂಪತ್ತು ಜನರ ನಂಬಿಕೆ – True wealth is the trust of people.", author: "Unknown" },
{ text: "ಬೇರೆಯವರ ಜೀವನದಲ್ಲಿ ಆಶೆಯಾಗಿ ಬೆಳಗೋಣ – Let us be hope in someone’s life.", author: "Unknown" },
{ text: "ಸಮಾಜದ ಅಭಿವೃದ್ಧಿಯೇ ನಮ್ಮ ಗುರಿ – Social development is our mission.", author: "Unknown" },
{ text: "ನಮ್ಮ ಕೆಲಸ ನಮ್ಮ ಗುರುತು – Our work defines us.", author: "Unknown" },
{ text: "ಒಟ್ಟಾಗಿ ನಡೆದು, ಒಟ್ಟಾಗಿ ಗೆಲ್ಲೋಣ – Walk together, win together.", author: "Unknown" },
{ text: "ಸೇವೆಯೇ ಸಮಾಜದ ಬಲ – Service is the strength of society.", author: "Unknown" },
{ text: "ನಮ್ಮ ನುಡಿ ನಮ್ಮ ಹೆಮ್ಮೆ – Our language is our pride.", author: "Unknown" },
{ text: "ಜನರಿಗಾಗಿ ಕೆಲಸ ಮಾಡುವುದು ದೇವರ ಸೇವೆಯಂತೆಯೇ – Serving people is serving God.", author: "Unknown" },
{ text: "ಒಗ್ಗಟ್ಟು, ಸೇವೆ, ಅಭಿವೃದ್ಧಿ – Unity, Service, Progress.", author: "Unknown" },
    { text: "The purpose of life is not to be happy. It is to be useful.", author: "Ralph Waldo Emerson" },
    { text: "We make a living by what we get, but we make a life by what we give.", author: "Winston Churchill" },
    { text: "Service to others is the rent you pay for your room here on earth.", author: "Muhammad Ali" },
    { text: "The meaning of life is to find your gift. The purpose of life is to give it away.", author: "Pablo Picasso" },
    { text: "Alone we can do so little; together we can do so much.", author: "Helen Keller" },
    { text: "The greatest good you can do for another is not just to share your riches, but to reveal to them their own.", author: "Benjamin Disraeli" }
]

const currentThought = ref(thoughts[Math.floor(Math.random() * thoughts.length)])

const toggleExplore = () => {
    showExploreMenu.value = !showExploreMenu.value
    showUserMenu.value = false
    showMobileMenu.value = false
    showNotificationMenu.value = false
}

const toggleUser = () => {
    showUserMenu.value = !showUserMenu.value
    showExploreMenu.value = false
    showMobileMenu.value = false
    showNotificationMenu.value = false
}

const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value
    showExploreMenu.value = false
    showUserMenu.value = false
    showAppSidebar.value = false
    showNotificationMenu.value = false
}

const toggleAppSidebar = () => {
    showAppSidebar.value = !showAppSidebar.value
    showMobileMenu.value = false
    showNotificationMenu.value = false
}

const logout = () => {
    router.post('/logout')
}

const logoutFromMenu = () => {
    showUserMenu.value = false
    logout()
}

const toggleNotifications = () => {
    showNotificationMenu.value = !showNotificationMenu.value
    showUserMenu.value = false
    showExploreMenu.value = false
}

const openNotification = (notification) => {
    router.post(`/notifications/${notification.id}/read`, {}, {
        preserveScroll: true,
        onFinish: () => {
            showNotificationMenu.value = false
            if (notification.target_url) {
                router.visit(notification.target_url)
            }
        },
    })
}

const markAllNotificationsRead = () => {
    router.post('/notifications/read-all', {}, { preserveScroll: true })
}

const formatNotificationTime = (timestamp) => {
    if (!timestamp) return ''
    return new Date(timestamp).toLocaleString()
}

const getDashboardUrl = () => {
    const user = currentUser.value
    if (!user) return '/dashboard'

    if (currentRoles.value.includes('corporate_csr_manager')) return '/csr/dashboard'
    if (currentRoles.value.includes('ngo_admin') || currentRoles.value.includes('ngo_staff')) return '/ngo/dashboard'
    if (currentRoles.value.includes('donor')) return '/donor/dashboard'

    return '/dashboard'
}

const handleClickOutside = (event) => {
    if (!event.target.closest('.mobile-menu-container') &&
        !event.target.closest('.profile-menu-root') &&
        !event.target.closest('a') &&
        !event.target.closest('button')) {
        showUserMenu.value = false
        showExploreMenu.value = false
        showMobileMenu.value = false
        showAppSidebar.value = false
        showNotificationMenu.value = false
    }
}

const isActiveLink = (href) => {
    if (!href) return false
    if (href === '/dashboard') {
        return currentPath.value === '/dashboard'
    }
    return currentPath.value === href || currentPath.value.startsWith(`${href}/`)
}

const accountMenuItemClass = (href) => {
    const base = 'account-menu-item'

    return isActiveLink(href) ? `${base} account-menu-item--active` : base
}

const updateViewportFlags = () => {
    isMobileViewport.value = window.matchMedia('(max-width: 1024px)').matches
    isStandaloneMode.value = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
    updateViewportFlags()
    window.addEventListener('resize', updateViewportFlags)
    window
        .matchMedia('(display-mode: standalone)')
        .addEventListener('change', updateViewportFlags)
    
    // Hide loader after content is ready
    setTimeout(() => {
        isLoading.value = false
    }, 2000)

    const rewardKey = `fevourdk_roadmap_reward_collected_${currentUser.value?.id ?? 'guest'}`
    roadmapRewardCollected.value = localStorage.getItem(rewardKey) === '1'
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
    window.removeEventListener('resize', updateViewportFlags)
    destroyProfileLottie()
})

watch(showAppSidebar, (isOpen) => {
    if (isOpen) {
        initProfileLottie()
        return
    }
    destroyProfileLottie()
})

watch(profileAvatarLottiePath, () => {
    if (!showAppSidebar.value) {
        return
    }
    destroyProfileLottie()
    initProfileLottie()
})
</script>


<style>
.nav-link {
    @apply text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors;
}

.nav-link-modern {
    @apply flex items-center px-5 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-xl text-sm font-medium transition-all duration-300 backdrop-blur-sm;
}

.dropdown-item {
    @apply block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors;
}

.dropdown-item-modern {
    @apply flex items-center p-4 hover:bg-gray-50 rounded-xl transition-all duration-200 cursor-pointer;
}

.account-menu-item {
    @apply flex items-start gap-3 rounded-xl px-3 py-2.5 text-left transition-all duration-200;
}

.account-menu-item > :first-child {
    @apply mt-0.5 shrink-0;
}

.account-menu-item:hover {
    @apply bg-slate-50;
}

.account-menu-item--active {
    @apply bg-blue-50/90 ring-1 ring-blue-100/90;
}

.account-dropdown-scroll {
    scrollbar-width: thin;
    scrollbar-color: rgb(203 213 225) transparent;
}

.account-dropdown-scroll::-webkit-scrollbar {
    width: 5px;
}

.account-dropdown-scroll::-webkit-scrollbar-thumb {
    border-radius: 9999px;
    background-color: rgb(203 213 225);
}

.btn-primary {
    @apply px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition;
}

.btn-modern-primary {
    @apply flex items-center px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl text-sm font-medium hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl;
}

.btn-green {
    @apply px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition;
}

.btn-modern-green {
    @apply flex items-center px-5 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl text-sm font-medium hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl;
}

.btn-modern-green-compact {
    @apply flex items-center px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg text-sm font-medium hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl;
}

.btn-accent {
    @apply px-4 py-2 bg-accent-600 text-white rounded-lg text-sm font-medium hover:bg-accent-700 transition;
}

.btn-modern-accent {
    @apply flex items-center px-5 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl text-sm font-medium hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl;
}

.user-button-modern {
    @apply p-1 rounded-full hover:ring-4 hover:ring-white/20 transition-all duration-300;
}

.roadmap-progress-stripes {
    background-size: 24px 24px;
    background-image:
        linear-gradient(
            -45deg,
            rgba(255, 255, 255, 0.2) 25%,
            transparent 25%,
            transparent 50%,
            rgba(255, 255, 255, 0.2) 50%,
            rgba(255, 255, 255, 0.2) 75%,
            transparent 75%,
            transparent
        );
    animation: roadmap-stripes 1.4s linear infinite;
}

@keyframes roadmap-stripes {
    0% { background-position: 0 0; }
    100% { background-position: 24px 0; }
}

.reward-chip {
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 700;
    color: #ffffff;
    background: linear-gradient(135deg, #22c55e, #14b8a6);
    box-shadow: 0 12px 30px rgba(20, 184, 166, 0.4);
}

.reward-pop-enter-active {
    animation: reward-pop-in 0.3s ease-out;
}

.reward-pop-leave-active {
    animation: reward-pop-out 0.35s ease-in forwards;
}

@keyframes reward-pop-in {
    from {
        opacity: 0;
        transform: scale(0.5) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes reward-pop-out {
    from {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
    to {
        opacity: 0;
        transform: scale(1.2) translateY(-16px);
    }
}
</style>
