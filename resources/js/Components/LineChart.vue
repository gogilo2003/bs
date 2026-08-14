<template>
    <div class="card">
        <Chart type="line" :data="chartData" :options="chartOptions" class="h-[30rem]" />
    </div>
</template>

<script setup lang="ts">
import { format } from 'date-fns';
import Chart from 'primevue/chart'
import { ref, onMounted } from 'vue';

const props = defineProps<{
    readings: Record<string, { date: string; type: string; mean_reading: number }[]>;
}>();

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});

const chartData = ref();
const chartOptions = ref();

const setChartData = () => {

    const documentStyle = getComputedStyle(document.documentElement);

    const dates = Object.keys(props.readings).sort(); // Ensure dates are in ascending order

    const fbsReadings = dates.map(date => {
        const reading = props.readings[date].find(r => r.type === 'fbs');
        return reading ? reading.mean_reading : null;
    });

    const rbsReadings = dates.map(date => {
        const reading = props.readings[date].find(r => r.type === 'rbs');
        return reading ? reading.mean_reading : null;
    });

    return {
        labels: dates.map(date => {
            return format(new Date(date), "iii, do MMM, yyyy")
        }),
        datasets: [
            {
                label: 'Fasting Blood Sugar Readings',
                data: fbsReadings,
                fill: false,
                borderColor: documentStyle.getPropertyValue('--p-purple-500'),
                tension: 0.4
            },
            {
                label: 'Random Blood Sugar Readings',
                data: rbsReadings,
                fill: false,
                borderColor: documentStyle.getPropertyValue('--p-orange-500'),
                tension: 0.4
            }
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}
</script>
