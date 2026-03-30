<template>
    <AppLayout>
        <Head title="FEVOURD-K — Karnataka voluntary organisations hub">
            <meta
                head-key="description"
                name="description"
                content="FEVOURD-K connects 800+ voluntary organisations across Karnataka with donors, verified campaigns, NGO digital tools, and transparent giving."
            />
            <meta property="og:title" content="FEVOURD-K — Karnataka voluntary organisations" />
            <meta
                property="og:description"
                content="Donate, register your NGO, and follow impact across Karnataka — mobile-friendly and installable as an app."
            />
            <meta property="og:type" content="website" />
            <meta v-if="siteUrl" property="og:image" :content="`${siteUrl}/assets/images/fevourd-k/logo.png`" />
            <meta name="twitter:card" content="summary" />
        </Head>
        <div class="min-h-screen">
            <!-- Hero Section -->
            <section id="hero" class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white overflow-hidden transition-all duration-1000" :class="{ 'translate-y-0 opacity-100': visibleSections.hero, 'translate-y-10 opacity-0': !visibleSections.hero }">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10 bg-pattern-dots"></div>
                
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
                    <div class="text-center">
                        <!-- Logo (real img for LCP, sharing, and install previews) -->
                        <div class="flex justify-center mb-8">
                            <img
                                :src="brandLogoSrc"
                                alt="FEVOURD-K — Federation of Voluntary Organisations Karnataka"
                                width="120"
                                height="123"
                                class="h-24 w-auto max-w-[min(280px,85vw)] object-contain drop-shadow-lg"
                                fetchpriority="high"
                                decoding="async"
                            >
                        </div>
                        
                        <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight">
                            AN APEX BODY OF<br>
                            <span class="text-yellow-400">VOLUNTARY ORGANISATIONS</span><br>
                            IN KARNATAKA
                        </h1>
                        
                        <p class="text-xl md:text-2xl mb-6 max-w-4xl mx-auto font-medium text-blue-100">
                            FEVOURD-K – Voluntary Action | Karnataka NGO Hub
                        </p>
                        
                        <p class="text-lg md:text-xl mb-8 max-w-5xl mx-auto text-blue-50 leading-relaxed">
                            A federation of over <span class="font-bold text-yellow-400">800 voluntary organisations</span> committed to strengthening the developmental indices in Karnataka through social, economic and cultural empowerment of deprived communities, environmental conservation and the promotion of a vibrant democracy in the state.
                        </p>

                        <!-- Quick access (mobile home-screen friendly) -->
                        <nav class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10" aria-label="Quick access">
                            <Link
                                href="/login"
                                class="inline-flex items-center rounded-full border border-white/40 bg-white/10 px-4 py-2 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20"
                            >
                                Sign in
                            </Link>
                            <Link
                                href="/register"
                                class="inline-flex items-center rounded-full border border-white/40 bg-white/10 px-4 py-2 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20"
                            >
                                Register
                            </Link>
                            <Link
                                href="/donate"
                                class="inline-flex items-center rounded-full border border-emerald-400/50 bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-50 backdrop-blur-sm transition hover:bg-emerald-500/30"
                            >
                                Donate
                            </Link>
                            <Link
                                href="/feeds"
                                class="inline-flex items-center rounded-full border border-white/40 bg-white/10 px-4 py-2 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20"
                            >
                                Community feed
                            </Link>
                        </nav>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 mb-10">
                            <Link href="/ngos" class="bg-yellow-400 text-blue-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-yellow-300 transform hover:scale-105 transition-all duration-200 shadow-lg cursor-pointer">
                                Learn More About Us
                            </Link>
                            <Link href="/register" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-blue-900 transform hover:scale-105 transition-all duration-200 cursor-pointer">
                                Join Our Network
                            </Link>
                            <Link href="/campaigns" class="bg-green-500 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-green-600 transform hover:scale-105 transition-all duration-200 shadow-lg cursor-pointer">
                                View Campaigns
                            </Link>
                        </div>

                        <p class="mb-10 max-w-lg mx-auto text-sm leading-relaxed text-blue-100/90">
                            <span class="font-semibold text-white">Install like an app:</span>
                            on Chrome or Edge, use the menu → <strong>Install FEVOURD-K</strong> (or the install icon). On iPhone/iPad: Share → <strong>Add to Home Screen</strong>.
                        </p>
                        
                        <!-- Contact Info -->
                        <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-8 text-blue-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span>fevourd.karnataka@gmail.com</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                <span>9448464171</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Wave Bottom -->
                <div class="absolute bottom-0 left-0 right-0">
                    <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="#F9FAFB"/>
                    </svg>
                </div>
            </section>
            
            <!-- Impact Statistics -->
            <section id="impact" class="py-20 bg-gray-50 transition-all duration-1000" :class="{ 'translate-y-0 opacity-100': visibleSections.impact, 'translate-y-10 opacity-0': !visibleSections.impact }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Impact Across Karnataka</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                            Together, we're creating lasting change across Karnataka through collective action and community empowerment. 
                            Founded in 1982, we now have a membership base in all <span class="font-bold text-blue-600">31 districts of Karnataka</span>.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 p-8 text-center border border-gray-100">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <div class="w-20 h-20 bg-blue-100 group-hover:bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-blue-600 group-hover:text-blue-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="text-4xl font-bold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">{{ stats.ngos.toLocaleString() }}+</div>
                                <div class="text-gray-600 group-hover:text-blue-100 font-medium transition-colors duration-300">Voluntary Organizations</div>
                            </div>
                        </div>
                        
                        <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 p-8 text-center border border-gray-100">
                            <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <div class="w-20 h-20 bg-green-100 group-hover:bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-green-600 group-hover:text-green-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3H5a3 3 0 00-3 3v14a3 3 0 003 3h14a3 3 0 003-3v-14a3 3 0 00-3-3H5z" />
                                    </svg>
                                </div>
                                <div class="text-4xl font-bold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">{{ stats.families.toLocaleString() }}+</div>
                                <div class="text-gray-600 group-hover:text-green-100 font-medium transition-colors duration-300">Families Impacted</div>
                            </div>
                        </div>
                        
                        <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 p-8 text-center border border-gray-100">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <div class="w-20 h-20 bg-purple-100 group-hover:bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-purple-600 group-hover:text-purple-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="text-4xl font-bold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">{{ stats.shgs.toLocaleString() }}+</div>
                                <div class="text-gray-600 group-hover:text-purple-100 font-medium transition-colors duration-300">Self-Help Groups</div>
                            </div>
                        </div>
                        
                        <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 p-8 text-center border border-gray-100">
                            <div class="absolute inset-0 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <div class="w-20 h-20 bg-yellow-100 group-hover:bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-yellow-600 group-hover:text-yellow-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                    </svg>
                                </div>
                                <div class="text-4xl font-bold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">{{ stats.fpos.toLocaleString() }}+</div>
                                <div class="text-gray-600 group-hover:text-yellow-100 font-medium transition-colors duration-300">Farmer Producers</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                        <div class="text-center p-6 bg-white rounded-xl shadow-md border border-gray-100">
                            <div class="text-3xl font-bold text-blue-600 mb-2">{{ stats.donors.toLocaleString() }}+</div>
                            <div class="text-gray-600 font-medium">Active Donors</div>
                        </div>
                        <div class="text-center p-6 bg-white rounded-xl shadow-md border border-gray-100">
                            <div class="text-3xl font-bold text-green-600 mb-2">₹{{ stats.fundsRaised.toLocaleString() }}+</div>
                            <div class="text-gray-600 font-medium">Funds Raised</div>
                        </div>
                        <div class="text-center p-6 bg-white rounded-xl shadow-md border border-gray-100">
                            <div class="text-3xl font-bold text-purple-600 mb-2">{{ stats.campaigns.toLocaleString() }}+</div>
                            <div class="text-gray-600 font-medium">Active Campaigns</div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Featured Campaigns Enhanced -->
            <section id="campaigns" class="py-24 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 transition-all duration-1000 relative overflow-hidden" :class="{ 'translate-y-0 opacity-100': visibleSections.campaigns, 'translate-y-10 opacity-0': !visibleSections.campaigns }">
                <!-- Enhanced Background -->
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 via-purple-600/10 to-indigo-600/10"></div>
                    <div class="absolute inset-0 bg-pattern opacity-5"></div>
                    <!-- Floating Elements -->
                    <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
                </div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <!-- Enhanced Header -->
                    <div class="text-center mb-20">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-3xl shadow-2xl mb-8 backdrop-blur-sm border border-white/20 transform hover:scale-110 transition-all duration-300">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-5xl lg:text-6xl font-bold text-white mb-6 bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                            Featured Campaigns
                        </h2>
                        <p class="text-xl lg:text-2xl text-blue-200 max-w-4xl mx-auto leading-relaxed">
                            Support impactful campaigns that are transforming communities across Karnataka. 
                            Together, we can create lasting change and empower those who need it most.
                        </p>
                        <div class="mt-8 flex justify-center space-x-8">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-400">{{ featuredCampaigns.length }}+</div>
                                <div class="text-blue-200 text-sm">Active Campaigns</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-400">₹{{ formatCurrency(featuredCampaigns.reduce((sum, c) => sum + c.raised_amount, 0)) }}</div>
                                <div class="text-blue-200 text-sm">Total Raised</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-400">{{ featuredCampaigns.reduce((sum, c) => sum + c.donors_count, 0) }}+</div>
                                <div class="text-blue-200 text-sm">Donors</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- WOW Carousel Container -->
                    <div class="relative max-w-6xl mx-auto wow-carousel animate-pulse-glow">
                        <!-- Main Carousel Display -->
                        <div class="relative overflow-hidden rounded-3xl shadow-2xl animate-shimmer">
                            <div class="flex transition-transform duration-1000 ease-in-out h-full" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                                <div v-for="(campaign, index) in featuredCampaigns" :key="campaign.id" class="w-full flex-shrink-0">
                                    <!-- Hero Campaign Card -->
                                    <div class="bg-gradient-to-br from-slate-900/90 to-blue-900/90 backdrop-blur-2xl rounded-3xl overflow-hidden border border-white/20 relative h-[600px]">
                                        <!-- Background Image with Overlay -->
                                        <div class="absolute inset-0">
                                            <img :src="getCampaignImage(index)" 
                                                 :alt="campaign.title" 
                                                 class="w-full h-full object-cover opacity-60">
                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/30 via-purple-600/20 to-indigo-600/30"></div>
                                        </div>
                                        
                                        <!-- Floating Particles Effect -->
                                        <div class="absolute inset-0 overflow-hidden">
                                            <div class="absolute top-10 left-10 w-32 h-32 bg-yellow-400/20 rounded-full blur-2xl animate-pulse"></div>
                                            <div class="absolute bottom-20 right-20 w-48 h-48 bg-blue-400/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
                                            <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-purple-400/20 rounded-full blur-2xl animate-pulse delay-500"></div>
                                        </div>
                                        
                                        <!-- Campaign Content -->
                                        <div class="relative z-10 h-full flex items-center">
                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 p-12 w-full">
                                                <!-- Left Content -->
                                                <div class="space-y-6">
                                                    <!-- Category Badge -->
                                                    <div class="inline-flex items-center space-x-2">
                                                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                                        <span class="text-green-400 font-semibold text-sm uppercase tracking-wider">Featured Campaign</span>
                                                    </div>
                                                    
                                                    <!-- Title -->
                                                    <h3 class="text-4xl lg:text-5xl font-bold text-white leading-tight">
                                                        {{ campaign.title }}
                                                    </h3>
                                                    
                                                    <!-- Description -->
                                                    <p class="text-xl text-blue-200 leading-relaxed">
                                                        {{ campaign.description }}
                                                    </p>
                                                    
                                                    <!-- Quick Stats -->
                                                    <div class="flex space-x-8">
                                                        <div>
                                                            <div class="text-3xl font-bold text-yellow-400">{{ campaign.progress_percentage }}%</div>
                                                            <div class="text-sm text-blue-200">Funded</div>
                                                        </div>
                                                        <div>
                                                            <div class="text-3xl font-bold text-green-400">{{ campaign.donors_count }}</div>
                                                            <div class="text-sm text-blue-200">Donors</div>
                                                        </div>
                                                        <div>
                                                            <div class="text-3xl font-bold text-purple-400">{{ campaign.days_left }}</div>
                                                            <div class="text-sm text-blue-200">Days Left</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Action Buttons -->
                                                    <div class="flex space-x-4">
                                                        <button class="group relative bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl font-bold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg overflow-hidden">
                                                            <span class="relative z-10 flex items-center">
                                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Donate Now
                                                            </span>
                                                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                        </button>
                                                        <Link :href="`/campaigns/${campaign.slug}`" class="group bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-bold hover:bg-white/20 transition-all duration-300 border border-white/20">
                                                            <span class="flex items-center">
                                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Learn More
                                                            </span>
                                                        </Link>
                                                    </div>
                                                </div>
                                                
                                                <!-- Right Content - Progress & Details -->
                                                <div class="space-y-8">
                                                    <!-- Progress Card -->
                                                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 border border-white/20">
                                                        <h4 class="text-2xl font-bold text-white mb-6">Campaign Progress</h4>
                                                        
                                                        <!-- Goal Amount -->
                                                        <div class="mb-6">
                                                            <div class="flex justify-between items-center mb-2">
                                                                <span class="text-blue-200 font-medium">Goal Amount</span>
                                                                <span class="text-white font-bold text-xl">₹{{ formatCurrency(campaign.goal_amount) }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Raised Amount -->
                                                        <div class="mb-6">
                                                            <div class="flex justify-between items-center mb-2">
                                                                <span class="text-blue-200 font-medium">Raised Amount</span>
                                                                <span class="text-green-400 font-bold text-xl">₹{{ formatCurrency(campaign.raised_amount) }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- WOW Progress Bar -->
                                                        <div class="mb-6">
                                                            <div class="relative h-6 bg-white/20 rounded-full overflow-hidden">
                                                                <div class="absolute inset-0 bg-gradient-to-r from-green-400 via-blue-500 to-purple-600 rounded-full transition-all duration-1000 ease-out" 
                                                                     :style="{ width: `${campaign.progress_percentage}%` }">
                                                                    <div class="absolute right-2 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg animate-pulse"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-3 text-center">
                                                                <span class="text-lg font-bold text-yellow-400">{{ campaign.progress_percentage }}% Complete</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Duration -->
                                                        <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl">
                                                            <div class="flex items-center space-x-3">
                                                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                <span class="text-white font-medium">Campaign Duration</span>
                                                            </div>
                                                            <span class="text-purple-400 font-bold">{{ campaign.duration }} days</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Urgency Alert -->
                                                    <div v-if="campaign.days_left <= 10" class="bg-red-500/20 backdrop-blur-xl rounded-2xl p-6 border border-red-500/30">
                                                        <div class="flex items-center space-x-4">
                                                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <div class="text-red-400 font-bold text-lg">Urgent!</div>
                                                                <div class="text-white">Only {{ campaign.days_left }} days left to support this campaign</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced Carousel Controls -->
                        <button @click="prevSlide" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-xl text-white p-4 rounded-full hover:bg-white/30 transition-all duration-300 group hover:scale-110 shadow-xl">
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button @click="nextSlide" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-xl text-white p-4 rounded-full hover:bg-white/30 transition-all duration-300 group hover:scale-110 shadow-xl">
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Enhanced Carousel Indicators -->
                    <div class="flex justify-center mt-8 space-x-3">
                        <button v-for="(campaign, index) in featuredCampaigns" :key="index" 
                                @click="goToSlide(index)"
                                :class="currentSlide === index ? 'w-12 bg-gradient-to-r from-yellow-400 to-orange-500 shadow-lg shadow-yellow-400/50' : 'w-3 bg-white/40 hover:bg-white/60'"
                                class="h-3 rounded-full transition-all duration-500 transform hover:scale-110">
                        </button>
                    </div>
                    
                    <!-- Enhanced View All Campaigns -->
                    <div class="text-center mt-12">
                        <div class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 backdrop-blur-xl rounded-2xl p-8 border border-white/20">
                            <h3 class="text-2xl font-bold text-white mb-4">Want to see more impactful campaigns?</h3>
                            <p class="text-blue-200 mb-6 max-w-2xl mx-auto">
                                Discover hundreds of campaigns making a real difference across Karnataka. 
                                From education to healthcare, environmental conservation to women's empowerment.
                            </p>
                            <div class="flex justify-center">
                                <Link href="/campaigns" class="inline-flex items-center bg-gradient-to-r from-yellow-400 to-orange-500 text-blue-900 px-8 py-4 rounded-xl font-bold hover:from-yellow-500 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    Explore All Campaigns
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Focus Areas -->
            <section id="focus" class="py-20 bg-white transition-all duration-1000" :class="{ 'translate-y-0 opacity-100': visibleSections.focus, 'translate-y-10 opacity-0': !visibleSections.focus }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Focus Areas</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                            FEVOURD-K is committed to empowering marginalized communities and strengthening NGOs through impactful programs and activities. Our initiatives focus on sustainable development, capacity building, and social transformation across Karnataka.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="group relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 text-center hover:shadow-2xl transition-all duration-300 border border-blue-200 hover:border-blue-300 transform hover:-translate-y-2" :class="{ 'translate-y-0 opacity-100': visibleSections.focus, 'translate-y-4 opacity-0': !visibleSections.focus }">
                            <div class="w-20 h-20 bg-blue-500 group-hover:bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300 shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">Children's Rights</h3>
                            <p class="text-gray-600 leading-relaxed">Advocating for protection and development of children.</p>
                        </div>
                        
                        <div class="group relative bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-8 text-center hover:shadow-2xl transition-all duration-300 border border-red-200 hover:border-red-300">
                            <div class="w-20 h-20 bg-red-500 group-hover:bg-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300 shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition-colors">Disaster Management</h3>
                            <p class="text-gray-600 leading-relaxed">Preparing for and responding to natural and man-made disasters.</p>
                        </div>
                        
                        <div class="group relative bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 text-center hover:shadow-2xl transition-all duration-300 border border-green-200 hover:border-green-300">
                            <div class="w-20 h-20 bg-green-500 group-hover:bg-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300 shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7 7 7-7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors">Community Development</h3>
                            <p class="text-gray-600 leading-relaxed">Implementing initiatives to uplift and empower local communities.</p>
                        </div>
                        
                        <div class="group relative bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl p-8 text-center hover:shadow-2xl transition-all duration-300 border border-yellow-200 hover:border-yellow-300">
                            <div class="w-20 h-20 bg-yellow-500 group-hover:bg-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-6 transition-colors duration-300 shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2h12a2 2 0 002-2V8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-yellow-600 transition-colors">Environment</h3>
                            <p class="text-gray-600 leading-relaxed">Engaging in environmental conservation and sustainable practices.</p>
                        </div>
                    </div>
                    
                    <!-- Additional Focus Areas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-8">
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 text-center border border-purple-200">
                            <h3 class="text-lg font-bold text-purple-900 mb-2">Disability</h3>
                            <p class="text-purple-700">Promoting inclusivity and support for individuals with disabilities.</p>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-8 text-center border border-indigo-200">
                            <h3 class="text-lg font-bold text-indigo-900 mb-2">Research</h3>
                            <p class="text-indigo-700">Conducting studies to inform and improve development strategies.</p>
                        </div>
                        <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl p-8 text-center border border-pink-200">
                            <h3 class="text-lg font-bold text-pink-900 mb-2">Community-Based Organizations</h3>
                            <p class="text-pink-700">Strengthening grassroots organizations for effective local governance.</p>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-8 text-center border border-orange-200">
                            <h3 class="text-lg font-bold text-orange-900 mb-2">Youth</h3>
                            <p class="text-orange-700">Empowering young people to become active participants in societal development.</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Partners & Sponsors -->
            <section id="partners" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50 transition-all duration-1000" :class="{ 'translate-y-0 opacity-100': visibleSections.partners, 'translate-y-10 opacity-0': !visibleSections.partners }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Partners & Sponsors</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                            We collaborate with government agencies, corporate partners, and international organizations to amplify our impact.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 items-center">
                        <!-- Partner logos placeholder -->
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex items-center justify-center h-24">
                            <div class="text-gray-400 font-semibold text-sm">Government Partner</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex items-center justify-center h-24">
                            <div class="text-gray-400 font-semibold text-sm">Corporate CSR</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex items-center justify-center h-24">
                            <div class="text-gray-400 font-semibold text-sm">International NGO</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex items-center justify-center h-24">
                            <div class="text-gray-400 font-semibold text-sm">Foundation</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex items-center justify-center h-24">
                            <div class="text-gray-400 font-semibold text-sm">Development Agency</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex items-center justify-center h-24">
                            <div class="text-gray-400 font-semibold text-sm">Corporate Partner</div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-12">
                        <Link href="/partners" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Become a Partner
                        </Link>
                    </div>
                </div>
            </section>
            
            <!-- Call to Action -->
            <section id="cta" class="py-20 bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white relative overflow-hidden transition-all duration-1000" :class="{ 'translate-y-0 opacity-100': visibleSections.cta, 'translate-y-10 opacity-0': !visibleSections.cta }">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10 bg-pattern-dots"></div>
                
                <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-4xl font-bold mb-6">Join Us in Making a Difference</h2>
                    <p class="text-xl mb-12 text-blue-100 leading-relaxed">
                        Whether you're an NGO looking to join our network, a donor wanting to support impactful causes, 
                        or a volunteer ready to contribute your time and skills - there's a place for you in the FEVOURD-K community.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                            <div class="w-16 h-16 bg-yellow-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Register Your NGO</h3>
                            <p class="text-blue-100 mb-6">Join our network of 800+ voluntary organizations across Karnataka.</p>
                            <Link href="/register" class="bg-yellow-400 text-blue-900 px-6 py-3 rounded-lg font-bold hover:bg-yellow-300 transition-colors">
                                Register Now
                            </Link>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                            <div class="w-16 h-16 bg-green-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Make a Donation</h3>
                            <p class="text-blue-100 mb-6">Support verified campaigns and make a tangible impact in communities.</p>
                            <Link href="/campaigns" class="bg-green-400 text-blue-900 px-6 py-3 rounded-lg font-bold hover:bg-green-300 transition-colors">
                                View Campaigns
                            </Link>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                            <div class="w-16 h-16 bg-purple-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3H5a3 3 0 00-3 3v14a3 3 0 003 3h14a3 3 0 003-3v-14a3 3 0 00-3-3H5z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Volunteer With Us</h3>
                            <p class="text-blue-100 mb-6">Contribute your time and skills to causes you care about.</p>
                            <Link href="/register" class="bg-purple-400 text-blue-900 px-6 py-3 rounded-lg font-bold hover:bg-purple-300 transition-colors">
                                Get Started
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Contact Section -->
            <section id="contact" class="py-20 bg-gray-900 text-white transition-all duration-1000" :class="{ 'translate-y-0 opacity-100': visibleSections.contact, 'translate-y-10 opacity-0': !visibleSections.contact }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                        <div>
                            <h2 class="text-4xl font-bold mb-6">Get in Touch</h2>
                            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                                Have questions about FEVOURD-K? Want to partner with us? Need support with your NGO registration? 
                                Our team is here to help you.
                            </p>
                            
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <div class="bg-blue-600 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold">Email</div>
                                        <div class="text-gray-300">fevourd.karnataka@gmail.com</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="bg-blue-600 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold">Phone</div>
                                        <div class="text-gray-300">+91 9448464171</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="bg-blue-600 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold">Address</div>
                                        <div class="text-gray-300">Bengaluru, Karnataka, India</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-2xl p-8">
                            <h3 class="text-2xl font-bold mb-6">Send us a Message</h3>
                            <form class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Your Name</label>
                                    <input type="text" class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="John Doe">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Email Address</label>
                                    <input type="email" class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="john@example.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Message</label>
                                    <textarea rows="4" class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tell us how we can help..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                    Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Footer -->
            <footer class="bg-black text-gray-300 py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="bg-yellow-400 text-blue-900 px-4 py-2 rounded-lg font-bold text-lg mr-3">
                                    FEVOURD-K
                                </div>
                                <span class="text-white font-bold text-lg">FEVOURD-K</span>
                            </div>
                            <p class="text-sm">Federation of Voluntary Organisations for Rural Development - Karnataka</p>
                        </div>
                        
                        <div>
                            <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                            <ul class="space-y-2 text-sm">
                                <li><Link href="/ngos" class="hover:text-white transition-colors">About Us</Link></li>
                                <li><Link href="/campaigns" class="hover:text-white transition-colors">Campaigns</Link></li>
                                <li><Link href="/register" class="hover:text-white transition-colors">Register</Link></li>
                                <li><Link href="/login" class="hover:text-white transition-colors">Login</Link></li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-white font-semibold mb-4">Programs</h4>
                            <ul class="space-y-2 text-sm">
                                <li><Link href="/ngos" class="hover:text-white transition-colors">Children's Rights</Link></li>
                                <li><Link href="/campaigns" class="hover:text-white transition-colors">Environment</Link></li>
                                <li><Link href="/register" class="hover:text-white transition-colors">Education</Link></li>
                                <li><Link href="/login" class="hover:text-white transition-colors">Healthcare</Link></li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-white font-semibold mb-4">Connect</h4>
                            <div class="flex space-x-4 mb-4">
                                <a href="#" class="bg-gray-700 p-2 rounded-lg hover:bg-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="#" class="bg-gray-700 p-2 rounded-lg hover:bg-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-4.594 6.78 13.978 13.978 0 01-10.141 5.144 4.929 4.929 0 002.27-2.713 9.735 9.735 0 01-3.13 1.194 4.92 4.92 0 004.29 4.722 9.833 9.833 0 01-6.114 2.107c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="#" class="bg-gray-700 p-2 rounded-lg hover:bg-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                                    </svg>
                                </a>
                            </div>
                            <p class="text-sm">Follow us for updates and impact stories</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm">
                        <p>&copy; 2026 FEVOURD-K. All rights reserved. | 
                            <Link href="/ngos" class="hover:text-white transition-colors">About Us</Link> | 
                            <Link href="/register" class="hover:text-white transition-colors">Register</Link>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'

/** Public path — use binding so Vite does not treat `/assets/...` as a module import */
const brandLogoSrc = '/assets/images/fevourd-k/logo.png'

// Sample campaigns data for carousel
const featuredCampaigns = ref([
    {
        id: 1,
        title: 'Clean Water for Rural Schools',
        description: 'Providing clean drinking water and sanitation facilities to 50 rural schools across Karnataka, ensuring better health and education outcomes for children.',
        goal_amount: 500000,
        raised_amount: 325000,
        progress_percentage: 65,
        featured_image: null,
        donors_count: 127,
        days_left: 15,
        duration: 45,
        slug: 'clean-water-rural-schools'
    },
    {
        id: 2,
        title: 'Education for Every Child',
        description: 'Supporting education initiatives for underprivileged children in urban slums, providing books, uniforms, and digital learning resources.',
        goal_amount: 1000000,
        raised_amount: 750000,
        progress_percentage: 75,
        featured_image: null,
        donors_count: 245,
        days_left: 30,
        duration: 90,
        slug: 'education-every-child'
    },
    {
        id: 3,
        title: 'Healthcare Access Program',
        description: 'Mobile healthcare units and medical camps for remote villages, providing essential medical services and health awareness programs.',
        goal_amount: 750000,
        raised_amount: 600000,
        progress_percentage: 80,
        featured_image: null,
        donors_count: 189,
        days_left: 22,
        duration: 60,
        slug: 'healthcare-access-program'
    }
])

// Carousel functionality
const currentSlide = ref(0)
const autoplayInterval = ref(null)

const nextSlide = () => {
    if (featuredCampaigns.value.length > 0) {
        currentSlide.value = (currentSlide.value + 1) % featuredCampaigns.value.length
    }
}

const prevSlide = () => {
    if (featuredCampaigns.value.length > 0) {
        currentSlide.value = currentSlide.value === 0 ? featuredCampaigns.value.length - 1 : currentSlide.value - 1
    }
}

// Autoplay functionality
const startAutoplay = () => {
    if (featuredCampaigns.value.length > 1) {
        autoplayInterval.value = setInterval(() => {
            nextSlide()
        }, 5000) // Change slide every 5 seconds exactly
    }
}

const stopAutoplay = () => {
    if (autoplayInterval.value) {
        clearInterval(autoplayInterval.value)
        autoplayInterval.value = null
    }
}

// Go to specific slide
const goToSlide = (index) => {
    currentSlide.value = index
    // Reset autoplay when manually navigating
    stopAutoplay()
    startAutoplay()
}

// Intersection Observer for animations
const visibleSections = ref({
    hero: false,
    impact: false,
    campaigns: false,
    focus: false,
    partners: false,
    cta: false,
    contact: false
})

const observeSection = (sectionName) => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                visibleSections.value[sectionName] = true
                observer.unobserve(entry.target)
            }
        })
    }, { threshold: 0.1 })

    const element = document.getElementById(sectionName)
    if (element) {
        observer.observe(element)
    }
}

