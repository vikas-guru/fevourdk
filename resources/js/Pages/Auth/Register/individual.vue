<template>
    <AppLayout title="Individual Registration - FEVOURD-K">
        <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex items-center space-x-1">
                            <!-- Step 1 -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                                     :class="currentStep >= 1 ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-300 text-gray-600'">
                                    1
                                </div>
                                <div class="w-16 h-2 transition-all duration-300"
                                     :class="currentStep >= 2 ? 'bg-green-600' : 'bg-gray-300'"></div>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                                     :class="currentStep >= 2 ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-300 text-gray-600'">
                                    2
                                </div>
                                <div class="w-16 h-2 transition-all duration-300"
                                     :class="currentStep >= 3 ? 'bg-green-600' : 'bg-gray-300'"></div>
                            </div>
                            
                            <!-- Step 3 -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                                     :class="currentStep >= 3 ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-300 text-gray-600'">
                                    3
                                </div>
                                <div class="w-16 h-2 transition-all duration-300"
                                     :class="currentStep >= 4 ? 'bg-green-600' : 'bg-gray-300'"></div>
                            </div>
                            
                            <!-- Step 4 -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                                     :class="currentStep >= 4 ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-300 text-gray-600'">
                                    4
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 inline-flex rounded-lg border border-gray-300 overflow-hidden">
                            <button
                                type="button"
                                class="px-3 py-1.5 text-sm"
                                :class="language === 'en' ? 'bg-green-600 text-white' : 'bg-white text-gray-700'"
                                @click="language = 'en'"
                            >
                                English
                            </button>
                            <button
                                type="button"
                                class="px-3 py-1.5 text-sm"
                                :class="language === 'kn' ? 'bg-green-600 text-white' : 'bg-white text-gray-700'"
                                @click="language = 'kn'"
                            >
                                ಕನ್ನಡ
                            </button>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ stepTitles[currentStep - 1] }}</h2>
                        <p class="text-gray-600 mt-2">{{ stepDescriptions[currentStep - 1] }}</p>
                    </div>
                </div>

                <!-- Registration Form -->
                <form @submit.prevent="submitForm" class="bg-white rounded-2xl shadow-xl p-8">
                    <div
                        v-if="showDraftPrompt"
                        class="mb-4 rounded-xl border border-indigo-200 bg-indigo-50 p-4"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-indigo-900">Saved draft found</p>
                                <p class="text-xs text-indigo-800 mt-1">
                                    Registration ID: <span class="font-mono">{{ draftMeta.id }}</span>
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="restoreDraft" class="px-3 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                                    Continue draft
                                </button>
                                <button type="button" @click="discardDraft" class="px-3 py-2 text-sm rounded-lg border border-indigo-300 text-indigo-800 hover:bg-indigo-100">
                                    Start fresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mb-4">
                        <button
                            type="button"
                            @click="saveDraft(true)"
                            class="px-3 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Save Draft
                        </button>
                    </div>
                    <p v-if="draftStatusMessage" class="text-xs text-emerald-700 -mt-2 mb-3">
                        {{ draftStatusMessage }}
                    </p>
                    <!-- Step 1: Personal Information -->
                    <div v-if="currentStep === 1" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ t('personal_details') }}</h3>
                                
                                <!-- Name Fields -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('first_name') }} *</label>
                                        <input 
                                            v-model="form.first_name"
                                            type="text" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            :class="{ 'border-red-500': fieldHasError('first_name') }"
                                            @input="clearClientError('first_name')"
                                            placeholder="John"
                                        >
                                        <div v-if="fieldHasError('first_name')" class="text-red-500 text-sm mt-1">{{ fieldError('first_name') }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('last_name') }} *</label>
                                        <input 
                                            v-model="form.last_name"
                                            type="text" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            :class="{ 'border-red-500': fieldHasError('last_name') }"
                                            @input="clearClientError('last_name')"
                                            placeholder="Doe"
                                        >
                                        <div v-if="fieldHasError('last_name')" class="text-red-500 text-sm mt-1">{{ fieldError('last_name') }}</div>
                                    </div>
                                </div>

                                <div v-if="otpPilot" class="rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-amber-900 text-sm">
                                    <strong>Pilot mode:</strong> phone OTP is fixed for NGO pilots (no live SMS). After &quot;Send OTP&quot;, use the code shown — default is <code class="font-mono font-semibold">1234</code> unless your host changed <code>OTP_PILOT_CODE</code>.
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('phone_number') }} *</label>
                                    <div class="flex gap-2">
                                        <div class="flex items-center px-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 min-w-[112px] w-fit">
                                            <span class="text-lg mr-2" aria-hidden="true">🇮🇳</span>
                                            <span class="font-medium">+91</span>
                                        </div>
                                        <input 
                                            v-model="form.phone"
                                            type="tel" 
                                            inputmode="numeric"
                                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            :class="{ 'border-red-500': fieldHasError('phone') }"
                                            @input="onPhoneInput"
                                            placeholder="9876543210"
                                        >
                                    </div>
                                    <div v-if="fieldHasError('phone')" class="text-red-500 text-sm mt-1">{{ fieldError('phone') }}</div>
                                    <div v-if="!otpSent && fieldHasError('otp_code')" class="text-red-500 text-sm mt-1">{{ fieldError('otp_code') }}</div>
                                    <div v-if="otpStatusMessage" class="text-emerald-700 text-sm mt-2">
                                        {{ otpStatusMessage }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('email_address') }} *</label>
                                    <input 
                                        v-model="form.email"
                                        type="email" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': fieldHasError('email') }"
                                        @input="clearClientError('email')"
                                        placeholder="john.doe@example.com"
                                    >
                                    <div v-if="fieldHasError('email')" class="text-red-500 text-sm mt-1">{{ fieldError('email') }}</div>
                                </div>

                                <div>
                                    <button 
                                        type="button"
                                        @click="sendOTP"
                                        :disabled="otpSent || form.phone.length !== 10 || !form.email"
                                        class="px-4 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 disabled:bg-gray-400 transition-colors w-full sm:w-auto"
                                    >
                                        {{ otpSent ? t('otp_sent') : t('send_otp_email_phone') }}
                                    </button>
                                </div>

                                <div v-if="form.location_permission !== 'granted'" class="rounded-xl border border-blue-200 bg-blue-50/60 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h4 class="font-semibold text-blue-900">{{ t('location_card_title') }}</h4>
                                            <p class="text-sm text-blue-800 mt-1">{{ t('location_card_desc') }}</p>
                                            <p v-if="locationStatusMessage" class="text-xs text-blue-700 mt-2">{{ locationStatusMessage }}</p>
                                        </div>
                                        <button
                                            type="button"
                                            @click="requestLocationAccess"
                                            class="px-3 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors whitespace-nowrap"
                                        >
                                            {{ t('allow_location') }}
                                        </button>
                                    </div>
                                </div>

                                <div v-if="form.notification_permission !== 'granted'" class="rounded-xl border border-purple-200 bg-purple-50/60 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h4 class="font-semibold text-purple-900">{{ t('notification_card_title') }}</h4>
                                            <p class="text-sm text-purple-800 mt-1">{{ t('notification_card_desc') }}</p>
                                            <p v-if="notificationStatusMessage" class="text-xs text-purple-700 mt-2">{{ notificationStatusMessage }}</p>
                                        </div>
                                        <button
                                            type="button"
                                            @click="requestNotificationAccess"
                                            class="px-3 py-2 text-sm rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition-colors whitespace-nowrap"
                                        >
                                            {{ t('allow_notifications') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- OTP Verification -->
                                <div v-if="otpSent">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('otp_code') }} *</label>
                                    <input 
                                        v-model="form.otp_code"
                                        type="text" 
                                        inputmode="numeric"
                                        autocomplete="one-time-code"
                                        :maxlength="otpLength"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': fieldHasError('otp_code') }"
                                        :placeholder="t('otp_placeholder').replace('{len}', otpLength)"
                                        @input="clearClientError('otp_code')"
                                    >
                                    <p v-if="otpPilot && lastPilotOtp" class="mt-2 text-sm text-amber-900 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                                        <span class="font-medium">Use this OTP:</span>
                                        <code class="ml-2 font-mono font-bold tracking-widest">{{ lastPilotOtp }}</code>
                                    </p>
                                    <div v-if="fieldHasError('otp_code')" class="text-red-500 text-sm mt-1">{{ fieldError('otp_code') }}</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ t('additional_info') }}</h3>

                                <!-- Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('login_pin') }} *</label>
                                    <input 
                                        v-model="form.password"
                                        type="password"
                                        inputmode="numeric"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': fieldHasError('password') }"
                                        @input="clearClientError('password')"
                                        placeholder="Minimum 5 digits"
                                    >
                                    <div v-if="fieldHasError('password')" class="text-red-500 text-sm mt-1">{{ fieldError('password') }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('confirm_login_pin') }} *</label>
                                    <input 
                                        v-model="form.password_confirmation"
                                        type="password"
                                        inputmode="numeric"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': fieldHasError('password_confirmation') }"
                                        @input="clearClientError('password_confirmation')"
                                        placeholder="Re-enter login PIN"
                                    >
                                    <div v-if="fieldHasError('password_confirmation')" class="text-red-500 text-sm mt-1">{{ fieldError('password_confirmation') }}</div>
                                </div>

                                <!-- Additional Personal Info -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                        <input 
                                            v-model="form.date_of_birth"
                                            type="date" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                                        <select 
                                            v-model="form.gender"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        >
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Location Information -->
                    <div v-if="currentStep === 2" class="space-y-6">
                        <div class="rounded-xl border border-blue-200 bg-blue-50/60 p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-bold text-blue-900">{{ t('location_details') }}</h3>
                                    <p class="text-sm text-blue-800 mt-1">
                                        Live location based details are auto-filled. You can override manually if needed.
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" @click="requestLocationAccess" class="px-3 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                        Refresh Location
                                    </button>
                                    <button type="button" @click="manualLocationOverride = !manualLocationOverride" class="px-3 py-2 text-sm rounded-lg border border-blue-300 text-blue-900 hover:bg-blue-100">
                                        {{ manualLocationOverride ? 'Lock Auto Mode' : 'Override Manually' }}
                                    </button>
                                </div>
                            </div>
                            <p v-if="locationStatusMessage" class="text-xs text-blue-700 mt-2">{{ locationStatusMessage }}</p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('state') }} *</label>
                                    <input v-model="form.state_name" type="text" :readonly="!manualLocationOverride" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" :class="{ 'border-red-500': fieldHasError('state_name') }" @input="clearClientError('state_name')" />
                                    <div v-if="fieldHasError('state_name')" class="text-red-500 text-sm mt-1">{{ fieldError('state_name') }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('district') }} *</label>
                                    <input v-model="form.district_name" type="text" :readonly="!manualLocationOverride" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" :class="{ 'border-red-500': fieldHasError('district_name') }" @input="clearClientError('district_name')" />
                                    <div v-if="fieldHasError('district_name')" class="text-red-500 text-sm mt-1">{{ fieldError('district_name') }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Town / Village / City *</label>
                                    <input v-model="form.city_name" type="text" :readonly="!manualLocationOverride" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" :class="{ 'border-red-500': fieldHasError('city_name') }" @input="clearClientError('city_name')" />
                                    <div v-if="fieldHasError('city_name')" class="text-red-500 text-sm mt-1">{{ fieldError('city_name') }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mandal / Taluk</label>
                                    <input v-model="form.mandal_name" type="text" :readonly="!manualLocationOverride" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" />
                                </div>
                            </div>

                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ t('address_info') }}</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('street_address') }} *</label>
                                    <textarea 
                                        v-model="form.address"
                                        rows="3"
                                        :readonly="!manualLocationOverride"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': fieldHasError('address') }"
                                        @input="clearClientError('address')"
                                        placeholder="Enter your complete address"
                                    ></textarea>
                                    <div v-if="fieldHasError('address')" class="text-red-500 text-sm mt-1">{{ fieldError('address') }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                    <input 
                                        v-model="form.postal_code"
                                        type="text" 
                                        :readonly="!manualLocationOverride"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="560001"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Preferences -->
                    <div v-if="currentStep === 3" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Interests & Preferences</h3>

                                <!-- Areas of Interest -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Areas of Interest</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label v-for="interest in interests" :key="interest" class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                :value="interest"
                                                v-model="form.interests"
                                                class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">{{ interest }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Skills -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Skills (Optional)</label>
                                    <textarea 
                                        v-model="form.skills"
                                        rows="4"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="Tell us about your skills and expertise..."
                                    ></textarea>
                                </div>

                                <!-- Availability -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Availability for Volunteering</label>
                                    <select 
                                        v-model="form.availability"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    >
                                        <option value="">Select availability</option>
                                        <option value="weekdays">Weekdays</option>
                                        <option value="weekends">Weekends</option>
                                        <option value="flexible">Flexible</option>
                                        <option value="remote">Remote</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Communication Preferences</h3>

                                <!-- Social Media -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn Profile (Optional)</label>
                                    <input 
                                        v-model="form.linkedin"
                                        type="url" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="https://linkedin.com/in/johndoe"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Profile (Optional)</label>
                                    <input 
                                        v-model="form.twitter"
                                        type="url" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="https://twitter.com/johndoe"
                                    >
                                </div>

                                <!-- Notification Preferences -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notification Preferences</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                v-model="form.email_notifications"
                                                class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Email notifications</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                v-model="form.sms_notifications"
                                                class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">SMS notifications</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                v-model="form.newsletter_subscribed"
                                                class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Newsletter subscription</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Review & Submit -->
                    <div v-if="currentStep === 4" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column - Review -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Review Your Information</h3>
                                
                                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                    <div class="border-b pb-3">
                                        <h4 class="font-semibold text-gray-900">Personal Details</h4>
                                        <p class="text-sm text-gray-600 mt-1"><strong>Name:</strong> {{ form.first_name }} {{ form.last_name }}</p>
                                        <p class="text-sm text-gray-600"><strong>Email:</strong> {{ form.email }}</p>
                                        <p class="text-sm text-gray-600"><strong>Phone:</strong> {{ form.phone }}</p>
                                        <p class="text-sm text-gray-600"><strong>Gender:</strong> {{ form.gender || 'Not specified' }}</p>
                                    </div>

                                    <div class="border-b pb-3">
                                        <h4 class="font-semibold text-gray-900">Location</h4>
                                        <p class="text-sm text-gray-600 mt-1"><strong>City:</strong> {{ getCityName() }}</p>
                                        <p class="text-sm text-gray-600"><strong>Postal Code:</strong> {{ form.postal_code || 'Not provided' }}</p>
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-900">Preferences</h4>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span v-for="interest in form.interests" :key="interest" class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                {{ interest }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Terms -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Terms & Conditions</h3>
                                
                                <!-- Terms and Conditions -->
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-yellow-800 mb-2">Important Notice</h4>
                                    <p class="text-sm text-yellow-700 mb-3">
                                        By submitting this registration, you confirm that all information provided is accurate and authentic. 
                                        Your account will be created and you can start participating in campaigns immediately.
                                    </p>
                                    <label class="flex items-start">
                                        <input 
                                            type="checkbox" 
                                            v-model="form.terms_accepted"
                                            class="mt-1 mr-3"
                                            id="terms"
                                            @change="clearClientError('terms_accepted')"
                                        >
                                        <span class="text-sm text-gray-700">
                                            I agree to the 
                                            <a href="#" class="text-green-600 hover:text-green-700">Terms of Service</a> and 
                                            <a href="#" class="text-green-600 hover:text-green-700">Privacy Policy</a>
                                        </span>
                                    </label>
                                </div>
                                <div v-if="fieldHasError('terms_accepted')" class="text-red-500 text-sm -mt-2">
                                    {{ fieldError('terms_accepted') }}
                                </div>

                                <!-- Privacy Settings -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-blue-800 mb-2">Privacy Settings</h4>
                                    <div class="space-y-3">
                                        <label class="flex items-start">
                                            <input 
                                                type="checkbox" 
                                                v-model="form.profile_public"
                                                class="mt-1 mr-3"
                                            >
                                            <span class="text-sm text-gray-700">
                                                Make my profile visible to other users and NGOs
                                            </span>
                                        </label>
                                        <label class="flex items-start">
                                            <input 
                                                type="checkbox" 
                                                v-model="form.marketing_consent"
                                                class="mt-1 mr-3"
                                            >
                                            <span class="text-sm text-gray-700">
                                                Allow NGOs to contact me about relevant opportunities
                                            </span>
                                        </label>
                                    </div>
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
                            {{ t('previous') }}
                        </button>
                        <div v-else></div>

                        <button 
                            type="button"
                            @click="nextStep"
                            v-if="currentStep < 4"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                        >
                            {{ t('next') }}
                        </button>

                        <button 
                            type="submit"
                            v-if="currentStep === 4"
                            :disabled="form.processing || !form.terms_accepted"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="form.processing">{{ t('creating_account') }}</span>
                            <span v-else>{{ t('complete_registration') }}</span>
                        </button>
                    </div>
                </form>

                <!-- Back Link -->
                <div class="text-center mt-8">
                    <inertia-link href="/register" class="text-gray-600 hover:text-gray-800 transition-colors">
                        ← {{ t('back_to_role_selection') }}
                    </inertia-link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onUnmounted, watch, nextTick } from 'vue'
