<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
  type: String,
});

const props = defineProps({
  type: {
    type: String,
    required: true,
  },
});

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <select
    v-if="props.type === 'select'"
    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    ref="input"
    v-model="model"
  >
    <slot></slot>
  </select>
  <input
    v-else
    :type="props.type"
    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    v-model="model"
    ref="input"
  />
</template>