onMounted(() => {
    // Start carousel autoplay
    startAutoplay()
    
    // Observe all sections for animations
    Object.keys(visibleSections.value).forEach(section => {
        observeSection(section)
    })
})

onUnmounted(() => {
    stopAutoplay()
})

// Format currency helper
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 0,
    }).format(amount || 0)
}

// Get campaign image based on index
const getCampaignImage = (index) => {
    const images = [
        'https://images.unsplash.com/photo-1578632292335-df3abbb0d586?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1542831371-11b2e40b1b24?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1559028012-481c89fa3d2d?w=800&h=600&fit=crop'
    ]
    return images[index % images.length]
}

defineProps({
    stats: {
        type: Object,
        required: true
    },
    featuredCampaigns: {
        type: Array,
        default: () => []
    },
    upcomingEvents: {
        type: Array,
        default: () => []
    },
    siteUrl: {
        type: String,
        default: ''
    }
})
</script>

<style scoped>
.bg-pattern-dots {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.bg-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Glassmorphism effect */
.backdrop-blur-xl {
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
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

/* Enhanced animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes glow {
    0%, 100% { box-shadow: 0 0 20px rgba(255, 255, 255, 0.3); }
    50% { box-shadow: 0 0 40px rgba(255, 255, 255, 0.6), 0 0 60px rgba(59, 130, 246, 0.4); }
}

@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

@keyframes pulse-glow {
    0%, 100% { 
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2), 
                   0 0 40px rgba(59, 130, 246, 0.1);
    }
    50% { 
        box-shadow: 0 0 30px rgba(255, 255, 255, 0.4), 
                   0 0 60px rgba(59, 130, 246, 0.3);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-glow {
    animation: glow 2s ease-in-out infinite;
}

.animate-shimmer {
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    background-size: 1000px 100%;
    animation: shimmer 3s infinite;
}

.animate-pulse-glow {
    animation: pulse-glow 3s ease-in-out infinite;
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 2;
    -o-line-clamp: 2;
}

/* Enhanced hover effects */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\:translate-x-1 {
    transform: translateX(4px);
}

/* Progress bar animation */
@keyframes progress-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.animate-pulse-slow {
    animation: progress-pulse 2s ease-in-out infinite;
}

/* Enhanced button effects */
.btn-glow {
    position: relative;
    overflow: hidden;
}

.btn-glow::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-glow:hover::before {
    left: 100%;
}

/* Carousel slide animation */
.carousel-slide-enter-active,
.carousel-slide-leave-active {
    transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
}

.carousel-slide-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.carousel-slide-leave-to {
    opacity: 0;
    transform: translateX(-100%);
}

/* Card hover effects */
.card-hover {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-8px) scale(1.02);
}

/* Gradient text animation */
@keyframes gradient-shift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.gradient-animate {
    background-size: 200% 200%;
    animation: gradient-shift 3s ease infinite;
}

/* WOW Carousel specific styles */
.wow-carousel {
    position: relative;
    overflow: hidden;
}

.wow-carousel::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #3b82f6, #8b5cf6, #ec4899, #f59e0b, #3b82f6);
    border-radius: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s;
    z-index: -1;
    background-size: 400% 400%;
    animation: gradient-shift 4s ease infinite;
}

.wow-carousel:hover::before {
    opacity: 0.3;
}

/* Floating particles */
.particle {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    opacity: 0.6;
    animation: float 4s ease-in-out infinite;
}

.particle:nth-child(1) {
    animation-delay: 0s;
    animation-duration: 6s;
}

.particle:nth-child(2) {
    animation-delay: 1s;
    animation-duration: 8s;
}

.particle:nth-child(3) {
    animation-delay: 2s;
    animation-duration: 7s;
}
</style>
