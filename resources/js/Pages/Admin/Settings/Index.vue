<script setup>
import { ref, reactive } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const page = usePage()
const processing = ref(false)

// Initialize form with settings
const form = reactive({
    settings: {
        general: {},
        theme: {},
        maintenance: {},
        system: {},
        email: {},
    }
})

// Initialize settings from props
const initializeSettings = () => {
    const settings = page.props.settings
    
    // General settings
    form.settings.general = {
        'site_name': { key: 'site_name', value: settings.general?.site_name?.value || 'FEVOURD-K', type: 'text', group: 'general', is_public: true },
        'site_tagline': { key: 'site_tagline', value: settings.general?.site_tagline?.value || 'Voluntary Action | Karnataka NGO Hub', type: 'text', group: 'general', is_public: true },
        'contact_email': { key: 'contact_email', value: settings.general?.contact_email?.value || 'fevourd.karnataka@gmail.com', type: 'text', group: 'general', is_public: true },
        'contact_phone': { key: 'contact_phone', value: settings.general?.contact_phone?.value || '+91 94484 64171', type: 'text', group: 'general', is_public: true },
        'site_description': { key: 'site_description', value: settings.general?.site_description?.value || 'Connecting NGOs with donors for social impact in Karnataka', type: 'text', group: 'general', is_public: true },
        'site_keywords': { key: 'site_keywords', value: settings.general?.site_keywords?.value || 'NGO, CSR, donations, Karnataka, social impact, volunteering', type: 'text', group: 'general', is_public: true },
        'timezone': { key: 'timezone', value: settings.general?.timezone?.value || 'Asia/Kolkata', type: 'text', group: 'general', is_public: true },
        'date_format': { key: 'date_format', value: settings.general?.date_format?.value || 'd M Y', type: 'text', group: 'general', is_public: true },
        'language': { key: 'language', value: settings.general?.language?.value || 'en', type: 'text', group: 'general', is_public: true },
    }
    
    // Maintenance settings
    form.settings.maintenance = {
        'maintenance_mode': { key: 'maintenance_mode', value: settings.maintenance?.maintenance_mode?.value || false, type: 'boolean', group: 'maintenance', is_public: true },
        'maintenance_message': { key: 'maintenance_message', value: settings.maintenance?.maintenance_message?.value || 'We are currently under maintenance. Please check back soon.', type: 'text', group: 'maintenance', is_public: true },
        'allowed_ips': { key: 'allowed_ips', value: settings.maintenance?.allowed_ips?.value || '', type: 'text', group: 'maintenance', is_public: true },
        'maintenance_start_time': { key: 'maintenance_start_time', value: settings.maintenance?.maintenance_start_time?.value || '', type: 'datetime', group: 'maintenance', is_public: true },
        'maintenance_end_time': { key: 'maintenance_end_time', value: settings.maintenance?.maintenance_end_time?.value || '', type: 'datetime', group: 'maintenance', is_public: true },
    }
    
    // Theme settings
    form.settings.theme = {
        'primary_color': { key: 'primary_color', value: settings.theme?.primary_color?.value || '#2563eb', type: 'color', group: 'theme', is_public: true },
        'secondary_color': { key: 'secondary_color', value: settings.theme?.secondary_color?.value || '#64748b', type: 'color', group: 'theme', is_public: true },
        'accent_color': { key: 'accent_color', value: settings.theme?.accent_color?.value || '#10b981', type: 'color', group: 'theme', is_public: true },
        'background_color': { key: 'background_color', value: settings.theme?.background_color?.value || '#ffffff', type: 'color', group: 'theme', is_public: true },
        'text_color': { key: 'text_color', value: settings.theme?.text_color?.value || '#1f2937', type: 'color', group: 'theme', is_public: true },
        'header_style': { key: 'header_style', value: settings.theme?.header_style?.value || 'modern', type: 'text', group: 'theme', is_public: true },
        'dark_mode': { key: 'dark_mode', value: settings.theme?.dark_mode?.value || false, type: 'boolean', group: 'theme', is_public: true },
    }
    
    // System settings
    form.settings.system = {
        'app_debug': { key: 'app_debug', value: settings.system?.app_debug?.value || false, type: 'boolean', group: 'system', is_public: false },
        'app_env': { key: 'app_env', value: settings.system?.app_env?.value || 'production', type: 'text', group: 'system', is_public: false },
        'max_upload_size': { key: 'max_upload_size', value: settings.system?.max_upload_size?.value || 10240, type: 'number', group: 'system', is_public: true },
        'session_lifetime': { key: 'session_lifetime', value: settings.system?.session_lifetime?.value || 120, type: 'number', group: 'system', is_public: true },
        'cache_driver': { key: 'cache_driver', value: settings.system?.cache_driver?.value || 'redis', type: 'text', group: 'system', is_public: true },
        'queue_connection': { key: 'queue_connection', value: settings.system?.queue_connection?.value || 'redis', type: 'text', group: 'system', is_public: true },
    }
    
    // Email settings
    form.settings.email = {
        'mail_driver': { key: 'mail_driver', value: settings.email?.mail_driver?.value || 'smtp', type: 'text', group: 'email', is_public: true },
        'mail_host': { key: 'mail_host', value: settings.email?.mail_host?.value || 'smtp.gmail.com', type: 'text', group: 'email', is_public: true },
        'mail_port': { key: 'mail_port', value: settings.email?.mail_port?.value || 587, type: 'number', group: 'email', is_public: true },
        'mail_username': { key: 'mail_username', value: settings.email?.mail_username?.value || '', type: 'text', group: 'email', is_public: true },
        'mail_encryption': { key: 'mail_encryption', value: settings.email?.mail_encryption?.value || 'tls', type: 'text', group: 'email', is_public: true },
        'mail_from_address': { key: 'mail_from_address', value: settings.email?.mail_from_address?.value || 'noreply@fevourd-k.org', type: 'text', group: 'email', is_public: true },
        'mail_from_name': { key: 'mail_from_name', value: settings.email?.mail_from_name?.value || 'FEVOURD-K', type: 'text', group: 'email', is_public: true },
    }
}

