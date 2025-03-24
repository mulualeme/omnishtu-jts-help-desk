<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ article.title }}
                </h2>
                <div v-if="$page.props.auth.user" class="flex space-x-4">
                    <Link
                        :href="
                            route('knowledge-base.articles.edit', article.id)
                        "
                        class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                    >
                        Edit Article
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <div
                                class="flex items-center text-sm text-gray-500"
                            >
                                <span
                                    >Category: {{ article.category.name }}</span
                                >
                                <span class="mx-2">•</span>
                                <span
                                    >Last updated:
                                    {{
                                        new Date(
                                            article.updated_at
                                        ).toLocaleDateString()
                                    }}</span
                                >
                                <span v-if="article.published_at" class="mx-2"
                                    >•</span
                                >
                                <span v-if="article.published_at">
                                    Published:
                                    {{
                                        new Date(
                                            article.published_at
                                        ).toLocaleDateString()
                                    }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="prose max-w-none"
                            v-html="article.content"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    article: Object,
});
</script>
