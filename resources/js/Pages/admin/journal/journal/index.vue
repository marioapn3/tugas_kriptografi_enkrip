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
import { Inertia } from "@inertiajs/inertia";
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
        name: "Accounting",
        active: false,
    },
    {
        name: "Journal",
        active: true,
        to: route('journals.account-categories.index')
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
const itemSelected = ref({})
const openAlert = ref(false)
const heads = ["No", "Date", "Description", "Account", "Debit", "Credit", "Status", ""]
const isLoading = ref(true)

const props = defineProps({
    title: string(),
    additional: object(),
})

const getData = debounce(async (page) => {
    axios.get(route('journals.journal.getdata'), {
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
    Inertia.visit(route('journals.journal.create'));
}

const handleEdit = (id) => {
    Inertia.visit(route('journals.journal.edit', id));
}

const handleDetail = (id) => {
    Inertia.visit(route('journals.journal.show', id));
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
    axios.delete(route('journals.journal.delete', { 'id': itemSelected.value.id })
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
        <h1 class="text-2xl font-bold md:text-3xl text-slate-800">Journal</h1>
    </div>
    <div class="bg-white border rounded-sm shadow-lg border-slate-200" :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'">
        <header class="items-center justify-between block px-4 py-6 sm:flex">
            <h2 class="font-semibold text-slate-800">
                All Journals <span class="text-slate-400 !font-medium ml">({{ pagination.total }})</span>
            </h2>
            <div class="flex justify-end mt-3 space-x-2 sm:mt-0 sm:justify-between">
                <!-- Filter -->
                <VFilter @search="searchHandle" />
                <VButton label="Create Journal" type="primary" @click="handleCreate" class="mt-auto" />
            </div>
        </header>

        <VDataTable :heads="heads" :isLoading="isLoading" bordered>
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
                <!-- <td class="h-16 px-4 whitespace-nowrap"> {{ index + 1 }} </td> -->
                <td class=" px-4 whitespace-nowrap h-12 text-sky-600 underline cursor-pointer"
                    @click="handleDetail(data.id)">
                    {{ data.no_transaction ?? '-' }}
                </td>
                <td class="h-24 px-4 whitespace-nowrap">{{ data.date }} </td>
                <td class="h-24 px-4 whitespace-nowrap">
                    <span v-if="data.purchase" class="text-sky-600 underline cursor-pointer">
                        {{ data.purchase.no_transaction }}
                    </span>
                    <span v-else-if="data.sales" class="text-sky-600 underline cursor-pointer">
                        {{ data.sales.no_transaction }}
                    </span>
                    <span v-else-if="data.expense" class="text-sky-600 underline cursor-pointer">
                        {{ data.expense.no_transaction }}
                    </span>
                    <span v-else>
                        {{ data.description}}
                    </span>
                </td>
                <td class="h-24 px-4 whitespace-nowrap">
                    <table>
                        <tr class="h-10" v-for="(detail, index) in data.journal_entries">
                            <td>
                                {{ detail.account_name }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="h-24 px-4 whitespace-nowrap">
                    <table>
                        <tr class="h-10" v-for="(detail, index) in data.journal_entries">
                            <td>
                                Rp. {{ detail.debit }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="h-24 px-4 whitespace-nowrap">
                    <table>
                        <tr class="h-10" v-for="(detail, index) in data.journal_entries">
                            <td>
                                Rp. {{ detail.credit }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="h-16 px-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Balance
                    </span>
                </td>

                <td class="h-16 px-4 text-right whitespace-nowrap">
                    <VDropdownEditMenu class="relative inline-flex r-0" :align="'right'"
                        :last="index === query.length - 1 ? true : false">
                        <li class="cursor-pointer hover:bg-slate-100" @click="handleEdit(data.id)">
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
    <!-- <VModalForm :data="itemSelected" :update-action="updateAction" :open-dialog="openModalForm" @close="closeModalForm"
        @successSubmit="successSubmit" :additional="additional" /> -->
</template>
