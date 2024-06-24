<template>
  <h1>view</h1>
</template>

<script setup>
import { ref } from "vue";
import UserLayout from "../Layouts/UserLayout.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
  products: {
    type: Object,
    required: true,
  },
});
</script>