import { useForm, Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const page = usePage()
const otpPilot = computed(() => page.props.otp?.pilot_mode ?? false)
const otpLength = computed(() => page.props.otp?.code_length ?? 6)
const language = ref('en')

const translations = {
    en: {
        step1_title: 'Personal Information',
        step2_title: 'Location Information',
        step3_title: 'Preferences & Interests',
        step4_title: 'Review & Submit',
        step1_desc: 'Tell us about yourself and contact details',
        step2_desc: 'Share your location and address information',
        step3_desc: 'Set your interests and communication preferences',
        step4_desc: 'Review your information and complete registration',
        personal_details: 'Personal Details',
        additional_info: 'Additional Information',
        first_name: 'First Name',
        last_name: 'Last Name',
        email_address: 'Email Address',
        phone_number: 'Phone Number',
        otp_code: 'OTP Code',
        otp_placeholder: 'Enter {len}-digit OTP',
        send_otp: 'Send OTP',
        send_otp_email_phone: 'Send OTP to phone + email',
        otp_sent: 'OTP Sent',
        location_card_title: 'Enable location for faster registration',
        location_card_desc: 'Allow location once. We will auto-fill Step 2 details (state, district, city, address, postal code).',
        allow_location: 'Allow location',
        notification_card_title: 'Enable notifications',
        notification_card_desc: 'Allow browser notifications for campaign updates and reminders.',
        allow_notifications: 'Allow notifications',
        login_pin: 'Login PIN',
        confirm_login_pin: 'Confirm Login PIN',
        location_details: 'Location Details',
        address_info: 'Address Information',
        state: 'State',
        district: 'District',
        city: 'City',
        street_address: 'Street Address',
        previous: 'Previous',
        next: 'Next',
        creating_account: 'Creating Account...',
        complete_registration: 'Complete Registration',
        back_to_role_selection: 'Back to Role Selection',
        err_first_name: 'First name is required',
        err_last_name: 'Last name is required',
        err_email: 'Email is required',
        err_email_invalid: 'Enter a valid email address',
        err_email_for_otp: 'Enter your email before sending OTP',
        err_phone: 'Phone number is required',
        err_phone_len: 'Enter a valid 10-digit mobile number',
        err_password: 'Login PIN is required',
        err_password_len: 'Login PIN must be at least 5 characters',
        err_password_confirm: 'Confirm your login PIN',
        err_password_match: 'Login PIN does not match',
        err_send_otp: 'Please click Send OTP',
        err_otp_required: 'OTP is required',
        err_otp_len: 'Enter a valid {len}-digit OTP',
        err_state: 'State is required',
        err_district: 'District is required',
        err_city: 'City is required',
        err_address: 'Address is required',
        err_terms: 'Please accept Terms & Conditions to continue',
    },
    kn: {
        step1_title: 'ವೈಯಕ್ತಿಕ ಮಾಹಿತಿ',
        step2_title: 'ಸ್ಥಳ ಮಾಹಿತಿ',
        step3_title: 'ಆಸಕ್ತಿ ಮತ್ತು ಆಯ್ಕೆಗಳು',
        step4_title: 'ಪರಿಶೀಲಿಸಿ ಮತ್ತು ಸಲ್ಲಿಸಿ',
        step1_desc: 'ನಿಮ್ಮ ವಿವರಗಳು ಮತ್ತು ಸಂಪರ್ಕ ಮಾಹಿತಿಯನ್ನು ನೀಡಿ',
        step2_desc: 'ನಿಮ್ಮ ಸ್ಥಳ ಮತ್ತು ವಿಳಾಸ ಮಾಹಿತಿಯನ್ನು ನೀಡಿ',
        step3_desc: 'ನಿಮ್ಮ ಆಸಕ್ತಿಗಳು ಮತ್ತು ಸಂವಹನ ಆಯ್ಕೆಗಳು ಹೊಂದಿಸಿ',
        step4_desc: 'ಮಾಹಿತಿಯನ್ನು ಪರಿಶೀಲಿಸಿ ಮತ್ತು ನೋಂದಣಿ ಪೂರ್ಣಗೊಳಿಸಿ',
        personal_details: 'ವೈಯಕ್ತಿಕ ವಿವರಗಳು',
        additional_info: 'ಹೆಚ್ಚುವರಿ ಮಾಹಿತಿ',
        first_name: 'ಮೊದಲ ಹೆಸರು',
        last_name: 'ಕೊನೆಯ ಹೆಸರು',
        email_address: 'ಇಮೇಲ್ ವಿಳಾಸ',
        phone_number: 'ದೂರವಾಣಿ ಸಂಖ್ಯೆ',
        otp_code: 'OTP ಕೋಡ್',
        otp_placeholder: '{len} ಅಂಕಿಯ OTP ನಮೂದಿಸಿ',
        send_otp: 'OTP ಕಳುಹಿಸಿ',
        send_otp_email_phone: 'ಫೋನ್ + ಇಮೇಲ್ ಗೆ OTP ಕಳುಹಿಸಿ',
        otp_sent: 'OTP ಕಳುಹಿಸಲಾಗಿದೆ',
        location_card_title: 'ವೇಗದ ನೋಂದಣಿಗೆ ಸ್ಥಳ ಅನುಮತಿ ನೀಡಿ',
        location_card_desc: 'ಒಮ್ಮೆ ಸ್ಥಳ ಅನುಮತಿ ನೀಡಿ. ಹಂತ 2ರಲ್ಲಿ ರಾಜ್ಯ, ಜಿಲ್ಲೆ, ನಗರ, ವಿಳಾಸ ಮತ್ತು ಪಿನ್ ಕೋಡ್ ಸ್ವಯಂ ತುಂಬಲಾಗುತ್ತದೆ.',
        allow_location: 'ಸ್ಥಳ ಅನುಮತಿ ನೀಡಿ',
        notification_card_title: 'ನೋಟಿಫಿಕೇಷನ್ ಅನುಮತಿ ನೀಡಿ',
        notification_card_desc: 'ಕ್ಯಾಂಪೇನ್ ಅಪ್‌ಡೇಟ್‌ಗಳು ಮತ್ತು ನೆನಪುಗಳಿಗೆ ಬ್ರೌಸರ್ ನೋಟಿಫಿಕೇಷನ್ ಅನುಮತಿ ನೀಡಿ.',
        allow_notifications: 'ನೋಟಿಫಿಕೇಷನ್ ಅನುಮತಿ ನೀಡಿ',
        login_pin: 'ಲಾಗಿನ್ PIN',
        confirm_login_pin: 'ಲಾಗಿನ್ PIN ದೃಢೀಕರಿಸಿ',
        location_details: 'ಸ್ಥಳ ವಿವರಗಳು',
        address_info: 'ವಿಳಾಸ ಮಾಹಿತಿ',
        state: 'ರಾಜ್ಯ',
        district: 'ಜಿಲ್ಲೆ',
        city: 'ನಗರ',
        street_address: 'ವಿಳಾಸ',
        previous: 'ಹಿಂದೆ',
        next: 'ಮುಂದೆ',
        creating_account: 'ಖಾತೆ ರಚಿಸಲಾಗುತ್ತಿದೆ...',
        complete_registration: 'ನೋಂದಣಿ ಪೂರ್ಣಗೊಳಿಸಿ',
        back_to_role_selection: 'ಪಾತ್ರ ಆಯ್ಕೆಗೆ ಹಿಂತಿರುಗಿ',
        err_first_name: 'ಮೊದಲ ಹೆಸರು ಅಗತ್ಯವಿದೆ',
        err_last_name: 'ಕೊನೆಯ ಹೆಸರು ಅಗತ್ಯವಿದೆ',
        err_email: 'ಇಮೇಲ್ ಅಗತ್ಯವಿದೆ',
        err_email_invalid: 'ಸರಿಯಾದ ಇಮೇಲ್ ವಿಳಾಸ ನಮೂದಿಸಿ',
        err_email_for_otp: 'OTP ಕಳುಹಿಸುವ ಮೊದಲು ನಿಮ್ಮ ಇಮೇಲ್ ನಮೂದಿಸಿ',
        err_phone: 'ದೂರವಾಣಿ ಸಂಖ್ಯೆ ಅಗತ್ಯವಿದೆ',
        err_phone_len: 'ಸರಿಯಾದ 10 ಅಂಕಿಯ ಮೊಬೈಲ್ ಸಂಖ್ಯೆ ನಮೂದಿಸಿ',
        err_password: 'ಲಾಗಿನ್ PIN ಅಗತ್ಯವಿದೆ',
        err_password_len: 'ಲಾಗಿನ್ PIN ಕನಿಷ್ಠ 5 ಅಕ್ಷರಗಳಿರಬೇಕು',
        err_password_confirm: 'ಲಾಗಿನ್ PIN ದೃಢೀಕರಿಸಿ',
        err_password_match: 'ಲಾಗಿನ್ PIN ಹೊಂದಿಕೆಯಾಗುತ್ತಿಲ್ಲ',
        err_send_otp: 'ದಯವಿಟ್ಟು Send OTP ಒತ್ತಿ',
        err_otp_required: 'OTP ಅಗತ್ಯವಿದೆ',
        err_otp_len: 'ಸರಿಯಾದ {len} ಅಂಕಿಯ OTP ನಮೂದಿಸಿ',
        err_state: 'ರಾಜ್ಯ ಅಗತ್ಯವಿದೆ',
        err_district: 'ಜಿಲ್ಲೆ ಅಗತ್ಯವಿದೆ',
        err_city: 'ನಗರ ಅಗತ್ಯವಿದೆ',
        err_address: 'ವಿಳಾಸ ಅಗತ್ಯವಿದೆ',
        err_terms: 'ಮುಂದುವರಿಯಲು ನಿಯಮಗಳು ಒಪ್ಪಿಕೊಳ್ಳಿ',
    },
}

const t = (key) => translations[language.value]?.[key] ?? translations.en[key] ?? key

const props = defineProps({
    states: Array
})

const currentStep = ref(1)
const profilePreview = ref(null)

const stepTitles = computed(() => [
    t('step1_title'),
    t('step2_title'),
    t('step3_title'),
    t('step4_title'),
])

const stepDescriptions = computed(() => [
    t('step1_desc'),
    t('step2_desc'),
    t('step3_desc'),
    t('step4_desc'),
])

const interests = [
    'Education',
    'Healthcare',
    'Environment',
    'Women Empowerment',
    'Child Welfare',
    'Rural Development',
    'Animal Welfare',
    'Arts & Culture',
    'Sports',
    'Technology',
    'Senior Citizen Care'
]

const form = useForm({
    // Step 1: Personal Information
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    date_of_birth: '',
    gender: '',
    otp_code: '',
    latitude: null,
    longitude: null,
    location_permission: 'prompt',
    notification_permission: 'default',
    notification_token: null,
    timezone_offset: new Date().getTimezoneOffset(),
    screen_width: typeof window !== 'undefined' ? window.screen.width : null,
    screen_height: typeof window !== 'undefined' ? window.screen.height : null,
    
    // Step 2: Location Information
    state_id: '',
    district_id: '',
    city_id: '',
    state_name: '',
    district_name: '',
    city_name: '',
    mandal_name: '',
    address: '',
    postal_code: '',
    
    // Step 3: Preferences
    interests: [],
    skills: '',
    availability: '',
    linkedin: '',
    twitter: '',
    email_notifications: true,
    sms_notifications: true,
    newsletter_subscribed: true,
    
    // Step 4: Additional fields
    profile_picture: null,
    profile_public: false,
    marketing_consent: false,
    terms_accepted: false
})

const otpSent = ref(false)
/** Shown after Send OTP in pilot mode (API returns otp_plain). */
const lastPilotOtp = ref('')
const otpStatusMessage = ref('')
const locationStatusMessage = ref('')
const notificationStatusMessage = ref('')
const clientErrors = reactive({})
const draftStorageKey = 'individual_registration_draft_v1'
const showDraftPrompt = ref(false)
const draftMeta = reactive({ id: '', saved_at: 0 })
const draftStatusMessage = ref('')
let autoSaveTimer = null
const manualLocationOverride = ref(false)

const fieldError = (name) => form.errors[name] || clientErrors[name] || ''
const fieldHasError = (name) => Boolean(fieldError(name))
const clearClientError = (name) => {
    if (clientErrors[name]) {
        delete clientErrors[name]
    }
}

const districts = computed(() => {
    if (!form.state_id) return []
    const state = props.states.find(s => s.id === form.state_id)
    return state ? state.districts : []
})

const cities = computed(() => {
    if (!form.district_id) return []
    const district = districts.value.find(d => d.id === form.district_id)
    return district ? district.cities : []
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
    Object.keys(clientErrors).forEach((key) => delete clientErrors[key])

    if (currentStep.value === 1) {
        if (!form.first_name?.trim()) clientErrors.first_name = t('err_first_name')
        if (!form.last_name?.trim()) clientErrors.last_name = t('err_last_name')
        if (!form.email?.trim()) {
            clientErrors.email = t('err_email')
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
            clientErrors.email = t('err_email_invalid')
        }
        if (!form.phone?.trim()) {
            clientErrors.phone = t('err_phone')
        } else if (!/^\d{10}$/.test(form.phone.trim())) {
            clientErrors.phone = t('err_phone_len')
        }
        if (!form.password) {
            clientErrors.password = t('err_password')
        } else if (form.password.length < 5) {
            clientErrors.password = t('err_password_len')
        }
        if (!form.password_confirmation) {
            clientErrors.password_confirmation = t('err_password_confirm')
        } else if (form.password !== form.password_confirmation) {
            clientErrors.password_confirmation = t('err_password_match')
        }
        if (!otpSent.value) {
            clientErrors.otp_code = t('err_send_otp')
        } else if (!form.otp_code?.trim()) {
            clientErrors.otp_code = t('err_otp_required')
        } else if (!new RegExp(`^\\d{${otpLength.value}}$`).test(form.otp_code.trim())) {
            clientErrors.otp_code = t('err_otp_len').replace('{len}', otpLength.value)
        }

        if (Object.keys(clientErrors).length > 0) return false
    } else if (currentStep.value === 2) {
        if (!form.state_name?.trim()) clientErrors.state_name = t('err_state')
        if (!form.district_name?.trim()) clientErrors.district_name = t('err_district')
        if (!form.city_name?.trim()) clientErrors.city_name = t('err_city')
        if (!form.address?.trim()) clientErrors.address = t('err_address')
        if (Object.keys(clientErrors).length > 0) return false
    } else if (currentStep.value === 3) {
        // Step 3 is optional, always allow to proceed
        return true
    } else if (currentStep.value === 4) {
        if (!form.terms_accepted) {
            clientErrors.terms_accepted = t('err_terms')
            return false
        }
    }
    return true
}

const handleProfileUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.profile_picture = file
        const reader = new FileReader()
        reader.onload = (e) => {
            profilePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    }
}

const removeProfile = () => {
    form.profile_picture = null
    profilePreview.value = null
}

const getCityName = () => {
    if (!form.city_id) return 'Not selected'
    const district = districts.value.find(d => d.id === form.district_id)
    if (!district) return 'Not selected'
    const city = district.cities.find(c => c.id === form.city_id)
    return city ? city.name : 'Not selected'
}

const onStateChange = () => {
    form.district_id = ''
    form.city_id = ''
}

const onDistrictChange = () => {
    form.city_id = ''
}

const onPhoneInput = (event) => {
    const digitsOnly = (event.target.value || '').replace(/\D/g, '').slice(0, 10)
    form.phone = digitsOnly
    clearClientError('phone')
}

const normalizeForMatch = (value) =>
    (value || '')
        .toLowerCase()
        .replace(/district|city|state|ಬೆಂಗಳೂರು ನಗರ|bengaluru urban/g, '')
        .replace(/[^a-z\u0C80-\u0CFF0-9 ]/g, ' ')
        .replace(/\s+/g, ' ')
        .trim()

const pickIdByName = (items, rawName) => {
    if (!rawName) return ''
    const expected = normalizeForMatch(rawName)
    const exact = items.find((item) => normalizeForMatch(item.name) === expected)
    if (exact) return exact.id

    const partial = items.find((item) => normalizeForMatch(item.name).includes(expected) || expected.includes(normalizeForMatch(item.name)))
    return partial ? partial.id : ''
}

const flattenCities = () => {
    const cities = []
    ;(props.states || []).forEach((state) => {
        ;(state.districts || []).forEach((district) => {
            ;(district.cities || []).forEach((city) => {
                cities.push({
                    state_id: state.id,
                    district_id: district.id,
                    city_id: city.id,
                    name: city.name,
                    type: city.type,
                    pincode: city.pincode ? String(city.pincode) : null,
                    latitude: city.latitude ? Number(city.latitude) : null,
                    longitude: city.longitude ? Number(city.longitude) : null,
                })
            })
        })
    })
    return cities
}

const pickNearestCityByCoordinates = (lat, lng) => {
    const cities = flattenCities().filter((city) => city.latitude && city.longitude)
    if (!cities.length) return null

    const toRad = (v) => (v * Math.PI) / 180
    const distanceKm = (aLat, aLng, bLat, bLng) => {
        const R = 6371
        const dLat = toRad(bLat - aLat)
        const dLng = toRad(bLng - aLng)
        const x =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRad(aLat)) * Math.cos(toRad(bLat)) * Math.sin(dLng / 2) * Math.sin(dLng / 2)
        const c = 2 * Math.atan2(Math.sqrt(x), Math.sqrt(1 - x))
        return R * c
    }

    let nearest = null
    let best = Number.POSITIVE_INFINITY
    for (const city of cities) {
        const d = distanceKm(lat, lng, city.latitude, city.longitude)
        if (d < best) {
            best = d
            nearest = city
        }
    }
    return nearest
}

