<template>
  <div class="max-w-sm bg-white rounded-lg shadow-lg" :data="data.bookId">
    <img class="rounded-t-lg" :src="`${data.url}`" alt="" />
    <div class="p-6">
      <h5 class="mb-2 text-xl font-medium text-gray-900">
        {{ data.authorName }}
      </h5>
      <h5 class="mb-2 text-xl font-medium text-gray-900">
        {{ data.title }}
      </h5>
      <p class="mb-2 text-sm text-right text-black">
        Language: {{ data.language }}
      </p>
      <p class="mb-2 text-sm text-right text-black">
        Category: {{ data.categoryName }}
      </p>
      <p class="mb-2 text-sm text-right text-black">
        {{ data.availability > 0 ? "Available" : "Not Available" }}
      </p>
      <p class="mt-2 mb-4 text-base text-justify text-gray-700">
        {{ data.description }}
      </p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  data: {
    type: Object,
    default: () => {},
  },
});
</script>

<style></style>