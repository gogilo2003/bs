<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { iReading1, iStats } from '../interfaces/index';
import { onMounted } from 'vue';
import Statistics from '@/Components/Statistics.vue';
import LineChart from '@/Components/LineChart.vue';

const props = defineProps<{
    weeklyStats: iStats;
    monthlyStats: iStats;
    quarterlyStats: iStats;
    allTimeStats: iStats;
    last7DaysReadings: Record<string, iReading1[]>;
}>();
onMounted(() => {
    console.log(props.last7DaysReadings);

})
</script>

<template>

    <Head title="Blood Sugar Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blood Sugar Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="p-3 border rounded-2xl">
                            <h2 class="font-medium uppercase text-blue-700">Weekly Statistics</h2>
                            <Statistics :stats="weeklyStats" />
                        </div>
                        <div class="p-3 border rounded-2xl">
                            <h2 class="font-medium uppercase text-blue-700">Monthly Statistics</h2>
                            <Statistics :stats="monthlyStats" />
                        </div>
                        <div class="p-3 border rounded-2xl">
                            <h2 class="font-medium uppercase text-blue-700">Quarterly Statistics</h2>
                            <Statistics :stats="quarterlyStats" />
                        </div>
                        <div class="p-3 border rounded-2xl">
                            <h2 class="font-medium uppercase text-blue-700">All Time Statistics</h2>
                            <Statistics :stats="allTimeStats" />
                        </div>
                    </div>
                    <div class="border rounded-2xl p-6 my-6">
                        <h2>Last 7 Days Readings</h2>
                        <LineChart :readings="last7DaysReadings" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
