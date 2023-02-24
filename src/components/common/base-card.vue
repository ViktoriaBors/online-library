<template>
    <div class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:border-gray-700">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ props.number }}</h5>
        <p class="font-normal text-gray-700 dark:text-gray-400">{{ props.text }}</p>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    number: {
        type: String,
        default: ''
    },
    text: {
        type: String,
        default: ''
    },
})

</script>

<style></style>