const pickByPincode = (postcode) => {
    const pin = String(postcode || '').replace(/\D/g, '').slice(0, 6)
    if (!pin) return null
    return flattenCities().find((city) => city.pincode === pin) || null
}

const pickByCityNameGlobal = (cityName) => {
    const expected = normalizeForMatch(cityName)
    if (!expected) return null
    return flattenCities().find((city) => normalizeForMatch(city.name) === expected)
        || flattenCities().find((city) => normalizeForMatch(city.name).includes(expected) || expected.includes(normalizeForMatch(city.name)))
        || null
}

const reverseGeocodeAndAutofill = async (lat, lng) => {
    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
    const data = await response.json()
    const addr = data.address || {}

    form.address = data.display_name || form.address
    form.postal_code = addr.postcode || form.postal_code

    const stateName = addr.state || addr.state_district || ''
    const districtName = addr.state_district || addr.county || addr.district || ''
    const cityName = addr.city || addr.town || addr.village || addr.hamlet || ''
    const mandalName = addr.suburb || addr.city_district || addr.county || ''

    form.state_name = stateName || form.state_name
    form.district_name = districtName || form.district_name
    form.city_name = cityName || form.city_name
    form.mandal_name = mandalName || form.mandal_name

    const stateId = pickIdByName(props.states || [], stateName)
    if (stateId) {
        form.state_id = stateId
        const state = (props.states || []).find((s) => s.id === stateId)
        const districtId = pickIdByName(state?.districts || [], districtName)
        if (districtId) {
            form.district_id = districtId
            const district = (state?.districts || []).find((d) => d.id === districtId)
            const cityId = pickIdByName(district?.cities || [], cityName)
            if (cityId) {
                form.city_id = cityId
            }
        }
    }

    if (!form.state_id || !form.district_id || !form.city_id) {
        const byPin = pickByPincode(addr.postcode)
        if (byPin) {
            form.state_id = form.state_id || byPin.state_id
            form.district_id = form.district_id || byPin.district_id
            form.city_id = form.city_id || byPin.city_id
        }
    }

    if (!form.state_id || !form.district_id || !form.city_id) {
        const byCityName = pickByCityNameGlobal(cityName)
        if (byCityName) {
            form.state_id = form.state_id || byCityName.state_id
            form.district_id = form.district_id || byCityName.district_id
            form.city_id = form.city_id || byCityName.city_id
        }
    }

    if (!form.state_id || !form.district_id || !form.city_id) {
        const nearest = pickNearestCityByCoordinates(lat, lng)
        if (nearest) {
            form.state_id = form.state_id || nearest.state_id
            form.district_id = form.district_id || nearest.district_id
            form.city_id = form.city_id || nearest.city_id
        }
    }

    clearClientError('state_id')
    clearClientError('district_id')
    clearClientError('city_id')
    clearClientError('state_name')
    clearClientError('district_name')
    clearClientError('city_name')
    clearClientError('address')
}

