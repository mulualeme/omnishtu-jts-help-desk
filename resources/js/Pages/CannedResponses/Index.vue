<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Canned Responses
                </h2>
                <Link
                    :href="route('canned-responses.create')"
                    class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                >
                    Add Response
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div
                            v-if="$page.props.flash?.success"
                            class="mb-4 p-4 bg-green-100 text-green-700 rounded"
                        >
                            {{ $page.props.flash.success }}
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Title
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Category
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Created By
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Visibility
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="response in responses.data"
                                        :key="response.id"
                                    >
                                        <td class="px-6 py-4">
                                            {{ response.title }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ response.category || "-" }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ response.user.name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                :class="[
                                                    response.is_public
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-yellow-100 text-yellow-800',
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                ]"
                                            >
                                                {{
                                                    response.is_public
                                                        ? "Public"
                                                        : "Private"
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                        >
                                            <button
                                                @click="
                                                    copyToClipboard(
                                                        response.content
                                                    )
                                                "
                                                class="text-gray-600 hover:text-gray-900 mr-4"
                                            >
                                                Copy
                                            </button>
                                            <Link
                                                v-if="canEdit(response)"
                                                :href="
                                                    route(
                                                        'canned-responses.edit',
                                                        response.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900 mr-4"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                v-if="canDelete(response)"
                                                @click="
                                                    deleteResponse(response)
                                                "
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <Pagination :links="responses.links" />
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
import Pagination from "@/Components/Pagination.vue";
import { router } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    responses: Object,
});

const page = usePage();

const canEdit = (response) => {
    return (
        page.props.auth.user.roles.some((role) => role.name === "admin") ||
        response.user_id === page.props.auth.user.id
    );
};

const canDelete = (response) => {
    return (
        page.props.auth.user.roles.some((role) => role.name === "admin") ||
        response.user_id === page.props.auth.user.id
    );
};

const copyToClipboard = async (content) => {
    try {
        await navigator.clipboard.writeText(content);
        alert("Response copied to clipboard!");
    } catch (err) {
        console.error("Failed to copy text: ", err);
    }
};

const deleteResponse = (response) => {
    if (confirm("Are you sure you want to delete this canned response?")) {
        router.delete(route("canned-responses.destroy", response.id));
    }
};
</script>
