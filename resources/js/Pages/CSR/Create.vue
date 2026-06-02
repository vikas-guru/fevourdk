<template>
    <AppLayout title="CSR Registration - FEVOURD-K" :hide-chrome-mobile="true">
        <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-6 sm:py-10">
            <div class="max-w-3xl mx-auto px-4 sm:px-6">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="mx-auto mb-3 w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 grid place-items-center shadow-lg shadow-green-600/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-[0.15em] text-emerald-600">Corporate · CSR</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1.5">Register your company</h1>
                    <p class="text-gray-500 mt-2 text-sm sm:text-base max-w-md mx-auto">Partner with verified NGOs, fund campaigns, and track your social-impact investments end to end.</p>
                </div>

                <form @submit.prevent="submitForm" class="reg-form bg-white rounded-3xl shadow-xl shadow-slate-300/30 ring-1 ring-slate-100 p-5 sm:p-8 space-y-5">
                    <div>
                        <label class="reg-label">Company Name *</label>
                        <input type="text" v-model="form.company_name" class="w-full" placeholder="e.g. TechCorp India Pvt Ltd" required>
                    </div>

                    <div>
                        <label class="reg-label">Company Email *</label>
                        <input type="email" v-model="form.company_email" class="w-full" placeholder="csr@company.com" required>
                    </div>

                    <div>
                        <label class="reg-label">Industry Sector *</label>
                        <select v-model="form.industry_sector" class="w-full" required>
                            <option value="">Select industry sector</option>
                            <option v-for="(name, key) in (props.sectors || {})" :key="key" :value="key">{{ name }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="reg-label">CSR Domains *</label>
                        <p class="text-xs text-gray-500 mb-2.5 -mt-1">Tap all the areas your company focuses on</p>
                        <div class="grid grid-cols-2 gap-2.5">
                            <label
                                v-for="domain in availableDomains" :key="domain.key"
                                class="flex items-center gap-2 px-3 py-2.5 rounded-xl border cursor-pointer select-none transition-all active:scale-[.98]"
                                :class="form.csr_domains.includes(domain.key) ? 'bg-green-600 border-green-600 text-white shadow-md shadow-green-600/25' : 'bg-white border-gray-200 text-gray-700 hover:border-green-300'"
                            >
                                <input type="checkbox" :value="domain.key" v-model="form.csr_domains" class="sr-only">
                                <span class="grid place-items-center w-4 h-4 rounded-full shrink-0 border" :class="form.csr_domains.includes(domain.key) ? 'bg-white border-white' : 'border-gray-300'">
                                    <svg v-if="form.csr_domains.includes(domain.key)" class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </span>
                                <span class="text-sm font-medium leading-tight">{{ domain.name }}</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="reg-label">SDG Alignment *</label>
                        <p class="text-xs text-gray-500 mb-2.5 -mt-1">Select the Sustainable Development Goals you support</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <label
                                v-for="sdg in sdgGoals" :key="sdg.number"
                                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl border cursor-pointer select-none transition-all active:scale-[.98]"
                                :class="form.sdg_goals.includes(sdg.number) ? 'bg-green-600 border-green-600 text-white shadow-md shadow-green-600/25' : 'bg-white border-gray-200 text-gray-700 hover:border-green-300'"
                            >
                                <input type="checkbox" :value="sdg.number" v-model="form.sdg_goals" class="sr-only">
                                <span class="grid place-items-center w-6 h-6 rounded-lg shrink-0 text-xs font-bold" :class="form.sdg_goals.includes(sdg.number) ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'">{{ sdg.number }}</span>
                                <span class="text-sm font-medium leading-tight">{{ sdg.title }}</span>
                            </label>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full px-6 py-3.5 bg-green-600 text-white rounded-xl font-semibold shadow-lg shadow-green-600/30 hover:bg-green-700 active:scale-95 disabled:bg-gray-300 disabled:shadow-none disabled:cursor-not-allowed transition-all"
                    >
                        <span v-if="form.processing">Processing…</span>
                        <span v-else>Submit registration</span>
                    </button>
                </form>

                <p class="text-center mt-6">
                    <Link href="/register" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back to role selection</Link>
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
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

<style scoped>
.reg-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}

/* App-grade text/email/select fields */
.reg-form :deep(input[type='text']),
.reg-form :deep(input[type='email']),
.reg-form :deep(select) {
    width: 100%;
    border: 1px solid #e6eaf1;
    border-radius: 0.85rem;
    background-color: #f8fafc;
    font-size: 16px;            /* prevents iOS zoom-on-focus */
    padding: 0.8rem 1rem;
    color: #0f172a;
    transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
}
.reg-form :deep(input:focus),
.reg-form :deep(select:focus) {
    outline: none;
    background-color: #fff;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.16);
}
.reg-form :deep(input::placeholder) { color: #9ca3af; }
</style>
