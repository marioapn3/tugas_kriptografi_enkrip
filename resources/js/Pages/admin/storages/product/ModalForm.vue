<script setup>
import axios from "axios";
import { ref } from "vue";
import { bool, object } from "vue-types";
import { notify } from "notiwind";
import VDialog from '@/components/VDialog/index.vue';
import VButton from '@/components/VButton/index.vue';
import VTextarea from '@/components/VTextarea/index.vue';
import VInput from '@/components/VInput/index.vue';
import VSelect from '@/components/VSelect/index.vue';

const props = defineProps({
    openDialog: bool(),
    updateAction: bool().def(false),
    data: object().def({}),
    additional: object().def({})
})

const emit = defineEmits(['close', 'successSubmit'])

const previewPicUrl = ref('')

const isLoading = ref(false);
const formError = ref({})
const form = ref({})


const fileSelected = (evt) => {
    formError.value.image = ''
    form.value.image = evt.target.files[0];
    previewPicUrl.value = URL.createObjectURL(evt.target.files[0]);
}

const openForm = () => {
    if (props.updateAction) {
        form.value = Object.assign(form.value, props.data)
        form.value.sale_price = props.data.sale_price_number_format
        form.value.purchase_price = props.data.purchase_price_number_format
        previewPicUrl.value = props.data.image_preview
    } else {
        form.value = ref({})

        axios.get(route('storages.product.generatecode')
        ).then((res) => {
            form.value.code = res.data.data.code
        }).catch((res) => {
            notify({
                type: "error",
                group: "top",
                text: res.response.data.message
            }, 2500)
        }).finally(() => isLoading.value = false)
    }
}

const closeForm = () => {
    form.value = ref({})
    formError.value = ref({})

    if (document.getElementById("imagePic")) {
        document.getElementById("imagePic").value = null
    }
}

const submit = async () => {
    props.updateAction ? update() : create()
}

const update = async () => {
    isLoading.value = true

    const fd = new FormData();
    if (form.value.image != null) {
        fd.append("image", form.value.image, form.value.image.name);
    }

    Object.keys(form.value).forEach(key => {
        fd.append(key, form.value[key]);
    })

    console.log(fd, form.value)

    axios.post(route('storages.product.update', { 'id': props.data.id }), fd)
        .then((res) => {
            emit('close')
            emit('successSubmit')
            form.value = ref({})
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

const create = async () => {
    isLoading.value = true

    const fd = new FormData();
    if (form.value.image != null) {
        fd.append("image", form.value.image, form.value.image.name);
    }

    Object.keys(form.value).forEach(key => {
        fd.append(key, form.value[key]);
    })

    axios.post(route('storages.product.create'), fd)
        .then((res) => {
            emit('close')
            emit('successSubmit')
            form.value = ref({})
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
    <VDialog :showModal="openDialog" :title="updateAction ? 'Update Product' : 'Create Product'" @opened="openForm"
        @closed="closeForm" size="2xl">
        <template v-slot:close>
            <button class="text-slate-400 hover:text-slate-500" @click="$emit('close')">
                <div class="sr-only">Close</div>
                <svg class="w-4 h-4 fill-current">
                    <path
                        d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                </svg>
            </button>
        </template>
        <template v-slot:content>
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2 md:col-span-1">
                    <VInput placeholder="Insert Code" label="Code" v-model="form.code" :errorMessage="formError.code"
                        @update:modelValue="formError.code = ''" />
                    <span class="text-xs mt-1 text-slate-500">This code auto generate, you can create custom create</span>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <VInput placeholder="Insert Name" label="Product Name" :required="true" v-model="form.name"
                        :errorMessage="formError.name" @update:modelValue="formError.name = ''" />
                </div>
                <div class="col-span-2">
                    <div class="col-span-2">
                        <VTextarea placeholder="Insert Description" label="Description" v-model="form.description"
                            :errorMessage="formError.description" @update:modelValue="formError.description = ''" />
                    </div>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <VInput placeholder="Insert Purchase Price" label="Purchase Price" :required="true"
                        v-model="form.purchase_price" :errorMessage="formError.purchase_price"
                        @update:modelValue="formError.purchase_price = ''" type="number" />
                </div>
                <div class="col-span-2 md:col-span-1">
                    <VSelect placeholder="Select Purchase Account" :required="true" v-model="form.purchase_account"
                        :options="additional.account_options" label="Purchase Account"
                        :errorMessage="formError.purchase_account" @update:modelValue="formError.purchase_account = ''" />
                </div>
                <div class="col-span-2 md:col-span-1">
                    <VInput placeholder="Insert Selling Price" label="Selling Price" :required="true"
                        v-model="form.sale_price" :errorMessage="formError.sale_price"
                        @update:modelValue="formError.sale_price = ''" type="number" />
                </div>
                <div class="col-span-2 md:col-span-1">
                    <VSelect placeholder="Select Selling Account" :required="true" v-model="form.sale_account"
                        :options="additional.account_options" label="Selling Account" :errorMessage="formError.sale_account"
                        @update:modelValue="formError.sale_account = ''" />
                </div>
                <div class="col-span-2 md:col-span-1">
                    <VInput placeholder="Insert Stock" label="Stock" v-model="form.stock" :errorMessage="formError.stock"
                        @update:modelValue="formError.stock = ''" type="number" />
                </div>
                <div class="col-span-2 md:col-span-1">
                    <VSelect placeholder="Select Inventory Account" :required="true" v-model="form.inventory_account"
                        :options="additional.account_options" label="Inventory Account"
                        :errorMessage="formError.inventory_account" @update:modelValue="formError.inventory_account = ''" />
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-slate-600 mb-1" for="profilePic">Image</label>
                    <img class="w-20 h-20 rounded-full mb-1" :src="previewPicUrl" width="80" height="80"
                        v-if="previewPicUrl" />
                    <input
                        class="block w-full cursor-pointer bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md"
                        type="file" id="imagePic" accept=".jpg, .jpeg, .png" @change="fileSelected">
                    <div class="text-xs mt-1" :class="[{
                        'text-rose-500': formError.image
                    }]" v-if="formError.image">
                        {{ formError.image }}
                    </div>
                </div>
            </div>

        </template>
        <template v-slot:footer>
            <div class="flex flex-wrap justify-end space-x-2">
                <VButton label="Cancel" type="default" @click="$emit('close')" />
                <VButton :is-loading="isLoading" :label="updateAction ? 'Update' : 'Create'" type="primary"
                    @click="submit" />
            </div>
        </template>
    </VDialog>
</template>