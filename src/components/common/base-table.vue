<template>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr class="h-14">
                    <th scope="col" class="px-6 py-3" v-for="headers in props.headline" :key="headers">
                        {{ headers }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b h-14 dark:bg-gray-900 dark:border-gray-700" v-for="data in props.data"
                    :key="data.id">
                    <td class="px-6 py-4" v-for="detail in data">
                        {{ detail }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { defineProps, computed, ref, onMounted } from "vue";

const props = defineProps({
    data: {
        type: Array,
        default: () => []
    },
    headline: {
        type: Array,
        default: () => []
    }
});




</script>

<style></style>