<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VButton from '@/components/VButton/index.vue';
import VDataTable from '@/components/VDataTable/index.vue';
import VInput from '@/components/VInput/index.vue';
import VSelect from '@/components/VSelect/index.vue';
import VTrash from '@/components/src/icons/VTrash.vue';
import VTextarea from '@/components/VTextarea/index.vue';
import AppLayout from '@/layouts/apps.vue';
import { Head } from "@inertiajs/inertia-vue3";
import dayjs from 'dayjs';
import { ref } from 'vue';
import { object, string } from "vue-types";
import { notify } from 'notiwind';
import axios from 'axios';

const props = defineProps({
    title: string(),
    additional: object(),
})

const heads = ["Account *", "Description", "Debit *", "Credit *", ""]

const isLoading = ref(false);

const isBalance = ref(false);
const totalDebit = ref(0);
const totalCredit = ref(0);
const journalEntries = ref([
    {
        account_id: '',
        description: '',
        debit: 0,
        credit: 0,
    }, {
        account_id: '',
        description: '',
        debit: 0,
        credit: 0,
    }
])
const formError = ref({})
const form = ref({})

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
        to: route('contacts.customer.index')
    },
    {
        name: "Create",
        active: true,
        // to: 
    },
]


const handleAddRow = () => {
    journalEntries.value.push({
        account_id: '',
        description: '',
        debit: 0,
        credit: 0,
    })
}


const getError = (property, index) => {
    return formError.value['journal_entries.' + index + '.' + property]
    // console.log(formError.value)
}


const handleDeleteRow = (index) => {
    // if count row == 1 then not delete
    if (journalEntries.value.length == 1) {
        return
    }

    // slice row by index
    journalEntries.value.splice(index, 1)
}

const onChangeAmount = () => {
    // count total debit and credit
    let debit = 0
    let credit = 0
    journalEntries.value.forEach((item) => {
        debit += parseInt(item.debit)
        credit += parseInt(item.credit)
    })

    totalDebit.value = debit
    totalCredit.value = credit

    // check is balance for total debit and credit
    // if debit not equal credit then not balance
    if (debit != credit) {
        isBalance.value = false
    } else {
        isBalance.value = true
    }
}

const handleDate = () => {
    if (form.value.date) {
        formError.value.date = "";
        form.value.date = dayjs(form.value.date).format("YYYY-MM-DD");
    }
}


const validateJournalEntry = () => {
    // validate for input journal entry 
    let error = {}
    journalEntries.value.forEach((item, index) => {
        if (item.account_id == '') {
            error.account_id = 'Account is required'
        } else {
            // remove account_id from error
            delete error.account_id
        }
    })

    formError.value = error
}

const submit = () => {

    validateJournalEntry()

    // check is formError empty or not
    if (Object.keys(formError.value).length > 0) {
        return
    }

    // if formError empty then submit

    const data = {
        no_transaction: form.value.no_transaction,
        date: form.value.date,
        description: form.value.description,
        journal_entries: journalEntries.value
    }

    isLoading.value = true
    axios.post(route('journals.journal.store'), data)
        .then((res) => {
            form.value = ref({})
            journalEntries.value = ref([
                {
                    account_id: '',
                    description: '',
                    debit: 0,
                    credit: 0,
                }, {
                    account_id: '',
                    description: '',
                    debit: 0,
                    credit: 0,
                }
            ])

            notify({
                type: "success",
                group: "top",
                text: res.data.meta.message
            }, 2500)
        }).catch((res) => {
            // Handle validation errors
            const result = res.response.data
            const metaError = res.response.data.meta?.error
            if (result.hasOwnProperty('errors')) {
                formError.value = ref({});
                Object.keys(result.errors).map((key) => {
                    formError.value[key] = result.errors[key].toString();
                });
            }

            if (metaError) {
                notify({
                    type: "error",
                    group: "top",
                    text: metaError
                }, 2500)
            } else {
                notify({
                    type: "error",
                    group: "top",
                    text: result.message
                }, 2500)
            }
        }).finally(() => isLoading.value = false)

}

