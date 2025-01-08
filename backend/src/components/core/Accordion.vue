<template>
  <div class="bg-white rounded-lg border">
    <div
      class="flex justify-between items-center hover:bg-gray-100 p-5 cursor-pointer border-b"
      @click="toggleAccordion"
    >
      <h2 class="text-lg font-semibold">{{ title }}</h2>
      <button>
        <ChevronDownIcon
          :class="{ 'rotate-180': isOpen }"
          class="h-6 w-6 transition-transform duration-300"
        />
      </button>
    </div>
    <div v-if="isOpen" class="flex flex-col p-8 justify-center gap-3">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { ChevronDownIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
});

const isOpen = ref(false);

const toggleAccordion = () => {
  isOpen.value = !isOpen.value;
};
</script>

<style scoped>
/* Optional additional styles */
</style>
