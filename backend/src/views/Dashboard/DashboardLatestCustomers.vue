<template>
  <div class="bg-white flex flex-col shadow-md rounded-lg p-6 space-x-4">
    <h2 class="font-bold mb-5 text-center">Latest Customers</h2>
    <div
      v-if="isLoading"
      v-for="index in 5"
      :key="'loading-' + index"
      :loading="true"
      class="flex items-center mb-6"
    >
      <div class="rounded-full w-12 h-12 mr-5 bg-gray-300 animate-pulse"></div>
      <div class="flex flex-col space-y-2 flex-1">
        <h2 class="h-5 w-20 bg-gray-300 rounded animate-pulse"></h2>
        <p class="h-3 w-28 bg-gray-200 rounded animate-pulse"></p>
      </div>
    </div>
    <div
      v-for="customer in customers"
      :key="customer.id"
      class="flex items-center mb-6"
    >
      <img
        src="https://randomuser.me/api/portraits/men/78.jpg"
        alt="User Profile"
        class="rounded-full w-12 mr-5"
      />
      <div>
        <h2 class="font-semibold">
          {{ capitalized(customer.first_name) }}
          {{ capitalized(customer.last_name) }}
        </h2>
        <p class="text-gray-500">{{ customer.email }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axiosClient from "../../axios";
import { useCapitalizedFirstLetter } from "../../utils/utils";

const capitalized = (letter) => useCapitalizedFirstLetter(letter);

const isLoading = ref(false);
const customers = ref([]);

const fetchLatestCustomers = () => {
  isLoading.value = true;

  axiosClient
    .get("dashboard/latest-customers")
    .then((response) => {
      customers.value = response.data;
    })
    .catch((error) => {
      console.error("Error fetching revenue data:", error);
    })
    .finally(() => {
      isLoading.value = false;
    });
};

onMounted(fetchLatestCustomers);
</script>