</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Create Journal</h1>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 pb-20 min-h-[40vh] sm:min-h-[50vh]">
        <section class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 mb-5">
            <VInput tooltip tooltipBg="white" placeholder="Auto" label="No Transaction" :required="false"
                v-model="form.no_transaction" :errorMessage="formError.no_transaction"
                @update:modelValue="formError.no_transaction = ''">
                <template v-slot:tooltip>
                    <div class="text-xs">
                        <div class="font-semibold text-slate-800 mb-1">No Transaction.</div>
                        <div class="mb-0.5">The transaction number will be automatically created by the system, and you can
                            create your own version</div>
                    </div>
                </template>
            </VInput>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">
                    Date <span class="text-rose-500">*</span>
                </label>
                <Datepicker v-model="form.date" @update:modelValue="handleDate" :enableTimePicker="false" position="left"
                    :clearable="false" format="dd MMMM yyyy" previewFormat="dd MMMM yyyy" placeholder="Date"
                    :class="{ 'date_error': formError.date }" />
                <div class="text-xs" :class="[{ 'text-rose-500': formError.date }]" v-if="formError.date">
                    {{ formError.date }}
                </div>
            </div>
        </section>

        <section class="px-1">
            <VDataTable :heads="heads" :divide-y="false" bordered>
                <tr v-for="(data, index) in journalEntries" :key="index">
                    <td class="h-14 w-1/4 pl-3">
                        <VSelect class="w-60 !z-0" placeholder="Choose Account" :required="true"
                            v-model="journalEntries[index].account_id" :options="additional.account_options"
                            :errorMessage="formError.account_id" @update:modelValue="''" />
                    </td>
                    <td class="h-14 w-1/4 pl-3">
                        <VInput class="w-60 !z-0" placeholder="Input Description" :required="false"
                            v-model="journalEntries[index].description" :errorMessage="formError.description"
                            @update:modelValue="formError.description = ''" />
                    </td>
                    <td class="h-14 w-1/4 pl-3">
                        <VInput class="w-60 !z-0" placeholder="Input Debit" :required="false"
                            v-model="journalEntries[index].debit" :errorMessage="getError('debit', index)"
                            @update:modelValue="onChangeAmount" type="number" />
                    </td>
                    <td class="h-14 w-1/4 pl-3">
                        <VInput class="w-60 !z-0" placeholder="Input Credit" :required="false"
                            v-model="journalEntries[index].credit" :errorMessage="getError('credit', index)"
                            @update:modelValue="onChangeAmount" type="number" />
                    </td>
                    <td class="h-14">
                        <div class="cursor-pointer" @click="handleDeleteRow(index)">
                            <span>
                                <VTrash color="danger" />
                            </span>
                        </div>
                    </td>
                </tr>
                <tr class="h-20 border-t">
                    <td colspan="2" class="h-12 w-1/4 pl-3">
                        <VButton label="Add Row" type="primary" @click="handleAddRow" size="small" />
                    </td>
                    <td class="h-12 w-1/4 pl-3">
                        <span class="font-semibold">Total Debit</span> <br>
                        Rp. {{ totalDebit }}
                    </td>
                    <td class="h-12 w-1/4 pl-3">
                        <span class="font-semibold">Total Credit</span> <br>
                        Rp. {{ totalCredit }}
                    </td>
                </tr>
                <tr class="">

                    <td colspan="2" />
                    <td class="h-12 w-1/4 pl-3">
                        <!-- balance or not balance -->
                        <span class="font-semibold">Status</span>
                        <br>
                        <span
                            :class="[{ 'text-emerald-600': isBalance == true }, { 'text-rose-600': isBalance == false }]">{{
                                isBalance ? 'Balance' : 'Not Balance'
                            }}</span>
                        <br>
                        <br>
                    </td>
                    <td class="h-12 w-1/4 pl-3">
                        <VTextarea placeholder="Insert Description" label="Description" v-model="form.description"
                            :errorMessage="formError.description" @update:modelValue="formError.description = ''" />
                    </td>
                </tr>
            </VDataTable>
        </section>

        <section class="p-4 flex justify-end">
            <VButton :is-loading="isLoading" label="Create Journal" type="primary" @click="submit" />
        </section>
    </div>
</template>

<style>
.dp__select {
    color: #4F8CF6 !important;
}

.date_error {
    --dp-border-color: #dc3545 !important;
}
</style>