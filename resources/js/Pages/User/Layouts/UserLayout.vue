<template>
  <Header />
  <slot />
  <Footer />
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
  } else {
  }
});
</script>
