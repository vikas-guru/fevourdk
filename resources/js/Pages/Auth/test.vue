<template>
    <AppLayout title="Test Page">
        <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
            <div class="max-w-md w-full space-y-8">
                <h1 class="text-3xl font-bold text-center text-gray-900">Navigation Test</h1>
                <p class="text-center text-gray-600">If you see this, navigation is working!</p>
                <div class="text-center">
                    <Link href="/login" class="bg-blue-600 text-white px-4 py-2 rounded-md">Test Login</Link>
                    <br><br>
                    <Link href="/register" class="bg-green-600 text-white px-4 py-2 rounded-md">Test Register</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>
