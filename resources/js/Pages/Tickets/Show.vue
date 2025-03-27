<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import TicketDetails from "@/Pages/Tickets/TicketDetails.vue";

const props = defineProps({
    auth: Object,
    ticket: Object,
});

const breadcrumbItems = computed(() => [
    {
        label: "Tickets",
        href: route("tickets.index"),
    },
    {
        label: props.ticket.title,
    },
]);

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

const formattedCreatedAt = computed(() => {
    const date = new Date(props.ticket.created_at);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 0) {
        const hours = Math.floor(diffTime / (1000 * 60 * 60));
        if (hours === 0) {
            const minutes = Math.floor(diffTime / (1000 * 60));
            return `${minutes} minute${minutes !== 1 ? "s" : ""} ago`;
        }
        return `${hours} hour${hours !== 1 ? "s" : ""} ago`;
    } else if (diffDays === 1) {
        return "yesterday";
    } else if (diffDays < 7) {
        return `${diffDays} days ago`;
    } else {
        return date.toLocaleDateString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    }
});
</script>

<template>
    <Head :title="ticket.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex-1 flex justify-between items-center">
                <div>
                    <Breadcrumb :items="breadcrumbItems" />
                </div>
                <div class="flex items-center space-x-4">
                    <Link
                        v-if="canEdit"
                        :href="route('tickets.edit', ticket.id)"
                    >
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
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                            Edit Ticket
                        </PrimaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="flex min-h-screen bg-gray-50">
            <!-- Main Content -->
            <div class="flex-grow p-6">
                <!-- Activity Feed -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 bg-gray-200 rounded-full flex-shrink-0 flex items-center justify-center text-lg font-medium"
                            >
                                {{ ticket.creator.name[0].toUpperCase() }}
                            </div>
                            <div class="flex-grow">
                                <div
                                    class="flex justify-between items-start mb-1"
                                >
                                    <div>
                                        <span
                                            class="font-medium text-gray-900"
                                            >{{ ticket.creator.name }}</span
                                        >
                                        <span class="text-gray-500 ml-2"
                                            >created this ticket</span
                                        >
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ formattedCreatedAt }}
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div
                                        class="prose prose-sm max-w-none text-gray-700 [&>ul]:list-disc [&>ul]:pl-6 [&>ul]:my-2 [&>ol]:list-decimal [&>ol]:pl-6 [&>ol]:my-2 [&>li]:my-1 [&>ul>li]:my-1 [&>ol>li]:my-1 [&>h1]:text-2xl [&>h1]:font-bold [&>h1]:my-4 [&>h2]:text-xl [&>h2]:font-bold [&>h2]:my-3 [&>h3]:text-lg [&>h3]:font-bold [&>h3]:my-2 [&>p]:my-2 [&>ul]:mb-4 [&>ol]:mb-4 [&>*:first-child]:mt-0 [&>*:last-child]:mb-0"
                                        v-html="ticket.description"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t px-6 py-4">
                        <div class="flex space-x-4">
                            <SecondaryButton class="w-24"
                                >Reply</SecondaryButton
                            >
                            <SecondaryButton class="w-24"
                                >Comment</SecondaryButton
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <TicketDetails :ticket="ticket" :can-edit="canEdit" :auth="auth" />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.border-black {
    border-color: #000;
}
</style>
