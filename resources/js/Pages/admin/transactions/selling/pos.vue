<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import { onMounted, ref } from 'vue';
import { array, object, string } from 'vue-types';
import AppLayout from '@/layouts/apps.vue';
import VSetting from "@/components/src/icons/VSetting.vue";
import VLoading from '@/components/VLoading/index.vue';
import VSelect from '@/components/VSelect/index.vue';
import VButtonRounded from "@/components/VButtonRounded/index.vue";
import VFilter from "./FilterPos.vue"
import { Head } from '@inertiajs/inertia-vue3';
import debounce from '@/composables/debounce';
import axios from 'axios';
import { notify } from 'notiwind';
import VEmpty from '@/components/src/icons/VEmpty.vue';
import VModalSettingPos from './ModalSettingPos.vue';
import VModalSuccess from './ModalSuccess.vue';

const props = defineProps({
    title: string(),
    filter: object(),
    query: array(),
    modules: array(),
    additionals: object()
})

const formError = ref({})
const form = ref({})

const query = ref([])
const cart = ref([])
const searchFilter = ref("");
const isLoading = ref(true)
const openModalSetting = ref(false)
const openModalSuccess = ref(false)

const searchHandle = (search) => {
    searchFilter.value = search
    isLoading.value = true
    getData(1)
};

