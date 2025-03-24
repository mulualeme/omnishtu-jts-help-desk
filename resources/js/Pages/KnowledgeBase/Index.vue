<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Knowledge Base
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                        >
                            <div
                                v-for="category in categories"
                                :key="category.id"
                                class="bg-white rounded-lg shadow-md p-6"
                            >
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-2"
                                >
                                    {{ category.name }}
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    {{ category.description }}
                                </p>
                                <div class="space-y-4">
                                    <div
                                        v-for="article in category.articles"
                                        :key="article.id"
                                        class="border-t pt-4"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'knowledge-base.show',
                                                    article.slug
                                                )
                                            "
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            {{ article.title }}
                                        </Link>
                                        <div class="text-sm text-gray-500 mt-1">
                                            Last updated:
                                            {{
                                                new Date(
                                                    article.updated_at
                                                ).toLocaleDateString()
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    categories: Array,
});
</script>
