<template>
    <Head title="Create Article" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Article
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <form
                        @submit.prevent="
                            form.post(route('knowledge-base.articles.store'))
                        "
                    >
                        <!-- Category -->
                        <div class="mb-4">
                            <label
                                for="category_id"
                                class="block text-sm font-medium text-gray-700"
                                >Category</label
                            >
                            <select
                                id="category_id"
                                v-model="form.category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required
                            >
                                <option value="">Select a category</option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.category_id"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.category_id }}
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label
                                for="title"
                                class="block text-sm font-medium text-gray-700"
                                >Title</label
                            >
                            <input
                                type="text"
                                id="title"
                                v-model="form.title"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required
                            />
                            <div
                                v-if="form.errors.title"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label
                                for="content"
                                class="block text-sm font-medium text-gray-700"
                                >Content</label
                            >
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="10"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required
                            ></textarea>
                            <div
                                v-if="form.errors.content"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.content }}
                            </div>
                        </div>

                        <!-- Published Status -->
                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input
                                    type="checkbox"
                                    v-model="form.is_published"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <span class="ml-2 text-sm text-gray-700"
                                    >Published</span
                                >
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <Link
                                :href="route('knowledge-base.index')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                :disabled="form.processing"
                            >
                                Create Article
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    categories: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    category_id: route().params.category_id || "",
    title: "",
    content: "",
    is_published: false,
});
</script>
