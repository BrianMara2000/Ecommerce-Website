<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Customers</h1>
  </div>

  <CustomerModal
    v-model="showModal"
    :customer="customerModel"
    :countries="countries"
    @close="onModalClose"
  />

  <CustomersTable @clickEdit="editCustomer" />
</template>

<script setup>
import { provide, ref } from "vue";
import CustomerModal from "./CustomerModal.vue";
import CustomersTable from "./CustomersTable.vue";
import store from "../../store";

const DEFAULT_EMPTY_OBJECT = {};

const showModal = ref(false);
const customerModel = ref({ ...DEFAULT_EMPTY_OBJECT });
const countries = ref([]);

provide("countries", countries);

const showCustomerModal = () => {
  showModal.value = true;
};

const onModalClose = () => {
  customerModel.value = { ...DEFAULT_EMPTY_OBJECT };
};

const editCustomer = (customer) => {
  store.dispatch("getCustomer", customer.id).then(({ data }) => {
    customerModel.value = data.customer;
    countries.value = data.countries;
    showCustomerModal();
  });
};
</script>
