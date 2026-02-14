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
                        <h2 class="text-2xl font-bold text-gray-900">{{ stepTitles[currentStep - 1] }}</h2>
                        <p class="text-gray-600 mt-2">{{ stepDescriptions[currentStep - 1] }}</p>
                    </div>
                </div>

                <!-- Registration Form -->
                <form @submit.prevent="submitForm" class="bg-white rounded-2xl shadow-xl p-8">
                    <!-- Step 1: Personal Information -->
                    <div v-if="currentStep === 1" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Personal Details</h3>
                                
                                <!-- Name Fields -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                        <input 
                                            v-model="form.first_name"
                                            type="text" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.first_name }"
                                            placeholder="John"
                                        >
                                        <div v-if="form.errors.first_name" class="text-red-500 text-sm mt-1">{{ form.errors.first_name }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                        <input 
                                            v-model="form.last_name"
                                            type="text" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.last_name }"
                                            placeholder="Doe"
                                        >
                                        <div v-if="form.errors.last_name" class="text-red-500 text-sm mt-1">{{ form.errors.last_name }}</div>
                                    </div>
                                </div>

                                <!-- Email and Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input 
                                        v-model="form.email"
                                        type="email" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.email }"
                                        placeholder="john.doe@example.com"
                                    >
                                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                    <div class="flex gap-2">
                                        <input 
                                            v-model="form.phone"
                                            type="tel" 
                                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.phone }"
                                            placeholder="+91 98765 43210"
                                        >
                                        <button 
                                            type="button"
                                            @click="sendOTP"
                                            :disabled="otpSent || !form.phone"
                                            class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 disabled:bg-gray-400 transition-colors"
                                        >
                                            {{ otpSent ? 'OTP Sent' : 'Send OTP' }}
                                        </button>
                                    </div>
                                    <div v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</div>
                                </div>

                                <!-- OTP Verification -->
                                <div v-if="otpSent">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">OTP Code *</label>
                                    <input 
                                        v-model="form.otp_code"
                                        type="text" 
                                        maxlength="6"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.otp_code }"
                                        placeholder="Enter 6-digit OTP"
                                    >
                                    <div v-if="form.errors.otp_code" class="text-red-500 text-sm mt-1">{{ form.errors.otp_code }}</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Additional Information</h3>

                                <!-- Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                    <input 
                                        v-model="form.password"
                                        type="password" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.password }"
                                        placeholder="Min. 8 characters"
                                    >
                                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                    <input 
                                        v-model="form.password_confirmation"
                                        type="password" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="Confirm password"
                                    >
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
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Location Details</h3>
                                
                                <!-- Location Dropdowns -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                                    <select 
                                        v-model="form.state_id"
                                        @change="onStateChange"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.state_id }"
                                    >
                                        <option value="">Select State</option>
                                        <option v-for="state in states" :key="state.id" :value="state.id">
                                            {{ state.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.state_id" class="text-red-500 text-sm mt-1">{{ form.errors.state_id }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">District *</label>
                                    <select 
                                        v-model="form.district_id"
                                        @change="onDistrictChange"
                                        :disabled="!form.state_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.district_id }"
                                    >
                                        <option value="">Select District</option>
                                        <option v-for="district in districts" :key="district.id" :value="district.id">
                                            {{ district.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.district_id" class="text-red-500 text-sm mt-1">{{ form.errors.district_id }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                    <select 
                                        v-model="form.city_id"
                                        :disabled="!form.district_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.city_id }"
                                    >
                                        <option value="">Select City</option>
                                        <option v-for="city in cities" :key="city.id" :value="city.id">
                                            {{ city.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.city_id" class="text-red-500 text-sm mt-1">{{ form.errors.city_id }}</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Address Information</h3>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                                    <textarea 
                                        v-model="form.address"
                                        rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="Enter your complete address"
                                    ></textarea>
                                    <div v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                    <input 
                                        v-model="form.postal_code"
                                        type="text" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="560001"
                                    >
                                </div>

                                <!-- Profile Picture -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture (Optional)</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                        <div v-if="!profilePreview" class="space-y-2">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="profile-upload" class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500">
                                                    <span>Upload a file</span>
                                                    <input id="profile-upload" name="profile_picture" type="file" class="sr-only" @change="handleProfileUpload" accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                        </div>
                                        <div v-else class="space-y-2">
                                            <img :src="profilePreview" alt="Profile preview" class="mx-auto h-24 w-24 object-cover rounded-lg">
                                            <button type="button" @click="removeProfile" class="text-sm text-red-600 hover:text-red-500">Remove</button>
                                        </div>
                                    </div>
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
                                        >
                                        <span class="text-sm text-gray-700">
                                            I agree to the 
                                            <a href="#" class="text-green-600 hover:text-green-700">Terms of Service</a> and 
                                            <a href="#" class="text-green-600 hover:text-green-700">Privacy Policy</a>
                                        </span>
                                    </label>
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
                            Previous
                        </button>
                        <div v-else></div>

                        <button 
                            type="button"
                            @click="nextStep"
                            v-if="currentStep < 4"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                        >
                            Next
                        </button>

                        <button 
                            type="submit"
                            v-if="currentStep === 4"
                            :disabled="form.processing || !form.terms_accepted"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="form.processing">Creating Account...</span>
                            <span v-else>Complete Registration</span>
                        </button>
                    </div>
                </form>

                <!-- Back Link -->
                <div class="text-center mt-8">
                    <inertia-link href="/register" class="text-gray-600 hover:text-gray-800 transition-colors">
                        ← Back to Role Selection
                    </inertia-link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useForm, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    states: Array
})

const currentStep = ref(1)
const profilePreview = ref(null)

const stepTitles = [
    'Personal Information',
    'Location Information', 
    'Preferences & Interests',
    'Review & Submit'
]

const stepDescriptions = [
    'Tell us about yourself and contact details',
    'Share your location and address information',
    'Set your interests and communication preferences',
    'Review your information and complete registration'
]

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
    
    // Step 2: Location Information
    state_id: '',
    district_id: '',
    city_id: '',
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
    if (currentStep.value === 1) {
        if (!form.first_name || !form.last_name || !form.email || !form.phone || !form.password || !form.password_confirmation) {
            return false
        }
    } else if (currentStep.value === 2) {
        if (!form.state_id || !form.district_id || !form.city_id || !form.address) {
            return false
        }
    } else if (currentStep.value === 3) {
        // Step 3 is optional, always allow to proceed
        return true
    } else if (currentStep.value === 4) {
        if (!form.terms_accepted) {
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

const sendOTP = async () => {
    if (!form.phone) return
    
    try {
        const response = await fetch('/auth/send-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ phone: form.phone })
        })
        
        const data = await response.json()
        if (data.success) {
            otpSent.value = true
            // In production, remove this alert
            alert(`OTP for demo: ${data.otp}`)
        }
    } catch (error) {
        console.error('Error sending OTP:', error)
    }
}

const submitForm = () => {
    form.post('/register/individual', {
        onSuccess: () => {
            router.push('/dashboard')
        }
    })
}
</script>