const requestLocationAccess = async () => {
    if (!navigator.geolocation) {
        form.location_permission = 'denied'
        locationStatusMessage.value = 'Geolocation is not supported in this browser.'
        return
    }

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            form.location_permission = 'granted'
            form.latitude = Number(position.coords.latitude.toFixed(7))
            form.longitude = Number(position.coords.longitude.toFixed(7))
            locationStatusMessage.value = 'Location access granted. Auto-filling Step 2 details...'

            try {
                await reverseGeocodeAndAutofill(form.latitude, form.longitude)
                locationStatusMessage.value = 'Step 2 details were auto-filled from your location. Please verify once.'
            } catch (error) {
                locationStatusMessage.value = 'Location captured, but address auto-fill failed. You can fill details manually.'
                console.error('Reverse geocoding failed:', error)
            }
        },
        () => {
            form.location_permission = 'denied'
            locationStatusMessage.value = 'Location permission denied.'
        },
        { enableHighAccuracy: true, timeout: 12000, maximumAge: 60000 }
    )
}

const requestNotificationAccess = async () => {
    if (!('Notification' in window)) {
        form.notification_permission = 'denied'
        notificationStatusMessage.value = 'Notifications are not supported in this browser.'
        return
    }

    const permission = await Notification.requestPermission()
    form.notification_permission = permission

    if (permission !== 'granted') {
        form.notification_token = null
        notificationStatusMessage.value = 'Notification permission not granted.'
        return
    }

    notificationStatusMessage.value = 'Notification permission granted.'

    try {
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            const registration = await navigator.serviceWorker.ready
            let sub = await registration.pushManager.getSubscription()
            if (!sub) {
                // In this pilot phase we only store a token when subscription already exists.
                // Full push setup (VAPID + backend push service) can be added next.
                form.notification_token = null
                notificationStatusMessage.value = 'Permission granted. Push token will be available after push service setup.'
                return
            }
            form.notification_token = sub.endpoint
            notificationStatusMessage.value = 'Notification permission granted and token captured.'
            return
        }
    } catch (error) {
        console.error('Push token capture failed:', error)
    }

    form.notification_token = null
}

