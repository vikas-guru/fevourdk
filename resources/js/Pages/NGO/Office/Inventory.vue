<template>
    <AppLayout title="Office & assets — FEVOURD-K">
        <NgoWorkspaceShell :ngo="ngo" current-key="office-inventory">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between" data-tour="intro">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Office & assets</h1>
                    <p class="mt-1 max-w-2xl text-sm text-slate-600">
                        Track fixed assets (equipment, furniture, vehicles) and consumables (stationery, supplies). Use reorder levels on consumables to flag low stock.
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Fixed assets</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ summary.fixed_assets }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Consumables</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ summary.consumables }}</p>
                </div>
                <div class="rounded-2xl border border-amber-200/80 bg-amber-50/80 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-800">Low stock</p>
                    <p class="mt-1 text-2xl font-bold text-amber-950">{{ summary.low_stock }}</p>
                    <p class="mt-1 text-[11px] text-amber-900/80">At or below reorder level</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Book value (est.)</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">₹{{ Number(summary.book_value_total || 0).toLocaleString('en-IN') }}</p>
                    <p class="mt-1 text-[11px] text-slate-500">Sum of current value on assets</p>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-600">Type</label>
                    <select
                        v-model="filterKind"
                        class="mt-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm"
                        @change="applyFilters"
                    >
                        <option value="">All</option>
                        <option value="fixed_asset">Fixed assets</option>
                        <option value="consumable">Consumables</option>
                    </select>
                </div>
                <div class="min-w-[12rem] flex-1 sm:max-w-md">
                    <label class="block text-xs font-semibold text-slate-600">Search</label>
                    <input
                        v-model="filterQ"
                        type="search"
                        placeholder="Name, tag, serial, location…"
                        class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm"
                        @keyup.enter="applyFilters"
                    >
                </div>
                <button
                    type="button"
                    class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-900"
                    @click="applyFilters"
                >
                    Apply
                </button>
            </div>

            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-2 border-b border-slate-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-bold text-slate-900">{{ editingId ? 'Edit item' : 'Add item' }}</h2>
                    <button
                        v-if="editingId"
                        type="button"
                        class="text-sm font-semibold text-slate-600 hover:text-slate-900"
                        @click="cancelEdit"
                    >
                        Cancel edit
                    </button>
                </div>
                <form class="mt-4 space-y-4" @submit.prevent="submitForm">
                    <div
                        v-if="Object.keys(form.errors).length"
                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
                    >
                        <p class="font-semibold">Please fix the highlighted fields.</p>
                        <ul class="mt-2 list-inside list-disc text-xs">
                            <li v-for="(msg, key) in form.errors" :key="key">{{ msg }}</li>
                        </ul>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Kind</label>
                            <select v-model="form.kind" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" required>
                                <option value="fixed_asset">Fixed asset</option>
                                <option value="consumable">Consumable / office supply</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-slate-600">Name</label>
                            <input v-model="form.name" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Category</label>
                            <input
                                v-model="form.category"
                                type="text"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
                                placeholder="e.g. IT, Furniture, Stationery"
                            >
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Quantity</label>
                            <input v-model="form.quantity" type="number" step="0.01" min="0" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Unit</label>
                            <input v-model="form.unit" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="pcs, box, kg…">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Reorder level</label>
                            <input v-model="form.reorder_level" type="number" step="0.01" min="0" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="Consumables only">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Asset tag / internal code</label>
                            <input v-model="form.asset_tag" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Serial number</label>
                            <input v-model="form.serial_number" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Condition</label>
                            <select v-model="form.asset_condition" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" required>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="retired">Retired</option>
                                <option value="depleted">Depleted</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Storage location</label>
                            <input
                                v-model="form.storage_location"
                                type="text"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
                                placeholder="Room, shelf, site"
                            >
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Custodian</label>
                            <select v-model="form.custodian_user_id" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                <option value="">— None —</option>
                                <option v-for="m in teamMembers" :key="m.id" :value="m.id">{{ m.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Purchase date</label>
                            <input v-model="form.purchase_date" type="date" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Purchase amount (₹)</label>
                            <input v-model="form.purchase_amount" type="number" step="0.01" min="0" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Current value estimate (₹)</label>
                            <input v-model="form.current_value_estimate" type="number" step="0.01" min="0" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="Assets / depreciation">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Supplier</label>
                            <input v-model="form.supplier_name" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Invoice / PO ref.</label>
                            <input v-model="form.invoice_reference" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Warranty ends</label>
                            <input v-model="form.warranty_expires_at" type="date" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-600">Description</label>
                        <textarea v-model="form.description" rows="2" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-600">Notes</label>
                        <textarea v-model="form.notes" rows="2" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="Maintenance, disposal, audit notes…" />
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
                            :disabled="form.processing"
                        >
                            {{ editingId ? 'Save changes' : 'Add to inventory' }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" data-tour="inventory">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Item</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Type</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Qty</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Location</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-700">Custodian</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700">Value (est.)</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-700"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="row in items.data"
                                :key="row.id"
                                class="border-t border-slate-100"
                                :class="{ 'bg-amber-50/50': row.low_stock }"
                            >
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-900">{{ row.name }}</p>
                                    <p v-if="row.category" class="text-xs text-slate-500">{{ row.category }}</p>
                                    <p v-if="row.low_stock" class="mt-0.5 text-[11px] font-semibold text-amber-800">Low stock</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ kindLabel(row.kind) }}</td>
                                <td class="px-4 py-3 text-slate-700">
                                    {{ row.quantity }}{{ row.unit ? ' ' + row.unit : '' }}
                                </td>
                                <td class="max-w-[10rem] truncate px-4 py-3 text-slate-600" :title="row.storage_location">{{ row.storage_location || '—' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ row.custodian_name || '—' }}</td>
                                <td class="px-4 py-3 text-right text-slate-700">
                                    <span v-if="row.kind === 'fixed_asset' && row.current_value_estimate != null">
                                        ₹{{ Number(row.current_value_estimate).toLocaleString('en-IN') }}
                                    </span>
                                    <span v-else class="text-slate-400">—</span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <button type="button" class="text-xs font-semibold text-blue-600 hover:text-blue-800" @click="startEdit(row)">
                                        Edit
                                    </button>
                                    <button type="button" class="ml-3 text-xs font-semibold text-red-600 hover:text-red-800" @click="remove(row)">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!items.data?.length">
                                <td colspan="7" class="px-4 py-12 text-center text-slate-500">
                                    No items yet. Add fixed assets and office supplies above.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="items.prev_page_url || items.next_page_url" class="flex justify-end gap-2 border-t border-slate-100 px-4 py-3">
                    <Link
                        v-if="items.prev_page_url"
                        :href="items.prev_page_url"
                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                        preserve-scroll
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="items.next_page_url"
                        :href="items.next_page_url"
                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                        preserve-scroll
                    >
                        Next
                    </Link>
                </div>
            </div>
            <DashboardTour ref="tourRef" :steps="steps" :storage-key="storageKey" auto-start />
        </NgoWorkspaceShell>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import DashboardTour from '@/Components/NGO/DashboardTour.vue'
import { useNgoTour } from '@/ngo/useNgoTour'

const { tourRef, steps, storageKey } = useNgoTour('office')

const props = defineProps({
    ngo: { type: Object, required: true },
    items: { type: Object, required: true },
    teamMembers: { type: Array, default: () => [] },
    summary: {
        type: Object,
        default: () => ({ fixed_assets: 0, consumables: 0, low_stock: 0, book_value_total: 0 }),
    },
    filters: { type: Object, default: () => ({ kind: '', q: '' }) },
})

const filterKind = ref(props.filters.kind || '')
const filterQ = ref(props.filters.q || '')
const editingId = ref(null)

const emptyForm = () => ({
    kind: 'fixed_asset',
    name: '',
    description: '',
    category: '',
    asset_tag: '',
    serial_number: '',
    quantity: '1',
    unit: '',
    reorder_level: '',
    purchase_date: '',
    purchase_amount: '',
    current_value_estimate: '',
    asset_condition: 'good',
    storage_location: '',
    custodian_user_id: '',
    supplier_name: '',
    invoice_reference: '',
    warranty_expires_at: '',
    notes: '',
})

const form = useForm(emptyForm())

function kindLabel(k) {
    if (k === 'fixed_asset') {
        return 'Fixed asset'
    }
    if (k === 'consumable') {
        return 'Consumable'
    }
    return k
}

function applyFilters() {
    router.get('/ngo/office/inventory', { kind: filterKind.value || undefined, q: filterQ.value || undefined }, {
        preserveState: true,
        replace: true,
    })
}

function cancelEdit() {
    editingId.value = null
    form.reset()
    form.clearErrors()
    Object.assign(form, emptyForm())
}

function startEdit(row) {
    editingId.value = row.id
    form.kind = row.kind
    form.name = row.name
    form.description = row.description || ''
    form.category = row.category || ''
    form.asset_tag = row.asset_tag || ''
    form.serial_number = row.serial_number || ''
    form.quantity = String(row.quantity)
    form.unit = row.unit || ''
    form.reorder_level = row.reorder_level != null ? String(row.reorder_level) : ''
    form.purchase_date = row.purchase_date || ''
    form.purchase_amount = row.purchase_amount != null ? String(row.purchase_amount) : ''
    form.current_value_estimate = row.current_value_estimate != null ? String(row.current_value_estimate) : ''
    form.asset_condition = row.asset_condition
    form.storage_location = row.storage_location || ''
    form.custodian_user_id = row.custodian_user_id || ''
    form.supplier_name = row.supplier_name || ''
    form.invoice_reference = row.invoice_reference || ''
    form.warranty_expires_at = row.warranty_expires_at || ''
    form.notes = row.notes || ''
    form.clearErrors()
    window.scrollTo({ top: 0, behavior: 'smooth' })
}

function submitForm() {
    const opts = {
        preserveScroll: true,
        onSuccess: () => {
            editingId.value = null
            form.reset()
            Object.assign(form, emptyForm())
        },
    }

    if (editingId.value) {
        form.put(`/ngo/office/inventory/${editingId.value}`, opts)
    } else {
        form.post('/ngo/office/inventory', opts)
    }
}

function remove(row) {
    if (!confirm(`Remove “${row.name}” from inventory?`)) {
        return
    }
    router.delete(`/ngo/office/inventory/${row.id}`, { preserveScroll: true })
}
</script>
