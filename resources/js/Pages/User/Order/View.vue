<template>
  <div class="mx-auto w-full lg:w-[50%] px-4 2xl:px-0">
    <!-- Order Header -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <div class="flex items-center mb-4">
        <h2 class="text-2xl font-bold mr-5">Order Number #{{ order.id }}</h2>
        <div
          class="px-2 py-1 rounded-xl text-sm font-bold"
          :class="
            order.isPaid
              ? 'text-green-600 bg-green-50'
              : 'text-yellow-600 bg-yellow-50  '
          "
        >
          {{ capitalizedStatus }}
        </div>
      </div>
      <p class="mb-10">{{ formatDate(order.created_at) }}</p>

      <!-- Product List -->
      <h1 class="text-xl font-semibold mb-5">Order Item</h1>

      <div
        v-for="(item, index) in order.items"
        :key="item.id"
        class="px-0 lg:px-4 divide-y divide-gray-200 bg-slate-50"
      >
        <!-- Product Item -->
        <div
          class="flex py-4 justify-between items-center"
          :class="index === order.items.length - 1 ? '' : 'border-b-2 mb-2'"
        >
          <div
            class="grid grid-rows-3 grid-flow-col gap-1 justify-items-start items-center"
          >
            <img :src="item.product.image" class="w-20 row-span-3" alt="" />
            <p class="text-sm text-gray-500">Category</p>
            <h3 class="font-medium">{{ item.product.title }}</h3>
            <p class="text-sm text-gray-500">Variation</p>
          </div>
          <div class="flex items-center space-x-10 justify-end text-right">
            <div class="">
              <p class="font-medium">${{ item.product.price }}</p>
              <p class="text-sm text-gray-500">Qty: {{ item.quantity }}</p>
            </div>
            <button
              class="flex items-center justify-center hover:bg-gray-100 p-2 rounded-lg"
            >
              <TrashIcon
                class="h-5 w-5 text-red-600 hover:text-red-400"
                aria-hidden="true"
              />
            </button>
          </div>
        </div>
      </div>

      <!-- Order Notes and Summary -->
      <div class="mt-6">
        <div class="grid grid-cols-2 border-t pt-4 text-right">
          <p class="text-gray-500">Subtotal:</p>
          <p class="font-medium">${{ order.total_price }}</p>
          <p class="text-gray-500">Shipping:</p>
          <p class="font-medium">$0.00</p>
          <p class="text-gray-500">Tax:</p>
          <p class="font-medium">$0.00</p>
          <p class="text-lg font-bold">Total:</p>
          <p class="text-lg font-bold">${{ order.total_price }}</p>
        </div>
      </div>
      <PrimaryButton
        v-if="!order.isPaid"
        class="flex items-center justify-center mt-6 w-full"
        @click="payOrder(order)"
        ><CreditCardIcon class="mr-2 h-5 w-5" aria-hidden="true" /> Pay
        Now</PrimaryButton
      >
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import UserLayout from "../Layouts/UserLayout.vue";
import { useFormatDate } from "@/utils/utils";
import { TrashIcon, CreditCardIcon } from "@heroicons/vue/24/outline";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { router } from "@inertiajs/vue3";

defineOptions({ layout: UserLayout });

const formatDate = (date) => useFormatDate(date);

const order = computed(() => usePage().props.order);

const capitalizedStatus = computed(() => {
  return (
    order.value.status.charAt(0).toUpperCase() + order.value.status.slice(1)
  );
});

const payOrder = (id) => {
  router.post(route("checkout.order", id));
};
</script>
