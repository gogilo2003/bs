<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Button from "primevue/button";
import { iReading, iStats, iReading1 } from '../interfaces/index';
import { format, subDays, startOfDay, isSameDay } from "date-fns";
import { ref, computed, onMounted } from 'vue';
import ReportChart from '@/Components/ReportChart.vue';
import SummaryTable from '@/Components/SummaryTable.vue';

interface ReportReading extends iReading {
    raw_read_at?: string;
}

const props = defineProps<{
    readings: ReportReading[];
    weeklyStats?: iStats;
    monthlyStats?: iStats;
    quarterlyStats?: iStats;
    allTimeStats?: iStats;
    last7DaysReadings?: Record<string, iReading1[]>;
}>();

const filter = ref<ReportReading[]>([]);
const report_type = ref<'all' | 'today' | 'week' | 'month' | 'quarterly'>('month');

const reportSubtitle = computed(() => {
    const now = new Date();
    const monthYear = format(now, 'MMMM yyyy').toUpperCase();
    switch (report_type.value) {
        case 'today':
            return `TODAY'S REPORT (${format(now, 'dd MMMM yyyy').toUpperCase()})`;
        case 'week':
            return `WEEKLY REPORT (${monthYear})`;
        case 'month':
            return `MONTHLY REPORT(${monthYear})`;
        case 'quarterly':
            return `QUARTERLY REPORT (${monthYear})`;
        case 'all':
        default:
            return `ALL TIME REPORT (${monthYear})`;
    }
});

const today = () => {
    report_type.value = "today";
    const now = new Date();
    filter.value = props.readings.filter((item) => {
        const d = new Date(item.raw_read_at || item.read_at);
        return isSameDay(d, now);
    });
};

const week = () => {
    report_type.value = "week";
    const cutoff = subDays(startOfDay(new Date()), 7);
    filter.value = props.readings.filter((item) => {
        const d = new Date(item.raw_read_at || item.read_at);
        return d >= cutoff;
    });
};

const month = () => {
    report_type.value = "month";
    const cutoff = subDays(startOfDay(new Date()), 30);
    filter.value = props.readings.filter((item) => {
        const d = new Date(item.raw_read_at || item.read_at);
        return d >= cutoff;
    });
};

const threeMonths = () => {
    report_type.value = "quarterly";
    const cutoff = subDays(startOfDay(new Date()), 90);
    filter.value = props.readings.filter((item) => {
        const d = new Date(item.raw_read_at || item.read_at);
        return d >= cutoff;
    });
};

const all = () => {
    report_type.value = "all";
    filter.value = props.readings;
};

const print = () => {
    window.print();
};

const formatDate = (dateVal: string | Date) => {
    try {
        const d = new Date(dateVal);
        return format(d, 'EEE dd-MMM-yyyy');
    } catch {
        return String(dateVal);
    }
};

const formatTime = (dateVal: string | Date) => {
    try {
        const d = new Date(dateVal);
        return format(d, 'hh:mma');
    } catch {
        return '';
    }
};

const formatType = (type: any) => {
    if (typeof type === 'string') return type.toUpperCase();
    if (type?.value) return type.value.toUpperCase();
    return '';
};

onMounted(() => {
    month();
});
</script>

