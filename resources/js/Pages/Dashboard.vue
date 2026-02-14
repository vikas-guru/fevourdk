<script setup>
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- User Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-xl font-bold text-gray-900">Fevourd-K</Link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Welcome, {{ user?.name }}</span>
                        <Link href="/logout" method="post" class="text-sm text-red-600 hover:text-red-800">Logout</Link>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">User Dashboard</h1>
                <p class="mt-2 text-gray-600">Welcome to your Fevourd-K dashboard</p>
            </div>

            <!-- User Info Card -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Profile</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="text-lg font-medium text-gray-900">{{ user?.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-lg font-medium text-gray-900">{{ user?.email }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Browse NGOs</h3>
                    <p class="text-gray-600 mb-4">Discover and support verified NGOs</p>
                    <Link href="/ngos" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-block">
                        Explore NGOs
                    </Link>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">View Campaigns</h3>
                    <p class="text-gray-600 mb-4">Support ongoing campaigns</p>
                    <Link href="/campaigns" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 inline-block">
                        View Campaigns
                    </Link>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your Donations</h3>
                    <p class="text-gray-600 mb-4">Track your donation history</p>
                    <button class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600" disabled>
                        Coming Soon
                    </button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Getting Started</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">1</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-medium text-gray-900">Complete Your Profile</h4>
                            <p class="text-sm text-gray-600">Add more details to your profile to get the most out of the platform</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">2</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-medium text-gray-900">Explore NGOs</h4>
                            <p class="text-sm text-gray-600">Browse verified NGOs and find causes you care about</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">3</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-medium text-gray-900">Make a Difference</h4>
                            <p class="text-sm text-gray-600">Support campaigns and make a positive impact</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
