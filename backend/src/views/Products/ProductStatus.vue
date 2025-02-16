<template>
  <div
    class="w-[45%] text-sm rounded-md px-1 py-2 flex items-center justify-center mx-auto"
    :class="{
      'bg-yellow-500 text-white': product === 'archive',
      'bg-gray-500 text-white': product === 'unpublished',
      'bg-purple-500 text-white': product === 'published',
    }"
  >
    {{ capitalizedStatus(product) }}
  </div>
</template>

<script setup>
import { useCapitalizedFirstLetter } from "../../utils/utils";

defineProps({
  product: String,
});

const capitalizedStatus = (stats) => useCapitalizedFirstLetter(stats);
</script>
