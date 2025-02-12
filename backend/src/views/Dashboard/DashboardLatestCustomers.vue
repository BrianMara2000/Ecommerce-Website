<template>
  <div class="bg-white flex flex-col shadow-md rounded-lg p-6 space-x-4">
    <h2 class="font-bold mb-5 text-center">Latest Customers</h2>
    <div
      v-for="customer in customers"
      :key="customer.id"
      class="flex items-center mb-6"
    >
      <img
        src="https://randomuser.me/api/portraits/men/78.jpg"
        alt="User Profile"
        class="rounded-full w-14 mr-5"
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
