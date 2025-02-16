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

  <ProductModal
    :isArchived="isArchived"
    v-model="showModal"
    :product="productModel"
    @close="onModalClose"
  />
  <ProductsTable
    @clickEdit="editProduct"
    :isArchived="isArchived"
    @update-is-archived="updateIsArchived"
  />
</template>

<script setup>
import { ref } from "vue";
import ProductsTable from "./ProductsTable.vue";
import ProductModal from "./ProductModal.vue";
import store from "../../store";

const DEFAULT_EMPTY_OBJECT = {
  id: "",
  titie: "",
  image: "",
  description: "",
  price: "",
  status: "",
};

const showModal = ref(false);
const isArchived = ref(false);
const productModel = ref({ ...DEFAULT_EMPTY_OBJECT });

function showProductModal() {
  showModal.value = true;
}

const updateIsArchived = (value) => {
  isArchived.value = value;
};

function editProduct(product) {
  store.dispatch("getProduct", product.id).then(({ data }) => {
    productModel.value = data;
    showProductModal();
  });
}

function onModalClose() {
  productModel.value = { ...DEFAULT_EMPTY_OBJECT };
}
</script>
