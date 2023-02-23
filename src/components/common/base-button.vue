<template>
  <component
    :is="type"
    :to="to"
    :href="href"
    :disabled="disabled"
    class="inline-block px-6 py-2 text-xs font-bold leading-tight text-white uppercase transition duration-150 ease-in-out bg-teal-700 border-2 border-gray-400 rounded shadow-md btn hover:bg-teal-600 hover:shadow-lg active:bg-teal-700 active:shadow-lg focus:outline-none focus:ring-0"
    :class="class"
    v-on="computedListeners"
  >
    <slot />
  </component>
</template>

<script setup>
import { defineProps, computed } from "vue";

const props = defineProps({
  to: {
    type: String,
    default: undefined,
  },
  href: {
    type: String,
    default: undefined,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  class: {
    type: String,
    default: "",
  },
});

const computedListeners = computed(() => {
  return {
    click: onClick,
    keyup: onKeyup,
    focusout: onFocusout,
  };
});

const type = computed(() => {
  if (props.disabled) {
    return "button";
  }
  if (props.to) {
    return "router-link";
  }
  if (props.href) {
    return "a";
  }
  return "button";
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
