<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import axios from "axios";
import { notify } from "notiwind";
import { string } from "vue-types";
import { Head } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AppLayout from '@/layouts/apps.vue';
import debounce from "@/composables/debounce"
import VDropdownEditMenu from '@/components/VDropdownEditMenu/index.vue';
import VDataTable from '@/components/VDataTable/index.vue';
import VPagination from '@/components/VPagination/index.vue'
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VLoading from '@/components/VLoading/index.vue';
import VEmpty from '@/components/src/icons/VEmpty.vue';
import VButton from '@/components/VButton/index.vue';
import VAlert from '@/components/VAlert/index.vue';
import VEdit from '@/components/src/icons/VEdit.vue';
import VTrash from '@/components/src/icons/VTrash.vue';
import VFilter from './Filter.vue';
// import VModalForm from './ModalForm.vue';

const query = ref([])
const searchFilter = ref("");
const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route('dashboard.index')
    },
    {
        name: "Transaction",
        active: false,
    },
    {
        name: "Expense",
        active: true,
        to: route('transaction.expense.index')
    },
]
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
const heads = ["Date", "No Transaction", "Detail Expense", "Expense Value", "Total Expense", "Status", ""]
const isLoading = ref(true)

const props = defineProps({
    title: string()
})

const getData = debounce(async (page) => {
    axios.get(route('transaction.expense.getdata'), {
        params: {
            page: page,
            search: searchFilter.value
        }
    }).then((res) => {
        query.value = res.data.data
        pagination.value = res.data.meta.pagination
    }).catch((res) => {
        notify({
            type: "error",
            group: "top",
            text: res.response.data.message
        }, 2500)
    }).finally(() => isLoading.value = false)
}, 500);


const handleDetail = (id) => {
    Inertia.visit(route('transaction.expense.show', id));
}

const nextPaginate = () => {
    pagination.value.current_page += 1
    isLoading.value = true
    getData(pagination.value.current_page)
}

const previousPaginate = () => {
    pagination.value.current_page -= 1
    isLoading.value = true
    getData(pagination.value.current_page)
}

const searchHandle = (search) => {
    searchFilter.value = search
    isLoading.value = true
    getData(1)
};

const handleCreate = () => {
    Inertia.visit(route('transaction.expense.create'));
}

const handleEdit = (data) => {
    Inertia.visit(route('transaction.expense.edit', { 'id': data.id }));
}

const alertDelete = (data) => {
    itemSelected.value = data
    openAlert.value = true
    alertData.headerLabel = 'Are you sure to delete this?'
    alertData.contentLabel = "You won't be able to revert this!"
    alertData.closeLabel = 'Cancel'
    alertData.submitLabel = 'Delete'
}

const closeAlert = () => {
    itemSelected.value = ref({})
    openAlert.value = false
}

const deleteHandle = async () => {
    axios.delete(route('transaction.expense.delete', { 'id': itemSelected.value.id })
    ).then((res) => {
        notify({
            type: "success",
            group: "top",
            text: res.data.meta.message
        }, 2500)
        openAlert.value = false
        isLoading.value = true
        getData(pagination.value.current_page)
    }).catch((res) => {
        notify({
            type: "error",
            group: "top",
            text: res.response.data.message
        }, 2500)
    })
};

onMounted(() => {
    getData(1);
});
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <h1 class="text-2xl font-bold md:text-3xl text-slate-800">Expense</h1>
    </div>
    <div class="bg-white border rounded-sm shadow-lg border-slate-200" :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'">
        <header class="items-center justify-between block px-4 py-6 sm:flex">
            <h2 class="font-semibold text-slate-800">
                All Expense <span class="text-slate-400 !font-medium ml">({{ pagination.total }})</span>
            </h2>
            <div class="flex justify-end mt-3 space-x-2 sm:mt-0 sm:justify-between">
                <!-- Filter -->
                <VFilter @search="searchHandle" />
                <VButton label="Add Expenses" type="primary" @click="handleCreate" class="mt-auto" />
            </div>
        </header>

        <VDataTable :heads="heads" :isLoading="isLoading">
            <tr v-if="isLoading">
                <td class="h-[100%] overflow-hidden my-2" :colspan="heads.length">
                    <VLoading />
                </td>
            </tr>
            <tr v-else-if="query.length === 0 && !isLoading">
                <td class="my-2 overflow-hidden" :colspan="heads.length">
                    <div class="flex flex-col items-center w-full my-32">
                        <VEmpty />
                        <div class="text-xl font-medium mt-9 text-slate-500 md:text-xl">Result not found.</div>
                    </div>
                </td>
            </tr>
            <tr v-for="(data, index) in query" :key="index" v-else>
                <td class="h-16 px-4 whitespace-nowrap"> {{ data.date }} </td>
                <td class="h-16 px-4 underline cursor-pointer whitespace-nowrap text-sky-600" @click="handleDetail(data.id)">
                    {{ data.no_transaction }} </td>
                <td class="h-16 px-4">
                    <table>
                        <tr class="h-10" v-for="(detail, index) in data.expense_details">
                            <td>
                                {{ detail.description }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="h-16 px-4">
                    <table>
                        <tr class="h-10" v-for="(detail, index) in data.expense_details">
                            <td>
                                Rp. {{ detail.expense }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="h-16 px-4 whitespace-nowrap">Rp. {{ data.total_price ?? '-' }} </td>
                <td class="h-16 px-4 whitespace-nowrap">
                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                        Success
                    </span>
                </td>
                <td class="h-16 px-4 text-right whitespace-nowrap">
                    <VDropdownEditMenu class="relative inline-flex r-0" :align="'right'"
                        :last="index === query.length - 1 ? true : false">
                        <li class="cursor-pointer hover:bg-slate-100" @click="handleEdit(data)">
                            <div class="flex items-center p-3 space-x-2">
                                <span>
                                    <VEdit color="primary" />
                                </span>
                                <span>Edit</span>
                            </div>
                        </li>
                        <li class="cursor-pointer hover:bg-slate-100">
                            <div class="flex items-center justify-between p-3 space-x-2" @click="alertDelete(data)">
                                <span>
                                    <VTrash color="danger" />
                                </span>
                                <span>Delete</span>
                            </div>
                        </li>
                    </VDropdownEditMenu>
                </td>
            </tr>
        </VDataTable>
        <div class="px-4 py-6">
            <VPagination :pagination="pagination" @next="nextPaginate" @previous="previousPaginate" />
        </div>
    </div>
    <VAlert :open-dialog="openAlert" @closeAlert="closeAlert" @submitAlert="deleteHandle" type="danger"
        :headerLabel="alertData.headerLabel" :content-label="alertData.contentLabel" :close-label="alertData.closeLabel"
        :submit-label="alertData.submitLabel" />
</template>
