<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { computed } from "vue";

const props = defineProps({
    ticket: {
        type: Object,
        required: true,
    },
    canEdit: {
        type: Boolean,
        default: false,
    },
    auth: {
        type: Object,
        required: true,
    },
});

const userRoles = computed(() =>
    props.auth.user.roles.map((role) => role.name)
);
const isAdmin = computed(() => userRoles.value.includes("admin"));
const isAgent = computed(() => userRoles.value.includes("agent"));
const isCustomer = computed(() => userRoles.value.includes("customer"));

const form = useForm({
    priority: props.ticket.priority,
    title: props.ticket.title,
    description: props.ticket.description,
    status: props.ticket.status,
});

const statusColors = {
    open: "bg-green-100 text-green-800",
    in_progress: "bg-yellow-100 text-yellow-800",
    closed: "bg-gray-100 text-gray-800",
};

const priorityColors = {
    low: "bg-blue-100 text-blue-800",
    medium: "bg-yellow-100 text-yellow-800",
    high: "bg-red-100 text-red-800",
    critical: "bg-purple-100 text-purple-800",
};

const updatePriority = (newPriority) => {
    form.priority = newPriority;
    form.put(route("tickets.update", props.ticket.id), {
        preserveScroll: true,
        preserveState: true,
        only: ["ticket"],
    });
};
</script>

<template>
    <div class="w-80 border-l bg-white p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Ticket Details</h3>
        <div class="space-y-6">
            <div>
                <div class="text-sm text-gray-500 mb-1">Status</div>
                <div>
                    <span
                        class="px-2 py-1 text-sm font-medium rounded-full"
                        :class="statusColors[ticket.status]"
                    >
                        {{ ticket.status.replace("_", " ") }}
                    </span>
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Priority</div>
                <div v-if="isAdmin || isAgent" class="relative">
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <span
                                    class="w-2 h-2 rounded-full mr-2"
                                    :class="{
                                        'bg-blue-500': form.priority === 'low',
                                        'bg-yellow-500':
                                            form.priority === 'medium',
                                        'bg-red-500': form.priority === 'high',
                                        'bg-purple-500':
                                            form.priority === 'critical',
                                    }"
                                ></span>
                                {{
                                    form.priority.charAt(0).toUpperCase() +
                                    form.priority.slice(1)
                                }}
                                <svg
                                    class="ml-2 -mr-0.5 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="py-1">
                                <button
                                    v-for="priority in [
                                        'low',
                                        'medium',
                                        'high',
                                        'critical',
                                    ]"
                                    :key="priority"
                                    @click="updatePriority(priority)"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                    :class="{
                                        'bg-gray-50':
                                            form.priority === priority,
                                    }"
                                >
                                    <span
                                        class="w-2 h-2 rounded-full mr-2"
                                        :class="{
                                            'bg-blue-500': priority === 'low',
                                            'bg-yellow-500':
                                                priority === 'medium',
                                            'bg-red-500': priority === 'high',
                                            'bg-purple-500':
                                                priority === 'critical',
                                        }"
                                    ></span>
                                    {{
                                        priority.charAt(0).toUpperCase() +
                                        priority.slice(1)
                                    }}
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <div v-else>
                    <span
                        class="px-2 py-1 text-sm font-medium rounded-full"
                        :class="priorityColors[ticket.priority]"
                    >
                        {{ ticket.priority }}
                    </span>
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Created by</div>
                <div class="font-medium">{{ ticket.creator.name }}</div>
            </div>

            <div v-if="ticket.assigned_agent">
                <div class="text-sm text-gray-500 mb-1">Assigned to</div>
                <div class="font-medium">{{ ticket.assigned_agent.name }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Created at</div>
                <div class="font-medium">
                    {{
                        new Date(ticket.created_at).toLocaleDateString(
                            "en-US",
                            {
                                year: "numeric",
                                month: "long",
                                day: "numeric",
                                hour: "2-digit",
                                minute: "2-digit",
                            }
                        )
                    }}
                </div>
            </div>

            <div v-if="ticket.updated_at !== ticket.created_at">
                <div class="text-sm text-gray-500 mb-1">Last updated</div>
                <div class="font-medium">
                    {{
                        new Date(ticket.updated_at).toLocaleDateString(
                            "en-US",
                            {
                                year: "numeric",
                                month: "long",
                                day: "numeric",
                                hour: "2-digit",
                                minute: "2-digit",
                            }
                        )
                    }}
                </div>
            </div>

            <div v-if="isAdmin || isAgent">
                <div class="text-sm text-gray-500 mb-1">Team</div>
                <Link
                    :href="route('teams.edit', ticket.id)"
                    class="text-blue-500 hover:text-blue-600 font-medium"
                >
                    {{ ticket.team ? ticket.team.name : "Add Team" }}
                </Link>
            </div>

            <div v-if="isAdmin || isAgent">
                <div class="text-sm text-gray-500 mb-1">Agent</div>
                <Link
                    :href="route('tickets.edit', ticket.id)"
                    class="text-blue-500 hover:text-blue-600 font-medium"
                >
                    {{
                        ticket.assigned_agent
                            ? ticket.assigned_agent.name
                            : "Assign Agent"
                    }}
                </Link>
            </div>
        </div>
    </div>
</template>
