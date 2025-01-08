<template>
  <div v-if="loading" class="flex justify-center items-center h-screen">
    <Spinner />
  </div>
  <div v-else class="p-6 min-h-screen transition-all flex flex-col">
    <div class="flex justify-between items-center mb-6">
      <div class="flex flex-col">
        <h1 class="text-2xl font-bold mb-6">Order Details</h1>
        <p class="mb-5">Order # {{ order.id }}</p>
        <OrderStatus
          class="w-[80%]"
          v-if="order.status"
          :order="order.status"
        />
      </div>
    </div>

    <div class="flex gap-6 mb-6 mt-6 w-full justify-between">
      <div class="flex flex-col flex-1 gap-6">
        <!-- Items Section -->
        <h2 class="text-lg font-semibold mb-3">Items</h2>

        <div class="bg-white rounded">
          <div
            v-for="item in order.items"
            :key="item.id"
            class="flex justify-between items-center mb-4 border rounded-lg p-6"
          >
            <div class="flex items-center">
              <img
                :src="item.product.image"
                :alt="item.product.title"
                class="w-20 h-20 rounded mr-4"
              />
              <div class="flex flex-col">
                <p class="font-medium">{{ item.product.title }}</p>
                <p class="text-sm text-gray-500">Color: Default</p>
              </div>
            </div>
            <div class="flex items-center gap-10">
              <p class="text-sm text-gray-500">
                Quantity: {{ item.quantity }} Item(s)
              </p>
              <p class="text-sm">
                <strong>$ {{ item.unit_price }}</strong>
              </p>
            </div>
          </div>
        </div>

        <!-- Order Summary Section -->
        <Accordion title="Order Summary">
          <div class="text-sm space-y-2">
            <div class="flex justify-between">
              <span>Subtotal</span><span>$ {{ order.subtotal }}</span>
            </div>
            <div class="flex justify-between">
              <span>Shipping Charge</span><span>$ 0.00</span>
            </div>
            <div class="flex justify-between">
              <span>Taxes</span><span>$ 0.00</span>
            </div>
            <div class="flex justify-between">
              <span>Discount</span><span>$ 0.00</span>
            </div>
            <hr class="my-2" />
            <div class="flex justify-between font-bold text-lg">
              <span>Total</span><span>$ {{ order.subtotal }}</span>
            </div>
          </div>
        </Accordion>
      </div>

      <div class="flex flex-col w-1/3 gap-6 mt-16">
        <!-- Order Info Section -->
        <Accordion title="Order Info">
          <p class="flex items-center text-sm mb-3 text-gray-700">
            <CalendarDaysIcon
              class="flex items-center justify-center h-6 w-6 mr-2"
            />
            {{ order.updated_at }}
          </p>
          <p class="flex items-center text-sm mb-3">
            <CreditCardIcon
              class="flex items-center justify-center h-6 w-6 mr-2"
            />
            <select
              v-model="order.status"
              @change="onStatusChange(order, orderStatus)"
              class="flex appearance-none relative w-32 px-2 py-1 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
            >
              <!-- <option v-for="status in orderStatuses" value="unpaid">Unpaid</option> -->
              <option
                v-for="(status, index) in orderStatuses"
                :key="index"
                :value="status"
              >
                {{ capitalizedStatus(status) }}
              </option>
            </select>
          </p>
        </Accordion>
        <!-- Customer Info Section -->
        <Accordion title="Customer">
          <p class="flex items-center text-sm mb-3 text-gray-700">
            <UserIcon class="flex items-center justify-center h-4 w-4 mr-2" />
            <span
              >{{ capitalizedStatus(order.customer?.first_name) }}
              {{ capitalizedStatus(order.customer?.last_name) }}</span
            >
          </p>
          <p class="flex items-center text- mb-3 text-gray-700">
            <EnvelopeIcon
              class="flex items-center justify-center h-4 w-4 mr-2"
            /><span>{{ order.customer?.email }}</span>
          </p>
          <p class="flex items-center text-sm mb-3 text-gray-700">
            <PhoneIcon
              class="flex items-center justify-center h-4 w-4 mr-2"
            /><span>{{ order.customer?.phone }}</span>
          </p>
        </Accordion>

        <!-- Shipping Address Section -->
        <Accordion title="Shipping Address">
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>Address 1 &ensp; : &ensp; </strong
            ><span> {{ order.customer?.shipping_address.address1 }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>Address 2 &ensp; : &ensp; </strong
            ><span> {{ order.customer?.shipping_address.address2 }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>City &ensp; : &ensp; </strong
            ><span> {{ order.customer?.shipping_address.city }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>Country &ensp; : &ensp; </strong
            ><span> {{ order.customer?.shipping_address.country }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>State &ensp; : &ensp; </strong
            ><span> {{ order.customer?.shipping_address.state }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>Zip Code &ensp; : &ensp; </strong
            ><span> {{ order.customer?.shipping_address.zipcode }}</span>
          </p>
        </Accordion>
        <!-- Billing Address Section -->
        <Accordion title="Billing Address">
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>Address 1 &ensp; : &ensp; </strong
            ><span> {{ order.customer?.billing_address.address1 }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>Address 2 &ensp; : &ensp; </strong
            ><span> {{ order.customer?.billing_address.address2 }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>City &ensp; : &ensp; </strong
            ><span> {{ order.customer?.billing_address.city }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>Country &ensp; : &ensp; </strong
            ><span> {{ order.customer?.billing_address.country }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>State &ensp; : &ensp; </strong
            ><span> {{ order.customer?.billing_address.state }}</span>
          </p>
          <p class="flex items-center text-gray-700 text-sm mb-3">
            <strong>Zip Code &ensp; : &ensp; </strong
            ><span> {{ order.customer?.billing_address.zipcode }}</span>
          </p>
        </Accordion>
      </div>
    </div>
  </div>
</template>

<script setup>
import store from "../../store";
import { onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
import { useCapitalizedStatus } from "../../utils/utils";
import Spinner from "../../components/core/Spinner.vue";
import {
  UserIcon,
  EnvelopeIcon,
  PhoneIcon,
  CalendarDaysIcon,
  CreditCardIcon,
} from "@heroicons/vue/24/outline";
import Accordion from "../../components/core/Accordion.vue";
import axiosClient from "../../axios";
import OrderStatus from "./OrderStatus.vue";
const route = useRoute();

const capitalizedStatus = (status) => {
  return status ? useCapitalizedStatus(status) : "";
};

const order = ref({});
const loading = ref(false);
const orderStatuses = ref([]);
const orderStatus = ref("");

onMounted(() => {
  showOrderDetails();
  axiosClient.get("/orders/statuses").then(({ data }) => {
    orderStatuses.value = data;
  });
});

const showOrderDetails = () => {
  loading.value = true;
  store.dispatch("getOrder", route.params.id).then((response) => {
    order.value = response.data;
    loading.value = false;
  });
};

const onStatusChange = () => {
  axiosClient.put(
    `/orders/change-status/${order.value.id}/${order.value.status}`
  );
};
// const onStatusChange = (order, status) => {
//   store
//     .dispatch("updateOrderStatus", { id: order.id, status })
//     .then(({ data }) => {
//       order.value = data;
//     });
// };
</script>
