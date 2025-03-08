<template>
  <div>
    <h1 class="text-2xl font-semibold mb-4">Edit Product</h1>
    <ProductForm :productId="id" />
  </div>
</template>

<script setup>
import ProductForm from "./ProductForm.vue";

defineProps({ id: String });
</script>
