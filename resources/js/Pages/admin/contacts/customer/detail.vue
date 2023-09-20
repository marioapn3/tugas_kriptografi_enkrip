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

const heads = ["No", "Date", "Type Transaction", "Total"]

const pagination = ref({
    count: '',
    current_page: 1,
    per_page: '',
    total: 0,
    total_pages: 1
})
const alertData = reactive({
    headerLabel: '',
    contentLabel: '',
    closeLabel: '',
    submitLabel: '',
})
const updateAction = ref(false)
const itemSelected = ref({})
const openAlert = ref(false)
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
    console.log(props.additional.data)
});
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Detail Customer</h1>
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
                <tr>
                    <td class=" px-4 whitespace-nowrap h-10"> 1</td>
                    <td class=" px-4 whitespace-nowrap h-10"> 30/02/2023</td>
                    <td class=" px-4 whitespace-nowrap h-10 text-sky-600 underline cursor-pointer"> Invoice Pembelian 0001
                    </td>
                    <td class=" px-4 whitespace-nowrap h-10"> Rp. 2.000.000,00</td>
                </tr>
            </VDataTable>
        </div>
    </div>
</template>