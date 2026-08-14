<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Paginator from "../Components/Paginator.vue";
import SecondaryButton from "../Components/SecondaryButton.vue";
import Modal from '../Components/Modal.vue'
import { computed, ref } from 'vue';
import { iReadings, iNotification, iReading } from '../interfaces/index';
import InputLabel from '@/Components/InputLabel.vue';
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber'
import DatePicker from 'primevue/datepicker'
import Select from 'primevue/select';
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast';
import InputError from '@/Components/InputError.vue';


const props = defineProps<{
    readings: iReadings,
    notification: iNotification
}>()

const toast = useToast();

const form = useForm<{
    id: number | null
    type: string | null
    read_at: Date | string | null
    reading: number | string | null
}>({
    id: null,
    type: null,
    read_at: null,
    reading: null
})
const showDialog = ref(false)

const title = computed(() => {
    if (form.id) {
        return 'Edit Reading'
    }
    return 'New Reading'
})

const editReading = (reading: iReading) => {
    form.id = reading.id
    form.type = reading.type
    form.read_at = new Date(reading.read_at)
    form.reading = reading.reading
    showDialog.value = true
}

const newReading = () => {
    form.id = null
    form.type = null
    form.read_at = new Date()
    form.reading = null
    showDialog.value = true
}

const close = () => {
    form.id = null
    form.type = null
    form.read_at = null
    form.reading = null
    form.clearErrors
    form.reset
    showDialog.value = false
}

const types = ref([
    {
        value: 'rbs',
        name: 'Random Blood Sugar'
    },
    {
        value: 'fbs',
        name: 'Fasting Blood Sugar'
    },
])

const save = () => {
    if (form.id) {
        form.transform(data => {
            console.log(data.read_at);

            return { ...data, type: data.type?.value, read_at: data.read_at.toLocaleString() }
        }).patch(route('readings-update', form.id), {
            preserveState: true,
            preserveScroll: true,
            only: ['readings', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    summary: 'Reading',
                    detail: props.notification.success,
                    severity: 'success',
                    life: 3000
                })
                close()
            },
            onError: () => {
                toast.add({
                    summary: 'Reading',
                    detail: props.notification.danger ?? 'An error occurred! Please try again',
                    severity: 'error',
                    life: 3000
                })
            }
        })
    } else {
        form.transform(data => {
            console.log(data.read_at);

            return { ...data, type: data.type?.value, read_at: data.read_at.toLocaleString() }
        }).post(route('readings-store'), {
            preserveState: true,
            preserveScroll: true,
            only: ['readings', 'notification', 'errors'],
            onSuccess: () => {
                toast.add({
                    summary: 'Reading',
                    detail: props.notification.success,
                    severity: 'success',
                    life: 3000
                })
                close()
            },
            onError: () => {
                toast.add({
                    summary: 'Reading',
                    detail: props.notification.danger ?? 'An error occurred! Please try again',
                    severity: 'error',
                    life: 3000
                })
            }
        })
    }
}
</script>

<template>

    <Head title="Readings" />
    <Toast position="top-center" />
    <Modal :show="showDialog">
        <div class="flex justify-between items-center p-3">
            <div v-text="title"></div>
            <Button @click="close" icon="pi pi-times" rounded text raised />
        </div>
        <div class="p-3">
            <form @submit.prevent="save">
                <div class="mb-3">
                    <InputLabel value="Reading Date" />
                    <DatePicker v-model="form.read_at" class="w-full" dateFormat="D, dd M, yy" showIcon showTime
                        hourFormat="12" fluid />
                    <InputError :message="form.errors.read_at" />
                </div>
                <div class="mb-3">
                    <InputLabel value="Reading Type" />
                    <Select v-model="form.type" :options="types" optionLabel="name" placeholder="Select a Type"
                        class="w-full" fluid />
                    <InputError :message="form.errors.type" />
                </div>
                <div class="mb-3">
                    <InputLabel value="Blood Sugar Reading" />
                    <InputNumber v-model="form.reading" class="w-full" :maxFractionDigits="2" fluid />
                    <InputError :message="form.errors.reading" />
                </div>
                <div class="mt-3 flex justify-between items-center">
                    <Button label="Cancel" icon="pi pi-times" text raised rounded @click="close"></Button>
                    <Button type="submit" label="Save" icon="pi pi-save" raised rounded></Button>
                </div>
            </form>
        </div>
    </Modal>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Readings</h2>
                <Button @click="newReading" label="New Reading" raised text rounded />
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col gap-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3" v-for="reading in readings.data">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div>
                                <div class="bg-white border-b border-gray-200 font-medium text-sm"
                                    v-text="reading.read_at">
                                </div>
                                <div class="flex gap-3 text-gray-800">
                                    <span v-text="reading.type.name"></span>
                                    <span v-text="reading.reading"></span>
                                </div>
                            </div>
                            <div>
                                <SecondaryButton @click="editReading(reading)">Edit</SecondaryButton>
                            </div>
                        </div>
                    </div>
                </div>
                <Paginator :items="readings" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
