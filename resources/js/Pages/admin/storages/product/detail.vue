<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VDataTable from '@/components/VDataTable/index.vue';
import AppLayout from '@/layouts/apps.vue';
import { Head } from "@inertiajs/inertia-vue3";
import { onMounted, reactive, ref } from "vue";
import { any, string } from "vue-types";

const query = ref([])
const searchFilter = ref("");
const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route('dashboard.index')
    },
    {
        name: "Storages",
        active: false,
    },
    {
        name: "Product",
        active: false,
        to: route('storages.product.index')
    },
    {
        name: "Detail",
        active: true,
    },
]

const heads = ["No", "Date", "Transaction", "Price", "Total", "Action", "Quantity"]


const updateAction = ref(false)
const openModalForm = ref(false)
const isLoading = ref(true)

const props = defineProps({
    title: string(),
    additional: any()
})


const handleAddModalForm = () => {
    updateAction.value = false
    openModalForm.value = true
}

onMounted(() => {
    console.log(props.additional.data.data)
});
</script>

<template>
    <Head :title="title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Detail Product</h1>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 pb-20"
        :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'">
        <div>
            <!-- <header class="block justify-between items-center sm:flex py-6 px-4">
                <h3 class="text-lg text-slate-800 font-bold">Personal Data</h3>
            </header> -->
            <div class="grid md:grid-cols-2 grid-cols-1 gap-3 px-4 mt-8">
                <div class="grid md:grid-cols-2 grid-cols-1 gap-3">
                    <div class="block md:hidden mb-2">
                        <img :src="additional.data.data.image_preview" width="150" alt="">
                    </div>
                    <div class="col-span-2 mb-2">
                        <label class="font-bold text-slate-800">Code</label>
                        <p class="text-sm">{{ additional.data.data.code }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Name</label>
                        <p class="text-sm">{{ additional.data.data.name }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Description</label>
                        <p class="text-sm">{{ additional.data.data.description ?? '-' }}</p>
                    </div>
                    <!-- <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Average Price</label>
                        <p class="text-sm">Rp. {{ additional.data.data.phone_number ?? '-' }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Last Purchase Price</label>
                        <p class="text-sm">Rp. {{ additional.data.data.description ?? '-' }}</p>
                    </div> -->
                    <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Purchase Price</label>
                        <p class="text-sm">Rp. {{ additional.data.data.purchase_price ?? '-' }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Purchase Account</label>
                        <p class="text-sm">{{ additional.data.data.purchase_account.code }} - {{
                            additional.data.data.purchase_account.name }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Selling Price</label>
                        <p class="text-sm">Rp. {{ additional.data.data.sale_price ?? '-' }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Selling Account</label>
                        <p class="text-sm">{{ additional.data.data.sale_account.code }} - {{
                            additional.data.data.sale_account.name }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Stock</label>
                        <p class="text-sm">{{ additional.data.data.stock ?? '-' }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1 mb-2">
                        <label class="font-bold text-slate-800">Inventory Account</label>
                        <p class="text-sm">{{ additional.data.data.inventory_account.code }} - {{
                            additional.data.data.inventory_account.name }}</p>
                    </div>
                </div>
                <div class="hidden md:block mb-2">
                    <img :src="additional.data.data.image_preview" width="150" alt="">
                </div>
            </div>
        </div>

        <div>
            <header class="block justify-between items-center sm:flex py-6 px-4">
                <h3 class="text-lg text-slate-800 font-bold">History Transaction</h3>
            </header>

            <VDataTable :heads="heads" bordered>
                <tr v-for="(data, index) in additional.data.data.transaction_history" :key="index">
                    <td class=" px-4 whitespace-nowrap h-10">{{ index + 1 }}</td>
                    <td class=" px-4 whitespace-nowrap h-10">
                        <p v-if="data.purchase">
                            {{ data.purchase.date }}
                        </p>
                        <p v-else>
                            {{ data.sales.date }}
                        </p>
                    </td>
                    <td class=" px-4 whitespace-nowrap h-14 text-xs">
                        <div v-if="data.purchase">
                            <p class="text-sky-600 underline cursor-pointer ">
                                {{ data.purchase.no_transaction }}
                            </p>
                            <p>
                                {{ data.purchase.description }}
                            </p>
                        </div>
                        <div v-else>
                            <p class="text-sky-600 underline cursor-pointer ">
                                {{ data.sales.no_transaction }}
                            </p>
                            <p>
                                {{ data.sales.description }}
                            </p>
                        </div>
                    </td>
                    <!-- <td class=" px-4 whitespace-nowrap h-14 text-xs">
                        <div v-if="data.journal.purchase">
                            <p class="text-sky-600 underline cursor-pointer ">
                                {{ data.journal.purchase.no_transaction }}
                            </p>
                            <p>
                                {{ data.journal.purchase.description }}
                            </p>
                        </div>
                        <div v-else>
                            <p class="text-sky-600 underline cursor-pointer ">
                                {{ data.journal.sales.no_transaction }}
                            </p>
                            <p>
                                {{ data.journal.sales.description }}
                            </p>
                        </div>
                    </td>
                    <td class=" px-4 whitespace-nowrap h-10">
                        <div v-if="data.journal.purchase">
                            {{ data.journal.purchase.purchase_details[0].quantity }}
                        </div>
                    </td> -->

                    <td class=" px-4 whitespace-nowrap h-10">
                        <p v-if="data.purchase">
                            Rp. {{ data.purchase.details[0].price }}
                        </p>
                        <p v-else>
                            Rp. {{ data.sales.details[0].price }}
                        </p>
                    </td>
                    <td class=" px-4 whitespace-nowrap h-10">
                        <p v-if="data.purchase">
                            Rp. {{ data.purchase.details[0].total }}
                        </p>
                        <p v-else>
                            Rp. {{ data.sales.details[0].total }}
                        </p>
                    </td>
                    <td class=" px-4 whitespace-nowrap h-10">
                        <span :class="[{ 'text-emerald-700': data.type == 'in' }, { 'text-rose-700': data.type == 'out' }]">
                            {{ data.type }}
                        </span>
                    </td>
                    <td class=" px-4 whitespace-nowrap h-10">
                        {{ data.qty }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td class="px-4 font-bold text-lg" align="right">
                        Total :
                    </td>
                    <td class="px-4 font-bold h-10 text-lg">
                        {{ additional.data.data.stock }}
                    </td>
                </tr>
            </VDataTable>
        </div>
    </div>
</template>