<template>
    <Head title="Edit Team" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Team
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <form
                        @submit.prevent="
                            form.put(route('teams.update', team.id))
                        "
                    >
                        <!-- Name -->
                        <div class="mb-4">
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                                >Team Name</label
                            >
                            <input
                                type="text"
                                id="name"
                                v-model="form.name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required
                            />
                            <div
                                v-if="form.errors.name"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label
                                for="description"
                                class="block text-sm font-medium text-gray-700"
                                >Description</label
                            >
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            ></textarea>
                            <div
                                v-if="form.errors.description"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- Team Leader -->
                        <div class="mb-4">
                            <label
                                for="leader_id"
                                class="block text-sm font-medium text-gray-700"
                                >Team Leader</label
                            >
                            <select
                                id="leader_id"
                                v-model="form.leader_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required
                            >
                                <option value="">Select a leader</option>
                                <option
                                    v-for="user in users"
                                    :key="user.id"
                                    :value="user.id"
                                >
                                    {{ user.name }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.leader_id"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.leader_id }}
                            </div>
                        </div>

                        <!-- Team Members -->
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Team Members</label
                            >
                            <div
                                class="space-y-2 max-h-60 overflow-y-auto border rounded-md p-2"
                            >
                                <label
                                    v-for="user in users"
                                    :key="user.id"
                                    class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded"
                                >
                                    <input
                                        type="checkbox"
                                        :value="user.id"
                                        v-model="form.members"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                    <span class="text-sm text-gray-900">{{
                                        user.name
                                    }}</span>
                                </label>
                            </div>
                            <div
                                v-if="form.errors.members"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.members }}
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <Link
                                :href="route('teams.index')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                :disabled="form.processing"
                            >
                                Update Team
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    team: {
        type: Object,
        required: true,
    },
    users: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: props.team.name,
    description: props.team.description,
    leader_id: props.team.leader_id,
    members: props.team.members.map((member) => member.id),
});
</script>
