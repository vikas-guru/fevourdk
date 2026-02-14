<template>
    <AppLayout title="NGO Registration - FEVOURD-K">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Registration Type Selection -->
                <div class="mb-8">
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">NGO Registration</h1>
                        <p class="text-gray-600">Choose your preferred registration method</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                        <!-- AI Registration Option -->
                        <Link href="/register/ngo-chat" class="group relative bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl p-6 text-white hover:shadow-2xl transition-all duration-300 border-2 border-transparent hover:border-blue-300">
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                                        </svg>
                                    </div>
                                    <span class="bg-yellow-400 text-blue-900 text-xs font-bold px-2 py-1 rounded-full">RECOMMENDED</span>
                                </div>
                                <h3 class="text-xl font-bold mb-2">AI-Powered Registration</h3>
                                <p class="text-blue-100 text-sm mb-4">Complete registration in 5 minutes using natural language and AI assistance</p>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-white/20 px-2 py-1 rounded text-xs">⚡ Fast</span>
                                    <span class="bg-white/20 px-2 py-1 rounded text-xs">🤖 Smart</span>
                                    <span class="bg-white/20 px-2 py-1 rounded text-xs">💬 Voice</span>
                                </div>
                            </div>
                            <div class="absolute top-2 right-2 w-20 h-20 bg-white/5 rounded-full animate-pulse"></div>
                            <div class="absolute bottom-2 right-2 w-16 h-16 bg-white/5 rounded-full animate-bounce"></div>
                        </Link>
                        
                        <!-- Traditional Form Option -->
                        <div class="bg-white rounded-2xl p-6 border-2 border-gray-200 hover:border-blue-300 transition-all duration-300 shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3h4v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"/>
                                    </svg>
                                </div>
                                <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2 py-1 rounded-full">TRADITIONAL</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Manual Form</h3>
                            <p class="text-gray-600 text-sm mb-4">Fill out the registration form step by step with detailed fields</p>
                            <div class="flex items-center space-x-2">
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs">📝 Detailed</span>
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs">✏️ Editable</span>
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs">📋 Structured</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CSR Registration Option -->
                    <!-- <div class="bg-gradient-to-br from-green-600 to-emerald-600 rounded-2xl p-6 text-white hover:shadow-2xl transition-all duration-300 border-2 border-transparent hover:border-green-300">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M12 2C6.48 2 2 6.48 2s4 0 8 0 8 4 0 8 4 0 0 1 4 0 0 1-4 0-4-4-4-4-4-4-4z"/>
                                        <path d="M12 2v6l3 3 3-3 3 0 0 1 4 0 0 1-3 3-3 3 0 0 1-3-3-3-3z"/>
                                    </svg>
                                </div>
                                <span class="bg-yellow-400 text-green-900 text-xs font-bold px-2 py-1 rounded-full">NEW</span>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Corporate CSR Registration</h3>
                            <p class="text-green-100 text-sm mb-4">Register your company for CSR partnerships and social impact initiatives</p>
                            <div class="flex items-center space-x-2">
                                <span class="bg-white/20 px-2 py-1 rounded text-xs">🏢 Business</span>
                                <span class="bg-white/20 px-2 py-1 rounded text-xs">🤝 Impact</span>
                                <span class="bg-white/20 px-2 py-1 rounded text-xs">📊 Analytics</span>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 w-20 h-20 bg-white/5 rounded-full animate-pulse"></div>
                        <div class="absolute bottom-2 right-2 w-16 h-16 bg-white/5 rounded-full animate-bounce"></div>
                    </div>
                    <Link href="/csr/register" class="block w-full text-center text-green-900 hover:text-green-800 font-medium transition-colors">
                        Register as Corporate Partner
                    </Link> -->
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex items-center space-x-2">
                            <!-- Step 1 -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                                     :class="currentStep >= 1 ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-300 text-gray-600'">
                                    1
                                </div>
                                <div class="w-16 h-2 transition-all duration-300"
                                     :class="currentStep >= 2 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                                     :class="currentStep >= 2 ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-300 text-gray-600'">
                                    2
                                </div>
                                <div class="w-16 h-2 transition-all duration-300"
                                     :class="currentStep >= 3 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                            </div>
                            
                            <!-- Step 3 -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                                     :class="currentStep >= 3 ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-300 text-gray-600'">
                                    3
                                </div>
                                <div class="w-16 h-2 transition-all duration-300"
                                     :class="currentStep >= 4 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                            </div>
                            
                            <!-- Step 4 -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                                     :class="currentStep >= 4 ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-300 text-gray-600'">
                                    4
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-900">{{ stepTitles[currentStep - 1] }}</h2>
                        <p class="text-gray-600 mt-2">{{ stepDescriptions[currentStep - 1] }}</p>
                    </div>
                </div>

                <!-- Registration Form -->
                <form @submit.prevent="submitForm" class="bg-white rounded-2xl shadow-xl p-8">
                    <!-- Step 1: Basic Information -->
                    <div v-if="currentStep === 1" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Organization Details</h3>
                                
                                <!-- NGO Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NGO Name *</label>
                                    <input 
                                        v-model="form.name"
                                        type="text" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.name }"
                                        placeholder="Enter your NGO name"
                                    >
                                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                                </div>

                                <!-- Registration Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number *</label>
                                    <input 
                                        v-model="form.registration_number"
                                        type="text" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.registration_number }"
                                        placeholder="NGO Registration Number"
                                    >
                                    <div v-if="form.errors.registration_number" class="text-red-500 text-sm mt-1">{{ form.errors.registration_number }}</div>
                                </div>

                                <!-- PAN Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">PAN Number *</label>
                                    <input 
                                        v-model="form.pan"
                                        type="text" 
                                        maxlength="10"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                                        :class="{ 'border-red-500': form.errors.pan }"
                                        placeholder="ABCDE1234F"
                                    >
                                    <div v-if="form.errors.pan" class="text-red-500 text-sm mt-1">{{ form.errors.pan }}</div>
                                </div>

                                <!-- Website -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Website (Optional)</label>
                                    <input 
                                        v-model="form.website"
                                        type="url" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="https://www.yourngo.org"
                                    >
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Contact Information</h3>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input 
                                        v-model="form.email"
                                        type="email" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.email }"
                                        placeholder="ngo@example.org"
                                    >
                                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                    <input 
                                        v-model="form.phone"
                                        type="tel" 
                                        maxlength="10"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.phone }"
                                        placeholder="9876543210"
                                    >
                                    <div v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</div>
                                </div>

                                <!-- Address -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                                    <textarea 
                                        v-model="form.address"
                                        rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.address }"
                                        placeholder="Enter complete address"
                                    ></textarea>
                                    <div v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</div>
                                </div>

                                <!-- Location -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                                    <select 
                                        v-model="form.city_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.city_id }"
                                    >
                                        <option value="">Select City</option>
                                        <option v-for="city in allCities" :key="city.id" :value="city.id">
                                            {{ city.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.city_id" class="text-red-500 text-sm mt-1">{{ form.errors.city_id }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Organization Details -->
                    <div v-if="currentStep === 2" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">About Your NGO</h3>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                                    <textarea 
                                        v-model="form.description"
                                        rows="6"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.description }"
                                        placeholder="Tell us about your NGO's mission, vision, and activities..."
                                    ></textarea>
                                    <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                                </div>

                                <!-- Focus Areas -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Focus Areas *</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label v-for="area in focusAreas" :key="area" class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                :value="area"
                                                v-model="form.focus_areas"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">{{ area }}</span>
                                        </label>
                                    </div>
                                    <div v-if="form.errors.focus_areas" class="text-red-500 text-sm mt-1">{{ form.errors.focus_areas }}</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Logo & Branding</h3>

                                <!-- Logo Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NGO Logo</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                        <div v-if="!logoPreview" class="space-y-2">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="logo-upload" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                    <span>Upload a file</span>
                                                    <input id="logo-upload" name="logo" type="file" class="sr-only" @change="handleLogoUpload" accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                        </div>
                                        <div v-else class="space-y-2">
                                            <img :src="logoPreview" alt="Logo preview" class="mx-auto h-24 w-24 object-cover rounded-lg">
                                            <button type="button" @click="removeLogo" class="text-sm text-red-600 hover:text-red-500">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Year Established -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Year Established</label>
                                    <input 
                                        v-model="form.year_established"
                                        type="number" 
                                        min="1900"
                                        :max="new Date().getFullYear()"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="2010"
                                    >
                                </div>

                                <!-- Team Size -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Team Size</label>
                                    <select 
                                        v-model="form.team_size"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                        <option value="">Select team size</option>
                                        <option value="1-10">1-10 members</option>
                                        <option value="11-50">11-50 members</option>
                                        <option value="51-100">51-100 members</option>
                                        <option value="101-500">101-500 members</option>
                                        <option value="500+">500+ members</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Banking & Payment -->
                    <div v-if="currentStep === 3" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Bank Account Details</h3>

                                <!-- Account Holder Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name *</label>
                                    <input 
                                        v-model="form.bank_account.account_holder_name"
                                        type="text" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Name as per bank records"
                                    >
                                </div>

                                <!-- Bank Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name *</label>
                                    <input 
                                        v-model="form.bank_account.bank_name"
                                        type="text" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Bank Name"
                                    >
                                </div>

                                <!-- Account Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Number *</label>
                                    <input 
                                        v-model="form.bank_account.account_number"
                                        type="text" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Bank Account Number"
                                    >
                                </div>

                                <!-- IFSC Code -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">IFSC Code *</label>
                                    <input 
                                        v-model="form.bank_account.ifsc_code"
                                        type="text" 
                                        maxlength="11"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                                        placeholder="BANK0000000"
                                    >
                                </div>

                                <!-- Branch Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Branch Name *</label>
                                    <input 
                                        v-model="form.bank_account.branch_name"
                                        type="text" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Branch Name"
                                    >
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Payment Gateway Setup</h3>

                                <!-- Payment Gateways -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Gateways</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                value="razorpay"
                                                v-model="form.payment_gateways"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">Razorpay</div>
                                                <div class="text-sm text-gray-500">Popular Indian payment gateway</div>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                value="phonepe"
                                                v-model="form.payment_gateways"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">PhonePe</div>
                                                <div class="text-sm text-gray-500">UPI and wallet payments</div>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                value="stripe"
                                                v-model="form.payment_gateways"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">Stripe</div>
                                                <div class="text-sm text-gray-500">International payments</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Admin Contact -->
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Admin Contact Details</h4>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Name *</label>
                                            <input 
                                                v-model="form.admin_contact.name"
                                                type="text" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Admin Full Name"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email *</label>
                                            <input 
                                                v-model="form.admin_contact.email"
                                                type="email" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="admin@ngo.org"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Phone *</label>
                                            <input 
                                                v-model="form.admin_contact.phone"
                                                type="tel" 
                                                maxlength="10"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="9876543210"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Document Upload & Review -->
                    <div v-if="currentStep === 4" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column - Document Upload -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Required Documents</h3>

                                <!-- Registration Certificate -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Certificate *</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors">
                                        <div v-if="!form.documents.registration_certificate" class="space-y-2">
                                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="reg-cert-upload" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                    <span>Upload certificate</span>
                                                    <input id="reg-cert-upload" name="registration_certificate" type="file" class="sr-only" @change="handleDocumentUpload('registration_certificate', $event)" accept="application/pdf,image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PDF or Image up to 10MB</p>
                                        </div>
                                        <div v-else class="flex items-center justify-between">
                                            <span class="text-sm text-gray-700">{{ form.documents.registration_certificate.name }}</span>
                                            <button type="button" @click="removeDocument('registration_certificate')" class="text-red-600 hover:text-red-500">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- PAN Card -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">PAN Card *</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors">
                                        <div v-if="!form.documents.pan_card" class="space-y-2">
                                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="pan-upload" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                    <span>Upload PAN card</span>
                                                    <input id="pan-upload" name="pan_card" type="file" class="sr-only" @change="handleDocumentUpload('pan_card', $event)" accept="application/pdf,image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PDF or Image up to 10MB</p>
                                        </div>
                                        <div v-else class="flex items-center justify-between">
                                            <span class="text-sm text-gray-700">{{ form.documents.pan_card.name }}</span>
                                            <button type="button" @click="removeDocument('pan_card')" class="text-red-600 hover:text-red-500">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- 80G Certificate -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">80G Certificate (Optional)</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors">
                                        <div v-if="!form.documents.eightyg_certificate" class="space-y-2">
                                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="80g-upload" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                    <span>Upload 80G certificate</span>
                                                    <input id="80g-upload" name="eightyg_certificate" type="file" class="sr-only" @change="handleDocumentUpload('eightyg_certificate', $event)" accept="application/pdf,image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PDF or Image up to 10MB</p>
                                        </div>
                                        <div v-else class="flex items-center justify-between">
                                            <span class="text-sm text-gray-700">{{ form.documents.eightyg_certificate.name }}</span>
                                            <button type="button" @click="removeDocument('eightyg_certificate')" class="text-red-600 hover:text-red-500">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Review -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Review Your Information</h3>
                                
                                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                    <div class="border-b pb-3">
                                        <h4 class="font-semibold text-gray-900">Organization Details</h4>
                                        <p class="text-sm text-gray-600 mt-1"><strong>Name:</strong> {{ form.name }}</p>
                                        <p class="text-sm text-gray-600"><strong>Registration:</strong> {{ form.registration_number }}</p>
                                        <p class="text-sm text-gray-600"><strong>PAN:</strong> {{ form.pan }}</p>
                                        <p class="text-sm text-gray-600"><strong>Email:</strong> {{ form.email }}</p>
                                        <p class="text-sm text-gray-600"><strong>Phone:</strong> {{ form.phone }}</p>
                                    </div>

                                    <div class="border-b pb-3">
                                        <h4 class="font-semibold text-gray-900">Focus Areas</h4>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span v-for="area in form.focus_areas" :key="area" class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                {{ area }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="border-b pb-3">
                                        <h4 class="font-semibold text-gray-900">Payment Gateways</h4>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span v-for="gateway in form.payment_gateways" :key="gateway" class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                {{ gateway }}
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-900">Documents</h4>
                                        <ul class="text-sm text-gray-600 mt-1 space-y-1">
                                            <li :class="{ 'text-green-600': form.documents.registration_certificate }">
                                                ✓ Registration Certificate: {{ form.documents.registration_certificate ? 'Uploaded' : 'Not uploaded' }}
                                            </li>
                                            <li :class="{ 'text-green-600': form.documents.pan_card }">
                                                ✓ PAN Card: {{ form.documents.pan_card ? 'Uploaded' : 'Not uploaded' }}
                                            </li>
                                            <li :class="{ 'text-green-600': form.documents.eightyg_certificate }">
                                                80G Certificate: {{ form.documents.eightyg_certificate ? 'Uploaded' : 'Optional' }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-yellow-800 mb-2">Important Notice</h4>
                                    <p class="text-sm text-yellow-700 mb-3">
                                        By submitting this registration, you confirm that all information provided is accurate and authentic. 
                                        Your NGO will undergo a verification process before being approved to join the FEVOURD-K platform.
                                    </p>
                                    <label class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            v-model="form.accept_terms"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        >
                                        <span class="ml-2 text-sm text-gray-700">I agree to the terms and conditions</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8">
                        <button 
                            type="button"
                            @click="previousStep"
                            v-if="currentStep > 1"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Previous
                        </button>
                        <div v-else></div>

                        <button 
                            type="button"
                            @click="nextStep"
                            v-if="currentStep < 4"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Next
                        </button>

                        <button 
                            type="submit"
                            v-if="currentStep === 4"
                            :disabled="form.processing || !form.accept_terms"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="form.processing">Submitting...</span>
                            <span v-else>Complete Registration</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
    states: Array,
    cities: Array,
    focusAreas: Array
})

const currentStep = ref(1)
const logoPreview = ref(null)

// Computed property for all cities
const allCities = computed(() => {
    return props.cities || []
})

const stepTitles = [
    'Basic Information',
    'Organization Details',
    'Banking & Payment Setup',
    'Documents & Review'
]

const stepDescriptions = [
    'Tell us about your NGO\'s basic information and contact details',
    'Share your mission, focus areas, and upload your logo',
    'Set up payment gateways and banking information',
    'Upload required documents and review your application'
]

const form = useForm({
    // Step 1: Basic Information
    name: '',
    registration_number: '',
    pan: '',
    email: '',
    phone: '',
    website: '',
    address: '',
    city_id: '',
    
    // Step 2: Organization Details
    description: '',
    focus_areas: [],
    logo: null,
    year_established: '',
    team_size: '',
    
    // Step 3: Banking & Payment
    bank_account: {
        account_holder_name: '',
        bank_name: '',
        account_number: '',
        ifsc_code: '',
        branch_name: ''
    },
    payment_gateways: [],
    admin_contact: {
        name: '',
        email: '',
        phone: ''
    },
    
    // Step 4: Documents
    documents: {
        registration_certificate: null,
        pan_card: null,
        eightyg_certificate: null
    },
    accept_terms: false
})

const nextStep = () => {
    if (validateCurrentStep()) {
        currentStep.value++
    }
}

const previousStep = () => {
    currentStep.value--
}

const validateCurrentStep = () => {
    if (currentStep.value === 1) {
        if (!form.name || !form.registration_number || !form.pan || !form.email || !form.phone || !form.address || !form.city_id) {
            return false
        }
    } else if (currentStep.value === 2) {
        if (!form.description || form.focus_areas.length === 0) {
            return false
        }
    } else if (currentStep.value === 3) {
        if (!form.bank_account.account_holder_name || !form.bank_account.bank_name || 
            !form.bank_account.account_number || !form.bank_account.ifsc_code || 
            !form.bank_account.branch_name || !form.admin_contact.name || 
            !form.admin_contact.email || !form.admin_contact.phone) {
            return false
        }
    }
    return true
}

const handleLogoUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.logo = file
        const reader = new FileReader()
        reader.onload = (e) => {
            logoPreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    }
}

const removeLogo = () => {
    form.logo = null
    logoPreview.value = null
}

const handleDocumentUpload = (documentType, event) => {
    const file = event.target.files[0]
    if (file) {
        form.documents[documentType] = file
    }
}

const removeDocument = (documentType) => {
    form.documents[documentType] = null
}

const submitForm = () => {
    if (!validateCurrentStep()) {
        return
    }

    form.post('/register/ngo', {
        onSuccess: () => {
            // Redirect to success page or dashboard
        },
        onError: () => {
            // Handle validation errors
        }
    })
}
</script>