const generateDraftId = () => `REG-${new Date().getFullYear()}-${Math.random().toString(36).slice(2, 8).toUpperCase()}`

const getDraftPayload = () => ({
    id: draftMeta.id || generateDraftId(),
    saved_at: Date.now(),
    current_step: currentStep.value,
    data: {
        ...form.data(),
    },
    otp_sent: otpSent.value,
    last_pilot_otp: lastPilotOtp.value,
})

const saveDraft = (manual = false) => {
    try {
        const payload = getDraftPayload()
        draftMeta.id = payload.id
        draftMeta.saved_at = payload.saved_at
        localStorage.setItem(draftStorageKey, JSON.stringify(payload))
        if (manual) {
            draftStatusMessage.value = `Draft saved. Registration ID: ${payload.id}`
            setTimeout(() => {
                draftStatusMessage.value = ''
            }, 3500)
        }
    } catch (error) {
        console.error('Could not save draft:', error)
    }
}

const loadDraft = () => {
    try {
        const raw = localStorage.getItem(draftStorageKey)
        if (!raw) return
        const draft = JSON.parse(raw)
        draftMeta.id = draft.id || generateDraftId()
        draftMeta.saved_at = draft.saved_at || Date.now()
        showDraftPrompt.value = true
    } catch (error) {
        console.error('Could not load draft:', error)
    }
}

