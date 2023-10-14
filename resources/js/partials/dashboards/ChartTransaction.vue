<script setup>
import axios from "axios";
import { onMounted, ref, watch, watchEffect } from "vue";
import { object } from "vue-types";
import { notify } from "notiwind";
import LineChart from "@/components/charts/LineChart.vue";
import { tailwindConfig, hexToRGB, formatValue } from "@/utils/Utils.js";
import VLoading from "@/components/VLoading/index.vue";
import debounce from "@/composables/debounce";
import VArrowDown from "@/components/src/icons/VArrowDown.vue";
import VArrowUp from "@/components/src/icons/VArrowUp.vue";

const props = defineProps({
    filter: object(),
});

const total_revenue = ref(0);
const loaded = ref(true);
const isLoading = ref(true);
const filter = ref({});
const isProfit = ref(true);

const chartData = ref({
    labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ],
    datasets: [
        // Indigo line
        {
            label: "Sales",
            data: [
                32,
                42,
                75,
                62,
                84,
                56,
                74,
                65,
                84,
                45,
                65,
                45,

            ],
            fill: true,
            backgroundColor: `rgba(${hexToRGB(
                tailwindConfig().theme.colors.blue[500]
            )}, 0.08)`,
            borderColor: tailwindConfig().theme.colors.indigo[500],
            borderWidth: 2,
            tension: 0.3,
            pointRadius: 0,
            pointHoverRadius: 3,
            pointBackgroundColor: tailwindConfig().theme.colors.indigo[500],
            pointHoverBackgroundColor: tailwindConfig().theme.colors.indigo[500],
            clip: 20,
        },
        {
            label: "Purchase",
            data: [
                32,
                32,
                45,
                62,
                44,
                12,
                20,
                65,
                33,
                45,
                75,
                45,
            ],
            fill: true,
            borderColor: `rgba(${hexToRGB(tailwindConfig().theme.colors.slate[500])}, 0.25)`,
            borderWidth: 2,
            tension: 0.3,
            pointRadius: 0,
            pointHoverRadius: 3,
            pointBackgroundColor: `rgba(${hexToRGB(tailwindConfig().theme.colors.slate[500])}, 0.25)`,
            pointHoverBackgroundColor: `rgba(${hexToRGB(tailwindConfig().theme.colors.slate[500])}, 0.25)`,
            clip: 20,
        },
    ],
});

const query = debounce(async () => {
    // axios
    //     .get(route("dashboard.gettotalrevenue"), {
    //         params: {
    //             start_date: filter.value.start_date,
    //             end_date: filter.value.end_date,
    //         },
    //     })
    //     .then((res) => {
    //         total_revenue.value = formatValue(res.data.total_revenue);
    //         chartData.value.labels = Object.keys(res.data.graph_data);
    //         chartData.value.datasets[0].data = Object.values(
    //             res.data.graph_data
    //         );
    //     })
    //     .catch((res) => {
    //         notify(
    //             {
    //                 type: "error",
    //                 group: "top",
    //                 text: res.response.data.message,
    //             },
    //             2500
    //         );
    //     })
    //     .finally(() => ((loaded.value = true), (isLoading.value = false)));
}, 500);


watchEffect(() => {
    isLoading.value = false;
    // filter.value = props.filter;
    // query();
});

onMounted(() => {
    // query();
});
</script>

<template>
    <div
        class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white shadow-lg rounded-sm border border-slate-200">
        <div v-if="isLoading" class="px-5 pt-5">
            <div class="h-[100%] overflow-hidden my-2">
                <VLoading />
            </div>
        </div>
        <div v-else>
            <div class="px-5 pt-5">
                <h2 class="text-lg font-semibold text-slate-800 mb-2">
                    Sales & Purchases
                </h2>
                <!-- <div class="flex items-start">
                    <div class="text-2xl font-bold text-slate-800 mr-2">
                        <p class="text-teal-500" v-if="isProfit">
                            <VArrowUp class="text-teal-500" />
                            Rp. 100jt
                        </p>
                        <p class="text-red-600" v-else>
                            <VArrowDown class="text-red-600" />
                            Rp. 100jt
                        </p>
                    </div>
                </div> -->
            </div>
            <!-- Chart built with Chart.js 3 -->
            <div class="grow">
                <!-- Change the height attribute to adjust the chart height -->
                <LineChart v-if="loaded" :data="chartData" height="200" />
            </div>
        </div>
    </div>
</template>
