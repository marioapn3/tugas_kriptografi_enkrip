<script setup>
import VNotification from "@/components/VNotification/Index.vue";
</script>

<template>
    <VNotification></VNotification>
    <main class="bg-slate-50">
        <!-- Content -->
        <div class="relative flex">
            <slot />
        </div>
    </main>
</template>

