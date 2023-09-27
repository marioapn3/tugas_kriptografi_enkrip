<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import dayjs from 'dayjs';
import axios from 'axios';
import debounce from "@/composables/debounce"
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VButton from '@/components/VButton/index.vue';
import VDataTable from '@/components/VDataTable/index.vue';
import VInput from '@/components/VInput/index.vue';
import VSelect from '@/components/VSelect/index.vue';
import VTrash from '@/components/src/icons/VTrash.vue';
import VTextarea from '@/components/VTextarea/index.vue';
import AppLayout from '@/layouts/apps.vue';
import VLoading from '@/components/VLoading/index.vue';
import { Head } from "@inertiajs/inertia-vue3";
import { onMounted, ref } from 'vue';
import { object, string } from "vue-types";
import { notify } from 'notiwind';

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
        account_id: null,
        description: '',
        debit: 0,
        credit: 0,
    }, {
        account_id: null,
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
        to: route('journals.journal.index')
    },
    {
        name: props.additional.data ? 'Edit' :'Create' ,
        active: true,
        // to:
    },
]


const handleAddRow = () => {
    journalEntries.value.push({
        account_id: null,
        description: '',
        debit: 0,
        credit: 0,
    })
}


const getError = (property, index) => {
    return formError.value['journal_entries.' + index + '.' + property]
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


const submit = () => {
    if (props.additional.data) {
        update()
    } else {
        create()
    }
}

const create = () => {
    const data = {
        no_transaction: form.value.no_transaction,
        date: form.value.date,
        description: form.value.description,
        journal_entries: journalEntries.value
    }

    isLoading.value = true
    debounce(axios.post(route('journals.journal.store'), data)
        .then((res) => {
            isLoading.value = false

            isBalance.value = false
            totalDebit.value = 0
            totalCredit.value = 0
            form.value = ref({})

            journalEntries.value = [
                {
                    account_id: null,
                    description: '',
                    debit: 0,
                    credit: 0,
                }, {
                    account_id: null,
                    description: '',
                    debit: 0,
                    credit: 0,
                }
            ]

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
        }).finally(() => isLoading.value = false), 1000)

}

const update = () => {
    const data = {
        no_transaction: form.value.no_transaction,
        date: form.value.date,
        description: form.value.description,
        journal_entries: journalEntries.value
    }

    isLoading.value = true
    debounce(axios.post(route('journals.journal.update', { id: props.additional.data.id }), data)
        .then((res) => {
            isLoading.value = false

            isBalance.value = false
            totalDebit.value = 0
            totalCredit.value = 0
            form.value = ref({})

            journalEntries.value = [
                {
                    account_id: null,
                    description: '',
                    debit: 0,
                    credit: 0,
                }, {
                    account_id: null,
                    description: '',
                    debit: 0,
                    credit: 0,
                }
            ]

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
        }).finally(() => isLoading.value = false), 1000)

}

onMounted(() => {
    if (props.additional.data) {
        form.value = props.additional.data
        journalEntries.value = props.additional.data.journal_details
        totalDebit.value = props.additional.data.journal_details.reduce((a, b) => parseInt(a) + (parseInt(b['debit']) || 0), 0)
        totalCredit.value = props.additional.data.journal_details.reduce((a, b) => parseInt(a) + (parseInt(b['credit']) || 0), 0)
        isBalance.value = totalDebit.value == totalCredit.value
    }
})

</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <h1 class="text-2xl font-bold md:text-3xl text-slate-800">{{ additional.data ? 'Edit' :'Create' }} Journal</h1>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 pb-20 min-h-[40vh] sm:min-h-[50vh]">
        <section class="grid grid-cols-1 gap-4 p-4 mb-5 md:grid-cols-4">
            <VInput tooltip tooltipBg="white" placeholder="Auto" label="No Transaction" :required="false"
                v-model="form.no_transaction" :errorMessage="formError.no_transaction"
                @update:modelValue="formError.no_transaction = ''">
                <template v-slot:tooltip>
                    <div class="text-xs">
                        <div class="mb-1 font-semibold text-slate-800">No Transaction.</div>
                        <div class="mb-0.5">The transaction number will be automatically created by the system, and you can
                            create your own version</div>
                    </div>
                </template>
            </VInput>
            <div>
                <label class="block mb-1 text-sm font-medium text-slate-600">
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
            <VDataTable :heads="heads" :divide-y="false" bordered :isLoading="isLoading">
                <tr v-if="isLoading">
                    <td class="h-[100%] overflow-hidden my-2" :colspan="heads.length">
                        <VLoading />
                    </td>
                </tr>
                <template v-else>
                    <tr v-for="(data, index) in journalEntries" :key="index">
                        <td class="w-1/4 pl-3 h-14">
                            <VSelect class="w-60 !z-0" placeholder="Choose Account" :required="true"
                                v-model="journalEntries[index].account_id" :options="additional.account_options"
                                :errorMessage="getError('account_id', index)" @update:modelValue="" />
                        </td>
                        <td class="w-1/4 pl-3 h-14">
                            <VInput class="w-60 !z-0" placeholder="Input Description" :required="false"
                                v-model="journalEntries[index].description" :errorMessage="formError.description"
                                @update:modelValue="formError.description = ''" />
                        </td>
                        <td class="w-1/4 pl-3 h-14">
                            <VInput class="w-60 !z-0" placeholder="Input Debit" :required="false"
                                v-model="journalEntries[index].debit" :errorMessage="getError('debit', index)"
                                @update:modelValue="onChangeAmount" type="number" />
                        </td>
                        <td class="w-1/4 pl-3 h-14">
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
                        <td colspan="2" class="w-1/4 h-12 pl-3">
                            <VButton label="Add Row" type="primary" @click="handleAddRow" size="small" />
                        </td>
                        <td class="w-1/4 h-12 pl-3">
                            <span class="font-semibold">Total Debit</span> <br>
                            Rp. {{ totalDebit }}
                        </td>
                        <td class="w-1/4 h-12 pl-3">
                            <span class="font-semibold">Total Credit</span> <br>
                            Rp. {{ totalCredit }}
                        </td>
                    </tr>
                    <tr class="">
                        <td colspan="2" />
                        <td class="w-1/4 h-12 pl-3">
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
                        <td class="w-1/4 h-12 pl-3">
                            <VTextarea placeholder="Insert Description" label="Description" v-model="form.description"
                                :errorMessage="formError.description" @update:modelValue="formError.description = ''" />
                        </td>
                    </tr>
                </template>
            </VDataTable>
        </section>

        <section class="flex justify-end p-4">
            <VButton :is-loading="isLoading" :label="additional.data ? 'Update Journal' : 'Create Journal'" type="primary"
                @click="submit" :disabled="!isBalance" />
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
