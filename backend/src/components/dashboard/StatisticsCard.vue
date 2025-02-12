<template>
  <div class="bg-white shadow-md rounded-lg p-8 flex items-center space-x-4">
    <div class="p-3 rounded-full bg-gray-100">
      <component :is="icon" class="w-6 h-6 text-gray-600" />
    </div>
    <div>
      <p class="text-gray-500 text-sm">{{ title }}</p>
      <p class="text-xl font-semibold">{{ value }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
  icon: {
    Object,
    Function,
  }, // Vue component for icon
});
</script>
