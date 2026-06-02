<template>
    <AppShell title="CSR Dashboard - FEVOURD-K">
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">CSR Dashboard</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage your CSR partnerships and social impact initiatives</p>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="exportReport('csv')" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export CSV
                        </button>
                        <button @click="exportReport('pdf')" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Export PDF
                        </button>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">C</span>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Campaigns</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ stats.total_campaigns }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">A</span>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Verification Status</dt>
                                        <dd class="text-lg font-semibold text-gray-900" v-html="company.verification_status_badge"></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">N</span>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">NGO Partners</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ stats.ngos_partnered }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">₹</span>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Funds Distributed</dt>
                                        <dd class="text-lg font-semibold text-gray-900">₹{{ NumberFormat(stats.total_funds_distributed).toLocaleString('en-IN') }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Campaigns -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Campaigns</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NGO Partner</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="campaign in recent_campaigns" :key="campaign.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ campaign.title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ campaign.ngo?.name || 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-full">
                                            <div class="flex items-center justify-between mb-1">
                                                <div class="text-sm text-gray-600">₹{{ NumberFormat(campaign.raised_amount).toLocaleString('en-IN') }}</div>
                                                <div class="text-sm text-gray-500">/ ₹{{ NumberFormat(campaign.target_amount).toLocaleString('en-IN') }}</div>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full" :style="{ width: (campaign.raised_amount / campaign.target_amount * 100) + '%' }"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <Link :href="`/campaigns/${campaign.id}/edit`" class="text-green-600 hover:text-green-900">Edit</Link>
                                        <span class="mx-2">|</span>
                                        <Link :href="`/campaigns/${campaign.id}/analytics`" class="text-blue-600 hover:text-blue-900">Analytics</Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Year-wise Analytics & Compliance Reports -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Year-wise Analytics -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Year-wise CSR Analytics</h2>
                        <div class="space-y-4">
                            <div v-for="year in analytics" :key="year.year" class="border-b pb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-medium text-gray-900">{{ year.year }} Analytics</h3>
                                    <span class="text-sm text-gray-500">{{ year.quarters.length }} Quarters</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Total Funds:</span>
                                        <span class="font-medium">₹{{ formatNumber(year.total_funds) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Utilized:</span>
                                        <span class="font-medium">₹{{ formatNumber(year.utilized_funds) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">NGOs:</span>
                                        <span class="font-medium">{{ year.ngos_partnered }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Beneficiaries:</span>
                                        <span class="font-medium">{{ formatNumber(year.beneficiaries) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compliance Reports -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Compliance Reports</h2>
                        <div class="space-y-3">
                            <div v-for="report in complianceReports" :key="report.id" class="border rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ report.report_type }}</h3>
                                        <p class="text-sm text-gray-500">{{ report.report_period }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-semibold" :class="report.complianceScoreColor">
                                            {{ report.compliance_score }}%
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            Grade: {{ report.compliance_grade }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="getStatusClass(report.status)">
                                        {{ report.status }}
                                    </span>
                                    <button @click="downloadReport(report)" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Download Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CSR Domains -->
                <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">CSR Domains</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="domain in domains" :key="domain.id" class="border rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-medium text-gray-900">{{ domain.domain_name }}</h3>
                                <span v-html="domain.verification_status_badge"></span>
                            </div>
                            <a v-if="domain.domain_url" :href="domain.domain_url" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                Visit Domain
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>

<script setup>
import { ref } from 'vue'
import AppShell from '@/Layouts/AppShell.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    company: Object,
    stats: Object,
    recent_campaigns: Array,
    analytics: Array,
    complianceReports: Array,
    domains: Array,
})

const formatNumber = (num) => {
    return new Intl.NumberFormat('en-IN').format(num)
}

const getStatusClass = (status) => {
    const classes = {
        draft: 'bg-gray-100 text-gray-800',
        submitted: 'bg-yellow-100 text-yellow-800',
        reviewed: 'bg-green-100 text-green-800',
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const exportReport = (format) => {
    // Implementation for CSV/PDF export
    console.log(`Exporting ${format} report...`)
    
    if (format === 'csv') {
        // Generate CSV download
        const csvContent = generateCSV()
        downloadFile(csvContent, 'csr-report.csv', 'text/csv')
    } else if (format === 'pdf') {
        // Generate PDF download
        generatePDF()
    }
}

const downloadReport = (report) => {
    // Implementation for downloading individual reports
    console.log('Downloading report:', report.id)
    // Generate audit-ready format
    const reportData = generateAuditReport(report)
    downloadFile(reportData, `compliance-report-${report.id}.pdf`, 'application/pdf')
}

const generateCSV = () => {
    // Generate CSV content for export
    let csv = 'Year,Quarter,Total Funds,Utilized Funds,NGOs Partnered,Beneficiaries\n'
    
    props.analytics.forEach(year => {
        year.quarters.forEach(quarter => {
            csv += `${year.year},${quarter.quarter},${quarter.total_funds_distributed},${quarter.funds_utilized},${quarter.ngos_partnered},${quarter.beneficiaries_reached}\n`
        })
    })
    
    return csv
}

const generatePDF = () => {
    // Implementation for PDF generation
    console.log('Generating PDF report...')
}

const generateAuditReport = (report) => {
    // Generate audit-ready report format
    return {
        ...report,
        generated_at: new Date().toISOString(),
        audit_trail: [
            { action: 'created', timestamp: report.created_at, user: 'system' },
            { action: 'submitted', timestamp: report.submitted_at, user: 'current_user' },
            { action: 'reviewed', timestamp: report.reviewed_at, user: 'reviewer' }
        ]
    }
}

const downloadFile = (content, filename, contentType) => {
    const blob = new Blob([content], { type: contentType })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = filename
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    window.URL.revokeObjectURL(url)
}
</script>
