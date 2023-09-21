<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import { array, object } from 'vue-types';
import AppLayout from '@/layouts/apps.vue';

const props = defineProps({
    filter: object(),
    query: array(),
    modules: array()
})
</script>

<template>
    <div class="flex gap-3 flex-wrap">
        <section class="min-h-screen p-5 bg-white w-full md:w-[65%] rounded-md">
            <div class="w-full grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 md:h-52 bg-slate-100 rounded-md cursor-pointer">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="px-2 pt-2 flex flex-col gap-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex justify-between items-center">
                            <p class="text-xs">Price</p>
                            <p class="text-xs">Stock</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="h-20 bg-white w-full md:w-[33%] rounded-md">

        </section>
    </div>
</template>