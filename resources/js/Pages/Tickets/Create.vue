<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import RichTextEditor from "@/Components/RichTextEditor.vue";

const form = useForm({
    title: "",
    description: "",
});

const submit = () => {
    form.post(route("tickets.store"));
};

defineProps({
    auth: Object,
});
</script>

<template>
    <Head title="New Ticket" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex-1 flex justify-between items-center">
                <div>
                    <nav class="flex items-center text-gray-500 text-base">
                        <Link
                            :href="route('tickets.index')"
                            class="hover:text-gray-700 text-xl font-semibold"
                        >
                            Tickets
                        </Link>
                        <svg
                            class="h-5 w-5 mx-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                        <span class="text-gray-700 text-xl font-semibold"
                            >New Ticket</span
                        >
                    </nav>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form
                            @submit.prevent="submit"
                            class="max-w-3xl mx-auto"
                        >
                            <div class="mb-6">
                                <InputLabel
                                    for="title"
                                    value="Subject"
                                    required
                                    class="text-base"
                                />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full text-base"
                                    v-model="form.title"
                                    placeholder="A short description"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.title"
                                />
                            </div>

                            <div class="mb-6">
                                <InputLabel
                                    for="description"
                                    value="Description"
                                    required
                                    class="text-base"
                                />
                                <RichTextEditor
                                    v-model="form.description"
                                    placeholder="Write your message here..."
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.description"
                                />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Link :href="route('tickets.index')">
                                    <SecondaryButton> Discard </SecondaryButton>
                                </Link>
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Submit
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.required:after {
    content: " *";
    color: #ef4444;
}
</style>
