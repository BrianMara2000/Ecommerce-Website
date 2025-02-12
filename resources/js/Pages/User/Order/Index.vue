<template>
  <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
    <h1 class="text-2xl font-bold mb-4">My orders</h1>

    <!-- Tabs -->
    <div class="flex items-center space-x-4 border-b mb-6">
      <button
        class="py-2 border-b-2 border-purple-500 text-purple-500 font-semibold"
      >
        Active
      </button>
      <button class="py-2 text-gray-500 hover:text-gray-700">Archive</button>
      <button class="py-2 text-gray-500 hover:text-gray-700">Relevance</button>
    </div>

    <!-- Table -->
    <div class="">
      <table class="min-w-full border-collapse border-spacing-0">
        <thead class="text-left border-b">
          <tr class="bg-gray-50">
            <th class="p-4">Order #</th>
            <th class="p-4">Date</th>
            <th class="p-4">SubTotal</th>
            <th class="p-4">Items</th>
            <th class="p-4">Status</th>
            <th class="p-4 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="order in orders.data"
            :key="order.id"
            class="border-b hover:hover:bg-purple-100"
          >
            <td class="p-4 text-purple-600">
              <Link :href="route('my-order.view', order)">
                {{ order.id }}
              </Link>
            </td>
            <td class="p-4">
              {{ formatDate(order.created_at) }}
            </td>
            <td class="p-4">$ {{ order.total_price }}</td>
            <td class="p-4">{{ getOrderQuantity(order.items) }} item(s)</td>
            <td class="p-4">
              <div
                class="w-[80%] text-sm rounded-md px-1 py-2 flex items-center justify-center"
                :class="{
                  'bg-emerald-500 text-white': order.status === 'paid',
                  'bg-yellow-500 text-white': order.status === 'unpaid',
                  'bg-gray-500 text-white': order.status === 'shipped',
                  'bg-purple-500 text-white': order.status === 'completed',
                  'bg-red-500 text-white': order.status === 'cancelled',
                }"
              >
                {{ capitalizedStatus(order.status) }}
              </div>
            </td>
            <td class="p-4 flex justify-center">
              <Menu as="div" class="relative inline-block text-left">
                <div>
                  <MenuButton
                    class="inline-flex items-center justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium text-white hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                  >
                    <EllipsisVerticalIcon
                      class="h-5 w-5 text-indigo-500"
                      aria-hidden="true"
                    />
                  </MenuButton>
                </div>
                <transition
                  enter-active-class="transition duration-100 ease-out"
                  enter-from-class="transform scale-95 opacity-0"
                  enter-to-class="transform scale-100 opacity-100"
                  leave-active-class="transition duration-75 ease-in"
                  leave-from-class="transform scale-100 opacity-100"
                  leave-to-class="transform scale-95 opacity-0"
                >
                  <MenuItems
                    class="absolute z-10 right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                  >
                    <div class="px-1 py-1">
                      <MenuItem v-slot="{ active }">
                        <button
                          :class="[
                            active
                              ? 'bg-violet-500 text-white'
                              : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                          ]"
                        >
                          <DocumentIcon
                            :active="active"
                            class="mr-2 h-5 w-5 text-violet-400"
                            aria-hidden="true"
                          />
                          View Invoice
                        </button>
                      </MenuItem>
                    </div>

                    <div v-if="order.status === 'unpaid'" class="px-1 py-1">
                      <MenuItem v-slot="{ active }">
                        <button
                          :class="[
                            active
                              ? 'bg-violet-500 text-white'
                              : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                          ]"
                          @click="payOrder(order)"
                        >
                          <CreditCardIcon
                            :active="active"
                            class="mr-2 h-5 w-5 text-violet-400"
                            aria-hidden="true"
                          />
                          Pay
                        </button>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </td>
          </tr>
        </tbody>
      </table>
      <div
        :class="!orders.data.length ? 'justify-center' : 'justify-between'"
        class="flex items-center mt-5"
      >
        <span class="text-center" v-if="!orders.data.length"
          >No orders found</span
        >
        <span v-else> Showing from {{ orders.from }} to {{ orders.to }} </span>
        <nav
          v-if="orders.total > orders.per_page"
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <a
            v-for="(link, i) of orders.links"
            :key="i"
            :disabled="!link.url"
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
  </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import UserLayout from "../Layouts/UserLayout.vue";
import { computed } from "vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
  EllipsisVerticalIcon,
  DocumentIcon,
  CreditCardIcon,
} from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";
import { useFormatDate, useCapitalizedStatus } from "@/utils/utils";

defineOptions({ layout: UserLayout });

const orders = computed(() => usePage().props.orders);

const formatDate = (date) => useFormatDate(date);
const capitalizedStatus = (stats) => useCapitalizedStatus(stats);

const payOrder = (id) => {
  router.post(route("checkout.order", id));
};

const getOrderQuantity = (items) => {
  return items.reduce((total, item) => total + item.quantity, 0);
};

const getOrders = () => {
  router.get(route("my-orders"));
};

const getForPage = (e, link) => {
  if (!link.url || link.active) {
    return;
  }
  router.get(link.url);
};

// console.log(orders.links.length);
</script>