const normalizeRestoredDraftData = (data) => {
    const normalized = { ...(data || {}) }
    for (const key of ['state_id', 'district_id', 'city_id']) {
        if (normalized[key] !== '' && normalized[key] !== null && normalized[key] !== undefined) {
            const numeric = Number(normalized[key])
            normalized[key] = Number.isNaN(numeric) ? normalized[key] : numeric
        }
    }
    return normalized
}

const restoreDraft = () => {
    try {
        const raw = localStorage.getItem(draftStorageKey)
        if (!raw) return
        const draft = JSON.parse(raw)
        const normalizedData = normalizeRestoredDraftData(draft.data)

        // Restore only actual form fields to avoid mutating Inertia form internals.
        const currentData = form.data()
        Object.keys(currentData).forEach((key) => {
            if (Object.prototype.hasOwnProperty.call(normalizedData, key)) {
                form[key] = normalizedData[key]
            }
        })

        const restoredStep = Number(draft.current_step ?? draft.step ?? 1)
        currentStep.value = Number.isFinite(restoredStep) ? Math.min(4, Math.max(1, restoredStep)) : 1
        otpSent.value = Boolean(draft.otp_sent)
        lastPilotOtp.value = draft.last_pilot_otp || ''
        draftMeta.id = draft.id || generateDraftId()
        showDraftPrompt.value = false
        draftStatusMessage.value = `Draft restored. Registration ID: ${draftMeta.id}`

        nextTick(() => {
            // Force refresh of dependent dropdowns in case restored IDs came as strings.
            if (currentStep.value >= 2 && form.state_id && form.district_id && form.city_id) {
                clearClientError('state_id')
                clearClientError('district_id')
                clearClientError('city_id')
            }
        })
    } catch (error) {
        console.error('Could not restore draft:', error)
    }
}

