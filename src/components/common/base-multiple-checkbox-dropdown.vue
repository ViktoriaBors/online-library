<template>
  <div class="flex flex-col">
    <base-button @click="isOpen = !isOpen">Categories &darr;
    </base-button>
    <!-- Dropdown menu -->
    <div class="z-10 bg-white rounded-lg shadow w-60 dark:bg-gray-700" v-if="isOpen">
      <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
        aria-labelledby="dropdownCategoryBtn">
        <li v-for="option in options" :key="option.value">
          <base-checkbox :class="'flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-600'" :label="option.label"
            @change="chosen = $event">
          </base-checkbox>
        </li>
      </ul>
      {{ chosen }}
    </div>
  </div>
</template>

<script setup>
import baseButton from '@/components/common/base-button.vue';
import baseCheckbox from '@/components/common/base-checkbox.vue'
import { ref, defineProps, reactive, computed, defineEmits } from 'vue';

let isOpen = ref(false)
let chosen = reactive({})

const props = defineProps({
  options: {
    type: Array,
    default: () => []
  },
  value: {
    type: undefined,
    default: undefined
  },
  disabled: {
    type: Boolean,
    default: false
  },
  class: {
    type: String,
    default: "",
  },
  name: {
    type: String,
    default: ""
  }
});

</script>

<style></style>