<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VDataTable from '@/components/VDataTable/index.vue';
import AppLayout from '@/layouts/apps.vue';
import VButton from '@/components/VButton/index.vue';
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
        name: "Accounting",
        active: false,
    },
    {
        name: "Journal",
        active: false,
        to: route('journals.journal.index')
    },
    {
        name: "Detail",
        active: true,
        to: ''
    },
]

const heads = ["Account Code", "Account Name", "Desciption", "Debit", "Credit"]

const isLoading = ref(true)

const props = defineProps({
    title: string(),
    additional: any()
})


onMounted(() => {
    console.log(props.additional.data)
});
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl md:text-3xl text-blue-500 font-bold mb-1">
                Journal Detail
            </h1>
            <h3 class="text-md md:text-lg text-blue-500">
                {{ additional.data.data.no_transaction }}
            </h3>
        </div>
        <div>
            <span
                class="px-3 md:px-5 inline-flex text-xs md:text-base leading-6 md:leading-8 font-medium rounded-full bg-green-200 text-green-800">
                Success
            </span>
        </div>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 pb-20"
        :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'">
        <div class="mt-5">
            <div class="grid md:grid-cols-4 grid-cols-1 gap-3 px-4">
                <div class="mb-2">
                    <label class="font-bold text-base text-slate-800">Date Transaction</label>
                    <p class="text-sm">
                        {{ additional.data.data.date }}
                    </p>
                </div>
                <div class="mb-2">
                    <label class="font-bold text-base text-slate-800">No Transaction</label>
                    <p class="text-sm">
                        {{ additional.data.data.no_transaction }}
                    </p>
                </div>
                <div class="mb-2">
                    <label class="font-bold text-base text-slate-800">Description</label>
                    <p class="text-sm">
                        {{ additional.data.data.description ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div>
            <header class="block justify-between items-center sm:flex px-4 mt-6 mb-3">
                <label class="font-bold text-base text-slate-800">Items</label>
            </header>

            <VDataTable :heads="heads" bordered :divide-y="false">
                <tr v-for="(data, index) in additional.data.data.journal_entries">
                    <td class=" px-4 whitespace-nowrap h-12">{{ data.account_code }}</td>
                    <td class=" px-4 whitespace-nowrap h-12 text-sky-600 underline cursor-pointer">
                        {{ data.account_name }}
                    </td>
                    <td class=" px-4 whitespace-nowrap h-12">
                        {{ data.description ?? '-' }}
                    </td>
                    <td class=" px-4 whitespace-nowrap h-12"> Rp. {{ data.debit }}</td>
                    <td class=" px-4 whitespace-nowrap h-12"> Rp. {{ data.credit }}</td>
                </tr>
                <tr class="h-20 border-t">
                    <td colspan="3" />
                    <td class="pl-4 h-12">
                        <br>
                        <!-- balance or not balance -->
                        <span class="font-semibold">Total Debit</span>
                        <br>
                        <p>
                            Rp. {{ additional.data.data.total_debit }}
                        </p>
                    </td>
                    <td class="pl-4 h-12">
                        <br>
                        <!-- balance or not balance -->
                        <span class="font-semibold">Total Credit</span>
                        <br>
                        <p>
                            Rp. {{ additional.data.data.total_credit }}
                        </p>
                    </td>
                </tr>
                <tr class="">
                    <td colspan="3" />
                    <td class="w-1/4 h-12 pl-4">
                        <br>
                        <!-- balance or not balance -->
                        <span class="font-semibold">Status</span>
                        <br>
                        <span class="text-emerald-600">
                            Balance
                        </span>
                        <br>
                        <br>
                    </td>
                </tr>
            </VDataTable>
            <div class="flex justify-between px-4 mt-3">
                <VButton label="Delete" type="danger" class="mt-auto" />
                <div class="flex">
                    <VButton label="Edit" type="success" class="mt-auto" />
                    <VButton label="Print & Review" type="primary" class="mt-auto" />
                </div>
            </div>

        </div>
    </div>
</template>