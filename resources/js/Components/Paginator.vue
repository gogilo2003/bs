<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Icon from './Icons/Icon.vue';
import { iReadings, iLink } from '../interfaces/index';

const props = defineProps<{
    items: {
        current_page: string
        data: Object[] | any[]
        first_page_url: string
        from: number
        last_page: number
        last_page_url: string
        links: iLink[]
        next_page_url: string
        path: string
        per_page: number
        prev_page_url: string
        to: number
        total: number
    } | iReadings
}>()

const lt = ref('&laquo; Previous');
const gt = ref('Next &raquo;');

const links = computed(() => {
    return props.items.links.map(link => {
        let search: string = new String(usePage().props.search ?? "").toString()

        if (link.url && search) {
            let queryArray = link.url.split('?')
            if (queryArray.length > 1) {
                let baseUrl = queryArray[0]
                let queryString = queryArray[1]
                const queryParams = new URLSearchParams(queryString)
                queryParams.set('search', search)
                let updatedQueryString = queryParams.toString();
                link.url = updatedQueryString ? `${baseUrl}?${updatedQueryString}` : baseUrl;
            }
        }
        return link
    })
})

</script>
<template>
    <div class="shadow my-3 border dark:border-gray-500 bg-gray-50 dark:bg-gray-800 rounded-xl px-4 py-3">
        <nav aria-label="Pagination" class="">
            <ul class="flex items-center flex-wrap md:flex-nowrap -space-x-px md:h-8 text-sm gap-1 justify-center">
                <li v-for="link in links">
                    <component :is="link.url ? Link :'span'" v-if="link.label == lt" :href="link.url"
                        class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg rounded-l-3xl hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                        <span class="sr-only">Previous</span>
                        <Icon type="pi-angle-double-left" />
                    </component>
                    <component :is="link.url ? Link : 'span'" v-else-if="link.label == gt" :href="link.url"
                        class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg rounded-r-3xl hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                        <span class="sr-only">Next</span>
                        <Icon type="pi-angle-double-right" />
                    </component>
                    <component :is="link.url ? Link : 'span'" v-else :href="link.url"
                        class="flex items-center justify-center px-3 h-8 w-8 rounded-full leading-tight bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
                        :class="{ 'z-10 text-secondary-600 border-secondary-300 bg-secondary-50 hover:bg-secondary-100 hover:text-secondary-700 dark:bg-gray-700 dark:text-primary-500': link.active, 'text-gray-500 bg-white border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white': !link.active }"
                        v-text="link.label">
                    </component>
                </li>
            </ul>
        </nav>
    </div>
</template>
