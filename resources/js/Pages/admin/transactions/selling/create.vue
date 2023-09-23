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

const heads = ["Product *", "Qty *", "Price *", "Subtotal", ""]

const isLoading = ref(false);

const totalPrice = ref(0)

const productEntries = ref([
    {
        product_id: null,
        qty: 0,
        price: 0,
        subtotal: 0
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
        name: "Transaction",
        active: false,
    },
    {
        name: "Sales",
        active: false,
        to: route('transaction.sale.index')
    },
    {
        name: "Create",
        active: true,
        // to: 
    },
]


const handleAddRow = () => {
    productEntries.value.push({
        product_id: null,
        qty: 0,
        price: 0,
        subtotal: 0
    })
}


const getError = (property, index) => {
    return formError.value['product_entries.' + index + '.' + property]
}


const handleDeleteRow = (index) => {
    // if count row == 1 then not delete
    if (productEntries.value.length == 1) {
        return
    }

    // slice row by index 
    productEntries.value.splice(index, 1)
}

const onSelectProduct = (index) => {
    const id = productEntries.value[index].product_id
    axios.get(route('transaction.sale.getproduct', { id: id }))
        .then((res) => {
            productEntries.value[index].qty = 1
            productEntries.value[index].price = parseInt(res.data.data.sale_price)
            productEntries.value[index].subtotal = parseInt(productEntries.value[index].price) * parseInt(productEntries.value[index].qty)
            onChangeSubtotal()
        }).catch((res) => {
            notify({
                type: "error",
                group: "top",
                text: res.response.data.message
            }, 2500)
        }).finally(() => isLoading.value = false)

}

const onChangeQty = (index) => {
    productEntries.value[index].subtotal = parseInt(productEntries.value[index].price) * parseInt(productEntries.value[index].qty)
    onChangeSubtotal()
}

const onChangeSubtotal = () => {
    totalPrice.value = 0
    productEntries.value.forEach((item) => {
        totalPrice.value += parseInt(item.subtotal)
    })
}

const onChangePrice = (index) => {
    productEntries.value[index].subtotal = parseInt(productEntries.value[index].price) * parseInt(productEntries.value[index].qty)
    onChangeSubtotal()
}

const onChangeAmount = () => {
    // count total debit and credit
    let debit = 0
    let credit = 0
    productEntries.value.forEach((item) => {
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
        // update()
    } else {
        create()
    }
}

const create = () => {
    const data = {
        no_transaction: form.value.no_transaction,
        date: form.value.date,
        customer_id: form.value.customer_id,
        account_id: form.value.account_id,
        description: form.value.description,
        product_entries: productEntries.value
    }

    isLoading.value = true
    debounce(axios.post(route('transaction.sale.store'), data)
        .then((res) => {
            isLoading.value = false
            totalPrice.value = 0
            form.value = ref({})

            productEntries.value = [
                {
                    product_id: null,
                    qty: 0,
                    price: 0,
                    subtotal: 0
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
        journal_entries: productEntries.value
    }

    isLoading.value = true
    debounce(axios.post(route('journals.journal.update', { id: props.additional.data.id }), data)
        .then((res) => {
            isLoading.value = false

            isBalance.value = false
            totalDebit.value = 0
            totalCredit.value = 0
            form.value = ref({})

            productEntries.value = [
                {
                    product_id: null,
                    description: '',
                    debit: 0,
                    credit: 0,
                }, {
                    product_id: null,
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
        productEntries.value = props.additional.data.journal_details
        totalDebit.value = props.additional.data.journal_details.reduce((a, b) => parseInt(a) + (parseInt(b['debit']) || 0), 0)
        totalCredit.value = props.additional.data.journal_details.reduce((a, b) => parseInt(a) + (parseInt(b['credit']) || 0), 0)
        isBalance.value = totalDebit.value == totalCredit.value
    }
})

</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Create Sale</h1>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 pb-20 min-h-[40vh] sm:min-h-[50vh]">
        <section class="grid grid-cols-1 md:grid-cols-4 gap-4 px-4 pt-4">
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
        <section class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 mb-5 !z-60">
            <VSelect label="Customer" class="" placeholder="Choose Customer" :required="true" v-model="form.customer_id"
                :options="additional.customer_options" :errorMessage="formError.customer_id" @update:modelValue="" />
            <VSelect label="Deposit to Account" class="" placeholder="Choose Account" :required="true"
                v-model="form.account_id" :options="additional.account_options" :errorMessage="formError.account_id"
                @update:modelValue="" />
        </section>

        <section class="px-1">
            <VDataTable :heads="heads" :divide-y="false" bordered :isLoading="isLoading">
                <tr v-if="isLoading">
                    <td class="h-[100%] overflow-hidden my-2" :colspan="heads.length">
                        <VLoading />
                    </td>
                </tr>
                <template v-else>
                    <tr v-for="(data, index) in productEntries" :key="index">
                        <td class="h-14 w-1/4 pl-3">
                            <VSelect class="w-60 !z-0" placeholder="Choose Product" :required="true" :clearable="false"
                                v-model="productEntries[index].product_id" :options="additional.product_options"
                                :errorMessage="getError('product_id', index)" @update:modelValue="onSelectProduct(index)" />
                        </td>
                        <td class="h-14 w-1/4 pl-3">
                            <VInput class="w-60 !z-0" placeholder="Input qty" :required="false"
                                v-model="productEntries[index].qty" :errorMessage="getError('qty', index)"
                                @update:modelValue="onChangeQty(index)" type="number" />
                        </td>
                        <td class="h-14 w-1/4 pl-3">
                            <VInput class="w-60 !z-0" placeholder="Input Price" :required="false"
                                v-model="productEntries[index].price" :errorMessage="getError('price', index)"
                                @update:modelValue="onChangePrice(index)" type="number" />
                        </td>
                        <td class="h-14 w-1/4 pl-3">
                            <VInput class="w-60 !z-0" placeholder="Subtotal" :required="false"
                                v-model="productEntries[index].subtotal" :errorMessage="getError('subtotal', index)"
                                @update:modelValue="onChangeSubtotal" type="number" disabled />
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
                        <td colspan="3" class="h-12 w-1/4 pl-3">
                            <VButton label="Add Row" type="primary" @click="handleAddRow" size="small" />
                        </td>
                        <td class="h-12 w-1/4 pl-3">
                            <span class="font-semibold text-lg">Total</span> <br>
                            <span class="text-md">
                                Rp. {{ totalPrice }}
                            </span>
                        </td>
                    </tr>
                    <tr class="">
                        <td colspan="3" />
                        <td class="h-12 w-1/4 pl-3">
                            <VTextarea placeholder="Insert Description" label="Description" v-model="form.description"
                                :errorMessage="formError.description" @update:modelValue="formError.description = ''" />
                        </td>
                    </tr>
                </template>
            </VDataTable>
        </section>

        <section class="p-4 flex justify-end">
            <VButton :is-loading="isLoading" :label="additional.data ? 'Update Journal' : 'Create Journal'" type="primary"
                @click="submit" />
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