<template>
  <div class="w-full h-screen flex flex-col font-manrope">
    <Header />
    <div class="flex-1 bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
      <slot />
    </div>
    <Footer />
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";
import Header from "./Header.vue";
import Footer from "./Footer.vue";
import { usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

onMounted(() => {
  initFlowbite();
  if (usePage().props.flash.info) {
    toast.info(usePage().props.flash.info);
  }
});
</script>
