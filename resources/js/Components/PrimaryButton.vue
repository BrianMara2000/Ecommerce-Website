<template>
  <button
    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
    :class="
      buttonText === 'Add to Cart' || buttonText === 'Checkout'
        ? 'lg:w-full'
        : ''
    "
  >
    {{ buttonText }}
    <slot />
  </button>
</template>

<script setup>
defineProps({
  buttonText: String,
});
</script>
