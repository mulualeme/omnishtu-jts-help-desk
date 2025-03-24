<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Knowledge Base Article
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label
                                    for="category_id"
                                    class="block text-sm font-medium text-gray-700"
                                    >Category</label
                                >
                                <select
                                    id="category_id"
                                    v-model="form.category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                />
                                <div
                                    v-if="form.errors.title"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.title }}
                                </div>
                            </div>

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
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                ></textarea>
                                <div
                                    v-if="form.errors.content"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.content }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        v-model="form.is_published"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    <span class="ml-2"
                                        >Publish immediately</span
                                    >
                                </label>
                            </div>

                            <div class="flex justify-end">
                                <Link
                                    :href="
                                        route('knowledge-base.articles.index')
                                    "
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 mr-4"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                                    :disabled="form.processing"
                                >
                                    Create Article
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    categories: Array,
});

const form = useForm({
    category_id: "",
    title: "",
    content: "",
    is_published: false,
});

const submit = () => {
    form.post(route("knowledge-base.articles.store"));
};
</script>
