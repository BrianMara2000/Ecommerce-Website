<template>
  <div
    tag="div"
    class="bg-white shadow-md rounded-lg p-6 flex items-center space-x-4 w-full hover:bg-[#7F7FFF] transition-all group"
  >
    <!-- Icon Loader -->
    <div
      v-if="loading"
      class="w-12 h-12 rounded-full bg-gray-300 animate-pulse flex-shrink-0"
    ></div>
    <div v-else class="p-3 rounded-full bg-gray-100 group-hover:bg-[#6F6FEF]">
      <component
        :is="icon"
        class="w-6 h-6 text-gray-600 group-hover:text-white"
      />
    </div>

    <div class="flex flex-col space-y-2 flex-1">
      <!-- Title Loader -->
      <p v-if="loading" class="h-4 w-20 bg-gray-200 rounded animate-pulse"></p>
      <p v-else class="text-gray-500 text-sm group-hover:text-white">
        {{ title }}
      </p>

      <!-- Value Loader -->
      <p v-if="loading" class="h-6 w-8 bg-gray-300 rounded animate-pulse"></p>
      <p v-else class="text-xl font-semibold group-hover:text-white">
        {{ value }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { TransitionGroup } from "vue";

const props = defineProps({
  title: String,
  value: [String, Number],
  icon: [Object, Function],
  loading: Boolean,
});
</script>
