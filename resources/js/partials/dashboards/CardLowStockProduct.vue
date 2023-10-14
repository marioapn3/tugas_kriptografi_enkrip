<script setup>
import { any, array, bool, string } from "vue-types";

const props = defineProps({
    title: string().isRequired,
    value: any().isRequired,
    isRupiah: bool(),
});
</script>

<template>
    <div
        class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white shadow-lg rounded-sm border border-slate-200 h-full">
        <header class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800 text-lg">Low Stock Products</h2>
        </header>
        <div class="px-5 pt-5 ">
            <!-- <div class="text-xs font-semibold text-slate-400 uppercase mb-1">
                Sales
            </div> -->
            <div class="my-4 text-center flex justify-center self-center">
                <div class="text-8xl font-bold text-slate-800 mt-5">
                    {{ value }}
                </div>
            </div>
        </div>
    </div>
</template>
