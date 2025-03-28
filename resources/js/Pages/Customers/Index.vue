<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    customers: Array,
});
</script>

<template>
    <Head title="Customers" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex-1 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                    Customers
                </h2>
                <Link :href="route('customers.create')">
                    <PrimaryButton>
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
                        Add Customer
                    </PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Name
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Created At
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
                                        v-for="customer in customers"
                                        :key="customer.id"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ customer.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ customer.email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{
                                                new Date(
                                                    customer.created_at
                                                ).toLocaleString()
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'customers.edit',
                                                        customer.id
                                                    )
                                                "
                                                class="text-blue-600 hover:text-blue-900 mr-4"
                                            >
                                                Edit
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'customers.destroy',
                                                        customer.id
                                                    )
                                                "
                                                method="delete"
                                                as="button"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