<template>
    <Head title="Blood Sugar Readings Report" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between flex-col md:flex-row gap-4 no-print">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blood Sugar Reports</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Filter, view, and print standard A4 medical reports.</p>
                </div>
                <div class="flex gap-2 flex-wrap justify-center md:justify-end items-center">
                    <Button @click="today" label="Today" :outlined="report_type !== 'today'" size="small" />
                    <Button @click="week" label="Week" :outlined="report_type !== 'week'" size="small" />
                    <Button @click="month" label="Month" :outlined="report_type !== 'month'" size="small" />
                    <Button @click="threeMonths" label="Quarterly" :outlined="report_type !== 'quarterly'" size="small" />
                    <Button @click="all" label="All" :outlined="report_type !== 'all'" size="small" />
                    <Button @click="print" label="Print Report" icon="pi pi-print" severity="secondary" size="small" class="ms-2" />
                </div>
            </div>
        </template>

        <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-100 min-h-screen flex justify-center print-container">
            <!-- A4 Document Sheet -->
            <div class="a4-sheet bg-white shadow-md border border-gray-200 text-gray-900 w-full max-w-[210mm] min-h-[297mm] p-[10mm] flex flex-col justify-between box-border">
                
                <!-- Report Content Wrapper -->
                <div class="w-full flex flex-col space-y-4">
                    
                    <!-- Header -->
                    <div class="text-center pt-1 pb-1">
                        <h1 class="text-base font-bold tracking-wide uppercase text-gray-950">
                            BLOOD SUGAR READINGS REPORT
                        </h1>
                        <h2 class="text-[11px] font-semibold tracking-wider uppercase text-gray-700 mt-1">
                            {{ reportSubtitle }}
                        </h2>
                    </div>

                    <!-- Chart Section (Last 7 Days) -->
                    <div class="w-full pt-1 pb-2">
                        <ReportChart :readings="last7DaysReadings || {}" />
                    </div>

                    <!-- Main Two-Column Layout -->
                    <div class="w-full flex flex-row gap-6 items-start pt-1">
                        
                        <!-- Left Column: Readings Table -->
                        <div class="flex-1 min-w-0">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-gray-900 mb-1">
                                READINGS
                            </div>
                            <table class="w-full border-collapse border border-[#A6A6A6] text-[10px]">
                                <thead>
                                    <tr class="bg-[#A6A6A6] text-white">
                                        <th class="border border-[#A6A6A6] px-2 py-1 text-center font-bold w-7">#</th>
                                        <th class="border border-[#A6A6A6] px-2 py-1 text-left font-bold w-28">DATE</th>
                                        <th class="border border-[#A6A6A6] px-2 py-1 text-left font-bold w-20">TIME</th>
                                        <th class="border border-[#A6A6A6] px-2 py-1 text-left font-bold w-16">TYPE</th>
                                        <th class="border border-[#A6A6A6] px-2 py-1 text-right font-bold w-20">READING</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr 
                                        v-for="(item, index) in filter" 
                                        :key="item.id || index" 
                                        :class="index % 2 === 0 ? 'bg-[#F0EFEF]' : 'bg-white'"
                                        class="h-6"
                                    >
                                        <td class="border border-[#A6A6A6] px-2 py-0.5 text-center text-gray-700">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="border border-[#A6A6A6] px-2 py-0.5 text-left text-gray-800 whitespace-nowrap">
                                            {{ formatDate(item.raw_read_at || item.read_at) }}
                                        </td>
                                        <td class="border border-[#A6A6A6] px-2 py-0.5 text-left text-gray-800 whitespace-nowrap">
                                            {{ formatTime(item.raw_read_at || item.read_at) }}
                                        </td>
                                        <td class="border border-[#A6A6A6] px-2 py-0.5 text-left font-medium text-gray-800">
                                            {{ formatType(item.type) }}
                                        </td>
                                        <td class="border border-[#A6A6A6] px-2 py-0.5 text-right font-medium text-gray-900">
                                            {{ item.reading }}
                                        </td>
                                    </tr>
                                    <tr v-if="filter.length === 0">
                                        <td colspan="5" class="border border-[#A6A6A6] px-3 py-6 text-center text-gray-500 italic bg-white">
                                            No readings recorded for the selected period.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Right Column: Summary Tables -->
                        <div class="w-40 shrink-0 flex flex-col space-y-4">
                            <SummaryTable title="WEEKLY SUMMARY" :stats="weeklyStats" />
                            <SummaryTable title="MONTHLY SUMMARY" :stats="monthlyStats" />
                            <SummaryTable title="QUARTERLY SUMMARY" :stats="quarterlyStats" />
                            <SummaryTable title="ALL TIME SUMMARY" :stats="allTimeStats" />
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@media print {
    :global(body) {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    :global(nav),
    :global(header),
    .no-print {
        display: none !important;
    }

    .print-container {
        padding: 0 !important;
        margin: 0 !important;
        background: #ffffff !important;
        min-height: auto !important;
    }

    .a4-sheet {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        min-height: auto !important;
    }

    @page {
        size: A4 portrait;
        margin: 10mm 10mm 10mm 10mm;
    }
}
</style>
