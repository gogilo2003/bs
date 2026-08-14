<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Paginator from "../Components/Paginator.vue";
import Button from "primevue/button";
import { iReading, iNotification } from '../interfaces/index';
import { subWeeks, subMonths, isAfter } from "date-fns/fp";
import { ref, onMounted } from 'vue';


const props = defineProps<{
    readings: iReading[]
}>()

const filter = ref([])
const report_type = ref()

const today = () => {
    filter.value = props.readings.filter(
        (item) =>
            new Date(item.read_at).toDateString() ===
            new Date().toDateString()
    );
    report_type.value = "today";
}

const week = () => {
    let end = new Date();
    let start = subWeeks(1, end);
    start.setHours(0);
    start.setMinutes(0);
    start.setSeconds(0);
    filter.value = props.readings.filter((item) =>
        isAfter(start, new Date(item.read_at))
    );
    report_type.value = "week";
}

const month = () => {
    let end = new Date();
    let start = subMonths(1, end);
    start.setHours(0);
    start.setMinutes(0);
    start.setSeconds(0);
    filter.value = props.readings.filter((item) =>
        isAfter(start, new Date(item.read_at))
    );
    report_type.value = "month";
}

const threeMonths = () => {
    let end = new Date();
    let start = subMonths(3, end);
    start.setHours(0);
    start.setMinutes(0);
    start.setSeconds(0);
    filter.value = props.readings.filter((item) =>
        isAfter(start, new Date(item.read_at))
    );
    report_type.value = "month";
}

const all = () => {
    filter.value = props.readings;
    report_type.value = "all";
}

const print = () => {
    let type = report_type.value;
    var date = new Date();
    var filename = `${date.getFullYear()}.${date.getMonth()}.${date.getDate()}-${type}.pdf`;
    var url = route('readings-download', { type });
    var a = document.createElement("a");
    a.href = url;
    a.download = filename;
    document.body.appendChild(a); // we need to append the element to the dom -> otherwise it will not work in firefox
    a.click();
    a.remove(); //afterwards we remove the element again
}
onMounted(() => {
    all()
})
</script>

<template>

    <Head title="Reports" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between flex-col md:flex-row">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reports</h2>
                <div class="flex gap-1 flex-wrap justify-center md:justify-end">
                    <Button @click="today" label="Today" text raised />
                    <Button @click="week" label="Week" text raised />
                    <Button @click="month" label="Month" text raised />
                    <Button @click="threeMonths" label="Quarterly" text raised />
                    <Button @click="all" label="All" text raised />
                    <Button @click="print" label="Print" text raised />
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white rounded-xl p-3">
                <table class="w-full md:max-w-3xl border-collapse border bg-white">
                    <thead class="bg-gray-200">
                        <tr class="uppercase">
                            <th class="px-3 py-2 text-left">#</th>
                            <th class="px-3 py-2 text-left">Date</th>
                            <th class="px-3 py-2 text-left">Type</th>
                            <th class="px-3 py-2 text-left">Reading</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="({ read_at, type, reading }, index) in filter" class="odd:bg-gray-100">
                            <td class="border px-3 py-2" v-text="index + 1"></td>
                            <td class="border px-3 py-2" v-text="read_at"></td>
                            <td class="border px-3 py-2" v-text="type.name"></td>
                            <td class="text-center border px-3 py-2" v-text="reading"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
