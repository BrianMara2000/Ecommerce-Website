<template>
  <div
    class="w-[80%] text-sm rounded-md px-1 py-2 flex items-center justify-center"
    :class="{
      'bg-emerald-500 text-white': order === 'paid',
      'bg-yellow-500 text-white': order === 'unpaid',
      'bg-gray-500 text-white': order === 'shipped',
      'bg-purple-500 text-white': order === 'completed',
      'bg-red-500 text-white': order === 'cancelled',
    }"
  >
    {{ capitalizedStatus(order) }}
  </div>
</template>

<script setup>
import { useCapitalizedStatus } from "../../utils/utils";

defineProps({
  order: String,
});

const capitalizedStatus = (stats) => useCapitalizedStatus(stats);
</script>
