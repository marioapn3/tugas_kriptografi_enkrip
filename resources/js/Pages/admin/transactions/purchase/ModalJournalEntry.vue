<script setup>
import VButton from '@/components/VButton/index.vue';
import VDataTable from '@/components/VDataTable/index.vue';
import VDialog from '@/components/VDialog/index.vue';
import { data } from 'autoprefixer';
import { onMounted, ref } from "vue";
import { bool, object } from "vue-types";

const props = defineProps({
    openDialog: bool(),
    data: object().def({}),
})

const emit = defineEmits(['close', 'successSubmit'])

const totalDebit = ref(0)
const totalCredit = ref(0)

onMounted(() => {
    props.data.journal_details.forEach((journal) => {
        totalDebit.value += journal.debit
        totalCredit.value += journal.credit
    })

})

</script>

<template>
    <VDialog :showModal="openDialog" title="Journal Entry" size="2xl">
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
            <VDataTable :heads="['Account', 'Debit', 'Credit']" bordered :divide-y="false">
                <tr v-for="(journal, index) in props.data.journal_details" :key="index">
                    <td class="px-4 whitespace-nowrap h-12">
                        {{ journal.account.code }} - {{ journal.account.name }}
                    </td>
                    <td class="px-4 whitespace-nowrap h-12">Rp. {{ journal.debit.toLocaleString('id-ID') }}</td>
                    <td class="px-4 whitespace-nowrap h-12">Rp. {{ journal.credit.toLocaleString('id-ID') }}</td>
                </tr>
                <tr>
                    <td class="px-4 whitespace-nowrap h-12 font-semibold" align="right"> Total :</td>
                    <td class="px-4 whitespace-nowrap h-12 font-semibold">Rp. {{ totalDebit.toLocaleString('id-ID') }}</td>
                    <td class="px-4 whitespace-nowrap h-12 font-semibold">Rp. {{ totalCredit.toLocaleString('id-ID') }}</td>
                </tr>
            </VDataTable>
        </template>
    </VDialog>
</template>