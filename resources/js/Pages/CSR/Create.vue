<template>
    <AppLayout title="CSR Registration - FEVOURD-K">
        <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">CSR Registration</h1>
                    <p class="text-gray-600">Register your company for CSR partnerships and social impact initiatives.</p>
                    
                    <form @submit.prevent="submitForm" class="mt-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input type="text" v-model="form.company_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Email *</label>
                            <input type="email" v-model="form.company_email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Industry Sector *</label>
                            <select v-model="form.industry_sector" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                <option value="">Select Industry Sector</option>
                                <option v-for="(name, key) in (props.sectors || {})" :key="key" :value="key">{{ name }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CSR Domains *</label>
                            <p class="text-sm text-gray-600 mb-3">Select all CSR domains your company focuses on</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <label v-for="domain in availableDomains" :key="domain.key" class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-green-50" :class="{ 'bg-green-100 border-green-500': form.csr_domains.includes(domain.key) }">
                                    <input type="checkbox" :value="domain.key" v-model="form.csr_domains" class="rounded">
                                    <span class="text-sm">{{ domain.name }}</span>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SDG Alignment *</label>
                            <p class="text-sm text-gray-600 mb-3">Select Sustainable Development Goals your CSR initiatives support</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <label v-for="sdg in sdgGoals" :key="sdg.number" class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-green-50" :class="{ 'bg-green-100 border-green-500': form.sdg_goals.includes(sdg.number) }">
                                    <input type="checkbox" :value="sdg.number" v-model="form.sdg_goals" class="rounded">
                                    <span class="text-sm">{{ sdg.number }}. {{ sdg.title }}</span>
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" :disabled="form.processing" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 disabled:bg-gray-400 transition-colors">
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Submit Registration</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    sectors: {
        type: Object,
        default: () => ({})
    },
    impact_areas: {
        type: Object,
        default: () => ({})
    },
    partnership_types: {
        type: Object,
        default: () => ({})
    },
})

const form = useForm({
    company_name: '',
    company_email: '',
    industry_sector: '',
    csr_domains: [],
    sdg_goals: [],
})

const availableDomains = [
    { key: 'education', name: 'Education & Literacy' },
    { key: 'healthcare', name: 'Healthcare & Wellness' },
    { key: 'environment', name: 'Environmental Protection' },
    { key: 'rural_development', name: 'Rural Development' },
    { key: 'women_empowerment', name: 'Women Empowerment' },
    { key: 'child_welfare', name: 'Child Welfare' },
    { key: 'skill_development', name: 'Skill Development' },
    { key: 'clean_water', name: 'Clean Water & Sanitation' },
    { key: 'renewable_energy', name: 'Renewable Energy' },
    { key: 'digital_literacy', name: 'Digital Literacy' },
]

const sdgGoals = [
    { number: 1, title: 'No Poverty' },
    { number: 2, title: 'Zero Hunger' },
    { number: 3, title: 'Good Health & Well-being' },
    { number: 4, title: 'Quality Education' },
    { number: 5, title: 'Gender Equality' },
    { number: 6, title: 'Clean Water & Sanitation' },
    { number: 7, title: 'Affordable Clean Energy' },
    { number: 8, title: 'Decent Work & Economic Growth' },
    { number: 9, title: 'Industry, Innovation & Infrastructure' },
    { number: 10, title: 'Reduced Inequalities' },
    { number: 11, title: 'Sustainable Cities & Communities' },
    { number: 12, title: 'Responsible Consumption & Production' },
    { number: 13, title: 'Climate Action' },
    { number: 14, title: 'Life Below Water' },
    { number: 15, title: 'Life on Land' },
    { number: 16, title: 'Peace, Justice & Strong Institutions' },
    { number: 17, title: 'Partnerships for the Goals' },
]

const submitForm = () => {
    form.post('/csr/register', {
        onSuccess: () => {
            // Redirect will be handled by controller
        },
        onError: () => {
            // Scroll to top of form to show errors
            window.scrollTo({ top: 0, behavior: 'smooth' })
        }
    })
}
</script>
