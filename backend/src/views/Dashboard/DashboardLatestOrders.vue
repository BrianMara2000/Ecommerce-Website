<template>
  <div
    class="bg-white flex flex-col col-span-2 shadow-md rounded-lg p-6 space-x-4"
  >
    <h2 class="font-bold mb-5 text-center">Latest Orders</h2>

    <div
      class="bg-white p-4 rounded-lg shadow animate-fade-in-down overflow-auto max-h-96"
    >
      <table class="table-auto w-full">
        <thead>
          <tr class="animate-fade-in-down text-center">
            <TableHeaderCell class="border-b-2 p-2 text-left" field="id"
              >Order #</TableHeaderCell
            >
            <TableHeaderCell class="border-b-2 p-2 text-left" field="name"
              >Customer</TableHeaderCell
            >
            <TableHeaderCell
              class="border-b-2 p-2 text-left"
              field="total_price"
              >SubTotal</TableHeaderCell
            >
            <TableHeaderCell
              class="border-b-2 p-2 text-left"
              field="number_of_items"
              >Items</TableHeaderCell
            >
            <TableHeaderCell class="border-b-2 p-2 text-left" field="status"
              >Order placed</TableHeaderCell
            >
          </tr>
        </thead>
        <tbody v-if="isLoading">
          <tr>
            <td colspan="7">
              <Spinner v-if="isLoading" class="mt-3" />
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr
            v-for="order in orders"
            class="hover:hover:bg-purple-100 transition-all"
          >
            <td class="border-b pl-6 p-4 text-purple-600">
              <router-link
                :to="{ name: 'app.orders.view', params: { id: order.id } }"
                >{{ order.id }}</router-link
              >
            </td>
            <td class="border-b p-4">
              {{ order.name }}
            </td>
            <td class="border-b p-4">${{ order.total_price }}</td>
            <td class="border-b p-4">{{ order.total_quantity }} Item(s)</td>
            <td class="border-b p-4">{{ order.order_date }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axiosClient from "../../axios";
import TableHeaderCell from "../../components/core/table/TableHeaderCell.vue";
import Spinner from "../../components/core/Spinner.vue";

const orders = ref([]);
const isLoading = ref(false);

const fetchLatestOrders = () => {
  isLoading.value = true;
  axiosClient
    .get("dashboard/latest-orders")
    .then((response) => {
      orders.value = response.data;
    })
    .catch((error) => {
      console.error("Error fetching revenue data:", error);
    })
    .finally(() => {
      isLoading.value = false;
    });
};

onMounted(fetchLatestOrders);
</script>
