<script setup>
import { ref, onMounted } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { Link, usePage } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar.vue";

const showingNavigationDropdown = ref(false);
const isSidebarOpen = ref(true);
const page = usePage();

onMounted(() => {
    // Check localStorage for sidebar state
    const savedSidebarState = localStorage.getItem("sidebarOpen");
    if (savedSidebarState !== null) {
        isSidebarOpen.value = savedSidebarState === "true";
    }
});
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <Sidebar v-model:isSidebarOpen="isSidebarOpen" />

        <!-- Main Content -->
        <div
            class="flex flex-col flex-1 transition-all duration-300"
            :class="[isSidebarOpen ? 'lg:pl-64' : 'lg:pl-16']"
        >
            <!-- Top Bar -->
            <div class="sticky top-0 z-10 bg-white shadow">
                <div class="flex items-center h-16 px-6">
                    <slot name="header" />
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
