<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    auth: Object,
    tickets: Array,
});

const page = usePage();
const currentRoute = computed(() => page.component);

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

const breadcrumbItems = computed(() => [
    {
        label: "Tickets",
    },
]);
</script>

<template>
    <Head title="Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex-1 flex justify-between items-center">
                <div>
                    <Breadcrumb :items="breadcrumbItems" />
                </div>
                <Link :href="route('tickets.create')">
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
                        Create Ticket
                    </PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex gap-4 mb-6">
                            <select
                                v-model="filters.status"
                                class="rounded-md border-gray-300"
                            >
                                <option value="">All Status</option>
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
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
                                <option value="critical">Critical</option>
                            </select>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            v-for="header in [
                                                'ID',
                                                'Title',
                                                'Status',
                                                'Priority',
                                                'Created By',
                                                'Assigned To',
                                                '',
                                            ]"
                                            :key="header"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            {{ header }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="ticket in filteredTickets"
                                        :key="ticket.id"
                                        class="hover:bg-gray-50 cursor-pointer"
                                        @click="
                                            $inertia.visit(
                                                route('tickets.show', ticket.id)
                                            )
                                        "
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
                                                    'bg-purple-100 text-purple-800':
                                                        ticket.priority ===
                                                        'critical',
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
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            <Dropdown
                                                align="right"
                                                width="48"
                                                @click.stop
                                            >
                                                <template #trigger>
                                                    <button
                                                        class="inline-flex items-center text-gray-400 hover:text-gray-600"
                                                    >
                                                        <svg
                                                            class="h-5 w-5"
                                                            viewBox="0 0 20 20"
                                                            fill="currentColor"
                                                        >
                                                            <path
                                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                                                            />
                                                        </svg>
                                                    </button>
                                                </template>

                                                <template #content>
                                                    <DropdownLink
                                                        :href="
                                                            route(
                                                                'tickets.show',
                                                                ticket.id
                                                            )
                                                        "
                                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                    >
                                                        View
                                                    </DropdownLink>
                                                    <DropdownLink
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
                                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                    >
                                                        Edit
                                                    </DropdownLink>
                                                </template>
                                            </Dropdown>
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
