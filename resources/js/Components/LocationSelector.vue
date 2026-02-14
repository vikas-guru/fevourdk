<template>
    <div class="location-selector">
        <!-- Current Location Display -->
        <div class="flex items-center space-x-2 mb-4">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a1.998 1.998 0 010-2.828l4.244-4.244a1.998 1.998 0 012.828 0l4.244 4.244a1.998 1.998 0 010 2.828z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">Current Location:</span>
            <span class="text-sm font-semibold text-blue-600">{{ currentLocationDisplay }}</span>
            <button @click="showSelector = !showSelector" 
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                {{ showSelector ? 'Cancel' : 'Change' }}
            </button>
        </div>

        <!-- Location Selector -->
        <div v-if="showSelector" class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Your Location</h3>
            
            <!-- State Selection -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                <select v-model="selectedState" @change="onStateChange" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select State</option>
                    <option v-for="state in states" :key="state.id" :value="state.id">
                        {{ state.name }}
                    </option>
                </select>
            </div>

            <!-- District Selection -->
            <div v-if="selectedState" class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                <select v-model="selectedDistrict" @change="onDistrictChange"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select District</option>
                    <option v-for="district in districts" :key="district.id" :value="district.id">
                        {{ district.name }}
                    </option>
                </select>
            </div>

            <!-- City Selection -->
            <div v-if="selectedDistrict" class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                <select v-model="selectedCity" @change="onCityChange"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select City (Optional)</option>
                    <option v-for="city in cities" :key="city.id" :value="city.id">
                        {{ city.name }}
                    </option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button @click="saveLocation" 
                        :disabled="!selectedState"
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors">
                    Save Location
                </button>
                <button @click="resetToDefault" 
                        class="px-4 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Reset
                </button>
            </div>

            <!-- Location Stats -->
            <div v-if="locationStats" class="mt-4 p-4 bg-blue-50 rounded-lg">
                <h4 class="text-sm font-semibold text-blue-900 mb-2">Location Statistics</h4>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <span class="text-gray-600">NGOs:</span>
                        <span class="font-medium text-blue-600">{{ locationStats.ngos_count || 0 }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Campaigns:</span>
                        <span class="font-medium text-blue-600">{{ locationStats.active_campaigns || 0 }}</span>
                    </div>
                    <div v-if="locationStats.total_population">
                        <span class="text-gray-600">Population:</span>
                        <span class="font-medium text-blue-600">{{ locationStats.total_population.toLocaleString() }}</span>
                    </div>
                    <div v-if="locationStats.literacy_rate">
                        <span class="text-gray-600">Literacy:</span>
                        <span class="font-medium text-blue-600">{{ locationStats.literacy_rate }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    initialLocation: {
        type: Object,
        default: () => ({})
    }
})

// Reactive data
const showSelector = ref(false)
const selectedState = ref(props.initialLocation.state_id || '')
const selectedDistrict = ref(props.initialLocation.district_id || '')
const selectedCity = ref(props.initialLocation.city_id || '')

const states = ref([])
const districts = ref([])
const cities = ref([])
const locationStats = ref(null)

// Computed properties
const currentLocationDisplay = computed(() => {
    if (props.initialLocation.city_name && props.initialLocation.district_name) {
        return `${props.initialLocation.city_name}, ${props.initialLocation.district_name}`
    }
    if (props.initialLocation.district_name && props.initialLocation.state_name) {
        return `${props.initialLocation.district_name}, ${props.initialLocation.state_name}`
    }
    if (props.initialLocation.state_name) {
        return props.initialLocation.state_name
    }
    return 'All Locations'
})

// Methods
const loadStates = async () => {
    try {
        const response = await fetch('/api/locations/states')
        const data = await response.json()
        states.value = data.states
    } catch (error) {
        console.error('Error loading states:', error)
    }
}

const loadDistricts = async (stateId) => {
    if (!stateId) {
        districts.value = []
        return
    }
    
    try {
        const response = await fetch(`/api/locations/districts?state_id=${stateId}`)
        const data = await response.json()
        districts.value = data.districts
    } catch (error) {
        console.error('Error loading districts:', error)
    }
}

const loadCities = async (districtId) => {
    if (!districtId) {
        cities.value = []
        return
    }
    
    try {
        const response = await fetch(`/api/locations/cities?district_id=${districtId}`)
        const data = await response.json()
        cities.value = data.cities
    } catch (error) {
        console.error('Error loading cities:', error)
    }
}

const onStateChange = () => {
    selectedDistrict.value = ''
    selectedCity.value = ''
    districts.value = []
    cities.value = []
    locationStats.value = null
    
    if (selectedState.value) {
        loadDistricts(selectedState.value)
        loadLocationStats()
    }
}

const onDistrictChange = () => {
    selectedCity.value = ''
    cities.value = []
    locationStats.value = null
    
    if (selectedDistrict.value) {
        loadCities(selectedDistrict.value)
        loadLocationStats()
    }
}

const onCityChange = () => {
    loadLocationStats()
}

const loadLocationStats = async () => {
    const params = new URLSearchParams()
    if (selectedState.value) params.append('state_id', selectedState.value)
    if (selectedDistrict.value) params.append('district_id', selectedDistrict.value)
    if (selectedCity.value) params.append('city_id', selectedCity.value)
    
    try {
        const response = await fetch(`/api/locations/stats?${params}`)
        const data = await response.json()
        locationStats.value = data.stats
    } catch (error) {
        console.error('Error loading location stats:', error)
    }
}

const saveLocation = async () => {
    try {
        const response = await fetch('/api/locations/set', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                state_id: selectedState.value,
                district_id: selectedDistrict.value,
                city_id: selectedCity.value
            })
        })
        
        if (response.ok) {
            const data = await response.json()
            showSelector.value = false
            // Reload the page to reflect the new location context
            router.reload()
        } else {
            console.error('Error saving location')
        }
    } catch (error) {
        console.error('Error saving location:', error)
    }
}

const resetToDefault = async () => {
    try {
        const response = await fetch('/api/locations/reset', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        
        if (response.ok) {
            showSelector.value = false
            router.reload()
        } else {
            console.error('Error resetting location')
        }
    } catch (error) {
        console.error('Error resetting location:', error)
    }
}

// Lifecycle
onMounted(() => {
    loadStates()
})
</script>

