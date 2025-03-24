<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    auth: Object,
    tickets: Array,
});

const filters = ref({
    status: "",
    priority: "",
});

const filteredTickets = computed(() => {
    return props.tickets.filter((ticket) => {
        if (filters.value.status && ticket.status !== filters.value.status)
            return false;
        if (
            filters.value.priority &&
            ticket.priority !== filters.value.priority
        )
            return false;
        return true;
    });
});
</script>

<template>
    <Head title="Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tickets
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex gap-4">
                                <select
                                    v-model="filters.status"
                                    class="rounded-md border-gray-300"
                                >
                                    <option value="">All Status</option>
                                    <option value="open">Open</option>
                                    <option value="in_progress">
                                        In Progress
                                    </option>
                                    <option value="closed">Closed</option>
                                </select>
                                <select
                                    v-model="filters.priority"
                                    class="rounded-md border-gray-300"
                                >
                                    <option value="">All Priority</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <Link
                                :href="route('tickets.create')"
                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
                            >
                                Create Ticket
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            ID
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Title
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Priority
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Created By
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Assigned To
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
                                        v-for="ticket in filteredTickets"
                                        :key="ticket.id"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ ticket.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ ticket.title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800':
                                                        ticket.status ===
                                                        'open',
                                                    'bg-yellow-100 text-yellow-800':
                                                        ticket.status ===
                                                        'in_progress',
                                                    'bg-gray-100 text-gray-800':
                                                        ticket.status ===
                                                        'closed',
                                                }"
                                            >
                                                {{
                                                    ticket.status.replace(
                                                        "_",
                                                        " "
                                                    )
                                                }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-red-100 text-red-800':
                                                        ticket.priority ===
                                                        'high',
                                                    'bg-yellow-100 text-yellow-800':
                                                        ticket.priority ===
                                                        'medium',
                                                    'bg-blue-100 text-blue-800':
                                                        ticket.priority ===
                                                        'low',
                                                }"
                                            >
                                                {{ ticket.priority }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ ticket.creator.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{
                                                ticket.assigned_agent?.name ||
                                                "Unassigned"
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'tickets.show',
                                                        ticket.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900 mr-4"
                                            >
                                                View
                                            </Link>
                                            <Link
                                                v-if="
                                                    auth.user.id ===
                                                    ticket.created_by
                                                "
                                                :href="
                                                    route(
                                                        'tickets.edit',
                                                        ticket.id
                                                    )
                                                "
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                Edit
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
