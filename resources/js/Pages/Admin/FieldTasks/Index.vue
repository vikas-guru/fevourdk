<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'

defineProps({
    tasks: Object,
    ngos: Array,
    filters: Object,
})

const page = usePage()

function filterNgo(e) {
    const id = e.target.value
    router.get('/admin/field-tasks', id ? { ngo_id: id } : {}, { preserveState: true, replace: true })
}
</script>

<template>
    <AdminLayout title="Field tasks">
        <div class="mx-auto max-w-6xl space-y-6 pb-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">NGO field tasks</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Super admin and state admin can assign work that appears in each NGO field hub.
                    </p>
                </div>
                <Link
                    href="/admin/field-tasks/create"
                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
                >
                    Assign task
                </Link>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <label class="text-sm font-medium text-slate-700">Filter NGO</label>
                <select
                    class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm"
                    :value="filters?.ngo_id || ''"
                    @change="filterNgo"
                >
                    <option value="">All NGOs</option>
                    <option v-for="n in ngos" :key="n.id" :value="n.id">{{ n.name }}</option>
                </select>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-slate-700">NGO</th>
                            <th class="px-4 py-3 font-semibold text-slate-700">Task</th>
                            <th class="px-4 py-3 font-semibold text-slate-700">Status</th>
                            <th class="px-4 py-3 font-semibold text-slate-700">Assignee</th>
                            <th class="px-4 py-3 font-semibold text-slate-700">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="t in tasks.data" :key="t.id">
                            <td class="px-4 py-3">
                                <Link :href="`/admin/ngos/${t.ngo_id}`" class="font-medium text-indigo-600 hover:underline">
                                    {{ t.ngo?.name }}
                                </Link>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-900">{{ t.title }}</p>
                                <p v-if="t.description" class="line-clamp-2 text-xs text-slate-500">{{ t.description }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">{{ t.status }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ t.assignee?.name || '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ new Date(t.created_at).toLocaleString() }}</td>
                        </tr>
                        <tr v-if="!(tasks.data || []).length">
                            <td colspan="5" class="px-4 py-10 text-center text-slate-500">No tasks yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="tasks.links?.length > 3" class="flex flex-wrap gap-1">
                <Link
                    v-for="l in tasks.links"
                    :key="l.label"
                    :href="l.url || '#'"
                    class="rounded-lg border px-3 py-1 text-sm"
                    :class="l.active ? 'border-indigo-600 bg-indigo-50 font-semibold text-indigo-800' : 'border-slate-200 text-slate-600'"
                    preserve-state
                >
                    <span v-html="l.label" />
                </Link>
            </div>

            <p v-if="page.props.flash?.success" class="text-sm font-medium text-emerald-600">{{ page.props.flash.success }}</p>
        </div>
    </AdminLayout>
</template>
