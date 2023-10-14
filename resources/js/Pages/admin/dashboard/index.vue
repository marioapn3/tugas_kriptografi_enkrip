<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import { array, object, string } from 'vue-types';
import AppLayout from '@/layouts/apps.vue';
import WelcomeBanner from '@/partials/WelcomeBanner.vue';
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/inertia-vue3';
import CardDashboard from '@/partials/dashboards/CardDashboard.vue';
import CardIncome from '@/partials/dashboards/CardIncome.vue';
import CardExpense from '@/partials/dashboards/CardExpense.vue';
import ChartIncomeStatement from '@/partials/dashboards/ChartIncomeStatement.vue';
import ChartCountries from '@/partials/dashboards/ChartCountries.vue';
import ChartTransaction from '@/partials/dashboards/ChartTransaction.vue';
import CardSales from '@/partials/dashboards/CardSales.vue';
import CardPurchases from '@/partials/dashboards/CardPurchases.vue';

const props = defineProps({
    filter: object(),
    query: array(),
    modules: array(),
    title: string(),
})

const user = computed(() => usePage().props.value.admin_data);
</script>

<template>
    <Head :title="props.title" />
    <WelcomeBanner :user="user" />


    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-8">
            <ChartIncomeStatement />
        </div>
        <div class="col-span-4 grid  gap-2">
            <CardIncome :value="342000000" />
            <CardExpense :value="111300300" />
        </div>

        <!-- Chart Transaction -->
        <div class="col-span-8">
            <ChartTransaction />
        </div>
        <div class="col-span-4 grid  gap-2">
            <CardSales :value="5" />
            <CardPurchases :value="5" />
        </div>

        <div class="col-span-4">
            <CardDashboard :value="5" />
        </div>
        <div class="col-span-4">
            <CardDashboard title="Low Stock Products" :value="2" />
        </div>
        <div class="col-span-4">
            <ChartCountries />
        </div>

    </div>
</template>