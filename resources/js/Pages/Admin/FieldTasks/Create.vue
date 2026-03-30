<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
    ngos: Array,
})

const staff = ref([])
const loadingStaff = ref(false)

const form = useForm({
    ngo_id: '',
    title: '',
    description: '',
    assignee_id: '',
    priority: 'normal',
    due_at: '',
    task_type: 'field_visit',
})

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

watch(
    () => form.ngo_id,
    async (id) => {
        form.assignee_id = ''
        staff.value = []
        if (!id) return
        loadingStaff.value = true
        try {
            const { data } = await axios.get(`/admin/field-tasks/staff/${id}`, {
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
            })
            staff.value = data.users || []
        } catch (e) {
            console.error(e)
        } finally {
            loadingStaff.value = false
        }
    }
)

const submit = () => {
    form.post('/admin/field-tasks', { preserveScroll: true })
}
</script>

<template>
    <AdminLayout title="Assign field task">
        <div class="mx-auto max-w-xl space-y-6 pb-10">
            <Link href="/admin/field-tasks" class="text-sm font-medium text-indigo-600 hover:underline">← Field tasks</Link>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Assign NGO field task</h1>
                <p class="mt-1 text-sm text-slate-600">Creates a task in the NGO workspace. Staff open it from Field tracker.</p>
            </div>

            <form class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
                <div>
                    <label class="block text-sm font-medium text-slate-700">NGO</label>
                    <select v-model="form.ngo_id" required class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <option disabled value="">Select NGO</option>
                        <option v-for="n in ngos" :key="n.id" :value="n.id">{{ n.name }}</option>
                    </select>
                    <p v-if="form.errors.ngo_id" class="mt-1 text-xs text-red-600">{{ form.errors.ngo_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Title</label>
                    <input v-model="form.title" required class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                    <p v-if="form.errors.title" class="mt-1 text-xs text-red-600">{{ form.errors.title }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Description</label>
                    <textarea v-model="form.description" rows="3" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Assign to staff (optional)</label>
                    <select v-model="form.assignee_id" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm" :disabled="!form.ngo_id || loadingStaff">
                        <option value="">Anyone in NGO</option>
                        <option v-for="u in staff" :key="u.id" :value="u.id">{{ u.name }} — {{ u.email }}</option>
                    </select>
                    <p v-if="loadingStaff" class="mt-1 text-xs text-slate-500">Loading staff…</p>
                    <p v-if="form.errors.assignee_id" class="mt-1 text-xs text-red-600">{{ form.errors.assignee_id }}</p>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Priority</label>
                        <select v-model="form.priority" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Due</label>
                        <input v-model="form.due_at" type="datetime-local" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                    </div>
                </div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-xl bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                >
                    {{ form.processing ? 'Saving…' : 'Create task' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