const submit = () => {
    processing.value = true
    
    const allSettings = []
    Object.values(form.settings).forEach(group => {
        Object.values(group).forEach(setting => {
            allSettings.push(setting)
        })
    })
    
    router.post('/admin/settings', {
        settings: allSettings
    }, {
        onSuccess: () => {
            processing.value = false
        },
        onError: () => {
            processing.value = false
        }
    })
}

const resetTheme = () => {
    if (confirm('Are you sure you want to reset theme to defaults?')) {
        router.post('/admin/settings/reset-theme', {}, {
            onSuccess: () => {
                initializeSettings()
            }
        })
    }
}

const exportSettings = () => {
    router.get('/admin/settings/export', {}, {
        onSuccess: (response) => {
            const blob = new Blob([JSON.stringify(response.props.settings), null, 2], { type: 'application/json' })
            const url = window.URL.createObjectURL(blob)
            const a = document.createElement('a')
            a.href = url
            a.download = 'settings.json'
            a.click()
        }
    })
}

const importSettings = () => {
    const input = document.createElement('input')
    input.type = 'file'
    input.accept = '.json'
    input.onchange = (e) => {
        const file = e.target.files[0]
        const reader = new FileReader()
        reader.onload = (event) => {
            try {
                const settings = JSON.parse(event.target.result)
                router.post('/admin/settings/import', { settings }, {
                    onSuccess: () => {
                        initializeSettings()
                    }
                })
            } catch (error) {
                alert('Invalid settings file')
            }
        }
        reader.readAsText(file)
    }
    input.click()
}

// Initialize settings on component mount
initializeSettings()
</script>

