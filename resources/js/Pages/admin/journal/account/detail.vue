<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import VAlert from '@/components/VAlert/index.vue';
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VDataTable from '@/components/VDataTable/index.vue';
import VDropdownEditMenu from '@/components/VDropdownEditMenu/index.vue';
import VLoading from '@/components/VLoading/index.vue';
import VPagination from '@/components/VPagination/index.vue';
import VEdit from '@/components/src/icons/VEdit.vue';
import VEmpty from '@/components/src/icons/VEmpty.vue';
import VTrash from '@/components/src/icons/VTrash.vue';
import debounce from "@/composables/debounce";
import AppLayout from '@/layouts/apps.vue';
import { Head } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { notify } from "notiwind";
import { onMounted, reactive, ref } from "vue";
import { object, string } from "vue-types";
import VFilter from './Filter.vue';

const query = ref([])
const searchFilter = ref("");
const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route('dashboard.index')
    },
    {
        name: "Accounting",
        active: false,
    },
    {
        name: "Account",
        active: false,
        to: route('journals.accounts.index')
    },
    {
        name: "Detail",
        active: true,
    },
]

const alertData = reactive({
    headerLabel: '',
    contentLabel: '',
    closeLabel: '',
    submitLabel: '',
})

const openAlert = ref(false)

const heads = ["Date", "No Transaction", "Description", "Contact", "Debit", "Credit"]
const isLoading = ref(true)

const props = defineProps({
    title: string(),
    additional: object(),
    data: object()
})

const getData = debounce(async (page) => {
    axios.get(route('journals.accounts.getdata'), {
        params: {
            page: page,
            search: searchFilter.value
        }
    }).then((res) => {
        query.value = res.data.data
    }).catch((res) => {
        notify({
            type: "error",
            group: "top",
            text: res.response.data.message
        }, 2500)
    }).finally(() => isLoading.value = false)
}, 500);

const searchHandle = (search) => {
    searchFilter.value = search
    isLoading.value = true
    getData(1)
};


onMounted(() => {
    console.log(props.data.data)
});
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div>
            <h1 class="text-2xl font-bold md:text-3xl text-slate-800">Account Detail</h1>
            <h3 class="text-slate-800">
                <span class="font-bold"> ({{ data.data.code }}) {{ data.data.name }} </span> - {{ data.data.category.name }}
            </h3>
        </div>
    </div>
    <div class="bg-white border rounded-sm shadow-lg border-slate-200" :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'">
        <header class="items-center justify-between block px-4 py-6 sm:flex">
            <h2 class="font-semibold text-slate-800">
                All Transaction
            </h2>
            <div class="flex justify-end mt-3 space-x-2 sm:mt-0 sm:justify-between">
                <!-- Filter -->
                <VFilter @search="searchHandle" />
            </div>
        </header>

        <VDataTable :heads="heads" :isLoading="isLoading">
            <tr v-for="(data, index) in data.data.transaction" :key="index">
                <td class="h-16 px-4 whitespace-nowrap"> {{ data.date }} </td>
                <td class="h-16 px-4 whitespace-nowrap"> {{ data.no_transaction }} </td>
                <td class="h-16 px-4 whitespace-nowrap"> {{ data.description ?? '-' }} </td>
                <td class="h-16 px-4 whitespace-nowrap"> {{ data.contact }} </td>
                <td class="h-16 px-4 whitespace-nowrap">Rp. {{ data.debit }} </td>
                <td class="h-16 px-4 whitespace-nowrap">Rp. {{ data.credit }} </td>
            </tr>
            <tr>
                <td colspan="4" />
                <td class="h-16 px-4 whitespace-nowrap font-bold text-base" align="right"> Total :</td>
                <td class="h-16 px-4 whitespace-nowrap font-bold text-base"> 
                    <!-- if data.data.total is minus give class text-red -->
                    <span :class="parseInt(data.data.total) < 0 && 'text-red-500'">
                        Rp. {{ data.data.total }}
                    </span>
                </td>
            </tr>
        </VDataTable>
    </div>
    <VAlert :open-dialog="openAlert" @closeAlert="closeAlert" @submitAlert="deleteHandle" type="danger"
        :headerLabel="alertData.headerLabel" :content-label="alertData.contentLabel" :close-label="alertData.closeLabel"
        :submit-label="alertData.submitLabel" />
</template>
