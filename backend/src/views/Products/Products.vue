<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Products</h1>
    <button
      @click="showProductModal"
      type="button"
      class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer"
    >
      Add new product
    </button>
  </div>

  <ProductModal v-model="showModal" :product="productModel" />
  <ProductsTable />
</template>

<script setup>
import { ref } from "vue";
import ProductsTable from "./ProductsTable.vue";
import ProductModal from "./ProductModal.vue";

const showModal = ref(false);
const productModel = ref({
  id: "",
  titie: "",
  image: "",
  description: "",
  price: "",
});

function showProductModal() {
  showModal.value = true;
}
</script>