<template>
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-semibold text-gray-900">Site Settings</h1>
                    <p class="mt-2 text-gray-600">Manage your website configuration, theme, and system settings.</p>
                </div>

                <form @submit.prevent="submit">
                    <!-- General Settings -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">General Settings</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Site Name</label>
                                    <input 
                                        type="text" 
                                        v-model="form.settings.general['site_name'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Site Tagline</label>
                                    <input 
                                        type="text" 
                                        v-model="form.settings.general['site_tagline'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact Email</label>
                                    <input 
                                        type="email" 
                                        v-model="form.settings.general['contact_email'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact Phone</label>
                                    <input 
                                        type="tel" 
                                        v-model="form.settings.general['contact_phone'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Site Description</label>
                                    <textarea 
                                        v-model="form.settings.general['site_description'].value"
                                        rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Site Keywords</label>
                                    <input 
                                        type="text" 
                                        v-model="form.settings.general['site_keywords'].value"
                                        placeholder="NGO, CSR, donations, Karnataka, social impact, volunteering"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Timezone</label>
                                    <select v-model="form.settings.general['timezone'].value" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="Asia/Kolkata">Asia/Kolkata</option>
                                        <option value="Asia/Delhi">Asia/Delhi</option>
                                        <option value="Asia/Mumbai">Asia/Mumbai</option>
                                        <option value="UTC">UTC</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date Format</label>
                                    <select v-model="form.settings.general['date_format'].value" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="d M Y">d M Y</option>
                                        <option value="Y-m-d">Y-m-d</option>
                                        <option value="m/d/Y">m/d/Y</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Language</label>
                                    <select v-model="form.settings.general['language'].value" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="en">English</option>
                                        <option value="hi">Hindi</option>
                                        <option value="kn">Kannada</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Settings -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Maintenance Mode</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               v-model="form.settings.maintenance['maintenance_mode'].value"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Enable Maintenance Mode</span>
                                    </label>
                                    <p class="text-sm text-gray-500">When enabled, only admins can access site</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Maintenance Message</label>
                                <textarea 
                                    v-model="form.settings.maintenance['maintenance_message'].value"
                                    rows="3"
                                    placeholder="We are currently under maintenance. Please check back soon."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                ></textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Start Time</label>
                                    <input 
                                        type="datetime-local" 
                                        v-model="form.settings.maintenance['maintenance_start_time'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">End Time</label>
                                    <input 
                                        type="datetime-local" 
                                        v-model="form.settings.maintenance['maintenance_end_time'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Theme Settings -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Theme & Appearance</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Primary Color</label>
                                    <input 
                                        type="color" 
                                        v-model="form.settings.theme['primary_color'].value"
                                        class="mt-1 h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Secondary Color</label>
                                    <input 
                                        type="color" 
                                        v-model="form.settings.theme['secondary_color'].value"
                                        class="mt-1 h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Dark Mode</label>
                                    <select v-model="form.settings.theme['dark_mode'].value" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option :value="false">Light Mode</option>
                                        <option :value="true">Dark Mode</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Header Style</label>
                                    <select v-model="form.settings.theme['header_style'].value" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="modern">Modern</option>
                                        <option value="classic">Classic</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">System Configuration</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Max Upload Size (KB)</label>
                                    <input 
                                        type="number" 
                                        v-model="form.settings.system['max_upload_size'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Session Lifetime (minutes)</label>
                                    <input 
                                        type="number" 
                                        v-model="form.settings.system['session_lifetime'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cache Driver</label>
                                    <select v-model="form.settings.system['cache_driver'].value" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="redis">Redis</option>
                                        <option value="memcached">Memcached</option>
                                        <option value="file">File</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Queue Connection</label>
                                    <select v-model="form.settings.system['queue_connection'].value" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="redis">Redis</option>
                                        <option value="database">Database</option>
                                        <option value="sync">Sync</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Settings -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Email Settings</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mail Driver</label>
                                    <select v-model="form.settings.email['mail_driver'].value" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="smtp">SMTP</option>
                                        <option value="mail">PHP Mail</option>
                                        <option value="sendmail">Sendmail</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                                    <input 
                                        type="text" 
                                        v-model="form.settings.email['mail_host'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                                    <input 
                                        type="number" 
                                        v-model="form.settings.email['mail_port'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">From Email Address</label>
                                    <input 
                                        type="email" 
                                        v-model="form.settings.email['mail_from_address'].value"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center">
                        <div class="space-x-4">
                            <button
                                type="button"
                                @click="resetTheme"
                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700"
                            >
                                Reset Theme
                            </button>
                            <button
                                type="button"
                                @click="exportSettings"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                            >
                                Export Settings
                            </button>
                            <label class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 cursor-pointer">
                                <input type="file" @change="importSettings" accept=".json" class="hidden" />
                                Import Settings
                            </label>
                        </div>
                        <div class="flex items-center">
                            <button
                                type="submit"
                                :disabled="processing"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                            >
                                <span v-if="processing">Saving...</span>
                                <span v-else>Save Settings</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
