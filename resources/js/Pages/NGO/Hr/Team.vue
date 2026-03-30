<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    ngo: Object,
    members: Array,
    managers: Array,
    isNgoAdmin: Boolean,
})

const page = usePage()
const editingId = ref(null)
const showAddPanel = ref(false)

const employmentTypes = [
    { value: 'full_time', label: 'Full-time' },
    { value: 'part_time', label: 'Part-time' },
    { value: 'intern', label: 'Intern' },
    { value: 'volunteer', label: 'Volunteer' },
    { value: 'consultant', label: 'Consultant' },
]

const addForm = useForm({
    name: '',
    email: '',
    phone: '',
    workplace_role: 'ngo_staff',
    designation: '',
    job_title: '',
    department: '',
    joined_at: '',
    manager_user_id: '',
    employee_code: '',
    employment_type: '',
    work_location: '',
    send_invite: true,
    password: '',
})

const form = useForm({
    designation: '',
    job_title: '',
    department: '',
    joined_at: '',
    manager_user_id: '',
    workplace_role: 'ngo_staff',
    employee_code: '',
    employment_type: '',
    work_location: '',
})

function primaryWorkplaceRole(m) {
    const names = (m.roles || []).map((r) => r.name)
    if (names.includes('ngo_admin')) return 'ngo_admin'
    if (names.includes('ngo_finance')) return 'ngo_finance'
    return 'ngo_staff'
}

const startEdit = (m) => {
    editingId.value = m.id
    form.designation = m.designation || ''
    form.job_title = m.job_title || ''
    form.department = m.department || ''
    form.joined_at = m.joined_at || ''
    form.manager_user_id = m.manager_user_id || ''
    form.workplace_role = primaryWorkplaceRole(m)
    form.employee_code = m.employee_code || ''
    form.employment_type = m.employment_type || ''
    form.work_location = m.work_location || ''
}

const cancelEdit = () => {
    editingId.value = null
    form.reset()
}

const save = (userId) => {
    form.patch(`/ngo/hr/members/${userId}`, {
        preserveScroll: true,
        onSuccess: () => {
            editingId.value = null
            form.reset()
        },
    })
}

function submitAdd() {
    addForm.post('/ngo/hr/employees', {
        preserveScroll: true,
        onSuccess: () => {
            addForm.reset()
            addForm.send_invite = true
            addForm.workplace_role = 'ngo_staff'
            showAddPanel.value = false
        },
    })
}

function setActive(member, active) {
    if (!active && !confirm('Deactivate this employee? They will not be able to sign in until you reactivate them.')) {
        return
    }
    router.patch(
        `/ngo/hr/members/${member.id}/active`,
        { is_active: active },
        { preserveScroll: true },
    )
}

function managersForEdit(memberId) {
    return (props.managers || []).filter((m) => m.id !== memberId)
}
</script>

