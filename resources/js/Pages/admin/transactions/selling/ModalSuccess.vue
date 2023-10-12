<script setup>
import axios from "axios";
import { ref } from "vue";
import { bool, object } from "vue-types";
import { notify } from "notiwind";
import VDialog from '@/components/VDialog/index.vue';
import VButton from '@/components/VButton/index.vue';
import VSelect from '@/components/VSelect/index.vue';
import VInput from '@/components/VInput/index.vue';
import dayjs from "dayjs";

const props = defineProps({
    openDialog: bool(),
    additional: object().def({})
})

const emit = defineEmits(['close', 'successSubmit'])

const isLoading = ref(false);

const openForm = () => {
}

const submit = async () => {
    window.location.reload()
}

</script>

<template>
    <VDialog :showModal="openDialog" @opened="openForm" size="sm">
        <template v-slot:content>
            <div class="text-center">
                <h1 class="text-2xl font-semibold">Yeay Transaction Success Created </h1>
                <p class="text-6xl my-5">🎉</p>
            </div>
        </template>
        <template v-slot:footer>
            <div class="flex flex-wrap justify-center space-x-2">
                <!-- <VButton label="Cancel" type="default" @click="$emit('close')" /> -->
                <VButton :is-loading="isLoading" label="Accept" type="primary" @click="submit" />
            </div>
        </template>
    </VDialog>
</template>