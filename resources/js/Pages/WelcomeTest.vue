<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-100 flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-blue-600 mb-4">FEVOURD-K Test Page</h1>
                <p class="text-lg text-gray-700 mb-4">This is a test to verify the Vue component is working.</p>
                <div class="bg-blue-600 text-white px-6 py-3 rounded-lg">
                    Stats: {{ stats.ngos || 'Loading...' }}
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    stats: {
        type: Object,
        required: true
    }
})
</script>