<template>
    <AppLayout title="Team">
        <NgoWorkspaceShell :ngo="ngo" current-key="hr-team">
            <div class="mx-auto max-w-6xl space-y-6 pb-10">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Employees</h1>
                        <p class="text-sm text-slate-600">
                            Add people with one workspace login, assign managers and roles, and keep HR fields audit-ready.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-sm font-semibold">
                        <a href="/ngo/hr" class="text-indigo-600 hover:underline">HRMS overview</a>
                        <a href="/ngo/hr/leaves" class="text-slate-600 hover:underline">Leaves →</a>
                        <button
                            v-if="isNgoAdmin"
                            type="button"
                            class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-slate-800"
                            @click="showAddPanel = !showAddPanel"
                        >
                            {{ showAddPanel ? 'Close' : '+ Add employee' }}
                        </button>
                    </div>
                </div>

                <div
                    v-if="isNgoAdmin && showAddPanel"
                    class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50/90 to-white p-5 shadow-sm"
                >
                    <h2 class="text-lg font-bold text-indigo-950">Add employee</h2>
                    <p class="mt-1 text-xs text-indigo-900/80">
                        New accounts get the <strong>same platform login</strong> as your team. Enable email invite so they set their own password, or set a temporary password below.
                    </p>
                    <form class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3" @submit.prevent="submitAdd">
                        <input v-model="addForm.name" required placeholder="Full name *" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <input v-model="addForm.email" type="email" required placeholder="Work email *" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <input v-model="addForm.phone" placeholder="Phone" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <select v-model="addForm.workplace_role" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="ngo_staff">Staff (programme / field)</option>
                            <option value="ngo_finance">Finance (treasury only)</option>
                            <option value="ngo_admin">NGO admin (full workspace)</option>
                        </select>
                        <input v-model="addForm.employee_code" placeholder="Employee ID / code" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <select v-model="addForm.employment_type" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Employment type</option>
                            <option v-for="et in employmentTypes" :key="et.value" :value="et.value">{{ et.label }}</option>
                        </select>
                        <input v-model="addForm.job_title" placeholder="Job title" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <input v-model="addForm.designation" placeholder="Designation" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <input v-model="addForm.department" placeholder="Department / unit" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <input v-model="addForm.work_location" placeholder="Work location (site / city)" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <input v-model="addForm.joined_at" type="date" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <select v-model="addForm.manager_user_id" class="sm:col-span-2 rounded-xl border border-slate-300 px-3 py-2 text-sm lg:col-span-1">
                            <option value="">Reports to (manager)</option>
                            <option v-for="mgr in managers" :key="mgr.id" :value="mgr.id">{{ mgr.name }}</option>
                        </select>
                        <label class="flex items-center gap-2 text-sm text-slate-700 sm:col-span-2 lg:col-span-3">
                            <input v-model="addForm.send_invite" type="checkbox" class="rounded border-slate-300">
                            Send email invite (password reset link)
                        </label>
                        <input
                            v-if="!addForm.send_invite"
                            v-model="addForm.password"
                            type="password"
                            placeholder="Temporary password (min 8 chars)"
                            class="sm:col-span-2 rounded-xl border border-slate-300 px-3 py-2 text-sm"
                            autocomplete="new-password"
                        >
                        <div class="flex flex-wrap gap-2 sm:col-span-2 lg:col-span-3">
                            <button type="submit" :disabled="addForm.processing" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                Create & assign
                            </button>
                        </div>
                    </form>
                    <p v-if="addForm.hasErrors" class="mt-2 text-xs text-red-600">{{ addForm.errors.email || addForm.errors.password || addForm.errors.manager_user_id || addForm.errors.employee_code }}</p>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-600">
                            <tr>
                                <th class="px-3 py-3">Status</th>
                                <th class="px-3 py-3">Code</th>
                                <th class="px-3 py-3">Name</th>
                                <th class="px-3 py-3">Access</th>
                                <th class="px-3 py-3">Job / Dept</th>
                                <th class="px-3 py-3">Joined</th>
                                <th v-if="isNgoAdmin" class="px-3 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template v-for="m in members" :key="m.id">
                                <tr :class="{ 'bg-slate-50/80': !m.is_active }">
                                    <td class="px-3 py-3">
                                        <span
                                            class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase"
                                            :class="m.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'"
                                        >
                                            {{ m.is_active ? 'Active' : 'Suspended' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 font-mono text-xs text-slate-600">{{ m.employee_code || '—' }}</td>
                                    <td class="px-3 py-3">
                                        <p class="font-medium text-slate-900">{{ m.name }}</p>
                                        <p class="text-xs text-slate-500">{{ m.email }}</p>
                                    </td>
                                    <td class="px-3 py-3 text-slate-600">
                                        <span v-for="r in m.roles" :key="r.id" class="mr-1 rounded bg-slate-100 px-1.5 py-0.5 text-xs">{{ r.name }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-slate-600">
                                        <p>{{ m.job_title || m.designation || '—' }}</p>
                                        <p class="text-xs">{{ m.department || '' }}</p>
                                        <p v-if="m.employment_type" class="text-[10px] uppercase text-slate-400">{{ m.employment_type.split('_').join(' ') }}</p>
                                        <p v-if="m.work_location" class="text-xs text-slate-500">{{ m.work_location }}</p>
                                        <p v-if="m.manager_name" class="text-xs text-slate-500">Mgr: {{ m.manager_name }}</p>
                                    </td>
                                    <td class="px-3 py-3 text-slate-600">{{ m.joined_at || '—' }}</td>
                                    <td v-if="isNgoAdmin" class="px-3 py-3">
                                        <div class="flex flex-col gap-1">
                                            <button
                                                v-if="editingId !== m.id"
                                                type="button"
                                                class="text-left text-xs font-semibold text-indigo-600 hover:underline"
                                                @click="startEdit(m)"
                                            >
                                                Edit HR
                                            </button>
                                            <button
                                                v-if="m.is_active"
                                                type="button"
                                                class="text-left text-xs font-semibold text-amber-700 hover:underline"
                                                @click="setActive(m, false)"
                                            >
                                                Deactivate
                                            </button>
                                            <button
                                                v-else
                                                type="button"
                                                class="text-left text-xs font-semibold text-emerald-700 hover:underline"
                                                @click="setActive(m, true)"
                                            >
                                                Reactivate
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="isNgoAdmin && editingId === m.id" class="bg-indigo-50/30">
                                    <td colspan="7" class="px-4 py-4">
                                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                            <select v-model="form.workplace_role" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                                <option value="ngo_staff">Staff</option>
                                                <option value="ngo_finance">Finance</option>
                                                <option value="ngo_admin">NGO admin</option>
                                            </select>
                                            <input v-model="form.employee_code" placeholder="Employee code" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                            <select v-model="form.employment_type" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                                <option value="">Employment type</option>
                                                <option v-for="et in employmentTypes" :key="et.value" :value="et.value">{{ et.label }}</option>
                                            </select>
                                            <input v-model="form.designation" placeholder="Designation" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                            <input v-model="form.job_title" placeholder="Job title" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                            <input v-model="form.department" placeholder="Department" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                            <input v-model="form.work_location" placeholder="Work location" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                            <input v-model="form.joined_at" type="date" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                            <select v-model="form.manager_user_id" class="rounded-xl border border-slate-300 px-3 py-2 text-sm sm:col-span-2 lg:col-span-1">
                                                <option value="">No manager</option>
                                                <option v-for="mgr in managersForEdit(m.id)" :key="mgr.id" :value="mgr.id">{{ mgr.name }}</option>
                                            </select>
                                        </div>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <button
                                                type="button"
                                                class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white"
                                                :disabled="form.processing"
                                                @click="save(m.id)"
                                            >
                                                Save changes
                                            </button>
                                            <button type="button" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs" @click="cancelEdit">Cancel</button>
                                        </div>
                                        <p v-if="form.hasErrors" class="mt-2 text-xs text-red-600">{{ form.errors.workplace_role || form.errors.manager_user_id || form.errors.employee_code }}</p>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 text-xs text-slate-600">
                    <p class="font-semibold text-slate-800">Enterprise notes</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        <li>Staff and admins all use <strong>one URL</strong> (<code class="rounded bg-white px-1">/login</code>) — roles control what they see.</li>
                        <li>Link existing users only if their account is not already tied to another NGO.</li>
                        <li>At least one active <strong>NGO admin</strong> is required; the system blocks demotion or deactivation that would remove the last admin.</li>
                        <li>Pair with <strong>Site visits & field tracker</strong> in the sidebar for ground teams.</li>
                    </ul>
                </div>

                <p v-if="page.props.flash?.success" class="text-sm font-medium text-emerald-600">{{ page.props.flash.success }}</p>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>
