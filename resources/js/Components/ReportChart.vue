<template>
    <div class="w-full">
        <Chart type="line" :data="chartData" :options="chartOptions" class="h-44 w-full" />
    </div>
</template>

<script setup lang="ts">
import { format } from 'date-fns';
import Chart from 'primevue/chart';
import { ref, onMounted, watch } from 'vue';

const props = defineProps<{
    readings: Record<string, { date: string; type: string; mean_reading: number }[]>;
}>();

const chartData = ref();
const chartOptions = ref();

const buildChart = () => {
    if (!props.readings || Object.keys(props.readings).length === 0) {
        chartData.value = { labels: [], datasets: [] };
        return;
    }

    const dates = Object.keys(props.readings).sort();

    const fbsReadings = dates.map(date => {
        const item = props.readings[date]?.find(r => r.type === 'fbs');
        return item ? item.mean_reading : null;
    });

    const rbsReadings = dates.map(date => {
        const item = props.readings[date]?.find(r => r.type === 'rbs');
        return item ? item.mean_reading : null;
    });

    chartData.value = {
        labels: dates.map(date => {
            try {
                return format(new Date(date), "EEE, do MMM, yyyy");
            } catch {
                return date;
            }
        }),
        datasets: [
            {
                label: 'Fasting Blood Sugar Readings',
                data: fbsReadings,
                fill: false,
                borderColor: '#9333EA',
                backgroundColor: '#9333EA',
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 4,
            },
            {
                label: 'Random Blood Sugar Readings',
                data: rbsReadings,
                fill: false,
                borderColor: '#F97316',
                backgroundColor: '#F97316',
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 4,
            }
        ]
    };

    chartOptions.value = {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        plugins: {
            legend: {
                position: 'top',
                align: 'start',
                labels: {
                    boxWidth: 14,
                    boxHeight: 6,
                    usePointStyle: false,
                    color: '#374151',
                    font: {
                        size: 9,
                        family: 'Figtree, sans-serif'
                    }
                }
            },
            tooltip: {
                enabled: true
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#6B7280',
                    font: {
                        size: 8,
                        family: 'Figtree, sans-serif'
                    }
                },
                grid: {
                    color: '#E5E7EB'
                }
            },
            y: {
                ticks: {
                    color: '#6B7280',
                    font: {
                        size: 9,
                        family: 'Figtree, sans-serif'
                    }
                },
                grid: {
                    color: '#E5E7EB'
                }
            }
        }
    };
};

onMounted(() => {
    buildChart();
});

watch(() => props.readings, () => {
    buildChart();
}, { deep: true });
</script>
