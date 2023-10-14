<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VDataTable from '@/components/VDataTable/index.vue';
import AppLayout from '@/layouts/apps.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head } from "@inertiajs/inertia-vue3";
import { onMounted, ref } from "vue";
import { any, string } from "vue-types";


const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route('dashboard.index')
    },
    {
        name: "Contacts",
        active: false,
    },
    {
        name: "Customer",
        active: false,
        to: route('contacts.customer.index')
    },
    {
        name: "Detail",
        active: true,
        to: route('contacts.customer.show', props.additional.data.id)
    },
]

const heads = ["No", "Date", "No Transaction", "Products Purchased", "Qty", "Total"]

const isLoading = ref(true)

const props = defineProps({
    title: string(),
    additional: any()
})

const handleDetailPurchase = (id) => {
    Inertia.visit(route('transaction.purchase.show', id))
}

onMounted(() => {
    console.log(props.additional.data)
});
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Detail Supplier</h1>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 pb-20"
        :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'">
        <div>
            <header class="block justify-between items-center sm:flex py-6 px-4">
                <h3 class="text-lg text-slate-800 font-bold">Personal Data</h3>
            </header>
            <div class="grid md:grid-cols-2 grid-cols-1 gap-3 px-4">
                <div class="mb-2">
                    <label class="font-medium text-slate-800">Name</label>
                    <p class="text-sm">{{ props.additional.data.name }}</p>
                </div>
                <div class="mb-2">
                    <label class="font-medium text-slate-800">Email</label>
                    <p class="text-sm">{{ props.additional.data.email ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <label class="font-medium text-slate-800">Phone Number</label>
                    <p class="text-sm">{{ props.additional.data.phone_number ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <label class="font-medium text-slate-800">Description</label>
                    <p class="text-sm">{{ props.additional.data.description ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div>
            <header class="block justify-between items-center sm:flex py-6 px-4">
                <h3 class="text-lg text-slate-800 font-bold">Address Information</h3>
            </header>
            <div class="grid md:grid-cols-2 grid-cols-1 gap-3 px-4">
                <div class="mb-2">
                    <label class="font-medium text-slate-800">Address</label>
                    <p class="text-sm">{{ props.additional.data.address ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <label class="font-medium text-slate-800">City</label>
                    <p class="text-sm">{{ props.additional.data.city ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <label class="font-medium text-slate-800">Portal Code</label>
                    <p class="text-sm">{{ props.additional.data.portal_code ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div>
            <header class="block justify-between items-center sm:flex py-6 px-4">
                <h3 class="text-lg text-slate-800 font-bold">History Transaction</h3>
            </header>

            <VDataTable :heads="heads" bordered>
                <tr v-for="(data, index) in additional.data.purchases" :key="index">
                    <td class=" px-4 whitespace-nowrap h-10">
                        {{ index + 1 }}
                    </td>
                    <td class=" px-4 whitespace-nowrap h-10"> {{ data.date }}</td>
                    <td class=" px-4 whitespace-nowrap h-10 text-sky-600 underline cursor-pointer"
                        @click="handleDetailPurchase(data.id)">
                        {{ data.no_transaction }}
                    </td>
                    <td class=" px-4 whitespace-nowrap h-10">
                        {{
                            // total length sale_details
                            data.purchase_details.length
                        }} Products
                    </td>
                    <td class=" px-4 whitespace-nowrap h-10">
                        {{ data.purchase_details?.reduce((acc, item) => {
                            return parseInt(acc + parseInt(item.quantity))
                        }, 0) }}
                    </td>
                    <td class=" px-4 whitespace-nowrap h-10"> Rp.
                        {{ data.purchase_details?.reduce((acc, item) => {
                            return parseInt(acc + (parseInt(item.price_per_unit) * parseInt(item.quantity)))
                        }, 0).toLocaleString('id-ID') }}
                    </td>
                </tr>
            </VDataTable>
        </div>
    </div>
</template>