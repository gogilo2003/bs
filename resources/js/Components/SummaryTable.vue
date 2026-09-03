<template>
    <div class="w-full">
        <div class="text-[10px] font-bold uppercase tracking-wider text-gray-900 mb-1">
            {{ title }}
        </div>
        <table class="w-full border-collapse border border-[#A6A6A6] text-[10px] bg-white">
            <tbody>
                <tr class="border-b border-[#A6A6A6]">
                    <td class="border-r border-[#A6A6A6] px-2 py-0.5 text-gray-800 font-medium w-1/2 uppercase">Mean</td>
                    <td class="px-2 py-0.5 text-right font-medium text-gray-900">{{ formatVal(stats?.mean) }}</td>
                </tr>
                <tr class="border-b border-[#A6A6A6]">
                    <td class="border-r border-[#A6A6A6] px-2 py-0.5 text-gray-800 font-medium w-1/2 uppercase">Min</td>
                    <td class="px-2 py-0.5 text-right font-medium text-gray-900">{{ formatVal(stats?.min) }}</td>
                </tr>
                <tr>
                    <td class="border-r border-[#A6A6A6] px-2 py-0.5 text-gray-800 font-medium w-1/2 uppercase">Max</td>
                    <td class="px-2 py-0.5 text-right font-medium text-gray-900">{{ formatVal(stats?.max) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { iStats } from '../interfaces/index';

defineProps<{
    title: string;
    stats?: iStats;
}>();

const formatVal = (val?: number) => {
    if (val === undefined || val === null || isNaN(val)) return '-';
    return Number(val).toFixed(1);
};
</script>
