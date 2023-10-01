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
import ModalJournalEntry from './ModalJournalEntry.vue';

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
        active: false,
        to: route('transaction.expense.index')
    },
    {
        name: "Detail",
        active: true,
        to: ''
    },
]

const heads = ["Account ", "Desciption", "Total"]

const isLoading = ref(true)
const total = ref(0)
const openModal = ref(false)

const props = defineProps({
    title: string(),
    additional: any()
})

const handleOpenModal = () => {
    openModal.value = true
}

const handleCloseModal = () => {
    openModal.value = false
}


onMounted(() => {
    console.log(props.additional.data)
    // total 
    total.value = props.additional.data.expense_details.reduce((a, b) => parseInt(a) + parseInt(b.total_expense), 0)
});
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl md:text-3xl text-blue-500 font-bold mb-1">
                Expense Detail
            </h1>
            <h3 class="text-md md:text-lg text-blue-500">
                {{ additional.data.no_transaction }}
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
                        {{ additional.data.date }}
                    </p>
                </div>
                <div class="mb-2">
                    <label class="font-bold text-base text-slate-800">No Transaction</label>
                    <p class="text-sm">
                        {{ additional.data.no_transaction }}
                    </p>
                </div>
                <div class="mb-2">
                    <label class="font-bold text-base text-slate-800">Account Payment</label>
                    <p class="text-sm">
                        {{ additional.data.payment_account.code }} - {{ additional.data.payment_account.name }}
                    </p>
                </div>
                <div class="mb-2">
                    <label class="font-bold text-base text-slate-800">Description</label>
                    <p class="text-sm">
                        {{ additional.data.description ?? '-' }}
                    </p>
                </div>
                <div class="mb-2">
                    <p class="text-sm underline text-sky-500 cursor-pointer" @click="handleOpenModal">
                        View Journal Entry
                    </p>
                </div>

            </div>
        </div>

        <div>
            <header class="block justify-between items-center sm:flex px-4 mt-6 mb-3">
                <label class="font-bold text-base text-slate-800">Items</label>
            </header>

            <VDataTable :heads="heads" bordered :divide-y="false">
                <tr v-for="(data, index) in additional.data.expense_details">
                    <td class=" px-4 whitespace-nowrap h-12">{{ data.expense_account.code }} - {{ data.expense_account.name
                    }}</td>
                    <td class=" px-4 whitespace-nowrap h-12">{{ data.expense_account.description ?? '-' }}</td>
                    <td class=" px-4 whitespace-nowrap h-12">Rp. {{ data.total_expense.toLocaleString('id-ID') }}</td>
                </tr>
                <tr class="h-20 border-t">
                    <td colspan="1" />
                    <td class="pl-4 h-12">
                        <!-- balance or not balance -->
                        <span class="font-semibold">Status</span>
                        <br>
                        <span class="text-emerald-600">
                            Balance
                        </span>
                    </td>
                    <td class="pl-4 h-12">
                        <!-- balance or not balance -->
                        <span class="font-semibold">Total</span>
                        <br>
                        <p>
                            Rp. {{ total.toLocaleString('id-ID') }}
                        </p>
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
    <ModalJournalEntry :data="additional.data.journal" :open-dialog="openModal" @close="handleCloseModal" />
</template>
