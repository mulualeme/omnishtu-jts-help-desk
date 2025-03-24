<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    stats: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Stats Grid -->
                <div
                    class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <!-- Total Tickets -->
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-blue-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">
                                    Total Tickets
                                </h3>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ stats?.totalTickets || 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Open Tickets -->
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-yellow-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">
                                    Open Tickets
                                </h3>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ stats?.openTickets || 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Resolved Today -->
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-green-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">
                                    Resolved Today
                                </h3>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ stats?.resolvedToday || 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Response Time -->
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-purple-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">
                                    Avg. Response Time
                                </h3>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ stats?.avgResponseTime || "0h" }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                    <!-- Recent Tickets -->
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Recent Tickets
                            </h3>
                            <Link
                                :href="route('tickets.index')"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                View All
                            </Link>
                        </div>
                        <div class="space-y-3">
                            <div
                                v-if="stats?.recentTickets?.length"
                                v-for="ticket in stats.recentTickets"
                                :key="ticket.id"
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                            >
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ ticket.title }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{
                                            ticket.customer?.name ||
                                            "Unknown Customer"
                                        }}
                                    </p>
                                </div>
                                <span
                                    :class="{
                                        'px-2 py-1 text-xs font-medium rounded-full': true,
                                        'bg-yellow-100 text-yellow-800':
                                            ticket.status === 'open',
                                        'bg-green-100 text-green-800':
                                            ticket.status === 'resolved',
                                        'bg-blue-100 text-blue-800':
                                            ticket.status === 'in_progress',
                                    }"
                                >
                                    {{ ticket.status }}
                                </span>
                            </div>
                            <div v-else class="text-gray-500 text-center py-4">
                                No recent tickets
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Quick Actions
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <Link
                                :href="route('tickets.create')"
                                class="flex items-center p-3 text-base font-medium text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mr-3 text-gray-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>
                                New Ticket
                            </Link>
                            <Link
                                :href="route('knowledge-base.index')"
                                class="flex items-center p-3 text-base font-medium text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mr-3 text-gray-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                    />
                                </svg>
                                Knowledge Base
                            </Link>
                            <Link
                                :href="route('canned-responses.index')"
                                class="flex items-center p-3 text-base font-medium text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mr-3 text-gray-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                                    />
                                </svg>
                                Canned Responses
                            </Link>
                            <Link
                                v-if="
                                    $page.props.auth.user?.roles?.some(
                                        (role) => role.name === 'admin'
                                    )
                                "
                                :href="route('agents.index')"
                                class="flex items-center p-3 text-base font-medium text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mr-3 text-gray-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                                Manage Agents
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
