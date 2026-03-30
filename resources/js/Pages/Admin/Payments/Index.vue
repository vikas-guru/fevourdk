<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
    paymentFlags: { type: Object, default: () => ({}) },
})

const page = usePage()

const form = useForm({
    payment_razorpay_enabled: !!props.paymentFlags?.payment_razorpay_enabled,
    payment_upi_enabled: !!props.paymentFlags?.payment_upi_enabled,
    payment_phonepe_enabled: !!props.paymentFlags?.payment_phonepe_enabled,
    payment_stripe_enabled: !!props.paymentFlags?.payment_stripe_enabled,
})

const submit = () => {
    form.put('/admin/payments', { preserveScroll: true })
}
</script>

<template>
    <AdminLayout title="Payment methods">
        <div class="mx-auto max-w-3xl space-y-8 pb-10">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Public donation checkout</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Control which options appear on <code class="rounded bg-slate-100 px-1">/donate</code>. Razorpay &amp; UPI are wired in the UI; PhonePe &amp; Stripe can be enabled when you complete gateway integration.
                </p>
            </div>

            <form class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
                <div class="space-y-4">
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-200">
                        <input v-model="form.payment_razorpay_enabled" type="checkbox" class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <p class="font-semibold text-slate-900">Razorpay</p>
                            <p class="text-sm text-slate-600">Card, netbanking, wallets via Razorpay checkout (requires valid key in app config).</p>
                        </div>
                    </label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-200">
                        <input v-model="form.payment_upi_enabled" type="checkbox" class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <p class="font-semibold text-slate-900">UPI</p>
                            <p class="text-sm text-slate-600">UPI path in donate flow (server process endpoint).</p>
                        </div>
                    </label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-200">
                        <input v-model="form.payment_phonepe_enabled" type="checkbox" class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <p class="font-semibold text-slate-900">PhonePe</p>
                            <p class="text-sm text-slate-600">Show option when you add PhonePe integration (placeholder for now).</p>
                        </div>
                    </label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-200">
                        <input v-model="form.payment_stripe_enabled" type="checkbox" class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <p class="font-semibold text-slate-900">Stripe</p>
                            <p class="text-sm text-slate-600">International cards when Stripe is integrated.</p>
                        </div>
                    </label>
                </div>

                <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
                    <button
                        type="submit"
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Saving…' : 'Save payment options' }}
                    </button>
                    <p v-if="page.props.flash?.success" class="text-sm font-medium text-emerald-600">{{ page.props.flash.success }}</p>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
