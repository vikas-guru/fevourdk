<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    categories: Array,
})

const form = useForm({
    title: '',
    slug: '',
    description: '',
    content: '',
    category: '',
    image: null,
    is_featured: false,
    is_active: true,
    sort_order: 0,
})

const submit = () => {
    form.post('/admin/programs', {
        onSuccess: () => {
            form.reset()
        },
    })
}

const handleImageChange = (event) => {
    form.image = event.target.files[0]
}
</script>

<template>
    <AdminLayout>
        <div class="max-w-4xl">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Page Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Create New Program</h1>
                    <p class="mt-1 text-sm text-gray-600">Add a new program to your organization's activities</p>
                </div>

                <!-- Basic Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.title }"
                                required
                            />
                            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input
                                id="slug"
                                v-model="form.slug"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.slug }"
                                placeholder="auto-generated-from-title"
                            />
                            <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                            <select
                                id="category"
                                v-model="form.category"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.category }"
                                required
                            >
                                <option value="">Select a category</option>
                                <option v-for="category in categories" :key="category" :value="category">
                                    {{ category }}
                                </option>
                            </select>
                            <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">{{ form.errors.category }}</p>
                        </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.description }"
                                required
                            ></textarea>
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                        </div>

                    <div class="mt-6">
                        <label for="content" class="block text-sm font-medium text-gray-700">Detailed Content</label>
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="8"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.content }"
                                placeholder="Enter detailed program information, activities, impact, etc."
                            ></textarea>
                            <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
                        </div>
                </div>

                <!-- Media -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Media</h2>
                    
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Program Image</label>
                        <input
                            id="image"
                            type="file"
                            @change="handleImageChange"
                            accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-gray-300 file:bg-gray-50 file:text-gray-900 hover:file:bg-gray-100"
                            :class="{ 'border-red-500': form.errors.image }"
                        />
                        <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">{{ form.errors.image }}</p>
                        <p class="mt-1 text-sm text-gray-500">Recommended size: 1200x800px. Max file size: 2MB.</p>
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Settings</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                            <input
                                id="sort_order"
                                v-model.number="form.sort_order"
                                type="number"
                                min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.sort_order }"
                            />
                            <p v-if="form.errors.sort_order" class="mt-1 text-sm text-red-600">{{ form.errors.sort_order }}</p>
                        </div>

                        <div class="flex items-center">
                            <input
                                id="is_featured"
                                v-model="form.is_featured"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            />
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700">Featured Program</label>
                        </div>

                        <div class="flex items-center">
                            <input
                                id="is_active"
                                v-model="form.is_active"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            />
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="/admin/programs" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Creating...' : 'Create Program' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
