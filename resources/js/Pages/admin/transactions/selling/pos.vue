<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import { ref } from 'vue';
import { array, object } from 'vue-types';
import AppLayout from '@/layouts/apps.vue';
import VSetting from "@/components/src/icons/VSetting.vue";
import VSelect from '@/components/VSelect/index.vue';
import VButtonRounded from "@/components/VButtonRounded/index.vue";
import VFilter from "./Filter.vue"

const props = defineProps({
    filter: object(),
    query: array(),
    modules: array()
})

const formError = ref({})
const form = ref({})

</script>

<template>
    <div class="flex-wrap hidden gap-3 sm:flex ">
        <section class="h-[calc(100vh-110px)] p-5 bg-white w-full md:w-[65%] rounded-md overflow-hidden">
            <VFilter @search="searchHandle" />

            <div
                class="h-[calc(100vh-180px)] overflow-auto w-full grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 pb-10 mt-7">
                <div v-for="(data, index) in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]"
                    class="h-48 rounded-md cursor-pointer md:h-52 bg-slate-100">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="flex flex-col gap-2 px-2 pt-2">
                        <h1 class="text-sm font-semibold truncate">Product Name asdasdasdasd</h1>
                        <div class="flex items-center justify-between">
                            <p class="text-xs">Rp. 30.000</p>
                            <p class="text-xs">10</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="px-5 py-3 h-[calc(100vh-110px)] bg-white w-full md:w-[33%] rounded-md">
            <section class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-black">Cart</h1>
                <div class="p-1 transition-all rounded cursor-pointer bg-slate-100 hover:bg-slate-200">
                    <VSetting />
                </div>
            </section>

            <section class="mt-7">
                <VSelect placeholder="Select Customer" :required="true" v-model="form.purchase_account" :options="[]"
                    :errorMessage="formError.purchase_account" @update:modelValue="formError.purchase_account = ''" />
            </section>

            <section class="mt-5 h-[calc(100vh-55vh)] overflow-auto w-full">
                <div v-for="(data, index) in [1, 2, 3]" :key="index" class="flex flex-row w-full gap-2 my-4 min-h-16">
                    <img class="hidden object-cover w-16 h-16 rounded-md xl:block"
                        src="https://img.freepik.com/free-photo/skin-products-arrangement-wooden-blocks_23-2148761445.jpg"
                        alt="">
                    <div class="flex flex-col justify-between w-full">
                        <h2 class="text-sm font-semibold lg:text-md">
                            Ponds White Beauty Day Cream 50gr
                        </h2>
                        <!-- <p class="text-sm font-medium">Rp. 10.100</p> -->
                        <div class="flex justify-between w-full ">
                            <p class="text-xs font-medium lg:text-sm">Rp. 10.100</p>

                            <div class="flex items-center">
                                <!-- button minus -->
                                <button class="px-2 py-1 transition-all rounded-full bg-slate-200 hover:bg-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 12H6" />
                                    </svg>
                                </button>

                                <!-- input -->
                                <input type="text" class="flex-grow w-12 h-6 text-sm font-medium text-center border-0"
                                    value="10" readonly>

                                <!-- button plus -->
                                <button class="px-2 py-1 transition-all rounded-full bg-slate-200 hover:bg-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-7">
                <div class="flex justify-between">
                    <p class="text-sm font-medium">Subtotal</p>
                    <p class="text-sm font-medium">Rp. 10.100</p>
                </div>
                <!-- <div class="flex justify-between">
                    <p class="text-sm font-medium">Discount</p>
                    <p class="text-sm font-medium">Rp. 10.100</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-sm font-medium">Tax</p>
                    <p class="text-sm font-medium">Rp. 10.100</p>
                </div> -->
                <div class="flex justify-between">
                    <p class="text-sm font-medium">Total</p>
                    <p class="text-sm font-medium">Rp. 10.100</p>
                </div>
            </section>
            <section class="mt-7">
                <VButtonRounded @click="login" label="Checkout" />
            </section>
        </section>
    </div>

    <div class="sm:hidden">
        <p class="p-4 font-normal bg-white rounded-md">
            Currently the POS feature is not available on mobile devices, please use a desktop or tablet device to access
            this
            feature.
        </p>
    </div>
</template>
