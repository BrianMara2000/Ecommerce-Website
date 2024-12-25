<script setup>
import { onMounted, computed, ref } from "vue";

const model = defineModel({
  type: String,
});

const props = defineProps({
  type: {
    type: String,
    required: true,
  },
  hasError: {
    type: Boolean,
    default: false,
  },
});

const input = ref(null);

onMounted(() => {
  if (input.value?.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

const inputClasses = computed(() => {
  return props.hasError
    ? "border-red-500 focus:border-red-500 focus:ring-red-500"
    : "border-gray-300 focus:border-indigo-500 focus:ring-indigo-500";
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <select
    v-if="props.type === 'select'"
    :class="`w-full rounded-md shadow-sm ${inputClasses}`"
    ref="input"
    v-model="model"
  >
    <slot></slot>
  </select>
  <input
    v-else
    :type="props.type"
    :class="`w-full rounded-md shadow-sm ${inputClasses}`"
    v-model="model"
    ref="input"
  />
</template>
