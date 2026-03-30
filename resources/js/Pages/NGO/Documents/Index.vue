<template>
    <AppLayout title="NGO documents — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="documents">
            <h1 class="text-2xl font-bold text-slate-900">Documents</h1>
            <p class="mt-1 text-sm text-slate-600">Registration and compliance files for verification.</p>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Type</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Updated</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="doc in documents" :key="doc.id" class="border-t border-slate-100">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ doc.document_type }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">{{ doc.status }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ formatDate(doc.updated_at) }}</td>
                            <td class="px-4 py-3 text-right">
                                <a
                                    v-if="doc.file_path"
                                    :href="'/storage/' + doc.file_path"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-700"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >Open</a>
                                <span v-else class="text-slate-400">—</span>
                            </td>
                        </tr>
                        <tr v-if="!documents?.length">
                            <td colspan="4" class="px-4 py-10 text-center text-slate-500">No documents uploaded.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'

defineProps({
    ngo: { type: Object, required: true },
    documents: { type: Array, default: () => [] },
})

function formatDate(iso) {
    if (!iso) {
        return '—'
    }
    return new Date(iso).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>
