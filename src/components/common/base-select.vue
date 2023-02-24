<template>
  <select class="px-4 py-2 pr-10 border border-teal-800 rounded focus:ring-teal-500 focus:border-teal-500" :class="class"
    :value="value" :disabled="disabled" :name="name" v-on="computedListeners">
    <option disabled selected value class="hidden" />
    <option v-for="option in options" :key="option.value" :value="option.value" :disabled="option.disabled">
      {{ option.label }}
    </option>
  </select>
</template>
  
<script setup>
import { defineProps, computed, defineEmits } from "vue";

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

const computedListeners = computed(() => {
  return {
    click: onClick,
    keyup: onKeyup,
    focusout: onFocusout,
    change: onChange,
  };
});

const emit = defineEmits(["click", "focusout", "keyup", "change"]);

const onClick = (event) => {
  emit("click", event);
};

const onFocusout = (event) => {
  emit("focusout", event);
};

const onKeyup = (event) => {
  emit("keyup", event);
};

const onChange = (event) => {
  const value = event.target.value;
  emit("change", value);
};

</script>
  
<style scoped></style>
  