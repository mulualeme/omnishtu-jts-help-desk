<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    items: {
        type: Array,
        required: true,
        // Each item should have: { label: string, href?: string }
    },
});
</script>

<template>
    <nav class="flex items-center text-gray-500 text-base">
        <template v-for="(item, index) in items" :key="index">
            <template v-if="index > 0">
                <span class="mx-2">/</span>
            </template>

            <Link
                v-if="item.href"
                :href="item.href"
                class="hover:text-gray-700"
            >
                {{ item.label }}
            </Link>
            <span v-else class="text-gray-700">
                {{ item.label }}
            </span>
        </template>
    </nav>
</template>
