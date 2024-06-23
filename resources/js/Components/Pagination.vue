<template>
  <div
    v-if="products.total > products.per_page"
    class="flex items-center justify-center mt-3"
  >
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, i) in products.links">
        <div
          v-if="link.url === null"
          :key="link.length"
          class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
          v-html="link.label"
        />
        <Link
          v-else
          :key="i"
          class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
          :class="[
            link.active
              ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
            i === 0 ? 'rounded-l-md' : '',
            i === products.links.length - 1 ? 'rounded-r-md' : '',
            !link.url ? 'bg-gray-100 text-gray-700' : '',
          ]"
          :href="link.url"
          v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
  products: {
    type: Object,
    required: true,
  },
});

// defineComponent({
//   components: {
//     InertiaLink,
//   },
// });
</script>
