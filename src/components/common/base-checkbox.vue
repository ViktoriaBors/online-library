<template>
    <div
      class="rounded-lg"
      :class="class"
    >  
        <input
          :id="id"
          :name="name"
          class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 rounded focus:ring-teal-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
          type="checkbox"
          :checked="value"
          :disabled="disabled"
          @click="onClick"
          @focusout="onFocusout"
          @keyup="onKeyup"
          @change="$emit('input', $event.target.checked); $emit('change', $event.target.checked)"
        >
      <label class="w-full py-3 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
      :for="name">
        <slot>{{ label }}</slot>
      </label>
      </div>
</template>
  
  <script setup>  
const props = defineProps({
      value: {
        type: Boolean,
        default: false
      },
      disabled: {
        type: Boolean,
        default: false
      },
      label: {
        type: String,
        default: ''
      },
      inverse: {
        type: Boolean,
        default: false
      },
      inline: {
        type: Boolean,
        default: true
      },
      name: {
        type: String,
        default: ''
      },
      id: {
        type: String,
        default: ''
      },
      class: {
        type: String,
        default: ''
      }
})

const emit = defineEmits(["click", "focusout", "keyup", "change", "input"]);

const onClick = (event) => {
    if (event.clientX === 0 && event.clientY === 0) {
          return;
        }
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
  emit("input", value);
};

const onChange = (event) => {
  const value = event.target.value;
  emit("change", value);
};

</script>
  
  <style scoped>

  
  </style>
  