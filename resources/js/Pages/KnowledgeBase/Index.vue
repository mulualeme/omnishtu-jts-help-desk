<template>
    <Head title="Knowledge Base" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Knowledge Base
                </h2>
                <div v-if="$page.props.auth.user" class="flex space-x-4">
                    <Link
                        :href="route('knowledge-base.categories.create')"
                        class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            />
                        </svg>
                        New Category
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Categories List -->
                        <div class="space-y-2">
                            <div
                                v-for="category in categories"
                                :key="category.id"
                                class="border border-gray-100 rounded-lg"
                            >
                                <!-- Category Header -->
                                <div
                                    class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg cursor-pointer"
                                    @click="toggleCategory(category.id)"
                                >
                                    <div
                                        class="flex items-center space-x-3 flex-1"
                                    >
                                        <!-- Folder/Arrow Icon -->
                                        <div class="flex items-center">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 transform transition-transform duration-200"
                                                :class="{
                                                    'rotate-90':
                                                        expandedCategories.has(
                                                            category.id
                                                        ),
                                                }"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 5l7 7-7 7"
                                                />
                                            </svg>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 ml-1"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3
                                                class="font-medium text-gray-900"
                                            >
                                                {{ category.name }}
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                {{
                                                    category.articles_count || 0
                                                }}
                                                articles
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Category Actions -->
                                    <div
                                        class="relative"
                                        v-if="$page.props.auth.user"
                                        @click.stop
                                    >
                                        <button
                                            @click="
                                                toggleCategoryMenu(category.id)
                                            "
                                            class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                                                />
                                            </svg>
                                        </button>
                                        <!-- Dropdown Menu -->
                                        <div
                                            v-show="
                                                activeCategoryMenu ===
                                                category.id
                                            "
                                            class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                        >
                                            <div class="py-1">
                                                <Link
                                                    :href="
                                                        route(
                                                            'knowledge-base.articles.create',
                                                            {
                                                                category_id:
                                                                    category.id,
                                                            }
                                                        )
                                                    "
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 mr-2 text-gray-500"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                        />
                                                    </svg>
                                                    Add Article
                                                </Link>
                                                <Link
                                                    :href="
                                                        route(
                                                            'knowledge-base.categories.edit',
                                                            category.id
                                                        )
                                                    "
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 mr-2 text-gray-500"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                        />
                                                    </svg>
                                                    Edit Category
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Articles List -->
                                <div
                                    v-show="expandedCategories.has(category.id)"
                                    class="border-t border-gray-100 divide-y divide-gray-100"
                                >
                                    <div
                                        v-for="article in category.articles"
                                        :key="article.id"
                                        class="flex items-center justify-between p-4 pl-12 hover:bg-gray-50"
                                    >
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                />
                                            </svg>
                                            <div>
                                                <Link
                                                    :href="
                                                        route(
                                                            'knowledge-base.articles.show',
                                                            article.id
                                                        )
                                                    "
                                                    class="text-sm font-medium text-gray-900 hover:text-blue-600"
                                                >
                                                    {{ article.title }}
                                                </Link>
                                                <div
                                                    class="flex items-center space-x-4 mt-1"
                                                >
                                                    <span
                                                        class="text-xs text-gray-500"
                                                        >Updated
                                                        {{
                                                            article.updated_at
                                                        }}</span
                                                    >
                                                    <span
                                                        v-if="
                                                            article.is_published
                                                        "
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"
                                                    >
                                                        Published
                                                    </span>
                                                    <span
                                                        v-else
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800"
                                                    >
                                                        Draft
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            v-if="$page.props.auth.user"
                                            class="flex items-center space-x-2"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'knowledge-base.articles.edit',
                                                        article.id
                                                    )
                                                "
                                                class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                    />
                                                </svg>
                                            </Link>
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
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    categories: {
        type: Array,
        required: true,
    },
});

const expandedCategories = ref(new Set());
const activeCategoryMenu = ref(null);

const toggleCategory = (categoryId) => {
    if (expandedCategories.value.has(categoryId)) {
        expandedCategories.value.delete(categoryId);
    } else {
        expandedCategories.value.add(categoryId);
    }
};

const toggleCategoryMenu = (categoryId) => {
    activeCategoryMenu.value =
        activeCategoryMenu.value === categoryId ? null : categoryId;
};

// Close dropdown when clicking outside
const closeDropdowns = (event) => {
    if (!event.target.closest(".relative")) {
        activeCategoryMenu.value = null;
    }
};

onMounted(() => {
    document.addEventListener("click", closeDropdowns);
});

onUnmounted(() => {
    document.removeEventListener("click", closeDropdowns);
});
</script>
