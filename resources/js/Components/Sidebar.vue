<script setup>
import { ref, computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    LayoutDashboard,
    LogOut,
    Ticket,
    BookOpen,
    Users,
    UsersRound,
    MessageSquareQuote,
    UserRound,
    ChevronDown,
    ChevronFirst,
    ChevronLast,
    Settings,
} from "lucide-vue-next";

const props = defineProps({
    isSidebarOpen: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["update:isSidebarOpen"]);

const page = usePage();
const user = computed(() => page.props.auth.user);

const hasRole = (role) => {
    return user.value?.roles?.some((r) => r.name === role);
};

const dashboardRoute = computed(() => {
    if (hasRole("admin")) return "dashboard";
    if (hasRole("agent")) return "dashboard";
    return "dashboard";
});

const isActive = (href) => {
    if (href === dashboardRoute.value) {
        return route().current("dashboard");
    }
    return route().current(href) || route().current(href + ".*");
};

const navigation = computed(() => {
    const items = [
        {
            name: "Dashboard",
            href: dashboardRoute.value,
            icon: LayoutDashboard,
            roles: null, // Always visible
        },
    ];

    // Add role-specific items
    if (hasRole("admin") || hasRole("agent")) {
        items.push(
            {
                name: "Tickets",
                href: "tickets.index",
                icon: Ticket,
                roles: ["admin", "agent"],
            },
            {
                name: "Knowledge Base",
                href: "knowledge-base.index",
                icon: BookOpen,
                roles: ["admin", "agent"],
            }
        );
    }

    // Customer can view tickets and knowledge base
    if (hasRole("customer")) {
        items.push(
            {
                name: "My Tickets",
                href: "tickets.index",
                icon: Ticket,
                roles: ["customer"],
            },
            {
                name: "Knowledge Base",
                href: "knowledge-base.index",
                icon: BookOpen,
                roles: ["customer"],
            }
        );
    }

    if (hasRole("admin")) {
        items.push(
            {
                name: "Agents",
                href: "agents.index",
                icon: Users,
                roles: ["admin"],
            },
            {
                name: "Teams",
                href: "teams.index",
                icon: UsersRound,
                roles: ["admin"],
            },
            {
                name: "Customers",
                href: "customers.index",
                icon: UserRound,
                roles: ["admin"],
            }
        );
    }

    if (hasRole("admin") || hasRole("agent")) {
        items.push({
            name: "Canned responses",
            href: "canned-responses.index",
            icon: MessageSquareQuote,
            roles: ["admin", "agent"],
        });
    }

    // Filter items based on user roles
    return items.filter(
        (item) => !item.roles || item.roles.some((role) => hasRole(role))
    );
});

const closeSidebar = () => {
    emit("update:isSidebarOpen", false);
    localStorage.setItem("sidebarOpen", "false");
};

const toggleSidebar = () => {
    const newState = !props.isSidebarOpen;
    emit("update:isSidebarOpen", newState);
    localStorage.setItem("sidebarOpen", newState);
};
</script>

<template>
    <!-- Mobile Overlay -->
    <div
        v-if="isSidebarOpen"
        class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 transition-opacity lg:hidden"
        @click="closeSidebar"
    ></div>

    <!-- Sidebar -->
    <div
        class="fixed inset-y-0 left-0 z-50 flex transform flex-col bg-white shadow-lg transition-all duration-300 ease-in-out"
        :class="[
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
            isSidebarOpen ? 'w-64' : 'w-16',
            'lg:translate-x-0', // Always show on large screens
        ]"
    >
        <!-- Sidebar Header -->
        <div class="flex h-auto flex-col border-b">
            <Dropdown align="bottom-start" width="48">
                <template #trigger>
                    <button
                        class="flex items-center w-full px-4 py-4 hover:bg-gray-50"
                    >
                        <ApplicationLogo
                            class="h-8 w-8 text-indigo-600 flex-shrink-0"
                        />

                        <div
                            v-if="isSidebarOpen"
                            class="flex flex-1 items-center justify-between ml-3"
                        >
                            <div class="flex flex-col items-start">
                                <span
                                    class="text-lg font-semibold text-gray-900"
                                >
                                    Helpdesk
                                </span>
                                <span class="text-sm text-gray-600">
                                    {{ user.name }}
                                </span>
                            </div>

                            <ChevronDown class="h-4 w-4 text-gray-400" />
                        </div>
                    </button>
                </template>

                <template #content>
                    <div class="py-1">
                        <Link
                            :href="route('profile.edit')"
                            class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                        >
                            Profile
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                        >
                            Log Out
                        </Link>
                    </div>
                </template>
            </Dropdown>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-2 py-4">
            <div class="space-y-4">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="route(item.href)"
                    class="group flex items-center px-2 py-2 text-base font-medium rounded-md transition-colors duration-200"
                    :class="[
                        isActive(item.href)
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                    ]"
                >
                    <div class="w-6 flex-shrink-0">
                        <component
                            :is="item.icon"
                            class="h-6 w-6 transition-colors ml-1 duration-300"
                            :class="[
                                isActive(item.href)
                                    ? 'text-gray-500'
                                    : 'text-gray-400 group-hover:text-gray-500',
                            ]"
                        />
                    </div>
                    <span
                        class="overflow-hidden transition-all duration-300 whitespace-nowrap"
                        :class="[
                            isSidebarOpen
                                ? 'w-auto opacity-100 ml-3'
                                : 'w-0 opacity-0',
                        ]"
                    >
                        {{ item.name }}
                    </span>
                </Link>
            </div>
        </nav>

        <!-- Collapse Button -->
        <div class="border-t border-gray-200 p-2">
            <button
                @click="toggleSidebar"
                class="group flex w-full items-center px-2 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200"
            >
                <div class="w-6 flex-shrink-0">
                    <component
                        :is="isSidebarOpen ? ChevronFirst : ChevronLast"
                        class="h-6 w-6 transition-colors ml-1 duration-300"
                        :class="['text-gray-400 group-hover:text-gray-500']"
                    />
                </div>
                <span
                    class="overflow-hidden transition-all duration-300 whitespace-nowrap"
                    :class="[
                        isSidebarOpen
                            ? 'w-auto opacity-100 ml-3'
                            : 'w-0 opacity-0',
                    ]"
                >
                    Collapse
                </span>
            </button>
        </div>
    </div>
</template>
