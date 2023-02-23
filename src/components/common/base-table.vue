<template>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3"
                    v-for="headers in props.headline"
                    :key="headers">
                       {{ headers }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700"
                    v-for="data in props.data"
                    :key="data.id"
                >
                    <td class="px-6 py-4"
                    v-for="detail in data">
                        {{ detail }}
                    </td>
                    <td class="px-6 py-4">
                        <button
                        :v-if="props.showActionLabel"
                        :data-bookId ="data.id"
                        v-on="computedListeners"
                        class="block font-bold leading-tight text-teal-800 uppercase">
                           {{ showActionLabel ? actionLabel : data.available === 'Available' ? 'Request Book' : '' }}</button>
                        
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
  },
  actionLabel: {
    type: String,
    default: ''
  },
  showActionLabel: {
    type: Boolean,
    default: true
  }
});

const computedListeners = computed(() => {
  return {
    click: onClick,
    keyup: onKeyup,
    focusout: onFocusout,
  };
});

const emit = defineEmits(["click", "focusout", "keyup"]);

const onClick = (event) => {
  emit("click", event);
};

const onFocusout = (event) => {
  emit("focusout", event);
};

const onKeyup = (event) => {
  emit("keyup", event);
};



</script>

<style></style>