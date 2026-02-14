<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Loader with Thought of the Day -->
        <div v-if="isLoading" class="fixed inset-0 bg-gradient-to-br from-primary-600 to-primary-800 z-50 flex items-center justify-center">
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
            <div class="hidden lg:flex items-center space-x-3 flex-1 justify-center">

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

                <!-- Mobile Menu Button -->
                <button 
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
                <div class="hidden lg:block">
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
                <div v-if="!$page.props.auth.user" class="hidden lg:flex items-center space-x-4">
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

                <!-- Logged User (Desktop Only) -->
                <div v-else class="hidden lg:relative">
                    <button
                        @click="toggleUser"
                        class="user-button-modern"
                    >
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg">{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
                        </div>
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
                            class="absolute right-0 mt-3 w-80 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl ring-2 ring-blue-500/20 z-[999] border border-white/50"
                        >
                            <div class="p-6 border-b border-gray-100">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-xl">{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-lg">{{ $page.props.auth.user.name }}</p>
                                        <p class="text-sm text-gray-500">{{ $page.props.auth.user.email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-2">
                                <Link :href="getDashboardUrl()" class="dropdown-item-modern">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7 7 7-7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Dashboard</div>
                                        <div class="text-xs text-gray-500">Your control panel</div>
                                    </div>
                                </Link>

                                <Link href="/profile" class="dropdown-item-modern">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 8 0 4 4 0 00-8 0zM12 14a7 7 0 00-7 7h14a7 7 0 007-7H12z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Profile</div>
                                        <div class="text-xs text-gray-500">Manage your info</div>
                                    </div>
                                </Link>

                                <button
                                    @click="logout"
                                    class="dropdown-item-modern w-full text-left"
                                >
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4 4m4-4H3m6 4h18a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-red-600">Logout</div>
                                        <div class="text-xs text-gray-500">Sign out of account</div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </transition>
                </div>

            </div>

        </div>
    </div>
</nav>

<!-- Mobile Menu Popup -->
<div v-if="showMobileMenu" class="lg:hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
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
        <main>
            <slot />
        </main>
        
        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
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
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import logoImage from '/public/assets/images/fevourd-k/logo.png'

const showExploreMenu = ref(false)
const showUserMenu = ref(false)
const showMobileMenu = ref(false)
const isLoading = ref(true)

// Thoughts of the day
const thoughts = [
    { text: "The best way to find yourself is to lose yourself in the service of others.", author: "Mahatma Gandhi" },
    { text: "No one has ever become poor by giving.", author: "Anne Frank" },
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
}

const toggleUser = () => {
    showUserMenu.value = !showUserMenu.value
    showExploreMenu.value = false
    showMobileMenu.value = false
}

const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value
    showExploreMenu.value = false
    showUserMenu.value = false
}

const logout = () => {
    router.post('/logout')
}

const getDashboardUrl = () => {
    const user = window.page?.props?.auth?.user
    if (!user) return '/dashboard'

    if (user.roles?.includes('corporate_csr_manager')) return '/csr/dashboard'
    if (user.roles?.includes('ngo_admin') || user.roles?.includes('ngo_staff')) return '/ngo/dashboard'
    if (user.roles?.includes('donor')) return '/donor/dashboard'

    return '/dashboard'
}

const handleClickOutside = (event) => {
    if (!event.target.closest('.mobile-menu-container') &&
        !event.target.closest('a') &&
        !event.target.closest('button')) {
        showUserMenu.value = false
        showExploreMenu.value = false
        showMobileMenu.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
    
    // Hide loader after content is ready
    setTimeout(() => {
        isLoading.value = false
    }, 2000)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
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
</style>
