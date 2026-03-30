<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NgoWorkspaceShell from '@/Components/NGO/NgoWorkspaceShell.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    ngo: { type: Object, required: true },
    isNgoAdmin: { type: Boolean, default: false },
})

const modules = [
    {
        title: 'Team directory',
        desc: 'Add employees, assign NGO admin vs staff, manager line, employee ID, employment type, activate or suspend access.',
        href: '/ngo/hr/team',
        icon: '👥',
    },
    {
        title: 'Leave management',
        desc: 'Configure leave types, submit requests, and approve or reject as NGO admin.',
        href: '/ngo/hr/leaves',
        icon: '📅',
    },
    {
        title: 'Site visits & field tasks',
        desc: 'Plan visits, assign staff, track live routes and completed field work from one place.',
        href: '/ngo/field',
        icon: '📍',
    },
    {
        title: 'Field tracker (mobile)',
        desc: 'GPS trail, speed, and distance — same login; bookmark on staff phones as a simple PWA.',
        href: '/ngo/field/app',
        icon: '🛰',
    },
    {
        title: 'Finance & cashbook',
        desc: 'Treasury hub, every rupee in/out, outbound payments to staff or vendors, banking config.',
        href: '/ngo/finance',
        icon: '₹',
    },
    {
        title: 'Expense claims',
        desc: 'Staff submit receipts; finance or NGO admin approves — posts automatically to the ledger.',
        href: '/ngo/finance/claims',
        icon: '🧾',
    },
    {
        title: 'Post programme updates',
        desc: 'Share impact stories to the public community feed — same account, no extra app.',
        href: '/ngo/post-update',
        icon: '＋',
    },
]

const adminOnly = [
    {
        title: 'Workplace security',
        desc: 'Trusted IPs and login geo rules for your organisation.',
        href: '/ngo/workplace-security',
        icon: '🔒',
    },
]
</script>

<template>
    <AppLayout title="HRMS">
        <NgoWorkspaceShell :ngo="ngo" current-key="hr-index">
            <div class="mx-auto max-w-4xl space-y-8 pb-12">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">HRMS & people operations</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
                        Staff use the <strong>same FEVOURD-K login</strong> (NGO admin or NGO staff). From here they can
                        request leave, run site visits, track field routes, file expenses, and publish updates — all tied
                        to your organisation.
                    </p>
                </div>

                <div class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50/80 to-white p-5 shadow-sm">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-indigo-900">Typical use cases</h2>
                    <ul class="mt-3 list-inside list-disc space-y-1.5 text-sm text-indigo-950/85">
                        <li>Onboard employees from <strong>Team</strong>: invite by email or set a temp password; assign <code class="rounded bg-white/80 px-1 text-xs">ngo_staff</code> or <code class="rounded bg-white/80 px-1 text-xs">ngo_admin</code>.</li>
                        <li>Programme officers log site visits and GPS trails during field days.</li>
                        <li>Leave policies per NGO; staff apply; admins approve in one queue.</li>
                        <li>Field-linked expenses submitted as claims, then reflected in the ledger after approval.</li>
                        <li>Communications lead posts public feed updates without a separate social tool.</li>
                    </ul>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <Link
                        v-for="m in modules"
                        :key="m.href"
                        :href="m.href"
                        class="group flex gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-indigo-200 hover:shadow-md"
                    >
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-lg group-hover:bg-indigo-50">{{ m.icon }}</span>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-slate-900 group-hover:text-indigo-800">{{ m.title }}</h3>
                            <p class="mt-1 text-xs leading-relaxed text-slate-600">{{ m.desc }}</p>
                        </div>
                    </Link>
                </div>

                <div v-if="isNgoAdmin" class="space-y-3">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500">Administration</h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <Link
                            v-for="m in adminOnly"
                            :key="m.href"
                            :href="m.href"
                            class="group flex gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-slate-300"
                        >
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-lg">{{ m.icon }}</span>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-slate-900">{{ m.title }}</h3>
                                <p class="mt-1 text-xs text-slate-600">{{ m.desc }}</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </NgoWorkspaceShell>
    </AppLayout>
</template>