const getData = debounce(async () => {
    axios.get(route('transaction.pos.getproduct'), {
        params: {
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

const addToCart = (data) => {
    console.log(data)

    // check if stock is 0 cannot add to cart
    if (data.stock === 0) {
        notify({
            type: "warning",
            title: "Sorry",
            group: "top",
            text: "This product is out of stock"
        }, 2500)
        return
    }

    // if data product is same update quantity only
    // else add new product to cart
    if (cart.value.length > 0) {
        const index = cart.value.findIndex((item) => item.id === data.id)
        if (index !== -1) {
            // check if quantity is same with stock
            if (cart.value[index].qty === data.stock) {
                notify({
                    type: "warning",
                    title: "Sorry",
                    group: "top",
                    text: "Quantity has reached the limit"
                }, 2500)
                return
            }
            cart.value[index].qty += 1
        } else {
            cart.value.push({
                id: data.id,
                name: data.name,
                price: data.sale_price_number_format,
                qty: 1,
                image: data.image,
                image_preview: data.image_preview
            })
        }
    } else {
        cart.value.push({
            id: data.id,
            name: data.name,
            price: data.sale_price_number_format,
            qty: 1,
            image: data.image,
            image_preview: data.image_preview
        })
    }
}

const updateQuantity = (index, action) => {
    // if action is minus
    // check if quantity is 1
    // if 1 remove from cart
    // else minus quantity
    if (action === 'minus') {
        if (cart.value[index].qty === 1) {
            cart.value.splice(index, 1)
        } else {
            cart.value[index].qty -= 1
        }
    } else {
        // if action is plus
        // check if quantity is same with stock
        // if same show alert
        // else plus quantity

        // get data product by id
        const data = query.value.find((item) => item.id === cart.value[index].id)
        // check if quantity is same with stock
        if (cart.value[index].qty === data.stock) {
            notify({
                type: "warning",
                title: "Sorry",
                group: "top",
                text: "Quantity has reached the limit"
            }, 2500)
            return
        }

        cart.value[index].qty += 1
    }
}

const handleOpenModalSetting = () => {
    openModalSetting.value = !openModalSetting.value
}


const create = () => {
    const data = {
        no_transaction: null,
        date: props.additionals.cart_setting.date,
        account_id: props.additionals.cart_setting.deposit_to_account_id,
        customer_id: form.value.customer_id,
        description: 'POS Transaction',
        product_entries: cart.value.map((item) => {
            return {
                product_id: item.id,
                qty: item.qty,
                price: item.price,
                subtotal: item.qty * item.price
            }
        })
    }

    isLoading.value = true
    debounce(axios.post(route('transaction.sale.store'), data)
        .then((res) => {
            isLoading.value = false

            // reload this

            openModalSuccess.value = true

            // getData();
            // notify({
            //     type: "success",
            //     group: "top",
            //     text: 'Transaction success'
            // }, 1000)

            // setTimeout(() => {
            //     window.location.reload()
            // }, 1500)
        }).catch((res) => {
            // Handle validation errors

            // console.log(res)
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
        }).finally(() => isLoading.value = false), 2000)
}


onMounted(() => {
    getData();

    form.value.customer_id = 1
});
</script>

<template>
    <Head :title="props.title" />
    <div class="hidden sm:flex gap-3 flex-wrap ">
        <section class="h-[calc(100vh-110px)] p-5 bg-white w-full md:w-[65%] rounded-md overflow-hidden">
            <VFilter @search="searchHandle" />

            <div
                class="max-h-[calc(100vh-180px)] overflow-auto w-full grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 pb-10 mt-7">
                <div class="col-span-full" v-if="isLoading">
                    <VLoading />
                </div>
                <div class="h-48 rounded-md cursor-pointer md:h-52 bg-slate-100 hover:bg-slate-200 border border-slate-100 hover:border-slate-200 transition-all"
                    v-for="(data, index) in query" :key="index" v-else @click="addToCart(data)">
                    <img class="w-full h-32 md:h-[142px]  object-cover rounded-t-md bg-white"
                        :src="data.image ? data.image_preview : 'https://klipaa.com/images/default_image.png'" alt="">
                    <div class="flex flex-col gap-2 px-2 pt-2">
                        <h1 class="text-sm font-semibold truncate" v-tooltip="{ content: data.name }">
                            {{ data.name }}
                        </h1>
                        <div class="flex items-center justify-between">
                            <p class="text-xs">Rp. {{ data.sale_price }}</p>
                            <p class="text-xs">{{ data.stock }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <section class="px-5 py-3 h-[calc(100vh-110px)] bg-white w-full md:w-[33%] rounded-md">
            <section class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-black">Cart</h1>
                <div class="p-1 transition-all rounded cursor-pointer bg-slate-100 hover:bg-slate-200"
                    @click="handleOpenModalSetting()">
                    <VSetting />
                </div>
            </section>

            <section class="mt-7">
                <VSelect placeholder="Select Customer" :required="true" v-model="form.customer_id"
                    :options="additionals.customer_list" :errorMessage="formError.customer_id"
                    @update:modelValue="formError.customer_id = ''" />
            </section>

            <section class="mt-5 h-[calc(100vh-55vh)] overflow-auto w-full">
                <div class="col-span-full" v-if="isLoading">
                    <VLoading />
                </div>
                <div v-else-if="!isLoading && cart.length === 0" class="flex flex-col items-center w-full my-32">
                    <div class="text-md font-medium mt-9 text-slate-500">
                        Let's add some product to cart
                    </div>
                </div>
                <div v-for="(data, index) in cart" :key="index" v-else
                    class="flex flex-row w-full gap-2 my-3 min-h-16 border border-slate-100 p-1 rounded-md">
                    <img class="hidden object-cover w-16 h-16 rounded-md xl:block "
                        :src="data.image ? data.image_preview : 'https://klipaa.com/images/default_image.png'" alt="">
                    <div class="flex flex-col justify-between w-full">
                        <h2 class="text-sm font-semibold lg:text-md">
                            {{ data.name }}
                        </h2>
                        <div class="flex justify-between w-full ">
                            <p class="text-xs font-medium lg:text-sm">Rp. {{ data.price?.toLocaleString('id-ID') }}</p>

                            <div class="flex items-center">
                                <!-- button minus -->
                                <button class="px-2 py-1 transition-all rounded-full bg-slate-200 hover:bg-slate-400"
                                    @click="updateQuantity(index, 'minus')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 12H6" />
                                    </svg>
                                </button>

                                <!-- input -->
                                <input type="text" class="flex-grow w-10 h-6 text-sm font-medium text-center border-0"
                                    readonly v-model="data.qty">

                                <!-- button plus -->
                                <button class="px-2 py-1 transition-all rounded-full bg-slate-200 hover:bg-slate-400"
                                    @click="updateQuantity(index, 'plus')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-7">
                <!-- <div class="flex justify-between">
                    <p class="text-sm font-medium">Subtotal</p>
                    <p class="text-sm font-medium">Rp. 10.100</p>
                </div> -->
                <!-- <div class="flex justify-between">
                    <p class="text-sm font-medium">Discount</p>
                    <p class="text-sm font-medium">Rp. 10.100</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-sm font-medium">Tax</p>
                    <p class="text-sm font-medium">Rp. 10.100</p>
                </div> -->
                <div class="flex justify-between">
                    <p class="text-sm font-medium text-lg">Total</p>
                    <p class="text-sm font-medium text-lg">Rp.
                        {{ cart.reduce((acc, item) => {
                            return acc + (parseInt(item.price) * parseInt(item.qty))
                        }, 0).toLocaleString('id-ID') }}
                    </p>
                </div>
            </section>
            <section class="mt-7">
                <VButtonRounded @click="create()" label="Checkout" />
            </section>
        </section>
    </div>

    <div class="sm:hidden">
        <p class="p-4 font-normal bg-white rounded-md">
            Currently the POS feature is not available on mobile devices, please use a desktop or tablet device to access
            this
            feature.
        </p>
    </div>

    <VModalSettingPos :open-dialog="openModalSetting" @close="handleOpenModalSetting()" :additional="additionals" />
    <VModalSuccess :open-dialog="openModalSuccess" />
</template>
