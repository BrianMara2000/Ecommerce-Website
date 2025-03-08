<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Products</h1>
    <button
      @click="navigateToCreate"
      type="button"
      class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer"
    >
      Add new product
    </button>
  </div>

  <ProductsTable
    @clickEdit="editProduct"
    :isArchived="isArchived"
    @update-is-archived="updateIsArchived"
  />
</template>

<script setup>
import { ref } from "vue";
import ProductsTable from "./ProductsTable.vue";
import { useRouter } from "vue-router";

const router = useRouter();
const isArchived = ref(false);

const navigateToCreate = () => {
  router.push({ name: "CreateProduct" });
};

const updateIsArchived = (value) => {
  isArchived.value = value;
};

const editProduct = (product) => {
  router.push({ name: "EditProduct", params: { id: product.id } });
};
</script>
