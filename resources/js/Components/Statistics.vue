<template>
    <div>
        <p class="grid grid-cols-2">
            <span>Min:</span><span v-text="stats.min"></span>
            <span>Max:</span><span v-text="stats.max"></span>
            <span>Mean:</span><span v-text="stats.mean"></span>
        </p>
    </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

interface Stats {
    min: number;
    max: number;
    mean: number;
}

const props = defineProps<{
    stats: Stats;
}>();
</script>