const discardDraft = () => {
    try {
        localStorage.removeItem(draftStorageKey)
    } catch (error) {
        console.error('Could not discard draft:', error)
    }
    showDraftPrompt.value = false
}

const handleBeforeUnload = () => saveDraft()

if (typeof window !== 'undefined') {
    loadDraft()
    window.addEventListener('beforeunload', handleBeforeUnload)
}

watch(
    () => ({
        step: currentStep.value,
        data: form.data(),
        otpSent: otpSent.value,
        otp: lastPilotOtp.value,
    }),
    () => {
        if (showDraftPrompt.value) return
        if (autoSaveTimer) clearTimeout(autoSaveTimer)
        autoSaveTimer = setTimeout(() => saveDraft(false), 800)
    },
    { deep: true }
)

onUnmounted(() => {
    if (typeof window !== 'undefined') {
        window.removeEventListener('beforeunload', handleBeforeUnload)
    }
    if (autoSaveTimer) clearTimeout(autoSaveTimer)
})

const sendOTP = async () => {
    if (!form.phone) return
    if (!form.email?.trim()) {
        clientErrors.email = t('err_email_for_otp')
        return
    }
    
    try {
        const response = await fetch('/auth/send-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                phone: form.phone,
                email: form.email || null,
            })
        })
        
        const data = await response.json()
        if (data.success) {
            otpSent.value = true
            lastPilotOtp.value = data.otp_plain || data.debug_otp || ''
            otpStatusMessage.value = data.message || 'OTP sent successfully.'
            if (import.meta.env.DEV && (data.debug_otp || data.otp_plain)) {
                console.info('[FEVOURD-K] OTP:', data.otp_plain || data.debug_otp)
            }
        } else {
            const msg = data.message || data.errors?.phone?.[0] || 'Could not send OTP. Check the number and try again.'
            otpStatusMessage.value = ''
            alert(msg)
        }
    } catch (error) {
        console.error('Error sending OTP:', error)
        otpStatusMessage.value = ''
    }
}

const submitForm = () => {
    form.post('/register/individual', {
        onSuccess: () => {
            try { localStorage.removeItem(draftStorageKey) } catch (e) {}
            draftMeta.id = ''
            showDraftPrompt.value = false
            router.push('/dashboard')
        }
    })
}
</script>
