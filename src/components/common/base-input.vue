<template>
  <input :type="type" class="
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
        bg-white
        border border-solid border-teal-800
        rounded
        transition
        ease-in-out
        m-0
        focus:ring-teal-500 focus:border-teal-500" :class="class" v-bind="$props" v-on="computedListeners" />
</template>

<script setup>
import { defineProps, computed } from "vue";

const props = defineProps({
  value: {
    type: undefined,
    default: undefined,
  },
  emptyValue: {
    type: undefined,
    default: "",
  },
  type: {
    type: String,
    default: "text",
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
    input: onInput,
    change: onChange,
  };
});

const emit = defineEmits(["click", "focusout", "keyup", "input", "change"]);

const onClick = (event) => {
  emit("click", event);
};

const onFocusout = (event) => {
  emit("focusout", event);
};

const onKeyup = (event) => {
  emit("keyup", event);
};

const onInput = (event) => {
  const value = event.target.value;
  let valueToEmit = value;
  if (value === "") {
    valueToEmit = props.emptyValue;
  }
  emit("input", valueToEmit);
};

const onChange = (event) => {
  const value = event.target.value;
  let valueToEmit = value;
  if (value === "") {
    valueToEmit = props.emptyValue;
  }
  emit("change", valueToEmit);
};
</script>

<style></style>
