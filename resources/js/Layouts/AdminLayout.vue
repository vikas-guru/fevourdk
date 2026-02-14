<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { 
    HomeIcon, 
    BuildingOfficeIcon, 
    UsersIcon, 
    GiftIcon, 
    Cog6ToothIcon,
    ArrowRightOnRectangleIcon,
    ClipboardDocumentListIcon,
    BanknotesIcon,
    DocumentTextIcon
} from '@heroicons/vue/24/outline'

const page = usePage()
const user = computed(() => page.props.auth?.user || {})

const navigation = [
    { name: 'Dashboard', href: '/admin/dashboard', icon: HomeIcon },
    { name: 'Programs & Activities', href: '/admin/programs', icon: DocumentTextIcon },
    { name: 'Users', href: '/admin/users', icon: UsersIcon },
    { name: 'NGOs', href: '/admin/ngos', icon: BuildingOfficeIcon },
    { name: 'Campaigns', href: '/admin/campaigns', icon: GiftIcon },
    { name: 'Donations', href: '/admin/donations', icon: BanknotesIcon },
    { name: 'Settings', href: '/admin/settings', icon: Cog6ToothIcon },
]

const currentRoute = computed(() => {
    return page.props.ziggy?.location || window.location.pathname
})

const isActive = (routePath) => {
    return currentRoute.value.includes(routePath)
}
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg lg:block lg:transform lg:translate-x-0">
            <div class="flex h-full flex-col">
                <!-- Logo -->
                <div class="flex h-16 items-center justify-center border-b border-gray-200 bg-gradient-to-r from-blue-600 to-purple-600">
                    <Link href="/admin/dashboard" class="flex items-center space-x-2">
                        <div class="h-8 w-8 rounded-lg bg-white p-1">
                            <div class="h-full w-full rounded bg-gradient-to-r from-blue-500 to-purple-500"></div>
                        </div>
                        <span class="text-xl font-bold text-white">Fevourd-K Admin</span>
                    </Link>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-1 px-3 py-4">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            isActive(item.href)
                                ? 'bg-blue-50 border-r-2 border-blue-600 text-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                            'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors'
                        ]"
                    >
                        <component
                            :is="item.icon"
                            :class="[
                                isActive(item.href)
                                    ? 'text-blue-600'
                                    : 'text-gray-400 group-hover:text-gray-500',
                                'mr-3 h-5 w-5 flex-shrink-0'
                            ]"
                        />
                        {{ item.name }}
                    </Link>
                </nav>

                <!-- User Section -->
                <div class="border-t border-gray-200 p-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">
                                {{ user?.name?.charAt(0)?.toUpperCase() || 'A' }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ user?.name || 'Admin User' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ user?.email || 'admin@favoredk.org' }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <Link
                            href="/logout"
                            method="post"
                            class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900 transition-colors"
                        >
                            <ArrowRightOnRectangleIcon class="mr-3 h-4 w-4" />
                            Logout
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center">
                            <h1 class="text-xl font-semibold text-gray-900">Admin Dashboard</h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">
                                Welcome back, {{ user?.name || 'Admin' }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
