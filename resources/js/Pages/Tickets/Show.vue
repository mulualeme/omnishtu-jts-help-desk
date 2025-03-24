<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    auth: Object,
    ticket: Object,
});

const canEdit = computed(() => {
    const userRoles = props.auth.user.roles.map((role) => role.name);
    return (
        props.auth.user.id === props.ticket.created_by ||
        userRoles.includes("admin")
    );
});

const canAssign = computed(() => {
    const userRoles = props.auth.user.roles.map((role) => role.name);
    return userRoles.includes("admin") || userRoles.includes("agent");
});
</script>

<template>
    <Head :title="ticket.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ ticket.title }}
                </h2>
                <div class="flex gap-4">
                    <Link
                        v-if="canEdit"
                        :href="route('tickets.edit', ticket.id)"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
                    >
                        Edit Ticket
                    </Link>
                    <Link
                        :href="route('tickets.index')"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600"
                    >
                        Back to Tickets
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">
                                    Ticket Details
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="font-medium">Status:</span>
                                        <span
                                            class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="{
                                                'bg-green-100 text-green-800':
                                                    ticket.status === 'open',
                                                'bg-yellow-100 text-yellow-800':
                                                    ticket.status ===
                                                    'in_progress',
                                                'bg-gray-100 text-gray-800':
                                                    ticket.status === 'closed',
                                            }"
                                        >
                                            {{
                                                ticket.status.replace("_", " ")
                                            }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium"
                                            >Priority:</span
                                        >
                                        <span
                                            class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="{
                                                'bg-red-100 text-red-800':
                                                    ticket.priority === 'high',
                                                'bg-yellow-100 text-yellow-800':
                                                    ticket.priority ===
                                                    'medium',
                                                'bg-blue-100 text-blue-800':
                                                    ticket.priority === 'low',
                                            }"
                                        >
                                            {{ ticket.priority }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium"
                                            >Created By:</span
                                        >
                                        <span class="ml-2">{{
                                            ticket.creator.name
                                        }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium"
                                            >Assigned To:</span
                                        >
                                        <span class="ml-2">{{
                                            ticket.assigned_agent?.name ||
                                            "Unassigned"
                                        }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium"
                                            >Created At:</span
                                        >
                                        <span class="ml-2">{{
                                            new Date(
                                                ticket.created_at
                                            ).toLocaleString()
                                        }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium"
                                            >Last Updated:</span
                                        >
                                        <span class="ml-2">{{
                                            new Date(
                                                ticket.updated_at
                                            ).toLocaleString()
                                        }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-4">
                                    Description
                                </h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    {{ ticket.description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
