<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import axios from "axios";
import { notify } from "notiwind";
import { any, object, string } from "vue-types";
import { Head } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive } from "vue";
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

const totalDebit = ref(0);
const totalCredit = ref(0);
const query = ref([])
const filter = ref({});


const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route('dashboard.index')
    },
    {
        name: "Report",
        active: false,
    },
    {
        name: "Balance Report",
        active: true,
        to: route('report.balance.index')
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
const heads = ["Account Code", "Account Name", "Debit", "Credit"]
const isLoading = ref(true)


const props = defineProps({
    title: string(),
    additional: object(),
})

const getData = debounce(async (page) => {
    axios.get(route('report.balance.getdata'), {
        params: {
            page: page,
            start_date: filter.value.start_date,
            end_date: filter.value.end_date
        }

    }).then((res) => {
        query.value = res.data.data
        pagination.value = res.data.meta.pagination;
        // Hitung total debit dan total kredit dari data
        totalDebit.value = query.value.reduce((total, data) => total + parseFloat(data.debit.replace(/,/g, '')), 0);
        totalCredit.value = query.value.reduce((total, data) => total + parseFloat(data.credit.replace(/,/g, '')), 0);
    }).catch((res) => {
        notify({
            type: "error",
            group: "top",
            text: res.response.data.message
        }, 2500)
    }).finally(() => isLoading.value = false)
}, 500);

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

const handleAddModalForm = () => {
    updateAction.value = false
    openModalForm.value = true
}

const handleEditModal = (data) => {
    updateAction.value = true
    itemSelected.value = data
    openModalForm.value = true
}

const successSubmit = () => {
    isLoading.value = true
    getData(pagination.value.current_page)
}

const closeModalForm = () => {
    itemSelected.value = ref({})
    openModalForm.value = false
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

const applyFilter = (data) => {
    filter.value = data
    isLoading.value = true
    getData(1)
}

const clearFilter = (data) => {
    filter.value = data
    isLoading.value = true
    getData(1)
}

const handleExportExcel = () => {
    window.open(route('report.balance.exportexcel', { 'start_date': filter.value.start_date, 'end_date': filter.value.end_date }));
}

const handleExportPdf = () => {
    window.open(route('report.balance.exportpdf', { 'start_date': filter.value.start_date, 'end_date': filter.value.end_date }));
}
onMounted(() => {
    getData(1);
});
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <h1 class="text-2xl font-bold md:text-3xl text-slate-800">Balance Sheet Report</h1>
    </div>
    <div class="bg-white border rounded-sm shadow-lg border-slate-200" :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'">
        <header class="items-center justify-between block px-4 py-6 sm:flex">
            <h2 class="font-semibold text-slate-800">
                All Balance Sheet Report <span class="text-slate-400 !font-medium ml">({{ pagination.total }})</span>
            </h2>
            <div class="flex justify-end mt-3 space-x-2 sm:mt-0 sm:justify-between">
                <!-- Filter -->
                <VFilter @apply="applyFilter" @clear="clearFilter" />
                <VButton label="Export Excel" type="success" @click="handleExportExcel" class="mt-auto" />
                <VButton label="Export Pdf" type="danger" @click="handleExportPdf" class="mt-auto" />
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
                <td class="h-16 px-4"> {{ data.code ?? '-' }} </td>
                <td class="h-16 px-4 whitespace-nowrap"> {{ data.name }} </td>
                <td class="h-16 px-4 whitespace-nowrap"> Rp. {{ data.debit }} </td>
                <td class="h-16 px-4 whitespace-nowrap"> Rp. {{ data.credit }} </td>
            </tr>
            <tr>
                <td class="h-16 px-4 font-semibold">
                    Total Balance Sheet
                </td>
                <td class="h-16 px-4 "></td>
                <td class="h-16 px-4 font-semibold"> Rp. {{ totalDebit.toLocaleString('id-ID') }} </td>
                <td class="h-16 px-4 font-semibold"> Rp. {{ totalCredit.toLocaleString('id-ID') }} </td>

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
