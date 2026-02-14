<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 py-8 sm:py-12 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-pattern"></div>
        </div>
        
        <div class="max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/10 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[600px] lg:min-h-[700px]">
                    <!-- Left Side - Branding & Info -->
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-8 lg:p-16 flex flex-col justify-center order-2 lg:order-1 relative">
                        <!-- Animated Background Elements -->
                        <div class="absolute inset-0 overflow-hidden">
                            <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
                            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
                        </div>
                        
                        <div class="text-center relative z-10">
                            <!-- Logo -->
                            <div class="mb-8">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/10 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 mb-6 group-hover:scale-110 transition-transform duration-500">
                                   <img :src="logoImage" alt="FEVOURD-K" class="w-16 h-12 object-contain">
                                </div>
                            </div>
                            
                            <!-- Welcome Text -->
                            <div class="space-y-6">
                                <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4">Welcome Back</h1>
                                <p class="text-xl lg:text-2xl text-blue-100 mb-8">Sign in to your FEVOURD-K account</p>
                                
                                <!-- Stats -->
                                <div class="flex justify-center gap-8 mb-8">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-white">800+</div>
                                        <div class="text-sm text-blue-300">NGOs</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-white">50K+</div>
                                        <div class="text-sm text-blue-300">Users</div>
                                    </div>
                                </div>
                                <!-- Features -->
                                <div class="space-y-6">
                                    <div class="flex items-center space-x-4 group">
                                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7 7 7-7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <h3 class="text-xl font-semibold text-white group-hover:text-blue-300 transition-colors">Secure Login</h3>
                                            <p class="text-sm text-blue-200">Protected authentication system</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4 group">
                                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <h3 class="text-xl font-semibold text-white group-hover:text-blue-300 transition-colors">Quick Access</h3>
                                            <p class="text-sm text-blue-200">Save time with smart login</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4 group">
                                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <h3 class="text-xl font-semibold text-white group-hover:text-blue-300 transition-colors">800+ NGOs</h3>
                                            <p class="text-sm text-blue-200">Verified organizations</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Side - Login Form -->
                    <div class="p-8 lg:p-16 order-1 lg:order-2 relative bg-white/5 backdrop-blur-sm">
                        <!-- Mobile Menu Button -->
                        <button 
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="lg:hidden fixed top-4 right-4 p-3 rounded-xl bg-white/90 backdrop-blur-md border border-gray-200 shadow-lg hover:bg-white hover:shadow-xl transition-all duration-200 z-50"
                        >
                            <svg v-if="!mobileMenuOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg v-else class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <!-- Mobile Menu Popup -->
                        <div v-if="mobileMenuOpen" class="lg:hidden fixed inset-0 z-40 flex items-center justify-center bg-black/50 backdrop-blur-sm" @click="mobileMenuOpen = false">
                            <div class="bg-white rounded-2xl shadow-2xl p-6 m-4 max-w-sm w-full animate-scale-in" @click.stop>
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg mb-4">
                                        <div class="text-2xl font-bold text-white">F-K</div>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Fevourd-K</h3>
                                    <p class="text-gray-600">Empowering Karnataka's NGOs</p>
                                </div>
                                
                                <div class="space-y-3">
                                    <inertia-link 
                                        href="/register" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200"
                                        @click="mobileMenuOpen = false"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        </svg>
                                        Create New Account
                                    </inertia-link>
                                    
                                    <inertia-link 
                                        href="/forgot-password" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors"
                                        @click="mobileMenuOpen = false"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                        Forgot Password?
                                    </inertia-link>
                                    
                                    <button 
                                        @click="mobileMenuOpen = false"
                                        class="w-full px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors"
                                    >
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="max-w-md w-full mx-auto">
                            <!-- Header -->
                            <div class="text-center mb-8">
                                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-3">Sign In</h2>
                                <p class="text-blue-200 text-lg">Access your social impact dashboard</p>
                            </div>
                            
                            <!-- Social Login Options -->
                            <div class="mb-8">
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-white/20"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-4 bg-transparent text-blue-200">Or continue with</span>
                                    </div>
                                </div>
                                
                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <button class="flex items-center justify-center px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-white/20 transition-all duration-200 group">
                                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                        </svg>
                                        <span class="text-white text-sm font-medium group-hover:text-blue-300">Google</span>
                                    </button>
                                    
                                    <button class="flex items-center justify-center px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-white/20 transition-all duration-200 group">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                        </svg>
                                        <span class="text-white text-sm font-medium group-hover:text-blue-300">GitHub</span>
                                    </button>
                                </div>
                            </div>
                            
                            <form class="space-y-6" @submit.prevent="submit">
                                <!-- Error Messages -->
                                <div v-if="$page.props.errors.email || $page.props.errors.password" class="p-4 bg-red-500/20 border border-red-400/30 rounded-xl animate-scale-in">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-red-300 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0c-1.657 0-3 .895-3 2s1.343 2 3 2 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-red-200 mb-1">
                                                {{ $page.props.errors.email ? 'Email Error' : 'Password Error' }}
                                            </h4>
                                            <p class="text-sm text-red-300">
                                                {{ $page.props.errors.email || $page.props.errors.password }}
                                            </p>
                                            <!-- Additional help for password errors -->
                                            <div v-if="$page.props.errors.password" class="mt-2 text-xs text-red-200">
                                                <p>💡 Tip: Make sure Caps Lock is off and check your spelling</p>
                                            </div>
                                            <!-- Additional help for email errors -->
                                            <div v-if="$page.props.errors.email && $page.props.errors.email.includes('No account found')" class="mt-2 text-xs text-red-200">
                                                <p>💡 Don't have an account? 
                                                    <Link href="/register" class="text-red-100 underline hover:text-white transition-colors">
                                                        Register here
                                                    </Link>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Email Field -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-blue-200 mb-2">
                                        Email address
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                            </svg>
                                        </div>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            autocomplete="email"
                                            required
                                            v-model="form.email"
                                            :class="{
                                                'border-red-500 focus:ring-red-500 focus:border-red-500': $page.props.errors.email,
                                                'border-white/20 focus:ring-blue-500 focus:border-blue-500': !$page.props.errors.email
                                            }"
                                            class="appearance-none relative block w-full pl-10 pr-3 py-3 bg-white/10 backdrop-blur-sm placeholder-blue-300 text-white rounded-xl focus:outline-none sm:text-sm transition-all duration-200"
                                            placeholder="Enter your email"
                                        >
                                        <div v-if="$page.props.errors.email" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0c-1.657 0-3 .895-3 2s1.343 2 3 2 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Password Field -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-blue-200 mb-2">
                                        Password
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            autocomplete="current-password"
                                            required
                                            v-model="form.password"
                                            :class="{
                                                'border-red-500 focus:ring-red-500 focus:border-red-500': $page.props.errors.password,
                                                'border-white/20 focus:ring-blue-500 focus:border-blue-500': !$page.props.errors.password
                                            }"
                                            class="appearance-none relative block w-full pl-10 pr-3 py-3 bg-white/10 backdrop-blur-sm placeholder-blue-300 text-white rounded-xl focus:outline-none sm:text-sm transition-all duration-200"
                                            placeholder="Enter your password"
                                        >
                                        <div v-if="$page.props.errors.password" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0c-1.657 0-3 .895-3 2s1.343 2 3 2 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Remember Me & Forgot Password -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input
                                            id="remember-me"
                                            name="remember-me"
                                            type="checkbox"
                                            v-model="form.remember"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-white/30 rounded bg-white/10"
                                        >
                                        <label for="remember-me" class="ml-2 block text-sm text-blue-200">
                                            Remember me for 30 days
                                        </label>
                                    </div>
                                    <Link href="/forgot-password" class="text-sm text-blue-300 hover:text-blue-200 transition-colors">
                                        Forgot password?
                                    </Link>
                                </div>
                                
                                <!-- Submit Button -->
                                <div>
                                    <button
                                        type="submit"
                                        :disabled="processing"
                                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105"
                                    >
                                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                            <svg v-if="!processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                            <svg v-else class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                        {{ processing ? 'Signing in...' : 'Sign in to your account' }}
                                    </button>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div v-if="processing" class="mt-4">
                                    <div class="w-full bg-white/20 rounded-full h-2 overflow-hidden">
                                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-2 rounded-full animate-pulse" style="width: 60%; animation: progress 2s ease-in-out infinite;"></div>
                                    </div>
                                    <p class="text-center text-sm text-blue-200 mt-2">Authenticating your credentials...</p>
                                </div>
                            </form>
                            
                            <!-- Sign Up Link -->
                            <div class="mt-8 text-center">
                                <p class="text-blue-200">
                                    Don't have an account? 
                                    <Link href="/register" class="text-white font-bold hover:text-blue-300 transition-colors underline decoration-2 underline-offset-4">
                                        Join FEVOURD-K
                                    </Link>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/vue3'
import logoImage from '/public/assets/images/fevourd-k/logo.png'

const form = reactive({
    email: '',
    password: '',
    remember: false,
})

const processing = ref(false)
const mobileMenuOpen = ref(false)

const submit = () => {
    processing.value = true
    Inertia.post('/login', form, {
        onFinish: () => {
            processing.value = false
        },
        onError: () => {
            processing.value = false
        }
    })
}
</script>

<style scoped>
/* Background pattern */
.bg-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Custom animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes progress {
    0% { width: 0%; }
    50% { width: 80%; }
    100% { width: 100%; }
}

@keyframes scale-in {
    0% { 
        opacity: 0;
        transform: scale(0.9);
    }
    100% { 
        opacity: 1;
        transform: scale(1);
    }
}

.animate-scale-in {
    animation: scale-in 0.3s ease-out;
}

/* Glassmorphism effect */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

/* Smooth transitions */
* {
    transition: all 0.3s ease;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>
