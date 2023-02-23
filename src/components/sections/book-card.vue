<template>
<div class="max-w-sm bg-white rounded-lg shadow-lg"
:data="data">
    <img class="rounded-t-lg" :src="data.thumbnail" alt=""/>
        <div class="p-6">
        <h5 class="mb-2 text-xl font-medium text-gray-900">Author:{{ data.id }}</h5>
        <h5 class="mb-2 text-xl font-medium text-gray-900">Book title: {{ data.title }}</h5>
        <p  class="mb-2 text-sm text-right text-black">Language: {{ data.price }}</p>
        <p  class="mb-2 text-sm text-right text-black">Category: {{ data.category }}</p>
        <p  class="mb-2 text-sm text-right text-black">Availability: {{ data.id }}</p>
        <p class="mb-4 text-base text-gray-700">
            {{ data.description }}
        </p>
    </div>
</div>
</template>

<script setup>
const props = defineProps({
      data: {
        type: Object,
        default: () => {}
      }
})
</script>

<style>
</style>