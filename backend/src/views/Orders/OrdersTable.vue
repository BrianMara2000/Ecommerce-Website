<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
    <div class="flex justify-between border-b-2 pb-3">
      <div class="flex items-center">
        <span class="whitespace-nowrap mr-3">Per page</span>
        <select
          @change="getOrders(null)"
          v-model="perPage"
          class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
        >
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="ml-3">Found {{ orders.total }} orders</span>
      </div>
      <div>
        <input
          type="text"
          v-model="search"
          @change="getOrders(null)"
          class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
          placeholder="Type to search orders"
        />
      </div>
    </div>

    <table class="table-auto w-full">
      <thead>
        <tr class="animate-fade-in-down text-center">
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left w-[8%]"
            field="id"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Order #</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left w-[15%]"
            field="customer"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Customer</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left w-[20%]"
            field="created_at"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Date</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left"
            field="subtotal"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >SubTotal</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left"
            field="number_of_items"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Items</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left w-[10%]"
            field="status"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Status</TableHeaderCell
          >
          <TableHeaderCell field="actions" class="text-center w-[20%]"
            >Actions</TableHeaderCell
          >
        </tr>
      </thead>
      <tbody v-if="orders.loading">
        <tr>
          <td colspan="7">
            <Spinner v-if="orders.loading" class="mt-3" />
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr
          v-for="(order, index) of orders.data"
          class="hover:hover:bg-purple-100 transition-all"
        >
          <!-- class="animate-fade-in-down"
          :style="{ 'animation-delay': `${index * 0.05}s`, 'z-index': '0' }" -->
          <td class="border-b pl-6 p-2">{{ order.id }}</td>
          <td class="border-b p-2">
            {{ order.customer.first_name + " " + order.customer.last_name }}
          </td>
          <td class="border-b p-2">{{ formatDate(order.created_at) }}</td>
          <td class="border-b p-2">${{ order.subtotal }}</td>
          <td class="border-b p-2">{{ order.number_of_items }} Item(s)</td>
          <td class="border-b p-2">
            <OrderStatus :order="order.status" />
          </td>
          <td class="border-b p-2 flex items-center justify-center">
            <router-link
              :to="{ name: 'app.orders.view', params: { id: order.id } }"
              class="flex items-center w-[60%] justify-center group font-semibold hover:bg-violet-500 hover:text-white rounded-md px-2 py-2 text-sm transition-all"
            >
              <EyeIcon
                class="mr-2 h-5 w-5 text-violet-400 group-hover:text-white"
                aria-hidden="true"
              />
              View Order
            </router-link>
          </td>
        </tr>
      </tbody>
    </table>
    <div
      v-if="!orders.loading"
      :class="!orders.data.length ? 'justify-center' : 'justify-between'"
      class="flex items-center mt-5"
    >
      <span class="text-center" v-if="!orders.data.length"
        >No orders found</span
      >
      <span v-else> Showing from {{ orders.from }} to {{ orders.to }} </span>
      <nav
        v-if="orders.total > orders.limit"
        class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
        aria-label="Pagination"
      >
        <a
          v-for="(link, i) of orders.links"
          :key="i"
          :disabled="!link.url"
          href="#"
          @click.prevent="getForPage($event, link)"
          aria-current="page"
          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
          :class="[
            link.active
              ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
            i === 0
              ? !orders.prev_page_url
                ? ' cursor-not-allowed'
                : 'rounded-r-md '
              : '',
            ,
            i === orders.links.length - 1
              ? !orders.next_page_url
                ? ' cursor-not-allowed'
                : 'rounded-r-md '
              : '',
            !link.url ? ' ' : 'bg-gray-100 text-gray-700 cursor-pointer',
          ]"
          v-html="link.label"
        ></a>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store";
import { PRODUCTS_PER_PAGE } from "../../constants";
import TableHeaderCell from "../../components/core/table/TableHeaderCell.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import { useCapitalizedStatus } from "../../utils/utils";
import OrderStatus from "./OrderStatus.vue";

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref("");
const orders = computed(() => store.state.orders);

const sortField = ref("updated_at");
const sortDirection = ref("desc");

const formatDate = (isoDate) => {
  const date = new Date(isoDate);
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return date.toLocaleString(undefined, options); // Adjusts to the user's locale
};

onMounted(() => {
  getOrders();
});

const getOrders = (url = null) => {
  store.dispatch("getOrders", {
    url,
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
    search: search.value,
    perPage: perPage.value,
  });
};

const getForPage = (e, link) => {
  if (!link.url || link.active) {
    return;
  }
  getOrders(link.url);
};

const sortProducts = (field) => {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === "desc" ? "asc" : "desc";
  } else {
    sortField.value = field;
    sortDirection.value = "desc";
  }

  getOrders();
};
</script>
